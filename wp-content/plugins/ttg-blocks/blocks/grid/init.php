<?php
include 'inc/class-jobadder-register-blocks.php';

function ttg_grid_enqueue_admin_script()
{
    $index_asset_file = TTG_Blocks_Utils::get_path('/blocks/grid/scripts/ttg-gutenberg.asset.php');
    $index_asset = file_exists($index_asset_file)
        ? require_once $index_asset_file
        : null;

    $index_dependencies = isset($index_asset['dependencies']) ? $index_asset['dependencies'] : array();
    global $wp_version;
    $wp_editor_dependency_to_remove = version_compare($wp_version, '5.2', '<') ? 'wp-block-editor' : 'wp-editor';
    $index_dependencies = array_filter(
        $index_dependencies,
        function ($dependency) use ($wp_editor_dependency_to_remove) {
            return $wp_editor_dependency_to_remove !== $dependency;
        }
    );

    wp_enqueue_script('tt-gutenberg', TTG_Blocks_Utils::get_url('blocks/grid/scripts/ttg-gutenberg.js'), $index_dependencies, '1');
}
add_action('admin_enqueue_scripts', 'ttg_grid_enqueue_admin_script');
