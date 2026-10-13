<?php

/**
 * Default Stone picker (per-ring "Ring Setting" group + global option):
 * only list evergreen stones, i.e. "Made to Order" (_stock_status = onbackorder).
 * A one-of-a-kind stone sells out after one order and would break the
 * "Our Recommended Stone" option for every ring that points at it.
 */
add_filter('acf/fields/post_object/query/name=default_stone', function ($args) {
    $args['meta_query'] = [
        [
            'key'   => '_stock_status',
            'value' => 'onbackorder',
        ],
    ];
    return $args;
});
