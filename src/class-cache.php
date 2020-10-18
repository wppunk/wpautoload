<?php
/**
 * Cache for autoload.
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
if ( class_exists( '\WPPunk\Autoload\Cache' ) ) {
	return;
}
/**
 * @codingStandardsIgnoreEnd
 */

/**
 * Class Cache
 *
 * @package wppunk/wpautoload
 */
class Cache {

	/**
	 * Classmap file
	 *
	 * @var string
	 */
	private $map_file;
	/**
	 * Classmap
	 *
	 * @var array
	 */
	private $map;
	/**
	 * Has the cache been updated
	 *
	 * @var bool
	 */
	private $has_been_update = false;

	/**
	 * Cache constructor.
	 */
	public function __construct() {
		$this->map_file = __DIR__ . '/../cache/classmap.php';
		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		if ( file_exists( $this->map_file ) ) {
			include $this->map_file;
		}
		$this->map = is_array( $this->map ) ? $this->map : [];
		register_shutdown_function( [ $this, 'save' ] );
	}

	/**
	 * Get path for class
	 *
	 * @param string $class Class name.
	 *
	 * @return string
	 */
	public function get( $class ) {
		return isset( $this->map[ $class ] ) ? $this->map[ $class ] : '';
	}

	/**
	 * Update cache
	 *
	 * @param string $class Class name.
	 * @param string $path  Path to file.
	 */
	public function update( $class, $path ) {
		$this->has_been_update = true;
		$this->map[ $class ]   = $path;
	}

	/**
	 * Save cache
	 */
	public function save() {
		if ( ! $this->has_been_update ) {
			return;
		}

		$this->clear_garbage();
		if ( ! $this->map ) {
			return;
		}

		$this->create_dir();

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
		file_put_contents( $this->map_file, '<?php return [' . $this->create_map() . '];', LOCK_EX );
	}

	/**
	 * Create cache directory.
	 */
	private function create_dir() {
		if ( ! file_exists( dirname( $this->map_file ) ) ) {
			mkdir( dirname( $this->map_file ), 0755, true );
		}
	}

	/**
	 * Create class map.
	 *
	 * @return string
	 */
	private function create_map() {
		$map  = '';
		$last = end( $this->map );
		foreach ( $this->map as $key => $value ) {
			$map .= "'$key' => '$value'";
			if ( $value !== $last ) {
				$map .= ",\n";
			}
		}

		return $map;
	}

	/**
	 * Clear garbage classes
	 */
	private function clear_garbage() {
		foreach ( $this->map as $key => $file ) {
			$this->clear_class( $key, $file );
		}
	}

	/**
	 * Clear class
	 *
	 * @param string $class_name Class name.
	 * @param string $path       Path to file.
	 */
	private function clear_class( $class_name, $path ) {
		$path = realpath( $path );
		if ( ! file_exists( $path ) ) {
			unset( $this->map[ $class_name ] );

			return;
		}

		$this->map[ $class_name ] = $path;
	}

}
