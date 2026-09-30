<?php

/**
 * Bypass page cache (Kinsta Edge/Server) for the build-ring flow.
 *
 * The build-ring wizard is $_SESSION-driven (tray/collections), so a cached
 * page would serve one visitor's in-progress ring to everyone.
 * rocket-exclude-build-ring.php only covers WP Rocket, which Kinsta ignores —
 * this sends real no-cache headers that nginx and Cloudflare respect.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('template_redirect', function () {
    if (isset($_GET['mode']) || is_page_template('page-build-ring.php')) {
        if (!defined('DONOTCACHEPAGE')) {
            define('DONOTCACHEPAGE', true);
        }
        nocache_headers();
    }
});

/**
 * Block checkout when a build-ring design is out of sync — missing variation
 * or a WC cart line that no longer exists (e.g. a metal-type switch race left
 * the design without its setting in the cart). Sends the user back to the
 * build-ring page to complete their selections instead of silently letting
 * them order only part of a design.
 */
add_action('template_redirect', function () {
    if (!function_exists('is_checkout') || !is_checkout()) {
        return;
    }

    // Never touch order-received / order-pay endpoints.
    if (is_wc_endpoint_url('order-received') || is_wc_endpoint_url('order-pay')) {
        return;
    }

    // Prune first: a design whose lines are ENTIRELY gone (e.g. session left
    // over from a completed order) is stale data, not a broken cart — it must
    // never lock the customer out of checkout.
    $collections = \TTG\Build_Ring\Controller::prune_stale_collections();
    if (empty($collections)) {
        return;
    }

    // Only complete designs (ring + stone) are checked here: a broken one means
    // the customer would pay for half a design. Partial/in-progress designs are
    // already handled by the front-end validation alerts.
    foreach ($collections as $collection) {
        if (empty($collection['ring']) || empty($collection['stone'])) {
            continue;
        }

        $variation_id = $collection['variation_id'] ?? 0;
        $ring_line    = $collection['ring_cart_item_line'] ?? '';
        $stone_line   = $collection['stone_cart_item_line'] ?? '';

        $is_broken = empty($variation_id)
            || empty($ring_line)
            || empty($stone_line)
            || empty(WC()->cart->get_cart_item($ring_line))
            || empty(WC()->cart->get_cart_item($stone_line));

        if ($is_broken) {
            wp_safe_redirect(home_url('/build-ring/'));
            exit;
        }
    }
});
