<?php
/*
Plugin Name: BP Site Post
Plugin URI: https://wordpress.org/plugins/bp-site-post/
Description: Write, publish and edit posts in the front end, BP Post Status enabled 
Version: 1.8.0
Author: Venutius
Author URI: https://www.buddyuser.com
Text Domain: bp-site-post
License: GPL2

  Copyright 2021 BuddyUser.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

//Todo: put in place check for two people editing the same post at the same time.


if(!defined('ABSPATH')) {
	exit;
}

if (!class_exists("BP_Site_Post")) {
	class BP_Site_Post {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'plugin_textdomain' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
//		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
//		register_uninstall_hook( __FILE__, array( 'bp-site-post', 'uninstall' ) );

		//Hooking in to setup admin settings page and settings menu
		add_action('admin_init', array($this, 'admin_init'));
		add_action('admin_menu', array($this, 'add_menu'));

		/**
		 * Custom actions
		 */

		// Setup Ajax Support
		add_action('wp_ajax_process_site_post_form', array( $this, 'process_site_post_form' ) );
		add_action('wp_ajax_nopriv_process_site_post_form', array( $this, 'process_site_post_form' ) );

		// Hide Toolbar
		add_action('init', array($this, 'hide_toolbar'));

		// Hide Toolbar items
		add_action('admin_bar_menu', array( $this, 'hide_toolbar_items' ), 999  );
		
		// Redirect non admin users from dashboard
		add_action( 'admin_init', array($this, 'redirect_nonadmin_fromdash'), 1);
	
		// Register a widget to show the post form in a sidebar
		add_action( 'widgets_init', array($this,'register_form_widget' ));
		 
		// Save an auto-draft to get a valid post-id
		add_action ('save_bpsp_auto_draft', array($this, 'save_bpsp_auto_draft'));

		/**
		 * Custom filter
		 */

		// Print an edit post on front end link whenever an edit post link is printed on front end.
		add_filter('edit_post_link', array($this, 'edit_post_link'), 10, 2);

		// Add edit link to content
		add_filter( 'the_content', array( $this, 'content_edit_post_link' ), 30, 2 );
		add_filter( 'the_excerpt', array( $this, 'content_edit_post_link' ), 30, 2 );
		add_filter( 'bpps_the_excerpt', array( $this, 'content_edit_post_link' ), 30, 2 );
		add_filter( 'bpps_create_summary', array( $this, 'content_edit_post_link' ), 30, 2 );
		// Redirect non admin users from dashboard
		// add_filter('login_redirect', array($this, 'bpsp_login_redirect'), 10, 3);
		
		//Call our shortcode handler
		add_shortcode('bp-site-post', array($this, 'handle_shortcode'));
		
		//Load  additional PHP
		add_action( 'init', array( $this, 'load_files' ) );
		
	} // end constructor

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */

	public function activate( $network_wide ) {
		$this->set_default_options();
	} // end activate

	/**
	 * Loads the plugin text domain for translation
	 */
	public function plugin_textdomain() {
		$domain = 'bp-site-post';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	} // end plugin_textdomain

	/**
	 * Loads dependent PHP scripts
	 */
	 public function load_files() {
		 include_once( plugin_dir_path( __FILE__ ) . 'inc/bpsp-template-tags.php' );
		 include_once( plugin_dir_path( __FILE__ ) . 'inc/bp-members.php' );
	 }

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
		wp_enqueue_style( 'bp-site-post-admin-styles', plugins_url( 'bp-site-post/css/admin.css' ) );
	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {
		wp_enqueue_script( 'bp-site-post-admin-script', plugins_url( 'bp-site-post/js/admin.js' ) );
		} // end register_admin_scripts

	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	public function register_plugin_styles() {
		wp_enqueue_style( 'bp-site-post-styles', plugin_dir_url( __FILE__ ) . '/css/display.css' );
	} // end register_plugin_styles

	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts() {
		if ( !wp_script_is( 'jquery', 'queue' ) ){
			wp_enqueue_script( 'jquery' );
		}
		wp_enqueue_script( 'bp-site-post-script', plugins_url( 'bp-site-post/js/display.js' ) );
		wp_enqueue_script( 'bp-site-post-ajax-script', plugins_url( 'bp-site-post/js/script.js' ) );
		wp_enqueue_script( 'farbtastic', home_url() . '/wp-admin/js/farbtastic.js' );
		wp_localize_script( 'bp-site-post-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php'), 'check_nonce' => wp_create_nonce('bpps-nonce') ) );		
	} // end register_plugin_scripts

	/**
	 * Registers our post form widget.
	 */
	public function register_form_widget() {
		require(sprintf("%s/inc/bpsp-widget.php", dirname(__FILE__)));
		register_widget( 'bpsp_site_post_widget' );
	} // end register_form_widget
	
	/*
	 * Hook into WP's admin_init action hook
	 */
	public function admin_init() {
		// Set up the settings for this plugin
		$this->init_settings();
	} // end public static function activate

	/*
	 * Redirect non admin users from dashboard
	 */
	public function redirect_nonadmin_fromdash() {
		$bpsp_options = get_option('bpsp_site_post_settings');

		if ( sanitize_text_field( $_SERVER['PHP_SELF'] ) == '/wp-admin/async-upload.php' ) {
			/* allow users to upload files */
			return true;
		} else if ( isset( $bpsp_options['bpsp-no-backend'] ) && $bpsp_options['bpsp-no-backend'] != 0 && ! current_user_can('publish_posts') ) {
			/* custom function get_user_role() checks user role, 
			requires administrator, else redirects */
			wp_safe_redirect(site_url());
			exit;
		}
	}

	public function bpsp_login_redirect( $redirect_to, $request, $user  ) {
		return ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) ? admin_url() : site_url();
	}

	/*
	 * Setting default values and store them in db
	 */
	public function set_default_options() {
		$defaultAdminOptions = array(
			'bpsp-form-name' 						=> esc_attr__(sanitize_text_field('Site Post'), 'bp-site-post'),
			'bpsp-edit-page'						=> '',
			'bpsp-publish-status' 					=> 'pending',
			'bpsp-enforce-unique-title'				=> '',
			'bpsp-post-confirmation' 				=> esc_attr__(sanitize_text_field('Post Submitted'), 'bp-site-post'),
			'bpsp-post-fail' 						=> esc_attr__(sanitize_text_field('Sorry that failed, please review your post and check all required items have been completed'), 'bp-site-post'),
			'bpsp-post-not-unique'					=> esc_attr__(sanitize_text_field('Duplicate title detected: Please provide an alternative title'), 'bp-site-post'),
			'bpsp-auto-add-thumb'					=> '1',
			'bpsp-redirect' 						=> '',
			'bpsp-no-backend'						=> '',
			'bpsp-mail' 							=> '1',
			'bpsp-hide-toolbar' 					=> '',
			'bpsp-hide-new' 						=> '',
			'bpsp-hide-toolbar-edit' 				=> '',
			'bpsp-hide-toolbar-wp'					=> '',
			'bpsp-hide-toolbar-site-name'			=> '',
			'bpsp-hide-toolbar-comments'			=> '',
			'bpsp-hide-edit' 						=> '',
			'bpsp-login-link' 						=> '',
			'bpsp-post-format' 						=> '',
			'bpsp-post-format-default' 				=> 'standard',
			'bpsp-allow-all-members-posts' 			=> '1',
			'bpsp-allow-guest-posts' 				=> '',
			'bpsp-guest-account' 					=> '',
			'bpsp-guest-cat-select' 				=> '1',
			'bpsp-guest-cat' 						=> '',
			'bpsp-categories' 						=> 'list',
			'bpsp-restrict-categories' 				=> '',
			'bpsp-restricted-categories' 			=> array(),
			'bpsp-default-category' 				=> '1',
			'bpsp-allow-new-category' 				=> '',
			'bpsp-category-order' 					=> 'id',
			'bpsp-title-required' 					=> '1',
			'bpsp-show-excerpt' 					=> '1',
			'bpsp-allow-media-upload' 				=> '',
			'bpsp-allow-subscriber-media-upload'	=> '',
			'bpsp-upload-no-content' 				=> '1',
			'bpsp-show-tags' 						=> '1',
			'bpsp-guest-info' 						=> '1',
			'bpsp-title' 							=> '',
			'bpsp-excerpt' 							=> '',
			'bpsp-content' 							=> '',
			'bpsp-editor-style' 					=> 'rich',
			'bpsp-upload' 							=> '',
			'bpsp-tags' 							=> '',
			'bpsp-categories-label' 				=> '',
			'bpsp-create-category' 					=> '',
			'bpsp-send-button' 						=> ''
		);
		// Check for previous options that might be stored in db
		$dbOptions = get_option('bpsp_site_post_settings');
		if (!empty($dbOptions)) {
			foreach ($dbOptions as $key => $option)
				$defaultAdminOptions[$key] = $option;
		}
		update_option('bpsp_site_post_settings', $defaultAdminOptions);
	} // end set_default_options()

	/*
	 * Initialize some custom settings
	 */
	public function init_settings() {
		// Register the settings for this plugin
		register_setting('bpsp_site_post_template_group', 'bpsp_site_post_settings', array($this, 'bpsp_site_post_validate_input'));
	} // end public function init_custom_settings()

	/*
	 * Add a menu for our settings page
	 */
	public function add_menu() {
		add_options_page( esc_attr( esc_attr__(sanitize_text_field('BP Site Post Settings'), 'bp-site-post' ) ), esc_attr__(sanitize_text_field( 'BP Site Post'), 'bp-site-post' ), 'manage_options', 'bp-site-post-settings', array($this, 'plugin_settings_page'));
	} // end public function add_menu()

	/*
	 * Admin menu callback
	 */
	public function plugin_settings_page() {
		if(!current_user_can('manage_options')) {
			wp_die( esc_attr__(sanitize_text_field( 'You do not have sufficient permissions to access this page.'), 'bp-site-post' ) );
		}
		// Render the settings template
		include(sprintf("%s/views/admin.php", dirname(__FILE__)));
	} // end public function plugin_settings_page()

	//function plugin_options_tabs() {
	//	$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'bpsp_general_settings';
	//
	//	screen_icon();
	//	echo '<h2 class="nav-tab-wrapper">';
	//	foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
	//		$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
	//		echo '<a class="nav-tab ' . $active . '" href="?page=' . 'bp-site-post-settings' . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
	//	}
	//	echo '</h2>';
	//}

	/*
	 * Validate input
	 */
	public function bpsp_site_post_validate_input($input) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {

		    // Check to see if the current option has a value. If so, process it.
		    if( isset( $input[$key] ) ) {
			// Strip all HTML and PHP tags and properly handle quoted strings
				if ( is_array( $value ) ) {
					foreach ( $value as $value_item ) {
						$value_array[] = sanitize_text_field( $value_item );
					}
					$output[$key] = $value_array;
				} else {
					$output[$key] = esc_attr(wp_strip_all_tags( stripslashes( $input[ $key ] ) ) );
				}
		    }
		}
		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'bpsp_site_post_validate_input', $output, $input );
	}

	// Following two functions make sure that image attachment gets the right post-id
	public function bpsp_insert_media_fix( $post_id ) {
		global $bpsp_media_post_id;
		global $post_ID; 
	
		/* WordPress 3.4.2 fix */
		$post_ID = $post_id; 
	
		/* WordPress 3.5.1 fix */
		$bpsp_media_post_id = $post_id;
		add_filter( 'media_view_settings', array($this, 'bpsp_insert_media_fix_filter'), 10, 2 );
	}

	public function bpsp_insert_media_fix_filter( $settings, $post ) {
		global $bpsp_media_post_id;
	
		$settings['post']['id'] = $bpsp_media_post_id;
		$settings['post']['nonce'] = wp_create_nonce( 'update-post_' . $bpsp_media_post_id );
		return $settings;
	}
	
	/*---------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/

	/*
	 * Add edit post link to the content.
	 */

	 function content_edit_post_link($content) {
		global $post;
		$ps_can_edit = false;
		if ( function_exists( 'bpps_create_summary' ) ) {
			$ps_can_edit = bpps_current_user_can_edit( $post->ID );
		}
		$supported_post_types = array( 'post' );
		if ( is_singular() && in_array( $post->post_type, $supported_post_types ) ) {
			$bpsp_options = get_option('bpsp_site_post_settings');
			if ( isset( $bpsp_options['bpsp-add-edit-link'] ) && $bpsp_options['bpsp-add-edit-link'] != '' && isset( $bpsp_options['bpsp-edit-page'] ) ) {
				if ( current_user_can( 'edit_others_posts' ) || $post->post_author == get_current_user_id()|| $ps_can_edit) {
					$message = esc_attr__(sanitize_text_field( 'Edit Post'), 'bp-site-post' );
					$content = $content . ' <a class="post-edit-link" href="' . home_url('/') . '?page_id='.$bpsp_options['bpsp-edit-page'] . '&post_id='.$post->ID . '" title="' . $message . '">' . $message . '</a>';
				}
			}
		}
		return $content;
	}

	/*
	 * Print a link to edit post on front end whenever an edit post link is printed on front end.
	 */
	function edit_post_link( $link, $post_id ) {
		$supported_post_types = array( 'post' );
		$post_type = get_post_type( $post_id );
		if ( in_array( $post_type, $supported_post_types ) ) {
			$bpsp_options = get_option( 'bpsp_site_post_settings' );
			$message = esc_attr__(sanitize_text_field( 'Edit Post'), 'bp-site-post' );
			if ( isset( $bpsp_options['bpsp-edit-page']) && $bpsp_options['bpsp-edit-page'] != '' ) {
				if ( isset($bpsp_options['bpsp-hide-edit'] ) && $bpsp_options['bpsp-hide-edit'] != '' ) {
					$link = '<a class="post-edit-link" href="' . home_url('/') . '?page_id='.$bpsp_options['bpsp-edit-page'] . '&post_id='.$post_id . '" title="' . $message . '">' . $message . '</a>';
				} else {
					$link = $link . '<a class="post-edit-link" href="' . home_url('/') . '?page_id='.$bpsp_options['bpsp-edit-page'] . '&post_id='.$post_id . '" title="' . $message . '">' . $message . '</a>';
				}
			}
		}
		return $link;
	}

	/*
	 * Format error messages for output.
	 */
	function format_error_msg($message, $type = '',  $source = ''){
		$html = '<p style="color:red"><em>';
		if(!$type)
			$type = esc_attr__(sanitize_text_field('Error'), 'bp-site-post');
		$html .= "<strong>" . htmlspecialchars($type) . "</strong>: ";
		$html .= $message;
		$html .= '</em></p>';
		if($source){
			$html .= '<pre style="margin-left:5px; border-left:solid 1px red; padding-left:5px;"><code class="xhtml malformed">';
			$html .= htmlspecialchars(esc_attr(sanitize_text_field($source)));
			$html .= '</code></pre>';
		}
		return $html;
	}

	/*
	 * Hide the WordPress Toolbar.
	 */
	function hide_toolbar() {

		if ( ! current_user_can( 'edit_others_posts' ) ) {
			$bpsp_options = get_option('bpsp_site_post_settings');
			if ( ( isset($bpsp_options['bpsp-hide-toolbar'] ) && $bpsp_options['bpsp-hide-toolbar'] != '' ) && ! current_user_can( 'manage_options' ) ) {
				add_filter('show_admin_bar', '__return_false');
			}
		}
	}
	
	/*
	 * Remove toolbar items.
	 */
	function hide_toolbar_items($wp_admin_bar) {
		$bpsp_options = get_option('bpsp_site_post_settings');
		if ( isset( $bpsp_options['bpsp-hide-toolbar-wp'] ) && $bpsp_options['bpsp-hide-toolbar-wp'] != '' ) {
			$wp_admin_bar->remove_node('wp-logo');
		}
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			if ( isset( $bpsp_options['bpsp-hide-new'] ) && $bpsp_options['bpsp-hide-new'] != '' ) {
				$wp_admin_bar->remove_node('new-content');
			}
			if ( isset( $bpsp_options['bpsp-hide-toolbar-edit'] ) && $bpsp_options['bpsp-hide-toolbar-edit'] != '' ) {
				$wp_admin_bar->remove_node('edit');
			}
			if ( isset( $bpsp_options['bpsp-hide-toolbar-comments'] ) && $bpsp_options['bpsp-hide-toolbar-comments'] != '' ) {
				$wp_admin_bar->remove_node('comments');
			}
			if ( isset( $bpsp_options['bpsp-hide-toolbar-site-name'] ) && $bpsp_options['bpsp-hide-toolbar-site-name'] != '' ) {
				$wp_admin_bar->remove_node('site-name');
			}
		}
	}
	
	/*
	 * Get current user info. If user is not logged in we check if guest posts are permitted and set variables accordingly.
	 */
	function verify_user() {
		$bpsp_userinfo = array ();
		$bpsp_options = get_option('bpsp_site_post_settings');

		if ( ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) || ( is_user_logged_in() && $bpsp_options['bpsp-allow-all-members-posts']&& $bpsp_options['bpsp-allow-all-members-posts'] != '' ) ) {
			global $current_user;
			wp_get_current_user();
			$bpsp_userinfo['bpsp_user_id'] = $current_user->ID;
			$bpsp_userinfo['bpsp_user_login'] = $current_user->user_login;
			if ( current_user_can('publish_posts') )
				$bpsp_userinfo['bpsp_can_publish_posts'] = true;
			if ( current_user_can('manage_categories') )
				$bpsp_userinfo['bpsp_can_manage_categories'] = true;
				
			if ( current_user_can('contributor') ) {
				//$contributor = get_role('contributor');
				//$contributor->add_cap('upload_files');
				//$contributor->add_cap('read');
				//$contributor->add_cap('edit_posts');
				$bpsp_userinfo['media_upload'] = true;
			}
			return $bpsp_userinfo;

		} elseif ( !is_user_logged_in() && isset( $bpsp_options['bpsp-allow-guest-posts'] ) && $bpsp_options['bpsp-allow-guest-posts'] != '' ) {
			$user_query = get_userdata($bpsp_options['bpsp-guest-account']);
			$bpsp_userinfo['bpsp_user_id'] = $user_query->ID;
			$bpsp_userinfo['bpsp_user_login'] = $user_query->user_login;
			
			// We give guests rights as a subscriber. Very limited, no media uploads.
			$bpsp_userinfo['bpsp_can_manage_categories'] = false;
			$bpsp_userinfo['bpsp_can_publish_posts'] = true;
			$bpsp_userinfo['publish_status'] = 'pending';
			$bpsp_userinfo['media_upload'] = false;

			return $bpsp_userinfo;
		}
		return false;
	} // end verify_user()

	function bpsp_check_user_role( $role, $user_id = null ) {
	 
		$user = wp_get_current_user();
	 
		if ( empty( $user ) )
			return false;
		return in_array( $role, (array) $user->roles );
	}

	function save_bpsp_auto_draft( $error_msg = false ) {

		global $bpsp_post_id;
	
		if (!function_exists('get_default_post_to_edit')){
			require_once(ABSPATH . "wp-admin" . '/includes/post.php');
		}
	
		/* Check if a new auto-draft (= no new post_ID) is needed or if the old can be used */
		$last_post_id = (int) get_user_option( 'dashboard_quick_press_last_post_id' ); // Get the last post_ID
		if ( $last_post_id ) {
			$post = get_post( $last_post_id );
			if ( empty( $post ) || $post->post_status != 'auto-draft' ) { // auto-draft doesn't exists anymore
				$post = get_default_post_to_edit( 'post', true );
				update_user_option( get_current_user_id(), 'dashboard_quick_press_last_post_id', (int) $post->ID ); // Save post_ID
			} else {
				$post->post_title = ''; // Remove the auto draft title
			}
		} else {
			$post = get_default_post_to_edit( 'post' , true);
			$user_id = get_current_user_id();
			// Don't create an option if this is a super admin who does not belong to this site.
			if ( ! ( is_super_admin( $user_id ) && ! in_array( get_current_blog_id(), array_keys( get_blogs_of_user( $user_id ) ) ) ) )
				update_user_option( $user_id, 'dashboard_quick_press_last_post_id', (int) $post->ID ); // Save post_ID
		}
	
		$bpsp_post_id = (int) $post->ID;
	
		// Getting the right post-id for media attachments
		$this->bpsp_insert_media_fix( $bpsp_post_id );
	
	}

	/*
	 * Registers the shortcode that has a required @name param indicating the function which returns the HTML code for the shortcode.
	 *
	 * Shortcode: [bp-site-post] With parameters: [bp-site-post success_url="url" success_page_id="id"]
	 * Parameters:
	 * 	success_url: URL of the page to redirect to after the post.
	 * 	success_page_id: ID of the page to redirect to after the post. Overwrites success_url if set.
	 */
	function handle_shortcode($atts, $content = null){

		global $shortcode_cache, $post, $bpsp_post_id;
		
		extract(shortcode_atts(array(
			'success_url' => '',
			'success_page_id' => 0,
			'called_from_widget' => '0',
		), $atts));
		$form_name = 'site_post_form';
		$bpsp_options = get_option('bpsp_site_post_settings');

		// Check for user logged in or guest posts permitted.
		if(!$user_verified = $this->verify_user())
			return $this->format_error_msg( esc_attr__(sanitize_text_field( 'You need the correct permissions to access this form.'), 'bp-site-post' ), esc_attr__(sanitize_text_field('Notice'), 'bp-site-post' ) );

		do_action ('save_bpsp_auto_draft');
			
		// success_page_id overwrites success_url.
		if($success_page_id)
			$success_url = get_permalink($success_page_id);

		// Shortcode 'success_url' attribute. This has priority over redirect set in admin panel.
		if(!$success_url) {
			if ( isset( $bpsp_options['bpsp-redirect'] ) ) {
				$success_url = $bpsp_options['bpsp-redirect'];
			} else {
				$success_url = home_url() . "/";
			}
		}

		// Call the function and grab the results (if nothing, output a comment noting that it was empty).
		// This one calls the form presented to the user.
		return call_user_func_array(array($this, $form_name), array($atts, $content, $user_verified, $bpsp_post_id, $called_from_widget));

	}

	function process_site_post_form() {
		if( isset($_POST) ){

			$bpsp_options = get_option('bpsp_site_post_settings');
			
			if ( !empty ($_POST["bpsp-our-id"])) $bpsp_post_id = sanitize_text_field( $_POST["bpsp-our-id"] );
	
				// Create post object with defaults
				$my_post = array(
					'ID' => $bpsp_post_id,
					'post_title' => '',
					'post_status' => 'publish',
					'post_author' => '',
					'post_category' => '',
					'comment_status' => 'open',
					'ping_status' => 'open',
					'post_content' => '',
					'post_excerpt' => '',
					'post_group' => '',
					'group_status' => '',
					'post_type' => 'post',
					'tags_input' => '',
					'to_ping' =>  ''
				);
	
				$form_name = 'site_post_form';
				$user_verified = array();
				if ( is_user_logged_in() ) {
					$user_verified['bpsp_user_id'] = get_current_user_id();
				}
				
				//Fill our $my_post array
				$my_post['post_title'] =  esc_attr(wp_strip_all_tags(sanitize_text_field($_POST['bpsp_site_post_title'] )));

				if( array_key_exists('bpspsitepostcontent', $_POST)) {
					$my_post['post_content'] = wp_kses_post( $_POST['bpspsitepostcontent'] );
				}
				if( array_key_exists('bpsp_site_post_excerpt', $_POST)) {
					$my_post['post_excerpt'] = esc_attr(wp_strip_all_tags(sanitize_text_field( $_POST['bpsp_site_post_excerpt'] )));
				}
				$ourCategory = array();
				if( array_key_exists('bpsp_site_post_select_category', $_POST)) {
					
					$yourCategory = array($_POST['bpsp_site_post_select_category'] );
					foreach ( $yourCategory as $category ) {
						$ourCategory[] = sanitize_text_field( $category );
					}
				}
				if( array_key_exists('bpsp_site_post_checklist_category', $_POST)) {
					$unsanitised = 	$_POST['bpsp_site_post_checklist_category'];
					foreach ( $unsanitised as $category ) {
						$ourCategory[] = esc_attr(sanitize_text_field( $category ));
					}
				}
				if( array_key_exists('bpsp_group_select', $_POST)) {
					$my_post['post_group'] = wp_strip_all_tags( esc_attr(sanitize_text_field( $_POST['bpsp_group_select'] )));
				}
				if( array_key_exists('bpsp_group_post_status', $_POST)) {
					$my_post['group_status'] = wp_strip_all_tags( esc_attr(sanitize_text_field( $_POST['bpsp_group_post_status'] )));
				}
				if( array_key_exists('bpsp_site_post_new_category', $_POST)) {
					if (!empty( $_POST['bpsp_site_post_new_category']) ) {
						require_once(WP_PLUGIN_DIR . '/../../wp-admin/includes/taxonomy.php');
						if ($newCatId = wp_create_category( wp_strip_all_tags( esc_attr(sanitize_text_field($_POST['bpsp_site_post_new_category'] ))))) {
							$ourCategory = 	array($newCatId);
						} else {
							throw new Exception( esc_attr__(sanitize_text_field( 'Unable to create new category. Please try again or select an existing category.'), 'bp-site-post' ));
						}
					}
				}
				
				if ( ! is_user_logged_in() && ! $bpsp_options['bpsp-guest-cat-select'] ) {
					$ourCategory = array( $bpsp_options['bpsp-guest-cat'] );
				}
				
				$my_post['post_category'] = $ourCategory;

				if ( !empty ($_POST["bpsp-our-author"])) {
					$my_post['post_author'] =  esc_attr(sanitize_text_field( $_POST["bpsp-our-author"] ));
				} else {
					$my_post['post_author'] = $user_verified['bpsp_user_id'];
				}
	
				if( array_key_exists('bpsp_site_post_tags', $_POST)) {
					$my_post['tags_input'] = wp_strip_all_tags( esc_attr(sanitize_text_field($_POST['bpsp_site_post_tags'] )));
				}
	
				if( $bpsp_options['bpsp-publish-status']) {
					$my_post['post_status'] = $bpsp_options['bpsp-publish-status'];
				}
				
				if( array_key_exists('bpsp-priv-publish-status', $_POST)) {
					$my_post['post_status'] = wp_strip_all_tags( esc_attr(sanitize_text_field($_POST['bpsp-priv-publish-status'] )));
				}

				// Insert the post into the database
				$post_success = wp_update_post( $my_post );
				if ( $my_post['post_status'] == 'group_post' || $my_post['post_status'] == 'group_post_pending' ) {
					update_post_meta( $my_post['ID'], 'bpgps_group', $my_post['post_group'] );
					update_post_meta( $my_post['ID'], 'bpgps_group_post_status', $my_post['group_status'] );
				}
					
				if($post_success === false) {
					$result = wp_strip_all_tags( esc_attr__(sanitize_text_field('error', 'bp-site-post'), 'bp-site-post'));
				}
				else {
					$result = "success";
					//if ( 'publish' == $my_post['post_status'] ) do_action('publish_post');
					if ( current_theme_supports('post-formats') ) {
						if (isset($_POST['bpsp-post-format'])) {
							set_post_format( $post_success, wp_strip_all_tags( esc_attr(sanitize_text_field( $_POST['bpsp-post-format'] ))));
						} else {
							set_post_format( $post_success, wp_strip_all_tags(esc_attr($bpsp_options['bpsp-post-format-default'])));
						}
					}
				}
				// @since 1.2.3
				//  
				//  
				// Auto add featured image
				if ( isset( $bpsp_options['bpsp-auto-add-thumb'] ) && $bpsp_options['bpsp-auto-add-thumb'] == 1 ) {
					$args = array(
						'post_parent' => $my_post['ID'],
						'post_type'   => 'attachment',
						'post_mime_type' => 'image',
						'numberposts' => 1,
						'order' => 'ASC' 
					);
					$attached_image = get_children( $args );
					if ( $attached_image ) {
						$attachment_values = array_values( $attached_image );
						// add attachment ID                                            
						update_post_meta( $my_post['ID'], '_thumbnail_id', $attachment_values[0]->ID, true );                                 
					// Below code not working					
					} else { //if the image was not uploaded, fetch it's url then attempt to find it in the media directory and add it as attachment.
						// Added 1.3.4 offloded the search of content for an image to make featured.
						$this->handle_featured_image( $my_post['ID'], $my_post['post_content'] );
					}
				}

				if( array_key_exists('bpsp_site_post_guest_name', $_POST)) {
					add_post_meta( $post_success, 'guest_name', wp_strip_all_tags( esc_attr(sanitize_text_field($_POST['bpsp_site_post_guest_name'] ))), true ) || update_post_meta( $post_success, 'guest_name', wp_strip_all_tags( esc_attr(sanitize_text_field( $_POST['bpsp_site_post_guest_name'] ))));
				}
				if( array_key_exists('bpsp_site_post_guest_email', $_POST)) {
					add_post_meta( $post_success, 'guest_email', wp_strip_all_tags( esc_attr(sanitize_text_field( $_POST['bpsp_site_post_guest_email']))), true ) || update_post_meta( $post_success, 'guest_name', wp_strip_all_tags( esc_attr(sanitize_text_field( $_POST['bpsp_site_post_guest_name'] ))));
				}
				
				if(apply_filters('form_abort_on_failure', true, $form_name))
					$success = $post_success;
				if($success){
					if($bpsp_options['bpsp-mail']) {
						$this->bpsp_sendmail($post_success, wp_strip_all_tags( esc_attr(sanitize_text_field( $_POST['bpsp_site_post_title'] ))));
					}
					if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower( sanitize_text_field( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) == 'xmlhttprequest') {
						echo $result;
					} else {
						setcookie('form_ok', 1,  time() + 10, '/');
						header("Location: " . sanitize_text_field( $_SERVER["HTTP_REFERER"] ) );
						die();
					}
				}
				else {
					throw new Exception( $bpsp_options['bpsp-post-fail'] ? $bpsp_options['bpsp-post-fail'] : esc_attr__(sanitize_text_field( 'We were unable to accept your post at this time. Please try again. If the problem persists tell the site owner.'), 'bp-site-post'));
				}
		} // isset($_POST)
		die();
	} //function process_site_post_form
	
	/**
	 * Find the first image in the post content and set it as the featured image. 
	 * Three methods are used first the image url is checked to see if it's an
	 * existing attachment guid, if that fails we check to see if it's a thumbnail
	 * and attempt to strip off the post size information then try again. If that 
	 * we re-upload the image as a new attachment and apply that as the f. image.
	 *
	 * @since 1.3.4
	 *
	 */
	
	function handle_featured_image( $post_id, $content ) {

		$first_img = '';
		$success = false;
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
		$first_img = esc_attr( $matches [1] [0] );
		
		if( ! empty($first_img)){
			
			global $wpdb;
			$attachment_id = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $first_img ));
			
			if ( $attachment_id ) { //Try to add it as the post thumbnail
				
				$success = update_post_meta( $my_post['ID'], '_thumbnail_id', $attachment_id[0]);
			
			} else { // if that fails, see if the image size is encoded in the image name and remove it.
				
				$image_size = getimagesize( $first_img );
				$test = '-' . $image_size[0] . 'x' . $image_size[1];
				$found = strpos( $first_img, $test );
				
				if ( $found ) { // if the image size is encoded in the filename strip it out
					$second_img = str_replace( $test, '', $first_img );
					$attachment_id = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $second_img ));
					
					if ( $attachment_id ) { //then try to add it as the post thumbnail
						$attachement_id = intval($attachment_id);
						$success = update_post_meta( $post_id, '_thumbnail_id', $attachment_id[0]);
					}								
				}
			}
			
			if ( ! $success ) { //if no attachment id is found, load the image to the uploads directory and set it as a new attachment ID
				$upload_dir = wp_upload_dir();
				//Get the remote image and save to uploads directory
				$wp_filetype = wp_check_filetype( $first_img , null );
				$img_name = time().'_'.basename( $first_img, array( 'timeout' => 150 ) ) . '.' . $wp_filetype['ext'];
				$img = wp_remote_get( $first_img );

				if ( is_wp_error( $img ) ) {
					$error_message = $img->get_error_message();
					//add_action( 'admin_notices', array( $this, 'wprthumb_admin_notice' ) );
				}
				else {
					$img = wp_remote_retrieve_body( $img );
					$fp = fopen( $upload_dir['path'].'/'.$img_name , 'w' );
					fwrite( $fp, $img );
					fclose( $fp );

					$attachment = array(
						'post_mime_type' => $wp_filetype['type'],
						'post_title' => preg_replace( '/\.[^.]+$/', '', $img_name ),
						'post_content' => '',
						'post_status' => 'inherit'
					);

					//required for wp_generate_attachment_metadata which generates image related meta-data also creates thumbs
					require_once ABSPATH . 'wp-admin/includes/image.php';
					$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'].'/'.$img_name, $post_id );
					//Generate post thumbnail of different sizes.
					$attach_data = wp_generate_attachment_metadata( $attach_id , $upload_dir['path'].'/'.$img_name );
					wp_update_attachment_metadata( $attach_id,  $attach_data );
					//Set as featured image.
					delete_post_meta( $post_id, '_thumbnail_id' );
					$success = add_post_meta( $post_id , '_thumbnail_id' , $attach_id, true );
				}
			}
			
		}
		
		if ( $success ) {
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * Notify admin about new post via email
	 */
	function bpsp_sendmail ($post_id, $post_title) {
		$post_title = esc_attr(sanitize_text_field($post_title));
		$blogname = get_option('blogname');
		$post_id = esc_attr(sanitize_text_field($post_id));
		$post = get_post( $post_id );
		$user_id = $post->post_author;
		$username = bp_core_get_user_displayname( $user_id );
		$post_status = sanitize_text_field( wp_strip_all_tags($_POST['bpsp-priv-publish-status'] ) );
		$email = get_option('admin_email');
		$headers = "MIME-Version: 1.0\r\n" . "From: " . esc_attr(sanitize_text_field($blogname)) . " " . "<" . esc_attr(sanitize_text_field($email)) . ">\n" . "Content-Type: text/HTML; charset=\"" . esc_attr(sanitize_text_field(get_option('blog_charset'))) . "\"\r\n";
		
		if ( $post_status == 'group_post' ) {
			
			$group_id = wp_strip_all_tags( esc_attr(sanitize_text_field( $_POST['bpsp_group_select'] )));
			$group_info = groups_get_group( $group_id );
			$group_name = esc_attr(sanitize_text_field($group_info->name));
			$group_creator = $group_info->creator_id;
			$creator_info = get_userdata( $group_creator );
			$group_creator_email = esc_attr(sanitize_text_field($creator_info->user_email));
			$post = get_post( $post_id );
			
			$opening_message = esc_attr__(sanitize_text_field( 'New group post, '), 'bp-site-post' );
			$group_admin_op_msg = esc_attr__(sanitize_text_field( 'New group post to'), 'bp-site-post' );
			
			if ( $post->post_date != $post->post_modified ) {
				$opening_message = esc_attr__(sanitize_text_field( 'Updated group post, '), 'bp-site-post' );
				$group_admin_op_msg = esc_attr__(sanitize_text_field( 'Updated group post to'), 'bp-site-post' );
			}
			
			$group_post_status = wp_strip_all_tags( esc_attr(sanitize_text_field( $_POST['bpsp_group_post_status'] )));
			$content = '<p>' . $opening_message . esc_attr(sanitize_text_field($post->post_title)) . esc_attr__(sanitize_text_field( ' has been submitted to '), 'bp-site-post' ) . $group_name . esc_attr__(sanitize_text_field( ' by '), 'bp-site-post' ) . esc_attr(sanitize_text_field($username)) . esc_attr__(sanitize_text_field(' and the post status is '), 'bp-site-post' ) . $group_post_status . '<br/>' . esc_attr__(sanitize_text_field( 'To view the post click here:'), 'bp-site-post' ) . ' '.'<a href="' . bpps_get_post_permalink($post, $group_info) . '"><strong>'.$post_title.'</strong></a></p>';
			wp_mail( $group_creator_email, $group_admin_op_msg . ' ' . $group_name . ': ' . $post_title, $content, $headers );
			
			if ( $group_creator_email != $email ) {
				
				wp_mail($email, esc_attr( esc_attr__( 'New front end post to ', 'bp-site-post' ) ) . $blogname . ': ' . $post_title, $content, $headers);
			
			}
		
		} else {
			
			if ( in_array( $post_status, array( 'publish', 'members_only' ) ) ) {
				
				$opening_message = esc_attr__(sanitize_text_field( 'New front end post, '), 'bp-site-post' );
				if ( $post->post_date != $post->post_modified ) {
					$opening_message = esc_attr__(sanitize_text_field( 'Updated front end post, '), 'bp-site-post' );
				}

				$content = '<p>' . $opening_message . $post->post_title . esc_attr__(sanitize_text_field( ' has been submitted to: '), 'bp-site-post' ) . $blogname . esc_attr__(sanitize_text_field( ' by '), 'bp-site-post' ) . $username . esc_attr__(sanitize_text_field( ' with a status of '), 'bp-site-post' ) . $post_status . '<br/>' .esc_attr__(sanitize_text_field( 'To view the post click here:'), 'bp-site-post' ) . ' ' . '<a href="' . esc_url(get_permalink($post_id)) . '"><strong>' . $post_title . '</strong></a></p>';
				wp_mail($email, sanitize_text_field( esc_attr__( 'New front end post to', 'bp-site-post' ) ) . ' ' . $blogname . ': ' . $post_title, $content, $headers);
				
			}
		
		}
	
	}
	
	/**
	 * Print the post form at the front end
	 */
	function site_post_form($attrs, $content, $verified_user, $bpsp_post_id, $called_from_widget){
		ob_start();
		global $current_user; //Global WordPress variable that stores what wp_get_current_user() returns.
		wp_get_current_user();
		$bpsp_options = get_option('bpsp_site_post_settings'); //Read the plugin's settings out of wpdb table wp_options.

		// Render the form html
		
		include_once (sprintf("%s/views/display.php", dirname(__FILE__)));

		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	/**
	* Send debug code to the Javascript console
	*/
	function dtc($data) {
		if(is_array($data) || is_object($data))
		{
			echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
		} else {
			echo("<script>console.log('PHP: ".$data."');</script>");
		}
	}

	/**
	 * Get size information for all currently-registered image sizes.
	 * @since 1.2.2
	 * @global $_wp_additional_image_sizes
	 * @uses   get_intermediate_image_sizes()
	 * @return array $sizes Data for all currently-registered image sizes.
	 */
	function get_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
				$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
				$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
				$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}

		return $sizes;
	}	

    } // end class
} // end if (!class_exists)

