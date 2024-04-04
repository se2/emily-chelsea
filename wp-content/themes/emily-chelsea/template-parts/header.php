<?php
$p = '';
$id = TTG_Util::get_acf_key();
$config = TTG_Config::get_post_heading($id);

$attr = [
    'style' => [
        '--header-line-height-pc' => $config['pc']['height'],
        '--header-line-height-tablet' => $config['tablet']['height'],
        '--header-line-height-m' => $config['mobile']['height'],
    ]
];
?>

<header id="main-header" class="main-header">
    <div class="main-header__sticky">
        <div class="main-header__inner">
            <div class="main-header__logo">
                <a href="<?php echo get_bloginfo('url') ?>">
                    <?php echo TTG_Template::get_icon('logo'); ?>
                </a>
            </div>
            <div class="main-header__right">
                <div class="main-header__actions">
                    <div class="header-search">
                        <form action="<?php echo home_url() ?>" class="header-search__form">
                            <input class="header-search__form__toggle" type="checkbox">
                            <div class="header-search__form__inner">
                                <div class="header-search__form__input-wrapper">
                                    <input value="<?php echo get_query_var('s') ?>" placeholder="Search..." type="text" class="form-control" name="s">
                                </div>
                                <button class="header-search__form__button" type="button">
                                    <div class="header-search__form__button__close"><?php echo TTG_Template::get_icon('close'); ?></div>
                                    <div class="header-search__form__button__search"><?php echo TTG_Template::get_icon('search'); ?></div>
                                </button>
                            </div>
                        </form>
                    </div>
                    <a class="header-cart" href="<?php echo wc_get_cart_url() ?>">
                        <?php echo TTG_Template::get_icon('cart'); ?>
                        <span class="header-cart__count">
                            <?php echo WC()->cart->get_cart_contents_count(); ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="main-header__inner-placeholder"></div>
</header>

<?php

if (is_singular('post')) {
    echo TTG_Template::get_template_part('single-post-heading', ['id' => $id]);
} else {
    $id = TTG_Util::get_acf_key();
    echo TTG_Template::get_template_part('page-heading', ['id' => $id]);
}
?>
<div <?php echo TTG_Util::generate_html_attrs($attr) ?> class="header-line"></div>