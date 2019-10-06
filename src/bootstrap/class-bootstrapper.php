<?php
/**
 * Bootstrapper. Initializes the plugin.
 *
 * @package    PressCounts
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2019, Brajesh Singh
 * @license    https://www.gnu.org/licenses/gpl.html GNU Public License
 * @author     Brajesh Singh
 * @since      1.0.0
 */

namespace PressPeople\PressCounts\Bootstrap;

use PressPeople\PressCounts\Shortcodes\User_Count;

// No direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 0 );
}

/**
 * Bootstrapper.
 */
class Bootstrapper {

	/**
	 * Setup the bootstrapper.
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Bind hooks
	 */
	private function setup() {
		add_action( 'plugins_loaded', array( $this, 'load' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'load_admin' ), 9996 ); // pt settings 1.0.4.
		add_action( 'init', array( $this, 'load_translations' ) );
	}

	/**
	 * Load core functions/template tags.
	 * These are non auto loadable constructs.
	 */
	public function load() {
		$path = presscounts()->path;

		$files = array(
			'src/core/press-counts-functions.php',
		);

		foreach ( $files as $file ) {
			require_once $path . $file;
		}

		User_Count::boot();
	}

	/**
	 * Load pt-settings framework
	 */
	public function load_admin() {

	}

	/**
	 * Load translations.
	 */
	public function load_translations() {
		load_plugin_textdomain( 'presscounts', false, basename( dirname( presscounts()->path ) ) . '/languages' );
	}
}
