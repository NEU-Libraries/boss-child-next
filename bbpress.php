<?php
/**
 * The template for displaying bbPress content.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

get_header(); 

?>
<?php do_action( 'bp_before_group_header' ); ?>
<div class="bb-cover-photo no-photo"></div>
<div class="page-right-sidebar group-single search-results-page">
<div id="buddypress">


<?php if( bbp_is_search() ): 
?>
    <!-- if widgets are loaded for any BuddyPress component, display the BuddyPress sidebar -->

<?php
$group_slug = $_GET['bbp_search_group_slug']; 
$forum_id = $_GET['bbp_search_forum_id'];

$args = array(
              'slug' => $group_slug,
              'type' => 'single-group'
        );
	
?>

<?php do_action( 'bp_before_group_home_content' ); ?>
<?php
     if ( bp_has_groups($args) ) : while ( bp_groups() ) : bp_the_group(); 

?>
<div id="item-header" role="complementary">                        
   <?php bp_get_template_part( 'groups/single/group-header' );  ?>
</div>
<div id="primary search-site-content" class="site-content" style="height:100%"> <!-- moved from top -->

                   <div id="content" role="main"> <!-- moved from top -->
	                   
                        <div class="below-cover-photo">
                        
                            <div id="group-description">
                                <?php bp_group_description(); ?>
                            </div>

                        </div>
                        
                       <div id="item-nav"> <!-- movwed inside #primary-->
                            <div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
                                    <ul>
 <li id="home-groups-li">
<a id="home" href=<?php echo "/groups/".$group_slug."/" ?>>Activity</a></li>

<li id="nav-forum-groups-li">
<a id="nav-forum" href=<?php echo "/groups/".$group_slug."/forum/"?>>Discussion</a>
</li>

<li id="nav-events-groups-li">
<a id="nav-events" href=<?php echo "/groups/".$group_slug."/events/"?>>Events</a>
</li>

<li id="deposits-groups-li">
<a id="deposits" href=<?php echo "/groups/".$group_slug."/deposits/"?>>From <em>CORE</em></a>
</li>

<li id="nav-docs-groups-li"><a id="nav-docs" href=<?php echo "/groups/".$group_slug."/docs/"?>>Docs</a></li>

<li id="nav-documents-groups-li"><a id="nav-documents" href=<?php echo "/groups/".$group_slug."/documents/"?>>Files </a></li>

<li id="members-groups-li"><a id="members" href=<?php echo "/groups/".$group_slug."/members/"?>>Members</a></li>

<li id="nav-notifications-groups-li"><a id="nav-notifications" href=<?php echo "/groups/".$group_slug."/notifications/"?>>Email Options</a></li>
                                    


</ul>
                            </div>
                        </div><!-- #item-nav -->
                        
  <div class="bbp-on-search-form">
            <?php //bbp_get_template_part( 'form', 'search' ); ?>


<form role="search" method="get" id="bbp-search-form" action="<?php bbp_search_url(); ?>">
        <div>
                <label class="screen-reader-text hidden" for="bbp_search"><?php _e( 'Search for:', 'bbpress' ); ?></label>
                <!--<input type="hidden" name="action" value="bbp-search-request" />-->
                <input tabindex="<?php bbp_tab_index(); ?>" type="text" value="<?php echo esc_attr( bbp_get_search_terms() ); ?>" name="bbp_search" id="bbp_search" />
                
                <?php if( $forum_id ): ?>
                    <input type="hidden" name="bbp_search_forum_id" value="<?php echo $forum_id; ?>" />
                <?php endif; ?>
                 
                <?php if( $group_slug ): ?>
                    <input type="hidden" name="bbp_search_group_slug" value="<?php echo $group_slug; ?>" />
                <?php endif; ?> 
     
                <input tabindex="<?php bbp_tab_index(); ?>" class="button" type="submit" id="bbp_search_submit" value="<?php esc_attr_e( 'Search', 'bbpress' ); ?>" />

        
       </div>
</form>


  
        </div>

                    </div><!-- #content -->

<?php while ( have_posts() ) : the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                                <header class="forum-header page-header">
                                    <h1 class="entry-title main-title search-title-results"><?php the_title(); ?></h1>
                                </header><!-- .page-header -->

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
                </div><!-- #primary -->

<!-- Check if BuddyPress is activated -->

		
		    <?php 
            global $learner;
            $group_status = groups_get_groupmeta( bp_get_group_id(), 'bp_course_attached', true ); 
            ?>
		    
				<div id="secondary" class="widget-area" role="complementary">
				                    
                <?php if($group_status && $learner): ?> 
                     <?php dynamic_sidebar( 'group' ); ?> 
                <?php else: ?> 
                
				    <div class="secondary-inner">
                        <a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>" class="group-header-avatar">

                            <?php bp_group_avatar('type=full&width=300&height=300'); ?>

                        </a>
                        <div id="group-description">
                            <h3><?php _e("Group Info",'boss'); ?></h3>
                            <?php bp_group_description(); ?>
                        </div>

                        <div id="item-actions">

                            <?php if ( bp_group_is_visible() ) : ?>

                            <h3><?php _e( 'Group Admins', 'boss' ); ?></h3>

                            <?php bp_group_list_admins();

                            do_action( 'bp_after_group_menu_admins' );

                            if ( bp_group_has_moderators() ) :
                                do_action( 'bp_before_group_menu_mods' ); ?>

                                <h3><?php _e( 'Group Mods' , 'boss' ); ?></h3>

                                <?php bp_group_list_mods();

                                do_action( 'bp_after_group_menu_mods' );

                            endif;

                            endif; ?>

                        </div><!-- #item-actions -->                           
                        <?php dynamic_sidebar( 'group' ); ?> 
                          
                    </div><!-- .secondary-inner -->
<?php endif; ?>				</div><!-- #secondary -->	
		
		


<?php endwhile; endif; ?>

</div>
</div>
<?php else : ?>

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

                                <header class="forum-header page-header">
                                    <h1 class="entry-title main-title"><?php the_title(); ?></h1>
                                </header><!-- .page-header -->

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
<?php endif; ?>
<?php get_footer(); ?>
