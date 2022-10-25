<?php
/**
 * KnowX\KnowX\Custom_Js\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Custom_Js;

use function add_action;
use function get_theme_file_path;
use function get_theme_file_uri;
use function wp_enqueue_script;
use KnowX\KnowX\Component_Interface;
use function KnowX\KnowX\knowx;
use function wp_script_add_data;

/**
 * Class for improving custom_js among various core features.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string component slug
	 */
	public function get_slug(): string {
		return 'custom_js';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_custom_js_script' ) );
	}

	/**
	 * Enqueues a script that improves navigation menu accessibility.
	 */
	public function action_enqueue_custom_js_script() {
		// If the AMP plugin is active, return early.
		if ( knowx()->is_amp() ) {
			return;
		}

		// Enqueue the superfish script.
		wp_enqueue_script(
			'knowx-superfish',
			get_theme_file_uri( '/assets/js/superfish.min.js' ),
			array(),
			knowx()->get_asset_version( get_theme_file_path( '/assets/js/superfish.min.js' ) ),
			true
		);
		wp_script_add_data( 'knowx-superfish', 'async', true );
		wp_script_add_data( 'knowx-superfish', 'precache', true );

		// Enqueue the isotope script.
		wp_enqueue_script(
			'knowx-isotope-pkgd',
			get_theme_file_uri( '/assets/js/isotope.pkgd.min.js' ),
			array(),
			knowx()->get_asset_version( get_theme_file_path( '/assets/js/isotope.pkgd.min.js' ) ),
			true
		);
		wp_script_add_data( 'knowx-isotope-pkgd', 'async', true );
		wp_script_add_data( 'knowx-isotope-pkgd', 'precache', true );

		// Enqueue the fitVids script.
		wp_enqueue_script(
			'knowx-fitvids',
			get_theme_file_uri( '/assets/js/fitvids.min.js' ),
			array(),
			knowx()->get_asset_version( get_theme_file_path( '/assets/js/fitvids.min.js' ) ),
			true
		);
		wp_script_add_data( 'knowx-fitvids', 'async', true );
		wp_script_add_data( 'knowx-fitvids', 'precache', true );

		// Enqueue the sticky kit script.
		wp_enqueue_script(
			'knowx-sticky-kit',
			get_theme_file_uri( '/assets/js/sticky-kit.min.js' ),
			array(),
			knowx()->get_asset_version( get_theme_file_path( '/assets/js/sticky-kit.min.js' ) ),
			true
		);
		wp_script_add_data( 'knowx-sticky-kit', 'async', true );
		wp_script_add_data( 'knowx-sticky-kit', 'precache', true );

		// Enqueue the custom script.
		wp_enqueue_script(
			'knowx-custom',
			get_theme_file_uri( '/assets/js/custom.min.js' ),
			array( 'jquery', 'knowx-superfish', 'knowx-isotope-pkgd', 'knowx-fitvids', 'knowx-sticky-kit' ),
			knowx()->get_asset_version( get_theme_file_path( '/assets/js/custom.min.js' ) ),
			true
		);
		wp_script_add_data( 'knowx-custom', 'async', true );
		wp_script_add_data( 'knowx-custom', 'precache', true );
	}
}
