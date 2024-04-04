<?php
extract($args);
$type = $data['type'];
$video_id = '';
if ($type === 'youtube') {
    $video_id = $data['youtube_id'];
} else if ($type === 'vimeo') {
    $video_id = $data['vimeo_id'];
} else if ($type === 'file') {
    $video_id = $data['file']['url'];
}
$attr = [
    "class" => 'woocommerce-product-gallery__image',
    'data-type' => $type,
    'video-id' => $video_id,
    'data-target' => 'product-gallery-video-' . $index
];

?>
<div <?php echo TTG_Util::generate_html_attrs($attr); ?>>
    <a href="">
        <?php
        if (!empty($data['poster'])) {
            echo wp_get_attachment_image($data['poster']['id']);
        }
        ?>
        <div class="play-icon">
            <?php echo TTG_Template::get_icon('play'); ?>
        </div>

    </a>
</div>