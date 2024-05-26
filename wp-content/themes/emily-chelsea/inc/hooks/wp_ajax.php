<?php
add_action('wp_ajax_get_cart_counter', 'get_cart_counter');
add_action('wp_ajax_nopriv_get_cart_counter', 'get_cart_counter');
function get_cart_counter()
{
    echo wp_json_encode(array(
        'counter' => WC()->cart->get_cart_contents_count()
    ));
    wp_die();
}


add_action('wp_ajax_get_products_by_attr', 'get_products_by_attr');
add_action('wp_ajax_nopriv_get_products_by_attr', 'get_products_by_attr');
function get_products_by_attr()
{
    $parent_product = isset($_POST['parent_product_id']) ? $_POST['parent_product_id'] : 0;
    $meta_type = isset($_POST['meta_type']) ? $_POST['meta_type'] : '';
    $pa_sizes = get_terms(array(
        'taxonomy' => 'pa_size',
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ));
    $parent_sizes = wc_get_product_terms($parent_product, 'pa_size');
    $child_size_count = 0;
    $sizes = [];
    $options = '<option value="">Choose your  Ring Size</option>';

    if (!empty($pa_sizes)) {
        foreach ($pa_sizes as $key => $value) {
            $k = 'size_' . $value->slug;
            $sizes[$k] = [
                'name' => $value->name,
                'stock_status' => '',
                'is_exist' => false
            ];
        }
    }

    if (!empty($parent_product) && !empty($meta_type)) {
        $products = get_posts(array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'attribute_pa_metal-type',
                    'value' => $meta_type,
                )
            ),
            'post_parent' => intval($parent_product)
        ));

        if (!empty($products)) {
            foreach ($products as $key => $value) {
                $product = wc_get_product($value->ID);
                $meta = get_post_meta($value->ID);
                $size = isset($meta['attribute_pa_size'][0]) ? $meta['attribute_pa_size'][0] : '';
                $stock_status = '';
                $k = 'size_' . $size;

                if ($product->get_stock_status() == "instock") {
                    $stock_status = ' (Ready to Ship)';
                }

                if (!empty($size)) {
                    $child_size_count++;
                }

                if (isset($sizes[$k])) {
                    $sizes[$k]['name'] = $sizes[$k]['name'] .  $stock_status;
                    $sizes[$k]['is_exist'] = true;
                    $sizes[$k]['size'] = $size;
                }
            }

            if (!empty($sizes)) {
                foreach ($sizes as $key => $value) {
                    if ($value['is_exist']) {
                        $options .= sprintf('<option value="%s">%s</option>', $value['size'], $value['name']);
                    }
                }
            }
        }
    }

    if ((empty($meta_type) || $child_size_count == 0) && !empty($sizes)) {
        if (!empty($parent_sizes)) {
            foreach ($parent_sizes as $key => $value) {
                $options .= sprintf('<option value="%s">%s</option>', $value->slug, $value->name);
            }
        }
    }

    echo wp_json_encode(array(
        'options' => $options,
    ));
    wp_die();
}
