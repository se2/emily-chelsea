<?php
// apply_filters( 'woocommerce_hide_invisible_variations', true, $this->get_id(), $variation )
add_filter('woocommerce_hide_invisible_variations', function () {
    return false;
});
