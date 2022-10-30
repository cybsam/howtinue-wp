<?php
defined( 'ABSPATH' ) || exit();

/**
 * Display the Help dialog on the frontend
 */
class EPHD_Help_Dialog_View {

	private $widget_config;
	private $design_config;
	private $is_opened;
	private $is_admin_preview;
	private $global_config;
	private $display_mode;

	public function __construct( $widget_config=null, $design_config=null, $is_opened=false, $is_admin_preview=false, $global_config=null ) {

		$this->widget_config = $widget_config;
		$this->design_config = $design_config;
		$this->is_opened = $is_opened;
		$this->is_admin_preview = $is_admin_preview;
		$this->global_config = $global_config;

		// if this is HD preview for backend then exit
		if ( ! empty( $widget_config ) ) {
			return;
		}

		add_action( 'wp_footer', array( $this, 'output_help_dialog' ) );
	}

	/**
	 * Display Help Dialog on the current page
	 *
	 * @param bool $return_html
	 *
	 * @return false|string|void
	 */
	public function output_help_dialog( $return_html=false ) {

		$is_front_page = is_front_page();

		// for public frontend we need to initialize widget_config later than in constructor to have the global $post set up
		if ( empty( $this->widget_config ) ) {

			// return if HD should not be displayed
			$this->widget_config = self::get_help_dialog_if_can_be_displayed( $is_front_page );
			if ( empty( $this->widget_config ) ) {
				return;
			}
		}

		// for demo preview config we might have design that does not exist yet
		if ( empty( $this->design_config ) ) {
			$designs_config = ephd_get_instance()->designs_config_obj->get_config();
			$this->design_config = isset( $designs_config[ $this->widget_config['design_id']] )
				? $designs_config[ $this->widget_config['design_id']]
				: $designs_config[EPHD_Config_Specs::DEFAULT_ID];
		}

		if ( empty( $this->global_config ) ) {
			$this->global_config = ephd_get_instance()->global_config_obj->get_config();
		}

		// define display mode
		$this->display_mode = empty( $this->global_config['dialog_display_mode'] ) ? 'both' : $this->global_config['dialog_display_mode'];

		if ( ! $return_html ) {
			do_action( 'ephd_enqueue_help_dialog_resources' );

			do_action( 'ephd_enqueue_font_scripts' );
			do_action( 'ephd_enqueue_help_dialog_scripts' );

            // enqueue pro scripts
			do_action( 'ephd_enqueue_help_dialog_pro_scripts' );
		}

		if ( ! empty( $return_html ) ) {
			ob_start();
		}

		$this->display_help_dialog( $is_front_page );

		if ( ! empty( $return_html ) ) {
			return ob_get_clean();
		}
	}

