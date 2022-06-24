<?php
    /**
     * Plugin Name: Variation Swatches for WooCommerce
     * Plugin URI: https://wordpress.org/plugins/woo-variation-swatches/
     * Description: Beautiful colors, images and buttons variation swatches for woocommerce product attributes. Requires WooCommerce 5.6+
     * Author: Emran Ahmed
     * Version: 2.0.2
     * Domain Path: /languages
     * Requires PHP: 7.0
     * Requires at least: 5.6
     * WC requires at least: 5.6
     * Tested up to: 6.0
     * WC tested up to: 6.6
     * Text Domain: woo-variation-swatches
     * Author URI: https://getwooplugins.com/
     */
    
    
    defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    
    if ( ! defined( 'WOO_VARIATION_SWATCHES_PLUGIN_VERSION' ) ) {
        define( 'WOO_VARIATION_SWATCHES_PLUGIN_VERSION', '2.0.2' );
    }
    
    if ( ! defined( 'WOO_VARIATION_SWATCHES_PLUGIN_FILE' ) ) {
        define( 'WOO_VARIATION_SWATCHES_PLUGIN_FILE', __FILE__ );
    }
    
    // Include the main class.
    if ( ! class_exists( 'Woo_Variation_Swatches', false ) ) {
        require_once dirname( __FILE__ ) . '/includes/class-woo-variation-swatches.php';
    }
    
    // Require woocommerce admin message
    function woo_variation_swatches_wc_requirement_notice() {
        
        if ( ! class_exists( 'WooCommerce' ) ) {
            $text    = esc_html__( 'WooCommerce', 'woo-variation-swatches' );
            $link = esc_url( add_query_arg(                                                                                                                                                                                                                                  array(
                                                                                                                                                                                                                                                                              'tab'       => 'plugin-information',
                                                                                                                                                                                                                                                                              'plugin' => 'woocommerce',
                                                                                                                                                                                                                                                                              'TB_iframe' => 'true',
                                                                                                                                                                                                                                                                              'width'     => '640',
                                                                                                                                                                                                                                                                              'height'    => '500',
                                                                                                                                                                                                                                                                          ), admin_url( 'plugin-install.php' ) ) );
            $message = wp_kses( __( "<strong>Variation Swatches for WooCommerce</strong> is an add-on of ", 'woo-variation-swatches' ), array( 'strong' => array() ) );
            
            printf( '<div class="%1$s"><p>%2$s <a class="thickbox open-plugin-details-modal" href="%3$s"><strong>%4$s</strong></a></p></div>', 'notice notice-error', $message, $link, $text );
        }
    }
    
    add_action( 'admin_notices', 'woo_variation_swatches_wc_requirement_notice' );
    
    /**
     * Returns the main instance.
     */
    
    function woo_variation_swatches() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
        
        if ( ! class_exists( 'WooCommerce', false ) ) {
            return false;
        }
        
        if ( function_exists( 'woo_variation_swatches_pro' ) ) {
            return woo_variation_swatches_pro();
        }
        
        return Woo_Variation_Swatches::instance();
    }
    
    add_action( 'plugins_loaded', 'woo_variation_swatches' );
    
    // Prevent activating pro old version
    function deactivate_woo_variation_swatches_pro() {
        
        if ( defined( 'WOO_VARIATION_SWATCHES_PRO_PLUGIN_FILE' ) ) {
            return;
        }
        
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        if ( is_plugin_active( 'woo-variation-swatches-pro/woo-variation-swatches-pro.php' ) ) {
            // Deactivate the plugin silently, Prevent deactivation hooks from running.
            deactivate_plugins( 'woo-variation-swatches-pro/woo-variation-swatches-pro.php', true );
        }
    }
    
    function prevent_active_woo_variation_swatches_pro() {
        
        if ( defined( 'WOO_VARIATION_SWATCHES_PRO_PLUGIN_FILE' ) ) {
            return;
        }
        
        echo 'You are running older version of "Variation Swatches for WooCommerce - Pro". Please upgrade to 2.0.0 or upper and continue.';
        exit();
    }
    
    add_action( 'plugins_loaded', 'deactivate_woo_variation_swatches_pro', 9 );
    add_action( 'activate_woo-variation-swatches-pro/woo-variation-swatches-pro.php', 'prevent_active_woo_variation_swatches_pro' );