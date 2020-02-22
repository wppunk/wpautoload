<?php
/**
 * WP-Autoload
 *
 * @package   WP-Autoload
 * @author    Maksym Denysenko
 * @link      https://github.com/mdenisenko/WP-Autoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

use WP_Autoload\Autoload;

/**
 * Don't use composer on production.
 */
require_once plugin_dir_path( __FILE__ ) . 'classes/class-autoload.php';
require_once plugin_dir_path( __FILE__ ) . 'classes/class-exception.php';

new Autoload();
