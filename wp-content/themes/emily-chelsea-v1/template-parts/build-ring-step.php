<?php
extract($args);
$step = TTG_Build_Ring::get_step();
$has_step = isset($_GET['step']) && count($_GET) == 1 ? true : false;
?>

<?php
if ($has_step) {
?>
    <style>
        .facetwp-type-reset {
            display: none;
        }
    </style>
<?php
}
?>

<?php
if (!empty($items)) {
    $active_step = '';
?>
    <div class="build-ring-step">
        <div class="build-ring-step__inner d-md-flex">
            <?php
            foreach ($items as $key => $value) {
                echo TTG_Template::render("build-ring-step-item", $value);
                if ($value['active']) {
                    $active_step = $value;
                }
            }
            ?>
        </div>

    </div>
    <?php
    if ($active_step) {
    ?>
        <div class="current-build-ring-step">
            <div class="current-build-ring-step__title">
                <div class="current-build-ring-step__number">Step <?php echo $active_step['step'] ?>.</div>
                <div class="current-build-ring-step__label"><?php echo $active_step['label'] ?></div>
            </div>
            <?php
            if (!is_page_template("page-ring-setting.php") && !is_page_template("page-stone-first.php")) {
                woocommerce_breadcrumb();
            }

            if (TTG_Build_Ring::get_step() === 1) {
                echo do_shortcode('[facetwp facet="sort_by"]');
            }

            ?>
        </div>
    <?php
    }
    ?>
<?php
}
?>