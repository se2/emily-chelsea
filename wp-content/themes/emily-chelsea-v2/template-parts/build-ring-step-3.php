<?php

use TTG\Build_Ring\Controller;
?>
<?php
$collection = Controller::get_collections();
$newCollection = [];
if (!empty($collection)) {
    foreach ($collection as $key => $item) {
        if (!empty($item['ring']) && !empty($item['stone'])) {
            $newCollection[$key] = $item;
        }
    }
}
wc_clear_notices();
?>
<div>
    <?php
    if (!empty($newCollection)) {
        $count = 1;
        $length = count($newCollection);
        foreach ($newCollection as $key => $item) {
            $ring = $item['ring'];
            $stone = $item['stone'];
    ?>
            <div class="build-ring-confirm" data-uuid="<?php echo $key; ?>">
                <div class="build-ring-confirm__inner">
                    <div class="build-ring-confirm__left">
                        <?php
                        if ($count == 1) {
                        ?>
                            <h2 class="build-ring-confirm__left__title">Your Design:</h2>
                        <?php } ?>
                        <div class="build-ring-collection">
                            <div class="build-ring-collection__title">
                                Design <?php echo $count; ?>
                                <a data-uuid="<?php echo $key; ?>" href="#<?php echo $key; ?>" class="remove-design">
                                    <?php echo TTG_Template::get_icon('close'); ?>
                                </a>
                            </div>
                            <div class="build-ring-collection__item">

                                <div class="build-ring-collection__item__ring">
                                    <?php
                                    echo TTG_Template::render('build-ring-collection-item-ring', array(
                                        'product_id' => $ring,
                                        'type' => 'Setting',
                                        'cart_line_item' => $item['ring_cart_item_line']
                                    ));
                                    ?>
                                </div>
                                <div class="build-ring-collection__item__stone">
                                    <?php echo \TTG_Template::render('build-ring-collection-item', array(
                                        'product_id' => $stone,
                                        'type' => 'Stone',
                                    )) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="build-ring-confirm__right">
                        <?php
                        if ($count == 1) {
                        ?>
                            <h2 class="build-ring-confirm__right__title">Please Make Necessary Selections</h2>
                        <?php } ?>

                        <div class="build-ring-collection__title">Design <?php echo $count; ?>:</div>
                        <div class="woocommerce">

                            <div class="product-detail">
                                <?php
                                global $uuid;
                                $uuid = $key;
                                add_action('woocommerce_after_add_to_cart_button',  function () {
                                    global $uuid;
                                    echo sprintf('<input type="hidden" name="uuid" value="%s" />', $uuid);
                                }, 10, 1);

                                setup_postdata($ring);
                                woocommerce_template_single_add_to_cart();
                                wp_reset_postdata();
                                ?>
                            </div>
                            <div class="product-error-message"></div>
                            <?php
                            if ($count == $length) {
                            ?>
                                <div class="subtotal-wrapper">
                                    <label for="subtotal">Subtotal:</label>
                                    <span class="subtotal"></span>
                                </div>
                            <?php } ?>

                        </div>

                    </div>
                </div>
            </div>
    <?php
            $count++;
        }
    }
    ?>
</div>

<div class="build-ring-confirm-actions">
    <div class="build-ring-confirm-actions__item">
        <h2 class="build-ring-confirm-actions__item__title">Build Another Ring?</h2>
        <a id="start-new-design" class="btn btn--solid btn--large build-ring-confirm-actions__item__btn" href="">START A NEW DESIGN</a>
    </div>
    <div class="build-ring-confirm-actions__item">
        <h2 class="build-ring-confirm-actions__item__title">Continue to Checkout?</h2>
        <a id="continue-to-checkout" class="btn btn--solid btn--large build-ring-confirm-actions__item__btn" href="">CHECKOUT NOW</a>
    </div>
</div>
<div style="display: none;">
    <?php
    echo TTG_Template::render("build-ring-filter");
    $terms = 'center-stones';
    $meta_query = [
        [
            'key'       => '_stock_status',
            'value'     => 'outofstock',
            'compare'   => 'NOT IN'
        ]
    ];
    $products = new WP_Query(array(
        'post_type' => 'product',
        'tax_query' => [
            [
                'taxonomy' => 'product_cat',
                "field" => "slug",
                "terms" => $terms
            ]
        ],
        "meta_query" =>  $meta_query,
        "facetwp"        => true
    ));
    ?>
    <ul class="products columns-3 facetwp-template <?php echo $terms ?>-list">
        <?php
        while ($products->have_posts()) {
            $products->the_post();

            /**
             * Hook: woocommerce_shop_loop.
             */
            do_action('woocommerce_shop_loop');

            wc_get_template_part('content', 'product');
        }
        ?>
    </ul>
    <nav class="woocommerce-pagination">
        <?php
        echo do_shortcode('[facetwp facet="pagination"]');
        echo do_shortcode('[facetwp facet="result_count"]');
        ?>
    </nav>
</div>