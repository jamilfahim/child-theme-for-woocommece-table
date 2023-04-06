<?php

/**
 * The plugin bootstrap file
 *
 *
 * @link              https://welaunch.io
 * @since             1.0.0
 * @package           WooCommerce_Variations_Table
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Variations Table
 * Plugin URI:        https://welaunch.io/plugins/woocommerce-variations-table/
 * Description:       Transform your Variation Products into nice tables.
 * Version:           1.3.9
 * Author:            weLaunch
 * Author URI:        https://welaunch.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-variations-table
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-variations-table-activator.php
 */
function activate_woocommerce_variations_table() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-variations-table-activator.php';
	WooCommerce_Variations_Table_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-variations-table-deactivator.php
 */
function deactivate_woocommerce_variations_table() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-variations-table-deactivator.php';
	WooCommerce_Variations_Table_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_variations_table' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_variations_table' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-variations-table.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_variations_table() {

	$plugin_data = get_plugin_data( __FILE__ );
	$version = $plugin_data['Version'];

	$plugin = new WooCommerce_Variations_Table($version);
	$plugin->run();

	return $plugin;

}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php') && (is_plugin_active('redux-dev-master/redux-framework.php') || is_plugin_active('redux-framework/redux-framework.php') ||  is_plugin_active('welaunch-framework/welaunch-framework.php') ) ){
	$WooCommerce_Variations_Table = run_woocommerce_variations_table();
} else {
	add_action( 'admin_notices', 'woocommerce_variations_table_installed_notice' );
}

function woocommerce_variations_table_installed_notice()
{
	?>
    <div class="error">
      <p><?php _e( 'WooCommerce Variations Table requires the WooCommerce & weLaunch Framework plugin. Please install or activate them before: https://www.welaunch.io/updates/welaunch-framework.zip', 'woocommerce-variations-table'); ?></p>
    </div>
    <?php
}