<?php
extract($args);
if (class_exists('TTG_Blocks_Template_Parts_Helper')) {
    $data = $value['data'];
    $video_type = $data['type'];
    $youtube_id = $data['youtube_id'];
    $vimeo_id = $data['vimeo_id'];
    $poster = $data['poster'];
    $file = $data['file'];
    $auto_play = true;
    $index  = $value['index'];
    $attr = [
        'class' => ['woocommerce-product-gallery__video'],
        'id' => 'product-gallery-video-' . $index
    ];

    if (!$has_thumb && $index == 0) {
        $auto_play = true;
    } else {
        $attr['class'][] = 'hide';
    }
?>
    <div <?php echo TTG_Util::generate_html_attrs($attr) ?>>
        <?php echo TTG_Blocks_Template_Parts_Helper::media(
            [
                'type' => $video_type,
                'youtube_id' => $youtube_id,
                'vimeo_id' => $vimeo_id,
                'poster' => $poster,
                'file' => $file,
                'auto_play' => $auto_play,
                'classes' => $default_class
            ]
        ); ?>
    </div>
<?php
}
?>