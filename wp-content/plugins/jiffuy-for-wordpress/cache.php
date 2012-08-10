<?php

/**
 * @package jiffuy
 * @version 1.02
 * 
 * License: Subscription - All rights reserved!
 * Notice: Every distribution of the source code without authors permission is strictly forbidden, for a written permission please contact info@jiffuy.com!
 */

if(!class_exists('jiffuyCache')) {
	
	class jiffuyCache {
		
		
		/**
		 * Lifetime in days (default 7 days)
		 * @var integer
		 */
		protected static $_lifetime = 7;
		
		
		/**
		 * Set cache by alias
		 * 
		 * @param	string $alias cache alias
		 * @param	string $content cache content
		 * @param	integer $lifetime lifetime in hours (optional)
		 */
		public static function setCache($alias, $content, $lifetime = null) {
			
			// set transient
			$lifetime = 60 * 60 * 24 * ((!is_null((int)$lifetime)) ? $lifetime : self::$_lifetime);
			set_transient($alias, array('data' => $content, 'time' => time()), $lifetime);
			
			return $content;
			
		}
		
		
		/**
		 * Get cache by alias
		 * 
		 * @param	string $alias cache alias
		 */
		public static function getCache($alias) {
			
			// get transient by cache hash
			$cache = get_transient($alias);			
			if($cache !== false && isset($cache['data'])) {
				return $cache['data'];
			}
			
			return false;
			
		}
		
		
		/**
		 * Get cache alias
		 * 
		 * @param	string $alias cache alias
		 * @param	string $token cache alias string for token
		 * @param	string $tokenUrl use url token (default: false)
		 */
		public static function generateCacheAlias($alias, $token = null, $tokenUrl = false) {
			$alias = 'jy_'.$alias;
			$alias = (!is_null($token)) ? $alias.'_'.md5($token) : $alias;
			$alias = ($tokenUrl) ? $alias.'_'.md5($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) : $alias;
			return substr($alias, 0, 45);
		}
		
		
		/**
		 * Flush cache
		 * 
		 * @param	none
		 */
		public static function flushCache() {
			$GLOBALS['wpdb']->query("DELETE FROM ".$GLOBALS['wpdb']->options . " WHERE option_name LIKE '_transient%_jy_%'");
			$GLOBALS['wpdb']->query("OPTIMIZE TABLE ".$GLOBALS['wpdb']->options);
		}
		
		
		/**
		 * Delete cache by url
		 * 
		 * @param	string $alias cache alias
		 */
		private static function _deleteCache($alias) {
			
			// delete transient
			delete_transient($alias);
			
			return true;
			
		}
		
		
	}
	
}



