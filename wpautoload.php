<?php
/**
 * WPPunk Autoload
 *
 * @package   WPPunk\Autoload
 * @author    WPPunk
 * @link      https://github.com/mdenisenko/WP-Autoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

use WPPunk\Autoload\Cache;
use Composer\Json\JsonFile;
use WPPunk\Autoload\Autoload;

$dir     = __DIR__ . '/../../../';
$cache   = new Cache();
$file    = new JsonFile( $dir . 'composer.json' );
$content = $file->read();
if ( ! empty( $content['extra']['wp-autoload'] ) ) {
	foreach ( $content['extra']['wp-autoload'] as $prefix => $folder ) {
		new Autoload( $prefix, $dir . $folder, $cache );
	}
}
