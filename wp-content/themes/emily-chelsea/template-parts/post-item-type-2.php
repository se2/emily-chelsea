<?php
extract($args);
if (!empty($post)) {
    $term = TTG_Util::get_main_term($post->ID);
    $is_product = $post->post_type == 'product' ? true : false;
    $label = '';
    if ($is_product) {
<<<<<<< HEAD
        $stock_status = $is_product ? get_post_meta($post->ID, '_stock_status', true) : null;
        $is_special_product = get_post_meta($post->ID, 'is_special_product', true);
        $label = $stock_status != 'outofstock' || $is_special_product ? '' : '<span class="stock-status stock-status--out-of-stock">Out of stock</span>';
=======
        $product = wc_get_product($post->ID);
        $label = '';
        if ($product && $product->is_type('simple') && !$product->is_in_stock()) {
            $label = '<span class="stock-status stock-status--out-of-stock">Out of stock</span>';
        }
>>>>>>> build-ring
    }

?>
    <div class="post-item post-item--style-2">
        <div class="post-item__inner">
            <a class="post-item__link" href="<?php echo get_the_permalink($post->ID) ?>">
                <div class="post-item__thumbnail">
                    <?php echo get_the_post_thumbnail($post->ID, 'full') ?>
                </div>
                <h2 class="heading-xsmall post-item__title">
                    <?php echo $post->post_title; ?>
                </h2>
                <div class="ttg-post post-item__desc">
                    <?php echo wp_trim_words(get_the_excerpt($post), 15); ?>
                </div>
            </a>
            <?php echo $label; ?>
            <div class="post-item__line"></div>
        </div>
    </div>
<?php
}
