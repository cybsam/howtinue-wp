<?php

/**
 * Handles settings specifications.
 */
class EPHD_Config_Specs {
	
	const DEFAULT_ID = 1;
	const HOME_PAGE = 0;

	public static function get_defaults() {
		return array(
			'label'         => __( 'Label', 'help-dialog' ),
			'type'          => EPHD_Input_Filter::TEXT,
			'mandatory'     => true,
			'max'           => '20',
			'min'           => '3',
			'options'       => array(),
			'internal'      => false,
			'default'       => '',
			'is_pro'        => false
		);
	}

	/**
	 * Return fields specification for configuration accordingly to config name
	 *
	 * @param string $config_name
	 *
	 * @return array|array[]
	 */
	public static function get_fields_specification( $config_name='' ) {

		switch ( $config_name ) {

			case EPHD_Widgets_DB::EPHD_WIDGETS_CONFIG_NAME:
				return self::get_widget_fields_specification();

			case EPHD_Config_DB::EPHD_DESIGNS_CONFIG_NAME:
				return self::get_design_fields_specification();

			case EPHD_Config_DB::EPHD_CONTACT_FORMS_CONFIG_NAME:
				return self::get_contact_form_fields_specification();

			case EPHD_Config_DB::EPHD_NOTIFICATION_RULES_CONFIG_NAME:
				return self::get_notification_rule_fields_specification();

			case EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME:
			default:
				return self::get_global_fields_specification();
		}
	}

	public static function get_all_specs() {
		return self::get_widget_fields_specification() + self::get_design_fields_specification() +
		       self::get_contact_form_fields_specification() + self::get_notification_rule_fields_specification() + self::get_global_fields_specification();
	}

