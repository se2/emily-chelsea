<?php
extract($args);
$color = '';
if (!empty($start_color) && !empty($end_color)) {
    $color = sprintf('linear-gradient(%sdeg, %s 0%%, %s 100%%)', $angle, $end_color, $start_color);
} else {
    if (!empty($color_gradient)) {
        $color = $color_gradient;
    }
}
?>
<?php
if (!empty($color)) {
    $style = sprintf('background: %s;', $color);
?>
    <div class="w-full h-full ttg-background__color-gradient" style="<?php echo $style ?>">

    </div>
<?php
}
?>