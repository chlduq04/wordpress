<?php

/**
 * @package jiffuy
 * @version 1.02
 * 
 * License: Subscription - All rights reserved!
 * Notice: Every distribution of the source code without authors permission is strictly forbidden, for a written permission please contact info@jiffuy.com!
 */

/*
 * 
Plugin Name: jiffuy
Plugin URI: http://wordpress.org/extend/plugins/jiffuy-for-wordpress/
Description: jiffuy ist der einfachste Weg mit Ihrer Website oder Ihrer Blog Geld zu verdienen. Anleitung: 1) Klicken Sie auf den "Aktivieren" Link auf der linken Seite. 2) <a href="http://www.jiffuy.com/unternehmen/registrierung">Registrieren Sie sich für einen jiffuy API-Key und Secret</a> und kopieren Sie diesen aus Ihrer E-Mail Bestätigung in die Zwischenablage. 3) Fügen Sie Ihren API-Key und Secret unter den <a href="options-general.php?page=jiffuy">Plugin-Einstellungen</a> ein und speichern diese im Anschluss.
Version: 5.2
Author: jiffuy
Author URI: http://www.jiffuy.com
License: Subscription
*/

define('JIFFUY_VERSION', '5.2');
define('JIFFUY_PLUGIN_URL', plugin_dir_url(__FILE__));

