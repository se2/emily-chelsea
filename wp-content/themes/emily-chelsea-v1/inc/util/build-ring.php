<?php
class TTG_Build_Ring
{
    public static $SETTING_FIRST = 'SETTING_FIRST';
    public static $STONE_FRIST = 'STONE_FRIST';

    public static function set_action($action)
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION["action"] = $action;
    }

    public static function get_action()
    {
        return  $_SESSION["action"];
    }

    public static function get_root_ring_page()
    {
        $page = get_field('setting_first_page', 'options');
        return get_permalink($page);
    }

    public static function get_root_stone_page()
    {
        $page = get_field('stone_first_page', 'options');
        return get_permalink($page);
    }

    public static function set_nav($current_nav)
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION["nav"] = $current_nav;
    }

    public static function get_nav()
    {
        if (!session_id()) {
            session_start();
        }

        return $_SESSION["nav"];
    }

    public static function get_step()
    {
        $step = isset($_GET['step']) ? $_GET['step'] : 1;
        $nav = self::get_nav();

        if ($nav === self::$SETTING_FIRST) {
            if (($step == 2 || self::is_stone_page())) return 2;
            if (($step == 1 && self::is_ring_page())) return 1;
        }

        if ($nav === self::$STONE_FRIST) {
            if (($step == 2 || self::is_ring_page())) return 2;
            if (($step == 1 && self::is_stone_page())) return 1;
        }


        return $step;
    }

    public static function get_setting_first()
    {
        $step = self::get_step();
        $nav = self::get_nav();

        $config = [
            [
                "step" => 1,
                "label" => "Choose Your Setting",
                "active" =>  $step == 1 ? true : false,
                "href" => self::get_ring_page()
            ],
            [
                "step" => 2,
                "label" => "Select A Stone",
                "active" => $step == 2 ? true : false,
                "href" => self::get_stone_page()
            ],
            [
                "step" => 3,
                "label" => "Confirm Your Cart",
                "active" => $step == 3 ? true : false,
                "href" => self::get_setting_page_step(3)
            ]
        ];

        return TTG_Template::render("build-ring-step", ["items" => $config]);
    }

    public static function get_stone_first()
    {
        $step = self::get_step();

        $config = [
            [
                "step" => 1,
                "label" => "Select A Stone",
                "active" => $step === 1 ? true : false,
                "href" => self::get_stone_page()
            ],
            [
                "step" => 2,
                "label" => "Choose Your Setting",
                "active" => $step === 2 ? true : false,
                "href" => self::get_ring_page()
            ],
            [
                "step" => 3,
                "label" => "Confirm Your Cart",
                "active" => $step == 3 ? true : false,
                "href" => self::get_setting_page_step(3)
            ]
        ];

        return TTG_Template::render("build-ring-step", ["items" => $config]);
    }

    public static function get_setting_page_step($step = 1)
    {
        $nav = self::get_nav();

        if ($nav === self::$SETTING_FIRST) {
            return self::get_root_ring_page() . '?step=' . $step;
        }

        return self::get_root_stone_page() . '?step=' . $step;
    }

    public static function get_ring_page()
    {
        $nav = self::get_nav();
        $step = self::get_step();

        if ($nav === self::$SETTING_FIRST) {
            return self::get_setting_page_step(1);
        }

        $selected_stone = self::get_selected_stone();
        if (empty($selected_stone) && $step != 3) {
            return self::get_root_ring_page();
        }

        return self::get_setting_page_step(2);
    }

    public static function get_stone_page()
    {
        $nav = self::get_nav();
        $step = self::get_step();

        if ($nav === self::$SETTING_FIRST) {
            $selected_ring = self::get_selected_ring();
            if (empty($selected_ring) && $step != 3) {
                return self::get_root_stone_page();
            }
            return self::get_setting_page_step(2);
        }

        return self::get_setting_page_step(1);
    }


    public static function is_ring_page()
    {
        $nav = self::get_nav();

        if (empty($nav)) return false;

        if (is_page_template("page-ring-setting.php")) {
            return true;
        }

        if (is_singular("product")) {
            global $post;
            return has_term(["rings"], 'product_cat', $post);
        }

        return false;
    }

    public static function is_stone_page()
    {
        $nav = self::get_nav();

        if (empty($nav)) return false;

        if (is_page_template("page-stone-first.php")) {
            return true;
        }

        if (is_tax("product_cat", "center-stones")) return true;
        if (is_singular("product")) {
            global $post;
            return has_term(["center-stones"], 'product_cat', $post);
        }

        return false;
    }

    public static function steps()
    {
        $nav = self::get_nav();
        if (is_page_template("page-ring-setting.php") || $nav === self::$SETTING_FIRST) {
            return self::get_setting_first();
        }

        if (is_page_template("page-stone-first.php") || $nav === self::$STONE_FRIST) {
            return self::get_stone_first();
        }
    }

    public static function button()
    {
        if (!self::is_ring_page() && !self::is_stone_page()) return '';

        return TTG_Template::render("choose-this-setting-btn");
    }

    public static function modal($product_id)
    {
        $nav = self::get_nav();

        if (self::is_ring_page() && $nav === self::$SETTING_FIRST) {
            $selected_ring = self::get_selected_ring();
            $ring = self::get_ring($selected_ring);
            $stone = $ring['stone'];
            $content = !empty($stone) ? '' : 'Donec placerat risus leo, id vehicula mi consequat sed. Proin ipsum libero, volutpat eget blandit vel, viverra nec sapien. Mauris rhoncus tincidunt bibendum. Proin imperdiet condimentum lectus, vitae dictum libero tincidunt et. Nunc et sem orci. Praesent nibh arcu, semper sit amet varius in, condimentum in arcu.';
            $defaut_stone = get_field('default_stone', $product_id);

            if (empty($defaut_stone)) {
                $defaut_stone = get_field('default_stone', 'options');
            }

            return TTG_Template::render("build-ring-modal", [
                "title" => 'You’ve Locked In Your Setting Now, Select the Perfect Center Stone!',
                "content" => $content,
                "actions" => TTG_Template::render(
                    "build-ring-actions",
                    [
                        "product_id" => $product_id,
                        'stone_id' => $stone,
                        'defaut_stone_id' => $defaut_stone
                    ]
                ),
                "note" => "*RECOMMENDED: " . get_the_title($defaut_stone),
            ]);
        }

        if (self::is_stone_page()) {
            $stones = self::get_stones_stack();
            $selected_ring = self::get_selected_ring();
            $title = "You’ve Locked In Stone, Now Choose the Perfect Setting!";
            $defaut_stone = '';

            if (count($stones) == 1 && !empty($selected_ring)) {
                $title = "Select the Perfect Center Stone!";
            } else if ($selected_ring && count($stones) <= 1) {
                $title = "Select the Perfect Center Stone!";
            } else if (count($stones) > 1) {
                $title = "You’ve replaced the previously selected stone";
            }

            if ($selected_ring && count($stones) <= 1) {
                $ring = self::get_ring($selected_ring);
                $defaut_stone = get_field('default_stone', $ring['ring']);

                if (empty($defaut_stone)) {
                    $defaut_stone = get_field('default_stone', 'options');
                }
                $stones = [];
                $stones[] = self::get_selected_stone();
                $stones[] = $defaut_stone;
            } else {
                $stones = array_filter($stones, function ($stone) {
                    return $stone !== self::get_selected_stone();
                });
                $stone_prev = $stones[count($stones) - 1];
                $stones = [];
                $stones[] = self::get_selected_stone();
                if ($stone_prev) {
                    $stones[] = $stone_prev;
                }
            }

            return TTG_Template::render("build-ring-modal", [
                "title" => $title,
                "content" => '',
                "actions" => TTG_Template::render(
                    "build-stone-actions",
                    [
                        "product_id" => $product_id,
                        "stones" => $stones,
                        'default_stone' => $defaut_stone,
                    ],
                ),
                "note" => "",
            ]);
        }
    }

    public static function get_rings()
    {
        if (!session_id()) {
            session_start();
        }

        return $_SESSION["rings"];
    }

    public static function add_ring($product_id, $attrs = [])
    {
        if (!session_id()) {
            session_start();
        }

        if (empty($product_id)) return $_SESSION["rings"];

        $_SESSION["selected_ring"] = $product_id;
        $_SESSION["rings"]['product_' . $product_id] = [
            'ring' => $product_id,
            'stone' => '',
            'attrs' => $attrs
        ];

        return self::get_rings();
    }

    public static function add_stone_to_ring($stone_id = '', $ring_id = '')
    {
        self::set_action("");
        if (empty($ring_id)) {
            self::selected_stone($stone_id);
            return self::get_rings();
        }

        self::selected_stone('');
        self::selected_stone([]);
        return self::update_ring($ring_id, ['stone' => $stone_id]);
    }

    public static function update_ring($product_id, $data = [])
    {
        if (!session_id()) {
            session_start();
        }
        $key = 'product_' . $product_id;
        if (isset($_SESSION["rings"][$key])) {
            $ring = $_SESSION["rings"][$key];
            $_SESSION["rings"][$key] = array_merge($ring, $data);
        }

        return self::get_rings();
    }

    public static function change_ring($product_id)
    {
        if (!session_id()) {
            session_start();
        }

        if (empty($product_id)) return $_SESSION["change_ring"];

        $_SESSION["change_ring"] = $product_id;
    }

    public static function selected_ring($product_id)
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION["selected_ring"] = $product_id;

        return $_SESSION["selected_ring"];
    }

    public static function selected_stone($product_id)
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION["selected_stone"] = $product_id;
        self::set_stone_stack($product_id);
        return $_SESSION["selected_stone"];
    }

    public static function set_stone_stack($stone_id)
    {
        if (!session_id()) {
            session_start();
        }

        if (empty($_SESSION["stone_stack"])) {
            $_SESSION["stone_stack"] = [];
        }

        if (!in_array($stone_id, $_SESSION["stone_stack"]) && !empty($stone_id)) {
            $_SESSION["stone_stack"][] = $stone_id;
        }

        return $_SESSION["stone_stack"];
    }

    public static function get_stones_stack()
    {
        if (!session_id()) {
            session_start();
        }

        if (empty($_SESSION["stone_stack"])) {
            $_SESSION["stone_stack"] = [];
        }

        return $_SESSION["stone_stack"];
    }

    public static function get_selected_ring()
    {
        if (!session_id()) {
            session_start();
        }

        return $_SESSION["selected_ring"];
    }

    public static function get_selected_stone()
    {
        if (!session_id()) {
            session_start();
        }

        return $_SESSION["selected_stone"];
    }

    public static function get_change_ring()
    {
        if (!session_id()) {
            session_start();
        }

        return $_SESSION["change_ring"];
    }

    public static function replace_ring($new_ring_id, $old_ring_id, $attrs = [])
    {
        if (!session_id()) {
            session_start();
        }

        $new_ring_key = 'product_' . $new_ring_id;
        $old_ring_key = 'product_' . $old_ring_id;


        if (isset($_SESSION["rings"][$old_ring_key])) {
            $rings = $_SESSION["rings"];
            $ring = $rings[$old_ring_key];
            $ring['ring'] = $new_ring_id;
            $ring['attrs'] = $attrs;
            unset($rings[$old_ring_key]);

            $rings[$new_ring_key] = $ring;
            $_SESSION["rings"] = $rings;
        }

        return self::get_rings();
    }

    public static function is_exist_ring($product_id)
    {
        if (!session_id()) {
            session_start();
        }
        $key = 'product_' . $product_id;
        return isset($_SESSION["rings"][$key]);
    }

    public static function get_ring($product_id)
    {
        if (!session_id()) {
            session_start();
        }
        $key = 'product_' . $product_id;
        return $_SESSION["rings"][$key];
    }

    public static function checkout_rings()
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION["rings"] = [];
        $_SESSION["checkout_rings"] = self::get_rings();

        return self::get_rings();
    }

    public static function get_next_action()
    {
        $step = self::get_step();
        $action = self::get_action();

        if ($action === "change_stone") {
            return self::get_setting_page_step(3);
        }

        return self::get_setting_page_step($step + 1);
    }

    public static function build_another_ring()
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION["selected_ring"] = '';
        $_SESSION["selected_stone"] = '';
        $_SESSION["stone_stack"] = [];
        $_SESSION["change_ring"] = '';
        $_SESSION["action"] = '';
    }
}

