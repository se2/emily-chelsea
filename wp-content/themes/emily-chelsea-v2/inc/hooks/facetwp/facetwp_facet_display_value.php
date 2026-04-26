<?php
add_filter('facetwp_facet_display_value', function ($label, $params) {
    // only apply to a facet named "vehicle_type"
    if ('instock' == $label) {
        return 'In Stock and Ready to Ship';
    }

    return $label;
}, 999, 2);
