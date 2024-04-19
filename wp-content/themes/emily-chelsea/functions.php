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
