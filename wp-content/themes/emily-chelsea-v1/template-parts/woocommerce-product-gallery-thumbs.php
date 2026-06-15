<?php
extract($args);
if (!empty($attachments)) {
?>
    <div class="woocommerce-product-gallery__thumbs">
        <?php
        foreach ($attachments as $key => $value) {
            if ($value['type'] === 'image') {
                echo TTG_Template::get_template_part('woo-product-gallery-thumb-item-image', ['attachment_id' => $value['id']]);
            } else if ($value['type'] === 'video') {
                echo TTG_Template::get_template_part('woo-product-gallery-thumb-item-video', [
                    'data' => $value['data'],
                    'index' => $value['index']
                ]);
            }
        }
        ?>
    </div>
<?php
}
