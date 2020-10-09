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

require_once __DIR__ . '/../../../src/class-exception.php';

/**
 * Class Test_Exception
 */
class Test_Exception extends TestCase {

	/**
	 * Test message
	 */
	public function test_message() {
		$class     = 'My_Class\Some\Path';
		$folder    = '/folder-1/';
		$exception = new \WPPunk\Autoload\Exception( $class, $folder );
		$message   = $exception->getMessage();
		$this->assertTrue( ! ! strpos( $message, $class ) );
		$this->assertTrue( ! ! strpos( $message, $folder ) );
	}

}
