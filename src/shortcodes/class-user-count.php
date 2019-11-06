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
		add_action( 'set_user_role', array( $this, 'clear_cache' ) );
		add_action( 'remove_user_role', array( $this, 'clear_cache' ) );
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
				'sep'   => ',', // thousand separator.
				'role'  => '',
			),
			$atts
		);

		$class = 'pcounts-total-users ' . $atts['class'];

		if ( $atts['role'] ) {
			$count = $this->get_count_by_role( $atts['role'] );
		} else {
			$count = $this->get_total_count();
		}

		return sprintf( '<span class="%s">%s</span>', esc_attr( $class ), number_format( $count, 0, '.', $atts['sep'] ) );
	}

	/**
	 * Get users count.
	 *
	 * @return int
	 */
	private function get_count() {

		$count = get_transient( 'presscounts_users_count' );
		if ( false === $count ) {
			// re count.
			$count = count_users();
			set_transient( 'presscounts_users_count', $count, DAY_IN_SECONDS );
		}

		return $count;
	}

	/**
	 * Get total count.
	 *
	 * @return int
	 */
	private function get_total_count() {
		$count = $this->get_count();

		return isset( $count['total_users'] ) ? $count['total_users'] : 0;
	}

	/**
	 * Get count by role.
	 *
	 * @param string $role role name.
	 *
	 * @return int
	 */
	private function get_count_by_role( $role ) {
		$count = $this->get_count();

		return isset( $count['avail_roles'][ $role ] ) ? $count['avail_roles'][ $role ] : 0;

	}

	/**
	 * Clear cache.
	 */
	public function clear_cache() {
		delete_transient( 'presscounts_users_count' );
	}
}
