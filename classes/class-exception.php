<?php
/**
 * Exception for autoload.
 *
 * @package   WP-Autoload
 * @author    Maksym Denysenko
 * @link      https://github.com/mdenisenko/WP-Autoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace WP_Autoload;

/**
 * Class Exception
 *
 * @package Autoload
 */
class Exception extends \Exception {

	/**
	 * Exception constructor.
	 *
	 * @param string $class   Class name.
	 * @param array  $folders List of folders for autoload.
	 */
	public function __construct( string $class, array $folders ) {
		$message = '<strong>Autoload ERROR</strong>: ';

		$message .= '<em>' . $class . '</em> is not found in ' . implode( ', ', $folders ) . '.';

		parent::__construct( $message );
	}

}
