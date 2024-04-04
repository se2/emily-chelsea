<?php
$config = TTG_Config::get_footer_config();
extract($config);

$attrs = [
    'style' => [
        '--footer-background-color' => $footer_background_color,
        '--footer-text-color' => $footer_text_color,
        '--footer-line-color' => $footer_line_color,
        '--footer-logo-color' => $footer_logo_color
    ]
];

if (!empty($footer_background_image)) {
    $attrs['style']['background-image'] = "url(" . $footer_background_image['url'] . ")";
}

if (!empty($footer_enable_parallax)) {
    $attrs['style']['background-attachment'] = 'fixed';
}

?>
<footer id="main-footer" class="main-footer" <?php echo TTG_Util::generate_html_attrs($attrs) ?>>
    <div class="main-footer__bg">
        <?php
        if ($footer_background_type && $footer_background_type !== 'image') {
            echo TTG_Blocks_Template_Parts_Helper::media(
                [
                    'type' => $footer_background_video_type,
                    'youtube_id' => $footer_background_youtube_id,
                    'vimeo_id' => $footer_background_vimeo_id,
                    'poster' => $footer_background_poster,
                    'file' => $footer_background_file,
                    'auto_play' => true,
                ]
            );
        }
        ?>
    </div>
    <div class="main-footer__top">
        <?php
        if (!empty($menus)) {
        ?>
            <div class="d-flex main-footer__item main-footer__menu">
                <?php
                foreach ($menus as $value) {
                    wp_nav_menu(array(
                        "menu" => $value['menu'],
                        "container" => false
                    ));
                }
                ?>
            </div>
        <?php
        }
        ?>

        <div class="main-footer__item text-center main-footer__info">
            <div class="ttg-post main-footer__address">
                <?php
                the_field('address', 'options');
                ?>
            </div>
            <a class="d-block main-footer__logo" href="<?php echo get_bloginfo('url') ?>">
                <?php echo TTG_Template::get_icon('logo'); ?>
            </a>
            <div class="ttg-post main-footer__address">
                <?php
                the_field('open_times', 'options');
                ?>
            </div>
        </div>

        <div class="main-footer__item text-center main-footer__social">
            <?php
            echo TTG_Template::get_template_part('socials', ['socials' => $social]);
            ?>
            <?php
            if (!empty($certified_image)) {
            ?>
                <div class="main-footer__certified">
                    <?php echo wp_get_attachment_image($certified_image['id'], 'full') ?>
                </div>
            <?php
            }
            ?>
        </div>

    </div>
    <div class="main-footer__bottom">
        <span class="main-footer__copyright">©2023 Emily Chelsea Jewelry. All Rights Reserved.</span>
        <?php
        if (!empty($menu_bottom)) {
            wp_nav_menu(array(
                "menu" => $menu_bottom,
                "container" => false,
                'menu_id' => "menu-footer-bottom"
            ));
        }
        ?>
    </div>
</footer>