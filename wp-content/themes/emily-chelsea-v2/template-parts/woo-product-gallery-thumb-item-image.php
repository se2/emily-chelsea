<?php
extract($args);
$attr = [
    'class' => 'woocommerce-product-gallery__image',
    'data-image-url' => wp_get_attachment_url($attachment_id),
    'data-type' => 'image'
];
?>
<div <?php echo TTG_Util::generate_html_attrs($attr) ?>>
    <a href="">
        <?php
        echo wp_get_attachment_image($attachment_id);
        ?>
    </a>

</div>