if(!class_exists('WP')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

/**
 *
 * Class for building the jiffuy plugin
 *
 * @category   jiffuy
 * @package    jiffuy
 * @subpackage general
 * @copyright  2012 jiffuy (http://www.jiffuy.com)
 * @license    Closed Source, All Rights Reserved
 * @author     jiffuy <info@jiffuy.com>
 */
if(!class_exists('jiffuy')) {
	
	class jiffuy {
		
		
		/**
		 * Settings
		 */
		protected $_settings = array();
		
		/**
		 * Api object
		 * @var jiffuyApi
		 */
		protected $_api = null;
		
		/**
		 * Api status
		 * @var boolean
		 */
		protected $_apiStatus = null;
		
		/**
		 * Shop object
		 * @var jiffuyShop
		 */
		protected $_shop = null;
		
		/**
		 * Affiliate base url
		 * @var string
		 */
		protected $_affiliateBaseUrl = 'http://u.jiffuy.com';
		
		/**
		 * Rewrite permalinks active
		 * @var boolean
		 */
		private $_rewritePermalinksActive = false;
		
		
		/**
		 * Construct
		 * 
		 * @param	jiffuyApi $api
		 */
		public function __construct() {
			
			// init filters and actions
			$this->addFilters();
			$this->addActions();
			
			// init
			$this->initialize();
			
		}
		
		
		/**
		 * Activate
		 * 
		 * @param	boolean $defaults
		 */
		public static function initSettings($defaults = false) {
			
			// default options
			if($defaults === true) {
				delete_option('jiffuy');
			}
			
			// flush cache
			jiffuyCache::flushCache();
			
			// plugin default settings
			$settings = array(
				'apiStatus' => false,
				'apiKey' => '',
				'apiSecret' => '',
				'shopBaseUrl' => 'shop',
				'shopProductsPerPage' => 20,
				'shopDisplaySidebarColors' => true,
				'shopDisplaySidebarGenders' => true,
				'shopDisplaySidebarPrices' => true,
				'shopStaticCategories' => parse_ini_file(dirname(__FILE__).'/shop/presets/default.static.inc', true),
				'shopUserCategories' => parse_ini_file(dirname(__FILE__).'/shop/presets/default.inc', true),
				'shopProductsIndexTagPagesCount' => 3,
				'shopProductsIndexDetailPages' => false,
				'shopUrlAllowedSeparator' => array('_',',',';'),
				'shopUrlSeparatorParameter' => ',',
				'shopUrlSeparatorGroup' => '_',
				'cacheExpires' => 7,
				'cacheApc' => 0,
				'version' => floatval(JIFFUY_VERSION),
			);
			
			// get existing settings
			$oldSettings = get_option('jiffuy');
			if($defaults === false && is_array($oldSettings)) {
				
				// clear old settings
				if(isset($oldSettings['shopUrlAllowedSeparator'])) { unset($oldSettings['shopUrlAllowedSeparator']); }
				if(isset($oldSettings['version'])) { unset($oldSettings['version']); }
				
				// merge new settings
				$settings = array_merge($settings, $oldSettings);
				
			}
			
			if(is_array(get_option('jiffuy')) && count(get_option('jiffuy')) > 0) {
				
				// update options
				update_option('jiffuy', $settings);
				
			} else {
				
				// add options
				add_option('jiffuy', $settings, '', 'yes');
				
			}
			
			return $settings;
			
		}
		
		
		/**
		 * Activate
		 * 
		 * @param	none
		 */
		public static function activate() {
			
			// init settings
			self::initSettings(false);
			
			// flush rewrite rules
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
			
		}
		
		
		/**
		 * Deactivate
		 * 
		 * @param	none
		 */
		public static function deactivate() {
			
			// flush rewrite rules
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
			
		}
		
		
		/**
		 * Initialize
		 * 
		 * @param	none
		 */
		public function initialize() {
			
			// init settings
			$this->_initSettings();
			$this->_initPermalinks();
			
			// init settings
			if(floatval($this->getSettings('version')) < floatval(JIFFUY_VERSION)) {
				
				// init new version settings
				self::initSettings(false);
				$this->_initSettings();
				
			}
			
		}
		
		
		/**
		 * Init api
		 * 
		 * @param	none
		 */
		public function initApi() {
			
			if($this->getSettings('apiStatus') === true) {
				$api = new jiffuyApi($this->getSettings('apiKey'), $this->getSettings('apiSecret'));
				$this->setApi($api);
			}
			
		}
		
		
		/**
		 * Add filters
		 * 
		 * @param	none
		 */
		public function addFilters() {
			
		}
		
		
		/**
		 * Add actions
		 * 
		 * @param	none
		 */
		public function addActions() {
			
		}
		
		
		/**
		 * Set settings
		 * 
		 * @param	array $settings
		 */
		public function setSettings(array $settings) {
			$this->_settings = $settings;
			return $this;
		}
		
		
		/**
		 * Get settings
		 * 
		 * @param	string $name name of the settings
		 */
		public function getSettings($name = null) {
			return (!is_null($name)) ? ((isset($this->_settings[$name])) ? $this->_settings[$name] : false) : $this->_settings;
		}
		
		
		/**
		 * Set api object
		 * 
		 * @param	jiffuyApi $api
		 */
		public function setApi(jiffuyApi $api) {
			$this->_api = $api;
			return $this;
		}
		
		
		/**
		 * Get api object
		 * 
		 * @param	none
		 */
		public function getApi() {
			return $this->_api;
		}
		
		
		/**
		 * Set shop object
		 * 
		 * @param	jiffuyShop $shop
		 */
		public function setShop(jiffuyShop $shop) {
			$this->_shop = $shop;
			return $this;
		}
		
		
		/**
		 * Get shop object
		 * 
		 * @param	none
		 */
		public function getShop() {
			return $this->_shop;
		}
		
		
		/**
		 * Set affiliate base url
		 * 
		 * @param	string $affiliateBaseUrl
		 */
		public function setAffiliateBaseUrl($affiliateBaseUrl) {
			$this->_affiliateBaseUrl = $affiliateBaseUrl;
			return $this;
		}
		
		
		/**
		 * Get affiliate base url
		 * 
		 * @param	none
		 */
		public function getAffiliateBaseUrl() {
			return $this->_affiliateBaseUrl;
		}
		
		
		/**
		 * Limit string
		 * 
		 * @param	none
		 */
		public function limitString($string, $limit = 100) {
			
			if(strlen($string) < $limit) {
				return $string;
			}
			
			$regex = "/(.{1,$limit})\b/";
			preg_match($regex, $string, $matches);
			
			return trim($matches[1]);
			
		}
		
		
		/**
		 * Get permalinks active
		 * 
		 * @param	none
		 */
		public function getRewritePermalinksActive() {
			return $this->_rewritePermalinksActive;
		}
		
		
		/**
		 * Get url by params by permalink structure
		 * 
		 * @param	mixed $params
		 */
		public function getUrl($params) {
			
			$url = site_url();
			
			if((is_array($params) && count($params) > 0)) {
				
				// add params array to url
				return $url.(($this->_rewritePermalinksActive === true) ? $this->_handleUrlParamsRewrite($params) : $this->_handleUrlParamsGet($params));
				
			} else if(is_string($params) && strlen($params) > 0) {
				
				// just add the params string to url
				return $url.(($this->_rewritePermalinksActive === true) ? '/'.$params.'/' : '?'.$params);
				
			}
			
			return $url;
			
		}
		
		
		/**
		 * Get affiliate url
		 * 
		 * @param	string $productId
		 */
		public function getAffiliateUrl($productId = null) {
			return (isset($this->_settings['apiKey']) && strlen(trim($this->_settings['apiKey'])) > 0) ? $this->getAffiliateBaseUrl().'/'.trim($this->_settings['apiKey']).'/'.$productId : $this->getAffiliateBaseUrl().'/false/'.$productId;
		}
		
		
		/**
		 * Get error page
		 * 
		 * @param	none
		 */
		public function getErrorPage() {
			
			// set http header
			if($this->getSettings('apiStatus') == false) {
				
				// set temporarily unavailable header
				header('HTTP/1.1 503 Service Temporarily Unavailable');
				header('Status: 503 Service Temporarily Unavailable');
				header('Retry-After: 7200');
				status_header(503);
				
			} else {
				
				// set page not found header
				header("HTTP/1.0 404 Not Found");
				header("Status: 404 Not Found");
				status_header(404);
				
			}
			
			get_header();
			
			print '<div id="content" class="narrowcolumn">';
			print '<h2><strong>'.__('Fehler').'</strong></h2>';
			print '<p>'.__('Die von Ihnen angeforderte Seite konnte leider nicht gefunden werden.').'</p>';
			print '</div>';
			
			get_footer();
			
			exit;
			
		}
		
		
		/**
	    * Replace umlauts and special characters of a string
	    *
	    * @param	mixed $string
	    * @return	string
	    */
		public static function generateSlug($string) {
			$string = htmlspecialchars_decode($string);
	   	$string = jiffuy::removeSpecialCharacters(strip_tags($string));
	   	$string = preg_replace('~^/+|/+$|/(?=/)~', '', $string);
	   	$string = preg_replace('/\s+/','-', $string);
	   	$string = trim(strtolower($string), '-');
	   	return $string;
		}
		
		
		/**
	    * Replace umlauts and special characters of a string
	    *
	    * @param	mixed $string
	    * @param	boolean $allowWhitespace
	    * @param	string $regexExtension extend regex (optional)
	    * @return	string
	    */
	   public static function removeSpecialCharacters($string, $allowWhitespace = true, $regexExtension = '') {
	   	$string = jiffuy::replaceUmlauts($string);
	   	$string = ($allowWhitespace) ? preg_replace('/[^A-Za-z0-9'.$regexExtension.'\_\-\/\s]/', '', $string) : preg_replace('/[^A-Za-z0-9'.$regexExtension.'\_\-\/]/', '', $string);
	   	return trim($string);
	   }
	   
	   
	   /**
	    * Replace umlauts and special characters of a string
	    *
	    * @param	mixed $string
	    * @return	string
	    */
	   public static function replaceUmlauts($string) {
	   	$replace = array('Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'UE', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'ae', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'oe', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', 'ü'=>'ue', 'Ü' => 'UE');
	   	return strtr($string, $replace);
	   }
	   
	   
	   /**
	    * Array sort by column
	    *
	    * @param	array $array
	    * @param	string $column
	    * @param	string $directory (default: SORT_ASC)
	    * @return	array
	    */
	   public static function arraySortByColumn(&$array, $column, $directory = SORT_ASC) {
	   	$sortCol = array();
	   	foreach ($array as $key => $row) {
	   		$sortCol[$key] = $row[$column];
	   	}
	   	array_multisort($sortCol, $directory, $array);
	   	return $array;
	   }	   
		
		
		/**
		 * Handle rewrite url params
		 * 
		 * @param	mixed $params
		 */
		private function _handleUrlParamsRewrite($params) {
			
			$url = '';
			
			foreach($params as $key => $value) {
				$url.= (strlen(trim($value)) > 0) ? '/'.$key.'/'.$value.'/' : '/'.$key;
			}
			
			return $url;
			
		}
		
		
		/**
		 * Handle get url params
		 * 
		 * @param	mixed $params
		 */
		private function _handleUrlParamsGet($params) {
			
			$url = '';
			
			foreach($params as $key => $value) {
				$url.= ((strlen($url) > 0) ? '&' : '/?') . $key.'='.$value;
			}
			
			return $url;
			
		}
		
		
		/**
		 * Init global settings
		 * 
		 * @param	none
		 */
		private function _initSettings() {
			if(is_array(get_option('jiffuy'))) {
				$this->setSettings(get_option('jiffuy'));
			}
		}
		
		
		/**
		 * Init permalinks settings
		 * 
		 * @param	none
		 */
		private function _initPermalinks() {
			$permalinks = get_option('permalink_structure');
			$this->_rewritePermalinksActive = (strlen($permalinks) > 0) ? true : false;
		}
		
		
	}
	
}

// debugging
add_action('activated_plugin','save_error');
function save_error() {
	delete_option('juy_plugin_error');
   add_option('juy_plugin_error',  ob_get_contents());
}

// load required classes
require_once dirname(__FILE__).'/api.php';
require_once dirname(__FILE__).'/cache.php';

// init plugin
$jiffuy = new jiffuy();

// handle admin part
if(is_admin()) {
	
	// init admin module	
	require_once dirname(__FILE__).'/admin.php';	
	$jiffuyAdmin = new jiffuyAdmin($jiffuy);

// handle frontend part
} else {
	
	// library
	require_once dirname(__FILE__).'/library/pagination.php';
	
	// set shop module	
	require_once dirname(__FILE__).'/shop.php';		
	$jiffuy->setShop(new jiffuyShop($jiffuy));
	
}

register_activation_hook(__FILE__, array('jiffuy', 'activate'));
register_deactivation_hook(__FILE__, array('jiffuy', 'deactivate'));

// debug plugin activation errors
// print get_option('juy_plugin_error');
