<?php
require_once 'register.php';
require_once('grid/init.php');

require TTG_Blocks_Utils::get_path('blocks/detail/init.php');

add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script('custom-core-blocks', TTG_Blocks_Utils::get_assets_url('libs/custom-core-blocks.js'), ['wp-edit-post']);
    wp_enqueue_style('block-editor', TTG_Blocks_Utils::get_assets_url("dist/css/components/block-editor.css"));
});
