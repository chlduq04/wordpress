
function jiffuyShop() {
	
	
	/**
	 * Options
	 * @var object
	 */
	this.options = new Object();
	
	/**
	 * Set myself
	 * @var object
	 */
	var self = this;
	
	
	/**
	 * Init jiffuy shop
	 * 
	 * @param	object $options
	 */
	this.initialize = function(options) {
		
		// init vars
		self.options = options;
		
	};
	
	
	/**
	 * Track conversion
	 */
	this.trackConversion = function(clicktype, advertiser, price, id, title, brand) {
		
		// build product title with brand
		if(typeof brand != 'undefined' && brand.length > 0) {
			title = title + ' ('+brand+')';
		}
		
		// handle juy tracking
		if(typeof juyT != 'undefined') {
		
			// track ecommerce product
			juyT.addEcommerceItem(
				id, // (required) SKU: Product unique identifier
				title, // (optional) Product name
				advertiser, // (optional) Product category. You can also specify an array of up to 5 categories eg. ["Books", "New releases", "Biography"]
				price, // (recommended) Product price
				1 // (optional, default to 1) Product quantity
			);
			
			// track ecommerce conversion
			juyT.trackEcommerceOrder(
				new Date().getTime(), // (required) Unique Order ID
				price, // (required) Order Revenue grand total (includes tax, shipping, and subtracted discount)
				price, // (optional) Order sub total (excludes shipping)
				0, // (optional) Tax amount
				0, // (optional) Shipping amount
				false // (optional) Discount offered (set to false for unspecified parameter)
			);
		
		}
		
		// handle google analytics tracking
		if(typeof _gaq != 'undefined') {
			
			// track ecommerce product
			_gaq.push(['_trackEvent', clicktype, advertiser, brand]);
			
		}
		
	};
	
	
}

var jiffuyShop = new jiffuyShop();
window.onload = function() {
	jiffuyShop.initialize(jiffuyShopOptions);
};
