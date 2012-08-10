<?php

require_once dirname(__FILE__) . '/admin/notifications.php';
$adminNotifications = new jiffuyAdminNotifications($jiffuy);

/**
 *
 * Class for building the jiffuy plugin
 *
 * @category   jiffuy
 * @package    default
 * @subpackage admin
 * @copyright  2012 jiffuy (http://www.jiffuy.com)
 * @license    Closed Source, All Rights Reserved
 * @author     jiffuy <info@jiffuy.com>
 */
if(!class_exists('jiffuyAdmin')) {

	class jiffuyAdmin {
		
		
		/**
		 * jiffuy object
		 * @var jiffuy
		 */
		protected $_jiffuy = null;
		
		/**
		 * shop presets directory
		 * @var string
		 */
		protected $_shopPresetsDirectory = null;
		
		
		/**
		 * Construct jiffuy admin
		 * 
		 * @param	jiffuy $jiffuy
		 */
		public function __construct(jiffuy $jiffuy) {
			
			// init vars
			$this->_jiffuy = $jiffuy;
			$this->_jiffuy->initApi();
			
			// init presets directory
			$this->_shopPresetsDirectory = dirname(__FILE__).'/shop/presets/';
			
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
			add_action('admin_init', array($this,'hookRegisterSettings'));
			add_action('admin_menu', array($this,'hookAdminMenu'));
			add_action('admin_head', array($this, 'hookStyles'));
		}
		
		
		/**
		 * Sets the jiffuy object
		 * 
		 * @param	jiffuy $jiffuy
		 */
		public function setJiffuy(jiffuy $jiffuy) {
			$this->_jiffuy = $jiffuy;
			return $this;
		}
		
		
		/**
		 * Gets the jiffuy object
		 * 
		 * @param	none
		 */
		public function getJiffuy() {
			return $this->_jiffuy;
		}
		
		
		/**
		 * Hook register settings
		 * 
		 * @param	none
		 */
		public function hookRegisterSettings() {
			
			// init styles and scripts
			wp_enqueue_style("jiffuyAdmin", JIFFUY_PLUGIN_URL."admin/css/admin.css", false, "1.0", "all");
			wp_enqueue_style("jiffuyJqueryUi", JIFFUY_PLUGIN_URL."css/jquery/smoothness/jquery-ui-1.8.18.custom.css", false, "1.0", "all");
			
			// init styles and scripts
			wp_enqueue_script("jiffuyJquery", JIFFUY_PLUGIN_URL."js/jquery-1.7.1.min.js", array(), "1.0", false);
			wp_enqueue_script("jiffuyJqueryUi", JIFFUY_PLUGIN_URL."js/jquery-ui-1.8.18.min.js", array(), "1.0", false);
			wp_enqueue_script("jiffuyJqueryTagsinput", JIFFUY_PLUGIN_URL."js/jquery.tagsinput.js", array(), "1.0", false);
			wp_enqueue_script("jiffuyAdmin", JIFFUY_PLUGIN_URL."admin/js/admin.js", array(), "1.0", false);
			
			// register settings
			register_setting('jiffuy','jiffuy', array($this, 'hookValidateOptions'));
			
		}
		
		
		/**
		 * Hook admin menu
		 * 
		 * @param	none
		 */
		public function hookAdminMenu() {
			add_options_page('jiffuy', '<span id="jiffuySidebarIcon"></span>jiffuy', 'manage_options', 'jiffuy', array($this,'hookOptionsPage'));
		}
		
		
		/**
		 * Sets the required styles
		 * 
		 * @param	none
		 */
		public function hookStyles() {
			$output = '<style type="text/css">';
			$output.= 'div#jiffuy-settings h2 {background:url('.plugins_url('admin/images/logo_settings.png', __FILE__).') no-repeat 0px 8px;}';
			$output.= 'div#jiffuy-settings ul#userCategories li {background:#f1f1f1 url('.plugins_url('admin/images/arrow_sort.png', __FILE__).') no-repeat 13px 6px;}';
			$output.= '</style>';
			print $output;
		}
		
		
		/**
		 * Hook options page
		 * 
		 * @param	none
		 */
		public function hookOptionsPage() {
			
			$options = $this->getJiffuy()->getSettings();
			
			$output = '<div id="jiffuy-settings" class="wrap">'."\n";
			
			$output.= '<h2>'.__('Settings').'</h2>'."\n";
			$output.= '<form action="options.php" method="post" id="jiffuy-settings">'."\n";
			
			$output.= jiffuyAdmin::getSettingsFields();
			
			$output.= '<fieldset>'."\n";
			$output.= '<h3>'.__('jiffuyAPI - Authentication').'</h3>'."\n";
			$output.= '<p>'.__('The jiffuy API key and secret is required to get authorized by our application! You can find them in your site management settings.').'</p>'."\n";
			$output.= '<p>'."\n";
			$output.= '<label for="apiKey">'.__('jiffuy API Key').'</label><br />'."\n";
			$output.= '<input id="apiKey" class="medium" name="jiffuy[apiKey]" type="text" value="'.$options['apiKey'].'" /><br />'."\n";
			$output.= '</p>'."\n";
			$output.= '<p>'."\n";
			$output.= '<label for="apiSecret">'.__('jiffuy API Secret').'</label><br />'."\n";
			$output.= '<input id="apiSecret" class="medium" name="jiffuy[apiSecret]" type="text" value="'.$options['apiSecret'].'" /><br />'."\n";
			$output.= '</p>'."\n";
			$output.= '<p>'.__('If you don\'t have a jiffuy account just <a href="http://www.jiffuy.com/unternehmen/registrierung" target="_blank">register for one</a> and get your free API key and secret!').'</p>'."\n";
			$output.= '</fieldset>'."\n";
			
			if($this->getJiffuy()->getSettings('apiStatus') == true) {
				
				$output.= '<fieldset>'."\n";
				$output.= '<h3>'.__('jiffuyProducts - Your custom affiliate shop').'</h3>'."\n";
				$output.= '<p>'.__('Your jiffuy shop is available under:').' <a href="'.$this->getJiffuy()->getUrl(array($options['shopBaseUrl'] => 'home')).'" target="_blank">'.$this->getJiffuy()->getUrl(array($options['shopBaseUrl'] => 'home')).'</a></p>';
				$output.= '<p><strong>'.__('URL settings').'</strong></p>'."\n";
				$output.= '<p>'."\n";
				$output.= '<label for="shopBaseUrl">'.__('Base url of the shop (default "shop")').'</label><br />'."\n";
				$output.= '<span class="notice">'.__('Important: Only change this setting at the initial shop setup! Later modifications will damage your search engine rankings.').'</span>';
				$output.= '<input id="shopBaseUrl" name="jiffuy[shopBaseUrl]" type="text" value="'.$options['shopBaseUrl'].'" /><br />'."\n";
				$output.= '</p>'."\n";				
				$output.= '<p><strong>'.__('Display settings').'</strong></p>'."\n";
				$output.= '<p>'."\n";
				$output.= '<label for="shopProductsPerPage">'.__('Products per page (default 20)').'</label><br />'."\n";
				$output.= '<input id="shopProductsPerPage" class="small" name="jiffuy[shopProductsPerPage]" type="text" value="'.$options['shopProductsPerPage'].'" /><br />'."\n";
				$output.= '</p>'."\n";
				$output.= '<p><label for="shopDisplaySidebarColors"><input id="shopDisplaySidebarColors" name="jiffuy[shopDisplaySidebarColors]" type="checkbox" value="1"'.((isset($options['shopDisplaySidebarColors']) && $options['shopDisplaySidebarColors'] != false) ? ' checked="checked"' : '').' /> '.__('Display sidebar color menu').'</label></p>'."\n";
				$output.= '<p><label for="shopDisplaySidebarGenders"><input id="shopDisplaySidebarGenders" name="jiffuy[shopDisplaySidebarGenders]" type="checkbox" value="1"'.((isset($options['shopDisplaySidebarGenders']) && $options['shopDisplaySidebarGenders'] != false) ? ' checked="checked"' : '').' /> '.__('Display sidebar gender menu').'</label></p>'."\n";
				$output.= '<p><label for="shopDisplaySidebarPrices"><input id="shopDisplaySidebarPrices" name="jiffuy[shopDisplaySidebarPrices]" type="checkbox" value="1"'.((isset($options['shopDisplaySidebarPrices']) && $options['shopDisplaySidebarPrices'] != false) ? ' checked="checked"' : '').' /> '.__('Display sidebar prices menu').'</label></p>'."\n";
				$output.= '<p><strong>'.__('Category settings').'</strong></p>'."\n";
				$output.= '<p>'."\n";
				$output.= __('Main categories (Sort by drag & drop)').' &dash; <a href="javascript:void(0);" onclick="jiffuyAddShopCategoryItem();">'.__('Add new category').'</a><br />'."\n";
				$output.= $this->_hookOptionsPageStaticCategories($options);
				$output.= $this->_hookOptionsPageUserCategories($options);
				$output.= '</p>'."\n";
				$output.= $this->_hookOptionsPageCategoryPresets($options);
				$output.= '<p><strong>'.__('SEO settings (experts only)').'</strong></p>'."\n";
				$output.= '<p>'."\n";
				$output.= '<label for="shopProductsIndexTagPagesCount">'.__('Minimum product count of indexed tag pages (recommendation: 3)').'</label><br />'."\n";
				$output.= '<input id="shopProductsIndexTagPagesCount" class="small" name="jiffuy[shopProductsIndexTagPagesCount]" type="text" value="'.$options['shopProductsIndexTagPagesCount'].'" /><br />'."\n";
				$output.= '</p>'."\n";
				$output.= '<p>'."\n";
				$output.= '<label for="shopProductsIndexDetailPages"><input id="shopProductsIndexDetailPages" name="jiffuy[shopProductsIndexDetailPages]" type="checkbox" value="1"'.((isset($options['shopProductsIndexDetailPages']) && $options['shopProductsIndexDetailPages'] != false) ? ' checked="checked"' : '').' /> '.__('Index product detail pages (recommendation: disabled)').'</label>'."\n";
				$output.= '</p>'."\n";
				$output.= '<p>'."\n";
				$output.= '<label for="shopUrlSeparatorParameter">'.__('URL parameter separator (recommendation: ",")').'</label><br />'."\n";
				$output.= '<span class="notice">'.__('Important: Only change this setting at the initial shop setup! Later modifications will damage your search engine rankings.').'</span>';
				$output.= '<input id="shopUrlSeparatorParameter" class="small" name="jiffuy[shopUrlSeparatorParameter]" type="text" value="'.$options['shopUrlSeparatorParameter'].'" />';
				$output.= ' (Allowed separators: [<strong>'.implode('</strong>] [<strong>', $this->getJiffuy()->getSettings('shopUrlAllowedSeparator')).'</strong>] - Note: Has to differ from the current URL group separator!)'."<br />"."\n";
				$output.= '</p>'."\n";
				$output.= '<p>'."\n";
				$output.= '<label for="shopUrlSeparatorGroup">'.__('URL group separator (recommendation: "_")').'</label><br />'."\n";
				$output.= '<span class="notice">'.__('Important: Only change this setting at the initial shop setup! Later modifications will damage your search engine rankings.').'</span>';
				$output.= '<input id="shopUrlSeparatorGroup" class="small" name="jiffuy[shopUrlSeparatorGroup]" type="text" value="'.$options['shopUrlSeparatorGroup'].'" />';
				$output.= ' (Allowed separators: [<strong>'.implode('</strong>] [<strong>', $this->getJiffuy()->getSettings('shopUrlAllowedSeparator')).'</strong>] - Note: Has to differ from the current URL parameter separator!)'."<br />"."\n";
				$output.= '</p>'."\n";
				$output.= '</fieldset>'."\n";
				
				$output.= '<p><a href="javascript:void(0);" onclick="$(\'fieldset#settings-system\').toggle();">'.__('Show/Hide system settings').'</a></p>'."\n";
				$output.= '<fieldset id="settings-system" class="warning">'."\n";
				$output.= '<h3>'.__('General system settings (danger zone – experts only!)').'</h3>'."\n";
				$output.= '<p>'."\n";
				$output.= '<label for="restoreSettings"><input id="restoreSettings" name="jiffuy[restoreSettings]" type="checkbox" value="1" /> '.__('Restore default plugin settings (Warning: Deletes all your personal settings!)').'</label>'."\n";
				$output.= '</p>'."\n";
				$output.= '<p>'."\n";
				$output.= '<label for="cacheExpires">'.__('Cache lifetime in days (recommendation "7" days)').'</label><br />'."\n";
				$output.= '<input id="cacheExpires" name="jiffuy[cacheExpires]" type="text" value="'.(int)$options['cacheExpires'].'" /><br />'."\n";
				$output.= '</p>'."\n";
				$output.= '<p>'.__('Press the clear cache button for cleaning up the cached data.').'</p>'."\n";
				$output.= '<input type="submit" name="clearCache" value="'.__('Clear cache').'" class="button-secondary" /></p>'."\n";
				$output.= '</fieldset>'."\n";
			
			}
			
			$output.= '<p class="submit"><input type="submit" name="submit" value="'.__('Save settings').'" class="button-primary" /></p>'."\n";
			$output.= '</form>'."\n";
			
			$output.= '<p class="version">&copy;'.date('Y').' powered by <a href="http://www.jiffuy.com" target="_blank">jiffuy</a></sup> - Version: '.JIFFUY_VERSION.'</p>';
			
			$output.= '</div>'."\n";
			
			print $output;
			
		}
		
		
		/**
		 * Hook options page static categories
		 * 
		 * @param	array $options
		 */
		private function _hookOptionsPageStaticCategories(array $options) {
			
			$output = '<ul id="staticCategories" class="shopCategories">'."\n";
			
			if(isset($options['shopStaticCategories']) && is_array($options['shopStaticCategories'])) {
				foreach($options['shopStaticCategories'] as $key => $category) {		
					if($key != 'preset') {			
						$output.= '<li id="static-'.$category['slug'].'">'.$this->_hookOptionsPageCategoriesForm($key, $category, 'shopStaticCategories', true).'</li>'."\n";
					}
				}
			}
			
			$output.= '</ul>';
			$output.= '<script type="text/javascript">var userCategories = '.((isset($options['shopStaticCategories']) && is_array($options['shopStaticCategories'])) ? count($options['shopStaticCategories']) - 1 : 0).';</script>';
			
			return $output;
			
		}
		
		
		/**
		 * Hook options page categories
		 * 
		 * @param	array $options
		 */
		private function _hookOptionsPageUserCategories(array $options) {
			
			$output = '<ul id="userCategories" class="shopCategories">'."\n";
			
			if(isset($options['shopUserCategories']) && is_array($options['shopUserCategories'])) {
				
				// build clean category tree
				ksort($options['shopUserCategories']);
				$options['shopUserCategories'] = array_values($options['shopUserCategories']);
				
				// render categories
				foreach($options['shopUserCategories'] as $key => $category) {
					$output.= '<li>'.$this->_hookOptionsPageCategoriesForm($key, $category, 'shopUserCategories', false).'</li>'."\n";
				}
				
			}
			
			$output.= '</ul>';
			$output.= '<script type="text/javascript">var userCategories = '.((isset($options['shopUserCategories']) && is_array($options['shopUserCategories'])) ? count($options['shopUserCategories']) - 1 : 0).';</script>';
			
			return $output;
			
		}
		
		
		/**
		 * Hook options page category presets
		 * 
		 * @param	array $options
		 */
		private function _hookOptionsPageCategoryPresets(array $options) {
			
			// init presets
			$presets = array();
			
			// handle presets
			$presetsList = glob($this->_shopPresetsDirectory.'*.static.inc');			
			foreach($presetsList as $presetStaticUrl) {
				$presetName = str_replace('.static.inc', '', basename($presetStaticUrl));
				$presetDataUrl = $this->_shopPresetsDirectory.$presetName.'.inc';
				if(file_exists($presetStaticUrl) && file_exists($presetDataUrl)) {
					$static = parse_ini_file($presetStaticUrl, true);
					$data = parse_ini_file($presetDataUrl, true);
					if($static !== false && $data !== false) {
						$presets[$presetName] = array('static' => $static, 'data' => $data);
					}
				}
			}
			
			$output = '<p>'."\n";
			$output.= '<label for="shopCategoryPreset">'.__('Shop category themes:').'</label><br />'."\n";
			$output.= '<select id="shopCategoryPreset" name="jiffuy[shopCategoryPreset]">'."\n";
			$output.= '<option value="default">Use custom user settings above (default)</option>'."\n";
			foreach($presets as $name => $data) {
				if(isset($data['static']['preset']['title'])) {
					$output.= '<option value="'.$name.'">'.$data['static']['preset']['title'].'</option>'."\n";
				}
			}
			$output.= '</select>'."\n";
			$output.= '<span class="notice">'.__('Important: A change will overwrite your personal category settings above.').'</span>'."\n";
			$output.= '</p>'."\n";
			
			return $output;
			
		}		
		
		
		/**
		 * Hook options page categories
		 * 
		 * @param	string $id
		 * @param	array $category
		 * @param	string $option option name
		 * @param	boolean $static static handling
		 */
		private function _hookOptionsPageCategoriesForm($id, array $category, $option, $static = false) {
			
			$output = '<p>'."\n";
			$output.= '<label for="shopCategoryName-'.$id.'">'.__('Name').':</label>'."\n";
			$output.= '<input id="shopCategoryName-'.$id.'" class="medium" name="jiffuy['.$option.']['.$id.'][name]" type="text" value="'.$category['name'].'" />'."\n";
			$output.= '&nbsp;<a href="javascript:void(0);" onclick="$(\'div#categoryDetails-'.$id.'\').toggle();">'.__('Change category settings').'</a>'."\n";
			$output.= '</p>'."\n";
			
			$output.= '<div id="categoryDetails-'.$id.'" class="categoryDetails">'."\n";
			
			// slug
			$output.= '<label for="shopCategorySlug-'.$id.'">'.__('URL:').'</label><br />'."\n";
			$output.= '<input id="shopCategorySlug-'.$id.'" class="medium'.(($static === true) ? ' disabled' : '').'" name="jiffuy['.$option.']['.$id.'][slug]" type="text" value="'.$category['slug'].'" '.(($static === true) ? 'disabled' : '').'/>&nbsp;(<i>'.__('critical to change').'</i>)<br />'."\n";
			
			// prepare tags
			$tags = (isset($category['tags']) && is_array($category['tags'])) ? implode(',', $category['tags']) : '';
			
			$output.= '<label for="shopCategoryTags-'.$id.'">'.__('Content tags to include').'</label><br />'."\n";
			$output.= '<input id="shopCategoryTags-'.$id.'" class="tags" name="jiffuy['.$option.']['.$id.'][tags]" type="text" value="'.$tags.'" />'."\n";
			
			// prepare tags
			$tagsNot = (isset($category['tagsNot']) && is_array($category['tagsNot'])) ? implode(',', $category['tagsNot']) : '';
						
			$output.= '<label for="shopCategoryTagsNot-'.$id.'">'.__('Content tags to exclude').'</label><br />'."\n";
			$output.= '<input id="shopCategoryTagsNot-'.$id.'" class="tagsNot" name="jiffuy['.$option.']['.$id.'][tagsNot]" type="text" value="'.$tagsNot.'" />'."\n";
			
			// title
			$output.= '<label for="shopCategoryTitle-'.$id.'">'.__('Title:').'</label><br />'."\n";
			$output.= '<input id="shopCategoryTitle-'.$id.'" class="medium" name="jiffuy['.$option.']['.$id.'][title]" type="text" value="'.$category['title'].'" /><br />'."\n";
			
			// description
			$output.= '<label for="shopCategoryDescription-'.$id.'">'.__('Category description (optional, HTML allowed)').':</label><br />'."\n";
			$output.= '<textarea id="shopCategoryDescription-'.$id.'" class="text" name="jiffuy['.$option.']['.$id.'][description]">'.((isset($category['description']) && strlen($category['description']) > 0) ? $category['description'] : '').'</textarea><br />'."\n";
			
			// meta description
			$output.= '<label for="shopCategoryMetaDescription-'.$id.'">'.__('Meta-Description (optional, plain text, max. 155 Characters)').':</label><br />'."\n";
			$output.= '<input id="shopCategoryMetaDescription-'.$id.'" class="large" name="jiffuy['.$option.']['.$id.'][metaDescription]" type="text" value="'.((isset($category['metaDescription']) && strlen($category['metaDescription']) > 0) ? $category['metaDescription'] : '').'" /><br />'."\n";
			
			// delete category
			$output.= '<p class="deleteCategory"><a class="delete" href="javascript:void(0);" onclick="jiffuyRemoveShopCategoryItem('.$id.');">'.__('Delete category').'</a></p>'."\n";
						
			$output.= '</div>'."\n";
			
			return $output;
			
		}
		
		
		/**
		 * Hook validate options
		 * 
		 * @param	array $data
		 */
		public function hookValidateOptions(array $data) {
			
			// check if any action isset
			if(isset($_REQUEST['clearCache'])) { jiffuyCache::flushCache(); }
			
			// restore default settings if required
			if(isset($data['restoreSettings']) && $data['restoreSettings'] == true) {
				return jiffuy::initSettings(true);
			}
			
			// get settings
			$settings = $this->getJiffuy()->getSettings();
			
			// build settings if api status is fine
			if($settings['apiStatus'] === true) {
				
				// shop settings
				$settings['shopBaseUrl'] = ((isset($data['shopBaseUrl']) && strlen(jiffuy::generateSlug($data['shopBaseUrl'])) > 0) ? str_replace('/', '', jiffuy::generateSlug($data['shopBaseUrl'])) : $settings['shopBaseUrl']);
				
				$settings['shopProductsPerPage'] = ((isset($data['shopProductsPerPage']) && (int)$data['shopProductsPerPage'] > 0) ? (int)$data['shopProductsPerPage'] : (int)$settings['shopProductsPerPage']);
				$settings['shopDisplaySidebarColors'] = ((isset($data['shopDisplaySidebarColors']) && (int)$data['shopDisplaySidebarColors'] > 0) ? true : false);
				$settings['shopDisplaySidebarGenders'] = ((isset($data['shopDisplaySidebarGenders']) && (int)$data['shopDisplaySidebarGenders'] > 0) ? true : false);
				$settings['shopDisplaySidebarPrices'] = ((isset($data['shopDisplaySidebarPrices']) && (int)$data['shopDisplaySidebarPrices'] > 0) ? true : false);
				
				// handle shop categories
				if(isset($data['shopCategoryPreset']) && $data['shopCategoryPreset'] != 'default') {
					
					$settings['shopStaticCategories'] = parse_ini_file($this->_shopPresetsDirectory.$data['shopCategoryPreset'].'.static.inc', true);
					$settings['shopUserCategories'] = parse_ini_file($this->_shopPresetsDirectory.$data['shopCategoryPreset'].'.inc', true);
					
				} else {
				
					if(isset($data['shopStaticCategories'])) { $data = $this->hookValidateOptionsCategories($data, $settings, 'shopStaticCategories', true); }
					if(isset($data['shopUserCategories'])) { $data = $this->hookValidateOptionsCategories($data, $settings, 'shopUserCategories', false); } else { $data['shopUserCategories'] = array(); }
					
					$settings['shopStaticCategories'] = ((isset($data['shopStaticCategories']) && is_array($data['shopStaticCategories'])) ? $data['shopStaticCategories'] : $settings['shopStaticCategories']);
					$settings['shopUserCategories'] = ((isset($data['shopUserCategories']) && is_array($data['shopUserCategories'])) ? $data['shopUserCategories'] : $settings['shopUserCategories']);
				
				}
				
				if(isset($data['shopUrlSeparatorParameter']) && isset($data['shopUrlSeparatorGroup']) && in_array($data['shopUrlSeparatorParameter'], $this->getJiffuy()->getSettings('shopUrlAllowedSeparator')) && in_array($data['shopUrlSeparatorGroup'], $this->getJiffuy()->getSettings('shopUrlAllowedSeparator'))) {
					if($data['shopUrlSeparatorParameter'] != $data['shopUrlSeparatorGroup']) {
						$settings['shopUrlSeparatorParameter'] = $data['shopUrlSeparatorParameter'];
						$settings['shopUrlSeparatorGroup'] = $data['shopUrlSeparatorGroup'];
					}
				}
				
				$settings['shopProductsIndexTagPagesCount'] = ((isset($data['shopProductsIndexTagPagesCount']) && (int)$data['shopProductsIndexTagPagesCount'] > 0) ? (int)$data['shopProductsIndexTagPagesCount'] : (int)$settings['shopProductsIndexTagPagesCount']);
				$settings['shopProductsIndexDetailPages'] = ((isset($data['shopProductsIndexDetailPages']) && (int)$data['shopProductsIndexDetailPages'] > 0) ? true : false);
				
				// system settings
				$settings['cacheExpires'] = ((isset($data['cacheExpires']) && (int)$data['cacheExpires'] > 0) ? (int)$data['cacheExpires'] : (int)$settings['cacheExpires']);
				$settings['cacheApc'] = ((isset($data['cacheApc']) && (int)$data['cacheApc'] > 0) ? true : false);
				
			}
			
			// validate api credentials
			if(isset($data['apiKey']) && isset($data['apiSecret']) && ($data['apiKey'] != $settings['apiKey'] || $data['apiSecret'] != $settings['apiSecret'])) {
				if($this->_hookValidateOptionsApiCredentials($data['apiKey'], $data['apiSecret'])) {
					$settings['apiStatus'] = true;
					$settings['apiKey'] = $data['apiKey'];
					$settings['apiSecret'] = $data['apiSecret'];
				}
			}
			
			return $settings;
			
		}
		
		
		/**
		 * Hook validate options categories
		 * 
		 * @param	array $data
		 * @param	array $current
		 * @param	string $option
		 * @param	boolean $static static category handling
		 */
		public function hookValidateOptionsCategories(array $data, array $current, $option, $static = false) {
			
			// handle shop categories
			if(isset($data[$option]) && is_array($data[$option])) {
				
				// build clean array tree
				if($static === false) {
					ksort($data[$option]);
					$data[$option] = array_values($data[$option]);
				}
				
				foreach($data[$option] as $key => $category) {
					
					// handle category data
					if($static === true) {
						$data[$option][$key]['slug'] = $current[$option][$key]['slug'];
					} else if($static === false) {
						$slug = (isset($category['slug'])) ? jiffuy::generateSlug($category['slug']) : '';
						$data[$option][$key]['slug'] = (strlen($slug) > 0) ? $slug : false;
					}
					
					$data[$option][$key]['name'] = trim(strip_tags($category['name']));
					$data[$option][$key]['tags'] = (!is_array($category['tags'])) ? $this->_hookValidateOptionsTags($category['tags']) : $category['tags'];
					$data[$option][$key]['tagsNot'] = (!is_array($category['tagsNot'])) ? $this->_hookValidateOptionsTags($category['tagsNot']) : $category['tagsNot'];
					
					$data[$option][$key]['title'] = trim(preg_replace('/\s+/',' ', strip_tags($category['title'])));
					$data[$option][$key]['description'] = preg_replace('/\s+/',' ', $category['description']);
					$data[$option][$key]['metaDescription'] = preg_replace('/\s+/',' ', strip_tags($category['metaDescription']));
					
					// check for category requirements
					if(empty($data[$option][$key]['name'])) { add_settings_error('jiffuy', 'error', __('Error: Cannot save categories without a valid name!'), 'error'); $data[$option] = false; return $data; }
					if(empty($data[$option][$key]['title'])) { add_settings_error('jiffuy', 'error', __('Error: Cannot save categories without a valid title!'), 'error'); $data[$option] = false; return $data; }
					if($static === false && empty($data[$option][$key]['slug'])) { add_settings_error('jiffuy', 'error', __('Error: Cannot save categories without a valid slug!'), 'error'); $data[$option] = false; return $data; }
					if($static === false && (!is_array($data[$option][$key]['tags']) || count($data[$option][$key]['tags']) == 0)) { add_settings_error('jiffuy', 'error', __('Error: Cannot save categories without content tags!'), 'error'); $data[$option] = false; return $data; }
					
				}
				
			}
			
			return $data;
			
		}
		
		
		/**
		 * Hook validate options tags
		 * 
		 * @param	string $apiKey
		 * @param	string $apiSecret
		 * @return	array $tags tags array
		 */
		private function _hookValidateOptionsApiCredentials($apiKey, $apiSecret) {
			$api = new jiffuyApi($apiKey, $apiSecret);
			return $api->testConnection();
		}
		
		
		/**
		 * Hook validate options tags
		 * 
		 * @param	string $string comma separated tag string
		 * @return	array $tags tags array
		 */
		private function _hookValidateOptionsTags($string) {
			
			$string = preg_replace('/\s/i',',',$string);
			$string = jiffuy::removeSpecialCharacters(strtolower($string), false, '\,');
			$tags = explode(',', $string);
			$tags = array_filter($tags);
			
			return $tags;
			
		}
		
		
		/**
		 * Hook validate options
		 * 
		 * @param	none
		 */
		public static function getSettingsFields() {
			
			// buffer output
			ob_start();
			settings_fields('jiffuy');
			$settingsFields = ob_get_contents();
			ob_end_clean();
			
			return $settingsFields;
			
		}
			
		
	}
	
}

