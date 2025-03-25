<?php
/**
 * Gravity Forms for Klaviyo Import/Export setup.
 *
 * @package gf_klaviyo
 */

namespace CrossPeakSoftware\GravityForms\Klaviyo;

use GFAPI;

/**
 * Add the Klaviyo feeds to the form for export.
 *
 * @param array $form The form object.
 * @return array
 */
function feed_export( $form ) {
	$feed_addon = gf_klaviyo_feed_addon();
	$feeds      = $feed_addon->get_feeds( $form['id'] );
	if ( ! empty( $feeds ) ) {
		$form['gf_klaviyo'] = array();
		foreach ( $feeds as $feed ) {
			$form['gf_klaviyo'][] = array(
				'is_active' => $feed['is_active'],
				'meta'      => $feed['meta'],
			);
		}
	}
	return $form;
}
add_filter( 'gform_export_form', __NAMESPACE__ . '\feed_export', 10, 1 );

/**
 * Create the Klaviyo feeds for a new form when imported.
 *
 * @param array $forms Array of form objects.
 */
function feed_import( $forms ) {
	$feed_addon = gf_klaviyo_feed_addon();
	foreach ( $forms as $form ) {
		if ( ! empty( $form['gf_klaviyo'] ) ) {
			foreach ( $form['gf_klaviyo'] as $feed_settings ) {
				$feed_addon->insert_feed( $form['id'], $feed_settings['is_active'], $feed_settings['meta'] );
			}
			unset( $form['gf_klaviyo'] );
			GFAPI::update_form( $form );
		}
	}
}
add_action( 'gform_forms_post_import', __NAMESPACE__ . '\feed_import', 10, 1 );
