<?php
global $post;
$custom_inquiry = get_field('custom_inquiry', 'options');
?>
<div class="add-to-cart-wrapper inquire-button">
    <div class="product-custom-buttons">
        <?php
        if (!empty($custom_inquiry)) {
        ?>
            <a class="btn btn--large btn--outline" href="<?php echo $custom_inquiry['url'] ?>">
                <?php echo $custom_inquiry['title'] ?>
            </a>
        <?php
        }
        ?>
    </div>
</div>