<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}
?>
<?php
remove_action('main-content-top', 'woocommerce_breadcrumb', 10);
echo TTG_Build_Ring::steps();
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('product-detail', $product); ?>>

    <div class="product-detail__info">
        <div class="product-detail__info__left">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action('woocommerce_before_single_product_summary');
            ?>
            <?php
            do_action('woocommerce_product_thumbnails');
            ?>
        </div>
        <?php
        $attrs = [
            'class' => ['product-detail__info__right']
        ];

        if (TTG_Build_Ring::is_ring_page()) {
            $attrs['class'][] = 'is-ring-summary';
        }

        if (TTG_Build_Ring::is_stone_page()) {
            $attrs['class'][] = 'is-stone-summary';
        }

        ?>
        <div <?php echo TTG_Util::generate_html_attrs($attrs) ?>>
            <div class="summary entry-summary">
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
                //do_action('woocommerce_single_product_summary');

                add_filter('woocommerce_short_description', function () {
                    global $post;
                    return $post->post_excerpt;
                });

                woocommerce_template_single_title();
                woocommerce_template_single_rating();
                woocommerce_template_single_price();

                echo '<div class="summary-spacing">';
                echo  TTG_Template::get_template_part('stone-warning', ['product' => $product]);
                echo '</div>';

                woocommerce_template_single_excerpt();
                echo wc_get_stock_html($product); // WPCS: XSS ok.

                echo TTG_Template::get_template_part('product-service');

                woocommerce_template_single_add_to_cart();

                if (TTG_Build_Ring::is_ring_page()) {
                    echo TTG_Template::get_template_part('build-ring-anchors');
                }

                if (TTG_Build_Ring::is_stone_page()) {
                    echo TTG_Template::get_template_part('build-stone-anchors');
                    add_filter("woocommerce_product_related_products_heading", function () {
                        return "More Stones to Consider:";
                    });
                }


                ?>
            </div>
        </div>
        <div class="product-detail__info__line"></div>

    </div>
    <?php
    if (TTG_Build_Ring::is_ring_page()) {
        echo TTG_Template::render("build-ring-content", [
            "product_id" => $product->get_id()
        ]);
    }
    ?>


    <?php
    /**
     * Hook: woocommerce_after_single_product_summary.
     *
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action('woocommerce_after_single_product_summary');
    ?>
</div>

<?php do_action('woocommerce_after_single_product'); ?>
<?php
echo TTG_Build_Ring::modal($product->get_id());
?>