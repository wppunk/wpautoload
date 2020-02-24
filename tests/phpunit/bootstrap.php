<?php
/**
 * Bootstrap file for tests
 *
 * @package   WP-Autoload
 * @author    Maksym Denysenko
 * @link      https://github.com/mdenisenko/WP-Autoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace WP_Autoload;

require_once __DIR__ . '/../../vendor/autoload.php';

\WP_Mock::bootstrap();
