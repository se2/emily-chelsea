<?php
$width = get_field('width');
$width_tablet = get_field('width_tablet');
$width_m = get_field('width_m');
$mar = get_field('mar');
$mar_tablet = get_field('mar_tablet');
$mar_m = get_field('mar_m');
$border = get_field('border_width');
$border_tablet = get_field('border_tablet');
$border_color = get_field('border_color');

$attrs = TTG_Block_HTML_Helpers::attrs(array(
    'class' => 'overflow-hidden mx-auto ttg-separator',
    'style' => [
        '--separator-width' => $width,
        '--separator-width-tablet' => $width_tablet,
        '--separator-width-m' => $width_m,
        '--separator-mar' => $mar,
        '--separator-mar-tablet' => $mar_tablet,
        '--separator-mar-m' => $mar_m,
        '--separator-border' => empty($border) ? '0px' : $border . 'px',
        '--separator-border-color' => empty($border_color) ? '#000' : $border_color
    ]
));
?>
<div <?php echo $attrs; ?>>

</div>