<?php
extract($args);

$type = get_field('type');
$youtube_id = get_field('youtube_id');
$vimeo_id = get_field('vimeo_id');
$poster = get_field('poster');
$file = get_field('file');
$auto_play = get_field('autoplay');


if (!empty($type) && !empty($youtube_id) || !empty($vimeo_id) || !empty($file)) {
    echo TTG_Blocks_Template_Parts_Helper::media([
        'type' => $type,
        'youtube_id' => $youtube_id,
        'vimeo_id' => $vimeo_id,
        'poster' => $poster,
        'file' => $file,
        'auto_play' => $auto_play,
        'classes' => $default_class
    ]);
}

?>
<?php
if (empty($youtube_id) && empty($vimeo_id) && empty($file)  && is_admin()) {
?>
    <div class="block-placeholder <?php echo esc_attr($classes); ?>">
        +
    </div>
<?php

}
?>