function add_ring()
{
    $product_id = $_GET['variation_id'];
    $all = $_GET;
    $attrs = [];
    $selected_ring = TTG_Build_Ring::get_selected_ring();
    $selected_stone = TTG_Build_Ring::get_selected_stone();

    if (!empty($all)) {
        foreach ($all as $key => $value) {
            $is_attr = strpos($key, "attribute") !== false ? true : false;
            if ($is_attr) {
                $attrs[$key] = $value;
            }
        }
    }

    if (!empty($selected_ring)) {
        $data = TTG_Build_Ring::replace_ring($product_id, $selected_ring, $attrs);
        $selected_ring = TTG_Build_Ring::selected_ring($product_id);
    } else {
        $data = TTG_Build_Ring::add_ring($product_id, $attrs);
        $selected_ring = TTG_Build_Ring::selected_ring($product_id);

        if ($selected_stone) {
            TTG_Build_Ring::add_stone_to_ring($selected_stone, $product_id);
        }
    }



    echo wp_json_encode([
        'rings' => $data,
        "selected" => $selected_ring
    ]);
    wp_die();
}

add_action("wp_ajax_add_ring", "add_ring");
add_action("wp_ajax_nopriv_add_ring", "add_ring");

function update_selected_ring()
{
    $product_id = $_GET['variation_id'];
    $selected_ring = TTG_Build_Ring::selected_ring($product_id);
    echo wp_json_encode([
        'rings' => TTG_Build_Ring::get_rings(),
        "selected" => $selected_ring
    ]);
    wp_die();
}
add_action("wp_ajax_update_selected_ring", "update_selected_ring");
add_action("wp_ajax_nopriv_update_selected_ring", "update_selected_ring");

