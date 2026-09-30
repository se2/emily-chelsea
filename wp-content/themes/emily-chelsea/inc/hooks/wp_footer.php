<?php

use TTG\Build_Ring\Controller;

add_action('wp_footer', function () {
?>
    <?php
    if (is_singular("product")) {
    ?>
        <script defer>
            (function($) {
                window.onload = function() {
                    var form = $(".variations_form");
                    var data = variations || [];
                    var updateVariation = jQuery.fn.wc_variations_image_update.bind(form);
                    jQuery("#pa_metal-type").on("change", function(el) {

                        var val = $(this).find("option:selected").attr("value");
                        if (data) {
                            var variation = data.find(
                                (item) => item.attributes["attribute_pa_metal-type"] === val,
                            );

                            setTimeout(() => {
                                if (variation) {
                                    updateVariation(variation);
                                }
                            }, 800);
                        }

                    });
                }



            })(jQuery);
        </script>
    <?php
    }
    ?>
    <?php
    if (Controller::is_building_ring()) {
    ?>
        <style>
            @media (max-width: 1099.98px) {
                access-widget-ui::part(acsb-trigger) {
                    left: 5px !important;
                    width: 30px;
                    height: 30px;
                    bottom: 120px !important;
                }

                .cky-revisit-bottom-left {
                    bottom: 160px !important;
                    left: 5px !important;
                    width: 30px !important;
                    height: 30px !important;
                }

                .cky-revisit-bottom-left img {
                    height: 25px !important;
                    width: 25px !important;
                }

                access-widget-ui::part(acsb-trigger) {
                    left: 5px !important;
                    bottom: 120px !important;
                }

                .kl-teaser-UApJ8p {
                    left: auto !important;
                    width: 100px !important;
                    bottom: 0px !important;
                    right: 0px !important;
                    margin: 0 !important;
                }

                .kl-teaser-UApJ8p span {
                    font-size: 12px !important;
                    padding: 0 !important;
                    min-height: 0 !important;
                }
            }
        </style>
    <?php
    }
    ?>
<?php
}, 100);
