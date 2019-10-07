<?php
/**
 * Action handler class
 *
 * @package PressCounts
 * @subpackage Handlers
 */

namespace PressPeople\PressCounts\Shortcodes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Actions_Handler
 */
class User_Count {

	/**
	 * Class self boot
	 */
	public static function boot() {
		$self = new self();
		$self->setup();
	}

	/**
	 * Setup
	 */
	private function setup() {
		add_shortcode( 'pcounts-total-users', array( $this, 'output' ) );

		// Clear count on registration
		// clear count on user delete.
		add_action( 'user_register', array( $this, 'clear_cache' ) );
		add_action( 'delete_user', array( $this, 'clear_cache' ) );
		add_action( 'make_spam_user', array( $this, 'clear_cache' ) );
		add_action( 'make_ham_user', array( $this, 'clear_cache' ) );
	}

	/**
	 * Generate shortcode output.
	 *
	 * Usage [pcount-total-users]
	 *
	 * @param array  $atts atts.
	 * @param string $content content.
	 *
	 * @return int
	 */
	public function output( $atts = array(), $content = '' ) {
		$atts = shortcode_atts(
			array(
				'class' => '',
				'sep'   => ',', // thosand separator.
			),
			$atts
		);

		$class = 'pcounts-total-users ' . $atts['class'];

		return sprintf( '<span class="%s">%s</span>', esc_attr( $class ), number_format( $this->get_count(), 0, '.', $atts['sep'] ) );
	}

	/**
	 * Get users count.
	 *
	 * @return int
	 */
	private function get_count() {

		$count = get_transient( 'presscounts_total_users_count' );
		if ( false === $count ) {
			// re count.
			$user_counts = count_users();
			$count     = $user_counts['total_users'];
			set_transient( 'presscounts_total_users_count', $count, DAY_IN_SECONDS );
		}

		return $count;
	}

	/**
	 * Clear cache.
	 */
	public function clear_cache() {
		delete_transient( 'presscounts_total_users_count' );
	}
}

