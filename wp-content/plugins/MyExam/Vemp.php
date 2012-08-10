<?php
/*
	Plugin Name: Vemp scroll veiwer
	Plugin URI: http://...
	Description: first exam
	Version: 0.1
	Author: choi UY
	Author URI: http://...

	Copyright 2011  Chlduq04(email : chlduq04@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/

function irw_admin_actions($hook) {
	if('widgets.php' != $hook) {
		return;
	}
	// Scripts
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');-
	wp_enqueue_script('jquery-ui-sortable');
	wp_register_script('irw-js', path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) ).'/js/main.js'), array('jquery','media-upload','thickbox'));
	wp_enqueue_script('irw-js');
	wp_register_script('irw-qtip', path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) ).'/js/jquery.qtip.js'), array('jquery','media-upload','thickbox'));
	wp_enqueue_script('irw-qtip');

	// Styles
	wp_enqueue_style('thickbox');
	wp_register_style('irw-css', path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) ).'/css/main.css'));
	wp_enqueue_style('irw-css');
}

?>