function build_another_ring()
{
    TTG_Build_Ring::build_another_ring();
    wp_die();
}
add_action("wp_ajax_build_another_ring", "build_another_ring");
add_action("wp_ajax_nopriv_build_another_ring", "build_another_ring");


function add_stone_to_ring()
{
    $stone_id = $_GET['stone_id'];
    $selected_ring = TTG_Build_Ring::get_selected_ring();

    TTG_Build_Ring::add_stone_to_ring($stone_id, $selected_ring);

    echo wp_json_encode([
        'rings' => TTG_Build_Ring::get_rings(),
        "selected" => $selected_ring
    ]);
    wp_die();
}
add_action("wp_ajax_add_stone_to_ring", "add_stone_to_ring");
add_action("wp_ajax_nopriv_add_stone_to_ring", "add_stone_to_ring");

function change_ring()
{
    $product_id = $_GET['ring_id'];
    $selected_ring = TTG_Build_Ring::selected_ring($product_id);
    TTG_Build_Ring::set_action("change_ring");

    echo wp_json_encode([
        'rings' => TTG_Build_Ring::get_rings(),
        "selected" => $selected_ring
    ]);
    wp_die();
}
add_action("wp_ajax_change_ring", "change_ring");
add_action("wp_ajax_nopriv_change_ring", "change_ring");

