<?php
/*
Plugin Name: WP Makerboard
Plugin URI: http://danieru.com/2011/06/09/wp-markerboard-widget/
Description: Wrapper widget for the jquery markerboard plugin, creates a html5 canvas on which users can draw temporary art.
Author: Daniel Dressler
Version: 1
Author URI: http://danieru.com/
*/
 
class wpMarkerboard extends WP_Widget
{
	function wpMarkerboard() {
		$widget_ops = array('classname' => 'wpMarkerboard', 'description' => 'A marker board visitors can play with');
		$this->WP_Widget('Markerboard', 'Markerboard', $widget_ops);
	}

	function form($instance) {
		$instance = wp_parse_args((array) $instance, array( 'color' => 'black', 'width' => '2', 'x' => '150', 'y' => '150',));
		$color = $instance['color'];
		$width = $instance['width'];
		$x = $instance['x'];
		$y = $instance['y'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id('color'); ?>">Color: 
			<input class="widefat" id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" type="text" value="<?php echo attribute_escape($color); ?>" />
			</label>
			<label for="<?php echo $this->get_field_id('width'); ?>">Line Width: 
			<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="number" value="<?php echo attribute_escape($width); ?>" />
			</label>
			<label for="<?php echo $this->get_field_id('x'); ?>">Box Width: 
			<input class="widefat" id="<?php echo $this->get_field_id('x'); ?>" name="<?php echo $this->get_field_name('x'); ?>" type="number" value="<?php echo attribute_escape($x); ?>" />
			</label>
			<label for="<?php echo $this->get_field_id('y'); ?>">Box Height: 
			<input class="widefat" id="<?php echo $this->get_field_id('y'); ?>" name="<?php echo $this->get_field_name('y'); ?>" type="number" value="<?php echo attribute_escape($y); ?>" />
			</label>
		</p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['color'] = $new_instance['color'];
		$instance['width'] = $new_instance['width'];
		$instance['x'] = $new_instance['x'];
		$instance['y'] = $new_instance['y'];
		return $instance;
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$color = $instance['color'];
		$width = $instance['width'];
		$x = $instance['x'];
		$y = $instance['y'];

		$pluginPath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		echo '<script type="text/javascript" src="'.$pluginPath.'jquery.markerboard.js"></script>
		<script type="text/javascript">jQuery(function() {jQuery(".wp_markerboard").markerboard({';
		if (!empty($color)) {echo "color: '$color' ,\n";}
		if (!empty($width)) {echo "width: '$width' ,\n";}
		echo '});});</script>';
		echo '<canvas class="wp_markerboard" width="'.$x.'" height="'.$y.'"></canvas>';

		echo $after_widget;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("wpMarkerboard");') );
?>
