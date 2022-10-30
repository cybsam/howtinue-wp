<?php

/**
 * Handle saving feature settings.
 */
class EPHD_Settings_Controller {

	const EPHD_DEBUG = 'ephd_debug';
	const TOGGLE_DEBUG_ACTION = 'ephd_toggle_debug';
	const DOWNLOAD_DEBUG_INFO_ACTION = 'ephd_download_debug_info';

	public function __construct() {
		add_action( 'admin_init', array( 'EPHD_Settings_Controller', 'download_debug_info' ) );

		add_action( 'wp_ajax_' . self::TOGGLE_DEBUG_ACTION, array( 'EPHD_Settings_Controller', 'toggle_debug' ) );
		add_action( 'wp_ajax_nopriv_' . self::TOGGLE_DEBUG_ACTION, array( 'EPHD_Utilities', 'user_not_logged_in' ) );
	}

	/**
	 * Triggered when user clicks to toggle debug.
	 */
	public static function toggle_debug() {

		// wp_die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		$is_debug_on = EPHD_Utilities::get_wp_option( self::EPHD_DEBUG, false );

		$is_debug_on = empty( $is_debug_on ) ? 1 : 0;

		EPHD_Utilities::save_wp_option( self::EPHD_DEBUG, $is_debug_on );

		if ( ! $is_debug_on ) {
			delete_transient( '_ephd_advanced_search_debug_activated' );
		}

		EPHD_Utilities::ajax_show_info_die( '' );
	}

	/**
	 * Generates a System Info download file
	 */
	public static function download_debug_info() {

		if ( EPHD_Utilities::post('action') != self::DOWNLOAD_DEBUG_INFO_ACTION ) {
			return;
		}

		// wp_die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		EPHD_Utilities::save_wp_option( self::EPHD_DEBUG, false );

		nocache_headers();

		header( 'Content-Type: text/plain' );
		header( 'Content-Disposition: attachment; filename="echo-debug-info.txt"' );

		$output = self::display_debug_data();
		echo wp_strip_all_tags( $output );

		die();
	}

	public static function display_debug_data() {
		/** @var $wpdb Wpdb */
		global $wpdb;

		// ensure user has correct permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			return __( 'You do not have permission to edit Help Dialog.', 'help-dialog' );
		}

		$ephd_version_first = EPHD_Utilities::get_wp_option( 'ephd_version_first', 'N/A' );
		$ephd_version = EPHD_Utilities::get_wp_option( 'ephd_version', 'N/A' );
		$ephd_purge_date = EPHD_Utilities::get_wp_option( 'ephd_analytics_purge_date', 'N/A' );

		$output = '<textarea class="ephd-debug__content" rows="30" cols="150">';

		// display HD configuration
		$output .= "HD Configurations:\n";
		$output .= "==================\n";
		$output .= "HD first version: " . $ephd_version_first . "\n";
		$output .= "HD version: " . $ephd_version . "\n\n\n";
		$output .= "Last purge date: " . $ephd_purge_date . "\n\n\n";

		// retrieve HD config specs
		$specs = EPHD_Config_Specs::get_all_specs();

		// retrieve custom post type labels
		$cpt_labels = EPHD_Utilities::get_post_type_labels( [] );

        // Global Config
		$global_config = ephd_get_instance()->global_config_obj->get_config();
		$output .= "DB Global Config:\n\n";
		foreach( $global_config as $name => $value ) {
			if ( is_array( $value ) ) {
				$value = EPHD_Utilities::get_variable_string( $value );
			}
			$label = empty( $specs[$name]['label'] ) ? 'unknown' : $specs[$name]['label'];
			$output .= '- ' . $label . ' [' . $name . ']' . ' => ' . $value . "\n";
		}

		// Design Config
		$designs_config = ephd_get_instance()->designs_config_obj->get_config();
		$output .= "\n\nDB Design Config:\n";
		foreach( $designs_config as $design ) {
			$output .= "\n-Design #" . $design['design_id'] . " (" . $design['design_name'] . "):\n";
            foreach ( $design as $name => $value ) {
    			if ( is_array( $value ) ) {
    				$value = EPHD_Utilities::get_variable_string( $value );
			    }
			    $label = empty( $specs[$name]['label'] ) ? 'unknown' : $specs[$name]['label'];
			    $output .= '    - ' . $label . ' [' . $name . ']' . ' => ' . $value . "\n";
		    }
		}

