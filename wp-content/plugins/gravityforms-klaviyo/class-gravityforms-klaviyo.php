<?php
/**
 * Gravity Forms Klaviyo feed class.
 *
 * @package gf_klaviyo
 */

// Load Klaviyo API.
require_once __DIR__ . '/wp-klaviyo-api/klaviyo-api.php';
use CrossPeakSoftware\GravityForms\Klaviyo\WPKlaviyoApi\KlaviyoAPI;
use CrossPeakSoftware\GravityForms\Klaviyo\WPKlaviyoApi\KlaviyoProfile;
use CrossPeakSoftware\GravityForms\Klaviyo\WPKlaviyoApi\KlaviyoEvent;

GFForms::include_feed_addon_framework();

/**
 * The main feed class.
 */
class GFKlaviyoFeedAddOn extends GFFeedAddOn {

	/**
	 * Feed version.
	 *
	 * @var string
	 */
	protected $_version = CP_GF_KLAVIYO_FEED_VERSION;

	/**
	 * Minimum gravity forms version.
	 *
	 * @var string
	 */
	protected $_min_gravityforms_version = '2.4.18';

	/**
	 * Plugin slug
	 *
	 * @var string
	 */
	protected $_slug = 'gravityforms-klaviyo';

	/**
	 * Plugin path
	 *
	 * @var string
	 */
	protected $_path = 'gravityforms-klaviyo/gravityforms-klaviyo.php';

	/**
	 * Plugin full path to class file.
	 *
	 * @var [type]
	 */
	protected $_full_path = __FILE__;

	/**
	 * Title of the plugin.
	 *
	 * @var string
	 */
	protected $_title = 'Gravity Forms For Klaviyo';

	/**
	 * Short title of the plugin.
	 *
	 * @var string
	 */
	protected $_short_title = 'Klaviyo';

	/**
	 * Instance to load.
	 *
	 * @var object
	 */
	private static $_instance = null;

	/**
	 * Defines the capabilities needed for the Klaviyo Add-On
	 *
	 * @since  3.0
	 * @access protected
	 * @var    array $_capabilities The capabilities needed for the Add-On
	 */
	protected $_capabilities = array( 'cp_gravityforms_klaviyo', 'cp_gravityforms_klaviyo_uninstall' );

	/**
	 * Defines the capability needed to access the Add-On settings page.
	 *
	 * @since  3.0
	 * @access protected
	 * @var    string $_capabilities_settings_page The capability needed to access the Add-On settings page.
	 */
	protected $_capabilities_settings_page = 'cp_gravityforms_klaviyo';

	/**
	 * Defines the capability needed to access the Add-On form settings page.
	 *
	 * @since  3.0
	 * @access protected
	 * @var    string $_capabilities_form_settings The capability needed to access the Add-On form settings page.
	 */
	protected $_capabilities_form_settings = 'cp_gravityforms_klaviyo';

	/**
	 * Defines the capability needed to uninstall the Add-On.
	 *
	 * @since  3.0
	 * @access protected
	 * @var    string $_capabilities_uninstall The capability needed to uninstall the Add-On.
	 */
	protected $_capabilities_uninstall = 'cp_gravityforms_klaviyo_uninstall';

