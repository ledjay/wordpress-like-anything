<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              Jérémie Gisserot
 * @since             1.0.0
 * @package           Hd_Like_Anything
 *
 * @wordpress-plugin
 * Plugin Name:       Happy Dev Like Anything
 * Plugin URI:        https://www.happy-dev.fr
 * Description:       Gestion des likes
 * Version:           1.0.0
 * Author:            HD Team
 * Author URI:        Jérémie Gisserot
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hd-like-anything
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'HD_LIKE_ANYTHING_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hd-like-anything-activator.php
 */
function activate_hd_like_anything() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hd-like-anything-activator.php';
	Hd_Like_Anything_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hd-like-anything-deactivator.php
 */
function deactivate_hd_like_anything() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hd-like-anything-deactivator.php';
	Hd_Like_Anything_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hd_like_anything' );
register_deactivation_hook( __FILE__, 'deactivate_hd_like_anything' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hd-like-anything.php';

/**
 * The helpers functions for theme use
 */
require plugin_dir_path( __FILE__ ) . 'helpers/helpers.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_hd_like_anything() {

	$plugin = new Hd_Like_Anything();
	$plugin->run();

}
run_hd_like_anything();