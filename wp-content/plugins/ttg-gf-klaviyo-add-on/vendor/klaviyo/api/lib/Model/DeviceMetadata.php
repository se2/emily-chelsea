<?php
/**
 * DeviceMetadata
 *
 * PHP version 7.4
 *
 * @category Class
 * @package  KlaviyoAPI
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Klaviyo API
 *
 * The Klaviyo REST API. Please visit https://developers.klaviyo.com for more details.
 *
 * The version of the OpenAPI document: 2024-07-15
 * Contact: developers@klaviyo.com
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 6.1.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace KlaviyoAPI\Model;

use \ArrayAccess;
use \KlaviyoAPI\ObjectSerializer;

/**
 * DeviceMetadata Class Doc Comment
 *
 * @category Class
 * @package  KlaviyoAPI
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class DeviceMetadata implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'DeviceMetadata';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'device_id' => 'string',
        'klaviyo_sdk' => 'string',
        'sdk_version' => 'string',
        'device_model' => 'string',
        'os_name' => 'string',
        'os_version' => 'string',
        'manufacturer' => 'string',
        'app_name' => 'string',
        'app_version' => 'string',
        'app_build' => 'string',
        'app_id' => 'string',
        'environment' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'device_id' => null,
        'klaviyo_sdk' => null,
        'sdk_version' => null,
        'device_model' => null,
        'os_name' => null,
        'os_version' => null,
        'manufacturer' => null,
        'app_name' => null,
        'app_version' => null,
        'app_build' => null,
        'app_id' => null,
        'environment' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'device_id' => true,
		'klaviyo_sdk' => true,
		'sdk_version' => true,
		'device_model' => true,
		'os_name' => true,
		'os_version' => true,
		'manufacturer' => true,
		'app_name' => true,
		'app_version' => true,
		'app_build' => true,
		'app_id' => true,
		'environment' => true
    ];

    /**
      * If a nullable field gets set to null, insert it here
      *
      * @var boolean[]
      */
    protected array $openAPINullablesSetToNull = [];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of nullable properties
     *
     * @return array
     */
    protected static function openAPINullables(): array
    {
        return self::$openAPINullables;
    }

    /**
     * Array of nullable field names deliberately set to null
     *
     * @return boolean[]
     */
    private function getOpenAPINullablesSetToNull(): array
    {
        return $this->openAPINullablesSetToNull;
    }

    /**
     * Checks if a property is nullable
     *
     * @param string $property
     * @return bool
     */
    public static function isNullable(string $property): bool
    {
        return self::openAPINullables()[$property] ?? false;
    }

    /**
     * Checks if a nullable property is set to null.
     *
     * @param string $property
     * @return bool
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'device_id' => 'device_id',
        'klaviyo_sdk' => 'klaviyo_sdk',
        'sdk_version' => 'sdk_version',
        'device_model' => 'device_model',
        'os_name' => 'os_name',
        'os_version' => 'os_version',
        'manufacturer' => 'manufacturer',
        'app_name' => 'app_name',
        'app_version' => 'app_version',
        'app_build' => 'app_build',
        'app_id' => 'app_id',
        'environment' => 'environment'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'device_id' => 'setDeviceId',
        'klaviyo_sdk' => 'setKlaviyoSdk',
        'sdk_version' => 'setSdkVersion',
        'device_model' => 'setDeviceModel',
        'os_name' => 'setOsName',
        'os_version' => 'setOsVersion',
        'manufacturer' => 'setManufacturer',
        'app_name' => 'setAppName',
        'app_version' => 'setAppVersion',
        'app_build' => 'setAppBuild',
        'app_id' => 'setAppId',
        'environment' => 'setEnvironment'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'device_id' => 'getDeviceId',
        'klaviyo_sdk' => 'getKlaviyoSdk',
        'sdk_version' => 'getSdkVersion',
        'device_model' => 'getDeviceModel',
        'os_name' => 'getOsName',
        'os_version' => 'getOsVersion',
        'manufacturer' => 'getManufacturer',
        'app_name' => 'getAppName',
        'app_version' => 'getAppVersion',
        'app_build' => 'getAppBuild',
        'app_id' => 'getAppId',
        'environment' => 'getEnvironment'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }

    public const KLAVIYO_SDK_ANDROID = 'android';
    public const KLAVIYO_SDK_SWIFT = 'swift';
    public const OS_NAME_ANDROID = 'android';
    public const OS_NAME_IOS = 'ios';
    public const OS_NAME_IPADOS = 'ipados';
    public const OS_NAME_MACOS = 'macos';
    public const OS_NAME_TVOS = 'tvos';
    public const ENVIRONMENT_DEBUG = 'debug';
    public const ENVIRONMENT_RELEASE = 'release';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getKlaviyoSdkAllowableValues()
    {
        return [
            self::KLAVIYO_SDK_ANDROID,
            self::KLAVIYO_SDK_SWIFT,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getOsNameAllowableValues()
    {
        return [
            self::OS_NAME_ANDROID,
            self::OS_NAME_IOS,
            self::OS_NAME_IPADOS,
            self::OS_NAME_MACOS,
            self::OS_NAME_TVOS,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getEnvironmentAllowableValues()
    {
        return [
            self::ENVIRONMENT_DEBUG,
            self::ENVIRONMENT_RELEASE,
        ];
    }

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->setIfExists('device_id', $data ?? [], null);
        $this->setIfExists('klaviyo_sdk', $data ?? [], null);
        $this->setIfExists('sdk_version', $data ?? [], null);
        $this->setIfExists('device_model', $data ?? [], null);
        $this->setIfExists('os_name', $data ?? [], null);
        $this->setIfExists('os_version', $data ?? [], null);
        $this->setIfExists('manufacturer', $data ?? [], null);
        $this->setIfExists('app_name', $data ?? [], null);
        $this->setIfExists('app_version', $data ?? [], null);
        $this->setIfExists('app_build', $data ?? [], null);
        $this->setIfExists('app_id', $data ?? [], null);
        $this->setIfExists('environment', $data ?? [], null);
    }

    /**
    * Sets $this->container[$variableName] to the given data or to the given default Value; if $variableName
    * is nullable and its value is set to null in the $fields array, then mark it as "set to null" in the
    * $this->openAPINullablesSetToNull array
    *
    * @param string $variableName
    * @param array  $fields
    * @param mixed  $defaultValue
    */
    private function setIfExists(string $variableName, array $fields, $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }

        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        $allowedValues = $this->getKlaviyoSdkAllowableValues();
        if (!is_null($this->container['klaviyo_sdk']) && !in_array($this->container['klaviyo_sdk'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'klaviyo_sdk', must be one of '%s'",
                $this->container['klaviyo_sdk'],
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getOsNameAllowableValues();
        if (!is_null($this->container['os_name']) && !in_array($this->container['os_name'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'os_name', must be one of '%s'",
                $this->container['os_name'],
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getEnvironmentAllowableValues();
        if (!is_null($this->container['environment']) && !in_array($this->container['environment'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'environment', must be one of '%s'",
                $this->container['environment'],
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets device_id
     *
     * @return string|null
     */
    public function getDeviceId()
    {
        return $this->container['device_id'];
    }

    /**
     * Sets device_id
     *
     * @param string|null $device_id Relatively stable ID for the device. Will update on app uninstall and reinstall
     *
     * @return self
     */
    public function setDeviceId($device_id)
    {

        if (is_null($device_id)) {
            array_push($this->openAPINullablesSetToNull, 'device_id');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('device_id', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['device_id'] = $device_id;

        return $this;
    }

    /**
     * Gets klaviyo_sdk
     *
     * @return string|null
     */
    public function getKlaviyoSdk()
    {
        return $this->container['klaviyo_sdk'];
    }

    /**
     * Sets klaviyo_sdk
     *
     * @param string|null $klaviyo_sdk The name of the SDK used to create the push token.
     *
     * @return self
     */
    public function setKlaviyoSdk($klaviyo_sdk)
    {
        $allowedValues = $this->getKlaviyoSdkAllowableValues();
        if (!is_null($klaviyo_sdk) && !in_array($klaviyo_sdk, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'klaviyo_sdk', must be one of '%s'",
                    $klaviyo_sdk,
                    implode("', '", $allowedValues)
                )
            );
        }

        if (is_null($klaviyo_sdk)) {
            array_push($this->openAPINullablesSetToNull, 'klaviyo_sdk');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('klaviyo_sdk', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['klaviyo_sdk'] = $klaviyo_sdk;

        return $this;
    }

    /**
     * Gets sdk_version
     *
     * @return string|null
     */
    public function getSdkVersion()
    {
        return $this->container['sdk_version'];
    }

    /**
     * Sets sdk_version
     *
     * @param string|null $sdk_version The version of the SDK used to create the push token
     *
     * @return self
     */
    public function setSdkVersion($sdk_version)
    {

        if (is_null($sdk_version)) {
            array_push($this->openAPINullablesSetToNull, 'sdk_version');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('sdk_version', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['sdk_version'] = $sdk_version;

        return $this;
    }

    /**
     * Gets device_model
     *
     * @return string|null
     */
    public function getDeviceModel()
    {
        return $this->container['device_model'];
    }

    /**
     * Sets device_model
     *
     * @param string|null $device_model The model of the device
     *
     * @return self
     */
    public function setDeviceModel($device_model)
    {

        if (is_null($device_model)) {
            array_push($this->openAPINullablesSetToNull, 'device_model');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('device_model', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['device_model'] = $device_model;

        return $this;
    }

    /**
     * Gets os_name
     *
     * @return string|null
     */
    public function getOsName()
    {
        return $this->container['os_name'];
    }

    /**
     * Sets os_name
     *
     * @param string|null $os_name The name of the operating system on the device.
     *
     * @return self
     */
    public function setOsName($os_name)
    {
        $allowedValues = $this->getOsNameAllowableValues();
        if (!is_null($os_name) && !in_array($os_name, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'os_name', must be one of '%s'",
                    $os_name,
                    implode("', '", $allowedValues)
                )
            );
        }

        if (is_null($os_name)) {
            array_push($this->openAPINullablesSetToNull, 'os_name');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('os_name', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['os_name'] = $os_name;

        return $this;
    }

    /**
     * Gets os_version
     *
     * @return string|null
     */
    public function getOsVersion()
    {
        return $this->container['os_version'];
    }

    /**
     * Sets os_version
     *
     * @param string|null $os_version The version of the operating system on the device
     *
     * @return self
     */
    public function setOsVersion($os_version)
    {

        if (is_null($os_version)) {
            array_push($this->openAPINullablesSetToNull, 'os_version');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('os_version', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['os_version'] = $os_version;

        return $this;
    }

    /**
     * Gets manufacturer
     *
     * @return string|null
     */
    public function getManufacturer()
    {
        return $this->container['manufacturer'];
    }

    /**
     * Sets manufacturer
     *
     * @param string|null $manufacturer The manufacturer of the device
     *
     * @return self
     */
    public function setManufacturer($manufacturer)
    {

        if (is_null($manufacturer)) {
            array_push($this->openAPINullablesSetToNull, 'manufacturer');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('manufacturer', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['manufacturer'] = $manufacturer;

        return $this;
    }

    /**
     * Gets app_name
     *
     * @return string|null
     */
    public function getAppName()
    {
        return $this->container['app_name'];
    }

    /**
     * Sets app_name
     *
     * @param string|null $app_name The name of the app that created the push token
     *
     * @return self
     */
    public function setAppName($app_name)
    {

        if (is_null($app_name)) {
            array_push($this->openAPINullablesSetToNull, 'app_name');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('app_name', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['app_name'] = $app_name;

        return $this;
    }

    /**
     * Gets app_version
     *
     * @return string|null
     */
    public function getAppVersion()
    {
        return $this->container['app_version'];
    }

    /**
     * Sets app_version
     *
     * @param string|null $app_version The version of the app that created the push token
     *
     * @return self
     */
    public function setAppVersion($app_version)
    {

        if (is_null($app_version)) {
            array_push($this->openAPINullablesSetToNull, 'app_version');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('app_version', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['app_version'] = $app_version;

        return $this;
    }

    /**
     * Gets app_build
     *
     * @return string|null
     */
    public function getAppBuild()
    {
        return $this->container['app_build'];
    }

    /**
     * Sets app_build
     *
     * @param string|null $app_build The build of the app that created the push token
     *
     * @return self
     */
    public function setAppBuild($app_build)
    {

        if (is_null($app_build)) {
            array_push($this->openAPINullablesSetToNull, 'app_build');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('app_build', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['app_build'] = $app_build;

        return $this;
    }

    /**
     * Gets app_id
     *
     * @return string|null
     */
    public function getAppId()
    {
        return $this->container['app_id'];
    }

    /**
     * Sets app_id
     *
     * @param string|null $app_id The ID of the app that created the push token
     *
     * @return self
     */
    public function setAppId($app_id)
    {

        if (is_null($app_id)) {
            array_push($this->openAPINullablesSetToNull, 'app_id');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('app_id', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['app_id'] = $app_id;

        return $this;
    }

    /**
     * Gets environment
     *
     * @return string|null
     */
    public function getEnvironment()
    {
        return $this->container['environment'];
    }

    /**
     * Sets environment
     *
     * @param string|null $environment The environment in which the push token was created
     *
     * @return self
     */
    public function setEnvironment($environment)
    {
        $allowedValues = $this->getEnvironmentAllowableValues();
        if (!is_null($environment) && !in_array($environment, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'environment', must be one of '%s'",
                    $environment,
                    implode("', '", $allowedValues)
                )
            );
        }

        if (is_null($environment)) {
            array_push($this->openAPINullablesSetToNull, 'environment');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('environment', $nullablesSetToNull);
            if ($index !== FALSE) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }

        $this->container['environment'] = $environment;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


