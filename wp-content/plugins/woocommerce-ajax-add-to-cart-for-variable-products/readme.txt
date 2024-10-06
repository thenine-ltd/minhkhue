=== Woocommerce Ajax add to cart for variable products ===
Contributors: rcreators
Donate link: NA
Tags: Woocommerce, Ajax, Variable Products, Add to cart
Requires at least: 4.6
Tested up to: 6.4.2
Stable tag: 2.2.1
WC requires at least: 3.0
WC tested up to: 8.5.2
Text domain: woocommerce-ajax-add-to-cart-variable-products
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin activate add to cart through ajax on varible product.

== Description ==

This plugin activate add to cart through ajax on varible product. By default woocommerce is not having this feature. Plugin is adding own jquery which is differ from woocommerce default add to cart jquery for simple product.

This plugin also supports sending additional field and other data added by other plugins. It will work with most of product addon and extra field data.

Let me know if you stumble upon any plugin conflict in support forum. 

== Installation ==

1. Upload `woocommerce-ajax-add-to-cart-variable-products` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How this plugin work? =

This plugin add new javascript file in your theme footer, which gets required data from the page when you click on add to cart button on varible product page and sent it to php ajax function of plugin.

= Is this plugin work with my theme? =

Yes this plugin will work with most themes. Just make sure you didn't removed any css classes from add to cart button from variable product template.

= Is this plugin only add functionality on single page or archive page or category page =

This plugin activate ajax functionality everywhere. So like is it a single page, category page, archive page or even sidebar with shortcode, where ever it gets varible product, it will work with ajax functionality.

== Screenshots ==

1. screenshot-1.jpg

== Changelog ==
= 2.2.1 =
* Fixed - Textdomain Fixed as per WP Standard
* Added - Language Folder added to plugin for future translation

= 2.2 =
* Fixed - PHP Error at line no 157 for $this. Removed code, it was not required for new version of WP
* Fixed - Now it will work with any variation from attributes

= 2.1 =
* Fixed - Different Qty for same variation was treated as separate line item in cart. Fixed now.

= 2.0 =
* Added - Now plugin will work with other plugin like name your price, product bundle, product  add-on. Thanks to @Tofandel for jQuery hint. Was able to work around and made PHP adjustment accordingly. 

= 1.5 =
* Fixed - Update Code for fetching id error, $product->id to $product->Get_ID()

= 1.4 =
* Fixed - Issue with sometheme ajax not working for single product when plugin activated

= 1.3 =
* Fixed - Updated code for getting product_type as per new woocommerce methods
* Fixed - Added stripslash to data. so now variation with slash work. : SpabRice
* Fixed - Set Cookies for current cart and old cart items. : unicco

= 1.2.9 =
* Fixed - Simple product ajax works with plugin now.

= 1.2.8 =
* Added support for woocommerce lightbox plugin
* Js updated to work with most of the theme now

= 1.2.7 =
* Network Activation Added - Suggested by User lucastello
* Redirect to cart page if option selected in woocommerce setting

= 1.2.6 =
* Jquery Refined with latest woocommerce version.
* Backward compability for swatches and hidden input variations

= 1.2.5 =
* Updated Jquery to work with Radio button plugins. :  mantish - WC Variations Radio Buttons - 8manos

= 1.2.4 =
* Updated Jquery issue reported by user. : david127, nonverbla
* Js Improvement suggested by user, now it will work with multiple tye of variations. : Igor Jerosimic
* Removed AddtocartAjax localize script which was not in use.
* Supports Latest Woocommerce and wordpress.

= 1.2.3 =
* Updated Jquery, so it works properly with IE10 / IE11

= 1.2.2 =
* jquery updated. so if no variation selected, user will get error to select variable.

= 1.2.1 =
* Minor fix for setting tab issue

= 1.2 =
* Added Selection in woocommerce product tab wc ajax variable product setting for variation selection need on category / shop page or not.
* Added Strip Html security fix. / Thanks - Michal for pointing out this security bug
* Added support for other variable swatches and color box selection plugin / Thanks - Mycreativeway for updated jquery code

= 1.1.1 =
* Added Ob_start() starting of hooks so it works perfect on chrome and Firefox. / Thanks - Michal for mail on it.

= 1.1 =
* Functions updated to work with minicart widget.
* Now Default cart widget of woocommerce will also update same time with adding to cart.

= 1.0.3 =
* Updated the Function in which Cart Fragments was not updating in Chrome. Will work on all browser now without issue.

= 1.0.2 =
* Updated function as ajax was not working for guest users. / - Thanks - sharpe89 to pointing issue.

= 1.0.1 =
* Bug Fix to not load js file after activation
* Remove files which not required from plugin

= 1.0 =
* Dirctly works after activation.
* No any setting page.

== Upgrade Notice ==

= 1.0 =
As Woocommerce not having add to cart with ajax for variable product, plugin adds this small functionality. So Users cannot dig into code for same.