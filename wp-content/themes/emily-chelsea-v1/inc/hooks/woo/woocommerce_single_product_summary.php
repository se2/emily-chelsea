<?php

/**
 * Hook: woocommerce_single_product_summary.
 *
 * @hooked woocommerce_template_single_title - 5
 * @hooked woocommerce_template_single_rating - 10
 * @hooked woocommerce_template_single_price - 10
 * @hooked woocommerce_template_single_excerpt - 20
 * @hooked woocommerce_template_single_add_to_cart - 30
 * @hooked woocommerce_template_single_meta - 40
 * @hooked woocommerce_template_single_sharing - 50
 * @hooked WC_Structured_Data::generate_product_data() - 60
 */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

add_action('woocommerce_single_product_summary', function () {
    global $post;
    global $product;
    $anchor_title = get_field('anchor_title', $post->ID);

    if ($product->get_type() === 'simple') {
        echo wc_get_stock_html($product);
    }

    echo '<div class="summary-spacing">' . TTG_Template::get_template_part('add-stone-button', ['product' => $product]) . '</div>';

    if (!empty($anchor_title)) {
        echo '<a href="#product-gallery" class="anchor-gallery">' . $anchor_title . '</a>';
    }

    echo TTG_Template::get_template_part('product-service');
}, 21);

add_action('woocommerce_single_product_summary', function () {
    global $product;
    echo '<div class="summary-spacing">'
        . TTG_Template::get_template_part('not-include-stone', ['product' => $product]) . '</div>';
}, 11);


add_action('woocommerce_single_product_summary', function () {
    global $product;
    $is_special_product = get_field('is_special_product', $product->get_id());
    if ($is_special_product) {
        echo TTG_Template::get_template_part('product-custom-buttons');
    }
    if (!$product->is_in_stock() && !$is_special_product && $product->is_type('simple')) {
?>
        <div class="cart"></div>
        <div class="product-line"></div>
<?php
        echo TTG_Template::get_template_part('inquire-button');
    }
}, 31);
