<?php
extract($args);
if (!empty($order_id)) {
    $pick_up_in_store = get_field('pick_up_in_store', $order_id, true);
?>
    <p>
        <strong>Pick up in store</strong> : <?php echo $pick_up_in_store ? 'Yes' : 'No' ?>
    </p>
<?php
}
?>