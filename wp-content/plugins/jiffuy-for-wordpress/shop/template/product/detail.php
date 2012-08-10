<?php

/**
 * @package jiffuy
 * @version 1.02
 * 
 * License: Subscription - All rights reserved!
 * Notice: Every distribution of the source code without authors permission is strictly forbidden, for a written permission please contact info@jiffuy.com!
 */

if(!class_exists('jiffuyShopTemplateProductDetail')) {
	
	class jiffuyShopTemplateProductDetail extends jiffuyShopTemplate {
		
		
		/**
		 * Product object
		 * @var jiffuyShopProduct
		 */
		protected $_product = null;
		
		
		/**
		 * Construct
		 * 
		 * @param	jiffuy $jiffuy
		 * @param	jiffuyShopModelProduct $modelProduct
		 * @param	string $type template type
		 */
		public function __construct(jiffuy $jiffuy, jiffuyShopModelProduct $modelProduct, $type) {
			
			// set headers
			header("HTTP/1.0 200 OK");
			header("Status: 200");
			
			// parent construct
			parent::__construct($jiffuy, $modelProduct, $type);
			
			// init template
			$this->initialize();
			
		}
		
		
		/**
		 * Initialize template by product data
		 * 
		 */
		public function initialize() {
			
			// init template class
			$this->setClass('jiffuyShopDetail');
			
		}
		
		
		/**
		 * Sets the product to view
		 * 
		 * @param	jiffuyShopProduct $product
		 */
		public function setProduct(jiffuyShopProduct $product) {
			$this->_product = $product;
			return $this;
		}
		
		
		/**
		 * Gets the product to view
		 */
		public function getProduct() {
			return $this->_product;
		}
		
		
		/**
		 * Build template
		 * 
		 */
		public function build() {
			
			if(!is_null($this->getActiveCategory()) && !is_null($this->getProduct())) {
				
				// set title
				$title = $this->getProduct()->getTitle() . ((!is_null($this->getActiveCategory()->getTitle())) ? $this->getActiveCategory()->getTitle() : '');
				$this->setTitle($title);
				
				// build template
				$this->setMetaKeywords($this->_buildMetaKeywords());
				$this->setMetaDescription($this->_buildMetaDescription());
				$this->setMetaRobots($this->_buildMetaRobots());
				
				$this->setContent($this->_buildContent());
			
			} else {
				throw new Exception('Cannot build detail template without an active category and products.');
			}
			
		}
		
		
		/**
		 * Build meta keywords
		 * 
		 */
		private function _buildMetaDescription() {
			
			$description = $this->_product->getDescription();
			if(strlen($description) > 0) {
				return ((strlen($this->_product->getTitle()) > 0 ? $this->_product->getTitle().': ' : '')).$description;
			}
			
			return false;
			
		}
		
		
		/**
		 * Build meta keywords
		 * 
		 */
		private function _buildMetaKeywords() {
			
			$keywords = $this->_product->getTag();
			if(is_array($keywords) && count($keywords) > 0) {
				return implode(',', $keywords);
			}
			
			return false;
			
		}
		
		
		/**
		 * Build meta robots
		 * 
		 */
		private function _buildMetaRobots() {
			
			// set meta robots
			return ($this->getJiffuy()->getSettings('shopProductsIndexDetailPages') === true) ? 'index,follow' : 'noindex,follow';
			
		}
		
		
		/**
		 * Build product content
		 * 
		 */
		private function _buildContent() {
			
			if($this->isSearchEngineReferrer()) {
				$content = '<p class="back">'.__('Nicht das was Sie gesucht haben?').' <a href="/'.$this->getJiffuy()->getShop()->getBaseUrl().'">'.__('Mehr Produkte zu Ihrer Suche!').'</strong></a></p>'."\n";
			} else {
				$content = '<p class="back"><a onclick="history.back();return false;" href="javascript:void(0);" rel="nofollow">'.__('Zurück zur Übersicht').'</a></p>'."\n";
			}
			
			$content.= '<div itemscope itemtype="http://data-vocabulary.org/Product">'."\n";
			
			$content.= '<h2><a href="'.$this->getJiffuy()->getAffiliateUrl($this->_product->getId()).'" title="'.esc_attr($this->_product->getTitle()).'" target="_blank" rel="nofollow" onclick="jiffuyShop.trackConversion(\'Product detail headline\',\''.$this->_product->getAdvertiser()->getName().'\',\''.$this->_product->getPrice().'\',\''.$this->_product->getId().'\',\''.$this->_product->getTitle().'\',\''.$this->_product->getBrand('name').'\');"><span itemprop="name">'.$this->_product->getTitle().'</span></a></h2>'."\n";			
			$content.= $this->_buildContentImage();
			$content.= $this->_buildContentMeta();
			
			$content.= '<p class="desc" itemprop="description">'.$this->_product->getDescription().'</p>'."\n";
			$content.= $this->_buildContentTags();
			
			$content.= '</div>'."\n";
			
			return $content;
			
		}
		
		
		/**
		 * Build product image
		 * 
		 */
		private function _buildContentImage() {

			$image = $this->_product->getImage();
			$imageUrl = plugins_url('images/noimage.gif', dirname(dirname(__FILE__)));
			
			if(is_array($image) && count($image) > 0) {
				$imageUrl = $image[0];
			}
			
			$content = '<a class="image" href="'.$this->getJiffuy()->getAffiliateUrl($this->_product->getId()).'" title="'.esc_attr($this->_product->getTitle()).'" target="_blank" rel="nofollow" onclick="jiffuyShop.trackConversion(\'Product detail image\',\''.$this->_product->getAdvertiser()->getName().'\',\''.$this->_product->getPrice().'\',\''.$this->_product->getId().'\',\''.$this->_product->getTitle().'\',\''.$this->_product->getBrand('name').'\');">'."\n";
			$content.= '<img itemprop="image" src="'.$imageUrl.'" alt="'.esc_attr($this->_product->getTitle()).'" width="180" height="180" border="0" />'."\n";
			$content.= '</a>'."\n";
			
			return $content;
			
		}
		
		
		/**
		 * Build product meta
		 * 
		 */
		private function _buildContentMeta() {
			
			$content = '<div class="meta">'."\n";
			
			$content.= '<p>'."\n";
			$content.= (!is_null($this->_product->getPrice()) && floatval($this->_product->getPrice()) > 0) ? __('Preis').': <strong>'.number_format($this->_product->getPrice(), 2, ',', '.').' '.$this->_product->getCurrency().'</strong><sup>1</sup>' : '<a class="price" title="'.__('Aktuellen Preis abfragen').'" href="'.$this->getJiffuy()->getAffiliateUrl($this->_product->getId()).'" target="_blank" rel="nofollow" onclick="jiffuyShop.trackConversion(\'Product detail price\',\''.$this->_product->getAdvertiser()->getTitle().'\',\''.$this->_product->getPrice().'\',\''.$this->_product->getId().'\',\''.$this->_product->getTitle().'\',\''.$this->_product->getBrand().'\');">'.__('Aktuellen Preis abfragen').'</a>';
			$content.= '<br /><span>inkl. 19% MwSt. / Preisänderung möglich</span><br />'."\n";
			$content.= (strlen($this->_product->getPriceShipping()) > 0 && strlen($this->_product->getPriceShipping()) > 0) ? '<span>Versandkosten: '.number_format($this->_product->getPriceShipping(), 2, ',', '.').' '.$this->_product->getCurrency().' <sup>1</sup></span><br />'."\n" : '';
			$content.= ($this->_product->getBrand('name') !== false) ? 'Marke: <span itemprop="brand">'.$this->_product->getBrand('name').'</span><br />'."\n" : '';
			$content.= '</p>'."\n";
			
			$content.= '<p><a class="goto" href="'.$this->getJiffuy()->getAffiliateUrl($this->_product->getId()).'" title="Zum Shop" target="_blank" rel="nofollow" onclick="jiffuyShop.trackConversion(\'Product detail button\',\''.$this->_product->getAdvertiser()->getName().'\',\''.$this->_product->getPrice().'\',\''.$this->_product->getId().'\',\''.$this->_product->getTitle().'\',\''.$this->_product->getBrand().'\');"><span>'.__('Zum Shop').'</span></a></p>'."\n";
			$content.= '<p><span><a href="'.$this->getJiffuy()->getAffiliateUrl($this->_product->getId()).'" title="Zum Shop" target="_blank" rel="nofollow" onclick="jiffuyShop.trackConversion(\'Product detail advertiser\',\''.$this->_product->getAdvertiser()->getName().'\',\''.$this->_product->getPrice().'\',\''.$this->_product->getId().'\',\''.$this->_product->getTitle().'\',\''.$this->_product->getBrand('name').'\');">'.$this->_product->getAdvertiser()->getName().'</a></span></p>'."\n";
			
			$content.= '</div>'."\n";
			
			return $content;
			
		}
		
		
		/**
		 * Build product description
		 * 
		 */
		private function _buildContentTags() {
			
			$tags = $this->_product->getTag();
			
			if(is_array($tags) && count($tags) > 0) {
				$content = '<p id="tags">'.__('Mehr von:').' <span itemprop="category">';
				$i = 0;
				foreach($tags as $tag) {
					$content.= (($i > 0) ? ', ' : '').'<a href="'.$this->getJiffuy()->getShop()->getUrl('tag', $tag).'">'.ucfirst($tag).'</a>';
					$i++;
				}
				$content.= '</span></p>';
				return $content;
			}
			
			return false;
			
		}
		
		
	}
	
}

