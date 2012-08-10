<?php

/**
 *
 * Class for building the jiffuy plugin
 *
 * @category   jiffuy
 * @package    shop
 * @subpackage controller home
 * @copyright  2012 jiffuy (http://www.jiffuy.com)
 * @license    Closed Source, All Rights Reserved
 * @author     jiffuy <info@jiffuy.com>
 */
if(!class_exists('jiffuyShopControllerHome')) {
	
	class jiffuyShopControllerHome extends jiffuyShopTemplateProductOverview {
		
		
		/**
		 * Constructor
		 * 
		 * @param	jiffuy $jiffuy
		 * @param	jiffuyShopModelProduct $modelProduct
		 */
		public function __construct(jiffuy $jiffuy, jiffuyShopModelProduct $modelProduct) {
			
			// construct parent
			parent::__construct($jiffuy, $modelProduct, 'home');
			
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
			
			// set category
			$this->_setActiveCategory();
			
			// only index if page one
			if($this->getJiffuy()->getShop()->getRoute('page') > 1) { $this->setMetaRobots('noindex'); }
			
			// set home tags if page equals one
			$tags = ($this->getJiffuy()->getShop()->getRoute('page') == 1 && count($this->getJiffuy()->getShop()->getParams()) == 0) ? $this->_activeCategory->getTags() : array();
			$tagsNot = ($this->getJiffuy()->getShop()->getRoute('page') == 1 && count($this->getJiffuy()->getShop()->getParams()) == 0) ? $this->_activeCategory->getTagsNot() : array();
			
			// fetch products
			$skip =  (!is_null($this->getJiffuy()->getShop()->getRoute('page'))) ? ($this->getJiffuy()->getShop()->getRoute('page') - 1) * $this->getJiffuy()->getSettings('shopProductsPerPage') : 0;
			$products = $this->getModelProduct()->getProductsByTags($this->getJiffuy()->getSettings('shopProductsPerPage'), $skip, $tags, $tagsNot);
			
			// check if products exist
			if(is_array($products['result']) && count($products['result']) > 0) {
				
				$this->setProducts($products);
				$this->setPagination(new jiffuyLibraryPagination($products['count'], $this->getJiffuy()->getShop()->getRoute('page'), $this->getJiffuy()->getSettings('shopProductsPerPage')));
				
			} else { $this->setMetaRobots('noindex'); }
			
			// build and render
			$this->build();
			print $this->render();
			
		}
		
		
		/**
		 * Set active category
		 * 
		 * @param	none
		 */
		private function _setActiveCategory() {
			
			// init category
			$categories = $this->getJiffuy()->getShop()->getCategories();
			$this->setActiveCategory($categories['home']);
			
		}
			
		
	}
	
}

