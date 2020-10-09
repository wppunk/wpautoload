<?php
/**
 * Cache for autoload.
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
 * Class Cache
 *
 * @package WP_Autoload
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

		$map  = '';
		$last = end( $this->map );
		foreach ( $this->map as $key => $value ) {
			$map .= "'$key' => '$value'";
			if ( $value !== $last ) {
				$map .= ",\n";
			}
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
		if ( ! file_exists( dirname( $this->map_file ) ) ) {
			mkdir( dirname( $this->map_file ), 0755, true );
		}

		file_put_contents( $this->map_file, '<?php return [' . $map . '];' );
	}

	public function clear_garbage() {
		if ( ! $this->map ) {
			return;
		}
		foreach ( $this->map as $key => $file ) {
			$file = realpath( $file );
			if ( ! file_exists( $file ) ) {
				unset( $this->map[ $key ] );
			} else {
				$this->map[ $key ] = $file;
			}
		}
	}

}
