<?php

/**
 * The mh-board Plugin
 *
 * mh-board is board with a twist from the creators of WordPress.
 *
 * @package mh-board
 * @subpackage Main
 */

/**
 * Plugin Name: MH Board
 * Plugin URI:  http://ssamture.net
 * Description: 워드프레스 한국형 게시판 플러그인입니다. 별도의 테이블을 생성한 별개가 아니기때문에 다른 플러그인과 숏코드등도 같이 사용 가능합니다.
 * Author:      MinHyeong Lim
 * Author URI:  http://ssamture.net
 * Version:     0.7.3
 * Text Domain: mhboard
 * Domain Path: /mhb-languages/
 */
define('MH_BOARD_VERSION','0.7.3');
define('MH_BOARD_UPDATE_URL','http://ssamture.net/mh_board.xml');
ini_set('memory_limit', -1);
require_once(dirname(__FILE__).'/includes/mh-functions.php');
require_once(dirname(__FILE__).'/includes/mh-post-type.php');
require_once(dirname(__FILE__).'/includes/mh-email-push.php');
require_once(dirname(__FILE__).'/admin/mh-board-option-page.php');
require_once(dirname(__FILE__).'/mh-core/mh_core_load.php');
require_once(dirname(__FILE__).'/widgets/mh_widgets.php');
$board_template = new MH_Templates_Loader('board');
global $mh_board;
class MHBoard{
	/*
	 * @public plugin_dir
	 */
	public $plugin_dir;
	/**
	 * @public board post type
	 */
	public $board_post_type;
	
	public $board_slug;
	
	public function MHBoard() {
		$this->__construct();
	}
	public function __construct(){
		$this->define_value();	
		$this->includes();
		$this->init();		
	}
	private function define_value(){
		$this->plugin_dir = dirname(__FILE__);
		$this->board_post_type = apply_filters( 'mhb_board_post_type', 'board' );
		$this->board_slug = apply_filters( 'mhb_board_slug', 'board' );
	}
	private function includes(){
		
	}
	
	private function init(){
		register_activation_hook(__FILE__ , array(&$this, 'mh_pageview_install'));//페이지뷰 테이블 생성
		add_action('wp_ajax_nopriv_wpp_update', array(&$this, 'wpp_ajax_update'));
		add_action('wp_head', array(&$this, 'wpp_print_ajax'));
		
	}
	
