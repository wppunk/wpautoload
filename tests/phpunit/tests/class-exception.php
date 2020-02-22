<?php

use PHPUnit\Framework\TestCase;

class Test_Exception extends TestCase {

	public function test_message() {
		$class     = 'My_Class\Some\Path';
		$folders   = [
			'/folder-1/',
			'/folder-2/',
		];
		$exception = new \WP_Autoload\Exception( $class, $folders );
		$message   = $exception->getMessage();
		$this->assertTrue( !! strpos( $message, $class ) );
		foreach ( $folders as $folder ) {
			$this->assertTrue( !! strpos( $message, $folder ) );
		}

	}

	protected function setUp(): void {
		require_once __DIR__ . '/../../../classes/class-exception.php';
		parent::setUp();
	}

}