<?php
global $post;
$custom_inquiry = get_field('custom_inquiry', 'options');
?>
<div class="add-to-cart-wrapper inquire-button">
    <div class="product-custom-buttons">
        <?php
        if (!empty($custom_inquiry)) {
        ?>
            <a data-target="#product-inquiry-form-modal" class="btn btn--large btn--outline toggle-modal" href="#">
                <?php echo $custom_inquiry['title'] ?>
            </a>
        <?php
        }
        ?>
    </div>
</div>

<div id="product-inquiry-form-modal" class="modal product-inquiry-form-modal">
    <div class="modal__inner">
        <div class="modal__close"> <?php echo TTG_Template::get_icon("close"); ?></div>
        <div class="modal__body">
            <div class="product-inquiry-form">
                <div class="product-inquiry-form__inner">
                    <div class="product-inquiry-form__left">
                        <?php
                        global $post;
                        echo get_the_post_thumbnail($post, 'full');
                        ?>
                    </div>
                    <div class="product-inquiry-form__right">
                        <div class="product-inquiry-form__icon">
                            <?php echo TTG_Template::get_icon("envelope"); ?>
                        </div>
                        <h3 class="product-inquiry-form__title">ASK A QUESTION</h3>
                        <h2 class="product-inquiry-form__product-title"><?php echo get_the_title($post); ?></h2>
                        <?php echo do_shortcode('[gravityform id="6" ajax="true" title="true"]') ?>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>