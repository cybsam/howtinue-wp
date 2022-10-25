<?php
/**
 * Template Name: Knowledge Base Layout Two
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

?>

	<?php do_action( 'knowx_sub_header' ); ?>
	
	<?php do_action( 'knowx_before_content' ); ?>
	
	<main id="primary" class="site-main">
		
	<?php
	if ( have_posts() ) {

		if ( get_theme_mod( 'knowx_page_title' ) == 'yes' ) {
			get_template_part( 'template-parts/content/page_header' );
		}

		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content/entry', 'page' );
		}
	} else {
		get_template_part( 'template-parts/content/error' );
	}
	?>

	<div id="knowx-categories-columns-four" class="knowx-knowledgebase-two">
		<?php
		$exclude_cats = get_post_meta( get_the_ID(), 'knowx-exclude-cats', true );
		if ( ! empty( $exclude_cats ) ) {
			$exclude = esc_attr( $exclude_cats );
		} elseif ( get_theme_mod( 'knowx_exclude' ) ) {
			$exclude = esc_attr( get_theme_mod( 'knowx_exclude' ) );
		} else {
			$exclude = '';
		}
		$knowx_cat_args = array(
			'hide_empty' => 0,
			'exclude'    => $exclude,
			'orderby'    => 'name',
			'order'      => 'asc',
		);
		$knowx_cats     = get_categories( $knowx_cat_args );

		foreach ( $knowx_cats as $category ) :
			echo '<ul class="cat-list"><li class="cat-name"><h2><a href="' . get_category_link( $category->cat_ID ) . '" title="' . $category->name . '" >' . $category->name . '</a></h2></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( get_theme_mod( 'knowx_cat_description' ) == 'yes' ) :
				if ( category_description( $category->cat_ID ) ) :
					echo '<div class="cat-description">' . wp_kses_post( category_description( $category->cat_ID ) ) . '</div>';
				endif;
			endif;

			if ( get_theme_mod( 'knowx_posts' ) ) :
				$posts_per_page = esc_attr( get_theme_mod( 'knowx_posts' ) );
			else :
				$posts_per_page = -1;
			endif;

			if ( get_theme_mod( 'knowx_order' ) == 'name' ) :
				$order_by  = 'name';
				$the_order = 'asc';
			else :
				$order_by  = 'date';
				$the_order = 'desc';
			endif;

			$knowx_post_args = array(
				'post_type'      => 'post',
				'tax_query'      => array(
					array(
						'taxonomy'         => 'category',
						'field'            => 'term_id',
						'terms'            => $category->term_id,
						'include_children' => false,
					),
				),
				'posts_per_page' => $posts_per_page,
				'orderby'        => $order_by,
				'order'          => $the_order,
			);

			$knowx_posts = get_posts( $knowx_post_args );

			foreach ( $knowx_posts as $single_post ) :
				echo '<li class="post-name"><a href="' . esc_url( get_permalink( $single_post->ID ) ) . '" rel="bookmark" title="' . esc_attr( get_the_title( $single_post->ID ) ) . '">' . esc_html( get_the_title( $single_post->ID ) ) . '</a></li>';
			endforeach;

			if ( get_theme_mod( 'knowx_view_all' ) == 'yes' ) {
				echo '<li class="view-all"><a href="' . esc_url( get_category_link( $category->cat_ID ) ) . '" title="' . esc_attr( $category->name ) . '" >' . esc_html( 'View All &raquo;', 'knowx' ) . '</a></li>';
			}

			echo '</ul>';
		endforeach;
		?>
	</div>

	</main><!-- #primary -->

	<?php do_action( 'knowx_after_content' ); ?>
<?php
get_footer();
