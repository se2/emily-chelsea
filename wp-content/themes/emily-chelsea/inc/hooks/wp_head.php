<?php
add_action('wp_head', function () {
    if (is_checkout()) {
        TTG_Product::add_cart_notices();
    }
    if (is_user_logged_in() && !is_admin()) {
?>
        <style>
            html {
                margin: 0 !important;
            }
        </style>
<?php
    }
}, 999);
