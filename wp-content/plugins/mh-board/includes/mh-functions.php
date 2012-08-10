<?php
function mh_media_buttons() {
	// If we're using http and the admin is forced to https, bail.
	if ( ! is_ssl() && ( force_ssl_admin() || get_user_option( 'use_ssl' ) )  ) {
		return;
	}

	include_once( ABSPATH . '/wp-admin/includes/media.php' );
	ob_start();
	do_action( 'media_buttons' );

	// Replace any relative paths to media-upload.php
	echo preg_replace( '/([\'"])media-upload.php/', '${1}' . admin_url( 'media-upload.php' ), ob_get_clean() );
}
function mh_pagenavi( $args = array() ){
	global $wp_query;
	$args['items'] = 5;
	$max_page_num = $wp_query->max_num_pages;
	$current_page_num = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	$befores = $current_page_num - floor( ( $args['items'] - 1 ) / 2 );
	$afters = $current_page_num + ceil( ( $args['items'] - 1 ) / 2 );

	if ( $max_page_num <= $args['items'] ) {
		$start = 1;
		$end = $max_page_num;
	} elseif ( $befores <= 1 ) {
		$start = 1;
		$end = $args['items'];
	} elseif ( $afters >= $max_page_num ) {
		$start = $max_page_num - $args['items'] + 1;
		$end = $max_page_num;
	} else {
		$start = $befores;
		$end = $afters;
	}
	if($start >= $args['items']){
		$previous_num = max( 1, $start - 1 );
?>
		<a href="<?php echo get_pagenum_link();?>" class="pre"><<</a>
		<a href="<?php echo get_pagenum_link( $previous_num );?>" class="pre"><</a>
<?php		
	}
	for ( $i = $start; $i <= $end; $i++ ) {
		if ( $i == $current_page_num ) {
			echo "<strong>{$i}</strong>";
		}else{
			?><a href="<?php echo get_pagenum_link($i);?>"><?php echo $i;?></a><?php
		}
	}	
	if($current_page_num != $max_page_num  && $max_page_num > $args['items']){
		$next_num = min( $max_page_num, $end + 1 );
?>
		<a href="<?php echo get_pagenum_link($next_num);?>" class="next">></a>
        <a href="<?php echo get_pagenum_link( $max_page_num );?>" class="next">>></a>
<?php		
	}
?>
<?php
}
function mh_board_register_default_page(){
	$mh_board_default_pagel = array(
		'Write'=>'[mh_board_write_form]',
		'Edit'=>'[mh_board_edit_form]',
	);
	foreach($mh_board_default_pagel as $post_title => $post_content){
		$args = array(
			'post_title' =>$post_title, 
			'post_status' => 'publish', 
			'post_type' => 'page',
			'post_author' => 1,
			'ping_status' => get_option('default_ping_status'),
			'comment_status' => 'closed',
			'post_content' => $post_content
		);

		if(!get_page_by_title($post_title)){
		  wp_insert_post( $args );
		}
	}
}
add_action( 'init', 'mh_board_register_default_page', 0 );
require_once(dirname(dirname(__FILE__)).'/shortcodes/write_form.php');
require_once(dirname(dirname(__FILE__)).'/shortcodes/edit_form.php');
add_shortcode('mh_board_write_form','mh_board_write');
add_shortcode('mh_board_edit_form','mh_board_edit');
add_action('init','test22');
function test22(){
	
}
add_filter('pre_option_posts_per_page', 'mh_limit_posts_per_page');
function mh_limit_posts_per_page(){
	global $wp_query;
	if ( $wp_query->query_vars['post_type'][0]=='board'){
        return 10;
    }else{
    	$all_options = wp_load_alloptions();
        return $all_options["posts_per_page"]; // default: 5 posts per page
    }
}
function my_custom_posts_per_page( &$q ) {
    if ( $q->is_archive ) // any archive
    if($q->query_vars['post_type'] == 'board'){  //custom post type "faq" archive
    $q->set( 'posts_per_page', 5 );
    }
   	return $q;
}

add_filter('parse_query', 'my_custom_posts_per_page');
function mh_get_board_write_link(){
	global $wpdb;
	
	if($link = get_option('mh_board_write_link')){
		return $link;
	}else if($result = $wpdb->get_results("select ID,guid from {$wpdb->prefix}posts where post_type = 'page' and post_content like '%[mh_board_write_form]%'")){
		if(get_option('permalink_structure') == ''){
			update_option('mh_board_write_link',home_url('?page_id='.$result[0]->ID));
			return home_url('?page_id='.$result[0]->ID);
		}else{
			update_option('mh_board_write_link',$result[0]->guid);
			return $result[0]->guid;	
		}
		
	}else{
		return home_url('/write');
	}
}
function mh_get_board_edit_link(){
	global $wpdb;
	if($link = get_option('mh_board_edit_link')){
		return $link;
	}else if($result = $wpdb->get_results("select ID,guid from {$wpdb->prefix}posts where post_type = 'page' and post_content like '%[mh_board_edit_form]%'")){
		if(get_option('permalink_structure') == ''){
			update_option('mh_board_edit_link',home_url('?page_id='.$result[0]->ID));
			return home_url('?page_id='.$result[0]->ID);	
		}else{
			update_option('mh_board_edit_link',$result[0]->guid);
			return $result[0]->guid;	
		}
	}else{
		return home_url('/edit');
	}
}
function mh_update_post_author($post_id , $author = 0){
	global $wpdb;
	if($wpdb->query("update {$wpdb->prefix}posts set post_author = $author where ID = $post_id")){
		return true;
	}
	return false;
}
?>