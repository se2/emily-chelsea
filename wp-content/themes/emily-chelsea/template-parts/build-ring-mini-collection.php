<?php

use TTG\Build_Ring\Controller;

$collection = Controller::get_collections();
$count = 1;
?>
<?php
$newCollection = array_filter($collection, function ($item) {
    return !empty($item['ring']) && !empty($item['stone']);
});
if (!empty($newCollection)) {
?>
    <div class="build-ring-mini-collection-wrapper">
        <div id="build-ring-mini-collection" class="build-ring-mini-collection">
            <div class="processing">Processing...</div>
            <?php foreach ($newCollection as $key => $item): ?>
                <?php
                $ring = $item['ring'];
                $variation_id = $item['variation_id'] ?? 0;
                $stone = $item['stone'];
                ?>
                <div class="build-ring-mini-collection-item">
                    <h2 class="build-ring-mini-collection-item__title">
                        DESIGN <?php echo $count++; ?>
                        <span class="remove-design" data-uuid="<?php echo $key; ?>" href="#<?php echo $key; ?>">
                            <?php echo TTG_Template::get_icon('close'); ?>
                        </span>
                    </h2>
                    <div class="build-ring-mini-collection-item__ring">
                        <?php
                        $image = get_the_post_thumbnail($variation_id, 'full', array(
                            'class' => 'product__image'
                        ));
                        if (empty($image)) {
                            $image = get_the_post_thumbnail($ring, 'full', array(
                                'class' => 'product__image'
                            ));
                        }

                        echo $image;
                        ?>
                    </div>
                    <div class="build-ring-mini-collection-item__stone">
                        <?php
                        $hover_image_stone = get_field('hover_feature_image', $stone);
                        echo get_the_post_thumbnail($stone, 'full', array(
                            'class' => 'product__image'
                        ));
                        if (empty($hover_image_stone)) {
                            echo get_the_post_thumbnail($stone, 'full', array(
                                'class' => 'product__image-hover'
                            ));
                        }
                        if (!empty($hover_image_stone)) {
                            echo wp_get_attachment_image($hover_image_stone['id'], 'full', false, array(
                                'class' => 'product__image-hover'
                            ));
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

<?php
}
?>