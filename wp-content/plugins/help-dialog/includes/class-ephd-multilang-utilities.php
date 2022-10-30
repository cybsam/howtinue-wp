<?php

/**
 * Various utility functions for multilanguages
 *
 * @copyright   Copyright (C) 2021, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Multilang_Utilities {

	static $POLYLANG = 'polylang';
	static $WPML = 'wpml';
	static $TRP = 'trp';

	/**
	 * Detect if the site multilanguage. Return false or string - type of multiline plugin WPML or Polylang
	 * @return string
	 */
	public static function get_multilang_plugin_name() {

		if ( defined( 'ICL_SITEPRESS_VERSION' ) && self::is_multilang_plugin_methods_exist( self::$WPML ) ) {
			return self::$WPML;
		}

		if ( defined( 'POLYLANG_VERSION' ) && self::is_multilang_plugin_methods_exist( self::$POLYLANG ) ) {
			return self::$POLYLANG;
		}

		if ( defined( 'TRP_PLUGIN_VERSION' ) && self::is_multilang_plugin_methods_exist( self::$TRP ) ) {
			return self::$TRP;
		}

		return '';
	}

	/**
	 * Get languages slug, flag icon, title
	 * if there only 1 language return empty array
	 * @return array
	 */
	public static function get_languages_data() {
		$data = [];

		$plugin = self::get_multilang_plugin_name();
		if ( empty( $plugin ) ) {
			return $data;
		}

		// polylang plugin
		if ( $plugin == self::$POLYLANG ) {

			$languages_names = pll_languages_list( [ 'fields' => 'name' ] );
			$languages_slugs = pll_languages_list( [ 'fields' => 'slug' ] );
			$languages_flags = pll_languages_list( [ 'fields' => 'flag_url' ] );

			foreach ( $languages_names as $key => $name ) {
				$data[ $languages_slugs[$key] ] = [
					'slug' => $languages_slugs[$key],
					'name' => $languages_names[$key],
					'flag_url' => $languages_flags[$key]
				];
			}
		}

		// TranslatePress plugin
		if ( $plugin == self::$TRP ) {
			$trp = TRP_Translate_Press::get_trp_instance();

			$trp_settings_component = $trp->get_component( 'settings' );
			$trp_settings           = $trp_settings_component->get_settings();

			$trp_languages_component = $trp->get_component( 'languages' );
			$trp_languages           = $trp_languages_component->get_wp_languages();

			foreach ( $trp_settings['publish-languages'] as $language_code ) {

				$slug = $trp_settings['url-slugs'][$language_code];

				$flags_path = apply_filters( 'trp_flags_path', TRP_PLUGIN_URL . 'assets/images/flags/', $language_code );
				$flag_file_name = apply_filters( 'trp_flag_file_name', $language_code . '.png', $language_code );

				$data[$slug] = [
					'slug'     => $slug,
					'name'     => $trp_languages[$language_code]['native_name'],
					'flag_url' => $flags_path . $flag_file_name
				];
			}

		}

		// TODO wpml
		return $data;
	}

	/**
	 * Get array with the post ids with all translates. Return array [ en => 5 ]. If not multilang return [ default => $post_id ]
	 *
	 * @param $post_id
	 *
	 * @return array
	 */
	public static function get_translated_posts_ids( $post_id ) {

		$plugin = self::get_multilang_plugin_name();
		if ( empty( $plugin ) ) {
			return [ 'default' => $post_id ];
		}

		$posts_ids = [];

		// polylang plugin
		if ( $plugin == self::$POLYLANG ) {

			$posts_ids = pll_get_post_translations( $post_id );

			// if there is no translations - return initial values
			if ( ! $posts_ids ) {
				$posts_ids['default'] = $post_id;
			}
		}

		// TODO add wpml

		return $posts_ids;
	}

	/**
	 * Update post language if need and language exist
	 *
	 * @param $post_id
	 * @param $language
	 */
	public static function update_post_language( $post_id, $language ) {

		$plugin = self::get_multilang_plugin_name();
		if ( empty( $plugin ) ) {
			return;
		}

		if ( $plugin == self::$POLYLANG ) {
			pll_set_post_language( $post_id, $language );
		}
	}

	/**
	 * Associate 2 posts as translated
	 * $posts_data is array [ en => 6, de => 5 ]
	 * posts data should be checked before using this function!
	 *
	 * @param $posts_data
	 * @return bool
	 */
	public static function save_post_translations( $posts_data ) {

		$plugin = self::get_multilang_plugin_name();
		if ( empty( $plugin ) ) {
			return true;
		}

		if ( $plugin == self::$POLYLANG ) {
			pll_save_post_translations( $posts_data );
		}

		// TODO add WPML

		return true;
	}

	/**
	 * Get post language. Return string.
	 *
	 * @param $post_id
	 * @return bool
	 */
	public static function get_post_language( $post_id ) {

		$plugin = self::get_multilang_plugin_name();
		if ( empty( $plugin ) ) {
			return '';
		}

		if ( $plugin == self::$POLYLANG ) {
			$default_language = pll_get_post_language( $post_id );
			return empty( $default_language ) ? '' : $default_language;
		}

		// TODO add WPML

		return '';
	}

	/**
	 * Get current language code
	 *
	 * @param bool $return_language_name
	 * @param string $default
	 *
	 * @return string
	 */
	public static function get_current_language( $return_language_name=false, $default = 'en' ) {

		$locale = get_locale();   // based on frontend output rather than user-specific language

		// get locale based on dashboard switcher (for admin panel only)
		if ( is_admin() ) {
			$plugin = self::get_multilang_plugin_name();

			// POLYLANG
			if ( ! empty( $plugin ) && $plugin == self::$POLYLANG ) {
				$pll_locale = pll_current_language();
				$locale = empty( $pll_locale ) ? $locale : $pll_locale;
			}
			// TODO add WPML
		}

		$language = empty( $locale ) ? $default : substr( $locale, 0, 2 );

		// Get language name
		if ( ! empty( $return_language_name ) ) {

			require_once ABSPATH . 'wp-admin/includes/translation-install.php';

			$translations = wp_get_available_translations();
			$language = isset( $translations[$language] ) ? $translations[$language]['native_name'] : __( 'Unknown', 'help-dialog' );
		}

		return $language;
	}

	/**
	 * Get current language code
	 *
	 * @return string
	 */
	public static function get_default_language() {

		$default = self::get_current_language();

		$plugin = self::get_multilang_plugin_name();
		if ( empty( $plugin ) ) {
			return $default;
		}

		// polylang plugin
		if ( $plugin == self::$POLYLANG ) {
			$default_language = pll_default_language();
			return empty( $default_language ) ? $default : $default_language;
		}

		// TranslatePress plugin
		if ( $plugin == self::$TRP ) {
			/** @noinspection PhpUndefinedClassInspection */
			$trp = TRP_Translate_Press::get_trp_instance();

			/** @noinspection PhpUndefinedMethodInspection */
			$trp_settings_component = $trp->get_component( 'settings' );
			/** @noinspection PhpUndefinedMethodInspection */
			$trp_settings           = $trp_settings_component->get_settings();

			$default_language = $trp_settings['url-slugs'][$trp_settings['default-language']];

			return empty( $default_language ) ? $default : $default_language;
		}

		// TODO add WPML

		return $default;
	}

	/**
	 * Check is all methods we use from multilang plugins are defined
	 *
	 * @param $plugin
	 * @return bool
	 */
	public static function is_multilang_plugin_methods_exist( $plugin ) {

		// polylang plugin
		if ( $plugin == self::$POLYLANG ) {
			return
				function_exists( 'pll_default_language' ) &&
				function_exists( 'pll_current_language' ) &&
				function_exists( 'pll_languages_list' ) &&
				function_exists( 'pll_get_post_translations' ) &&
				function_exists( 'pll_set_post_language' ) &&
				function_exists( 'pll_save_post_translations' ) &&
				function_exists( 'pll_get_post_language' );
		}

		// TranslatePress plugin
		if ( $plugin == self::$TRP ) {

			if ( method_exists( 'TRP_Translate_Press', 'get_trp_instance' ) ) {

				/** @noinspection PhpUndefinedClassInspection */
				$instance = TRP_Translate_Press::get_trp_instance();

				if ( method_exists( $instance, 'get_component' ) ) {
					$settings = $instance->get_component( 'settings' );
					$languages = $instance->get_component( 'languages' );

					if ( method_exists( $settings, 'get_settings' ) && method_exists( $languages, 'get_wp_languages' ) ) {
						return true;
					}
				}
			}
			return false;
		}

		// WPML plugin
		if ( $plugin == self::$WPML ) {
			// TODO
		}

		return false;
	}

} 