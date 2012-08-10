<?php

/**
 * @package jiffuy
 * @version 1.02
 * 
 * License: Subscription - All rights reserved!
 * Notice: Every distribution of the source code without authors permission is strictly forbidden, for a written permission please contact info@jiffuy.com!
 */

if(!class_exists('jiffuyShopAdvertiser')) {
	
	class jiffuyShopAdvertiser {
		
		
		/**
		 * Slug
		 * @var string
		 */
		protected $_id;
		
		/**
		 * Slug
		 * @var string
		 */
		protected $_slug;
		
		/**
		 * Name
		 * @var string
		 */
		protected $_name;
		
		
		/**
		 * Construct
		 * 
		 * @param	array $properties
		 */
		public function __construct(array $properties = array()) {
			foreach($properties as $name => $value){
				$this->{'_'.$name} = $value;
			}
		}
		
		
		/**
		 * Sets the id
		 * 
		 * @param	string $slug
		 */
		public function setId($id) {
			$this->_id = $id;
			return $this;
		}
		
		
		/**
		 * Gets the id
		 * 
		 * @param	none
		 */
		public function getId() {
			return $this->_id;
		}
		
		
		/**
		 * Sets the slug
		 * 
		 * @param	string $slug
		 */
		public function setSlug($slug) {
			$this->_slug = $slug;
			return $this;
		}
		
		
		/**
		 * Gets the slug
		 * 
		 * @param	string $slug
		 */
		public function getSlug($slug) {
			return $this->_slug;
		}
		
		
		/**
		 * Sets the name
		 * 
		 * @param	string $name
		 */
		public function setName($name) {
			$this->_name = $name;
			return $this;
		}
		
		
		/**
		 * Gets the name
		 * 
		 * @param	none
		 */
		public function getName() {
			return $this->_name;
		}
		
		
	}
	
}