	/**
	 * Display the Help Dialog box on frontend or admin pages.
	 *
	 * @param $is_front_page
	 */
	private function display_help_dialog( $is_front_page ) {
		global $post;

		// retrieve configuration for all Contact Forms
		$contact_form_id = empty( $this->widget_config['contact_form_id'] ) ? EPHD_Config_Specs::DEFAULT_ID : $this->widget_config['contact_form_id'];
		$contact_forms_config = ephd_get_instance()->contact_forms_config_obj->get_config();
		$contact_form_config = empty( $contact_forms_config[$contact_form_id] ) ? $contact_forms_config[EPHD_Config_Specs::DEFAULT_ID] : $contact_forms_config[$contact_form_id];

		$topClassTab = $this->display_mode == 'both' || $this->display_mode == 'faqs' ? 'ephd-hd-faqs-tab--active' : 'ephd-hd-contact-tab--active';

		$activeWPTheme = 'ephd-help-dialog-active-theme-'. EPHD_Utilities::get_wp_option( 'stylesheet', 'unknown' );
		$widget_id = EPHD_Widgets_DB::DEFAULT_ID //TODO if we ever have separate colors etc.. then this has to be dynamic        ?>

		<div id="ephd-help-dialog" class="ephd-help-dialog-container ephd-widget-preset--<?php echo esc_attr( $this->global_config['dialog_width'] ); ?> ephd-widget--<?php echo $widget_id . ' ' . esc_attr( $activeWPTheme ) . ' ' . $topClassTab; ?>"
		     style="display:<?php echo $this->is_opened ? 'block' : 'none'; ?>;"
             data-ephd-tab="<?php echo ( $this->display_mode == 'contact' ? 'contact' : 'faqs' ); ?>"
             data-ephd-page-id="<?php echo empty( $post ) ? -1 : ( $is_front_page ? 0 : esc_attr( $post->ID ) ); ?>"
             data-ephd-count-analytics="<?php echo self::is_count_analytics( $post ) ? 'on' : ''; ?>"
             data-ephd-widget-id="<?php echo esc_attr( $this->widget_config['widget_id'] ); ?>"
             role="dialog" aria-labelledby="ephd-hd-header__welcome__title" aria-describedby="ephd-hd-header__welcome__text" tabindex="-1">            <?php

			$this->display_tabs_if_enabled();   ?>

			<!-- HEADER CONTAINER -->
			<div id="ephd-hd-header-container">

				<!-- MAIN HEADING -->
				<div class="ephd-hd-header__main-heading-container">					<?php

					if ( ! empty( $this->global_config['logo_image_url'] ) ) {	?>
						<div class="ephd-hd-header__logo">
							<img class="ephd-hd-header__logo__img" src="<?php echo esc_url( $this->global_config['logo_image_url'] ); ?>" alt="">
						</div>      <?php
					} ?>

					<div class="ephd-hd-header__welcome <?php echo empty( $this->global_config['logo_image_url'] ) ? 'ephd-hd-header__welcome--centered' : ''; ?>">
						<div id="ephd-hd-header__welcome__title" class="ephd-hd-header__welcome__title"><?php echo esc_html( $this->design_config['welcome_title'] ); ?></div>
                        <div id="ephd-hd-header__welcome__text" class="ephd-hd-header__welcome__text"><?php echo esc_html( $this->design_config['welcome_text'] ); ?></div> <?php
                        if ( $this->display_mode == 'both' || $this->display_mode == 'contact' ) {  ?>
                            <div id="ephd-hd-header__welcome__contact-text" class="ephd-hd-header__welcome__contact-text"><?php echo esc_html( $contact_form_config['contact_title'] ); ?></div>   <?php
	                    }   ?>
					</div>  <?php

					$this->display_search_results_header_if_enabled();  ?>
                    
				</div>  <?php

				$this->display_sub_heading_if_enabled();   ?>

			</div>

			<!-- BODY CONTAINER -->
			<div id="ephd-hd-body-container">    <?php

				$this->display_faqs_box_if_enabled();

				$this->display_contact_box_if_enabled( $contact_form_config, $post, $is_front_page );    ?>

				<div class="ephd-hd__loading-spinner__container">
					<div class="ephd-hd__loading-spinner"></div>
				</div>

			</div>

			<!-- FOOTER CONTAINER -->
            <div id="ephd-hd-footer-container">  <?php
                $powered_by_html = apply_filters( 'ephd_help_dialog_view_powered_by_html', self::display_powered_by_box(), $this->global_config );
                if ( ! empty( $powered_by_html ) && is_string( $powered_by_html ) ) {
                	echo $powered_by_html;
                }   ?>
			</div>
		</div>  <?php

		$help_dialog_launcher_analytics_delay = 5;
		$help_dialog_launcher_start_wait = 0;
		if ( ! empty( $this->design_config['launcher_start_wait'] ) ) {
			$help_dialog_launcher_start_wait = $this->design_config['launcher_start_wait'];
		}

		// show notification to admin if widget is draft
        $draft_notification = '';
		if ( $this->widget_config['widget_status'] == EPHD_Help_Dialog_Handler::HELP_DIALOG_STATUS_DRAFT && ! $this->is_admin_preview ) {
			ob_start(); ?>
            <span class="ephd-hd-toggle__draft-notification">
                <?php esc_html_e( 'DRAFT - Currently Not Visible to the Public', 'help-dialog' ); ?>
            </span>    <?php
			$draft_notification = ob_get_clean();
		}

		// display launcher icon/text
		if ( EPHD_Utilities::is_help_dialog_pro_enabled() ) {
			echo apply_filters( 'ephd_help_dialog_show_launcher', '', array(
				'is_open'            => $this->is_opened,
				'is_shown'           => $this->is_admin_preview,
				'is_front_page'      => $is_front_page,
				'design_id'          => $this->design_config['design_id'],
				'analytics_delay'    => $help_dialog_launcher_analytics_delay,
				'launcher_wait'      => $help_dialog_launcher_start_wait,
				'draft_notification' => $draft_notification,
				'widget_config'      => $this->widget_config,
			) );

			return;
		}   ?>

		<div
			class="ephd-hd-toggle <?php echo $this->is_opened ? 'ephd-hd-toggle--on' : 'ephd-hd-toggle--off'; ?> ephd-widget--<?php echo esc_attr( $this->design_config['design_id'] ); ?> <?php echo esc_attr( $activeWPTheme ).'-toggle'; ?>"
			data-ephd-analytics-delay="<?php echo esc_attr( $help_dialog_launcher_analytics_delay ); ?>"
			data-ephd-start-wait="<?php echo esc_attr( $help_dialog_launcher_start_wait ); ?>"
			style="display:<?php echo ( $this->is_opened || $this->is_admin_preview ) ? 'block' : 'none'; ?>;"
            role="button" aria-label="Open Help Dialog" tabindex="0" aria-pressed="false">                <?php

            // Format icon based on Font Icon or HD custom Icon.
            if ( $this->global_config['launcher_icon'] == 'ep_font_icon_help_dialog' ) { ?>
                <span class="ephd-hd-toggle__icon ephd-hd-icon ephdfa <?php echo $this->is_opened ? 'ephdfa-times' : esc_attr( $this->global_config['launcher_icon'] ); ?>"
                      data-ephd-toggle-icons="ephdfa-times <?php echo esc_attr( $this->global_config['launcher_icon'] ); ?>">
                </span>                <?php
			} else { 	?>
                <span class="ephd-hd-toggle__icon ephdfa <?php echo $this->is_opened ? 'ephdfa-times' : 'ephdfa-'.esc_attr( $this->global_config['launcher_icon'] ); ?>"
                      data-ephd-toggle-icons="ephdfa-times <?php echo 'ephdfa-'.esc_attr( $this->global_config['launcher_icon'] ); ?>">
                </span>                <?php
			}

            echo wp_kses_post( $draft_notification );   ?>
		</div> <?php
	}

	/**
	 * Display Tabs only if both FAQs and Contact Form are enabled
	 */
	private function display_tabs_if_enabled() {

		if ( $this->display_mode != 'both' ) {
			return;
		}   ?>

		<!-- TAB CONTAINER -->
		<div id="ephd-hd-top-tab-container" role="tablist" aria-label="Help Dialog Top Tabs">

			<!-- FAQs tab -->
			<div id="ephd-hd-faq-tab" role="tab" aria-selected="true" tabindex="0" class="ephd-hd-tab ephd-hd-tab__faq-btn ephd-hd-tab--active" data-ephd-target-tab="faqs">
				<span class="ephd-hd-tab__faq-btn__text"><?php echo esc_html( $this->design_config['faqs_top_tab'] ); ?></span>
			</div>

			<!-- Contact Form tab -->
			<div id="ephd-hd-contact-us-tab" role="tab" aria-selected="false" tabindex="-1" class="ephd-hd-tab ephd-hd-tab__contact-btn" data-ephd-target-tab="contact">
				<span class="ephd-hd-tab__contact-btn__text"><?php echo esc_html( $this->design_config['contact_us_top_tab'] ); ?></span>
			</div>

		</div>  <?php
	}

