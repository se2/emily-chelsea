<?php

/**
 * starter-theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ttg-wp
 */

if (!function_exists('ttg_wp_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function ttg_wp_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on starter-theme, use a find and replace
		 * to change 'ttg' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('ttg', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');
		add_post_type_support('page', 'excerpt');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'menu-main' => esc_html__('Primary', 'ttg'),
			//'menu-main-mobile' => esc_html__('Primary Mobile', 'ttg'),
		));

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support('html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		));

		// Set up the WordPress core custom background feature.
		add_theme_support('custom-background', apply_filters('wp_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)));

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support('custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		));
		add_theme_support('woocommerce');
		//add_theme_support('wc-product-gallery-zoom');
		//add_theme_support('wc-product-gallery-lightbox');
		//add_theme_support('wc-product-gallery-slider');
	}
endif;
add_action('after_setup_theme', 'ttg_wp_setup');

add_filter('the_content', function ($content) {
	$content = force_balance_tags($content);
	return preg_replace('/<p>(?:\s|&nbsp;)*?<\/p>/i', '<p class="empty">&nbsp;</p>', $content);
}, 10, 1);

function remove_css_js_version($src)
{
	if (strpos($src, '?ver='))
		$src = remove_query_arg('ver', $src);
	return $src;
}
//add_filter('style_loader_src', 'remove_css_js_version', 9999);
//add_filter('script_loader_src', 'remove_css_js_version', 9999);
