<?php
class Load_Scripts_Helpers
{
    public static $scripts = [];

    public static function add_script($handle, $url, $order = 0)
    {
        self::$scripts[$handle] =  [
            'url' => $url,
            'order' => $order
        ];
    }

    public static function generate()
    {
        if (!empty(self::$scripts) && !is_admin()) {
            usort(self::$scripts, function ($a, $b) {
                return ($a['order'] < $b['order']) ? -1 : 1;
            });
            foreach (self::$scripts as $key => $value) {
?>
                <script type="text/javascript" src="<?php echo $value['url'] ?>" defer></script>
<?php
            }
        }
    }
}
