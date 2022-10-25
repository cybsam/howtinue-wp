<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kirki' ) ) {
	return;
}

//
// custom style
// -------------------------------------------------------------
function knowx_get_customizer_style() {

	// Site Loader Background
    $site_loader_bg = get_theme_mod( 'site_loader_bg' );
    if ( $site_loader_bg ) {

		echo '.site-loader {';
		echo sprintf( 'background-color: %s;', esc_attr( $site_loader_bg ) );
		echo '}';

	}

    // Site Title Hover Color
    $site_title_hover_color = get_theme_mod( 'site_title_hover_color' );
    if ( $site_title_hover_color ) {

		echo '.site-title a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $site_title_hover_color ) );
		echo '}';

	}

    // Menu Hover Color
    $menu_hover_color = get_theme_mod( 'menu_hover_color' );
    if ( $menu_hover_color ) {

        // color
		echo '.main-navigation a:hover, .main-navigation ul li a:hover, .nav--toggle-sub li.menu-item-has-children:hover, .nav--toggle-small .menu-toggle:hover {';
		echo sprintf( 'color: %s;', esc_attr( $menu_hover_color ) );
		echo '}';

        // border color
        echo '.nav--toggle-small .menu-toggle:hover {';
        echo sprintf( 'border-color: %s;', esc_attr( $menu_hover_color ) );
        echo '}';

	}

    // Menu Active Color
    $menu_active_color = get_theme_mod( 'menu_active_color' );
    if ( $menu_active_color ) {

		echo '.main-navigation ul li.current-menu-item>a {';
		echo sprintf( 'color: %s;', esc_attr( $menu_active_color ) );
		echo '}';

	}

    // Header Background Color
    $site_header_bg_color = get_theme_mod( 'site_header_bg_color' );
    if ( $site_header_bg_color ) {

        // color
		echo '.site-header-wrapper, .layout-boxed .site-header-wrapper, .nav--toggle-sub ul ul, #user-profile-menu, .bp-header-submenu, .main-navigation .primary-menu-container, .main-navigation #user-profile-menu, .main-navigation .bp-header-submenu {';
		echo sprintf( 'background-color: %s;', esc_attr( $site_header_bg_color ) );
		echo '}';

        // border color
        echo '.site-header-wrapper {';
        echo sprintf( 'border-color: %s;', esc_attr( $site_header_bg_color ) );
        echo '}';

        // border top color
        echo '.menu-item--has-toggle>ul.sub-menu:before, .nav--toggle-sub ul.user-profile-menu .sub-menu:before, .bp-header-submenu:before, .user-profile-menu:before {';
        echo sprintf( 'border-top-color: %s;', esc_attr( $site_header_bg_color ) );
        echo '}';

        // border right color
        echo '.menu-item--has-toggle>ul.sub-menu:before, .nav--toggle-sub ul.user-profile-menu .sub-menu:before, .bp-header-submenu:before, .user-profile-menu:before {';
        echo sprintf( 'border-right-color: %s;', esc_attr( $site_header_bg_color ) );
        echo '}';

	}

    // Body Background Color
    $body_background_color = get_theme_mod( 'body_background_color' );
    if ( $body_background_color ) {

		echo 'body, body.layout-boxed {';
		echo sprintf( 'background-color: %s;', esc_attr( $body_background_color ) );
		echo '}';

	}

    // Theme Color
    $site_primary_color = get_theme_mod( 'site_primary_color' );
    if ( $site_primary_color ) {

        // color
		echo '.knowx-breadcrumbs a, #breadcrumbs a, .pagination .current, .knowx-popular-posts ul li a:hover, .knowx-recent-posts ul li a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $site_primary_color ) );
		echo '}';

        // background color
        echo '.knowx-knowledgebase-one ul.cat-list li.cat-name a:hover {';
        echo sprintf( 'background-color: %s;', esc_attr( $site_primary_color ) );
        echo '}';

        // border color
        echo '.knowx-knowledgebase-one ul.cat-list li.cat-name a {';
        echo sprintf( 'border-color: %s;', esc_attr( $site_primary_color ) );
        echo '}';

	}
    
    // Link Color
    $site_links_color = get_theme_mod( 'site_links_color' );
    if ( $site_links_color ) {

		echo 'a {';
		echo sprintf( 'color: %s;', esc_attr( $site_links_color ) );
		echo '}';

	}

    // Link Hover
    $site_links_focus_hover_color = get_theme_mod( 'site_links_focus_hover_color' );
    if ( $site_links_focus_hover_color ) {

		echo 'a:hover, a:active, a:focus, #knowx-categories-columns-four li.post-name a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $site_links_focus_hover_color ) );
		echo '}';

	}

    // Button Background Color
    $site_buttons_background_color = get_theme_mod( 'site_buttons_background_color' );
    if ( $site_buttons_background_color ) {

		echo 'a.read-more.button, button:not(.menu-toggle), input[type="button"], input[type="reset"], input[type="submit"], .knowx-support-button a {';
		echo sprintf( 'background: %s;', esc_attr( $site_buttons_background_color ) );
		echo '}';

	}

    // Button Background Hover Color
    $site_buttons_background_hover_color = get_theme_mod( 'site_buttons_background_hover_color' );
    if ( $site_buttons_background_hover_color ) {

		echo 'a.read-more.button:hover, button:not(.menu-toggle):hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:active, button:focus, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus, .knowx-support-button a:hover {';
		echo sprintf( 'background: %s;', esc_attr( $site_buttons_background_hover_color ) );
		echo '}';

	}

    // Button Text Color
    $site_buttons_text_color = get_theme_mod( 'site_buttons_text_color' );
    if ( $site_buttons_text_color ) {

		echo 'a.read-more.button, button:not(.menu-toggle), input[type="button"], input[type="reset"], input[type="submit"], .knowx-support-button a {';
		echo sprintf( 'color: %s;', esc_attr( $site_buttons_text_color ) );
		echo '}';

	}

    // Button Text Hover Color
    $site_buttons_text_hover_color = get_theme_mod( 'site_buttons_text_hover_color' );
    if ( $site_buttons_text_hover_color ) {

		echo 'a.read-more.button:hover, button:not(.menu-toggle):hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:active, button:focus, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus, .knowx-support-button a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $site_buttons_text_hover_color ) );
		echo '}';

	}

    // Button Border Color
    $site_buttons_border_color = get_theme_mod( 'site_buttons_border_color' );
    if ( $site_buttons_border_color ) {

		echo 'a.read-more.button, button:not(.menu-toggle), input[type="button"], input[type="reset"], input[type="submit"], .knowx-support-button a {';
		echo sprintf( 'border-color: %s;', esc_attr( $site_buttons_border_color ) );
		echo '}';

	}

    // Button Border Hover Color
    $site_buttons_border_hover_color = get_theme_mod( 'site_buttons_border_hover_color' );
    if ( $site_buttons_border_hover_color ) {

		echo 'a.read-more.button:hover, button:not(.menu-toggle):hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:active, button:focus, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus, .knowx-support-button a:hover {';
		echo sprintf( 'border-color: %s;', esc_attr( $site_buttons_border_hover_color ) );
		echo '}';

	}

    // Footer Title Color
    $site_footer_title_color = get_theme_mod( 'site_footer_title_color' );
    if ( $site_footer_title_color ) {

		echo '.site-footer .widget-title {';
		echo sprintf( 'color: %s;', esc_attr( $site_footer_title_color ) );
		echo '}';

	}

    // Footer Content Color
    $site_footer_content_color = get_theme_mod( 'site_footer_content_color' );
    if ( $site_footer_content_color ) {

		echo '.site-footer {';
		echo sprintf( 'color: %s;', esc_attr( $site_footer_content_color ) );
		echo '}';

	}

    // Footer Link Color
    $site_footer_links_color = get_theme_mod( 'site_footer_links_color' );
    if ( $site_footer_links_color ) {

		echo '.site-footer a {';
		echo sprintf( 'color: %s;', esc_attr( $site_footer_links_color ) );
		echo '}';

	}

    // Footer Link Hover
    $site_footer_links_hover_color = get_theme_mod( 'site_footer_links_hover_color' );
    if ( $site_footer_links_hover_color ) {

		echo '.site-footer a:hover, .site-footer a:active {';
		echo sprintf( 'color: %s;', esc_attr( $site_footer_links_hover_color ) );
		echo '}';

	}

    // Copyright Background Color
    $site_copyright_background_color = get_theme_mod( 'site_copyright_background_color' );
    if ( $site_copyright_background_color ) {

		echo '.site-info {';
		echo sprintf( 'background-color: %s;', esc_attr( $site_copyright_background_color ) );
		echo '}';

	}

    // Copyright Border Color
    $site_copyright_border_color = get_theme_mod( 'site_copyright_border_color' );
    if ( $site_copyright_border_color ) {

		echo '.site-info {';
		echo sprintf( 'border-color: %s;', esc_attr( $site_copyright_border_color ) );
		echo '}';

	}

    // Copyright Content Color
    $site_copyright_content_color = get_theme_mod( 'site_copyright_content_color' );
    if ( $site_copyright_content_color ) {

		echo '.site-info {';
		echo sprintf( 'color: %s;', esc_attr( $site_copyright_content_color ) );
		echo '}';

	}

    // Copyright Link Color
    $site_copyright_links_color = get_theme_mod( 'site_copyright_links_color' );
    if ( $site_copyright_links_color ) {

		echo '.site-info a {';
		echo sprintf( 'color: %s;', esc_attr( $site_copyright_links_color ) );
		echo '}';

	}

    // Copyright Link Hover Color
    $site_copyright_links_hover_color = get_theme_mod( 'site_copyright_links_hover_color' );
    if ( $site_copyright_links_hover_color ) {

		echo '.site-info a:hover {';
		echo sprintf( 'color: %s;', esc_attr( $site_copyright_links_hover_color ) );
		echo '}';

	}

}