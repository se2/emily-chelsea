<?php

GFForms::include_feed_addon_framework();

class TTG_GFKlaviyoAPI extends GFFeedAddOn
{
	/**
	 * Special attributes as identified by Klaviyo
	 *
	 * @var string[]
	 */
	public $specialAttributes = array(
		'email',
		'first_name',
		'last_name',
		'organization',
		'title',
		'address1',
		'address2',
		'city',
		'region',
		'zip',
		'country',
		'phone_number',
	);

	protected $_version = TTG_GF_KLAVIYO_API_VERSION;
	protected $_min_gravityforms_version = '1.9.16';
	protected $_slug = TTG_GF_KLAVIYO_PREFIX . '-klaviyoaddon';
	protected $_path = 'klaviyoaddon/klaviyoaddon.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms Klaviyo Feed Add-On';
	protected $_short_title = TTG_GF_KLAVIYO_PREFIX . ' Klaviyo';

	private static $_instance = null;
	private $klaviyoClient = null;

	public function __construct()
	{
		parent::__construct();
		$this->klaviyoClient =  new TTG_Klaviyo($this->get_plugin_setting('private_api_key'), $this->get_plugin_setting('api_key'));
	}

	public function getKlaviyoClient()
	{
		return $this->klaviyoClient;
	}

	/**
	 * Get an instance of this class.
	 *
	 * @return TTG_GFKlaviyoAPI
	 */
	public static function get_instance()
	{
		if (self::$_instance == null) {
			self::$_instance = new TTG_GFKlaviyoAPI();
		}

		return self::$_instance;
	}

	/**
	 * Plugin starting point. Handles hooks, loading of language files and PayPal delayed payment support.
	 */
	public function init()
	{

		parent::init();

		$this->add_delayed_payment_support(
			array(
				'option_label' => esc_html__('Subscribe contact to service x only when payment is received.', TTG_GF_KLAVIYO_TEXT_DOMAIN)
			)
		);
	}

