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
 * Get an instance via WooCommerce::theme_support().
 *
 * @package Automattic\WooCommerce\Theming
 */
class ThemeSupport {

	const DEFAULTS_KEY = '_defaults';

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
	 * Adds default theme support options for the current theme.
	 *
	 * @param array $options The options to be added.
	 */
	public function add_default_theme_support_options( $options ) {
		$default_options = $this->get_theme_support_option( self::DEFAULTS_KEY, array() );
		$default_options = array_merge( $default_options, $options );
		$this->add_theme_support_options( array( self::DEFAULTS_KEY => $default_options ) );
	}

	/**
	 * Gets "theme support" options from the current theme, if set.
	 *
	 * @param string $option_name Option name, possibly nested (key::subkey), to get specific value. Blank to get all the existing options as an array.
	 * @param mixed  $default_value Value to return if the specified option doesn't exist.
	 * @return mixed The retrieved option or the default value.
	 */
	public function get_theme_support_option( $option_name = '', $default_value = null ) {
		$theme_support_options = $this->get_all_theme_support_options();

		if ( ! $theme_support_options ) {
			return $default_value;
		}

		if ( $option_name ) {
			$value = ArrayUtils::get_nested_value( $theme_support_options, $option_name );
			if ( is_null( $value ) ) {
				$value = ArrayUtils::get_nested_value( $theme_support_options, self::DEFAULTS_KEY . '::' . $option_name, $default_value );
			}
			return $value;
		}

		return $theme_support_options;
	}

	/**
	 * Checks whether a given theme support option has been defined.
	 *
	 * @param string $option_name The (possibly nested) name of the option to check.
	 * @param bool   $include_defaults True to include the default values in the check, false otherwise.
	 *
	 * @return bool True if the specified theme support option has been defined, false otherwise.
	 */
	public function has_theme_support_option( $option_name, $include_defaults = true ) {
		$theme_support_options = $this->get_all_theme_support_options();

		if ( ! $theme_support_options ) {
			return false;
		}

		$value = ArrayUtils::get_nested_value( $theme_support_options, $option_name );
		if ( ! is_null( $value ) ) {
			return true;
		}

		if ( ! $include_defaults ) {
			return false;
		}
		$value = ArrayUtils::get_nested_value( $theme_support_options, self::DEFAULTS_KEY . '::' . $option_name );
		return ! is_null( $value );
	}

	/**
	 * Get all the defined theme support options for the 'woocommerce' feature.
	 *
	 * @return array An array with all the theme support options defined for the 'woocommerce' feature, or false if nothing has been defined for that feature.
	 */
	private function get_all_theme_support_options() {
		$theme_support = get_theme_support( 'woocommerce' );
		return is_array( $theme_support ) ? $theme_support[0] : false;
	}
}
