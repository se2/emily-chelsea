<?php 

add_action('wp_footer', function(){
	$notice = get_field('store_notice', 'option');
	$is_active = get_field('active_store_notice', 'option');

	if ($is_active && !empty($notice)) {
		echo sprintf('<div class="emily-store-notice">%s</div>', $notice);

		$bg_color = get_field('store_notice_bg_color', 'option') ?? '#ffffff';
		$txt_color = get_field('store_notice_txt_color', 'option') ?? '#000000';

		?>

		<style>
            #toggle-nav-checkbox, .toggle-nav-btn {
                height: 45px!important;
                top: 54px;
            }
            .main-header__sticky {
                margin-top: 40px;
            }
			.emily-store-notice{
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				z-index: 9999;
				background-color: <?php echo $bg_color; ?>;
				color:  <?php echo $txt_color; ?>;
				padding: 10px 30px;
				box-shadow: 0 0 15px rgb(40 40 40 / 10%);
			}

			.emily-store-notice p:last-child{
				margin-bottom: 0;
			}

			/*	.admin-bar .emily-store-notice{
				top: 32px;
			}*/

			@media (max-width: 768px){
                #toggle-nav-checkbox, .toggle-nav-btn {
                    top: 50px!important;
                }
                .main-header__sticky {
                    margin-top: 27px;
                }
				.emily-store-notice{
					padding: 10px;
					        font-size: 77%;
				}

				.emily-store-notice p {
					    line-height: 1.3;
				}


			}
		</style>

		<script>
			// var	cT = jQuery('.toggle-nav-btn').position().top,
			// nH = jQuery('.emily-store-notice').outerHeight();
            //
            // console.log(cT);
			// jQuery('.main-header__sticky').css('margin-top', nH);
			// //jQuery('.toggle-nav-btn,#toggle-nav-checkbox').css('top', nH + cT/2);
            //
			// jQuery(window).on('resize', function(){
            //     console.log(nH + cT/2);
			// 	nH = jQuery('.emily-store-notice').outerHeight();
			// 	jQuery('.main-header__sticky').css('margin-top', nH);
			// 	//jQuery('.toggle-nav-btn, #toggle-nav-checkbox').css('top', nH + cT/2);
			// })
		</script>

		<?php
	}
});


add_filter('body_class', function($class){
	$is_active = get_field('active_store_notice', 'option');
	$notice = get_field('store_notice', 'option');

	if ($is_active && !empty($notice)) {
		$class[] = 'store-notice-active';
	}

	return $class;
}, 10, 1);


add_action('init', function(){

	if (isset($_GET['ukirot'])) {
		die();
		// Get the products that don't have '_sale_price'
		$args = array(
		    'post_type'      => 'product',
		    'posts_per_page' => 1000,
		    'tax_query'     => array(
		        'relation' => 'AND',
		        array(
		            'terms'     => [705],
		            'taxonomy' => 'product_cat',
		            'field' => 'id'
		        ),
		       
		    ),
		);
		$products = query_posts( $args );
		$discount = 20;
		$shipping_class_id = 704;

		echo '<table border="1" cellpadding="5px">
			<thead>
				<tr>
					<th>Product ID</th>
					<th>Product Title</th>
					<th>Variation ID</th>
					<th>Regular Price</th>
					<th>Sale Price (Disount 20%)</th>
					<th>Set Free Shipping Class</th>
				</tr>
			</thead>
		<tbody>';

		// Loop through queried products
		foreach ($products as $post) {
		    $product = wc_get_product( $post->ID );

		    if ($product->is_type( 'variable' )) 
			{
			    $available_variations = $product->get_available_variations();

			    $product->set_shipping_class_id( $shipping_class_id ); 
			    $product->save();

			    foreach ($available_variations as $variation) 
			    { 
			    	$variation_id = $variation['variation_id'];
			    	$product_variation = new WC_Product_Variation($variation_id);

			    	$regular_price = get_post_meta($variation_id, '_regular_price', true);
			    	$sale_price = round($regular_price * ((100-$discount) / 100), 2);

			    	$product_variation->set_sale_price($sale_price);
			    	$product_variation->save();

			        echo sprintf("<tr>
			        	<td>#%s</td>
			        	<td>%s</td>
			        	<td>#%s</td>
			        	<td>%s</td>
			        	<td>%s</td>
			        	<td>%s</td>
			        </tr>",
			        	$post->ID,
			        	get_the_title($post->ID),
				    	$variation_id,
				    	wc_price($regular_price),
				    	wc_price($sale_price),
				    	true
					);
			    }
			} else {
			    // Discount by 10% and round to 2 decimal places
			    // $newprice = round($product->get_regular_price() * ((100-$discount) / 100), 2);

			    echo sprintf("<tr><td>#%s</td><td>%s</td></tr>",
			    	$post->ID,
			    	$discount
				);
			    // // Update product's prices
			    // update_post_meta( $product->get_id(), '_sale_price', $newprice );
			    // update_post_meta( $product->get_id(), '_price', $newprice );
		    }

		}

		echo '</tbody><table>';
		exit;
	}

});