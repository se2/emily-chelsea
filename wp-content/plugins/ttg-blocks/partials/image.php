<?php
extract($args);
?>
<div class="ttg-image">
    <?php
    if ($image_pc) {
        echo wp_get_attachment_image($image_pc['id'], 'full', false, array('class' => 'd-none d-lg-block'));
    }
    ?>
    <?php
    if ($image_tablet) {
        echo wp_get_attachment_image($image_tablet['id'], 'full', false, array('class' => 'd-none d-md-block d-lg-none'));
    }
    ?>
    <?php
    if ($image_mobile) {
        echo wp_get_attachment_image($image_mobile['id'], 'full', false, array('class' => 'd-block d-md-none d-lg-none'));
    }
    ?>
</div>