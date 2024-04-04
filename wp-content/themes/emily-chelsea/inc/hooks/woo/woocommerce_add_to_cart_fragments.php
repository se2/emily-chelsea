<?php
function ttg_refresh_cart_count($fragments)
{
    ob_start();
?>
    <span class="header-cart__count">
        <?php echo WC()->cart->get_cart_contents_count(); ?>
    </span>
<?php
    $fragments['.header-cart__count'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'ttg_refresh_cart_count');
