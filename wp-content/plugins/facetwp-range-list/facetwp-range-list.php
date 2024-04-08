<?php
/*
Plugin Name: FacetWP - Range List
Description: Range list facet type
Version: 0.7.2
Author: FacetWP, LLC
Author URI: https://facetwp.com/
GitHub URI: facetwp/facetwp-range-list
*/

defined( 'ABSPATH' ) or exit;

define( 'FACETWP_RANGE_LIST_VERSION', '0.7.2' );


/**
 * FacetWP registration hook
 */
add_filter( 'facetwp_facet_types', function( $types ) {
    include( dirname( __FILE__ ) . '/class-range-list.php' );
    $types['range_list'] = new FacetWP_Facet_Range_List_Addon();
    return $types;
} );