	/**
	 * Get an instance of this class.
	 *
	 * @return GFKlaviyoFeedAddOn
	 */
	public static function get_instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new GFKlaviyoFeedAddOn();
		}

		return self::$_instance;
	}

	/**
	 * Plugin starting point. Handles hooks, loading of language files and PayPal delayed payment support.
	 */
	public function init() {

		parent::init();

		$this->add_delayed_payment_support(
			array(
				'option_label' => esc_html__( 'Subscribe contact to service x only when payment is received.', 'gravityforms-klaviyo' ),
			)
		);

		$this->gf_klaviyo_update_sms_consent_field_on_form();
	}

	/**
	 * Add AJAX hooks or add initialization code when an AJAX request is being performed
	 */
	public function init_ajax() {

		parent::init_ajax();

		add_action( "wp_ajax_gf_feed_is_active_{$this->_slug}", array( $this, 'gf_klaviyo_update_sms_consent_field_on_form' ) );
		add_action( "gform_{$this->get_short_slug()}_pre_delete_feed", array( $this, 'gf_klaviyo_update_sms_consent_field_on_form' ) );
	}

	/**
	 * Add a SMS Disclaimer/Consent HTML field to the form when the SMS consent setting is active for a feed.
	 */
	public function gf_klaviyo_update_sms_consent_field_on_form() {

		$feed_exists = true;
		$feed_id     = $this->get_current_feed_id();
		if ( empty( $feed_id ) ) {
			$feed_id = rgpost( 'feed_id' );
		}
		if ( 'delete' === rgpost( 'single_action' ) && 'gravityforms-klaviyo' === rgpost( 'subview' ) ) {
			$feed_id     = rgpost( 'single_action_argument' );
			$feed_exists = false;
		}

		if ( $feed_id ) {

			$feed = $this->get_feed( $feed_id );

			$form = GFAPI::get_form( $feed['form_id'] );

			// Check if sms consent setting is already active on another feed and return to avoid override.
			$feeds = $this->get_feeds( $feed['form_id'] );
			foreach ( $feeds as $the_feed ) {
				if ( $feed_id !== $the_feed['id'] && $the_feed['is_active'] && $the_feed['meta']['sms_consent_field'] ) {
					return;
				}
			}

			$enable_sms_signup = $_POST['_gform_setting_enable_sms_signup'] ?? $feed['meta']['enable_sms_signup']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$sms_consent_field = $_POST['_gform_setting_sms_consent_field'] ?? $feed['meta']['sms_consent_field']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$disclaimer_text   = $_POST['_gform_setting_sms_disclaimer_text_field'] ?? $feed['meta']['sms_disclaimer_text_field']; // phpcs:ignore WordPress.Security.NonceVerification.Missing

			$is_active = isset( $_POST['is_active'] ) ? rgpost( 'is_active' ) : $feed['is_active']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( $enable_sms_signup && $is_active && $feed_exists ) {

				// Hide/show the disclaimer field.
				if ( 'disclaimer' === $sms_consent_field ) {
					$form = $this->toggle_sms_consent_field_on_form( $form, $disclaimer_text, 'disclaimer', 'visible' );
					$form = $this->toggle_sms_consent_field_on_form( $form, $disclaimer_text, 'consent', 'hidden' );
				} elseif ( 'required' === $sms_consent_field ) {
					$form = $this->toggle_sms_consent_field_on_form( $form, $disclaimer_text, 'disclaimer', 'hidden' );
					$form = $this->toggle_sms_consent_field_on_form( $form, $disclaimer_text, 'consent_required', 'visible' );
				} elseif ( 'optional' === $sms_consent_field ) {
					$form = $this->toggle_sms_consent_field_on_form( $form, $disclaimer_text, 'disclaimer', 'hidden' );
					$form = $this->toggle_sms_consent_field_on_form( $form, $disclaimer_text, 'consent', 'visible' );
				} else {
					$form = $this->toggle_sms_consent_field_on_form( $form, $disclaimer_text, 'disclaimer', 'hidden' );
					$form = $this->toggle_sms_consent_field_on_form( $form, $disclaimer_text, 'consent', 'hidden' );
				}
			} else {
				$form = $this->toggle_sms_consent_field_on_form( $form, $disclaimer_text, 'disclaimer', 'hidden' );
				$form = $this->toggle_sms_consent_field_on_form( $form, $disclaimer_text, 'consent', 'hidden' );
			}
		}
	}

	/**
	 * Toggle the visibility of the SMS disclaimer and consent fields on the feed's form.
	 *
	 * @param array  $form The form object currently being processed.
	 * @param string $disclaimer_text The disclaimer text.
	 * @param string $type The type of field to toggle.
	 * @param string $status The visibility status of the field.
	 */
	public function toggle_sms_consent_field_on_form( $form, $disclaimer_text, $type, $status ) {
		$field_is_required = false;
		if ( 'consent_required' === $type ) {
			$type              = 'consent';
			$field_is_required = true;
		}

		if ( 'visible' === $status ) {

			// Check if sms disclaimer or consent fields already exist on form.
			$sms_disclaimer_exists = false;
			$sms_consent_exists    = false;
			foreach ( $form['fields'] as $field ) {
				if ( str_contains( $field['cssClass'], 'sms-disclaimer' ) ) {
					$sms_disclaimer_exists = true;
				}
				if ( str_contains( $field['cssClass'], 'sms-consent' ) ) {
					$sms_consent_exists = true;
				}
			}

			if ( 'disclaimer' === $type && ! $sms_disclaimer_exists ) {
				$new_field_id             = GFFormsModel::get_next_field_id( $form['fields'] );
				$properties['type']       = 'html';
				$properties['id']         = $new_field_id;
				$properties['label']      = 'SMS Disclaimer';
				$properties['cssClass']   = 'sms-disclaimer';
				$properties['visibility'] = 'visible';
				$properties['content']    = $disclaimer_text;
				$field                    = GF_Fields::create( $properties );

				$form['fields'][] = $field;
			} elseif ( 'consent' === $type && ! $sms_consent_exists ) {
				$new_field_id                = GFFormsModel::get_next_field_id( $form['fields'] );
				$properties['type']          = 'consent';
				$properties['id']            = $new_field_id;
				$properties['isRequired']    = $field_is_required;
				$properties['label']         = 'SMS Consent';
				$properties['checkboxLabel'] = $disclaimer_text;
				$properties['description']   = '';
				$properties['cssClass']      = 'sms-consent';
				$properties['visibility']    = 'visible';
				$properties['inputs']        = array(
					array(
						'id'    => "$new_field_id.1",
						'label' => 'SMS Consent',
						'name'  => '',
					),
					array(
						'id'       => "$new_field_id.2",
						'label'    => 'Text',
						'name'     => '',
						'isHidden' => true,
					),
					array(
						'id'       => "$new_field_id.3",
						'label'    => 'Description',
						'name'     => '',
						'isHidden' => true,
					),
				);
				$properties['choices']       = array(
					array(
						'text'  => 'Checked',
						'value' => 1,
					),
				);
				$field                       = GF_Fields::create( $properties );

				$form['fields'][] = $field;
			}
		}

		foreach ( $form['fields'] as $field_id => $field ) {
			if ( ( 'disclaimer' === $type && str_contains( $field['cssClass'], 'sms-disclaimer' ) ) || ( 'consent' === $type && str_contains( $field['cssClass'], 'sms-consent' ) ) ) {
				if ( 'disclaimer' === $type ) {
					$form['fields'][ $field_id ]['content'] = $disclaimer_text;
				} elseif ( 'consent' === $type ) {
					$form['fields'][ $field_id ]['checkboxLabel'] = $disclaimer_text;
					$form['fields'][ $field_id ]['isRequired']    = $field_is_required;
				}
				$form['fields'][ $field_id ]['visibility'] = $status;
				break;
			}
		}

		GFAPI::update_form( $form );
		return $form;
	}

	// # FEED PROCESSING -----------------------------------------------------------------------------------------------

	/**
	 * Process the feed e.g. subscribe the user to a list.
	 *
	 * @param array $feed The feed object to be processed.
	 * @param array $entry The entry object currently being processed.
	 * @param array $form The form object currently being processed.
	 *
	 * @return bool|void
	 */
	public function process_feed( $feed, $entry, $form ) {

		// Get API key from settings.
		$private_api = $this->get_plugin_setting( 'klaviyo_priv_api' );

		// call Klaviyo API object.
		$klaviyo         = new KlaviyoAPI( $private_api );
		$klaviyo_profile = new KlaviyoProfile();

		$sms_signup_enabled = $feed['meta']['enable_sms_signup'];
		$sms_consent_field  = $feed['meta']['sms_consent_field'];
		$sms_signup_list    = $feed['meta']['sms_signup_list'];

		// Get selected lists.
		$email_lists = array();
		foreach ( $feed['meta'] as $name => $value ) {
			if ( strpos( $name, 'list_' ) !== false && $value ) {
				$name          = explode( 'list_', $name );
				$name          = end( $name );
				$email_lists[] = $name;
			}
		}
		$lists = $email_lists;

		// Get all profile fields.
		$mapped_profile_fields     = $this->get_field_map_fields( $feed, 'mapped_profile_fields' );
		$additional_profile_fields = $this->get_dynamic_field_map_fields( $feed, 'additional_profile_fields' );
		$generic_profile_fields    = $this->get_generic_map_fields( $feed, 'additional_profile_fields' );

		// Get mapped field values for creating a Klaviyo profile.
		$custom_source = null;
		foreach ( $mapped_profile_fields as $name => $field_id ) {
			$val = $this->get_field_value( $form, $entry, $field_id );
			if ( $val ) {
				$klaviyo_profile->set( $name, $val );
				if ( '$source' === $name ) {
					$custom_source = $val;
				}
			}
		}

		// Get all pre-defined custom properties.
		$property_vals     = array();
		$custom_properties = $this->get_custom_properties();
		foreach ( $custom_properties as $property ) {
			$property_vals[] = $property['value'];
		}

		foreach ( $additional_profile_fields as $name => $field_id ) {
			if ( 'gf_custom' === $field_id ) {
				$field_value = GFCommon::replace_variables( $generic_profile_fields[ $name ] ?? '', $form, $entry, false, false );
			} else {
				$field_value = $this->get_field_value( $form, $entry, $field_id );
			}
			if ( '$source' === $name ) {
				$custom_source = $field_value;
			}
			if ( $field_value ) {
				$klaviyo_profile->set( $name, $field_value );
			}
		}

		$sign_up_for_sms = false;
		if ( $sms_signup_enabled && $sms_signup_list ) {
			if ( 'disclaimer' === $sms_consent_field || 'none' === $sms_consent_field ) {
				$sign_up_for_sms = true;
			} elseif ( 'optional' === $sms_consent_field || 'required' === $sms_consent_field ) {
				$consent_field = null;
				foreach ( $form['fields'] as $field ) {
					if ( str_contains( $field['cssClass'], 'sms-consent' ) ) {
						$consent_field = $this->get_field_value( $form, $entry, $field['id'] . '.1' );
					}
				}
				if ( 'Checked' === $consent_field ) {
					$sign_up_for_sms = true;
				}
			}
		}

		if ( $sign_up_for_sms && ! in_array( $sms_signup_list, $lists, true ) ) {
			$lists[] = $sms_signup_list;
		}

		// Create or update a profile.
		try {
			$klaviyo_profile = $klaviyo->update_or_create_profile( $klaviyo_profile );
			$logs            = $klaviyo->get_logs();
			foreach ( $logs as $log ) {
				if ( 'error' === $log['type'] ) {
					$this->log_error( $log['message'] );
				} else {
					$this->log_debug( $log['message'] );
				}
			}
			$klaviyo->clear_logs();
			if ( isset( $entry['id'] ) ) {
				$notes = $klaviyo->get_notes();
				foreach ( $notes as $note ) {
					$this->add_note( $entry['id'], $note['message'], $note['type'] );
				}
				$klaviyo->clear_notes();
			}
		} catch ( \Exception $e ) {
			$this->log_debug( 'Klaviyo Update or Create Profile: ' . $e->getMessage() );
			$klaviyo_profile = null;
			if ( isset( $entry['id'] ) ) {
				$this->add_note( $entry['id'], __( 'Error creating Klaviyo Profile: ', 'gravityforms-klaviyo' ) . $e->getMessage(), 'error' );
			}
		}

		if ( $klaviyo_profile ) {
			// Subscribe profile to lists.
			foreach ( $lists as $list ) {
				$subscribe_email = in_array( $list, $email_lists, true );
				$subscribe_sms   = $sign_up_for_sms && $list === $sms_signup_list;

				if ( $subscribe_email || $subscribe_sms ) {
					// Get the list name.
					$list_name = $list;
					try {
						$list_obj  = $klaviyo->get_list( $list );
						$list_name = empty( $list_obj ) || empty( $list_obj->get_name() ) ? $list : $list_obj->get_name() . ' (' . $list . ')';
					} catch ( Exception $e ) {
						$this->log_error( 'Klaviyo Get List: ' . $e->getMessage() );
					}
					try {
						// TODO: more checking on result.
						$success = $klaviyo->subscribe_profile( $klaviyo_profile, $list, $subscribe_email, $subscribe_sms, $custom_source );
						if ( isset( $entry['id'] ) ) {
							if ( $success ) {
								$this->add_note( $entry['id'], __( 'Subscribed profile to list: ', 'gravityforms-klaviyo' ) . $list_name, 'success' );
							} else {
								$this->add_note( $entry['id'], __( 'Error subscribing profile to list: ', 'gravityforms-klaviyo' ) . $list_name . ' ' . $e->getMessage(), 'error' );
							}
						}
					} catch ( Exception $e ) {
						$this->log_error( 'Klaviyo Subscribe Profiles: ' . $e->getMessage() );
						if ( isset( $entry['id'] ) ) {
							$this->add_note( $entry['id'], __( 'Error subscribing profile to list: ', 'gravityforms-klaviyo' ) . $list_name . ' ' . $e->getMessage(), 'error' );
						}
					}
				}
			}

			// Create an event.
			if ( $klaviyo_profile && isset( $feed['meta']['event_name'] ) && '' !== $feed['meta']['event_name'] ) {
				$klaviyo_event = new KlaviyoEvent();
				$klaviyo_event->set_metric( $feed['meta']['event_name'] );
				$klaviyo_event->add_property( 'Event', $feed['meta']['event_name'] );

				$dynamic_event_properties = $this->get_dynamic_field_map_fields( $feed, 'event_properties' );
				$generic_event_properties = $this->get_generic_map_fields( $feed, 'event_properties' );
				foreach ( $dynamic_event_properties as $name => $field_id ) {
					if ( 'gf_custom' === $field_id ) {
						$field_value = GFCommon::replace_variables( $generic_event_properties[ $name ] ?? '', $form, $entry, false, false );
					} else {
						$field_value = $this->get_field_value( $form, $entry, $field_id );
					}
					if ( $field_value ) {
						$data['data']['attributes']['properties'][ $name ] = $field_value;
						$klaviyo_event->add_property( $name, $field_value );
					}
				}

				if ( isset( $feed['meta']['event_value'] ) && is_numeric( $feed['meta']['event_value'] ) ) {
					$klaviyo_event->set_value( $feed['meta']['event_value'] );
				}

				try {
					$success = $klaviyo->send_event( $klaviyo_profile, $klaviyo_event );
					if ( isset( $entry['id'] ) ) {
						if ( $success ) {
							$this->add_note( $entry['id'], __( 'Created event: ', 'gravityforms-klaviyo' ) . $feed['meta']['event_name'], 'success' );
						} else {
							$this->add_note( $entry['id'], __( 'Error creating event: ', 'gravityforms-klaviyo' ) . $feed['meta']['event_name'], 'error' );
						}
					}
				} catch ( Exception $e ) {
					$this->log_error( 'Klaviyo Create Event: ' . $e->getMessage() );
					if ( isset( $entry['id'] ) ) {
						$this->add_note( $entry['id'], __( 'Error creating event: ', 'gravityforms-klaviyo' ) . $e->getMessage(), 'error' );
					}
				}
			}
		}

		$logs = $klaviyo->get_logs();
		foreach ( $logs as $log ) {
			if ( 'error' === $log['type'] ) {
				$this->log_error( $log['message'] );
			} else {
				$this->log_debug( $log['message'] );
			}
		}
		$klaviyo->clear_logs();
		if ( isset( $entry['id'] ) ) {
			$notes = $klaviyo->get_notes();
			foreach ( $notes as $note ) {
				$this->add_note( $entry['id'], $note['message'], $note['type'] );
			}
			$klaviyo->clear_notes();
		}
	}

	/**
	 * Custom format the phone type field values before they are returned by $this->get_field_value().
	 *
	 * @param array          $entry The Entry currently being processed.
	 * @param string         $field_id The ID of the Field currently being processed.
	 * @param GF_Field_Phone $field The Field currently being processed.
	 *
	 * @return string
	 */
	public function get_phone_field_value( $entry, $field_id, $field ) {

		// Get the field value from the Entry Object.
		$field_value = rgar( $entry, $field_id );

		// If there is a value and the field phoneFormat setting is set to standard reformat the value.
		if ( ! empty( $field_value ) ) {
			if ( 'standard' === $field->phoneFormat && preg_match( '/^\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/', $field_value, $matches ) ) {
				$field_value = sprintf( '1%s%s%s', $matches[1], $matches[2], $matches[3] );
			} else {
				$field_value = preg_replace( '/\D/', '', $field_value );
			}
			if ( strlen( $field_value ) === 10 ) {
				$field_value = '1' . $field_value;
			}
			$field_value = '+' . $field_value;
		}

		return $field_value;
	}

	// # ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------

	/**
	 * Load in the javascript for the feed settings page.
	 *
	 * @return array
	 */
	public function scripts() {
		$scripts = array(
			array(
				'handle'  => 'klaviyo_feed',
				'src'     => $this->get_base_url() . '/assets/js/feed.js',
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				'enqueue' => array(
					array(
						'admin_page' => array( 'form_settings' ),
						'tab'        => 'gravityforms-klaviyo',
					),
				),
			),
		);

		return array_merge( parent::scripts(), $scripts );
	}


	/**
	 * Configures the settings which should be rendered on the add-on settings tab.
	 *
	 * @return array
	 */
	public function plugin_settings_fields() {
		return array(
			array(
				'title'       => 'Klaviyo API Keys',
				'description' => sprintf(
					'%s <br/><a target="_blank" href="https://help.klaviyo.com/hc/en-us/articles/115005062267-How-to-manage-your-account-s-API-keys#find-your-api-keys2">%s</a>',
					esc_html__( 'Enter your Klaviyo Public and Private API keys to allow your Gravity Form Feed to pass form submissions directly into the service.', 'gravityforms-klaviyo' ),
					esc_html__( 'How to find your API keys', 'gravityforms-klaviyo' )
				),
				'fields'      => array(
					array(
						'type'          => 'text',
						'id'            => 'klaviyo_pub_api',
						'name'          => 'klaviyo_pub_api',
						'label'         => esc_html__( 'Public API Key.', 'gravityforms-klaviyo' ),
						'required'      => true,
						'class'         => 'medium',
						'tooltip'       => esc_html__( 'Your public API key is also called your Site ID. This is a short alphanumeric value. This public key is a unique identifier for your Klaviyo account, and there is only one per account. <a href="https://help.klaviyo.com/hc/en-us/articles/7423954176283" target="_blank">Learn More</a>', 'gravityforms-klaviyo' ),
						'tooltip_class' => 'tooltipclass',
					),
					array(
						'type'          => 'text',
						'input_type'    => 'password',
						'id'            => 'klaviyo_priv_api',
						'name'          => 'klaviyo_priv_api',
						'label'         => esc_html__( 'Private API Key.', 'gravityforms-klaviyo' ),
						'required'      => true,
						'class'         => 'medium',
						'tooltip'       => esc_html__( 'Private API keys are used for reading data from Klaviyo and manipulating some sensitive objects such as lists. Treat private API keys like passwords kept in a safe place and never exposed to the public.', 'gravityforms-klaviyo' ),
						'tooltip_class' => 'tooltipclass',
					),
				),
			),
		);
	}

	/**
	 * Configures the settings which should be rendered on the feed edit page in the Form Settings > Klaviyo Feed Add-On area.
	 *
	 * @return array
	 */
	public function feed_settings_fields() {

		// Get API keys from settings.
		$private_api = $this->get_plugin_setting( 'klaviyo_priv_api' );

		// call Klaviyo API object.
		$klaviyo = new KlaviyoAPI( $private_api );

		try {
			$lists = $klaviyo->get_lists();
		} catch ( Exception $e ) {
			$this->log_debug( 'Klaviyo Error getting lists: ' . $e->getMessage() );
			$error_message = '<p>An error occurred while retrieving the list(s) from Klaviyo.</p>
			<p>The error message from Klaviyo was: <b>' . esc_html( $e->getMessage() ) . '</b></p>
			<p>You may need to go to your <a href="' . admin_url( 'admin.php?page=gf_settings&subview=gravityforms-klaviyo' ) . '">Gravity Forms Settings</a> to update your Klaviyo API keys.</p>';
			return array(
				array(
					'fields' => $this->feed_name_settings(),
				),
				array(
					'title'  => esc_html__( 'Subscription Info', 'gravityforms-klaviyo' ),
					'fields' => array(
						array(
							'type' => 'html',
							'name' => 'error',
							// We put the error message here twice, once for the alert at the top and once for inline in the page here.
							'html' => '<div class="alert error">' . $error_message . '</div>' . $error_message,
						),
					),
				),
			);
		}
		$list_options     = array();
		$sms_list_options = array(
			array(
				'label' => 'Select a List',
				'value' => '',
			),
		);
		if ( ! empty( $lists ) ) {
			foreach ( $lists as $list ) {
				$list_options[]     = array(
					'label' => $list->get_name(),
					'name'  => 'list_' . $list->get_id(),
				);
				$sms_list_options[] = array(
					'label' => $list->get_name(),
					'value' => $list->get_id(),
				);
			}
		}

		return array(
			array(
				'fields' => $this->feed_name_settings(),
			),
			array(
				'title'  => esc_html__( 'Subscription Info', 'gravityforms-klaviyo' ),
				'fields' => array(
					array(
						'label'   => esc_html__( 'Email Lists', 'gravityforms-klaviyo' ),
						'type'    => 'checkbox',
						'name'    => 'lists',
						'tooltip' => esc_html__( 'Select the Klaviyo list(s) the user will be subscribed to by email.', 'gravityforms-klaviyo' ),
						'choices' => $list_options,
					),
					array(
						'label'    => esc_html__( 'SMS Subscriptions', 'gravityforms-klaviyo' ),
						'type'     => 'checkbox',
						'name'     => 'enable_sms_signup_field',
						'choices'  => array(
							array(
								'label' => 'Enable SMS Signup',
								'name'  => 'enable_sms_signup',
							),
						),
						'onchange' => 'gravityforms_klaviyo.toggle_sms(this.checked);',
					),
					array(
						'label'   => esc_html__( 'SMS Signup List', 'gravityforms-klaviyo' ),
						'type'    => 'select',
						'name'    => 'sms_signup_list',
						'tooltip' => esc_html__( 'Select the Klaviyo list the user will be subscribed to by SMS.', 'gravityforms-klaviyo' ),
						'choices' => $sms_list_options,
					),
					array(
						'label'         => esc_html__( 'SMS Consent', 'gravityforms-klaviyo' ),
						'type'          => 'radio',
						'name'          => 'sms_consent_field',
						'default_value' => 'disclaimer',
						'choices'       => array(
							array(
								'label' => 'Add a SMS consent disclaimer field to the form',
								'value' => 'disclaimer',
							),
							array(
								'label' => 'Include a required checkbox for collecting SMS consent',
								'value' => 'required',
							),
							array(
								'label' => 'Include an optional checkbox for collecting SMS consent',
								'value' => 'optional',
							),
							array(
								'label'   => 'Do not add any disclaimer to the form.',
								'value'   => 'none',
								'tooltip' => esc_html__( 'If you select this option, you should add your own disclaimer to your form. <a target="_blank" href="https://help.klaviyo.com/hc/en-us/articles/360035056972-Guide-to-collecting-SMS-consent#what-counts-as-sms-consent1">Learn More</a>', 'gravityforms-klaviyo' ),
							),
						),
						'tooltip'       => esc_html__( 'Add a SMS consent disclaimer and/or manually collect consent for users signing up for a SMS subscription. <a target="_blank" href="https://help.klaviyo.com/hc/en-us/articles/360035056972-Guide-to-collecting-SMS-consent#what-counts-as-sms-consent1">Learn More</a>', 'gravityforms-klaviyo' ),
					),
					array(
						'label'         => esc_html__( 'SMS Disclaimer Text', 'gravityforms-klaviyo' ),
						'type'          => 'textarea',
						'name'          => 'sms_disclaimer_text_field',
						'default_value' => 'By submitting this form and providing your phone number, you consent to receive marketing text messages (e.g. promos, cart reminders) at the number provided, including messages sent by autodialer. Msg & data rates may apply. Msg frequency varies. Unsubscribe at any time by replying STOP or clicking the unsubscribe link (where available).',
					),
				),
			),
			array(
				'title'  => esc_html__( 'Profile Fields', 'gravityforms-klaviyo' ),
				'fields' => array(
					array(
						'name'      => 'mapped_profile_fields',
						'type'      => 'field_map',
						'field_map' => array(
							array(
								'name'       => 'email',
								'label'      => esc_html__( 'Email', 'gravityforms-klaviyo' ),
								'required'   => 1,
								'field_type' => array( 'email', 'hidden' ),
							),
							array(
								'name'       => 'phone_number',
								'label'      => esc_html__( 'Phone', 'gravityforms-klaviyo' ),
								'required'   => 0,
								'field_type' => 'phone',
							),
							array(
								'name'     => 'first_name',
								'label'    => esc_html__( 'First Name', 'gravityforms-klaviyo' ),
								'required' => 0,
							),
							array(
								'name'     => 'last_name',
								'label'    => esc_html__( 'Last Name', 'gravityforms-klaviyo' ),
								'required' => 0,
							),
							array(
								'name'          => '$source',
								'label'         => esc_html__( 'Source', 'gravityforms-klaviyo' ),
								'required'      => 0,
								'default_value' => 'source_url',
							),
						),
					),
					array(
						'name'        => 'additional_profile_fields',
						'type'        => 'generic_map',
						'label'       => esc_html__( 'Additional Fields', 'gravityforms-klaviyo' ),
						'key_field'   => array(
							'title'   => 'Field',
							'type'    => 'select',
							'choices' => $this->get_custom_properties(),
						),
						'value_field' => array(
							'title'   => 'Form Field',
							'type'    => 'select',
							'choices' => $this->create_map_field_custom(),
						),
					),
				),
			),
			array(
				'title'       => esc_html__( 'Event', 'gravityforms-klaviyo' ),
				'description' => esc_html__( 'Send a custom event to Klaviyo. This can be used to trigger a flow. Leave name blank to not send an event.', 'gravityforms-klaviyo' ),
				'fields'      => array(
					array(
						'label' => esc_html__( 'Name', 'gravityforms-klaviyo' ),
						'type'  => 'text',
						'name'  => 'event_name',
						'class' => 'merge-tag-support',
					),
					array(
						'label'      => esc_html__( 'Value', 'gravityforms-klaviyo' ),
						'type'       => 'text',
						'input_type' => 'number',
						'name'       => 'event_value',
						'class'      => 'merge-tag-support',
						'tooltip'    => esc_html__( 'An optional numeric value to associate with this event. For example, the dollar amount of a purchase.', 'gravityforms-klaviyo' ),
					),
					array(
						'label'       => esc_html__( 'Event Properties', 'gravityforms-klaviyo' ),
						'name'        => 'event_properties',
						'type'        => 'generic_map',
						'tooltip'     => esc_html__( 'Optional properties for this event. Event properties can be used to create segments.', 'gravityforms-klaviyo' ),
						'value_field' => array(
							'title'   => 'Value',
							'type'    => 'select',
							'choices' => $this->create_map_field_custom(),
						),
					),
				),
			),
			array(
				'title'  => __( 'Other Settings', 'gravityforms-klaviyo' ),
				'fields' => array(
					array(
						'name'    => 'conditionalLogic',
						'label'   => __( 'Conditional Logic', 'gravityforms-klaviyo' ),
						'type'    => 'feed_condition',
						'tooltip' => '<h6>' . __( 'Conditional Logic', 'gravityforms-klaviyo' ) . '</h6>' . __( 'When conditions are enabled, events will only be sent to google when the conditions are met. When disabled, all form submissions will trigger an event.', 'gravityforms-klaviyo' ),
					),
				),
			),
		);
	}

	/**
	 * Get the list of fields to display in the Feed Name section of the feed settings.
	 *
	 * @return array
	 */
	public function feed_name_settings() {
		return array(
			array(
				'label'   => esc_html__( 'Feed name', 'gravityforms-klaviyo' ),
				'type'    => 'text',
				'name'    => 'feed_name',
				'tooltip' => esc_html__( 'Name the feed to help identify the list and/or campaign the form is attached.', 'gravityforms-klaviyo' ),
				'class'   => 'small',
			),
			array(
				'type' => 'html',
				'name' => 'instructions',
				'html' => '<ul style="list-style: disc; padding-left: 20px;"><li style="list-style: disc;">View our <a href="https://www.crosspeaksoftware.com/docs/gravity-forms-for-klaviyo-installation-instructions/" target="_blank">Installation Guide</a> for help on setting up this Klaviyo Feed.</li>
			<li style="list-style: disc;">See our <a href="https://www.crosspeaksoftware.com/docs/faq-gravity-forms-for-klaviyo/" target="_blank">FAQ</a> for answers to <a href="https://www.crosspeaksoftware.com/docs/faq-gravity-forms-for-klaviyo/" target="_blank">Frequently Asked Questions</a>.</li>
			<li style="list-style: disc;">View our <a href="https://www.crosspeaksoftware.com/doc-category/gravity-forms-for-klaviyo/" target="_blank">full documentation</a> for more information.</li>
			<li style="list-style: disc;">If you have any questions, please contact <a href="https://www.crosspeaksoftware.com/support/" target="_blank">Our Support Team</a>.</li></ul>',
			),
		);
	}

	/**
	 * Use gform_crosspeak_klaviyo_field_value filter instead of the framework gform_SLUG_field_value filter.
	 *
	 * @param string $field_value The field value.
	 * @param array  $form        The form object currently being processed.
	 * @param array  $entry       The entry object currently being processed.
	 * @param string $field_id    The ID of the field being processed.
	 *
	 * @return string
	 */
	public function maybe_override_field_value( $field_value, $form, $entry, $field_id ) {
		return gf_apply_filters(
			array(
				'gform_crosspeak_klaviyo_field_value',
				$form['id'],
				$field_id,
			),
			$field_value,
			$form,
			$entry,
			$field_id
		);
	}

	/**
	 * Return the plugin's icon for the plugin/form settings menu.
	 *
	 * @return string
	 */
	public function get_menu_icon() {
		return file_get_contents( $this->get_base_path() . '/assets/svg/klaviyo-icon.svg' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	/**
	 * Create the custom field map.
	 */
	public function create_map_field_custom() {
		$form_id = GFAddOn::get_current_form()['id'];
		$lists   = GFAddOn::get_field_map_choices( $form_id );
		return $lists;
	}

	/**
	 * Create the additional properties available to be mapped in the feed.
	 */
	public function get_custom_properties() {

		$list_custom = array(
			array(
				'label' => 'Title',
				'value' => 'title',
			),
			array(
				'label' => 'Organization',
				'value' => 'organization',
			),
			array(
				'label' => 'Street Address',
				'value' => 'location.address1',
			),
			array(
				'label' => 'Address 2',
				'value' => 'location.address2',
			),
			array(
				'label' => 'City',
				'value' => 'location.city',
			),
			array(
				'label' => 'State',
				'value' => 'location.region',
			),
			array(
				'label' => 'Zip',
				'value' => 'location.zip',
			),
			array(
				'label' => 'Country',
				'value' => 'location.country',
			),
		);

		return $list_custom;
	}

	/**
	 * Configures which columns should be displayed on the feed list page.
	 *
	 * @return array
	 */
	public function feed_list_columns() {
		return array(
			'feed_name' => esc_html__( 'Name', 'gravityforms-klaviyo' ),
		);
	}

	/**
	 * Prevent feeds being listed or created if an api key isn't valid.
	 *
	 * @return bool
	 */
	public function can_create_feed() {

		// Get the plugin settings.
		$settings = $this->get_plugin_settings();

		// Allow access if private api key available.
		if ( rgar( $settings, 'klaviyo_priv_api' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if 2 emails match each other.
	 *
	 * @param string $email1 First email.
	 * @param string $email2 Second email.
	 * @return string
	 */
	public function emails_match( $email1, $email2 ) {
		return $this->strtolower( trim( $email1 ) ) === $this->strtolower( trim( $email2 ) );
	}

	/**
	 * Handle string to lowercase with mb support if we have it.
	 *
	 * @param string $text String to lowercase.
	 * @return string
	 */
	public function strtolower( $text ) {
		$text = trim( $text );
		if ( function_exists( 'mb_strtolower' ) ) {
			return mb_strtolower( $text );
		}
		return strtolower( $text );
	}
}
