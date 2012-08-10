<?php

/**
 * @package jiffuy
 * @version 1.02
 * 
 * License: Subscription - All rights reserved!
 * Notice: Every distribution of the source code without authors permission is strictly forbidden, for a written permission please contact info@jiffuy.com!
 */

if(!class_exists('jiffuyShopProduct')) {
	
	class jiffuyShopProduct {
		
		/**
		 * Id
		 * @var string
		 */
		protected $_id;
		
		/**
		 * Slug
		 * @var string
		 */
		protected $_slug;
		
		/**
		 * Currency
		 * @var string
		 */
		protected $_currency;
		
		/**
		 * Price
		 * @var float
		 */
		protected $_price;
		
		/**
		 * Price
		 * @var float
		 */
		protected $_priceOld;
		
		/**
		 * Price
		 * @var float
		 */
		protected $_priceShipping;
		
		/**
		 * Brand
		 * @var string
		 */
		protected $_brand;
		
		/**
		 * Gender
		 * @var string
		 */
		protected $_gender;
		
		/**
		 * Title
		 * @var string
		 */
		protected $_title;
		
		/**
		 * Desctiption
		 * @var string
		 */
		protected $_description;
	
		/**
		 * Color
		 * @var array
		 */
		protected $_color;
		
		/**
		 * Image
		 * @var array
		 */
		protected $_image;
		
		/**
		 * Tags
		 * @var array
		 */
		protected $_tag;
		
		/**
		 * Product categories
		 * @var array
		 */
		protected $_typeCategory;
	
		/**
		 * Product keywords
		 * @var array
		 */
		protected $_typeKeyword;
		
		/**
		 * Category
		 * @var jiffuyShopCategory
		 */
		protected $_category;
		
		/**
		 * Advertiser
		 * @var jiffuyShopAdvertiser
		 */
		protected $_advertiser;
		
		
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
		 * @param	string $id
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
		 * Sets the article id
		 * 
		 * @param	string $articleId
		 */
		public function setArticleId($articleId) {
			$this->_articleId = $articleId;
			return $this;
		}
		
		
		/**
		 * Gets the article id
		 * 
		 * @param	none
		 */
		public function getArticleId() {
			return $this->_articleId;
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
		 * Sets the currency
		 * 
		 * @param	string $currency
		 */
		public function setCurrency($currency) {
			$this->_currency = $currency;
			return $this;
		}
		
		
		/**
		 * Gets the currency
		 * 
		 * @param	none
		 */
		public function getCurrency() {
			return $this->_currency;
		}
		
		
		/**
		 * Sets the price
		 * 
		 * @param	string $price
		 */
		public function setPrice($price) {
			$this->_price = $price;
			return $this;
		}
		
		
		/**
		 * Gets the price
		 * 
		 * @param	none
		 */
		public function getPrice() {
			return $this->_price;
		}
		
		
		/**
		 * Sets the price old
		 * 
		 * @param	string $priceOld
		 */
		public function setPriceOld($priceOld) {
			$this->_priceOld = $priceOld;
			return $this;
		}
		
		
		/**
		 * Gets the price old
		 * 
		 * @param	none
		 */
		public function getPriceOld() {
			return $this->_priceOld;
		}
		
		
		/**
		 * Sets the price shipping
		 * 
		 * @param	string $priceShipping
		 */
		public function setPriceShipping($priceShipping) {
			$this->_priceShipping = $priceShipping;
			return $this;
		}
		
		
		/**
		 * Gets the price shipping
		 * 
		 * @param	none
		 */
		public function getPriceShipping() {
			return $this->_priceShipping;
		}
		
		
		/**
		 * Sets the brand
		 * 
		 * @param	string $brand
		 */
		public function setBrand($brand) {
			$this->_brand = $brand;
			return $this;
		}
		
		
		/**
		 * Gets the brand
		 * 
		 * @param	string $param
		 */
		public function getBrand($param = 'name') {
			if(is_array($this->_brand) && isset($this->_brand[$param]) && !is_null($this->_brand[$param]) > 0) {
				return $this->_brand[$param];
			}			
			return false;			
		}
		
		
		/**
		 * Sets the gender
		 * 
		 * @param	string $gender
		 */
		public function setGender($gender) {
			$this->_gender = $gender;
			return $this;
		}
		
		
		/**
		 * Gets the gender
		 * 
		 * @param	none
		 */
		public function getGender() {
			return $this->_gender;
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
		 * Gets the title
		 * 
		 * @param	none
		 */
		public function getTitle() {
			return $this->_title;
		}
		
		
		/**
		 * Sets the description
		 * 
		 * @param	string $title
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
		 * Sets the color collection
		 * 
		 * @param	array $color
		 */
		public function setColor($color) {
			$this->_color = $color;
			return $this;
		}
		
		
		/**
		 * Gets the color collection
		 * 
		 * @param	none
		 */
		public function getColor() {
			return $this->_color;
		}
		
		
		/**
		 * Sets the image collection
		 * 
		 * @param	array $image
		 */
		public function setImage($image) {
			$this->_image = $image;
			return $this;
		}
		
		
		/**
		 * Gets the image collection
		 * 
		 * @param	none
		 */
		public function getImage() {
			return $this->_image;
		}
		
		
		/**
		 * Sets the tag collection
		 * 
		 * @param	array $tag
		 */
		public function setTag($tag) {
			$this->_tag = $tag;
			return $this;
		}
		
		
		/**
		 * Gets the tag collection
		 * 
		 * @param	none
		 */
		public function getTag() {
			return $this->_tag;
		}
		
		
		/**
		 * Sets the type category collection
		 * 
		 * @param	array $typeCategory
		 */
		public function setTypeCategory($typeCategory) {
			$this->_typeCategory = $typeCategory;
			return $this;
		}
		
		
		/**
		 * Gets the type category collection
		 * 
		 * @param	none
		 */
		public function getTypeCategory() {
			return $this->_typeCategory;
		}
		
		
		/**
		 * Sets the type keyword collection
		 * 
		 * @param	array $typeKeyword
		 */
		public function setTypeKeyword($typeKeyword) {
			$this->_typeKeyword = $typeKeyword;
			return $this;
		}
		
		
		/**
		 * Gets the type keyword collection
		 * 
		 * @param	none
		 */
		public function getTypeKeyword() {
			return $this->_typeKeyword;
		}
		
		
		/**
		 * Sets the product category
		 * 
		 * @param	jiffuyShopCategory $category
		 */
		public function setCategory(jiffuyShopCategory $category) {
			$this->_category = $category;
			return $this;
		}
		
		
		/**
		 * Gets the product category
		 * 
		 * @param	none
		 */
		public function getCategory() {
			return $this->_category;
		}
		
		
		/**
		 * Sets the product advertiser
		 * 
		 * @param	jiffuyShopAdvertiser $advertiser
		 */
		public function setAdvertiser(jiffuyShopAdvertiser $advertiser) {
			$this->_advertiser = $advertiser;
			return $this;
		}
		
		
		/**
		 * Gets the product advertiser
		 * 
		 * @param	none
		 */
		public function getAdvertiser() {
			return $this->_advertiser;
		}
		
		
	}
	
}

