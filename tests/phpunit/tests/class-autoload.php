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

use WPPunk\Autoload\Autoload;
use Prefix\Autoload_Success_2;
use Prefix\Autoload_Success_1;
use Prefix\Autoload_Success_3;
use PHPUnit\Framework\TestCase;

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
		$autoload = new Autoload( self::PREFIX, self::FOLDER1 );
		new Autoload_Success_1();

		spl_autoload_unregister( [ $autoload, 'autoload' ] );
	}

	/**
	 * Test success load class by path in second folder inside cache
	 */
	public function test_success_load_by_second_path() {
		$autoload = new Autoload( self::PREFIX, self::FOLDER2 );
		new Autoload_Success_2();

		spl_autoload_unregister( [ $autoload, 'autoload' ] );
	}

	/**
	 * Test loading interface
	 */
	public function test_success_load_interface() {
		$autoload = new Autoload( self::PREFIX, self::FOLDER1 );
		new Autoload_Success_3();

		spl_autoload_unregister( [ $autoload, 'autoload' ] );
	}

	/**
	 * Test invalid namespace.
	 */
	public function test_invalid_namespace() {
		$autoload = new Autoload( self::PREFIX, self::FOLDER1 );
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

}
