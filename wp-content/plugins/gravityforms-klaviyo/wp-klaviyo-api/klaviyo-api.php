<?php
/**
 * Klaviyo API
 *
 * @package klaviyo-api
 */

namespace CrossPeakSoftware\GravityForms\Klaviyo\WPKlaviyoApi;

require_once __DIR__ . '/klaviyo-api-list.php';
require_once __DIR__ . '/klaviyo-api-profile.php';
require_once __DIR__ . '/klaviyo-api-event.php';

/**
 * Klaviyo API class.
 */
class KlaviyoAPI {

	/**
	 * Public API key.
	 *
	 * @var string
	 */
	private $public_api_key;

	/**
	 * Private API key.
	 *
	 * @var string
	 */
	private $private_api_key;

	/**
	 * Logs.
	 *
	 * @var array
	 */
	private $logs = array();

	/**
	 * Notes.
	 *
	 * @var array
	 */
	private $notes = array();

	/**
	 * Constructor.
	 *
	 * @param string $private_api_key Private API key.
	 */
	public function __construct( $private_api_key ) {
		$this->private_api_key = $private_api_key;
	}

	/**
	 * Set public API key.
	 *
	 * @param string $public_api_key Public API key.
	 */
	public function set_public_api_key( $public_api_key ) {
		$this->public_api_key = $public_api_key;
	}

