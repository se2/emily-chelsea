<?php
add_filter('woocommerce_variation_option_name', function ($term_name, $term, $attribute, $product) {
    if (is_admin()) return $term_name;

    if ($term->taxonomy == "pa_metal-type") {
        $products_instock = TTG_Product::is_instock_meta_type($product->get_id(), $term->slug);
        return $products_instock > 0 ? $term_name . ' (Ready to Ship)' : $term_name;
    }
    return  $term_name;
}, 10, 4);
