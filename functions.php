<?php
/**
 * @package Boss Child Theme
 * The parent theme functions are located at /boss/buddyboss-inc/theme-functions.php
 * Add your own functions in this file.
 */

if ( ! defined( 'BP_AVATAR_THUMB_WIDTH' ) ) define ( 'BP_AVATAR_THUMB_WIDTH', 150 );
if ( ! defined( 'BP_AVATAR_THUMB_HEIGHT' ) ) define ( 'BP_AVATAR_THUMB_HEIGHT', 150 );

/**
 * Sets up theme defaults
 *
 * @since Boss Child Theme 1.0.0
 */
function boss_child_theme_setup() {

	/**
	 * Makes child theme available for translation.
	 * Translations can be added into the /languages/ directory.
	 * Read more at: http://www.buddyboss.com/tutorials/language-translations/
	 */

	// Translate text from the PARENT theme.
	load_theme_textdomain( 'boss', get_stylesheet_directory() . '/languages' );

	// Translate text from the CHILD theme only.
	// Change 'boss' instances in all child theme files to 'boss_child_theme'.
	// load_theme_textdomain( 'boss_child_theme', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'boss_child_theme_setup' );

/**
 * removes dynamic css (set in boss options admin page) from page output entirely,
 * since we include all necessary rules ourselves in this child theme
 */
function boss_child_theme_remove_dynamic_css( $reduxFramework ) {
	// remove both instances of dynamic css: one from redux, one from boss
	remove_action( 'wp_head', 'boss_generate_option_css', 99 );
	remove_action( 'wp_head', array( $reduxFramework, '_output_css' ), 150 );
}
add_action( 'redux/loaded', 'boss_child_theme_remove_dynamic_css' );

/**
 * Enqueues styles for child theme front-end.
 */
function boss_child_theme_enqueue_style() {
	if (
		class_exists( 'Humanities_Commons' ) &&
		! empty( Humanities_Commons::$society_id ) &&
		file_exists( get_stylesheet_directory() . '/css/' . Humanities_Commons::$society_id . '.css' )
	) {
		wp_enqueue_style( 'boss-child-custom', get_stylesheet_directory_uri() . '/css/' . Humanities_Commons::$society_id . '.css' );
	}

}
// priority 200 to ensure this loads after redux which uses 150
add_action( 'wp_enqueue_scripts', 'boss_child_theme_enqueue_style', 200 );


/**
 * Enqueues scripts for child theme front-end.
 */
function boss_child_theme_enqueue_script() {
	wp_enqueue_script( 'boss-child-custom', get_stylesheet_directory_uri() . '/js/boss-child.js' );
}
// priority 200 to ensure this loads after redux which uses 150
add_action( 'wp_enqueue_scripts', 'boss_child_theme_enqueue_script' );

function boss_child_theme_enqueue_typekit() {
	wp_enqueue_script( 'typekit', '//use.typekit.net/bgx6tpq.js', array(), null );
	wp_add_inline_script( 'typekit', 'try{Typekit.load();}catch(e){};' );
}
add_action( 'wp_enqueue_scripts', 'boss_child_theme_enqueue_typekit' );

/**
 * some thumbnails have been generated with small dimensions due to
 * BP_AVATAR_THUMB_WIDTH being too small at the time. this is a temporary
 * workaround to prevent artifacts/blurriness where those thumbnails appear by
 * using the full avatar rather than the thumb.
 *
 * TODO once bad thumbnails have been replaced/removed, this filter should be
 * removed to improve performance.
 */
function hcommons_filter_bp_get_group_invite_user_avatar() {
	global $invites_template;
	return $invites_template->invite->user->avatar; // rather than avatar_thumb
}
add_filter( 'bp_get_group_invite_user_avatar', 'hcommons_filter_bp_get_group_invite_user_avatar' );

/**
 * affects boss mobile right-hand main/top user menu
 */
function boss_child_change_profile_edit_to_view_in_adminbar() {
	global $wp_admin_bar;

	if ( is_user_logged_in() ) {
		// the item which has the user's name/avatar as title and links to "edit"
		$user_info_clone = $wp_admin_bar->get_node( 'user-info' );
		// the item which has "Profile" as title and links to "view"
		$my_account_xprofile_clone = $wp_admin_bar->get_node( 'my-account-xprofile' );

		// use "view" url for the name/avatar item
		$user_info_clone->href = $my_account_xprofile_clone->href;
		$wp_admin_bar->add_menu( $user_info_clone );

		// remove the second, now redundant, item
		$wp_admin_bar->remove_menu( 'edit-profile' );
	}
}
// priority 1000 to override boss buddyboss_strip_unnecessary_admin_bar_nodes()
add_action( 'admin_bar_menu', 'boss_child_change_profile_edit_to_view_in_adminbar', 1000 );


/**
 * Handles ajax for the boss-child theme
 * @return void
 */
function boss_child_theme_ajax() {

	//this is for settings-general ajax
	$user = wp_get_current_user();
	$nonce = wp_create_nonce('settings_general_nonce');
	wp_localize_script( 'boss-child-custom', 'settings_general_req', [ 'user' => $user, 'nonce' => $nonce ], ['jquery'] );

}

add_action('wp_enqueue_scripts', 'boss_child_theme_ajax');