<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
// error_reporting(E_ALL);

require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

/**
 * Template Name: Page Test
 * */
?>
<?php // get_header();
?>
<?php

$args = array(
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    // 'offset'         => (1000 * 13),
    // 'meta_query' => array(
    //     array(
    //         'key' => '_stock_status',
    //         'value' => 'instock',
    //         'compare' => '=',
    //     )
    // ),
    'tax_query'        => array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug', //This is optional, as it defaults to 'term_id'
            // 'terms'    => array('earrings_diamond', 'earrings_solitaire', 'earrings_studs'),
            'terms'    => array('earrings', 'necklaces'),
            'operator' => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
        )
    )
    // 'meta_query'     => array(
    //     array(
    //         'key'   => '_sku',
    //         'value' => $skus,
    //         'compare' => 'IN'
    //     )
    // )
);
$products = get_posts($args);

?>
<div class="head-spacer"></div>
<div class="container-content">
    <?php
    foreach ($products as $key => $p) {
        $post_id = $p->ID;
        $product = wc_get_product($post_id);
        if ($product && $product->is_type('variable')) {
            $variations = $product->get_children(); // Get all variation IDs
            foreach ($variations as $variation_id) {
                $variation = wc_get_product($variation_id); // Get the variation object
                
                if (!$variation->get_manage_stock() || $variation->get_backorders() === 'no') {                    
                    echo $variation->get_title() . '<br>';
                    // Update stock management and backorder settings
                    $variation->set_manage_stock(true);        // Enable stock management
                    $variation->set_backorders('yes');         // Allow backorders ('no', 'notify', 'yes')

                    // Save the updated variation
                    // $variation->save();
                }
            }
        }
    }
    ?>
</div>
<?php // get_footer();
?>