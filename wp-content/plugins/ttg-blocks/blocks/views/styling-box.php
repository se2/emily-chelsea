<?php
include(__DIR__ . '/general.php');
include(__DIR__ . '/full-size.php');

if (!function_exists('get_vp')) {
    function get_vp($viewport = '')
    {
        return !empty($viewport) ? '-' . $viewport . '-' : '-';
    }
}

if (!function_exists('get_position')) {
    function get_position($key = 'left', $viewport = '')
    {
        $vp = get_vp($viewport);
        $positions = [
            'left' => "justify-content{$vp}start",
            "right" => "justify-content{$vp}end",
            "center" => "justify-content{$vp}center",
            "left-center" => "justify-content{$vp}start align-items{$vp}center",
            "right-center" => "justify-content{$vp}end align-items{$vp}center",
            "center-center" => "justify-content{$vp}center align-items{$vp}center",
        ];

        return $positions[$key];
    }
}

if (!function_exists('get_inner_position')) {
    function get_inner_position($key = 'left-top', $viewport = '')
    {
        $vp = get_vp($viewport);
        $positions = [
            'left-top' => "align-items{$vp}start justify-content{$vp}start text{$vp}start",
            'right-top' => "align-items{$vp}end justify-content{$vp}start text{$vp}end",
            'center-top' => "align-items{$vp}center justify-content{$vp}start text{$vp}center",
            'left-center' => "align-items{$vp}start justify-content{$vp}center text{$vp}start",
            'right-center' => "align-items{$vp}end justify-content{$vp}center text{$vp}end",
            'center center' => "align-items{$vp}center justify-content{$vp}center text{$vp}center",
            'left-bottom' => "align-items{$vp}start justify-content{$vp}end text{$vp}start",
            'right-bottom' => "align-items{$vp}end justify-content{$vp}end text{$vp}end",
            'center-bottom' => "align-items{$vp}center justify-content{$vp}end text{$vp}center"
        ];

        return $positions[$key];
    }
}

if (!function_exists('get_direction')) {
    function get_direction($key = 'column', $viewport = '')
    {
        $vp = get_vp($viewport);
        $direction = [
            'column' => "flex{$vp}column",
            'row' => "flex{$vp}row"
        ];

        return $direction['$key'];
    }
}

$image_position = [
    'object-center' => 'center center',
    'object-left' => 'left center',
    'object-left-bottom' => 'left bottom',
    'object-left-top' => 'left top',
    'object-right' => 'right center',
    'object-right-bottom' => 'right bottom',
    'object-right-top' => 'right top',
    'object-top' => 'center top',
    'object-bottom' => 'center bottom'
];

$container_tag = get_field('container_tag');
$container_tag = empty($container_tag) ? 'div' : $container_tag;
$url = get_field('url');

$max_width = get_field('max_width');
$max_width_tablet = get_field('max_width_tablet');
$max_width_m = get_field('max_width_m');

$width = get_field('width');
$width_tablet = get_field('width_tablet');
$width_m = get_field('width_m');

$height = get_field('height');
$height_tablet = get_field('height_tablet');
$height_m = get_field('height_m');

$p = get_field('p');
$p_tablet = get_field('p_tablet');
$p_m = get_field('p_m');

$mar = get_field('mar');
$mar_tablet = get_field('mar_tablet');
$mar_m = get_field('mar_m');



$bg_type = get_field('bg_type');
$bg_blur = get_field('bg_blur');
$bg_color = get_field('bg_color');
$bg_color_opacity = get_field('bg_color_opacity');
$bg_color_start = get_field('bg_color_start');
$bg_color_end = get_field('bg_color_end');
$bg_color_gradient = get_field('bg_color_gradient');
$bg_color_angle = get_field('bg_color_angle');

$border_radius = get_field('border_radius');
$border_radius_tablet = get_field('border_radius_tablet');
$border_radius_m = get_field('border_radius_m');

$media_type = get_field('media_type');
$video = get_field('video');
$youtube_id = get_field('youtube_id');
$vimeo_id = get_field('vimeo_id');
$poster = get_field('poster');
$bg_images = TTG_Block_HTML_Helpers::get_image('bg_image_pc', 'bg_image_mobile', 'bg_image_tablet');
$is_parallax = get_field('is_parallax');

$bg_image_position_tablet = get_field('bg_image_position_tablet');
$bg_image_position = get_field('bg_image_position');
$bg_image_position_m = get_field('bg_image_position_m');

$bg_size_tablet = get_field("bg_size_tablet");
$bg_size = get_field("bg_size");
$bg_size_m = get_field("bg_size_m");

$custom_bg_image_position_tablet = get_field('custom_bg_image_position_tablet');
$custom_bg_image_position_pc = get_field('custom_bg_image_position_pc');
$custom_bg_image_position_m = get_field('custom_bg_image_position_m');

$link_color = get_field('link_color');
$link_color_hover = get_field('link_color_hover');

