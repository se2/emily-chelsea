<?php
extract($args);
if (!empty($poster)) {
    $image = wp_get_attachment_image(
        $poster['id'],
        'full',
        false,
        array(
            'class' => 'd-block w-full h-full object-cover object-center'
        )
    );
    $image = sprintf('<div class="position-absolute w-full h-full ttg-background__poster">%s</div>', $image);
}
$video_html = sprintf('%s<div class="position-absolute w-full ttg-background__youtube"><div id="ytplayer-%s"  class="video-youtube" data-id="%s"></div></div>', $image, uniqid(), $youtube_id);
if (!empty($video)) {
    $video_html = sprintf('%s<div class="position-absolute w-full ttg-background__video"><video class="w-full h-full d-block" autoplay muted loop playsinline><source src="%s" type="video/mp4">Your browser does not support the video tag.</video></div>', '', $video['url']);
}
echo $video_html;
