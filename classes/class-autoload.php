<?php
/**
 * Autoload classes, interfaces and traits for your namespaces.
 *
 * @package   WP-Autoload
 * @author    Maksym Denysenko
 * @link      https://github.com/mdenisenko/WP-Autoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace WP_Autoload;

use function wp_die;

/**
 * Class Autoload
 */
class Autoload {

	/**
	 * Prefix for your namespace
	 *
	 * @var string
	 */
	private $prefix;
	/**
	 * List of folders for autoload
	 *
	 * @var array
	 */
	private $folders;
	/**
	 * Cache
	 *
	 * @var Cache
	 */
	private $cache;

	/**
	 * Autoload constructor.
	 *
	 * @param string $prefix  Prefix for your namespace.
	 * @param array  $folders List of folders for autoload.
	 * @param Cache  $cache   Cache.
	 */
	public function __construct( string $prefix, array $folders, Cache $cache ) {
		$this->prefix  = $prefix;
		$this->folders = $folders;
		$this->cache   = $cache;
		spl_autoload_register( [ $this, 'autoload' ] );
	}

	/**
	 * Autoload files for custom plugins
	 *
	 * @param string $class Full class name.
	 */
	private function autoload( string $class ): void {
		if ( 0 === strpos( $class, $this->prefix ) ) {
			$path = $this->cache->get( $class );
			if ( $path ) {
				require_once $path;
			} else {
				try {
					$path = $this->file_path( $class );
					$this->cache->update( $class, $path );
					require_once $path;
				} catch ( Exception $e ) {
					wp_die( wp_kses_post( $e->getMessage() ) );
				}
			}
		}

	}

	/**
	 * Find file path by namespace
	 *
	 * @param string $class Full class name.
	 *
	 * @return string
	 * @throws Exception Class not found.
	 */
	private function file_path( string $class ): string {
		$plugin_parts = explode( '\\', $class );
		$name         = array_pop( $plugin_parts );
		$name         = preg_match( '/^(Interface|Trait)/', $name )
			? $name . '.php'
			: 'class-' . $name . '.php';
		$local_path   = implode( '/', $plugin_parts ) . '/' . $name;
		$local_path   = strtolower( str_replace( [ '\\', '_' ], [ '/', '-' ], $local_path ) );

		foreach ( $this->folders as $folder ) {
			$path = $folder . $local_path;
			if ( file_exists( $path ) ) {
				return $path;
			}
		}

		throw new Exception( $class, $this->folders );
	}

}
