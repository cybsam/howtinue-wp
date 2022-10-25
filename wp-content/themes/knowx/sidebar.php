<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package knowx
 */

namespace KnowX\KnowX;

if ( ! knowx()->is_left_sidebar_active() || ! knowx()->is_right_sidebar_active() ) {
	return;
}

knowx()->print_styles( 'knowx-sidebar', 'knowx-widgets' );

?>
<aside id="secondary" class="primary-sidebar widget-area">
	<div class="sticky-sidebar">
		<?php
		$sidebar = get_theme_mod( 'sidebar_option' );
		switch ( $sidebar ) {
			case 'left':
				knowx()->display_left_sidebar();
				break;
			case 'right':
				knowx()->display_right_sidebar();
				break;
			default:
		}
		?>
	</div>
</aside><!-- #secondary -->
