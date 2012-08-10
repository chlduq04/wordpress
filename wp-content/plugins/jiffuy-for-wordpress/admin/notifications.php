<?php

/**
 *
 * Class for handling the jiffuy plugin admin notifications part
 *
 * @category   jiffuy
 * @package    Wordpress
 * @subpackage Admin Notifications
 * @copyright  2012 jiffuy (http://www.jiffuy.com)
 * @license    Closed Source, All Rights Reserved
 * @author     jiffuy <info@jiffuy.com>
 */
if(!class_exists('jiffuyAdminNotifications')) {

	class jiffuyAdminNotifications {
		
		
		/**
		 * jiffuy object
		 * @var jiffuy
		 */
		protected $_jiffuy = null;
		
		
		/**
		 * Constructor
		 * 
		 * @param	jiffuy $jiffuy
		 */
		public function __construct(jiffuy $jiffuy) {
			
			// init vars
			$this->_jiffuy = $jiffuy;
			
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
			add_action('admin_notices', array($this, 'hookAdminNotices'));
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
		 * Hook admin notices
		 * 
		 * @param	none
		 * @return	return the validate api key notification
		 */
		public function hookAdminNotices() {
			
			// admin notices
			$this->hookAdminNoticesApi();
			
		}
		
		
		/**
		 * Hook admin notices api
		 * 
		 * @param	none
		 * @return	return the validate api key notification
		 */
		public function hookAdminNoticesApi() {
				
			$settings = $this->getJiffuy()->getSettings();
				
			if(!isset($settings['apiKey']) || strlen($settings['apiKey']) == 0 || !isset($settings['apiSecret']) || strlen($settings['apiSecret']) == 0) {
				print self::getWarning('<p><strong>'.__('jiffuy is almost ready.').'</strong> '.sprintf(__('Just <a href="%1$s">enter your jiffuy API key and secret</a> to activate the plugin.'), "options-general.php?page=jiffuy").'</p>');
			} else if($this->getJiffuy()->getSettings('apiStatus') === false) {
				print self::getWarning('<p><strong>'.__('There is a problem with your API key or secret!').'</strong> '.sprintf(__('You must <a href="%1$s">enter a valid jiffuy API key and secret</a> to finally activate the plugin.'), "options-general.php?page=jiffuy").'</p>');
			}
			
		}
		
		
		/**
		 * Get Warning
		 * 
		 * @param	string $warning warning to show
		 * @return	string with warning
		 */
		public static function getWarning($warning) {
			
			$content = '<div id="jiffuy-warning" class="updated fade">'."\n";
			$content.= $warning."\n";
			$content.= '</div>'."\n";
			
			return $content;
			
		}
		
		
	}
	
}

