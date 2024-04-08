<?php

namespace WPDesk\FSPro\TableRate\Rule\Condition;

use WPDesk\FS\TableRate\Rule\ContentsFilter;
use WPDesk\FSPro\TableRate\Rule\Condition\ProductDimension\ProductLengthContentsFilter;

/**
 * Product length condition.
 */
class ProductDimensionLength extends ProductDimension {

	/** @var string */
	const CONDITION_ID = 'product_length';

	/**
	 * MaxDimension constructor.
	 *
	 * @param int $priority .
	 */
	public function __construct( $priority = 10 ) {
		parent::__construct( $priority );
		$this->condition_id = self::CONDITION_ID;
		$this->name         = __( 'Length', 'flexible-shipping-pro' );
		$this->description  = __( 'Shipping cost based on the product\'s length', 'flexible-shipping-pro' );
		$this->group        = __( 'Product', 'flexible-shipping-pro' );
	}


	/**
	 * @inheritDoc
	 */
	protected function get_dimension( $product ): float {
		if ( $product->has_dimensions() ) {
			return (float) $product->get_length();
		}

		return 0.0;
	}

	/**
	 * @inheritDoc
	 */
	protected function get_dimension_content_filter( $min, $max ): ContentsFilter {
		return new ProductLengthContentsFilter( $min, $max );
	}
}
