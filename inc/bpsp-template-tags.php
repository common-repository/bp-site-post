<?php
/*
 * Template Tags for Site Post
 * @since 1.3.0
 * @package bp-site-post
 *
 */
if(!defined('ABSPATH')) {
	exit;
}
//if inside the post loop
function in_bpsp_loop() {
	
	$bp = buddypress();
	
	return isset( $bp->bpsp ) ? $bp->bpsp->in_the_loop : false;
}

//use it to mark t5he start of post loop
function bpsp_loop_start() {
	$bp = buddypress();

	$bp->bpsp = new stdClass();
	$bp->bpsp->in_the_loop = true;
}

//use it to mark the end of loop
function bpsp_loop_end() {
	$bp = buddypress();

	$bp->bpsp->in_the_loop = false;
}


/**
 * Generate Pagination Link for posts
 * @param type $q 
 */
function bpsp_pagination( $q ) {

	$posts_per_page = intval( get_query_var( 'posts_per_page' ) );
	$paged = intval( get_query_var( 'paged' ) );
	
	$numposts = $q->found_posts;
	$max_page = $q->max_num_pages;
	
	if ( empty( $paged ) || $paged == 0 ) {
		$paged = 1;
	}

	$pag_links = paginate_links( array(
		'base'		=> add_query_arg( array( 'paged' => '%#%', 'num' => $posts_per_page ) ),
		'format'	=> '',
		'total'		=> ceil( $numposts / $posts_per_page ),
		'current'	=> $paged,
		'prev_text'	=> '&larr;',
		'next_text'	=> '&rarr;',
		'mid_size'	=> 1
	) );
	
	echo $pag_links;
}

//viewing x of z posts
function bpsp_posts_pagination_count( $q ) {

	$posts_per_page = intval( get_query_var( 'posts_per_page' ) );
	$paged = intval( get_query_var( 'paged' ) );
	
	$numposts = $q->found_posts;
	$max_page = $q->max_num_pages;
	
	if ( empty( $paged ) || $paged == 0 ) {
		$paged = 1;
	}

	$start_num = intval( $posts_per_page * ( $paged - 1 ) ) + 1;
	$from_num = bp_core_number_format( $start_num );
	$to_num = bp_core_number_format( ( $start_num + ( $posts_per_page - 1 ) > $numposts ) ? $numposts : $start_num + ( $posts_per_page - 1 )  );
	
	$total = bp_core_number_format( $numposts );

	//$taxonomy = get_taxonomy( bcg_get_taxonomies() );
	$post_type_object = get_post_type_object( 'post' );

	printf( sanitize_text_field(esc_attr__( 'Viewing %1s %2$s to %3$s (of %4$s )', 'bp-site-post' )), esc_attr(sanitize_text_field($post_type_object->labels->name)), esc_attr(sanitize_text_field($from_num)), esc_attr(sanitize_text_field($to_num)), esc_attr(sanitize_text_field($total ))) . "&nbsp;";
	?>
	<span class="ajax-loader"></span><?php
}

/**

 * Let bp nouveau know the my posts and pending posts nav have a count.

 *

 * @since 1.3.0

 * @return bool

 */

function bpsp_nouveau_nav_has_count( $status, $nav_item, $displayed_nav ) {
	
	if ( $nav_item->slug == 'my-posts-pending' ) {
		$count = bpsp_count_pending_posts();
		if ( $count >= 1 ) {
			return true;
		}
	} else if ( $nav_item->slug == 'my-posts-moderation' ) {
		$count = bpsp_count_moderation_posts();
		if ( $count >= 1 ) {
			return true;
		}
	} else if ( $nav_item->slug == 'my-posts' ) {
		$pen_count = bpsp_count_pending_posts();
		$mod_count = bpsp_count_moderation_posts();
		$count = count_user_posts( bp_displayed_user_id(), 'post' );
		if ( $count + $pen_count + $mod_count >= 1 ) {
			return true;
		}
	}
	return $status;
	
}

/**

 * set let bp nouveau know the my posts and pending posts count.

 *

 * @since 1.3.0

 * 

 * @return int

 */

function bpsp_nouveau_get_nav_count( $count, $nav_item, $displayed_nav ) {
	
	if ( $nav_item->slug == 'my-posts-pending' ) {
		$count = bpsp_count_pending_posts();
		return $count;
	} else if ( $nav_item->slug == 'my-posts-moderation' ) {
		$count = bpsp_count_moderation_posts();
		return $count;
	} else if ( $nav_item->slug == 'my-posts' ) {
		$pen_count = bpsp_count_pending_posts();
		$mod_count = bpsp_count_moderation_posts();
		$count = count_user_posts( bp_displayed_user_id(), 'post' );
		return $count + $pen_count + $mod_count;
	}
	return 0;
	
}
