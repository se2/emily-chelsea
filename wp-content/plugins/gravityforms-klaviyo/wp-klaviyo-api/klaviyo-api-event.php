<?php
/**
 * Klaviyo API Event
 *
 * @package klaviyo-api
 */

namespace CrossPeakSoftware\GravityForms\Klaviyo\WPKlaviyoApi;

/**
 * Klaviyo API class.
 */
class KlaviyoEvent {
	/**
	 * The metric name.
	 *
	 * @var string
	 */
	public $metric;

	/**
	 * The properties.
	 *
	 * @var array
	 */
	public $properties = array();

	/**
	 * The value.
	 *
	 * @var ?int
	 */
	public $value;

	/**
	 * Constructor.
	 *
	 * @param array $data The data.
	 */
	public function __construct( $data = array() ) {
		if ( ! empty( $data ) ) {
			$this->metric     = $data['metric'] ?? '';
			$this->properties = $data['properties'] ?? array();
			$this->value      = $data['value'] ?? null;
		}
	}

	/**
	 * Set the metric name.
	 *
	 * @param string $metric The metric name.
	 */
	public function set_metric( $metric ) {
		$this->metric = $metric;
	}

	/**
	 * Add a property.
	 *
	 * @param string $name The property name.
	 * @param mixed  $value The property value.
	 */
	public function add_property( $name, $value ) {
		$this->properties[ $name ] = $value;
	}

	/**
	 * Set the value.
	 *
	 * @param ?int $value The value.
	 */
	public function set_value( $value ) {
		$this->value = $value;
	}

	/**
	 * Create a new KlaviyoEvent from the API data.
	 *
	 * @param array $data The API data.
	 *
	 * @return KlaviyoEvent The new KlaviyoEvent.
	 */
	public static function from_api( $data ) {
		$metric_name = '';
		if ( ! empty( $data['relationships']['metric']['data']['name'] ) ) {
			$metric_name = $data['relationships']['metric']['data']['name'];
		}
		return new self(
			array(
				'metric'     => $metric_name,
				'properties' => $data['attributes']['event_properties'],
				'value'      => $data['attributes']['event_properties']['$value'] ?? null,
			)
		);
	}

	/**
	 * Convert the KlaviyoEvent to the API attributes.
	 *
	 * @return array The API attributes.
	 */
	public function to_api_attributes() {
		return array(
			'properties' => $this->properties,
			'metric'     => array(
				'data' => array(
					'type'       => 'metric',
					'attributes' => array(
						'name' => $this->metric,
					),
				),
			),
		);
	}

	/**
	 * Get the metric name.
	 *
	 * @return string The metric name.
	 */
	public function get_metric() {
		return $this->metric;
	}

	/**
	 * Get the properties.
	 *
	 * @return array The properties.
	 */
	public function get_properties() {
		return $this->properties;
	}

	/**
	 * Get the value.
	 *
	 * @return ?int The value.
	 */
	public function get_value() {
		return $this->value;
	}
}
