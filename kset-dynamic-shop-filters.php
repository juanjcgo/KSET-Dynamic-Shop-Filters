<?php
/**
 * Plugin Name: KSET Dynamic Shop Filters
 * Plugin URI: https://github.com/juanjcgo/KSET-Dynamic-Shop-Filters.git
 * Description: A dynamic filtering system for WooCommerce shops with real-time AJAX filtering by categories, attributes, price range, and more. Features a modern React-based frontend interface with smooth animations and responsive design.
 * Version: 1.0.0
 * Author: Juan Carlos Garcia
 * Author URI: https://werock.space
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: kset-dynamic-shop-filters
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.7
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 9.3
 * Network: false
 * 
 * @package KSET_Dynamic_Shop_Filters
 * @category Plugin
 * @author Your Name
 * @version 1.0.0
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2025 Your Name
*/

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'KSET_DSF_VERSION', '1.0.0' );
define( 'KSET_DSF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'KSET_DSF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'KSET_DSF_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'KSET_DSF_TEXT_DOMAIN', 'kset-dynamic-shop-filters' );

/**
 * Check if WooCommerce is active
 */
function kset_dsf_check_woocommerce() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', 'kset_dsf_woocommerce_missing_notice' );
        return false;
    }
    return true;
}

/**
 * Display admin notice if WooCommerce is not active
 */
function kset_dsf_woocommerce_missing_notice() {
    $class = 'notice notice-error';
    $message = __( 'KSET Dynamic Shop Filters requires WooCommerce to be installed and active.', 'kset-dynamic-shop-filters' );
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

/**
 * Initialize the plugin
 */
function kset_dsf_init() {
    // Check if WooCommerce is active
    if ( ! kset_dsf_check_woocommerce() ) {
        return;
    }
    
    // Load plugin text domain
    load_plugin_textdomain( 'kset-dynamic-shop-filters', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    
    // Initialize main plugin class
    if ( file_exists( KSET_DSF_PLUGIN_PATH . 'includes/class-init.php' ) ) {
        require_once KSET_DSF_PLUGIN_PATH . 'includes/class-init.php';
        if ( class_exists( 'KSET_Dynamic_Shop_Filters_Init' ) ) {
            KSET_Dynamic_Shop_Filters_Init::get_instance();
        }
    }
}

// Hook into WordPress
add_action( 'plugins_loaded', 'kset_dsf_init' );

/**
 * Plugin activation hook
 */
function kset_dsf_activate() {
    // Check WordPress version
    if ( version_compare( get_bloginfo( 'version' ), '5.0', '<' ) ) {
        wp_die( __( 'KSET Dynamic Shop Filters requires WordPress 5.0 or higher.', 'kset-dynamic-shop-filters' ) );
    }
    
    // Check PHP version
    if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
        wp_die( __( 'KSET Dynamic Shop Filters requires PHP 7.4 or higher.', 'kset-dynamic-shop-filters' ) );
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'kset_dsf_activate' );

/**
 * Plugin deactivation hook
 */
function kset_dsf_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'kset_dsf_deactivate' );

/**
 * Plugin uninstall hook
 */
function kset_dsf_uninstall() {
    // Clean up options, database tables, etc.
    // This will be implemented in the database schema class
}
register_uninstall_hook( __FILE__, 'kset_dsf_uninstall' );