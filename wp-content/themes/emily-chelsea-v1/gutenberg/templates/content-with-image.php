<?php
extract($args);

$type = get_field('type');
$image = get_field('image');
$content_position = get_field('content_position');
$content_background_color = get_field('content_background_color');
$content_background_color_m = $content_background_color;
$content_background_color_opacity = get_field('content_background_color_opacity');
$content_background_color_blur = get_field('content_background_color_blur');
$content_width = get_field('content_width');
$media_height = get_field('media_height');
$media_max_height = get_field('media_max_height');


$attrs = [
    'class' => ['ttg-content-with-image', $default_class],
    'id' => $default_id,
    'style' => [
        '--ttg-content-with-image-bg-height' => $media_height,
        '--ttg-content-with-image-bg-max-height' => $media_max_height,
    ]
];

if ($content_background_color_opacity < 10) {
    $content_background_color = $content_background_color . '0' . $content_background_color_opacity;
} else if ($content_background_color_opacity >= 10 && $content_background_color_opacity < 100) {
    $content_background_color = $content_background_color . $content_background_color_opacity;
}


$content_attrs = [
    'class' => ['ttg-content-with-image__content', 'ttg-content-with-image__content--' . $content_position],
    'style' => [
        '--ttg-content-with-image-bg-color' => $content_background_color,
        '--ttg-content-with-image-bg-color-m' => $content_background_color_m,
        '--ttg-content-with-image-width' => !empty($content_width) ? $content_width : '440px',
        '--ttg-content-with-image-bg-blur' => $content_background_color_blur . 'px',

    ]
];

if (empty($type)) {
    $type = 'image';
}

?>
<div <?php echo TTG_Util::generate_html_attrs($attrs) ?>>
    <div class="ttg-content-with-image__image">
        <div class="ttg-content-with-image__image__inner">
            <?php
            if (!empty($image) && $type  === 'image') {
                echo wp_get_attachment_image($image['id'], 'full');
            }
            if ($type !== 'image' && class_exists('TTG_Blocks_Template_Parts_Helper')) {
                $youtube_id = get_field('youtube_id');
                $vimeo_id = get_field('vimeo_id');
                $poster = get_field('poster');
                $file = get_field('file');
                $auto_play = get_field('autoplay');
                echo TTG_Blocks_Template_Parts_Helper::media(
                    [
                        'type' => $type,
                        'youtube_id' => $youtube_id,
                        'vimeo_id' => $vimeo_id,
                        'poster' => $poster,
                        'file' => $file,
                        'auto_play' => $auto_play,
                        'classes' => $default_class
                    ]
                );
            }
            ?>
        </div>
    </div>
    <div <?php echo TTG_Util::generate_html_attrs($content_attrs) ?>>
        <div class="ttg-content-with-image__content__inner">
            <div class="ttg-post">
                <InnerBlocks />
            </div>
        </div>
    </div>
</div>