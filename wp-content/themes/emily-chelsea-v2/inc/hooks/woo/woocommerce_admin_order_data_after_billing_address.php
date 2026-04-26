<?php
add_action('woocommerce_admin_order_data_after_billing_address', function ($order) {
    echo TTG_Template::get_template_part('order/pick-up-in-store', ['order_id' => $order->get_id()]);
});
