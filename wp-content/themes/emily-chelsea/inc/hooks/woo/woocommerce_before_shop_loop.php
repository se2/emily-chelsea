<?php

/**
 * Hook: woocommerce_before_shop_loop.
 *
 * @hooked woocommerce_output_all_notices - 10
 * @hooked woocommerce_result_count - 20
 * @hooked woocommerce_catalog_ordering - 30
 */
remove_action("woocommerce_before_shop_loop", "woocommerce_result_count", 20);
remove_action("woocommerce_before_shop_loop", "woocommerce_output_all_notices", 10);
//remove_action("woocommerce_before_shop_loop", "woocommerce_catalog_ordering", 30);
add_action('woocommerce_before_shop_loop', function () {
    echo TTG_Template::get_template_part('products-filter');
    echo '</div>';
}, 40);
add_action('woocommerce_before_shop_loop', function () {
    echo '<div class="products-filter-wrapper">';
}, 19);
