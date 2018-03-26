<?php
/**
 * Template Name: Page Redirect
 *
 * @package Boss_Child
 */

if ( function_exists( 'have_posts' ) && have_posts() ) {
	// Prevent links turning into oembed.
	remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );

	while ( have_posts() ) {
		the_post();

		ob_start();
		the_content();
		$contents = ob_get_contents();
		ob_end_clean();

		// Grab the 'raw' link.
		$link = trim( strip_tags( $contents ) );

		if ( ! preg_match( '%^(http|https)://%', $link ) ) {

			if ( isset( $_SERVER['HTTP_HOST'] ) ) {
				$host = sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) );
			}

			if ( isset( $_SERVER['PHP_SELF'] ) ) {
				$dir = dirname( sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) );
			}

			$link = sprintf( "https://%1$s%2$s/%3$s", $host, $dir, $link );
		}

		header( 'HTTP/1.1 301 Moved Permanently' );
		header( "Location: $link" );
		exit;
	}
}
