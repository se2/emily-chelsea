<?php
/**
 * Klaviyo API List
 *
 * @package klaviyo-api
 */

namespace CrossPeakSoftware\GravityForms\Klaviyo\WPKlaviyoApi;

/**
 * Klaviyo API class that represents a Klaviyo Profile.
 */
class KlaviyoProfile {

	/**
	 * The ID of the Klaviyo profile.
	 *
	 * @var string|null
	 */
	protected $id;

	/**
	 * The email address associated with the Klaviyo profile.
	 *
	 * @var string|null
	 */
	protected $email;

	/**
	 * The phone number associated with the Klaviyo profile.
	 *
	 * @var string|null
	 */
	protected $phone_number;

	/**
	 * The first name of the Klaviyo profile.
	 *
	 * @var string|null
	 */
	protected $first_name;

	/**
	 * The last name of the Klaviyo profile.
	 *
	 * @var string|null
	 */
	protected $last_name;

	/**
	 * The organization associated with the Klaviyo profile.
	 *
	 * @var string|null
	 */
	protected $organization;

	/**
	 * The locale of the Klaviyo profile.
	 *
	 * @var string|null
	 */
	protected $locale;

	/**
	 * The location information of the Klaviyo profile.
	 *
	 * @var array
	 */
	protected $location;

	/**
	 * Additional properties of the Klaviyo profile.
	 *
	 * @var array
	 */
	protected $properties;

	/**
	 * The subscriptions of the Klaviyo profile.
	 *
	 * @var array
	 */
	protected $subscriptions;

	/**
	 * Flag indicating if the profile was recently created.
	 *
	 * @var bool
	 */
	protected $recently_created = false;

	/**
	 * Flag indicating if the profile was created without a phone number.
	 *
	 * @var bool
	 */
	protected $created_without_phone_number = false;

	/**
	 * Tracks changes to the profile properties.
	 *
	 * @var array
	 */
	protected $dirty = array();

	/**
	 * The original data of the profile.
	 *
	 * @var array
	 */
	protected $original = array();

	/**
	 * KlaviyoProfile constructor.
	 *
	 * @param array $data The data to initialize the profile with.
	 */
	public function __construct( $data = array() ) {
		$this->original      = $data;
		$this->id            = $data['id'] ?? null;
		$this->email         = $data['email'] ?? null;
		$this->phone_number  = $data['phone_number'] ?? null;
		$this->first_name    = $data['first_name'] ?? null;
		$this->last_name     = $data['last_name'] ?? null;
		$this->organization  = $data['organization'] ?? null;
		$this->locale        = $data['locale'] ?? null;
		$this->location      = $data['location'] ?? array();
		$this->properties    = $data['properties'] ?? array();
		$this->subscriptions = $data['subscriptions'] ?? array();
	}

	/**
	 * Create a KlaviyoProfile instance from API data.
	 *
	 * @param array $data The data from the API.
	 * @return KlaviyoProfile The created profile instance.
	 */
	public static function from_api( $data ) {
		return new self(
			array(
				'id'            => $data['id'],
				'email'         => $data['attributes']['email'] ?? null,
				'phone_number'  => $data['attributes']['phone_number'] ?? null,
				'first_name'    => $data['attributes']['first_name'] ?? null,
				'last_name'     => $data['attributes']['last_name'] ?? null,
				'organization'  => $data['attributes']['organization'] ?? null,
				'locale'        => $data['attributes']['locale'] ?? null,
				'location'      => $data['attributes']['location'] ?? array(),
				'properties'    => $data['attributes']['properties'] ?? array(),
				'subscriptions' => $data['attributes']['subscriptions'] ?? array(),
			)
		);
	}

	/**
	 * Create a KlaviyoProfile instance from data.
	 *
	 * @param array $data The data to create the profile with.
	 * @return KlaviyoProfile The created profile instance.
	 */
	public static function make( $data ) {
		return new self( $data );
	}

