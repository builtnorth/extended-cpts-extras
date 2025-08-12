<?php
/**
 * PHPUnit bootstrap file for Extended CPTs Extras
 *
 * @package BuiltNorth\ExtendedCPTsExtras
 */

// Suppress PHP 8.4 deprecation warnings from WP_Mock
error_reporting(E_ALL & ~E_DEPRECATED);

// Require the Composer autoloader.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Bootstrap WP_Mock.
WP_Mock::bootstrap();

// Define WordPress constants that may be used in the code.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', '/tmp/wordpress/' );
}

if ( ! defined( 'WP_CONTENT_DIR' ) ) {
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
}

if ( ! defined( 'WP_CONTENT_URL' ) ) {
	define( 'WP_CONTENT_URL', 'http://example.com/wp-content' );
}