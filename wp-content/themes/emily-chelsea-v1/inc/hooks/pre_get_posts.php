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
        ));
    }

    if (!is_admin() && $query->is_main_query() && (is_shop() || is_tax('product_cat'))) {
        $query->set('orderby', 'meta_value_num');
        $query->set('meta_key', 'order_number');
        $query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'modify_post_type_in_search_page');
