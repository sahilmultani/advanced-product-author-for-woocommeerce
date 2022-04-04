<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Fired during plugin deactivation
 *
 * @since      1.0.0
 *
 * @package    WPA_Woo_Product_Author
 * @subpackage WPA_Woo_Product_Author/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    WPA_Woo_Product_Author
 * @subpackage WPA_Woo_Product_Author/includes
 * @author     Multidots <inquiry@multidots.in>
 */

if ( !class_exists( 'WPA_Woo_Product_Author_Deactivator' ) ) {
	class WPA_Woo_Product_Author_Deactivator {

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function deactivate() {

		}

	}
}