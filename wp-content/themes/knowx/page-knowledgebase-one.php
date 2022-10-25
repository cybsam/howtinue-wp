<?php
/**
 * Template Name: Knowledge Base Layout One
 * Description: Template for displaying Categories and Posts
 * Template Post Type: page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package knowx
 */

namespace KnowX\KnowX;

get_header();

knowx()->print_styles( 'knowx-content' );

$hero_title   = get_theme_mod( 'hero_section_heading' );
$hero_content = get_theme_mod( 'hero_section_content' );

$support_title   = get_theme_mod( 'support_section_heading' );
$support_content = get_theme_mod( 'support_section_content' );

$support_button_text = get_theme_mod( 'support_section_button_text' );
$support_button_link = get_theme_mod( 'support_section_button_link_url' );

?>
	</div><!--close .container-->
	<?php $backgroundImg = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="knowx-section-hero" style="background-image: url(' <?php echo $backgroundImg[0]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> ');">
		<?php } else { ?>
			<div class="knowx-section-hero">
		<?php } ?>
		<div class="container">
			<?php if ( ! empty( $hero_title ) ) { ?>
				<h1 class="entry-title"><?php echo esc_html( $hero_title ); ?></h1>
				<?php
			} else {
				get_template_part( 'template-parts/content/page_header' );
			}
			?>
			<?php if ( ! empty( $hero_content ) ) { ?>
				<div class="entry-summary">
					<?php echo esc_html( $hero_content ); ?>
				</div><!-- .entry-summary -->
			<?php } else { ?>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-summary -->
			<?php } ?>
			<div class="knowx-hero-section-search">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
	
	
	<?php do_action( 'knowx_before_content' ); ?>
	
	<main id="primary" class="site-main">

	<div class="container">

		<div id="knowx-categories-columns-three" class="knowx-knowledgebase-one">
			<?php
			$exclude_cats = get_post_meta( get_the_ID(), 'knowx-exclude-cats', true );
			if ( ! empty( $exclude_cats ) ) {
				$exclude = esc_attr( $exclude_cats );
			} elseif ( get_theme_mod( 'knowx_exclude_posts' ) ) {
				$exclude = esc_attr( get_theme_mod( 'knowx_exclude_posts' ) );
			} else {
				$exclude = '';
			}
			$knowx_cat_args = array(
				'parent'     => 0,
				'hide_empty' => 0,
				'exclude'    => $exclude,
				'orderby'    => 'name',
				'order'      => 'asc',
			);
			$knowx_cats     = get_categories( $knowx_cat_args );

			foreach ( $knowx_cats as $category ) :
				echo '<ul class="cat-list"><li class="cat-name"><h2><a href="' . get_category_link( $category->cat_ID ) . '" title="' . $category->name . '" >' . $category->name . '</a></h2></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</ul>';
			endforeach;
			?>
		</div><!-- #knowx-categories-columns-three -->

		<div class="knowx-post-section">
			<div class="row">
				<div class="col-md-6">
					<div class="knowx-popular-posts">
					<h3><?php esc_html_e( 'Popular Articles', 'knowx' ); ?></h3>
					<?php
					if ( function_exists( 'wpp_get_mostpopular' ) ) {
						/* Get up to the top 5 */
						wpp_get_mostpopular(
							array(
								'limit' => 5,
							)
						);
					} else {
						?>
						<p><?php esc_html_e( 'No data found.', 'knowx' ); ?></p>
						<?php
					}
					?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="knowx-recent-posts">
					<h3><?php esc_html_e( 'Recent Articles', 'knowx' ); ?></h3>
					<?php
						$related = get_posts( array( 'numberposts' => 5 ) );
					if ( $related ) {
						foreach ( $related as $post ) {
							setup_postdata( $post );
							?>
								<ul> 
									<li><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
								</ul>   
							<?php
						}
					}
							wp_reset_postdata();
					?>
					</div>
				</div>
			</div><!-- .row -->
		</div><!-- .knowx-post-section -->

	</div><!-- .container -->

	<div class="knowx-support-section">
		<div class="container">
			<div class="knowx-support-heading">
			<?php if ( ! empty( $support_title ) ) { ?>
					<h2 class="knowx-support-title"><?php echo esc_html( $support_title ); ?></h2>
				<?php } else { ?>
					<h2 class="knowx-support-title"><?php esc_html_e( 'Can\'t find what you\'re looking for?', 'knowx' ); ?></h2>
				<?php } ?>
			</div>
			<div class="knowx-support-content">
				<?php if ( ! empty( $support_content ) ) { ?>
					<p><?php echo esc_html( $support_content ); ?></p>
				<?php } else { ?>
					<p><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.', 'knowx' ); ?></p>
				<?php } ?>
			</div>
			<div class="knowx-support-button">
				<?php if ( ! empty( $support_button_text ) && ! empty( $support_button_link ) ) { ?>
					<a href="<?php echo esc_url( $support_button_link ); ?>">
						<?php echo esc_html( $support_button_text ); ?>
					</a>
				<?php } else { ?>
					<a href="<?php esc_url( '#' ); ?>"><?php esc_html_e( 'Contact Support', 'knowx' ); ?></a>
				<?php } ?>
			</div>
		</div><!-- .knowx-support-section -->
	</div><!-- .container -->
</main><!-- #primary -->

	<?php do_action( 'knowx_after_content' ); ?>
<?php
get_footer();
