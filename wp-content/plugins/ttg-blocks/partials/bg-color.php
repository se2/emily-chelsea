<?php
extract($args);
$color = empty($color) ? 'transparent' : TTG_Block_HTML_Helpers::hex2rgba($color, $opacity);
$attrs = TTG_Block_HTML_Helpers::attrs(array(
    'class' => 'w-full h-full ttg-background__color',
    'style' => array(
        'background-color' => $color,
        '-webkit-backdrop-filter' => 'blur(' . $blur . 'px)',
        'backdrop-filter' => 'blur(' . $blur . 'px)',
    )
));
?>
<div <?php echo $attrs ?>>

</div>