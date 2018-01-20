<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://about.me/sagarprajapati48
 * @since             1.0.0
 * @package           Temporary_Access_For_Users
 *
 * @wordpress-plugin
 * Plugin Name:       Temporary access for users
 * Plugin URI:        https://wordpress.org/plugins/temporary-access-for-users/
 * Description:       This plugin is used for provide temporary access to user
 * Version:           1.0.0
 * Author:            Sagar Prajapati
 * Author URI:        https://about.me/sagarprajapati48
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       temporary-access-for-users
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-temporary-access-for-users-activator.php
 */
function activate_temporary_access_for_users() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-temporary-access-for-users-activator.php';
	Temporary_Access_For_Users_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-temporary-access-for-users-deactivator.php
 */
function deactivate_temporary_access_for_users() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-temporary-access-for-users-deactivator.php';
	Temporary_Access_For_Users_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_temporary_access_for_users' );
register_deactivation_hook( __FILE__, 'deactivate_temporary_access_for_users' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-temporary-access-for-users.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_temporary_access_for_users() {

	$plugin = new Temporary_Access_For_Users();
	$plugin->run();

}
run_temporary_access_for_users();
