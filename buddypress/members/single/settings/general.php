<?php
/**
 * BuddyPress - Members Single Profile
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/settings/profile.php */
do_action( 'bp_before_member_settings_template' ); ?>

<h4>Current Memberships</h4>
<br />
<ul>
<?php $memberships = bp_get_member_type( bp_displayed_user_id(), false );
	foreach ( $memberships as $membership ) {
		if ( 'beta' !== $membership ) {
			echo '<li>' . strtoupper( $membership ) . '</li>';
		}
	} ?>
</ul>
<?php if ( is_user_logged_in() && bp_loggedin_user_id() === bp_displayed_user_id() ) { ?>
<br />
<?php
	global $comanage_api;
	$user = wp_get_current_user();
	$header_printed = false;
	$comanage_roles = $comanage_api->get_person_roles( $user->data->user_login );
	foreach( $comanage_roles as $comanage_key => $comanage_role ) {
		if ( ! in_array( strtolower( $comanage_key ), $memberships ) ) {
			if ( ! $header_printed ) {
				$header_printed = true; ?>
<h4>Other Memberships</h4>
<br />
			<?php }
			if ( 'Expired' == $comanage_role['status'] ) {
					echo "<p>",$comanage_key, " membership status ", $comanage_role['status'], " effective ",
						$comanage_role['valid_through'], "</P>";
				} else {
					echo "<p>",$comanage_key, " membership status ", $comanage_role['status'], " effective from ",
						$comanage_role['valid_from'], " through ", $comanage_role['valid_through'], "</P>";
				}
		}
	}
?>
<br />
<p>Missing a membership? Let us know <a href="mailto:hello@hcommons.org">here</a>.</p>
<?php } ?>
<br />
<h4>Current Log-in Methods</h4>
<br />
<ul>
<?php $login_methods = Humanities_Commons::hcommons_get_user_login_methods( bp_displayed_user_id() );
	foreach ( $login_methods as $login_method ) {
		echo '<li>' . strtoupper( $login_method ) . '</li>';
	} ?>
</ul>
<br />
<?php if ( is_user_logged_in() && bp_loggedin_user_id() === bp_displayed_user_id() ) { ?>
<?php	$registry_url = constant( 'REGISTRY_SERVER_URL' ) . '/Shibboleth.sso/Login?SAMLDS=1';
	$discovery_url = urlencode( constant( 'REGISTRY_SERVER_URL' ) . '/discovery_service_registry_only/index.php' );
	$society_account_link_constant = strtoupper( Humanities_Commons::$society_id ) . '_ACCOUNT_LINK_URL';
	$target_url = urlencode( constant( $society_account_link_constant ) );
	$formatted_provider = false;
	$entity_id = urlencode( Humanities_Commons::hcommons_get_identity_provider( $formatted_provider ) );
	$society_name = ( 'hc' === Humanities_Commons::$society_id ) ? 'Humanities Commons' : strtoupper( Humanities_Commons::$society_id ) . ' Commons';
	echo sprintf( '<p><a href="%s&discoveryURL=%s&target=%s&entityID=%s">Link another log-in method</a> to your %s Account</p>', $registry_url, $discovery_url, $target_url, $entity_id, $society_name );


} ?>
<?php if ( is_user_logged_in() && bp_loggedin_user_id() === bp_displayed_user_id() ) {

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	if( isset( $_POST['primary_email'] ) && ! empty( $_POST['primary_email'] ) ) {

	    $user->user_email = $_POST['primary_email'];
	    wp_update_user( ['ID' => $user->ID, 'user_email' => esc_attr( $_POST['primary_email'] ) ] );
	
	}

}

?>

<form method="post" class="no-ajax standard-form" id="settings-form-general" action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/general'; ?>">

<br />
<h4>Currently Registered E-mails</h4>
<br />
<?php

$shib_email = Humanities_Commons::hcommons_shib_email( $user );

//lets check if $shib_email is an array to loop through, 
//otherwise the user does not have multiple emails to select from
if( is_array( $shib_email ) ) : ?>

<p>Use selected email as primary email:</p>
<ul class="email_selection">
<?php

	foreach( $shib_email as $email ) : 

	//lets check to see if the current email is in the list of emails from shib
		if( $email == $user->user_email ) : ?>
		<li> <input type="radio" name="primary_email" value="<?php echo $email; ?>" checked /><?php echo $email; ?> </li>
		<?php else : ?>
		<li> <input type="radio" name="primary_email" value="<?php echo $email; ?>" /><?php echo $email; ?> </li>
		
<?php 	endif;
	endforeach;
?>
</ul>
<!--
	<?php if ( !is_super_admin() ) : ?>

		<label for="pwd"><?php _e( 'Current Password <span>(required to update email or change current password)</span>', 'buddypress' ); ?></label>
		<input type="password" name="pwd" id="pwd" size="16" value="" class="settings-input small" <?php bp_form_field_attributes( 'password' ); ?>/> &nbsp;<a href="<?php echo wp_lostpassword_url(); ?>" title="<?php esc_attr_e( 'Password Lost and Found', 'buddypress' ); ?>"><?php _e( 'Lost your password?', 'buddypress' ); ?></a>

	<?php endif; ?>

	<label for="email"><?php _e( 'Account Email', 'buddypress' ); ?></label>
	<input type="email" name="email" id="email" value="<?php echo bp_get_displayed_user_email(); ?>" class="settings-input" <?php bp_form_field_attributes( 'email' ); ?>/>

	<label for="pass1"><?php _e( 'Change Password <span>(leave blank for no change)</span>', 'buddypress' ); ?></label>
	<input type="password" name="pass1" id="pass1" size="16" value="" class="settings-input small password-entry" <?php bp_form_field_attributes( 'password' ); ?>/> &nbsp;<?php _e( 'New Password', 'buddypress' ); ?><br />
	<div id="pass-strength-result"></div>
	<label for="pass2" class="bp-screen-reader-text"><?php
		/* translators: accessibility text */
		_e( 'Repeat New Password', 'buddypress' );
	?></label>
	<input type="password" name="pass2" id="pass2" size="16" value="" class="settings-input small password-entry-confirm" <?php bp_form_field_attributes( 'password' ); ?>/> &nbsp;<?php _e( 'Repeat New Password', 'buddypress' ); ?>

	<?php

	/**
	 * Fires before the display of the submit button for user general settings saving.
	 *
	 * @since 1.5.0
	 */
	//do_action( 'bp_core_general_settings_before_submit' ); ?>
-->
	<div class="submit">
		<input type="submit" name="submit" class="no-ajax" value="<?php esc_attr_e( 'Save Changes', 'buddypress' ); ?>" />
	</div>

	<?php

	/**
	 * Fires after the display of the submit button for user general settings saving.
	 *
	 * @since 1.5.0
	 */
	//do_action( 'bp_core_general_settings_after_submit' ); ?>

	<?php wp_nonce_field( 'bp_settings_general' );
//wp_nonce_field( 'new_settings_general' );
 ?>

</form>

<?php else : ?> 
<p><?php echo $user->user_email; ?></p>
<?php endif; //end is_array() check
} //end current form ?>

<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/settings/profile.php */
do_action( 'bp_after_member_settings_template' ); ?>

