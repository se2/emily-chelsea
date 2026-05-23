<?php

/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

use TTG\Build_Ring\Controller;

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<div class="woocommerce-cart-form-wrapper">

	<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
		<?php do_action('woocommerce_before_cart_table'); ?>
		<?php
		wc_print_notices();
		?>
		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>
					<th class="product-thumbnail"><span class="screen-reader-text"><?php esc_html_e('Thumbnail image', 'woocommerce'); ?></span></th>
					<th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
					<th class="product-price"><?php esc_html_e('Price', 'woocommerce'); ?></th>
					<th class="product-quantity"><?php esc_html_e('Quantity', 'woocommerce'); ?></th>
					<th class="product-subtotal"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
					<th class="product-remove"><span class="screen-reader-text"><?php esc_html_e('Remove item', 'woocommerce'); ?></span></th>
				</tr>
			</thead>
			<tbody>
				<?php do_action('woocommerce_before_cart_contents'); ?>

				<?php
				$raw_cart = Controller::sort_cart(WC()->cart->get_cart());

				// Separate finished designs (group by uuid) from standalone items
				$design_groups = [];
				$standalone_items = [];
				$seen_uuids = [];

				foreach ($raw_cart as $cart_item_key => $cart_item) {
					$is_finish_design = !empty($cart_item['is_finish_design']);
					$uuid             = $cart_item['uuid'] ?? '';

					if ($is_finish_design && $uuid) {
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

				$statues = [
					'instock'     => '<div class="cart-instock-status">In Stock Ready to Ship</div>',
					'outofstock'  => '<div class="cart-outofstock-status">OUT OF STOCK</div>',
					'onbackorder' => '<div class="cart-onbackorder-status">Made to Order</div>',
				];

				// --- Finished design rows (ring + stone in separate <tr> rows) ---
				foreach ($design_groups as $uuid => $group) {
					$ring_key   = $group['ring']['key']   ?? '';
					$ring_item  = $group['ring']['item']  ?? null;
					$stone_key  = $group['stone']['key']  ?? '';
					$stone_item = $group['stone']['item'] ?? null;

					if (!$ring_item || !$stone_item) continue;

					$ring_product  = apply_filters('woocommerce_cart_item_product', $ring_item['data'],  $ring_item,  $ring_key);
					$stone_product = apply_filters('woocommerce_cart_item_product', $stone_item['data'], $stone_item, $stone_key);

					if (!$ring_product || !$ring_product->exists()) continue;

					$ring_permalink  = apply_filters('woocommerce_cart_item_permalink', $ring_product->is_visible()  ? $ring_product->get_permalink($ring_item)   : '', $ring_item,  $ring_key);
					$stone_permalink = apply_filters('woocommerce_cart_item_permalink', $stone_product->is_visible() ? $stone_product->get_permalink($stone_item) : '', $stone_item, $stone_key);

					$ring_product_id = apply_filters('woocommerce_cart_item_product_id', $ring_item['product_id'], $ring_item, $ring_key);

					$min_qty = 1;
					$max_qty = $ring_product->is_sold_individually() ? 1 : $ring_product->get_max_purchase_quantity();

					$ring_subtotal  = $ring_product->get_price()  * $ring_item['quantity'];
					$stone_subtotal = $stone_product->get_price() * $stone_item['quantity'];
				?>
					<!-- Ring row -->
					<tr class="woocommerce-cart-form__cart-item cart_item design-group-row design-group-ring" data-uuid="<?php echo esc_attr($uuid); ?>">
						<td class="product-thumbnail">
							<?php
							$ring_thumb = apply_filters('woocommerce_cart_item_thumbnail', $ring_product->get_image(), $ring_item, $ring_key);
							echo $ring_permalink ? sprintf('<a href="%s">%s</a>', esc_url($ring_permalink), $ring_thumb) : $ring_thumb; // phpcs:ignore
							?>
							<button class="build-ring-collection__change" data-mode="START_WITH_SETTING" data-uuid="<?php echo esc_attr($uuid); ?>">Change Setting</button>
						</td>

						<td class="product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
							<?php
							$ring_name = apply_filters('woocommerce_cart_item_name', $ring_product->get_name(), $ring_item, $ring_key);
							echo wp_kses_post($ring_permalink ? sprintf('<a href="%s">%s</a>', esc_url($ring_permalink), $ring_product->get_name()) : $ring_name);
							echo wc_get_formatted_cart_item_data($ring_item); // phpcs:ignore
							echo $statues[$ring_product->get_stock_status()];
							?>
						</td>

						<td class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
							<?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($ring_product), $ring_item, $ring_key); // phpcs:ignore ?>
						</td>

						<td class="product-quantity" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>" rowspan="2">
							<?php
							echo apply_filters('woocommerce_cart_item_quantity', woocommerce_quantity_input( // phpcs:ignore
								[
									'input_name'   => "cart[{$ring_key}][qty]",
									'input_value'  => $ring_item['quantity'],
									'max_value'    => $max_qty,
									'min_value'    => $min_qty,
									'product_name' => $ring_product->get_name(),
									'readonly'     => true,
								],
								$ring_product,
								false
							), $ring_key, $ring_item);
							?>
						</td>

						<td class="product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>" rowspan="2">
							<?php echo wc_price($ring_subtotal + $stone_subtotal); // phpcs:ignore ?>
						</td>

						<td class="product-remove" rowspan="2">
							<?php
							echo apply_filters( // phpcs:ignore
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a data-uuid="%s" href="#" class="remove remove-design-from-cart" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
									esc_attr($uuid),
									esc_attr(sprintf(__('Remove design from cart', 'woocommerce'))),
									esc_attr($ring_product_id),
									esc_attr($ring_product->get_sku()),
									TTG_Template::get_icon('remove')
								),
								$ring_key
							);
							?>
						</td>
					</tr>

					<!-- Stone row -->
					<tr class="woocommerce-cart-form__cart-item cart_item design-group-row design-group-stone" data-uuid="<?php echo esc_attr($uuid); ?>">
						<td class="product-thumbnail">
							<?php
							$stone_thumb = apply_filters('woocommerce_cart_item_thumbnail', $stone_product->get_image(), $stone_item, $stone_key);
							echo $stone_permalink ? sprintf('<a href="%s">%s</a>', esc_url($stone_permalink), $stone_thumb) : $stone_thumb; // phpcs:ignore
							?>
							<button class="build-ring-collection__change" data-mode="START_WITH_STONE" data-uuid="<?php echo esc_attr($uuid); ?>">Change Stone</button>
						</td>

						<td class="product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
							<?php
							$stone_name = apply_filters('woocommerce_cart_item_name', $stone_product->get_name(), $stone_item, $stone_key);
							echo wp_kses_post($stone_permalink ? sprintf('<a href="%s">%s</a>', esc_url($stone_permalink), $stone_product->get_name()) : $stone_name);
							echo wc_get_formatted_cart_item_data($stone_item); // phpcs:ignore
							echo $statues[$stone_product->get_stock_status()];
							?>
						</td>

						<td class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
							<?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($stone_product), $stone_item, $stone_key); // phpcs:ignore ?>
						</td>
					</tr>
				<?php
				}

				// --- Standalone / tray items ---
				foreach ($standalone_items as $cart_item_key => $cart_item) {
					$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
					$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
					$is_ring    = !empty($cart_item['is_ring']);
					$is_stone   = !empty($cart_item['is_stone']);
					$is_couple  = $is_ring || $is_stone;
					$uuid       = $cart_item['uuid'] ?? '';

					$product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

					if (!$_product || !$_product->exists() || $cart_item['quantity'] <= 0 || !apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) continue;

					$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
				?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
						<td class="product-thumbnail">
							<?php
							$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
							echo $product_permalink ? sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail) : $thumbnail; // phpcs:ignore
							?>
						</td>

						<td class="product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
							<?php
							echo wp_kses_post($product_permalink
								? apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key)
								: $product_name . '&nbsp;'
							);
							do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);
							echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore
							if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
								echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
							}
							echo $statues[$_product->get_stock_status()];
							?>
						</td>

						<td class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
							<?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // phpcs:ignore ?>
						</td>

						<td class="product-quantity" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
							<?php
							$min_qty = $is_couple ? 0 : ($cart_item['quantity']);
							$max_qty = $_product->is_sold_individually() ? 1 : $_product->get_max_purchase_quantity();
							echo apply_filters('woocommerce_cart_item_quantity', woocommerce_quantity_input( // phpcs:ignore
								[
									'input_name'   => "cart[{$cart_item_key}][qty]",
									'input_value'  => $cart_item['quantity'],
									'max_value'    => $max_qty,
									'min_value'    => 0,
									'product_name' => $product_name,
								],
								$_product,
								false
							), $cart_item_key, $cart_item);
							?>
						</td>

						<td class="product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
							<?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore ?>
						</td>

						<td class="product-remove">
							<?php
							if ($is_couple) {
								$tray_item = TTG\Build_Ring\Controller::get_tray_item_by_cart_line_item($cart_item_key);
								$tray_uuid = $tray_item['uuid'] ?? '';
								echo apply_filters('woocommerce_cart_item_remove_link', sprintf( // phpcs:ignore
									'<a data-uuid="%s" href="#" class="remove remove-tray-item-from-cart" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
									esc_attr($tray_uuid),
									esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
									esc_attr($product_id),
									esc_attr($_product->get_sku()),
									TTG_Template::get_icon('remove')
								), $cart_item_key);
							} else {
								echo apply_filters('woocommerce_cart_item_remove_link', sprintf( // phpcs:ignore
									'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
									esc_url(wc_get_cart_remove_url($cart_item_key)),
									esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
									esc_attr($product_id),
									esc_attr($_product->get_sku()),
									TTG_Template::get_icon('remove')
								), $cart_item_key);
							}
							?>
						</td>
					</tr>
				<?php
				}
				?>

				<?php do_action('woocommerce_cart_contents'); ?>

				<tr>
					<td colspan="6" class="actions">
						<div class="actions-wrapper">
							<?php if (false) { ?>
								<div class="coupon">
									<label for="coupon_code" class="screen-reader-text"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" /> <button type="submit" class="button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_html_e('Apply coupon', 'woocommerce'); ?></button>
									<?php do_action('woocommerce_cart_coupon'); ?>
								</div>
							<?php } ?>

							<button type="submit" class="button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>


						</div>
						<?php do_action('woocommerce_cart_actions'); ?>

						<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
					</td>
				</tr>

				<?php do_action('woocommerce_after_cart_contents'); ?>
			</tbody>
		</table>
		<?php do_action('woocommerce_after_cart_table'); ?>
	</form>

	<?php do_action('woocommerce_before_cart_collaterals'); ?>

	<div class="cart-collaterals">
		<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action('woocommerce_cart_collaterals');
		?>
	</div>
</div>
<?php do_action('woocommerce_after_cart'); ?>