<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Implementation for WordPress functions missing in older WordPress versions.
 *
 * @package WordPress
 * @subpackage Importer
 */

if ( ! function_exists( 'solace_extra_wp_slash_strings_only' ) ) {
	/**
	 * Adds slashes to only string values in an array of values.
	 *
	 * Compat for WordPress < 5.3.0.
	 *
	 * @since 0.7.0
	 *
	 * @param mixed $value Scalar or array of scalars.
	 * @return mixed Slashes $value
	 */
	function solace_extra_wp_slash_strings_only( $value ) {
		return map_deep( $value, 'solace_extra_addslashes_strings_only' );
	}
}

if ( ! function_exists( 'solace_extra_addslashes_strings_only' ) ) {
	/**
	 * Adds slashes only if the provided value is a string.
	 *
	 * Compat for WordPress < 5.3.0.
	 *
	 * @since 0.7.0
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	function solace_extra_addslashes_strings_only( $value ) {
		return is_string( $value ) ? addslashes( $value ) : $value;
	}
}
