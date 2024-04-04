<?php get_header() ?>
<article class="single-post-article">
    <div class="single-post-article__inner">
        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                the_content();
            }
        }
        ?>
    </div>
</article>
<?php
$next_post = get_next_post();
$prev_post = get_previous_post();
?>
<div class="single-post-nav">
    <?php
    if ($prev_post) {
    ?>
        <a class="single-post-nav__prev" href="<?php echo get_permalink($prev_post->ID) ?>">« PREVIOUS ARTICLE</a>
    <?php
    }
    ?>
    <?php
    if ($next_post) {
    ?>
        <a class="single-post-nav__next" href="<?php echo get_permalink($next_post->ID) ?>">NEXT ARTICLE »</a>
    <?php } ?>
</div>
<?php
echo TTG_Template::get_template_part('related-posts');
?>
<?php get_footer() ?>