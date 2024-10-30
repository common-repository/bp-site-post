=== BP Site Post ===
Contributors: venutius, djarzyna
Donate Link: paypal.me/GeorgeChaplin
Tags: quick post, frontend, front end, insert post, post, front end post, guest post
Requires at least: 3.3.1
Tested up to: 6.6.1
Stable tag: 1.8.0
License: GPLv2 or later

Designed to work with BuddyPress Group, Members Only and Friends posts this front end post editor comes with a wide range of features to allow you to customize the front end editing experience.

== Description ==

Providing a front end post solution for a BuddyPress social network is not simply a case of enabling a front-end editor. There are other considerations such as removing access to the WP back-end if that's required. This plugin aims to help with both of these tasks by providing an editor that is closely integrated with BuddyPress and also providing options to better customize the overall look and feel of the users access to the site. So for example to can hide key elements of the WordPress Tool-bar, you can also disable any WordPress edit links that would traditionally go to the back-end. You can also deny users access to the backend altogether by redirecting to such requests to the home page.

This plugin is a fork of DJD Site Post, it's been updated in order to work with BP Post Status to deliver a front end editing experience which offers site admin a great degree of control over the user editing experience. A large number of features can be customized. It supports Group. Members Only, Friends Only and Follow post statuses and you can choose to only allow pending submissions or to allow users to to publish immediately.

Some of the features include:

* Front end posting from a page with the shortcode loaded [bp-site-post];
* Front End editing of posts - the plugin has options to replace the WordPress edit links with links pointing to the Site Post editor.
* Supports BP Post Status post statuses - Group, Members, Friends and Follow posts.
* Various elements of the post structure are optional.
* Where applicable, certain aspects of the post can be enforced, for example you can enforce unique post titles across the site.
* Allows guest publishing without a login.
* Posts can be from all site members or just those with edit_posts capability.
* Post Format, Categories and tags can be selected.
* Media upload for image insertion plus other related third party plugins for all site members, not just thse with 'upload_files' capability.
* Post categories can be restricted to a subset of the full category list.
* The first inserted image in the post can be automatically set as the featured image.
* Post structure is customizable and enforceable.
* Post widget with the same features as the shortcode.
* Hide the WP Toolbar, or just the New Content and Edit menu items
* Replace WP Edit Post link with links to the Site Post editor.
* Supports Shortcodes Ultimate button for more front end posting options (media upload capability required).
* Provides My Posts page listing the authors posts for visitors to the profile, if the author views the profile, draft posts will be displayed with additional post information.
* Provides a Pending Posts page for users to view and edit their submitted posts.
* Provides a Moderation page to users who can edit others posts - Editors.

After installing and activating the plugin you will need to add the [bp-site-post] shortcode to the page that will host the editor. This adds a responsive form to the front end.

Please note: This plugin is not compatible with Gutenberg edited posts; Though it's possible to completely separate front end edited posts from those created in the back end.

Note that whilst it will work standalone it has been designed to work best with BP Post Status. It also needs BuddyPress.

== Installation ==

1. Unzip bp-site-post.zip
2. Upload all the files to the `/wp-content/plugins/bp-site-post` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Customize the plugin. To do so click `BP Site Post` in the WordPress `Settings` menu.
5. Include the shortcode [bpsp-site-post] in the post or page were you want to display the form.

== Frequently Asked Questions ==

No questions asked so far.

== Screenshots ==

1. screenshot-1.jpg BP Site Post front end editor.
2. screenshot-1.jpg BP Site Post Settings.

== Changelog ==

= 1.8.0 =

* 29/07/2024

* Update: Corrected depricated functions and updated escating and sanitization to new standards.

= 1.7.6 =

* 01/01/2021

* Fix: Corrected incorrect variable name

= 1.7.5 =

* 17/05/2019

* Fix: Corrected variable not found error when HTTP_REFERER not set.

= 1.7.4 =

* 14/05/2019

* Fix: Improved capability checks for Add Media.

= 1.7.3 =

* 14/05/2019

* Fix: Corrected 'You do not have permission' error for media upload.

= 1.7.2 =

* 13/05/2019

* Fix: Corrected variable not found error in bp-members.php.

= 1.7.1 =

* 13/05/2019

* Fix: Corrected variable not found error in bp-members.php.

= 1.7.0 =

* 13/05/2019

* New: Added ability to allow media upload for subscribers.

= 1.6.5 =

* 12/05/2019

* Fix: Further updates for allowing all users to post.

= 1.6.5 =

* 11/05/2019

* Fix: Corrected undefined variable notice for restricted categories when non set.

= 1.6.4 =

* 10/05/2019

* Fix: BP Features now work when all site users are allowed to post and the user is subscriber.

= 1.6.3 =

* 26/04/2019

* Fix: Corrected styling error.

= 1.6.2 =

* 26/04/2019

* Fix: Corrected error causing hidden groups to not be displayed.

= 1.6.1 =

* 11/04/2019

* New: Added French translations, thanks to Bruno Verrier.

= 1.6.0 =

* 08/04/2019

* New: Added the ability to restrict the categories displayed.

= 1.5.0 =

* 07/04/2019

* New: Added ability for only edit_posts users to create posts.

= 1.4.1 =

* 17/03/2019

* Fix: Corrected error causing some options not being recognised.

= 1.4.0 =

* 17/03/2019

* New: Added Unique Title Check.
* Fix: Corrected call to undefined function error.

* Fix: corrected error causing group_post_pending not to save the group_id.

= 1.3.4 =

* 12/03/2019

* New: Auto-set featured image now adds any first image found as featured.
* Fix: Corrected incorrect email message for pending posts.
* Fix: Auto-set featured image now accepts all images added via Add Media.

= 1.3.3 =

* 11/03/2019

* Fix: refactored bp_members.php.

= 1.3.2 =

* 11/03/2019

* Fix: Corrected error loading myposts.php in standalone mode.

= 1.3.1 =

* 11/03/2019

* Fix: Corrected toolbar menu loading error with BP Post Status loaded.

= 1.3.0 =

* 11/03/2019

* New: Added Moderation posts tab for Editors with posts to approve and publish.
* New: Added My Posts and Pending Posts pages to the standalone instance.
* New: Finalized correct behavior of post counts depending on who is viewing.

= 1.2.4 =

* 10/03/2019

* New: Compatibility with BP Post Status profile Page Pending Posts item for editors.
* Fix: Corrected my posts menu link url.
* Fix: Corrected error caused by BP Profile nav trying to load in admin view.

= 1.2.3 =

* 09/03/2019

* Fix: Corrected display of My Posts tab when the user has no posts.
* New: Added post count to My Posts tab link.
* New: updated emails to distinguish between new posts and updates.

= 1.2.2 =

* 01/03/2019

* Fix: added bpps_the_excerpt and bpps_create_summary to the edit link action.
* New: Admins can now choose to automatically add the first uploaded image as featured.
* Fix: Refactored Site Post settings routtines to prevent not found variable errors.

= 1.2.0 =

* 28/02/2019

* New: Added support for Shortcodes Ultimate.
* New: Added setting to remove WP logo from the toolbar.
* New: Added setting to remove Comments link from the toolbar.
* New: Added setting to deny subscribers and contributors access to the admin area.
* New: Added setting to remove the site-name menu item from the WP toolbar ( link to the backend );

= 1.1.0 =

* 28/02/2019

* New: Added ability to hide the toolbar New Content menu.
* New: Added ability to hide the toolbar Edit Post link.

= 1.0.0 =

* Initial release

== Upgrade Notice ==

Nothing yet.