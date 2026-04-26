<?php

namespace TTG\Build_Ring;

use TTG_Template;

define("BUILD_RING_SESSION_KEY", "BUILD_RING_SESSION");

class PRODUCT_TYPES
{
    const RING = "rings";
    const STONE = "center-stones";
}

class MODE
{
    const START_WITH_SETTING = "START_WITH_SETTING";
    const START_WITH_STONE = "START_WITH_STONE";
    const CONFIRM = "CONFIRM";
}

class Model
{
    public static function start_session()
    {
        if (!session_id()) {
            session_start();
        }
    }

    public static function get_session($key = '')
    {
        self::start_session();
        $data = $_SESSION[BUILD_RING_SESSION_KEY] ?? [];

        if (empty($key)) {
            return $data;
        }

        return $data[$key] ?? null;
    }

    public static function set_session($key, $data)
    {
        self::start_session();
        $_SESSION[BUILD_RING_SESSION_KEY][$key] = $data;

        return $_SESSION[BUILD_RING_SESSION_KEY];
    }

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

class Controller
{
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

        $cart_line_item = WC()->cart->add_to_cart($productId, 1,  $variation_id, $attrs);

        $item = array_merge($item, $newItem);
        $item["cart_line_item"] = $cart_line_item;

        $uuid = $item["uuid"];
        $tray[$uuid] = $item;

        return Model::set_session("tray", $tray);
    }

    public static function get_tray()
    {
        return Model::get_session("tray") ?? [];
    }

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

    public static function clear_tray()
    {
        return Model::clear_session("tray");
    }

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

    public static function get_steps($initStep = MODE::START_WITH_SETTING)
    {
        $steps = [
            MODE::START_WITH_SETTING => [MODE::START_WITH_SETTING, MODE::START_WITH_STONE, MODE::CONFIRM],
            MODE::START_WITH_STONE => [MODE::START_WITH_STONE, MODE::START_WITH_SETTING, MODE::CONFIRM],
        ];

        return $steps[$initStep] ?? [];
    }

    public static function is_building_ring()
    {
        $is_processing = !empty(self::get_mode());

        if (is_singular('product')) {
            global $post;
            $mode = isset($_GET['mode']) ? $_GET['mode'] : '';
            $is_has_terms = has_term([PRODUCT_TYPES::RING, PRODUCT_TYPES::STONE], 'product_cat', $post);
            $is_processing = $is_processing && $is_has_terms ? true : false;
            if (empty($mode)) {
                $is_processing = false;
            }
        }


        return $is_processing || is_page_template('page-build-ring.php');
    }

    public static function set_uuid()
    {
        $current_uuid = self::get_uuid();
        if (!empty($current_uuid)) return $current_uuid;

        $uuid = uniqid();
        return Model::set_session("uuid", $uuid);
    }

    public static function clear_uuid()
    {
        return Model::clear_session("uuid");
    }

    public static function get_uuid()
    {
        return Model::get_session("uuid");
    }

    public static function set_init_mode($mode)
    {
        return  Model::set_session("init_mode", $mode);
    }

    public static function get_init_mode()
    {
        return Model::get_session("init_mode");
    }

    public static function set_mode($mode)
    {
        $init_mode = self::get_init_mode();
        if (empty($init_mode)) {
            self::set_init_mode($mode);
        }

        return  Model::set_session("mode", $mode);
    }

    public static function get_mode()
    {
        $mode = Model::get_session("mode");
        return empty($mode) ? null : $mode;
    }

    public static function set_ring($ring)
    {
        return Model::set_session("ring", $ring);
    }

    public static function get_ring()
    {
        $tray = self::get_tray();
        $ring = self::get_ring_from_tray($tray);
        $ring_id = $ring["product_id"] ?? 0;

        return $ring_id;
    }

    public static function set_stone($stone)
    {
        return Model::set_session("stone", $stone);
    }

    public static function get_stone()
    {
        $tray = self::get_tray();
        $stone = self::get_stone_from_tray($tray);
        $stone_id = $stone["product_id"] ?? 0;

        return $stone_id;
    }

    public static function get_active_step()
    {
        $step = Model::get_session("active_step");
        return empty($step) ? 1 : $step;
    }

    public static function set_active_step($step = 1)
    {
        return Model::set_session("active_step", $step);
    }

    public static function is_finish_design()
    {
        $ring = Controller::get_ring();
        $stone = Controller::get_stone();
        return $ring > 0 && $stone > 0 ? true : false;
    }

    public static function is_start_design()
    {
        $ring = self::get_ring();
        $stone = self::get_stone();

        return empty($ring) && empty($stone) ? true : false;
    }

    public static function get_collections()
    {
        return Model::get_session("collections") ?? [];
    }

