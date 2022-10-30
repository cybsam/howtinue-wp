<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Help Dialog Channels page
 *
 * @copyright   Copyright (C) 2022, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Channels_Display {

	/**
	 * Displays the Help Dialog Channels page with top panel
	 */
	public function display_page() {

		$handler = self::get_channels_page_handler();
		if ( is_wp_error( $handler ) ) {
			EPHD_HTML_Admin::display_config_error_page( $handler );
			return;
		}

		// display the Channels page
		$handler->display_page();
	}

	public static function get_channels_page_handler( $channels_config=[] ) {

		// retrieve channels configuration
		/*
		if ( empty( $channels_config ) ) {

			// retrieve main widgets configs
			$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
			if ( is_wp_error( $widgets_config ) ) {
				return $widgets_config;
			}
		}
		*/

		return new EPHD_Channels_Page( $channels_config );
	}
}
