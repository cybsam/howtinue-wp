<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package knowx
 */

namespace KnowX\KnowX;

get_header();

knowx()->print_styles( 'knowx-content' );

?>
	<?php do_action( 'knowx_before_content' ); ?>

	<main id="primary" class="site-main">
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) { ?>
			<?php get_template_part( 'template-parts/content/error', '404' ); ?>
		<?php } ?>
	</main><!-- #primary -->
	
	<?php do_action( 'knowx_after_content' ); ?>
<?php
get_footer();
