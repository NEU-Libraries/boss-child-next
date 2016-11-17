<?php
global $rtl, $current_user;
$header_style = boss_get_option('boss_header');
$boxed = boss_get_option( 'boss_layout_style' );

if ( class_exists( 'Humanities_Commons' ) && ! empty( $current_user ) ) {
	$shib_login_host = get_user_meta( $current_user->ID, 'shib_login_host', true );

	if  ( ! empty( $shib_login_host ) ) {
		$user_society_slug = preg_replace( '/\.?' . Humanities_Commons::$main_network->domain . '/', '', $shib_login_host );

		$current_society_name = ( empty( Humanities_Commons::$society_id ) ? 'HC' : strtoupper( Humanities_Commons::$society_id ) );

		$user_society_name = ( empty( $user_society_slug ) ? 'HC' : strtoupper( $user_society_slug ) );

		if ( $current_society_name !== $user_society_name ) {
			$back_to_network_link = sprintf(
				'<a href="%s">Return to %s</a>',
				'https://' . $shib_login_host,
				( ( $user_society_name === 'HC' ) ? 'Humanities' : $user_society_name ) . ' Commons'
			);
		}
	}
}

?>

<?php if( '1' == $header_style ) { ?>
<div class="middle-col">
<?php } ?>
	<?php
    $buddypanel_menu = '';
	if ( $boxed == 'boxed' ) {
		// <!-- Custom menu -->
		$buddypanel_menu = wp_nav_menu( array(
			'theme_location' => 'left-panel-menu',
			'items_wrap'	 => '%3$s',
			'fallback_cb'	 => '',
			'container'		 => false,
			'echo'			 => false,
			'walker'		 => new BuddybossWalker
		) );
	}

	$titlebar_menu = wp_nav_menu( array(
		'theme_location' => 'header-menu',
		'items_wrap'	 => '%3$s',
		'fallback_cb'	 => '',
		'echo'			 => false,
		'container'		 => false,
		'walker'		 => new BuddybossWalker
	) );

	if ( !empty( $buddypanel_menu ) || !empty( $titlebar_menu ) ): ?>
		<!-- Navigation -->
		<div class="header-navigation">
			<div id="header-menu">
				<ul>
					<?php echo $buddypanel_menu . $titlebar_menu; ?>
				</ul>
			</div>
			<a href="#" class="responsive_btn"><i class="fa fa-align-justify"></i></a>
		</div>
    <?php else: ?>
        <div class="header-navigation">
					<ul>
<li>
					<p><?php echo ( ! empty( $back_to_network_link ) ) ? $back_to_network_link : '' ?></p>
</li>
					</ul>
        </div>
    <?php endif; ?>

    <?php if( '2' == $header_style ): ?>

    <div id="titlebar-search">
    <?php
    get_template_part( 'searchform', 'header' );
    ?>
        <a href="#" id="search-open" class="header-button" title="<?php _e( 'Search', 'boss' ); ?>"><i class="fa fa-search"></i></a>
    </div><!-- #titlebar-search-->

    <?php else: ?>

	<?php if ( $boxed == 'boxed' ) { ?>
		<!-- search form -->
		<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="text" value="" name="s" id="s" placeholder="<?php _e( 'Type to search...', 'boss' ); ?>">
			<button type="submit" id="searchsubmit"><i class="fa fa-search"></i></button>
		</form>
    <?php } ?>

    <?php endif; ?>

<?php if( '1' == $header_style ) { ?>
</div><!--.middle-col-->
<?php } ?>
