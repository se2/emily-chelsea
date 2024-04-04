<?php
extract($args);
if (!empty($post)) {
    if (!isset($term)) {
        $term = TTG_Util::get_main_term($post->ID);
    }
?>
    <div class="post-feature-item">
        <div class="post-feature-item__inner">
            <div class="post-feature-item__left">
                <a href="<?php echo get_the_permalink($post->ID) ?>" class="d-block post-feature-item__image">
                    <?php echo get_the_post_thumbnail($post->ID, 'full') ?>
                </a>
            </div>
            <div class="post-feature-item__right">
                <div class="post-feature-item__content">
                    <?php
                    if (!empty($term)) {
                    ?>
                        <h3 class="post-feature-item__content__cat"><?php echo $term->name ?></h3>
                    <?php
                    }
                    ?>
                    <h2 class="heading-small post-feature-item__content__title"><?php echo $post->post_title; ?></h2>
                    <div class="ttg-post post-feature-item__content__desc">
                        <?php echo get_the_excerpt($post->ID) ?>
                    </div>
                    <a class="btn btn--outline btn--small post-feature-item__content__btn" href="<?php echo get_the_permalink($post->ID) ?>">CONTINUE READING »</a>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>