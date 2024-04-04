<?php

class TTG_Config
{
    public static function get_post_heading($id)
    {
        $pc = get_field('hero_banner_pc', $id);
        $tablet = get_field('hero_banner_tablet', $id);
        $mobile = get_field('hero_banner_mobile', $id);
        return [
            'pc' => $pc,
            'tablet' => $tablet,
            'mobile' => $mobile
        ];
    }

    public static function get_footer_config_by_id($post_id = '')
    {
        $id = get_field('footer_style', 'options');
        $footer_id = get_field('footer_style', $post_id);

        $menus = get_field('menus', 'options');
        $menu_bottom = get_field('menu_bottom', 'options');
        $address = get_field('address', 'options');
        $open_times = get_field('open_times', 'options');
        $social = get_field('social', 'options');
        $certified_image = get_field('certified_image', 'options');

        if (!empty($footer_id)) {
            $id = $footer_id;
        }

        $social_icons = get_field('social_icons', $id);
        $footer_background_color = get_field('footer_background_color', $id);
        $footer_text_color = get_field('footer_text_color', $id);
        $footer_line_color = get_field('footer_line_color', $id);
        $footer_background_type = get_field('footer_background_type', $id);
        $footer_enable_parallax = get_field('footer_enable_parallax', $id);
        $footer_background_image = get_field('footer_background_image', $id);
        $footer_background_poster = get_field('footer_background_poster', $id);
        $footer_background_file = get_field('footer_background_file', $id);
        $footer_background_youtube_id = get_field('footer_background_youtube_id', $id);
        $footer_background_vimeo_id = get_field('footer_background_vimeo_id', $id);
        $footer_logo_color = get_field('footer_logo_color', $id);

        if (!empty($social_icons) && !empty($social)) {
            foreach ($social as $key => $value) {
                if (!empty($social_icons[$key])) {
                    $social[$key]['image'] = $social_icons[$key]['image'];
                }
            }
        }

        return [
            'menus' => $menus,
            'menu_bottom' => $menu_bottom,
            'address' => $address,
            'open_times' => $open_times,
            'certified_image' => $certified_image,
            'social' => $social,
            'footer_background_color' => $footer_background_color,
            'footer_text_color' => $footer_text_color,
            'footer_line_color' =>  $footer_line_color,
            'footer_background_type' => $footer_background_type,
            'footer_enable_parallax' => $footer_enable_parallax,
            'footer_background_image' => $footer_background_image,
            'footer_background_poster' => $footer_background_poster,
            'footer_background_file' => $footer_background_file,
            'footer_background_youtube_id' => $footer_background_youtube_id,
            'footer_background_vimeo_id' => $footer_background_vimeo_id,
            'footer_logo_color' => $footer_logo_color
        ];
    }

    public static function get_header_config_by_id($post_id = 'options')
    {
        $id = get_field('header_style', 'options');
        $header_id = get_field('header_style', $post_id);
        $social = get_field('social', 'options');

        if (!empty($header_id)) {
            $id =  $header_id;
        }

        $header_menu_close = get_field('header_menu_close', $id);
        $header_menu_open = get_field('header_menu_open', $id);
        $logo = get_field('header_logo', $id);
        $cart_icon = get_field('header_cart_icon', $id);
        $cart_icon_counter = get_field('header_cart_icon_counter', $id);
        $cart_icon_counter_bg = get_field('header_cart_icon_counter_bg', $id);
        $search_icon = get_field('header_search_icon', $id);
        $header_background_color = get_field('header_background_color', $id);
        $header_line_color = get_field('header_line_color', $id);
        $social_icons = get_field('social_icons', $id);
        $menu_text_color = get_field('menu_text_color', $id);
        $menu_background_outer_color = get_field('menu_background_outer_color', $id);
        $menu_background_inner_color = get_field('menu_background_inner_color', $id);
        $header_sticky = get_field('header_sticky', $id);

        if (!empty($social_icons) && !empty($social)) {
            foreach ($social as $key => $value) {
                if (!empty($social_icons[$key])) {
                    $social[$key]['image'] = $social_icons[$key]['image'];
                }
            }
        }

        return [
            'menu_close' => $header_menu_close,
            'menu_open' => $header_menu_open,
            'logo' => $logo,
            'cart_icon' => $cart_icon,
            'cart_icon_counter' => $cart_icon_counter,
            'cart_icon_counter_bg' => $cart_icon_counter_bg,
            'search_icon' => $search_icon,
            'header_background_color' => $header_background_color,
            'header_line_color' => $header_line_color,
            'menu_background_outer_color' => $menu_background_outer_color,
            'menu_background_inner_color' => $menu_background_inner_color,
            'menu_text_color' => $menu_text_color,
            'social' => $social,
            'header_sticky' => $header_sticky
        ];
    }

    public static function get_header_config()
    {
        $key  = 'options';
        if (is_singular(['post', 'page', 'product'])) {
            global $post;
            $key = $post->ID;
        } else if (is_search()) {
            $search_page = get_field('search_page', 'options');
            $key = $search_page->ID;
        }

        return self::get_header_config_by_id($key);
    }

    public static function get_footer_config()
    {
        $key  = 'options';
        if (is_singular(['post', 'page', 'product'])) {
            global $post;
            $key = $post->ID;
        } else if (is_search()) {
            $search_page = get_field('search_page', 'options');
            $key = $search_page->ID;
        }

        return self::get_footer_config_by_id($key);
    }
}
