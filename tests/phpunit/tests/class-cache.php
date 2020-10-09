<?php
/**
 * Test cache
 *
 * @package   WP-Autoload
 * @author    Maksym Denysenko
 * @link      https://github.com/mdenisenko/WP-Autoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

use bovigo\vfs\vfsStream;
use WPPunk\Autoload\Cache;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../src/class-cache.php';

/**
 * Class Test_Cache
 */
class Test_Cache extends TestCase {

	/**
	 * Cache not found.
	 */
	public function test_not_exists_cache() {
		$cache = new Cache();

		$this->assertSame( '', $cache->get( '\Prefix\Autoload_Success_1' ) );
	}

	/**
	 * Get valid cache.
	 */
	public function test_update_valid_cache() {
		$class = '\Prefix\Autoload_Success_1';
		$path  = __DIR__ . '/../classes/path-1/prefix/class-autoload-success-1.php';

		$cache = new Cache();
		$cache->update( $class, $path );

		$this->assertSame( $path, $cache->get( $class ) );
	}

	/**
	 * Dont save cache
	 */
	public function test_create_dir() {
		if ( file_exists( ROOT_DIR . 'cache/classmap.php' ) ) {
			unlink( ROOT_DIR . 'cache/classmap.php' );
			rmdir( ROOT_DIR . 'cache/' );
		}
		$cache = new Cache();
		$cache->update( '\Prefix\Autoload_Success_1', __DIR__ . '/../classes/path-1/prefix/class-autoload-success-1.php' );
		$cache->save();

		$this->assertTrue( true );
	}

	/**
	 * Dont save cache
	 */
	public function test_dont_save() {
		$cache = new Cache();
		$cache->save();

		$this->assertTrue( true );
	}

	/**
	 * Dont save cache
	 */
	public function test_only_garbage() {
		$cache = new Cache();
		$cache->update( '\Prefix\Autoload_Success_1', __DIR__ . '/../classes/path-1111/prefix/class-autoload-success-1.php' );
		$cache->save();

		$this->assertTrue( true );
	}

	/**
	 * Save cache
	 */
	public function test_save() {
		$cache = new Cache();
		$cache->update( '\Prefix\Autoload_Success_1', __DIR__ . '/../classes/path-1/prefix/class-autoload-success-1.php' );
		$cache->update( '\Prefix\Autoload_Success_3', __DIR__ . '/../classes/path-1/prefix/class-autoload-success-3.php' );
		$cache->save();

		$this->assertTrue( true );
	}

}