	/**
	 * Display FAQs box if it is enabled
	 */
	private function display_faqs_box_if_enabled() {

		if ( $this->display_mode != 'both' && $this->display_mode != 'faqs' ) {
			return;
		}   ?>

		<!-- FAQs Container -->
		<div id="ephd-hd-body__content-container" role="tabpanel" tabindex="0" aria-labelledby="ephd-hd-faq-tab" data-ephd-tab="faqs">

			<!-- FAQ List -->
			<div class="ephd-hd-faq-container">

				<div class="ephd-hd-faq__list"> <?php

					$this->display_faqs_list_or_no_faqs_message();

					$this->display_search_result_box_if_enabled();   ?>

				</div>

			</div>  <?php

			$this->display_search_input_box_if_enabled(); ?>

		</div>      <?php
	}

	/**
	 * Display Contact Box if it is enabled
	 *
	 * @param $contact_forms_config
	 * @param $post
	 * @param $is_front_page
	 */
	private function display_contact_box_if_enabled( $contact_forms_config, $post, $is_front_page ) {

		if ( $this->display_mode != 'both' && $this->display_mode != 'contact' ) {
			return;
		}   ?>

		<!-- Contact form -->
		<div id="ephd-hd-body__contact-container" role="tabpanel" tabindex="0" aria-labelledby="ephd-hd-contact-us-tab" data-ephd-tab="contact">
			<form id="ephd-hd__contact-form" method="post" enctype="multipart/form-data">
				<div class="ephd-hd__contact-form-response"></div>          <?php
				wp_nonce_field( '_wpnonce_ephd_ajax_action' );				?>
				<input type="hidden" name="action" value="ephd_help_dialog_contact">
				<input type="hidden" name="widget_id" value="<?php echo esc_attr( $this->widget_config['widget_id'] ); ?>">
				<input type="hidden" name="widget_name" value="<?php echo esc_attr( $this->widget_config['widget_name'] ); ?>">
				<input type="hidden" name="page_id" value="<?php echo empty( $post ) ? -1 : ( $is_front_page ? 0 : esc_attr( $post->ID ) ); ?>">
				<input type="hidden" name="page_name" value="<?php echo empty( $post ) ? '' : esc_attr( $post->post_title ); ?>">
				<input type="hidden" name="contact_form_id" value="<?php echo esc_attr( $contact_forms_config['contact_form_id'] ); ?>">
				<div id="ephd-hd__contact-form-body">   <?php

					if ( $this->global_config['contact_name_toggle'] == 'on' ) {   ?>
						<div class="ephd-hd__contact-form-field">
							<label class="ephd-hd__contact-form-user_first_name_label" for="ephd-hd__contact-form-user_first_name">     <?php
								echo esc_html( $contact_forms_config['contact_name_text'] );     ?>
								<span class="ephd-hd__contact-form-field__required-tag">*</span>
							</label>
							<input name="user_first_name" type="text" value="" required id="ephd-hd__contact-form-user_first_name" placeholder="" maxlength="<?php echo EPHD_Submissions_DB::NAME_LENGTH; ?>">
						</div>      <?php
					}

					// Set fake input field that is visible only for spam bots     ?>
					<div class="ephd-hd__contact-form-field ephd-hd__contact-form-field--catch-details">
						<label class="ephd-hd__contact-form-comment_label" for="ephd-hd__contact-form-catch-details">
							<span class="ephd-hd__contact-form-field__label-text"><?php esc_html_e( 'Catch Details', 'help-dialog' ); ?></span>
						</label>
						<input name="catch_details" type="text" value="" id="ephd-hd__contact-form-catch-details" placeholder="" maxlength="100" tabindex="-1" autocomplete="off">
					</div>

					<div class="ephd-hd__contact-form-field">
						<label class="ephd-hd__contact-form-email_label" for="ephd-hd__contact-form-email">
							<span class="ephd-hd__contact-form-field__label-text"><?php echo esc_html( $contact_forms_config['contact_user_email_text'] ); ?></span>
							<span class="ephd-hd__contact-form-field__required-tag">*</span>
						</label>
						<input name="email" type="email" value="" required id="ephd-hd__contact-form-email" placeholder="" maxlength="<?php echo EPHD_Submissions_DB::EMAIL_LENGTH; ?>">
					</div>  <?php

					if ( $this->global_config['contact_subject_toggle'] == 'on' ) {   ?>
						<div class="ephd-hd__contact-form-field">
							<label class="ephd-hd__contact-form-subject_label" for="ephd-hd__contact-form-subject">
								<span class="ephd-hd__contact-form-field__label-text"><?php echo esc_html( $contact_forms_config['contact_subject_text'] ); ?></span>
								<span class="ephd-hd__contact-form-field__required-tag">*</span>
							</label>
							<input name="subject" type="text" value="" required id="ephd-hd__contact-form-subject" placeholder="" maxlength="<?php echo EPHD_Submissions_DB::SUBJECT_LENGTH; ?>">
						</div>  <?php
					}   ?>

					<div class="ephd-hd__contact-form-field">
						<label class="ephd-hd__contact-form-comment_label" for="ephd-hd__contact-form-comment">
							<span class="ephd-hd__contact-form-field__label-text"><?php echo esc_html( $contact_forms_config['contact_comment_text'] ); ?></span>
							<span class="ephd-hd__contact-form-field__required-tag">*</span>
						</label>
						<textarea name="comment" required id="ephd-hd__contact-form-comment" rows="4" placeholder="" maxlength="<?php echo EPHD_Submissions_DB::COMMENT_LENGTH; ?>"></textarea>
					</div>                    <?php

                    // Acceptance Checkbox
					if ( $this->global_config['contact_acceptance_checkbox'] == 'on' ) {
						$contact_form_specs = EPHD_Config_Specs::get_fields_specification( EPHD_Config_DB::EPHD_CONTACT_FORMS_CONFIG_NAME );  ?>

                        <div class="ephd-hd__contact-form-field ephd-hd__contact-form-acceptance-container">   <?php
	                        if ( $this->global_config['contact_acceptance_title_toggle'] == 'on' ) {   ?>
		                        <span class="ephd-hd__contact-form-field__label-title">
                                    <span class="ephd-hd-acceptance-title"><?php echo esc_html( $contact_forms_config['contact_acceptance_title'] ); ?></span>
                                    <span class="ephd-hd__contact-form-field__required-tag">*</span>
                                </span>    <?php
	                        }   ?>
	                        <label class="ephd-hd-acceptance-label" for="ephd-hd__contact-form-acceptance">
	                            <input name="acceptance" type="checkbox" value="1" required id="ephd-hd__contact-form-acceptance" placeholder="">   <?php
	                            if ( $this->global_config['contact_acceptance_title_toggle'] == 'off' ) {   ?>
		                            <span class="ephd-hd__contact-form-field__required-tag">*</span>    <?php
	                            }   ?>
                                <span class="ephd-hd__contact-form-field__label-text"><?php echo wp_kses( $contact_forms_config['contact_acceptance_text'], $contact_form_specs['contact_acceptance_text']['allowed_tags'] ); ?></span>
                            </label>
                        </div>  <?php
                    }   ?>

					<div class="ephd-hd__contact-form-btn-wrap">
						<div class="ephd-hd__contact-form-error"></div>
						<input type="submit" name="submit" value="<?php echo esc_attr( $contact_forms_config['contact_button_title'] ); ?>" class="ephd-hd__contact-form-btn">
					</div>

				</div>
			</form>
		</div>		<?php
	}

