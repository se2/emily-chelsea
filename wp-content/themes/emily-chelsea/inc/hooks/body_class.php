<?php
add_filter('body_class', function ($classes) {
    if (is_singular('product')) {
        global $post;
        $is_special_product = get_field('is_special_product', $post->ID);
        if ($is_special_product) {
            return array_merge($classes, array('is-product-special'));
        }
    }

    return $classes;
});