	/**
	 * Defines data needed for display, initialization and validation/sanitation of settings
	 *
	 * ALL FIELDS ARE MANDATORY by default ( otherwise use 'mandatory' => false )
	 *
	 * @return array with settings specification
	 */
	public static function get_global_fields_specification() {

		// all default settings are listed here
		return array(
			'logo_image_url'                            => array(
				'label'     => __( 'Logo Image URL', 'help-dialog' ),
				'name'      => 'logo_image_url',
				'size'      => '60',
				'max'       => '300',
				'min'       => '0',
				'mandatory' => false,
				'type'      => EPHD_Input_Filter::TEXT,
				'default'   => Echo_Help_Dialog::$plugin_url . 'img/logo-placement.png'
			),
			'logo_image_width'                      => array(
				'label'       => __( 'Logo Width (px)', 'help-dialog' ),
				'name'        => 'logo_image_width',
				'max'         => 120,
				'min'         => 1,
				'type'        => EPHD_Input_Filter::NUMBER,
				'default'     => 70
			),

			// sizes
			'container_desktop_width'                   => array(
				'label'     => __( 'Desktop Width (px)', 'help-dialog' ),
				'name'      => 'container_desktop_width',
				'max'       => '1000',
				'min'       => '0',
				'type'      => EPHD_Input_Filter::NUMBER,
				'default'   => '400'
			),
			'container_tablet_width'                    => array(
				'label'     => __( 'Width (px)', 'help-dialog' ),
				'name'      => 'container_tablet_width',
				'max'       => '1000',
				'min'       => '0',
				'type'      => EPHD_Input_Filter::NUMBER,
				'default'   => '400'
			),
			'tablet_break_point'                        => array(
				'label'     => __( 'Break Point (px)', 'help-dialog' ),
				'name'      => 'tablet_break_point',
				'max'       => 2000,
				'min'       => 100,
				'type'      => EPHD_Input_Filter::NUMBER,
				'style'     => 'small',
				'default'   => 1025
			),
			'mobile_break_point'                        => array(
				'label'     => __( 'Mobile Break Point (px)', 'help-dialog' ),
				'name'      => 'mobile_break_point',
				'max'       => 2000,
				'min'       => 100,
				'type'      => EPHD_Input_Filter::NUMBER,
				'style'     => 'small',
				'default'   => 768
			),
			'main_title_font_size'                      => array(
				'label'       => __( 'Main Title Font Size (px)', 'help-dialog' ),
				'name'        => 'main_title_font_size',
				'max'         => 40,
				'min'         => 1,
				'type'        => EPHD_Input_Filter::NUMBER,
				'default'     => 20
			),

			// IDs sequence
			'last_widget_id'                            => array(
				'label'     => __( 'Last Widget ID', 'help-dialog' ),
				'name'      => 'last_widget_id',
				'max'       => 999999999999999,
				'min'       => self::DEFAULT_ID,
				'type'      => EPHD_Input_Filter::NUMBER,
				'internal'  => true,
				'default'   => self::DEFAULT_ID
			),
			'last_design_id'                            => array(
				'label'     => __( 'Last Design ID', 'help-dialog' ),
				'name'      => 'last_design_id',
				'max'       => 999999999999999,
				'min'       => self::DEFAULT_ID,
				'type'      => EPHD_Input_Filter::NUMBER,
				'internal'  => true,
				'default'   => self::DEFAULT_ID
			),
			'last_contact_form_id'                      => array(
				'label'     => __( 'Last Contact Form ID', 'help-dialog' ),
				'name'      => 'last_contact_form_id',
				'max'       => 999999999999999,
				'min'       => self::DEFAULT_ID,
				'type'      => EPHD_Input_Filter::NUMBER,
				'internal'  => true,
				'default'   => self::DEFAULT_ID
			),

			// preview
			'preview_post_mode'                             => array(
				'label'     => __( 'Preview Post Mode', 'help-dialog' ),
				'name'      => 'preview_post_mode',
				'type'      => EPHD_Input_Filter::SELECTION,
				'is_pro'    => false,
				'options'   => array(
					'direct' => __( 'Direct to Post', 'help-dialog' ),
					'excerpt' => __( 'Excerpt', 'help-dialog' )
				),
				'default'   => 'excerpt'
			),
			'preview_kb_mode'                             => array(
				'label'     => __( 'Preview KB Article Mode', 'help-dialog' ),
				'name'      => 'preview_kb_mode',
				'type'      => EPHD_Input_Filter::SELECTION,
				'is_pro'    => false,
				'options'   => array(
					'iframe'      => __( 'Iframe', 'help-dialog' ),
					'excerpt' => __( 'Excerpt', 'help-dialog' ),
					'direct' => __( 'Direct to Article', 'help-dialog' )
				),
				'default'   => 'iframe'
			),

			// launcher
			'launcher_mode'                             => array(
				'label'     => __( 'Launcher Mode', 'help-dialog' ),
				'name'      => 'launcher_mode',
				'type'      => EPHD_Input_Filter::SELECTION,
				'is_pro'    => true,
				'options'   => array(
					'icon'      => __( 'Icon', 'help-dialog' ),
					'icon_text' => __( 'Icon + Text', 'help-dialog' ),
					'text_icon' => __( 'Text + Icon', 'help-dialog' )
				),
				'default'   => 'icon'
			),
			'launcher_icon'                             => array(
				'label'     => __( 'Launcher Icon', 'help-dialog' ),
				'name'      => 'launcher_icon',
				'type'      => EPHD_Input_Filter::SELECTION,
				'options'   => array(
					'ep_font_icon_help_dialog'   => __( 'HD Icon (default)', 'help-dialog' ),
					'comments-o'   => __( 'Icon 1 (default)', 'help-dialog' ),
					'comments'     => __( 'Icon 2', 'help-dialog' ),
					'commenting-o' => __( 'Icon 3', 'help-dialog' ),
					'commenting'   => __( 'Icon 4', 'help-dialog' ),
					'comment-o'    => __( 'Icon 5', 'help-dialog' ),
				),
				'default'   => 'comments-o'
			),
			'launcher_text'                             => array(
				'label'     => __( 'Launcher Text', 'help-dialog' ),
				'name'      => 'launcher_text',
				'size'      => '30',
				'max'       => '300',
				'min'       => '1',
				'type'      => EPHD_Input_Filter::TEXT,
				'is_pro'    => true,
				'default'   => __( 'Need help?', 'help-dialog' )
			),
			'launcher_location'                         => array(
				'label'     => __( 'Launcher Location', 'help-dialog' ),
				'name'      => 'launcher_location',
				'type'      => EPHD_Input_Filter::SELECTION,
				'options'   => array(
					'left'  => __( 'Left', 'help-dialog' ),
					'right' => __( 'Right ', 'help-dialog' ),
				),
				'default'   => 'right'
			),
			'launcher_bottom_distance'                  => array(
				'label'     => __( 'Launcher Bottom Distance (px)', 'help-dialog' ),
				'name'      => 'launcher_bottom_distance',
				'max'       => '2000',
				'min'       => '0',
				'type'      => EPHD_Input_Filter::NUMBER,
				'style'     => 'small',
				'default'   => '10'
			),
			'launcher_powered_by'                       => array(
				'label'     => __( 'Powered By Text', 'help-dialog' ),
				'name'      => 'launcher_powered_by',
				'type'      => EPHD_Input_Filter::SELECTION,
				'is_pro'    => true,
				'options'   => array(
					'show' => __( 'Show', 'help-dialog' ),
					'hide' => __( 'Hide', 'help-dialog' ),
				),
				'default'   => 'show'
			),

			// analytics
			'analytic_count_launcher_impression'        => array(
				'label'     => __( 'Count Launcher Impression', 'help-dialog' ),
				'name'      => 'analytic_count_launcher_impression',
				'type'      => EPHD_Input_Filter::CHECKBOX,
				'options'   => array(
					'off' => __( 'Disable Impression Counting', 'help-dialog' ),
					'on'  => __( 'Enable Impression Counting ', 'help-dialog' ),
				),
				'default'   => 'off'
			),
			'analytic_excluded_roles'                  => array(
				'label'       => __( 'Exclude Users', 'help-dialog' ),
				'name'        => 'widget_status',
				'type'        => EPHD_Input_Filter::CHECKBOXES_MULTI_SELECT,
				'options'     => array( 'administrator', 'editor', 'author', 'contributor', 'subscriber' ),
				'default'     => array( 'administrator', 'editor', 'author', 'contributor' )
			),

			'kb_article_hidden_classes'                => array(
				'label'     => __( 'Classes to Hide Content', 'help-dialog' ),
				'name'      => 'kb_article_hidden_classes',
				'max'       => '1000',
				'min'       => '0',
				'mandatory' => false,
				'type'      => EPHD_Input_Filter::TEXT,
				'default'   => ''
			),
			'dialog_width'                            => array(
				'label'       => __( 'Dialog Width', 'help-dialog' ),
				'name'        => 'dialog_width',
				'type'        => EPHD_Input_Filter::SELECTION,
				'options'     => array(
					'small'  => __( 'Small', 'help-dialog' ),
					'medium' => __( 'Medium', 'help-dialog' ),
					'large'  => __( 'Large', 'help-dialog' )
				),
				'default'     => 'medium'
			),

			// Contact Form
			'contact_submission_email'                  => array(  // TODO FUTURE (move or keep as global / default one)
               'label'        => __( 'Email for User Submissions', 'help-dialog' ),
               'name'         => 'contact_submission_email',
               'size'         => '30',
               'max'          => '50',
               'min'          => '0',
               'mandatory'    => false,
               'type'         => EPHD_Input_Filter::EMAIL,
               'default'      => ''
			),
			'contact_name_toggle'                       => array(
				'label'     => __( 'Name Input', 'help-dialog' ),
				'name'      => 'contact_name_toggle',
				'type'      => EPHD_Input_Filter::SELECTION,
				'options'   => array(
					'off' => __( 'Disable', 'help-dialog' ),
					'on'  => __( 'Enable', 'help-dialog' ),
				),
				'default'   => 'on'
			),
			'contact_subject_toggle'                    => array(
				'label'     => __( 'Subject Input', 'help-dialog' ),
				'name'      => 'contact_subject_toggle',
				'type'      => EPHD_Input_Filter::SELECTION,
				'options'   => array(
					'off' => __( 'Disable', 'help-dialog' ),
					'on'  => __( 'Enable', 'help-dialog' ),
				),
				'default'   => 'on'
			),
			'contact_acceptance_checkbox'               => array(
				'label'     => __( 'Acceptance Checkbox', 'help-dialog' ),
				'name'      => 'contact_acceptance_checkbox',
				'type'      => EPHD_Input_Filter::SELECTION,
				'options'   => array(
					'off' => __( 'Disable', 'help-dialog' ),
					'on'  => __( 'Enable', 'help-dialog' ),
				),
				'default'   => 'off'
			),
			'contact_acceptance_title_toggle'           => array(
				'label'     => __( 'Acceptance Checkbox Title', 'help-dialog' ),
				'name'      => 'contact_acceptance_title_toggle',
				'type'      => EPHD_Input_Filter::SELECTION,
				'options'   => array(
					'off' => __( 'Disable', 'help-dialog' ),
					'on'  => __( 'Enable', 'help-dialog' ),
				),
				'default'   => 'off'
			),

			// HD Launcher Tabs // TODO future: we might have per-widget setting if there is need for it
			'dialog_display_mode'                       => array(
				'label'     => __( 'Display Mode', 'help-dialog' ),
				'name'      => 'dialog_display_mode',
				'type'      => EPHD_Input_Filter::SELECTION,
				'options'   => array(
					'both'    => __( 'Both', 'help-dialog' ),
					'faqs'    => __( 'FAQs', 'help-dialog' ),
					'contact' => __( 'Contact Us', 'help-dialog' )
				),
				'default'   => 'both'
			),

			'private_faqs_included_roles'               => array(
				'label'       => __( 'Included Users for Private FAQ Articles', 'help-dialog' ),
				'name'        => 'private_faqs_included_roles',
				'type'        => EPHD_Input_Filter::CHECKBOXES_MULTI_SELECT,
				'options'     => array( 'administrator', 'editor', 'author', 'contributor', 'subscriber' ),
				'default'     => array( 'administrator', 'editor' )
			),
		);
	}

