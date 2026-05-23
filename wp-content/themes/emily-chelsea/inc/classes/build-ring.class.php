<?php
/**
 * Build-a-Ring feature — session state, business logic, and AJAX handlers.
 *
 * Organises the custom ring builder flow (Setting → Stone → Confirm or Stone → Setting → Confirm)
 * and keeps a "tray" (in-progress design) plus a "collections" map (completed ring+stone pairs)
 * in the PHP session, mirrored into the WooCommerce cart for pricing.
 *
 * @package TTG\Build_Ring
 */

namespace TTG\Build_Ring;

use TTG_Template;

define("BUILD_RING_SESSION_KEY", "BUILD_RING_SESSION");

/**
 * WooCommerce product-category slugs used to identify ring settings and center stones.
 */
class PRODUCT_TYPES
{
    const RING  = "rings";
    const STONE = "center-stones";
}

/**
 * Builder flow modes / step identifiers.
 *
 * START_WITH_SETTING — user picks the ring setting first.
 * START_WITH_STONE   — user picks the center stone first.
 * CONFIRM            — both items chosen; user reviews and confirms.
 */
class MODE
{
    const START_WITH_SETTING = "START_WITH_SETTING";
    const START_WITH_STONE   = "START_WITH_STONE";
    const CONFIRM            = "CONFIRM";
}

/**
 * Thin wrapper around $_SESSION for the Build-a-Ring namespace.
 *
 * All data is stored under BUILD_RING_SESSION_KEY to avoid collisions.
 */
class Model
{
    /**
     * Starts a PHP session if one is not already active.
     */
    public static function start_session()
    {
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * Returns the full session bag or a single key from it.
     *
     * @param  string $key Optional key to retrieve. Empty = return entire bag.
     * @return mixed        Array when no key given; scalar/null for a specific key.
     */
    public static function get_session($key = '')
    {
        self::start_session();
        $data = $_SESSION[BUILD_RING_SESSION_KEY] ?? [];

        if (empty($key)) {
            return $data;
        }

        return $data[$key] ?? null;
    }

    /**
     * Writes a value into the session bag and returns the updated bag.
     *
     * @param  string $key  Key to write.
     * @param  mixed  $data Value to store.
     * @return array        The updated session bag.
     */
    public static function set_session($key, $data)
    {
        self::start_session();
        $_SESSION[BUILD_RING_SESSION_KEY][$key] = $data;

        return $_SESSION[BUILD_RING_SESSION_KEY];
    }

    /**
     * Clears a single key (sets it to null) or the entire bag ('ALL').
     *
     * @param  string $key Key to clear, or 'ALL' to wipe the entire bag.
     * @return array       The session bag after clearing.
     */
    public static function clear_session($key = '')
    {
        self::start_session();

        if (!empty($key)) {
            $_SESSION[BUILD_RING_SESSION_KEY][$key] = null;
        }

        if ($key == 'ALL') {
            $_SESSION[BUILD_RING_SESSION_KEY] = [];
        }



        return $_SESSION[BUILD_RING_SESSION_KEY];
    }
}

/**
 * Business-logic layer for the Build-a-Ring flow.
 *
 * Manages the in-progress "tray" (one ring + one stone being assembled),
 * the "collections" map (uuid → completed ring+stone pair), step navigation,
 * and WooCommerce cart synchronisation.
 */
class Controller
{
    /**
     * Adds or replaces an item in the tray and syncs with the WC cart.
     *
     * If an item of the same type already exists in the tray, its existing
     * WC cart line is removed before the new product is added.
     *
     * @param  array $newItem {
     *     @type string $type         PRODUCT_TYPES constant.
     *     @type int    $product_id   WooCommerce product ID.
     *     @type int    $variation_id Variation ID (0 for simple products).
     *     @type array  $attrs        Variation attribute key/value pairs.
     * }
     * @return array Updated tray stored in session.
     */
    public static function add_item_to_tray($newItem = [])
    {
        $tray = self::get_tray();
        $order = count($tray);
        $type = $newItem["type"] ?? "";
        $item = ['uuid' => uniqid(), 'order' => $order];

        if (!empty($tray)) {
            foreach ($tray as $key => $value) {
                if ($value['type'] === $type) {
                    $item = $value;
                }
            }
        }

        $cart_line_item = $item["cart_line_item"] ?? '';

        if (!empty($cart_line_item)) {
            WC()->cart->remove_cart_item($cart_line_item);
        }

        $productId = $newItem["product_id"] ?? 0;
        $variation_id = $newItem["variation_id"] ?? 0;
        $attrs = $newItem["attrs"] ?? [];

        $cart_line_item = WC()->cart->add_to_cart($productId, 1, $variation_id, $attrs, [
            'unique_key' => $item['uuid'],
        ]);

        $item = array_merge($item, $newItem);
        $item["cart_line_item"] = $cart_line_item;

        $uuid = $item["uuid"];
        $tray[$uuid] = $item;

        return Model::set_session("tray", $tray);
    }

    /**
     * Finds the tray item whose cart_line_item key matches the given value.
     *
     * @param  string $cart_line_item WC cart line item key.
     * @return array                  Matching tray item, or [] if not found.
     */
    public static function get_tray_item_by_cart_line_item($cart_line_item = '')
    {
        $tray = self::get_tray();
        $tray_item = [];
        if (!empty($tray)) {
            foreach ($tray as $item) {
                if (($item['cart_line_item'] ?? '') === $cart_line_item) {
                    $tray_item = $item;
                }
            }
        }

        return $tray_item;
    }

    /**
     * Returns the current tray (keyed by uuid).
     *
     * @return array Tray items, or [] if the tray is empty.
     */
    public static function get_tray()
    {
        return Model::get_session("tray") ?? [];
    }

    /**
     * Removes a tray item by uuid and removes its WC cart line.
     *
     * @param  string $id UUID of the tray item to remove.
     * @return array      Updated tray.
     */
    public static function remove_item_from_tray($id = '')
    {
        $tray = self::get_tray();
        if (empty($id)) return $tray;

        $item = $tray[$id] ?? [];

        if (!empty($item)) {
            $cart_line_item = $item["cart_line_item"] ?? '';
            if (!empty($cart_line_item)) {
                WC()->cart->remove_cart_item($cart_line_item);
            }
        }

        unset($tray[$id]);

        return Model::set_session("tray", $tray);
    }