		// Widgets Config
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config();
		$output .= "\n\nDB Widgets Config:\n";
		foreach( $widgets_config as $widget ) {
			$output .= "\n- Widget #" . $widget['widget_id'] . " (" . $widget['widget_name'] . "):\n";
			foreach ( $widget as $name => $value ) {

				if ( is_array( $value ) ) {

                    // show URLs for pages and posts locations
                    if ( in_array( $name, ['location_pages_list', 'location_posts_list'] ) ) {
	                    foreach ( $value as $key => $location_id ) {
                            if ( $location_id == 0 ) {
	                            $value[$key] = home_url();
                            }
		                    if ( $location_id > 0 ) {
			                    $value[$key] = get_permalink( $location_id );
		                    }
		                    $value[$key] = empty( $value[$key] ) ? $location_id : $value[$key];
	                    }
                    }
					// show CPT location name
                    if ( in_array( $name, ['location_cpts_list'] ) ) {
	                    foreach ( $value as $key => $location_id ) {
		                    $value[$key] = isset( $cpt_labels[$location_id] ) ? $cpt_labels[$location_id] : $location_id;
	                    }
					}
                    $value = EPHD_Utilities::get_variable_string( $value );
				}
				$label = empty( $specs[$name]['label'] ) ? 'unknown' : $specs[$name]['label'];
				$label = in_array( $name, ['location_pages_list', 'location_posts_list', 'location_cpts_list', 'faqs_sequence'] ) ? 'Locations' : $label;
				$output .= '    - ' . $label . ' [' . $name . ']' . ' => ' . $value . "\n";
			}
		}

		// Contact Forms Config
		$contact_forms_config = ephd_get_instance()->contact_forms_config_obj->get_config();
		$output .= "\n\nDB Contact Forms Config:\n";
		foreach( $contact_forms_config as $contact_form ) {
			$output .= "\n- Contact Form #" . $contact_form['contact_form_id'] . " (" . $contact_form['contact_form_name'] . "):\n";
			foreach ( $contact_form as $name => $value ) {
				if ( is_array( $value ) ) {
					$value = EPHD_Utilities::get_variable_string( $value );
				}
				$label = empty( $specs[$name]['label'] ) ? 'unknown' : $specs[$name]['label'];
				$output .= '    - ' . $label . ' [' . $name . ']' . ' => ' . $value . "\n";
			}
		}

		// display PHP and WP settings
		$output .= self::get_system_info();

		// display error logs
		$output .= "\n\nERROR LOG:\n";
		$output .= "==========\n";
		$logs = EPHD_Logging::get_logs();
		foreach( $logs as $log ) {
			$output .= empty( $log['plugin'] ) ? '' : $log['plugin'] . " ";
			$output .= empty( $log['date'] ) ? '' : $log['date'] . "\n";
			$output .= empty( $log['message'] ) ? '' : $log['message'] . "\n";
			$output .= empty( $log['trace'] ) ? '' : $log['trace'] . "\n\n";
		}

		$output .= '</textarea>';

