<?php
add_filter('ttg_blocks_css', function ($css) {
    $new_css = $css;
    foreach ($new_css as $key => $value) {
        if ($value['handle'] === 'button') {
            $new_css[$key]['url'] = TTG_Util::get_assets_url('dist/css/components/buttons.css');
        }
    }
    return $new_css;
});

if (class_exists('TTG_Blocks')) {
    $template_path = get_template_directory() . '/gutenberg/templates/';
    $blocks = new TTG_Blocks($template_path);

    $blocks->set_base_scripts(
        [
            [
                'handle' => 'base',
                'url' => TTG_Util::get_assets_url('dist/css/base.min.css')
            ]
        ]
    );

    $blocks->add(
        'content-with-image',
        'TTG Content With Image',
        'TTG Content With Image',
        [
            [
                'handle' => 'content-with-image',
                'url' => TTG_Util::get_assets_url('dist/css/components/content-with-image.css')
            ]
        ]
    );

    $blocks->add(
        'content-with-image-2',
        'TTG Content With Image 2',
        'TTG Content With Image 2',
        [
            [
                'handle' => 'content-with-image-2',
                'url' => TTG_Util::get_assets_url('dist/css/components/content-with-image-2.css')
            ]
        ]
    );

    $blocks->add(
        'container',
        'TTG Container',
        'TTG Container',
        [
            [
                'handle' => 'container',
                'url' => TTG_Util::get_assets_url('dist/css/components/container.css')
            ]
        ]
    );

    $blocks->add(
        'products',
        'TTG Products',
        'TTG Products',
        [
            [
                'handle' => 'products',
                'url' => TTG_Util::get_assets_url('dist/css/components/products.css')
            ],
            [
                'handle' => 'products-block',
                'url' => TTG_Util::get_assets_url('dist/css/components/products-block.css')
            ]
        ]
    );

    $blocks->add(
        'products-special',
        'TTG Products Special',
        'TTG Products Special',
        [
            [
                'handle' => 'products',
                'url' => TTG_Util::get_assets_url('dist/css/components/products.css')
            ],
            [
                'handle' => 'custom-woocommerce-pagination',
                'url' => TTG_Util::get_assets_url('dist/css/components/woocommerce-pagination.css')
            ],
        ]
    );

    $blocks->add(
        'posts',
        'TTG Posts',
        'TTG Posts',
        [
            [
                'handle' => 'blog-list',
                'url' => TTG_Util::get_assets_url('dist/css/components/blog-list.css')
            ],
            [
                'handle' => 'block-posts',
                'url' => TTG_Util::get_assets_url('dist/css/components/block-posts.css')
            ]
        ]
    );
}

// add style for core block
add_action('wp_enqueue_scripts', function () {
    if (has_block('core/quote')) {
        wp_enqueue_style('block-quote', TTG_Util::get_assets_url('/dist/css/components/block-quote.css'));;
    }
    if (has_block('core/image')) {
        wp_enqueue_style('block-image', TTG_Util::get_assets_url('/dist/css/components/block-image.css'));;
    }
});

add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style('emily-block-editor', TTG_Util::get_assets_url("dist/css/components/block-editor.css"));
    wp_enqueue_style('font-outfit', 'https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap');
    wp_enqueue_style('font-base', get_theme_file_uri('/src/fonts/newforest/stylesheet.css'));

    wp_deregister_style('wp-reset-editor-styles');
    wp_dequeue_style('wp-reset-editor-styles');

    wp_enqueue_style('wp-reset-editor-styles', get_template_directory_uri() . '/src/dist/css/components/wp-reset-editor-styles.css');
}, 1);