$bpsp_site_post = new BP_Site_Post();

// @since 1.4.0
// function to process the JS AJAX unique title check
// @param $post_id int Post ID of post being checked
// $param $title string The Title being checked
// @return int hit count.

function bpsp_check_title_ajax() {

	check_ajax_referer( 'bpps-nonce', 'security' );

	$post_id = esc_attr( $_POST['post_id'] );
	$post_title = sanitize_text_field( $_POST['post_title'] );
	$post_title = "'" . $post_title . "'";
	
	$bpsp_options = get_option( 'bpsp_site_post_settings' );
	
	if ( ! current_user_can( 'edit_posts' ) || ! isset( $title ) && ( ! isset( $bpsp_options['bpsp-enforce-unique-title'] ) || $bpsp_options['bpsp-enforce-unique-title'] == '' ) ) {
		return false;
		die();
	}
	global $wpdb;
	$posts_status_query = '';

	if ( isset( $post_id ) && is_numeric( $post_id ) ) {
		$excluded_post_query = " AND ID != '" . $post_id . "'";
	}
		
	if ( function_exists( 'bpps_get_users_visible_group_posts_count' ) ) {
		
		// Members only query
			$posts_status_query .= "'members_only',";
		
		// Friends only query
		if ( bp_is_active( 'friends' ) ) {
			$posts_status_query .= "'friends_only',";
		}
		
		// Following query
		$args = array(
			'leader_id'   => $user_id,
			'follower_id' => $current_user_id
		);
		
		if ( function_exists( 'bp_follow_is_following' ) ) {
			$posts_status_query .= "'following',";
		}

		// Followed query
		$args = array(
			'leader_id'   => $current_user_id,
			'follower_id' => $user_id
		);
		
		if ( function_exists( 'bp_follow_is_following' ) ) {
			$posts_status_query .= "'followed',";
		}

		//Groups query
		if ( bp_is_active( 'groups' ) ) {
			$posts_status_query .= "'group_post',";
		}
		
	}
	
	// Public posts query
	$posts_status_query .= "'publish'";
	
	if ( $posts_status_query = 'publish' ) {
		$posts_status_query = "= 'publish'";
	} else { 
		$posts_status_query = "IN ($posts_status_query)";
	}
	
	// Post types
	$post_types = get_post_types( array( 'public' => true, 'exclude_from_search' => false ), 'names' );
	
	$post_type_query = '';
	foreach ( $post_types as $key => $post_type ) {
		if ( $post_type == 'post' || $post_type == 'attachment' ) continue;
		$post_type_query .= "'$post_type',";
	}
	$post_type_query .= "'post'";
	
	if ( $post_type_query = 'post' ) {
		$post_type_query = "= 'post'";
	} else {
		$post_type_query = "IN ($post_type_query)";
	}
	
	// Execute query
	$titles_query = "SELECT post_title from $wpdb->posts WHERE post_status $posts_status_query AND post_type $post_type_query AND post_title = $post_title$excluded_post_query";
	$title_match_post_ids = $wpdb->get_results($titles_query, ARRAY_N);
	
	if ( is_array( $title_match_post_ids ) && count( $title_match_post_ids > 0 ) ) {
		echo count( $title_match_post_ids );
	} else {
		echo 0;
	}
		
	die();
	
}

