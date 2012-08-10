<?php
/**
 * Get limit 3 posts from a db
 * select post_title from wp_posts where post_type = 'post' and post_status = 'publish' order by id desc limit 3;
 */

function get_recently_posts($num){
	global $wpdb;
	if($num==NULL){
		$query =
		"SELECT id,post_title,post_content,guid FROM {$wpdb->posts} WHERE post_type = 'post' and post_status = 'publish' ORDER BY post_date DESC LIMIT 3;";
	}
	else {
		$query =
		"SELECT id,post_title,post_content,guid FROM {$wpdb->posts} WHERE post_type = 'post' and post_status = 'publish' ORDER BY post_date DESC LIMIT ".$num .";";
	}
	$result = $wpdb->get_results($query);

	return $result;
}
function get_first_img($id){
	global $wpdb;
	$query = "SELECT post_content FROM {$wpdb->posts} WHERE id = '".$id ."' and post_status='publish' and post_type = 'post';";
	
	$result = $wpdb->get_results($query);
	return $result;
}
/**
 * Get all
 */

?>