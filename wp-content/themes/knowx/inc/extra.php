<?php

// Content wrapper
if ( ! function_exists( 'knowx_content_top' ) ) {
	function knowx_content_top() { ?>
		<div class="site-wrapper">
		<?php
	}
}

add_action( 'knowx_before_content', 'knowx_content_top' );

if ( ! function_exists( 'knowx_content_bottom' ) ) {
	function knowx_content_bottom() {
		?>
		</div>
		<?php
	}
}

add_action( 'knowx_after_content', 'knowx_content_bottom' );

// Site Sub Header
if ( ! function_exists( 'knowx_sub_header' ) ) {
	add_action( 'knowx_sub_header', 'knowx_sub_header' );

	function knowx_sub_header() {
		global $post;
		if ( is_front_page() ) {
			return;
		}
		$site_subheader = get_theme_mod( 'site_sub_header', knowx_defaults( 'site-sub-header' ) );
		if ( ! empty( $site_subheader ) ) {
			?>
		<div class="site-sub-header">
			<div class="container">
				<?php
				$breadcrumbs = get_theme_mod( 'site_breadcrumbs', knowx_defaults( 'site-breadcrumbs' ) );
				if ( ! empty( $breadcrumbs ) ) {
					knowx_the_breadcrumb();
				}
				?>
				<?php
				$site_search = get_theme_mod( 'site_search', knowx_defaults( 'site-search' ) );
				if ( ! empty( $site_search ) ) {
					get_search_form();
				}
				?>
			</div>
		</div>
			<?php
		}
	}
}

/*
 * BREADCRUMBS
 */
// to include in functions.php
if ( ! function_exists( 'knowx_the_breadcrumb' ) ) {
	function knowx_the_breadcrumb() {
		$wpseo_titles = get_option( 'wpseo_titles' );
		if ( function_exists( 'yoast_breadcrumb' ) && isset( $wpseo_titles['breadcrumbs-enable'] ) && $wpseo_titles['breadcrumbs-enable'] == 1 ) {
			yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
		} else {
			echo '<div class="knowx-breadcrumbs">';
				knowx_get_breadcrumb();
			echo '</div>';
		}
	}
}

// Site Loader
if ( ! function_exists( 'knowx_site_loader' ) ) {
	function knowx_site_loader() {
		$loader = get_theme_mod( 'site_loader', knowx_defaults( 'site-loader' ) );
		if ( $loader == '1' ) {
			echo '<div class="site-loader"><div class="loader-inner"><span class="dot"></span><span class="dot dot1"></span><span class="dot dot2"></span><span class="dot dot3"></span><span class="dot dot4"></span></div></div>';
		}
	}
}

