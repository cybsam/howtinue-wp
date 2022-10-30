<?php

/**
 * Various utility functions
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Core_Utilities {

	/**
	 * Retrieve user IP address if possible.
	 *
	 * @return string
	 */
	public static function get_ip_address() {

		$ip_params = array( 'HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' );
		foreach ( $ip_params as $ip_param ) {
			if ( ! empty($_SERVER[$ip_param]) ) {
				foreach ( explode( ',', $_SERVER[$ip_param] ) as $ip ) {
					$ip = trim( $ip );

					// validate IP address
					if ( filter_var( $ip, FILTER_VALIDATE_IP ) !== false ) {
						return esc_attr( $ip );
					}
				}
			}
		}

		return '';
	}

	/**
	 * Return url of a random page where the widget will be shown.
	 * $widget = object from config array ephd_get_instance()->widgets_config_obj->get_config()
	 *
	 * @param $widget
	 * @return false|string
	 */
	public static function get_first_widget_page_url( $widget ) {

		if ( empty( $widget ) ) {
			return home_url();
		}

		// check home page 
		if ( in_array( EPHD_Config_Specs::HOME_PAGE, $widget['location_pages_list'] ) ) {
			return home_url();
		}

		$types = ['page', 'post', 'cpt'];
		
		// check if there is some included page/post/cpt
		foreach ( $types as $type ) {

			if ( empty( $widget['location_' . $type . 's_list'] ) ) {
				continue;
			}

			foreach ( $widget['location_' . $type . 's_list'] as $page_id ) {

				$post = get_post( $page_id );
				if ( $post && in_array( $post->post_status, [ 'private', 'publish', 'draft' ] ) && empty( $post->post_mime_type ) ) {
					return get_the_permalink( $post );
				}
			}
		}

		return false;
	}

	/**
	 * Is given ADMIN page part of the widget and not excluded?
	 *
	 * @param $page_url
	 * @param $widget
	 * @return bool
	 */
	public static function is_admin_page_in_widget( $page_url, $widget ) {

		if ( empty( $widget ) || empty( $widget->widgets ) || ! isset( $widget->widgets['admin_pages'] ) ) {
			return false;
		}

		foreach( $widget->widgets['admin_pages'] as $admin_page_data ) {
			if ( ! empty( $admin_page_data['url'] ) && $admin_page_data['url'] == $page_url ) {
				return true;
			}
		}

		return false;
	}

	public static function is_help_dialog_admin_page( $request_page ) {
		return in_array( $request_page, ['ephd-help-dialog', 'ephd-help-dialog-advanced-config', 'ephd-help-dialog-widgets', 'ephd-help-dialog-faqs-articles', 'ephd-help-dialog-contact-form', 'ephd-plugin-analytics', 'ephd-help-dialog-channels'] );
	}

	/**
	 * Get link to an admin page
	 *
	 * @param $url_param
	 * @param $label_text
	 * @param bool $target_blank
	 * @param string $css_class
	 * @return string
	 */
	public static function get_admin_page_link( $url_param, $label_text, $target_blank=true, $css_class='' ) {
		return '<a class="ephd-hd__wizard-link ' .$css_class. '" href="' . esc_url( admin_url( '/admin.php' . ( empty($url_param) ? '' : '?' ) . $url_param ) ) . '"' . ( empty( $target_blank ) ? '' : ' target="_blank"' ) . '>' . wp_kses_post( $label_text ) . '</a>';
	}

	/**
	 * Show WordPress Editor for user to edit Question and Answer
	 *
	 * @param $widget_id
	 */
	public static function display_wp_editor( $widget_id ) {

		$languages = EPHD_Multilang_Utilities::get_languages_data();    ?>

		<div class="ephd-fp__wp-editor">
		<div class="ephd-fp__wp-editor__overlay"></div>
		<form id="ephd-fp__article-form" class="<?php echo $languages ? 'ephd-fp__article-form--multilang' : ''; ?>"><?php

			if ( $languages && count( $languages ) > 1 ) { ?>
				<div class="ephd-fp__wp-editor__languages"><?php
					foreach ( $languages as $language ) { ?>
						<div class="ephd-fp__wp-editor__language ephd-fp__wp-editor__language-<?php echo esc_attr( $language['slug'] ); ?>" data-slug="<?php echo esc_attr( $language['slug'] ); ?>">

							<div class="ephd-fp__wp-editor__language__flag"> <img src="<?php echo esc_url( $language['flag_url'] ); ?>"></div>
							<div class="ephd-fp__wp-editor__language__text"><?php echo esc_attr( $language['name'] ); ?></div>

						</div>  <?php
					}       ?>
				</div>  <?php
			}   ?>

			<input type="hidden" id="widget_id" name="widget_id" value="<?php echo esc_attr( $widget_id ); ?>">
			<input type="hidden" id="question_id" name="question_id" placeholder="<?php esc_attr_e( 'Question', 'help-dialog' ); ?>">
			<div class="ephd-fp__wp-editor__question">
				<h4><?php esc_html_e( 'Question', 'help-dialog' ); ?></h4>
				<div class="ephd-fp__wp-editor__question__input-container">
                    <input type="text" id="ephd-fp__wp-editor__question-title" name="ephd-fp__wp-editor__question-title" required maxlength="200">
                    <div class="ephd-characters_left"><span class="ephd-characters_left-counter">200</span>/<span>200</span></div>
                </div>
            </div>
			<div class="ephd-fp__wp-editor__answer">
				<h4><?php esc_html_e( 'Answer', 'help-dialog' ); ?></h4><?php
				wp_editor( '', 'ephd-fp__wp-editor', array( 'media_buttons' => false ) ); ?>
				<div class="ephd-characters_left"><span class="ephd-characters_left-counter">1500</span>/<span>1500</span></div>
			</div>
			<div class="ephd-fp__wp-editor__buttons">				<?php
				EPHD_HTML_Elements::submit_button_v2( __( 'Save', 'help-dialog' ), 'ephd_save_question_data', 'ephd__help_editor__action__save', '', true, '', 'ephd-success-btn' );
				EPHD_HTML_Elements::submit_button_v2( __( 'Cancel', 'help-dialog' ), '', 'ephd__help_editor__action__cancel', '', '', '', 'ephd-error-btn' );				?>
			</div>
		</form>
		</div><?php
	}

	/**
	 * Return sales page for given plugin
	 *
	 * @param $plugin_name
	 * @return String
	 */
	public static function get_plugin_sales_page( $plugin_name ) {
		switch( $plugin_name ) {
			case 'pro':
				return 'https://www.helpdialog.com/help-dialog-pro/';
		}

		return '';
	}
} 