<?php

/**
 * @package jiffuy
 * @version 1.02
 * 
 * License: Subscription - All rights reserved!
 * Notice: Every distribution of the source code without authors permission is strictly forbidden, for a written permission please contact info@jiffuy.com!
 */

require_once dirname(__FILE__).'/shop/product.php';
require_once dirname(__FILE__).'/shop/category.php';
require_once dirname(__FILE__).'/shop/advertiser.php';
require_once dirname(__FILE__).'/shop/model/product.php';
require_once dirname(__FILE__).'/shop/model/category.php';	
require_once dirname(__FILE__).'/shop/template.php';
require_once dirname(__FILE__).'/shop/template/product/detail.php';
require_once dirname(__FILE__).'/shop/template/product/overview.php';
require_once dirname(__FILE__).'/shop/controller/home.php';
require_once dirname(__FILE__).'/shop/controller/tag.php';
require_once dirname(__FILE__).'/shop/controller/category.php';
require_once dirname(__FILE__).'/shop/controller/product.php';
require_once dirname(__FILE__).'/shop/controller/search.php';

/**
 *
 * Class for building the jiffuy plugin
 *
 * @category   jiffuy
 * @package    shop
 * @subpackage default
 * @copyright  2012 jiffuy (http://www.jiffuy.com)
 * @license    Closed Source, All Rights Reserved
 * @author     jiffuy <info@jiffuy.com>
 */
