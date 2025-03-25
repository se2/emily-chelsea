<?php
require get_template_directory() . '/tools/utils.php';
$file = get_template_directory()  . '/tools/data/tax.csv';
$products = csv_to_array($file);

if (!empty($products)) {
    try {
        foreach ($products as $key => $value) {
            $images = $value['Image Filename'];
            $alts = $value['Image Alt Text'];
            $images_args = explode("|", $images);
            $alts = explode("|", $alts);

            if (!empty($images_args)) {
                foreach ($images_args as $k => $v) {
                    if (!empty($v) && !empty($alts[$k])) {
                        $args = array(
                            'post_type' => 'attachment',
                            'post_status' => 'inherit',
                            'post_mime_type' => 'image',
                            's' => $v
                        );
                        $query_images = new WP_Query($args);
                        $posts =  $query_images->posts;
                        if (!empty($posts)) {
                            $p = $posts[0];
                            // update_post_meta($p->ID, '_wp_attachment_image_alt', $alts[$k]);
                            // print_r($p->ID);
                            // print_r($v);
                            // print_r($alts[$k]);
                            // echo '--------------';
                        } else {
                            print_r($v);
                            print_r($alts[$k]);
                            echo '--------------';
                        }
                    }
                }
            }
        }
    } catch (Exception $e) {
    }
}
