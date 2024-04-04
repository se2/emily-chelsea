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

add_action('init', function () {
	// if (isset($_GET['test'])) {
	// 	$items = get_posts(array(
	// 		'post_type' => 'product',
	// 		'posts_per_page' => -1
	// 	));
	// 	if (!empty($items)) {
	// 		foreach ($items as $key => $value) {
	// 			$product = wc_get_product($value->ID);
	// 			if ($product->is_type("variable")) {
	// 				$variations = $product->get_children();
	// 				$list = [];

	// 				if (!empty($variations)) {
	// 					foreach ($variations as $v) {
	// 						$p = wc_get_product($v);
	// 						$attrs = $p->get_variation_attributes();
	// 						$key = implode("-", $attrs);
	// 						if (isset($list[$key])) {
	// 							wp_delete_post($v);
	// 						} else {
	// 							$list[$key] = $v;
	// 						}
	// 					}
	// 					var_dump($list);
	// 					echo "----------------------------------------\\n";
	// 				}
	// 			}
	// 		}
	// 	}
	// }
});
