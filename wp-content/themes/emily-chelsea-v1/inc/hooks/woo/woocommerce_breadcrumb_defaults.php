<?php
add_filter('woocommerce_breadcrumb_defaults', function ($args) {

    if (is_tax('product_cat') || is_singular('product')) {
        $args['home'] = get_the_title(get_option('woocommerce_shop_page_id'));
    }

    return $args;
});
