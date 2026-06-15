<?php
if (isset($_GET['removeDuplicate'])) {
    $items = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1
    ));
    if (!empty($items)) {
        foreach ($items as $key => $value) {
            $product = wc_get_product($value->ID);
            if ($product->is_type("variable")) {
                $variations = $product->get_children();
                $list = [];

                if (!empty($variations)) {
                    foreach ($variations as $v) {
                        $p = wc_get_product($v);
                        $attrs = $p->get_variation_attributes();
                        $key = implode("-", $attrs);
                        if (isset($list[$key])) {
                            wp_delete_post($v);
                        } else {
                            $list[$key] = $v;
                        }
                    }
                    var_dump($list);
                    echo "----------------------------------------\\n";
                }
            }
        }
    }
}
