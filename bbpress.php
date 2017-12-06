<?php
/**
 * The template for displaying bbPress content.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

get_header(); ?>

	<!-- if widgets are loaded in the Forums sidebar, display it -->	
	<?php if ( is_active_sidebar('forums') ) : ?>		
		<div class="page-right-sidebar">

	<!-- if not, hide the sidebar -->
	<?php else: ?>
		<div class="page-full-width">
	<?php endif; ?>


			<!-- bbPress template content -->
			<div id="primary" class="site-content">
			
				<div id="content" role="main">

					<?php while ( have_posts() ) : the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                
                                <?php //if( !bbp_is_search() ): ?>
 
                                    <header class="forum-header page-header">
                                        <h1 class="entry-title main-title"><?php the_title(); ?></h1>
                                    </header><!-- .page-header -->

                                <?php //endif; ?>
                                  
                                <?php if( bbp_is_search() ):
					
                                        if ( !empty( $_GET['bbp_search_group_id'] ) ) {
                                          
                                            $group_id = $_GET['bbp_search_group_id'];
                                            $group = groups_get_group( $group_id  );

                                           //var_dump($group->name);
                                            $group_name = $group->name; 
           do_action( 'bp_before_group_header' ); 
					    //output cover photo.
					    if ( boss_get_option( 'boss_layout_style' ) != 'boxed' && boss_get_option('boss_cover_group') ) {
				 	        echo buddyboss_cover_photo( "group", $group_id );
                                            }
                                        
                          ?>
               <div id="item-header-cover" class="table">

	<div class="table-cell">

		<div id="group-name">
			<h1 class="main-title"><?php
				
				if ( ! empty( $group_name ) ) {

					//Get truncated string with long width group title
					if ( wp_is_mobile() ) {
						echo mb_strimwidth( $group_name, 0, 35, "...");
					} else {
						echo mb_strimwidth( $group_name, 0, 55, "...");
					}
				}
				?></h1>
			<span class="activity"><?php printf( __( 'active %s', 'boss' ), bp_get_group_last_active() ); ?></span>
		</div>


		<div id="item-header-avatar-mobile">
			<a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>">

				<?php bp_group_avatar(); ?>

			</a>
		</div><!-- #item-header-avatar -->


		<div id="item-header-content">
			<ul class="group-info">
				<li class="group-type">
					<p><?php echo ucfirst( trim( str_replace( 'group', '', strtolower( bp_get_group_type() ) ) ) ); ?></p>
					<p class="small"><?php _e( "Group", 'boss' ); ?></p>
				</li>
				<li class="group-members">
					<p>
						<?php
						global $groups_template;
						if ( isset( $groups_template->group->total_member_count ) ) {
							$count = (int) $groups_template->group->total_member_count;
						} else {
							$count = 0;
						}
						echo $count;
						?>
					</p>
					<p class="small"><?php echo _n( 'Member', 'Members', $count, 'boss' ); ?></p>
				</li>

				<?php do_action( 'bb_before_group_header_meta_extra_li' ); ?>
			</ul>

			<?php do_action( 'bp_before_group_header_meta' ); ?>


			<div id="item-buttons" class="group">

				<?php do_action( 'bp_group_header_actions' ); ?>

			</div><!-- #item-buttons -->


		</div><!-- #item-header-content -->

		<div id="item-actions">

			<?php if ( bp_group_is_visible() ) : ?>

				<h3><?php _e( 'Group Admins', 'boss' ); ?></h3>

				<?php
				bp_group_list_admins();

				do_action( 'bp_after_group_menu_admins' );

				if ( bp_group_has_moderators() ) :
					do_action( 'bp_before_group_menu_mods' );
					?>

					<h3><?php _e( 'Group Mods', 'boss' ); ?></h3>

					<?php
					bp_group_list_mods();

					do_action( 'bp_after_group_menu_mods' );

				endif;

			endif;
			?>

		</div><!-- #item-actions -->


	</div>

	<div id="item-header-avatar" class="table-cell">
		<a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>">

			<?php bp_group_avatar(); ?>

		</a>
	</div><!-- #item-header-avatar -->

</div>
<?php
                                         }

				    ?>
                                <?php endif; ?>

                                <div class="entry-content">
                                    <?php the_content(); ?>
                                    <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'boss' ), 'after' => '</div>' ) ); ?>
                                </div><!-- .entry-content -->

                                <footer class="entry-meta">
                                    <?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
                                </footer><!-- .entry-meta -->

                            </article><!-- #post -->
						<?php comments_template( '', true ); ?>
					<?php endwhile; // end of the loop. ?>

				</div><!-- #content -->
			</div><!-- #primary -->

			<?php get_sidebar('bbpress'); ?>


		</div><!-- closing div -->

<?php get_footer(); ?>

