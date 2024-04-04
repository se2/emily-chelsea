<?php
extract($args);
if (!empty($post)) {
    $term = TTG_Util::get_main_term($post->ID);
?>
    <div class="post-item post-item--style-2">
        <div class="post-item__inner">
            <a class="post-item__link" href="<?php echo get_the_permalink($post->ID) ?>">
                <div class="post-item__thumbnail">
                    <?php echo get_the_post_thumbnail($post->ID, 'full') ?>
                </div>
                <h2 class="heading-xsmall post-item__title">
                    <?php echo $post->post_title; ?>
                </h2>
                <div class="ttg-post post-item__desc">
                    <?php echo wp_trim_words(get_the_excerpt($post), 15); ?>
                </div>
            </a>
            <div class="post-item__line"></div>
        </div>
    </div>
<?php
}
