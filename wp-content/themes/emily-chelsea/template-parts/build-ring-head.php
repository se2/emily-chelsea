<?php

use TTG\Build_Ring\MODE;
use TTG\Build_Ring\Controller;

$mode = Controller::get_mode();
?>
<?php
if ($mode === MODE::START_WITH_SETTING) {
?>
    <div class="build-ring-head">
        <h2>Select a Setting</h2>
        <p>Drag your selection to the tray to choose a setting, or click to view more information.</p>
    </div>

<?php
}
?>

<?php
if ($mode === MODE::START_WITH_STONE) {
    $ring_id = Controller::get_ring();
    $stone_id = Controller::get_default_stone($ring_id);
    $stone_url = $stone_id ? get_permalink($stone_id) : '';
?>
    <div class="build-ring-head">
        <h2>Select a Stone</h2>
        <p>Drag your selection to the tray to choose a stone, click to view more information, or go with <?php if ($stone_url): ?><a href="<?php echo esc_url($stone_url); ?>?mode=build-ring"><u>our recommended stone</u></a><?php else: ?>our recommended stone<?php endif; ?>.</p>
    </div>

<?php
}
?>