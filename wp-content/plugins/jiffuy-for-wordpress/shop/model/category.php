<?php

/**
 *
 * Class for building the jiffuy plugin
 *
 * @category   jiffuy
 * @package    shop
 * @subpackage model category
 * @copyright  2012 jiffuy (http://www.jiffuy.com)
 * @license    Closed Source, All Rights Reserved
 * @author     jiffuy <info@jiffuy.com>
 */
if(!class_exists('jiffuyShopModelCategory')) {
	
	class jiffuyShopModelCategory {
		
		
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
		 * Construct
		 * 
		 * @param	jiffuy $jiffuy
		 */
		public function __construct(jiffuy $jiffuy) {
			
			// init vars
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
		 * Get category by slug
		 * 
		 * @param	string $slug
		 */
		public function getCategoryBySlug($slug) {
			
			$result = jiffuyCache::getCache(jiffuyCache::generateCacheAlias('cgcbs', $slug));
			
			if($result === false) {
				$result = $this->_api->getCategory(array('slug' => $slug, 'limit' => 1, 'skip' => 0));			
				if(isset($result['result']) && is_array($result['result']) && count($result['result']) == 1) {
					foreach($result['result'] as $key => $item) {
						$result['result'][$key] = new jiffuyShopCategory($item);
					}
					return jiffuyCache::setCache(jiffuyCache::generateCacheAlias('cgcbs', $slug), $result, $this->getJiffuy()->getSettings('cacheExpires'));
				}
			} else {
				return $result;
			}
			
			return false;
			
		}
		
		
		/**
		 * Get categories by level
		 * 
		 * @param	integer $level
		 */
		public function getCategoriesByLevel($level) {
			
			$result = jiffuyCache::getCache(jiffuyCache::generateCacheAlias('cgcbl', $level));
			
			if($result === false) {
				$result = $this->_api->getCategory(array('level' => (int)$level));			
				if(isset($result['result']) && is_array($result['result']) && count($result['result']) > 0) {
					foreach($result['result'] as $key => $item) {
						$result['result'][$key] = new jiffuyShopCategory($item);
					}
					return jiffuyCache::setCache(jiffuyCache::generateCacheAlias('cgcbl', $level), $result, $this->getJiffuy()->getSettings('cacheExpires'));
				}
			} else {
				return $result;
			}
			
			return false;
			
		}
		
		
	}
	
}

