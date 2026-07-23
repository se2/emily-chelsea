<?php

/**
 * Exclude the build-ring flow from WP Rocket caching.
 *
 * The build-ring wizard is session-driven ($_SESSION tray/collections), so a cached
 * page would serve one visitor's in-progress ring to everyone. We reject every page
 * using the page-build-ring.php template by resolving its permalink at runtime, so
 * the rule stays correct even if the page is renamed or its slug changes.
 *
 * Product pages in build mode (?mode=...) are already skipped by WP Rocket because it
 * does not cache URLs with query strings unless they are added to "Cache Query Strings"
 * (do NOT add `mode` there).
 */

if (!defined('ABSPATH')) {
    exit;
}

add_filter('rocket_cache_reject_uri', function ($uris) {
    $pages = get_pages([
        'meta_key'   => '_wp_page_template',
        'meta_value' => 'page-build-ring.php',
    ]);

    if (empty($pages)) {
        return $uris;
    }

    foreach ($pages as $page) {
        $path = trim((string) parse_url(get_permalink($page->ID), PHP_URL_PATH), '/');
        if ($path !== '') {
            $uris[] = '/' . $path . '(/.*)?';
        }
    }

    return $uris;
});

/**
 * Theme-relative path patterns for the build-ring script and its jQuery UI dependencies.
 * The drag-drop wizard breaks if these are minified/combined or deferred.
 */
function ttg_build_ring_js_excludes()
{
    $theme_path = trim((string) parse_url(get_template_directory_uri(), PHP_URL_PATH), '/');

    return [
        '/' . $theme_path . '/src/dist/js/pages/build-ring.js',
        '/' . $theme_path . '/src/dist/js/components/build-ring-selection.js',
        '/wp-includes/js/jquery/ui/draggable',
        '/wp-includes/js/jquery/ui/droppable',
        '/wp-includes/js/jquery/ui/mouse',
    ];
}

// Exclude from minification / concatenation.
add_filter('rocket_exclude_js', function ($excluded) {
    return array_merge($excluded, ttg_build_ring_js_excludes());
});

// Exclude from "Delay JavaScript Execution".
add_filter('rocket_delay_js_exclusions', function ($excluded) {
    return array_merge($excluded, ttg_build_ring_js_excludes());
});