    /**
     * Converts a tray array into a flat collection record suitable for storage in "collections".
     *
     * @param  array $tray Tray items array.
     * @return array       Collection with keys: ring, stone, variation_id, attrs,
     *                     ring_cart_item_line, stone_cart_item_line.
     */
    public static function parse_tray_to_collection($tray = [])
    {
        $collection = [];
        $ring = self::get_ring_from_tray($tray);
        $stone = self::get_stone_from_tray($tray);

        $collection['ring_cart_item_line'] = $ring["cart_line_item"] ?? '';
        $collection['ring'] =  $ring["product_id"] ?? 0;
        $collection['attrs'] = $ring['attrs'];
        $collection['variation_id'] = $ring['variation_id'];

        $collection['stone_cart_item_line'] = $stone["cart_line_item"] ?? '';
        $collection['stone'] = $stone["product_id"] ?? 0;

        return $collection;
    }

    /**
     * Wipes the tray from the session (does NOT remove WC cart items).
     *
     * @return array The (now-empty) session bag.
     */
    public static function clear_tray()
    {
        return Model::clear_session("tray");
    }

    /**
     * Extracts the stone item from a tray array.
     *
     * @param  array $tray Tray items array.
     * @return array       Stone tray item, or [] if none.
     */
    public static function get_stone_from_tray($tray = [])
    {
        $stone = [];
        if (!empty($tray)) {
            foreach ($tray as $item) {
                if ($item['type'] === PRODUCT_TYPES::STONE) {
                    $stone = $item;
                }
            }
        }

        return $stone;
    }

    /**
     * Extracts the ring setting item from a tray array.
     *
     * @param  array $tray Tray items array.
     * @return array       Ring tray item, or [] if none.
     */
    public static function get_ring_from_tray($tray = [])
    {
        $ring = [];
        if (!empty($tray)) {
            foreach ($tray as $item) {
                if ($item['type'] === PRODUCT_TYPES::RING) {
                    $ring = $item;
                }
            }
        }

        return $ring;
    }

    /**
     * Returns the ordered step sequence for the given starting mode.
     *
     * START_WITH_SETTING → [START_WITH_SETTING, START_WITH_STONE, CONFIRM]
     * START_WITH_STONE   → [START_WITH_STONE,   START_WITH_SETTING, CONFIRM]
     *
     * @param  string $initStep MODE constant for the first step chosen.
     * @return string[]          Ordered array of MODE constants, or [] for unknown modes.
     */
    public static function get_steps($initStep = MODE::START_WITH_SETTING)
    {
        $steps = [
            MODE::START_WITH_SETTING => [MODE::START_WITH_SETTING, MODE::START_WITH_STONE, MODE::CONFIRM],
            MODE::START_WITH_STONE => [MODE::START_WITH_STONE, MODE::START_WITH_SETTING, MODE::CONFIRM],
        ];

        return $steps[$initStep] ?? [];
    }

    /**
     * Returns true when the user is inside the build-ring flow.
     *
     * True when: an active mode is set AND (on a product page) the product belongs to the
     * rings or center-stones category AND the ?mode query arg is present. Always true on the
     * page-build-ring.php template regardless of session state.
     *
     * @return bool
     */
    public static function is_building_ring()
    {
        $is_processing = false;

        if (is_singular('product')) {
            global $post;
            $url_mode      = isset($_GET['mode']) ? $_GET['mode'] : '';
            $is_has_terms  = has_term([PRODUCT_TYPES::RING, PRODUCT_TYPES::STONE], 'product_cat', $post);
            $is_processing = !empty($url_mode) && !empty(self::get_mode()) && $is_has_terms;
        }

        return $is_processing || is_page_template('page-build-ring.php');
    }

    /**
     * Sets a session UUID for the current tray if one does not already exist.
     *
     * @return string The current (or newly created) UUID.
     */
    public static function set_uuid()
    {
        $current_uuid = self::get_uuid();
        if (!empty($current_uuid)) return $current_uuid;

        $uuid = uniqid();
        return Model::set_session("uuid", $uuid);
    }

    /** Clears the tray UUID from the session. */
    public static function clear_uuid()
    {
        return Model::clear_session("uuid");
    }

    /**
     * Returns the current tray UUID.
     *
     * @return string|null
     */
    public static function get_uuid()
    {
        return Model::get_session("uuid");
    }

    /**
     * Stores the initial mode (the flow the user started with).
     *
     * @param string $mode MODE constant.
     */
    public static function set_init_mode($mode)
    {
        return  Model::set_session("init_mode", $mode);
    }

    /**
     * Returns the initial mode stored at the start of the flow.
     *
     * @return string|null MODE constant, or null if not yet set.
     */
    public static function get_init_mode()
    {
        return Model::get_session("init_mode");
    }

    /**
     * Sets the current active mode; also sets init_mode if this is the first call.
     *
     * @param  string $mode MODE constant.
     * @return array        Updated session bag.
     */
    public static function set_mode($mode)
    {
        $init_mode = self::get_init_mode();
        if (empty($init_mode)) {
            self::set_init_mode($mode);
        }

        return  Model::set_session("mode", $mode);
    }

    /**
     * Returns the current active mode, or null when none is set.
     *
     * @return string|null MODE constant.
     */
    public static function get_mode()
    {
        $mode = Model::get_session("mode");
        return empty($mode) ? null : $mode;
    }

    /**
     * @param mixed $ring Ring product ID (kept for legacy callers; tray is the source of truth).
     */
    public static function set_ring($ring)
    {
        return Model::set_session("ring", $ring);
    }

    /**
     * Returns the product ID of the ring currently in the tray, or 0 if none.
     *
     * @return int
     */
    public static function get_ring()
    {
        $tray = self::get_tray();
        $ring = self::get_ring_from_tray($tray);
        $ring_id = $ring["product_id"] ?? 0;

        return $ring_id;
    }

    /**
     * @param mixed $stone Stone product ID (kept for legacy callers; tray is the source of truth).
     */
    public static function set_stone($stone)
    {
        return Model::set_session("stone", $stone);
    }

    /**
     * Returns the product ID of the stone currently in the tray, or 0 if none.
     *
     * @return int
     */
    public static function get_stone()
    {
        $tray = self::get_tray();
        $stone = self::get_stone_from_tray($tray);
        $stone_id = $stone["product_id"] ?? 0;

        return $stone_id;
    }

