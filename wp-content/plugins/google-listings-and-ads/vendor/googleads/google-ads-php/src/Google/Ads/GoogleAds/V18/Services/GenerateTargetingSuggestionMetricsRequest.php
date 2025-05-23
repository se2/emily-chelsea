<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v18/services/audience_insights_service.proto

namespace Google\Ads\GoogleAds\V18\Services;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request message for
 * [AudienceInsightsService.GenerateTargetingSuggestionMetrics][google.ads.googleads.v18.services.AudienceInsightsService.GenerateTargetingSuggestionMetrics].
 *
 * Generated from protobuf message <code>google.ads.googleads.v18.services.GenerateTargetingSuggestionMetricsRequest</code>
 */
class GenerateTargetingSuggestionMetricsRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The ID of the customer.
     *
     * Generated from protobuf field <code>string customer_id = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    protected $customer_id = '';
    /**
     * Required. Audiences to request metrics for.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v18.services.BasicInsightsAudience audiences = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $audiences;
    /**
     * Optional. The name of the customer being planned for.  This is a
     * user-defined value.
     *
     * Generated from protobuf field <code>string customer_insights_group = 3 [(.google.api.field_behavior) = OPTIONAL];</code>
     */
    protected $customer_insights_group = '';

    /**
     * @param string                                                     $customerId Required. The ID of the customer.
     * @param \Google\Ads\GoogleAds\V18\Services\BasicInsightsAudience[] $audiences  Required. Audiences to request metrics for.
     *
     * @return \Google\Ads\GoogleAds\V18\Services\GenerateTargetingSuggestionMetricsRequest
     *
     * @experimental
     */
    public static function build(string $customerId, array $audiences): self
    {
        return (new self())
            ->setCustomerId($customerId)
            ->setAudiences($audiences);
    }

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $customer_id
     *           Required. The ID of the customer.
     *     @type array<\Google\Ads\GoogleAds\V18\Services\BasicInsightsAudience>|\Google\Protobuf\Internal\RepeatedField $audiences
     *           Required. Audiences to request metrics for.
     *     @type string $customer_insights_group
     *           Optional. The name of the customer being planned for.  This is a
     *           user-defined value.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Ads\GoogleAds\V18\Services\AudienceInsightsService::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The ID of the customer.
     *
     * Generated from protobuf field <code>string customer_id = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Required. The ID of the customer.
     *
     * Generated from protobuf field <code>string customer_id = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setCustomerId($var)
    {
        GPBUtil::checkString($var, True);
        $this->customer_id = $var;

        return $this;
    }

    /**
     * Required. Audiences to request metrics for.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v18.services.BasicInsightsAudience audiences = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getAudiences()
    {
        return $this->audiences;
    }

    /**
     * Required. Audiences to request metrics for.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v18.services.BasicInsightsAudience audiences = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param array<\Google\Ads\GoogleAds\V18\Services\BasicInsightsAudience>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setAudiences($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Ads\GoogleAds\V18\Services\BasicInsightsAudience::class);
        $this->audiences = $arr;

        return $this;
    }

    /**
     * Optional. The name of the customer being planned for.  This is a
     * user-defined value.
     *
     * Generated from protobuf field <code>string customer_insights_group = 3 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @return string
     */
    public function getCustomerInsightsGroup()
    {
        return $this->customer_insights_group;
    }

    /**
     * Optional. The name of the customer being planned for.  This is a
     * user-defined value.
     *
     * Generated from protobuf field <code>string customer_insights_group = 3 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @param string $var
     * @return $this
     */
    public function setCustomerInsightsGroup($var)
    {
        GPBUtil::checkString($var, True);
        $this->customer_insights_group = $var;

        return $this;
    }

}

