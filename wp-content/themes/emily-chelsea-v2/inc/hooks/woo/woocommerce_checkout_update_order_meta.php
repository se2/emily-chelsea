<?php
add_action('woocommerce_checkout_update_order_meta', function ($order_id) {
    if (!empty($_POST['pick_up_in_store'])) {
        update_post_meta($order_id, 'pick_up_in_store', $_POST['pick_up_in_store']);
    }
});
