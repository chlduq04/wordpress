<?php

/**
 * @package jiffuy
 * @version 1.02
 * 
 * License: Subscription - All rights reserved!
 * Notice: Every distribution of the source code without authors permission is strictly forbidden, for a written permission please contact info@jiffuy.com!
 */

if(!class_exists('jiffuyShopCategory')) {
	
	class jiffuyShopCategory {
		
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
		 * Title
		 * @var string
		 */
		protected $_title;
		
		/**
		 * Description
		 * @var string
		 */
		protected $_description;
		
		/**
		 * Meta description
		 * @var string
		 */
		protected $_metaDescription;
		
		/**
		 * Tags to include
		 * @var array
		 */
		protected $_tags;
		
		/**
		 * Tags to exclude
		 * @var array
		 */
		protected $_tagsNot;
		
		
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
		 * @param	none
		 */
		public function getSlug() {
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
		
		
		/**
		 * Sets the title
		 * 
		 * @param	string $title
		 */
		public function setTitle($title) {
			$this->_title = $title;
			return $this;
		}
		
		
		/**
		 * Gets the description
		 * 
		 * @param	none
		 */
		public function getTitle() {
			return $this->_title;
		}
		
		
		/**
		 * Sets the description
		 * 
		 * @param	string $description
		 */
		public function setDescription($description) {
			$this->_description = $description;
			return $this;
		}
		
		
		/**
		 * Gets the description
		 * 
		 * @param	none
		 */
		public function getDescription() {
			return $this->_description;
		}
		
		
		/**
		 * Sets the meta description
		 * 
		 * @param	string $metaDescription
		 */
		public function setMetaDescription($metaDescription) {
			$this->_metaDescription = $metaDescription;
			return $this;
		}
		
		
		/**
		 * Gets the meta description
		 * 
		 * @param	none
		 */
		public function getMetaDescription() {
			return $this->_metaDescription;
		}
		
		
		/**
		 * Sets the tags collection
		 * 
		 * @param	array $tags
		 */
		public function setTags(array $tags) {
			$this->_tags = $tags;
			return $this;
		}
		
		
		/**
		 * Gets the tags collection
		 * 
		 * @param	none
		 */
		public function getTags() {
			return $this->_tags;
		}
		
		
		/**
		 * Sets the tags not collection
		 * 
		 * @param	array $tagsNot
		 */
		public function setTagsNot(array $tagsNot) {
			$this->_tagsNot = $tagsNot;
			return $this;
		}
		
		
		/**
		 * Gets the tags not collection
		 * 
		 * @param	none
		 */
		public function getTagsNot() {
			return $this->_tagsNot;
		}
		
		
	}
	
}

