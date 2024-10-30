<?php

if(!defined('ABSPATH')) {
	exit;
}

// @package bp-site-post

// @since 1.1.0


add_action( 'bp_setup_nav', 'bpsp_add_my_posts_tab', 100 );
add_action( 'bp_setup_admin_bar', 'bpsp_admin_bar_add', 50 );

/**
 * Adds My Posts tab to the BuddyPress member profile.
 *
 * @since 1.2.2
 *
 * @return bool
 */


function bpsp_add_my_posts_tab() {
	
	if ( ! bp_is_user_profile() && ! bp_is_user_activity() && ! bp_is_user() ) {
		return;
	}
	
	global $bp;
	
	$has_posts = true;
	if ( function_exists( 'bpps_get_post_permalink' ) ) {
		return;
	}

	$count = count_user_posts( $bp->displayed_user->id, 'post' );
	$logged_in = is_user_logged_in();
	$bpsp_options = get_option('bpsp_site_post_settings');
	$members_allowed = $bpsp_options['bpsp-allow-all-members-posts'];
	if ( $members_allowed && $logged_in ) $allowed_to_post = true;
	
	if ( current_user_can( 'edit_others_posts' ) ) {
		$count_posts = wp_count_posts();
		if ( $count_posts ) {
			$mod_count = $count_posts->pending;
		} else {
			$mod_count = 0;
		}
	} else {
		$mod_count = 0;
	}
	
	if ( current_user_can( 'edit_posts' ) || ( isset( $allowed_to_post ) ) ) {
		$user_id = get_current_user_id();
		$pending_count = bpsp_count_users_pending_posts( $user_id );
	} else {
		$pending_count = 0;
	}
	if ( get_current_user_id() == bp_displayed_user_id() ) {
		$own_profile = true;
	} else {
		$own_profile = false;
	}
	$total = $count + $mod_count + $pending_count;
	
	if ( $total < 1 ) $has_posts = false;
	
	if ( $has_posts ) {
		bp_core_new_nav_item( array(
			'name'                  => sanitize_text_field(esc_attr__( 'My Posts ', 'bp-site-post' ) ) . '<span class="count">' . $total . '</span>',
			'slug'                  => 'my-posts',
			'parent_url'            => $bp->displayed_user->domain,
			'parent_slug'           => $bp->profile->slug,
			'screen_function'       => 'bpsp_my_posts_screen',			
			'position'              => 10,
			'default_subnav_slug'   => 'my-posts'
		) );
	}

	if ( ( current_user_can( 'edit_posts' ) || isset( $allowed_to_post ) ) && $pending_count > 0 && $own_profile ) {
		bp_core_new_subnav_item( array(
			'name'                  => sanitize_text_field(esc_attr__( 'Pending Posts ', 'bp-site-post' ) ) . '<span class="count">' . $pending_count . '</span>',
			'slug'                  => 'my-posts-pending',
			'parent_url'            => $bp->displayed_user->domain . 'my-posts/',
			'parent_slug'           => 'my-posts',
			'screen_function'       => 'bpsp_pending_posts_screen',			
			'position'              => 10,
			'default_subnav_slug'   => 'my-posts-pending'
		) );
	}

	if ( current_user_can( 'edit_others_posts' ) && $mod_count > 0 && $own_profile ) {
		bp_core_new_subnav_item( array(
			'name'                  => sanitize_text_field(esc_attr__( 'Moderation ', 'bp-site-post' ) ) . '<span class="count">' . $mod_count . '</span>',
			'slug'                  => 'my-posts-moderation',
			'parent_url'            => $bp->displayed_user->domain . 'my-posts/',
			'parent_slug'           => 'my-posts',
			'screen_function'       => 'bpsp_moderation_posts_screen',			
			'position'              => 10,
			'default_subnav_slug'   => 'my-posts-moderation'
		) );
	}
}


