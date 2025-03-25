<?php
/**
 * Plugin Name: Gravity Forms for Klaviyo
 * Plugin URI: https://www.crosspeaksoftware.com
 * Description: A feed add-on to integrate Gravity Forms submissions with the Klaviyo email and SMS marketing service.
 * Version: 1.7.3
 * Requires at least: 4.2
 * Requires PHP: 7.0
 * Author: CrossPeak Software
 * Author URI: https://www.crosspeaksoftware.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gravityforms-klaviyo
 * Domain Path: /languages
 *
 * @package gf_klaviyo
 */

define( 'CP_GF_KLAVIYO_FEED_VERSION', '1.7.3' );

/**
 * Register the Feed AddOn Bootstrap.
 */
function gf_klaviyo_feed_addon_bootstrap() {
	if ( ! method_exists( 'GFForms', 'include_feed_addon_framework' ) ) {
		return;
	}

	require_once __DIR__ . '/class-gravityforms-klaviyo.php';
	require_once __DIR__ . '/includes/entries.php';
	require_once __DIR__ . '/includes/import-export.php';
	GFAddOn::register( 'GFKlaviyoFeedAddOn' );
}
add_action( 'gform_loaded', 'gf_klaviyo_feed_addon_bootstrap', 5 );

/**
 * Get the the Feed AddOn instance.
 */
function gf_klaviyo_feed_addon() {
	if ( ! class_exists( 'GFKlaviyoFeedAddOn', false ) ) {
		return null;
	}
	return GFKlaviyoFeedAddOn::get_instance();
}

require_once __DIR__ . '/libs/action-scheduler/action-scheduler.php';

if ( ! function_exists( '\CrossPeakSoftware\Updater\get_plugins' ) ) {
	require __DIR__ . '/crosspeak-updater/crosspeak-software-updater.php';
}

/**
 * Register the plugin with the CrossPeak updater.
 *
 * @param array $plugins Current CrossPeak Plugins.
 * @return array
 */
function gf_klaviyo_register_plugin( $plugins ) {
	$plugins[] = array(
		'name'    => __( 'Gravity Forms for Klaviyo', 'gravityforms-klaviyo' ),
		'slug'    => 'gravityforms-klaviyo',
		'id'      => '38',
		'version' => CP_GF_KLAVIYO_FEED_VERSION,
		'file'    => __FILE__,
	);
	return $plugins;
}
add_filter( 'crosspeak_software_plugins', 'gf_klaviyo_register_plugin' );
