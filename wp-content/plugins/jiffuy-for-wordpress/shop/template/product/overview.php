<?php

/**
 * @package jiffuy
 * @version 1.02
 * 
 * License: Subscription - All rights reserved!
 * Notice: Every distribution of the source code without authors permission is strictly forbidden, for a written permission please contact info@jiffuy.com!
 */

if(!class_exists('jiffuyShopTemplateProductOverview')) {
	
	class jiffuyShopTemplateProductOverview extends jiffuyShopTemplate {
		
		
		/**
		 * Products
		 * @var array
		 */
		protected $_products = array();
		
		/**
		 * Products title
		 * @var array
		 */
		protected $_productsTitle = array();
		
		/**
		 * Build title
		 * @var array
		 */
		protected $_buildTitle = array('prepend' => array(), 'append' => array());
		
		/**
		 * Pagination
		 * @var jiffuyLibraryPagination
		 */
		protected $_pagination = null;
		
		/**
		 * Filter gender
		 * @var array
		 */
		protected $_filterGender = array('damen' => 'female', 'herren' => 'male');
		
		/**
		 * Filter color
		 * @var array
		 */
		protected $_filterColor = array('beige' => 'beige', 'schwarz' => 'black', 'schwarze' => 'black', 'weiss' => 'white', 'weisse' => 'white', 'blau' => 'blue', 'blaue' => 'blue', 'dunkelblau' => 'darkblue', 'dunkelblaue' => 'darkblue', 'hellblau' => 'lightblue', 'hellblaue' => 'lightblue', 'braun' => 'brown', 'braune' => 'brown', 'gruen' => 'green', 'gruene' => 'green', 'orange' => 'orange', 'gelb' => 'yellow', 'gelbe' => 'yellow', 'grau' => 'grey', 'graue' => 'grey', 'violett' => 'purple', 'violette' => 'purple', 'lila' => 'purple', 'lilane' => 'purple', 'rosa' => 'pink', 'rosane' => 'pink', 'gold' => 'gold', 'golden' => 'gold', 'goldene' => 'gold', 'silber' => 'silver', 'silbern' => 'silver', 'silberne' => 'silver', 'bronze' => 'bronze', 'bronzene' => 'bronze', 'tuerkis' => 'cyan', 'tuerkise' => 'cyan', 'rot' => 'red', 'bordeaux' => 'darkred', 'lavendel' => 'lavender');
		
		/**
		 * Filter price
		 * @var array
		 */
		protected $_filterPrice = array('0-5' => '0&nbsp;bis&nbsp;5&nbsp;EUR', '10-50' => '10&nbsp;bis&nbsp;50&nbsp;EUR', '50-100' => '50&nbsp;bis&nbsp;100&nbsp;EUR', '100-200' => '100&nbsp;bis&nbsp;200&nbsp;EUR', '200-500' => '200&nbsp;bis&nbsp;500&nbsp;EUR', '500-99999' => 'Ab&nbsp;500&nbsp;EUR');
		
		
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
			status_header(200);
			
			// parent construct
			parent::__construct($jiffuy, $modelProduct, $type);
			
			// init template
			$this->initialize();
			
		}
		
		
		/**
		 * Initialize template
		 * 
		 */
		public function initialize() {
			
			// init template class
			$this->setClass('jiffuyShopOverview');
			
			// init filter
			$this->_initFilter();
			
			// init search query
			$this->setSearchQuery(str_replace('-', ' ', $this->getJiffuy()->getShop()->getParams('q')));
			
		}
		
		
		/**
		 * Sets the pagination
		 * 
		 * @param	jiffuyLibraryPagination $pagination
		 */
		public function setPagination(jiffuyLibraryPagination $pagination) {
			$this->_pagination = $pagination;
			return $this;			
		}
		
		
		/**
		 * Gets the pagination
		 */
		public function getPagination() {
			return $this->_pagination;
		}
		
		
		/**
		 * Sets the products to view
		 * 
		 * @param	array $products
		 */
		public function setProducts(array $products) {
			$this->_products = $products;
			return $this;			
		}
		
		
		/**
		 * Gets the products to view
		 */
		public function getProducts() {
			return $this->_products;
		}
		
		
		/**
		 * Sets the products title
		 * 
		 * @param	array $products
		 */
		public function setProductsTitle(array $productsTitle) {
			$this->_productsTitle = $productsTitle;
			return $this;
		}
		
		
		/**
		 * Gets the products title
		 */
		public function getProductsTitle() {
			return $this->_productsTitle;
		}
		
		
		/**
		 * Build template
		 * 
		 */
		public function build() {
			
			if(!is_null($this->getActiveCategory())) {
				
				// build template
				$this->setHeader($this->_buildHeader());
				$this->setContent($this->_buildContent());
				$this->setFooter($this->_buildFooter());
				$this->_buildTitle();
				
				$this->setMetaKeywords($this->_buildMetaKeywords());
				$this->setMetaDescription($this->_buildMetaDescription());				
				if(is_null($this->getMetaRobots())) {
					$this->setMetaRobots($this->_buildMetaRobots());
				}
				
			} else {
				throw new Exception('Cannot build overview template without an active category and products.');
			}
			
		}
		
		
		/**
		 * Build title
		 * 
		 */
		private function _buildTitle() {
			
			// build title prepend
			$this->prependTitle(implode(' ', $this->_buildTitle['prepend']), ' ');
			
			// category title
			$this->appendTitle((($this->_page == 1 && count($this->getJiffuy()->getShop()->getParams()) == 0) ? $this->getActiveCategory()->getTitle() : $this->getActiveCategory()->getName()), ' ');
			
			// build title append
			$this->appendTitle(implode(' ', $this->_buildTitle['append']), ' ');
			
		}
		
		
		/**
		 * Build meta description
		 * 
		 */
		private function _buildMetaDescription() {
			
			// set meta description
			$metaDescription = $this->getActiveCategory()->getMetaDescription();
			if(strlen($metaDescription) > 0) {
				
				// build description by static category description
				return $metaDescription;
				
			} else {
				
				// build description by product collection
				$description = $this->getActiveCategory()->getTitle().__(' günstig einkaufen!');
				if(count($this->getProductsTitle()) > 0) {
					$description.= __(' Wir bieten Ihnen ');
					foreach($this->getProductsTitle() as $key => $title) {
						$description.= ((int)$key > 0) ? (($key % 2) ? ' oder ' : ' und ') : '';
						$description.= $title;
					}
				}
				return $description;
				
			}
			
			return false;
			
		}
		
		
		/**
		 * Build meta keywords
		 * 
		 */
		private function _buildMetaKeywords() {
			
			// set meta keywords
			$keywords = $this->getActiveCategory()->getTags();			
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
			switch($this->_type) {
				case "home": return 'index,follow';
				case "category": return (isset($this->_products['count']) && (int)$this->_products['count'] >= $this->getJiffuy()->getSettings('shopProductsIndexTagPagesCount')) ? 'index,follow' : 'noindex,follow';
				case "tag": return (isset($this->_products['count']) && (int)$this->_products['count'] >= $this->getJiffuy()->getSettings('shopProductsIndexTagPagesCount')) ? 'index,follow' : 'noindex,follow';
			}
			
			return false;
			
		}
		
		
		/**
		 * Build header
		 * 
		 * @param	none
		 */
		private function _buildHeader() {
			
			$content = '';
			
			return $content;
			
		}
		
		
		/**
		 * Build content
		 * 
		 */
		private function _buildContent() {
			
			$content = $this->_buildContentSidebar();
			
			$content.= '<div id="juy-content">'."\n";
			$content.= $this->_buildContentHeader();
			$content.= $this->_buildContentCollection();
			$content.= '</div>'."\n";
			
			return $content;
			
		}
		
		
		/**
		 * Build content sidebar
		 * 
		 * @param	none
		 */
		private function _buildContentSidebar() {
			
			// start sidebar
			$sidebar = '';
				
			// color
			if($this->getJiffuy()->getSettings('shopDisplaySidebarColors') === true) {
				$colors = array('rot' => '#ef0909', 'rosa' => '#f47995', 'orange' => '#ffa61b', 'gelb' => '#efec00', 'gruen' => '#05d900', 'blau' => '#1f58d9', 'tuerkis' => '#b4ece9', 'violett' => '#7b23ff', 'beige' => '#d8c097', 'braun' => '#ae762d', 'schwarz' => '#000000', 'grau' => '#8e8e8e', 'weiss' => '#ffffff');
				$sidebar.= '<h2>Farben</h2>'."\n";
				$sidebar.= ($this->getJiffuy()->getShop()->getParams('f') !== false) ? '<p class="reset"><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getJiffuy()->getShop()->getRoute('slug'), 1, $this->getJiffuy()->getShop()->removeParams('f', true)).'">'.__('Filter entfernen').'</a></p>'."\n" : '';
				$sidebar.= '<ul id="colors">'."\n";
				foreach($colors as $color => $hex) {
					$active = ($this->getJiffuy()->getShop()->getParams('f') == $color) ? ' class="hi"' : '';
					$sidebar.= '<li'.$active.'><a style="background:'.$hex.';" href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getJiffuy()->getShop()->getRoute('slug'), 1, $this->getJiffuy()->getShop()->addParam('f', $color, true)).'"> </a></li>'."\n";
				}
				$sidebar.= '</ul>'."\n";
			}
			
			// gender
			if($this->getJiffuy()->getSettings('shopDisplaySidebarGenders') === true) {
				$sidebar.= '<h2>Geschlecht</h2>'."\n";
				$sidebar.= ($this->getJiffuy()->getShop()->getParams('g') !== false) ? '<p class="reset"><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getJiffuy()->getShop()->getRoute('slug'), 1, $this->getJiffuy()->getShop()->removeParams('g', true)).'">'.__('Filter entfernen').'</a></p>'."\n" : '';
				$sidebar.= '<ul class="filter">'."\n";
				foreach($this->_filterGender as $title => $value) {
					$active = ($this->getJiffuy()->getShop()->getParams('g') == $title) ? ' class="hi"' : '';
					$sidebar.= '<li'.$active.'><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getJiffuy()->getShop()->getRoute('slug'), 1, $this->getJiffuy()->getShop()->addParam('g', $title, true)).'">'.ucfirst($title).'</a></li>'."\n";
				}
				$sidebar.= '</ul>'."\n";
			}
			
			// brands
			$facetBrands = (isset($this->_products['facet']['brand']) && count($this->_products['facet']['brand']) > 0) ? $this->_products['facet']['brand'] : false;
			if($facetBrands !== false || $this->getJiffuy()->getShop()->getParams('b') !== false) {
				$sidebar.= '<h2>Marken und Hersteller</h2>'."\n";
				$sidebar.= ($this->getJiffuy()->getShop()->getParams('b') !== false) ? '<p class="reset"><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getJiffuy()->getShop()->getRoute('slug'), 1, $this->getJiffuy()->getShop()->removeParams('b', true)).'">'.__('Filter entfernen').'</a></p>'."\n" : '';
				$sidebar.= '<ul class="filter">'."\n";
				if($facetBrands !== false) {
					foreach($facetBrands as $brand) {
						$active = ($this->getJiffuy()->getShop()->getParams('b') == $brand['slug']) ? ' class="hi"' : '';
						$sidebar.= '<li'.$active.'><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getJiffuy()->getShop()->getRoute('slug'), 1, $this->getJiffuy()->getShop()->addParam('b', $brand['slug'], true)).'">'.$brand['name'].' ('.$brand['count'].')</a></li>'."\n";
					}
				} else {
					$sidebar.= '<li><strong>'.__('Keine Produkte (0)').'</strong></li>'."\n";
				}
				$sidebar.= '</ul>'."\n";
			}
			
			// price
			if($this->getJiffuy()->getSettings('shopDisplaySidebarPrices') === true) {
				$sidebar.= '<h2>Preis</h2>'."\n";
				$sidebar.= ($this->getJiffuy()->getShop()->getParams('m') !== false) ? '<p class="reset"><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getJiffuy()->getShop()->getRoute('slug'), 1, $this->getJiffuy()->getShop()->removeParams('m', true)).'">'.__('Filter entfernen').'</a></p>'."\n" : '';
				$sidebar.= '<ul class="filter">'."\n";
				foreach($this->_filterPrice as $price => $title) {
					$active = ($this->getJiffuy()->getShop()->getParams('m') == $price) ? ' class="hi"' : '';
					$sidebar.= '<li'.$active.'><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getJiffuy()->getShop()->getRoute('slug'), 1, $this->getJiffuy()->getShop()->addParam('m', $price, true)).'">'.$title.'</a></li>'."\n";
				}
				$sidebar.= '</ul>'."\n";
			}
			
			// advertisers
			$facetAdvertisers = (isset($this->_products['facet']['advertiser']) && count($this->_products['facet']['advertiser']) > 0) ? $this->_products['facet']['advertiser'] : false;
			if($facetAdvertisers !== false || $this->getJiffuy()->getShop()->getParams('a') !== false) {
				$sidebar.= '<h2>Shops</h2>'."\n";
				$sidebar.= ($this->getJiffuy()->getShop()->getParams('a') !== false) ? '<p class="reset"><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getJiffuy()->getShop()->getRoute('slug'), 1, $this->getJiffuy()->getShop()->removeParams('a', true)).'">'.__('Filter entfernen').'</a></p>'."\n" : '';
				$sidebar.= '<ul class="filter">'."\n";
				if($facetAdvertisers !== false) {
					foreach($facetAdvertisers as $advertiser) {
						$active = ($this->getJiffuy()->getShop()->getParams('a') == $advertiser['slug']) ? ' class="hi"' : '';
						$sidebar.= '<li'.$active.'><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getJiffuy()->getShop()->getRoute('slug'), 1, $this->getJiffuy()->getShop()->addParam('a', $advertiser['slug'], true)).'">'.$advertiser['name'].' ('.$advertiser['count'].')</a></li>'."\n";
					}
				} else {
					$sidebar.= '<li><strong>'.__('Keine Produkte (0)').'</strong></li>'."\n";
				}
				$sidebar.= '</ul>'."\n";
			}
				
			$sidebar.= (strlen($this->getActiveCategory()->getDescription()) > 0) ? '<div class="cdesc">'.$this->getActiveCategory()->getDescription().'</div>' : '';
			
			return '<div id="juy-sidebar">'."\n".$sidebar.'</div>'."\n";
			
		}
		
		
		/**
		 * Build content sidebar brand
		 * 
		 * @param	none
		 */
		private function _buildContentSidebarBrand() {
			
			if(isset($this->_products['meta']['brand']) && is_array($this->_products['meta']['brand'])) {
				$i = 0;
				foreach($this->_products['meta']['brand'] as $brand) {
					if($i < 80) {
						if(isset($brand['count']) && (int)$brand['count'] > 20 && isset($brand['title']) && strlen($brand['title']) > 0 && isset($brand['slug']) && strlen($brand['slug']) > 0) {
							$this->_filterBrand[$brand['slug']] = array('title' => $brand['title'], 'count' => $brand['count']);
							$i++;
						}
					} else {
						break;
					}
				}
			}
			
		}
		
		
		/**
		 * Build product collection
		 * 
		 */
		private function _buildContentHeader() {
			
			$content = '<h2>'.$this->_activeCategory->getTitle().'</h2>'."\n";
			
			return $content;
			
		}
		
		
		/**
		 * Build product collection
		 * 
		 */
		private function _buildContentCollection() {
			
			if(!is_null($this->_products) && isset($this->_products['result'])) {
				
				$content = '<ul class="pcollection">'."\n";
				
				foreach($this->_products['result'] as $product) {
					if($product instanceof jiffuyShopProduct) {						
						$content.= '<li>'."\n";
						$content.= $this->_buildContentCollectionImage($product);
						$content.= $this->_buildContentCollectionMeta($product);
						$content.= '</li>'."\n";						
					}
				}
				
				$content.= '</ul>'."\n";
				
			} else {
				$content = '<p class="info">'.__('Es wurden keine Produkte gefunden.').'</p>'."\n";
			}
			
			return $content;
			
		}
		
		
		/**
		 * Build product collection image
		 * 
		 * @param	jiffuyShopProduct $product
		 */
		private function _buildContentCollectionImage(jiffuyShopProduct $product) {
			
			$image = $product->getImage();
			$imageUrl = plugins_url('images/noimage.gif', dirname(dirname(__FILE__)));
			
			if(is_array($image) && count($image) > 0) {
				$imageUrl = $image[0];
			}
			
			$content = '<a class="image" href="'.$this->getJiffuy()->getShop()->getUrl('product', $product->getSlug()).'" title="'.esc_attr($product->getTitle()).'">'."\n";
			$content.= '<img src="'.$imageUrl.'" alt="'.esc_attr($product->getTitle()).'" width="180" height="180" border="0" />'."\n";
			$content.= '</a>'."\n";
			
			return $content;
			
		}
		
		
		/**
		 * Build product collection meta
		 * 
		 * @param	jiffuyShopProduct $product
		 */
		private function _buildContentCollectionMeta(jiffuyShopProduct $product) {
			
			// add product title to collection
			$this->_productsTitle[] = esc_attr($product->getTitle());
			
			$content = '<div class="meta">'."\n";
			
			$content.= '<h3><a href="'.$this->getJiffuy()->getShop()->getUrl('product', $product->getSlug()).'" title="'.esc_attr($product->getTitle()).'">'.$product->getTitle().'</a></h3>'."\n";
			
			$content.= '<p><strong>'."\n";
			$content.= (!is_null($product->getPrice()) && floatval($product->getPrice()) > 0) ? number_format($product->getPrice(), 2, ',', '.').' '.$product->getCurrency().'<sup>1</sup>' : '<a class="price" title="'.__('Aktuellen Preis abfragen').'" href="'.$this->getJiffuy()->getAffiliateUrl($product->getId()).'" target="_blank" rel="nofollow" onclick="jiffuyShop.trackConversion(\'Product overview price\',\''.$product->getAdvertiser()->getName().'\',\''.$product->getPrice().'\',\''.$product->getId().'\',\''.$product->getTitle().'\',\''.$product->getBrand('name').'\');">'.__('Aktuellen Preis abfragen').'</a>';
			$content.= '</strong><br /><span>Preisänderung möglich</span><br />'."\n";
			$content.= (strlen($product->getPriceShipping()) > 0 && strlen($product->getPriceShipping()) > 0) ? '<span>Versandkosten: '.number_format($product->getPriceShipping(), 2, ',', '.').' '.$product->getCurrency().' <sup>1</sup></span><br />'."\n" : '';
			$content.= '</p>'."\n";
			
			$content.= '<p><a class="goto" href="'.$this->getJiffuy()->getAffiliateUrl($product->getId()).'" title="'.__('Zum Shop').'" target="_blank" rel="nofollow" onclick="jiffuyShop.trackConversion(\'Product overview button\',\''.$product->getAdvertiser()->getName().'\',\''.$product->getPrice().'\',\''.$product->getId().'\',\''.$product->getTitle().'\',\''.$product->getBrand('name').'\');"><span>'.__('Zum Shop').'</span></a></p>'."\n";
			
			$content.= '</div>'."\n";
			
			return $content;
			
		}
		
		
		/**
		 * Build footer
		 * 
		 * @param	none
		 */
		private function _buildFooter() {
			
			$content = ($this->_pagination instanceof jiffuyLibraryPagination) ? $this->_buildFooterPagination() : '';
			
			return $content;
			
		}
		
		
		/**
		 * Build footer pagination
		 * 
		 * @param	integer $paginationRange default 10
		 */
		private function _buildFooterPagination() {
			
			$pagination = $this->_pagination->buildPagination();
			
			if($this->getActiveCategory()->getSlug() == 'home' && $this->getJiffuy()->getShop()->getRoute('page') == 1 && count($this->getJiffuy()->getShop()->getParams()) == 0) {
				
				$content = '<h3 id="plink"><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getActiveCategory()->getSlug(), 2, $this->getJiffuy()->getShop()->getParams()).'">'.__('Artikel-Archiv').'</a></h3>'."\n";
				
				return $content;
				
			} else if($pagination !== false && isset($pagination['pagination']) && is_array($pagination['pagination'])) {
				
				$content = '<ul id="pagination">'."\n";
				$content.= (is_array($pagination['previous'])) ? '<li class="previous"><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getActiveCategory()->getSlug(), key($pagination['previous']), $this->getJiffuy()->getShop()->getParams()).'">'.__('Zurück').'</a></li>'."\n" : '';
				$content.= (is_array($pagination['paginationStart'])) ? '<li class="start"><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getActiveCategory()->getSlug(), key($pagination['paginationStart']), $this->getJiffuy()->getShop()->getParams()).'">'.key($pagination['paginationStart']).'</a></li><li class="space"><span>...</span></li>'."\n" : '';
				
				foreach($pagination['pagination'] as $page => $active) {
					$content.= '<li'.(($active) ? ' class="active"' : '').'><a href="'.$this->_jiffuy->getShop()->getUrl($this->_type, $this->getActiveCategory()->getSlug(), $page, $this->getJiffuy()->getShop()->getParams()).'">'.$page.'</a></li>'."\n";
				}
				
				$content.= (is_array($pagination['paginationEnd'])) ? '<li class="space"><span>...</span></li><li class="end"><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getActiveCategory()->getSlug(), key($pagination['paginationEnd']), $this->getJiffuy()->getShop()->getParams()).'">'.key($pagination['paginationEnd']).'</a></li>'."\n" : '';
				$content.= (is_array($pagination['next'])) ? '<li class="next"><a href="'.$this->getJiffuy()->getShop()->getUrl($this->_type, $this->getActiveCategory()->getSlug(), key($pagination['next']), $this->getJiffuy()->getShop()->getParams()).'">'.__('Weiter').'</a></li>'."\n" : '';
				$content.= '</ul>'."\n";
				
				return $content;
				
			}
			
			return false;
			
		}
		
		
		/**
		 * Init filter
		 * 
		 * @param none
		 */
		public function _initFilter() {
			
			// get params
			$title = array();			
			$params = $this->getJiffuy()->getShop()->getParams();
			
			// process params
			foreach($params as $type => $value) {
				
				switch($type) {
					
					// filter search
					case "q":
						$value = jiffuy::generateSlug($value);
						if(strlen($value) > 0) {
							$this->_buildTitle['prepend'][] = strtoupper(str_replace('-', ' ', $value));
							$this->setModelProduct($this->getModelProduct()->addQuery('search', $value));
						}
						break;
					
					// filter gender
					case "g":
						if(array_key_exists($value, $this->_filterGender)) {
							$this->setMetaRobots('noindex');
							$this->_buildTitle['prepend'][] = ucfirst($value);
							$this->setModelProduct($this->getModelProduct()->addQuery('gender', $this->_filterGender[$value]));
						}
						break;
						
					// filter color
					case "f":
						if(array_key_exists($value, $this->_filterColor)) {
							$this->_buildTitle['append'][] = ucfirst($value);
							$this->setModelProduct($this->getModelProduct()->addQuery('color', $this->_filterColor[$value]));
						}
						break;
						
					// filter price
					case "m":
						if(array_key_exists($value, $this->_filterPrice)) {
							$this->setMetaRobots('noindex');
							$price = explode('-', $value);
							$this->_buildTitle['append'][] = __('von ').ucfirst($this->_filterPrice[$value]);
							$this->setModelProduct($this->getModelProduct()->addQuery('priceGreaterThan', $price[0])->addQuery('priceLowerThan', $price[1]));
						}
						break;
					
					// filter brand
					case "b":
						$this->_buildTitle['prepend'][] = strtoupper($value);
						$this->setModelProduct($this->getModelProduct()->addQuery('brand', $value));
						break;
						
					// filter advertiser
					case "a":
						$this->_buildTitle['append'][] = __('bei ').ucfirst($value);
						$this->setModelProduct($this->getModelProduct()->addQuery('advertiser', $value));
						break;
					
						
				}
				
			}
			
		}
		
		
	}
	
}

