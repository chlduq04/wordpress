<?php

/**
 *
 * Class for building the jiffuy plugin
 *
 * @category   jiffuy
 * @package    shop
 * @subpackage controller search
 * @copyright  2012 jiffuy (http://www.jiffuy.com)
 * @license    Closed Source, All Rights Reserved
 * @author     jiffuy <info@jiffuy.com>
 */
if(!class_exists('jiffuyShopControllerSearch')) {
	
	class jiffuyShopControllerSearch extends jiffuyShopTemplateProductOverview {
		
		
		/**
		 * Constructor
		 * 
		 * @param	jiffuy $jiffuy
		 * @param	jiffuyShopModelProduct $modelProduct
		 */
		public function __construct(jiffuy $jiffuy, jiffuyShopModelProduct $modelProduct) {
			
			// construct parent
			parent::__construct($jiffuy, $modelProduct, 'search');
			
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
			
			$searchQueryString = str_replace('-', ' ', $this->getJiffuy()->getShop()->getParams('q'));
			
			// init search query and category
			$this->setSearchQuery($searchQueryString);			
			$this->_setActiveCategory();
					
			// set products
			$skip =  (!is_null($this->getJiffuy()->getShop()->getRoute('page'))) ? ($this->getJiffuy()->getShop()->getRoute('page') - 1) * $this->getJiffuy()->getSettings('shopProductsPerPage') : 0;
			$products = $this->getModelProduct()->getProductsBySearch($this->getJiffuy()->getSettings('shopProductsPerPage'), $skip, $searchQueryString);
			
			// check if products exist
			if(is_array($products['result']) && count($products['result']) > 0) {
						
				$this->setProducts($products);
				$this->setPagination(new jiffuyLibraryPagination($products['count'], $this->getJiffuy()->getShop()->getRoute('page'), $this->getJiffuy()->getSettings('shopProductsPerPage')));
						
			}
			
			// not index search pages
			$this->setMetaRobots('noindex');
			
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
			$category = new jiffuyShopCategory();
			$category->setSlug('search');
			$category->setTitle(__('Suche').': '.$this->getSearchQuery());
			$this->setActiveCategory($category);
			
		}
			
		
	}
	
}

