<!DOCTYPE html>
<html>

<head>
    <meta name="facebook-domain-verification" content="gtew4wz3ee7o14zj6sk2m4hjep47hp" />
    <!-- Fontawesome v5.10.0 -->
    <!-- <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <?php wp_head(); ?>
</head>

<?php
$config = TTG_Config::get_header_config();
$attr = ['style' => apply_filters('body_attr_style', [])];
?>

<body <?php body_class(''); ?> <?php echo TTG_Util::generate_html_attrs($attr) ?>>
    <input type="checkbox" id="toggle-nav-checkbox" />
    <button id="toggle-nav-btn" class="toggle-nav-btn">
        <div class="toggle-nav-btn__inner">
            <span class="toggle-nav-btn__line"></span>
            <span class="toggle-nav-btn__line"></span>
            <span class="toggle-nav-btn__line"></span>
            <div class="toggle-nav-btn__text">CLOSE MENU</div>
        </div>
    </button>
    <?php echo TTG_Template::get_template_part('main-navigation'); ?>

    <div id="wrapper">
        <?php echo TTG_Template::get_template_part('header'); ?>

        <div id="wrapper__inner">
            <!-- #et-main-area -->
            <main id="main-content">
                <?php
                if (
                    is_singular('product') || is_shop() || is_tax('product_cat') || is_page_template('page-special-products.php')
                ) {
                ?>
                    <div id="main-content__top">
                        <?php do_action('main-content-top'); ?>
                    </div>
                <?php

                }
                ?>