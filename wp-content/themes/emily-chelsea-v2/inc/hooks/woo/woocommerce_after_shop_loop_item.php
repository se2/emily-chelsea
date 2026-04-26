<?php

/**
 * Hook: woocommerce_after_shop_loop_item.
 *
 * @hooked woocommerce_template_loop_product_link_close - 5
 * @hooked woocommerce_template_loop_add_to_cart - 10
 */
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
