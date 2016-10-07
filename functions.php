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
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function boss_child_theme_echo_style_link() {

	if (
		class_exists( 'Humanities_Commons' ) &&
		! empty( Humanities_Commons::$society_id ) &&
		file_exists( get_stylesheet_directory() . '/css/' . Humanities_Commons::$society_id . '.css' )
	) {
		$href = get_stylesheet_directory_uri() . '/css/' . Humanities_Commons::$society_id . '.css';
		echo '<link rel="stylesheet" id="boss-child-custom-css"  href="' . $href . '" type="text/css" media="all" />';
	}

}
// echo <link> after inline dynamic styles rather than enqueueing the usual way,
// so we don't have to resort to !important everywhere
add_action( 'wp_head', 'boss_child_theme_echo_style_link', 151 );


function boss_child_theme_enqueue_typekit() {
	wp_enqueue_script( 'typekit', '//use.typekit.net/bgx6tpq.js', array(), null );
	wp_add_inline_script( 'typekit', 'try{Typekit.load();}catch(e){};' );
}
add_action( 'wp_enqueue_scripts', 'boss_child_theme_enqueue_typekit' );
