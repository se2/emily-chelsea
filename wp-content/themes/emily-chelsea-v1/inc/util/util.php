<?php

class TTG_Util
{
    public static function generate_html_attrs($attrs = [])
    {
        if (count($attrs) <= 0 || !is_array($attrs)) {
            return '';
        }

        $after_format = [];
        foreach ($attrs as $key => $attr) {
            if (is_array($attr) && $key === 'style') {
                $items = [];
                foreach ($attr as $k => $v) {
                    if (!empty($v)) {
                        $items[] = sprintf('%s:%s', $k, $v);
                    }
                }
                $after_format[] = sprintf('%s="%s"', $key, join(';', $items));
            } else if (is_array($attr) && $key === 'class') {
                $after_format[] = sprintf('%s="%s"', $key, join(' ', $attr));
            } else {
                $after_format[] = sprintf('%s="%s"', $key, $attr);
            }
        }

        return join(" ", $after_format);
    }

    public static function generate_classes($classes = [])
    {
        return join(" ", $classes);
    }

    public static function hex2rgba($hex, $alpha = 1)
    {
        $hex      = str_replace('#', '', $hex);
        $length   = strlen($hex);
        $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
        $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
        $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
        $rgb['a'] = $alpha;

        return sprintf("rgba(%s,%s,%s,%s)", $rgb['r'], $rgb['g'], $rgb['b'], $rgb['a']);
    }

    public static function get_assets_url($path = '')
    {
        $asset_base_url = get_template_directory_uri() . '/src/';
        return  $asset_base_url . $path;
    }

    public static function get_main_term($post_id, $tax = 'category')
    {
        $term = '';
        if (function_exists('yoast_get_primary_term_id')) {
            $term = yoast_get_primary_term($tax, 1);
        }

        if (empty($primary_term_id)) {
            $terms = wp_get_post_terms($post_id, $tax);
            if (!empty($terms)) {
                $term = $terms[0];
            }
        }

        return $term;
    }

    public static function get_acf_key()
    {

        if (is_404()) {
            return get_field('page_404', 'options');
        }
        if (is_singular('product')) {
            global $post;
            $is_independence_header_config = get_field('is_independence_header_config', $post->ID);
            if (!$is_independence_header_config) {
                return get_option('woocommerce_shop_page_id');
            }

            return $post->ID;
        }

        if (is_singular()) {
            global $post;
            return $post->ID;
        }

        if (is_tax()) {
            $term = get_queried_object();
            $is_independence_header_config = get_field('is_independence_header_config', $term);
            if (!$is_independence_header_config) {
                return get_option('woocommerce_shop_page_id');
            }

            return get_queried_object();
        }

        if (is_shop()) {
            return get_option('woocommerce_shop_page_id');
        }

        if (is_home()) {
            return get_option('page_for_posts');
        }

        if (is_search()) {
            $search = get_field('search_page', 'options');
            if (!empty($search)) {
                return $search->ID;
            }
        }

        return '';
    }
}
