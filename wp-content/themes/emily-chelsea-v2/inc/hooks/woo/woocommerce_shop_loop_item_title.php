<?php

/**
 * Hook: woocommerce_shop_loop_item_title.
 *
 * @hooked woocommerce_template_loop_product_title - 10
 */
add_action('woocommerce_shop_loop_item_title', function () {
    echo "<div class='product-title-wrapper'>";
}, 9);
