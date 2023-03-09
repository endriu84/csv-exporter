<?php

namespace unit;

use Mockery;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class ExampleTest extends TestCase {
	// private $test_class_under_tests;

	public function setUp() {
		WP_Mock::setUp();

//		$example = Mockery::mock( class );
//		$this->test_class_under_tests =
	}

	public function tearDown() {
		WP_Mock::tearDown();
	}
//
//	public function testShouldImplementsHookable() {
//		// Then
//		$this->assertInstanceOf( Hookable::class, $this->test_class_under_tests );
//	}
//
//	public function testShouldAddHooks() {
//		// Expects
//		WP_Mock::expectFilterAdded( 'woocommerce_get_sections_shipping', [ $this->test_class_under_tests, 'add_section_to_array' ] );
//		WP_Mock::expectFilterAdded( 'woocommerce_get_settings_shipping', [ $this->test_class_under_tests, 'get_section_settings_fields' ], 10, 2 );
//
//		// When
//		$this->test_class_under_tests->hooks();
//
//		// Then
//		$this->assertTrue( true );
//	}

	public function testShouldReturnTrueAlways() {
		// Expects.

		// Given.

		// When.

		// Then.
		$this->assertTrue( true );
	}
}
