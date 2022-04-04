<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    WPA_Woo_Product_Author
 * @subpackage WPA_Woo_Product_Author/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WPA_Woo_Product_Author
 * @subpackage WPA_Woo_Product_Author/includes
 * @author     Sahil Multani
 */
if ( !class_exists( 'WPA_Woo_Product_Author_Activator' ) ) {
	class WPA_Woo_Product_Author_Activator {

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function activate() {
			set_transient( '_welcome_screen_wpa_mode_activation_redirect_data', true, 30 );
			
			if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) && ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
				wp_die( "<strong>Advanced Product Author for WooCommerce</strong> plugin requires <strong>WooCommerce</strong>. Return to <a href='" . esc_url( get_admin_url( null, 'plugins.php' ) ) . "'>Plugins page</a>." );
			}
		}
	}
}