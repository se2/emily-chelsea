<?php
add_filter('facetwp_render_output', function ($output) {

    if (isset($output['settings']['necklace_style'])) {
        $output['settings']['necklace_style']['overflowText'] = __("Style", "ttg");
        $output['settings']['necklace_style']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['stones'])) {
        $output['settings']['stones']['overflowText'] = __("stones", "ttg");
        $output['settings']['stones']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['band_style'])) {
        $output['settings']['band_style']['overflowText'] = __("Style", "ttg");
        $output['settings']['band_style']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['stone_shape'])) {
        $output['settings']['stone_shape']['overflowText'] = __("SHAPE", "ttg");
        $output['settings']['stone_shape']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['origin'])) {
        $output['settings']['origin']['overflowText'] = __("Origin", "ttg");
        $output['settings']['origin']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['clarity'])) {
        $output['settings']['clarity']['overflowText'] = __("Clarity", "ttg");
        $output['settings']['clarity']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['color'])) {
        $output['settings']['color']['overflowText'] = __("Color", "ttg");
        $output['settings']['color']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['texture'])) {
        $output['settings']['texture']['overflowText'] = __("texture", "ttg");
        $output['settings']['texture']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['style'])) {
        $output['settings']['style']['overflowText'] = __("STYLE", "ttg");
        $output['settings']['style']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['stone_type'])) {
        $output['settings']['stone_type']['overflowText'] = __("TYPE", "ttg");
        $output['settings']['stone_type']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['metal_type'])) {
        $output['settings']['metal_type']['overflowText'] = __("METAL TYPE", "ttg");
        $output['settings']['metal_type']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['size'])) {
        $output['settings']['size']['overflowText'] = __("SIZE", "ttg");
        $output['settings']['size']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['filter_by_category'])) {
        $output['settings']['filter_by_category']['overflowText'] = __("FILTER BY CATEGORY:", "ttg");
        $output['settings']['filter_by_category']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['sidestone'])) {
        $output['settings']['sidestone']['overflowText'] = __("Side Stones", "ttg");
        $output['settings']['sidestone']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['jewelry_type'])) {
        $output['settings']['jewelry_type']['overflowText'] = __("Type", "ttg");
        $output['settings']['jewelry_type']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['main_stone_type'])) {
        $output['settings']['main_stone_type']['overflowText'] = __("Stones", "ttg");
        $output['settings']['main_stone_type']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['earring_style'])) {
        $output['settings']['earring_style']['overflowText'] = __("Style", "ttg");
        $output['settings']['earring_style']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['shape'])) {
        $output['settings']['shape']['overflowText'] = __("Shape", "ttg");
        $output['settings']['shape']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['ring_style'])) {
        $output['settings']['ring_style']['overflowText'] = __("Style", "ttg");
        $output['settings']['ring_style']['numDisplayed'] = 0;
    }
    if (isset($output['settings']['carat_size'])) {
        $output['settings']['carat_size']['overflowText'] = __("Carat Size", "ttg");
        $output['settings']['carat_size']['numDisplayed'] = 0;
    }
    return $output;
});
