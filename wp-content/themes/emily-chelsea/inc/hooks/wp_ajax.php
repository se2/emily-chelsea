<?php
add_action('wp_ajax_get_cart_counter', 'get_cart_counter');
add_action('wp_ajax_nopriv_get_cart_counter', 'get_cart_counter');
function get_cart_counter()
{
    echo wp_json_encode(array(
        'counter' => WC()->cart->get_cart_contents_count()
    ));
    wp_die();
}
