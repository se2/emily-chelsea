<?php
extract($args);

?>
<?php
if ($defaut_stone_id && $stone_id) {
?>
    <div class="have-stone">
        <div class="have-stone__inner">
            <div class="have-stone__item">
                <?php echo get_the_post_thumbnail($stone_id, 'full') ?>
                <a data-stone-id="<?php echo $stone_id ?>" class="btn btn--large btn--outline btn--white update-stone" href="<?php echo TTG_Build_Ring::get_setting_page_step(3) ?>">CHOOSE YOUR CENTER STONE</a>
            </div>
            <div class="have-stone__sep">or</div>
            <div class="have-stone__item">
                <?php echo get_the_post_thumbnail($defaut_stone_id, 'full') ?>
                <a data-stone-id="<?php echo $defaut_stone_id ?>" class="btn btn--large btn--outline btn--white update-stone" href="<?php echo TTG_Build_Ring::get_setting_page_step(3) ?>">SELECT OUR RECOMMENDED STONE*</a>
            </div>
        </div>
    </div>
<?php
} else {
?>
    <div class="no-stone">
        <a class="btn btn--large btn--outline btn--white" href="<?php echo TTG_Build_Ring::get_setting_page_step(2) ?>">SELECT YOUR STONE</a>
        <span>– OR –</span>
        <a data-stone-id="<?php echo $defaut_stone_id ?>" class="btn btn--large btn--outline btn--white update-stone" href="<?php echo TTG_Build_Ring::get_setting_page_step(3) ?>">SELECT OUR RECOMMENDED STONE*</a>
    </div>
<?php
}
?>