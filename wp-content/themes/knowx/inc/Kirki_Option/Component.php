<?php
/**
 * KnowX\KnowX\Typography_Options\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Kirki_Option;

use function add_action;
use function add_filter;
use KnowX\KnowX\Component_Interface;

/**
 * Class for adding custom background support.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'kirki_option';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'add_panels_and_sections' ) );
		add_filter( 'kirki/fields', array( $this, 'add_fields' ) );
		add_filter( 'body_class', array( $this, 'site_width_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_sidebar_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_header_classes' ) );
	}

	/**
	 * Site width body class.
	 */
	public function site_width_body_classes( array $classes ) : array {
		$classes[] = 'layout-' . get_theme_mod( 'site_layout', knowx_defaults( 'site-layout' ) );

		return $classes;
	}

	/**
	 * Site sticky sidebar body class.
	 */
	public function site_sticky_sidebar_body_classes( array $classes ) : array {

		$sticky_sidebar = get_theme_mod( 'sticky_sidebar_option', knowx_defaults( 'sticky-sidebar' ) );
		if ( $sticky_sidebar ) {
			$classes[] = 'sticky-sidebar-enable';
		}

		return $classes;
	}

	/**
	 * Site sticky header body class.
	 */
	public function site_sticky_header_classes( array $classes ): array {
		$sticky_header = get_theme_mod( 'site_sticky_header', knowx_defaults( 'site-sticky-header' ) );
		if ( $sticky_header ) {
			$classes[] = 'sticky-header';
		}

		return $classes;
	}

	/**
	 * Add Customizer Section
	 */
	public function add_panels_and_sections( $wp_customize ) {
		// Site Layout.
		$wp_customize->add_panel(
			'site_layout_panel',
			array(
				'title'       => esc_html__( 'General', 'knowx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_layout',
			array(
				'title'       => esc_html__( 'Site Layout', 'knowx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Site Loader.
		$wp_customize->add_section(
			'site_loader',
			array(
				'title'       => esc_html__( 'Site Loader', 'knowx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Typography.
		$wp_customize->add_panel(
			'typography_panel',
			array(
				'title'       => esc_html__( 'Typography', 'knowx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_title_typography_section',
			array(
				'title'       => esc_html__( 'Site Title', 'knowx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'headings_typography_section',
			array(
				'title'       => esc_html__( 'Headings', 'knowx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'menu_typography_section',
			array(
				'title'       => esc_html__( 'Menu', 'knowx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'body_typography_section',
			array(
				'title'       => esc_html__( 'Body', 'knowx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		// Site Header.
		$wp_customize->add_section(
			'site_header_section',
			array(
				'title'       => esc_html__( 'Site Header', 'knowx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Sub Header.
		$wp_customize->add_section(
			'site_sub_header_section',
			array(
				'title'       => esc_html__( 'Site Sub Header', 'knowx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Skin.
		$wp_customize->add_section(
			'site_skin_section',
			array(
				'title'       => esc_html__( 'Site Skin', 'knowx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Blog Layout.
		$wp_customize->add_section(
			'site_blog_section',
			array(
				'title'       => esc_html__( 'Site Blog', 'knowx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Sidebar Layout.
		$wp_customize->add_section(
			'site_sidebar_layout',
			array(
				'title'       => esc_html__( 'Site Sidebar', 'knowx' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Knowledge Base.
		$wp_customize->add_panel(
			'knowledge_panel',
			array(
				'title'       => esc_html__( 'Knowledge Base', 'knowx' ),
				'priority'    => 11,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_knowledge_section_one',
			array(
				'title'       => esc_html__( 'Knowledge Base Layout One', 'knowx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'knowledge_panel',
			)
		);

		$wp_customize->add_section(
			'site_knowledge_section_two',
			array(
				'title'       => esc_html__( 'Knowledge Base Layout Two', 'knowx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'knowledge_panel',
			)
		);

		// Site Footer.
		$wp_customize->add_panel(
			'site_footer_panel',
			array(
				'title'       => esc_html__( 'Site Footer', 'knowx' ),
				'priority'    => 11,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_footer_section',
			array(
				'title'       => esc_html__( 'Footer Section', 'knowx' ),
				'priority'    => 10,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Copyright.
		$wp_customize->add_section(
			'site_copyright_section',
			array(
				'title'       => esc_html__( 'Copyright Section', 'knowx' ),
				'priority'    => 11,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Performance.
		$wp_customize->add_section(
			'site_performance_section',
			array(
				'title'       => esc_html__( 'Site Performance', 'knowx' ),
				'priority'    => 11,
				'description' => '',
			)
		);
	}

	/**
	 * Add fields
	 */
	public function add_fields( $fields ) {
		/**
		 *  Site Layout
		 */
		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'site_layout',
			'label'    => esc_html__( 'Site Layout', 'knowx' ),
			'section'  => 'site_layout',
			'priority' => 10,
			'default'  => 'wide',
			'choices'  => array(
				'boxed' => get_template_directory_uri() . '/assets/images/boxed.png',
				'wide'  => get_template_directory_uri() . '/assets/images/wide.png',
			),
		);

		/**
		 *  Site Container Width
		 */
		$fields[] = array(
			'type'        => 'dimension',
			'settings'    => 'site_container_width',
			'label'       => esc_html__( 'Max Content Layout Width', 'knowx' ),
			'description' => esc_html__( 'Select the maximum content width for your website (px)', 'knowx' ),
			'section'     => 'site_layout',
			'default'     => '1170px',
			'priority'    => 10,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'element'  => '.container',
					'function' => 'css',
					'property' => 'max-width',
				),
			),
		);

		/**
		 *  Site Loader
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_loader',
			'label'    => esc_html__( 'Site Loader ?', 'knowx' ),
			'section'  => 'site_loader',
			'default'  => '2',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		);

		$fields[] = array(
			'type'            => 'color',
			'settings'        => 'site_loader_bg',
			'label'           => esc_html__( 'Site Loader Background', 'knowx' ),
			'section'         => 'site_loader',
			'default'         => '#03A9F4',
			'choices'         => array( 'alpha' => true ),
			'priority'        => 10,
			'output'          => array(
				array(
					'element'  => '.site-loader',
					'property' => 'background-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_loader',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		/**
		 *  Site Title Typography
		 */
		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'site_title_typography_option',
			'label'    => esc_html__( 'Site Title Settings', 'knowx' ),
			'section'  => 'site_title_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '600',
				'font-size'      => '38px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.site-title a',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_title_hover_color',
			'label'    => esc_html__( 'Site Title Hover Color', 'knowx' ),
			'section'  => 'site_title_typography_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.site-title a:hover',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'site_tagline_typography_option',
			'label'    => esc_html__( 'Site Tagline Settings', 'knowx' ),
			'section'  => 'site_title_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => 'regular',
				'font-size'      => '14px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#757575',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.site-description',
				),
			),
		);

		/**
		 *  Headings Typography
		 */
		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'h1_typography_option',
			'label'    => esc_html__( 'H1 Tag Settings', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '30px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h1',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'h2_typography_option',
			'label'    => esc_html__( 'H2 Tag Settings', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '24px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h2',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'h3_typography_option',
			'label'    => esc_html__( 'H3 Tag Settings', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '22px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h3',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'h4_typography_option',
			'label'    => esc_html__( 'H4 Tag Settings', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '20px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h4',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'h5_typography_option',
			'label'    => esc_html__( 'H5 Tag Settings', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '18px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h5',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'h6_typography_option',
			'label'    => esc_html__( 'H6 Tag Settings', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '16px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h6',
				),
			),
		);

		/**
		 *  Menu Typography
		 */
		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'menu_typography_option',
			'label'    => esc_html__( 'Menu Settings', 'knowx' ),
			'section'  => 'menu_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '14px',
				'line-height'    => '1.6',
				'letter-spacing' => '0.02em',
				'color'          => '#222222',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.main-navigation a, .main-navigation ul li a, .nav--toggle-sub li.menu-item-has-children, .nav--toggle-small .menu-toggle',
				),
				array(
					'element'  => '.nav--toggle-small .menu-toggle',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'menu_hover_color',
			'label'    => esc_html__( 'Menu Hover Color', 'knowx' ),
			'section'  => 'menu_typography_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.main-navigation a:hover, .main-navigation ul li a:hover, .nav--toggle-sub li.menu-item-has-children:hover, .nav--toggle-small .menu-toggle:hover',
					'property' => 'color',
				),
				array(
					'element'  => '.nav--toggle-small .menu-toggle:hover',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'menu_active_color',
			'label'    => esc_html__( 'Menu Active Color', 'knowx' ),
			'section'  => 'menu_typography_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.main-navigation ul li.current-menu-item>a',
					'property' => 'color',
				),
			),
		);

		/**
		 * Body Typography
		 */
		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'typography_option',
			'label'    => esc_html__( 'Settings', 'knowx' ),
			'section'  => 'body_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => 'regular',
				'font-size'      => '14px',
				'line-height'    => '1.6',
				'letter-spacing' => '0',
				'color'          => '#505050',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'body:not(.block-editor-page):not(.wp-core-ui), body:not(.block-editor-page):not(.wp-core-ui) pre',
				),
			),
		);

		/*
		 * Site Header
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_sticky_header',
			'label'    => esc_html__( 'Enable Sticky Header ?', 'knowx' ),
			'section'  => 'site_header_section',
			'default'  => '1',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_header_bg_color',
			'label'    => esc_html__( 'Header Background Color', 'knowx' ),
			'section'  => 'site_header_section',
			'default'  => '#ffffff',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.site-header-wrapper, .layout-boxed .site-header-wrapper, .nav--toggle-sub ul ul, #user-profile-menu, .bp-header-submenu, .main-navigation .primary-menu-container, .main-navigation #user-profile-menu, .main-navigation .bp-header-submenu',
					'property' => 'background-color',
				),
				array(
					'element'  => '.site-header-wrapper',
					'property' => 'border-color',
				),
				array(
					'element'  => '.menu-item--has-toggle>ul.sub-menu:before, .nav--toggle-sub ul.user-profile-menu .sub-menu:before, .bp-header-submenu:before, .user-profile-menu:before',
					'property' => 'border-top-color',
				),
				array(
					'element'  => '.menu-item--has-toggle>ul.sub-menu:before, .nav--toggle-sub ul.user-profile-menu .sub-menu:before, .bp-header-submenu:before, .user-profile-menu:before',
					'property' => 'border-right-color',
				),
			),
		);

		/**
		 *  Site Sub Header
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_sub_header',
			'label'    => esc_html__( 'Site Sub Header?', 'knowx' ),
			'section'  => 'site_sub_header_section',
			'default'  => 'on',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		);

		$fields[] = array(
			'type'            => 'switch',
			'settings'        => 'site_sub_header_bg',
			'label'           => esc_html__( 'Customize Background ?', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => 'off',
			'choices'         => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'            => 'background',
			'settings'        => 'sub_header_background_setting',
			'label'           => esc_html__( 'Background Control', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => array(
				'background-color'      => 'rgba(255,255,255,0.5)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element' => '.site-sub-header',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header_bg',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'            => 'typography',
			'settings'        => 'site_sub_header_typography',
			'label'           => esc_html__( 'Content Typography', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#333333',
				'text-transform' => 'none',
			),
			'priority'        => 10,
			'output'          => array(
				array(
					'element' => '.site-sub-header, .site-sub-header .entry-header .entry-title, .site-sub-header .page-header .page-title, .site-sub-header .entry-header, .site-sub-header .page-header, .site-sub-header .entry-title, .site-sub-header .page-title',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'            => 'switch',
			'settings'        => 'site_breadcrumbs',
			'label'           => esc_html__( 'Site Breadcrumbs?', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => 'on',
			'choices'         => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'            => 'switch',
			'settings'        => 'site_search',
			'label'           => esc_html__( 'Site Search?', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => 'on',
			'choices'         => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		/**
		 * Site Skin
		 */
		$fields[] = array(
			'type'     => 'color',
			'settings' => 'body_background_color',
			'label'    => esc_html__( 'Body Background Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#f7f7f9',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => 'body',
					'property' => 'background-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_primary_color',
			'label'    => esc_html__( 'Theme Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.knowx-breadcrumbs a, #breadcrumbs a, .pagination .current, .knowx-popular-posts ul li a:hover, .knowx-recent-posts ul li a:hover',
					'property' => 'color',
				),
				array(
					'element'  => '.knowx-knowledgebase-one ul.cat-list li.cat-name a:hover',
					'property' => 'background-color',
				),
				array(
					'element'  => '.knowx-knowledgebase-one ul.cat-list li.cat-name a',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_links_color',
			'label'    => esc_html__( 'Link Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => 'a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_links_focus_hover_color',
			'label'    => esc_html__( 'Link Hover', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#222222',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => 'a:hover, a:active, a:focus, #knowx-categories-columns-four li.post-name a:hover',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'custom',
			'settings' => 'custom-skin-divider',
			'section'  => 'site_skin_section',
			'default'  => '<hr>',
		);

		// Site Buttons.
		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_buttons_background_color',
			'label'    => esc_html__( 'Button Background Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => 'a.read-more.button, button:not(.menu-toggle), input[type="button"], input[type="reset"], input[type="submit"], .knowx-support-button a',
					'property' => 'background',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_buttons_background_hover_color',
			'label'    => esc_html__( 'Button Background Hover Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#222222',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'choices'  => array( 'alpha' => true ),
			'output'   => array(
				array(
					'element'  => 'a.read-more.button:hover, button:not(.menu-toggle):hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:active, button:focus, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus, .knowx-support-button a:hover',
					'property' => 'background',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_buttons_text_color',
			'label'    => esc_html__( 'Button Text Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#ffffff',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => 'a.read-more.button, button:not(.menu-toggle), input[type="button"], input[type="reset"], input[type="submit"], .knowx-support-button a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_buttons_text_hover_color',
			'label'    => esc_html__( 'Button Text Hover Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#ffffff',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => 'a.read-more.button:hover, button:not(.menu-toggle):hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:active, button:focus, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus, .knowx-support-button a:hover',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_buttons_border_color',
			'label'    => esc_html__( 'Button Border Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => 'a.read-more.button, button:not(.menu-toggle), input[type="button"], input[type="reset"], input[type="submit"], .knowx-support-button a',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_buttons_border_hover_color',
			'label'    => esc_html__( 'Button Border Hover Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#222222',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => 'a.read-more.button:hover, button:not(.menu-toggle):hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:active, button:focus, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus, .knowx-support-button a:hover',
					'property' => 'border-color',
				),
			),
		);

		/**
		 *  Site Blog Layout
		 */
		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'blog_layout_option',
			'label'    => esc_html__( 'Blog Layout', 'knowx' ),
			'section'  => 'site_blog_section',
			'priority' => 10,
			'default'  => 'default-layout',
			'choices'  => array(
				'default-layout' => get_template_directory_uri() . '/assets/images/one-column.png',
				'grid-layout'    => get_template_directory_uri() . '/assets/images/one-half-column.png',
				'masonry-layout' => get_template_directory_uri() . '/assets/images/one-third-column.png',
			),
		);

		$fields[] = array(
			'type'            => 'select',
			'settings'        => 'post_per_row',
			'label'           => esc_html__( 'Post Per Row', 'knowx' ),
			'section'         => 'site_blog_section',
			'default'         => '4',
			'priority'        => 10,
			'choices'         => array(
				'12' => esc_html__( 'Column 1', 'knowx' ),
				'6'  => esc_html__( 'Column 2', 'knowx' ),
				'4'  => esc_html__( 'Column 3', 'knowx' ),
				'3'  => esc_html__( 'Column 4', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_layout_option',
					'operator' => '!==',
					'value'    => 'default-layout',
				),
			),
		);

		/**
		 *  Site Sidebar Layout
		 */
		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'sidebar_option',
			'label'    => esc_html__( 'Sidebar Layout', 'knowx' ),
			'section'  => 'site_sidebar_layout',
			'priority' => 10,
			'default'  => 'right',
			'choices'  => array(
				'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
				'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
				'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
				'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
			),
		);

		if ( function_exists( 'is_bbpress' ) ) {
			$fields[] = array(
				'type'     => 'radio-image',
				'settings' => 'bbpress_sidebar_option',
				'label'    => esc_html__( 'bbPress Sidebar Layout', 'knowx' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => array(
					'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				),
			);
		}

		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'sticky_sidebar_option',
			'label'    => esc_html__( 'Sticky Sidebar ?', 'knowx' ),
			'section'  => 'site_sidebar_layout',
			'default'  => '1',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		);

		/**
		 *  Site Knowledge Base Layout One
		 */
		$fields[] = array(
			'type'        => 'background',
			'settings'    => 'hero_section_background_setting',
			'label'       => esc_html__( 'Hero Section Background Control', 'knowx' ),
			'description' => esc_html__( 'Set hero section background color and image. Note: "Background Image" not apply when set page featured image from backend.', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
			'default'     => array(
				'background-color'      => 'rgba(3, 169, 244, 1)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'element' => '.knowx-section-hero',
				),
			),
		);

		$fields[] = array(
			'type'     => 'custom',
			'settings' => 'custom-knowledge-divider-one',
			'section'  => 'site_knowledge_section_one',
			'default'  => '<hr>',
		);

		$fields[] = array(
			'type'        => 'text',
			'settings'    => 'hero_section_heading',
			'label'       => esc_html__( 'Hero Section Heading', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
			'default'     => '',
			'input_attrs' => array(
				'placeholder' => esc_html__( 'Create a Knowledge Base with KnowX', 'knowx' ),
			),
			'priority'    => 10,
		);

		$fields[] = array(
			'type'     => 'textarea',
			'settings' => 'hero_section_content',
			'label'    => esc_html__( 'Hero Section Content', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '',
			'priority' => 10,
		);

		$fields[] = array(
			'type'     => 'custom',
			'settings' => 'custom-knowledge-divider-two',
			'section'  => 'site_knowledge_section_one',
			'default'  => '<hr>',
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'hero_section_title_typo',
			'label'    => esc_html__( 'Hero Section Title Typography', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#ffffff',
				'text-transform' => 'none',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.knowx-section-hero .entry-title',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'hero_section_content_typo',
			'label'    => esc_html__( 'Hero Section Content Typography', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#ffffff',
				'text-transform' => 'none',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.knowx-section-hero .entry-summary, .knowx-section-hero .entry-summary p',
				),
			),
		);

		$fields[] = array(
			'type'     => 'custom',
			'settings' => 'custom-knowledge-divider-three',
			'section'  => 'site_knowledge_section_one',
			'default'  => '<hr>',
		);

		$fields[] = array(
			'type'        => 'input',
			'settings'    => 'knowx_exclude_posts',
			'label'       => esc_html__( 'Exclude category by ID', 'knowx' ),
			'description' => esc_html__( 'Use a comma to separate multiple categories.', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
		);

		$fields[] = array(
			'type'     => 'custom',
			'settings' => 'custom-knowledge-divider-four',
			'section'  => 'site_knowledge_section_one',
			'default'  => '<hr>',
		);

		$fields[] = array(
			'type'        => 'background',
			'settings'    => 'support_section_background_setting',
			'label'       => esc_html__( 'Support Section Background Control', 'knowx' ),
			'description' => esc_html__( 'Set support section background color and image.', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
			'default'     => array(
				'background-color'      => 'rgba(236,236,236,.94)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'element' => '.knowx-support-section',
				),
			),
		);

		$fields[] = array(
			'type'        => 'text',
			'settings'    => 'support_section_heading',
			'label'       => esc_html__( 'Support Section Heading', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
			'default'     => '',
			'input_attrs' => array(
				'placeholder' => esc_html__( 'Can not find what you are looking for?', 'knowx' ),
			),
			'priority'    => 10,
		);

		$fields[] = array(
			'type'     => 'textarea',
			'settings' => 'support_section_content',
			'label'    => esc_html__( 'Support Section Content', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '',
			'priority' => 10,
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'support_section_title_typo',
			'label'    => esc_html__( 'Support Section Heading Typography', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#333333',
				'text-transform' => 'none',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.knowx-support-title',
				),
			),
		);

		$fields[] = array(
			'type'     => 'typography',
			'settings' => 'support_section_content_typo',
			'label'    => esc_html__( 'Support Section Content Typography', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#333333',
				'text-transform' => 'none',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.knowx-support-content, .knowx-support-content p',
				),
			),
		);

		$fields[] = array(
			'type'     => 'text',
			'settings' => 'support_section_button_text',
			'label'    => esc_html__( 'Button Text', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '',
			'priority' => 10,
		);

		$fields[] = array(
			'type'     => 'link',
			'settings' => 'support_section_button_link_url',
			'label'    => esc_html__( 'Button Link URL', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '',
			'priority' => 10,
		);

		/**
		 *  Site Knowledge Base Layout Two
		 */
		$fields[] = array(
			'type'     => 'radio',
			'settings' => 'knowx_page_title',
			'label'    => esc_html__( 'Show page title', 'knowx' ),
			'section'  => 'site_knowledge_section_two',
			'default'  => 'yes',
			'choices'  => array(
				'yes' => esc_html__( 'Yes', 'knowx' ),
				'no'  => esc_html__( 'No', 'knowx' ),
			),
		);

		$fields[] = array(
			'type'     => 'radio',
			'settings' => 'knowx_view_all',
			'label'    => esc_html__( 'Show View All link', 'knowx' ),
			'section'  => 'site_knowledge_section_two',
			'default'  => 'no',
			'choices'  => array(
				'yes' => esc_html__( 'Yes', 'knowx' ),
				'no'  => esc_html__( 'No', 'knowx' ),
			),
		);

		$fields[] = array(
			'type'     => 'radio',
			'settings' => 'knowx_cat_description',
			'label'    => esc_html__( 'Show category description', 'knowx' ),
			'section'  => 'site_knowledge_section_two',
			'default'  => 'no',
			'choices'  => array(
				'yes' => esc_html__( 'Yes', 'knowx' ),
				'no'  => esc_html__( 'No', 'knowx' ),
			),
		);

		$fields[] = array(
			'type'        => 'input',
			'settings'    => 'knowx_exclude',
			'label'       => esc_html__( 'Exclude category by ID', 'knowx' ),
			'description' => esc_html__( 'Use a comma to separate multiple categories.', 'knowx' ),
			'section'     => 'site_knowledge_section_two',
		);

		$fields[] = array(
			'type'        => 'number',
			'settings'    => 'knowx_posts',
			'label'       => esc_html__( 'Posts per category', 'knowx' ),
			'description' => esc_html__( 'Only numeric characters allowed.', 'knowx' ),
			'section'     => 'site_knowledge_section_two',
		);

		$fields[] = array(
			'type'     => 'radio',
			'settings' => 'knowx_order',
			'label'    => esc_html__( 'Order posts', 'knowx' ),
			'section'  => 'site_knowledge_section_two',
			'default'  => 'date',
			'choices'  => array(
				'date' => esc_html__( 'By date', 'knowx' ),
				'name' => esc_html__( 'By name', 'knowx' ),
			),
		);

		/**
		 *  Site Footer
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_footer_bg',
			'label'    => esc_html__( 'Customize Background ?', 'knowx' ),
			'section'  => 'site_footer_section',
			'default'  => 'off',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		);

		$fields[] = array(
			'type'            => 'background',
			'settings'        => 'background_setting',
			'label'           => esc_html__( 'Background Control', 'knowx' ),
			'section'         => 'site_footer_section',
			'default'         => array(
				'background-color'      => 'rgba(255,255,255,0.8)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element' => '.site-footer-wrapper',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_footer_bg',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_footer_title_color',
			'label'    => esc_html__( 'Title Color', 'knowx' ),
			'section'  => 'site_footer_section',
			'default'  => '#333333',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.site-footer .widget-title',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_footer_content_color',
			'label'    => esc_html__( 'Content Color', 'knowx' ),
			'section'  => 'site_footer_section',
			'default'  => '#505050',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.site-footer',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_footer_links_color',
			'label'    => esc_html__( 'Link Color', 'knowx' ),
			'section'  => 'site_footer_section',
			'default'  => '#222222',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.site-footer a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_footer_links_hover_color',
			'label'    => esc_html__( 'Link Hover', 'knowx' ),
			'section'  => 'site_footer_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.site-footer a:hover, .site-footer a:active',
					'property' => 'color',
				),
			),
		);

		/**
		 *  Site Copyright
		 */
		$fields[] = array(
			'type'        => 'textarea',
			'settings'    => 'site_copyright_text',
			'label'       => esc_html__( 'Add Content', 'knowx' ),
			'description' => esc_html__( 'You can use [current_year], [site_title], [theme_author] shortcode if you want.', 'knowx' ),
			'section'     => 'site_copyright_section',
			'default'     => esc_html__( 'Copyright © [current_year] [site_title] | Powered by [theme_author]', 'knowx' ),
			'priority'    => 10,
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_copyright_background_color',
			'label'    => esc_html__( 'Background Color', 'knowx' ),
			'section'  => 'site_copyright_section',
			'default'  => '#ffffff',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.site-info',
					'property' => 'background-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_copyright_border_color',
			'label'    => esc_html__( 'Border Color', 'knowx' ),
			'section'  => 'site_copyright_section',
			'default'  => '#e8e8e8',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.site-info',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_copyright_content_color',
			'label'    => esc_html__( 'Content Color', 'knowx' ),
			'section'  => 'site_copyright_section',
			'default'  => '#505050',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.site-info',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_copyright_links_color',
			'label'    => esc_html__( 'Link Color', 'knowx' ),
			'section'  => 'site_copyright_section',
			'default'  => '#222222',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.site-info a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'     => 'color',
			'settings' => 'site_copyright_links_hover_color',
			'label'    => esc_html__( 'Link Hover Color', 'knowx' ),
			'section'  => 'site_copyright_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
			'output'   => array(
				array(
					'element'  => '.site-info a:hover',
					'property' => 'color',
				),
			),
		);

		/**
		 *  Site Performance
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_load_google_font_locally',
			'label'    => esc_html__( 'Load Google Fonts Locally ?', 'knowx' ),
			'section'  => 'site_performance_section',
			'default'  => '',
			'choices'  => array(
				'on'  => esc_html__( 'Enable', 'knowx' ),
				'off' => esc_html__( 'Disable', 'knowx' ),
			),
		);

		$fields[] = array(
			'type'            => 'switch',
			'settings'        => 'site_preload_local_font',
			'label'           => esc_html__( 'Preload Local Fonts ?', 'knowx' ),
			'section'         => 'site_performance_section',
			'default'         => '',
			'choices'         => array(
				'on'  => esc_html__( 'Enable', 'knowx' ),
				'off' => esc_html__( 'Disable', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_load_google_font_locally',
					'operator' => '==',
					'value'    => 1,
				),
			),
		);

		$fields[] = array(
			'type'            => 'custom',
			'settings'        => 'site_flush_local_font',
			'label'           => esc_html__( 'Flush Local Fonts Cache', 'knowx' ),
			'description'     => esc_html__( 'Click the button to reset the local fonts cache.', 'knowx' ),
			'section'         => 'site_performance_section',
			'default'         => '<input type="submit" value="Flush Local Font Files" class="button button-secondary knowx-flush-font-files">',
			'active_callback' => array(
				array(
					'setting'  => 'site_load_google_font_locally',
					'operator' => '==',
					'value'    => 1,
				),
			),
		);

		return $fields;
	}
}
