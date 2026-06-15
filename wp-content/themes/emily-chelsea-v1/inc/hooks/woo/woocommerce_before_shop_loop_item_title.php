<?php

/**
 * Hook: woocommerce_before_shop_loop_item_title.
 *
 * @hooked woocommerce_show_product_loop_sale_flash - 10
 * @hooked woocommerce_template_loop_product_thumbnail - 10
 */
add_action('woocommerce_before_shop_loop_item_title', function () {
    global $product;
    $product_id =  $product->get_id();
    echo '<div class="product__image-wrapper">';
    if (TTG_Product::is_included_stone($product_id)) {
?>
        <div class="link-product">
            <div class="link-product__item">
                <div class="link-product__item__icon">
                    <img src="<?php echo get_theme_file_uri('src/dist/img/Group-161@2x.png') ?>" alt=" Center Stones INCLUDED" />
                </div>
                <div class="link-product__item__text">
                    Center Stones INCLUDED
                </div>
            </div>
        </div>
<?php
    }
}, 1);
add_action('woocommerce_before_shop_loop_item_title', function () {
    global $product;
    $hover_image = get_field('hover_feature_image', $product->get_id());
    if (empty($hover_image)) {
        echo get_the_post_thumbnail($product->get_id(), 'full', array(
            'class' => 'product__image-hover'
        ));
    }
    if (!empty($hover_image)) {
        echo wp_get_attachment_image($hover_image['id'], 'full', false, array(
            'class' => 'product__image-hover'
        ));
    }
    echo '</div>';
}, 99);
