<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Help Dialog Channels page
 *
 * @copyright   Copyright (C) 2022, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Channels_Page {

	private $message = array(); // error/warning/success messages

	private $global_config;
	private $channels_config;
	private $channels_specs;

	public function __construct( $channels_config ) {
        // Global config and specs
		$this->global_config = ephd_get_instance()->global_config_obj->get_config( true );
	}

	/**
	 * Displays the Help Dialog Channels page with top panel
	 */
	public function display_page() {

		if ( is_wp_error( $this->global_config ) ) {
			EPHD_HTML_Admin::display_config_error_page( $this->global_config );
			return;
		}

		$admin_page_views = $this->get_regular_view_config();
		if ( empty( $admin_page_views ) ) {
			EPHD_HTML_Admin::display_config_error_page( 'No admin page views' );
			return;
		}

		EPHD_HTML_Admin::admin_page_css_missing_message( true ); ?>

		<!-- Admin Page Wrap -->
		<div id="ephd-admin-page-wrap">

            <div class="ephd-channels-page-container"> <?php

				/**
				 * ADMIN HEADER
				 */
				EPHD_HTML_Admin::admin_header();

                /**
	             * LIST OF SETTINGS IN TABS
	             */
	            EPHD_HTML_Admin::admin_settings_tab_content( $admin_page_views, 'ephd-config-wrapper' ); ?>

                <div class="ephd-bottom-notice-message fadeOutDown"></div>

            </div>

        </div>        <?php

		/**
		 * Show any notifications
		 */
		foreach ( $this->message as $class => $message ) {
			echo  EPHD_HTML_Forms::notification_box_bottom( $message, '', $class );
		}
	}

	/**
	 * Return HTML for editor form of a single widget
	 *
	 * @return false|string
	 */
	public function get_channel_form() {

		ob_start();

		EPHD_HTML_Admin::display_admin_form_header( array(
			'icon_html'     => EPHD_HTML_Admin::get_hd_icon_html( 'ephd-admin__form-title-icon' ),
			'title'         => '',
			'title_desc'    => __( 'Chat Channels', 'help-dialog' ),
			'desc'          => __( 'Settings', 'help-dialog' ),
			'actions_html'  => self::get_form_actions_html(),
		) );

		$tabs_config = [
			'channels' => [
				'tabs'  => [],
			],
		];

		// TAB: WhatsApp
		$tabs_config['channels']['tabs'][] = array(
			'title'     => __( 'Whatsapp', 'help-dialog' ),
			'icon'      => 'ephd-cp-icon ephd-cp-icon__whatsapp',
			'key'       => 'whatsapp',
			'active'    => true,
			'contents'  => array(
				array(
					'title'         => __( 'Settings', 'help-dialog' ),
					'desc'          => __( 'whatsapp description', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_channel_whatsapp(),
				),
			),
		);

		// TAB: Slack
		$tabs_config['channels']['tabs'][] = array(
			'title'     => __( 'Slack', 'help-dialog' ),
			'icon'      => 'ephd-cp-icon ephd-cp-icon__slack',
			'key'       => 'slack',
			'active'    => false,
			'contents'  => array(
				array(
					'title'         => __( 'Settings', 'help-dialog' ),
					'desc'          => __( 'slack description', 'help-dialog' ),
					'body_html'     => $this->get_tab_content_channel_slack(),
				),
			),
		); ?>

		<!-- Channels Form Body -->
		<div class="ephd-cp__channels-form__body">    <?php
			EPHD_HTML_Admin::display_admin_form_tabs( $tabs_config );   ?>
		</div><!-- End Channels Form Body --> <?php

		return ob_get_clean();
	}

	/**
	 * Get configuration array for Channels views of Help Dialog admin page
	 *
	 * @return array
	 */
	private function get_regular_view_config() {

		/**
		 * VIEW: Channels
		 */
		$views_config[] = array(

			// Shared
			'active' => true,
			'list_key' => 'channels',

			// Boxes List
			'boxes_list' => [
				[
					'class' => 'ephd-cp__channels-form',
					'html'  => $this->get_channel_form(),
				]
			]
		);

		return $views_config;
	}

	/**
	 * Return HTML for Whatsapp tab
	 *
	 * @return false|string
	 */
	private function get_tab_content_channel_whatsapp() {

		ob_start();
		echo 'hello Whatsapp';
		return ob_get_clean();
	}


	/**
	 * Return HTML for Slack tab
	 *
	 * @return false|string
	 */
	private function get_tab_content_channel_slack() {

		ob_start();
		echo 'hello Slack';
		return ob_get_clean();
	}

	/**
	 * Get HTML of Channels form
	 *
	 * @return false|string
	 */
	private static function get_form_actions_html() {

		ob_start(); ?>
        <div class="ephd-cp__channels-action__save-wrap">
            <div class="ephd-cp__channels-action__save-btns-wrap"><?php
				EPHD_HTML_Elements::submit_button_v2( __( 'Save', 'help-dialog' ), 'ephd_update_channels', 'ephd-cp__channels-action__publish-btn', '', false, '', 'ephd-success-btn' ); ?>
            </div>
        </div>  <?php

		return ob_get_clean();
	}
}
