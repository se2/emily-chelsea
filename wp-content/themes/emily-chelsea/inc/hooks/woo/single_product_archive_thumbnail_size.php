<?php
add_filter('single_product_archive_thumbnail_size', function ($size) {
    return 'full';
});