$align_content = get_field('align_content');
$align_content_tablet = get_field('align_content_tablet');
$align_content_m = get_field('align_content_m');

$content_position = get_field('content_position');
$content_position_tablet = get_field('content_position_tablet');
$content_position_m = get_field('content_position_m');

$content_position = empty($content_position) ? 'center' : $content_position;
$content_position_tablet = empty($content_position_tablet) ? 'center' : $content_position_tablet;
$content_position_m = empty($content_position_m) ? 'center' : $content_position_m;

$border_width = get_field('border_width');
$border_width_tablet = get_field('border_width_tablet');
$border_width_m = get_field('border_width_m');

$border_color = get_field('border_color');
$border_color_tablet = get_field('border_color_tablet');
$border_color_m = get_field('border_color_m');

$border_style = get_field('border_style');
$border_style_tablet = get_field('border_style_tablet');
$border_style_m = get_field('border_style_m');

$box_shadow = get_field('box_shadow');
$box_shadow_tablet = get_field('box_shadow_tablet');
$box_shadow_m = get_field('box_shadow_m');

$hidden = get_field('hidden');
$hidden_tablet = get_field('hidden_tablet');
$hidden_m = get_field('hidden_m');

$z_index = get_field('z_index');
$z_index_tablet = get_field('z_index_tablet');
$z_index_m = get_field('z_index_m');

$shape = get_field('shape');
$shape_tablet = get_field('shape_tablet');
$shape_m = get_field('shape_mobile');

$html_id = 'ttg-styling-box-' . $id;

$direction = get_field('direction');
$direction_tablet = get_field('direction_tablet');
$direction_m = get_field('direction_m');

$direction = !empty($direction) ? $direction : 'column';
$direction_tablet = !empty($direction_tablet) ? $direction_tablet : 'column';
$direction_m = !empty($direction_m) ? $direction_m : 'column';


if ($custom_bg_image_position_tablet != 'custom') {
    $custom_bg_image_position_tablet = $image_position[$bg_image_position_tablet];
}

if ($bg_image_position != 'custom') {
    $custom_bg_image_position_pc = $image_position[$bg_image_position];
}

if ($bg_image_position_m != 'custom') {
    $custom_bg_image_position_m = $image_position[$bg_image_position_m];
}


$attrs_config = array(
    'class' => [
        get_position($content_position_m),
        get_position($content_position_tablet),
        get_position($content_position_lg, 'lg'),
        'position-relative ttg-styling-box',
        $default_class
    ],
    'style' => [
        '--styling-box-width' => empty($width) ? 'initial' : $width,
        '--styling-box-width-tablet' => empty($width_tablet) ? 'initial' : $width_tablet,
        '--styling-box-width-m' => empty($width_m) ? 'initial' : $width_m,

        '--styling-box-height' => empty($height) ? 'initial' : $height,
        '--styling-box-height-tablet' => empty($height_tablet) ? 'initial' : $height_tablet,
        '--styling-box-height-m' => empty($height_m) ? 'initial' : $height_m,

        '--styling-box-p' => empty($p) ? '0px 0px' : $p,
        '--styling-box-p-tablet' => empty($p_tablet) ? '0px 0px' : $p_tablet,
        '--styling-box-p-m' => empty($p_m) ? '0px 0px' : $p_m,

        '--styling-box-mar' => empty($mar) ? '0px 0px' : $mar,
        '--styling-box-mar-tablet' => empty($mar_tablet) ? '0px 0px' : $mar_tablet,
        '--styling-box-mar-m' => empty($mar_m) ? '0px 0px' : $mar_m,

        '--styling-box-border-width' => empty($border_width) ? '0px' : $border_width,
        '--styling-box-border-width-tablet' => empty($border_width_tablet) ? '0px' : $border_width_tablet,
        '--styling-box-border-width-m' => empty($border_width_m) ? '0px' : $border_width_m,

        '--styling-box-border-color' => empty($border_color) ? '#000' : $border_color,
        '--styling-box-border-color-tablet' => empty($border_color_tablet) ? '#000' : $border_color_tablet,
        '--styling-box-border-color-m' => empty($border_color_m) ? '#000' : $border_color_m,

        '--styling-box-border-style' => empty($border_style) ? 'solid' : $border_style,
        '--styling-box-border-style-tablet' => empty($border_style_tablet) ? 'solid' : $border_style_tablet,
        '--styling-box-border-style-m' => empty($border_style_m) ? 'solid' : $border_style_m,

        '--styling-box-max-width' => empty($max_width) ? '100%' : $max_width,
        '--styling-box-max-width-tablet' => empty($max_width_tablet) ? '100%' : $max_width_tablet,
        '--styling-box-max-width-m' => empty($max_width_m) ? '100%' : $max_width_m,

        '--styling-box-bg-size' => !empty($bg_size) ? $bg_size : 'cover',
        '--styling-box-bg-size-tablet' => !empty($bg_size_tablet) ? $bg_size_tablet : 'cover',
        '--styling-box-bg-size-m' => !empty($bg_size_m) ? $bg_size_m : 'cover',

        '--styling-box-border-radius' => !empty($border_radius) ? $border_radius : '0px',
        '--styling-box-border-radius-tablet' => !empty($border_radius_tablet) ? $border_radius_tablet : '0px',
        '--styling-box-border-radius-m' => !empty($border_radius_m) ? $border_radius_m : '0px',

        '--styling-box-box-shadow' => !empty($box_shadow) ? $box_shadow : 'none',
        '--styling-box-box-shadow-tablet' => !empty($box_shadow_tablet) ? $box_shadow_tablet : 'none',
        '--styling-box-box-shadow-m' => !empty($box_shadow_m) ? $box_shadow_m : 'none',

        '--styling-box-z-index' => !empty($z_index) ? $z_index : '0',
        '--styling-box-z-index-tablet' => !empty($z_index_tablet) ? $z_index_tablet : '0',
        '--styling-box-z-index-m' => !empty($z_index_m) ? $z_index_m : '0',

        '--styling-box-ratio' => $shape === 'square' ? '1' : 'auto',
        '--styling-box-ratio-tablet' => $shape_tablet === 'square' ? '1' : 'auto',
        '--styling-box-ratio-m' => $shape_m === 'square' ? '1' : 'auto',

        '--styling-box-postion' => empty($z_index) ? 'static' : '',
        '--styling-box-postion-tablet' => empty($z_index_tablet) ? 'static' : '',
        '--styling-box-postion-m' => empty($z_index_m) ? 'static' : '',
    ],
    'id' => $html_id
);

