<?php
/**
 * KnowX\KnowX\Sidebars\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Sidebars;

use KnowX\KnowX\Component_Interface;
use KnowX\KnowX\Templating_Component_Interface;
use function add_action;
use function add_filter;
use function register_sidebar;
//use function esc_html__;
use function is_active_sidebar;
use function dynamic_sidebar;

/**
 * Class for managing sidebars.
 *
 * Exposes template tags:
 * * `knowx()->is_right_sidebar_active()`
 * * `knowx()->display_primary_sidebar()`
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/
 */
class Component implements Component_Interface, Templating_Component_Interface {

	const LEFT_SIDEBAR_SLUG  = 'sidebar-left';
	const RIGHT_SIDEBAR_SLUG = 'sidebar-right';
	const BBPRESS_LEFT_SIDEBAR_SLUG = 'bbpress-sidebar-left';
	const BBPRESS_RIGHT_SIDEBAR_SLUG = 'bbpress-sidebar-right';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'sidebars';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'widgets_init', [ $this, 'action_register_sidebars' ] );
		add_filter( 'body_class', [ $this, 'filter_body_classes' ] );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `knowx()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() : array {
		return [
			'is_left_sidebar_active'  => [ $this, 'is_left_sidebar_active' ],
			'display_left_sidebar'    => [ $this, 'display_left_sidebar' ],
			'is_right_sidebar_active' => [ $this, 'is_right_sidebar_active' ],
			'display_right_sidebar'   => [ $this, 'display_right_sidebar' ],

			'display_bbpress_left_sidebar'    => [ $this, 'display_bbpress_left_sidebar' ],
			'is_bbpress_left_sidebar_active'  => [ $this, 'is_bbpress_left_sidebar_active' ],
			'display_bbpress_right_sidebar'    => [ $this, 'display_bbpress_right_sidebar' ],
			'is_bbpress_right_sidebar_active'  => [ $this, 'is_bbpress_right_sidebar_active' ],
		];
	}

	/**
	 * Registers the sidebars.
	 */
	public function action_register_sidebars() {
		register_sidebar(
			[
				'name'          => esc_html__( 'Right Sidebar', 'knowx' ),
				'id'            => static::RIGHT_SIDEBAR_SLUG,
				'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Left Sidebar', 'knowx' ),
				'id'            => static::LEFT_SIDEBAR_SLUG,
				'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);

		if ( function_exists('is_bbpress') ) {
    		register_sidebar(
				[
					'name'          => esc_html__( 'bbPress Left Sidebar', 'knowx' ),
					'id'            => static::BBPRESS_LEFT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);

			register_sidebar(
				[
					'name'          => esc_html__( 'bbPress Right Sidebar', 'knowx' ),
					'id'            => static::BBPRESS_RIGHT_SIDEBAR_SLUG,
					'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				]
			);
        }

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer 1', 'knowx' ),
				'id'            => 'footer-1',
				'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer 2', 'knowx' ),
				'id'            => 'footer-2',
				'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer 3', 'knowx' ),
				'id'            => 'footer-3',
				'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer 4', 'knowx' ),
				'id'            => 'footer-4',
				'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);
	}

	/**
	 * Adds custom classes to indicate whether a sidebar is present to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes( array $classes ) : array {
		$default_sidebar = get_theme_mod( 'sidebar_option', knowx_defaults( 'sidebar-option' ) );

		if ( $this->is_left_sidebar_active() && $default_sidebar == 'left' ) {
			global $template;

			if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
				$classes[] = 'has-sidebar-left';
			}
		} elseif ( $this->is_right_sidebar_active() && $default_sidebar == 'right' ) {
			global $template;

			if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
				$classes[] = 'has-sidebar-right';
			}
		} elseif ( $this->is_right_sidebar_active() && $this->is_right_sidebar_active() && $default_sidebar == 'both' ) {
			global $template;

			if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
				$classes[] = 'has-sidebar-both';
			}
		}

		//bbPress
		if ( function_exists('is_bbpress') ) {
			$bbpress_sidebar = get_theme_mod( 'bbpress_sidebar_option', knowx_defaults( 'bbpress-sidebar-option' ) );

			if ( $this->is_bbpress_left_sidebar_active() && $bbpress_sidebar == 'left' ) {
				global $template;

				if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
					$classes[] = 'has-bbpress-sidebar-left';
				}
			} elseif ( $this->is_bbpress_right_sidebar_active() && $bbpress_sidebar == 'right' ) {
				global $template;

				if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
					$classes[] = 'has-bbpress-sidebar-right';
				}
			} elseif ( $this->is_bbpress_right_sidebar_active() && $this->is_bbpress_right_sidebar_active() && $bbpress_sidebar == 'both' ) {
				global $template;

				if ( ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] ) ) {
					$classes[] = 'has-bbpress-sidebar-both';
				}
			}
		}

		return $classes;
	}

	/**
	 * Checks whether the left sidebar is active.
	 *
	 * @return bool True if the left sidebar is active, false otherwise.
	 */
	public function is_left_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the left sidebar.
	 */
	public function display_left_sidebar() {
		dynamic_sidebar( static::LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the right sidebar is active.
	 *
	 * @return bool True if the right sidebar is active, false otherwise.
	 */
	public function is_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the right sidebar.
	 */
	public function display_right_sidebar() {
		dynamic_sidebar( static::RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the bbpress left sidebar is active.
	 *
	 * @return bool True if the bbpress left sidebar is active, false otherwise.
	 */
	public function is_bbpress_left_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::BBPRESS_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the bbpress left sidebar.
	 */
	public function display_bbpress_left_sidebar() {
		dynamic_sidebar( static::BBPRESS_LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the bbpress right sidebar is active.
	 *
	 * @return bool True if the bbpress right sidebar is active, false otherwise.
	 */
	public function is_bbpress_right_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::BBPRESS_RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the bbpress right sidebar.
	 */
	public function display_bbpress_right_sidebar() {
		dynamic_sidebar( static::BBPRESS_RIGHT_SIDEBAR_SLUG );
	}
}