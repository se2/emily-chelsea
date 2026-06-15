<?php
function custom_save_post_post($post_id)
{
    update_post_meta($post_id, 'ttg_post_type', 0);
}
add_action('save_post_post', 'custom_save_post_post', 99);

function custom_save_post_product($post_id)
{
    update_post_meta($post_id, 'ttg_post_type', 1);
}
add_action('save_post_product', 'custom_save_post_product', 99);
