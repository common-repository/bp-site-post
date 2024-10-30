<?php
/**
 * This file is used to markup the public facing aspect of the plugin.
 */

if(!defined('ABSPATH')) {
	exit;
}

// If called from Frontpage Edit link we get a post_id
if (isset($_GET["post_id"])) { 
	$my_post = get_post( htmlspecialchars( sanitize_text_field( $_GET["post_id"] ) ) );
	if ( $my_post->post_status == 'group_post' ) {
		$group_selected = get_post_meta( $my_post->ID, 'bpgps_group' );
		$selected_group_post_status = get_post_meta( $my_post->ID, 'bpgps_group_post_status' );
	}
} else {
	$my_post = '';
	$group_selected = NULL;
	$selected_group_post_status = NULL;
}

$bpsp_options = get_option( 'bpsp_site_post_settings' );
$logged_in = is_user_logged_in();


// Set editor (content field) style
switch($bpsp_options['bpsp-editor-style']){
	case 'simple':
		$teeny = true;
		$show_quicktags = false;
		add_filter( 'teeny_mce_buttons', create_function ( '' , "return array('');" ) , 50 );
		break;
	case 'rich':
		$teeny = false;
		$show_quicktags = true;
		break;
	case 'visual':
		$teeny = false;
		$show_quicktags = false;
		break;
	case 'html':
		$teeny = true;
		$show_quicktags = true;
		add_filter ( 'user_can_richedit' , create_function ( '' , 'return false;' ) , 50 );
		break;
}

if ($called_from_widget == '1') {
	$teeny = true;
	$show_quicktags = false;
	add_filter( 'teeny_mce_buttons', function() { return array(''); } , 50 );
//	add_filter ( 'user_can_richedit' , create_function ( '' , 'return false;' ) , 50 );
}

function bpsp_tinymce_buttons_2($buttons)
 {
	//Remove the format dropdown select and text color selector
	$remove = array('formatselect','forecolor', 'indent', 'outdent', 'charmap');

	return array_diff($buttons,$remove);
 }
//add_filter('mce_buttons_2','bpsp_tinymce_buttons_2');

function bpsp_tinymce_buttons($buttons)
 {
	//Remove the format dropdown select and text color selector
	$remove = array('link','unlink', 'blockquote', 'strikethrough', 'fullscreen', 'wp_more', 'wp_adv');

	return array_diff($buttons,$remove);
 }
//add_filter('mce_buttons','bpsp_tinymce_buttons');

?>

