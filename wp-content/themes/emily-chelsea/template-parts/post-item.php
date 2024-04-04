<?php
extract($args);
if (!empty($post)) {
    $term = TTG_Util::get_main_term($post->ID);
?>
    <div class="post-item">
        <div class="post-item__inner">
            <a class="post-item__link" href="<?php echo get_the_permalink($post->ID) ?>">
                <div class="post-item__thumbnail">
                    <?php echo get_the_post_thumbnail($post->ID, 'full') ?>
                </div>
                <h2 class="heading-small post-item__title">
                    <?php echo $post->post_title; ?>
                </h2>
            </a>
            <div class="post-item__cat">
                <?php echo $term->name ?>
            </div>
        </div>
    </div>
<?php
}
