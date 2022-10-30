<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Help Dialog Widgets page
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Widgets_Display {

	/**
	 * Displays the Help Dialog Widgets page with top panel
	 */
	public function display_page() {

		$handler = self::get_widgets_page_handler();
		if ( is_wp_error( $handler ) ) {
			EPHD_HTML_Admin::display_config_error_page( $handler );
			return;
		}

		// display the widgets page
		$handler->display_page();
	}

	public static function get_widgets_page_handler( $widgets_config=[], $designs_config=[], $contact_forms_config=[] ) {

		// retrieve widgets configuration
		if ( empty( $widgets_config ) ) {

			// retrieve main widgets configs
			$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
			if ( is_wp_error( $widgets_config ) ) {
				return $widgets_config;
			}
		}

		// retrieve designs configuration
		$designs_config = empty( $designs_config ) ? ephd_get_instance()->designs_config_obj->get_config( true ) : $designs_config;
		if ( is_wp_error( $designs_config ) ) {
			return $designs_config;
		}

		// retrieve Contact Forms configuration
		$contact_forms_config = empty( $contact_forms_config ) ? ephd_get_instance()->contact_forms_config_obj->get_config( true ) : $contact_forms_config;
		if ( is_wp_error( $contact_forms_config ) ) {
			return $contact_forms_config;
		}

		return new EPHD_Widgets_Page( $widgets_config, $designs_config, $contact_forms_config );
	}
}