	/**
	 * Display Sub Heading if FAQs tab and Search option are enabled
	 */
	private function display_sub_heading_if_enabled() {

		if ( ( $this->display_mode != 'both' && $this->display_mode != 'faqs' ) && $this->widget_config['search_option'] != 'show_search' ) {
			return;
		}   ?>

		<!-- SUB HEADING -->
		<div class="ephd-hd-header__sub-heading-container">

			<!-- BREADCRUMB -->
			<div class="ephd-hd-sub-heading__breadcrumb-container">
				<div class="ephd-hd-sub-heading__breadcrumb-wrap">

					<div class="ephd-hd__faq__back-btn">
						<div class="ephd-hd__faq__back-btn__icon ephdfa ephdfa-arrow-left"></div>
						<div class="ephd-hd__faq__back-btn__text"><?php esc_html_e( $this->design_config['article_back_button_text'] ); ?></div>
					</div>

					<nav class="ephd-hd__breadcrumb__nav" aria-label="Breadcrumb">
						<ol>
							<li>
								<span id="ephd-hd__breadcrumb__home" class="ephd-hd__breadcrumb_text" data-ephd-breadcrumb="home"><?php echo esc_html( $this->design_config['breadcrumb_home_text'] ); ?></span>
								<span  aria-hidden="true" id="ephd-search-home-arrow" class=" ephd-hd-faq__header__title-arrow ephdfa ephdfa-caret-right"></span>
							</li>
							<li>
								<span id="ephd-hd__breadcrumb__search-results" class="ephd-hd__breadcrumb_text" data-ephd-breadcrumb="search_results"><?php echo esc_html( $this->design_config['breadcrumb_search_result_text'] ); ?></span>
								<span aria-hidden="true" id="ephd-search-result-arrow" class=" ephd-hd-faq__header__title-arrow ephdfa ephdfa-caret-right"></span>
							</li>
							<li>
								<span id="ephd-hd__breadcrumb__article" class="ephd-hd__breadcrumb_text" data-ephd-breadcrumb="article"><?php echo esc_html( $this->design_config['breadcrumb_article_text'] ); ?></span>
							</li>
						</ol>
					</nav>

				</div>
			</div>  <?php

			$this->display_search_tab(); ?>
		</div>  <?php
	}

	/**
	 * Display List Questions in Help Dialog or No FAQs message
	 */
	private function display_faqs_list_or_no_faqs_message() {

		$faqs_db_handler = new EPHD_FAQs_Articles_DB();
		$questions = $faqs_db_handler->get_faqs_by_ids( $this->widget_config['faqs_sequence'] );    ?>

		<!-- FAQ Wrap -->
		<div class="ephd-hd-faq__faqs-container<?php echo ( empty( $questions ) || is_wp_error( $questions ) ) ? ' ephd-hd-faq__faqs-container--no-faqs' : ''; ?>">  <?php

			// No Questions found or error
			if ( empty( $questions ) || is_wp_error( $questions ) ) {    ?>
				<div class="ephd-hd__no-questions-set">
					<div><img src="<?php echo esc_url( Echo_Help_Dialog::$plugin_url . 'img/no-faqs-defined.jpeg' ); ?>" alt="" /></div>
					<span class="ephd-hd__contact-us__message"><?php echo esc_html__( 'Search for your question below', 'help-dialog' ) . ' ' . esc_html__( 'or', 'help-dialog' ) . ' '; ?></span>
					<span class="ephd-hd__contact-us__link" data-ephd-target-tab="contact"> <?php esc_html_e( 'contact us here', 'help-dialog' ); ?></span>
				</div>   <?php

			// Display Questions
			} else {
				foreach ( $this->widget_config['faqs_sequence'] as $question_id ) {
					foreach ( $questions as $question ) {
						if ( ! empty( $question->faq_id ) && $question_id == $question->faq_id ) {
							echo EPHD_FAQs_Articles_Page::get_faq_item_html( $question->faq_id, $question->question, $question->answer );
							break;
						}
					}
				}
			}   ?>

		</div>  <?php
	}

