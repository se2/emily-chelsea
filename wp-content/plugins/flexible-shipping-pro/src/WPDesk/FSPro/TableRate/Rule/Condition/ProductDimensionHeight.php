<?php

namespace WPDesk\FSPro\TableRate\Rule\Condition;

use WPDesk\FS\TableRate\Rule\ContentsFilter;
use WPDesk\FSPro\TableRate\Rule\Condition\ProductDimension\ProductHeightContentsFilter;

/**
 * Product height condition.
 */
class ProductDimensionHeight extends ProductDimension {

	/** @var string */
	const CONDITION_ID = 'product_height';

	/**
	 * MaxDimension constructor.
	 *
	 * @param int $priority .
	 */
	public function __construct( $priority = 10 ) {
		parent::__construct( $priority );
		$this->condition_id = self::CONDITION_ID;
		$this->name         = __( 'Height', 'flexible-shipping-pro' );
		$this->description  = __( 'Shipping cost based on the product\'s height', 'flexible-shipping-pro' );
		$this->group        = __( 'Product', 'flexible-shipping-pro' );
	}


	/**
	 * @inheritDoc
	 */
	protected function get_dimension( $product ): float {
		if ( $product->has_dimensions() ) {
			return (float) $product->get_height();
		}

		return 0.0;
	}

	/**
	 * @inheritDoc
	 */
	protected function get_dimension_content_filter( $min, $max ): ContentsFilter {
		return new ProductHeightContentsFilter( $min, $max );
	}
}