function change_stone()
{
    $product_id = $_GET['ring_id'];
    $selected_ring = TTG_Build_Ring::selected_ring($product_id);
    $ring = TTG_Build_Ring::get_ring($selected_ring);
    TTG_Build_Ring::set_action("change_stone");
    if ($ring['stone']) {
        TTG_Build_Ring::selected_stone($ring['stone']);
    }

    echo wp_json_encode([
        'rings' => TTG_Build_Ring::get_rings(),
        "selected" => $selected_ring
    ]);
    wp_die();
}
add_action("wp_ajax_change_stone", "change_stone");
add_action("wp_ajax_nopriv_change_stone", "change_stone");

function add_stone()
{
    $stone_id = $_GET['stone_id'];
    $selected_stone = TTG_Build_Ring::get_selected_stone();
    TTG_Build_Ring::selected_stone($stone_id);

    echo wp_json_encode([
        "selected_stone" =>  $selected_stone
    ]);
    wp_die();
}
add_action("wp_ajax_add_stone", "add_stone");
add_action("wp_ajax_nopriv_add_stone", "add_stone");


function confirm_rings()
{
    $rings = TTG_Build_Ring::get_rings();

    if (!empty($rings)) {
        foreach ($rings as $key => $value) {

            if ($value['ring']) {
                $ring = wc_get_product(intval($value['ring']));
                $product_id = $value['ring'];
                $variation_id = 0;
                if ($ring->get_parent_id()) {
                    $product_id = $ring->get_parent_id();
                    $variation_id = $value['ring'];
                }
                WC()->cart->add_to_cart($product_id, 1, $variation_id, $value['attrs'], [
                    'ring_data' => $value,
                ]);
            }

            if ($value['stone']) {
                WC()->cart->add_to_cart($value['stone'], 1, 0, [], [
                    'ring_data' => $value,
                ]);
            }
        }
        TTG_Build_Ring::checkout_rings();
    }


    echo wp_json_encode(TTG_Build_Ring::get_rings());
    wp_die();
}
add_action("wp_ajax_confirm_rings", "confirm_rings");
add_action("wp_ajax_nopriv_confirm_rings", "confirm_rings");

