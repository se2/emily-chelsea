<?php
extract($args);
$is_not_included_stone = TTG_Product::is_not_included_stone($product->get_id());
if ($is_not_included_stone) {
    $stone = TTG_Product::get_stone();
    $stone_config = TTG_Product::get_stone_config($product);
?>
    <a href="<?php echo get_term_link($stone) ?>" class="add-stone">
        <?php echo $stone_config['icon']; ?>
        <?php echo $stone_config['add_text'] ?>
    </a>
<?php } ?>

<?php
if (TTG_Product::is_stone($product->get_id())) {
    $ring = TTG_Product::get_ring();
    if (!empty($ring)) {
        $ring_link = get_term_link($ring, $ring->taxonomy);
?>
        <a href="<?php echo $ring_link ?>" class="add-stone">
            Add a Ring
        </a>
<?php
    }
}
?>