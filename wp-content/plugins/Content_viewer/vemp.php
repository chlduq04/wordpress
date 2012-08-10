<?php
/*
 * Plugin Name: Content_viewer_by_Vemp
* Version: 1.2.5
* Plugin URI: http://www.davidajnered.com/
* Description: Simple Post Preview is a widget that creates pushes for posts.
* Author: David Ajnered
*/

class vemp_preview extends WP_Widget {
	/**
	 * Init method
	 */
	function vemp_preview(){
		$widget_options = array('classname' => 'content_viewer','description' => __("Creates pushes for your posts"));
		$control_options = array('width' => 100, 'height' => 100);
		$this->WP_Widget('Vemp_widget', __('Vemp Previewer'), $widget_options, $control_options);
	}
	
	/**
	 * Displays the widget
	 */
	function widget($args, $instance) {
		if(!empty($instance)) {
			// Variables
			//$instance['name'] ==> Take variable we can use
			$title = $instance['title'];
			$length = $instance['length'];
			$content_length = $instance['content_length'];
			include('includes/view.php');
		}
	}
	/**
	 * Saves the widget settings
	 */
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['length'] = strip_tags(stripslashes($new_instance['length']));
		$instance['content_length'] = strip_tags(stripslashes($new_instance['content_length']));
		return $instance;
	}

	/**
	 * GUI for backend
	 */
	function form($instance) {
		$title = htmlspecialchars($instance['title']);
		$length = htmlspecialchars($instance['length']);
		$content_length = htmlspecialchars($instance['content_length']);
		/* Print interface */
		include('includes/vemp_ui.php');
	}

} 
/** End of class */


/**
 * Register Widget
 */
function vemp_init() {
	register_widget('vemp_preview');
}
add_action('widgets_init', 'vemp_init');



/**
 * Add CSS and JS to head
 */
function vemp_ui() {
	$plug_path = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	echo '<link rel="stylesheet" type="text/css" href="' . $plug_path . '/css/style.css" media="all"/>';
//	echo '<script type="text/javascript" src="' . $plug_path . '/js/simple-post-preview.js"></script>';
}

function vemp_content() {
	$plug_path = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
	echo '<link rel="stylesheet" type="text/css" href="' . $plug_path . '/css/content.css" media="all"/>';
	//	echo '<script type="text/javascript" src="' . $plug_path . '/js/simple-post-preview.js"></script>';
}
add_action('admin_head', 'vemp_ui');
add_action('wp_head','vemp_content');
?>