<?php

use TTG\Build_Ring\Controller;

$tray = Controller::get_tray();
$items = [null, null];

if (!empty($tray)) {
    $count = 0;
    foreach ($tray as $item) {
        $items[$count] = $item;
        $count++;
    }
}

?>
<div class="build-ring-tray__inner">
    <h2 class="build-ring-tray__title">Your <br class="d-md-none" />Design Tray:</h2>
    <div class="build-ring-tray__items">
        <?php
        foreach ($items as $item) {
            if (empty($item)) {
                echo TTG_Template::render('build-ring-tray-empty');
            } else {
                echo '<div class="build-ring-tray__item">';
                echo '<div data-id="' . $item['uuid'] . '" class="build-ring-tray__item-remove">' . TTG_Template::get_icon('close') . '</div>';
                echo '<div class="processing">Processing...</div>';
                echo TTG_Template::render('build-ring-tray-product', [
                    'product_id' => $item['product_id'],
                ]);
                echo '</div>';
            }
        }
        ?>
    </div>
</div>