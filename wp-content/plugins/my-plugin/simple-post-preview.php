<?php
/*
 * Plugin Name: Vemp
* Version: 1.2.5
* Plugin URI: http://www.davidajnered.com/
* Description: Simple Post Preview is a widget that creates pushes for posts.
* Author: David Ajnered
*/

class sample_preview extends WP_Widget {
	/**
	 * Init method
	 */
	function sample_preview(){
		//make preview
		$widget_option = array('classname' => 'Vemp Image Viewer','description' => __("My first Widget"));
		//make hashtable in php => we can use key
		$control_option = array('width' => 100, 'height' => 100);
		$this->WP_Widget('preview', __('Sample Preview'), $widget_option, $control_option);
	}
	/**
	 * Displays the widget
	 */
	function widget($args, $instance){
		$pre;
		if(!empty($instance)) {
			// Variables
			$title = $instance['title'];
			$length = (int)$instance['length'];
			$item = $instance['item'];
			$data_to_use = $instance['data_to_use'];
			$link = $instance['link'];
			$link_to = $instance['link_to'];

			// Find dropdown value
			if(strpos($item, 'p:') !== FALSE) {
				$post = str_replace('p:', '', $item);
			} else if(strpos($item, 'c:') !== FALSE) {
				$category = str_replace('c:', '', $item);
			}

			include_once('includes/db_queries.php');
			if($category != 0) {
				$data = spp_get_post('category', $category);
				$data = $data[0];
			} else if($post != 0) {
				$data = spp_get_post('post', $post);
				$data = $data[0];
			} else {
				// If no post or category is selected, use the most recent post.
				$data = spp_get_post('post');
				$data = $data[0];
				if(!$data) {
					$title = "Simple Post Preview";
					$length = 100;
					$data = (object)array(
							'post_title' => 'Error!',
							'post_content' => 'This widget needs configuration',
					);
				}
			}
		}

		if($data != NULL) {
			// Set link url, post is default
			$url = get_bloginfo('url');
			$url .= ($link_to == 'Category') ? '?cat='.$data->term_id : '?p='.$data->ID;
			$html_link = '<a href="';
			$html_link .= $url;
			$html_link .= '">'.$link.'</a>';
		}

		
		$output = $args['before_widget'].$args['before_title'];
		// Use custom title or post title
		if($title != NULL) {
			$output .= $title;
		} else {
			$output .= $data->post_title;
		}
		$output .= $args['after_title'];
		// Show thumbnail
		if($thumbnail == TRUE) {
			$output .= '<a href="' . $url . '">';
			$output .= get_the_post_thumbnail($data->ID, $thumbnail_size);
			$output .= '</a>';
		}
		// Use post content or post excerpt
		if($data_to_use == 'excerpt') {
			$content = $data->post_excerpt;

		} else {
			$content = $data->post_content;

		}
		// Show the specified length of the content
		if($length <= -1) {
			$content = '';
		} else if (strlen($content) > $length) {
			if($length > 0) {
				$content = substr($content, 0, $length-1) . '&hellip; ';
			}
		}
		// Link to post of category
		$output .= $content . ' ' . $html_link . $args['after_widget'];
		// Print
		echo $output;
		echo " <br />";
	} 
	
	/** * Saves the widget settings */
	function update($new_instance,$old_instance){
		$thumb = strip_tags(stripslashes($new_instance['thumbnail']));
		$instance = $old_instance; 
		$instance['title'] = strip_tags(stripslashes($new_instance['title'])); 
		$instance['item'] = strip_tags(stripslashes($new_instance['item']));
		$instance['data_to_use'] = strip_tags(stripslashes($new_instance['data_to_use']));
		$instance['length'] = strip_tags(stripslashes($new_instance['length']));
		$instance['link'] = strip_tags(stripslashes($new_instance['link']));
		$instance['link_to'] = strip_tags(stripslashes($new_instance['link_to']));
		$instance['what'] = strip_tags(stripslashes($new_instance['link_to']));
		return $instance;
	}
	
	/** * GUI for backend */ 
	function form($instance) {
	$title =htmlspecialchars($instance['title']); 
	$item = htmlspecialchars($instance['item']); 
	$data_to_use = htmlspecialchars($instance['data_to_use']); 
	$length = htmlspecialchars($instance['length']); 
	$link = htmlspecialchars($instance['link']); 
	$link_to = htmlspecialchars($instance['link_to']);
	$instance['what'] = htmlspecialchars($instance['link_to']);
	include('includes/interface.php');
	}
} 


/** * Register Widget */ 

function sample_preview_init() {
	register_widget('sample_preview');
}
add_action('widgets_init', 'sample_preview_init'); 

/** * Add CSS and JS to head */ 
function sample_preview_head() {
$plug_path =
WP_PLUGIN_URL.'/'.str_replace(basename(
		__FILE__),"",plugin_basename(__FILE__)); echo '
		<link
		rel="stylesheet" type="text/css"
		href="' . $plug_path . '/css/simple-post-preview.css" />
		'; echo '
		<script
		type="text/javascript"
		src="' . $plug_path . '/js/simple-post-preview.js"></script>
		';
} add_action('admin_head', 'sample_preview_head');
?>
