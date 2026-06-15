<?php
add_filter('facetwp_index_row', function ($params, $class) {
    if ('available_in_fairmined_gold' == $params['facet_name']) {
        $is_fairmined_gold = strpos($params['facet_value'], 'fairmined-gold');

        if ($is_fairmined_gold === false) {
            $params['facet_value'] = ''; // don't index this row
        } else {
            $params['facet_value'] = 'available-in-fairmined-gold';
            $params['facet_display_value'] = 'Available in Fairmined Gold';
        }
    } else if ($params['facet_name'] == 'band_width') {
        $params['facet_value'] = floatval($params['facet_display_value']);
    }

    return $params;
}, 10, 2);
