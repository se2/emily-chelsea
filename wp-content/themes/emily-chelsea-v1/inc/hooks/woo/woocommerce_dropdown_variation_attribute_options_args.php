<?php
add_filter('woocommerce_dropdown_variation_attribute_options_args', function ($args) {
    $term = get_taxonomy($args['attribute']);
    if (!is_wp_error($term) && !empty($term)) {
        $label = str_replace('Product', "", $term->label);
        $args['show_option_none'] = "Choose your " .  $label;
    }

    return $args;
}, 999, 1);
