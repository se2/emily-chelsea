<?php
add_filter('woocommerce_breadcrumb_home_url', function ($url) {

    if (is_tax('product_cat') || is_singular('product')) {
        return get_permalink(get_option('woocommerce_shop_page_id'));
    }

    return $url;
});