		return $output;
	}

	/**
	 * Based on EDD system-info.php file
	 * @return string
	 */
	private static function get_system_info() {
		/** @var $wpdb Wpdb */
		global $wpdb;

		/** @var $theme_data WP_Theme */
		$theme_data = wp_get_theme();
		/** @noinspection PhpUndefinedFieldInspection */
		$theme = $theme_data->Name . ' ' . $theme_data->Version;

		ob_start();     ?>


		PHP and WordPress Information:
		==============================

		Multisite:                <?php echo ( is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ); ?>

		SITE_URL:                 <?php echo site_url() . "\n"; ?>
		HOME_URL:                 <?php echo home_url() . "\n"; ?>

		WordPress Version:        <?php echo get_bloginfo( 'version' ) . "\n"; ?>
		Permalink Structure:      <?php echo get_option( 'permalink_structure', '' ) . "\n"; ?>
		Active Theme:             <?php echo esc_html( $theme ) . "\n"; ?>
		Host:                     <?php echo ( defined( 'WPE_APIKEY' ) ? 'Host: WP Engine' : '<unknown>' . "\n" ); ?>
		WP Memory Limit:          <?php echo size_format( (int) WP_MEMORY_LIMIT * 1048576 ) . "\n"; ?>
		PHP Version:              <?php echo PHP_VERSION . "\n"; ?>

		PHP Post Max Size:        <?php echo ini_get( 'post_max_size' ) . "\n"; ?>
		PHP Time Limit:           <?php echo ini_get( 'max_execution_time' ) . "\n"; ?>
		PHP Max Input Vars:       <?php echo ini_get( 'max_input_vars' ) . "\n"; ?>
		WP_DEBUG:                 <?php echo defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' . "\n" : 'Disabled' . "\n" : 'Not set' . "\n" ?>

		WP Table Prefix:          <?php echo "Length: ". strlen( $wpdb->prefix );   ?>

		DISPLAY ERRORS:           <?php echo ( ini_get( 'display_errors' ) ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A'; ?><?php echo "\n"; ?>
		FSOCKOPEN:                <?php echo ( function_exists( 'fsockopen' ) ) ? 'Your server supports fsockopen.' : 'Your server does not support fsockopen.'; ?><?php echo "\n"; ?>
		cURL:                     <?php echo ( function_exists( 'curl_init' ) ) ? 'Your server supports cURL:' : 'Your server does not support cURL.'; ?><?php echo "\n";

		if ( function_exists( 'curl_init' ) ) {
			$curl_values = curl_version();
			echo "\n\t\t\t\tVersion: " . $curl_values["version"];
			echo "\n\t\t\t\tSSL Version: " . $curl_values["ssl_version"];
			echo "\n\t\t\t\tLib Version: " . $curl_values["libz_version"] . "\n";
		}		?>

		SOAP Client:              <?php echo ( class_exists( 'SoapClient' ) ) ? 'Your server has the SOAP Client enabled.' : 'Your server does not have the SOAP Client enabled.'; ?><?php echo "\n";

		$plugins = get_plugins();
		$active_plugins = get_option( 'active_plugins', array() );

		echo "\n\n";
		echo "PLUGINS:	         \n\n";

		foreach ( $plugins as $plugin_path => $plugin ) {
			// If the plugin isn't active, don't show it.
			if ( ! in_array( $plugin_path, $active_plugins ) )
				continue;

			echo "		" . $plugin['Name'] . ': ' . $plugin['Version'] ."\n";
		}

		if ( is_multisite() ) {		?>
			NETWORK ACTIVE PLUGINS:		<?php  echo "\n";

			$plugins = wp_get_active_network_plugins();
			$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

			foreach ( $plugins as $plugin_path ) {
				$plugin_base = plugin_basename( $plugin_path );

				// If the plugin isn't active, don't show it.
				if ( ! array_key_exists( $plugin_base, $active_plugins ) ) {
					continue;
				}

				$plugin = get_plugin_data( $plugin_path );

				echo "		" . $plugin['Name'] . ': ' . $plugin['Version'] ."\n";
			}
		}

		return ob_get_clean();
	}

	/**
	 * Display Debug page.
	 *
	 * @return false|string
	 */
	public static function display_debug_info() {

		$is_debug_on = EPHD_Utilities::get_wp_option( self::EPHD_DEBUG, false );

		ob_start();     ?>

		<div id="ephd_debug_info_tab_page">     <?php

			EPHD_HTML_Elements::submit_button_v2(
				$is_debug_on ? __( 'Disable Debug', 'help-dialog' ) : __( 'Enable Debug', 'help-dialog' ),
				self::TOGGLE_DEBUG_ACTION,
				'ephd-debug__toggle',
				'',
				true,
				'',
				'ephd-primary-btn'
			);

			if ( $is_debug_on ) {       ?>
				<h3 class="ephd-debug__title"><?php echo esc_html__( 'Debug Information', 'help-dialog' ) . ':'; ?></h3>     <?php

				echo self::display_debug_data();        ?>

				<form action="<?php echo esc_url( admin_url( 'admin.php?page=ephd-help-dialog-advanced-config#debug' ) ); ?>" method="post" dir="ltr">   <?php

					EPHD_HTML_Elements::submit_button_v2(
							__( 'Download System Information', 'help-dialog' ),
							self::DOWNLOAD_DEBUG_INFO_ACTION, 'ephd-debug__download-info',
							'',
							true,
							'',
							'ephd-primary-btn'
					);      ?>

				</form>     <?php
			}    ?>

			<div id="ephd-ajax-in-progress-debug-switch" style="display: none;">
				<?php esc_html_e( 'Switching debug', 'help-dialog' ); ?>... <img class="ephd-ajax waiting" style="height: 30px;" src="<?php echo esc_url( Echo_Help_Dialog::$plugin_url . 'img/loading_spinner.gif' ); ?>">
			</div>

		</div>      		<?php

		return ob_get_clean();
	}
}