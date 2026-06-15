<?php
extract($args);
$stone = TTG_Product::get_stone($product);
$has_depend_product = TTG_Product::has_depend_product($product);
if (!empty($stone) && $has_depend_product) {
    $stone_config = TTG_Product::get_stone_config($stone);
?>
    <div class="badge-stone-included">
        <?php echo $stone_config['icon']; ?>
        <?php echo $stone_config['text'] ?>
    </div>
<?php } ?>