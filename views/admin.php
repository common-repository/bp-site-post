<?php

if(!defined('ABSPATH')) {
	exit;
}


//Register our admin panel sections and fields

//Adding the sections and fields
//Syntax for settings:
//add_settings_section( $id, $title, $callback, $page )
//Syntax for fields:
//add_settings_field( $id, $title, $callback, $page, $section, $args ) $args are given to callback function

//Main settings and fields
add_settings_section('bpsp_site_post_plugin_main', sanitize_text_field(esc_attr__('Main Settings', 'bp-site-post')), 'bpsp_site_post_plugin_main_text', 'bp-site-post-plugin');
add_settings_field('bpsp_site_post_form_name', sanitize_text_field(esc_attr__('Form Title', 'bp-site-post')), 'bpsp_site_post_form_name', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_edit_page', sanitize_text_field(esc_attr__('Edit Page ID', 'bp-site-post')), 'bpsp_site_post_edit_page', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_add_edit_link', sanitize_text_field(esc_attr__('Add edit link to post content', 'bp-site-post')), 'bpsp_site_post_add_edit_link', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_publish_status', sanitize_text_field(esc_attr__('Publish Status', 'bp-site-post')), 'bpsp_site_post_publish_status', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_enforce_unique_title', sanitize_text_field(esc_attr__('Enforce Unique Titles', 'bp-site-post')), 'bpsp_site_post_enforce_unique_title', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_post_confirmation', sanitize_text_field(esc_attr__('Post Success Message', 'bp-site-post')), 'bpsp_site_post_post_confirmation', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_post_fail', sanitize_text_field(esc_attr__('Post Failure Message', 'bp-site-post')), 'bpsp_site_post_post_fail', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_not_unique', sanitize_text_field(esc_attr__('Post Failure Message - Title not unique', 'bp-site-post')), 'bpsp_site_post_not_unique', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_auto_thumbnail', sanitize_text_field(esc_attr__('Automatically Add Featured Image', 'bp-site-post')), 'bpsp_site_post_auto_thumbnail', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
//add_settings_field('bpsp_site_post_redirect', sanitize_text_field(esc_attr__('Redirect to', 'bp-site-post')), 'bpsp_site_post_redirect', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_mail', sanitize_text_field(esc_attr__('Mail on New Post', 'bp-site-post')), 'bpsp_site_post_mail', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_login_link', sanitize_text_field(esc_attr__('Display Login Link in Form', 'bp-site-post')), 'bpsp_site_post_login_link', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');

if ( current_theme_supports('post-formats') ) {
	add_settings_field('bpsp_site_post_post_format_default', sanitize_text_field(esc_attr__('Default Post Format', 'bp-site-post')), 'bpsp_site_post_post_format_default', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
	add_settings_field('bpsp_site_post_post_format', sanitize_text_field(esc_attr__('Allow selection of Post Format', 'bp-site-post')), 'bpsp_site_post_post_format', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
}

add_settings_field('bpsp_site_post_hide_toolbar', sanitize_text_field(esc_attr__('Hide WordPress Toolbar', 'bp-site-post')), 'bpsp_site_post_hide_toolbar', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_hide_toolbar_edit', sanitize_text_field(esc_attr__('Hide regular Toolbar Edit Link', 'bp-site-post')), 'bpsp_site_post_hide_toolbar_edit', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_hide_new', sanitize_text_field(esc_attr__('Hide New Content Menu', 'bp-site-post')), 'bpsp_site_post_hide_new', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_hide_toolbar_wp', sanitize_text_field(esc_attr__('Hide Toolbar WP logo', 'bp-site-post')), 'bpsp_site_post_hide_toolbar_wp', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_hide_toolbar_site_name', sanitize_text_field(esc_attr__('Hide Toolbar Site Name', 'bp-site-post')), 'bpsp_site_post_hide_toolbar_site_name', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_hide_toolbar_comments', sanitize_text_field(esc_attr__('Hide Toolbar Comments Link', 'bp-site-post')), 'bpsp_site_post_hide_toolbar_comments', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_hide_edit', sanitize_text_field(esc_attr__('Hide regular WP Edit Link', 'bp-site-post')), 'bpsp_site_post_hide_edit', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_no_backend', sanitize_text_field(esc_attr__('Deny Backend Access for Subscribers and Contributors', 'bp-site-post')), 'bpsp_site_post_no_backend', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_all_members_posts', sanitize_text_field(esc_attr__('Allow all site members to post', 'bp-site-post')), 'bpsp_site_post_allow_all_members_posts', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_allow_media_upload', sanitize_text_field(esc_attr__('Allow Media Upload', 'bp-site-post')), 'bpsp_site_post_allow_media_upload', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_allow_subscriber_media_upload', sanitize_text_field(esc_attr__('Allow Media Upload for all site users', 'bp-site-post')), 'bpsp_site_post_allow_subscriber_media_upload', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_show_tags', sanitize_text_field(esc_attr__('Allow Tags', 'bp-site-post')), 'bpsp_site_post_show_tags', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_guest_info', sanitize_text_field(esc_attr__('Email & Name for Guest Posts', 'bp-site-post')), 'bpsp_site_post_guest_info', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_guest_posts', sanitize_text_field(esc_attr__('Allow guest to post', 'bp-site-post')), 'bpsp_site_post_allow_guest_posts', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_guest_account', sanitize_text_field(esc_attr__('Guest Account', 'bp-site-post')), 'bpsp_site_post_guest_account', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_guest_cat_select', sanitize_text_field(esc_attr__('Allow Guests to select Category', 'bp-site-post')), 'bpsp_site_post_guest_cat_select', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_guest_cat', sanitize_text_field(esc_attr__('Category for Guest Posts', 'bp-site-post')), 'bpsp_site_post_guest_cat', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
add_settings_field('bpsp_site_post_quiz', sanitize_text_field(esc_attr__('Show Guests a Spam Prevention Quiz', 'bp-site-post')), 'bpsp_site_post_quiz', 'bp-site-post-plugin', 'bpsp_site_post_plugin_main');
//Category settings and fields
add_settings_section('bpsp_site_post_plugin_cats', sanitize_text_field(esc_attr__('Category Settings', 'bp-site-post')), 'bpsp_site_post_plugin_cats_text', 'bp-site-post-plugin');
add_settings_field('bpsp_site_post_categories', sanitize_text_field(esc_attr__('Display Categories', 'bp-site-post')), 'bpsp_site_post_categories', 'bp-site-post-plugin', 'bpsp_site_post_plugin_cats');
add_settings_field('bpsp_site_post_restrict_categories', sanitize_text_field(esc_attr__('Restrict Categories', 'bp-site-post')), 'bpsp_site_post_restrict_categories', 'bp-site-post-plugin', 'bpsp_site_post_plugin_cats');
add_settings_field('bpsp_site_post_restricted_categories', sanitize_text_field(esc_attr__('Restricted Categories', 'bp-site-post')), 'bpsp_site_post_restricted_categories', 'bp-site-post-plugin', 'bpsp_site_post_plugin_cats');
add_settings_field('bpsp_site_post_allow_new_category', sanitize_text_field(esc_attr__('Create New Category', 'bp-site-post')), 'bpsp_site_post_allow_new_category', 'bp-site-post-plugin', 'bpsp_site_post_plugin_cats');
add_settings_field('bpsp_site_post_category_order', sanitize_text_field(esc_attr__('Category Order', 'bp-site-post')), 'bpsp_site_post_category_order', 'bp-site-post-plugin', 'bpsp_site_post_plugin_cats');

//Field settings and fields
add_settings_section('bpsp_site_post_plugin_fields', sanitize_text_field(esc_attr__('Field Settings', 'bp-site-post')), 'bpsp_site_post_plugin_fields_text', 'bp-site-post-plugin');
add_settings_field('bpsp_site_post_title-required', sanitize_text_field(esc_attr__('Require a Title', 'bp-site-post')), 'bpsp_site_post_title_required', 'bp-site-post-plugin', 'bpsp_site_post_plugin_fields');
add_settings_field('bpsp_site_post_show_excerpt', sanitize_text_field(esc_attr__('Show Separate Excerpt', 'bp-site-post')), 'bpsp_site_post_show_excerpt', 'bp-site-post-plugin', 'bpsp_site_post_plugin_fields');
add_settings_field('bpsp_site_post_editor_style', sanitize_text_field(esc_attr__('Content Field Style', 'bp-site-post')), 'bpsp_site_post_editor_style', 'bp-site-post-plugin', 'bpsp_site_post_plugin_fields');

//Label settings and fields
add_settings_section('bpsp_site_post_plugin_labels', sanitize_text_field(esc_attr__('Labels', 'bp-site-post')), 'bpsp_site_post_plugin_label_text', 'bp-site-post-plugin');
add_settings_field('bpsp_site_post_title', sanitize_text_field(esc_attr__('Title', 'bp-site-post')), 'bpsp_site_post_title', 'bp-site-post-plugin', 'bpsp_site_post_plugin_labels');
add_settings_field('bpsp_site_post_excerpt', sanitize_text_field(esc_attr__('Excerpt', 'bp-site-post')), 'bpsp_site_post_excerpt', 'bp-site-post-plugin', 'bpsp_site_post_plugin_labels');
add_settings_field('bpsp_site_post_content', sanitize_text_field(esc_attr__('Content', 'bp-site-post')), 'bpsp_site_post_content', 'bp-site-post-plugin', 'bpsp_site_post_plugin_labels');
add_settings_field('bpsp_site_post_tags', sanitize_text_field(esc_attr__('Tags', 'bp-site-post')), 'bpsp_site_post_tags', 'bp-site-post-plugin', 'bpsp_site_post_plugin_labels');
add_settings_field('bpsp_site_post_categories_label', sanitize_text_field(esc_attr__('Categories', 'bp-site-post')), 'bpsp_site_post_categories_label', 'bp-site-post-plugin', 'bpsp_site_post_plugin_labels');
add_settings_field('bpsp_site_post_create_category', sanitize_text_field(esc_attr__('New Category', 'bp-site-post')), 'bpsp_site_post_create_category', 'bp-site-post-plugin', 'bpsp_site_post_plugin_labels');
add_settings_field('bpsp_site_post_send_button', sanitize_text_field(esc_attr__('Send Button', 'bp-site-post')), 'bpsp_site_post_send_button', 'bp-site-post-plugin', 'bpsp_site_post_plugin_labels');

?>

<!-- Print the html that makes up our admin settings page -->

<?php /*

<?php $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'bpspspp_general_settings'; ?>
<div class="wrap">
    <div class="icon32">
	<img src="<?php echo plugins_url('/images/bpsp-grey34x34.png', __FILE__) ?>">
    </div>
    <h2><?php sanitize_text_field(esc_attr_e('BP Site Post Pro Settings', 'bp-site-post')); ?></h2>
    <a href="http://wordpress.org/support/plugin/bp-site-post/"><?php sanitize_text_field(esc_attr_e('Need help? Just click here!', 'bp-site-post')) ?></a>
	<br />
	<a href="http://www.bpspesign.de/bp-site-post-plugin-en/"><?php sanitize_text_field(esc_attr_e('Documentation is here!', 'bp-site-post')) ?></a>
    <br/>
	<?php $this->plugin_options_tabs(); ?>
    <form id="bpsp_site_post_admin_form" method="post" action="options.php">
	<?php wp_nonce_field( 'update-options' ); ?>
	<?php
	// Argument in settings_fields is the option-group registered with register_setting
	settings_fields($tab);
	// Argument in do_settings_sections is same as $page in add_settings_field
	do_settings_sections($tab);
	?>

	<p class="submit"><input class="button-primary" name="Submit" type="submit" value="<?php esc_attr_e('Save Changes', 'bp-site-post'); ?>" /></p>
    </form>
</div>

*/ 


?>

<div class="wrap">
    <div class="icon32">
	<img src="<?php echo plugins_url('/images/bpsp-grey34x34.png', __FILE__) ?>">
    </div>
    <h2><?php sanitize_text_field(esc_attr_e('BP Site Post Settings', 'bp-site-post')); ?></h2>
    <a href="https://wordpress.org/plugins/bp-site-post"><?php sanitize_text_field(esc_attr_e('Need help? Just click here!', 'bp-site-post')) ?></a>
	<br />
	<a href="http://buddyuser.com/plugin-bp-site-post/"><?php sanitize_text_field(esc_attr_e('Documentation is here!', 'bp-site-post')) ?></a>
    <br/>
    <form id="bpsp_site_post_admin_form" method="post" action="options.php">
	<?php
	// Argument in settings_fields is the option-group registered with register_setting
	settings_fields('bpsp_site_post_template_group');
	// Argument in do_settings_sections is same as $page in add_settings_field
	do_settings_sections('bp-site-post-plugin');
	?>

	<p class="submit"><input class="button-primary" name="Submit" type="submit" value="<?php sanitize_text_field(esc_attr_e('Save Changes', 'bp-site-post')); ?>" /></p>
    </form>
</div>



<?php

//The callback functions that read the settings from the db and echo the html
function bpsp_site_post_form_name() {
    $options = get_option('bpsp_site_post_settings');
	$form_name = esc_attr(sanitize_text_field( $options['bpsp-form-name']));
    echo "<input type='text' id='bpsp-form-name' class='regular-text' name='bpsp_site_post_settings[bpsp-form-name]' value='" . esc_attr(sanitize_text_field($form_name)) . "' size='40' /> ";
}
function bpsp_site_post_edit_page() {
    $options = get_option('bpsp_site_post_settings');
	$edit_page = 0;
	if ( isset( $options['bpsp-edit-page']  ) ) {
		$edit_page = esc_attr(sanitize_text_field( $options['bpsp-edit-page']));
	}
    echo "<input type='text' id='bpsp-edit-page' name='bpsp_site_post_settings[bpsp-edit-page]' value='" . esc_attr($edit_page) . "' size='4' /> ";
	sanitize_text_field(esc_attr_e('Enter the ID of the page where the edit form appears (the page where you entered the plugin shortcode).', 'bp-site-post'));
}

function bpsp_site_post_add_edit_link() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-add-edit-link'] ) && $options['bpsp-add-edit-link'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-add-edit-link' name='bpsp_site_post_settings[bpsp-add-edit-link]' value='1' <?php checked(1, $checked ); ?> />
    <?php
	sanitize_text_field(esc_attr_e('Add a link to edit the post after the post content).', 'bp-site-post'));
}
function bpsp_site_post_auto_thumbnail() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-auto-add-thumb'] ) && $options['bpsp-auto-add-thumb'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-auto-add-thumb' name='bpsp_site_post_settings[bpsp-auto-add-thumb]' value='1' <?php checked(1, $checked ); ?> />
    <?php
	sanitize_text_field(esc_attr_e('Automatically add the first uploaded image in the post as featured.', 'bp-site-post'));
}
function bpsp_site_post_title() {
    $options = get_option('bpsp_site_post_settings');
	$title = esc_attr($options['bpsp-title']);
    echo "<input type='text' id='bpsp-title' name='bpsp_site_post_settings[bpsp-title]' value='" . esc_attr(sanitize_text_field($title)) . "' size='40' /> ";
}
function bpsp_site_post_content() {
    $options = get_option('bpsp_site_post_settings');
	$content = esc_attr( $options['bpsp-content']);
    echo "<input type='text' id='bpsp-content' name='bpsp_site_post_settings[bpsp-content]' value='" . $content . "' size='40' /> ";
}
function bpsp_site_post_excerpt() {
    $options = get_option('bpsp_site_post_settings');
	$excerpt = esc_attr( $options['bpsp-excerpt']);
    echo "<input type='text' id='bpsp-excerpt' name='bpsp_site_post_settings[bpsp-excerpt]' value='" . $excerpt . "' size='40' /> ";
}
function bpsp_site_post_tags() {
    $options = get_option('bpsp_site_post_settings');
	$tags = esc_attr( $options['bpsp-tags']);
    echo "<input type='text' id='bpsp-tags' name='bpsp_site_post_settings[bpsp-tags]' value='" . $tags . "' size='40' /> ";
}
function bpsp_site_post_categories_label() {
    $options = get_option('bpsp_site_post_settings');
	$cat_label = esc_attr( $options['bpsp-categories-label']);
    echo "<input type='text' id='bpsp-categories-label' name='bpsp_site_post_settings[bpsp-categories-label]' value='" . $cat_label . "' size='40' /> ";
}
function bpsp_site_post_create_category() {
    $options = get_option('bpsp_site_post_settings');
	$create_cat = esc_attr( $options['bpsp-create-category']);
    echo "<input type='text' id='bpsp-create-category' name='bpsp_site_post_settings[bpsp-create-category]' value='" . $create_cat . "' size='40' /> ";
}
function bpsp_site_post_send_button() {
    $options = get_option('bpsp_site_post_settings');
	$send_but = esc_attr( $options['bpsp-send-button']);
    echo "<input type='text' id='bpsp-send-button' name='bpsp_site_post_settings[bpsp-send-button]' value='" . $send_but . "' size='40' /> ";
}
function bpsp_site_post_publish_status() {
    $options = get_option('bpsp_site_post_settings');
	?>
    <select id='bpsp-publish-status' name='bpsp_site_post_settings[bpsp-publish-status]' value="<?php echo esc_attr($options['bpsp-publish-status']); ?>" >
        <option value='publish' <?php if ($options['bpsp-publish-status'] == 'publish') echo 'selected="selected"'; ?>> <?php sanitize_text_field(esc_attr_e('Publish', 'bp-site-post')) ?></option>
        <option value='pending' <?php if ($options['bpsp-publish-status'] == 'pending') echo 'selected="selected"'; ?>> <?php sanitize_text_field(esc_attr_e('Pending', 'bp-site-post')) ?></option>
    </select>
    <?php
    sanitize_text_field(esc_attr_e("<p class='description'>The Statuses available to standard users - Pending or Publish.</p>", "bp-site-post"));
}
// @since 1.4.0
// Enforce unique titles before allowing publish
function bpsp_site_post_enforce_unique_title() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-enforce-unique-title'] ) && $options['bpsp-enforce-unique-title'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-enforce-unique-title' name='bpsp_site_post_settings[bpsp-enforce-unique-title]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Check to require a unique title for the post.', 'bp-site-post'));
}

