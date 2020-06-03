<?php
/**
 * ArrayUtils class file.
 *
 * @package WooCommerce\Utils
 */

namespace Automattic\WooCommerce\Utils;

use Automattic\WooCommerce\Utils\StringUtils;

defined( 'ABSPATH' ) || exit;

/**
 * Miscellaneous array related utility methods.
 *
 * @package WooCommerce\Utils
 */
class ArrayUtils {

	/**
	 * Get a value from an nested array by specifying the entire key hierarchy with '::' as separator.
	 *
	 * E.g. for [ 'foo' => [ 'bar' => [ 'fizz' => 'buzz' ] ] ] the value for key 'foo::bar::fizz' would be 'buzz'.
	 *
	 * @param array  $array The array to get the value from.
	 * @param string $key The complete key hierarchy, using '::' as separator.
	 * @param mixed  $default The value to return if the key doesn't exist in the array.
	 *
	 * @return mixed The retrieved value, or the supplied default value.
	 * @throws \Exception $array is not an array.
	 */
	public static function get_nested_value( $array, $key, $default = null ) {
		if ( ! is_array( $array ) ) {
			throw new \Exception( StringUtils::get_class_short_name( __CLASS__ ) . '::' . __METHOD__ . ': $array must be an array.' );
		}

		$key_stack = explode( '::', $key );
		$subkey    = array_shift( $key_stack );

		if ( isset( $array[ $subkey ] ) ) {
			$value = $array[ $subkey ];

			if ( count( $key_stack ) ) {
				foreach ( $key_stack as $subkey ) {
					if ( is_array( $value ) && isset( $value[ $subkey ] ) ) {
						$value = $value[ $subkey ];
					} else {
						$value = $default;
						break;
					}
				}
			}
		} else {
			$value = $default;
		}

		return $value;
	}
}
