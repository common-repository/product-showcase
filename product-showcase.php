<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Product_Showcase
 *
 * @wordpress-plugin
 * Plugin Name:       Product Showcase
 * Plugin URI:        http://example.com/product-showcase-uri/
 * Description:       Bietet eine Fläche zur Darstellung von Produkten
 * Version:           1.0.0
 * Author:            Maik Schmaddebeck
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       product-showcase
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-product-showcase-activator.php
 */
function activate_product_showcase() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-product-showcase-activator.php';
	Product_Showcase_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-product-showcase-deactivator.php
 */
function deactivate_product_showcase() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-product-showcase-deactivator.php';
	Product_Showcase_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_product_showcase' );
register_deactivation_hook( __FILE__, 'deactivate_product_showcase' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-product-showcase.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_product_showcase() {

	$plugin = new Product_Showcase();
	$plugin->run();

}
run_product_showcase();
