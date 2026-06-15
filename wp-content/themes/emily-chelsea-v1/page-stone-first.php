<?php

/**
 * Template Name: Stone First
 */
?>
<?php
TTG_Build_Ring::set_nav(TTG_Build_Ring::$STONE_FRIST);
get_header();
$step = TTG_Build_Ring::get_step();
?>

<div class="build-rings-step-<?php echo $step ?>">
    <?php
    echo TTG_Build_Ring::steps();
    ?>
    <?php
    if ($step != 3) {
        echo TTG_Template::get_template_part('products-filter');
    }
    ?>
    <article class="post page-article">
        <?php
        if ($step != 3) {
            $terms = 'center-stones';
            $meta_query = [
                [
                    'key'       => '_stock_status',
                    'value'     => 'outofstock',
                    'compare'   => 'NOT IN'
                ]
            ];
            if ($step == 2) {
                $terms = 'rings';
            }

            $products = new WP_Query(array(
                'post_type' => 'product',
                'tax_query' => [
                    [
                        'taxonomy' => 'product_cat',
                        "field" => "slug",
                        "terms" => $terms
                    ]
                ],
                "meta_query" =>  $meta_query,
            ));
        ?>
            <ul class="products columns-4 facetwp-template <?php echo $terms ?>-list">
                <?php
                while ($products->have_posts()) {
                    $products->the_post();

                    /**
                     * Hook: woocommerce_shop_loop.
                     */
                    do_action('woocommerce_shop_loop');

                    wc_get_template_part('content', 'product');
                }
                ?>
            </ul>
        <?php
        }
        ?>

        <?php
        if ($step == 3) {
            echo TTG_Template::render('build-ring-confirm');
        }
        ?>
    </article>
</div>


<?php get_footer() ?>