<?php get_header(); ?>

<style type="text/css">

p.res_message {
	width: 50%;
	margin: 20px auto 0 auto;
}

#remind-me-container p {
	margin-bottom: 5px;
}

#remind-me-container input#rm_user_email, #remind-me-container input#rm_username {
	width: 70%;
}

#remind-me-container .res_message {
	width: 65%;
	padding: 10px;
	margin: 0 auto;
	margin-top: 50px;
	font-size: 20px;
}

</style>

<?php if( $_SERVER['REQUEST_METHOD'] == 'POST' &&  wp_verify_nonce($_POST['rm_nonce'], 'remind-me-nonce' ) ) {

	//var_dump( $_POST );

	switch( $_POST['req_method'] ) {
		
		case "email" :
			$user = get_user_by('email', filter_var( $_POST['user_email'], FILTER_SANITIZE_EMAIL ) );
		break;

		case "username" :
			$user = get_user_by( 'login', filter_var( $_POST['username'], FILTER_SANITIZE_STRING ) );
		break;

		default:
			$user = false;

	}

	//echo "<pre>";
	//var_dump( $_POST );
	//echo "user object: <br>";
	//var_dump( $user->data->user_email );

	if( $user !== false ) {

		$user_login_methods = implode( '<br />', Humanities_Commons::hcommons_get_user_login_methods( $user->data->ID ) );
//var_dump( $user_login_methods );
		//var_dump( implode( '<br />', $user_login_methods ) );
		wp_mail( $user->data->user_email, "Your Humanities Commons Login Method Request", "<p>You currently log in to Humanities Commons using: </p> <h3>{$user_login_methods}</h3>", "From: HC <hc@hcommons.org>" );

		echo "<p class='res_message'>If we have this username or e-mail address on file, we will send you a message detailing how you have previously logged in to _Humanities Commons_. Please check your inbox.</p>";
	
	} else {
        	echo "<p class='res_message'>If we have this username or e-mail address on file, we will send you a message detailing how you have previously logged in to _Humanities Commons_. Please check your inbox.</p>";
        }

	//echo "</pre>";

} else {

?>

<script type="text/javascript">

	$(document).ready(function() {
		
		$('#rm_username').hide();
		$('#username_choice').on('click', function() {
			$('#rm_username').toggle();
			$('#rm_user_email').hide();
		});

		$('#email_choice').on('click', function() {
			
			if( ! $('#rm_user_email').is(':visible') )	{
				$('#rm_user_email').toggle();
				$('#rm_username').hide();
			}	

		});
	});

</script>

<div class="page-full-width">
<div id="primary" class="site-content">
<div id="content" role="main">

<?php while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<div class="entry-content">
<?php the_content(); ?>

<div id="remind-me-container">

<p>Send me an e-mail with my login information!</p>

<form action="" id="remindMeForm" method="POST">
	
	<p><input type="radio" id="email_choice" name="req_method" value="email" checked />I'll identify myself with my registered e-mail</p>
	<p><input type="radio" id="username_choice" name="req_method" value="username" />I'll identify myself with my <em>Humanities Commons</em> username</p>

	<input type="email" id="rm_user_email" name="user_email" />
	<input type="text" id="rm_username"  name="username" />

	<input type="submit" value="Submit!" />
        <input type="hidden" name="rm_nonce" value="<?php echo wp_create_nonce('remind-me-nonce'); ?>" />
</form>	

</div><!-- .entry-content -->

<footer class="entry-meta">
         <?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
</footer><!-- .entry-meta -->

</article><!-- #post -->

<?php endwhile; // end of the loop. ?>

</div> <!-- #remind-me-container -->
</div><!-- #content -->
</div><!-- #primary -->
</div><!-- .page-full-width -->
<?php } get_footer(); ?>
