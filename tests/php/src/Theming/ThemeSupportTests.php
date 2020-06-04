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
}