// Site Search and Woo icon
if ( ! function_exists( 'knowx_site_menu_icon' ) ) {
	function knowx_site_menu_icon() {
		// menu icons
		$searchicon = (int) get_theme_mod( 'site_search', knowx_defaults( 'site-search' ) );
		$carticon   = (int) get_theme_mod( 'site_cart', knowx_defaults( 'site-cart' ) );
		if ( ! empty( $searchicon ) || ! empty( $carticon ) ) :
			?>
			<div class="menu-icons-wrapper">
			<?php
			if ( ! empty( $searchicon ) ) :
				?>
					<div class="search" <?php echo apply_filters( 'knowx_search_slide_toggle_data_attrs', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<a href="#" id="overlay-search" class="search-icon"> <span class="fa fa-search"> </span> </a>
						<div class="top-menu-search-container" <?php echo apply_filters( 'knowx_search_field_toggle_data_attrs', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php get_search_form(); ?>
						</div>
					</div>
					<?php
				endif;
			?>
			</div>
			<?php
		endif;
	}
}

/**
 * Function Footer Custom Text
 */
if ( ! function_exists( 'knowx_footer_custom_text' ) ) {
	/**
	 * Function Footer Custom Text
	 *
	 * @since 1.0.14
	 * @param string $option Custom text option name.
	 * @return mixed         Markup of custom text option.
	 */
	function knowx_footer_custom_text() {

		$copyright = get_theme_mod( 'site_copyright_text' );
		if ( ! empty( $copyright ) ) {
			$copyright    = str_replace( '[current_year]', date_i18n( 'Y' ), $copyright );
			$copyright    = str_replace( '[site_title]', '<span class="knowx-footer-site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></span>', $copyright );
			$theme_author = apply_filters(
				'knowx_theme_author',
				array(
					'theme_name'       => esc_html__( 'Knowx WordPress Theme', 'knowx' ),
					'theme_author_url' => esc_url( 'https://wbcomdesigns.com/downloads/knowx-theme/' ),
				)
			);
			$copyright    = str_replace( '[theme_author]', '<a href="' . esc_url( $theme_author['theme_author_url'] ) . '">' . esc_html( $theme_author['theme_name'] ) . '</a>', $copyright );
			return apply_filters( 'knowx_footer_copyright_text', $copyright );
		} else {
			$output       = 'Copyright © [current_year] [site_title] | Powered by [theme_author]';
			$output       = str_replace( '[current_year]', date_i18n( 'Y' ), $output );
			$output       = str_replace( '[site_title]', '<span class="knowx-footer-site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></span>', $output );
			$theme_author = apply_filters(
				'knowx_theme_author',
				array(
					'theme_name'       => esc_html__( 'Knowx WordPress Theme', 'knowx' ),
					'theme_author_url' => esc_url( 'https://wbcomdesigns.com/downloads/knowx-theme/' ),
				)
			);
			$output       = str_replace( '[theme_author]', '<a href="' . esc_url( $theme_author['theme_author_url'] ) . '">' . esc_html( $theme_author['theme_name'] ) . '</a>', $output );
			return apply_filters( 'knowx_footer_copyright_text', $output );
		}

	}
}

/**
 * Categorized Blog
 * Find out if blog has more than one category.
 */
if ( ! function_exists( 'knowx_categorized_blog' ) ) {
	function knowx_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'knowx_category_count' ) ) ) {

			$all_the_cool_cats = get_categories( array( 'hide_empty' => 1 ) );
			$all_the_cool_cats = count( $all_the_cool_cats );
			set_transient( 'knowx_category_count', $all_the_cool_cats );

		}

		if ( 1 !== (int) $all_the_cool_cats ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Blog Post Meta
 */
if ( ! function_exists( 'knowx_posted_on' ) ) {

	function knowx_posted_on() {

		global $post;

		if ( is_sticky() && is_home() && ! is_paged() ) {
			echo '<span class="entry-featured">' . esc_html__( 'Sticky', 'knowx' ) . '</span>';
		}

		if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && knowx_categorized_blog() ) {
			echo '<span class="entry-cat-links">' . get_the_category_list( ', ' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		if ( ! is_search() ) {

			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
				echo '<span class="entry-comments-link">';
				comments_popup_link( esc_html__( 'Leave a comment', 'knowx' ), esc_html__( '1 Comment', 'knowx' ), esc_html__( '% Comments', 'knowx' ) );
				echo '</span>';
			}
		}

		edit_post_link( esc_html__( 'Edit', 'knowx' ), '<span class="entry-edit-link">', '</span>' );
	}
}

/**
 * Add Elementor Locations Support
 */
if ( ! function_exists( 'knowx_register_elementor_locations' ) ) {
	function knowx_register_elementor_locations( $elementor_theme_manager ) {

		$elementor_theme_manager->register_all_core_location();

	}
	add_action( 'elementor/theme/register_locations', 'knowx_register_elementor_locations' );
}

/**
 * Meta box for knowledge base page template
 */
function knowx_metabox_callback( $post ) {
	wp_nonce_field( 'knowx_meta_box', 'knowx_nonce' );
	$cats_value = get_post_meta( $post->ID, 'knowx-exclude-cats', true );
	?>
	<p><?php esc_html_e( 'Setting for the Knowledge Base page template. This field will override the one in Customizer.', 'knowx' ); ?></p>
	<p><strong><label for="knowx-cats"><?php esc_html_e( 'Exclude category by ID', 'knowx' ); ?></label></strong></p>
	<input class="widefat" id="knowx-cats" type="text" name="knowx-cats" value="<?php echo esc_attr( $cats_value ); ?>" />
	<p><?php esc_html_e( 'Use a comma to separate multiple categories.', 'knowx' ); ?></p>
	<?php
}

function knowx_save_meta( $post_id ) {
	if ( ! isset( $_POST['knowx_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['knowx_nonce'], 'knowx_meta_box' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ( get_post_type() != 'page' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['knowx-cats'] ) ) {
		update_post_meta( $post_id, 'knowx-exclude-cats', sanitize_text_field( wp_unslash( $_POST['knowx-cats'] ) ) );
	}
}
add_action( 'save_post', 'knowx_save_meta' );


/*
 * Set post views count using post meta
 */
function setPostViews( $postID ) {
	$count_key = 'post_views_count';
	$count     = get_post_meta( $postID, $count_key, true );
	if ( $count == '' ) {
		$count = 0;
		delete_post_meta( $postID, $count_key );
		add_post_meta( $postID, $count_key, '0' );
	} else {
		$count++;
		update_post_meta( $postID, $count_key, $count );
	}
}

/*
 * Hide bbp breadcrumb
 */
function custom_bbp_no_breadcrumb( $param ) {
	return true;
}
add_filter( 'bbp_no_breadcrumb', 'custom_bbp_no_breadcrumb' );

/**
 *
 * CSS Compress
 *
 */
if ( ! function_exists( 'knowx_css_compress' ) ) {
	function knowx_css_compress( $css ) {
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
		$css = str_replace( ': ', ':', $css );
		$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
		return $css;
	}
}