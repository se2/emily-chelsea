<?php

/**
 * Shop breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/breadcrumb.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if (!defined('ABSPATH')) {
	exit;
}


if (is_singular('product')) {
	global $post;
	$is_special_product = get_field('is_special_product', $post->ID);
	$custom_products_page = get_field("custom_products_page", "options");
	$title = '';
	$link = "";

	if (!empty($custom_products_page)) {
		$title = $custom_products_page->post_title;
		$link = get_permalink($custom_products_page->ID);
	}

	if ($is_special_product) {
		$shop_id = wc_get_page_id('shop');
		$shop_url = get_permalink($shop_id);
		$shop_title = get_the_title($shop_id);
		$breadcrumb = [
			array($shop_title, $shop_url),
			array($title, $link),
			array($post->post_title, '')
		];
	}
} else if (is_page_template('page-special-products.php')) {
	$shop_id = wc_get_page_id('shop');
	$shop_url = get_permalink($shop_id);
	$shop_title = get_the_title($shop_id);
	$breadcrumb = [
		array($shop_title, $shop_url),
		array('Special Products', ''),
	];
}

if (!empty($breadcrumb)) {
	echo $wrap_before;
	foreach ($breadcrumb as $key => $crumb) {

		echo $before;

		if (!empty($crumb[1]) && sizeof($breadcrumb) !== $key + 1) {
			echo '<a href="' . esc_url($crumb[1]) . '">' . esc_html($crumb[0]) . '</a>';
		} else {
			echo esc_html($crumb[0]);
		}

		echo $after;

		if (sizeof($breadcrumb) !== $key + 1) {
			echo '<span class="delimiter">//</span>';
		}
	}

	echo $wrap_after;
}
