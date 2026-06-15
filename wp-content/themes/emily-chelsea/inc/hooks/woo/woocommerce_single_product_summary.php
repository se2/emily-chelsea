<?php

use TTG\Build_Ring\Controller;

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
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

add_action('woocommerce_single_product_summary', function () {
    global $product;
    $stock_status = $product->get_stock_status();
    if ($stock_status != 'outofstock') {
?>
        <style>
            .available-on-backorder {
                display: none;
            }
        </style>
<<<<<<< HEAD
    <?php
=======
<?php
>>>>>>> build-ring
    }
});

add_action('woocommerce_single_product_summary', function () {
    global $post;
    global $product;
    $anchor_title = get_field('anchor_title', $post->ID);
    $stock_label = '';

    if (!$product->is_in_stock()) {
        $stock_label = '<div class="summary-spacing" style="color:#fa5160; margin-bottom:20px"><span class="stock-status stock-status--out-of-stock" >Out of stock</span></div>';
    }

<<<<<<< HEAD
    echo $stock_label;

    echo '<div class="summary-spacing">' . TTG_Template::get_template_part('add-stone-button', ['product' => $product]) . '</div>';
=======
    if (Controller::is_building_ring()) {
        echo $stock_label;

        if (!empty($anchor_title)) {
            echo '<a href="#product-gallery" class="anchor-gallery">' . $anchor_title . '</a>';
        }

        echo TTG_Template::get_template_part('product-service');

        return;
    }

    echo $stock_label;

    if (!Controller::is_building_ring()) {
        echo '<div class="summary-spacing">' . TTG_Template::get_template_part('add-stone-button', ['product' => $product]) . '</div>';
    }
>>>>>>> build-ring

    if (!empty($anchor_title)) {
        echo '<a href="#product-gallery" class="anchor-gallery">' . $anchor_title . '</a>';
    }

    echo TTG_Template::get_template_part('product-service');
}, 21);


add_action('woocommerce_single_product_summary', function () {

    if (Controller::is_building_ring()) {
        return;
    }

    global $product;
    $is_special_product = get_field('is_special_product', $product->get_id());

    if ($is_special_product) {
        echo TTG_Template::get_template_part('product-custom-buttons');
    }
    if (!$product->is_in_stock() && !$is_special_product && $product->is_type('simple')) {
<<<<<<< HEAD
    ?>
        <div class="cart"></div>
        <div class="product-line"></div>
<?php
=======
        echo "<div class=\"cart\"></div>";
        echo '<div class="product-line"></div>';
>>>>>>> build-ring
        echo TTG_Template::get_template_part('inquire-button');
    }
}, 31);
