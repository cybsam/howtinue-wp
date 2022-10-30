<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handle Help Dialog data
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 */
class EPHD_Help_Dialog_Handler {

	const HELP_DIALOG_STATUS_PUBLIC = 'published';
	const HELP_DIALOG_STATUS_DRAFT = 'draft';

	/**
	 * Add default FAQs
	 */
	public static function add_default_faqs() {

		// retrieve all Widgets configuration - including default one; ignore errors
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config();

		// get Home Page ID or default ID from specs if it is not a real page
		$page_on_front = get_option( 'page_on_front' );
		$home_page_id = empty( $page_on_front ) ? EPHD_Config_Specs::HOME_PAGE : $page_on_front;

		// update locations
		$widgets_config[EPHD_Config_Specs::DEFAULT_ID]['location_pages_list'] = [$home_page_id];

		// create demo questions
		$demo_questions = self::get_demo_questions();
		foreach ( $demo_questions as $question ) {
			$question = self::create_sample_faq( $question->question, $question->answer );
			if ( empty( $question ) ) {
				continue;
			}

			array_push( $widgets_config[EPHD_Config_Specs::DEFAULT_ID]['faqs_sequence'], $question->faq_id );
		}

		// save Widgets configuration
		$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_widgets_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Widgets configuration. (05)' );
		}
	}

	/**
	 * Create demo Widgets
	 */
	public static function create_demo_widgets() {

		// retrieve all Widgets configuration - ignore errors
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config();

		// do not overwrite existing Widgets
		if ( count( $widgets_config ) > 1 ) {
			return;
		}

		// retrieve Global configuration - ignore errors
		$global_config = ephd_get_instance()->global_config_obj->get_config();

		// configuration for demo Widgets
		$demo_widgets_config = array(

			// Pricing Page
			array(
				'widget_name'   => __( 'Pricing Page - EXAMPLE', 'help-dialog' ),
			),

			// Documentation Page
			array(
				'widget_name'   => __( 'Documentation Page - EXAMPLE', 'help-dialog' ),
			),
		);

		// insert demo Widgets
		foreach ( $demo_widgets_config as $demo_widget ) {

			$widget_id = $global_config['last_widget_id'];
			$global_config['last_widget_id'] = ++$widget_id;
			$demo_widget['widget_id'] = $widget_id;

			// set default Widget configuration
			$widgets_config[$widget_id] = $widgets_config[EPHD_Config_Specs::DEFAULT_ID];

			// set locations - make sure the new Widget does not inherit locations from default Widget
			$widgets_config[$widget_id]['location_pages_list'] = isset( $demo_widget['location_pages_list'] ) ? $demo_widget['location_pages_list'] : [];
			$widgets_config[$widget_id]['location_posts_list'] = isset( $demo_widget['location_posts_list'] ) ? $demo_widget['location_posts_list'] : [];
			$widgets_config[$widget_id]['location_cpts_list'] = isset( $demo_widget['location_cpts_list'] ) ? $demo_widget['location_cpts_list'] : [];

			// update Widget id
			$widgets_config[$widget_id]['widget_id'] = $widget_id;

			// update Widget name
			$widgets_config[$widget_id]['widget_name'] = $demo_widget['widget_name'];

			// set Design id
			$widgets_config[$widget_id]['design_id'] = EPHD_Config_Specs::DEFAULT_ID;

			// enable search input
			$widgets_config[$widget_id]['search_option'] = 'show_search';
		}

		// save Widgets configuration
		$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_widgets_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Widgets configuration. (05)' );
		}

		// update last Widget id in Global configuration
		$updated_global_config = ephd_get_instance()->global_config_obj->update_config( $global_config );
		if ( is_wp_error( $updated_global_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Global configuration. (10)' );
		}
	}

	/**
	 * Return array of FAQs for demo purpose only
	 *
	 * @return array
	 */
	private static function get_demo_questions() {

		$faqs = [];

		$faqs_config = [
			[
				'question' => __( 'Where can I find documentation?', 'help-dialog' ),
				'answer' => esc_html__( 'EXAMPLE', 'help-dialog' ) . ' - ' . esc_html__( 'We have a detailed knowledge base about our product and services', 'help-dialog' ) .
				            ' <a href="https://www.helpdialog.com/documentation/" target="_blank">' . esc_html__( 'here', 'help-dialog' ) . '<span class="ephdfa ephdfa-external-link"></span></a>',
			],
			[
				'question' => __( 'Do you offer any discounts?', 'help-dialog' ),
				'answer' => __( 'EXAMPLE', 'help-dialog' ) . ' - ' . __( 'Currently, we have a sale for 20% off all regular priced merchandise in the store.', 'help-dialog' ),
			],
			[
				'question' => __( 'What payment methods do you accept?', 'help-dialog' ),
				'answer' => __( 'EXAMPLE', 'help-dialog' ) . ' - '. __( 'We accept all main methods of payments: VISA, Mastercard, and PayPal.', 'help-dialog' ),
			],
		];

		foreach ( $faqs_config as $faq_config ) {
			$new_faq = new stdClass();
			$new_faq->question = $faq_config['question'];
			$new_faq->answer = $faq_config['answer'];
			$faqs[] = $new_faq;
		}

		return $faqs;
	}

	/**
	 * Create sample FAQ
	 *
	 * @param $question
	 * @param $answer
	 *
	 * @return object|null
	 */
	private static function create_sample_faq( $question, $answer ) {

		// create question
		$faqs_db_handler = new EPHD_FAQs_Articles_DB();
		$faq = $faqs_db_handler->insert_faq( 0, 0, $question, $answer, 'publish' );
		if ( is_wp_error( $faq ) || empty( $faq ) ) {
			EPHD_Logging::add_log( 'Could not insert post for a new FAQ', $faq );
			return null;
		}

		return $faq;
	}
}
