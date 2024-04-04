<?php
add_filter('woocommerce_output_related_products_args', function ($args) {
    $args['posts_per_page'] = 4;
    return $args;
}, 10, 1);
