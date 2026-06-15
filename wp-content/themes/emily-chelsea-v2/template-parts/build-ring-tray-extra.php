<?php

use TTG\Build_Ring\Controller;
use TTG\Build_Ring\MODE;

$mode = Controller::get_mode();
$ring = Controller::get_ring();
$stone = Controller::get_stone();

if ($mode === MODE::START_WITH_STONE && $ring > 0) {
    echo '<div id="build-ring-tray-extra" class="build-ring-tray-product-info-wrapper">';
    $product_tray_product_info_html = $ring > 0 ? \TTG_Template::get_template_part('build-ring-tray-product-info', ['product_id' => $ring]) : '';
    $stone_options_html = $ring > 0 ? \TTG_Template::get_template_part('build-ring-stone-options', ['product_id' => $ring]) : '';

    echo $product_tray_product_info_html;
    echo $stone_options_html;
    echo '</div>';
}

if ($mode === MODE::START_WITH_SETTING && $stone > 0) {
    echo '<div id="build-ring-tray-extra" class="build-ring-tray-product-info-wrapper">';
    $product_tray_product_info_html = $stone > 0 ? \TTG_Template::get_template_part('build-ring-tray-product-info', ['product_id' => $stone]) : '';
    echo $product_tray_product_info_html;
    echo '</div>';
}
