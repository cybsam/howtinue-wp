<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kirki' ) ) {
	return;
}

/*
* Add Config
/* ------------------------------------ */
Kirki::add_config(
	'knowx_kirki',
	array(
		'capability'  => 'edit_theme_options',
		'option_type' => 'theme_mod',
	)
);

/*
* Add Panels And Sections
/*
 ------------------------------------ */
// Site Layout
Kirki::add_panel(
	'site_layout_panel',
	array(
		'title'       => esc_html__( 'General', 'knowx' ),
		'priority'    => 10,
		'description' => '',
	)
);

Kirki::add_section(
	'site_layout',
	array(
		'title'       => esc_html__( 'Site Layout', 'knowx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'site_layout_panel',
	)
);

// Site Loader
Kirki::add_section(
	'site_loader',
	array(
		'title'       => esc_html__( 'Site Loader', 'knowx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'site_layout_panel',
	)
);

// Typography
Kirki::add_panel(
	'typography_panel',
	array(
		'title'       => esc_html__( 'Typography', 'knowx' ),
		'priority'    => 10,
		'description' => '',
	)
);

Kirki::add_section(
	'site_title_typography_section',
	array(
		'title'       => esc_html__( 'Site Title', 'knowx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'typography_panel',
	)
);

Kirki::add_section(
	'headings_typography_section',
	array(
		'title'       => esc_html__( 'Headings', 'knowx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'typography_panel',
	)
);

Kirki::add_section(
	'menu_typography_section',
	array(
		'title'       => esc_html__( 'Menu', 'knowx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'typography_panel',
	)
);

Kirki::add_section(
	'body_typography_section',
	array(
		'title'       => esc_html__( 'Body', 'knowx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'typography_panel',
	)
);

// Site Header
Kirki::add_section(
	'site_header_section',
	array(
		'title'       => esc_html__( 'Site Header', 'knowx' ),
		'priority'    => 10,
		'description' => '',
	)
);

// Site Sub Header
Kirki::add_section(
	'site_sub_header_section',
	array(
		'title'       => esc_html__( 'Site Sub Header', 'knowx' ),
		'priority'    => 10,
		'description' => '',
	)
);

// Site Skin
Kirki::add_section(
	'site_skin_section',
	array(
		'title'       => esc_html__( 'Site Skin', 'knowx' ),
		'priority'    => 10,
		'description' => '',
	)
);

// Site Blog Layout
Kirki::add_section(
	'site_blog_section',
	array(
		'title'       => esc_html__( 'Site Blog', 'knowx' ),
		'priority'    => 10,
		'description' => '',
	)
);

// Site Sidebar Layout
Kirki::add_section(
	'site_sidebar_layout',
	array(
		'title'       => esc_html__( 'Site Sidebar', 'knowx' ),
		'priority'    => 10,
		'description' => '',
	)
);

// Site Knowledge Base
Kirki::add_panel(
	'knowledge_panel',
	array(
		'title'       => esc_html__( 'Knowledge Base', 'knowx' ),
		'priority'    => 11,
		'description' => '',
	)
);

Kirki::add_section(
	'site_knowledge_section_one',
	array(
		'title'       => esc_html__( 'Knowledge Base Layout One', 'knowx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'knowledge_panel',
	)
);

Kirki::add_section(
	'site_knowledge_section_two',
	array(
		'title'       => esc_html__( 'Knowledge Base Layout Two', 'knowx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'knowledge_panel',
	)
);

// Site Footer
Kirki::add_panel(
	'site_footer_panel',
	array(
		'title'       => esc_html__( 'Site Footer', 'knowx' ),
		'priority'    => 11,
		'description' => '',
	)
);

Kirki::add_section(
	'site_footer_section',
	array(
		'title'       => esc_html__( 'Footer Section', 'knowx' ),
		'priority'    => 10,
		'description' => '',
		'panel'       => 'site_footer_panel',
	)
);

// Site Copyright
Kirki::add_section(
	'site_copyright_section',
	array(
		'title'       => esc_html__( 'Copyright Section', 'knowx' ),
		'priority'    => 11,
		'description' => '',
		'panel'       => 'site_footer_panel',
	)
);


/*
* Add Fields
/* ------------------------------------ */
/**
 *  Site Layout
 */
Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

/**
 *  Site Container Width
 */
Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

/**
 *  Site Loader
 */
Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'switch',
		'settings' => 'site_loader',
		'label'    => esc_html__( 'Site Loader ?', 'knowx' ),
		'section'  => 'site_loader',
		'default'  => '',
		'choices'  => array(
			'on'  => esc_html__( 'Yes', 'knowx' ),
			'off' => esc_html__( 'No', 'knowx' ),
		),
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'            => 'color',
		'settings'        => 'site_loader_bg',
		'label'           => esc_html__( 'Site Loader Background', 'knowx' ),
		'section'         => 'site_loader',
		'default'         => '#03A9F4',
		'choices'         => array( 'alpha' => true ),
		'priority'        => 10,
		'active_callback' => array(
			array(
				'setting'  => 'site_loader',
				'operator' => '==',
				'value'    => '1',
			),
		),
	)
);

/**
 *  Site Title Typography
 */
Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_title_hover_color',
		'label'    => esc_html__( 'Site Title Hover Color', 'knowx' ),
		'section'  => 'site_title_typography_section',
		'default'  => '#03A9F4',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

/**
 *  Headings Typography
 */
Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

/**
 *  Menu Typography
 */
Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'menu_hover_color',
		'label'    => esc_html__( 'Menu Hover Color', 'knowx' ),
		'section'  => 'menu_typography_section',
		'default'  => '#03A9F4',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'menu_active_color',
		'label'    => esc_html__( 'Menu Active Color', 'knowx' ),
		'section'  => 'menu_typography_section',
		'default'  => '#03A9F4',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

/**
 * Body Typography
 */
Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

/**
 * Site Header
 */
Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_header_bg_color',
		'label'    => esc_html__( 'Header Background Color', 'knowx' ),
		'section'  => 'site_header_section',
		'default'  => '#ffffff',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

/**
 *  Site Sub Header
 */
Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'switch',
		'settings' => 'site_sub_header',
		'label'    => esc_html__( 'Site Sub Header?', 'knowx' ),
		'section'  => 'site_sub_header_section',
		'default'  => 'on',
		'choices'  => array(
			'on'  => esc_html__( 'Yes', 'knowx' ),
			'off' => esc_html__( 'No', 'knowx' ),
		),
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'            => 'switch',
		'settings'        => 'site_sub_header_bg',
		'label'           => esc_html__( 'Customize Background ?', 'knowx' ),
		'section'         => 'site_sub_header_section',
		'default'         => '',
		'choices'         => array(
			'on'  => esc_html__( 'Yes', 'knowx' ),
			'off' => esc_html__( 'No', 'knowx' ),
		),
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

/**
 * Site Skin
 */
Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'body_background_color',
		'label'    => esc_html__( 'Body Background Color', 'knowx' ),
		'section'  => 'site_skin_section',
		'default'  => '#f7f7f9',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_primary_color',
		'label'    => esc_html__( 'Theme Color', 'knowx' ),
		'section'  => 'site_skin_section',
		'default'  => '#03A9F4',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_links_color',
		'label'    => esc_html__( 'Link Color', 'knowx' ),
		'section'  => 'site_skin_section',
		'default'  => '#03A9F4',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_links_focus_hover_color',
		'label'    => esc_html__( 'Link Hover', 'knowx' ),
		'section'  => 'site_skin_section',
		'default'  => '#222222',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'custom',
		'settings' => 'custom-skin-divider',
		'section'  => 'site_skin_section',
		'default'  => '<hr>',
	)
);

// Site Buttons
Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_buttons_background_color',
		'label'    => esc_html__( 'Button Background Color', 'knowx' ),
		'section'  => 'site_skin_section',
		'default'  => '#03A9F4',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_buttons_background_hover_color',
		'label'    => esc_html__( 'Button Background Hover Color', 'knowx' ),
		'section'  => 'site_skin_section',
		'default'  => '#222222',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_buttons_text_color',
		'label'    => esc_html__( 'Button Text Color', 'knowx' ),
		'section'  => 'site_skin_section',
		'default'  => '#ffffff',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_buttons_text_hover_color',
		'label'    => esc_html__( 'Button Text Hover Color', 'knowx' ),
		'section'  => 'site_skin_section',
		'default'  => '#ffffff',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_buttons_border_color',
		'label'    => esc_html__( 'Button Border Color', 'knowx' ),
		'section'  => 'site_skin_section',
		'default'  => '#03A9F4',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_buttons_border_hover_color',
		'label'    => esc_html__( 'Button Border Hover Color', 'knowx' ),
		'section'  => 'site_skin_section',
		'default'  => '#222222',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

/**
 *  Site Blog Layout
 */
Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

/**
 *  Site Sidebar Layout
 */
Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

if ( function_exists( 'is_bbpress' ) ) {
	Kirki::add_field(
		'knowx_kirki',
		array(
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
		)
	);
}

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'switch',
		'settings' => 'sticky_sidebar_option',
		'label'    => esc_html__( 'Sticky Sidebar ?', 'knowx' ),
		'section'  => 'site_sidebar_layout',
		'default'  => '1',
		'choices'  => array(
			'on'  => esc_html__( 'Yes', 'knowx' ),
			'off' => esc_html__( 'No', 'knowx' ),
		),
	)
);

/**
 *  Site Knowledge Base Layout One
 */
Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'custom',
		'settings' => 'custom-knowledge-divider-one',
		'section'  => 'site_knowledge_section_one',
		'default'  => '<hr>',
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'        => 'text',
		'settings'    => 'hero_section_heading',
		'label'       => esc_html__( 'Hero Section Heading', 'knowx' ),
		'section'     => 'site_knowledge_section_one',
		'default'     => '',
		'input_attrs' => array(
			'placeholder' => esc_html__( 'Create a Knowledge Base with KnowX', 'knowx' ),
		),
		'priority'    => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'textarea',
		'settings' => 'hero_section_content',
		'label'    => esc_html__( 'Hero Section Content', 'knowx' ),
		'section'  => 'site_knowledge_section_one',
		'default'  => '',
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'custom',
		'settings' => 'custom-knowledge-divider-two',
		'section'  => 'site_knowledge_section_one',
		'default'  => '<hr>',
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'custom',
		'settings' => 'custom-knowledge-divider-three',
		'section'  => 'site_knowledge_section_one',
		'default'  => '<hr>',
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'        => 'input',
		'settings'    => 'knowx_exclude_posts',
		'label'       => esc_html__( 'Exclude category by ID', 'knowx' ),
		'description' => esc_html__( 'Use a comma to separate multiple categories.', 'knowx' ),
		'section'     => 'site_knowledge_section_one',
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'custom',
		'settings' => 'custom-knowledge-divider-four',
		'section'  => 'site_knowledge_section_one',
		'default'  => '<hr>',
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'        => 'text',
		'settings'    => 'support_section_heading',
		'label'       => esc_html__( 'Support Section Heading', 'knowx' ),
		'section'     => 'site_knowledge_section_one',
		'default'     => '',
		'input_attrs' => array(
			'placeholder' => esc_html__( 'Can not find what you are looking for?', 'knowx' ),
		),
		'priority'    => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'textarea',
		'settings' => 'support_section_content',
		'label'    => esc_html__( 'Support Section Content', 'knowx' ),
		'section'  => 'site_knowledge_section_one',
		'default'  => '',
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'text',
		'settings' => 'support_section_button_text',
		'label'    => esc_html__( 'Button Text', 'knowx' ),
		'section'  => 'site_knowledge_section_one',
		'default'  => '',
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'link',
		'settings' => 'support_section_button_link_url',
		'label'    => esc_html__( 'Button Link URL', 'knowx' ),
		'section'  => 'site_knowledge_section_one',
		'default'  => '',
		'priority' => 10,
	)
);

