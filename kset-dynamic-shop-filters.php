<?php
/**
 * Plugin Name: KSET Dynamic Shop Filters
 * Plugin URI: https://github.com/yourusername/kset-dynamic-shop-filters
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
        KSET_Dynamic_Shop_Filters_Init::get_instance();
    }
}

// Hook into WordPress
add_action( 'plugins_loaded', 'kset_dsf_init' );

/**
 * Add plugin action links
 */
function kset_dsf_plugin_action_links( $links ) {
    $plugin_links = array(
        '<a href="' . admin_url( 'admin.php?page=kset-dsf-details' ) . '" style="color: #2271b1; font-weight: 600;">' . __( 'View Details', 'kset-dynamic-shop-filters' ) . '</a>',
        '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=kset_dsf' ) . '">' . __( 'Settings', 'kset-dynamic-shop-filters' ) . '</a>',
    );
    return array_merge( $plugin_links, $links );
}
add_filter( 'plugin_action_links_' . KSET_DSF_PLUGIN_BASENAME, 'kset_dsf_plugin_action_links' );

/**
 * Add plugin details page to admin menu
 */
function kset_dsf_add_admin_menu() {
    add_submenu_page(
        null, // No parent menu (hidden)
        __( 'KSET Dynamic Shop Filters - Details', 'kset-dynamic-shop-filters' ),
        __( 'Plugin Details', 'kset-dynamic-shop-filters' ),
        'manage_options',
        'kset-dsf-details',
        'kset_dsf_details_page'
    );
}
add_action( 'admin_menu', 'kset_dsf_add_admin_menu' );

/**
 * Plugin details page content
 */
