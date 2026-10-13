<?php

/**
 * Default Stone picker: explain why only some stones are listed.
 */
add_filter('acf/prepare_field/name=default_stone', function ($field) {
    $field['instructions'] = 'Only evergreen stones are listed: stock status "Made to Order" (Backorders: Allow). "Sold individually" must be off so one stone can be used in many designs.';
    return $field;
});
