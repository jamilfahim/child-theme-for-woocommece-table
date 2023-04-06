<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://plugins.db-dzine.com
 * @since      1.0.0
 *
 * @package    WooCommerce_Variations_Table
 * @subpackage WooCommerce_Variations_Table/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WooCommerce_Variations_Table
 * @subpackage WooCommerce_Variations_Table/admin
 * @author     Daniel Barenkamp <support@db-dzine.com>
 */
class WooCommerce_Variations_Table_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function load_redux(){
	    // Load the theme/plugin options
	    if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/options-init.php' ) ) {
	        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/options-init.php';
	    }
	}
}
