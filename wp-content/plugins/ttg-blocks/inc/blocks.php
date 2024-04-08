<?php
class TTG_Blocks
{
    public $config;
    public $category;
    public $blocks;
    public $icon;
    public $base_scripts;
    public $template_path;
    private $template_name = '';

    function __construct($template_path = '')
    {
        $this->category = 'ttg';
        $this->icon = '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
		<path fill="#bf0000" d="M18 1H6.084L0 8l12 15L24 8.084 18 1zm-6.067 5.52L8.413 3h6.452l-2.932 3.52zM9.586 7H3.52l2.82-3.246L9.586 7zM11 9v9.55L3.36 9H11zm2 0h7.695L13 18.566V9zm7.46-2h-6.325l3.138-3.766L20.46 7z"></path></svg>';
        $this->config = array(
            'slug' => $this->category,
            'title' => __('TTG'),
            'icon'  => $this->icon,
        );
        $this->blocks = [];
        $this->base_scripts = [];
        $this->template_path = !empty($template_path) ? $template_path : TTG_Blocks_Utils::get_path('blocks/views/');


        add_filter('block_categories', [$this, 'register_category']);
        add_action('acf/init', [$this, 'register']);
        add_action('wp_enqueue_scripts', [$this, 'block_styles']);
    }

    public function register_category($categories)
    {
        return array_merge(
            [$this->config],
            $categories
        );
    }

    public function set_base_scripts($css = [], $js = [])
    {
        $this->base_scripts = [
            'css' => $css,
            'js' => $js
        ];
    }

    public function set_template_path($path)
    {
        $this->template_path = $path;
    }

    public function load_css($css)
    {
        $css = apply_filters('ttg_blocks_css', $css);
        if (!empty($css)) {
            foreach ($css as $key => $value) {
                wp_enqueue_style($value['handle'], $value['url']);
            }
        }
    }

    public function load_js($js)
    {
        $js = apply_filters('ttg_blocks_js', $js);
        if (!empty($js)) {
            foreach ($js as $key => $value) {
                wp_enqueue_script($value['handle'], $value['url'], $value['deps'], false, true);
            }
        }
    }

    public function add($id, $title, $desc, $css = [], $js = [])
    {
        $this->blocks[] = [
            'name' => $id,
            'title' => $title,
            'description' => $desc,
            'css' => $css,
            'js' => $js
        ];
    }

    function render_callback(
        $block,
        $content = '',
        $is_preview = false,
        $post_id = 0,
        $wp_block = false,
        $context = false,
        $block_name
    ) {
        load_template($this->template_path . $this->template_name . '.php', false, []);
    }

    public function register()
    {
        if (!empty($this->blocks)) {
            foreach ($this->blocks as $key => $block) {
                $css = $block['css'];
                $js = $block['js'];
                $template_name = $block['name'];
                $config = [
                    'name' => $block['name'],
                    'title' => $block['title'],
                    'description' => $block['description'],
                    'render_callback' => function (
                        $block,
                        $content = '',
                        $is_preview = false,
                        $post_id = 0,
                        $wp_block = false,
                        $context = false
                    ) use ($template_name) {
                        $classes = '';
                        $id = empty($block['anchor']) ? uniqid() : $block['anchor'];

                        if (!empty($block['className'])) {
                            $classes .= sprintf(' %s', $block['className']);
                        }

                        if (!empty($block['align'])) {
                            echo sprintf('<div class="align%s">', $block['align']);
                        }

                        do_action(
                            'ttg_blocks_before_load_content',
                            $template_name,
                            $block,
                            $content,
                            $is_preview,
                            $post_id,
                            $wp_block,
                            $context,
                        );
                        load_template($this->template_path . $template_name . '.php', false, [
                            'default_class' => $classes,
                            'default_id' => $id
                        ]);
                        do_action(
                            'ttg_blocks_after_load_content',
                            $template_name,
                            $block,
                            $content,
                            $is_preview,
                            $post_id,
                            $wp_block,
                            $context,
                        );
                        if (!empty($block['align'])) {
                            echo '</div>';
                        }
                    },
                    'category' => $this->category,
                    'mode' => 'preview',
                    'icon' => $this->icon,
                    'supports'          => array(
                        'mode' => true,
                        'jsx' => true,
                        'align' => array('wide', 'full')
                    ),
                    'enqueue_assets' => function () use ($css, $js) {
                        if (is_admin()) {
                            $this->load_css($this->base_scripts['css']);
                            $this->load_css($css);
                        }

                        $this->load_js($this->base_scripts['js']);
                        $this->load_js($js);
                    }
                ];
                if (function_exists('acf_register_block_type')) {
                    acf_register_block_type($config);
                }
            }
        }
    }

    /**
     * Register the styles (CSS) for the blocks outside
     * acf_register_block_type() as loading styles
     * using acf_register_block_type() will load the
     * styles in the footer and not in <head> causing
     * CLS issues 
     */
    public function block_styles()
    {
        $post = apply_filters('ttg_block_styles_post', null);
        if (!empty($this->blocks)) {
            $is_loaded_base = false;
            foreach ($this->blocks as $key => $block) {
                $css = $block['css'];
                $has_block = has_block('acf/' . $block['name'], $post);
                if (!$is_loaded_base) {
                    $this->load_css($this->base_scripts['css']);
                    $is_loaded_base = true;
                }
                if ($has_block) {
                    $this->load_css($css);
                }
            }
        }
    }
}
