<?php

/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined('ABSPATH') || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if (!function_exists('wc_get_gallery_image_html')) {
	return;
}

global $product;

$columns           = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
		'woocommerce-product-gallery--columns-' . absint($columns),
		'images',
	)
);
$video_gallery = get_field('product_video_gallery', $product->get_id());
$attachments = [];

if (!empty($post_thumbnail_id)) {
	$attachments[] = [
		'id' => $post_thumbnail_id,
		'type' => 'image'
	];
}

if (!empty($video_gallery)) {
	foreach ($video_gallery as $key => $value) {
		$attachments[] = [
			'id' => !empty($value['poster']['id']) ? $value['poster']['id'] : '',
			'type' => 'video',
			'data' => $value,
			'index' => $key
		];
	}
}
?>
<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>" data-columns="<?php echo esc_attr($columns); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
	<div class="woocommerce-product-gallery__wrapper">
		<?php
		if (empty($attachments)) {
			$html  = '<div id="woocommerce-product-gallery__image-main">';
			$html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src('woocommerce_single')), esc_html__('Awaiting product image', 'woocommerce'));
			$html .= '</div>';
		}

		if ($attachments) {
			$html = '<div id="woocommerce-product-gallery__image-main">';
			foreach ($attachments as $key => $value) {
				$type = $value['type'];
				if ($type === 'image') {
					$html .= wc_get_gallery_image_html($post_thumbnail_id, true);
				} else {
					$html .= TTG_Template::get_template_part('woo-product-gallery-video', [
						'value' => $value,
						'has_thumb' => !empty($post_thumbnail_id)
					]);
				}
			}
			$html .= '</div>';
		} else {
		}

		echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

		//do_action('woocommerce_product_thumbnails');
		?>
	</div>
</div>