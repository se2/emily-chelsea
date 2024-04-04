<?php
add_filter('facetwp_is_main_query', function ($is_main_query, $query) {
    if ($query->is_home() && $query->is_main_query()) {
        $is_main_query = false;
    }
    return $is_main_query;
}, 10, 2);
