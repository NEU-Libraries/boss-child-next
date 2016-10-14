<?php
/**
 * Template Name: Not A Member Template
 *
 * Description: Handle logins without necessary privs.
 *
 * @since HCommons
 */
get_header(); ?>

<div class="page-full-width">

        <div id="primary" class="site-content">
                <div id="content" role="main">

                        <?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<header class="entry-header <?php if(is_search()){ echo 'page-header'; }?>">
			<h1 class="entry-title <?php if(is_search()){ echo 'main-title'; }?>"><?php the_title(); ?></h1>
		</header>

		<div class="entry-content">
			<?php the_content(); ?>

	<?php global $humanities_commons;
	$memberships = $humanities_commons::hcommons_get_user_memberships();
	if ( ! empty( $memberships ) ) { ?>
		<span>You are not currently a member of this site. You can access the public content of this site as a visitor. Here is a list of your current society memberships:</span>
		</p >
		<h4>Current Society Memberships</h4>
		<p />
		<ul>
		<?php foreach ( $memberships['societies'] as $membership ) {
			echo '		<li>' . strtoupper( $membership ) . '</li>';
		} ?>
		</ul>
		<p />
	<?php } else {
		$identity_provider = $humanities_commons::hcommons_get_identity_provider();
		echo "You have chosen a login method (" . $identity_provider . ") that is not linked to any account in Humanities Commons.";
	} ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->

	</article><!-- #post -->

                        <?php endwhile; // end of the loop. ?>

                </div><!-- #content -->
        </div><!-- #primary -->

</div><!-- .page-full-width -->
<?php get_footer(); ?>

