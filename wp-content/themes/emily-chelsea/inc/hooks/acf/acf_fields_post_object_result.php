<?php

/**
 * Default Stone picker: flag evergreen stones that can't be shared across designs.
 * Each design adds the stone as its own cart line, which WooCommerce refuses
 * for a "Sold individually" product once it is already in the cart.
 */
add_filter('acf/fields/post_object/result/name=default_stone', function ($text, $post) {
    $product = wc_get_product($post->ID);
    if ($product && $product->is_sold_individually()) {
        $text .= ' — ⚠ Sold individually: turn off so it can be reused';
    }
    return $text;
}, 10, 2);
