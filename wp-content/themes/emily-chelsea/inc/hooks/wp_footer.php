<?php
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
}, 100);
