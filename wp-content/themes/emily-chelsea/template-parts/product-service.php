<?php
global $post;
$product_services = get_field('product_services', 'options');
$is_special_product = get_field('is_special_product', $post->ID);
$is_stone = has_term('center-stones', 'product_cat', $post);
if (!empty($product_services) && !$is_special_product && !$is_stone) {
?>
    <div class="product-services">
        <div class="product-services__inner">
            <?php
            if (!empty($product_services)) {
                foreach ($product_services as $key => $value) {
            ?>
                    <div class="product-services__item">
                        <?php echo wp_get_attachment_image($value['image']['id'], 'full') ?>
                        <span><?php echo $value['label']; ?></span>
                    </div>
                <?php
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