if(!class_exists('jiffuyShop')) {
	
	class jiffuyShop {
		
		
		/**
		 * jiffuy object
		 * @var jiffuy
		 */
		protected $_jiffuy = null;
		
		/**
		 * categories
		 * @var array
		 */
		protected $_categories = array();
		
		/**
		 * route
		 * @var array
		 */
		protected $_route = null;
		
		/**
		 * params
		 * @var array
		 */
		protected $_params = array();
		
		/**
		 * params sequence
		 * @var array
		 */
		protected $_paramsSequence = array('b' => array('q'), 'f' => array('q','b'), 'g' => array('q','b','f'), 'm' => array('q','b','f','g'), 'a' => array('q','b','f','g','m'));
		
		/**
		 * Url separator parameter (default: ",")
		 * @var string
		 */
		protected $_urlSeparatorParameter = ',';
		
		/**
		 * Url separator (default: "_")
		 * @var string
		 */
		protected $_urlSeparatorGroup = '_';
		
		
		/**
		 * Construct jiffuy admin
		 * 
		 * @param	jiffuy $jiffuy
		 */
		public function __construct(jiffuy $jiffuy) {
			
			// init vars
			$this->_jiffuy = $jiffuy;
			$this->_urlSeparatorParameter = $this->getJiffuy()->getSettings('shopUrlSeparatorParameter');
			$this->_urlSeparatorGroup = $this->getJiffuy()->getSettings('shopUrlSeparatorGroup');
			
			// initialize
			$this->initialize();
			
			// init filters and actions
			$this->addFilters();
			$this->addActions();
			
		}
		
		
		/**
		 * Initialize
		 * 
		 * @param	none
		 */
		public function initialize() {
			
			// init object
			$this->_initCategories();
			
		}
		
		
		/**
		 * Sets the jiffuy object
		 * 
		 * @param	jiffuy $jiffuy
		 */
		public function setJiffuy(jiffuy $jiffuy) {
			$this->_jiffuy = $jiffuy;
			return $this;
		}
		
		
		/**
		 * Gets the jiffuy object
		 * 
		 * @param	none
		 */
		public function getJiffuy() {
			return $this->_jiffuy;
		}
		
		
		/**
		 * Sets the categories
		 * 
		 * @param	array $categories
		 */
		public function setCategories(array $categories) {
			$this->_categories = $categories;
			return $this;
		}
		
		
		/**
		 * Gets the categories
		 * 
		 * @param	none
		 */
		public function getCategories() {
			return $this->_categories;
		}
		
		
		/**
		 * Sets the route
		 * 
		 * @param	array $route
		 */
		public function setRoute(array $route) {
			$this->_route = $route;
			return $this;
		}
		
		
		/**
		 * Gets the route
		 * 
		 * @param	string $param
		 */
		public function getRoute($param = null) {
			return (is_null($param)) ? $this->_route : ((isset($this->_route[$param])) ? $this->_route[$param] : false);
		}
		
		
		/**
		 * Add param to params
		 * 
		 * @param	string $type
		 * @param	string $value
		 * @param	boolean $return default false - just return value?
		 */
		public function addParam($type, $value, $return = false) {
			
			$params = $this->_params;
			$params[$type] = $value;
			
			uksort($params, array($this, 'addParamSort'));
			
			$this->_params = ($return) ? $this->_params : $params;
			
			return ($return) ? $params : $this;
			
		}
		
		
		/**
		 * Add param to params
		 * 
		 * @param	string $a
		 * @param	string $b
		 */
		public function addParamSort($a, $b) {
			return (isset($this->_paramsSequence[$a]) && in_array($b, $this->_paramsSequence[$a])) ? 1 : -1;
		}
		
		
		/**
		 * Remove param from query
		 * 
		 * @param	(string|array) $type
		 * @param	boolean $return default false - just return value?
		 */
		public function removeParams($type = null, $return = false) {
			
			$params = $this->_params;
			
			if(!is_null($type)) {
				if(is_array($type)) {
					foreach($type as $name) {
						if(isset($params[$name])) { unset($params[$name]); }
					}
				} else {
					if(isset($params[$type])) { unset($params[$type]); }
				}
			} else {
				$params = array();
			}
			
			$this->_params = ($return) ? $this->_params : $params;			
			return ($return) ? $params : $this;
		
		}
				
		
		/**
		 * Gets the categories
		 * 
		 * @param	string $type type (optional)
		 */
		public function getParams($type = null) {
			if(!is_null($type)) {
				return (isset($this->_params[$type])) ? $this->_params[$type] : false;
			}
			return $this->_params;
		}
		
		
		/**
		 * Gets the base url of the shop
		 * 
		 * @param	none
		 */
		public function getBaseUrl() {
			return $this->getJiffuy()->getSettings('shopBaseUrl');
		}
		
		
		/**
		 * Get shop url
		 * 
		 * @param	string $type url type
		 * @param	string $slug url slug
		 * @param	integer $page page count
		 * @param	array $params params array
		 */
		public function getUrl($type, $slug, $page = 1, array $params = null) {
		
			// handle url parameter
			$params = (!is_null($params)) ? $params : $this->getParams();
			
			$query = false;
			foreach($params as $name => $value) {
				$query.= $this->_urlSeparatorGroup.$value.$this->_urlSeparatorParameter.$name;
			}
			
			// handle url by type
			switch($type) {
				case "home":
					if((int)$page < 2 && $query === false) {
						return ($this->getJiffuy()->getRewritePermalinksActive() === false) ? $this->_jiffuy->getUrl(array($this->getBaseUrl() => 'home'.$query)) : $this->_jiffuy->getUrl($this->getBaseUrl().$query);
					} else {
						return $this->_jiffuy->getUrl(array($this->getBaseUrl() => $slug.$this->_urlSeparatorParameter.'h'.$this->_urlSeparatorParameter.(int)$page.$query));
					}
				case "category":
					return $this->_jiffuy->getUrl(array($this->getBaseUrl() => $slug.$this->_urlSeparatorParameter.'c'.$this->_urlSeparatorParameter.(int)$page.$query));
				case "tag":
					return $this->_jiffuy->getUrl(array($this->getBaseUrl() => $slug.$this->_urlSeparatorParameter.'t'.$this->_urlSeparatorParameter.(int)$page.$query));
				case "search":
					return $this->_jiffuy->getUrl(array($this->getBaseUrl() => $slug.$this->_urlSeparatorParameter.'s'.$this->_urlSeparatorParameter.(int)$page.$query));
				case "product":
					return $this->_jiffuy->getUrl(array($this->getBaseUrl() => $slug.$this->_urlSeparatorParameter.'p'.$this->_urlSeparatorParameter.(int)$page));
			}
			
		}
		
		
		/**
		 * Add filters
		 * 
		 * @param	none
		 */
		public function addFilters() {
			add_filter('rewrite_rules_array', array($this, 'rewriteRulesArray'), 0);			
			add_filter('query_vars', array($this, 'setupQueryVars'), 1);
		}		
		
		
		/**
		 * Setup rewrite rules array
		 * 
		 * @param	array $existingRules
		 */
		public function rewriteRulesArray($existingRules) {
			global $wp_rewrite;
			$rules = array(
				'^'.$this->getBaseUrl().'/(.+)' => 'index.php?'.$this->getBaseUrl().'=/'.$this->getBaseUrl().'/'.$wp_rewrite->preg_index(1),
				'^'.$this->getBaseUrl().'$' => 'index.php?'.$this->getBaseUrl().'=/'.$this->getBaseUrl().'/'
			);
			$existingRules = (!is_array($existingRules)) ? array() : $existingRules;
			return array_merge($rules, $existingRules);
		}
		
		
		/**
		 * Setup query vars
		 * 
		 * @param	array $queryVars
		 */
		public function setupQueryVars($queryVars) {
			$queryVars[] = $this->getBaseUrl();
			return $queryVars;
		}
		
		
		/**
		 * Flush rewrite rules
		 * 
		 * @param	none
		 */
		public function flushRewriteRules() {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
			return $this;
		}
		
		
		/**
		 * Add actions
		 * 
		 * @param	none
		 */
		public function addActions() {
			add_action('wp_loaded', array($this, 'flushRewriteRules'), 0);
			add_action('template_redirect', array($this, 'setupRoutes'), 1);
			remove_action('wp_head', 'noindex', 1);
		}
		
		
		/**
		 * Setup template redirect
		 * 
		 */
		public function setupRoutes() {
			
			global $wp_query;
			
			// get query var
			$shopQuery = get_query_var($this->getBaseUrl());
			
			// check for trailing slash
			if($this->getJiffuy()->getRewritePermalinksActive() === true && substr($_SERVER['REQUEST_URI'], -1) != '/') {
				wp_redirect($_SERVER['REQUEST_URI'].'/', 301);
				return;
			}
			
			// check for home url
			if($this->getJiffuy()->getRewritePermalinksActive() === true && (
				$_SERVER['REQUEST_URI'] == '/'.$this->getBaseUrl() ||
			   rtrim($_SERVER['REQUEST_URI'], '/') == '/'.$this->getBaseUrl().'/home'.$this->_urlSeparatorParameter.'h'.$this->_urlSeparatorParameter.'1' ||
			   rtrim($_SERVER['REQUEST_URI'], '/') == '/'.$this->getBaseUrl().'/home'
			)) {
				wp_redirect('/'.$this->getBaseUrl().'/', 301);
				return;
			}
			
			// add default shop route
			if(($this->getJiffuy()->getRewritePermalinksActive() === false && strlen($shopQuery) > 0) ||
				($this->getJiffuy()->getRewritePermalinksActive() === true && preg_match('/'.$this->getBaseUrl().'/', $shopQuery))) {
				
				// disable is home page
				$wp_query->is_home = false;
					
				// set home route
				$shopQuery = preg_replace('/^\/'.$this->getBaseUrl().'\//', '', $shopQuery);
				$shopQuery = ($shopQuery == '' || $shopQuery == 'home') ? 'home'.$this->_urlSeparatorParameter.'h'.$this->_urlSeparatorParameter.'1' : $shopQuery;
				
				// init jiffuy api
				$this->getJiffuy()->initApi();
				
				// check base url
				$baseUrl = $this->_filterRouteValue($shopQuery);
				
				if(!is_null($baseUrl) && !is_null($this->getJiffuy()->getApi())) {
					$this->_setupRoute($baseUrl);
				} else {
					$this->getJiffuy()->getErrorPage();
				}
				
			}
			
		}
		
		
		/**
		 * Filter route default value
		 * 
		 * @param	string $string
		 */
		private function _filterRouteValue($string) {
			$string = preg_replace("/[^A-Za-z0-9-_,]/", '', strip_tags($string));
			return $string;
		}
		
		
		/**
		 * Setup the controller route
		 * 
		 * @param	string $query
		 */
		private function _setupRoute($query) {
			
			// prepare query
			$this->_prepareRoute($query);
			
			if(!is_null($this->getRoute())) {
				
				// handle category and product routes
				$this->_handleRoute();
				
			} else { $this->getJiffuy()->getErrorPage(); }
			
		}
		
		
		/**
		 * Prepare route
		 * 
		 * @param	string $queryUrl
		 */
		private function _prepareRoute($queryUrl) {
			
			// prepare query and route
			$query = explode($this->_urlSeparatorGroup, $queryUrl);
			$route = explode($this->_urlSeparatorParameter, array_shift($query));
			
			// check for home route
			if(is_array($query) && is_array($route) && count($route) == 3) {
				
				// handle default route
				$this->_prepareRouteDefault($route, $query);
				
			}
			
		}
		
		
		/**
		 * Prepare route default
		 * 
		 * @param	array $route
		 * @param	array $query
		 */
		private function _prepareRouteDefault(array $route, array $query) {
			
			// parse search query
			$query = ($route[0] == 'search') ? $this->_parseSearchQuery($query) : $query;
			
			// handle default route
			$preparedRoute = array(
				'slug' => ((isset($route[0])) ? $route[0] : false),
				'type' => ((isset($route[1])) ? $route[1] : false),
				'page' => ((isset($route[2]) && (int)$route[2] > 1) ? (int)$route[2] : 1)
			);
			$this->setRoute($preparedRoute);
			
			// prepare params
			$this->_prepareRouteParams($query);
			
		}
		
		
		/**
		 * Parse search query to params
		 * 
		 * @param	array $query
		 */
		private function _parseSearchQuery($query) {
			
			// check if search params exist
			if(isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], "?") !== false) {
				$searchQuery = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], "?")+1);		
				if(is_string($searchQuery) && strlen($searchQuery) > 0) {
					parse_str($searchQuery, $params);
					if(isset($params['juys']) && strlen($params['juys']) > 0) {
						// add search query param
						if(is_array($query)) {
							$searchQuery = jiffuy::generateSlug($params['juys']);
							foreach($query as $key => $param) {
								$param = explode($this->_urlSeparatorParameter, $param);
								if($param[1] == 'q') {
									unset($query[$key]);
								}
							}
							if(strlen($searchQuery) > 0) {
								$query[] = $searchQuery.$this->_urlSeparatorParameter.'q';
							}
						}				
					}					
				}				
			}
			
			return $query;
			
		}
		
		
		/**
		 * Prepare route params
		 * 
		 * @param	array $query
		 */
		private function _prepareRouteParams(array $query) {
			
			// only allow params if type not disallowed
			if(in_array($this->getRoute('type'), array('p')) && count($query) > 0) {
				$this->getJiffuy()->getErrorPage();
			}
			
			// set params
			if($this->_validateRouteParams($query) === false) { $this->getJiffuy()->getErrorPage(); }
			
		}
		
		
		/**
		 * Validate route params
		 * 
		 * @param	array $query
		 */
		private function _validateRouteParams(array $query) {
			
			$params = array();
			$previousKey = false;
			foreach($query as $key => $setting) {
				$setting = explode($this->_urlSeparatorParameter, $setting);
				if(is_array($setting) && count($setting) == 2) {
					if($previousKey !== false && (isset($this->_paramsSequence[$setting[1]]) && !in_array($previousKey, $this->_paramsSequence[$setting[1]]))) {
						return false;
					}
					$this->addParam($setting[1], $setting[0]);
					$previousKey = $setting[1];
				} else {
					return false;
				}
			}
			
			return true;
			
		}
		
		
		/**
		 * Handle the default route
		 * 
		 * @param	none
		 */
		private function _handleRoute() {
			
			// handle routes
			switch($this->getRoute('type')) {
				case "h": $this->_handleRouteHome(); break;
				case "c": $this->_handleRouteCategory(); break;
				case "t": $this->_handleRouteTag(); break;
				case "p": $this->_handleRouteProduct(); break;
				case "s": $this->_handleRouteSearch(); break;
				default: $this->getJiffuy()->getErrorPage(); break;
			}
			
		}
		
		
		/**
		 * Handle the home route
		 * 
		 * @param	none
		 */
		private function _handleRouteHome() {
			
			// handle home route
			$controller = new jiffuyShopControllerHome($this->getJiffuy(), new jiffuyShopModelProduct($this->_jiffuy));
			$controller->index();
			exit;
			
		}
		
		
		/**
		 * Handle the default route
		 * 
		 * @param	none
		 */
		private function _handleRouteCategory() {
			
			// handle home route
			$controller = new jiffuyShopControllerCategory($this->getJiffuy(), new jiffuyShopModelProduct($this->_jiffuy));
			$controller->index();
			exit;
			
		}
		
		
		/**
		 * Handle the tag route
		 * 
		 * @param	none
		 */
		private function _handleRouteTag() {
			
			// handle home route
			$controller = new jiffuyShopControllerTag($this->getJiffuy(), new jiffuyShopModelProduct($this->_jiffuy));
			$controller->index();
			exit;
			
		}
		
		
		/**
		 * Handle the product route
		 * 
		 * @param	none
		 */
		private function _handleRouteProduct() {
			
			// handle home route
			$controller = new jiffuyShopControllerProduct($this->getJiffuy(), new jiffuyShopModelProduct($this->_jiffuy));
			$controller->index();
			exit;
			
		}
		
		
		/**
		 * Handle the search route
		 * 
		 * @param	none
		 */
		private function _handleRouteSearch() {
			
			// handle home route
			$controller = new jiffuyShopControllerSearch($this->getJiffuy(), new jiffuyShopModelProduct($this->_jiffuy));
			$controller->index();
			exit;
			
		}
		
		
		/**
		 * Init categories
		 * 
		 * @param	none
		 */
		private function _initCategories() {
			
			$this->_initCategoriesByArray($this->getJiffuy()->getSettings('shopStaticCategories'));
			$this->_initCategoriesByArray($this->getJiffuy()->getSettings('shopUserCategories'));
			
		}
		
		
		/**
		 * Init categories by array
		 * 
		 * @param	none
		 */
		private function _initCategoriesByArray($categories) {
		
			if(is_array($categories)) {
				
				if(isset($categories['preset'])) {
					unset($categories['preset']);
				}
				
				foreach($categories as $key => $item) {
					$this->_categories[$item['slug']] = new jiffuyShopCategory(
						array(
							'slug' => $item['slug'],
							'name' => $item['name'],
							'title' => $item['title'],
							'description' => (isset($item['description'])) ? $item['description'] : false,
							'metaDescription' => (isset($item['metaDescription'])) ? $item['metaDescription'] : false,
							'tags' => (isset($item['tags']) && is_array($item['tags'])) ? $item['tags'] : array(),
							'tagsNot' => (isset($item['tagsNot']) && is_array($item['tagsNot'])) ? $item['tagsNot'] : array(),
							'tree' => (isset($item['tree'])) ? $item['tree'] : false,
							'level' => (isset($item['level'])) ? $item['level'] : false
						)
					);
				}
				
			}
			
		}
		
		
	}
	
}

