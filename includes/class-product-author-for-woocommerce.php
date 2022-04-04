<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    WPA_Woo_Product_Author
 * @subpackage WPA_Woo_Product_Author/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WPA_Woo_Product_Author
 * @subpackage WPA_Woo_Product_Author/includes
 * @author     Sahil Multani
 */

if ( !class_exists( 'WPA_Woo_Product_Author' ) ) {
	class WPA_Woo_Product_Author {
		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      WPA_Woo_Product_Author_Loader $loader Maintains and registers all hooks for the plugin.
		 */
		protected $loader;
		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $plugin_name The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;
		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $version The current version of the plugin.
		 */
		protected $version;
		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			$this->plugin_name = 'advanced-product-author-for-woocommerce';
			$this->version     = WPA_PLUGIN_VERSION;
			$this->load_dependencies();
			$this->define_admin_hooks();
			$prefix = is_network_admin() ? 'network_admin_' : '';
			add_filter( "{$prefix}plugin_action_links_" . WPA_PLUGIN_BASENAME, array(
				$this,
				'wpa_plugin_action_links',
			), 10, 4 );
		}
		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {
			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-product-author-for-woocommerce-loader.php';
			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-product-author-for-woocommerce-admin.php';
			
			$this->loader = new WPA_Woo_Product_Author_Loader();
		}
		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {
			$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
			$plugin_admin = new WPA_Woo_Product_Author_Admin( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'admin_init', $plugin_admin, 'wpa_welcome_screen_do_activation_redirect' );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'wpa_enqueue_admin_scripts' );
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'wpa_admin_menu_intigration' );

			$this->loader->add_action( 'init', $plugin_admin, 'wpa_woo_product_author_support', 999 );

			$this->loader->add_action( 'wp_ajax_wpa_save_product_author_genral_settings', $plugin_admin, 'wpa_save_product_author_genral_settings' );
			$this->loader->add_action( 'wp_ajax_nopriv_wpa_save_product_author_genral_settings', $plugin_admin, 'wpa_save_product_author_genral_settings' );

			if ( ! empty( $page ) && ( false !== strpos( $page, 'wpa' ) ) ) {
				$this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'wpa_admin_footer_review' );
			}
		}
		/**
		 * Return the plugin action links.  This will only be called if the plugin
		 * is active.
		 *
		 * @param array $actions associative array of action names to anchor tags
		 *
		 * @return array associative array of plugin action links
		 * @since 1.0.0
		 */
		public function wpa_plugin_action_links( $actions ) {
			$custom_actions = array(
				'configure' => sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( array( 'page' => 'wpa-woo-author-settings' ), admin_url( 'admin.php' ) ) ), __( 'Settings', 'advanced-product-author-for-woocommerce' ) ),
				'support'   => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://wordpress.org/support/plugin/advanced-product-author-for-woocommerce/' ), __( 'Support', 'advanced-product-author-for-woocommerce' ) ),
			);
			// add the links to the front of the actions list
			return array_merge( $custom_actions, $actions );
		}
		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}
		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @return    string    The name of the plugin.
		 * @since     1.0.0
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}
		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @return    Advanced_Flat_Rate_Shipping_For_WooCommerce_Pro_Loader    Orchestrates the hooks of the plugin.
		 * @since     1.0.0
		 */
		public function get_loader() {
			return $this->loader;
		}
		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @return    string    The version number of the plugin.
		 * @since     1.0.0
		 */
		public function get_version() {
			return $this->version;
		}
	}
}