    public static function set_collections($collections = [])
    {
        return Model::set_session("collections", $collections);
    }

    public static function set_product_attrs($attrs = [])
    {
        return Model::set_session('attrs', $attrs);
    }

    public static function get_attrs()
    {
        return Model::get_session('attrs');
    }

    public static function set_product_variation($variation_id)
    {
        return Model::set_session('variation', $variation_id);
    }

    public static function get_product_variation()
    {
        return Model::get_session('variation');
    }

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

    public static function get_default_stone($product_id = 0)
    {
        if (empty($product_id)) return 0;

        $default_stone = get_field('default_stone', $product_id);
        $default_stone_global = get_field('default_stone', 'option');

        return empty($default_stone) ? $default_stone_global : $default_stone;
    }

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

    public static function sort_cart($cart = [])
    {
        $collections = self::get_collections();
        $newCart = [];
        if (!empty($collections)) {
            foreach ($collections as $uuid => $collection) {
                $ring_cart_item_line = $collection['ring_cart_item_line'] ?? '';
                $stone_cart_item_line = $collection['stone_cart_item_line'] ?? '';

                if (isset($cart[$ring_cart_item_line])) {
                    $newCart[$ring_cart_item_line] = $cart[$ring_cart_item_line];
                    $newCart[$ring_cart_item_line]['is_ring'] = true;
                    $newCart[$ring_cart_item_line]['uuid'] = $uuid;
                    $cart[$ring_cart_item_line] = null;
                }

                if (isset($cart[$stone_cart_item_line])) {
                    $newCart[$stone_cart_item_line] = $cart[$stone_cart_item_line];
                    $newCart[$stone_cart_item_line]['is_stone'] = true;
                    $cart[$stone_cart_item_line] = null;
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

    public static function set_cart_item_lines($cart_item_lines = [])
    {
        return Model::set_session('cart_item_lines', $cart_item_lines);
    }

    public static function get_cart_item_lines()
    {
        return Model::get_session('cart_item_lines');
    }

    public static function reset()
    {
        self::set_ring(0);
        self::set_stone(0);
        self::set_product_attrs([]);
        self::set_product_variation(0);
        self::clear_uuid();
        self::clear_tray();
    }

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
}


class Ajax
{
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

    public static function select_product()
    {
        $productId = intval($_POST["product_id"]);
        $variation_id = !empty($_POST["variation_id"]) ? intval($_POST["variation_id"]) : 0;
        $current_uuid = !empty($_POST["uuid"]) ? $_POST["uuid"] : '';
        $attrs = [];
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

                    $ring_cart_item_line = WC()->cart->add_to_cart($productId, 1,  $variation_id, $attrs);
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
                        "variation_id" => $variation_id,
                        "attrs" => $attrs,
                    ]);
                }

                if (!empty($current_uuid)) {
                    $stone_cart_item_line = $currentCollection['stone_cart_item_line'] ?? '';

                    if (!empty($stone_cart_item_line)) {
                        WC()->cart->remove_cart_item($stone_cart_item_line);
                    }

                    $stone_cart_item_line = WC()->cart->add_to_cart($stone_option, 1,  $variation_id, $attrs);
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
                    $stone_cart_item_line = WC()->cart->add_to_cart($productId, 1,  $variation_id, $attrs);
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

        echo wp_json_encode([
            'reload' => true,
            "isSuccess" => empty($message),
            'message' => $message,
            'data' => $data,
        ]);
        wp_die();
    }

    public static function get_init_data()
    {
        echo wp_json_encode(Controller::get_data());
        wp_die();
    }

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

    public static function get_subtotal()
    {

        echo wp_json_encode([
            "isSuccess" => true,
            "data" => wc_price(WC()->cart->get_subtotal()),
        ]);
        wp_die();
    }

    public static function get_product_tray_product_info()
    {
        $product_id = intval($_GET["product_id"]);
        echo wp_json_encode([
            "isSuccess" => true,
            "data" => \TTG_Template::get_template_part('build-ring-tray-product-info', ['product_id' => $product_id]),
        ]);
        wp_die();
    }

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

    public static function remove_design()
    {
        $uuid = $_GET["uuid"];

        Controller::remove_design($uuid);

        echo wp_json_encode([
            "isSuccess" => true
        ]);
        wp_die();
    }

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

    public static function reset()
    {
        Controller::reset();

        echo wp_json_encode([
            "isSuccess" => true
        ]);
        wp_die();
    }

    public static function remove_tray_item()
    {
        $id = $_GET["id"];
        Controller::remove_item_from_tray($id);

        echo wp_json_encode([
            "isSuccess" => true
        ]);
        wp_die();
    }

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
            ]
        );

        echo wp_json_encode([
            'data' => $ring_html,
            "isSuccess" => true
        ]);
        wp_die();
    }
}

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
