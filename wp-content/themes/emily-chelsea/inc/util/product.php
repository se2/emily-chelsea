<?php
class TTG_Product
{
    public static function has_depend_product($product)
    {
        $has_depend_cate = WC_Product_Dependencies()->get_tied_category_ids($product);
        $has_depend_product = WC_Product_Dependencies()->get_tied_product_ids($product);

        return !empty($has_depend_cate) || !empty($has_depend_product);
    }

    public static function get_stone()
    {
        return get_field('stone_term', 'options');
    }

    public static function get_ring()
    {
        return get_field('ring_term', 'options');
    }

    public static function is_stone($product_id)
    {
        $stone = self::get_stone();
        $p = get_post($product_id);
        return has_term($stone->term_id, 'product_cat', $p);
    }

    public static function get_stone_config()
    {
        $stone = self::get_stone();
        $icon = get_field('combine_product_indicator_icon', $stone);
        $text = get_field('combine_product_indicator_text', $stone);
        $add_text = get_field('combine_product_indicator_add_text', $stone);
        $not_include_text = get_field('combine_product_indicator_not_include_text', $stone);

        if (empty($icon)) {
            $icon = '<img src="' . get_theme_file_uri('src/dist/img/Group-161@2x.png') . '" alt="' . $stone->name . '" />';
        } else {
            $icon = wp_get_attachment_image($icon['id'], 'full');
        }

        if (empty($text)) {
            $text = $stone->name . ' INCLUDED';
        }

        if (empty($not_include_text)) {
            $not_include_text = "* " . $stone->name . " NOT INCLUDED\"";
        }

        if (empty($add_text)) {
            $add_text = 'ADD A CENTER STONE';
        }

        return [
            'icon' => $icon,
            'text' => $text,
            'add_text' => $add_text,
            'url' => get_term_link($stone),
            'not_include_text' => $not_include_text
        ];
    }

    public static function is_included_stone($product_id)
    {
        $stone_included = get_field("center_stone", $product_id);
        return $stone_included === 'include';
    }

    public static function is_not_included_stone($product_id)
    {
        $not_included = get_field("center_stone", $product_id);
        return $not_included === 'notInclude';
    }

    public static function count_stone_in_cart($cart_contents = [])
    {
        $stone_count = 0;
        $stone = TTG_Product::get_stone();
        $cart_contents = empty($cart_contents) ? WC()->cart->cart_contents : $cart_contents;
        foreach ($cart_contents as $cart_item) {
            $product_id   = $cart_item['product_id'];
            $quantity = $cart_item["quantity"];
            $p = get_post($product_id);
            if (has_term($stone->term_id, 'product_cat', $p)) {
                $stone_count += $quantity;
            }
        }

        return $stone_count;
    }

    public static function get_products_depend_stone_in_cart($cart_contents = [])
    {
        $product_ids = [];
        $total_quantity = 0;
        $cart_contents = empty($cart_contents) ? WC()->cart->cart_contents : $cart_contents;
        foreach ($cart_contents as $cart_item) {
            $product_id   = $cart_item['product_id'];
            $quantity = $cart_item["quantity"];
            if (self::is_not_included_stone($product_id)) {
                $product_ids[] = $product_id;
                $total_quantity += $quantity;
            }
        }

        return [
            'ids' => $product_ids,
            'total_quantity' => $total_quantity
        ];
    }

    public static function is_valid_cart($cart_contents = [])
    {
        $cart_contents = empty($cart_contents) ? WC()->cart->cart_contents : $cart_contents;
        $products = self::get_products_depend_stone_in_cart($cart_contents);
        $count_stone = self::count_stone_in_cart($cart_contents);

        if ($products['total_quantity'] > 0 && $count_stone < $products['total_quantity']) {
            return false;
        }

        return true;
    }

    public static function add_cart_notices($cart_contents = [])
    {
        $cart_contents = empty($cart_contents) ? WC()->cart->cart_contents : $cart_contents;
        $is_valid_cart = self::is_valid_cart($cart_contents);

        if (!$is_valid_cart) {
            $products = self::get_products_depend_stone_in_cart($cart_contents);
            $count_stone = self::count_stone_in_cart($cart_contents);
            $message = self::get_require_stone_message_cart($products['total_quantity'] - $count_stone);
            wc_add_notice($message, 'error');
        }
    }

    public static function get_require_stone_message_cart($number_stone_need = 0)
    {

        $stone = TTG_Product::get_stone();
        $stone_link =  sprintf('<a href="%s">%s</a>', get_term_link($stone, $stone->taxonomy), $stone->name);
        $message = sprintf('Item(s) In Your Cart Requires Product From %s Category. Please Add Product(s) From The %s Category To Your Cart.', $stone->name, $stone_link);
        return $message;
    }

    public static function get_require_stone_message($product_id)
    {
        $product_title = get_the_title($product_id);
        $stone = TTG_Product::get_stone();
        $stone_link =  sprintf('<a href="%s">%s</a>', get_term_link($stone, $stone->taxonomy), $stone->name);
        $message = sprintf('"%s" Requires Purchasing A Product From The "%s" Category. To Get Access To This Product Now, Please Add A Product From The %s Category To Your Cart.', $product_title, $stone->name, $stone_link);
        return $message;
    }

    public static function is_instock_meta_type($parent_product, $meta_type)
    {
        $products = get_posts(array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'attribute_pa_metal-type',
                    'value' => $meta_type,
                ),
                array(
                    'key' => '_stock',
                    'value' => 0,
                    "compare" => ">"
                )
            ),
            'post_parent' => intval($parent_product)
        ));

        return count($products);
    }
}
