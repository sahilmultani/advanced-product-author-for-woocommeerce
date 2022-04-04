<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    WPA_Woo_Product_Author
 * @subpackage WPA_Woo_Product_Author/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WPA_Woo_Product_Author
 * @subpackage WPA_Woo_Product_Author/admin
 * @author     Sahil Multani
 */
if ( !class_exists( 'WPA_Woo_Product_Author_Admin' ) ) {
	class WPA_Woo_Product_Author_Admin {
		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;
		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;
		/**
		 * Initialize the class and set its properties.
		 *
		 * @param string $plugin_name The name of this plugin.
		 * @param string $version     The version of this plugin.
		 *
		 * @since    1.0.0
		 */
		public function __construct( $plugin_name, $version ) {
			$this->plugin_name = $plugin_name;
			$this->version     = $version;
		}

		/**
		 * Redirect to quick start guide after plugin activation
		 *
		 * @since    1.0.0
		 */
        public function wpa_welcome_screen_do_activation_redirect() {
        	// if no activation redirect
			if ( ! get_transient( '_welcome_screen_wpa_mode_activation_redirect_data' ) ) {
				return;
			}
			// Delete the redirect transient
			delete_transient( '_welcome_screen_wpa_mode_activation_redirect_data' );
			// if activating from network, or bulk
			$activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_STRING );
			if ( is_network_admin() || isset( $activate_multi ) ) {
				return;
			}
			// Redirect to welcome  page
			wp_safe_redirect( add_query_arg( array( 'page' => 'wpa-woo-author-settings' ), admin_url( 'admin.php' ) ) );
			exit;
        }

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @param string $hook display current page name
		 *
		 * @since    1.0.0
		 *
		 */
		public function wpa_enqueue_admin_scripts( $hook ) {
			if ( false !== strpos( $hook, 'wc-plugins_page_wpa' ) ) {
				wp_enqueue_style( $this->plugin_name . 'chosen-style', plugin_dir_url( __FILE__ ) . 'css/chosen.min.css', array(), 'all' );
		        wp_enqueue_style( $this->plugin_name . 'main-style', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), 'all' );

		        wp_enqueue_script( $this->plugin_name . 'chosen-js', plugin_dir_url( __FILE__ ) . 'js/chosen.jquery.min.js', array('jquery'), $this->version, true );
		        wp_enqueue_script( $this->plugin_name . 'admin-js', plugin_dir_url( __FILE__ ) . 'js/woo-product-author-admin.js', array(), $this->version, true );

		        wp_localize_script( $this->plugin_name . 'admin-js', 'coditional_vars', array(
						'ajaxurl' => admin_url( 'admin-ajax.php' ),
					)
				);
		    }
		}

		/*
	     * Woo Product Menu Integration
	     *
	     * @since    1.0.0
	     */
	    public function wpa_admin_menu_intigration()
	    {
	    	global  $GLOBALS ;
	        if ( empty($GLOBALS['admin_page_hooks']['smwc_plugins']) ) {
	            add_menu_page(
	                'WC Plugins',
	                __( 'WC Plugins', 'advanced-product-author-for-woocommerce' ),
	                'null',
	                'smwc_plugins',
	                'smwc_menu_page',
	                WPA_PLUGIN_URL . 'images/woo-icon.png',
	                25
	            );
	        }
			add_submenu_page(
	            'smwc_plugins',
	            'Product Author for WooCommerce',
	            __( 'Product Author for WooCommerce', 'advanced-product-author-for-woocommerce' ),
	            'manage_options',
	            'wpa-woo-author-settings',
	            array(
					$this,
					'wpa_admin_settings_page',
				)
	        );
	    }
		/**
		 * Plugin settings page
		 *
		 * @since    1.0.0
		 */
	    public function wpa_admin_settings_page()
	    {
	        require_once plugin_dir_path( __FILE__ ) . 'partials/wpa-admin-settings-page.php';
	    }

	    /**
	     * Show admin footer review text.
	     *
	     * @since    1.0.0
	     */
	    public function wpa_admin_footer_review() {
			$url = esc_url( 'https://wordpress.org/plugins/advanced-product-author-for-woocommerce/#reviews' );
            echo sprintf( wp_kses( __( 'If you like <strong>Advanced Product Author for WooCommerce</strong> plugin, please leave us ★★★★★ ratings on <a href="%1$s" target="_blank">WP.org</a>.', 'advanced-product-author-for-woocommerce' ), array(
	                'strong' => array(),
	                'a'      => array(
		                'href'   => array(),
		                'target' => 'blank',
		            ),
	            ) ), esc_url( $url ) );

            return '';
        }

        /**
	     * Add author support for woocommerce products
	     *
	     * @since    1.0.0
	     */
        public function wpa_woo_product_author_support() {
        	$wpa_settings_enable_disable = get_option( 'wpa_settings_enable_disable' );
        	$wpa_select_users_list	 	 = get_option( 'wpa_select_users_list' );
        	$wpa_select_user_role_list	 = get_option( 'wpa_select_user_role_list' );

        	if ( isset($wpa_settings_enable_disable) && empty($wpa_settings_enable_disable) ) {
				$wpa_settings_enable_disable = 'on';
			}

        	// get current user
			$user_data = $this->wpa_get_current_user_details();

			$wpa_users_restrict_flag = 0;
        	if ( isset($wpa_select_users_list) && is_array($wpa_select_users_list) && !empty($wpa_select_users_list) ) {
				if ( isset($user_data) && is_array($user_data) && !empty($user_data) ) {
					if (is_user_logged_in()) {
	                    if (in_array( $user_data[1], $wpa_select_users_list, true )) {
	                        $wpa_users_restrict_flag = 1;  // apply for users which is set by admin side
	                    }
	                }
				}
			}

        	$wpa_user_roles_restrict_flag = 0;
        	if ( isset($wpa_select_user_role_list) && is_array($wpa_select_user_role_list) && !empty($wpa_select_user_role_list) ) {
				if ( isset($user_data) && is_array($user_data) && !empty($user_data) ) {
					if (is_user_logged_in()) {
	                    if (in_array( $user_data[0], $wpa_select_user_role_list, true )) {
	                        $wpa_user_roles_restrict_flag = 1;  // apply for user roles which is set by admin side
	                    }
	                }
				}
			}

			if ( 'on' === $wpa_settings_enable_disable ) {
				if( 1 === $wpa_users_restrict_flag ) {
					add_post_type_support( 'product', 'author' );
				} elseif( 1 === $wpa_user_roles_restrict_flag ) {
					add_post_type_support( 'product', 'author' );
				} elseif( empty($wpa_select_users_list) && empty($wpa_select_user_role_list) ) {
					add_post_type_support( 'product', 'author' );
				}
			}
		}

		/**
	     * Add plugin settings page tabs
	     *
	     * @since    1.0.0
	     */
		public function wpa_settings_page_tabs() {
		    $tabs = array(
		        'general-settings'   => __( 'General Settings', 'advanced-product-author-for-woocommerce' )
		    );

		    $html = '<h2 class="nav-tab-wrapper">';
		    foreach( $tabs as $tab => $name ){
		        $html .= '<a class="nav-tab nav-tab-active" href="?page=wpa-woo-author-settings&tab=' . $tab . '">' . $name . '</a>';
		    }
		    $html .= '</h2>';
		    echo wp_kses_post( $html );
		}

		/**
		 * get current user details
		 *
		 * @since    1.0.0
		 *
		 */
		function wpa_get_current_user_details() {
			if( is_user_logged_in() ) {
				$user_data = wp_get_current_user();
				$current_user = $user_data->roles;
				$current_user[] = $user_data->user_login;
				return $current_user;
			} else {
				return array();
			}
		}

		/**
		 * Save product author genral settings data
		 *
		 * @since 1.0.0
		 */
		public function wpa_save_product_author_genral_settings() {
			$get_wpa_settings_enable_disable = filter_input( INPUT_POST, 'wpa_settings_enable_disable', FILTER_SANITIZE_STRING );
			$get_wpa_select_users_list		 = filter_input( INPUT_POST, 'wpa_select_users_list', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
			$get_wpa_select_user_role_list   = filter_input( INPUT_POST, 'wpa_select_user_role_list', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );

			$wpa_settings_enable_disable  	 = ! empty( $get_wpa_settings_enable_disable ) ? sanitize_text_field( wp_unslash( $get_wpa_settings_enable_disable ) ) : '';
			$wpa_select_users_list   		 = isset( $get_wpa_select_users_list ) ? array_map( 'sanitize_text_field', $get_wpa_select_users_list ) : array();
			$wpa_select_user_role_list   	 = isset( $get_wpa_select_user_role_list ) ? array_map( 'sanitize_text_field', $get_wpa_select_user_role_list ) : array();

			if ( isset( $wpa_settings_enable_disable ) && ! empty( $wpa_settings_enable_disable ) ) {
				update_option( 'wpa_settings_enable_disable', $wpa_settings_enable_disable );
			} else {
				update_option( 'wpa_settings_enable_disable', 'on' );
			}
			if ( isset( $wpa_select_users_list ) && ! empty( $wpa_select_users_list ) ) {
				update_option( 'wpa_select_users_list', $wpa_select_users_list );
			} else {
				update_option( 'wpa_select_users_list', array() );
			}
			if ( isset( $wpa_select_user_role_list ) && ! empty( $wpa_select_user_role_list ) ) {
				update_option( 'wpa_select_user_role_list', $wpa_select_user_role_list );
			} else {
				update_option( 'wpa_select_user_role_list', array() );
			}
		}
	}
}
