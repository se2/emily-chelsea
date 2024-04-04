<?php
add_action('woocommerce_archive_description', function () {
    echo do_shortcode('[facetwp facet="result_count"]');
    echo TTG_Template::get_template_part('product-filter-user-selection');
});
