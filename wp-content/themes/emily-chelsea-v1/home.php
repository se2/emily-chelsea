<?php get_header(); ?>
<?php
$lastest_posts = get_posts(array(
    'post_type' => 'post',
    'posts_per_page' => 1
));
$lastest_post = !empty($lastest_posts) ? $lastest_posts[0] : null;
?>
<?php
if (!empty($lastest_post)) {
?>
    <div class="feature-post-wrapper">
        <h2 class="feature-post-wrapper__title">FEATURED ARTICLE:</h2>
        <?php
        echo TTG_Template::get_template_part('post-feature-item', ['post' => $lastest_post])
        ?>
    </div>
<?php
}
?>
<?php echo TTG_Template::get_template_part('blog-filter'); ?>
<?php

$config = array(
    'post_type' => 'post',
    'posts_per_page' => 12,
    'post__not_in' => [$lastest_post ? $lastest_post->ID : 0],
    'paged' => $paged,
    "facetwp" => true
);

if (!empty($s)) {
    $config['s'] = $s;
}

$blogs = new  WP_Query($config);

?>
<?php
if (!empty($blogs->posts)) {
?>
    <div class="blog-list">
        <div id="blog-list-container" class="d-flex flex-wrap blog-list__inner facetwp-template">
            <?php
            foreach ($blogs->posts as $key => $value) {
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

<?php
}
wp_reset_postdata();
?>

<?php get_footer(); ?>