<?php
add_action('wp_footer', function () {
?>
    <script>
        (function($) {
            document.addEventListener('facetwp-loaded', function() {
                var width = 0;
                var numberItems = 0;
                $.each(FWP.settings.num_choices, function(key, val) {

                    // assuming each facet is wrapped within a "facet-wrap" container element
                    // this may need to change depending on your setup, for example:
                    // change ".facet-wrap" to ".widget" if using WP text widgets
                    var $facet = $('.facetwp-facet-' + key);
                    var $wrap = $facet.closest('.products-filter__item');
                    var $flyout = $facet.closest('.flyout-row');
                    if (key === 'available_in_fairmined_gold') {
                        if (!val) {
                            $wrap.find(".products-filter__item__label").show()
                        } else {
                            $wrap.find(".products-filter__item__label").hide()
                        }

                    }
                    // if ($wrap.length || $flyout.length) {
                    //     var $which = $wrap.length ? $wrap : $flyout;

                    //     if (val === 0) {
                    //         $which.hide();
                    //     } else {
                    //         $which.show();
                    //         numberItems += 1;
                    //         width += $which.width();
                    //         console.log('width', width);
                    //     }
                    // }

                });
                // if (numberItems < 5) {
                //     $('.products-filter__items').css('--products-filter-max-width', (width + 150) + 'px');
                // } else {
                //     $('.products-filter__items').css('--products-filter-max-width', '100%');
                // }

                // setTimeout(function() {
                //     if ($(document).find('.facetwp-load-more').hasClass('facetwp-hidden')) {
                //         $(document).find('.woocommerce-pagination .facetwp-facet-result_count').hide()
                //     } else {
                //         $(document).find('.woocommerce-pagination .facetwp-facet-result_count').show()
                //     }
                // }, 500)
            });
        })(jQuery);
    </script>
    <?php
    if (is_singular("product")) {
    ?>
        <script>
            (function($) {

                const form = $(".variations_form");
                const data = form.data(".product_variations");
                const updateVariation = jQuery.fn.wc_variations_image_update.bind(form);
                jQuery("#pa_metal-type").on("change", function() {
                    var val = jQuery(this).val();
                    var variation = data.find(
                        (item) => item.attributes["attribute_pa_metal-type"] === val,
                    );

                    setTimeout(() => {
                        if (variation) {
                            updateVariation(variation);
                        }
                    }, 500);
                });

            })(jQuery);
        </script>
    <?php
    }
    ?>
<?php
}, 100);
