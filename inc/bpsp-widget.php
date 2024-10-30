<?php
/**
 * Makes a custom Widget for displaying a post form in the sidebar
 *
 *
 * @package BP Site Post
 * @since BP Site Post 1.0.0
*/

if(!defined('ABSPATH')) {
	exit;
}

class BPSP_Site_Post_Widget extends WP_Widget {

	/**
	 * Constructor
	 *
	 * @return void
	 **/
	function __construct() {
		$widget_ops = array( 'classname' => 'bpsp_site_post_widget', 'description' => sanitize_text_field(esc_attr__( 'Use this widget to include a form to write and publish articles from the sidebar.', 'bp-site-post' )));
		parent::__construct(
			'bpsp_site_post_widget',
			sanitize_text_field(esc_attr__( 'BP Site Post Widget', 'bp-site-post' ) ),
			$widget_ops);
	}
	function bpsp_site_post_widget() {
//		$widget_ops = array( 'classname' => 'bpsp_site_post_widget', 'description' => sanitize_text_field(esc_attr__( 'Use this widget to include a form to write and publish articles from the sidebar.', 'bp-site-post' )));
//		$this->WP_Widget( 'bpsp_site_post_widget', sanitize_text_field(esc_attr__( 'BP Site Post Widget', 'bp-site-post' )), $widget_ops );
		$this->alt_option_name = 'bpsp_site_post_widget';

		add_action( 'save_post', array(&$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache' ) );
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array An array of standard parameters for widgets in this theme
	 * @param array An array of settings for this widget instance
	 * @return void Echoes it's output
	 **/
	function widget( $args, $instance ) {
		$cache = wp_cache_get( 'bpsp_site_post_widget', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = null;

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract( $args, EXTR_SKIP );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? sanitize_text_field(esc_attr__( 'BP Site Post', 'bp-site-post' )) : esc_attr(sanitize_text_field($instance['title'])), $instance, $this->id_base);

		echo $before_widget;
		echo $before_title;
		echo $title; // Can set this with a widget option, or omit altogether
		echo $after_title;

		echo do_shortcode('[bp-site-post called_from_widget = "1"]');

		echo $after_widget;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set( 'bpsp_site_post_widget', $cache, 'widget' );
	}

	/**
	 * Deals with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 **/
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = wp_strip_all_tags(esc_attr(sanitize_text_field( $new_instance['title'] )));
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['bpsp_site_post_widget'] ) )
			delete_option( 'bpsp_site_post_widget' );

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'bpsp_site_post_widget', 'widget' );
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 **/
	function form( $instance ) {
		$title = isset( $instance['title']) ? esc_attr( $instance['title'] ) : '';
		?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php sanitize_text_field(esc_attr_e( 'Title:', 'bp-site-post' )); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr(sanitize_text_field( $this->get_field_name( 'title' ))); ?>" type="text" value="<?php echo esc_attr(sanitize_text_field( $title )); ?>" /></p>
		<?php
	}
}