	/**
	 * Fields specifications for Widget
	 *
	 * @return array[]
	 */
	private static function get_widget_fields_specification() {

		// all default settings are listed here
		return array(
			'widget_id'                                 => array(
				'label'       => __( 'Widget ID', 'help-dialog' ),
				'name'        => 'widget_id',
				'max'         => 1000000000,
				'min'         => 0,
				'type'        => EPHD_Input_Filter::NUMBER,
				'style'       => 'small',
				'default'     => self::DEFAULT_ID
			),
			'widget_name'                               => array(
				'label'       => __( 'Nickname', 'help-dialog' ),
				'name'        => 'widget_name',
				'size'        => '30',
				'max'         => '100',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Home Page', 'help-dialog' )
			),
			'widget_status'                             => array(
				'label'       => __( 'Visibility', 'help-dialog' ),
				'name'        => 'widget_status',
				'type'        => EPHD_Input_Filter::SELECTION,
				'options'     => array(
					'draft'     => __( 'Draft', 'help-dialog' ),
					'published' => __( 'Published', 'help-dialog' ),
				),
				'default'     => 'draft'
			),
			'design_id'                                 => array(
				'label'       => __( 'Widget Appearance', 'help-dialog' ),
				'name'        => 'design_id',
				'max'         => 1000000000,
				'min'         => 0,
				'type'        => EPHD_Input_Filter::NUMBER,
				'style'       => 'small',
				'default'     => self::DEFAULT_ID
			),
			'contact_form_id'                           => array(
				'label'       => __( 'Widget Contact Form', 'help-dialog' ),
				'name'        => 'contact_form_id',
				'max'         => 1000000000,
				'min'         => 0,
				'type'        => EPHD_Input_Filter::NUMBER,
				'style'       => 'small',
				'default'     => self::DEFAULT_ID
			),
			'faqs_name'                                 => array(
				'label'       => __( 'FAQs', 'help-dialog' ),
				'name'        => 'faqs_name',
				'size'        => '30',
				'max'         => '100',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Default Questions', 'help-dialog' )
			),
			'search_option'                             => array(
				'label'       => __( 'Search Input Box', 'help-dialog' ),
				'name'        => 'search_option',
				'type'        => EPHD_Input_Filter::SELECTION,
				'options'     => array(
					'show_search'  => __( 'Show Search', 'help-dialog' ),
					'hide_search'  => __( 'Hide Search', 'help-dialog' ),
				),
				'default'     => 'show_search'
			),
			'search_posts'                              => array(
				'label'       => __( 'Search Posts', 'help-dialog' ),
				'name'        => 'search_posts',
				'type'        => EPHD_Input_Filter::SELECTION,
				'options'     => array(
					'off'  => __( 'Off', 'help-dialog' ),
					'on'   => __( 'On', 'help-dialog' ),
				),
				'default'     => 'off'
			),
			'search_kb'                                 => array(
				'label'       => __( 'Search Knowledge Base', 'help-dialog' ),
				'name'        => 'search_kb',
				'mandatory'   => false,
				'type'        => EPHD_Input_Filter::SELECTION,
				// do not automatically populate options here. do it in UI
				'default'     => 'off'
			),
			'location_pages_list'                       => array(
				'name'       => 'location_pages_list',
				'type'        => EPHD_Input_Filter::INTERNAL_ARRAY,
				'internal'    => true,
				'default'     => array()
			),
			'location_posts_list'                       => array(
				'name'       => 'location_posts_list',
				'type'        => EPHD_Input_Filter::INTERNAL_ARRAY,
				'internal'    => true,
				'default'     => array()
			),
			'location_cpts_list'                        => array(
				'name'       => 'location_cpts_list',
				'type'        => EPHD_Input_Filter::INTERNAL_ARRAY,
				'internal'    => true,
				'default'     => array()
			),
			'faqs_sequence'                             => array(
				'name'       => 'faqs_sequence',
				'type'        => EPHD_Input_Filter::INTERNAL_ARRAY,
				'internal'    => true,
				'default'     => array()
			),

			// initial message
			'initial_message_toggle'                    => array(
				'label'     => __( 'Initial Message', 'help-dialog' ),
				'name'      => 'initial_message_toggle',
				'type'      => EPHD_Input_Filter::SELECTION,
				'is_pro'    => true,
				'options'   => array(
					'show' => __( 'Show', 'help-dialog' ),
					'hide' => __( 'Hide', 'help-dialog' ),
				),
				'default'   => 'hide'
			),
			'initial_message_text'                       => array(
				'label'     => __( 'Message Text', 'help-dialog' ),
				'name'      => 'initial_message_text',
				'size'      => '60',
				'max'       => '300',
				'min'       => '0',
				'mandatory' => false,
				'type'      => EPHD_Input_Filter::WP_EDITOR,
				'is_pro'    => true,
				'default'   => __( 'Need help?', 'help-dialog' )
			),
			'initial_message_mode'                             => array(
				'label'     => __( 'Message Mode', 'help-dialog' ),
				'name'      => 'initial_message_mode',
				'type'      => EPHD_Input_Filter::SELECTION,
				'is_pro'    => true,
				'options'   => array(
					'text' => __( 'Text', 'help-dialog' ),
					'icon_text' => __( 'Text + Icon', 'help-dialog' )
				),
				'default'   => 'icon_text'
			),
			'initial_message_image_url'                  => array(
				'label'     => __( 'Message Image URL', 'help-dialog' ),
				'name'      => 'initial_message_image_url',
				'size'      => '60',
				'max'       => '300',
				'min'       => '0',
				'mandatory' => false,
				'type'      => EPHD_Input_Filter::TEXT,
				'is_pro'    => true,
				'default'   => Echo_Help_Dialog::$plugin_url . 'img/kb-icon.png'
			),
			'initial_message_id'                         => array(
				'label'     => __( 'Message ID', 'help-dialog' ),
				'name'      => 'initial_message_id',
				'max'       => 999999999999999,
				'min'       => 1,
				'type'      => EPHD_Input_Filter::NUMBER,
				'internal'  => true,
				'default'   => 1
			),
		);
	}

