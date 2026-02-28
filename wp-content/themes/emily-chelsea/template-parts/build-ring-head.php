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
?>
    <div class="build-ring-head">
        <h2>Select a Stone</h2>
        <p>Drag your selection to the tray to choose a stone, click to view more information, or go with our recommended stone.</p>
    </div>

<?php
}
?>