<?php
extract($args);
if (!empty($product_id)) {
    $gallery = get_field('product_gallery', $product_id);
    $gallery_title = get_field('gallery_title', $post->ID);
    if (!empty($gallery)) {
?>
        <div class="product-gallery" id="product-gallery">
            <?php
            if (!empty($gallery_title)) {
            ?>
                <h2 class="heading-large product-gallery__title"><?php echo $gallery_title ?></h2>
            <?php
            }
            ?>
            <div class="product-gallery__items">
                <?php
                foreach ($gallery as $key => $value) {
                ?>
                    <div class="product-gallery__item">
                        <div class="product-gallery__item__inner">
                            <?php echo wp_get_attachment_image($value['id'], 'full'); ?>
                        </div>
                    </div>
                <?php
                }
                ?>

            </div>
        </div>
<?php
    }
}
?>