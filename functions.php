<?php
/**
 * @package Boss Child Theme
 * The parent theme functions are located at /boss/buddyboss-inc/theme-functions.php
 * Add your own functions in this file.
 */

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
		wp_enqueue_style( 'boss-child-custom', get_stylesheet_directory_uri() . '/css/' . Humanities_Commons::$society_id . '.css');
	}

}
// priority 200 to ensure this loads after redux which uses 150
add_action( 'wp_enqueue_scripts', 'boss_child_theme_enqueue_style', 200 );


function boss_child_theme_enqueue_typekit() {
	wp_enqueue_script( 'typekit', '//use.typekit.net/bgx6tpq.js', array(), null );
	wp_add_inline_script( 'typekit', 'try{Typekit.load();}catch(e){};' );
}
add_action( 'wp_enqueue_scripts', 'boss_child_theme_enqueue_typekit' );