add_action( 'wp_ajax_bpsp_check_title', 'bpsp_check_title_ajax');

function _bpsp_allow_media_upload( $caps = array(), $cap = '', $user_id = 0, $args = array() ) {
	
	// Bail if not checking the 'bp_moderate' cap.
	if ( 'upload_files' !== $cap ) {
		return $caps;
	}

	$bpsp_options = get_option( 'bpsp_site_post_settings' );
	
	if ( ! isset( $bpsp_options['bpsp-edit-page'] ) || $bpsp_options['bpsp-edit-page'] == '' ) {
		return $caps;
	}
	
	if ( ! isset( $bpsp_options['bpsp-allow-subscriber-media-upload'] ) || $bpsp_options['bpsp-allow-subscriber-media-upload'] == '' ) {
		return $caps;
	}
	
	// Never trust inactive users.
	if ( bp_is_user_inactive( $user_id ) ) {
		return $caps;
	}
	$post_data = get_post( $bpsp_options['bpsp-edit-page'] );
	if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
		if ( strpos( $_SERVER['HTTP_REFERER'], $post_data->post_name ) != false ) {
			$from_add_post = true;
		}
	}
	$post_data = get_post( $bpsp_options['bpsp-edit-page'] );
	if( is_page( $bpsp_options['bpsp-edit-page'] ) || isset( $from_add_post ) ) {
		return array( 'read' );
	}

	return $caps;
}
add_filter( 'map_meta_cap', '_bpsp_allow_media_upload', 10, 4 );