	/**
	 * Check for errors in the response.
	 *
	 * @param array $data Response data.
	 * @throws \Exception If there is an error.
	 */
	private function check_for_errors( $data ) {
		// Check for json errors.
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			$this->log_debug( 'Error parsing response from Klaviyo: ' . esc_html( json_last_error_msg() ) );
			throw new \Exception( 'Error parsing response from Klaviyo: ' . esc_html( json_last_error_msg() ) );
		}
		if ( isset( $data['errors'] ) ) {
			$this->log_debug( 'Klaviyo Error: ' . esc_html( $data['errors'][0]['title'] ) . ' ' . esc_html( $data['errors'][0]['detail'] ) );
			$this->log_debug( var_export( $data, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
			throw new \Exception( esc_html( $data['errors'][0]['title'] ) . ' ' . esc_html( $data['errors'][0]['detail'] ) );
		}
	}

	/**
	 * Do a GET request.
	 *
	 * @param string $url URL.
	 * @return array
	 * @throws \Exception If there is an error.
	 */
	public function get_request( $url ) {
		$response = wp_remote_get(
			$url,
			$this->get_http_args()
		);
		$data     = json_decode( wp_remote_retrieve_body( $response ), true );
		$this->check_for_errors( $data );
		return $data;
	}

	/**
	 * Post a POST request.
	 *
	 * @param string $url URL.
	 * @param array  $data Data.
	 * @return array
	 * @throws \Exception If there is an error.
	 */
	public function post_request( $url, $data ) {
		$args                = $this->get_http_args();
		$args['body']        = wp_json_encode( $data );
		$args['method']      = 'POST';
		$args['data_format'] = 'body';
		$response            = wp_remote_post( $url, $args );
		$status              = wp_remote_retrieve_response_code( $response );
		if ( 202 === $status ) {
			return true;
		}
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		$this->check_for_errors( $data );
		return $data;
	}

	/**
	 * Post a Public POST request.
	 *
	 * @param string $url URL.
	 * @param array  $data Data.
	 * @return array
	 * @throws \Exception If there is an error.
	 */
	public function public_post_request( $url, $data ) {
		if ( empty( $this->public_api_key ) ) {
			return false;
		}
		$args                = $this->get_http_args( false );
		$args['body']        = wp_json_encode( $data );
		$args['method']      = 'POST';
		$args['data_format'] = 'body';
		$url                .= '?company_id=' . $this->public_api_key;
		$response            = wp_remote_post( $url, $args );
		$status              = wp_remote_retrieve_response_code( $response );
		if ( 202 === $status ) {
			return true;
		}
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		$this->check_for_errors( $data );
		return $data;
	}

	/**
	 * Post a PATCH request.
	 *
	 * @param string $url URL.
	 * @param array  $data Data.
	 * @return array
	 * @throws \Exception If there is an error.
	 */
	private function patch_request( $url, $data ) {
		$args                = $this->get_http_args();
		$args['body']        = wp_json_encode( $data );
		$args['method']      = 'PATCH';
		$args['data_format'] = 'body';
		$response            = wp_remote_post( $url, $args );
		$status              = wp_remote_retrieve_response_code( $response );
		if ( 202 === $status ) {
			return true;
		}
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		try {
			$this->check_for_errors( $data );
		} catch ( \Exception $e ) {
			throw $e;
		}
		return $data;
	}

	/**
	 * Get HTTP args.
	 *
	 * @param bool $private_request Should the arguments include the public API key.
	 * @return array
	 */
	private function get_http_args( $private_request = true ) {
		$args = array(
			'timeout'     => 120,
			'httpversion' => '1.1',
			'user-agent'  => 'crosspeaksoftware.com/wp-klaviyo-api/1.2.0',
			'headers'     => array(
				'Content-Type' => 'application/vnd.api+json',
				'Accept'       => 'application/vnd.api+json',
				'revision'     => '2024-10-15',
			),
		);
		if ( $private_request ) {
			$args['headers']['Authorization'] = 'Klaviyo-API-Key ' . $this->private_api_key;
		}
		return $args;
	}

	/**
	 * Get lists.
	 *
	 * @return array<KlaviyoList>
	 */
	public function get_lists() {
		$next_list_page = '';
		$lists          = $this->get_request( 'https://a.klaviyo.com/api/lists' );
		$result         = array();
		while ( ! is_null( $next_list_page ) ) {
			if ( isset( $lists['data'] ) && is_array( $lists['data'] ) ) {
				foreach ( $lists['data'] as $list ) {
					$result[] = new KlaviyoList( $list );
				}
			}
			$next_list_page = isset( $lists['links']['next'] ) ? $lists['links']['next'] : null;
			if ( ! empty( $next_list_page ) ) {
				try {
					$lists = $this->get_request( $next_list_page );
				} catch ( \Exception $e ) {
					$lists = array();
					$this->log_debug( 'Klaviyo Error getting lists: ' . $e->getMessage() );
				}
			}
		}
		return $result;
	}

	/**
	 * Get list.
	 *
	 * @param string $list_id List ID.
	 * @return ?KlaviyoList
	 */
	public function get_list( $list_id ) {
		$list = $this->get_request( 'https://a.klaviyo.com/api/lists/' . $list_id . '/' );
		if ( ! isset( $list['data'] ) ) {
			return null;
		}
		return new KlaviyoList( $list['data'] );
	}

	/**
	 * Send an event.
	 *
	 * @param KlaviyoProfile $profile Profile object.
	 * @param KlaviyoEvent   $event Event object.
	 * @return bool
	 */
	public function send_event( $profile, $event ) {
		if ( empty( $event->get_metric() ) ) {
			return false;
		}
		$data                                  = array(
			'data' => array(
				'type'       => 'event',
				'attributes' => $event->to_api_attributes(),
			),
		);
		$data['data']['attributes']['profile'] = array(
			'data' => array(
				'type'       => 'profile',
				'attributes' => array(),
				'id'         => $profile->get_id(),
			),
		);
		if ( ! empty( $profile->get_email() ) ) {
			$data['data']['attributes']['profile']['attributes']['email'] = $profile->get_email();
		}
		$customer_identity = $this->get_customer_identity();
		if ( ! empty( $customer_identity['_kx'] ) ) {
			$data['data']['attributes']['profile']['attributes']['_kx'] = $customer_identity['_kx'];
		}
		if ( ! is_null( $event->get_value() ) ) {
			$data['data']['attributes']['value'] = $event->get_value();
		}
		$this->log_debug( 'Klaviyo Create Event Request: ' . var_export( $data, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
		$response = $this->post_request( 'https://a.klaviyo.com/api/events', $data );
		$this->log_debug( 'Klaviyo Create Event Response: ' . var_export( $response, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
		return $response;
	}

	/**
	 * Get the custom email from the Klaviyo cookie.
	 *
	 * @link https://help.klaviyo.com/hc/en-us/articles/360034666712#using-api-to-access-cookies3
	 *
	 * @return ?array
	 */
	public function get_customer_identity() {

		$customer_identity = null;
		if ( isset( $_COOKIE['__kla_id'] ) ) {
			$cookie            = $_COOKIE['__kla_id'];
			$decoded_cookie    = json_decode( base64_decode( $cookie ), true ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
			$customer_identity = array();

			if ( isset( $decoded_cookie['$exchange_id'] ) ) {
				$customer_identity['_kx'] = $decoded_cookie['$exchange_id'];
			}
			if ( isset( $decoded_cookie['$email'] ) ) {
				$customer_identity['email'] = $decoded_cookie['$email'];
			}
			if ( isset( $decoded_cookie['email'] ) ) {
				$customer_identity['email'] = $decoded_cookie['email'];
			}
		}

		return apply_filters( 'crosspeak_software_klaviyo_customer_identity', $customer_identity );
	}

	/**
	 * Send public event.
	 *
	 * @param KlaviyoEvent $event Event object.
	 * @param ?array       $customer_identity Customer identity.
	 * @return bool
	 */
	public function send_public_event( $event, $customer_identity = null ) {
		if ( is_null( $customer_identity ) ) {
			$customer_identity = $this->get_customer_identity();
		}
		if ( is_null( $customer_identity ) ) {
			return false;
		}
		if ( empty( $event->get_metric() ) ) {
			return false;
		}
		$data                                  = array(
			'data' => array(
				'type'       => 'event',
				'attributes' => $event->to_api_attributes(),
			),
		);
		$data['data']['attributes']['profile'] = array(
			'data' => array(
				'type'       => 'profile',
				'attributes' => $customer_identity,
			),
		);
		if ( ! is_null( $event->get_value() ) ) {
			$data['data']['attributes']['value'] = $event->get_value();
		}
		$this->log_debug( 'Klaviyo Create Public Event Request: ' . var_export( $data, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
		$response = $this->public_post_request( 'https://a.klaviyo.com/client/events', $data );
		$this->log_debug( 'Klaviyo Create Public Event Response: ' . var_export( $response, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
		return $response;
	}

	/**
	 * Get Events for a Profile
	 *
	 * @param KlaviyoProfile $profile Profile object.
	 * @return  array<KlaviyoEvent>
	 */
	public function get_events_by_profile( $profile ) {
		$events = $this->get_request( 'https://a.klaviyo.com/api/events?fields[event]=event_properties&fields[metric]=name&include=metric&filter=' . rawurlencode( 'equals(profile_id,"' . $profile->get_id() . '")' ) );
		$result = array();
		if ( isset( $events['data'] ) && is_array( $events['data'] ) ) {
			foreach ( $events['data'] as $event ) {
				if ( ! empty( $events['included'] ) && ! empty( $event['relationships']['metric']['data'] ) ) {
					foreach ( $events['included'] as $included ) {
						if ( $included['id'] === $event['relationships']['metric']['data']['id'] && ! empty( $included['attributes']['name'] ) ) {
							$event['relationships']['metric']['data']['name'] = $included['attributes']['name'];
						}
					}
				}
				$result[] = KlaviyoEvent::from_api( $event );
			}
		}
		return $result;
	}

	/**
	 * Create a profile
	 *
	 * @param KlaviyoProfile $profile Profile data.
	 * @return KlaviyoProfile
	 */
	public function create_profile( $profile ) {
		$data = $profile->to_create_api();
		try {
			$this->log_debug( 'Klaviyo Create Profile Request: ' . var_export( $data, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
			$result = $this->post_request( 'https://a.klaviyo.com/api/profiles/', $data );
			$this->log_debug( 'Klaviyo Create Profile Response: ' . var_export( $result, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
			if ( ! isset( $result['data'] ) ) {
				return null;
			}
			$profile = KlaviyoProfile::from_api( $result['data'] );
			$profile->flag_recently_created();
			$this->add_note( __( 'Klaviyo Profile created: ', 'gravityforms-klaviyo' ) . $profile->get_id(), 'success' );
			return $profile;
		} catch ( \Exception $e ) {
			// If error message is for phone number, try creating the profile again without the phone number.
			if ( str_contains( $e->getMessage(), 'phone number' ) ) {
				try {
					unset( $data['data']['attributes']['phone_number'] );
					$this->log_debug( 'Klaviyo Create Profile Request: ' . var_export( $data, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
					$result = $this->post_request( 'https://a.klaviyo.com/api/profiles/', $data );
					$this->log_debug( 'Klaviyo Create Profile Response: ' . var_export( $result, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
					if ( ! isset( $result['data'] ) ) {
						return null;
					}
					$profile = KlaviyoProfile::from_api( $result['data'] );
					$this->add_note( __( 'Klaviyo Profile created without phone number: ', 'gravityforms-klaviyo' ) . $profile->get_id(), 'success' );
					$profile->flag_recently_created();
					$profile->flag_created_without_phone_number();
					return $profile;
				} catch ( \Exception $e ) {
					$this->log_debug( 'Klaviyo Create Profile: ' . $e->getMessage() );
					$this->add_note( __( 'Error creating Klaviyo Profile: ', 'gravityforms-klaviyo' ) . $e->getMessage(), 'error' );
				}
			}
		}
	}

	/**
	 * Update profile.
	 *
	 * @param KlaviyoProfile $profile Profile to update.
	 * @return boolean
	 */
	public function update_profile( $profile ) {
		try {
			$data = $profile->to_update_api();
			$this->log_debug( 'Klaviyo Update Profile Request: ' . var_export( $data, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
			$result = $this->patch_request( 'https://a.klaviyo.com/api/profiles/' . $profile->get_id() . '/', $data );
			$this->log_debug( 'Klaviyo Update Profile Response: ' . var_export( $result, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
			if ( ! empty( $result['data']['id'] ) ) {
				$this->add_note( __( 'Klaviyo Profile updated: ', 'gravityforms-klaviyo' ) . $result['data']['id'], 'success' );
			}
			return $result;
		} catch ( \Exception $e ) {
			$this->log_debug( 'Klaviyo Update Profile: ' . $e->getMessage() );
			$this->add_note( __( 'Error updating Klaviyo Profile: ', 'gravityforms-klaviyo' ) . $e->getMessage(), 'error' );
		}
		return false;
	}

	/**
	 * Update or create profile.
	 *
	 * @param KlaviyoProfile $profile Profile to update or create.
	 * @return KlaviyoProfile|null
	 */
	public function update_or_create_profile( $profile ) {
		$found_profile = $this->find_or_create_profile( $profile );
		if ( $found_profile && ! $found_profile->was_recently_created() ) {
			$found_profile->set_from_profile( $profile );
			$this->update_profile( $found_profile );
		}
		return $found_profile;
	}

	/**
	 * Delete profile.
	 *
	 * @param string $profile_id Profile ID.
	 * @return array
	 */
	public function delete_profile( $profile_id ) {
		$data = array(
			'data' => array(
				'type'       => 'data-privacy-deletion-job',
				'attributes' => array(
					'profile' => array(
						'data' => array(
							'type' => 'profile',
							'id'   => $profile_id,
						),
					),
				),
			),
		);
		return $this->post_request( 'https://a.klaviyo.com/api/data-privacy-deletion-jobs/', $data );
	}

	/**
	 * Get profiles.
	 *
	 * @param string $filter Filter.
	 * @return array
	 */
	public function get_profiles( $filter ) {
		$filter_url = '';
		if ( ! empty( $filter ) ) {
			$filter_url = '?filter=' . rawurlencode( $filter );
		}
		$profiles = $this->get_request( 'https://a.klaviyo.com/api/profiles/' . $filter_url );
		$result   = array();
		if ( isset( $profiles['data'] ) && is_array( $profiles['data'] ) ) {
			foreach ( $profiles['data'] as $profile ) {
				$result[] = KlaviyoProfile::from_api( $profile );
			}
		}
		return $result;
	}

	/**
	 * Find profile by email or phone number.
	 *
	 * @param KlaviyoProfile $profile Kliaviyo Profile.
	 * @return KlaviyoProfile|null
	 */
	public function find_profile( $profile ) {
		if ( ! empty( $profile->get_id() ) ) {
			return $profile;
		}
		if ( ! empty( $profile->get_email() ) ) {
			$found_profile = $this->find_profile_by_email( $profile->get_email() );
			if ( $found_profile ) {
				return $found_profile;
			}
		}
		if ( ! empty( $profile->get_phone_number() ) ) {
			$found_profile = $this->find_profile_by_phone_number( $profile->get_phone_number() );
			if ( $found_profile ) {
				return $found_profile;
			}
		}
		return null;
	}

	/**
	 * Find or create profile.
	 *
	 * @param KlaviyoProfile $profile Kliaviyo Profile.
	 * @param bool           $retry If the request can be retried.
	 * @return KlaviyoProfile|null
	 */
	public function find_or_create_profile( $profile, $retry = true ) {
		$found_profile = $this->find_profile( $profile );
		if ( $found_profile ) {
			return $found_profile;
		}
		try {
			return $this->create_profile( $profile );
		} catch ( \Exception $e ) {
			if ( $retry && str_contains( $e->getMessage(), 'A profile already exists' ) ) {
				return $this->find_or_create_profile( $profile, false );
			}
		}
	}

	/**
	 * Find profile by email.
	 *
	 * @param string $email Email.
	 * @return KlaviyoProfile|null
	 */
	public function find_profile_by_email( $email ) {
		$profiles = $this->get_profiles( 'equals(email,"' . $email . '")' );
		if ( empty( $profiles ) ) {
			return null;
		}
		foreach ( $profiles as $profile ) {
			if ( $this->emails_match( $profile->get_email(), $email ) ) {
				return $profile;
			}
		}
		return null;
	}

	/**
	 * Find profile by phone number.
	 *
	 * @param string $phone_number Phone number.
	 * @return KlaviyoProfile|null
	 */
	public function find_profile_by_phone_number( $phone_number ) {
		$phone_number = trim( $phone_number );
		$profiles     = $this->get_profiles( 'equals(phone_number,"' . $phone_number . '")' );
		if ( empty( $profiles ) ) {
			return null;
		}
		foreach ( $profiles as $profile ) {
			if ( trim( $profile->get_phone_number() ) === $phone_number ) {
				return $profile;
			}
		}
		return null;
	}

	/**
	 * Check if 2 emails match each other.
	 *
	 * @param string $email1 First email.
	 * @param string $email2 Second email.
	 * @return string
	 */
	public function emails_match( $email1, $email2 ) {
		return $this->strtolower( $email1 ) === $this->strtolower( $email2 );
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

	/**
	 * Get profile.
	 *
	 * @param string $profile_id Profile ID.
	 * @return array
	 */
	public function get_profile( $profile_id ) {
		$profile = $this->get_request( 'https://a.klaviyo.com/api/profiles/' . $profile_id . '/' );
		if ( ! isset( $profile['data'] ) ) {
			return null;
		}
		return KlaviyoProfile::from_api( $profile['data'] );
	}

	/**
	 * Subscribe profile.
	 *
	 * @param KlaviyoProfile $profile Profile.
	 * @param string         $list_id List ID.
	 * @param bool           $email Include email in the subscription.
	 * @param bool           $sms Include SMS in the subscription.
	 * @param string         $source Source.
	 * @param string         $subscribed_at Subscribed at.
	 * @param bool           $historical Include historical data.
	 *
	 * @return bool
	 */
	public function subscribe_profile( $profile, $list_id, $email = false, $sms = false, $source = null, $subscribed_at = null, $historical = false ) {
		return $this->subscribe_profiles( array( $profile ), $list_id, $email, $sms, $source, $subscribed_at, $historical );
	}

	/**
	 * Subscribe profiles.
	 *
	 * @param array<KlaviyoProfile> $profiles Profiles.
	 * @param string                $list_id List ID.
	 * @param bool                  $email Include email in the subscription.
	 * @param bool                  $sms Include SMS in the subscription.
	 * @param string                $source Source.
	 * @param string                $subscribed_at Subscribed at.
	 * @param bool                  $historical Include historical data.
	 * @return bool
	 */
	public function subscribe_profiles( $profiles, $list_id, $email = false, $sms = false, $source = null, $subscribed_at = null, $historical = false ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		if ( ! $email && ! $sms ) {
			return false;
		}
		$body = array(
			'data' => array(
				'type'          => 'profile-subscription-bulk-create-job',
				'attributes'    => array(
					'profiles'          => array(
						'data' => array(),
					),
					'historical_import' => $historical,
				),
				'relationships' => array(
					'list' => array(
						'data' => array(
							'type' => 'list',
							'id'   => $list_id,
						),
					),
				),
			),
		);
		foreach ( $profiles as $profile ) {
			$subscription = array(
				'type'       => 'profile',
				'attributes' => array(
					'subscriptions' => array(),
				),
				'id'         => $profile->get_id(),
			);
			if ( $email && ! empty( $profile->get_email() ) ) {
				$subscription['attributes']['subscriptions']['email'] = array(
					'marketing' => array(
						'consent' => 'SUBSCRIBED',
					),
				);
				if ( $historical && ! is_null( $subscribed_at ) ) {
					$subscription['attributes']['subscriptions']['email']['marketing']['consented_at'] = $subscribed_at;
				}
				$subscription['attributes']['email'] = $profile->get_email();
			}
			if ( $sms && ! empty( $profile->get_phone_number() ) ) {
				$subscription['attributes']['subscriptions']['sms'] = array(
					'marketing' => array(
						'consent' => 'SUBSCRIBED',
					),
				);
				if ( $historical && ! is_null( $subscribed_at ) ) {
					$subscription['attributes']['subscriptions']['sms']['marketing']['consented_at'] = $subscribed_at;
				}
				$subscription['attributes']['phone_number'] = $profile->get_phone_number();
			}
			if ( ! empty( $subscription['attributes']['subscriptions'] ) ) {
				$body['data']['attributes']['profiles']['data'][] = $subscription;
			}
		}
		if ( ! is_null( $source ) ) {
			$body['data']['attributes']['custom_source'] = $source;
		}
		return $this->post_request( 'https://a.klaviyo.com/api/profile-subscription-bulk-create-jobs', $body );
	}

	/**
	 * Log to debug log.
	 *
	 * @param string $message Message to log.
	 * @return void
	 */
	public function log_debug( $message ) {
		$this->log( $message, 'debug' );
	}

	/**
	 * Log to error log.
	 *
	 * @param string $message Message to log.
	 * @return void
	 */
	public function log_error( $message ) {
		$this->log( $message, 'error' );
	}

	/**
	 * Log message
	 *
	 * @param string $message Message to log.
	 * @param string $type Type of log.
	 * @return void
	 */
	public function log( $message, $type = 'debug' ) {
		$this->logs[] = array(
			'message' => $message,
			'type'    => $type,
		);
	}

	/**
	 * Get logs.
	 *
	 * @return array
	 */
	public function get_logs() {
		return $this->logs;
	}

	/**
	 * Clear the logs.
	 *
	 * @return void
	 */
	public function clear_logs() {
		$this->logs = array();
	}

	/**
	 * Add a note.
	 *
	 * @param string $message Message to add to the notes.
	 * @param string $type Type of note.
	 * @return void
	 */
	public function add_note( $message, $type = 'success' ) {
		$this->notes[] = array(
			'message' => $message,
			'type'    => $type,
		);
	}

	/**
	 * Get notes.
	 *
	 * @return array
	 */
	public function get_notes() {
		return $this->notes;
	}

	/**
	 * Clear the notes.
	 *
	 * @return void
	 */
	public function clear_notes() {
		$this->notes = array();
	}
}
