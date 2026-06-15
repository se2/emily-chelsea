<?php
function modify_post_type_in_search_page($query)
{
    if ($query->is_search() && $query->is_main_query() && !is_admin()) {
        $query->set('post_type', array('post', 'product'));
    }

    if (is_shop() && $query->is_main_query() && !is_admin()) {
        $terms = get_field('exclude_product_terms', 'options');
        if (!empty($terms)) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'product_cat',
                    'terms' => $terms,
                    'operator' => 'NOT IN'
                )
            ));
        }
    }

    if ((is_shop() || is_tax('product_cat')) && $query->is_main_query() && !is_admin()) {
        $query->set('meta_query', array(
            'relation' => 'AND',
            array(
                'key' => 'is_special_product',
                'value' => "0",
            ),
            array(
                'key' => 'hide_product_from_catalog_listing',
                'value' => "0",
            ),
            array(
                'key' => '_stock_status',
                'value' => "outofstock",
                "compare" => "!="
            ),
        ));

        // Exclude out-of-stock simple products
        // $out_of_stock_simple_ids = get_posts(array(
        //     'post_type'      => 'product',
        //     'posts_per_page' => -1,
        //     'post_status'    => 'publish',
        //     'fields'         => 'ids',
        //     'tax_query'      => array(
        //         array(
        //             'taxonomy' => 'product_type',
        //             'field'    => 'slug',
        //             'terms'    => 'simple',
        //         ),
        //     ),
        //     'meta_query'     => array(
        //         array(
        //             'key'   => '_stock',
        //             'value' => 0,
        //             'compare' => '<='
        //         ),
        //     ),
        // ));

        // if (!empty($out_of_stock_simple_ids)) {
        //     $query->set('post__not_in', $out_of_stock_simple_ids);
        // }
    }

    if (!is_admin() && $query->is_main_query() && (is_shop() || is_tax('product_cat'))) {
        //$query->set('orderby', 'meta_value_num');
        //$query->set('meta_key', 'order_number');
        //$query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'modify_post_type_in_search_page');
