<?php
namespace RQ;



add_filter( 'rq_title', 'RQ\title_builtin', 10, 2 );
function title_builtin($name, $link){
	$tag = 'h4';
	if( $name )
		$name = "<{$tag}>{$name}</{$tag}>";
	
	if( $link )
		$name = "<a href='{$link}'>{$name}</a>";

	return $name;
}
/**
 * Show Review Post From Archive 
 */
add_shortcode( 'RQ_ARCHIVE', 'RQ\posts_render' );
add_shortcode( 'RQ_POSTS', 'RQ\posts_render' );
add_shortcode( 'rq', 'RQ\posts_render' );
function posts_render(){
	$query = new \WP_Query(	array(
		'post_type' => RQ_TYPE,
		'posts_per_page' => -1,
		'post_status' => 'publish',
		//'order'   => 'DESC', // or ASC
		) );

	ob_start();
	while ( $query->have_posts() ) {
		$query->the_post();

		get_template_part( 'template-parts/content', RQ_TYPE );
	}
	return ob_get_clean();
}

/**
 * Show Review Forms
 */