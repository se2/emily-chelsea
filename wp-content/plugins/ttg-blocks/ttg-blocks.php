<?php
/*
Plugin Name:  TTG Blocks
Description:  TTG Blocks
Version:      1.0
Author:       TTG Team
*/
class TTG_Blocks_Utils
{

    public static function get_path($path = '')
    {
        return plugin_dir_path(__FILE__) . $path;
    }

    public static function get_url($path = '')
    {
        return plugins_url($path, __FILE__);
    }

    public static function get_assets_url($path = '')
    {
        return self::get_url('src/' . $path);
    }

    public static function get_blocks_url($path = '')
    {
        return self::get_url('blocks/' . $path);
    }
}

function ttg_blocks_load_scripts()
{
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'ttg_blocks_load_scripts');

add_action('wp_head', function () {
    printf('<script type="text/javascript">var ttgBlocks = %s</script>', json_encode(array('assetUrl' => TTG_Blocks_Utils::get_assets_url())));
});

require_once __DIR__ . '/inc/blocks.php';
require_once __DIR__ . '/inc/html-helpers.php';
require_once __DIR__ . '/inc/template-parts-helper.php';
require_once __DIR__ . '/inc/acf.php';
require_once __DIR__ . '/blocks/init.php';
