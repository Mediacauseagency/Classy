<?php

/**
 * The plugin bootstrap file
 *
 * @link              http://mediacause.org
 * @since             1.0.0
 * @package           Classy
 *
 * @wordpress-plugin
 * Plugin Name:       Classy
 * Plugin URI:        http://mediacause.org
 * Description:       A tool used to integrate Classy's API into easily accessible shortcodes
 * Version:           1.0.0
 * Author:            Media Cause
 * Author URI:        http://mediacause.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       classy
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-classy-activator.php
 */
function activate_classy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-classy-activator.php';
	Classy_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-classy-deactivator.php
 */
function deactivate_classy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-classy-deactivator.php';
	Classy_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_classy' );
register_deactivation_hook( __FILE__, 'deactivate_classy' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-classy.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_classy() {

	$plugin = new Classy();
	$plugin->run();

}
run_classy();