/**
 *  Site Knowledge Base Layout Two
 */
Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'radio',
		'settings' => 'knowx_page_title',
		'label'    => esc_html__( 'Show page title', 'knowx' ),
		'section'  => 'site_knowledge_section_two',
		'default'  => 'yes',
		'choices'  => array(
			'yes' => esc_html__( 'Yes', 'knowx' ),
			'no'  => esc_html__( 'No', 'knowx' ),
		),
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'radio',
		'settings' => 'knowx_view_all',
		'label'    => esc_html__( 'Show View All link', 'knowx' ),
		'section'  => 'site_knowledge_section_two',
		'default'  => 'no',
		'choices'  => array(
			'yes' => esc_html__( 'Yes', 'knowx' ),
			'no'  => esc_html__( 'No', 'knowx' ),
		),
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'radio',
		'settings' => 'knowx_cat_description',
		'label'    => esc_html__( 'Show category description', 'knowx' ),
		'section'  => 'site_knowledge_section_two',
		'default'  => 'no',
		'choices'  => array(
			'yes' => esc_html__( 'Yes', 'knowx' ),
			'no'  => esc_html__( 'No', 'knowx' ),
		),
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'        => 'input',
		'settings'    => 'knowx_exclude',
		'label'       => esc_html__( 'Exclude category by ID', 'knowx' ),
		'description' => esc_html__( 'Use a comma to separate multiple categories.', 'knowx' ),
		'section'     => 'site_knowledge_section_two',
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'        => 'number',
		'settings'    => 'knowx_posts',
		'label'       => esc_html__( 'Posts per category', 'knowx' ),
		'description' => esc_html__( 'Only numeric characters allowed.', 'knowx' ),
		'section'     => 'site_knowledge_section_two',
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'radio',
		'settings' => 'knowx_order',
		'label'    => esc_html__( 'Order posts', 'knowx' ),
		'section'  => 'site_knowledge_section_two',
		'default'  => 'date',
		'choices'  => array(
			'date' => esc_html__( 'By date', 'knowx' ),
			'name' => esc_html__( 'By name', 'knowx' ),
		),
	)
);

