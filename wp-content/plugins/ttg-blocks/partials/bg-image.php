<?php
extract($args);

$attr_pc = [
    'class' => "d-none d-lg-block w-full h-full bg-img",
    'style' => [
        'background-image' => "url({$image_pc['url']})",
        '--bg-img-pos' => $bg_image_position
    ]
];

$attr_tablet = [
    'class' => "d-none d-md-block d-lg-none w-full h-full bg-img",
    'style' => [
        'background-image' => "url({$image_tablet['url']})",
        '--bg-img-pos-tablet' => $bg_image_position_tablet
    ]
];

$attr_m = [
    'class' => "d-block d-md-none d-lg-none w-full h-full bg-img",
    'style' => [
        'background-image' => "url({$image_mobile['url']})",
        '--bg-img-pos-m' => $bg_image_position_m
    ]
];

//background-attachment: fixed;
if (!empty($is_parallax)) {
    $attr_pc['style']['background-attachment'] = 'fixed';
    $attr_tablet['style']['background-attachment'] = 'fixed';
    $attr_m['style']['background-attachment'] = 'fixed';
}

?>
<div class="w-full h-full ttg-background__img">
    <?php
    if (!empty($image_pc)) {
    ?>
        <div <?php echo TTG_Block_HTML_Helpers::attrs($attr_pc) ?>></div>
    <?php
    }
    ?>

    <?php
    if (!empty($image_tablet)) {
    ?>
        <div <?php echo TTG_Block_HTML_Helpers::attrs($attr_tablet) ?>></div>
    <?php
    }
    ?>

    <?php
    if (!empty($image_mobile)) {
    ?>
        <div <?php echo TTG_Block_HTML_Helpers::attrs($attr_m) ?>></div>
    <?php
    }
    ?>
</div>