<?php

use TTG\Build_Ring\Controller;

add_action('main-content-top', function () {
    if (Controller::is_building_ring()) {
        echo TTG_Template::render('build-ring-steps');
        return;
    }

    if (!Controller::is_building_ring()) {
        woocommerce_breadcrumb();
        return;
    }
}, 10);
