<?php
$products_block_number_items = get_field('products_block_number_items');
$products_block_columns = get_field('products_block_columns');
$products = new WP_Query(array(
    'post_type' => 'product',
    'posts_per_page' => $products_block_number_items,
    'meta_query' => array(
        array(
            'key' => 'is_special_product',
            'value' => "1",
        ),
    ),
    "facetwp"        => true
))
?>
<div class="products-special">
    <ul class="products columns-<?php echo $products_block_columns; ?> facetwp-template">
        <?php
        if ($products->have_posts()) {
            while ($products->have_posts()) {
                $products->the_post();
                do_action('woocommerce_shop_loop');
                wc_get_template_part('content', 'product');
            }
        }
        ?>
    </ul>
    <nav class="woocommerce-pagination">
        <?php
        echo do_shortcode('[facetwp facet="load_more"]');
        echo do_shortcode('[facetwp facet="result_count"]');
        ?>
    </nav>
</div>