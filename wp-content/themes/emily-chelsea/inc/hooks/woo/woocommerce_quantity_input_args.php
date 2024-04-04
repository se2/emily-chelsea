<?php
//apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );
add_filter('woocommerce_quantity_input_args', function ($args, $product) {
    $max_value = $args['max_value'];
    $min_value = $args['min_value'];
    $args['is_hidden'] =  $min_value > 0 && $min_value === $max_value && $product->get_manage_stock() ? true : false;

    return $args;
}, 10, 2);
