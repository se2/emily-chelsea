<?php
extract($args);
global $post;
if (!isset($term)) {
    $term = TTG_Util::get_main_term($post->ID);
}
$posts = get_posts(array(
    'post_type' => 'post',
    'posts_per_page' => 2,
    'tax_query' => array(
        array(
            'taxonomy' => 'category',
            'terms' => [$term->term_id]
        )
    ),
    'post__not_in' => [$post->ID]
))
?>
<div class="related-posts">
    <h2 class="heading-large related-posts__title">
        Related Articles
    </h2>
    <div class="blog-list">
        <div id="blog-list-container" class="d-flex flex-wrap blog-list__inner">
            <?php
            foreach ($posts as $key => $value) {
                echo TTG_Template::get_template_part('post-item', ['post' => $value]);
            }
            ?>
        </div>
        <div class="line"></div>
        <nav class="woocommerce-pagination">
            <?php
            echo do_shortcode('[facetwp facet="load_more"]');
            ?>
        </nav>
    </div>
</div>