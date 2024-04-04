<?php
// add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 10, 3 );
add_filter('woocommerce_add_to_cart_validation', function ($passed_validation, $item_id, $quantity) {

    $is_not_included_stone = TTG_Product::is_not_included_stone($item_id);

    if (!$is_not_included_stone) {
        return $passed_validation;
    }

    $stone_count = TTG_Product::count_stone_in_cart();

    if ($stone_count <= 0) {
        $message = TTG_Product::get_require_stone_message($item_id);
        wc_add_notice($message, 'error');
    }


    return $stone_count != 0;
}, 10, 3);


//do_action( 'woocommerce_remove_cart_item', $cart_item_key, $this );
// add_action("woocommerce_cart_item_removed", function ($cart_item_key, $cart) {
//     $cart_contents = $cart->cart_contents;
//     unset($cart_contents[$cart_item_key]);
//     $stone_count = TTG_Product::count_stone_in_cart($cart_contents);
//     if ($stone_count <= 0) {
//         foreach ($cart_contents as $cart_item) {
//             $product_id   = $cart_item['product_id'];

//             $is_not_included_stone = TTG_Product::is_not_included_stone($product_id);
//             if ($is_not_included_stone) {
//                 $key = $cart_item['key'];
//                 WC()->cart->remove_cart_item($key);
//             }
//         }
//     }
// }, 10, 2);
