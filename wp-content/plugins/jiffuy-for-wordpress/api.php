<?php

/**
 * @package jiffuy
 * @version 1.02
 * 
 * License: Subscription - All rights reserved!
 * Notice: Every distribution of the source code without authors permission is strictly forbidden, for a written permission please contact info@jiffuy.com!
 */

if(!class_exists('jiffuyApi')) {
	
	class jiffuyApi {
		
		
		/**
		 * Remote get params
		 * @var string
		 */
		protected $_remoteGetParams = array(
			'timeout' => 10000
		);
		
		/**
		 * Api default params
		 * @var string
		 */
		protected $_defaultParams = array(
			'format' => 'json',
			'key' => null,
			'secret' => null,
		);
		
		/**
		 * Setup default api urls
		 * @var string
		 */
		const API_URL_PRODUCT = 'http://www.jiffuy.com/api/product';
		const API_URL_CATEGORY = 'http://www.jiffuy.com/api/category';
		
		
		/**
		 * Construct
		 * 
		 * @param	string $key
		 * @param	string $secret
		 */
		public function __construct($key, $secret) {
			
			// init vars
			$this->_defaultParams['key'] = $key;
			$this->_defaultParams['secret'] = $secret;
			
		}
		
		
		/**
		 * Test connection
		 * 
		 * @param	none
		 */
		public function testConnection() {
			
			// fetch test data
			$result = $this->_fetchDataByParams(self::API_URL_PRODUCT, array('id' => 'none'));
			
			if(is_array($result)) {
				return true;
			}
			
			return false;
			
		}
		
		
		/**
		 * Get category data
		 * 
		 * @param	array $params
		 */
		public function getCategory(array $params) {
			
			// fetch data by params
			return $this->_fetchDataByParams(self::API_URL_CATEGORY, $params);
			
		}
		
		
		/**
		 * Get product data
		 * 
		 * @param	array $params
		 */
		public function getProduct(array $params) {
			
			// fetch data by params
			return $this->_fetchDataByParams(self::API_URL_PRODUCT, $params);
			
		}
		
		
		/**
		 * Fetch data by params
		 * 
		 * @param	string $url
		 * @param	array $params
		 */
		public function _fetchDataByParams($url, array $params) {
			
			// query params
			$params = array_merge($this->_defaultParams, $params);
			
			$paramUrl = '/?'.self::buildHttpQuery($params);
			$response = wp_remote_get($url.$paramUrl, $this->_remoteGetParams);
			
			if(is_array($response) && isset($response['body'])) {
				$response = json_decode($response['body'], true);
				if(isset($response['status']) && $response['status'] == 'success' && isset($response['result'])) {
					return $response['result'];
				}
			}
			
			return false;
			
		}
		
		
		/**
		 * Build http query
		 * 
		 * @param	array $params
		 */
		public static function buildHttpQuery($params, $prefix = '', $removeFinalAmp = true) {
			$queryString = '';
			if(is_array($params)) {
				foreach ($params as $key => $value) {
					$correctKey = $prefix;
					if('' === $prefix) {
						$correctKey .= $key;
					} else {
						$correctKey .= '['.$key.']';
					}
					if(!is_array($value)) {
						$queryString .= urlencode($correctKey).'='.urlencode($value).'&';
					} else {
						$queryString .= self::buildHttpQuery($value, $correctKey, false);
					}
				}
			}
			if($removeFinalAmp === true) {
				return substr($queryString, 0, strlen($queryString) - 1);
			} else {
				return $queryString;
			}
		}
				
		
	}
	
}

