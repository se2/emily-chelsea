<?php
add_filter("woocommerce_get_availability_text", function ($availability, $product) {
    if ($product->is_on_backorder(1)) {
        return __('Made to Order', 'woocommerce');
    }
    return $availability;
}, 10, 2);
