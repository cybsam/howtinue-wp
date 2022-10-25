<?php
/**
 * Shims for recent WordPress functions.
 */

/*
 * Adds backwards compatibility for knowx_wp_body_open() introduced with WordPress 5.2
 */
if ( ! function_exists( 'knowx_wp_body_open' ) ) {
	/**
	 * Run the knowx_wp_body_open action.
	 *
	 * @return void
	 */
	function knowx_wp_body_open() {
		do_action( 'knowx_wp_body_open' );
	}
}
