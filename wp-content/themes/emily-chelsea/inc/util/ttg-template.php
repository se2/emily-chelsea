<?php
class TTG_Template
{
    public static function render($file, $data = [])
    {
        ob_start();
        get_template_part('/template-parts/' . $file, null, $data);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public static function get_template_part($name, $data = [])
    {
        return self::render($name, $data);
    }

    public static function get_icon($name)
    {

        return self::render('icons/' . $name);
    }
}