	/**	 
	 * Check public key and private key are added
	 * 
	 * @return bool
	 */
	public function isKeyValid()
	{
		return !empty($this->get_plugin_setting('api_key')) && !empty($this->get_plugin_setting('private_api_key'));
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
	public function process_feed($feed, $entry, $form)
	{
		if (!$this->isKeyValid()) {
			return;
		}

		$feedName  = $feed['meta']['feedName'];
		$list_id = $feed['meta']['list'];

		// Retrieve the name => value pairs for all fields mapped in the 'mappedFields' field map.
		$field_map = $this->get_field_map_fields($feed, 'mappedFields');
		$dynamic_field_map = $this->get_dynamic_field_map_fields($feed, 'dynamicMappedFields');
		$keys = array_flip($this->specialAttributes);



		// Loop through the fields from the field map setting building an array of values to be passed to the third-party service.
		$properties = array();
		foreach ($field_map as $name => $field_id) {
			// Get the field value for the specified field id
			$key =  $name;
			$value = $this->get_field_value($form, $entry, $field_id);

			if (isset($keys[$name]) && !empty($value)) {
				$properties[$key] = $value;
			}
		}

		$custom_properties = array();
		if (!empty($dynamic_field_map)) {
			foreach ($dynamic_field_map as $name => $field_id) {
				$custom_properties[$name] = $this->get_field_value($form, $entry, $field_id);
			}
		}

		// Track Profile Activity
		$this->klaviyoClient->track(
			'Active on Site',
			$properties,
			$custom_properties,
		);

		// add memeber to list
		$members_properties = array_merge($properties, [
			'consent' => 'email',
			'source' => 'GravityForms: ' . $form['title']
		], $custom_properties);

		$this->klaviyoClient->add_members_to_list($list_id, [$members_properties]);
	}

	/**
	 * Custom format the phone type field values before they are returned by $this->get_field_value().
	 *
	 * @param array $entry The Entry currently being processed.
	 * @param string $field_id The ID of the Field currently being processed.
	 * @param GF_Field_Phone $field The Field currently being processed.
	 *
	 * @return string
	 */
	public function get_phone_field_value($entry, $field_id, $field)
	{

		// Get the field value from the Entry Object.
		$field_value = rgar($entry, $field_id);

		// If there is a value and the field phoneFormat setting is set to standard reformat the value.
		if (!empty($field_value) && $field->phoneFormat == 'standard' && preg_match('/^\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/', $field_value, $matches)) {
			$field_value = sprintf('%s-%s-%s', $matches[1], $matches[2], $matches[3]);
		}

		return $field_value;
	}

	// # ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------


	/**
	 * Configures the settings which should be rendered on the add-on settings tab.
	 *
	 * @return array
	 */
	public function plugin_settings_fields()
	{
		return array(
			array(
				'title'  => esc_html__('Insert your Klaviyo API keys below to connect. You can find them on your Klaviyo account page.', 'klaviyoaddon'),
				'fields' => array(
					array(
						'name'    => 'api_key',
						'label'   => esc_html__('Public API Key', 'klaviyoaddon'),
						'type'    => 'text',
						'class'   => 'small',
					),
					array(
						'name'    => 'private_api_key',
						'label'   => esc_html__('Private API Key', 'klaviyoaddon'),
						'type'    => 'text',
						'class'   => 'medium',
					),
				),
			),
		);
	}

	/**
	 * Configures the settings which should be rendered on the feed edit page in the Form Settings > Klaviyo area.
	 *
	 * @return array
	 */
	public function feed_settings_fields()
	{

		return array(
			array(
				'title'  => esc_html__('Klaviyo Feed Settings', TTG_GF_KLAVIYO_TEXT_DOMAIN),
				'fields' => array(
					array(
						'label'   => esc_html__('Feed name', TTG_GF_KLAVIYO_TEXT_DOMAIN),
						'type'    => 'text',
						'name'    => 'feedName',
						'class'   => 'small',
						'tooltip'  => '<h6>' . esc_html__('Name', TTG_GF_KLAVIYO_TEXT_DOMAIN) . '</h6>' . esc_html__('Enter a feed name to uniquely identify this setup.', TTG_GF_KLAVIYO_TEXT_DOMAIN)
					),
					array(
						'name'     => 'list',
						'label'    => esc_html__('Klaviyo List', TTG_GF_KLAVIYO_TEXT_DOMAIN),
						'type'     => 'select',
						'required' => true,
						'choices'  => $this->lists_for_feed_setting(),
						'tooltip'  => '<h6>' . esc_html__('Klaviyo List', TTG_GF_KLAVIYO_TEXT_DOMAIN) . '</h6>' . esc_html__('Select which Klaviyo list this feed will add contacts to.', TTG_GF_KLAVIYO_TEXT_DOMAIN)
					),
					array(
						'name'      => 'mappedFields',
						'label'     => esc_html__('Map Fields (Properties)', TTG_GF_KLAVIYO_TEXT_DOMAIN),
						'type'      => 'field_map',
						'field_map' => array(
							array(
								'name'       => 'email',
								'label'      => esc_html__('Email', TTG_GF_KLAVIYO_TEXT_DOMAIN),
								'required'   => true,
								'field_type' => array('email', 'hidden'),
							),
							array(
								'name'     => 'first_name',
								'label'    => esc_html__('First Name', TTG_GF_KLAVIYO_TEXT_DOMAIN),
								'required' => true
							),
							array(
								'name'     => 'last_name',
								'label'    => esc_html__('Last Name', TTG_GF_KLAVIYO_TEXT_DOMAIN),
								'required' => true
							),
							array(
								'name'     => 'phone_number',
								'label'    => esc_html__('Phone', TTG_GF_KLAVIYO_TEXT_DOMAIN),
								'required' => false
							),
							array(
								'name'     => 'organization',
								'label'    => esc_html__('Organization', TTG_GF_KLAVIYO_TEXT_DOMAIN),
								'required' => false
							),
							array(
								'name'     => 'title',
								'label'    => esc_html__('Title', TTG_GF_KLAVIYO_TEXT_DOMAIN),
								'required' => false
							),
							array(
								'name'     => 'address1',
								'label'    => esc_html__('Address 1', TTG_GF_KLAVIYO_TEXT_DOMAIN),
								'required' => false
							),
							array(
								'name'     => 'address2',
								'label'    => esc_html__('Address 2', TTG_GF_KLAVIYO_TEXT_DOMAIN),
								'required' => false
							),
							array(
								'name'     => 'country',
								'label'    => esc_html__('Country', TTG_GF_KLAVIYO_TEXT_DOMAIN),
								'required' => false
							),
							array(
								'name'     => 'city',
								'label'    => esc_html__('City', TTG_GF_KLAVIYO_TEXT_DOMAIN),
								'required' => false
							),
							array(
								'name'     => 'region',
								'label'    => esc_html__('State/Region', TTG_GF_KLAVIYO_TEXT_DOMAIN),
								'required' => false
							),
							array(
								'name'     => 'zip',
								'label'    => esc_html__('Zip', TTG_GF_KLAVIYO_TEXT_DOMAIN),
								'required' => false
							),
						),
					),
					array(
						'name'           => 'dynamicMappedFields',
						'label'          => esc_html__('Other fields (Custom Properties)', TTG_GF_KLAVIYO_TEXT_DOMAIN),
						'type'           => 'dynamic_field_map',
					),
					array(
						'name'           => 'condition',
						'label'          => esc_html__('Condition', TTG_GF_KLAVIYO_TEXT_DOMAIN),
						'type'           => 'feed_condition',
						'checkbox_label' => esc_html__('Enable Condition', TTG_GF_KLAVIYO_TEXT_DOMAIN),
						'instructions'   => esc_html__('Process this feed if', TTG_GF_KLAVIYO_TEXT_DOMAIN),
					),
				),
			),
		);
	}

	/**
	 * Configures which columns should be displayed on the feed list page.
	 *
	 * @return array
	 */
	public function feed_list_columns()
	{
		return array(
			'feedName'  => esc_html__('Name', TTG_GF_KLAVIYO_TEXT_DOMAIN),
			'list' => esc_html__('Klaviyo List', TTG_GF_KLAVIYO_TEXT_DOMAIN),
		);
	}

	/**
	 * Format the value to be displayed in the mytextbox column.
	 *
	 * @param array $feed The feed being included in the feed list.
	 *
	 * @return string
	 */
	public function get_column_value_mytextbox($feed)
	{
		return '<b>' . rgars($feed, 'meta/mytextbox') . '</b>';
	}

	/**
	 * Prevent feeds being listed or created if an api key isn't valid.
	 *
	 * @return bool
	 */
	public function can_create_feed()
	{
		return $this->isKeyValid();
	}

	public function lists_for_feed_setting()
	{
		$lists = array(
			array(
				'label' => '',
				'value' => ''
			)
		);
		$ac_lists = $this->klaviyoClient->get_lists();
		/* Add Klaviyo lists to array and return it. */
		$lists = array();
		foreach ($ac_lists as $list) {
			$lists[] = array(
				'label' => $list['list_name'],
				'value' => $list['list_id']
			);
		}

		return $lists;
	}
}

function gf_klaviyo_api_feed()
{
	return TTG_GFKlaviyoAPI::get_instance();
}
