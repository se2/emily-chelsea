<?php

function get_google_category($product_id) {
  $terms = get_the_terms($product_id, 'product_cat');

  if (!$terms) return '';

  foreach ($terms as $term) {
    switch ($term->slug) {
      case 'bracelets':
        return 'Apparel & Accessories > Jewelry > Bracelets';
      case 'necklaces':
        return 'Apparel & Accessories > Jewelry > Necklaces';
      case 'earrings':
        return 'Apparel & Accessories > Jewelry > Earrings';
      case 'other-rings':
        return 'Apparel & Accessories > Jewelry > Rings';
      case 'pins':
        return 'Apparel & Accessories > Jewelry > Pins & Brooches';
    }
  }

  return '';
}

function get_product_type($product_id) {
  $terms = get_the_terms($product_id, 'product_cat');

  if (!$terms) return '';

  foreach ($terms as $term) {
    switch ($term->slug) {
      case 'bracelets':
        return 'Bracelets';
      case 'pins':
        return 'Pins';
      case 'earrings':
        return 'Earrings';
      case 'necklaces':
        return 'Necklaces';
      case 'other-rings':
        return 'Rings';
    }
  }

  return '';
}

function generate_meta_feed_file() {

  $upload_dir = wp_upload_dir();
  $file_path = $upload_dir['basedir'] . '/meta-feed.csv';
  $file_url  = $upload_dir['baseurl'] . '/meta-feed.csv';

  $output = fopen($file_path, 'w');

  fputcsv($output, [
    'id','title','description','availability',
    'condition','price','link','image_link','item_group_id',
    'brand','google_product_category','product_type'
  ]);

  $args = [
    'limit' => -1,
    'status' => 'publish',
    'category' => [
      'bracelets','pins','earrings','necklaces','other-rings'
    ]
  ];

  $products = wc_get_products($args);

  foreach ($products as $product) {
    $brand = 'Emily Chelsea Jewelry';
    $google_category = get_google_category($product->get_id());
    $product_type = get_product_type($product->get_id());

    if ($product->is_type('variable')) {
      foreach ($product->get_children() as $variation_id) {

        $variation = wc_get_product($variation_id);
        if (!$variation) continue;

        fputcsv($output, [
          $variation->get_id(),
          $product->get_name(),
          $product->get_description(),
          $variation->is_in_stock() ? 'in stock' : 'out of stock',
          'new',
          $variation->get_price() . ' USD',
          get_permalink($product->get_id()),
          wp_get_attachment_url(
            $variation->get_image_id() ?: $product->get_image_id()
          ),
          $product->get_id(),
          $brand,
          $google_category,
          $product_type
        ]);
      }

    } else {
      fputcsv($output, [
        $product->get_id(),
        $product->get_name(),
        $product->get_description(),
        $product->is_in_stock() ? 'in stock' : 'out of stock',
        'new',
        $product->get_price() . ' USD',
        get_permalink($product->get_id()),
        wp_get_attachment_url($product->get_image_id()),
        '',
        $brand,
        $google_category,
        $product_type
      ]);
    }
  }

  fclose($output);

  return $file_url;
}

if (!wp_next_scheduled('generate_meta_feed_cron')) {
  wp_schedule_event(time(), 'hourly', 'generate_meta_feed_cron');
}

add_action('generate_meta_feed_cron', 'generate_meta_feed_file');

add_action('init', function () {

  if (!isset($_GET['regen_meta_feed'])) {
    return;
  }

  // 🔒 Restrict to admin only
  if (!is_user_logged_in() || !current_user_can('manage_options')) {
    wp_die('Unauthorized', 403);
  }

  // // Optional: extra security via nonce
  // if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'regen_meta_feed')) {
  //   wp_die('Invalid nonce', 403);
  // }

  // 🔄 Generate feed
  $file_url = generate_meta_feed_file();

  // 📄 Output result
  header('Content-Type: text/plain');
  echo "Feed regenerated successfully\n";
  echo "URL: " . $file_url;

  exit;
});
