<?php
extract($args);
echo \TTG_Template::render('build-ring-collection-item', array(
    'product_id' => $product_id,
    'type' => 'Setting',
    'cart_line_item' => $cart_line_item,
));