	/**
	 * Display Search Input if Search option is enabled
	 */
	private function display_search_input_box_if_enabled() {

		if ( $this->widget_config['search_option'] != 'show_search' ) {
			return;
		}

		global $post;   ?>

		<!-- Search Box -->
		<div class="ephd-hd-search-container">

			<!----- Search Box ------>
			<div class="ephd-hd__search-box">
                <div class="ephd-hd__search-box__search-label"><?php echo esc_html( $this->design_config['search_input_label'] ); ?></div>
				<form id="ephd-hd__search-form" method="post" action="" onSubmit="return false;">
					<input type="text" id="ephd-hd__search-terms" name="ephd-hd__search-terms" value=""
						   placeholder="<?php echo esc_attr( $this->design_config['search_input_placeholder'] ); ?>"
						   data-ephd-location-id="<?php echo empty( $post ) ? 0 : esc_attr( $post->ID ); ?>"
						   maxlength="<?php echo EPHD_Search::SEARCH_INPUT_LENGTH; ?>"
							data-ephd-widget-id="<?php echo esc_attr( $this->widget_config['widget_id'] ); ?>" autocomplete="off"/>
					<div class="ephd-hd__search-tooltip">
						<div class="ephd-hd__search-tooltip__header"><?php
							esc_html_e( 'Search Guideline', 'help-dialog' ); ?>
						</div>
						<div class="ephd-hd__search-tooltip__body">
							<p><?php esc_html_e( 'Use up to three keywords instead of using a full sentence for the best search results.', 'help-dialog' ); ?></p>
							<div class="ephd-hd__search-tooltip__body--columns">
								<div class="ephd-hd__search-tooltip__body--left"><?php esc_html_e( 'Examples:', 'help-dialog' ); ?></div>
								<div class="ephd-hd__search-tooltip__body--right">
									<p><?php esc_html_e( 'product', 'help-dialog' ); ?></p>
									<p><?php esc_html_e( 'product warranty', 'help-dialog' ); ?></p>
									<p><?php esc_html_e( 'free shipping offer', 'help-dialog' ); ?></p>
								</div>
							</div>
						</div>
					</div>
					<span class="ephd-hd__search-terms__icon ephdfa ephdfa-search"></span>
				</form>
			</div>

		</div>		<?php
	}

	/**
	 * Display Search Tab
	 */
	private function display_search_tab() {  ?>

		<!-- SEARCH TAB CONTAINER -->
		<div id="ephd-hd-search-results__tab-container">

			<!-- FAQs Tab -->
			<span id="ephd-hd__search-results-faqs-tab" class="ephd-hd-results__tab ephd-hd-results__tab--active" data-ephd-tab="faq" tabindex="0">
                <span class="ephd-hd-results__tab__text"><?php echo esc_html( $this->design_config['found_faqs_tab_text'] ); ?></span>
                <span class="ephd-hd-results__tab--active__icon"></span>
            </span> <?php

			if ( $this->widget_config['search_kb'] != 'off' ) {   ?>
				<!-- Articles Tab -->
				<span id="ephd-hd__search-results-articles-tab" class="ephd-hd-results__tab" data-ephd-tab="articles" tabindex="0">
		            <span class="ephd-hd-results__tab__text"><?php echo esc_html( $this->design_config['found_articles_tab_text'] ); ?></span>
		            <span class="ephd-hd-results__tab--active__icon"></span>
				</span> <?php
			}

			if ( $this->widget_config['search_posts'] == 'on' ) {   ?>
				<!-- Post Tab -->
				<span id="ephd-hd__search-results-post-tab" class="ephd-hd-results__tab" data-ephd-tab="post" tabindex="0">
	                <span class="ephd-hd-results__tab__text"><?php echo esc_html( $this->design_config['found_posts_tab_text'] ); ?></span>
	                <span class="ephd-hd-results__tab--active__icon"></span>
	            </span> <?php
			}   ?>

		</div>  <?php
	}

	/**
	 * Display Search Results box if Search option is enabled
	 */
	private function display_search_result_box_if_enabled() {

		if ( $this->widget_config['search_option'] != 'show_search' ) {
			return;
		}   ?>

		<!-- Search Results Container -->
		<div class="ephd-hd-kb__search-results-container">

			<!----- Search Box Results ------>
			<div class="ephd-hd-search-results-container">

	            <!-- Tab Content Container -->
	            <div class="ephd-hd-search-results__tab-content-container">

	                <div id="ephd-hd__search_results__faqs" class="ephd-hd-results__tab-content ephd-hd-search-results__faqs-list" data-ephd-tab-content="faq"></div>

	                <div id="ephd-hd__search_results__articles" class="ephd-hd-results__tab-content ephd-hd-search-results__article-list" data-ephd-tab-content="articles"></div>

	                <div id="ephd-hd__search_results__posts" class="ephd-hd-results__tab-content ephd-hd-search-results__post-list" data-ephd-tab-content="post"></div>

	            </div>

	            <div id="ephd-hd__search_results__errors"></div>

				<div id="ephd-hd__search_results-cat-article-details" class="ephd-hd__search_step">

					<div class="ephd-hd_article-item-details">
						<div id="ephd-hd_article-desc_excerpt" class="ephd-hd_article-desc"></div>
					</div>

					<div class="ephd-hd_article-item-footer">
						<a class="ephd-hd_article-link" target="_blank"><?php esc_html_e( $this->design_config['article_read_more_text'] ); ?></a>
					</div>

				</div>

			</div>

		</div> <?php
	}

	/**
	 * Display Header for Search Results if FAQs tab and Search are enabled
	 */
	private function display_search_results_header_if_enabled() {

		if ( ( $this->display_mode != 'both' && $this->display_mode != 'faqs' ) || $this->widget_config['search_option'] != 'show_search' ) {
			return;
		}   ?>

		<div id="ephd-hd-header__search-container">
			<div class="ephd-hd__search-back-btn ephdfa ephdfa-angle-left"></div>
			<div class="ephd-hd__search-text"><?php echo esc_html( $this->design_config['search_results_title'] ); ?></div>
		</div>  <?php
	}

