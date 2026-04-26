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
                        $hover_image_ring = get_field('hover_feature_image', $ring);
                        echo get_the_post_thumbnail($ring, 'full', array(
                            'class' => 'product__image'
                        ));
                        if (empty($hover_image_ring)) {
                            echo get_the_post_thumbnail($ring, 'full', array(
                                'class' => 'product__image-hover'
                            ));
                        }
                        if (!empty($hover_image_ring)) {
                            echo wp_get_attachment_image($hover_image_ring['id'], 'full', false, array(
                                'class' => 'product__image-hover'
                            ));
                        }
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