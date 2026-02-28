<?php
global $post;
$product_services = get_field('product_services', 'options');
$is_special_product = get_field('is_special_product', $post->ID);
$hide_product_service = get_field('hide_product_service', $post->ID);
$is_stone = has_term('center-stones', 'product_cat', $post);
if (!empty($product_services) && !$is_special_product && !$is_stone && !$hide_product_service) {
    $product = wc_get_product($post->ID);
    $attributes = $product->get_attributes();
    $meta_type = isset($attributes['pa_metal-type']) ? $attributes['pa_metal-type'] : '';
    $term_ids = [];
    if (!empty($meta_type)) {
        $term_ids = wc_get_product_terms($post->ID, $meta_type->get_name(), array('fields' => 'ids'));
    }

?>
    <div class="product-services">
        <div class="product-services__inner">
            <?php
            if (!empty($product_services)) {
                foreach ($product_services as $key => $value) {
                    $condition_visible = $value['condition_visible'];
                    $show = true;

                    if (!empty($condition_visible) && !empty($term_ids)) {
                        $show = false;
                        foreach ($term_ids as $k => $t) {
                            if (in_array($t, $condition_visible)) {
                                $show = true;
                                break;
                            }
                        }
                    }
                    if ($show) {
            ?>
                        <div class="product-services__item">
                            <?php echo wp_get_attachment_image($value['image']['id'], 'full') ?>
                            <span><?php echo $value['label']; ?></span>
                        </div>
                <?php
                    }
                }
                ?>
            <?php
            }
            ?>
        </div>
    </div>
<?php
}
?>