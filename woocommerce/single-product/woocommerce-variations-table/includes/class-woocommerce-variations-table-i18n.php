<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://plugins.db-dzine.com
 * @since      1.0.0
 *
 * @package    WooCommerce_Variations_Table
 * @subpackage WooCommerce_Variations_Table/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WooCommerce_Variations_Table
 * @subpackage WooCommerce_Variations_Table/includes
 * @author     Daniel Barenkamp <support@db-dzine.com>
 */
class WooCommerce_Variations_Table_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$loaded = load_plugin_textdomain(
			'woocommerce-variations-table',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
