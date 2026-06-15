<?php
extract($args);
if (!empty($id)) {
    $term = TTG_Util::get_main_term($id);
    $config = TTG_Config::get_post_heading($id);

    $attr = [
        'style' => [
            '--hero-banner-height-pc' => $config['pc']['height'],
            '--hero-banner-height-tablet' => $config['tablet']['height'],
            '--hero-banner-height-mobile' => $config['mobile']['height'],
        ]
    ];
?>
    <header <?php echo TTG_Util::generate_html_attrs($attr) ?> class="single-post-header">
        <div class="single-post-header__bg">
            <?php
            echo TTG_Template::get_template_part('hero-banner', ['id' => $id]);
            ?>
        </div>
        <div class="single-post-header__content">
            <?php
            if (!empty($term)) {
            ?>
                <div class="single-post-header__cat"><?php echo $term->name ?></div>
            <?php
            }
            ?>
            <h1 class="heading-large single-post-header__title"><?php echo $post->post_title; ?></h1>
        </div>
    </header>
<?php } ?>