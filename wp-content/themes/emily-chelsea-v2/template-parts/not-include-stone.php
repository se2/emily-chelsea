<?php
extract($args);
$is_not_included_stone = TTG_Product::is_not_included_stone($product->get_id());
if ($is_not_included_stone) {
    $stone = TTG_Product::get_stone();
    $stone_config = TTG_Product::get_stone_config($stone);
?>
    <div class="not-include-stone"><?php echo $stone_config['not_include_text'] ?></div>
<?php } ?>