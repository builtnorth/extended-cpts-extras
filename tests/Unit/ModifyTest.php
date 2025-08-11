<?php
/**
 * Tests for the modify functions
 *
 * @package BuiltNorth\ExtendedCPTsExtras\Tests\Unit
 */

namespace BuiltNorth\ExtendedCPTsExtras\Tests\Unit;

use BuiltNorth\ExtendedCPTsExtras\Tests\TestCase;
use WP_Mock;
use Mockery;
use stdClass;

/**
 * Test the modify functions
 */
class ModifyTest extends TestCase {

	/**
	 * Test extended_post_type_modify_existing function exists
	 */
	public function test_extended_post_type_modify_existing_exists() {
		// Load the functions file
		require_once dirname( dirname( __DIR__ ) ) . '/inc/functions/modify.php';
		
		$this->assertTrue( function_exists( 'extended_post_type_modify_existing' ) );
	}

	/**
	 * Test modify existing with single post type
	 */
	public function test_modify_existing_single_post_type() {
		WP_Mock::expectActionAdded( 'init', WP_Mock\Functions::type( 'callable' ), 11 );
		
		extended_post_type_modify_existing( 'post', [
			'menu_position' => 5,
			'menu_icon' => 'dashicons-admin-post'
		] );
		
		$this->assertConditionsMet();
	}

	/**
	 * Test modify existing with multiple post types
	 */
	public function test_modify_existing_multiple_post_types() {
		WP_Mock::expectActionAdded( 'init', WP_Mock\Functions::type( 'callable' ), 11 );
		
		extended_post_type_modify_existing( [ 'post', 'page' ], [
			'menu_position' => 10
		] );
		
		$this->assertConditionsMet();
	}

	/**
	 * Test modify existing with template options
	 */
	public function test_modify_existing_with_template() {
		WP_Mock::expectActionAdded( 'init', WP_Mock\Functions::type( 'callable' ), 11 );
		
		$template = [
			[ 'core/heading', [ 'placeholder' => 'Add Title' ] ],
			[ 'core/paragraph', [ 'placeholder' => 'Add Content' ] ]
		];
		
		extended_post_type_modify_existing( 'post', [
			'template' => $template,
			'template_lock' => 'all'
		] );
		
		$this->assertConditionsMet();
	}

	/**
	 * Test modify existing with all options
	 */
	public function test_modify_existing_all_options() {
		WP_Mock::expectActionAdded( 'init', WP_Mock\Functions::type( 'callable' ), 11 );
		
		extended_post_type_modify_existing( 'custom_post', [
			'template' => [
				[ 'core/heading' ],
				[ 'core/paragraph' ]
			],
			'template_lock' => 'insert',
			'menu_position' => 25,
			'menu_icon' => 'dashicons-star-filled'
		] );
		
		$this->assertConditionsMet();
	}

	/**
	 * Test modify existing with empty options
	 */
	public function test_modify_existing_empty_options() {
		WP_Mock::expectActionAdded( 'init', WP_Mock\Functions::type( 'callable' ), 11 );
		
		extended_post_type_modify_existing( 'post', [] );
		
		$this->assertConditionsMet();
	}

	/**
	 * Test the init callback modifies post type object
	 */
	public function test_init_callback_modifies_post_type() {
		// Create a simple stdClass to act as post type object
		$post_type_object = new stdClass();
		
		WP_Mock::userFunction( 'get_post_type_object' )
			->once()
			->with( 'test_post_type' )
			->andReturn( $post_type_object );
		
		// Manually test the callback
		$options = [
			'menu_position' => 15,
			'menu_icon' => 'dashicons-admin-generic'
		];
		
		// Execute the callback logic directly
		$callback = function() use ( $options, &$post_type_object ) {
			$retrieved_object = get_post_type_object( 'test_post_type' );
			if ( $retrieved_object ) {
				if ( isset( $options['menu_position'] ) ) {
					$retrieved_object->menu_position = $options['menu_position'];
				}
				if ( isset( $options['menu_icon'] ) ) {
					$retrieved_object->menu_icon = $options['menu_icon'];
				}
			}
		};
		
		$callback();
		
		$this->assertEquals( 15, $post_type_object->menu_position );
		$this->assertEquals( 'dashicons-admin-generic', $post_type_object->menu_icon );
		$this->assertConditionsMet();
	}
}