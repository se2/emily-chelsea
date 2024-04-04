<div id="main-navigation" class="main-navigation">
    <div class="main-navigation__top">

    </div>
    <div class="main-navigation__middle">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'menu-main',
            'container' => false
        ))
        ?>
    </div>
    <div class="main-navigation__bottom">
        <?php
        $key  = 'options';
        if (is_singular(['post', 'page', 'product'])) {
            global $post;
            $key = $post->ID;
        }
        $config = TTG_Config::get_header_config($key);
        echo TTG_Template::get_template_part('socials', ['socials' => $config['social']]);
        ?>
    </div>
</div>