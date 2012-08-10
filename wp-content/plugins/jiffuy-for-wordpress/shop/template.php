<?php

/**
 * @package jiffuy
 * @version 1.02
 * 
 * License: Subscription - All rights reserved!
 * Notice: Every distribution of the source code without authors permission is strictly forbidden, for a written permission please contact info@jiffuy.com!
 */

if(!class_exists('jiffuyShopTemplate')) {
	
	class jiffuyShopTemplate {
		
		/**
		 * jiffuy object
		 * @var jiffuy
		 */
		protected $_jiffuy = null;
		
		/**
		 * model product
		 * @var jiffuyShopModelProduct
		 */
		protected $_modelProduct = null;
		
		/**
		 * Template type
		 * @var string
		 */
		protected $_type = null;
		
		/**
		 * Page count
		 * @var integer
		 */
		protected $_page = null;
		
		/**
		 * active category item
		 * @var jiffuyShopCategory
		 */
		protected $_activeCategory = null;
		
		/**
		 * Template class
		 * @var string
		 */
		protected $_class = 'jiffuy';
		
		/**
		 * Meta description
		 * @var string
		 */
		protected $_metaDescription = null;
		
		/**
		 * Meta keywords
		 * @var string
		 */
		protected $_metaKeywords = null;
		
		/**
		 * Meta robots
		 * @var string
		 */
		protected $_metaRobots = null;
		
		/**
		 * Title
		 * @var string
		 */
		protected $_title = null;
		
		/**
		 * Header
		 * @var string
		 */
		protected $_header = null;
		
		/**
		 * Content
		 * @var string
		 */
		protected $_content = null;
		
		/**
		 * Footer
		 * @var string
		 */
		protected $_footer = null;
		
		/**
		 * Search
		 * @var string
		 */
		protected $_searchQuery = null;
		
		
		/**
		 * Construct
		 * 
		 * @param	jiffuy $jiffuy
		 * @param	jiffuyShopModelProduct $modelProduct
		 * @param	string $type template type
		 * @param	jiffuyShopCategory $activeCategory
		 */
		public function __construct(jiffuy $jiffuy, jiffuyShopModelProduct $modelProduct, $type, jiffuyShopCategory $activeCategory = null) {
			
			// init vars
			$this->setJiffuy($jiffuy);
			$this->setModelProduct($modelProduct);
			
			$this->_type = $type;
			$this->_page = $this->getJiffuy()->getShop()->getRoute('page');
			$this->_activeCategory = (!is_null($activeCategory)) ? $activeCategory : $this->_activeCategory;
			
			// init styles and scripts
			wp_enqueue_script("jiffuyShopScript", JIFFUY_PLUGIN_URL."shop/js/shop.js", array(), "1.0", false);
			
		}
		
		
		/**
		 * Sets jiffuy object
		 * 
		 * @param	jiffuy $jiffuy
		 */
		public function setJiffuy(jiffuy $jiffuy) {
			$this->_jiffuy = $jiffuy;
			return $this;			
		}
		
		
		/**
		 * Gets the jiffuy object
		 */
		public function getJiffuy() {
			return $this->_jiffuy;
		}
		
		
		/**
		 * Sets model product
		 * 
		 * @param	jiffuyShopModelProduct $modelProduct
		 */
		public function setModelProduct(jiffuyShopModelProduct $modelProduct) {
			$this->_modelProduct = $modelProduct;
			return $this;
		}
		
		
		/**
		 * Gets the model product
		 */
		public function getModelProduct() {
			return $this->_modelProduct;
		}
		
		
		/**
		 * Sets the active category
		 * 
		 * @param	jiffuyShopCategory $activeCategory
		 */
		public function setActiveCategory(jiffuyShopCategory $activeCategory) {
			$this->_activeCategory = $activeCategory;
			return $this;
		}
		
		
		/**
		 * Gets the active category
		 */
		public function getActiveCategory() {
			return $this->_activeCategory;
		}
		
		
		/**
		 * Sets the template class
		 * 
		 * @param	string $class
		 */
		public function setClass($class) {
			$this->_class = $class;
			return $this;			
		}
		
		
		/**
		 * Gets the template class
		 */
		public function getClass() {
			return $this->_class;
		}
		
		
		/**
		 * Sets the meta description
		 * 
		 * @param	string $metaDescription
		 */
		public function setMetaDescription($metaDescription) {
			$this->_metaDescription = $this->getJiffuy()->limitString($metaDescription, 156);
			return $this;
		}
		
		
		/**
		 * Gets the meta description
		 */
		public function getMetaDescription() {
			return $this->_metaDescription;
		}
		
		
		/**
		 * Sets the meta keywords
		 * 
		 * @param	string $metaKeywords
		 */
		public function setMetaKeywords($metaKeywords) {
			$this->_metaKeywords = $metaKeywords;
			return $this;			
		}
		
		
		/**
		 * Gets the meta keywords
		 */
		public function getMetaKeywords() {
			return $this->_metaKeywords;
		}
		
		
		/**
		 * Sets the meta robots
		 * 
		 * @param	string $metaRobots
		 */
		public function setMetaRobots($metaRobots) {
			$this->_metaRobots = $metaRobots;
			return $this;			
		}
		
		
		/**
		 * Gets the meta robots
		 */
		public function getMetaRobots() {
			return $this->_metaRobots;
		}
		
		
		/**
		 * Sets the document title
		 * 
		 * @param	string $title
		 */
		public function setTitle($title) {
			$this->_title = $title;
			return $this;			
		}
		
		
		/**
		 * Append the document title
		 * 
		 * @param	string $title
		 * @param	string $separator default: ' - '
		 */
		public function appendTitle($title, $separator = ' - ') {
			$this->_title = $this->_title.((strlen($title) > 0) ? $separator.$title : $title);
			return $this;
		}
		
		
		/**
		 * Prepend the document title
		 * 
		 * @param	string $title
		 * @param	string $separator default: ' - '
		 */
		public function prependTitle($title, $separator = ' - ') {
			$this->_title = $title.((strlen($title) > 0) ? $separator.$this->_title : $this->_title);
			return $this;
		}
		
		
		/**
		 * Gets the document title
		 */
		public function getTitle() {
			return $this->_title;
		}
		
		
		/**
		 * Sets the header
		 * 
		 * @param	string $header
		 */
		public function setHeader($header) {
			$this->_header = $header;
			return $this;
		}
		
		
		/**
		 * Gets the header
		 */
		public function getHeader() {
			return $this->_header;
		}
		
		
		/**
		 * Sets the content
		 * 
		 * @param	string $content
		 */
		public function setContent($content) {
			$this->_content = $content;
			return $this;			
		}
		
		
		/**
		 * Gets the content
		 */
		public function getContent() {
			return $this->_content;
		}
		
		
		/**
		 * Sets the footer
		 * 
		 * @param	string $footer
		 */
		public function setFooter($footer) {
			$this->_footer = $footer;
			return $this;
		}
		
		
		/**
		 * Gets the footer
		 */
		public function getFooter() {
			return $this->_footer;
		}
		
		
		/**
		 * Sets the search query
		 * 
		 * @param	string $searchQuery
		 */
		public function setSearchQuery($searchQuery) {
			$this->_searchQuery = $searchQuery;
			return $this;
		}
		
		
		/**
		 * Gets the search query
		 */
		public function getSearchQuery() {
			return $this->_searchQuery;
		}
		
		
		/**
		 * Sets the meta tags
		 * 
		 * @param	none
		 */
		public function hookMetaTags() {
			$output = (strlen($this->_metaDescription) > 0) ? '<meta name="description" content="'.$this->_metaDescription.'" />'."\n" : '';
			$output.= (strlen($this->_metaKeywords) > 0) ? '<meta name="keywords" content="'.$this->_metaKeywords.'" />'."\n" : '';
			$output.= (strlen($this->_metaRobots) > 0) ? '<meta name="robots" content="'.$this->_metaRobots.'" />'."\n" : '';
			print $output;
		}
		
		
		/**
		 * Sets the required javascript
		 * 
		 * @param	none
		 */
		public function hookJavaScript() {
			$output = '<script type="text/javascript">';
			$output.= 'var jiffuyShopOptions = new Object();'."\n";
			$output.= '</script>'."\n";
			print $output;
		}
		
		
		/**
		 * Sets the required stylesheets
		 * 
		 * @param	none
		 */
		public function hookStyles() {
			$output = '<link rel="stylesheet" href="'.plugins_url('css/shop.css?ver='.JIFFUY_VERSION, __FILE__).'" type="text/css" media="all" />';
			$output.= '<style type="text/css">';
			$output.= 'div.jiffuy a.goto {background:url('.plugins_url('images/product/go.png', __FILE__).') no-repeat top left;}';
			$output.= 'div.jiffuy a.goto:hover {background:url('.plugins_url('images/product/go_hover.png', __FILE__).') no-repeat top left;}';
			$output.= '</style>';
			print $output;
		}
		
		
		/**
		 * Sets the page title
		 * 
		 * @param	string $title
		 */
		public function hookBlogDescription($value) {
			return '';
		}
		
		
		/**
		 * Render the current page
		 */
		public function render() {
			
			// check for required settings
			if(!$this->getActiveCategory() instanceof jiffuyShopCategory) {
				throw new Exception('Cannot render shop template without an active category item.');
			}
			
			// render template
			add_action('wp_head', array($this, 'hookMetaTags'));
			add_action('wp_head', array($this, 'hookJavaScript'));
			add_action('wp_head', array($this, 'hookStyles'));
			
			add_filter('option_blogdescription', array($this, 'hookBlogDescription'), 999);
			
			// build template
			$content = $this->_renderHeader();
			$content.= $this->_renderBody();
			$content.= $this->_renderFooter();
			
			return $content;
			
		}
		
		
		/**
		 * Render header
		 * 
		 * @param	none
		 */
		private function _renderHeader() {
			
			// buffer output
			ob_start();
			nocache_headers();
			get_header();
			$header = ob_get_contents();
			ob_end_clean();
			
			$header = str_replace("'", '"', $header);
			
			if((int)$this->_page > 1) { $this->setTitle($this->getTitle().' - '.'Seite '.(int)$this->_page); }
			$header = preg_replace('/<title>(.*)<\/title>/', '<title>'.$this->getTitle().' - '.get_bloginfo('name').'</title>', $header);
			
			return $header;
			
		}
		
		
		/**
		 * Render body
		 * 
		 * @param	none
		 */
		private function _renderBody() {
			
			$body = '<div class="jiffuy '.$this->_class.'">'."\n";
			
			$body.= $this->buildHeader();
			$body.= $this->buildContent();
			$body.= $this->buildFooter();
			$body.= $this->buildTracking();
			
			$body.= '</div>'."\n";
			
			return $body;
			
		}
		
		
		/**
		 * Render footer
		 * 
		 * @param	none
		 */
		private function _renderFooter() {
			
			// buffer output
			ob_start();
			get_footer();
			$footer = ob_get_contents();
			ob_end_clean();
			
			return $footer;
			
		}
		
		
		/**
		 * Build header
		 * 
		 * @param	none
		 */
		public function buildHeader() {
			
			$content = $this->_header."\n";
			
			// build navigation
			$categories = $this->getJiffuy()->getShop()->getCategories();
			$content.= (is_array($categories) && count($categories) > 0) ? $this->buildHeaderNavigation($categories)."\n" : '';
			$content.= $this->buildHeaderSearch();
			
			return $content;
			
		}
		
		
		/**
		 * Build header navigation
		 * 
		 * @param	array $categories
		 */
		public function buildHeaderNavigation(array $categories) {
			
			$content = '<ul id="nav">'."\n";
			$content.= '<li'.( ($this->getActiveCategory()->getSlug() == 'home') ? ' class="active"' : '' ).'><a href="'.$this->_jiffuy->getShop()->getUrl('home', 'home', 1, $this->getJiffuy()->getShop()->removeParams(array('q','b','a'), true)).'">'.$categories['home']->getName().'</a></li>';
			
			foreach ($categories as $category) {
				if($category->getSlug() != 'home') {
					$active = ($this->getActiveCategory()->getSlug() == $category->getSlug()) ? true : false;
					$content.= '<li'.( ($active) ? ' class="active"' : '' ).'><a href="'.$this->_jiffuy->getShop()->getUrl('category', $category->getSlug(), 1, $this->getJiffuy()->getShop()->removeParams(array('q','b','a'), true)).'">'.$category->getName().'</a></li>';
				}	
			}
				
			$content.= '</ul>'."\n";
				
			return $content;
			
		}
		
		
		/**
		 * Build header search
		 * 
		 * @param	none
		 */
		public function buildHeaderSearch() {
			
			$content = '<form id="jysSearchform" action="'.$this->getJiffuy()->getShop()->getUrl('search', 'search', 1, $this->getJiffuy()->getShop()->removeParams('q', true)).'" method="get" name="search">'."\n";
			$content.= '<input name="juys" id="jysField" onblur="if(this.value==\'\') this.value=\'Suche nach Produkten, Marken, Shops\';" onfocus="if(this.value ==\'Suche nach Produkten, Marken, Shops\') this.value=\'\';" value="'.( (!is_null($this->getSearchQuery()) && strlen($this->getSearchQuery()) > 0) ? $this->getSearchQuery() : 'Suche nach Produkten, Marken, Shops') .'" type="text" />'."\n";
			
			// handle get query route
			if($this->getJiffuy()->getRewritePermalinksActive() === false) {
				$query = 'search'.$this->getJiffuy()->getSettings('shopUrlSeparatorParameter').'s'.$this->getJiffuy()->getSettings('shopUrlSeparatorParameter').'1';
				$content.= '<input name="'.$this->getJiffuy()->getShop()->getBaseUrl().'" type="hidden" value="'.$query.'" />'."\n";
			}
			
			$content.= '<input id="jysSearch" value="Suchen" title="Suchen" type="submit" />'."\n";
			$content.= '</form>';
			
			return $content;
			
		}
		
		
		/**
		 * Build content
		 * 
		 * @param	none
		 */
		public function buildContent() {
						
			$content = $this->_content."\n";			
			return $content;
			
		}
		
		
		/**
		 * Build footer
		 * 
		 * @param	none
		 */
		public function buildFooter() {
			
			$content = $this->_footer."\n";
			
			// add disclaimer
			$content.= '<p class="di"><img src="'.plugins_url('images/product/disclaim.png', __FILE__).'" width="500" height="119" border="0" /></p>'."\n";
			$content.= '<p class="pb">V'.JIFFUY_VERSION.' - '.base64_decode('cG93ZXJlZCBieSA8YSBocmVmPSJodHRwOi8vd3d3LmppZmZ1eS5jb20iIHRpdGxlPSJNaXQgZGVyIGVpZ2VuZW4gV2Vic2l0ZSBHZWxkIHZlcmRpZW5lbiEiPmppZmZ1eS5jb208L2E+').'</p>';
			
			// preload hover button
			$content.= '<img src="'.plugins_url('images/product/go_hover.png', __FILE__).'" border="0" height="1" width="1" />';
			
			return $content;
			
		}
		
		
		/**
		 * Build footer
		 * 
		 * @param	none
		 */
		public function buildTracking() {			
			$customVars = array();			
			$customVars[] = 'juyT.setCustomVariable(1,\'version\', \''.JIFFUY_VERSION.'\', \'visit\');';
			$customVars[] = 'juyT.setCustomVariable(2,\'site\', \''.home_url().'\', \'visit\');';
			$content = '<script type="text/javascript">var pkBaseURL = (("https:" == document.location.protocol) ? "https://mo.jiffuy.com/" : "http://mo.jiffuy.com/");document.write(unescape("%3Cscript src=\'"+pkBaseURL+"t.js\' type=\'text/javascript\'%3E%3C/script%3E"));</script><script type="text/javascript">try {var juyT = Piwik.getTracker(pkBaseURL+"t.php", 2);'.implode('', $customVars).'juyT.trackPageView();juyT.enableLinkTracking();}catch(err){}</script><noscript><p><img src="http://mo.jiffuy.com/t.php?idsite=2" border="0" alt="" /></p></noscript>'."\n";
			return $content;
			
		}
		
		
		/**
		 * Is search engine referrer
		 * 
		 * @param	none
		 */
		public function isSearchEngineReferrer() {
			
			if(isset($_SERVER['HTTP_REFERER'])) {
				
				// get referer
				$url = parse_url($_SERVER['HTTP_REFERER']);				
				if(isset($url['host']) && strlen($url['host']) > 0) {
					$searchEngines = array('google','bing','msn','yahoo','t-online');
					if(preg_match('/(' . implode('|', $searchEngines) . ')\./', $url['host'])) {
						return true;
					}
				}
				
			}
			
			return false;
			
		}
		
		
	}
	
}

