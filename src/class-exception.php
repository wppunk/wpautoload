<?php
/**
 * Exception for autoload.
 *
 * @package   wppunk/wpautoload
 * @author    WPPunk
 * @link      https://github.com/wppunk/wpautoload/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 */

namespace WPPunk\Autoload;

/**
 * @codeCoverageIgnore
 */
if ( class_exists( '\WPPunk\Autoload\Exception' ) ) {
	return;
}
/**
 * @codingStandardsIgnoreEnd
 */

/**
 * Class Exception
 *
 * @package wppunk/wpautoload
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
