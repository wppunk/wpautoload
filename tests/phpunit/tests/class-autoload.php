<?php

use PHPUnit\Framework\TestCase;
use WP_Autoload\Autoload;

class Test_Autoload extends TestCase {

	public function test_load() {
		new \Some_Class\Tests\Case;
	}

	protected function setUp(): void {
		require_once __DIR__ . '/../../../classes/class-exception.php';
		require_once __DIR__ . '/../../../classes/class-autoload.php';
		new Autoload();
		parent::setUp();
	}

}