    /**
     * Returns the 1-based index of the current wizard step (defaults to 1).
     *
     * @return int
     */
    public static function get_active_step()
    {
        $step = Model::get_session("active_step");
        return empty($step) ? 1 : $step;
    }

    /**
     * @param int $step 1-based step index to persist.
     */
    public static function set_active_step($step = 1)
    {
        return Model::set_session("active_step", $step);
    }

    /**
     * Returns true when both a ring and a stone are selected in the tray.
     *
     * @return bool
     */
    public static function is_finish_design()
    {
        $ring = Controller::get_ring();
        $stone = Controller::get_stone();
        return $ring > 0 && $stone > 0 ? true : false;
    }

    /**
     * Returns true when neither a ring nor a stone has been added to the tray yet.
     *
     * @return bool
     */
    public static function is_start_design()
    {
        $ring = self::get_ring();
        $stone = self::get_stone();

        return empty($ring) && empty($stone) ? true : false;
    }

    /**
     * Returns all saved ring+stone collections keyed by their UUID.
     *
     * @return array<string, array>
     */
    public static function get_collections()
    {
        return Model::get_session("collections") ?? [];
    }

    /**
     * Persists the full collections map to session.
     *
     * @param  array<string, array> $collections UUID-keyed collection records.
     * @return array                              Updated session bag.
     */
    public static function set_collections($collections = [])
    {
        return Model::set_session("collections", $collections);
    }

    /**
     * Stores the variation attributes chosen by the user.
     *
     * @param  array $attrs Key/value pairs, e.g. ['attribute_pa_color' => 'gold'].
     * @return array        Updated session bag.
     */
    public static function set_product_attrs($attrs = [])
    {
        return Model::set_session('attrs', $attrs);
    }

    /**
     * Returns the stored variation attributes, or null.
     *
     * @return array|null
     */
    public static function get_attrs()
    {
        return Model::get_session('attrs');
    }

    /**
     * @param int $variation_id WooCommerce variation post ID.
     */
    public static function set_product_variation($variation_id)
    {
        return Model::set_session('variation', $variation_id);
    }

    /**
     * Returns the stored variation ID, or null.
     *
     * @return int|null
     */
    public static function get_product_variation()
    {
        return Model::get_session('variation');
    }

    /**
     * Removes a completed collection by UUID and removes its WC cart lines.
     *
     * @param  string $uuid Collection UUID.
     * @return array        The removed collection record (empty if not found).
     */
    public static function remove_design($uuid = '')
    {
        $collections = self::get_collections();
        $collection = empty($collections[$uuid]) ? [] : $collections[$uuid];

        if (!empty($collection['ring_cart_item_line'])) {
            WC()->cart->remove_cart_item($collection['ring_cart_item_line']);
        }

        if (!empty($collection['stone_cart_item_line'])) {
            WC()->cart->remove_cart_item($collection['stone_cart_item_line']);
        }

        unset($collections[$uuid]);
        self::set_collections($collections);

        return $collection;
    }

    /**
     * Returns the default stone ACF value for a ring product, falling back to the global option.
     *
     * @param  int $product_id Ring product ID.
     * @return mixed            Stone product ID from ACF, or 0/null if not configured.
     */
    public static function get_default_stone($product_id = 0)
    {
        if (empty($product_id)) return 0;

        $default_stone = get_field('default_stone', $product_id);
        $default_stone_global = get_field('default_stone', 'option');

        return empty($default_stone) ? $default_stone_global : $default_stone;
    }

    /**
     * Outputs a JSON response containing the combined price of a ring and stone.
     * Exits via wp_die() — intended as an AJAX handler body.
     *
     * @param int $product_id Ring product ID.
     * @param int $stone_id   Stone product ID; uses tray stone when 0.
     */
    public static function get_subtotal($product_id = 0, $stone_id = 0)
    {
        $stone_id = empty($stone_id) ? intval(self::get_stone()) : intval($stone_id);
        $product = wc_get_product(intval($product_id));
        $stone = wc_get_product(intval($stone_id));

        $product_price = empty($product) ? 0 : $product->get_price();
        $stone_price = empty($stone) ? 0 : $stone->get_price();

        echo wp_json_encode([
            "isSuccess" => true,
            "data" => wc_price($product_price + $stone_price),
        ]);
        wp_die();
    }

    /**
     * Validates that a product is in stock and purchasable.
     *
     * @param  int    $product_id Product or variation ID.
     * @return string             Error message, or '' when the product is valid.
     */
    public static function product_is_valid($product_id = 0)
    {
        $product = wc_get_product(intval($product_id));
        $message = '';

        if (!$product->is_in_stock() || !$product->is_purchasable() || !$product->has_enough_stock(1)) {
            $message = 'Product is available for purchase';
        }

        if ($product->managing_stock()) {
            $products_qty_in_cart = WC()->cart->get_cart_item_quantities();
            $stock_quantity         = $product->get_stock_quantity();
            $stock_quantity_in_cart = $products_qty_in_cart[$product->get_stock_managed_by_id()];
            if (isset($products_qty_in_cart[$product->get_stock_managed_by_id()]) && ! $product->has_enough_stock($products_qty_in_cart[$product->get_stock_managed_by_id()] + 1)) {
                $message = sprintf(__('You cannot add that amount to the cart. We have %1$s in stock and you already have %2$s in your cart.', 'woocommerce'), wc_format_stock_quantity_for_display($stock_quantity, $product), wc_format_stock_quantity_for_display($stock_quantity_in_cart, $product));
            }
        }

        return $message;
    }