	/**
	 * Set properties from another profile.
	 *
	 * @param KlaviyoProfile $profile The profile to copy properties from.
	 */
	public function set_from_profile( $profile ) {
		if ( ! is_null( $profile->get_email() ) && $profile->get_email() !== $this->get_email() ) {
			$this->set( 'email', $profile->get_email() );
		}
		if ( ! is_null( $profile->get_phone_number() ) && $profile->get_phone_number() !== $this->get_phone_number() ) {
			$this->set( 'phone_number', $profile->get_phone_number() );
		}
		if ( ! is_null( $profile->get_first_name() ) && $profile->get_first_name() !== $this->get_first_name() ) {
			$this->set( 'first_name', $profile->get_first_name() );
		}
		if ( ! is_null( $profile->get_last_name() ) && $profile->get_last_name() !== $this->get_last_name() ) {
			$this->set( 'last_name', $profile->get_last_name() );
		}
		if ( ! is_null( $profile->get_organization() ) && $profile->get_organization() !== $this->get_organization() ) {
			$this->set( 'organization', $profile->get_organization() );
		}
		if ( ! is_null( $profile->get_locale() ) && $profile->get_locale() !== $this->get_locale() ) {
			$this->set( 'locale', $profile->get_locale() );
		}
		$location = $profile->get_location();
		foreach ( $location as $key => $value ) {
			if ( ! is_null( $value ) && $value !== $this->get_location( $key ) ) {
				$this->set( $key, $value );
			}
		}
		$properties = $profile->get_properties();
		foreach ( $properties as $key => $value ) {
			if ( ! is_null( $value ) && $value !== $this->get_property( $key ) ) {
				$this->set( $key, $value );
			}
		}
	}

	/**
	 * Set a property value.
	 *
	 * @param string $key The property name.
	 * @param mixed  $value The property value.
	 */
	public function set( $key, $value ) {
		switch ( $key ) {
			case 'email':
				$this->email = $value;
				break;
			case 'phone_number':
				$this->phone_number = $value;
				break;
			case 'first_name':
				$this->first_name = $value;
				break;
			case 'last_name':
				$this->last_name = $value;
				break;
			case 'organization':
				$this->organization = $value;
				break;
			case 'locale':
				$this->locale = $value;
				break;
			case 'location':
				$this->location = $value;
				break;
			case 'location.address1':
			case 'address1':
				$this->location['address1'] = $value;
				break;
			case 'location.region':
			case 'region':
				$this->location['region'] = $value;
				break;
			case 'location.city':
			case 'city':
				$this->location['city'] = $value;
				break;
			case 'location.zip':
			case 'zip':
				$this->location['zip'] = $value;
				break;
			case 'location.longitute':
			case 'longitute':
				$this->location['longitute'] = $value;
				break;
			case 'location.latitude':
			case 'latitude':
				$this->location['latitude'] = $value;
				break;
			case 'location.address2':
			case 'address2':
				$this->location['address2'] = $value;
				break;
			case 'location.timezone':
			case 'timezone':
				$this->location['timezone'] = $value;
				break;
			case 'location.ip':
			case 'ip':
				$this->location['ip'] = $value;
				break;
			default:
				$this->properties[ $key ] = $value;
				break;
		}
		$this->dirty[ $key ] = true;
	}

	/**
	 * Prevent setting properties dynamically.
	 *
	 * @param string $key The property name.
	 * @param mixed  $value The property value.
	 * @throws \Exception If trying to set a property dynamically.
	 */
	public function __set( $key, $value ) {
		throw new \Exception( 'Cannot set property: ' . esc_html( $key ) );
	}

	/**
	 * Get the ID of the Klaviyo profile.
	 *
	 * @return string|null The ID of the profile.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the email of the Klaviyo profile.
	 *
	 * @return string|null The email of the profile.
	 */
	public function get_email() {
		return $this->email;
	}

	/**
	 * Get the phone number of the Klaviyo profile.
	 *
	 * @return string|null The phone number of the profile.
	 */
	public function get_phone_number() {
		return $this->phone_number;
	}

	/**
	 * Get the first name of the Klaviyo profile.
	 *
	 * @return string|null The first name of the profile.
	 */
	public function get_first_name() {
		return $this->first_name;
	}

	/**
	 * Get the last name of the Klaviyo profile.
	 *
	 * @return string|null The last name of the profile.
	 */
	public function get_last_name() {
		return $this->last_name;
	}

	/**
	 * Get the organization of the Klaviyo profile.
	 *
	 * @return string|null The organization of the profile.
	 */
	public function get_organization() {
		return $this->organization;
	}

	/**
	 * Get the locale of the Klaviyo profile.
	 *
	 * @return string|null The locale of the profile.
	 */
	public function get_locale() {
		return $this->locale;
	}

	/**
	 * Get the location of the Klaviyo profile.
	 *
	 * @param string|null $key The location key.
	 * @return array|string|null The location of the profile.
	 */
	public function get_location( $key = null ) {
		if ( is_null( $key ) ) {
			return $this->location;
		}
		return $this->location[ $key ] ?? null;
	}

