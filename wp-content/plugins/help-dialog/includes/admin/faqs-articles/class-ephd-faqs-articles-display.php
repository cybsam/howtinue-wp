<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Help Dialog FAQs page
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_FAQs_Articles_Display {

	/**
	 * Displays the Help Dialog Widgets page with top panel
	 */
	public function display_page() {

		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_HTML_Admin::display_config_error_page( $widgets_config );
			return;
		}

		// display the FAQs page
		$handler = new EPHD_FAQs_Articles_Page( $widgets_config );
		$handler->display_page();
	}
}
