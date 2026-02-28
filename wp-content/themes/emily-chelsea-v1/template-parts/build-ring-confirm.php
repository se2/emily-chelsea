<?php
$rings = TTG_Build_Ring::get_rings();
$subtotal = 0;
TTG_Build_Ring::selected_ring('');
?>
<div class="container-fluid confirm-rings">
    <?php
    if (!empty($rings)) {
    ?>
        <div class="confirm-rings__title">Your Ring:</div>
        <div class="confirm-rings__body">
            <div class="confirm-rings__list">
                <?php
                $count = 1;
                foreach ($rings as $key => $value) {
                    if (!empty($value['ring']) && !empty($value['stone'])) {
                        $ring = wc_get_product($value['ring']);
                        $stone = wc_get_product($value['stone']);

                        $subtotal += $ring->get_price() + $stone->get_price();
                ?>
                        <div class="confirm-rings__list__item">
                            <div class="confirm-rings__list__item__left">
                                <div class="confirm-rings-product">
                                    <div class="confirm-rings-product__img">
                                        <?php echo get_the_post_thumbnail($value['ring'], 'full') ?>
                                    </div>
                                    <div class="confirm-rings-product__content">
                                        <div class="confirm-rings-product__label"><?php echo $count ?>. Setting</div>
                                        <h2 class="confirm-rings-product__title"><?php echo get_the_title($value['ring']) ?></h2>
                                        <?php echo $ring->get_price_html(); ?>
                                        <div class="confirm-rings-product__actions">
                                            <a href="<?php echo TTG_Build_Ring::get_ring_page() ?>" class="change-ring" data-ring-id="<?php echo $value['ring'] ?>">Change</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="confirm-rings__list__item__right">
                                <div class="confirm-rings-product">
                                    <div class="confirm-rings-product__img">
                                        <?php echo get_the_post_thumbnail($value['stone'], 'full') ?>
                                    </div>
                                    <div class="confirm-rings-product__content">
                                        <div class="confirm-rings-product__label"><?php echo $count + 1 ?>. Stone</div>
                                        <h2 class="confirm-rings-product__title"><?php echo get_the_title($value['stone']) ?></h2>
                                        <?php echo $stone->get_price_html(); ?>
                                        <div class="confirm-rings-product__actions">
                                            <a href="<?php echo TTG_Build_Ring::get_stone_page() ?>" class="change-stone" data-ring-id="<?php echo $value['ring'] ?>">Change</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                        $count += 2;
                    }
                }
                ?>
            </div>
        </div>
        <div class="confirm-rings__subtotal">
            <strong>Subtotal</strong>
            <?php echo wc_price($subtotal) ?>
        </div>
    <?php
    } else {
    ?>
        <div class="confirm-rings__empty">
            <h2 class="confirm-rings__empty__title">You have not selected any rings yet.</h2>
        </div>
    <?php
    }
    ?>
    <div class="confirm-rings__actions">
        <a
            class="btn btn--large btn--outline confirm-rings__add-another-ring"
            href="<?php echo TTG_Build_Ring::get_setting_page_step(1) ?>">
            <?php echo TTG_Template::get_icon("plus") ?>
            BUILD ANOTHER RING
        </a>
        <a href="<?php echo wc_get_cart_url() ?>" class="btn btn--large btn--solid confirm-rings-btn">CONFIRM AND PROCEED TO CHECKOUT</a>
    </div>
</div>