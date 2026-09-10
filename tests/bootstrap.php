<?php
/**
 * PHPUnit bootstrap file for Extended CPTs Extras
 *
 * @package BuiltNorth\ExtendedCPTsExtras
 */

// Suppress PHP 8.4 deprecation warnings from WP_Mock
error_reporting(E_ALL & ~E_DEPRECATED);

// Require Composer autoloader, falling back to the monorepo root's autoloader
// when this package has no standalone vendor/ install (the normal dev setup —
// see "Autoloader Architecture" in the root CLAUDE.md).
$autoloader             = dirname( __DIR__ ) . '/vendor/autoload.php';
$using_root_autoloader = ! file_exists( $autoloader );
if ( $using_root_autoloader ) {
	$autoloader = dirname( __DIR__, 3 ) . '/vendor/autoload.php';
}
require_once $autoloader;

// The root autoloader only carries this package's own runtime `autoload` PSR-4
// mapping, never a dependency's `autoload-dev` — register the Tests namespace
// by hand when running under the root autoloader.
if ( $using_root_autoloader ) {
	spl_autoload_register(
		static function ( string $class ): void {
			$prefix = 'BuiltNorth\\ExtendedCPTsExtras\\Tests\\';
			if ( ! str_starts_with( $class, $prefix ) ) {
				return;
			}
			$relative = substr( $class, strlen( $prefix ) );
			$file     = __DIR__ . '/' . str_replace( '\\', '/', $relative ) . '.php';
			if ( file_exists( $file ) ) {
				require $file;
			}
		}
	);
}

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