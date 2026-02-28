<?php
add_filter("woocommerce_product_stock_status_options", function ($statues) {

    $statues["onbackorder"] = __('Made to Order', 'woocommerce');

    return $statues;
}, 10, 1);