    /**
     * Re-orders the WC cart array so ring+stone pairs appear together.
     *
     * Annotates each cart item with is_ring, is_stone, uuid, and is_finish_design
     * flags consumed by the cart template.
     *
     * @param  array $cart Raw WC cart items array.
     * @return array       Sorted and annotated cart items array.
     */
    public static function sort_cart($cart = [])
    {
        $collections = self::get_collections();
        $newCart = [];
        if (!empty($collections)) {
            foreach ($collections as $uuid => $collection) {
                $ring_cart_item_line = $collection['ring_cart_item_line'] ?? '';
                $stone_cart_item_line = $collection['stone_cart_item_line'] ?? '';
                $is_finish_design = !empty($ring_cart_item_line) && !empty($stone_cart_item_line) ? true : false;

                if (isset($cart[$ring_cart_item_line])) {
                    $newCart[$ring_cart_item_line] = $cart[$ring_cart_item_line];
                    $newCart[$ring_cart_item_line]['is_ring'] = true;
                    $newCart[$ring_cart_item_line]['uuid'] = $uuid;
                    $newCart[$ring_cart_item_line]['is_finish_design'] = $is_finish_design;
                    $cart[$ring_cart_item_line] = null;
                }

                if (isset($cart[$stone_cart_item_line])) {
                    $newCart[$stone_cart_item_line] = $cart[$stone_cart_item_line];
                    $newCart[$stone_cart_item_line]['is_stone'] = true;
                    $cart[$stone_cart_item_line] = null;
                    $newCart[$stone_cart_item_line]['is_finish_design'] = $is_finish_design;
                    $newCart[$stone_cart_item_line]['uuid'] = $uuid;
                }
            }

            foreach ($cart as $key => $item) {
                if (!empty($item)) {
                    $newCart[$key] = $item;
                }
            }

            return $newCart;
        }

        return $cart;
    }

    /**
     * @param array $cart_item_lines WC cart line item keys to persist.
     */
    public static function set_cart_item_lines($cart_item_lines = [])
    {
        return Model::set_session('cart_item_lines', $cart_item_lines);
    }

    /** @return array|null Stored cart item line keys. */
    public static function get_cart_item_lines()
    {
        return Model::get_session('cart_item_lines');
    }

    /**
     * Loads a saved collection back into the tray so the user can edit it.
     *
     * If another tray is in progress it is either saved (when complete) or its
     * WC cart items are removed (when incomplete) before the requested collection
     * is restored.
     *
     * @param  string $uuid The collection UUID to restore.
     * @param  string $mode The MODE step to land on after restoring.
     * @return bool         True on success, false if the UUID does not exist.
     */
    public static function restore_collection_to_tray($uuid, $mode)
    {
        $collections = self::get_collections();
        $collection  = $collections[$uuid] ?? [];

        if (empty($collection)) return false;

        // Handle existing in-progress tray before overwriting
        $existing_tray = self::get_tray();
        if (!empty($existing_tray)) {
            $existing_ring  = self::get_ring_from_tray($existing_tray);
            $existing_stone = self::get_stone_from_tray($existing_tray);

            if (!empty($existing_ring['product_id']) && !empty($existing_stone['product_id'])) {
                // Complete design — update its collection with current tray state
                $existing_uuid               = self::get_uuid() ?: uniqid();
                $updated_collections         = self::get_collections();
                $updated_collections[$existing_uuid] = self::parse_tray_to_collection($existing_tray);
                self::set_collections($updated_collections);
            } else {
                // Incomplete — remove from WC cart
                foreach ($existing_tray as $item) {
                    $cart_key = $item['cart_line_item'] ?? '';
                    if (!empty($cart_key)) {
                        WC()->cart->remove_cart_item($cart_key);
                    }
                }
            }
        }
        self::clear_uuid();

        $tray = [];

        if (!empty($collection['ring'])) {
            $ring_uuid        = uniqid();
            $tray[$ring_uuid] = [
                'uuid'           => $ring_uuid,
                'order'          => 0,
                'type'           => PRODUCT_TYPES::RING,
                'product_id'     => $collection['ring'],
                'variation_id'   => $collection['variation_id'] ?? 0,
                'attrs'          => $collection['attrs'] ?? [],
                'cart_line_item' => $collection['ring_cart_item_line'] ?? '',
            ];
        }

        if (!empty($collection['stone'])) {
            $stone_uuid        = uniqid();
            $tray[$stone_uuid] = [
                'uuid'           => $stone_uuid,
                'order'          => 1,
                'type'           => PRODUCT_TYPES::STONE,
                'product_id'     => $collection['stone'],
                'variation_id'   => 0,
                'attrs'          => [],
                'cart_line_item' => $collection['stone_cart_item_line'] ?? '',
            ];
        }

        // Restore tray + uuid (keep collection intact until user finishes editing)
        Model::set_session('tray', $tray);
        Model::set_session('uuid', $uuid);

        // Determine step from initMode, fallback to START_WITH_SETTING if missing
        $initMode = self::get_init_mode() ?: MODE::START_WITH_SETTING;
        $steps    = self::get_steps($initMode);
        $index    = array_search($mode, $steps);
        $step     = $index !== false ? $index + 1 : 1;

        self::set_mode($mode);
        self::set_active_step($step);

        return true;
    }

    /**
     * Clears the in-progress tray (ring, stone, attrs, variation, uuid).
     * Does not affect saved collections or page-level state.
     */
    public static function reset()
    {
        self::set_ring(0);
        self::set_stone(0);
        self::set_product_attrs([]);
        self::set_product_variation(0);
        self::clear_uuid();
        self::clear_tray();
    }

    /**
     * Resets the wizard page state (init_mode, mode, active_step) without touching the tray.
     */
    public static function reset_page_data()
    {
        self::set_init_mode('');
        self::set_mode('');
        self::set_active_step(1);
    }

    /**
     * Decrements the active step and updates mode accordingly.
     *
     * @return array {
     *     @type int    $step        New 1-based step number.
     *     @type string $mode        New MODE constant.
     *     @type bool   $isFirstStep True if the user is already on step 1.
     * }
     */
    public static function go_back_step()
    {
        $currentStep = self::get_active_step();

        if ($currentStep <= 1) {
            return ['step' => 1, 'mode' => self::get_mode(), 'isFirstStep' => true];
        }

        $prevStep = $currentStep - 1;
        $initMode = self::get_init_mode();
        $steps = self::get_steps($initMode);
        $mode = $steps[$prevStep - 1] ?? $initMode;

        self::set_active_step($prevStep);
        self::set_mode($mode);

        return ['step' => $prevStep, 'mode' => $mode, 'isFirstStep' => false];
    }

