<?php
class TTG_Blocks_Template_Parts_Helper
{
    public static function render($file, $data = [])
    {
        ob_start();
        $path = TTG_Blocks_Utils::get_path('partials/' . $file . '.php');
        load_template($path, false, $data);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public static function bg_color($color = '', $blur = 0, $opacity = 1)
    {
        return self::render('bg-color', [
            'color' => $color,
            'blur' => $blur,
            'opacity' => $opacity
        ]);
    }

    public static function bg_color_gradient($start_color = '', $end_color = '', $angle = 0, $color_gradient = '')
    {
        return self::render('bg-color-gradient', [
            'start_color' => $start_color,
            'end_color' => $end_color,
            'angle' => $angle,
            'color_gradient' => $color_gradient,
        ]);
    }

    public static function bg_image(
        $image_pc = '',
        $image_tablet = '',
        $image_mobile = '',
        $bg_image_position = '',
        $bg_image_position_tablet = '',
        $bg_image_position_m = '',
        $is_parallax = false
    ) {
        return self::render('bg-image', [
            'image_pc' => $image_pc,
            'image_tablet' => $image_tablet,
            'image_mobile' => $image_mobile,
            'bg_image_position' => $bg_image_position,
            'bg_image_position_tablet' => $bg_image_position_tablet,
            'bg_image_position_m' => $bg_image_position_m,
            'is_parallax' => $is_parallax
        ]);
    }

    public static function bg_video(
        $video = [],
        $youtube_id = '',
        $poster = []
    ) {
        return self::render('bg-video', [
            'video' => $video,
            'youtube_id' => $youtube_id,
            'poster' => $poster
        ]);
    }

    /**
     * @param style
     * @param link
     * @param size
     * @param custom_style  
     *  ['type'] => 'solid | outline',
     *  ['style'] => ['pc'] => [], ['tablet'] => [], ['mobile'] => []
     */
    public static function button($params)
    {
        return self::render('button', $params);
    }

    public static function buttons($buttons = [])
    {
        return self::render('buttons', [
            'buttons' => $buttons
        ]);
    }

    public static function image($image_pc = [], $image_tablet = [], $image_mobile = [])
    {
        return self::render('image', [
            'image_pc' => $image_pc,
            'image_tablet' => $image_tablet,
            'image_mobile' => $image_mobile
        ]);
    }

    public static function blog_head($data)
    {
        return self::render('blog-head', $data);
    }


    // [
    //     'type' => 'string',
    //     'youtube_id' => 'string',
    //     'vimeo_id' => 'string',
    //     'poster' => 'acf image',
    //     'file' => 'acf file',
    //     'auto_play' => 'bool'
    // ]
    public static function media($data)
    {
        return self::render('media', $data);
    }
}
