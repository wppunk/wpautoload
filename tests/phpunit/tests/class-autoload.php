<?php

use PHPUnit\Framework\TestCase;
use WP_Autoload\Autoload;

class Test_Autoload extends TestCase {

	public function test_success_load_by_first_path() {
		new \Prefix\Autoload_Success_1();
		$this->assertTrue( true );
	}

//	public function test_fail_load() {
//		WP_Mock::userFunction( 'wp_die' );
//		WP_Mock::userFunction( 'wp_kses_post' );
//
////		$this->expectException( PHPUnit\Framework\Error\Error::class );
////		new \Prefix\Autoload_Fail();
//		$this->assertTrue( true );
//	}

	public function test_success_load_by_second_path() {
		new \Prefix\Autoload_Success_2();
		$this->assertTrue( true );
	}

	protected function setUp(): void {
		require_once __DIR__ . '/../../../classes/class-exception.php';
		require_once __DIR__ . '/../../../classes/class-autoload.php';
		WP_Mock::userFunction(
			'plugin_dir_path',
			[
				'return' => __DIR__,
			]
		);
		new Autoload(
			'Prefix',
			[
				__DIR__ . '/../classes/path-1/',
				__DIR__ . '/../classes/path-2/',
			]
		);
		parent::setUp();
	}

}