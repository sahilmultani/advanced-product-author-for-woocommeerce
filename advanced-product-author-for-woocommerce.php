<?php
/**
 * Plugin Name: Advanced Product Author For WooCommeerce
 * Plugin URI: https://profiles.wordpress.org/sahilmultani/
 * Description: Advanced Product Author for WooCommerce is a plugin that allows the admin to change the authorship of WooCommerce products.
 * Version: 1.0.0
 * Author: Sahil Multani
 * Author URI: https://github.com/sahilmultani
 * Text Domain: advanced-product-author-for-woocommerce
 * Domain Path: /languages/
 */
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    die;
}

if ( !defined( 'WPA_PLUGIN_VERSION' ) ) {
    define( 'WPA_PLUGIN_VERSION', '1.0.0' );
}
if ( !defined( 'WPA_PLUGIN_URL' ) ) {
    define( 'WPA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'WPA_PLUGIN_BASENAME' ) ) {
    define( 'WPA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( !defined( 'WPA_PLUGIN_NAME' ) ) {
    define( 'WPA_PLUGIN_NAME', 'Advanced Product Author for WooCommerce' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-product-author-for-woocommerce-activator.php
 */
if ( !function_exists( 'activate_woo_product_author' ) ) {
    function activate_woo_product_author()
    {
        require plugin_dir_path( __FILE__ ) . 'includes/class-product-author-for-woocommerce-activator.php';
        WPA_Woo_Product_Author_Activator::activate();
    }

}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-product-author-for-woocommerce-deactivator.php
 */
if ( !function_exists( 'deactivate_woo_product_author' ) ) {
    function deactivate_woo_product_author()
    {
        require plugin_dir_path( __FILE__ ) . 'includes/class-product-author-for-woocommerce-deactivator.php';
        WPA_Woo_Product_Author_Deactivator::deactivate();
    }

}
register_activation_hook( __FILE__, 'activate_woo_product_author' );
register_deactivation_hook( __FILE__, 'deactivate_woo_product_author' );

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) || function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-product-author-for-woocommerce.php';
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    if ( !function_exists( 'run_wpa_woo_product_author' ) ) {
        function run_wpa_woo_product_author()
        {
            $plugin = new WPA_Woo_Product_Author();
            $plugin->run();
        }
    
    }
}

/**
 * Check Initialize plugin in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
if ( !function_exists( 'wpa_initialize_plugin' ) ) {
    function wpa_initialize_plugin()
    {
        
        if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) && (!function_exists( 'is_plugin_active_for_network' ) || !is_plugin_active_for_network( 'woocommerce/woocommerce.php' )) ) {
            add_action( 'admin_notices', 'wpa_plugin_admin_notice' );
        } else {
            run_wpa_woo_product_author();
        }
        
        // Load the plugin text domain for translation.
        load_plugin_textdomain( 'advanced-product-author-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

}
add_action( 'plugins_loaded', 'wpa_initialize_plugin' );

/**
 * Show admin notice in case of WooCommerce plugin is missing.
 *
 * @since    1.0.0
 */
if ( !function_exists( 'wpa_plugin_admin_notice' ) ) {
    function wpa_plugin_admin_notice()
    {
        $wpa_plugin_name = esc_html__( 'Advanced Product Author for WooCommerce', 'advanced-product-author-for-woocommerce' );
        $wc_plugin = esc_html__( 'WooCommerce', 'advanced-product-author-for-woocommerce' );
        ?>
        <div class="error">
            <p>
                <?php 
        echo  sprintf( esc_html__( '%1$s requires %2$s to be installed & activated!', 'advanced-product-author-for-woocommerce' ), '<strong>' . esc_html( $wpa_plugin_name ) . '</strong>', '<a href="' . esc_url( 'https://wordpress.org/plugins/woocommerce/' ) . '" target="_blank"><strong>' . esc_html( $wc_plugin ) . '</strong></a>' ) ;
        ?>
            </p>
        </div>
        <?php 
    }

}