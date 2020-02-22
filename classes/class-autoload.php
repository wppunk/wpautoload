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

use WP_Filesystem_Direct;

/**
 * Class Autoload
 */
class Autoload {

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
	 * Has the cache been updated
	 *
	 * @var bool
	 */
	private $has_been_update = false;

	/**
	 * Autoload constructor.
	 *
	 * @param string $prefix  Prefix for your namespace.
	 * @param array  $folders List of folders for autoload.
	 */
	public function __construct( string $prefix, array $folders ) {
		$this->prefix   = $prefix;
		$this->folders  = $folders;
		$this->map_file = plugin_dir_path( __DIR__ ) . 'cache/classmap.php';
		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$this->map = @include $this->map_file;
		$this->map = is_array( $this->map ) ? $this->map : [];
		spl_autoload_register( [ $this, 'autoload' ] );
		add_action( 'shutdown', [ $this, 'update_cache' ] );
	}

	/**
	 * Autoload files for custom plugins
	 *
	 * @param string $class Full class name.
	 */
	private function autoload( string $class ): void {
		if ( 0 === strpos( $class, $this->prefix ) ) {
			if ( $this->map[ $class ] && file_exists( $this->map[ $class ] ) ) {
				require_once $this->map[ $class ];
			} else {
				$this->has_been_update = true;
				try {
					$path = $this->file_path( $class );
					// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					$this->map[ $class ] = $path;
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

	/**
	 * Update classmap
	 */
	public function update_cache(): void {
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
		$filesystem = self::WP_Filesystem();
		$filesystem->put_contents( $this->map_file, '<?php return [' . $map . '];' );
	}

}
