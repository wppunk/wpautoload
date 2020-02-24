<?php
/**
 * Test WP_Autoload\Autoload class.
 *
 * @package   WP-Autoload
 * @author    Maksym Denysenko
 * @link      https://github.com/mdenisenko/WP-Autoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

use PHPUnit\Framework\TestCase;
use Prefix\Autoload_Fail;
use Prefix\Autoload_Success_1;
use Prefix\Autoload_Success_2;
use WP_Autoload\Autoload;

require_once __DIR__ . '/../../../classes/class-autoload.php';

/**
 * Class Test_Autoload
 */
class Test_Autoload extends TestCase {

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
	 * Prefix for your namespace
	 *
	 * @var string
	 */
	private $prefix = 'Prefix';
	/**
	 * List of folders for autoload
	 *
	 * @var array
	 */
	private $folders = [
		__DIR__ . '/../classes/path-1/',
		__DIR__ . '/../classes/path-2/',
	];

	/**
	 * Test __construct
	 */
	public function test___construct() {
		$cache          = Mockery::mock( 'WP_Autoload\Cache' );
		$autoload_count = count( spl_autoload_functions() );

		$autoload = new Autoload(
			$this->prefix,
			$this->folders,
			$cache
		);

		$this->assertSame( ++ $autoload_count, count( spl_autoload_functions() ) );
		spl_autoload_unregister( [ $autoload, 'autoload' ] );
	}

	/**
	 * Test success load class by path in first folder inside cache.
	 */
	public function test_success_load_by_first_path() {
		$cache = Mockery::mock( 'WP_Autoload\Cache' );
		$cache->shouldReceive( 'get' )->andReturn( '' );
		$cache->shouldReceive( 'update' )->once();

		$autoload = new Autoload(
			$this->prefix,
			$this->folders,
			$cache
		);
		new Autoload_Success_1();

		spl_autoload_unregister( [ $autoload, 'autoload' ] );
	}

	/**
	 * Test success load class by path in second folder inside cache
	 */
	public function test_success_load_by_second_path() {
		$cache = Mockery::mock( 'WP_Autoload\Cache' );
		$cache->shouldReceive( 'get' )->andReturn( '' );
		$cache->shouldReceive( 'update' )->once();

		$autoload = new Autoload(
			$this->prefix,
			$this->folders,
			$cache
		);
		new Autoload_Success_2();

		spl_autoload_unregister( [ $autoload, 'autoload' ] );
	}

	/**
	 * Test success load class from cache.
	 */
	public function test_success_load_from_cache() {
		$cache = Mockery::mock( 'WP_Autoload\Cache' );
		$cache->shouldReceive( 'get' )->andReturn( __DIR__ . '/../classes/path-1/prefix/class-autoload-success-1.php' );

		$autoload = new Autoload(
			$this->prefix,
			$this->folders,
			$cache
		);
		new Autoload_Success_1();

		spl_autoload_unregister( [ $autoload, 'autoload' ] );
	}

	/**
	 * Test invalid class path.
	 *
	 * @noinspection PhpUndefinedClassInspection
	 */
	public function test_fail_load() {
		WP_Mock::userFunction( 'wp_kses_post', [ 'times' => 1 ] );
		WP_Mock::userFunction( 'wp_die', [ 'times' => 1 ] );
		$cache = Mockery::mock( 'WP_Autoload\Cache' );
		$cache->shouldReceive( 'get' )->andReturn( '' );

		new Autoload(
			$this->prefix,
			$this->folders,
			$cache
		);

		$this->expectException( Error::class );

		new Autoload_Fail();
	}

}
