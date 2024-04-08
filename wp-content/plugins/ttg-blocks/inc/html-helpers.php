<?php

class TTG_Block_HTML_Helpers
{
    public static function get_image($image_pc_key, $image_mobile_key, $image_tablet_key = '')
    {
        $image_pc = get_field($image_pc_key);
        $image_mobile = get_field($image_mobile_key);
        $image_tablet = '';


        if (empty($image_mobile)) {
            $image_mobile = $image_pc;
        }

        if (!empty($image_tablet_key)) {
            $image_tablet = get_field($image_tablet_key);
            if (empty($image_tablet)) {
                $image_tablet = $image_pc;
            }
        }


        return [
            'image_tablet' => $image_tablet,
            'image_pc' => $image_pc,
            'image_mobile' => $image_mobile
        ];
    }

    public static function attrs($attrs = [])
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
}
