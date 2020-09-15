<?php 
/**
 * The helpers functions for theme use
 *
 * This file contains a toolbox to use the HDLA features in theme and plugins
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
 * Returns the full like form
 */
function hdla_getlike_form($post_id = "") {
    Hd_Like_Anything_Public::output_like_form($post_id);
}