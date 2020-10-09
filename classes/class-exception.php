<?php
/**
 * Exception for autoload.
 *
 * @package   WPPunk\Autoload
 * @author    WPPunk
 * @link      https://github.com/mdenisenko/WPPunk\Autoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace WPPunk\Autoload;

/**
 * Class Exception
 *
 * @package Autoload
 */
class Exception extends \Exception {

	/**
	 * Exception constructor.
	 *
	 * @param string $class Class name.
	 * @param string $path  Correct path.
	 */
	public function __construct( $class, $path ) {
		$message = '<strong>Autoload ERROR</strong>: ';

		$message .= '<em>' . $class . '</em> is not found in ' . $path . '.';

		parent::__construct( $message );
	}

}