	/**
	 * Get the additional properties of the Klaviyo profile.
	 *
	 * @return array The properties of the profile.
	 */
	public function get_properties() {
		return $this->properties;
	}

	/**
	 * Get a specific property of the Klaviyo profile.
	 *
	 * @param string $key The property name.
	 * @return mixed|null The property value.
	 */
	public function get_property( $key ) {
		return $this->properties[ $key ] ?? null;
	}

	/**
	 * Get the subscriptions of the Klaviyo profile.
	 *
	 * @return array The subscriptions of the profile.
	 */
	public function get_subscriptions() {
		return $this->subscriptions;
	}

	/**
	 * Flag the profile as recently created.
	 */
	public function flag_recently_created() {
		$this->recently_created = true;
	}

	/**
	 * Check if the profile was recently created.
	 *
	 * @return bool True if the profile was recently created, false otherwise.
	 */
	public function was_recently_created() {
		return $this->recently_created;
	}

	/**
	 * Flag the profile as created without a phone number.
	 */
	public function flag_created_without_phone_number() {
		$this->created_without_phone_number = true;
	}

	/**
	 * Check if the profile was created without a phone number.
	 *
	 * @return bool True if the profile was created without a phone number, false otherwise.
	 */
	public function was_created_without_phone_number() {
		return $this->created_without_phone_number;
	}

	/**
	 * Convert the profile data to the format required for creating via the API.
	 *
	 * @return array The profile data formatted for API creation.
	 */
	public function to_create_api() {
		$data = array(
			'data' => array(
				'type'       => 'profile',
				'attributes' => array(),
			),
		);
		if ( ! empty( $this->email ) ) {
			$data['data']['attributes']['email'] = $this->email;
		}
		if ( ! empty( $this->phone_number ) ) {
			$data['data']['attributes']['phone_number'] = $this->phone_number;
		}
		if ( ! empty( $this->first_name ) ) {
			$data['data']['attributes']['first_name'] = $this->first_name;
		}
		if ( ! empty( $this->last_name ) ) {
			$data['data']['attributes']['last_name'] = $this->last_name;
		}
		if ( ! empty( $this->organization ) ) {
			$data['data']['attributes']['organization'] = $this->organization;
		}
		if ( ! empty( $this->locale ) ) {
			$data['data']['attributes']['locale'] = $this->locale;
		}
		if ( ! empty( $this->location ) ) {
			$data['data']['attributes']['location'] = $this->location;
		}
		if ( ! empty( $this->properties ) ) {
			$data['data']['attributes']['properties'] = $this->properties;
		}
		return $data;
	}

	/**
	 * Convert the profile data to the format required for updating via the API.
	 *
	 * @return array The profile data formatted for API updating.
	 */
	public function to_update_api() {
		$data = array(
			'data' => array(
				'type'       => 'profile',
				'id'         => $this->id,
				'attributes' => array(),
			),
		);
		foreach ( $this->dirty as $key => $_ ) {
			switch ( $key ) {
				case 'email':
				case 'phone_number':
				case 'first_name':
				case 'last_name':
				case 'organization':
				case 'locale':
				case 'location':
				case 'properties':
					if ( ( $this->original[ $key ] ?? null ) !== $this->$key ) {
						$data['data']['attributes'][ $key ] = $this->$key;
					}
					break;
				case 'location.region':
				case 'location.city':
				case 'location.zip':
				case 'location.longitute':
				case 'location.latitude':
				case 'location.address2':
				case 'location.timezone':
				case 'location.ip':
					$key = str_replace( 'location.', '', $key );
					if ( ( $this->original['location'][ $key ] ?? null ) !== $this->location[ $key ] ?? null ) {
						$data['data']['attributes']['location'][ $key ] = $this->location[ $key ];
					}
					break;
				case 'address1':
				case 'timezone':
				case 'address2':
				case 'latitude':
				case 'longitute':
				case 'zip':
				case 'city':
				case 'region':
				case 'ip':
					if ( ( $this->original['location'][ $key ] ?? null ) !== $this->location[ $key ] ?? null ) {
						$data['data']['attributes']['location'][ $key ] = $this->location[ $key ];
					}
					break;
				default:
					if ( ( $this->original['properties'][ $key ] ?? null ) !== $this->properties[ $key ] ) {
						$data['data']['attributes']['properties'][ $key ] = $this->properties[ $key ];
					}
					break;
			}
		}
		return $data;
	}
}
