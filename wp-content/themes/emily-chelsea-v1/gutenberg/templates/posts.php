<?php
extract($args);
$attrs = [
    'class' => ['ttg-block-posts', $default_class],
    'id' => $default_id
];

$posts_block_number_items = get_field('posts_block_number_items');
$posts_block_type = get_field('posts_block_type');
$posts_block_filter = get_field('posts_block_filter');
$posts_block_posts = get_field('posts_block_posts');
$items = [];

if ($posts_block_type === 'select') {
    $items = $posts_block_products;
} else {
    $config = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_block_number_items
    );

    if (!empty($posts_block_filter)) {
        $config['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'terms' => $posts_block_filter
            )
        );
    }

    $items = get_posts($config);
}

?>
<?php
if (!empty($items)) {
?>
    <div <?php echo TTG_Util::generate_html_attrs($attrs) ?>>
        <div class="blog-list">
            <div id="blog-list-container" class="d-flex flex-wrap blog-list__inner">
                <?php
                foreach ($items as $key => $value) {
                    echo TTG_Template::get_template_part('post-item', ['post' => $value]);
                }
                ?>
            </div>
        </div>
    </div>
<?php
}
?>