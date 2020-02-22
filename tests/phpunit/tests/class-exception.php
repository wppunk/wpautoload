<?php

use PHPUnit\Framework\TestCase;

class Test_Exception extends TestCase {

	public function test___constructor() {
		$class = 'My_Class\Some\Path';
		$folders = [
			'/folder-1/',
			'/folder-2/',
		];
		$exception =new \WP_Autoload\Exception( $class, $folders );
		echo $exception->getMessage();
		$this->assertTrue( true );
	}

}