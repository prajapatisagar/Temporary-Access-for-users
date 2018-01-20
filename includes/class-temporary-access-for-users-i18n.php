<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://about.me/sagarprajapati48
 * @since      1.0.0
 *
 * @package    Temporary_Access_For_Users
 * @subpackage Temporary_Access_For_Users/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Temporary_Access_For_Users
 * @subpackage Temporary_Access_For_Users/includes
 * @author     Sagar Prajapati <sagarprajapati48@gmail.com>
 */
class Temporary_Access_For_Users_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'temporary-access-for-users',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