	/**
	 * Display Powered By box
	 *
	 * @return false|string
	 */
	private function display_powered_by_box() {
		ob_start();  ?>
        <span class="ephd-hd-footer__poweredBy"><?php esc_html_e( 'Powered By', 'help-dialog' ); ?></span>
        <img class="ephd-hd-footer__icon" src="<?php echo esc_url( Echo_Help_Dialog::$plugin_url . 'img/HD-logo-footer-light.png' ); ?>" alt="">
        <a class="ephd-hd-footer__link" href="https://www.helpdialog.com/" target="_blank"><?php esc_html_e( 'Help Dialog', 'help-dialog' ); ?></a>  <?php
        return ob_get_clean();
	}

	/**
	 * Should we count analytics? Exclude admin pages, drafts etc.
	 *
	 * @param $post
	 * @return bool
	 */
	private static function is_count_analytics( $post ) {

        // is admin pages
		if ( is_admin() ) {
            return false;
		}

        // is this published/private post
        if ( empty( $post->post_status ) || ! in_array( $post->post_status, ['publish', 'private'] ) ) {
            return false;
        }

        return true;
	}

	/**
	 * Return current widget
	 *
	 * @param $is_front_page
	 * @return array|null
	 */
	private static function get_help_dialog_if_can_be_displayed( $is_front_page ) {

		// is this page or post or main page to display the Help Dialog on?
		$post = get_queried_object();

		if ( ! $is_front_page && ( empty( $post ) || get_class( $post ) !== 'WP_Post' || empty( $post->ID ) ) ) {
			return null;
		}

		// get all defined Widgets - default Widget returned if there are no defined Widgets yet or error occurred
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config();

		if ( $is_front_page ) {
			$post_type = 'page';
		} else if ( $post->post_type == 'post' || $post->post_type == 'page' ) {
			$post_type = $post->post_type;
		} else {
			$post_type = 'cpt';
		}

		// try to match by CPT or post id
		$matching_widget = null;
		$post_id = $is_front_page ? 0 : $post->ID;
		foreach ( $widgets_config as $widget ) {
			// check matching widget by post id or CPT
			$key = $post_type == 'cpt' ? $post->post_type : $post_id;
			if ( in_array( $key, $widget['location_' . $post_type . 's_list'] ) ) {
				$matching_widget = $widget;
				break;
			}
		}

		// main page set to static page - search by post ID
		if ( $is_front_page && ! empty( $post ) && ! empty( $post->ID ) ) {
			$post_id = $post->ID;
			foreach ( $widgets_config as $widget ) {
				if ( in_array( $post_id, $widget['location_' . $post_type . 's_list'] ) ) {
					$matching_widget = $widget;
					break;
				}
			}
		}

		// did we found matching post or page
		if ( empty( $matching_widget ) || ! is_array( $matching_widget ) ) {
			return null;
		}

		// hide HD set as Draft (except admin)
		if ( ! empty( $matching_widget['widget_status'] ) && $matching_widget['widget_status'] == EPHD_Help_Dialog_Handler::HELP_DIALOG_STATUS_DRAFT && ! EPHD_Utilities::is_user_admin() ) {
			return null;
		}

		return $matching_widget;
	}

	/**
	 * Insert public inline styles for all designs
	 *
	 * @param array $global_config
	 * @param array $widget_config
	 * @param array $designs_config
	 * @param bool $is_demo
	 * @param bool $output_all
	 * @return string|void
	 */
	public static function insert_widget_inline_styles( $global_config=[], $widget_config=[], $designs_config=[], $is_demo=false, $output_all=false ) {

		if ( empty( $global_config ) ) {
			$global_config = ephd_get_instance()->global_config_obj->get_config();
		}

		// if widget should not be displayed then don't insert inline styles
		if ( ! $output_all && empty( $widget_config ) ) {
			$is_front_page = is_front_page();
			$widget_config = self::get_help_dialog_if_can_be_displayed( $is_front_page );
			if ( empty( $widget_config) ) {
				return;
			}
		}

		if ( empty( $designs_config ) ) {
			$designs_config = ephd_get_instance()->designs_config_obj->get_config();
		}

		// add all Widgets design for admin
		if ( $output_all ) {
			$widgets_config = ephd_get_instance()->widgets_config_obj->get_config();
		// frontend - add only style for particular widget
		} else {
			$widgets_config = [$widget_config];
		}

		$all_designs_style_in = '';
		foreach( $widgets_config as $widget_config ) {
			$all_designs_style_in .= self::get_design_inline_styles( $global_config, $widget_config, $designs_config );
			$all_designs_style_in .= self::get_position_inline_styles( $global_config, $widget_config );
		}

		// PRO can add design
		$all_designs_style = apply_filters( 'ephd_help_dialog_view_designs_style', $all_designs_style_in, [ 'global_config' => $global_config ] );
		if ( empty( $all_designs_style ) ) {
			$all_designs_style = $all_designs_style_in;
		}

		// Add article hidden classes if kb core enabled
		if ( EPHD_KB_Core_Utilities::is_kb_or_amag_enabled() ) {
			$hidden_element_classes = self::get_hidden_element_classes_inline_styles( $global_config );
			$hidden_element_classes = EPHD_Utilities::minify_css( $hidden_element_classes );
			wp_add_inline_style( 'ephd-user-defined-values', $hidden_element_classes );
		}

		if ( $is_demo ) {
			return $all_designs_style;
		}

		$all_designs_style = EPHD_Utilities::minify_css( $all_designs_style );
		wp_add_inline_style( 'ephd-public-styles', $all_designs_style );
	}

	/**
	 * Get CSS for certain Widget
	 *
	 * @param $global_config
	 * @param $widget_config
	 * @param $designs_config
	 * @return string
	 */
	private static function get_design_inline_styles( $global_config, $widget_config, $designs_config ) {

		$design_config = $designs_config[EPHD_Config_Specs::DEFAULT_ID];
		foreach ( $designs_config as $one_design_config ) {
			if ( $widget_config['design_id'] == $one_design_config['design_id'] ) {
				$design_config = $one_design_config;
				break;
			}
		}

		$widget_id = EPHD_Widgets_DB::DEFAULT_ID; //TODO if we ever have separate colors etc.. then this has to be dynamic

		$default_global_config = EPHD_Config_Specs::get_default_hd_config();

		$display_mode = empty( $global_config['dialog_display_mode'] ) ? 'both' : $global_config['dialog_display_mode'];

		return
			'#ephd-help-dialog.ephd-widget--' . $widget_id . ' {
                /* TODO hide for now
				width: ' . $global_config['container_desktop_width'] . 'px;
				*/
				background-color: ' . $design_config['background_color'] . ';
			}
			