function bpsp_my_posts_screen() {

	add_action( 'bp_template_title', 'bpsp_my_posts_screen_title' );
	add_action( 'bp_template_content', 'bpsp_my_posts_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}


function bpsp_my_posts_screen_title() { 
	echo '<h3>' . sanitize_text_field(esc_attr__( 'My Posts', 'bp-site-post' ) ) . '</h3>';
}

function bpsp_my_posts_screen_content() { 
	
	if ( function_exists( 'bpps_load_template' ) ) {
		bpps_load_template( 'my-posts.php' );
	} else {
		load_template( plugin_dir_path( __DIR__ ) . 'templates/my-posts.php' );
	}
}

/**
 * Adds Pending Posts page to the users profile.
 *
 * @since 1.3.0
 *
 * @return bool
 */

function bpsp_pending_posts_screen() {

	add_action( 'bp_template_title', 'bpsp_pending_posts_screen_title' );
	add_action( 'bp_template_content', 'bpsp_pending_posts_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}


function bpsp_pending_posts_screen_title() { 
	echo '<h3>' . sanitize_text_field(esc_attr__( 'Pending Posts', 'bp-site-post' ) ) . '</h3>';
}

function bpsp_pending_posts_screen_content() { 
	
	if ( function_exists( 'bpps_load_template' ) ) {
		bpps_load_template( 'my-posts-pending.php' );
	} else {
		load_template( plugin_dir_path( __DIR__ ) . 'templates/my-posts-pending.php' );
	}
}

/**
 * Adds Posts Moderation page to the users profile.
 *
 * @since 1.3.0
 *
 * @return bool
 */

function bpsp_moderation_posts_screen() {

	add_action( 'bp_template_title', 'bpsp_moderation_posts_screen_title' );
	add_action( 'bp_template_content', 'bpsp_moderation_posts_screen_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}


function bpsp_moderation_posts_screen_title() { 
	echo '<h3>' . sanitize_text_field(esc_attr__( 'Moderation', 'bp-site-post' ) ) . '</h3>';
}

function bpsp_moderation_posts_screen_content() { 
	
	if ( function_exists( 'bpps_load_template' ) ) {
		bpps_load_template( 'my-posts-moderation.php' );
	} else {
		load_template( plugin_dir_path( __DIR__ ) . 'templates/my-posts-moderation.php' );
	}
}

/**
 * Adds My Posts links to the BuddyPress member menu for our administrators.
 *
 * @since 1.2.2
 *
 * @return bool
 */
function bpsp_admin_bar_add() {
	
	global $wp_admin_bar, $bp;

	if ( defined( 'DOING_AJAX' ) ) {
		return false;
	}

	$user_id = get_current_user_id();
	
	if ( ! $user_id || $user_id == 0 || ! is_numeric( $user_id ) ) {
		
		return;
		
	}

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'bp-post-status/loader.php' ) ) {
		return;
	}

	$logged_in = is_user_logged_in();
	$bpsp_options = get_option('bpsp_site_post_settings');
	if ( isset( $bpsp_options['bpsp-allow-all-members-posts'] ) && $bpsp_options['bpsp-allow-all-members-posts'] != '' ) $members_allowed = true;
	if ( isset( $members_allowed ) && $logged_in ) $allowed_to_post = true;

	$my_posts_page = bp_members_get_user_url( $user_id ) . 'my-posts';
	$has_posts = true;	
	$count = count_user_posts( $user_id, 'post' );
		
	if ( current_user_can( 'edit_others_posts' ) ) {
		$moderation_posts_page = bp_members_get_user_url( $user_id ) . 'my-posts/my-posts-moderation';
		$count_posts = wp_count_posts();
		if ( $count_posts ) {
			$mod_count = $count_posts->pending;
		} else {
			$mod_count = 0;
		}
	} else {
		$mod_count = 0;
	}
	
	if ( current_user_can( 'edit_posts' ) || isset( $allowed_to_post ) ) {
		$pending_posts_page = bp_members_get_user_url( $user_id ) . 'my-posts/my-posts-pending';
		$pending_count = bpsp_count_users_pending_posts( $user_id );
	} else {
		$pending_count = 0;
	}
	
	$total = $count + $mod_count + $pending_count;
	
	if ( $total < 1 ) $has_posts = false;

	$add_post_page = bpsp_get_add_post_url();
	
	if ( $has_posts ) {
		$wp_admin_bar->add_menu( array(
			'parent' => $bp->my_account_menu_id,
			'id'     => 'bp-site-post',
			'title'  => sanitize_text_field(esc_attr__( 'My Posts', 'bp-site-post' )) . '<span class="count">' . esc_attr($total) . '</span>',
			'meta' => array( 'class' => 'menupop' ),
			'href'   => $my_posts_page,
		) );
	} else if ( current_user_can( 'edit_posts' ) || $allowed_to_post ) {
		
		$wp_admin_bar->add_menu( array(
			'parent' => $bp->my_account_menu_id,
			'id'     => 'bp-site-post',
			'title'  => esc_attr__( 'Add Post', 'bp-site-post' ),
			'meta' => array( 'class' => 'menupop' ),
			'href'   => $add_post_page,
		) );		
	}

	// Submenus.
	if ( $has_posts && ( current_user_can( 'edit_posts' ) || isset( $allowed_to_post ) ) ) {
		$wp_admin_bar->add_menu( array(
			'parent' => 'bp-site-post',
			'id'     => 'bp-site-post-my-posts',
			'title'  => sanitize_text_field(esc_attr__( 'My Posts', 'bp-site-post' )) . '<span class="count">' . esc_attr($count) . '</span>',
			'href'   => $my_posts_page,
		) );
	}
	// Submenus.
	if ( isset( $add_post_page ) && ( current_user_can( 'edit_posts' ) || isset( $allowed_to_post ) ) ) {
		$wp_admin_bar->add_menu( array(
			'parent' => 'bp-site-post',
			'id'     => 'bp-site-post-add-post',
			'title'  => sanitize_text_field(esc_attr__( 'Add Post', 'bo-site-post' )),
			'href'   => $add_post_page,
		) );
	}
	// Submenus.
	if ( $mod_count >= 1 && current_user_can( 'edit_others_posts' ) ) {
		$wp_admin_bar->add_menu( array(
			'parent' => 'bp-site-post',
			'id'     => 'bp-site-post-my-posts-moderation',
			'title'  => sanitize_text_field(esc_attr__( 'Moderation', 'bp-site-post' )) . '<span class="count">' . esc_attr($mod_count) . '</span>',
			'href'   => $moderation_posts_page,
		) );
	}
	// Submenus.
	if ( $pending_count >= 1 && ( current_user_can( 'edit_posts' ) || isset( $allowed_to_post ) ) ) {
		$wp_admin_bar->add_menu( array(
			'parent' => 'bp-site-post',
			'id'     => 'bp-site-post-my-posts-pending',
			'title'  => sanitize_text_field(esc_attr__( 'Pending Posts', 'bp-site-post' )) . '<span class="count">' . esc_attr($pending_count) . '</span>',
			'href'   => $pending_posts_page,
		) );
	}


	return true;
}

// Get post permalink for the add new page.
//@since 1.2.0
//@return string url

function bpsp_get_add_post_url() {
	
	$options = get_option('bpsp_site_post_settings');
	
	if ( isset( $options['bpsp-edit-page'] ) ) {
		
		$edit_page_id = $options['bpsp-edit-page'];
		$post = get_post( $edit_page_id );
		if ( function_exists( 'bpps_get_post_permalink' ) ) {
			return bpps_get_post_permalink( $post );
		} else {
			return get_permalink( $post );
		}
	}
	
	  return false;
}

/**
 * get the query string for the profile My Posts page
 *
 * @version: since 1.3.0
 *
 * @return type array WP_Query args
 */

function bpsp_get_my_posts_query( $query_type = '', $query_string = '' ) {
	
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$user_id = bp_displayed_user_id();

	$args = array(
		'author' 			=> $user_id,
		'paged' 			=> $paged,
		'posts_per_page' 	=> 10
	);


	if ( $query_type == 'search' ) {
		$args['s'] = $query_string;
	}

	if ( ( $user_id == get_current_user_id() ) || current_user_can( 'manage_options' ) ) {
		$args['post_status'] = 'any';
	}
		
	return $args;
	
}


/**
 * get a users pending posts count.
 *
 * @since 1.3.0
 * 
 * @return int users pending posts count
 */

 function bpsp_count_users_pending_posts( $user_id = '' ) {
	
	$logged_in = is_user_logged_in();
	$bpsp_options = get_option('bpsp_site_post_settings');
	if ( isset( $bpsp_options['bpsp-allow-all-members-posts'] ) && $bpsp_options['bpsp-allow-all-members-posts'] != '' ) $members_allowed = true;
	if ( isset( $members_allowed ) && $logged_in ) $allowed_to_post = true;
	
	
	if ( ! current_user_can( 'edit_posts' ) && ! isset( $allowed_to_post ) ) {
		return false;
	}
	
	if ( $user_id == '' ) {
		$user_id = get_current_user_id();
	}
	
	global $wpdb;
	
	$pending_post_ids = array();
	$pending_posts_query = "SELECT ID from $wpdb->posts WHERE post_status = 'pending' AND post_author = $user_id";
	$pending_post_ids = $wpdb->get_results($pending_posts_query, ARRAY_N);
	
	return count( $pending_post_ids );
	
}

/**
 * count the total pending posts for a user
 *
 * @since 1.3.0
 * 
 * @return int pending posts count
 */

 function bpsp_count_pending_posts() {
	
	$logged_in = is_user_logged_in();
	$bpsp_options = get_option('bpsp_site_post_settings');
	if ( isset( $bpsp_options['bpsp-allow-all-members-posts'] ) && $bpsp_options['bpsp-allow-all-members-posts'] != '' ) $members_allowed = true;
	if ( isset( $members_allowed ) && $logged_in ) $allowed_to_post = true;

	if ( ! current_user_can( 'edit_posts' ) && ! isset( $allowed_to_post ) ) {
		return false;
	}
	global $wpdb;
	$user_id = get_current_user_id();
	$pending_post_ids = array();
	$pending_posts_query = "SELECT ID from $wpdb->posts WHERE post_status = 'pending' && post_author = $user_id";
	$pending_post_ids = $wpdb->get_results($pending_posts_query, ARRAY_N);
	
	return count( $pending_post_ids );
	
}

/**
 * count the total moderation posts for a site
 *
 * @since 1.3.0
 * 
 * @return int pending posts count
 */

 function bpsp_count_moderation_posts() {
	
	if ( ! current_user_can( 'edit_others_posts' ) ) {
		return false;
	}
	global $wpdb;
	
	$mod_post_ids = array();
	$mod_posts_query = "SELECT ID from $wpdb->posts WHERE post_status = 'pending'";
	$mod_post_ids = $wpdb->get_results($mod_posts_query, ARRAY_N);
	
	return count( $mod_post_ids );
	
}

