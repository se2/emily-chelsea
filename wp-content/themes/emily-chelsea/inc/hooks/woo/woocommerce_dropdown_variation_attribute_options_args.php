<?php
add_filter('woocommerce_dropdown_variation_attribute_options_args', function ($args) {
    $term = get_term_by("slug", $args['attribute']);
    if (!is_wp_error($term) && !empty($term)) {
        $args['show_option_none'] = "Choose an " . $term->name;
    }
    return $args;
}, 10, 1);
