<?php
/*
	This page is used for the profile pending posts page
	
*/

if(!defined('ABSPATH')) {
	exit;
}

if ( ! current_user_can( 'edit_posts' ) ) {
	return;
}


$args = array( 
	'author' => bp_displayed_user_id(),
	'post_status' => array(
		'pending'
) );
//query_posts( $args );
$q = new WP_Query( $args );

if ($q->have_posts() ) : ?>
<?php do_action( 'bpsp_before_pending_posts_content' ) ?>
<div class="pagination no-ajax">
	<div id="posts-count" class="pag-count">
		<?php bpsp_posts_pagination_count( $q ) ?>
	</div>

	<div id="posts-pagination" class="pagination-links">
		<?php bpsp_pagination( $q ) ?>
	</div>
</div>

<?php do_action( 'bpsp_before_pending_posts_list' ) ?>
<?php
global $post;
bpsp_loop_start();
?><form><?php
while( $q->have_posts() ):$q->the_post();
?>
<div class="post" id="post-<?php the_ID(); ?>">
	<div class="post-content">
		
		<?php if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( get_the_ID() ) ):?>

			<div class="post-featured-image">
				<?php  esc_html(the_post_thumbnail());?>
			</div>

		<?php endif; ?>
		
		<h2 class="posttitle"><a href="<?php echo esc_url(get_the_permalink($post)) ?>" rel="bookmark" title="<?php sanitize_text_field(esc_attr_e( 'Link to', 'bp-site-post' )) ?> <?php esc_attr(the_title_attribute()); ?>"><?php esc_attr(sanitize_text_field(the_title())); ?></a></h2>

		<p class="category"><?php// the_time() ?> <em><?php sanitize_text_field(esc_attr_e( 'in', 'bp-site-post' )) ?> <?php the_category(', ') ?></em></p>

		<div class="entry">
			<?php the_excerpt( $post->ID ); ?>
		</div>
		
		<p class="postmetadata"><span class="tags"><?php the_tags( sanitize_text_field(esc_attr__( 'Tags: ', 'bp-site-post' )), ', ', '<br />'); ?></span> <span class="comments"><?php comments_popup_link( sanitize_text_field(esc_attr__( 'No Comments &#187;', 'bp-site-post' )), sanitize_text_field(esc_attr__( '1 Comment &#187;', 'bp-site-post' )), sanitize_text_field(esc_attr__( '% Comments &#187;', 'bp-site-post' ))); ?></span></p>
		
		<em class="post-status"><?php echo sanitize_text_field(esc_attr__( 'Post Status: ', 'bp-site-post' )); echo esc_attr(sanitize_text_field($post->post_status));?></em>
		
		<em class="post-date"><?php echo sanitize_text_field(esc_attr__( 'Post Date: ', 'bp-site-post' )); echo esc_attr(get_the_time(get_option( 'date_format' )));?></em>
		
		<p id="post-label-<?php esc_attr(the_ID()); ?>" style="display:none;"></p>

	</div>
</div>

<?php endwhile;?>

</form>

<?php 
	do_action( 'bpsp_after_pending_posts_content' ) ;
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
