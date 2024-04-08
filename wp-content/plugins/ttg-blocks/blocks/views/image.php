<?php
extract($args);

$pc = get_field('ttg_image_pc');
$tablet = get_field('ttg_image_tablet');
$mobile = get_field('ttg_image_mobile');
$ttg_image_enable_parallax = get_field('ttg_image_enable_parallax');
$ttg_image_disable_overlap_pc = get_field('ttg_image_disable_overlap_pc');
$ttg_image_disable_overlap_tablet = get_field('ttg_image_disable_overlap_tablet');
$ttg_image_disable_overlap_mobile = get_field('ttg_image_disable_overlap_mobile');

if (!empty($pc['image'])) {

    if (empty($tablet['image'])) {
        $tablet['image'] = $pc['image'];
    }

    if (empty($mobile['image'])) {
        $mobile['image'] = $pc['image'];
    }

    $attr_pc = [
        'class' => "d-none d-lg-block ttg-image__pc",
        'style' => [
            //'background-image' => "url({$pc['image']['url']})",
            //'background-attachment' => $ttg_image_enable_parallax ? 'fixed' : 'none',
            'max-width' => $pc['max_width'],
            'max-height' => $pc['max_height']
        ]
    ];

    $attr_tablet = [
        'class' => "d-none d-md-block d-lg-none ttg-image__tablet",
        'style' => [
            //'background-image' => "url({$tablet['image']['url']})",
            //'background-attachment' => $ttg_image_enable_parallax ? 'fixed' : 'none',
            'max-width' => $tablet['max_width'],
            'max-height' => $tablet['max_height']
        ]
    ];

    $attr_m = [
        'class' => "d-block d-md-none d-lg-none ttg-image__mobile",
        'style' => [
            //'background-image' => "url({$mobile['image']['url']})",
            //'background-attachment' => $ttg_image_enable_parallax ? 'fixed' : 'none',
            'max-width' => $mobile['max_width'],
            'max-height' => $mobile['max_height']
        ]
    ];

    $attrs = [
        'class' => ['prallax-image-container ttg-image']
    ];
    $image_class = $ttg_image_enable_parallax ? 'simple-parallax' : '';
    $config = json_encode([
        'intensity' => get_field('ttg_image_intensity')
    ]);

    if ($ttg_image_enable_parallax) {
        $attrs['style'] = [
            '--ttg-image-overflow-pc' => $ttg_image_disable_overlap_pc ? 'hidden' : 'visiable',
            '--ttg-image-overflow-tablet' => $ttg_image_disable_overlap_tablet ? 'hidden' : 'visiable',
            '--ttg-image-overflow-mobile' => $ttg_image_disable_overlap_mobile ? 'hidden' : 'visiable',
        ];
    }
?>
    <div <?php echo TTG_Block_HTML_Helpers::attrs($attrs) ?>>
        <div <?php echo TTG_Block_HTML_Helpers::attrs($attr_pc) ?>>
            <?php echo wp_get_attachment_image($pc['image']['id'], 'full', false, ['class' => $image_class, 'data-config' => $config]) ?>
        </div>
        <div <?php echo TTG_Block_HTML_Helpers::attrs($attr_tablet) ?>>
            <?php echo wp_get_attachment_image($tablet['image']['id'], 'full', false, ['class' => $image_class, 'data-config' => $config]) ?>
        </div>
        <div <?php echo TTG_Block_HTML_Helpers::attrs($attr_m) ?>>
            <?php echo wp_get_attachment_image($mobile['image']['id'], 'full', false, ['class' => $image_class, 'data-config' => $config]) ?>
        </div>

    </div>
<?php
}
?>
<?php
if (empty($pc['image']) && is_admin()) {
?>
    <div class="block-placeholder <?php echo esc_attr($classes); ?>">
        +
    </div>
<?php

}
?>