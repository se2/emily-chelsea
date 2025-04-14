<!DOCTYPE html>
<html>

<head>
    <meta name="facebook-domain-verification" content="gtew4wz3ee7o14zj6sk2m4hjep47hp" />
    <!-- Fontawesome v5.10.0 -->
    <!-- <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-5NRGW2NN');
    </script>
    <!-- End Google Tag Manager -->
    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '146856861782937');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=146856861782937&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->
    <?php wp_head(); ?>
</head>

<?php
$config = TTG_Config::get_header_config();
$attr = ['style' => apply_filters('body_attr_style', [])];
?>

<body <?php body_class(''); ?> <?php echo TTG_Util::generate_html_attrs($attr) ?>>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5NRGW2NN" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
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
        <a class="header-phone header-phone--mobile" href="tel:<?php the_field("header_phone", "option"); ?>">
            <?php echo TTG_Template::get_icon('phone'); ?>
        </a>
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