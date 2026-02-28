<?php
extract($args);
?>
<?php
if (count($stones) > 1) {
?>
    <div class="have-stone">
        <div class="have-stone__inner">
            <?php
            foreach ($stones as $key => $value) {
                $label = $value === TTG_Build_Ring::get_selected_stone() ? 'CHOOSE YOUR CENTER STONE' : 'SELECT CENTER STONE';
                if ($value === $default_stone) {
                    $label = 'SELECT OUR RECOMMENDED STONE*';
                }
            ?>
                <div class="have-stone__item">
                    <?php echo get_the_post_thumbnail($value, 'full') ?>
                    <a data-stone-id="<?php echo $value ?>" class="btn btn--large btn--outline btn--white update-stone" href="<?php echo TTG_Build_Ring::get_next_action() ?>"><?php echo $label ?></a>
                </div>
                <?php
                if ($key % 2 == 0) {
                ?>
                    <div class="have-stone__sep">or</div>
                <?php
                }
                ?>

            <?php
            }
            ?>
        </div>
    </div>
<?php
} else {
?>
    <div class="no-stone">
        <a class="btn btn--large btn--outline btn--white" href="<?php echo TTG_Build_Ring::get_next_action() ?>">CHOOSE YOUR SETTING</a>
    </div>
<?php
}
?>