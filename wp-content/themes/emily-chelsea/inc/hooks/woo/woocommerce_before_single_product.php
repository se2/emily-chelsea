<?php

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
add_action('main-content-top', function () {
    if (is_singular('product')) {
        woocommerce_output_all_notices();
    }
}, 99);