<?php if (!isset($_POST["bpsp_site_post_title"])) {

	//init variables
	$cf = array();
	$sr = false;

	if ( isset( $_COOKIE['form_ok'] ) ) {
		if ($_COOKIE["form_ok"] == 1 ) {
			$cf['form_ok'] = true;
			$sr = true;
		}
}

?>

<form id="site_post_form" class="bpsp_site_post_form bordered" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" enctype="multipart/form-data">
	<p hidden="hidden" class="form_error_message"></p>
	<input type="hidden" name="bpsp-our-id" id="bpsp-our-id" <?php echo ( $my_post ? "value='".esc_attr($my_post->ID)."'" : "value='".esc_attr($bpsp_post_id)."'" ); ?> />
	<input type="hidden" name="bpsp-our-author" <?php if ( $my_post ) echo "value='".esc_attr($my_post->post_author)."'"; ?> />
	<?php if ( isset( $bpsp_options['bpsp-login-link']) && $bpsp_options['bpsp-login-link'] != '' ) { ?>
		<a style="float: right;" href="<?php echo wp_login_url( get_permalink() ); ?>" title="Login"><?php sanitize_text_field(esc_attr_e( 'Login', 'bp-site-post')) ?></a>
	<?php } ?>
	<div id="field-wrapper"><?php
	$form_name = esc_attr( $bpsp_options['bpsp-form-name'] );
	$title = esc_attr( $bpsp_options['bpsp-title'] );
	$excerpt = esc_attr($bpsp_options['bpsp-excerpt']);
	$content = esc_attr($bpsp_options['bpsp-content']);?>
		<legend><?php echo ( $form_name ? $form_name : sanitize_text_field(esc_attr__('Site Post', 'bp-site-post') ) ); ?></legend>
		<label for="bpsp_site_post_title"><?php echo ( $title ? $title : sanitize_text_field(esc_attr__('Title', 'bp-site-post') ) ); ?></label>
		<input type="text" <?php if ( $bpsp_options['bpsp-title-required'] == "1" ) echo "required='required'"; ?> id="bpsp_site_post_title" name="bpsp_site_post_title" maxlength="255" <?php if ( $my_post ) echo "value='". esc_attr(sanitize_text_field($my_post->post_title)) . "'"; ?>autofocus="autofocus"/>
		<?php if (isset($bpsp_options['bpsp-show-excerpt']) && $bpsp_options['bpsp-show-excerpt'] != ''  ) { ?>
			<label for="bpsp_site_post_excerpt"><?php echo ( $excerpt ? $excerpt : sanitize_text_field(esc_attr__('Excerpt', 'bp-site-post') ) ); ?></label>
			<textarea id="bpsp_site_post_excerpt" name="bpsp_site_post_excerpt"><?php if ( $my_post ) echo esc_attr($my_post->post_excerpt); ?></textarea>
		<?php } ?>
		<label for="bpspsitepostcontent"><?php echo ( $content ? $content : sanitize_text_field(esc_attr__('Text', 'bp-site-post') ) ); ?></label>
		<?php
		$settings = array(
			'media_buttons'	=> (boolean) $bpsp_options['bpsp-allow-media-upload'],
			'teeny'			=> $teeny,
			'wpautop'		=> true,
			'quicktags'		=> $show_quicktags
		);
		$editor_content = '';
		if ( $my_post ) $editor_content = $my_post->post_content;
		wp_editor($editor_content, 'bpspsitepostcontent', $settings );
		
		if ( $logged_in || $bpsp_options['bpsp-guest-cat-select'] ){
		
			$orderby = esc_attr($bpsp_options['bpsp-category-order']); //The sort order for categories.
			$active_cat=0;
			if ( $my_post ) {
				$cats=get_the_category($my_post->ID);
				if($cats[0]) $active_cat=$cats[0]->cat_ID;
			}
			$cat_label = esc_attr( $bpsp_options['bpsp-categories-label'] );
			if ( isset( $bpsp_options['bpsp-restrict-categories'] ) && $bpsp_options['bpsp-restrict-categories'] != '' ) {
				if ( isset( $bpsp_options['bpsp-restricted-categories'] ) ) {
					$included_categories = $bpsp_options['bpsp-restricted-categories'];
				} else {
					$included_categories = array();
				}
			} else {
				$included_categories = array();
			}
			switch($bpsp_options['bpsp-categories']){
				case 'none':
					break;
				case 'list':
					$args = array(
						'orderby'           => $orderby,
						'order'             => 'ASC',
						'show_count'        => 0,
						'include'			=> $included_categories,
						'hide_empty'        => 0,
						'child_of'          => 0,
						'echo'              => 0,
						'selected'          => $active_cat,
						'hierarchical'      => 1,
						'name'              => 'bpsp_site_post_select_category',
						'class'             => 'class=bpsp_site_post_form',
						'depth'             => 0,
						'tab_index'         => 0,
						'hide_if_empty'     => false
					);
			?>
					<label for="select_post_category"><?php echo ( $cat_label ? $cat_label : sanitize_text_field(esc_attr__('Select a Category', 'bp-site-post') ) ); ?></label><?php
					echo str_replace("&nbsp;", "&#160;", wp_dropdown_categories($args));
					break;
				case 'check':
					$args = array(
						'type'              => 'post',
						'orderby'           => $orderby,
						'order'             => 'ASC',
						'include'			=> $included_categories,
						'hide_empty'        => 0,
						'hierarchical'      => 0,
						'taxonomy'          => 'category',
						'pad_counts'        => false
					); ?>
					<label for="bpsp_site_post_cat_checklist"><?php echo ( $cat_label ? $cat_label : sanitize_text_field(esc_attr__('Category', 'bp-site-post') ) ); ?></label>
					<ul id="bpsp_site_post_cat_checklist">
					<?php $cats = get_categories($args);

					foreach ($cats as $cat) { 
					$checked = '';
					if ( isset( $my_post->ID ) ) {
						if ( in_category( $cat->cat_ID, $my_post->ID ) ) {
							$checked = 'checked="checked"';
						}
					}?>
						<li><input type="checkbox" name="bpsp_site_post_checklist_category[]" value="<?php echo (esc_attr($cat->cat_ID)); ?>" <?php echo $checked; ?> />&nbsp;<?php echo(esc_attr($cat->cat_name)); ?></li>
					<?php } ?>
					</ul>
					<?php break;
			}
		}
		if ( ( isset( $bpsp_options['bpsp-allow-new-category'] ) && $bpsp_options['bpsp-allow-new-category'] != '' ) && $verified_user['bpsp_can_manage_categories']) { ?>
			<label for="bpsp_site_post_new_category"><?php echo ( $bpsp_options['bpsp-create-category'] ? $bpsp_options['bpsp-create-category'] : sanitize_text_field(esc_attr__('New category', 'bp-site-post') ) ); ?></label>
			<input type="text" id="bpsp_site_post_new_category" name="bpsp_site_post_new_category" maxlength="255" />
		<?php }
		if ( isset( $bpsp_options['bpsp-show-tags']) && $bpsp_options['bpsp-show-tags'] != '' ) { ?>
			<label for="bpsp_site_post_tags"><?php echo ( $bpsp_options['bpsp-tags'] ? $bpsp_options['bpsp-tags'] : sanitize_text_field(esc_attr__('Tags (comma-separated)', 'bp-site-post') ) ); ?></label>
			<input type="text" id="bpsp_site_post_tags" name="bpsp_site_post_tags" maxlength="255" <?php if ( $my_post ) echo "value='".implode( ', ', $my_post->tags_input )."'"; ?>/>
		<?php }

		if (current_theme_supports('post-formats') && ( isset($bpsp_options['bpsp-post-format'] ) && $bpsp_options['bpsp-post-format'] != '' ) ) {
			$post_formats = get_theme_support( 'post-formats' );
		
			if ( is_array( $post_formats[0] ) ) :
				$post_format = get_post_format( $my_post->ID );
				if ( !$post_format )
					$post_format = '0';
				// Add in the current one if it isn't there yet, in case the current theme doesn't support it
				if ( $post_format && !in_array( $post_format, $post_formats[0] ) )
					$post_formats[0][] = $post_format;
			?>
				<label for='bpsp-post-format'><?php esc_attr_e('Post Format', 'bp-site-post'); ?></label>
				<select id='bpsp-post-format' name='bpsp-post-format'>
				<option value="0" <?php selected( $post_format, '0' ); ?> ><?php echo esc_attr(sanitize_text_field(get_post_format_string( 'standard' ))); ?></option>
				<?php foreach ( $post_formats[0] as $format ) : ?>
				<option value="<?php echo esc_attr( $format ); ?>" <?php selected( $post_format, $format ); ?> ><?php echo esc_attr(sanitize_text_field( get_post_format_string( $format ))); ?></option>
				<?php endforeach; ?>
				</select>
			<?php endif;
		}

		if ( (isset( $bpsp_options['bpsp-guest-info'] ) && $bpsp_options['bpsp-guest-info'] != '' ) && (! $logged_in) ){ ?>
			<label for="bpsp_site_post_guest_name"><?php sanitize_text_field(esc_attr_e('Your Name', 'bp-site-post')); ?></label>
			<input type="text" required="required" id="bpsp_site_post_guest_name" name="bpsp_site_post_guest_name" maxlength="40" />

			<label for="bpsp_site_post_guest_email"><?php sanitize_text_field(esc_attr_e('Your Email', 'bp-site-post')); ?></label>
			<input type="email" required="required" id="bpsp_site_post_guest_email" name="bpsp_site_post_guest_email" maxlength="40" /><br><br>
		<?php } ?>

	<!--<span id="loading"></span>-->
	<input type="hidden" name="action" value="process_site_post_form"/>
	<?php if ( isset( $bpsp_options['bpsp-quiz'] ) && $bpsp_options['bpsp-quiz'] != '' && (! $logged_in) ) { ?>
		<?php $no1 = esc_attr(mt_rand(1, 12)); $no2 = esc_attr(mt_rand(1, 12)); ?>
		<label class="error" for="bpsp_quiz" id="quiz_error" style="margin: 0 0 5px 10px; display: none; color: red;"><?php sanitize_text_field(esc_attr_e('Wrong Quiz Answer!', 'bp-site-post')); ?></label>
		<label for="bpsp_quiz" id="bpsp_quiz_label"><?php echo $no1 . sanitize_text_field(esc_attr__( ' plus ', 'bp-site-post' ) ) . $no2; ?> =</label>
		<input type="text" required="required" id="bpsp_quiz" name="bpsp_quiz" maxlength="2" size="2" />
		<input type="hidden" id="bpsp_quiz_hidden" name="bpsp_quiz_hidden" value="<?php echo $no1 + $no2; ?>" />
	<?php } ?>
	<?php
	if ( $logged_in ) {
		if ( $this->bpsp_check_user_role( 'administrator', $verified_user['bpsp_user_id'] ) || $this->bpsp_check_user_role( 'editor', $verified_user['bpsp_user_id'] ) || $bpsp_options['bpsp-publish-status'] == 'publish' ) {
			?>
			<label for="bpsp-priv-publish-status"><?php sanitize_text_field(esc_attr_e('Post Status', 'bp-site-post')); ?></label>
			<select id='bpsp-priv-publish-status' name='bpsp-priv-publish-status'>
				<option value='publish' <?php if ( $my_post != '' ) {
					if ($my_post->post_status == 'publish') {
						echo 'selected="selected"';
					}
				}
				?>> <?php esc_attr_e('Publish', 'bp-site-post') ?></option>
				<option value='pending' <?php if ( $my_post != '' ) {
					if ($my_post->post_status  == 'pending') {
						echo 'selected="selected"';
					}
				}
				?>> <?php esc_attr_e('Pending', 'bp-site-post') ?></option>
				<option value='draft' <?php if ( $my_post != '' ) {
					if ($my_post->post_status  == 'draft') {
						echo 'selected="selected"';
					}
				}
				?>> <?php esc_attr_e('Draft', 'bp-site-post') ?></option>
				<option value='private'<?php if ( $my_post != '' ) {
					if ($my_post->post_status  == 'private') {
						echo 'selected="selected"';
					}
				}
				?>> <?php esc_attr_e('Private', 'bp-site-post') ?></option>
				<?php if ( defined( 'BPPS_GROUP_NAV_SLUG' ) ) {
					$master_group_disable = bpps_core_posts_disabled( 'groups' );
					$master_group_user_can_post = bpps_core_user_can_post( 'groups' );
					$master_friends_disable = bpps_core_posts_disabled( 'friends' );
					$friends_can_post = bpps_core_user_can_post( 'friends' );
					$master_members_disable = bpps_core_posts_disabled( 'members' );
					$members_can_post = bpps_core_user_can_post( 'members' );
					$master_following_disable = bpps_core_posts_disabled( 'following' );
					$master_followed_disable = bpps_core_posts_disabled( 'followed' );
					$following_can_post = bpps_core_user_can_post( 'following' );
					$followed_can_post = bpps_core_user_can_post( 'followed' );
					if ( ! $master_members_disable && ( $members_can_post || ( $logged_in && $bpsp_options['bpsp-allow-all-members-posts'] == 1 ) ) ) {?>
						<option value = 'members_only'<?php if ( $my_post != '' ) {
							if ($my_post->post_status  == 'members_only') {
								echo 'selected="selected"';
							}
						}
					?>> <?php sanitize_text_field(esc_attr_e( 'Members Only', 'bp-site-post' )) ?></option>
					<?php }
					if ( ! $master_group_disable && ( $master_group_user_can_post || ( $logged_in && $bpsp_options['bpsp-allow-all-members-posts'] == 1 ) ) ) { ?>
						<option value='group_post'<?php
						if ( ( $my_post != '' ) ) {
							if ($my_post->post_status  == 'group_post') {
								echo 'selected="selected"';
							}
						}
					?>> <?php sanitize_text_field(esc_attr_e( 'Group Post', 'bp-site-post' ) ) ?></option>
					<?php 
					}
					if ( ! $master_friends_disable && ( $friends_can_post || ( $logged_in && $bpsp_options['bpsp-allow-all-members-posts'] == 1 ) ) ) { ?>
						<option value = 'friends_only'<?php if ( $my_post != '' ) {
							if ($my_post->post_status  == 'friends_only') {
								echo 'selected="selected"';
							}
						}
					?>> <?php sanitize_text_field(esc_attr_e( 'Friends Only', 'bp-site-post' ) ) ?></option>
					<?php }
					if ( ! $master_following_disable && ( $following_can_post || ( $logged_in && $bpsp_options['bpsp-allow-all-members-posts'] == 1 ) ) ) {?>
						<option value = 'following'<?php if ( $my_post != '' ) {
							if ($my_post->post_status  == 'following') {
								echo 'selected="selected"';
							}
						}
					?>> <?php sanitize_text_field(esc_attr_e( 'Following', 'bp-site-post' )) ?></option>
					<?php }
					if ( ! $master_followed_disable && ( $followed_can_post || ( $logged_in && $bpsp_options['bpsp-allow-all-members-posts'] == 1 ) ) ) {?>
						<option value = 'followed'<?php if ( $my_post != '' ) {
							if ($my_post->post_status  == 'followed') {
								echo 'selected="selected"';
							}
						}
					?>> <?php sanitize_text_field(esc_attr_e( 'Followed', 'bp-site-post' )) ?></option>
					<?php }
				}?>
				</select><br>
				<?php if (defined( 'BPPS_GROUP_NAV_SLUG') ) {
				if ( ! $master_group_disable && ( $master_group_user_can_post || ( $logged_in && $bpsp_options['bpsp-allow-all-members-posts'] == 1 ) ) ) {
					$user_id = get_current_user_id();
					$groups = BP_Groups_Member::get_group_ids( $user_id, 100 );
					$groups = $groups['groups'];
					$group_list = array();
					foreach ( $groups as $group_id ) {
							
						$group_lookup = bpps_core_group_lookup( $user_id, $group_id );
						$group_posts_disabled = $group_lookup[0];
						$group_user_can_post = $group_lookup[1];

						if ( $group_posts_disabled == 0 && $group_user_can_post ) {
							
							$group_list[$group_id] = 1;
						
						}
					
					}
?>
					<label for="bpsp_group_select" <?php if ( ! isset( $group_selected )  ) echo 'style="display: none;"'; ?> id="bpsp_group_select_label"><?php sanitize_text_field(esc_attr_e( 'Group', 'bp-site-post' ) ); ?></label>
					<select id="bpsp_group_select" name='bpsp_group_select' <?php if ( ! isset( $group_selected ) ) echo 'style="display: none;"'; ?>>
					<?php 
					
					foreach ( $group_list as $group_id => $enabled ) {
						
						$group = groups_get_group($group_id);
						
						if ( $enabled == 1 ) {?>

							<option id="bpsp_post_group" value="<?php echo $group_id;?>"<?php if ( isset( $group_selected[0] ) ) {
								if ($group_selected[0] == $group_id) {
									echo 'selected="selected"';
								}
							}
							?>> <?php echo esc_attr(sanitize_text_field($group->name)); ?>
							</option>
						
						<?php }
					} ?>
					</select>
					<label <?php if ( ! isset( $selected_group_post_status ) ) echo 'style="display: none;"'; ?> for="bpsp_group_post_status" id="bpsp_group_post_status_label"><?php sanitize_text_field(esc_attr_e( 'Visibility:', 'bp-site-post' )); ?></label>
			
					<select <?php if ( ! isset( $selected_group_post_status )  ) echo 'style="display: none;"'; ?> name="bpsp_group_post_status" id="bpsp_group_post_status">
					
					<?php 
					
					$status_options = array(
						'group_only' 	=> sanitize_text_field(esc_attr__('Group Only', 'bp-site-post' ) ),
						'members_only' 	=> sanitize_text_field(esc_attr__('Members Only', 'bp-site-post' ) ),
						'public'		=> sanitize_text_field(esc_attr__('Public', 'bp-site-post' ) )
						);
						
					foreach ( $status_options as $status => $label ) {
						
						?>
						
						<option id="group_post_status" <?php if ( isset( $selected_group_post_status[0] ) ) {
							if ( $selected_group_post_status[0]  == $status ) { 
								echo 'selected="selected"';
							}
						}
						?>value="<?php echo $status;?>" <?php 
						
						?>><?php
							echo $label; ?></option>
					
					<?php } 
					}
				?>
					
				</select><br>					
				<?php			}
		} else if ( $bpsp_options['bpsp-publish-status'] == 'pending' ) {
			?>
			<label for="bpsp-priv-publish-status"><?php sanitize_text_field(esc_attr_e('Post Status', 'bp-site-post')); ?></label>
			<select id='bpsp-priv-publish-status' name='bpsp-priv-publish-status'>
				<option value='pending' <?php if ( $my_post != '' ) {
					if ($my_post->post_status  == 'pending') {
						echo 'selected="selected"';
					}
				}
				?>> <?php sanitize_text_field(esc_attr_e('Pending', 'bp-site-post')) ?></option>
				<option value='draft' <?php if ( $my_post != '' ) {
					if ($my_post->post_status  == 'draft') {
						echo 'selected="selected"';
					}
				}
				?>> <?php sanitize_text_field(esc_attr_e('Draft', 'bp-site-post')) ?></option>
				<option value='private'<?php if ( $my_post != '' ) {
					if ($my_post->post_status  == 'private') {
						echo 'selected="selected"';
					}
				}
				?>> <?php sanitize_text_field(esc_attr_e('Private', 'bp-site-post')) ?></option>
				<?php if ( defined( 'BPPS_GROUP_NAV_SLUG' ) ) {
					$master_group_disable = bpps_core_posts_disabled( 'groups' );
					$master_group_user_can_post = bpps_core_user_can_post( 'groups' );
					$master_friends_disable = bpps_core_posts_disabled( 'friends' );
					$friends_can_post = bpps_core_user_can_post( 'friends' );
					$master_members_disable = bpps_core_posts_disabled( 'members' );
					$members_can_post = bpps_core_user_can_post( 'members' );
					$master_following_disable = bpps_core_posts_disabled( 'following' );
					$master_followed_disable = bpps_core_posts_disabled( 'followed' );
					$following_can_post = bpps_core_user_can_post( 'following' );
					$followed_can_post = bpps_core_user_can_post( 'followed' );
					if ( ! $master_members_disable && ( $members_can_post || ( $logged_in && $bpsp_options['bpsp-allow-all-members-posts'] == 1 ) ) ) {?>
						<option value = 'members_only_pending'<?php if ( $my_post != '' ) {
							if ($my_post->post_status  == 'members_only_pending') {
								echo 'selected="selected"';
							}
						}
					?>> <?php sanitize_text_field(esc_attr_e( 'Members Only Pending', 'bp-site-post' )) ?></option>
					<?php }
					if ( ! $master_group_disable && ( $master_group_user_can_post || ( $logged_in && $bpsp_options['bpsp-allow-all-members-posts'] == 1 ) ) ) { ?>
						<option value='group_post_pending'<?php if ( $my_post != '' ) {
							if ($my_post->post_status  == 'group_post_pending') {
								echo 'selected="selected"';
							}
						}
					?>> <?php sanitize_text_field(esc_attr_e( 'Group Post Pending', 'bp-site-post' )) ?></option>
					<?php }
					if ( ! $master_friends_disable && ( $friends_can_post || ( $logged_in && $bpsp_options['bpsp-allow-all-members-posts'] == 1 ) ) ) { ?>
						<option value = 'friends_only'<?php if ( $my_post != '' ) {
							if ($my_post->post_status  == 'friends_only') {
								echo 'selected="selected"';
							}
						}
					?>> <?php sanitize_text_field(esc_attr_e( 'Friends Only', 'bp-site-post' ) ) ?></option>
					<?php }
					if ( ! $master_following_disable && ( $following_can_post || ( $logged_in && $bpsp_options['bpsp-allow-all-members-posts'] == 1 ) ) ) {?>
						<option value = 'following'<?php if ( $my_post != '' ) {
							if ($my_post->post_status  == 'following') {
								echo 'selected="selected"';
							}
						}
					?>> <?php sanitize_text_field(esc_attr_e( 'Following', 'bp-site-post' )) ?></option>
					<?php }
					if ( ! $master_followed_disable && ( $followed_can_post || ( $logged_in && $bpsp_options['bpsp-allow-all-members-posts'] == 1 ) ) ) {?>
						<option value = 'followed'<?php if ( $my_post != '' ) {
							if ($my_post->post_status  == 'followed') {
								echo 'selected="selected"';
							}
						}
					?>> <?php sanitize_text_field(esc_attr_e( 'Followed', 'bp-site-post' )) ?></option>
					<?php }
				}?>
				</select><br>
				<?php if ( defined( 'BPPS_GROUP_NAV_SLUG' ) ) {
				if ( ! $master_group_disable && ( $master_group_user_can_post || ( $logged_in && $bpsp_options['bpsp-allow-all-members-posts'] == 1 ) ) ) {
					$user_id = get_current_user_id();
					$groups = BP_Groups_Member::get_group_ids( $user_id, 100 );
					$groups = $groups['groups'];
					$group_list = array();
					
					foreach ( $groups as $group_id ) {
							
						$group_lookup = bpps_core_group_lookup( $user_id, $group_id );
						$group_posts_disabled = $group_lookup[0];
						$group_user_can_post = $group_lookup[1];

						if ( $group_posts_disabled == 0 && $group_user_can_post ) {
							
							$group_list[$group_id] = 1;
						
						}
					
					}
?>
					<label for="bpsp_group_select" <?php if ( ! isset( $group_selected ) ) echo 'style="display: none;"'; ?> id="bpsp_group_select_label"><?php esc_attr_e( 'Group', 'bp-site-post' ); ?></label>
					<select id="bpsp_group_select" name='bpsp_group_select' <?php if ( ! isset( $group_selected ) ) echo 'style="display: none;"'; ?>>
					<?php 
					
					foreach ( $group_list as $group_id => $enabled ) {
						
						$group = groups_get_group($group_id);
						
						if ( $enabled == 1 ) {?>

							<option id="bpsp_post_group" value="<?php echo $group_id;?>"<?php if ( isset( $group_selected[0] ) ) {
								if ($group_selected[0] == $group_id) {
									echo 'selected="selected"';
								}
							}
							?>> <?php echo esc_attr(sanitize_text_field($group->name)); ?>
							</option>
						
						<?php }
					} ?>
					</select>
					<label <?php if ( ! isset( $selected_group_post_status ) ) echo 'style="display: none;"'; ?> for="bpsp_group_post_status" id="bpsp_group_post_status_label"><?php sanitize_text_field(esc_attr_e( 'Visibility:', 'bp-site-post' ) ); ?></label>
			
					<select <?php if ( ! isset( $selected_group_post_status ) ) echo 'style="display: none;"'; ?> name="bpsp_group_post_status" id="bpsp_group_post_status">
					
					<?php 
					
					$status_options = array(
						'group_only' 	=> sanitize_text_field(esc_attr__('Group Only', 'bp-site-post' ) ),
						'members_only' 	=> sanitize_text_field(esc_attr__('Members Only', 'bp-site-post' ) ),
						'public'		=> sanitize_text_field(esc_attr__('Public', 'bp-site-post' ) )
						);
					
					
					
					foreach ( $status_options as $status => $label ) {
						
						?>
						
						<option id="group_post_status" <?php if ( isset ( $selected_group_post_status[0] ) ) {
							if ( $selected_group_post_status[0]  == $status ) {
								echo 'selected="selected"';
							}
						}
						?>value="<?php echo $status;?>" <?php 
						
						?>><?php
							echo $label; ?></option>
					
					<?php } ?>
					
				</select><br>					
				<?php }
			}
		}
	} ?>
	<button type="submit" class="send-button" id="submit"><?php echo ( $bpsp_options['bpsp-send-button'] ? esc_attr(sanitize_text_field($bpsp_options['bpsp-send-button'])) : sanitize_text_field(esc_attr__('Publish', 'bp-site-post'))); ?></button>
	<button id="refresher" type="reset" onclick="RefreshPage()" class="send-button <?php echo ($sr && $cf['form_ok']) ? 'visible' : ''; ?>"><?php sanitize_text_field(esc_attr_e('New Post', 'bp-site-post')); ?></button>
	<p id="success" class="<?php echo ($sr && $cf['form_ok']) ? 'visible' : ''; ?>"><?php echo esc_attr(sanitize_text_field($bpsp_options['bpsp-post-confirmation'])); ?></p>
	<p id="unique-title-message" style="display: none;"><?php echo sanitize_text_field($bpsp_options['bpsp-post-not-unique'] ); ?></p>
	<p id="error" class="<?php echo ($sr && !$cf['form_ok']) ? 'visible' : ''; ?>"><?php echo esc_attr(sanitize_text_field($bpsp_options['bpsp-post-fail'])); ?></p>
	</div> <!-- field-wrapper -->
</form>
<!--<div id="feedback"></div>-->
<?php } ?>
<script>
	var myForm = document.getElementById("site_post_form");
	var groupSelect = document.getElementById("bpsp_group_select");
	var groupSelectLabel = document.getElementById("bpsp_group_select_label");
	var groupStatus = document.getElementById("bpsp_group_post_status");
	var groupStatusLabel = document.getElementById("bpsp_group_post_status_label");
	myForm.style.display = "block";
	function groupsSelect(e) {
		statusChange = document.getElementById("bpsp-priv-publish-status").value;
		if ( statusChange == 'group_post' || statusChange == 'group_post_pending' ) {
			groupSelect.style.display = 'block';
			groupSelectLabel.style.display = 'block';
			groupStatus.style.display = 'block';
			groupStatusLabel.style.display = 'block';
		} else {
			groupSelect.style.display = 'none';
			groupSelectLabel.style.display = 'none';
			groupStatus.style.display = 'none';
			groupStatusLabel.style.display = 'none';
		}
	}
	var publishStatus = document.getElementById("bpsp-priv-publish-status");
	publishStatus.addEventListener( 'change', groupsSelect);
</script>
<noscript>
	<div class="noscriptmsg">
		<p><?php sanitize_text_field(esc_attr_e("Seems like you don't have Javascript enabled. To use this function you need to enable JavaScript.", "bp-site-post") ); ?></p>
	</div>
</noscript>
<script type="text/javascript">
	jQuery('#site_post_form').on('submit', ProcessFormAjax);
</script>
<script>
	function RefreshPage(){
		var newlocation = location.href;
		location.replace( newlocation.replace(location.search, '') );
	}
</script>