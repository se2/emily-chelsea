<?php
extract($args);
$is_exist_video = false;

if ($type === 'youtube' && !empty($youtube_id)) {
    $is_exist_video = true;
} else if ($type === 'vimeo' && !empty($vimeo_id)) {
    $is_exist_video = true;
} else if ($type === 'file' && !empty($file)) {
    $is_exist_video = true;
}

$attrs = [
    'class' => ['ttg-media', $classes]
];

if (empty($poster)) {
    $attrs['class'][] = 'ttg-media--no-poster';
}

if (!$is_exist_video) {
    $attrs['class'][] = 'ttg-media--no-video';
}

?>
<div <?php echo TTG_Block_HTML_Helpers::attrs($attrs) ?>>
    <div class="ttg-media__inner">
        <?php
        if (!empty($poster)) {
        ?>
            <div class="ttg-media__poster">
                <?php echo wp_get_attachment_image($poster['id'], 'full', false); ?>
            </div>
        <?php
        }
        ?>
        <?php
        if (!$auto_play) {
        ?>
            <div class="ttg-media__play disabled" data-type="<?php echo $type ?>">
                <div class="ttg-media__play__icon">
                    <div class="ttg-media__play__icon__play">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.00684 24.4651L24.792 14.8066L9.00684 6.02142V24.4651Z" fill="currentColor" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 3L27 15L7.5 27V3ZM9.7519 22.9728L22.7127 15L9.7519 7.02619V22.9728Z" fill="currentColor" />
                        </svg>
                    </div>
                    <div class="ttg-media__play__icon__pause">
                        <svg height="512px" viewBox="0 0 512 512" width="512px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g>
                                <path fill="currentColor" d="M224,435.8V76.1c0-6.7-5.4-12.1-12.2-12.1h-71.6c-6.8,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6   C218.6,448,224,442.6,224,435.8z" />
                                <path fill="currentColor" d="M371.8,64h-71.6c-6.7,0-12.2,5.4-12.2,12.1v359.7c0,6.7,5.4,12.2,12.2,12.2h71.6c6.7,0,12.2-5.4,12.2-12.2V76.1   C384,69.4,378.6,64,371.8,64z" />
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <div class="ttg-media__video-wrapper ttg-media__video-wrapper--<?php echo $type; ?>">
            <div class="ttg-media__video-wrapper__inner" data-type="<?php echo $type ?>">
                <?php
                if ($type === 'youtube' && !empty($youtube_id)) {
                ?>
                    <div data-autoplay="<?php echo $auto_play ?>" class="ttg-media__video youtube-player" data-type="<?php echo $type ?>" data-id="<?php echo $youtube_id; ?>" id="youtube-player-<?php echo uniqid() ?>"></div>
                <?php
                }
                ?>
                <?php
                if ($type === 'vimeo' && !empty($vimeo_id)) {
                ?>
                    <div data-autoplay="<?php echo $auto_play ?>" class="ttg-media__video vimeo-player" data-type="<?php echo $type ?>" data-id="<?php echo $vimeo_id; ?>" id="vimeo-player-<?php echo uniqid() ?>"></div>
                <?php
                }
                ?>
                <?php
                if ($type === 'file' && !empty($file)) {
                ?>
                    <div data-autoplay="<?php echo $auto_play ?>" class="ttg-media__video file-player" data-type="<?php echo $type ?>">
                        <video class="w-full h-full d-block" id="file-player-<?php echo uniqid() ?>">
                            <source data-src="<?php echo $file['url'] ?>" type="video/mp4">Your browser does not support the video tag.
                        </video>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="ttg-media__center"></div>
</div>