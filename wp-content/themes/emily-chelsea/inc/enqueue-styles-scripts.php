<?php

/**
 * Enqueue scripts and styles.
 */
function ttg_wp_scripts()
{

    // Slick carousel
    // @link http://kenwheeler.github.io/slick/
    // wp_enqueue_style( 'slick', get_template_directory_uri() . '/js/slick-1.8.1/slick.css' );
    // wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/js/slick-1.8.1/slick-theme.css' );
    // wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick-1.8.1/slick.min.js', ['jquery'] );
    // wp_enqueue_style( 'uikit', get_template_directory_uri() . '/css/uikit-min.css' );

    wp_dequeue_style('woocommerce-general');
    wp_dequeue_style('woocommerce-layout');
    wp_dequeue_style('woocommerce-smallscreen');
    wp_dequeue_style('wc-blocks-vendors-style');
    wp_dequeue_style('wc-all-blocks-style');
    wp_dequeue_style('buttons');

    wp_enqueue_style('single-post-heading', get_template_directory_uri() . '/src/dist/css/components/single-post-heading.css');
    wp_enqueue_style('base', get_template_directory_uri() . '/src/dist/css/base.min.css');

    if (!is_admin()) {
        wp_enqueue_style('ttg-front-end', get_template_directory_uri() . '/src/dist/css/components/only-fe.css');
    }

    wp_enqueue_script('ttg-common', get_template_directory_uri() . '/src/dist/js/components/common.js', [], time(), true);
    wp_localize_script('ttg-common', 'jsData', array(
        'ajaxUrl' => admin_url('admin-ajax.php')
    ));
    wp_enqueue_script('header', get_template_directory_uri() . '/src/dist/js/components/header.js', [], false, true);


    if (class_exists('TTG_Blocks_Utils')) {
        wp_enqueue_style('ttg-media', TTG_Blocks_Utils::get_assets_url('dist/css/components/media.css'));
        wp_enqueue_script('ttg-media', TTG_Blocks_Utils::get_assets_url('dist/js/components/media.js'), [], false, true);
    }


    if (is_tax() || is_post_type_archive('product') || is_shop() || is_page_template('page-special-products.php')) {
        wp_enqueue_style('custom-facetwp-facet', get_template_directory_uri() . '/src/dist/css/components/facetwp-facet.css');
        wp_enqueue_style('custom-woocommerce-pagination', get_template_directory_uri() . '/src/dist/css/components/woocommerce-pagination.css');
        wp_enqueue_style('custom-breadcrumb', get_template_directory_uri() . '/src/dist/css/components/woocommerce-breadcrumb.css');
        wp_enqueue_style('custom-products-header', get_template_directory_uri() . '/src/dist/css/components/woocommerce-products-header.css');
        wp_enqueue_style('custom-products-filter', get_template_directory_uri() . '/src/dist/css/components/products-filter.css');
        wp_enqueue_style('custom-products', get_template_directory_uri() . '/src/dist/css/components/products.css');

        wp_enqueue_script('sticky-kit', get_template_directory_uri() . '/src/libs/jquery.sticky-kit.min.js', [], false, true);
        wp_enqueue_script('products-page', get_template_directory_uri() . '/src/dist/js/components/products-page.js', [], false, true);
    }

    if (is_singular('product')) {
        wp_enqueue_style('product-services', get_template_directory_uri() . '/src/dist/css/components/product-services.css');
        wp_enqueue_style('ttg-buttons', get_template_directory_uri() . '/src/dist/css/components/buttons.css');
        wp_enqueue_style('custom-breadcrumb', get_template_directory_uri() . '/src/dist/css/components/woocommerce-breadcrumb.css');
        wp_enqueue_style('custom-products', get_template_directory_uri() . '/src/dist/css/components/products.css');
        wp_enqueue_style('quantity', get_template_directory_uri() . '/src/dist/css/components/quantity.css');
        wp_enqueue_style('slick-slider', get_template_directory_uri() . '/src/libs/slick/slick.css');
        wp_enqueue_style('slick-slider', get_template_directory_uri() . '/src/libs/slick/slick-theme.css');

        wp_enqueue_style('single-product', get_template_directory_uri() . '/src/dist/css/pages/single-product.css');


        wp_enqueue_script('isotope', get_template_directory_uri() . '/src/libs/isotope.min.js', [], false, true);
        wp_enqueue_script('slick-slider', get_template_directory_uri() . '/src/libs/slick/slick.min.js', [], false, true);
        wp_enqueue_script('quantity', get_template_directory_uri() . '/src/dist/js/components/quantity.js', [], false, true);
        wp_enqueue_script('single-product', get_template_directory_uri() . '/src/dist/js/components/single-product.js', [], false, true);
    }

    if (is_post_type_archive('post') || is_home()) {
        wp_enqueue_style('custom-woocommerce-pagination', get_template_directory_uri() . '/src/dist/css/components/woocommerce-pagination.css');
        wp_enqueue_style('custom-facetwp-facet', get_template_directory_uri() . '/src/dist/css/components/facetwp-facet.css');
        wp_enqueue_style('ttg-buttons', get_template_directory_uri() . '/src/dist/css/components/buttons.css');
        wp_enqueue_style('post-feature-item', get_template_directory_uri() . '/src/dist/css/components/post-feature-item.css');
        wp_enqueue_style('blog-list', get_template_directory_uri() . '/src/dist/css/components/blog-list.css');
        wp_enqueue_style('blog', get_template_directory_uri() . '/src/dist/css/pages/blog.css');
    }

    if (is_singular('post')) {
        wp_enqueue_style('blog-list', get_template_directory_uri() . '/src/dist/css/components/blog-list.css');
        wp_enqueue_style('single-post', get_template_directory_uri() . '/src/dist/css/pages/single.css');
    }

    if (is_page()) {
        wp_enqueue_style('custom-breadcrumb', get_template_directory_uri() . '/src/dist/css/components/woocommerce-breadcrumb.css');
        wp_enqueue_style('page', get_template_directory_uri() . '/src/dist/css/pages/page.css');
    }

    if (is_cart()) {
        wp_enqueue_style('quantity', get_template_directory_uri() . '/src/dist/css/components/quantity.css');
        wp_enqueue_style('shop-table', get_template_directory_uri() . '/src/dist/css/components/shop-table.css');
        wp_enqueue_style('cart', get_template_directory_uri() . '/src/dist/css/components/cart.css');
        wp_enqueue_style('page-cart', get_template_directory_uri() . '/src/dist/css/pages/cart.css');

        wp_enqueue_script('quantity', get_template_directory_uri() . '/src/dist/js/components/quantity.js', [], false, true);
    }

    if (is_checkout()) {
        wp_enqueue_style('shop-table', get_template_directory_uri() . '/src/dist/css/components/shop-table.css');
        wp_enqueue_style('checkout', get_template_directory_uri() . '/src/dist/css/pages/checkout.css');

        wp_enqueue_script('sticky-kit', get_template_directory_uri() . '/src/libs/jquery.sticky-kit.min.js', [], false, true);
        wp_enqueue_script('checkout', get_template_directory_uri() . '/src/dist/js/pages/checkout.js', ['sticky-kit', 'jquery'], false, true);
    }

    if (is_account_page()) {
        wp_enqueue_style('shop-table', get_template_directory_uri() . '/src/dist/css/components/shop-table.css');
        wp_enqueue_style('ttg-buttons', get_template_directory_uri() . '/src/dist/css/components/buttons.css');
        wp_enqueue_style('login-form', get_template_directory_uri() . '/src/dist/css/components/login-form.css');
        wp_enqueue_style('my-account', get_template_directory_uri() . '/src/dist/css/pages/my-account.css');
    }

    if (is_search()) {
        wp_enqueue_style('custom-woocommerce-pagination', get_template_directory_uri() . '/src/dist/css/components/woocommerce-pagination.css');
        wp_enqueue_style('blog-list', get_template_directory_uri() . '/src/dist/css/components/blog-list.css');
        wp_enqueue_style('search-page', get_template_directory_uri() . '/src/dist/css/pages/search.css');
    }
}
add_action('wp_enqueue_scripts', 'ttg_wp_scripts', 9999);

// remove all block styles
add_action('wp_enqueue_scripts', function () {
    if (!is_singular('post') && !is_page()) {
        global $wp_styles;
        foreach ($wp_styles->queue as $key => $handle) {
            if (strpos($handle, 'wp-block-') === 0) {
                wp_dequeue_style($handle);
            }
        }
    }
}, 999);


// clean up header
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('wp_head', 'wp_oembed_add_host_js');
remove_action('rest_api_init', 'wp_oembed_register_route');
remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
