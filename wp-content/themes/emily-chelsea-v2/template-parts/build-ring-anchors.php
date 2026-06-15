<?php
extract($args);
$image = get_field("image_of_past_wheat_rings", $product_id);
?>
<div class="build-ring-anchors">
    <div class="build-ring-anchors__inner">
        <div class="summary-spacing">
            <a href="#additional-details">Read Additional Information »</a>
            <?php
            if (!empty($image)) {
            ?>
                <div class="product-line"></div>
                <a href="#imagery-of-past-wheat-rings">Get Inspired- See Past Wheat Rings Below »</a>
            <?php
            }
            ?>

        </div>
    </div>
</div>