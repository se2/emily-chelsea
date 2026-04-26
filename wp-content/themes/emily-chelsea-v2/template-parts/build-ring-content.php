<?php
extract($args);
if ($product_id) {
    $image = get_field("image_of_past_wheat_rings", $product_id);
    $content = get_the_content(null, false, $product_id);
    $image = wp_get_attachment_image($image['id'], 'full');

?>
    <div class="build-ring-content">
        <div id="additional-details" class="build-ring-content__left">
            <div class="post-content">
                <?php
                if (!empty($content)) {
                    echo apply_filters('the_content', $content);
                }
                ?>
            </div>
        </div>
        <?php
        if (!empty($image)) {
        ?>
            <div id="imagery-of-past-wheat-rings" class="build-ring-content__right">
                <h2 class="build-ring-content__title">IMAGERY OF PAST WHEAT RINGS:</h2>
                <div class="build-ring-content__img">
                    <?php
                    echo $image;
                    ?>
                </div>
            </div>
        <?php
        }
        ?>

    </div>
<?php
}
?>