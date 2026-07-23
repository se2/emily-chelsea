<?php

use TTG\Build_Ring\PRODUCT_TYPES;
use TTG\Build_Ring\MODE;
use TTG\Build_Ring\Controller;

global $product;
add_filter('woocommerce_product_single_add_to_cart_text', function () {
    global $product;
    if (has_term(PRODUCT_TYPES::RING, 'product_cat', $product->get_id())) {
        return __('CHOOSE THIS SETTING', 'woocommerce');
    }

    if (has_term(PRODUCT_TYPES::STONE, 'product_cat', $product->get_id())) {
        return __('ADD STONE TO TRAY', 'woocommerce');
    }

    return __('CHOOSE THIS SETTING', 'woocommerce');
}, 10, 1);

add_action('woocommerce_before_add_to_cart_button', function () {
    global $product;
    $stone = Controller::get_stone();
    if (has_term(PRODUCT_TYPES::RING, 'product_cat', $product->get_id()) && empty($stone)) {
        echo TTG_Template::get_template_part('build-ring-stone-options', ['product_id' => $product->get_id()]);
    }
}, 10, 1);

add_action('woocommerce_after_add_to_cart_button', function () {
    global $product;
    $product_type = has_term(PRODUCT_TYPES::STONE, 'product_cat', $product->get_id()) ? PRODUCT_TYPES::STONE : PRODUCT_TYPES::RING;
    echo sprintf('<input type="hidden" name="product_type" value="%s" />', $product_type);
    if (has_term(PRODUCT_TYPES::STONE, 'product_cat', $product->get_id())) {
        echo "<div style='width:100%'></div>";
        echo "<a href='/build-ring/' class='btn btn--large btn--solid btn-return-list'>RETURN TO STONES</a>";
    }
}, 10, 1);

// add_action('woocommerce_single_product_summary', function () {
//     global $product;
//     if (has_term(PRODUCT_TYPES::RING, 'product_cat', $product->get_id())) {
//         echo TTG_Template::get_template_part('build-ring-anchors', ['product_id' => $product->get_id()]);
//     }
// }, 35, 1);

remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_single_product_summary', function () {
    global $post;
    $content = apply_filters('the_content', $post->post_content);
    echo '<div class="woocommerce-product-details__short-description">' . $content . '</div>';
}, 20);
?>
<div class="product-detail-build-ring">
    <div class="product-detail-build-ring__inner">
        <div class="product-detail-build-ring__left">
            <?php
            /**
             * Hook: woocommerce_before_single_product.
             *
             * @hooked woocommerce_output_all_notices - 10
             */
            do_action('woocommerce_before_single_product');
            ?>
            <div id="product-<?php the_ID(); ?>" <?php wc_product_class('product-detail', $product); ?>>

                <div class="product-detail__info">
                    <div class="product-detail__info__left">
                        <?php
                        woocommerce_breadcrumb();
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
                    <div class="product-detail__info__right">
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

                            do_action('woocommerce_single_product_summary');
                            ?>
                        </div>
                    </div>
                    <div class="product-detail__info__line"></div>
                </div>

                <?php
                // echo TTG_Template::render("build-ring-content", [
                //     "product_id" => $product->get_id()
                // ]);
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
        </div>
        <div class="product-detail-build-ring__right">
            <div class="build-ring-tray-collection">
                <?php echo TTG_Template::render("build-ring-mini-collection") ?>
                <?php echo TTG_Template::render("build-ring-tray") ?>
            </div>

        </div>
    </div>
</div>