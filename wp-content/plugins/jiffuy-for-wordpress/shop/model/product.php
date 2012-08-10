<?php

/**
 *
 * Class for building the jiffuy plugin
 *
 * @category   jiffuy
 * @package    shop
 * @subpackage model product
 * @copyright  2012 jiffuy (http://www.jiffuy.com)
 * @license    Closed Source, All Rights Reserved
 * @author     jiffuy <info@jiffuy.com>
 */
if(!class_exists('jiffuyShopModelProduct')) {
	
	class jiffuyShopModelProduct {
		
		
		/**
		 * jiffuy object
		 * @var jiffuy
		 */
		protected $_jiffuy = null;
		
		/**
		 * jiffuy api
		 * @var jiffuyApi
		 */
		protected $_api = null;
		
		/**
		 * query
		 * @var array
		 */
		protected $_query = array();
		
		
		
		/**
		 * Construct
		 * 
		 * @param	jiffuy $jiffuy
		 */
		public function __construct(jiffuy $jiffuy) {

			// init var
			$this->_jiffuy = $jiffuy;
			$this->_api = $this->_jiffuy->getApi();
			
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
		 * Add query
		 * 
		 * @param	string $field field
		 * @param	mixed $value value
		 */
		public function addQuery($field, $value) {
			if(!isset($this->_query[$field]) || $field == 'search') {
				$this->_query[$field] = $value;
			} else {
				if(is_array($this->_query[$field]) && is_array($value)) {
					$this->_query[$field] = array_unique(array_merge($this->_query[$field], $value));
				} else if(is_array($this->_query[$field]) && is_string($value)) {
					$this->_query[$field][] = $value;
					$this->_query[$field] = array_unique($this->_query[$field]);
				} else if(is_string($this->_query[$field]) && is_array($value)) {
					$value[] = $this->_query[$field];
					$this->_query[$field] = $value;
				} else if(is_string($this->_query[$field]) && is_string($value)) {
					$this->_query[$field] = array($this->_query[$field], $value);
				}
			}
			return $this;
		}
		
		
		/**
		 * Get query
		 * 
		 * @param	array $query
		 */
		public function getQuery() {
			return $this->_query;
		}
		
		
		/**
		 * Get query cache alias
		 * 
		 * @param	string $cacheAlias
		 */
		public function getQueryCacheAlias() {
			$cacheAlias = '';
			foreach($this->getQuery() as $field => $value) {
				$cacheAlias.= substr($field, 0, 5);
				if($field == 'slug' || $field == 'search') {
					$cacheAlias.= $value;
				} else if(is_string($value) || is_numeric($value)) {
					$cacheAlias.= substr((string)$value, 0, 5);
				} else if(is_array($value)) {
					foreach($value as $item) {
						$cacheAlias.= (is_string($item)) ? substr($item, 0, 5) : '';
					}
				}
			}
			return $cacheAlias;			
		}
		
		
		/**
		 * Get products
		 * 
		 * @param	integer $limit
		 * @param	integer $skip
		 */
		public function getProducts($limit, $skip) {
			
			// add queries
			$this->addQuery('limit', $limit);
			$this->addQuery('skip', $skip);
			
			$result = jiffuyCache::getCache(jiffuyCache::generateCacheAlias('pgp', $this->getQueryCacheAlias()));
			
			if($result === false) {
				$result = $this->_api->getProduct($this->getQuery());
				if(isset($result['result']) && is_array($result['result'])) {
					foreach($result['result'] as $key => $item) {
						$result['result'][$key] = $this->_handleResultItem($item);
					}
					return jiffuyCache::setCache(jiffuyCache::generateCacheAlias('pgp', $this->getQueryCacheAlias()), $result, $this->getJiffuy()->getSettings('cacheExpires'));				
				}			
			} else {	
				return $result;		
			}
			
			return false;
			
		}
		
		
		/**
		 * Get product by slug
		 * 
		 * @param	string $slug
		 */
		public function getProductsBySlug($slug) {
			
			// add queries
			$this->addQuery('slug', $slug);
			$this->addQuery('limit', 1);
			$this->addQuery('skip', 0);
			
			$result = jiffuyCache::getCache(jiffuyCache::generateCacheAlias('pgpbs', $this->getQueryCacheAlias()));
			
			if($result === false) {
				$result = $this->_api->getProduct($this->getQuery());
				if(isset($result['count']) && $result['count'] > 0) {
					foreach($result['result'] as $key => $item) {
						$result['result'][$key] = $this->_handleResultItem($item);
					}
					return jiffuyCache::setCache(jiffuyCache::generateCacheAlias('pgpbs', $this->getQueryCacheAlias()), $result, $this->getJiffuy()->getSettings('cacheExpires'));
				}
			} else {				
				return $result;		
			}
			
			return false;
			
		}
		
		
		/**
		 * Get products by tags
		 * 
		 * @param	integer $limit
		 * @param	integer $skip
		 * @param	array $tags
		 * @param	array $tagsNot
		 * @param	array $tagsAll
		 */
		public function getProductsByTags($limit, $skip, array $tags = array(), array $tagsNot = array()) {
			
			// add queries
			$this->addQuery('tag', $tags);
			$this->addQuery('tagNot', $tagsNot);
			$this->addQuery('limit', $limit);
			$this->addQuery('skip', $skip);
			
			$result = jiffuyCache::getCache(jiffuyCache::generateCacheAlias('pgpbt', $this->getQueryCacheAlias()));
			
			if($result === false) {
				$result = $this->_api->getProduct($this->getQuery());
				if(isset($result['count']) && $result['count'] > 0) {
					foreach($result['result'] as $key => $item) {
						$result['result'][$key] = $this->_handleResultItem($item);
					}
					foreach($result['facet'] as $type => $item) {
						$result['facet'][$type] = $this->_handleFacetItem($type, $item);
					}
					return jiffuyCache::setCache(jiffuyCache::generateCacheAlias('pgpbt', $this->getQueryCacheAlias()), $result, $this->getJiffuy()->getSettings('cacheExpires'));
				}
			} else {
				return $result;
			}
			
			return false;
			
		}
		
		
		/**
		 * Get products by search
		 * 
		 * @param	integer $limit
		 * @param	integer $skip
		 * @param	string $search
		 */
		public function getProductsBySearch($limit, $skip, $search) {
			
			// add queries
			$this->addQuery('search', $search);
			$this->addQuery('limit', $limit);
			$this->addQuery('skip', $skip);
			
			$result = jiffuyCache::getCache(jiffuyCache::generateCacheAlias('pgpbs', $this->getQueryCacheAlias()));
			
			if($result === false) {
				$result = $this->_api->getProduct($this->getQuery());
				if(isset($result['count']) && $result['count'] > 0) {
					foreach($result['result'] as $key => $item) {
						$result['result'][$key] = $this->_handleResultItem($item);
					}
					foreach($result['facet'] as $type => $item) {
						$result['facet'][$type] = $this->_handleFacetItem($type, $item);
					}
					return jiffuyCache::setCache(jiffuyCache::generateCacheAlias('pgpbs', $this->getQueryCacheAlias()), $result, $this->getJiffuy()->getSettings('cacheExpires'));
				}
			} else {
				return $result;
			}
			
			return false;
			
		}
		
		
		/**
		 * Handle result item
		 * 
		 * @param	array $item
		 */
		private function _handleResultItem(array $item) {
			
			// init product
			$product = new jiffuyShopProduct($item);
			
			// init advertiser
			if(isset($item['advertiser']) && is_array($item['advertiser'])) {
				$product->setAdvertiser(new jiffuyShopAdvertiser($item['advertiser']));
			}
			
			// init redirect urls
			if(isset($item['url']['_id']['$id']) && is_string($item['url']['_id']['$id'])) {
				$product->setUrl($this->_jiffuy->getRedirectUrl().$item['url']['_id']['$id']);
			}
			
			return $product;
			
		}
		
		
		/**
		 * Handle facet item
		 * 
		 * @param	array $item
		 */
		private function _handleFacetItem($type, array $item) {
			
			$results = array();
			
			if(isset($item['terms']) && is_array($item['terms'])) {
				foreach($item['terms'] as $term) {
					if($type == 'color') {
						$results[] = array('slug' => $term['term'], 'name' => $term['term'], 'count' => $term['count']);
					} else {
						$term['term'] = explode('[:]', $term['term']);
						$results[] = array('slug' => $term['term'][0], 'name' => $term['term'][1], 'count' => $term['count']);
					}
				}
			}
			
			return jiffuy::arraySortByColumn($results, 'name');
			
		}
		
		
	}
	
}

