<?php
/**
 * Template part for displaying a pagination
 *
 * @package knowx
 */

namespace KnowX\KnowX;

the_posts_pagination(
	[
		'mid_size'           => 2,
		'prev_text'          => _x( 'Previous', 'previous set of search results', 'knowx' ),
		'next_text'          => _x( 'Next', 'next set of search results', 'knowx' ),
		'screen_reader_text' => __( 'Page navigation', 'knowx' ),
	]
);
