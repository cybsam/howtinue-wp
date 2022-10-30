<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handle user submission for Analytics data
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Analytics_Ctrl {

	public function __construct() {
		add_action( 'wp_ajax_ephd_count_invocations_action', array( 'EPHD_Analytics_Ctrl', 'invocations_handler' ) );
		// record analytics even if user not logged in
		add_action( 'wp_ajax_nopriv_ephd_count_invocations_action', array( 'EPHD_Analytics_Ctrl', 'invocations_handler' ) );

		add_action( 'wp_ajax_ephd_save_analytics_settings', array( 'EPHD_Analytics_Ctrl', 'save_analytics_settings' ) );
		add_action( 'wp_ajax_nopriv_ephd_save_analytics_settings', array( 'EPHD_Utilities', 'user_not_logged_in' ) );
	}

	/**
	 * AJAX handler to count events for analytics
	 */
	public static function invocations_handler() {

		// check wpnonce and prevent direct access
		EPHD_Utilities::ajax_verify_nonce_and_prevent_direct_access_or_error_die();

		// Retrieve target Widget ID
		$widget_id = (int)EPHD_Utilities::post( 'widget_id' );
		if ( empty( $widget_id ) || $widget_id <= 0 ) {
			EPHD_Logging::add_log( 'Analytics Update: Could not retrieve Widget ID' );
			wp_die();
		}

		// Retrieve target Widget config
		$widgets_config_handler = new EPHD_Widgets_DB();
		$widget_config = $widgets_config_handler->get_widget_config_by_id( $widget_id );
		if ( empty( $widget_config ) ) {
			EPHD_Logging::add_log( 'Analytics Update: Could not retrieve Widget for ID: ' . $widget_id );
			wp_die();
		}

		// Retrieve target Page ID
		$page_id = (int)EPHD_Utilities::post( 'page_id' );
		if ( $page_id < 0 ) {
			EPHD_Logging::add_log( 'Analytics Update: Could not retrieve Page ID for widget ID: ' . $widget_id );
			wp_die();
		}

		// Retrieve Event Name
		$event_name = EPHD_Utilities::post( 'event_name' );
		if ( empty( $event_name ) ) {
			EPHD_Logging::add_log( 'Analytics Update: Could not retrieve Event Name for widget ID: ' . $widget_id . ' and page ID: ' . $page_id  );
			wp_die();
		}

		// Retrieve column name. Optional, default: 'other_2'
		$column_name = EPHD_Utilities::post( 'column_name', 'other_2' );
		if ( ! in_array( $column_name, ['view', 'click_1', 'click_2', 'other_1', 'other_2'] ) ) {
			EPHD_Logging::add_log( 'Analytics Update: Invalid column name: ' . $column_name  );
			wp_die();
		}

		// retrieve global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Logging::add_log( 'Failed to load global config', $global_config );
			wp_die();
		}

		// is user role excluded from counting?
		$current_user = EPHD_Utilities::get_current_user();
		if ( ! empty( $current_user ) ) {
			$current_user_excluded_roles = array_intersect( $global_config['analytic_excluded_roles'], $current_user->roles );
			if ( ! empty( $current_user_excluded_roles ) ) {
				return;
			}
		}

		// Retrieve Analytics DB object
		$analytics = new EPHD_Analytics_DB();

		// Check if event exist
		if ( ! isset( $analytics->events_meta[$event_name] ) ) {
			EPHD_Logging::add_log( 'Analytics update: Event "' . $event_name . '" does not exist for widget ID: ' . $widget_id . ' and page ID: ' . $page_id );
			wp_die();
		}

		// Validate event
		$event_meta = $analytics->events_meta[$event_name];

		// Check if event param and column value exist
		if ( ! isset( $event_meta['type_id'] ) || ! isset( $event_meta['aggregation'] ) || empty( $column_name ) ) {
			EPHD_Logging::add_log( 'Analytics update: Could not retrieve Event Meta for ID: ' . $event_name . ', Column name: ' . $column_name . ' for widget ID: ' . $widget_id . ' and page ID: ' . $page_id);
			wp_die();
		}

		// is counting of this event disabled in config?
		if ( ! empty( $event_meta['count_config'] ) ) {
			if ( isset( $global_config[ $event_meta['count_config'] ] ) ) {
				if ( 'off' == $global_config[ $event_meta['count_config'] ] ) {
					wp_die();
				}
			}
		}

		// Retrieve Object ID.
		$object_id = (int)EPHD_Utilities::post( 'object_id' );
		// if event object is a widget
		/* if ( 'widget' == $event_meta['object'] && empty( $object_id ) ) {
			$object_id = $widget_id;
		}
		if ( empty( $object_id ) ) {
			EPHD_Logging::add_log( 'Analytics Update: Could not retrieve Object ID for widget ID: ' . $widget_id . ' and page ID: ' . $page_id  );
			wp_die();
		}
		// is FAQ object_id valid for a widget
		if ( 'faq' == $event_meta['object'] ) {
			$widgets_config_obj = ephd_get_instance()->widgets_config_obj->get_config( true );
			if ( is_wp_error( $widgets_config_obj ) ) {
				EPHD_Logging::add_log( 'Analytics Update: failed to retrieve FAQs configuration for widget ID: ' . $widget_id );
				wp_die();
			}
			if ( ! in_array( $object_id, $widgets_config_obj[ $widget_config['widget_id'] ]['faqs_sequence'] ) ) {
				EPHD_Logging::add_log( 'Analytics Update: invalid FAQ ID for widget ID: ' . $widget_id );
				wp_die();
			}
		}
		// is Contact Form object_id valid for a widget
		if ( 'contact' == $event_meta['object'] ) {
			$contact_forms_config = ephd_get_instance()->contact_forms_config_obj->get_config( true );
			if ( is_wp_error( $contact_forms_config ) ) {
				EPHD_Logging::add_log( 'Analytics Update: failed to retrieve Contact Form configuration for widget ID: ' . $widget_id );
				wp_die();
			}
			if ( ! in_array( $object_id, $contact_forms_config[ $widget_config['contact_form_id'] ] ) ) {
				EPHD_Logging::add_log( 'Analytics Update: invalid Contact Form ID for widget ID: ' . $widget_id );
				wp_die();
			}
		} */

		// Count event
		$result = $analytics->count_event( $widget_id, $page_id, $object_id, $column_name, $event_meta );
		if ( empty( $result ) ) {
			EPHD_Logging::add_log( "Could not update analytics for Widget: {$widget_id} Event: {$event_name} Column: {$column_name}" );
		}

		wp_die();
	}

	/**
	 * User updated Help Dialog Analytics Settings
	 */
	public static function save_analytics_settings() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve Global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 410 ) );
		}

		// OPTION: Count Launcher Impression Status
		/* FUTURE TODO $analytic_count_launcher_impression = EPHD_Utilities::post( 'analytic_count_launcher_impression', 'off' );
		$result = ephd_get_instance()->global_config_obj->set_value( 'analytic_count_launcher_impression', $analytic_count_launcher_impression );
		if ( is_wp_error( $result ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 411, $result ) );
		}*/

		// OPTION: Excluded User Roles
		$global_config['analytic_excluded_roles'] = EPHD_Utilities::post( 'analytic_excluded_roles', [] );

		// save changes
		$updated_global_config = ephd_get_instance()->global_config_obj->update_config( $global_config );
		if ( is_wp_error( $updated_global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 412, $updated_global_config ) );
		}

		wp_die( json_encode( array(
			'status'    => 'success',
			'message'   => esc_html__( 'Configuration Saved', 'help-dialog')
		) ) );
	}

}