	/**
	 * Fields specifications for Design
	 *
	 * @return array[]
	 */
	// FUTURE: we could create a new one on SAVE if needed
	private static function get_design_fields_specification() {
		return array(
			'design_id'                                 => array(
				'label'       => '',
				'name'        => 'design_id',
				'max'         => 1000000000,
				'min'         => 1,
				'type'        => EPHD_Input_Filter::NUMBER,
				'style'       => 'small',
				'default'     => self::DEFAULT_ID
			),
			'design_name'                               => array(
				'label'       => __( 'Design Name', 'help-dialog' ),
				'name'        => 'design_name',
				'size'        => '30',
				'max'         => '100',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Default Appearance', 'help-dialog' )
			),
			'welcome_title'                              => array(
				'label'       => __( 'Welcome Title', 'help-dialog' ),
				'name'        => 'welcome_title',
				'size'        => '30',
				'max'         => '70',
				'min'         => '1',
				'mandatory'   => false,
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Welcome to Support', 'help-dialog' )
			),

			// - Top buttons
			'back_text_color'                           => array(
				'label'       => __( 'Text/Icon Color', 'help-dialog' ),
				'name'        => 'back_text_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#ffffff"
			),
			'back_text_color_hover_color'               => array(
				'label'       => __( 'Text/Icon Hover Color', 'help-dialog' ),
				'name'        => 'back_text_color_hover_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#ffffff"
			),
			'back_background_color'                     => array(
				'label'       => __( 'Background Color', 'help-dialog' ),
				'name'        => 'back_background_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#066fc0"
			),
			'back_background_color_hover_color'         => array(
				'label'       => __( 'Background Color Hover Color', 'help-dialog' ),
				'name'        => 'back_background_color_hover_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#066fc0"
			),
			'contact_us_top_tab'                        => array(
				'label'       => __( 'Contact Us Tab Text', 'help-dialog' ),
				'name'        => 'contact_us_top_tab',
				'size'        => '30',
				'max'         => '50',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Contact Us', 'help-dialog' )
			),

			// - Launcher
			'launcher_start_wait'                       => array(
				'label'       => __( 'Delay Displaying Launcher (sec)', 'help-dialog' ),
				'name'        => 'launcher_start_wait',
				'max'         => '500',
				'min'         => '0',
				'type'        => EPHD_Input_Filter::NUMBER,
				'default'     => '0'
			),
			'launcher_background_color'                 => array(
				'label'       => __( 'Background', 'help-dialog' ),
				'name'        => 'launcher_background_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#0f4874"
			),
			'launcher_background_hover_color'           => array(
				'label'       => __( 'Background Hover', 'help-dialog' ),
				'name'        => 'launcher_background_hover_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#a5a5a5"
			),
			'launcher_icon_color'                       => array(
				'label'       => __( 'Icon', 'help-dialog' ),
				'name'        => 'launcher_icon_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#ffffff"
			),
			'launcher_icon_hover_color'                 => array(
				'label'       => __( 'Icon Hover', 'help-dialog' ),
				'name'        => 'launcher_icon_hover_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#000000"
			),

			// - FAQ List Tab
			'faqs_top_tab'                              => array(
				'label'       => __( 'FAQs Tab Text', 'help-dialog' ),
				'name'        => 'faqs_top_tab',
				'size'        => '30',
				'max'         => '50',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'FAQs', 'help-dialog' )
			),
			'welcome_text'                              => array(
				'label'       => __( 'Welcome Text', 'help-dialog' ),
				'name'        => 'welcome_text',
				'size'        => '30',
				'max'         => '200',
				'min'         => '1',
				'mandatory'   => false,
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'How can we help you?', 'help-dialog' )
			),
			'search_input_label'                   => array(
				'label'       => __( 'Search Label', 'help-dialog' ),
				'name'        => 'search_input_label',
				'size'        => '30',
				'max'         => '50',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Search for an Answer', 'help-dialog' )
			),
			'search_input_placeholder'                   => array(
				'label'       => __( 'Search Placeholder', 'help-dialog' ),
				'name'        => 'search_input_placeholder',
				'size'        => '30',
				'max'         => '50',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Enter one or two keywords', 'help-dialog' )
			),
			'article_read_more_text'                    => array(
				'label'       => __( 'Read More Text', 'help-dialog' ),
				'name'        => 'article_read_more_text',
				'size'        => '30',
				'max'         => '100',
				'min'         => '0',
				'mandatory'   => false,
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Read More', 'help-dialog' )
			),
			'background_color'                          => array(
				'label'       => __( 'Main Background / Active Tab', 'help-dialog' ),
				'name'        => 'background_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#0f4874"
			),
			'not_active_tab_color'                      => array(
				'label'       => __( 'Not Active Tab', 'help-dialog' ),
				'name'        => 'not_active_tab_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#132e59"
			),
			'tab_text_color'                            => array(
				'label'       => __( 'Tab text', 'help-dialog' ),
				'name'        => 'tab_text_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#ffffff"
			),
			'main_title_text_color'                     => array(
				'label'       => __( 'Main Title / Search Results', 'help-dialog' ),
				'name'        => 'main_title_text_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#FFFFFF"
			),
			'welcome_title_color'                       => array(
				'label'       => __( 'Welcome Title', 'help-dialog' ),
				'name'        => 'welcome_title_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#FFFFFF"
			),
			'breadcrumb_color'                          => array(
				'label'       => __( 'Breadcrumb Color', 'help-dialog' ),
				'name'        => 'breadcrumb_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#333333"
			),
			'breadcrumb_background_color'                => array(
				'label'       => __( 'Breadcrumb Background Color', 'help-dialog' ),
				'name'        => 'breadcrumb_background_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#e6e6e6"
			),
			'breadcrumb_arrow_color'                    => array(
				'label'       => __( 'Breadcrumb Arrow', 'help-dialog' ),
				'name'        => 'breadcrumb_arrow_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#000000"
			),
			'faqs_qa_border_color'                      => array(
				'label'       => __( 'Question Border', 'help-dialog' ),
				'name'        => 'faqs_qa_border_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#CCCCCC"
			),
			'faqs_question_text_color'                  => array(
				'label'       => __( 'Question Text', 'help-dialog' ),
				'name'        => 'faqs_question_text_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#000000"
			),
			'faqs_question_background_color'            => array(
				'label'       => __( 'Question Background', 'help-dialog' ),
				'name'        => 'faqs_question_background_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#f7f7f7"
			),
			'faqs_question_active_text_color'           => array(
				'label'       => __( 'Question Active text', 'help-dialog' ),
				'name'        => 'faqs_question_active_text_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#000000"
			),
			'faqs_question_active_background_color'     => array(
				'label'       => __( 'Question Active Background', 'help-dialog' ),
				'name'        => 'faqs_question_active_background_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#ffffff"
			),
			'faqs_answer_text_color'                    => array(
				'label'       => __( 'Answer Text', 'help-dialog' ),
				'name'        => 'faqs_answer_text_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#000000"
			),
			'faqs_answer_background_color'              => array(
				'label'       => __( 'Answer Background', 'help-dialog' ),
				'name'        => 'faqs_answer_background_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#ffffff"
			),