			#ephd-admin-page-wrap #ephd-help-dialog.ephd-widget--' . $widget_id . ' {
			    /* TODO hide for now
				width: ' . $default_global_config['container_desktop_width'] . 'px;
				*/
			}

			/* DESKTOP */ ' .

			/* Display styles only if both FAQs and Contact Form tabs are enabled */
			( $display_mode == 'both' ?
				'#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-tab {
					background-color: ' . $design_config['not_active_tab_color'] . ';
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-tab--active {
					background-color: ' . $design_config['background_color'] . ';
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-tab__faq-btn__text,
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-tab__contact-btn__text{
					color: ' . $design_config['tab_text_color'] . ';
				}'
				: '' ) . '

			/* Launcher */
			.ephd-hd-toggle.ephd-widget--' . $widget_id . ' .ephd-hd-toggle__icon {
				background-color: ' . $design_config['launcher_background_color'] . ';
			}
			.ephd-hd-toggle.ephd-widget--' . $widget_id . ':hover .ephd-hd-toggle__icon {
				background-color: ' . $design_config['launcher_background_hover_color'] . ';
			}
			.ephd-hd-toggle.ephd-widget--' . $widget_id . ' .ephd-hd-toggle__icon:before {
				color: ' . $design_config['launcher_icon_color'] . ';
			}
			.ephd-hd-toggle.ephd-widget--' . $widget_id . ':hover .ephd-hd-toggle__icon:before {
				color: ' . $design_config['launcher_icon_hover_color'] . ';
			}

			/* General*/
			#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-header__welcome {
				color: ' . $design_config['welcome_title_color'] . ';
			}

			#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-header__logo {
				width: ' . $global_config['logo_image_width'] . 'px !important;
			}' .

			/* Display styles only if FAQs tab and Search are enabled */
			( ( $display_mode == 'both' || $display_mode == 'faqs' ) && $widget_config['search_option'] == 'show_search' ?
				'/* Sub Heading */
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__breadcrumb__nav {
					color: ' . $design_config['breadcrumb_color'] . ';
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-sub-heading__breadcrumb-container {
					background-color: ' . $design_config['breadcrumb_background_color'] . ';
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__breadcrumb__nav .ephd-hd-faq__header__title-arrow {
					color: ' . $design_config['breadcrumb_arrow_color'] . ';
				}

				/* Back Navigation */
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__faq__back-btn .ephd-hd__faq__back-btn__icon,
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__faq__back-btn .ephd-hd__faq__back-btn__text {
					color: ' . $design_config['back_text_color'] . ';
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__faq__back-btn:hover .ephd-hd__faq__back-btn__icon,
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__faq__back-btn:hover .ephd-hd__faq__back-btn__text {
					color: ' . $design_config['back_text_color_hover_color'] . ';
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__faq__back-btn {
					background-color: ' . $design_config['back_background_color'] . ';
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__faq__back-btn:hover {
					background-color: ' . $design_config['back_background_color_hover_color'] . ';
				}

				/* Search results tab indicator */
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-results__tab--active__icon {
	                background-color: ' . $design_config['background_color'] . ';
	            }

				/* Search Results */
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__search-text, 
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__search-back-btn{
					color: ' . $design_config['main_title_text_color'] . ';
					font-size: ' . $global_config['main_title_font_size'] . 'px !important;
				}

				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-results__tab {
					color: ' . $design_config['found_faqs_article_tab_color'] . ' !important;
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-results__tab--active {
					color: ' . $design_config['found_faqs_article_active_tab_color'] . ' !important;
				}

				#ephd-help-dialog #ephd-hd__search_results__articles .ephd-hd_article-item__text,
				#ephd-help-dialog #ephd-hd__search_results__posts .ephd-hd_article-item__text {
					color: ' . $design_config['article_post_list_title_color'] . ' !important;
				}
				#ephd-help-dialog #ephd-hd__search_results__articles .ephd-hd_article-item__icon,
				#ephd-help-dialog #ephd-hd__search_results__posts .ephd-hd_article-item__icon {
					color: ' . $design_config['article_post_list_icon_color'] . ' !important;
				}
				
				/* Single Article */
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' #ephd-hd__search_results-cat-article-details .ephd-hd_article-link {
					color: ' . $design_config['single_article_read_more_text_color'] . '!important;
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' #ephd-hd__search_results-cat-article-details .ephd-hd_article-link:hover {
					color: ' . $design_config['single_article_read_more_text_hover_color'] . '!important;
				}
				
				/* Excerpt */
				#ephd-help-dialog .ephd-hd-article__excerpt-container .ephd-hd_article-title {
					color: #000 !important;
				}
				#ephd-help-dialog .ephd-hd-article__excerpt-container .ephd-hd-excerpt__body,
				#ephd-help-dialog .ephd-hd-article__excerpt-container .ephd-hd-excerpt__body * {
					color: #000 !important;
				}'
				: '' ) .

			/* Display styles only if FAQs is enabled */
			( $display_mode == 'both' || $display_mode == 'faqs' ?
				'/* FAQs */
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-faq__list__item-container {
					border-color: ' . $design_config['faqs_qa_border_color'] . ' !important;
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__item__question {
					color: ' . $design_config['faqs_question_text_color'] . ' !important;
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__element--active .ephd-hd__item__question {
					color: ' . $design_config['faqs_question_active_text_color'] . ' !important;
				}

	            /* FAQs - Answer Text Colors */	    
	            #ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__item__answer__text h1,
	            #ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__item__answer__text h2,
	            #ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__item__answer__text h3,
	            #ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__item__answer__text h4,
	            #ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__item__answer__text h5,
	            #ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__item__answer__text h6,
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__item__answer__text {
					color: ' . $design_config['faqs_answer_text_color'] . ' !important;
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__item__answer {
					background-color: ' . $design_config['faqs_answer_background_color'] . ' !important;
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-faq__list__item-container {
					background-color: ' . $design_config['faqs_question_background_color'] . ' !important;
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__element--active {
					background-color: ' . $design_config['faqs_question_active_background_color'] . ' !important;
				}'
				: '' ) .

			/* Display styles only if Contact Form is enabled */
			( $display_mode == 'both' || $display_mode == 'contact' ?
				'/* Contact Form */
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__contact-form-btn {
					background-color: ' . $design_config['contact_submit_button_color'] . ' !important;
					color: ' . $design_config['contact_submit_button_text_color'] . ' !important;
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__contact-form-btn:hover {
					background-color: ' . $design_config['contact_submit_button_hover_color'] . ' !important;
					color: ' . $design_config['contact_submit_button_text_hover_color'] . ' !important;
				}'
				. ( $global_config['contact_acceptance_checkbox'] == 'on' ?
				'#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd__contact-form-acceptance-container {
					background-color: ' . $design_config['contact_acceptance_background_color'] . ' !important;
				}' : '' )
				: '' ) . '

			/* --- Mobile Settings ---*/

			/* TABLET */
			@media only screen and ( max-width: ' . $global_config['tablet_break_point'] . 'px ) {
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' {
				    /* TODO hide for now
					width: ' . $global_config['container_tablet_width'] . 'px;
					*/
				}
			}
			/* MOBILE */
			@media only screen and ( max-width: ' . $global_config['mobile_break_point'] . 'px ) {
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' {
					width: 98%;
					margin: 0px 1%;
					right:0 !important;
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' #ephd-hd-body-container {
					/*height: calc(100vh - 286px) !important;*/
				}
				#ephd-help-dialog.ephd-widget--' . $widget_id . ' .ephd-hd-header__welcome__title {
					font-size: 20px !important;
				}
			}';
	}

	/**
     * Get CSS for dialog position
     *
	 * @param $global_config
	 * @param $widget_config
	 *
	 * @return string
	 */
	private static function get_position_inline_styles( $global_config, $widget_config ) {

		$is_admin_preview = is_admin();

		// Add Help Dialog bottom position styles
		$styles = $is_admin_preview ? '' : '
        /* HELP DIALOG BOTTOM DISTANCE */
        #ephd-help-dialog.ephd-widget--' . $widget_config['widget_id'] . ' {
            bottom: ' . ( $global_config['launcher_bottom_distance'] + 80 ) . 'px !important;
        }
        .ephd-hd-toggle.ephd-widget--' . $widget_config['widget_id'] . ' {
            bottom: ' . $global_config['launcher_bottom_distance'] . 'px !important;
        }';

        // If Initial Message is Active, display CSS
		if ( $widget_config['initial_message_toggle'] == 'show' ) {
			$styles .= '
			.ephd-widget--' . $widget_config['widget_id'] . ' .ephp-hd__initial-message {
                bottom: ' . ( $global_config['launcher_bottom_distance'] + 80 ) . 'px !important;
            }
			';
		}

		// Add Help Dialog left position styles
		if ( $global_config['launcher_location'] == 'left' ) {
			$styles .= $is_admin_preview ? '' : '
	        /* HELP DIALOG LOCATION */
	        #ephd-help-dialog, .ephd-hd-toggle {
	            right: unset !important;
	            left: 20px;
	        }';

			$styles .= '
	        .ephd-hd-toggle .ephd-hd-toggle__draft-notification {
	            left: 0px;
	        }
	        .ephd-hd-toggle .ephd-hd-toggle__draft-notification:after {
                left: 25px;
	        }
	        .ephp-hd__initial-message {
	            left: 20px;
	        }';

			$styles .= $is_admin_preview ? '' : '
	        /* MOBILE HELP DIALOG LOCATION */
            @media only screen and ( max-width: ' . $global_config['mobile_break_point'] . 'px ) {
                #ephd-help-dialog {
                    left: 0 !important;                
                }
            }';
		}

		return $styles;
	}

	/**
	 * Get CSS for hidden Help Dialog elements
	 *
	 * @param $global_config
	 *
	 * @return string
	 */
	private static function get_hidden_element_classes_inline_styles( $global_config ) {

		if ( empty( $global_config['kb_article_hidden_classes'] ) ) {
            return '';
		}

		$classes = explode( ',' , $global_config['kb_article_hidden_classes'] );

		// Parent element selector (iframe article body)
		$parent_selector = 'body.ephd-hd_article-desc__body';

        // sanitize classes string
		$result_classes = array();
		foreach ( $classes as $class ) {
			$text = EPHD_Utilities::sanitize_english_text( $class );
			$class = trim( $text );
            if ( ! empty( $class ) && ! is_numeric( $class ) ) {
	            $result_classes[] = $parent_selector . ' .' . strtolower( $class );
            }
		}

        if ( empty( $result_classes ) ) {
            return '';
        }

		$result_selector = implode( ', ', $result_classes );

		return '
        /* CUSTOM HIDDEN CLASSES */
        ' . $result_selector . ' {
            display: none !important;
        }';
	}

	/**
	 * TODO future: unused CSS classes until we enable KB articles
	 * Return inline styles for article details shown in iframe (defined in configs)
	 *
	 * @return string
	 */
	/*public function get_public_article_details_styles() {

		return
			'
			.ephd-hd_article-desc__body.ephd-widget--' . $this->design_configs['widget_id'] . ',
			.ephd-hd_article-desc__body.ephd-widget--' . $this->design_configs['widget_id'] . ' p{
				color: ' . $this->design_configs['single_article_desc_color'] . ' !important;
			}';
	}*/
}