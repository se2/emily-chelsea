<?php

use TTG\Build_Ring\MODE;
use TTG\Build_Ring\Controller;


$mode = Controller::get_mode();
$is_active = $mode == MODE::START_WITH_SETTING ? true : false;
?>
<div class="build-ring-mode">
    <div class="build-ring-mode__inner">
        <div id="build-ring-mode--stone" class="build-ring-mode__label <?php echo  $mode == MODE::START_WITH_STONE ? 'active' : ''  ?>">
            <span class="build-ring-mode__image"><?php echo TTG_Template::get_icon('stone') ?></span>
            <span>Stone</span>
        </div>
        <!-- <div id="build-ring-toggle-mode" class="build-ring-mode__switch <?php echo $is_active ? 'active' : '' ?>"></div> -->
        <div id="build-ring-mode--setting" class="build-ring-mode__label <?php echo $mode == MODE::START_WITH_SETTING ? 'active' : ''  ?>">
            <span class=" build-ring-mode__image"><?php echo TTG_Template::get_icon('ring') ?></span>
            <span>Setting</span>
        </div>
    </div>

</div>