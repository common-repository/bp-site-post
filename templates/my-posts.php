<?php
/*
	This page is used for the profile my posts page
	
*/

if(!defined('ABSPATH')) {
	exit;
}

$user_id = bp_displayed_user_id();
$user = get_userdata( $user_id );

if ( ( $user_id == get_current_user_id() ) || current_user_can( 'manage_options' ) ) {
	$author_view = true;
}

if ( ! empty( $_GET['bpsp-search-type'] ) ) {
	$is_search = esc_attr( $_GET['bpsp-search-type'] );
	$search_string = esc_attr(sanitize_text_field( $_GET['s'] ));
}

$add_post_url = bpsp_get_add_post_url();

$slug = '';//fix for post_permalink not returning post slug

//query_posts( $args );
if ( isset( $is_search ) ) {
	$q = new WP_Query( bpsp_get_my_posts_query( 'search', $search_string ) );
} else {
	$q = new WP_Query( bpsp_get_my_posts_query() );
}

if ( current_user_can( 'edit_posts' ) ) :?>

<input type="button" value="<?php echo sanitize_text_field(esc_attr__( 'Add Post', 'bp-site-post' )); ?>" onclick="window.location.href='<?php echo esc_url($add_post_url); ?>'" /><br /> 

<?php endif; ?>

<div class="search-form">
	<form class="searchform" method="get" action="<?php echo esc_url(bp_displayed_user_domain()) . '/my-posts'; ?>">
		<div class="input-group">
			
			<input type="text" name="s" class="form-control" placeholder="<?php sanitize_text_field(esc_attr_e( 'Search ', 'bp-site-post' )); echo esc_attr(sanitize_text_field(bp_core_get_user_displayname( $user_id ))); echo sanitize_text_field(esc_attr__( '\'s posts', 'bp-site-post' )); ?>">
			
			<input type="hidden" value="<?php echo esc_attr(sanitize_text_field($user->user_login)); ?>" name="author_name" />
			
			<input type="hidden" value="my-posts-search" name="bpsp-search-type" />
			
			<span class="input-btn">
				<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
			</span>
		</div>
		<br/>
		<?php do_action('bpsp_my_posts_search_form'); ?>
	</form>
</div>


<?php if ($q->have_posts() ) : ?>

<?php do_action( 'bpps_before_pending_posts_content' ) ?>

<div class="pagination no-ajax">
	<div id="posts-count" class="pag-count">
		<?php esc_attr(bpsp_posts_pagination_count( $q )) ?>
	</div>

	<div id="posts-pagination" class="pagination-links">
		<?php bpsp_pagination( $q ) ?>
	</div>
</div>

<?php do_action( 'bpsp_before_my_post_list' ) ?>

<?php
global $post;
bpsp_loop_start();?>

<form>

<?php while( $q->have_posts() ):$q->the_post(); ?>

<div class="post" id="post-<?php esc_attr(the_ID()); ?>">
	
	<div class="post-content">
		
		<?php if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( get_the_ID() ) ):?>

			<div class="post-featured-image">
				<?php  esc_html(the_post_thumbnail());?>
			</div>

		<?php endif; ?>
		
		<h2 class="posttitle"><a href="<?php echo esc_html(get_the_permalink($post)); ?>" rel="bookmark" title="<?php sanitize_text_field(esc_attr_e( 'Link to', 'bp-site-post' )) ?> <?php esc_attr(the_title_attribute()); ?>"><?php esc_attr(sanitize_text_field(the_title())); ?></a></h2>

		<p class="category"> <em><?php sanitize_text_field(esc_attr_e( 'in', 'bp-site-post' )); the_category(', '); ?></em></p>

		<div class="entry">
			<?php the_excerpt( $post->ID ); ?>
		</div>
		
		<p class="postmetadata"><span class="tags"><?php the_tags( sanitize_text_field(esc_attr__('Tags: ', 'bp-post-status' )), ', ', '<br />'); ?></span> <span class="comments"><?php comments_popup_link( sanitize_text_field(esc_attr__( 'No Comments &#187;', 'bp-post-status' )), sanitize_text_field(esc_attr__( '1 Comment &#187;', 'bp-post-status' )), sanitize_text_field(esc_attr__( '% Comments &#187;', 'bp-post-status' ))); ?></span></p>
		
		<?php if ( isset( $author_view ) ) :?>
		
				<em class="post-status"><?php echo sanitize_text_field(esc_attr__( 'Post Status: ', 'bp-post-status' )); echo esc_attr($post->post_status);?></em>
				
				<em class="post-date"><?php echo sanitize_text_field(esc_attr__( 'Post Date: ', 'bp-post-status' )); echo esc_attr(get_the_time(get_option( 'date_format' )));?></em>
				
				<p id="post-label-<?php esc_attr(the_ID()); ?>" style="display:none;"></p>
		
		<?php endif; ?>
	
	</div>
</div>

<?php endwhile;?>

</form>

<?php 
	do_action( 'bpsp_after_my_post_content' ) ;
	bpsp_loop_end();
?>

<div class="pagination no-ajax">
	<div id="posts-count" class="pag-count">
		<?php bpsp_posts_pagination_count( $q ) ?>
	</div>

	<div id="posts-pagination" class="pagination-links">
		<?php bpsp_pagination( $q ) ?>
	</div>
</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php sanitize_text_field(esc_attr_e( 'No posts found.', 'bp-site-post' )); ?></p>
	</div>
<?php endif;

wp_reset_postdata(); ?>
