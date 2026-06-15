<?php
add_action('woocommerce_after_add_to_cart_quantity', function () {
    echo '<div class="add-to-cart-wrapper">';
}, 29);
