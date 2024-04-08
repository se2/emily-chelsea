<?php
extract($args);

$more_text = get_field('more_text');
$close_text = get_field('close_text');
$font_more_close_size = get_field('font_more_close_size');
$color_more_close_text = get_field('color_more_close_text');
if (empty($more_text)) {
    $more_text = 'Show more';
}
if (empty($close_text)) {
    $close_text = 'Close Bio';
}

$attrs = [
    'style' => [
        '--show-more-text-font-size' => $font_more_close_size,
        '--show-more-text-color' => $color_more_close_text
    ]
];

?>
<div class="ttg-show-more-text">
    <div class="ttg-show-more-text__content">
        <InnerBlocks />
    </div>
    <div class="ttg-show-more-text__title">
        <span class="ttg-show-more-text__title__more"><?php echo $more_text ?></span>
        <span class="ttg-show-more-text__title__close"><?php echo $close_text ?></span>
        <span class="ttg-show-more-text__icon">
            <span class="ttg-show-more-text__icon__open">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">

                    <g>
                        <g transform="translate(50 50) scale(0.69 0.69) rotate(0) translate(-50 -50)">
                            <g>
                                <path fill="currentColor" class="st0" d="M54.3,76.3l43.9-43.7c2.4-2.5,2.4-6.4,0-8.8s-6.4-2.4-8.8,0L50,63.1L10.6,23.7c-2.5-2.4-6.4-2.4-8.8,0
				s-2.4,6.3,0,8.8l43.7,43.7C48.1,78.7,51.9,78.7,54.3,76.3z" />
                            </g>
                        </g>
                    </g>
                </svg>
            </span>
            <span class="ttg-show-more-text__icon__close">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1200 1200" style="enable-background:new 0 0 1200 1200;" xml:space="preserve">
                    <path fill="currentColor" d="M861.1,407.7c9.7-9.4,14.5-20.9,14.4-34.4c0.1-13.5-4.7-25.1-14.4-34.8c-9.4-9.4-20.9-14.2-34.4-14.4
	c-13.5,0.1-25.1,4.9-34.8,14.4L600,530.9L407.7,338.5c-9.4-9.4-20.9-14.2-34.4-14.4c-13.5,0.1-25.1,4.9-34.8,14.4
	c-9.4,9.7-14.2,21.3-14.4,34.8c0.1,13.5,4.9,25,14.4,34.4L530.9,600L338.5,792c-9.4,9.7-14.2,21.3-14.4,34.8
	c0.1,13.5,4.9,25,14.4,34.4c9.7,9.7,21.3,14.5,34.8,14.4c13.5,0.1,25-4.7,34.4-14.4l192.3-192l192,192c9.7,9.7,21.3,14.5,34.8,14.4
	c13.5,0.1,25-4.7,34.4-14.4c9.7-9.4,14.5-20.9,14.4-34.4c0.1-13.5-4.7-25.1-14.4-34.8l-192-192L861.1,407.7z" />
                </svg>
            </span>
        </span>
    </div>
</div>