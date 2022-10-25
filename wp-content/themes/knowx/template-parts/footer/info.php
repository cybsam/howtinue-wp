<?php
/**
 * Template part for displaying the footer info
 *
 * @package knowx
 */

namespace KnowX\KnowX;

?>
<div class="site-info">
	<div class="container">	
		<?php echo knowx_footer_custom_text(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>

	<?php
	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link();
	}
	?>
</div><!-- .site-info -->
