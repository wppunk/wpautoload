<?php
/**
 * WPPunk Autoload
 *
 * @package   wppunk/wpautoload
 * @author    WPPunk
 * @link      https://github.com/wppunk/wpautoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 */

use Composer\Factory;
use Composer\Json\JsonFile;
use WPPunk\Autoload\Autoload;

$file    = new JsonFile( Factory::getComposerFile() );
$content = $file->read();
if ( empty( $content['extra']['wp-autoload'] ) ) {
	return;
}

$dir = dirname( Factory::getComposerFile() ) . '/';

foreach ( $content['extra']['wp-autoload'] as $prefix => $folder ) {
	new Autoload( $prefix, $dir . $folder );
}