/**
 *  Site Footer
 */
Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'switch',
		'settings' => 'site_footer_bg',
		'label'    => esc_html__( 'Customize Background ?', 'knowx' ),
		'section'  => 'site_footer_section',
		'default'  => 'off',
		'choices'  => array(
			'on'  => esc_html__( 'Yes', 'knowx' ),
			'off' => esc_html__( 'No', 'knowx' ),
		),
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
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
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_footer_title_color',
		'label'    => esc_html__( 'Title Color', 'knowx' ),
		'section'  => 'site_footer_section',
		'default'  => '#333333',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_footer_content_color',
		'label'    => esc_html__( 'Content Color', 'knowx' ),
		'section'  => 'site_footer_section',
		'default'  => '#505050',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_footer_links_color',
		'label'    => esc_html__( 'Link Color', 'knowx' ),
		'section'  => 'site_footer_section',
		'default'  => '#222222',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_footer_links_hover_color',
		'label'    => esc_html__( 'Link Hover', 'knowx' ),
		'section'  => 'site_footer_section',
		'default'  => '#03A9F4',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

/**
 *  Site Copyright
 */
Kirki::add_field(
	'knowx_kirki',
	array(
		'type'        => 'textarea',
		'settings'    => 'site_copyright_text',
		'label'       => esc_html__( 'Add Content', 'knowx' ),
		'description' => esc_html__( 'You can use [current_year], [site_title], [theme_author] shortcode if you want.', 'knowx' ),
		'section'     => 'site_copyright_section',
		'default'     => esc_html__( 'Copyright © [current_year] [site_title] | Powered by [theme_author]', 'knowx' ),
		'priority'    => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_copyright_background_color',
		'label'    => esc_html__( 'Background Color', 'knowx' ),
		'section'  => 'site_copyright_section',
		'default'  => '#ffffff',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_copyright_border_color',
		'label'    => esc_html__( 'Border Color', 'knowx' ),
		'section'  => 'site_copyright_section',
		'default'  => '#e8e8e8',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_copyright_content_color',
		'label'    => esc_html__( 'Content Color', 'knowx' ),
		'section'  => 'site_copyright_section',
		'default'  => '#505050',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_copyright_links_color',
		'label'    => esc_html__( 'Link Color', 'knowx' ),
		'section'  => 'site_copyright_section',
		'default'  => '#222222',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);

Kirki::add_field(
	'knowx_kirki',
	array(
		'type'     => 'color',
		'settings' => 'site_copyright_links_hover_color',
		'label'    => esc_html__( 'Link Hover Color', 'knowx' ),
		'section'  => 'site_copyright_section',
		'default'  => '#03A9F4',
		'choices'  => array( 'alpha' => true ),
		'priority' => 10,
	)
);
