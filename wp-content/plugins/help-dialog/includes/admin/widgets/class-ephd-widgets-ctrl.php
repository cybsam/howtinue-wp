<?php

defined( 'ABSPATH' ) || exit();

/**
 * Handle user submission from Help dialog Widgets
 */
class EPHD_Widgets_Ctrl {

	public function __construct() {

		add_action( 'wp_ajax_ephd_create_widget', array( $this, 'create_widget' ) );
		add_action( 'wp_ajax_nopriv_ephd_create_widget', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_update_widget', array( $this, 'update_widget' ) );
		add_action( 'wp_ajax_nopriv_ephd_update_widget', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_delete_widget', array( $this, 'delete_widget' ) );
		add_action( 'wp_ajax_nopriv_ephd_delete_widget', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_update_preview', array( $this, 'update_preview' ) );
		add_action( 'wp_ajax_nopriv_ephd_update_preview', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_load_widget_form', array( $this, 'load_widget_form' ) );
		add_action( 'wp_ajax_nopriv_ephd_load_widget_form', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_search_locations', array( $this, 'search_locations' ) );
		add_action( 'wp_ajax_nopriv_ephd_search_locations', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_copy_design_to', array( $this, 'copy_design_to' ) );
		add_action( 'wp_ajax_nopriv_ephd_copy_design_to', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_tiny_mce_input_save', array( $this, 'tiny_mce_input_save' ) );
		add_action( 'wp_ajax_nopriv_ephd_tiny_mce_input_save', array( 'EPHD_Utilities', 'user_not_logged_in' ) );
	}

	/**
	 * Create WIDGET together with its own design
	 */
	public function create_widget() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve configuration for all Widgets
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 28, $widgets_config ) );
		}

		// retrieve Widget data
		$widget = self::get_sanitized_widget_from_input();

		// add missing fields from specs
		$widget = array_merge( EPHD_Config_Specs::get_default_hd_config(), $widget );

		// retrieve configuration for all Designs
		$designs_config = ephd_get_instance()->designs_config_obj->get_config( true );
		if ( is_wp_error( $designs_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 29 ) );
		}

		// retrieve presets Design data
		$design = self::get_updated_design_from_input( $designs_config );

		// cannot overwrite existing widget
		if ( isset( $widgets_config[$widget['widget_id']] ) ) {
			EPHD_Logging::add_log( 'Widget already exists (30)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 30, 'widget id: ' . $widget['widget_id'] ) );
		}

		// retrieve Global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 31 ) );
		}

		// retrieve and apply changes for Global config
		$global_config = self::get_sanitized_global_form_from_input( $global_config );

		// assign new widget an ID
		$global_config['last_widget_id']++;
		$widget['widget_id'] = $global_config['last_widget_id'];

		// assign new design an ID and its widget new design ID
		/* $global_config['last_design_id']++;
		$design['design_id'] = $global_config['last_design_id'];
		$widget['design_id'] = $global_config['last_design_id']; */

		// save Widgets configuration
		$widgets_config[$widget['widget_id']] = $widget;
		$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_widgets_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Widgets configuration. (33)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 33, $updated_widgets_config ) );
		}
		$updated_widget = $updated_widgets_config[$widget['widget_id']];

