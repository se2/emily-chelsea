<?php
extract($args);
if ($product_id > 0) {
    $hover_image = get_field('hover_feature_image', $product_id);
    $product = wc_get_product($product_id);
?>
    <div class="build-ring-collection-proudct">
        <div class="build-ring-collection-proudct__inner">
            <div class="build-ring-collection-proudct__image">
                <?php
                echo get_the_post_thumbnail($product_id, 'full', array(
                    'class' => 'product__image'
                ));
                ?>
            </div>
            <div class="build-ring-collection-proudct__info">
                <h4 class="build-ring-collection-proudct__info__type">
                    <?php echo $type; ?>
                </h4>
                <h3 class="build-ring-collection-proudct__info__title">
                    <?php echo get_the_title($product_id); ?></h3>
                <div class="build-ring-collection-proudct__info__price">
                    <?php echo $product->get_price_html(); ?>
                </div>
                <div class="build-ring-collection-proudct__info__attrs">
                    <?php
                    if (!empty($cart_line_item)) {
                        $cart_item = WC()->cart->get_cart_item($cart_line_item);
                        echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
