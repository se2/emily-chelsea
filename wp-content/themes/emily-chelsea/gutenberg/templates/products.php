<?php
$products_block_number_items = get_field('products_block_number_items');
$products_block_type = get_field('products_block_type');
$products_block_filter = get_field('products_block_filter');
$products_block_products = get_field('products_block_products');
$products_block_columns = get_field('products_block_columns');
$products_block_pc = get_field('products_block_pc');
$products_block_tablet = get_field('products_block_tablet');
$products_block_mobile = get_field('products_block_mobile');

$items = [];

if ($products_block_type === 'select') {
    $items = $products_block_products;
} else {
    $config = array(
        'post_type' => 'product',
        'posts_per_page' => $products_block_number_items
    );

    if (!empty($products_block_filter)) {
        $config['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'term' => $products_block_filter
            )
        );
    }

    $items = get_posts($config);
}

$attrs = [
    'class' => ['ttg-block-products', $default_class],
    'id' => $default_id,
    'style' => [
        '--ttg-block-products-font-size-heading-pc' => $products_block_pc['heading_font_size'],
        '--ttg-block-products-font-size-heading-tablet' => $products_block_tablet['heading_font_size'],
        '--ttg-block-products-font-size-heading-m' => $products_block_mobile['heading_font_size'],

        '--ttg-block-products-font-size-price-pc' => $products_block_pc['price_font_size'],
        '--ttg-block-products-font-size-price-tablet' => $products_block_tablet['price_font_size'],
        '--ttg-block-products-font-size-price-m' => $products_block_mobile['price_font_size'],

        '--ttg-block-products-font-size-meta-pc' => $products_block_pc['meta_font_size'],
        '--ttg-block-products-font-size-meta-tablet' => $products_block_tablet['meta_font_size'],
        '--ttg-block-products-font-size-meta-m' => $products_block_mobile['meta_font_size'],
    ]
];

?>
<?php
if (!empty($items)) {
?>
    <div <?php echo TTG_Util::generate_html_attrs($attrs) ?>>
        <ul class="products columns-<?php echo $products_block_columns; ?>">
            <?php
            global $post;
            $old_product = $product;
            foreach ($items as $key => $post) {
                setup_postdata($post);
                do_action('woocommerce_shop_loop');
                wc_get_template_part('content', 'product');
            }
            wp_reset_postdata();
            ?>
        </ul>
    </div>
<?php
}
?>