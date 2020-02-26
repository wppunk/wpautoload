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
use PHPUnit\Framework\TestCase;
use WP_Autoload\Cache;

require_once __DIR__ . '/../../../classes/class-cache.php';

/**
 * Class Test_Cache
 */
class Test_Cache extends TestCase {

	/**
	 * Setup test
	 */
	public function setUp(): void {
		parent::setUp();
		WP_Mock::setUp();
	}

	/**
	 * End test
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
		Mockery::close();
		parent::tearDown();
	}

	/**
	 * Test __construct
	 */
	public function test___construct() {
		WP_Mock::userFunction( 'plugin_dir_path', [ 'times' => 1 ] );

		new Cache();

		$this->assertTrue( true );
	}

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
	 * Have invalid cache.
	 */
	public function test_update_invalid_cache() {
		$class = '\Prefix\Autoload_Success_1';
		$path  = __DIR__ . '/../classes/path-1/prefix/class-autoload-success-1111.php';

		$cache = new Cache();
		$cache->update( $class, $path );

		$this->assertSame( '', $cache->get( $class ) );
	}

	/**
	 * Save cache
	 */
	public function test_save() {
		Mockery::mock( 'WP_Filesystem_Base' );
		$wp_filesystem = Mockery::mock( 'overload:WP_Filesystem_Direct' );
		$wp_filesystem->shouldReceive( 'put_contents' )->once();
		WP_Mock::userFunction( 'WP_Filesystem', [ 'times' => 1 ] );

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

}
