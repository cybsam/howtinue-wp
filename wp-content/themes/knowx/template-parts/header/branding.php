<?php
/**
 * Template part for displaying the header branding
 *
 * @package knowx
 */

namespace KnowX\KnowX;

?>

<div class="site-branding">
	<div class="site-logo-wrapper">
		<?php the_custom_logo(); ?>
	</div><!-- .site-logo-wrapper -->
	<div class="site-branding-inner">
		<?php
		if ( is_front_page() && is_home() ) {
			?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php
		} else {
			?>
			<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
		}
		?>

		<?php
		$knowx_description = get_bloginfo( 'description', 'display' );
		if ( $knowx_description || is_customize_preview() ) {
			?>
			<p class="site-description">
				<?php echo esc_html( $knowx_description ); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
			</p>
			<?php
		}
		?>
	</div><!-- .site-branding-inner -->
</div><!-- .site-branding -->