function bpsp_site_post_show_tags() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-show-tags'] ) && $options['bpsp-show-tags'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-show-tags' name='bpsp_site_post_settings[bpsp-show-tags]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Check to display a field to enter tags.', 'bp-site-post'));
}
function bpsp_site_post_guest_info() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-guest-info'] ) && $options['bpsp-guest-info'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-guest-info' name='bpsp_site_post_settings[bpsp-guest-info]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Check to require email and name for guest posts.', 'bp-site-post'));
}
function bpsp_site_post_title_required() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-title-required'] ) && $options['bpsp-title-required'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-title-required' name='bpsp_site_post_settings[bpsp-title-required]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Check to enforce the user to enter a title for his post.', 'bp-site-post'));
}
function bpsp_site_post_show_excerpt() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-show-excerpt'] ) && $options['bpsp-show-excerpt'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-show-excerpt' name='bpsp_site_post_settings[bpsp-show-excerpt]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Check to display a separate field for the excerpt.', 'bp-site-post'));
}
function bpsp_site_post_show_content() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-show-content'] ) && $options['bpsp-show-content'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-show-content' name='bpsp_site_post_settings[bpsp-show-content]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Check to display an editor field for the content.', 'bp-site-post'));
}
function bpsp_site_post_editor_style() {
    $options = get_option('bpsp_site_post_settings');
    ?>
    <select id='bpsp-editor-style' name='bpsp_site_post_settings[bpsp-editor-style]' value="<?php echo esc_attr($options['bpsp-editor-style']); ?>" >
        <option value='simple' <?php if ($options['bpsp-editor-style'] == 'simple') echo 'selected="selected"'; ?>> <?php sanitize_text_field(esc_attr_e('Simple - Plain Text', 'bp-site-post')) ?></option>
        <option value='rich' <?php if ($options['bpsp-editor-style'] == 'rich') echo 'selected="selected"'; ?>> <?php sanitize_text_field(esc_attr_e('Rich - Visual and HTML', 'bp-site-post')) ?></option>
        <option value='visual' <?php if ($options['bpsp-editor-style'] == 'visual') echo 'selected="selected"'; ?>> <?php sanitize_text_field(esc_attr_e('Visual - Visual Only', 'bp-site-post')) ?></option>
		<option value='html' <?php if ($options['bpsp-editor-style'] == 'html') echo 'selected="selected"'; ?>> <?php sanitize_text_field(esc_attr_e('HTML - HTML Only', 'bp-site-post')) ?></option>
    </select>
    <?php
}
function bpsp_site_post_post_confirmation() {
    $options = get_option('bpsp_site_post_settings');
	$post_confirm = esc_attr( $options['bpsp-post-confirmation']);
    echo "<input type='text' id='bpsp-post-confirmation' name='bpsp_site_post_settings[bpsp-post-confirmation]' value='" . $post_confirm . "' size='40' /> ";
    sanitize_text_field(esc_attr_e('<p class="description">Your custom post-success message.</p>', 'bp-site-post'));
}
function bpsp_site_post_post_fail() {
    $options = get_option('bpsp_site_post_settings');
	$post_fail = esc_attr( $options['bpsp-post-fail']);
    echo "<input type='text' id='bpsp-post-fail' name='bpsp_site_post_settings[bpsp-post-fail]' value='" . $post_fail . "' size='40' /> ";
    sanitize_text_field(esc_attr_e('<p class="description">Your custom post-failure message.</p>', 'bp-site-post'));
}
function bpsp_site_post_not_unique() {
    $options = get_option('bpsp_site_post_settings');
	$post_fail = sanitize_text_field( $options['bpsp-post-not-unique']);
    echo "<input type='text' id='bpsp-post-not-unique' name='bpsp_site_post_settings[bpsp-post-not-unique]' value='" . $post_fail . "' size='40' /> ";
    sanitize_text_field(esc_attr_e('<p class="description">Your custom post not unique message.</p>', 'bp-site-post'));
}
function bpsp_site_post_redirect() {
    $options = get_option('bpsp_site_post_settings');
	$redirect = esc_attr( $options['bpsp-redirect']);
    echo "<input type='text' id='bpsp-redirect' name='bpsp_site_post_settings[bpsp-redirect]' value='" . $redirect . "' size='40' /> ";
    sanitize_text_field(esc_attr_e('<p class="description">The URL to redirect the user to after his post.</p>', 'bp-site-post'));
    sanitize_text_field(esc_attr_e('<p class="description">Shortcode parameters will overwrite this setting.</p>', 'bp-site-post'));
}
function bpsp_site_post_mail() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-mail'] ) && $options['bpsp-mail'] !='' ) {
		$checked = 1;
	}

    ?>
    <input type='checkbox' id='bpsp-mail' name='bpsp_site_post_settings[bpsp-mail]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Check to notify admin on new post.', 'bp-site-post'));
}
function bpsp_site_post_categories() {
    $options = get_option('bpsp_site_post_settings');
    ?>
    <select id='bpsp-categories' name='bpsp_site_post_settings[bpsp-categories]' value="<?php echo esc_attr($options['bpsp-categories']); ?>" >
        <option value='list' <?php if ($options['bpsp-categories'] == 'list') echo 'selected="selected"'; ?>> <?php sanitize_text_field(esc_attr_e('Droplist', 'bp-site-post')) ?></option>
        <option value='check' <?php if ($options['bpsp-categories'] == 'check') echo 'selected="selected"'; ?>> <?php sanitize_text_field(esc_attr_e('Check boxes', 'bp-site-post')) ?></option>
        <option value='none' <?php if ($options['bpsp-categories'] == 'none') echo 'selected="selected"'; ?>> <?php sanitize_text_field(esc_attr_e('No display', 'bp-site-post')) ?></option>
    </select>
    <?php
    sanitize_text_field(esc_attr_e('How categories appear at the front end.', 'bp-site-post'));
    sanitize_text_field(esc_attr_e('<p class="description">You can select not to display categories at all.</p>', 'bp-site-post'));
}
function bpsp_site_post_restrict_categories() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-restrict-categories'] ) && $options['bpsp-restrict-categories'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-restrict-categories' name='bpsp_site_post_settings[bpsp-restrict-categories]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Limit the categories users can choose.', 'bp-site-post'));
}
function bpsp_site_post_restricted_categories() {
    $options = get_option('bpsp_site_post_settings');
	$orderby = esc_attr($options['bpsp-category-order']);
	$args = array(
		'type'              => 'post',
		'orderby'           => $orderby,
		'order'             => 'ASC',
		'hide_empty'        => 0,
		'hierarchical'      => 0,
		'taxonomy'          => 'category',
		'pad_counts'        => false
	);
	$categories = get_categories( $args );
	?><ul><?php
	foreach( $categories as $category ) : 
		$checked = '';
		if ( isset( $options['bpsp-restricted-categories'] ) && is_array( $options['bpsp-restricted-categories'] ) && in_array( $category->term_id, $options['bpsp-restricted-categories'] ) ) {
			$checked = 'checked="checked"';
		}?>
		<li><input type="checkbox" name="bpsp_site_post_settings[bpsp-restricted-categories][]" value="<?php echo esc_attr($category->term_id); ?>" <?php echo $checked; ?> />&nbsp;<?php echo esc_attr($category->name); ?></li>
	<?php endforeach; ?>
    </ul>
	<?php
    sanitize_text_field(esc_attr_e('List of restricted categories.', 'bp-site-post'));
}
function bpsp_site_post_allow_new_category() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-allow-new-category'] ) && $options['bpsp-allow-new-category'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-allow-new-category' name='bpsp_site_post_settings[bpsp-allow-new-category]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Check to allow users to create new categories.', 'bp-site-post'));
}
function bpsp_site_post_category_order() {
    $options = get_option('bpsp_site_post_settings');
    ?>
    <select id='bpsp-category-order' name='bpsp_site_post_settings[bpsp-category-order]' value="<?php echo $options['bpsp-category-order']; ?>" >
        <option value='id' <?php if ($options['bpsp-category-order'] == 'id') echo 'selected="selected"'; ?>> <?php sanitize_text_field(esc_attr_e('by ID', 'bp-site-post')) ?></option>
        <option value='name' <?php if ($options['bpsp-category-order'] == 'name') echo 'selected="selected"'; ?>> <?php sanitize_text_field(esc_attr_e('by name', 'bp-site-post')) ?></option>
    </select>
    <?php
    sanitize_text_field(esc_attr_e('The sort order of categories at the front end.', 'bp-site-post'));
}
function bpsp_site_post_allow_media_upload() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-allow-media-upload'] ) && $options['bpsp-allow-media-upload'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-allow-media-upload' name='bpsp_site_post_settings[bpsp-allow-media-upload]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('This setting will allow members with "upload_files" capability to add images etc, to extend this to all site members check below too.', 'bp-site-post'));
}
function bpsp_site_post_allow_subscriber_media_upload() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-allow-subscriber-media-upload'] ) && $options['bpsp-allow-subscriber-media-upload'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-allow-subscriber-media-upload' name='bpsp_site_post_settings[bpsp-allow-subscriber-media-upload]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Allow all site users to upload new media files (picture, video).', 'bp-site-post'));
}
function bpsp_site_post_login_link () {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-login-link'] ) && $options['bpsp-login-link'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-login-link' name='bpsp_site_post_settings[bpsp-login-link]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Display a Login Link inside the form.', 'bp-site-post'));
}
function bpsp_site_post_post_format_default () {
    $options = get_option('bpsp_site_post_settings');
	$post_formats = get_theme_support( 'post-formats' );
		
	if ( is_array( $post_formats[0] ) ) : ?>
		<select id='bpsp-post-format-default' name='bpsp_site_post_settings[bpsp-post-format-default]' value="<?php echo $options['bpsp-post-format-default']; ?>">
		<option value="0" <?php selected( $options['bpsp-post-format-default'], '0' ); ?> ><?php echo esc_attr(sanitize_text_field(get_post_format_string( 'standard' ))); ?></option>
		<?php foreach ( $post_formats[0] as $format ) : ?>
		<option value="<?php echo esc_attr( $format ); ?>" <?php selected( isset($options['bpsp-post-format-default']), $format ); ?> ><?php echo esc_attr(sanitize_text_field( get_post_format_string( $format ))); ?></option>
		<?php endforeach; ?>
		</select>
	<?php endif;
    sanitize_text_field(esc_attr_e( 'Select the default Post Format.', 'bp-site-post' ) );
}
function bpsp_site_post_post_format () {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-post-format'] ) && $options['bpsp-post-format'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-post-format' name='bpsp_site_post_settings[bpsp-post-format]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Allow the selection off the Post Format inside the form.', 'bp-site-post'));
}
function bpsp_site_post_hide_toolbar () {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-hide-toolbar'] ) && $options['bpsp-hide-toolbar'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-hide-toolbar' name='bpsp_site_post_settings[bpsp-hide-toolbar]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Hide the WordPress Toolbar for non editors.', 'bp-site-post'));
}
function bpsp_site_post_hide_new () {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-hide-new'] ) && $options['bpsp-hide-new'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-hide-new' name='bpsp_site_post_settings[bpsp-hide-new]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Hide the WordPress New Content Toolbar menu item for non editors.', 'bp-site-post'));
}
function bpsp_site_post_hide_toolbar_wp () {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-hide-toolbar-wp'] ) && $options['bpsp-hide-toolbar-wp'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-hide-toolbar-wp' name='bpsp_site_post_settings[bpsp-hide-toolbar-wp]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Hide the Toolbar WP logo.', 'bp-site-post'));
}
function bpsp_site_post_hide_toolbar_site_name () {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-hide-site-name'] ) && $options['bpsp-site-name'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-hide-toolbar-site-name' name='bpsp_site_post_settings[bpsp-hide-toolbar-site-name]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Hide the Toolbar Site Name Link for non Editors.', 'bp-site-post'));
}
function bpsp_site_post_hide_toolbar_comments () {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-toolbar-comments'] ) && $options['bpsp-hide-toolbar-comments'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-hide-toolbar-comments' name='bpsp_site_post_settings[bpsp-hide-toolbar-comments]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Hide the Toolbar Comments link.', 'bp-site-post'));
}
function bpsp_site_post_no_backend () {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-no-backend'] ) && $options['bpsp-no-backend'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' name='bpsp_site_post_settings[bpsp-no-backend]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Deny subscribers and contributors access to the backend.', 'bp-site-post'));
}
function bpsp_site_post_hide_edit () {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-hide-edit'] ) && $options['bpsp-hide-edit'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-hide-edit' name='bpsp_site_post_settings[bpsp-hide-edit]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Hide the regular WordPress Edit Link that is displayed while viewing a post, replace it with an edit link to Site Post.', 'bp-site-post'));
}
function bpsp_site_post_hide_toolbar_edit () {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-hide-toolbar-edit'] ) && $options['bpsp-hide-toolbar-edit'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-hide-toolbar-edit' name='bpsp_site_post_settings[bpsp-hide-toolbar-edit]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Hide the toolbar edit link that is displayed while viewing posts for non editors.', 'bp-site-post'));
}
function bpsp_site_post_allow_all_members_posts() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-allow-all-members-posts'] ) && $options['bpsp-allow-all-members-posts'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-allow-all-members-posts' name='bpsp_site_post_settings[bpsp-allow-all-members-posts]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Allow all registered members to post, not just those with "edit_posts" capability.', 'bp-site-post'));
}
function bpsp_site_post_allow_guest_posts() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-allow-guest-posts'] ) && $options['bpsp-allow-guest-posts'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-allow-guest-posts' name='bpsp_site_post_settings[bpsp-allow-guest-posts]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Allow guests to post.', 'bp-site-post'));
}
function bpsp_site_post_guest_account() {
    $options = get_option('bpsp_site_post_settings');
    $args = array(
        'selected'                => isset($options['bpsp-guest-account']),
        'name'                    => 'bpsp_site_post_settings[bpsp-guest-account]',
        'class'                   => 'bpsp_site_post_droplist'
    ); ?>
    <span title="<?php sanitize_text_field(esc_attr_e('Dedicated account to use for guests', 'bp-site-post')) ?>"><?php wp_dropdown_users($args); ?></span>
    <?php
    sanitize_text_field(esc_attr_e('The dedicated account that should be used for guest posts.', 'bp-site-post'));
}
function bpsp_site_post_guest_cat_select() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-guest-cat-select'] ) && $options['bpsp-guest-cat-select'] !='' ) {
		$checked = 1;
	}

    ?>
    <input type='checkbox' id='bpsp-guest-cat-select' name='bpsp_site_post_settings[bpsp-guest-cat-select]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Check if you want guests to select categories themselves. If not checked the default category you specify below will be used for guest posts.', 'bp-site-post'));
}
function bpsp_site_post_guest_cat() {
    $options = get_option('bpsp_site_post_settings');
	if (isset($options['bpsp-guest-cat'])) {
		$guest_cat = esc_attr($options['bpsp-guest-cat']);
	} else {
		$guest_cat = 'uncategorised';
	}
    $args = array(
	
		'orderby'                 => 'name',
		'order'                   => 'ASC',
        'selected'                => $guest_cat,
        'name'                    => 'bpsp_site_post_settings[bpsp-guest-cat]',
        'class'                   => 'bpsp_site_post_droplist',
		'hide_empty'              => 0,
		'hide_if_empty'           => false
    ); ?>
    <span title="<?php sanitize_text_field(esc_attr_e('Category used for guest posts', 'bp-site-post')) ?>"><?php wp_dropdown_categories($args); ?></span>
    <?php
    sanitize_text_field(esc_attr_e('The category guest posts should be assigned to.', 'bp-site-post'));
}
function bpsp_site_post_quiz() {
    $options = get_option('bpsp_site_post_settings');
	$checked = 0;
	if ( isset( $options['bpsp-quiz'] ) && $options['bpsp-quiz'] !='' ) {
		$checked = 1;
	}
    ?>
    <input type='checkbox' id='bpsp-quiz' name='bpsp_site_post_settings[bpsp-quiz]' value='1' <?php checked( 1, $checked ); ?> />
    <?php
    sanitize_text_field(esc_attr_e('Display a Spam Prevention Quiz. Applies to users not logged in.', 'bp-site-post'));
}
function bpsp_site_post_plugin_main_text(){
    echo "<p>" . sanitize_text_field(esc_attr_e('Section for all the general settings for this plugin.', 'bp-site-post')) . "</p>";
}
function bpsp_site_post_plugin_cats_text(){
    echo "<p>" . sanitize_text_field(esc_attr_e('Section for all the settings specific to categories.', 'bp-site-post')) . "</p>";
}
function bpsp_site_post_plugin_fields_text(){
    echo "<p>" . sanitize_text_field(esc_attr_e('Specify the fields you want to include in the form generated.', 'bp-site-post')) . "</p>";
    echo "<p>" . sanitize_text_field(esc_attr_e('Please note that the Title will always be included - No Post without Title.', 'bp-site-post')) . "</p>";
}
function bpsp_site_post_plugin_label_text(){
    echo "<p>" . sanitize_text_field(esc_attr_e('You can specify your own labels for the form fields shown to the user', 'bp-site-post')) . "</p>";
}