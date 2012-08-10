<?php
wp_enqueue_style( 'rolassepuluh-style', get_template_directory_uri() . '/css/screen.css', false, '', 'all' );
if ( ! isset( $content_width ) )
	$content_width = 625;
function rolassepuluh_sidebars() {
	register_sidebar(array('name'=>'Top Sidebar 300px','before_widget' => '<div class="sidebox">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>',));
	register_sidebar(array('name'=>'Middle Sidebar Left 160px','before_widget' => '<div class="sidebox">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>',));
	register_sidebar(array('name'=>'Middle Sidebar Right 120px','before_widget' => '<div class="sidebox">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>',));
	register_sidebar(array('name'=>'Bottom Sidebar 300px','before_widget' => '<div class="sidebox">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>',));
	register_sidebar(array('name'=>'Footer Widgets','before_widget' => '<div class="footerbox">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>',));
}
add_action( 'widgets_init', 'rolassepuluh_sidebars' );
if ( function_exists( 'register_nav_menu' ) ) {register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'rolassepuluh' ),
	) );
	}
add_theme_support('post-thumbnails'); set_post_thumbnail_size(150, 150, true);
function will_paginate() {
	global $wp_query;
	if ( !is_singular() ) { $max_num_pages = $wp_query->max_num_pages;
	if ( $max_num_pages > 1 ) { return true; }} return false;
}
function excerpt($limit) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt)>=$limit) {array_pop($excerpt); $excerpt = implode(" ",$excerpt).'...'; }
	else {$excerpt = implode(" ",$excerpt); } $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt); return $excerpt;
}
function content($limit) {
	$content = explode(' ', get_the_content(), $limit);
	if (count($content)>=$limit) { array_pop($content); $content = implode(" ",$content).'...';}
	else { $content = implode(" ",$content); }	$content = preg_replace('/\[.+\]/','', $content); $content = apply_filters('the_content', $content); $content = str_replace(']]>', ']]&gt;', $content);return $content;
}
function the_breadcrumb() 
{
	if (!is_home())
	{
	echo '<a href="';
	echo home_url();
	echo '">';
	echo "Home";
	echo "</a> &raquo; ";
	if (is_single()) {the_category(', '); {echo " &raquo; "; the_title();}}
	elseif (is_category()) {echo single_cat_title();}
	elseif (is_page()) {echo the_title();}
	elseif (is_tag()) {echo single_tag_title();}
	elseif (is_day()) {echo " Archive for "; the_time('F jS, Y');}
	elseif (is_month()) {echo " Archive for "; the_time('F, Y');}
	elseif (is_year()) {echo "Archive for "; the_time('Y');}
	elseif (is_author()) {echo "Author Archive" ;}
	elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "Blog Archives";}
	elseif (is_search()) {echo "Search Results";}
	}
}
add_theme_support( 'automatic-feed-links' );
add_custom_background();
add_editor_style('rolassepuluh-editor-style.css');
define('HEADER_TEXTCOLOR', '000000');
define('HEADER_IMAGE', '%s/images/default-header.jpg');
define('HEADER_IMAGE_WIDTH', 950);
define('HEADER_IMAGE_HEIGHT', 100);
define('NO_HEADER_TEXT', true );
function header_style() {
    ?><style type="text/css">
        #header {
            background: url(<?php header_image(); ?>);
        }
		#header a {
			color: #<?php header_textcolor(); ?>;
		}
    </style><?php
}
function admin_header_style() {
    ?><style type="text/css">
        #heading {
            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
            background: no-repeat;
        }
    </style><?php
}
add_custom_image_header('header_style', 'admin_header_style');
if ( ! function_exists( 'rolassepuluh_comment' ) ) :
function rolassepuluh_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">says:</span>', 'rolassepuluh' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'rolassepuluh' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'rolassepuluh' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'rolassepuluh' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'rolassepuluh' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'rolassepuluh' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;
?>