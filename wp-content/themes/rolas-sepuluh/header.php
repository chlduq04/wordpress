<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php global $page, $paged; wp_title( '|', true, 'right' );	bloginfo( 'name' ); $site_description = get_bloginfo( 'description', 'display' ); if ( $site_description && ( is_home() || is_front_page() ) ) echo " | $site_description"; if ( $paged >= 2 || $page >= 2 ) echo ' | ' . sprintf( __( 'Page %s', 'rolassepuluh' ), max( $paged, $page ) ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?> 
</head>
<body <?php body_class(); ?>>
<div id="container" class="container">
	<div id="header" class="span-24">
		<div class="span-16">
		<?php if(is_home()) { ?>
			<h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
		<?php } else { ?>
			<h2><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h2>
		<?php } ?>
		<h3><?php bloginfo('description'); ?></h3>
		</div>
		<div class="span-8">
		<div class="headerright">
			<?php get_search_form(); ?>
		</div>
		</div>
	<div class="clear"></div>
	</div>
	<div id="access">
		<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
	</div>