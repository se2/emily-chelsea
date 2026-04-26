<?php

/**
 ** output only total results
 **/

add_filter('facetwp_result_count', function ($output, $params) {
    $output = $params['total'] . ' results';
    return $output;
}, 10, 2);
