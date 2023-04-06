# Changelog
======
1.3.9
======
- NEW:	Added DE, NL, IT, FR Translations
- FIX:	JS FN extend not available
- FIX:	Added URL Decode to add to cart

======
1.3.8
======
- NEW:	Added support for our own Wishlist + Yith Wishlist Plugin (Regular does not support variations)
		https://www.welaunch.io/en/product/woocommerce-wishlist/
		https://imgur.com/a/7q3vzrx
- NEW:	Added Support for custom Meta Data
		https://imgur.com/a/CptctDE
- NEW:	Support for our Single Variations Plugin
		https://www.welaunch.io/en/product/woocommerce-single-variations/
		https://imgur.com/a/spRnpai
- NEW:	Added support for yith barcode plugin
- NEW:	Attributes are now sorted by float values
- NEW:	Name also sorted by default
- NEW:	All CSS & JS only gets loaded when needed
- FIX:	Removed datatables CDN
- FIX:	Removed search input field from enquiry + cart button columns
- FIX:	Price field sorting fixed

======
1.3.7
======
- NEW:	Added support for our PDF Export Products plugin: https://www.welaunch.io/en/product/woocommerce-print-products/
		https://imgur.com/a/PlYrG5q
- FIX:	Enquiry functionality not working

======
1.3.6
======
- NEW:	Dropped Redux Framework support and added our own framework 
		Read more here: https://www.welaunch.io/en/2021/01/switching-from-redux-to-our-own-framework
		This ensure auto updates & removes all gutenberg stuff
		You can delete Redux (if not used somewhere else) afterwards
		https://www.welaunch.io/updates/welaunch-framework.zip
		https://imgur.com/a/BIBz6kz

======
1.3.5
======
- FIX:	Data existing check

======
1.3.4
======
- NEW:	Added is type variable check to shortcode
- FIX:	Variation table data check

======
1.3.3
======
- FIX:	Added support for on utf8 attributes

======
1.3.2
======
- FIX:	Better handling of out of stock products

======
1.3.1
======
- FIX:	Quantity will be set to zero after adding all to cart
- FIX:	Removed alert added to cart

======
1.3.0
======
- NEW:	Performance increase in admin panel through AJAX loading
		!! MAKE SURE YOU ARE ON LATEST VERSION OF REDUX FRAMEWORK !!

======
1.2.19
======
- FIX:	Do not render title if empty
- FIX:	Flatsome support
- FIX:	Image overlapping 
- FIX:	Updated all datatable scripts to latest version

======
1.2.18
======
- FIX:	PHP errors in shortcode
- FIX:	Paging not working

======
1.2.17
======
- NEW:	Quantity field showed when out of stock

======
1.2.16
======
- FIX:	Get Dimensions & Weight display issue

======
1.2.15
======
- FIX:	On Backorder showed out of stock status
- FIX:	Updated german Translations (Thanks to Martina Stoilova)

======
1.2.14
======
- FIX:	Multiple cart button not working

======
1.2.13
======
- FIX:	Stroke price when filtering disabled / otherwise only sale price shows

======
1.2.12
======
- FIX:	2 shortcodes broke datatables HTML code
- FIX:	Add all to cart not working with 2 tables

======
1.2.11
======
- FIX:	Multiple shortcodes showing same products

======
1.2.10
======
- FIX:	Attributes not showing in cart

======
1.2.9
======
- NEW: 	Old theme support option in advanced settings
		Enable this when attributes do not show in cart.

======
1.2.8
======
- NEW:	Default Filtering when you have set a default variation
		Example: https://imgur.com/a/4NWGBc6
- FIX:	Removed background color for table header

======
1.2.7
======
- NEW:	Readded the exclusion functionality
- NEW:	Added transient caching for wp-admin settings panel

======
1.2.6
======
- FIX:	Uncaught SyntaxError: Unexpected token o in JSON at position 1 at JSON.parse

======
1.2.5
=====
- NEW:	Added an option to set a default page length when paging enabled
- NEW:	Added an option to set customn page length menu when paging enabled
- NEW:	Added support for our attribute images plugin
		images for attribute values can now appear in the columns see: https://imgur.com/a/OhRVtEp

======
1.2.4
=====
- NEW:	Filter for disabling the variations table: 
		woocommerce_variations_table_enabled
		apply_filters('woocommerce_variations_table_enabled', true, $product );

======
1.2.3
=====
- NEW:	Variations table shortcode now tries to take current product when no product ID is set
		This to work with page builders.

======
1.2.2
=====
- FIX:	Multiple Header rows appeared when using shortcode multiple times

======
1.2.1
======
- FIX:	Quantity was changeable through keyboard input and not respecting max qt
- NEW:	Added description of what CB and QT is in plugin settings

======
1.2.0
======
- NEW:	Lightbox for variation product images
- FIX:	Quantity max now respects the stock level

======
1.1.15
======
- NEW:	Added a default option to return custom post meta data
		when using 

======
1.1.14
======
- NEW:	Added Support for Cart Redirect after Add to Cart

======
1.1.13
======
- FIX:	Added Flatsome AJAX add to cart support

======
1.1.12
======
- NEW:	Added an option to set a default column order and column order type (asc or desc)

======
1.1.11
======
- NEW:	Alert message, that products were added to cart
- FIX:	Responsive option disabled single add to cart

======
1.1.10
======
- FIX:	Added Attributes to add to cart functionality
- FIX:	PHP Notices

======
1.1.9
======
- FIX:	PHP Notice:  attributes was called incorrectly
- FIX:	Removed strip tags

======
1.1.8
======
- FIX:	Sales price not stroke through

======
1.1.7
======
- FIX:	IE11 JS Fix
- FIX:	Added return messages

======
1.1.6
======
- NEW:	Added support to our Catalog Mode Plugin
		-> means you can add an enquiry button to your table
		See: https://codecanyon.net/item/woocommerce-product-catalog-mode/14518494
- FIX:	Removed Exclusions for better Performance

======
1.1.5
======
- NEW:	Another option to use select filters called Column Filter Widgets
- NEW:	Fixed Table Header Option
- NEW:	Fixed Table Footer Option
- NEW:	Search in Columns by Input Field
- FIX:	Stripped Price HTML tags for better filtering
- FIX:	Export Buttons are now Translateable

======
1.1.4
======
- NEW:	Multiple Add to Cart with Quantity (QT)
		Users can set only quantities and add them all to cart
		This does not work in Conjunction with the Checkbox (CB)
		

======
1.1.3
======
- NEW:	Multiple Add to Cart now respect quantity
- NEW:	QUantity has now it's own column
- FIX:	Multiple add to cart bug fixes not showing variation

======
1.1.2
======
- FIX:	Export buttons always showed when all deactivated

======
1.1.1
======
- NEW:	Product Name can be added to variation data
		See Settings > Variation Tanble > Variation Data

======
1.1.0
======
- NEW:	Shortcode now support multiple products or all products from a category
		See here: https://plugins.db-dzine.com/woocommerce-variations-table/faq/shortcode/

======
1.0.4
======
- NEW:	Option to show a checkbox for multiple add to cart
- FIX:	Missing translation "Show All"

======
1.0.3
======
- FIX:	Stock Status Translations

======
1.0.2
======
- FIX:	Updated Translation files

======
1.0.1
======
- NEW:	Shortcode [woocommerce_variations_table product="PRODUCT-ID"]
- FIX:	Added image to print export
- FIX:	Removed select fields from export

======
1.0.0
======
- Inital release