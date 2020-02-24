<?php
/**
 * Cache for autoload.
 *
 * @package   WP-Autoload
 * @author    Maksym Denysenko
 * @link      https://github.com/mdenisenko/WP-Autoload
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace WP_Autoload;

use WP_Filesystem_Direct;
use function add_action;
use function plugin_dir_path;

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
		$this->map_file = plugin_dir_path( __DIR__ ) . 'cache/classmap.php';
		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$this->map = @include $this->map_file;
		$this->map = is_array( $this->map ) ? $this->map : [];
		add_action( 'shutdown', [ $this, 'save' ] );
	}

	/**
	 * Has valid cache for class.
	 *
	 * @param string $class Class name.
	 *
	 * @return bool
	 */
	private function valid( string $class ): bool {
		if ( ! isset( $this->map[ $class ] ) ) {
			return false;
		}

		if ( ! file_exists( $this->map[ $class ] ) ) {
			unset( $this->map[ $class ] );

			return false;
		}

		return true;
	}

	/**
	 * Get path for class
	 *
	 * @param string $class Class name.
	 *
	 * @return string
	 */
	public function get( string $class ): string {
		return $this->valid( $class ) ? $this->map[ $class ] : '';
	}

	/**
	 * Update cache
	 *
	 * @param string $class Class name.
	 * @param string $path  Path to file.
	 */
	public function update( string $class, string $path ) {
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
		$map = implode(
			"\n",
			array_map(
				function ( $k, $v ) {
					return "'$k' => '$v',";
				},
				array_keys( $this->map ),
				array_values( $this->map )
			)
		);
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
		$filesystem = $this->WP_Filesystem();
		$filesystem->put_contents( $this->map_file, '<?php return [' . $map . '];' );
	}

	/**
	 * Create instance WP_Filesystem
	 *
	 * @return WP_Filesystem_Direct
	 *
	 * phpcs:disable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
	 */
	private function WP_Filesystem(): WP_Filesystem_Direct {
		//phpcs:enable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		global $wp_filesystem;
		if ( null === $wp_filesystem ) {
			if ( ! class_exists( 'WP_Filesystem_Base' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
			}
			WP_Filesystem();
		}

		return new WP_Filesystem_Direct( null );
	}

}
