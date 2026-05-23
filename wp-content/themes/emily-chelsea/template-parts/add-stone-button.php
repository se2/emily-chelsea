<?php
extract($args);
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