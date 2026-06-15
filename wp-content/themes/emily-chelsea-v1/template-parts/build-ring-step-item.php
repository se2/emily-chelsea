<?php
extract($args);
$attrs = [
    "class" => ["build-ring-step__item"],
];

if ($active) {
    $attrs["class"][] = "build-ring-step__item--active";
}

if ($active && (is_singular("product") || $step == 3)) {
    $attrs["class"][] = "build-ring-step__item--active-2";
}

if (empty($href)) {
    $href = '';
}

?>
<a href="<?php echo $href; ?>" <?php echo TTG_Util::generate_html_attrs($attrs) ?>>
    <span class="build-ring-step__item__number"><?php echo $step ?>.</span>
    <span class="build-ring-step__item__label"><?php echo $label ?></span>
</a>