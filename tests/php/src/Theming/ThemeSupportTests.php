<?php
/**
 * Tests for ThemeSupport
 *
 * @package WooCommerce\Tests\ThemeSupport
 */

use Automattic\WooCommerce\Theming\ThemeSupport;

/**
 * Tests for ThemeSupport
 */
class ThemeSupportTests extends WC_Unit_Test_Case {

	/**
	 * Runs before each test.
	 */
	public function setUp() {
		remove_theme_support( 'woocommerce' );
	}

	/**
	 * @testdox add_theme_support should add the supplied options under the 'woocommerce' feature.
	 */
	public function test_add_theme_support_options() {
		$sut = new ThemeSupport();

		$options = array( 'foo' => 'bar' );
		$sut->add_theme_support_options( $options );

		$actual = get_theme_support( 'woocommerce' )[0];
		$this->assertEquals( $options, $actual );
	}

	/**
	 * @testdox add_default_theme_support should add the supplied options under the 'woocommerce' feature on a '_defaults' key.
	 */
	public function test_add_default_theme_support_options() {
		$sut = new ThemeSupport();

		$sut->add_default_theme_support_options( array( 'foo' => 'bar' ) );
		$sut->add_default_theme_support_options( array( 'fizz' => 'buzz' ) );

		$actual   = get_theme_support( 'woocommerce' )[0];
		$expected = array(
			ThemeSupport::DEFAULTS_KEY => array(
				'foo'  => 'bar',
				'fizz' => 'buzz',
			),
		);
		$this->assertEquals( $expected, $actual );
	}

	/**
	 * @testdox get_theme_support whould return all the options under the 'woocommerce' feature when invoked with blank option name.
	 */
	public function test_get_theme_support_with_no_option_name() {
		$sut = new ThemeSupport();

		$options = array( 'foo' => 'bar' );
		$sut->add_theme_support_options( $options );

		$actual = $sut->get_theme_support_option();
		$this->assertEquals( $options, $actual );
	}

	/**
	 * @testdox get_theme_support should return null if no 'woocommerce' feature exists and no default value is supplied.
	 */
	public function test_get_theme_support_with_no_option_name_when_no_options_exist_and_no_default_value_supplied() {
		$sut = new ThemeSupport();

		$actual = $sut->get_theme_support_option();
		$this->assertNull( $actual );
	}

	/**
	 * @testdox get_theme_support should return the supplied default value if no 'woocommerce' feature exists.
	 */
	public function test_get_theme_support_with_no_option_name_when_no_options_exist_and_default_value_supplied() {
		$sut = new ThemeSupport();

		$actual = $sut->get_theme_support_option( '', 'DEFAULT' );
		$this->assertEquals( 'DEFAULT', $actual );
	}

	/**
	 * @testdox get_theme_support should return the value of the requested option if it exists.
	 */
	public function test_get_theme_support_with_option_name() {
		$sut = new ThemeSupport();

		$options = array( 'foo' => array( 'bar' => 'fizz' ) );
		$sut->add_theme_support_options( $options );

		$actual = $sut->get_theme_support_option( 'foo::bar' );
		$this->assertEquals( 'fizz', $actual );
	}

	/**
	 * @testdox get_theme_support should return null if the requested option doesn't exist and no default value is supplied.
	 */
	public function test_get_theme_support_with_option_name_when_option_does_not_exist_and_no_default_value_supplied() {
		$sut = new ThemeSupport();

		$options = array( 'foo' => array( 'bar' => 'fizz' ) );
		$sut->add_theme_support_options( $options );

		$actual = $sut->get_theme_support_option( 'buzz' );
		$this->assertNull( $actual );
	}

	/**
	 * @testdox get_theme_support should return the supplied default value if the requested option doesn't exist.
	 */
	public function test_get_theme_support_with_option_name_when_option_does_not_exist_and_default_value_supplied() {
		$sut = new ThemeSupport();

		$options = array( 'foo' => array( 'bar' => 'fizz' ) );
		$sut->add_theme_support_options( $options );

		$actual = $sut->get_theme_support_option( 'buzz', 'DEFAULT' );
		$this->assertEquals( 'DEFAULT', $actual );
	}

	/**
	 * @testdox get_theme_support should return the value of the requested option if it has been defined as a default.
	 */
	public function test_get_theme_support_with_option_name_and_option_defined_as_default() {
		$sut = new ThemeSupport();

		$options = array( 'foo' => array( 'bar' => 'fizz' ) );
		$sut->add_default_theme_support_options( $options );

		$actual = $sut->get_theme_support_option( 'foo::bar' );
		$this->assertEquals( 'fizz', $actual );
	}

	/**
	 * @testdox has_theme_support should return false if no 'woocommerce' feature exists.
	 *
	 * @testWith [true]
	 *           [false]
	 *
	 * @param bool $include_defaults Whether to include defaults in the search or not.
	 */
	public function test_has_theme_support_when_no_woocommerce_feature_is_defined( $include_defaults ) {
		$sut = new ThemeSupport();

		$this->assertFalse( $sut->has_theme_support_option( 'foo::bar', $include_defaults ) );
	}

	/**
	 * @testdox has_theme_support should return false if the specified option has not been defined.
	 *
	 * @testWith [true]
	 *           [false]
	 *
	 * @param bool $include_defaults Whether to include defaults in the search or not.
	 */
	public function test_has_theme_support_when_option_is_not_defined( $include_defaults ) {
		$sut = new ThemeSupport();

		$sut->add_theme_support_options( array( 'foo' => 'bar' ) );
		$this->assertFalse( $sut->has_theme_support_option( 'fizz::buzz', $include_defaults ) );
	}

	/**
	 * @testdox has_theme_support should return true if the specified option has been defined.
	 *
	 * @testWith [true]
	 *           [false]
	 *
	 * @param bool $include_defaults Whether to include defaults in the search or not.
	 */
	public function test_has_theme_support_when_option_is_defined( $include_defaults ) {
		$sut = new ThemeSupport();

		$sut->add_theme_support_options( array( 'foo' => 'bar' ) );
		$this->assertTrue( $sut->has_theme_support_option( 'foo', $include_defaults ) );
	}

	/**
	 * @testdox If an option has been defined as a default, has_theme_support should return true if $include_defaults is passed as true, should return false otherwise.
	 *
	 * @testWith [true, true]
	 *           [false, false]
	 *
	 * @param bool $include_defaults Whether to include defaults in the search or not.
	 * @param bool $expected_result The expected return value from the tested method.
	 */
	public function test_has_theme_support_when_option_is_defined_as_default( $include_defaults, $expected_result ) {
		$sut = new ThemeSupport();

		$sut->add_default_theme_support_options( array( 'foo' => 'bar' ) );
		$this->assertEquals( $expected_result, $sut->has_theme_support_option( 'foo', $include_defaults ) );
	}
}