if ($hidden) {
    $attrs_config['class'][] = 'd-lg-none';
} else {
    $attrs_config['class'][] = 'd-lg-flex';
}

if ($hidden_tablet) {
    $attrs_config['class'][] = 'd-md-none';
} else {
    $attrs_config['class'][] = 'd-md-flex';
}

if ($hidden_m) {
    $attrs_config['class'][] = 'd-none';
} else {
    $attrs_config['class'][] = 'd-flex';
}

if ($container_tag === 'a' && $url) {
    $attrs_config['href'] = $url;
}

$attrs = TTG_Block_HTML_Helpers::attrs($attrs_config);

$attrs_classes = ['d-flex flex-wrap'];

$attrs_classes[] = get_direction($direction_m);
$attrs_classes[] = get_direction($direction_tablet);
$attrs_classes[] = get_direction($direction);

if (!empty($align_content_m)) {
    $attrs_classes[] = get_inner_position($align_content_m);
}

if (!empty($align_content_tablet)) {
    $attrs_classes[] = get_inner_position($align_content_tablet, 'md');
}

if (!empty($align_content)) {
    $attrs_classes[] = get_inner_position($align_content, 'lg');
}

$attrs_classes[] = 'position-relative ttg-styling-box__inner';


$attrs_inner = TTG_Block_HTML_Helpers::attrs(array(
    'class' => $attrs_classes,
));

?>
<style>
    <?php
    if (!empty($link_color)) {
        echo '#' . $html_id . " a{ color: " . $link_color . " }";
    }
    if (!empty($link_color)) {
        echo '#' . $html_id . " a:hover{ color: " . $link_color_hover . " }";
    }
    ?>
</style>
<<?php echo $container_tag; ?> <?php echo $attrs; ?>>
    <div class="position-absolute ttg-styling-box__bg">
        <?php
        $bg = '';
        if ($bg_type === 'normal') {
            $bg = TTG_Blocks_Template_Parts_Helper::bg_color($bg_color, $bg_blur, $bg_color_opacity);
        }
        if ($bg_type === 'gradient') {
            $bg = TTG_Blocks_Template_Parts_Helper::bg_color_gradient($bg_color_start, $bg_color_end, $bg_color_angle, $bg_color_gradient);
        }

        if ($bg_type === 'image') {
            $bg = TTG_Blocks_Template_Parts_Helper::bg_image(
                $bg_images['image_pc'],
                $bg_images['image_tablet'],
                $bg_images['image_mobile'],
                $custom_bg_image_position_pc,
                $custom_bg_image_position_tablet,
                $custom_bg_image_position_m,
                $is_parallax
            );
        }

        if ($bg_type === 'video') {
            $bg = TTG_Blocks_Template_Parts_Helper::media([
                'type' => $media_type,
                'youtube_id' => $youtube_id,
                'vimeo_id' => $vimeo_id,
                'poster' => $poster,
                'file' => $video,
                'auto_play' => true
            ]);
        }

        if (!empty($bg)) {
            echo sprintf('<div class="overflow-hidden w-full h-full position-absolute ttg-background">%s</div>', $bg);
        }
        ?>

    </div>
    <div <?php echo $attrs_inner; ?>>
        <InnerBlocks />
    </div>
</<?php echo $container_tag; ?>>