			// Search Results
			'search_results_title'                      => array(
				'label'       => __( 'Search Results Title', 'help-dialog' ),
				'name'        => 'search_results_title',
				'size'        => '30',
				'max'         => '20',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Search Results', 'help-dialog' )
			),
			'breadcrumb_home_text'                      => array(
				'label'       => __( 'Breadcrumb - Home', 'help-dialog' ),
				'name'        => 'breadcrumb_home_text',
				'size'        => '30',
				'max'         => '20',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Home', 'help-dialog' )
			),
			'breadcrumb_search_result_text'             => array(
				'label'       => __( 'Breadcrumb - Search Results', 'help-dialog' ),
				'name'        => 'breadcrumb_search_result_text',
				'size'        => '30',
				'max'         => '20',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Search Results', 'help-dialog' )
			),
			'breadcrumb_article_text'                   => array(
				'label'       => __( 'Breadcrumb - Article', 'help-dialog' ),
				'name'        => 'breadcrumb_article_text',
				'size'        => '30',
				'max'         => '20',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Article', 'help-dialog' )
			),
			'found_faqs_tab_text'                       => array(
				'label'       => __( 'Found FAQs Tab', 'help-dialog' ),
				'name'        => 'found_faqs_tab_text',
				'size'        => '30',
				'max'         => '50',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'FAQs', 'help-dialog' )
			),
			'found_articles_tab_text'                   => array(
				'label'       => __( 'Found Articles Tab', 'help-dialog' ),
				'name'        => 'found_articles_tab_text',
				'size'        => '30',
				'max'         => '50',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Articles', 'help-dialog' )
			),
			'found_posts_tab_text'                      => array(
				'label'       => __( 'Found Posts Tab', 'help-dialog' ),
				'name'        => 'found_posts_tab_text',
				'size'        => '30',
				'max'         => '50',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Posts', 'help-dialog' )
			),
			'no_results_found_title_text'               => array(
				'label'       => __( 'No Results Title', 'help-dialog' ),
				'name'        => 'no_results_found_title_text',
				'size'        => '30',
				'max'         => '70',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'No Matches Found For', 'help-dialog' )
			),

			// Articles
			'protected_article_placeholder_text'                => array(
				'label'       => __( 'Password Protected Article Placeholder', 'help-dialog' ),
				'name'        => 'protected_article_placeholder_text',
				'size'        => '30',
				'max'         => '100',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Article is protected by password', 'help-dialog' )
			),
			'no_results_found_content_html'             => array(
				'label'       => __( 'No Results Found', 'help-dialog' ),
				'name'        => 'no_results_found_content_html',
				'size'        => '500',
				'max'         => '800',
				'min'         => '0',
				'mandatory'   => false,
				'type'        => EPHD_Input_Filter::WP_EDITOR,
				'default'     => esc_html__( 'Search hints', 'help-dialog' ) . ':' .
					'<ol>' .
					'<li>' . esc_html__( "Use specific, rather than generic, search terms.", 'help-dialog' ) . '</li>' .
					'<li>' . esc_html__( 'Try using fewer words.', 'help-dialog' ) . '</li>' .
					'<li>' . esc_html__( 'Make sure the spelling is correct.', 'help-dialog' ) . '</li>' .
					'</ol>'
			),
			'article_back_button_text'                  => array(
				'label'       => __( 'Back Button Text', 'help-dialog' ),
				'name'        => 'article_back_button_text',
				'size'        => '30',
				'max'         => '50',
				'min'         => '0',
				'mandatory'   => false,
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Back', 'help-dialog' )
			),
			'found_faqs_article_active_tab_color'       => array(
				'label'       => __( 'Active Tab', 'help-dialog' ),
				'name'        => 'found_faqs_article_active_tab_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#0f9beb"
			),
			'found_faqs_article_tab_color'              => array(
				'label'       => __( 'Inactive Tabs', 'help-dialog' ),
				'name'        => 'found_faqs_article_tab_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#000000"
			),
			'no_result_contact_us_text'                 => array(
				'label'       => __( 'Contact Us Link', 'help-dialog' ),
				'name'        => 'no_result_contact_us_text',
				'size'        => '30',
				'max'         => '70',
				'min'         => '0',
				'mandatory'   => false,
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Or contact us here', 'help-dialog' )
			),
			'article_post_list_title_color'                => array(
				'label'       => __( 'Article/Post Title Color', 'help-dialog' ),
				'name'        => 'article_post_list_title_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#000000"
			),
			'article_post_list_icon_color'                => array(
				'label'       => __( 'Article/Post Icon Color', 'help-dialog' ),
				'name'        => 'article_post_list_icon_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#000000"
			),

			// - Single Article
			'single_article_read_more_text_color'       => array(
				'label'       => __( 'Read More Text Color', 'help-dialog' ),
				'name'        => 'single_article_read_more_text_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#0f9beb"
			),
			'single_article_read_more_text_hover_color' => array(
				'label'       => __( 'Read More Text Hover Color', 'help-dialog' ),
				'name'        => 'single_article_read_more_text_hover_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#007eed"
			),

			// - Contact Form Tab
			'contact_submit_button_color'               => array(
				'label'       => __( 'Submit Button Color', 'help-dialog' ),
				'name'        => 'contact_submit_button_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#2D7EBE"
			),
			'contact_submit_button_hover_color'         => array(
				'label'       => __( 'Submit Button Hover Color', 'help-dialog' ),
				'name'        => 'contact_submit_button_hover_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#4D4986"
			),
			'contact_submit_button_text_color'          => array(
				'label'       => __( 'Submit Button Text Color', 'help-dialog' ),
				'name'        => 'contact_submit_button_text_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#ffffff"
			),
			'contact_submit_button_text_hover_color'    => array(
				'label'       => __( 'Submit Button Text Hover Color', 'help-dialog' ),
				'name'        => 'contact_submit_button_text_hover_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#ffffff"
			),
			'contact_acceptance_background_color'    => array(
				'label'       => __( 'Acceptance Checkbox Background Color', 'help-dialog' ),
				'name'        => 'contact_acceptance_background_color',
				'size'        => '10',
				'max'         => '7',
				'min'         => '7',
				'type'        => EPHD_Input_Filter::COLOR_HEX,
				'default'     => "#ffffff"
			),
		);
	}

	/**
	 * Fields specifications for Contact Form
	 *
	 * @return array[]
	 */
	private static function get_contact_form_fields_specification() {
		return array(
			'contact_form_id'                           => array(
				'label'       => '',
				'name'        => 'contact_form_id',
				'max'         => 1000000000,
				'min'         => 1,
				'type'        => EPHD_Input_Filter::NUMBER,
				'style'       => 'small',
				'default'     => self::DEFAULT_ID
			),
			'contact_form_name'                         => array(
				'label'       => __( 'Design Name', 'help-dialog' ),
				'name'        => 'contact_form_name',
				'size'        => '30',
				'max'         => '100',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Default Contact Form', 'help-dialog' )
			),
			'contact_user_email_text'                   => array(
				'label'       => __( 'Email Text', 'help-dialog' ),
				'name'        => 'contact_user_email_text',
				'size'        => '30',
				'max'         => '50',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Email', 'help-dialog' )
			),
			'contact_title'                             => array(
				'label'       => __( 'Contact Us Title', 'help-dialog' ),
				'name'        => 'contact_title',
				'size'        => '30',
				'max'         => '50',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Get in Touch', 'help-dialog' )
			),
			'contact_name_text'                         => array(
				'label'       => __( 'Name Text', 'help-dialog' ),
				'name'        => 'contact_name_text',
				'size'        => '30',
				'max'         => '50',
				'min'         => '0',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Name', 'help-dialog' )
			),
			'contact_subject_text'                      => array(
				'label'       => __( 'Subject Text', 'help-dialog' ),
				'name'        => 'contact_subject_text',
				'size'        => '30',
				'max'         => '50',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Subject', 'help-dialog' )
			),
			'contact_comment_text'                      => array(
				'label'       => __( 'Comment Text', 'help-dialog' ),
				'name'        => 'contact_comment_text',
				'size'        => '30',
				'max'         => '250',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'How can we help you?', 'help-dialog' )
			),
			'contact_acceptance_title'                  => array(
				'label'       => __( 'Acceptance Checkbox Title', 'help-dialog' ),
				'name'        => 'contact_acceptance_title',
				'size'        => '30',
				'max'         => '75',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'GDPR Agreement', 'help-dialog' )
			),
			'contact_acceptance_text'                   => array(
				'label'       => __( 'Acceptance Checkbox Text', 'help-dialog' ),
				'name'        => 'contact_acceptance_text',
				'size'        => '30',
				'max'         => '1000',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'allowed_tags' => array(
					'a' => array(
						'href'  => true,
						'title' => true,
					),
					'strong' => array(),
					'i' => array(),
				),
				'default'     => __( 'I accept the terms and conditions.', 'help-dialog' )
			),
			'contact_button_title'                      => array(
				'label'       => __( 'Submit Button Text', 'help-dialog' ),
				'name'        => 'contact_button_title',
				'size'        => '30',
				'max'         => '50',
				'min'         => '1',
				'type'        => EPHD_Input_Filter::TEXT,
				'default'     => __( 'Submit', 'help-dialog' )
			),
			'contact_success_message'                   => array(
				'label'        => __( 'Email Sent Success Message', 'help-dialog' ),
				'name'         => 'contact_success_message',
				'size'         => '60',
				'max'          => '150',
				'min'          => '0',
				'mandatory'    => false,
				'type'         => EPHD_Input_Filter::TEXT,
				'default'      => __( 'Thank you. We will get back to you soon.', 'help-dialog' ),
				'allowed_tags' => array(
					'a' => array(
						'href'  => true,
						'title' => true,
					),
				),
			),
		);
	}

	/**
	 * Fields specifications for Notification Rule
	 *
	 * @return array[]
	 */
	private static function get_notification_rule_fields_specification() {
		return array(
			'notification_rule_id'                    => array(
				'label'       => '',
				'name'        => 'notification_rule_id',
				'max'         => 1000000000,
				'min'         => 0,
				'type'        => EPHD_Input_Filter::NUMBER,
				'style'       => 'small',
				'default'     => 0
			),
		);
	}

	/**
	 * Get Plugin default configuration
	 *
	 * @param string $config_name
	 *
	 * @return array contains default setting values
	 */
	public static function get_default_hd_config( $config_name='' ) {

		$setting_specs = self::get_fields_specification( $config_name );

		$default_configuration = array();
		foreach( $setting_specs as $key => $spec ) {
			$default = isset( $spec['default'] ) ? $spec['default'] : '';
			$default_configuration += array( $key => $default );
		}

		return $default_configuration;
	}

	/**
	 * Get names of all configuration items for Plugin settings
	 *
	 * @param string $config_name
	 *
	 * @return int[]|string[]
	 */
	public static function get_specs_item_names( $config_name='' ) {
		return array_keys( self::get_fields_specification( $config_name ) );
	}
}