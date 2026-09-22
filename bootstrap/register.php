<?php
/**
 * Registers this copy of the package with the shared version coordinator.
 *
 * Runs eagerly via composer's `files` autoload, so every plugin that vendors
 * this package registers its own copy with no action on the plugin author's
 * part.
 *
 * This package ships no classes — its whole surface is the global functions in
 * inc/functions/, which composer loads eagerly and which are first-wins via
 * their own function_exists guards. So registering here buys no class
 * arbitration; it exists so the version is reportable through the coordinator
 * and a site running mismatched copies is diagnosable rather than silent.
 *
 * Lives outside any psr-4 root on purpose: a registration file reachable
 * through its own package's namespace prefix would make the coordinator
 * resolve a class name back onto this file.
 *
 * @package ExtendedCPTsExtras
 */

declare(strict_types=1);

use Novalis\PackageLoader\Registry;

// A consumer could vendor this package without the coordinator (a stale
// composer.lock, a hand-assembled vendor tree). Falling back to plain Composer
// resolution is correct there — worse version selection, never a fatal.
// Autoloading stays off: the coordinator declares Registry inline from its own
// files entry, so if it is not already declared no autoloader can produce it.
if (class_exists(Registry::class, false)) {
	Registry::instance()->register([
		'package' => 'builtnorth/extended-cpts-extras',
		'version' => (string) require dirname(__DIR__) . '/version.php',
		'root'    => dirname(__DIR__),
		// No psr-4 prefix: the package declares no classes.
		'psr4'    => [],
		// Function-only package — nothing to start.
		'boot'    => null,
	]);
}
