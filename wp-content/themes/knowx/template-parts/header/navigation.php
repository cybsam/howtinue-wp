<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package knowx
 */

namespace KnowX\KnowX;

if ( ! knowx()->is_primary_nav_menu_active() ) {
	return;
}

?>

<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small" aria-label="<?php esc_attr_e( 'Main menu', 'knowx' ); ?>"
	<?php
	if ( knowx()->is_amp() ) {
		?>
		[class]=" siteNavigationMenu.expanded ? 'main-navigation nav--toggle-sub nav--toggle-small nav--toggled-on' : 'main-navigation nav--toggle-sub nav--toggle-small' "
		<?php
	}
	?>
>
	<?php
	if ( knowx()->is_amp() ) {
		?>
		<amp-state id="siteNavigationMenu">
			<script type="application/json">
				{
					"expanded": false
				}
			</script>
		</amp-state>
		<?php
	}
	?>

	<button class="menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'knowx' ); ?>" aria-controls="primary-menu" aria-expanded="false"
		<?php
		if ( knowx()->is_amp() ) {
			?>
			on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )"
			[aria-expanded]="siteNavigationMenu.expanded ? 'true' : 'false'"
			<?php
		}
		?>
	>
	<i class="fa fa-bars" aria-hidden="true"></i>
	</button>

	<div class="primary-menu-container mobile-menu-container">
		<div class="mobile-menu-heading">
			<h3 class="menu-title"><?php esc_html_e( 'Menu', 'knowx' ); ?></h3>
			<a href="<?php echo esc_url( '#' ); ?>" class="menu-close" <?php if ( knowx()->is_amp() ) { ?>
			on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )"
			[aria-expanded]="siteNavigationMenu.expanded ? 'true' : 'false'"
			<?php } ?>><?php esc_html_e( 'Close', 'knowx' ); ?></a>
		</div>
		<?php knowx()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) ); ?>
	</div>
	
	<div class="primary-menu-container desktop-menu-container">
		<?php knowx()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) ); ?>
	</div>
</nav><!-- #site-navigation -->
