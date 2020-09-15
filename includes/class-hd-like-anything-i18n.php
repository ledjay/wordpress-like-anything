<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       Jérémie Gisserot
 * @since      1.0.0
 *
 * @package    Hd_Like_Anything
 * @subpackage Hd_Like_Anything/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Hd_Like_Anything
 * @subpackage Hd_Like_Anything/includes
 * @author     HD Team <jeremie@labubulle.com>
 */
class Hd_Like_Anything_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'hd-like-anything',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
