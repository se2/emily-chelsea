<?php
/**
 * Class ProductContentsFilterAny
 *
 * @package WPDesk\FSPro\TableRate\Rule\Condition\Product
 */

namespace WPDesk\FSPro\TableRate\Rule\Condition\Product;

use WC_Product;
use WPDesk\FS\TableRate\Rule\ContentsFilter;

/**
 * Can filter shipping contents against product.
 */
class ProductContentsFilterAny extends AbstractProductContentsFilter {

	/**
	 * Returns filtered contents.
	 *
	 * @param array $contents .
	 *
	 * @return array
	 */
	public function get_filtered_contents( array $contents ) {
		foreach ( $contents as $key => $item ) {
			if ( ! $this->is_matched( $item['product_id'], $item['variation_id'] ) ) {
				unset( $contents[ $key ] );
			}
		}

		return $contents;
	}

}
