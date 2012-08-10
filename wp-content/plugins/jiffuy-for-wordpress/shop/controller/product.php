<?php

/**
 *
 * Class for building the jiffuy plugin
 *
 * @category   jiffuy
 * @package    shop
 * @subpackage controller product
 * @copyright  2012 jiffuy (http://www.jiffuy.com)
 * @license    Closed Source, All Rights Reserved
 * @author     jiffuy <info@jiffuy.com>
 */
if(!class_exists('jiffuyShopControllerProduct')) {
	
	class jiffuyShopControllerProduct extends jiffuyShopTemplateProductDetail {
		
		
		/**
		 * Constructor
		 * 
		 * @param	jiffuy $jiffuy
		 * @param	jiffuyShopModelProduct $modelProduct
		 */
		public function __construct(jiffuy $jiffuy, jiffuyShopModelProduct $modelProduct) {
			
			// construct parent
			parent::__construct($jiffuy, $modelProduct, 'product');
			
			// init filters and actions
			$this->addFilters();
			$this->addActions();
			
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
		 * Index
		 * 
		 * @param	none
		 */
		public function index() {
				
			// fetch products
			$product = $this->getModelProduct()->getProductsBySlug($this->getJiffuy()->getShop()->getRoute('slug'));
			$product = ($product !== false) ? current($product['result']) : false;
			
			if($product !== false) {
				
				// init category
				$category = new jiffuyShopCategory();
				$category->setTags($product->getTag());
				$this->setActiveCategory($category);
					
				// set product and category
				$this->setProduct($product);
				$this->setMetaRobots('noindex');
					
				// build and render
				$this->build();
				print $this->render();
					
			} else { $this->getJiffuy()->getErrorPage(); }
			
		}
			
		
	}
	
}

