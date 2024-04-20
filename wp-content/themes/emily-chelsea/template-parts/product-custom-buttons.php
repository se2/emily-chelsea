<?php
$book_an_appointment = get_field('book_an_appointment', 'options');
$custom_inquiry = get_field('custom_inquiry', 'options');
?>
<div class="cart"></div>
<div class="add-to-cart-wrapper">
    <div class="product-custom-buttons">
        <?php
        if (!empty($book_an_appointment)) {
        ?>
            <a class="btn btn--large btn--solid" href="<?php echo $book_an_appointment['url'] ?>">
                <?php echo $book_an_appointment['title'] ?>
            </a>
        <?php
        }
        ?>
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