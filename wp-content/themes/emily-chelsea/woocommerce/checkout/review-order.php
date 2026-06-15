<?php

/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

use TTG\Build_Ring\Controller;

defined('ABSPATH') || exit;
?>
<table class="shop_table woocommerce-checkout-review-order-table">
	<thead>
		<tr>
			<th class="product-thumbnail"></th>
			<th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
			<th class="product-total"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		do_action('woocommerce_review_order_before_cart_contents');

		$raw_cart = Controller::sort_cart(WC()->cart->get_cart());

		// Group finished designs by uuid; keep standalone items separate
		$design_groups    = [];
		$standalone_items = [];

		foreach ($raw_cart as $cart_item_key => $cart_item) {
			$is_finish = !empty($cart_item['is_finish_design']);
			$uuid      = $cart_item['uuid'] ?? '';

			if ($is_finish && $uuid) {
				if (!isset($design_groups[$uuid])) {
					$design_groups[$uuid] = ['ring' => null, 'stone' => null];
				}
				if (!empty($cart_item['is_ring'])) {
					$design_groups[$uuid]['ring'] = ['key' => $cart_item_key, 'item' => $cart_item];
				} else {
					$design_groups[$uuid]['stone'] = ['key' => $cart_item_key, 'item' => $cart_item];
				}
			} else {
				$standalone_items[$cart_item_key] = $cart_item;
			}
		}

		// --- Finished designs: one row per design ---
		foreach ($design_groups as $uuid => $group) {
			$ring_key   = $group['ring']['key']   ?? '';
			$ring_item  = $group['ring']['item']  ?? null;
			$stone_key  = $group['stone']['key']  ?? '';
			$stone_item = $group['stone']['item'] ?? null;

			if (!$ring_item || !$stone_item) continue;

			$ring_product  = apply_filters('woocommerce_cart_item_product', $ring_item['data'],  $ring_item,  $ring_key);
			$stone_product = apply_filters('woocommerce_cart_item_product', $stone_item['data'], $stone_item, $stone_key);

			if (!$ring_product || !$ring_product->exists()) continue;
			if (!apply_filters('woocommerce_checkout_cart_item_visible', true, $ring_item, $ring_key)) continue;

			$ring_permalink  = $ring_product->is_visible()  ? $ring_product->get_permalink($ring_item)   : '';
			$stone_permalink = $stone_product->is_visible() ? $stone_product->get_permalink($stone_item) : '';
		?>
			<tr class="cart_item design-group-row" data-uuid="<?php echo esc_attr($uuid); ?>">
				<td class="product-thumbnail">
					<div class="design-pair">
						<div class="design-pair__item">
							<?php
							$ring_thumb = apply_filters('woocommerce_cart_item_thumbnail', $ring_product->get_image('woocommerce_thumbnail'), $ring_item, $ring_key);
							echo $ring_permalink ? '<a href="' . esc_url($ring_permalink) . '">' . $ring_thumb . '</a>' : $ring_thumb; // phpcs:ignore
							?>
							<button class="build-ring-collection__change" data-mode="START_WITH_SETTING" data-uuid="<?php echo esc_attr($uuid); ?>">Change Setting</button>
						</div>
						<div class="design-pair__item">
							<?php
							$stone_thumb = apply_filters('woocommerce_cart_item_thumbnail', $stone_product->get_image('woocommerce_thumbnail'), $stone_item, $stone_key);
							echo $stone_permalink ? '<a href="' . esc_url($stone_permalink) . '">' . $stone_thumb . '</a>' : $stone_thumb; // phpcs:ignore
							?>
							<button class="build-ring-collection__change" data-mode="START_WITH_STONE" data-uuid="<?php echo esc_attr($uuid); ?>">Change Stone</button>
						</div>
					</div>
				</td>
				<td class="product-name">
					<div class="design-pair">
						<div class="design-pair__item">
							<?php
							$ring_name = apply_filters('woocommerce_cart_item_name', $ring_product->get_name(), $ring_item, $ring_key);
							echo wp_kses_post($ring_permalink ? sprintf('<a href="%s">%s</a>', esc_url($ring_permalink), $ring_product->get_name()) : $ring_name);
							echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">&times;&nbsp;' . $ring_item['quantity'] . '</strong>', $ring_item, $ring_key); // phpcs:ignore
							echo wc_get_formatted_cart_item_data($ring_item); // phpcs:ignore
							?>
						</div>
						<div class="design-pair__item">
							<?php
							$stone_name = apply_filters('woocommerce_cart_item_name', $stone_product->get_name(), $stone_item, $stone_key);
							echo wp_kses_post($stone_permalink ? sprintf('<a href="%s">%s</a>', esc_url($stone_permalink), $stone_product->get_name()) : $stone_name);
							echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">&times;&nbsp;' . $stone_item['quantity'] . '</strong>', $stone_item, $stone_key); // phpcs:ignore
							echo wc_get_formatted_cart_item_data($stone_item); // phpcs:ignore
							?>
						</div>
					</div>
				</td>
				<td class="product-total">
					<?php
					$total = ($ring_product->get_price() * $ring_item['quantity']) + ($stone_product->get_price() * $stone_item['quantity']);
					echo wc_price($total); // phpcs:ignore
					?>
				</td>
			</tr>
		<?php
		}

		// --- Standalone items ---
		foreach ($standalone_items as $cart_item_key => $cart_item) {
			$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

			if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0 || !apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) continue;

			$product_permalink = $_product->is_visible() ? $_product->get_permalink($cart_item) : '';
		?>
			<tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
				<td class="product-thumbnail">
					<?php
					$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);
					echo $product_permalink ? '<a href="' . esc_url($product_permalink) . '">' . $thumbnail . '</a>' : $thumbnail; // phpcs:ignore
					?>
				</td>
				<td class="product-name">
					<?php
					echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) . '&nbsp;';
					echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">&times;&nbsp;' . $cart_item['quantity'] . '</strong>', $cart_item, $cart_item_key); // phpcs:ignore
					echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore
					?>
				</td>
				<td class="product-total">
					<?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore ?>
				</td>
			</tr>
		<?php
		}

		do_action('woocommerce_review_order_after_cart_contents');
		?>
	</tbody>
	<tfoot>

		<tr class="cart-subtotal">
			<th><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
				<th><?php wc_cart_totals_coupon_label($coupon); ?></th>
				<td><?php wc_cart_totals_coupon_html($coupon); ?></td>
			</tr>
		<?php endforeach; ?>

		<tr class="shipping-fee" style="text-align: right;">
			<th style="text-align: right;">Shipping</th>
			<td>
				<?php
				$shipping_total = WC()->cart->get_shipping_total();
				echo wc_price($shipping_total);
				?>
			</td>
		</tr>


		<?php foreach (WC()->cart->get_fees() as $fee) : ?>
			<tr class="fee" style="text-align: right;">
				<th style="text-align: right;"><?php echo esc_html($fee->name); ?></th>
				<td><?php wc_cart_totals_fee_html($fee); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
			<?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
				<?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				?>
					<tr style="text-align: right;" class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
						<th style="text-align: right;"><?php echo esc_html($tax->label); ?></th>
						<td><?php echo wp_kses_post($tax->formatted_amount); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total" style="text-align: right;">
					<th style="text-align: right;"><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
					<td><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action('woocommerce_review_order_before_order_total'); ?>

		<tr class="order-total">
			<th><?php esc_html_e('Total', 'woocommerce'); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action('woocommerce_review_order_after_order_total'); ?>

	</tfoot>
</table>
