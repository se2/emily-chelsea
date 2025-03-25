<?php

/**
 * Functions.php
 *
 * Do not write PHP code in here! Instead, add a new file in the `/lib` folder, OR add your code
 * to a _relevant_ existing file.
 *
 * This structure keeps the code clean, organized, and easy to work with.
 */

require(get_template_directory() . '/extension/acf-nav-menu/fz-acf-nav-menu.php');


function deep_scan($dir = __DIR__, $files = [])
{
	$new_files = $files;
	$current_files = array_diff(scandir($dir), array('..', '.'));
	if (!empty($current_files)) {
		foreach ($current_files as $file) {
			$path =  $dir . '/' . $file;
			if (preg_match("/.php$/i", $path)) {
				$new_files[] = $path;
			} else if (is_dir($path)) {
				$new_files = array_merge($new_files, deep_scan($path, []));
			}
		}
	}

	return $new_files;
}

$template_directory  = get_template_directory();
$folders = ['inc'];
foreach ($folders as $key => $folder) {
	$files = deep_scan($template_directory . '/' . $folder, []);
	if (!empty($files)) {
		foreach ($files as $file) {
			require($file);
		}
	}
}

require(get_template_directory() . '/gutenberg/init.php');


function swap_attr($from, $to, $from_attr = [], $to_attr = [])
{
	$items = get_posts(array(
		'post_type' => 'product',
		'posts_per_page' => -1
	));
	if (!empty($items)) {
		foreach ($items as $key => $value) {
			$product = wc_get_product($value->ID);
			$attributes = $product->get_attributes();
			$from_data = $attributes[$from];
			$to_data = $attributes[$to];
			$items = [];
			$item_ids = [];
			$attrs = get_post_meta($product->get_id(), "_product_attributes", true);


			if (!empty($to_data) && !empty($to_data->get_terms())) {
				foreach ($to_data->get_terms() as $key => $value) {
					$name = $value->name;
					$items[$name] = $name;
				}
			}

			if (!empty($from_data) && !empty($from_data->get_terms())) {
				foreach ($from_data->get_terms() as $key => $value) {
					$name = $value->name;
					$items[$name] = $name;
				}
			}

			if (!empty($items)) {
				foreach ($items as $key => $value) {
					$term = get_term_by("name", $value, $to);
					if (!empty($term->term_id)) {
						$item_ids[] = $term->term_id;
					} else {
						$new_term = wp_insert_term($value, $to);
						if (!is_wp_error(($new_term))) {
							$item_ids[] = $new_term;
						}
					}
				}
			}

			if (isset($attrs[$from])) {
				$attrs[$from] = array_merge($attrs[$from], $from_attr);
			}

			if (!empty($item_ids)) {

				$res = wp_set_object_terms($product->get_id(), $item_ids, $to, TRUE);
				$attrs[$to] = array_merge([
					"name" => $to,
					"value" => implode("|", $item_ids),
					"is_visible" => 1,
					"is_variation" => 1,
					"is_taxonomy" => 1
				], $to_attr);

				if (!empty($attrs)) {
					update_post_meta($product->get_id(), '_product_attributes', $attrs);
				}
			}


			if ($product->is_type("variable")) {
				$variations = $product->get_children();
				foreach ($variations as $key => $value) {
					$p = wc_get_product($value);
					$variation_attrs = $p->get_variation_attributes($from);
					$from_value = $variation_attrs['attribute_' . $from];
					echo $product->get_id();
					var_dump($variation_attrs);
					var_dump($from_value);
					if (empty($variation_attrs['attribute_' . $to]) && !empty($variation_attrs['attribute_' . $from])) {
						$variation_attrs['attribute_' . $to] = $variation_attrs['attribute_' . $from];

						echo 'after';
						var_dump($variation_attrs);
						$p->set_attributes($variation_attrs);
						$p->save();
					}
				}
			}
		}
	}
}

// add_action('init', function () {
// 	if (isset($_GET['update_attr'])) {
// 		swap_attr("pa_ring-size", "pa_size", ['is_variation' => 0], ["position" => 9999]);
// 		swap_attr("pa_shape", "pa_stone-shape", ['is_variation' => 0], ['is_variation' => 0]);
// 	}
// });


