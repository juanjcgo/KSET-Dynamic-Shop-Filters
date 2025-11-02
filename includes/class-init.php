<?php
/**
 * Main initialization class for KSET Dynamic Shop Filters
 *
 * @package KSET_Dynamic_Shop_Filters
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main Plugin Class
 */
class KSET_Dynamic_Shop_Filters_Init {

    /**
     * The single instance of the class.
     *
     * @var KSET_Dynamic_Shop_Filters_Init
     */
    protected static $_instance = null;

    /**
     * Main Instance.
     *
     * @return KSET_Dynamic_Shop_Filters_Init - Main instance.
     */
    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters.
     */
    private function init_hooks() {
        add_action( 'init', array( $this, 'init' ), 0 );
    }

    /**
     * Init plugin when WordPress Initialises.
     */
    public function init() {
        // Set up localisation
        $this->load_plugin_textdomain();
        
        // Initialize components
        $this->init_components();
    }

    /**
     * Load Localisation files.
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( 'kset-dynamic-shop-filters', false, plugin_basename( dirname( KSET_DSF_PLUGIN_PATH ) ) . '/languages' );
    }

    /**
     * Initialize plugin components.
     */
    private function init_components() {
        // Load assets management
        $this->load_assets();
        
        // Load shortcodes
        $this->load_shortcodes();
        
        // Load REST API routes
        $this->load_rest_routes();
        
        // Here we can load controllers, models, etc.
        // For now, just a placeholder
        do_action( 'kset_dsf_init_components' );
    }

    /**
     * Load assets management class.
     */
    private function load_assets() {
        $assets_file = KSET_DSF_PLUGIN_PATH . 'includes/class-assets.php';
        if ( file_exists( $assets_file ) ) {
            require_once $assets_file;
            if ( class_exists( 'KSET_Dynamic_Shop_Filters_Assets' ) ) {
                new KSET_Dynamic_Shop_Filters_Assets();
            }
        }
    }

    /**
     * Load shortcodes class.
     */
    private function load_shortcodes() {
        $shortcodes_file = KSET_DSF_PLUGIN_PATH . 'includes/class-shortcodes.php';
        if ( file_exists( $shortcodes_file ) ) {
            require_once $shortcodes_file;
            if ( class_exists( 'KSET_Dynamic_Shop_Filters_Shortcodes' ) ) {
                KSET_Dynamic_Shop_Filters_Shortcodes::get_instance();
            }
        }
    }

    /**
     * Load REST API routes.
     */
    private function load_rest_routes() {
        $rest_routes_file = KSET_DSF_PLUGIN_PATH . 'includes/routes/rest-filters.php';
        if ( file_exists( $rest_routes_file ) ) {
            require_once $rest_routes_file;
        }
    }

    /**
     * Get the plugin path.
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( KSET_DSF_PLUGIN_PATH ) );
    }

    /**
     * Get the plugin URL.
     */
    public function plugin_url() {
        return untrailingslashit( plugin_dir_url( KSET_DSF_PLUGIN_PATH ) );
    }
}