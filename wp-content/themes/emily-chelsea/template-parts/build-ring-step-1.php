<?php

use TTG\Build_Ring\Controller;
use TTG\Build_Ring\MODE;

add_filter('woocommerce_loop_product_link', function ($post_link, $post) {
    return $post_link .= '?mode=build-ring';;
}, 10, 2);
?>
<div class="page-build-ring__inner">
    <div class="page-build-ring__left">
        <?php echo TTG_Template::render("build-ring-filter") ?>
    </div>
    <div class="page-build-ring__center">
        <?php
        echo TTG_Template::render('build-ring-mode');
        echo TTG_Template::render('build-ring-head')
        ?>
        <?php
        $terms = 'rings';
        $mode = Controller::get_mode();
        $meta_query = [
            [
                'key'       => '_stock_status',
                'value'     => 'outofstock',
                'compare'   => 'NOT IN'
            ]
        ];
        if ($mode == MODE::START_WITH_STONE) {
            $terms = 'center-stones';
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
            "facetwp"        => true,
            "posts_per_page" => 24,
        ));
        ?>
        <ul class="products columns-3 facetwp-template <?php echo $terms ?>-list">
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
        <nav class="woocommerce-pagination">
            <?php
            echo do_shortcode('[facetwp facet="pagination"]');
            echo do_shortcode('[facetwp facet="result_count"]');
            ?>
        </nav>
    </div>
    <div class="page-build-ring__right" id="build-ring-tray-slots">
        <div class="build-ring-tray-collection">
            <?php echo TTG_Template::render("build-ring-mini-collection") ?>
            <?php echo TTG_Template::render("build-ring-tray") ?>
        </div>

    </div>
</div>