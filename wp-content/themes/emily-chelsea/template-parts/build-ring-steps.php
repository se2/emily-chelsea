<?php

use TTG\Build_Ring\Controller;

$steps = [1, 2, 3];
$active_step = Controller::get_active_step();
?>
<div class="build-ring-steps">
    <div class="build-ring-steps__inner">
        <h2 class="build-ring-steps__title">« RETURN TO PREVIOUS PAGE</h2>
        <ul class="build-ring-steps__items">
            <?php
            foreach ($steps as $key => $value) {
            ?>
                <li class="build-ring-steps__item <?php echo $value === $active_step ? 'active' : ''; ?>">
                    <a data-step="<?php echo $value ?>" class="build-ring-step-action set-step" href="#<?php echo $value ?>">
                        <?php
                        if ($value != 3) {
                        ?>
                            <?php echo $value ?>.
                        <?php
                        } else {
                        ?>
                            <?php echo  TTG_Template::get_icon('cart-2'); ?>
                        <?php
                        }
                        ?>
                    </a>
                </li>
            <?php
            }
            ?>
        </ul>

    </div>
</div>