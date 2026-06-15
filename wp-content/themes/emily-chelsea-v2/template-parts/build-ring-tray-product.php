<?php
extract($args);
if ($product_id > 0) {
    $hover_image = get_field('hover_feature_image', $product_id);
    echo "<div class='build-ring-tray__product'>";
    echo get_the_post_thumbnail($product_id, 'full', array(
        'class' => 'product__image'
    ));
    echo '</div>';
}
