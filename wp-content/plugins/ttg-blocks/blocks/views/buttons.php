<?php
extract($args);

$buttons = get_field('buttons');
$attrs = [
    'class' => ['ttg-button-wrapper', $default_class],
    'id' => $default_id
]
?>
<div <?php echo TTG_Block_HTML_Helpers::attrs($attrs) ?>>
    <?php echo TTG_Blocks_Template_Parts_Helper::buttons($buttons); ?>
</div>