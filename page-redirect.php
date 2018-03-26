<?php /* Template Name: Page Redirect */

if (function_exists('have_posts') && have_posts())
{
	// Prevent links turning into oembed
	remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );

	while (have_posts())
	{
		the_post();

		ob_start();
		the_content();
		$contents	= ob_get_contents();
		ob_end_clean();

		// grab the 'naked' link
		$link = trim(strip_tags($contents));

		if( ! preg_match('%^(http|https)://%', $link) ) {
			$host	= $_SERVER['HTTP_HOST'];
			$dir	= dirname( $_SERVER['PHP_SELF'] );
			$link	= "https://$host$dir/$link";
		}

		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $link");
		exit;
	}
}
?>