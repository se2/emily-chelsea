<!DOCTYPE html>
<html>

<head>
    <?php wp_head(); ?>
</head>

<body <?php body_class('woocommerce'); ?>>
    <div class="woocommerce">
        <div class="product-detail">
            <?php
            $ring = isset($_GET['ring']) ? intval($_GET['ring']) : null;
            add_action('woocommerce_after_add_to_cart_button',  function () {
                $uuid = $_GET['uuid'] ?? '';
                echo sprintf('<input type="hidden" name="uuid" value="%s" />', $uuid);
            }, 10, 1);

            setup_postdata($ring);
            woocommerce_template_single_add_to_cart();
            wp_reset_postdata();
            ?>
        </div>
    </div>

    <?php wp_footer(); ?>
</body>

</html>