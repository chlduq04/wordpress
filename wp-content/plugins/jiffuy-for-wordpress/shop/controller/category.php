<?php

/**
 *
 * Class for building the jiffuy plugin
 *
 * @category   jiffuy
 * @package    shop
 * @subpackage controller category
 * @copyright  2012 jiffuy (http://www.jiffuy.com)
 * @license    Closed Source, All Rights Reserved
 * @author     jiffuy <info@jiffuy.com>
 */
if(!class_exists('jiffuyShopControllerCategory')) {
	
	class jiffuyShopControllerCategory extends jiffuyShopTemplateProductOverview {
		
		
		/**
		 * Constructor
		 * 
		 * @param	jiffuy $jiffuy
		 * @param	jiffuyShopModelProduct $modelProduct
		 */
		public function __construct(jiffuy $jiffuy, jiffuyShopModelProduct $modelProduct) {
			
			// construct parent
			parent::__construct($jiffuy, $modelProduct, 'category');
			
			// init filters and actions
			$this->addFilters();
			$this->addActions();
			
			// set category
			$this->_setActiveCategory();
			
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
			
			if($this->getActiveCategory() instanceof jiffuyShopCategory) {
				
				// only index if page one
				if($this->getJiffuy()->getShop()->getRoute('page') > 1) { $this->setMetaRobots('noindex'); }
				
				// set products
				$skip =  (!is_null($this->getJiffuy()->getShop()->getRoute('page'))) ? ($this->getJiffuy()->getShop()->getRoute('page') - 1) * $this->getJiffuy()->getSettings('shopProductsPerPage') : 0;
				$products = $this->getModelProduct()->getProductsByTags($this->getJiffuy()->getSettings('shopProductsPerPage'), $skip, $this->getActiveCategory()->getTags(), $this->getActiveCategory()->getTagsNot());
				
				// check if products exist
				if(is_array($products['result']) && count($products['result']) > 0) {
					
					$this->setProducts($products);
					$this->setPagination(new jiffuyLibraryPagination($products['count'], $this->getJiffuy()->getShop()->getRoute('page'), $this->getJiffuy()->getSettings('shopProductsPerPage')));
					
				} else { $this->setMetaRobots('noindex'); }
				
				// build and render
				$this->build();
				print $this->render();
				
			} else { $this->getJiffuy()->getErrorPage(); }
			
		}
		
		
		/**
		 * Set active category
		 * 
		 * @param	none
		 */
		private function _setActiveCategory() {
			
			// get available categories
			$categories = $this->getJiffuy()->getShop()->getCategories();
			
			if(array_key_exists($this->getJiffuy()->getShop()->getRoute('slug'), $categories)) {
				
				// init category
				$this->setActiveCategory($categories[$this->getJiffuy()->getShop()->getRoute('slug')]);
				
			}
			
		}
		
		
	}
	
}

