<?php
extract($args);
$classes = [
    'primary-solid' => 'btn btn--solid',
    'primary-outline' => 'btn btn--outline',
    'second-solid' => 'btn btn--secondary-solid',
    'second-outline' => 'btn btn--secondary-outline',
    'white-solid' => 'btn btn--solid btn--white',
    'white-outline' => 'btn btn--outline btn--white'
];
$sizes = [
    'small' => 'btn--small',
    'medium' => 'btn--medium',
    'large' => 'btn--large'
];
$viewport = ['pc', 'tablet', 'mobile'];

$attrs = [
    'class' => ['ttg-button']
];

if ($style != 'custom') {
    if (!isset($size)) {
        $size = 'small';
    }
    $attrs['class'][] = $classes[$style];
    $attrs['class'][] = $sizes[$size];

    $vp_style = $custom_style['viewport'];
    $properties = ['width'];

    foreach ($viewport as $vp) {
        $items = $vp_style[$vp];
        foreach ($properties as $p) {
            $k = '--ttg-button-' . $p . '-' . $vp;
            $attrs['style'][$k] = $items[$p];
        }
    }
} else {
    $properties = ['height', 'border_width'];
    $type = $custom_style['type'];
    $common = $custom_style['common'];
    $style = $custom_style['viewport'];
    $attrs['class'][] = 'btn btn-custom btn-custom--' . $type;
    $attrs['style']['--ttg-button-text_color'] = $common['text_color'];
    $attrs['style']['--ttg-button-background_color'] = $common['background_color'];
    $attrs['style']['--ttg-button-text_color_hover'] = $common['text_color_hover'];
    $attrs['style']['--ttg-button-background_color_hover'] = $common['background_color_hover'];

    foreach ($viewport as $vp) {
        $items = $style[$vp];
        foreach ($properties as $p) {
            $k = '--ttg-button-' . $p . '-' . $vp;
            $attrs['style'][$k] = $items[$p];
        }
    }
}
?>
<?php

if (!empty($link)) {
    $link_url = $link['url'];
    $link_title = $link['title'];
    $link_target = $link['target'] ? $link['target'] : '_self';
?>
    <a <?php echo TTG_Block_HTML_Helpers::attrs($attrs) ?> href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?></a>
<?php } ?>