<?php
add_action('wp_head', function () {
    if (is_checkout()) {
        TTG_Product::add_cart_notices();
    }
    if (is_user_logged_in() && !is_admin()) {
?>
        <style>
            html {
                margin: 0 !important;
            }
        </style>
<?php
    }
}, 999);

/**
 * Install Microsoft Clarity
 */
add_action('wp_head', function () { ?>
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "oi549imkmx");
</script>
<?php }, 999);