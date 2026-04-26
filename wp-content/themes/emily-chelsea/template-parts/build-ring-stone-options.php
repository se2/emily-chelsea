<?php

use TTG\Build_Ring\Controller;

extract($args);


if (empty($product_id)) {
    return;
}

$default_stone = Controller::get_default_stone($product_id);
$stone = wc_get_product($default_stone);
?>
<div class="build-ring-stone-options">
    <div class="build-ring-stone-options__inner">
        <h4 class="build-ring-stone-options__title">Continue with: <span>* (please select one)</span></h4>
        <div class="build-ring-stone-options__items">
            <?php
            if ($stone) {
                $is_disabled = !$stone->is_in_stock() || !$stone->is_purchasable();
            ?>
                <div class="build-ring-stone-options__item <?php echo $is_disabled ? 'disabled' : "" ?>" id="build-ring-stone-options__item--1-wrapper">
                    <input <?php disabled($is_disabled, true); ?> type="radio" id="build-ring-stone-options__item--1" name="stone_option" value="<?php echo $default_stone ?>">
                    <label for="build-ring-stone-options__item--1">
                        <div class="build-ring-stone-options__item__image">
                            <?php
                            echo get_the_post_thumbnail($default_stone, 'full');
                            ?>
                        </div>
                        <div class="build-ring-stone-options__item__label">
                            <strong>Our Recommended Stone:<?php echo $is_disabled ? "(Out of stock)" : "" ?></strong>
                            <div><?php echo get_the_title($default_stone) ?></div>
                        </div>
                    </label>
                </div>
            <?php
            }
            ?>
            <div class="build-ring-stone-options__item">
                <input checked type="radio" id="build-ring-stone-options__item--2" name="stone_option" value="custom_stone">
                <label for="build-ring-stone-options__item--2">
                    <div class="build-ring-stone-options__item__image">
                        <div class="stone-custom-image">
                            <?php echo TTG_Template::get_icon('stone'); ?>
                        </div>
                    </div>
                    <div class="build-ring-stone-options__item__label">
                        <strong>Select Your Own Stone:</strong>
                        <div>You will be brought to the Stone Selection screen.</div>
                    </div>
                </label>
            </div>

        </div>
    </div>

</div>