<?php
/**
 * Klaviyo API List
 *
 * @package klaviyo-api
 */

namespace CrossPeakSoftware\GravityForms\Klaviyo\WPKlaviyoApi;

/**
 * Klaviyo API class that represents a Klaviyo List.
 */
class KlaviyoList {
	/**
	 * The ID of the Klaviyo list.
	 *
	 * @var string
	 */
	public $id;

	/**
	 * The name of the Klaviyo list.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * The creation date of the Klaviyo list.
	 *
	 * @var string
	 */
	public $created_at;

	/**
	 * The last updated date of the Klaviyo list.
	 *
	 * @var string
	 */
	public $updated_at;

	/**
	 * The opt-in process status of the Klaviyo list.
	 *
	 * @var bool
	 */
	public $opt_in_process;

	/**
	 * The type of the Klaviyo list.
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Constructor.
	 *
	 * @param array $data The data to initialize the Klaviyo list with.
	 */
	public function __construct( $data ) {
		$this->id             = $data['id'];
		$this->name           = $data['attributes']['name'];
		$this->created_at     = $data['attributes']['created'];
		$this->updated_at     = $data['attributes']['updated'];
		$this->opt_in_process = $data['attributes']['opt_in_process'];
		$this->type           = $data['type'];
	}

	/**
	 * Get the ID of the Klaviyo list.
	 *
	 * @return string The ID of the list.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the name of the Klaviyo list.
	 *
	 * @return string The name of the list.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get the creation date of the Klaviyo list.
	 *
	 * @return string The creation date of the list.
	 */
	public function get_created_at() {
		return $this->created_at;
	}

	/**
	 * Get the last updated date of the Klaviyo list.
	 *
	 * @return string The last updated date of the list.
	 */
	public function get_updated_at() {
		return $this->updated_at;
	}

	/**
	 * Get the opt-in process status of the Klaviyo list.
	 *
	 * @return bool The opt-in process status.
	 */
	public function get_opt_in_process() {
		return $this->opt_in_process;
	}

	/**
	 * Get the type of the Klaviyo list.
	 *
	 * @return string The type of the list.
	 */
	public function get_type() {
		return $this->type;
	}
}
