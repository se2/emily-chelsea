<?php
class TTG_Block_Grid
{
    private static $instance;
    private $slug;
    private $assets_url;

    public static function register()
    {
        if (null === self::$instance) {
            self::$instance = new TTG_Block_Grid();
        }

        return self::$instance;
    }



    public function __construct()
    {
        $this->slug = 'ttg';
        $this->assets_url = TTG_Blocks_Utils::get_assets_url('dist/');

        add_action('init', array($this, 'register_blocks'), 99);
    }

    public function register_blocks()
    {

        // Return early if this function does not exist.
        if (!function_exists('register_block_type')) {
            return;
        }

        // Shortcut for the slug.
        $slug = $this->slug;

        wp_register_style($slug . '-column', $this->assets_url . 'css/components/ttg-column.css', false, false);
        wp_register_style($slug . '-column-editor', $this->assets_url . 'css/components/ttg-column-editor.css', false, false);
        register_block_type(
            $slug . '/column',
            array(
                'editor_style'  => $slug . '-column-editor',
                'style'         => $slug . '-column',
            )
        );

        wp_register_style($slug . '-row', $this->assets_url . 'css/components/ttg-row.css', false, false);
        wp_register_style($slug . '-row-editor', $this->assets_url . 'css/components/ttg-row-editor.css', false, false);
        register_block_type(
            $slug . '/row',
            array(
                'editor_style'  => $slug . '-row-editor',
                'style'         => $slug . '-row',
            )
        );
    }
}

TTG_Block_Grid::register();