    /**
     * Assembles the full UI state payload sent to the front-end after most AJAX calls.
     *
     * @return array {
     *     @type string|null $mode             Current MODE constant.
     *     @type int         $step             Current 1-based step number.
     *     @type bool        $isSuccess        Always true.
     *     @type array       $collections      All saved ring+stone collections.
     *     @type array|null  $attrs            Current variation attributes.
     *     @type int|null    $variation_id     Current variation ID.
     *     @type string      $trayExtra        Rendered extra-tray HTML.
     *     @type string      $trayItems        Rendered tray-items HTML.
     *     @type string      $miniCollection   Rendered mini-collection HTML.
     * }
     */
    public static function get_data()
    {
        $mode = Controller::get_mode();
        $ring = Controller::get_ring();
        $stone = Controller::get_stone();
        $step = Controller::get_active_step();
        $collections = Controller::get_collections();

        $extra_html = $ring > 0 || $stone > 0 ? \TTG_Template::get_template_part('build-ring-tray-extra') : '';
        $tray_html = \TTG_Template::get_template_part('build-ring-tray-items');
        $mini_collection_html = \TTG_Template::get_template_part('build-ring-mini-collection');


        return [
            'mode' => $mode,
            'step' => $step,
            "isSuccess" => true,
            'collections' => $collections,
            'attrs' => Controller::get_attrs(),
            "variation_id" => Controller::get_product_variation(),
            'trayExtra' => $extra_html,
            'trayItems' => $tray_html,
            'miniCollection' => $mini_collection_html,
        ];
    }

    /**
     * Returns true when every saved collection has a variation_id selected.
     * A missing variation means the user left a ring design in a partially-configured state.
     *
     * @return bool
     */
    public static function is_valid_collections()
    {
        $collections = self::get_collections();
        $is_valid = true;

        if (!empty($collections)) {
            foreach ($collections as $value) {
                $variation_id = $value['variation_id'] ?? 0;

                if (!$variation_id) {
                    $is_valid = false;
                    return $is_valid;
                }
            }
        }

        return $is_valid;
    }
}


/**
 * WordPress AJAX handlers for the Build-a-Ring feature.
 *
 * Every public method maps 1-to-1 to a wp_ajax_* / wp_ajax_nopriv_* action registered
 * at the bottom of this file. Each handler reads its input from $_GET or $_POST,
 * calls Controller methods for business logic, echoes a JSON response, and calls wp_die().
 */
class Ajax
{
    /**
     * Flips the current mode between START_WITH_SETTING and START_WITH_STONE.
     * Updates init_mode when no items have been selected yet.
     * AJAX action: toggle_mode
     */
    public static function toggle_mode()
    {
        $mode = Controller::get_mode();
        $newMode = '';
        $newMode = $mode === MODE::START_WITH_SETTING ? MODE::START_WITH_STONE : MODE::START_WITH_SETTING;

        if (Controller::is_start_design()) {
            Controller::set_init_mode($newMode);
        }

        Controller::set_mode($newMode);
        $initMode = Controller::get_init_mode();
        $steps = Controller::get_steps($initMode);
        foreach ($steps as $key => $value) {
            if ($value === $newMode) {
                Controller::set_active_step($key + 1);
                break;
            }
        }


        echo wp_json_encode([
            'mode' => $newMode,
            "isSuccess" => true
        ]);
        wp_die();
    }

    /**
     * Sets the active mode from the ?mode query parameter.
     * AJAX action: set_mode   $_GET: mode
     */
    public static function set_mode()
    {
        $newMode = $_GET["mode"];

        if (Controller::is_start_design()) {
            Controller::set_init_mode($newMode);
        }

        $initMode = Controller::get_init_mode();
        $steps = Controller::get_steps($initMode);
        foreach ($steps as $key => $value) {
            if ($value === $newMode) {
                Controller::set_active_step($key + 1);
                break;
            }
        }
        Controller::set_mode($newMode);

        echo wp_json_encode([
            'mode' => $newMode,
            "isSuccess" => true
        ]);
        wp_die();
    }

    /**
     * Handles product selection for both ring and stone steps.
     *
     * Adds the product to the tray (new design) or updates the WC cart line (editing an
     * existing collection via uuid). Advances or corrects the step, saves the collection,
     * and resets the tray when the design is complete.
     *
     * AJAX action: select_product
     * $_POST: product_id, variation_id, type, uuid (optional), stone_option (optional),
     *         attribute_* (variation attributes, any number of keys)
     */
    public static function select_product()
    {
        $productId    = intval($_POST["product_id"]);
        $variation_id = !empty($_POST["variation_id"]) ? intval($_POST["variation_id"]) : 0;
        $current_uuid = !empty($_POST["uuid"]) ? $_POST["uuid"] : '';
        $message      = '';
        $attrs        = [];
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $is_attr = strpos($key, "attribute_");
                if ($is_attr !== false) {
                    $attrs[$key] = $value;
                }
            }
        }

        $stone_option = isset($_POST["stone_option"]) ? $_POST["stone_option"] : '';
        $type = $_POST["type"];
        $initMode = Controller::get_init_mode();
        $steps = Controller::get_steps($initMode);
        $currentStep = Controller::get_active_step();
        $nextStep = $currentStep + 1 > 3 ? 3 : $currentStep + 1;
        $mode = $steps[$nextStep - 1] ?? '';

        Controller::set_uuid();
        $uuid = empty($current_uuid) ? Controller::get_uuid() : $current_uuid;

        $collections = Controller::get_collections();
        $currentCollection = $collections[$uuid] ?? [];

        if ($type === PRODUCT_TYPES::RING) {
            $id = empty($variation_id) ? $productId : $variation_id;
            $message = Controller::product_is_valid($id);

            if (empty($message)) {
                if (empty($current_uuid)) {
                    Controller::add_item_to_tray([
                        "type" => PRODUCT_TYPES::RING,
                        "product_id" => $productId,
                        "variation_id" => $variation_id,
                        "attrs" => $attrs,
                    ]);
                }

                if (!empty($current_uuid)) {
                    $ring_cart_item_line = $currentCollection['ring_cart_item_line'] ?? '';
                    if (!empty($ring_cart_item_line)) {
                        WC()->cart->remove_cart_item($ring_cart_item_line);
                    }

                    $ring_cart_item_line = WC()->cart->add_to_cart($productId, 1, $variation_id, $attrs, [
                        'unique_key' => $uuid . '_ring',
                    ]);
                    $currentCollection['ring_cart_item_line'] = $ring_cart_item_line;
                    $currentCollection['ring'] = $productId;
                    $currentCollection['attrs'] = $attrs;
                    $currentCollection['variation_id'] = $variation_id;
                }
            }
        }

