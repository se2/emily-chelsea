<?php

use TTG\Build_Ring\PRODUCT_TYPES;

extract($args);
$product = wc_get_product($product_id);
$price = wc_price($product->get_price());
$is_ring = has_term(PRODUCT_TYPES::RING, 'product_cat', $product_id);
$type_text  = $is_ring ? 'Setting' : 'Stone';
?>
<div class="build-ring-tray-product-info">
    <div class="build-ring-tray-product-info__type"><?php echo $type_text; ?></div>
    <h3 class="build-ring-tray-product-info__title"><?php echo $product->get_title(); ?></h3>
    <div class="build-ring-tray-product-info__price"><?php echo $price; ?></div>
</div>