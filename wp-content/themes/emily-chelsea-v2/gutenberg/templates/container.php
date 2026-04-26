<?php
extract($args);

$container_pc = get_field('container_pc');
$container_tablet = get_field('container_tablet');
$container_mobile = get_field('container_mobile');
$container_enable_max_width = get_field('container_enable_max_width');

$attrs = [
    'id' => $default_id,
    'class' => [$default_class, 'ttg-container'],
    'style' => [
        '--ttg-container-padding-left-m' => $container_mobile['padding_left'],
        '--ttg-container-padding-right-m' => $container_mobile['padding_right'],
        '--ttg-container-padding-left-tablet' => $container_tablet['padding_left'],
        '--ttg-container-padding-right-tablet' => $container_tablet['padding_right'],
        '--ttg-container-padding-left-pc' => $container_pc['padding_left'],
        '--ttg-container-padding-right-pc' => $container_pc['padding_right'],
        '--ttg-container-max-width' => $container_enable_max_width ? '1600px' :  '',
    ]
];

if (!$container_enable_max_width) {
    $attrs['class'][] = 'ttg-container--full';
}

?>
<div <?php echo TTG_Util::generate_html_attrs($attrs) ?>>
    <div class="ttg-container__inner">
        <InnerBlocks />
    </div>
</div>