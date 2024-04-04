<?php
extract($args);

if (!empty($id)) {
    $type = get_field('hero_banner_type', $id);

    if (empty($type)) {
        $type = 'image';
    }

    if ($type === 'image') {
        $hero_banner_image_position_x = get_field('hero_banner_image_position_x', $id);
        $hero_banner_image_position_y = get_field('hero_banner_image_position_y', $id);
        $image = get_field('hero_banner_image', $id);
        $image_html = !empty($image['id']) ? wp_get_attachment_image($image['id'], 'full') : '';

        if (empty($image_html) && is_numeric($id)) {
            $image_html = get_the_post_thumbnail($id, 'full');
        }

        $hero_banner_img = [
            'img' => $image_html,
            'position' => [
                'x' => $hero_banner_image_position_x,
                'y' => $hero_banner_image_position_y
            ]
        ];
        $hero_banner_img = apply_filters('ttg_hero_banner_img', $hero_banner_img, $id);

        $attr_bg = [
            'class' => ['ttg-bg'],
            'style' => [
                '--ttg-bg-image-position-x' => $hero_banner_img['position']['x'],
                '--ttg-bg-image-position-y' => $hero_banner_img['position']['y']
            ]
        ];

        if (empty($image['id'])) {
            $attr_bg['class'][] = 'ttg-bg--no-image';
        }

?>
        <div <?php echo TTG_Util::generate_html_attrs($attr_bg) ?>>
            <?php
            echo $hero_banner_img['img'];
            ?>
        </div>
<?php
    }

    if ($type !== 'image' && class_exists('TTG_Blocks_Template_Parts_Helper')) {
        $youtube_id = get_field('hero_banner_youtube_id', $id);
        $vimeo_id = get_field('hero_banner_vimeo_id', $id);
        $poster = get_field('hero_banner_poster', $id);
        $file = get_field('hero_banner_file', $id);
        $auto_play = get_field('hero_banner_autoplay', $id);
        if (class_exists('TTG_Blocks_Template_Parts_Helper')) {
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
    }
}