	/**
	 * since 1.0
	 * 
	 */
	function mh_board_list(){
		global $wp_query;
		require_once(dirname(__FILE__).'/template/mh-board-list.php');
	}
	function mh_board_view(){
		require_once(dirname(__FILE__).'/template/mh-board-view.php');
	}
	function mh_board_write(){
		require_once(dirname(__FILE__).'/template/post-form.php');
	}
	function mh_board_sidebar(){
		require_once(dirname(__FILE__).'/template/mh-board-sidebar.php');
	}
	function mh_pageview_install(){
		global $wpdb;
		$sql = "";
		$charset_collate = "";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		if ( ! empty($wpdb->charset) ) $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) ) $charset_collate .= " COLLATE $wpdb->collate";
		
		// set table name
		$table = $wpdb->prefix . "popularpostsdata";
		
		// does popularpostsdata table exists?
		if ( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table ) { // fresh setup
			// create tables popularpostsdata and popularpostsdatacache
			$sql = "CREATE TABLE " . $table . " ( UNIQUE KEY id (postid), postid int(10) NOT NULL, day datetime NOT NULL default '0000-00-00 00:00:00', last_viewed datetime NOT NULL default '0000-00-00 00:00:00', pageviews int(10) default 1 ) $charset_collate; CREATE TABLE " . $table ."cache ( UNIQUE KEY id (id, day), id int(10) NOT NULL, day datetime NOT NULL default '0000-00-00 00:00:00', pageviews int(10) default 1 ) $charset_collate;";
		} else {
			$cache = $table . "cache";
			if ( $wpdb->get_var("SHOW TABLES LIKE '$cache'") != $cache ) {
				// someone is upgrading from version 1.5.x
				$sql = "CREATE TABLE " . $table ."cache ( UNIQUE KEY id (id, day), id int(10) NOT NULL, day datetime NOT NULL, pageviews int(10) default 1 ) $charset_collate;";
			}
			
			$dateField = $wpdb->get_results("SHOW FIELDS FROM " . $table ."cache", ARRAY_A);
			if ($dateField[1]['Type'] != 'datetime') $wpdb->query("ALTER TABLE ". $table ."cache CHANGE day day datetime NOT NULL default '0000-00-00 00:00:00';");
		}
		
		dbDelta($sql);
	}
	//모바일 페이지뷰 정보 갱신
	function wpp_ajax_update() {		
		$nonce = $_POST['token'];
		
		// is this a valid request?
		if (! wp_verify_nonce($nonce, 'wpp-token') ) die("Oops!");
		
		if (is_numeric($_POST['id']) && (intval($_POST['id']) == floatval($_POST['id'])) && ($_POST['id'] != '')) {
			$id = $_POST['id'];
		} else {
			die("Invalid ID");
		}		
		// if we got an ID, let's update the data table
					
		global $wpdb;
		
		$wpdb->show_errors();
		
		$table = $wpdb->prefix . 'popularpostsdata';
		
		// update popularpostsdata table
		$exists = $wpdb->get_results("SELECT postid FROM $table WHERE postid = '$id'");							
		if ($exists) {
			$result = $wpdb->query("UPDATE $table SET last_viewed = '".$this->now()."', pageviews = pageviews + 1 WHERE postid = '$id'");
		} else {
			$result = $wpdb->query("INSERT INTO $table (postid, day, last_viewed) VALUES ('".$id."', '".$this->now()."', '".$this->now()."')" );
		}
		
		// update popularpostsdatacache table
		$isincache = $wpdb->get_results("SELECT id FROM ".$table."cache WHERE id = '" . $id ."' AND day BETWEEN '".$this->curdate()." 00:00:00' AND '".$this->curdate()." 23:59:59';");
		if ($isincache) {
			$result2 = $wpdb->query("UPDATE ".$table."cache SET pageviews = pageviews + 1, day = '".$this->now()."' WHERE id = '". $id . "' AND day BETWEEN '".$this->curdate()." 00:00:00' AND '".$this->curdate()." 23:59:59';");
		} else {
			$result2 = $wpdb->query("INSERT INTO ".$table."cache (id, day) VALUES ('".$id."', '".$this->now()."')");
		}
		
		if (($result == 1) && ($result2 == 1)) {
			die("OK");
		} else {
			die($wpdb->print_error);
		}		
		
	}
	function wpp_print_ajax() {		
		// let's add jQuery
		wp_print_scripts('jquery');
			
		// create security token
		$nonce = wp_create_nonce('wpp-token');
		
		// get current post's ID
		global $wp_query;
		wp_reset_query();
		
		// if we're on a page or post, load the script
		if ( (is_single() || is_page()) ) {
			$id = $wp_query->post->ID;
		?>
<!-- Wordpress Popular Posts v<?php echo $this->version; ?> -->
<script type="text/javascript" charset="utf-8">
/* <![CDATA[ */				
jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {action: 'wpp_update', token: '<?php echo $nonce; ?>', id: <?php echo $id; ?>});
/* ]]> */
</script>
<!-- End Wordpress Popular Posts v<?php echo $this->version; ?> -->
        <?php
		}
	}
	function now() {		
		//return "'".current_time('mysql')."'";
		return current_time('mysql');
	}
	function curdate() {
		//return "'".gmdate( 'Y-m-d', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ))."'";
		return gmdate( 'Y-m-d', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ));
	}
	function get_count( $id ){
		global $wpdb;
		
		$table = $wpdb->prefix.'popularpostsdata';
		
		if($result = $wpdb->get_results("select pageviews from {$table} where postid = $id")){
			return $result[0]->pageviews;
		}else{
			return 0;
		}
	} 
}
$GLOBALS['mh_board'] = new MHBoard();
add_action('wp_enqueue_scripts','mh_board_styles');
function mh_board_styles(){
	wp_register_style('mh-board-style', plugins_url('/templates/style.css', __FILE__),'','0.1' );
	wp_enqueue_style('mh-board-style');
}
add_action('wp_enqueue_scripts','mh_board_scripts');
function mh_board_scripts(){
	wp_register_script('mh-board-script', plugins_url('/templates/js/mh_board.js', __FILE__),array('jquery'),'0.1'  );
	wp_enqueue_script('mh-board-script');
}
add_action('admin_menu','test');
function test(){
	remove_submenu_page( 'edit.php?post_type=board','post-new.php?post_type=board' );
}
add_action( 'add_meta_boxes', 'mh_board_meta_boxes' );

function mh_board_meta_boxes() {
	add_meta_box( 'mh_board_notice', '공지여부', 'mh_board_notice_meta_box', 'board', 'side', 'default');
}
function mh_board_notice_meta_box($post){
	$mh_board_notice = get_post_meta($post->ID, 'mh_board_notice', true) ;
	?>
	<div>
		<p class="form-field">
			<label for="notice">공지여부</label>
			<input type="checkbox" name="notice" value="1"<?php if($mh_board_notice == 1){echo "checked";}?>/>
		</p>
	</div>
	<?php
}
add_action( 'save_post', 'mh_board_save_post' );
function mh_board_save_post( $post_id ){
	global $post;
	if( $post->post_type == 'board' ){
		$notice = empty($_POST['notice']) ? 0 : (int) $_POST['notice'];
		update_post_meta($post_id, 'mh_board_notice', $notice);
		if(get_post_meta($post_id,'guest_info',true)){
			mh_update_post_author($post_id);
		}
	}
}
?>