		// save Designs configuration
		$designs_config[$design['design_id']] = $design;
		$updated_designs_config = ephd_get_instance()->designs_config_obj->update_config( $designs_config );
		if ( is_wp_error( $updated_designs_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Designs configuration. (34)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 34, $updated_designs_config ) );
		}

		// update last Widget id and last Design id in Global configuration
		$updated_global_config = ephd_get_instance()->global_config_obj->update_config( $global_config );
		if ( is_wp_error( $updated_global_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Global configuration. (35)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 35, $updated_global_config ) );
		}

		// pass into JS the new Widget as a new settings box
		$widgets_page_handler = EPHD_Widgets_Display::get_widgets_page_handler( $widgets_config, $designs_config );
		if ( is_wp_error( $widgets_page_handler ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 36 ) );
		}

		$widget_form_config = array(
			'class'         => 'ephd-wp__widget-form ephd-wp__widget-form--active',
			'html'          => $widgets_page_handler->get_widget_form( $updated_widget ),
			'return_html'   => true,
		);

		// pass into JS a preview box for the new Widget
		$widget_preview_config = $widgets_page_handler->get_config_of_widget_preview_box( $updated_widget, true );

		// Set notification message
		$notification_message = __( 'Configuration Saved', 'help-dialog');
		if ( $widget['widget_status'] == 'published' ) {
			$notification_message = __( 'Configuration is saved and the Widget is published', 'help-dialog');
		}
		if ( $widget['widget_status'] == 'draft' ) {
			$notification_message = __( 'Configuration is saved and the Widget is set to Draft', 'help-dialog');
		}

		wp_die( json_encode( array(
			'status'            => 'success',
			'message'           => esc_html( $notification_message ),
			'widget_id'         => esc_attr( $updated_widget['widget_id'] ),
			'widget_form'       => EPHD_HTML_Forms::admin_settings_box( $widget_form_config ),
			'demo_styles'       => EPHD_Help_Dialog_View::insert_widget_inline_styles( $updated_global_config, $widget, [], true ),
			'widget_preview'    => EPHD_HTML_Forms::admin_settings_box( $widget_preview_config ),
		) ) );
	}

	/**
	 * Update WIDGET
	 */
	public function update_widget() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve Widget data
		$widget = self::get_sanitized_widget_from_input();

		// create a new widget if it doesn't exist
		if ( empty( $widget['widget_id'] ) ) {
			self::create_widget();
			return;
		}

		/** UPDATE WIDGET SETTINGS */

		// retrieve configuration for all widgets
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 40 ) );
		}

		// retrieve configuration for all designs
		$designs_config = ephd_get_instance()->designs_config_obj->get_config( true );
		if ( is_wp_error( $designs_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 41 ) );
		}

		// do not update if the widget does not exist
		if ( ! isset( $widgets_config[$widget['widget_id']] ) ) {
			EPHD_Logging::add_log( 'Widget does not exist (42)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 42, 'widget id: ' . $widget['widget_id'] ) );
		}

		// Save widget status before changes
		$initial_widget_status = $widgets_config[$widget['widget_id']]['widget_status'];

		// add missing fields for existing Widget
		$widget = array_merge( $widgets_config[$widget['widget_id']], $widget );

		// save Widgets configuration
		$widgets_config[$widget['widget_id']] = $widget;
		$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_widgets_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving widgets configuration. (43)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 43, $updated_widgets_config ) );
		}
		$updated_widget = $updated_widgets_config[$widget['widget_id']];

		/** UPDATE DESIGN SETTINGS */

		// apply Design changes
		$designs_config[$widget['design_id']] = self::get_updated_design_from_input( $designs_config );

		// retrieve design setting fields
		$designs_config[$widget['design_id']] = self::get_sanitized_design_from_input( $designs_config[$widget['design_id']] );

		// restore Design id
		$designs_config[$widget['design_id']]['design_id'] = $widget['design_id'];

		// save Designs configuration
		$updated_designs_config = ephd_get_instance()->designs_config_obj->update_config( $designs_config );
		if ( is_wp_error( $updated_designs_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving designs configuration. (44)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 44, $updated_designs_config ) );
		}

		/** UPDATE GLOBAL SETTINGS */

		// retrieve Global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 45 ) );
		}

		// retrieve and apply changes for Global config
		$global_config = self::get_sanitized_global_form_from_input( $global_config );

		// save Global configuration
		$updated_global_config = ephd_get_instance()->global_config_obj->update_config( $global_config );
		if ( is_wp_error( $updated_global_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving global configuration. (46)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 46, $updated_global_config ) );
		}

		/** UPDATE CONTACT SETTINGS */

		// retrieve configuration for all contact forms
		$contact_forms_config = ephd_get_instance()->contact_forms_config_obj->get_config( true );
		if ( is_wp_error( $contact_forms_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 47 ) );
		}

		// retrieve contact form setting fields
		$contact_forms_config[$widget['contact_form_id']] = self::get_sanitized_contact_forms_from_input( $contact_forms_config[$widget['contact_form_id']] );

		// save contact form configuration
		$updated_contact_forms_config = ephd_get_instance()->contact_forms_config_obj->update_config( $contact_forms_config );
		if ( is_wp_error( $updated_contact_forms_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving contact form configuration. (48)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 48, $updated_contact_forms_config ) );
		}

		// pass into JS the new widget as a new settings box
		$widgets_page_handler = EPHD_Widgets_Display::get_widgets_page_handler( $widgets_config, $designs_config );
		if ( is_wp_error( $widgets_page_handler ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 49 ) );
		}

		/** FINISH */

		$widget_form_config = array(
			'class'         => 'ephd-wp__widget-form ephd-wp__widget-form--active',
			'html'          => $widgets_page_handler->get_widget_form( $updated_widget ),
			'return_html'   => true,
		);

		// pass into JS a preview box for the new Widget
		$widget_preview_config = $widgets_page_handler->get_config_of_widget_preview_box( $updated_widget, true );

		// Set notification message
		$notification_message = __( 'Configuration Saved', 'help-dialog');
		if ( $widget['widget_status'] == 'published' && $initial_widget_status == 'draft' ) {
			$notification_message = __( 'Configuration is saved and the Widget is published', 'help-dialog');
		}
		if ( $widget['widget_status'] == 'draft' && $initial_widget_status == 'published' ) {
			$notification_message = __( 'Configuration is saved and the Widget is set to Draft', 'help-dialog');
		}

		wp_die( json_encode( array(
			'status'            => 'success',
			'message'           => esc_html( $notification_message ),
			'widget_form'       => EPHD_HTML_Forms::admin_settings_box( $widget_form_config ),
			'demo_styles'       => EPHD_Help_Dialog_View::insert_widget_inline_styles( $updated_global_config, $widget, [], true ),
			'widget_preview'    => EPHD_HTML_Forms::admin_settings_box( $widget_preview_config ),
		) ) );
	}

	/**
	 * Delete WIDGET
	 */
	public function delete_widget() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve Widget id ( default Widget has id=1; normally user does not have option to delete the default Widget - generate error on attempt )
		$widget_id = (int)EPHD_Utilities::post( 'widget_id' );
		if ( empty( $widget_id ) || $widget_id == EPHD_Config_Specs::DEFAULT_ID ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 50 ) );
		}

		// retrieve configuration for all Widgets
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 51, $widgets_config ) );
		}

		// cannot delete Widget if it does not exist in configuration
		if ( ! isset( $widgets_config[$widget_id] ) ) {
			EPHD_Logging::add_log( 'Widget does not exist (52)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 52, $widgets_config ) );
		}

		// retrieve Design to delete
		$design_id = $widgets_config[$widget_id]['design_id'];

		// remove Widget
		unset( $widgets_config[$widget_id] );

		// update Widgets configuration
		$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_widgets_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving widgets configuration. (53)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 53, $updated_widgets_config ) );
		}

		// remove widget from custom table
		ephd_get_instance()->widgets_config_obj->delete_widget_by_id( $widget_id );  // ignore return value; will be cleared when saving changes

		// check if the current Design is used in other Widgets
		$design_unused = true;
		foreach ( $updated_widgets_config as $widget ) {
			if ( $widget['design_id'] == $design_id ) {
				$design_unused = false;
				break;
			}
		}

		// delete Design if it is unused
		if ( $design_unused ) {
			$designs_config = ephd_get_instance()->designs_config_obj->get_config( true );
			if ( is_wp_error( $designs_config ) ) {
				EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 54 ) );
			}

			unset( $designs_config[$design_id] );

			// update Designs configuration
			$updated_designs_config = ephd_get_instance()->designs_config_obj->update_config( $designs_config );
			if ( is_wp_error( $updated_designs_config ) ) {
				EPHD_Logging::add_log( 'Error occurred on saving Designs configuration. (55)' );
				EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 55, $updated_designs_config ) );
			}
		}

		wp_die( json_encode( array(
			'status'    => 'success',
			'message'   => esc_html__( 'Widget removed', 'help-dialog' ),
		) ) );
	}

	/**
	 * Update WIDGET preview when user changes settings - nothing is saved here
	 */
	public function update_preview() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve Widget data
		$widget = self::get_sanitized_widget_from_input();

		// retrieve configuration for all Widgets - user can preview existing Widget or be creating a Widget (when passed Widget id is 0)
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 60 ) );
		}

		// add missing fields for existing Widget
		$base_widget = isset( $widgets_config[$widget['widget_id']] )
			? $widgets_config[$widget['widget_id']]
			: $widgets_config[EPHD_Config_Specs::DEFAULT_ID];
		$widget = array_merge( $base_widget, $widget );

		// retrieve Designs configuration for all Widgets
		$designs_config = ephd_get_instance()->designs_config_obj->get_config( true );
		if ( is_wp_error( $designs_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 61 ) );
		}

		// add default Design from specs if it does not exist
		if ( ! isset( $designs_config[$widget['design_id']] ) ) {
			$designs_config[$widget['design_id']] = $designs_config[EPHD_Config_Specs::DEFAULT_ID];
			$designs_config[$widget['design_id']]['design_id'] = $widget['design_id'];
		}

		// retrieve and apply Design changes
		$designs_config[$widget['design_id']] = self::get_updated_design_from_input( $designs_config );

		// retrieve Design setting fields
		$designs_config[$widget['design_id']] = self::get_sanitized_design_from_input( $designs_config[$widget['design_id']] );

		// retrieve Global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 62 ) );
		}

		// retrieve and apply changes for Global config - do not save, preview only
		$global_config = self::get_sanitized_global_form_from_input( $global_config );

		// restore Design id
		$designs_config[$widget['design_id']]['design_id'] = $widget['design_id'];

		// define whether need to return the Widget preview opened or closed
		$is_opened = (bool)EPHD_Utilities::post( 'is_opened', false );

		$hd_handler = new EPHD_Help_Dialog_View( $widget, $designs_config[$widget['design_id']], $is_opened, true, $global_config );
		wp_die( json_encode( array(
			'status'        => 'success',
			'message'       => 'success',
			'demo_styles'   => EPHD_Help_Dialog_View::insert_widget_inline_styles( $global_config, $widget, $designs_config, true ),
			'preview'       => $hd_handler->output_help_dialog( true ),
		) ) );
	}

	/**
	 * Return form HTML to create a new Widget
	 */
	public function load_widget_form() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve Widget id (0 if it is needed to create a new Widget)
		$widget_id = (int)EPHD_Utilities::post( 'widget_id', -1 );
		if ( $widget_id < 0 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 65 ) );
		}

		// retrieve configs for all Widgets
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 66 ) );
		}

		// retrieve current Widget configuration
		if ( isset( $widgets_config[$widget_id] ) ) {
			$widget = $widgets_config[$widget_id];

		// or use default to create a new one
		} else {
			$widget = $widgets_config[EPHD_Widgets_DB::DEFAULT_ID];
			$widget['widget_id'] = 0;

			// retrieve new one widget name
			$widget_name = EPHD_Utilities::post( 'widget_name', $widgets_config[EPHD_Widgets_DB::DEFAULT_ID]['widget_name'] );
			if ( empty( $widget_name ) ) {
				EPHD_Utilities::ajax_show_error_die( __( 'Widget name cannot be empty.', 'help-dialog' ) );
			}
			$widget['widget_name'] = $widget_name;

			// make sure the new Widget does not inherit locations from default Widget
			$widget['location_pages_list'] = [];
			$widget['location_posts_list'] = [];
			$widget['location_cpts_list'] = [];
			$widget['faqs_sequence'] = [];
		}

		// pass into JS the new Widget as a new settings box
		$widgets_page_handler = EPHD_Widgets_Display::get_widgets_page_handler( $widgets_config );
		if ( is_wp_error( $widgets_page_handler ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 67 ) );
		}

		$widget_form_config = array(
			'class'         => 'ephd-wp__widget-form ephd-wp__widget-form--active' . ( isset( $widgets_config[$widget_id] ) ? '' : ' ephd-wp__new-widget-form' ),
			'html'          => $widgets_page_handler->get_widget_form( $widget ),
			'return_html'   => true,
		);

		// retrieve Designs configuration for all Widgets
		$designs_config = ephd_get_instance()->designs_config_obj->get_config( true );
		if ( is_wp_error( $designs_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 61 ) );
		}

		// add default Design from specs if it does not exist
		if ( isset( $designs_config[$widget['design_id']] ) ) {
			$design_config = $designs_config[$widget['design_id']];
		} else {
			$design_config = $designs_config[EPHD_Config_Specs::DEFAULT_ID];
			$design_config['design_id'] = $widget['design_id'];
		}
		$hd_handler = new EPHD_Help_Dialog_View( $widget, $design_config, true, true );

		wp_die( json_encode( array(
			'status'        => 'success',
			'message'       => 'success',
			'widget_form'   => EPHD_HTML_Forms::admin_settings_box( $widget_form_config ),
			'preview'       => $hd_handler->output_help_dialog( true ),
			'demo_styles'   => EPHD_Help_Dialog_View::insert_widget_inline_styles( [], $widget, [], true ),
		) ) );
	}

	/**
	 * Return sanitized Widget data from request data
	 *
	 * @return array
	 */
	private static function get_sanitized_widget_from_input() {

		$widget = [];

		// retrieve status value, can be only public or draft
		$widget['widget_status'] = EPHD_Utilities::post( 'widget_status' );
		if ( empty( $widget['widget_status'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 69 ) );
		}
		if ( $widget['widget_status'] !== EPHD_Help_Dialog_Handler::HELP_DIALOG_STATUS_PUBLIC ) {
			$widget['widget_status'] = EPHD_Help_Dialog_Handler::HELP_DIALOG_STATUS_DRAFT;
		}

		// retrieve Widget name, it cannot be empty - allow empty name only for preview update purpose
		$widget['widget_name'] = EPHD_Utilities::post( 'widget_name' );
		if ( empty( $widget['widget_name'] ) ) {
			EPHD_Utilities::ajax_show_error_die( __( 'Widget name cannot be empty.', 'help-dialog' ) );
		}

		// retrieve Widget id (0 if it is needed to create a new Widget)
		$widget['widget_id'] = (int)EPHD_Utilities::post( 'widget_id', -1 );
		if ( $widget['widget_id'] < 0 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 70 ) );
		}

		// retrieve Contact Form id (0 is for No Contact Form option)
		$widget['contact_form_id'] = (int)EPHD_Utilities::post( 'contact_form_id', -1 );
		if ( $widget['contact_form_id'] < 0 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 72 ) );
		}

		// retrieve search option
		$widget['search_option'] = EPHD_Utilities::post( 'search_option' );
		if ( ! in_array( $widget['search_option'], ['show_search', 'hide_search'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 73 ) );
		}

		// retrieve Design id (0 if it is needed to create a new Design)
		$widget['design_id'] = (int)EPHD_Utilities::post( 'design_id', -1 );
		if ( $widget['design_id'] < 0 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 74 ) );
		}

		// retrieve search posts
		$widget['search_posts'] = EPHD_Utilities::post( 'search_posts' );
		if ( ! in_array( $widget['search_posts'], ['on', 'off'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 75 ) );
		}

		// retrieve search kb
		if ( EPHD_KB_Core_Utilities::is_kb_or_amag_enabled() ) {
			$widget['search_kb'] = EPHD_Utilities::post( 'search_kb' );
			if ( empty( $widget['search_kb'] ) ) {
				EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 76 ) );
			}
		}

		// retrieve 'page' type of Locations
		$widget['location_pages_list'] = EPHD_Utilities::post( 'location_pages_list', [] );
		if ( ! is_array( $widget['location_pages_list'] ) ) {
			$widget['location_pages_list'] = array();
		}

		// retrieve 'post' type of Locations
		$widget['location_posts_list'] = EPHD_Utilities::post( 'location_posts_list', [] );
		if ( ! is_array( $widget['location_posts_list'] ) ) {
			$widget['location_posts_list'] = array();
		}

		// retrieve 'cpt' type of Locations
		$widget['location_cpts_list'] = EPHD_Utilities::post( 'location_cpts_list', [] );
		if ( ! is_array( $widget['location_cpts_list'] ) ) {
			$widget['location_cpts_list'] = array();
		}

		// retrieve faqs_sequence
		$widget['faqs_sequence'] = EPHD_Utilities::post( 'faqs_sequence', [] );
		if ( ! is_array( $widget['faqs_sequence'] ) ) {
			$widget['faqs_sequence'] = array();
		}

		// retrieve Message ID
		$initial_message_id = (int)EPHD_Utilities::post( 'initial_message_id' );

		// retrieve Message Text
		$initial_message_text = EPHD_Utilities::post( 'initial_message_text', '', 'wp_editor' );

		// retrieve Message Image URL
		$initial_message_image_url = EPHD_Utilities::post( 'initial_message_image_url' );

		// update Message ID only if text or image URL for message was changed
		$widget['initial_message_id'] = ! isset( $widget['initial_message_text'] ) || empty( $widget['initial_message_image_url'] ) || $initial_message_text != $widget['initial_message_text'] || $initial_message_image_url != $widget['initial_message_image_url']
			? current_time( 'timestamp', true )
			: $initial_message_id;

		// retrieve Show/Hide Initial Message
		$widget['initial_message_toggle'] = EPHD_Utilities::post( 'initial_message_toggle' );

		// sign Message Text
		$widget['initial_message_text'] = $initial_message_text;

		// retrieve Initial Message Mode
		$widget['initial_message_mode'] = EPHD_Utilities::post( 'initial_message_mode' );

		// sign Message Image URL
		$widget['initial_message_image_url'] = EPHD_Utilities::post( 'initial_message_image_url' );

		// retrieve custom options
		$widget = apply_filters( 'ephd_admin_widget_feature_option_sanitize', $widget );

		return $widget;
	}

	/**
	 * Retrieve updated Design data from request
	 *
	 * @param $designs_config
	 *
	 * @return array
	 */
	private static function get_updated_design_from_input( $designs_config ) {

		// get selected Colors Set
		$colors_set_id = EPHD_Utilities::post( 'colors_set' );

		// get selected Style Feature.
		$dialog_width_id = EPHD_Utilities::post( 'dialog_width' );

		// get selected theme settings
		return EPHD_Premade_Designs::get_premade_design( $colors_set_id, $dialog_width_id, $designs_config[EPHD_Config_Specs::DEFAULT_ID] );
	}

	/**
	 * Return sanitized Design data from request data
	 *
	 * @param $designs_config
	 * @return array
	 */
	private static function get_sanitized_design_from_input( $designs_config ) {

		// Labels
		$designs_config['faqs_top_tab'] = EPHD_Utilities::post( 'faqs_top_tab' );
		$designs_config['contact_us_top_tab'] = EPHD_Utilities::post( 'contact_us_top_tab' );
		$designs_config['welcome_title'] = EPHD_Utilities::post( 'welcome_title' );
		$designs_config['welcome_text'] = EPHD_Utilities::post( 'welcome_text' );
		$designs_config['search_input_placeholder'] = EPHD_Utilities::post( 'search_input_placeholder' );
		$designs_config['article_read_more_text'] = EPHD_Utilities::post( 'article_read_more_text' );
		$designs_config['search_results_title'] = EPHD_Utilities::post( 'search_results_title' );
		$designs_config['breadcrumb_home_text'] = EPHD_Utilities::post( 'breadcrumb_home_text' );
		$designs_config['breadcrumb_search_result_text'] = EPHD_Utilities::post( 'breadcrumb_search_result_text' );
		$designs_config['breadcrumb_article_text'] = EPHD_Utilities::post( 'breadcrumb_article_text' );
		$designs_config['found_faqs_tab_text'] = EPHD_Utilities::post( 'found_faqs_tab_text' );
		$designs_config['found_articles_tab_text'] = EPHD_Utilities::post( 'found_articles_tab_text' );
		$designs_config['found_posts_tab_text'] = EPHD_Utilities::post( 'found_posts_tab_text' );
		$designs_config['no_results_found_title_text'] = EPHD_Utilities::post( 'no_results_found_title_text' );
		$designs_config['protected_article_placeholder_text'] = EPHD_Utilities::post( 'protected_article_placeholder_text' );
		$designs_config['search_input_label'] = EPHD_Utilities::post( 'search_input_label' );
		$designs_config['no_result_contact_us_text'] = EPHD_Utilities::post( 'no_result_contact_us_text' );

		// Other config
		$designs_config['launcher_start_wait'] = EPHD_Utilities::post( 'launcher_start_wait' );

		// skip updating colors if predefined colors is selected
		$colors_set = EPHD_Utilities::post( 'colors_set' );
		if ( ! empty( $colors_set ) ) {
			return $designs_config;
		}

		// Colors
		$designs_config['launcher_background_color'] = EPHD_Utilities::post( 'launcher_background_color' );
		$designs_config['launcher_background_hover_color'] = EPHD_Utilities::post( 'launcher_background_hover_color' );
		$designs_config['launcher_icon_color'] = EPHD_Utilities::post( 'launcher_icon_color' );
		$designs_config['launcher_icon_hover_color'] = EPHD_Utilities::post( 'launcher_icon_hover_color' );
		$designs_config['background_color'] = EPHD_Utilities::post( 'background_color' );
		$designs_config['not_active_tab_color'] = EPHD_Utilities::post( 'not_active_tab_color' );
		$designs_config['tab_text_color'] = EPHD_Utilities::post( 'tab_text_color' );
		$designs_config['main_title_text_color'] = EPHD_Utilities::post( 'main_title_text_color' );
		$designs_config['welcome_title_color'] = EPHD_Utilities::post( 'welcome_title_color' );
		$designs_config['found_faqs_article_active_tab_color'] = EPHD_Utilities::post( 'found_faqs_article_active_tab_color' );
		$designs_config['found_faqs_article_tab_color'] = EPHD_Utilities::post( 'found_faqs_article_tab_color' );
		$designs_config['article_post_list_title_color'] = EPHD_Utilities::post( 'article_post_list_title_color' );
		$designs_config['article_post_list_icon_color'] = EPHD_Utilities::post( 'article_post_list_icon_color' );
		$designs_config['breadcrumb_color'] = EPHD_Utilities::post( 'breadcrumb_color' );
		$designs_config['breadcrumb_background_color'] = EPHD_Utilities::post( 'breadcrumb_background_color' );
		$designs_config['breadcrumb_arrow_color'] = EPHD_Utilities::post( 'breadcrumb_arrow_color' );
		$designs_config['faqs_qa_border_color'] = EPHD_Utilities::post( 'faqs_qa_border_color' );
		$designs_config['faqs_question_text_color'] = EPHD_Utilities::post( 'faqs_question_text_color' );
		$designs_config['faqs_question_background_color'] = EPHD_Utilities::post( 'faqs_question_background_color' );
		$designs_config['faqs_question_active_text_color'] = EPHD_Utilities::post( 'faqs_question_active_text_color' );
		$designs_config['faqs_question_active_background_color'] = EPHD_Utilities::post( 'faqs_question_active_background_color' );
		$designs_config['faqs_answer_text_color'] = EPHD_Utilities::post( 'faqs_answer_text_color' );
		$designs_config['faqs_answer_background_color'] = EPHD_Utilities::post( 'faqs_answer_background_color' );
		$designs_config['single_article_read_more_text_color'] = EPHD_Utilities::post( 'single_article_read_more_text_color' );
		$designs_config['single_article_read_more_text_hover_color'] = EPHD_Utilities::post( 'single_article_read_more_text_hover_color' );
		$designs_config['back_text_color'] = EPHD_Utilities::post( 'back_text_color' );
		$designs_config['back_text_color_hover_color'] = EPHD_Utilities::post( 'back_text_color_hover_color' );
		$designs_config['back_background_color'] = EPHD_Utilities::post( 'back_background_color' );
		$designs_config['back_background_color_hover_color'] = EPHD_Utilities::post( 'back_background_color_hover_color' );
		$designs_config['contact_submit_button_color'] = EPHD_Utilities::post( 'contact_submit_button_color' );
		$designs_config['contact_submit_button_hover_color'] = EPHD_Utilities::post( 'contact_submit_button_hover_color' );
		$designs_config['contact_submit_button_text_color'] = EPHD_Utilities::post( 'contact_submit_button_text_color' );
		$designs_config['contact_submit_button_text_hover_color'] = EPHD_Utilities::post( 'contact_submit_button_text_hover_color' );
		$designs_config['contact_acceptance_background_color'] = EPHD_Utilities::post( 'contact_acceptance_background_color' );

		return $designs_config;
	}

	/**
	 * Return sanitized Contact Form data from request data
	 *
	 * @param $contact_forms_config
	 * @return array
	 */
	private static function get_sanitized_contact_forms_from_input( $contact_forms_config ) {

		$contact_forms_config['contact_title_header'] = EPHD_Utilities::post( 'contact_title_header' );
		$contact_forms_config['contact_title'] = EPHD_Utilities::post( 'contact_title' );
		$contact_forms_config['contact_name_text'] = EPHD_Utilities::post( 'contact_name_text' );
		$contact_forms_config['contact_user_email_text'] = EPHD_Utilities::post( 'contact_user_email_text' );
		$contact_forms_config['contact_subject_text'] = EPHD_Utilities::post( 'contact_subject_text' );
		$contact_forms_config['contact_comment_text'] = EPHD_Utilities::post( 'contact_comment_text' );
		$contact_forms_config['contact_acceptance_text'] = EPHD_Utilities::post( 'contact_acceptance_text', '', 'wp_editor' );
		$contact_forms_config['contact_acceptance_title'] = EPHD_Utilities::post( 'contact_acceptance_title' );
		$contact_forms_config['contact_button_title'] = EPHD_Utilities::post( 'contact_button_title' );
		$contact_forms_config['contact_success_message'] = EPHD_Utilities::post( 'contact_success_message', '', 'wp_editor' );

		return $contact_forms_config;
	}

	/**
	 * Retrieve updated Global config from request
	 *
	 * @param $global_config
	 *
	 * @return array|null
	 */
	private static function get_sanitized_global_form_from_input( $global_config ) {

		$global_config_specs = EPHD_Config_Specs::get_fields_specification( EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME );

		/* TODO hide for now
		// retrieve Desktop Width
		$global_config['container_desktop_width'] = EPHD_Utilities::post( 'container_desktop_width' );

		// retrieve Table Width
		$global_config['container_tablet_width'] = EPHD_Utilities::post( 'container_tablet_width' );

		// retrieve Tablet Break Point
		$global_config['tablet_break_point'] = EPHD_Utilities::post( 'tablet_break_point' );
		*/

		// retrieve Mobile Break Point
		$global_config['mobile_break_point'] = EPHD_Utilities::post( 'mobile_break_point' );

		// retrieve Preview Mode
		$global_config['preview_post_mode'] = EPHD_Utilities::post( 'preview_post_mode' );

		if ( EPHD_KB_Core_Utilities::is_kb_or_amag_enabled() ) {
			$global_config['preview_kb_mode'] = EPHD_Utilities::post( 'preview_kb_mode' );
		}

		// retrieve Launcher Mode
		$global_config['launcher_mode'] = EPHD_Utilities::post( 'launcher_mode' );

		// retrieve Launcher Icon
		$global_config['launcher_icon'] = EPHD_Utilities::post( 'launcher_icon' );

		// retrieve Launcher Location
		$global_config['launcher_location'] = EPHD_Utilities::post( 'launcher_location' );

		// retrieve Launcher Text
		$global_config['launcher_text'] = EPHD_Utilities::post( 'launcher_text' );

		// retrieve Launcher Bottom Distance
		$global_config['launcher_bottom_distance'] = EPHD_Utilities::post( 'launcher_bottom_distance' );

		// retrieve Show/Hide Launcher Powered By Text
		$global_config['launcher_powered_by'] = EPHD_Utilities::post( 'launcher_powered_by' );

		// retrieve Launcher Tabs Mode
		$global_config['dialog_display_mode'] = EPHD_Utilities::post( 'dialog_display_mode' );

		// retrieve selected Style Feature
		$dialog_width_id = EPHD_Utilities::post( 'dialog_width' );

		// retrieve Name Input
		$global_config['contact_name_toggle'] = EPHD_Utilities::post( 'contact_name_toggle' );

		// retrieve Subject Input
		$global_config['contact_subject_toggle'] = EPHD_Utilities::post( 'contact_subject_toggle' );

		// retrieve Acceptance Checkbox
		$global_config['contact_acceptance_checkbox'] = EPHD_Utilities::post( 'contact_acceptance_checkbox' );

		// retrieve Acceptance Title toggle
		$global_config['contact_acceptance_title_toggle'] = EPHD_Utilities::post( 'contact_acceptance_title_toggle' );

		// retrieve logo image url
		$global_config['logo_image_url'] = EPHD_Utilities::post( 'logo_image_url' );

		// get selected theme settings
		return EPHD_Premade_Designs::get_premade_global_config( $dialog_width_id, $global_config );
	}

	/**
	 * Perform search for certain type of Locations
	 */
	public function search_locations() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve post type
		$locations_type = EPHD_Utilities::post( 'locations_type' );
		if ( empty( $locations_type ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 78 ) );
		}

		// retrieve search value
		$search_value = EPHD_Utilities::post( 'search_value' );

		// retrieve excluded Location ids
		$excluded_ids = EPHD_Utilities::post( 'excluded_ids', [] );
		if ( ! is_array( $excluded_ids ) ) {
			$excluded_ids = array();
		}

		$widgets_page_handler = EPHD_Widgets_Display::get_widgets_page_handler();
		if ( is_wp_error( $widgets_page_handler ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 77 ) );
		}

		wp_die( json_encode( array(
			'status'        => 'success',
			'message'       => 'success',
			'locations'     => $widgets_page_handler->get_available_locations_list( $locations_type, true, $search_value, $excluded_ids ),
		) ) );
	}

	/**
	 * Copy current Widget Design to another Widget Design
	 */
	public function copy_design_to() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve configuration for all Designs
		$designs_config = ephd_get_instance()->designs_config_obj->get_config( true );
		if ( is_wp_error( $designs_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 80 ) );
		}

		// retrieve target Design id - only existing Design allowed
		$target_design_id = (int)EPHD_Utilities::post( 'target_design_id' );
		if ( empty( $target_design_id ) || ! isset( $designs_config[$target_design_id] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 81 ) );
		}

		// retrieve Design id - either existing Design or default Design (when copy from a newly creating Widget that is not saved yet)
		$current_design_id = (int)EPHD_Utilities::post( 'current_design_id' );
		if ( ! isset( $designs_config[$current_design_id] ) && $current_design_id != 1 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 82 ) );
		}

		// retrieve current Design
		$current_design = isset( $designs_config[$current_design_id] )
			? $designs_config[$current_design_id]
			: $designs_config[EPHD_Config_Specs::DEFAULT_ID];

		// copy current Widget Design to the target Widget Design
		$designs_config[$target_design_id] = $current_design;

		// copy the Design id
		$designs_config[$target_design_id]['design_id'] = $target_design_id;

		// update Designs configuration
		$updated_designs_config = ephd_get_instance()->designs_config_obj->update_config( $designs_config );
		if ( is_wp_error( $updated_designs_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Designs configuration. (83)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 83, $updated_designs_config ) );
		}

		wp_die( json_encode( array(
			'status'    => 'success',
			'message'   => esc_html__( 'Design Copied', 'help-dialog' ),
		) ) );
	}

	/**
	 * Save setting input from Tiny MCE editor
	 */
	public function tiny_mce_input_save() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve option name - allow only certain option names
		$option_name = EPHD_Utilities::post( 'option_name' );
		if ( ! in_array( $option_name, ['no_results_found_content_html'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 86 ) );
		}

		// retrieve Design id
		$design_id = (int)EPHD_Utilities::post( 'design_id', -1 );
		if ( $design_id < 1 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 87 ) );
		}

		// retrieve configuration for all Designs
		$designs_config = ephd_get_instance()->designs_config_obj->get_config( true );
		if ( is_wp_error( $designs_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 88 ) );
		}

		if ( empty( $designs_config[$design_id] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 90 ) );
		}

		// retrieve specification for Design fields
		$design_specs = EPHD_Config_Specs::get_fields_specification( EPHD_Config_DB::EPHD_DESIGNS_CONFIG_NAME );

		// retrieve option name - allow only certain option names
		$max_option_length = intval( $design_specs[$option_name]['max'] );
		$option_value = EPHD_Utilities::post( 'option_value', '', $design_specs[$option_name]['type'], $max_option_length );

		// update option
		$designs_config[$design_id][$option_name] = $option_value;

		// update Designs configuration
		$updated_designs_config = ephd_get_instance()->designs_config_obj->update_config( $designs_config );
		if ( is_wp_error( $updated_designs_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Designs configuration. (89)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 89, $updated_designs_config ) );
		}

		wp_die( json_encode( array(
			'status'    => 'success',
			'message'   => esc_html__( 'Configuration Saved', 'help-dialog' ),
		) ) );
	}
}
