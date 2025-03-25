<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v18/services/audience_insights_service.proto

namespace Google\Ads\GoogleAds\V18\Services;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * A collection of related attributes of the same type in an audience
 * composition insights report.
 *
 * Generated from protobuf message <code>google.ads.googleads.v18.services.AudienceCompositionSection</code>
 */
class AudienceCompositionSection extends \Google\Protobuf\Internal\Message
{
    /**
     * The type of the attributes in this section.
     *
     * Generated from protobuf field <code>.google.ads.googleads.v18.enums.AudienceInsightsDimensionEnum.AudienceInsightsDimension dimension = 1;</code>
     */
    protected $dimension = 0;
    /**
     * The most relevant segments for this audience.  If dimension is GENDER,
     * AGE_RANGE or PARENTAL_STATUS, then this list of attributes is exhaustive.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v18.services.AudienceCompositionAttribute top_attributes = 3;</code>
     */
    private $top_attributes;
    /**
     * Additional attributes for this audience, grouped into clusters.  Only
     * populated if dimension is YOUTUBE_CHANNEL.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v18.services.AudienceCompositionAttributeCluster clustered_attributes = 4;</code>
     */
    private $clustered_attributes;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $dimension
     *           The type of the attributes in this section.
     *     @type array<\Google\Ads\GoogleAds\V18\Services\AudienceCompositionAttribute>|\Google\Protobuf\Internal\RepeatedField $top_attributes
     *           The most relevant segments for this audience.  If dimension is GENDER,
     *           AGE_RANGE or PARENTAL_STATUS, then this list of attributes is exhaustive.
     *     @type array<\Google\Ads\GoogleAds\V18\Services\AudienceCompositionAttributeCluster>|\Google\Protobuf\Internal\RepeatedField $clustered_attributes
     *           Additional attributes for this audience, grouped into clusters.  Only
     *           populated if dimension is YOUTUBE_CHANNEL.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Ads\GoogleAds\V18\Services\AudienceInsightsService::initOnce();
        parent::__construct($data);
    }

    /**
     * The type of the attributes in this section.
     *
     * Generated from protobuf field <code>.google.ads.googleads.v18.enums.AudienceInsightsDimensionEnum.AudienceInsightsDimension dimension = 1;</code>
     * @return int
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * The type of the attributes in this section.
     *
     * Generated from protobuf field <code>.google.ads.googleads.v18.enums.AudienceInsightsDimensionEnum.AudienceInsightsDimension dimension = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setDimension($var)
    {
        GPBUtil::checkEnum($var, \Google\Ads\GoogleAds\V18\Enums\AudienceInsightsDimensionEnum\AudienceInsightsDimension::class);
        $this->dimension = $var;

        return $this;
    }

    /**
     * The most relevant segments for this audience.  If dimension is GENDER,
     * AGE_RANGE or PARENTAL_STATUS, then this list of attributes is exhaustive.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v18.services.AudienceCompositionAttribute top_attributes = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getTopAttributes()
    {
        return $this->top_attributes;
    }

    /**
     * The most relevant segments for this audience.  If dimension is GENDER,
     * AGE_RANGE or PARENTAL_STATUS, then this list of attributes is exhaustive.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v18.services.AudienceCompositionAttribute top_attributes = 3;</code>
     * @param array<\Google\Ads\GoogleAds\V18\Services\AudienceCompositionAttribute>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setTopAttributes($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Ads\GoogleAds\V18\Services\AudienceCompositionAttribute::class);
        $this->top_attributes = $arr;

        return $this;
    }

    /**
     * Additional attributes for this audience, grouped into clusters.  Only
     * populated if dimension is YOUTUBE_CHANNEL.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v18.services.AudienceCompositionAttributeCluster clustered_attributes = 4;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getClusteredAttributes()
    {
        return $this->clustered_attributes;
    }

    /**
     * Additional attributes for this audience, grouped into clusters.  Only
     * populated if dimension is YOUTUBE_CHANNEL.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v18.services.AudienceCompositionAttributeCluster clustered_attributes = 4;</code>
     * @param array<\Google\Ads\GoogleAds\V18\Services\AudienceCompositionAttributeCluster>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setClusteredAttributes($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Ads\GoogleAds\V18\Services\AudienceCompositionAttributeCluster::class);
        $this->clustered_attributes = $arr;

        return $this;
    }

}