        if (!empty($stone_option) && $stone_option !== 'custom_stone') {
            $message = Controller::product_is_valid($stone_option);
            if (empty($message)) {
                if (empty($current_uuid)) {
                    Controller::add_item_to_tray([
                        "type" => PRODUCT_TYPES::STONE,
                        "product_id" => $stone_option,
                        "variation_id" => 0,
                        "attrs" => [],
                    ]);
                }

                if (!empty($current_uuid)) {
                    $stone_cart_item_line = $currentCollection['stone_cart_item_line'] ?? '';

                    if (!empty($stone_cart_item_line)) {
                        WC()->cart->remove_cart_item($stone_cart_item_line);
                    }

                    $stone_cart_item_line = WC()->cart->add_to_cart($stone_option, 1, 0, [], [
                        'unique_key' => $uuid . '_stone_option',
                    ]);
                    $currentCollection['stone_cart_item_line'] = $stone_cart_item_line;
                    $currentCollection['stone'] = $stone_option;
                }
            }
        }

        if ($type === PRODUCT_TYPES::STONE) {
            $message = Controller::product_is_valid($productId);
            if (empty($message)) {
                if (empty($current_uuid)) {
                    Controller::add_item_to_tray([
                        "type" => PRODUCT_TYPES::STONE,
                        "product_id" => $productId,
                        "variation_id" => $variation_id,
                        "attrs" => $attrs,
                    ]);
                }

                if (!empty($current_uuid)) {
                    $stone_cart_item_line = $currentCollection['stone_cart_item_line'] ?? '';
                    if (!empty($stone_cart_item_line)) {
                        WC()->cart->remove_cart_item($stone_cart_item_line);
                    }
                    $stone_cart_item_line = WC()->cart->add_to_cart($productId, 1, $variation_id, $attrs, [
                        'unique_key' => $uuid . '_stone',
                    ]);
                    $currentCollection['stone_cart_item_line'] = $stone_cart_item_line;
                    $currentCollection['stone'] = $productId;
                }
            }
        }

        $stone = Controller::get_stone();
        $ring = Controller::get_ring();
        if ($nextStep === 3 && empty($stone)) {
            foreach ($steps as $key => $step) {
                if ($step === MODE::START_WITH_STONE) {
                    $nextStep = $key + 1;
                    $mode = $steps[$nextStep - 1] ?? '';
                    break;
                }
            }
        }

        if ($nextStep === 3 && empty($ring)) {
            foreach ($steps as $key => $step) {
                if ($step === MODE::START_WITH_SETTING) {
                    $nextStep = $key + 1;
                    $mode = $steps[$nextStep - 1] ?? '';
                    break;
                }
            }
        }

        if (Controller::is_finish_design()) {
            $nextStep = 3;
            $mode = MODE::CONFIRM;
        }

        if (empty($message)) {
            Controller::set_active_step($nextStep);
            Controller::set_mode($mode);

            if (empty($current_uuid)) {
                $tray = Controller::get_tray();
                $collections[$uuid] = Controller::parse_tray_to_collection($tray);
            }

            if (!empty($current_uuid)) {
                $collections[$uuid] = $currentCollection;
            }

            Controller::set_collections($collections);
        }

        $data = Controller::get_data();

        if ($nextStep === 3 && Controller::is_finish_design()) {
            Controller::reset();
        }

        wc_clear_notices();

