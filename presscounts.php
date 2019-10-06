<?php
/**
 * Plugin Name: PressCounts
 * Version: 1.0.0
 * Plugin URI: https://buddydev.com/plugins/wp-count-all
 * Description: Count things in WordPress
 * Author: BuddyDev
 * Author URI: https://buddydev.com/
 * Requires PHP: 5.3
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  presscounts
 * Domain Path:  /languages
 *
 * @package wp-skeleton
 **/

use PressPeople\PressCounts\Bootstrap\Autoloader;
use PressPeople\PressCounts\Bootstrap\Bootstrapper;

// No direct access over web.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PressCounts
 *
 * @property-read $path     string Absolute path to the plugin directory.
 * @property-read $url      string Absolute url to the plugin directory.
 * @property-read $basename string Plugin base name.
 * @property-read $version  string Plugin version.
 */
class PressCounts {

	/**
	 * Plugin Version.
	 *
	 * @var string
	 */
	private $version = '1.0.0';

	/**
	 * Class instance
	 *
	 * @var PressCounts
	 */
	private static $instance = null;

	/**
	 * Plugin absolute directory path
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Plugin absolute directory url
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Plugin Basename.
	 *
	 * @var string
	 */
	private $basename;

	/**
	 * Protected properties. These properties are inaccessible via magic method.
	 *
	 * @var array
	 */
	private $secure_properties = array( 'instance' );

	/**
	 * PressCounts constructor.
	 */
	private function __construct() {
		$this->bootstrap();
	}

	/**
	 * Get Singleton Instance
	 *
	 * @return PressCounts
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Bootstrap the core.
	 */
	private function bootstrap() {
		$this->path     = plugin_dir_path( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->basename = plugin_basename( __FILE__ );

		// Load autoloader.
		require_once $this->path . 'src/bootstrap/class-autoloader.php';

		$autoloader = new Autoloader( 'PressPeople\PressCounts\\', __DIR__ . '/src/' );

		spl_autoload_register( $autoloader );

		// register_activation_hook( __FILE__, array( $this, 'on_activation' ) );
		// register_deactivation_hook( __FILE__, array( $this, 'on_deactivation' ) );

		Bootstrapper::boot();
	}

	/**
	 * On activation create table
	 */
	public function on_activation() {
	}

	/**
	 * On deactivation. Do cleanup if needed.
	 */
	public function on_deactivation() {
		// do cleanup.
		// delete_option( 'wp_skeleton_settings' );
	}

	/**
	 * Magic method for accessing property as readonly(It's a lie, references can be updated).
	 *
	 * @param string $name property name.
	 *
	 * @return mixed|null
	 */
	public function __get( $name ) {

		if ( ! in_array( $name, $this->secure_properties, true ) && property_exists( $this, $name ) ) {
			return $this->{$name};
		}

		return null;
	}
}

/**
 * Helper to access singleton instance
 *
 * @return PressCounts
 */
function presscounts() {
	return PressCounts::get_instance();
}

presscounts();
