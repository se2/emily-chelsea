<?php

/**
 * Hook: woocommerce_after_shop_loop_item_title.
 *
 * @hooked woocommerce_template_loop_rating - 5
 * @hooked woocommerce_template_loop_price - 10
 */

add_action('woocommerce_after_shop_loop_item_title', function () {
    global $post;
    $term = TTG_Util::get_main_term($post->ID, 'product_cat');
    if (!empty($term)) {
        echo '<div class="product-cat">' . $term->name . '</div>';
    }
    echo '</div>';
}, 11);
