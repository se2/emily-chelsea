<?php

/**
 * Hook: woocommerce_after_single_product_summary.
 *
 * @hooked woocommerce_output_product_data_tabs - 10
 * @hooked woocommerce_upsell_display - 15
 * @hooked woocommerce_output_related_products - 20
 */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

add_action('woocommerce_after_single_product_summary', function () {
    global $post;
    $product_ctas = get_field('product_ctas', $post->ID);
    foreach ($product_ctas as $key => $value) {
        echo TTG_Template::get_template_part('single-product-cta', ['product' => $value]);
    }
    echo TTG_Template::get_template_part('product-gallery', ['product_id' => $post->ID]);
}, 10);

add_action('woocommerce_after_single_product_summary', function () {
?>
    <div class="shop-continue">
        <a class="d-block text-center d-md-none" href="<?php echo wc_get_page_permalink('shop') ?>">CONTINUE SHOPPING</a>
    </div>
<?php
}, 30);
