<?php
/**
 * Test exception
 *
 * @package   WP-Autoload
 * @author    Maksym Denysenko
 * @link      https://github.com/mdenisenko/WP-Autoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../classes/class-exception.php';

/**
 * Class Test_Exception
 */
class Test_Exception extends TestCase {

	/**
	 * Setup test
	 */
	public function setUp() {
		parent::setUp();
		\WP_Mock::setUp();
	}

	/**
	 * End test
	 */
	public function tearDown() {
		\WP_Mock::tearDown();
		\Mockery::close();
		parent::tearDown();
	}

	/**
	 * Test message
	 */
	public function test_message() {
		$class     = 'My_Class\Some\Path';
		$folders   = [
			'/folder-1/',
			'/folder-2/',
		];
		$exception = new \WP_Autoload\Exception( $class, $folders );
		$message   = $exception->getMessage();
		$this->assertTrue( ! ! strpos( $message, $class ) );
		foreach ( $folders as $folder ) {
			$this->assertTrue( ! ! strpos( $message, $folder ) );
		}

	}

}
