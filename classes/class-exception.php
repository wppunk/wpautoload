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
		$message = '<strong>Autoload ERROR</strong>:';

		$message .= $class . ' is not found in ' . implode( ', ', $folders ) . '.<br>';
		$message .= 'The file can be located in the following paths:';
		$message .= '<code>';
		foreach ( $folders as $folder ) {
			$message .= $folder . '<br>';
		}
		$message .= '</code>';
		parent::__construct( $message );
	}

}