add_action("woocommerce_remove_cart_item", function ($cart_item_key, $cart) {
    $cart_item = $cart->cart_contents[$cart_item_key];
    $is_couple = !empty($cart_item['ring_data']) ? true : false;
    $is_ring = $is_couple && intval($cart_item['ring_data']['ring']) == $cart_item['variation_id'] ? true : false;

    if ($is_ring) {
        // find stone in cart
        $stone_id = $cart_item['ring_data']['stone'];
        if (!empty($stone_id)) {
            foreach ($cart->cart_contents as $key => $item) {
                if (isset($item['ring_data']) && isset($item['ring_data']['stone']) && $item['ring_data']['stone'] == $stone_id && $key !== $cart_item_key) {
                    $cart->remove_cart_item($key);
                    break;
                }
            }
        }
    }
}, 10, 2);

add_action("woocommerce_cart_item_set_quantity", function ($cart_item_key, $quantity, $cart) {
    $cart_item = $cart->cart_contents[$cart_item_key];
    $is_couple = !empty($cart_item['ring_data']) ? true : false;
    $is_ring = $is_couple && intval($cart_item['ring_data']['ring']) == $cart_item['variation_id'] ? true : false;
    if ($is_ring) {
        // find stone in cart
        $stone_id = $cart_item['ring_data']['stone'];
        if (!empty($stone_id)) {
            foreach ($cart->cart_contents as $key => $item) {
                if (isset($item['ring_data']) && isset($item['ring_data']['stone']) && $item['ring_data']['stone'] == $stone_id && $key !== $cart_item_key) {
                    $cart->set_quantity($key, $quantity);
                    break;
                }
            }
        }
    }
}, 10, 3);

add_action('woocommerce_restore_cart_item', function ($cart_item_key, $cart) {
    $cart_item = $cart->removed_cart_contents[$cart_item_key];
    $is_couple = !empty($cart_item['ring_data']) ? true : false;
    $is_ring = $is_couple && intval($cart_item['ring_data']['ring']) == $cart_item['variation_id'] ? true : false;
    if ($is_ring) {
        // find stone in cart
        $stone_id = $cart_item['ring_data']['stone'];
        if (!empty($stone_id)) {
            foreach ($cart->removed_cart_contents as $key => $item) {
                if (isset($item['ring_data']) && isset($item['ring_data']['stone']) && $item['ring_data']['stone'] == $stone_id && $key !== $cart_item_key) {
                    $cart->restore_cart_item($key);
                    break;
                }
            }
        }
    }
}, 10, 2);
