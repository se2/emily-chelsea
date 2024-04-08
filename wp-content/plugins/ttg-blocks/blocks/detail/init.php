<?php
add_action('wp_enqueue_scripts', function () {
    if (has_block('core/details')) {
        wp_enqueue_style('ttg-detail-block', TTG_Blocks_Utils::get_blocks_url('detail/build/styles.css'));
    }
});
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style('ttg-detail-block', TTG_Blocks_Utils::get_blocks_url('detail/build/styles.css'));
    wp_enqueue_script('ttg-detail-block', TTG_Blocks_Utils::get_blocks_url("detail/build/index.js"), ['wp-edit-post']);
});
