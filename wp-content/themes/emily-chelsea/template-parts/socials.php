<?php
extract($args);
if (!empty($socials)) {
    echo '<div class="social">';
    foreach ($socials as $key => $value) {
?>
        <a class="d-block social__item" href="<?php echo $value['url'] ?>">
            <?php echo wp_get_attachment_image($value['image']['id'], 'full') ?>
        </a>
<?php
    }
    echo '</div>';
}
?>