        echo wp_json_encode([
            'reload' => true,
            "isSuccess" => empty($message),
            'message' => $message,
            'data' => $data,
        ]);
        wp_die();
    }

    /**
     * Returns the full UI state payload on page load.
     * AJAX action: get_init_data
     */
    public static function get_init_data()
    {
        echo wp_json_encode(Controller::get_data());
        wp_die();
    }

    /**
     * Saves selected variation attributes and variation ID without changing any cart items.
     * Used when the user adjusts ring options on the product page before confirming.
     * AJAX action: update_product_attrs   $_GET: variation_id, attribute_* keys
     */
    public static function update_product_attrs()
    {
        $request = $_GET;
        $variation_id = intval($request['variation_id']);
        $attrs = [];
        if (!empty($request)) {
            foreach ($request as $key => $value) {
                $is_attr = strpos($key, "attribute_");
                if ($is_attr !== false) {
                    $attrs[$key] = $value;
                }
            }
        }

        Controller::set_product_variation($variation_id);

        echo wp_json_encode([
            "isSuccess" => true,
            "data" => Controller::set_product_attrs($attrs)
        ]);
        wp_die();
    }

    /**
     * Returns the current WC cart subtotal as a formatted price string.
     * AJAX action: get_subtotal
     */
    public static function get_subtotal()
    {

        echo wp_json_encode([
            "isSuccess" => true,
            "data" => wc_price(WC()->cart->get_subtotal()),
        ]);
        wp_die();
    }

    /**
     * Returns rendered HTML for a single tray product info block.
     * AJAX action: get_product_tray_product_info   $_GET: product_id
     */
    public static function get_product_tray_product_info()
    {
        $product_id = intval($_GET["product_id"]);
        echo wp_json_encode([
            "isSuccess" => true,
            "data" => \TTG_Template::get_template_part('build-ring-tray-product-info', ['product_id' => $product_id]),
        ]);
        wp_die();
    }

    /**
     * Returns rendered HTML for the stone options panel of the currently selected ring.
     * AJAX action: get_stone_options
     */
    public static function get_stone_options()
    {
        $ring = Controller::get_ring();
        global $product;
        $product = wc_get_product($ring);
        $stone_options_html = \TTG_Template::get_template_part('build-ring-stone-options', ['product_id' => $ring]);

        echo wp_json_encode([
            'data' => $stone_options_html,
            "isSuccess" => true
        ]);
        wp_die();
    }

    /**
     * Validates the requested product (and the stored stone if any) and returns error messages.
     * AJAX action: product_is_valid   $_GET: product_id
     */
    public static function product_is_valid()
    {
        $product_id = isset($_GET["product_id"]) ? intval($_GET["product_id"]) : 0;
        $stone_id = Controller::get_stone();
        $message = Controller::product_is_valid($product_id);
        $messages = [];

        if (!empty($message)) {
            $messages[] = "Setting: " . $message;
        }

        if ($stone_id > 0) {
            $message = Controller::product_is_valid($stone_id);
            if (!empty($message)) {
                $messages[] = "Stone: " . $message;
            }
        }

        echo wp_json_encode([
            'data' => implode('<br>', $messages),
            "isSuccess" => true
        ]);
        wp_die();
    }

    /**
     * Jumps the wizard to an arbitrary step number.
     * AJAX action: set_step   $_GET: step
     */
    public static function set_step()
    {
        $step = intval($_GET["step"]);
        $initMode = Controller::get_init_mode();
        $steps = Controller::get_steps($initMode);
        $mode = $steps[$step - 1] ?? '';

        Controller::set_active_step($step);
        Controller::set_mode($mode);
        echo wp_json_encode([
            "isSuccess" => true
        ]);
        wp_die();
    }

    /**
     * Removes a saved collection and its WC cart items.
     * AJAX action: remove_design   $_GET: uuid
     */
    public static function remove_design()
    {
        $uuid = $_GET["uuid"];

        Controller::remove_design($uuid);

        echo wp_json_encode([
            "isSuccess" => true
        ]);
        wp_die();
    }

    /**
     * Sets both init_mode and mode from a query parameter (used on page load).
     * AJAX action: set_init_mode   $_GET: initMode
     */
    public static function set_init_mode()
    {
        $initMode = $_GET["initMode"];
        Controller::set_init_mode($initMode);
        Controller::set_mode($initMode);

        echo wp_json_encode([
            "isSuccess" => true
        ]);
        wp_die();
    }

    /**
     * Resets the tray and page-level state.
     *
     * Without ?force=1 the reset is blocked when a tray is in progress, returning
     * isSuccess:false and an isEditing flag so the front-end can prompt the user.
     *
     * AJAX action: reset   $_GET: force (optional)
     */
    public static function reset()
    {
        $force = !empty($_GET['force']);
        $tray  = Controller::get_tray();
        $uuid  = Controller::get_uuid();

        if (!$force && !empty($tray)) {
            $collections = Controller::get_collections();
            $isEditing   = !empty($uuid) && !empty($collections[$uuid]);

            echo wp_json_encode([
                'isSuccess' => false,
                'isEditing' => $isEditing,
            ]);
            wp_die();
        }

        Controller::reset();
        Controller::reset_page_data();

        echo wp_json_encode([
            "isSuccess" => true,
            "data"      => Controller::get_data(),
        ]);
        wp_die();
    }

    /**
     * Restores a saved collection to the tray so the user can change its ring or stone.
     *
     * Blocked without ?force=1 when another tray is already in progress.
     *
     * AJAX action: change_item   $_GET: uuid, mode, force (optional)
     */
    public static function change_item()
    {
        $uuid  = $_GET['uuid']  ?? '';
        $mode  = $_GET['mode']  ?? '';
        $force = !empty($_GET['force']);

        if (empty($uuid) || empty($mode)) {
            echo wp_json_encode(['isSuccess' => false, 'message' => 'Missing params']);
            wp_die();
        }

        if (!$force && !empty(Controller::get_tray())) {
            echo wp_json_encode(['isSuccess' => false, 'hasPendingTray' => true]);
            wp_die();
        }

        $result = Controller::restore_collection_to_tray($uuid, $mode);

        echo wp_json_encode([
            'isSuccess' => $result,
            'data'      => Controller::get_data(),
        ]);
        wp_die();
    }

    /**
     * Aborts an in-progress edit without touching WC cart items.
     * The tray session and uuid are cleared; the collection's cart lines remain intact.
     * AJAX action: cancel_editing
     */
    public static function cancel_editing()
    {
        // Only clear tray session + uuid — do NOT touch WC cart
        // (tray items reference the same cart items as the collection)
        Controller::clear_tray();
        Controller::clear_uuid();

        echo wp_json_encode([
            'isSuccess' => true,
            'data'      => Controller::get_data(),
        ]);
        wp_die();
    }

    /**
     * Moves the wizard back one step.
     * AJAX action: go_back_step
     */
    public static function go_back_step()
    {
        $result = Controller::go_back_step();

        echo wp_json_encode([
            "isSuccess" => true,
            "isFirstStep" => $result['isFirstStep'],
            "data" => Controller::get_data(),
        ]);
        wp_die();
    }

    /**
     * Removes a single item from the tray by its UUID and removes its WC cart line.
     * AJAX action: remove_tray_item   $_GET: id (tray item uuid)
     */
    public static function remove_tray_item()
    {
        $id = $_GET["id"];
        Controller::remove_item_from_tray($id);

        echo wp_json_encode([
            "isSuccess" => true
        ]);
        wp_die();
    }

    /**
     * Returns re-rendered HTML for the ring card inside a saved collection.
     * Used after attributes are updated to reflect the new variation visually.
     * AJAX action: refresh_collection_ring   $_GET: uuid
     */
    public static function refresh_collection_ring()
    {
        $uuid = $_GET["uuid"];

        if (empty($uuid)) {
            echo wp_json_encode([
                "isSuccess" => false,
                "message" => "UUID is empty"
            ]);
            wp_die();
        }

        $collection = Controller::get_collections();
        $collection = $collection[$uuid] ?? [];

        if (empty($collection)) {
            echo wp_json_encode([
                "isSuccess" => false,
                "message" => "Collection is empty"
            ]);
            wp_die();
        }

        $ring_html = \TTG_Template::render(
            'build-ring-collection-item-ring',
            [
                'product_id' => $collection['ring'],
                'cart_line_item' => $collection['ring_cart_item_line'],
                'type' => 'Setting',
                'variation_id' => $collection['variation_id'],
                'mode'           => \TTG\Build_Ring\MODE::START_WITH_SETTING,
                'uuid'          => $uuid ?? '',
            ]
        );

        echo wp_json_encode([
            'data' => $ring_html,
            "isSuccess" => true
        ]);
        wp_die();
    }

    /**
     * Removes any collection that is missing either a ring or a stone, and cleans its WC cart lines.
     * Intended to be called before checkout to prevent partial designs from reaching the order.
     * AJAX action: clear_uncomplete_design
     */
    public static function clear_uncomplete_design()
    {
        $collections = Controller::get_collections();

        if (!empty($collections)) {
            foreach ($collections as $uuid => $collection) {
                $ring = $collection['ring'] ?? 0;
                $stone = $collection['stone'] ?? 0;
                $ring_cart_item_line = $collection['ring_cart_item_line'] ?? '';
                $stone_cart_item_line = $collection['stone_cart_item_line'] ?? '';
                $is_uncomplete_design = empty($ring) || empty($stone) ? true : false;

                if ($is_uncomplete_design) {
                    if (!empty($ring_cart_item_line)) {
                        WC()->cart->remove_cart_item($ring_cart_item_line);
                    }

                    if (!empty($stone_cart_item_line)) {
                        WC()->cart->remove_cart_item($stone_cart_item_line);
                    }

                    unset($collections[$uuid]);
                    Controller::set_collections($collections);
                    continue;
                }
            }
        }

        echo wp_json_encode([
            "isSuccess" => true
        ]);
        wp_die();
    }

    /**
     * Checks whether every collection has a variation selected and returns a user-facing message if not.
     * AJAX action: is_valid_collections
     */
    public static function is_valid_collections()
    {
        $is_valid = Controller::is_valid_collections();
        $message = $is_valid ? '' : 'Some ring designs are still incomplete. Please complete them to continue.';

        echo wp_json_encode([
            "isSuccess" => true,
            "isValid" => $is_valid,
            "message" => $message
        ]);
        wp_die();
    }
}

