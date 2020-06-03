<?php
/**
 * StringUtils class file.
 *
 * @package WooCommerce\Utils
 */

namespace Automattic\WooCommerce\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Miscellaneous array related utility methods.
 *
 * @package WooCommerce\Utils
 */
class StringUtils {

	/**
	 * Get the short name (without namespace) of a class name.
	 *
	 * @param object|string $object_or_class The object whose class short name we want, or a string representing a class name.
	 *
	 * @return string The class short name.
	 */
	public static function get_class_short_name( $object_or_class ) {
		$class_name = is_object( $object_or_class ) ? get_class( $object_or_class ) : $object_or_class;

		$pos = strrpos( $class_name, '\\' );
		return false === $pos ? $class_name : substr( $class_name, $pos + 1 );
	}
}
