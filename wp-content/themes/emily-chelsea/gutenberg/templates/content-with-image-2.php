<?php
extract($args);

$type = get_field('type');
$image = get_field('image');
$content_position = get_field('content_position');
$content_outer_position = get_field('content_outer_position');
$content_background_color_opacity = get_field('content_background_color_opacity');
$content_background_color = get_field('content_background_color');
$content_background_color_m = $content_background_color;
$content_width = get_field('content_width');
$content_outer_width = get_field('content_outer_width');
$media_height = get_field('media_height');
$media_max_height = get_field('media_max_height');

$attrs = [
    'class' => ['ttg-content-with-image-2', $default_class],
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
    'class' => [
        'ttg-content-with-image-2__content',
        'ttg-content-with-image-2__content--' . $content_position,
        'ttg-content-with-image-2__content--outer-' . $content_outer_position,
    ],
    'style' => [
        '--ttg-content-with-image-2-bg-color' => $content_background_color,
        '--ttg-content-with-image-2-bg-color-m' => $content_background_color_m,
        '--ttg-content-with-image-2-outer-width' => !empty($content_outer_width) ? $content_outer_width : '',
        '--ttg-content-with-image-2-width' => !empty($content_width) ? $content_width : ''
    ]
];

if (empty($type)) {
    $type = 'image';
}

?>
<div <?php echo TTG_Util::generate_html_attrs($attrs) ?>>
    <div class="ttg-content-with-image-2__image">
        <div class="ttg-content-with-image-2__image__inner">
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
        <div class="ttg-post ttg-content-with-image-2__content__inner">
            <div class="ttg-post">
                <InnerBlocks />
            </div>

        </div>
    </div>
</div>