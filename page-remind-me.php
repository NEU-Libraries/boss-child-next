<?php get_header(); 


if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

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
		wp_mail( $user->data->user_email, "Your Humanities Commons Login Method Request", "<p>Your current login Methods are: </p> <h3>{$user_login_methods}</h3>", "From: HC <hc@hcommons.org>" );

		echo "<p>If we have this email on file, you will receive a message.</p>";
	
	} else {
        	echo "<p>If we have this email on file, you will receive a message.</p>";
        }

	//echo "</pre>";

}

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

<h3>Remind Me</h3>
<p>Recover my login details</p>

<form action="" id="remindMeForm" method="POST">
	
	<p><input type="radio" id="email_choice" name="req_method" value="email" checked />I want to use my email to recover my login details</p>
	<p><input type="radio" id="username_choice" name="req_method" value="username" />I want to use my username to recover my login details</p>

	<input type="email" id="rm_user_email" name="user_email" />
	<input type="text" id="rm_username"  name="username" />

	<input type="submit" value="Submit!" />
</form>	

<?php

if ( have_posts() ) {
  while ( have_posts() ) {
    
    the_post();
    the_content();

  } // end while
} // end if
?>

<?php get_footer(); ?>
