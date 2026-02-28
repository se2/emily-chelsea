<?php
add_filter('body_attr_style', function ($attr) {
    $config = TTG_Config::get_header_config();

    $header_menu_close = $config['menu_close'];
    $attr['--header-menu-close-hamburger-color'] = $header_menu_close['header_hamburger_color'];
    $attr['--header-menu-close-hamburger-color-hover'] = $header_menu_close['header_hamburger_color_hover'];
    $attr['--header-menu-close-hamburger-text-color'] = $header_menu_close['header_hamburger_text_color'];
    $attr['--header-menu-close-hamburger-text-color-hover'] = $header_menu_close['header_hamburger_text_color_hover'];

    $header_menu_open = $config['menu_open'];
    $attr['--header-menu-open-hamburger-color'] = $header_menu_open['header_hamburger_color'];
    $attr['--header-menu-open-hamburger-color-hover'] = $header_menu_open['header_hamburger_color_hover'];
    $attr['--header-menu-open-hamburger-text-color'] = $header_menu_open['header_hamburger_text_color'];
    $attr['--header-menu-open-hamburger-text-color-hover'] = $header_menu_open['header_hamburger_text_color_hover'];

    $attr['--header-menu-text-color'] = $config['menu_text_color'];
    $attr['--header-menu-background-outer-color'] = $config['menu_background_outer_color'];
    $attr['--header-menu-background-inner-color'] = $config['menu_background_inner_color'];

    $logo = $config['logo'];
    $attr['--header-logo-color'] = $logo['color'];
    $attr['--header-logo-color-hover'] = $logo['hover_color'];

    $cart_icon = $config['cart_icon'];
    $attr['--header-cart-icon-color'] = $cart_icon['color'];
    $attr['--header-cart-icon-color-hover'] = $cart_icon['hover_color'];

    $cart_icon_counter = $config['cart_icon_counter'];
    $attr['--header-cart-icon-counter-color'] = $cart_icon_counter['color'];
    $attr['--header-cart-icon-counter-color-hover'] = $cart_icon_counter['hover_color'];

    $cart_icon_counter_bg = $config['cart_icon_counter_bg'];
    $attr['--header-cart-icon-counter-bg-color'] = $cart_icon_counter_bg['color'];
    $attr['--header-cart-icon-counter-bg-color-hover'] = $cart_icon_counter_bg['hover_color'];

    $search_icon = $config['search_icon'];
    $attr['--header-search-icon-color'] = $search_icon['color'];
    $attr['--header-search-icon-color-hover'] = $search_icon['hover_color'];

    $attr['--header-background-color'] = $config['header_background_color'];
    $attr['--header-line-color'] = $config['header_line_color'];

    $header_sticky = $config['header_sticky'];
    $attr['--header-sticky-background-color'] = $header_sticky['background_color'];

    $attr['--hero-banner-height-mobile-default'] = '160px';
    $attr['--hero-banner-height-tablet-default'] = '215px';
    $attr['--hero-banner-height-pc-default'] = '215px';



    if (is_page() && !is_shop() && !is_account_page() && !is_tax()) {
        $attr['--hero-banner-height-mobile-default'] = '460px';
        $attr['--hero-banner-height-tablet-default'] = '600px';
        $attr['--hero-banner-height-pc-default'] = '600px';
    }

    return $attr;
});
