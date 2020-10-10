<?php
/**
 * Test Autoload class.
 *
 * @package   wppunk/wpautoload
 * @author    WPPunk
 * @link      https://github.com/wppunk/wpautoload/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 */

use Prefix\Autoload_Fail;
use WPPunk\Autoload\Autoload;
use Prefix\Autoload_Success_2;
use Prefix\Autoload_Success_3;
use Prefix\Autoload_Success_4;
use Prefix\Autoload_Success_1;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../src/class-autoload.php';

/**
 * Class Test_Autoload
 */
class Test_Autoload extends TestCase {

	/**
	 * End test
	 */
	public function tearDown(): void { // phpcs:ignore PHPCompatibility.FunctionDeclarations.NewReturnTypeDeclarations.voidFound
		Mockery::close();
		parent::tearDown();
	}

	/**
	 * Prefix for your namespace
	 *
	 * @var string
	 */
	const PREFIX = 'Prefix';

	/**
	 * Path to first folder
	 *
	 * @var array
	 */
	const FOLDER1 = __DIR__ . '/../classes/path-1/prefix';

	/**
	 * Path to second folder
	 *
	 * @var array
	 */
	const FOLDER2 = __DIR__ . '/../classes/path-2/prefix';

	/**
	 * Test success load class by path in first folder inside cache.
	 */
	public function test_success_load_by_first_path() {
		$cache = Mockery::mock( 'WPPunk\Autoload\Cache' );
		$cache->shouldReceive( 'get' )->andReturn( '' );
		$cache->shouldReceive( 'update' )->once();

		$autoload = new Autoload(
			self::PREFIX,
			self::FOLDER1,
			$cache
		);
		new Autoload_Success_1();

		spl_autoload_unregister( [ $autoload, 'autoload' ] );
	}

	/**
	 * Test success load class by path in second folder inside cache
	 */
	public function test_success_load_by_second_path() {
		$cache = Mockery::mock( 'WPPunk\Autoload\Cache' );
		$cache->shouldReceive( 'get' )->andReturn( '' );
		$cache->shouldReceive( 'update' )->once();

		$autoload = new Autoload(
			self::PREFIX,
			self::FOLDER2,
			$cache
		);
		new Autoload_Success_2();

		spl_autoload_unregister( [ $autoload, 'autoload' ] );
	}

	/**
	 * Test success load class from cache.
	 */
	public function test_success_load_from_cache() {
		$cache = Mockery::mock( 'WPPunk\Autoload\Cache' );
		$cache->shouldReceive( 'get' )->andReturn( __DIR__ . '/../classes/path-1/prefix/class-autoload-success-4.php' );

		$autoload = new Autoload(
			self::PREFIX,
			self::FOLDER1,
			$cache
		);
		new Autoload_Success_4();

		spl_autoload_unregister( [ $autoload, 'autoload' ] );
	}

	/**
	 * Test invalid namespace.
	 */
	public function test_invalid_namespace() {
		$autoload = new Autoload(
			self::PREFIX,
			self::FOLDER1,
			Mockery::mock( 'WPPunk\Autoload\Cache' )
		);
		function test_autoload( $class ) {
			if ( 'Invalid_Name_Space' !== $class ) {
				return;
			}
			require_once __DIR__ . '/../classes/path-1/class-invalid-name-space.php';
		}

		spl_autoload_register( 'test_autoload' );
		new Invalid_Name_Space();

		spl_autoload_unregister( [ $autoload, 'autoload' ] );
		spl_autoload_unregister( 'test_autoload' );
	}

	/**
	 * Test loading interface
	 */
	public function test_success_load_interface() {
		$cache = Mockery::mock( 'WPPunk\Autoload\Cache' );
		$cache->shouldReceive( 'get' )->andReturn( '' );
		$cache->shouldReceive( 'update' );

		$autoload = new Autoload(
			self::PREFIX,
			self::FOLDER1,
			$cache
		);
		new Autoload_Success_3();

		spl_autoload_unregister( [ $autoload, 'autoload' ] );
	}

	/**
	 * Test invalid class path.
	 *
	 * @noinspection PhpUndefinedClassInspection
	 */
	public function test_fail_load() {
		$cache = Mockery::mock( 'WPPunk\Autoload\Cache' );
		$cache->shouldReceive( 'get' )->andReturn( '' );

		new Autoload(
			self::PREFIX,
			self::FOLDER1,
			$cache
		);
		$this->expectException( WPPunk\Autoload\Exception::class );
		new Autoload_Fail();
	}

}
