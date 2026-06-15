<?php
extract($args);
?>
<div class="build-ring-modal d-none">
    <div class="build-ring-modal__bg">
        <div class="build-ring-modal__inner">
            <div class="build-ring-modal__close">
                <?php echo TTG_Template::get_icon("close") ?>
            </div>
            <div class="build-ring-modal__head">
                <?php echo TTG_Template::get_icon("ring-icon") ?>
                <?php echo TTG_Template::get_icon("arrow-right") ?>
                <?php echo TTG_Template::get_icon("stone") ?>
            </div>
            <div class="build-ring-modal__body">
                <?php
                if (!empty($title)) {
                ?>
                    <h2 class="build-ring-modal__body__title"><?php echo $title; ?></h2>
                <?php
                }
                ?>

                <?php
                if (!empty($title)) {
                ?>
                    <div class="build-ring-modal__body__content"><?php echo $content ?></div>
                <?php
                }
                ?>


                <div class="build-ring-modal__body__actions">
                    <?php echo $actions ?>
                </div>
                <?php
                if (!empty($note)) {
                ?>
                    <div class="build-ring-modal__body__note"><?php echo $note ?></div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>