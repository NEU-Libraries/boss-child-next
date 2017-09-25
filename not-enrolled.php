<?php
/**
 * Template Name: Not Enrolled Template
 *
 * Description: Handle logins that are not linked to any account in HC
 *
 * @since HCommons
 */
        $memberships = Humanities_Commons::hcommons_get_user_memberships();
        if ( ! empty( $memberships ) ) {
                global $comanage_api;
                $comanage_roles = $comanage_api->get_person_roles( Humanities_Commons::hcommons_get_session_username(), Humanities_Commons::$society_id );
                $inactive_role = false;
                foreach( $comanage_roles as $comanage_key => $comanage_role ) {
                        if ( $comanage_key == strtoupper( Humanities_Commons::$society_id ) && 'Active' != $comanage_role['status'] ) {
                                $inactive_role = true;
                        }
                }
                if ( $inactive_role ) {
                        wp_redirect( '/inactive-member/' );
                }
        }
//must redirect first

get_header(); ?>

<div class="page-full-width">

        <div id="primary" class="site-content">
                <div id="content" role="main">

                        <?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<?php
		$identity_provider = Humanities_Commons::hcommons_get_identity_provider();
		if ( ! in_array( $identity_provider, array( 'Google', 'Twitter' ) ) ) {
			$id_string = 'ID (' . hcommons_get_session_eppn() . ')';
		} else {
			$id_string = 'ID';
		}
		echo '<h1 class="entry-title">Unknown Login Method and ID</h1>';
		echo 'You have chosen a login method (' . $identity_provider . ') and ' . $id_string . ' that is not linked to an account in Humanities Commons.';
		?>
		<div class="entry-content">
		<?php the_content(); ?>
		</div><!-- .entry-content -->

	</article><!-- #post -->

                        <?php endwhile; // end of the loop. ?>

                </div><!-- #content -->
        </div><!-- #primary -->

</div><!-- .page-full-width -->
<?php get_footer(); ?>