add_action("wp_ajax_is_valid_collections", "TTG\Build_Ring\Ajax::is_valid_collections");
add_action("wp_ajax_nopriv_is_valid_collections", "TTG\Build_Ring\Ajax::is_valid_collections");

add_action("wp_ajax_clear_uncomplete_design", "TTG\Build_Ring\Ajax::clear_uncomplete_design");
add_action("wp_ajax_nopriv_clear_uncomplete_design", "TTG\Build_Ring\Ajax::clear_uncomplete_design");

add_action("wp_ajax_refresh_collection_ring", "TTG\Build_Ring\Ajax::refresh_collection_ring");
add_action("wp_ajax_nopriv_refresh_collection_ring", "TTG\Build_Ring\Ajax::refresh_collection_ring");

add_action("wp_ajax_remove_tray_item", "TTG\Build_Ring\Ajax::remove_tray_item");
add_action("wp_ajax_nopriv_remove_tray_item", "TTG\Build_Ring\Ajax::remove_tray_item");

add_action("wp_ajax_set_init_mode", "TTG\Build_Ring\Ajax::set_init_mode");
add_action("wp_ajax_nopriv_set_init_mode", "TTG\Build_Ring\Ajax::set_init_mode");

add_action("wp_ajax_reset", "TTG\Build_Ring\Ajax::reset");
add_action("wp_ajax_nopriv_reset", "TTG\Build_Ring\Ajax::reset");

add_action("wp_ajax_remove_design", "TTG\Build_Ring\Ajax::remove_design");
add_action("wp_ajax_nopriv_remove_design", "TTG\Build_Ring\Ajax::remove_design");

add_action("wp_ajax_set_step", "TTG\Build_Ring\Ajax::set_step");
add_action("wp_ajax_nopriv_set_step", "TTG\Build_Ring\Ajax::set_step");

add_action("wp_ajax_product_is_valid", "TTG\Build_Ring\Ajax::product_is_valid");
add_action("wp_ajax_nopriv_product_is_valid", "TTG\Build_Ring\Ajax::product_is_valid");

add_action("wp_ajax_get_product_tray_product_info", "TTG\Build_Ring\Ajax::get_product_tray_product_info");
add_action("wp_ajax_nopriv_get_product_tray_product_info", "TTG\Build_Ring\Ajax::get_product_tray_product_info");

add_action("wp_ajax_get_stone_options", "TTG\Build_Ring\Ajax::get_stone_options");
add_action("wp_ajax_nopriv_get_stone_options", "TTG\Build_Ring\Ajax::get_stone_options");

add_action("wp_ajax_get_subtotal", "TTG\Build_Ring\Ajax::get_subtotal");
add_action("wp_ajax_nopriv_get_subtotal", "TTG\Build_Ring\Ajax::get_subtotal");

add_action("wp_ajax_update_product_attrs", "TTG\Build_Ring\Ajax::update_product_attrs");
add_action("wp_ajax_nopriv_update_product_attrs", "TTG\Build_Ring\Ajax::update_product_attrs");

add_action("wp_ajax_get_init_data", "TTG\Build_Ring\Ajax::get_init_data");
add_action("wp_ajax_nopriv_get_init_data", "TTG\Build_Ring\Ajax::get_init_data");

add_action("wp_ajax_cancel_editing", "TTG\Build_Ring\Ajax::cancel_editing");
add_action("wp_ajax_nopriv_cancel_editing", "TTG\Build_Ring\Ajax::cancel_editing");

add_action("wp_ajax_change_item", "TTG\Build_Ring\Ajax::change_item");
add_action("wp_ajax_nopriv_change_item", "TTG\Build_Ring\Ajax::change_item");

add_action("wp_ajax_go_back_step", "TTG\Build_Ring\Ajax::go_back_step");
add_action("wp_ajax_nopriv_go_back_step", "TTG\Build_Ring\Ajax::go_back_step");

add_action("wp_ajax_toggle_mode", "TTG\Build_Ring\Ajax::toggle_mode");
add_action("wp_ajax_nopriv_toggle_mode", "TTG\Build_Ring\Ajax::toggle_mode");

add_action("wp_ajax_set_mode", "TTG\Build_Ring\Ajax::set_mode");
add_action("wp_ajax_nopriv_set_mode", "TTG\Build_Ring\Ajax::set_mode");

add_action("wp_ajax_select_product", "TTG\Build_Ring\Ajax::select_product");
add_action("wp_ajax_nopriv_select_product", "TTG\Build_Ring\Ajax::select_product");

add_filter("body_class", function ($classes) {
    if (Controller::is_building_ring()) {
        $classes[] = "page-build-ring-wrapper";
    }

    return $classes;
});

add_action('wp_head', function () {
    if (Controller::is_building_ring()) {
?>
        <script>
            var currentStep = <?php echo Controller::get_active_step(); ?>;
        </script>
<?php
    }
});
