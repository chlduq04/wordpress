<?php

/**
 *
 * Class for building a pagination
 *
 * @category   jiffuy
 * @package    library
 * @subpackage pagination
 * @copyright  2012 jiffuy (http://www.jiffuy.com)
 * @license    Closed Source, All Rights Reserved
 * @author     jiffuy <info@jiffuy.com>
 */
if(!class_exists('jiffuyLibraryPagination')) {
	
	class jiffuyLibraryPagination {
		
		/**
		 * Items total
		 * @var integer
		 */
		protected $_itemsTotal = null;
		
		/**
		 * Current page
		 * @var integer
		 */
		protected $_currentPage = null;
		
		/**
		 * Items per page
		 * @var integer
		 */
		protected $_itemsPerPage = 20;
		
		/**
		 * Page display count
		 * @var integer
		 */
	   protected $_pageDisplayCount = 10;
	   
	   /**
		 * Pages total
		 * @var integer
		 */
	   protected $_pagesTotal = null;
	   
	   
	   /**
	    * Construct
	    * 
	    * @param	integer $itemsTotal
	    * @param	integer $currentPage default 0
	    * @param	integer $itemsPerPage default 20
	    * @param	integer $pageDisplayCount default 10
	    */
	   public function __construct($itemsTotal, $currentPage = null, $itemsPerPage = null, $pageDisplayCount = null) {
	   	
	   	// init vars
	   	$this->_itemsTotal = (int)$itemsTotal;
	   	$this->_currentPage = (int)$currentPage;
	   	$this->_itemsPerPage = (!is_null($itemsPerPage)) ? (int)$itemsPerPage : $this->_itemsPerPage;
	   	$this->_pageDisplayCount = (!is_null($pageDisplayCount)) ? (int)$pageDisplayCount : $this->_pageDisplayCount;
	   	$this->_pagesTotal = (int)ceil($this->_itemsTotal / $this->_itemsPerPage);
	   	
	   }
	   
	   
	   /**
	    * Build pagination
	    * 
	    */
	   public function buildPagination() {
	   	
	   	// check if pages exist
	   	if($this->_pagesTotal > 1) {
	   		
	   		// build pagination array
	   		return $this->_buildPagination();
	   		
	   	}
	   	
	   	return false;
	   	
	   }
	   
	   
	   /**
	    * Build pagination array
	    * 
	    * @param	none
	    */
	   private function _buildPagination() {
	   	
	   	$pagination = array();
	   	 	
	   	$pageStart = $this->_currentPage - ($this->_pageDisplayCount / 2);
	   	$pageEnd = $this->_currentPage + ($this->_pageDisplayCount / 2);
	   	
	   	// check if start page exist
	   	if($pageStart < 1) {
	   		$pageEnd = $pageEnd + ($pageStart * -1);
	   		$pageStart = 1;
	   	}
	   	
	   	// check if end page exist
	   	$pageEnd = ($pageEnd > $this->_pagesTotal) ? $this->_pagesTotal : $pageEnd;
	   	
	   	// build pagination tree
	   	for($i = $pageStart; $i <= $pageEnd; $i++) {
	   		$pagination[$i] = ($i == $this->_currentPage) ? true : false;
	   	}
	   	
	   	return $this->_buildPaginationArray($pageStart, $pageEnd, $pagination);
	   	
	   }
	   
	   
	   /**
	    * Build pagination array
	    * 
	    * @param	integer $pageStart page to start
	    * @param	integer $pageEnd page to end
	    * @param	integer $pagination pagination array
	    */
	   private function _buildPaginationArray($pageStart, $pageEnd, array $pagination) {
	   	
	   	return array(
	   		'previous' => $this->_buildPaginationPreviousArray($pageStart, $pageEnd),
	   		'paginationStart' => $this->_buildPaginationStartArray($pageStart),
	   		'pagination' => $pagination,
	   		'paginationEnd' => $this->_buildPaginationEndArray($pageEnd),
	   		'next' => $this->_buildPaginationNextArray($pageStart, $pageEnd),
	   	);
	   	
	   }
	   
	   
	   /**
	    * Build pagination start array
	    * 
	    * @param	integer $pageStart page to start
	    */
	   private function _buildPaginationStartArray($pageStart) {
	   	return ($pageStart > 1) ? array('1' => false) : false;
	   }
	   
	   
		/**
	    * Build pagination end array
	    * 
	    * @param	integer $pageEnd page to end
	    */
	   private function _buildPaginationEndArray($pageEnd) {
	   	return ($pageEnd < $this->_pagesTotal) ? array($this->_pagesTotal => false) : false;
	   }
	   
	   
	   /**
	    * Build pagination previous array
	    * 
	    * @param	integer $pageStart page to start
	    * @param	integer $pageEnd page to end
	    */
	   private function _buildPaginationPreviousArray($pageStart, $pageEnd) {
	   	return ($this->_currentPage > 1) ? array(($this->_currentPage - 1) => false) : false;
	   }
	   
	   
		/**
	    * Build pagination next array
	    * 
	    * @param	integer $pageStart page to start
	    * @param	integer $pageEnd page to end
	    */
	   private function _buildPaginationNextArray($pageStart, $pageEnd) {
	   	return ($this->_currentPage < $this->_pagesTotal) ? array(($this->_currentPage + 1) => false) : false;
	   }
	   
	   
	}
		
	
}

