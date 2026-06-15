<?php
extract($args);
if (!empty($id)) {
    $config = TTG_Config::get_post_heading($id);
    $attr = [
        'style' => [
            '--hero-banner-height-pc' => $config['pc']['height'],
            '--hero-banner-height-tablet' => $config['tablet']['height'],
            '--hero-banner-height-mobile' => $config['mobile']['height'],
        ]
    ];
?>
    <header <?php echo TTG_Util::generate_html_attrs($attr) ?> class="single-post-header single-post-header--medium">
        <div class="single-post-header__bg">
            <?php
            echo TTG_Template::get_template_part('hero-banner', ['id' => $id]);
            ?>
        </div>
    </header>
<?php } ?>