<?php

/**
 * Template Name: Build Ring
 */

use TTG\Build_Ring\Controller;
use TTG\Build_Ring\MODE;


$mode = Controller::get_mode();
if (empty($mode)) {
    Controller::set_mode(MODE::START_WITH_SETTING);
}

add_filter('ttg_hero_banner_image', function ($image, $id) {
    $mode = Controller::get_mode();
    if ($mode === MODE::START_WITH_SETTING) {
        return get_field('step_1_image', $id) ?? $image;
    }

    if ($mode === MODE::START_WITH_STONE) {
        return get_field('step_2_image', $id) ?? $image;
    }

    if ($mode === MODE::CONFIRM) {
        return get_field('step_3_image', $id) ?? $image;
    }

    return $image;
}, 10, 2);
wp_enqueue_script('wc-add-to-cart-variation');
?>
<?php get_header() ?>
<article class="page-build-ring">
    <div class="processing">Processing...</div>
    <?php echo TTG_Template::render("build-ring-steps") ?>
    <?php


    if ($mode == MODE::CONFIRM) {
        echo TTG_Template::render('build-ring-step-3');
    } else {
        echo TTG_Template::render('build-ring-step-1');
    }
    ?>
</article>
<?php get_footer() ?>