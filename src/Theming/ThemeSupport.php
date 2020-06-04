<?php
/**
 * ThemeSupport class file.
 *
 * @package WooCommerce\Theming
 */

namespace Automattic\WooCommerce\Theming;

use Automattic\WooCommerce\Utils\ArrayUtils;

/**
 * Provides methods for theme support.
 * Get an instance via WC()->theme_support().
 *
 * @package Automattic\WooCommerce\Theming
 */
class ThemeSupport {

	/**
	 * Holds the only instance of the class.
	 *
	 * @var ThemeSupport The only instance of the class.
	 */
	private static $instance;

	/**
	 * Returns the only existing instance of the class.
	 *
	 * @return ThemeSupport The only existing instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Adds theme support options for the current theme.
	 *
	 * @param array $options The options to be added.
	 */
	public function add_theme_support_options( $options ) {
		add_theme_support( 'woocommerce', $options );
	}

	/**
	 * Gets "theme support" options from the current theme, if set.
	 *
	 * @param string $option_name Option name, possibly nested (key::subkey), to get specific value. Blank to get all the existing options as an array.
	 * @param mixed  $default_value Value to return if the specified option doesn't exist.
	 * @return mixed The retrieved option or the default value.
	 */
	public function get_theme_support_option( $option_name = '', $default_value = null ) {
		$theme_support = get_theme_support( 'woocommerce' );
		$theme_support = is_array( $theme_support ) ? $theme_support[0] : false;

		if ( ! $theme_support ) {
			return $default_value;
		}

		if ( $option_name ) {
			return ArrayUtils::get_nested_value( $theme_support, $option_name, $default_value );
		}

		return $theme_support;
	}
}