function kset_dsf_details_page() {
    ?>
    <div class="wrap">
        <style>
            .kset-details-container {
                max-width: 1200px;
                margin: 20px 0;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .kset-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 40px;
                text-align: center;
            }
            .kset-header h1 {
                margin: 0 0 10px 0;
                font-size: 2.5em;
                font-weight: 300;
            }
            .kset-header p {
                margin: 0;
                font-size: 1.2em;
                opacity: 0.9;
            }
            .kset-version {
                background: rgba(255,255,255,0.2);
                padding: 5px 15px;
                border-radius: 20px;
                display: inline-block;
                margin-top: 15px;
                font-weight: 500;
            }
            .kset-content {
                padding: 40px;
            }
            .kset-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
                margin-bottom: 30px;
            }
            .kset-card {
                background: #f8f9fa;
                padding: 25px;
                border-radius: 8px;
                border-left: 4px solid #667eea;
            }
            .kset-card h3 {
                margin: 0 0 15px 0;
                color: #333;
                font-size: 1.3em;
            }
            .kset-card ul {
                margin: 0;
                padding-left: 20px;
            }
            .kset-card li {
                margin-bottom: 8px;
                color: #666;
            }
            .kset-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
                margin-top: 30px;
            }
            .kset-stat {
                text-align: center;
                padding: 20px;
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                color: white;
                border-radius: 8px;
            }
            .kset-stat-number {
                font-size: 2em;
                font-weight: bold;
                display: block;
            }
            .kset-stat-label {
                opacity: 0.9;
                margin-top: 5px;
            }
            .kset-button {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                padding: 12px 25px;
                border-radius: 6px;
                text-decoration: none;
                display: inline-block;
                margin: 10px 10px 10px 0;
                font-weight: 500;
                transition: transform 0.2s;
            }
            .kset-button:hover {
                transform: translateY(-2px);
                color: white;
                text-decoration: none;
            }
            .kset-button.secondary {
                background: #6c757d;
            }
            @media (max-width: 768px) {
                .kset-grid {
                    grid-template-columns: 1fr;
                }
                .kset-header {
                    padding: 30px 20px;
                }
                .kset-content {
                    padding: 30px 20px;
                }
            }
        </style>

        <div class="kset-details-container">
            <div class="kset-header">
                <h1><?php echo esc_html__( 'KSET Dynamic Shop Filters', 'kset-dynamic-shop-filters' ); ?></h1>
                <p><?php echo esc_html__( 'Advanced WooCommerce filtering with React frontend', 'kset-dynamic-shop-filters' ); ?></p>
                <span class="kset-version"><?php echo esc_html__( 'Version', 'kset-dynamic-shop-filters' ); ?> <?php echo KSET_DSF_VERSION; ?></span>
            </div>

            <div class="kset-content">
                <div class="kset-grid">
                    <div class="kset-card">
                        <h3><?php echo esc_html__( '✨ Features', 'kset-dynamic-shop-filters' ); ?></h3>
                        <ul>
                            <li><?php echo esc_html__( 'Real-time AJAX filtering', 'kset-dynamic-shop-filters' ); ?></li>
                            <li><?php echo esc_html__( 'React-based modern UI', 'kset-dynamic-shop-filters' ); ?></li>
                            <li><?php echo esc_html__( 'Mobile responsive design', 'kset-dynamic-shop-filters' ); ?></li>
                            <li><?php echo esc_html__( 'Multiple filter types', 'kset-dynamic-shop-filters' ); ?></li>
                            <li><?php echo esc_html__( 'High performance', 'kset-dynamic-shop-filters' ); ?></li>
                        </ul>
                    </div>

                    <div class="kset-card">
                        <h3><?php echo esc_html__( '🛠️ Technical Info', 'kset-dynamic-shop-filters' ); ?></h3>
                        <ul>
                            <li><strong>WordPress:</strong> <?php echo get_bloginfo( 'version' ); ?></li>
                            <li><strong>PHP:</strong> <?php echo PHP_VERSION; ?></li>
                            <li><strong>WooCommerce:</strong> <?php echo class_exists( 'WooCommerce' ) ? WC()->version : __( 'Not installed', 'kset-dynamic-shop-filters' ); ?></li>
                            <li><strong>Plugin Version:</strong> <?php echo KSET_DSF_VERSION; ?></li>
                            <li><strong>Text Domain:</strong> kset-dynamic-shop-filters</li>
                        </ul>
                    </div>
                </div>

                <div class="kset-stats">
                    <div class="kset-stat">
                        <span class="kset-stat-number"><?php echo wp_count_posts( 'product' )->publish; ?></span>
                        <div class="kset-stat-label"><?php echo esc_html__( 'Products', 'kset-dynamic-shop-filters' ); ?></div>
                    </div>
                    <div class="kset-stat">
                        <span class="kset-stat-number"><?php echo wp_count_terms( 'product_cat' ); ?></span>
                        <div class="kset-stat-label"><?php echo esc_html__( 'Categories', 'kset-dynamic-shop-filters' ); ?></div>
                    </div>
                    <div class="kset-stat">
                        <span class="kset-stat-number"><?php echo count( wc_get_attribute_taxonomies() ); ?></span>
                        <div class="kset-stat-label"><?php echo esc_html__( 'Attributes', 'kset-dynamic-shop-filters' ); ?></div>
                    </div>
                </div>

                <div style="margin-top: 40px; text-align: center;">
                    <a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=kset_dsf' ); ?>" class="kset-button">
                        <?php echo esc_html__( '⚙️ Plugin Settings', 'kset-dynamic-shop-filters' ); ?>
                    </a>
                    <a href="https://github.com/juanjcgo/KSET-Dynamic-Shop-Filters" class="kset-button secondary" target="_blank">
                        <?php echo esc_html__( '📚 Documentation', 'kset-dynamic-shop-filters' ); ?>
                    </a>
                    <a href="https://werock.space" class="kset-button secondary" target="_blank">
                        <?php echo esc_html__( '🌐 Author Website', 'kset-dynamic-shop-filters' ); ?>
                    </a>
                </div>

                <div style="margin-top: 30px; padding: 20px; background: #e7f3ff; border-radius: 8px; text-align: center;">
                    <h4 style="margin: 0 0 10px 0; color: #0073aa;">
                        <?php echo esc_html__( '🚀 Ready to enhance your shop?', 'kset-dynamic-shop-filters' ); ?>
                    </h4>
                    <p style="margin: 0; color: #666;">
                        <?php echo esc_html__( 'Configure your filters and provide an amazing shopping experience to your customers.', 'kset-dynamic-shop-filters' ); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php
}

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