<?php
/**
 * REST API Routes for KSET Dynamic Shop Filters
 *
 * @package KSET_Dynamic_Shop_Filters
 * @subpackage Routes
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class KSET_Dynamic_Shop_Filters_REST_Routes
 * 
 * Handles registration of REST API endpoints for the KSET Dynamic Shop Filters plugin.
 * 
 * @since 1.0.0
 */
class KSET_Dynamic_Shop_Filters_REST_Routes {

    /**
     * API namespace
     *
     * @var string
     */
    private $namespace = 'kset/v1';

    /**
     * Initialize the REST routes
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    /**
     * Register REST API routes
     *
     * @since 1.0.0
     * @return void
     */
    public function register_routes() {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        // Register product filter endpoint
        register_rest_route(
            $this->namespace,
            '/products/filter',
            array(
                'methods'             => array( 'GET', 'POST' ),
                'callback'            => array( $this, 'filter_products' ),
                'permission_callback' => '__return_true',
                'args'                => $this->get_filter_args(),
            )
        );

        // Register attributes endpoint
        register_rest_route(
            $this->namespace,
            '/products/attributes',
            array(
                'methods'             => array( 'GET', 'POST' ),
                'callback'            => array( $this, 'get_attributes' ),
                'permission_callback' => '__return_true',
                'args'                => $this->get_attributes_args(),
            )
        );

        // Register categories endpoint
        register_rest_route(
            $this->namespace,
            '/products/categories',
            array(
                'methods'             => 'GET',
                'callback'            => array( $this, 'get_categories' ),
                'permission_callback' => '__return_true',
            )
        );
    }

    /**
     * Handle product filtering request
     *
     * @since 1.0.0
     * @param WP_REST_Request $request REST request object
     * @return WP_REST_Response|WP_Error REST response or error
     */
    public function filter_products( $request ) {
        try {
            // Load the filter controller
            require_once KSET_DSF_PLUGIN_PATH . 'includes/controllers/FilterController.php';
            
            $controller = new KSET_Dynamic_Shop_Filters_Filter_Controller();
            return $controller->filter_products( $request );
            
        } catch ( Exception $e ) {
            return new WP_Error(
                'kset_filter_error',
                __( 'Error processing filter request: ', 'kset-dynamic-shop-filters' ) . $e->getMessage(),
                array( 'status' => 500 )
            );
        }
    }

    /**
     * Handle attributes request
     *
     * @since 1.0.0
     * @param WP_REST_Request $request REST request object
     * @return WP_REST_Response|WP_Error REST response or error
     */
    public function get_attributes( $request ) {
        try {
            // Load the filter controller
            require_once KSET_DSF_PLUGIN_PATH . 'includes/controllers/FilterController.php';
            
            $controller = new KSET_Dynamic_Shop_Filters_Filter_Controller();
            return $controller->get_attributes( $request );
            
        } catch ( Exception $e ) {
            return new WP_Error(
                'kset_attributes_error',
                __( 'Error getting attributes: ', 'kset-dynamic-shop-filters' ) . $e->getMessage(),
                array( 'status' => 500 )
            );
        }
    }

    /**
     * Handle categories request
     *
     * @since 1.0.0
     * @param WP_REST_Request $request REST request object
     * @return WP_REST_Response|WP_Error REST response or error
     */
    public function get_categories( $request ) {
        try {
            // Load the filter controller
            require_once KSET_DSF_PLUGIN_PATH . 'includes/controllers/FilterController.php';
            
            $controller = new KSET_Dynamic_Shop_Filters_Filter_Controller();
            return $controller->get_categories( $request );
            
        } catch ( Exception $e ) {
            return new WP_Error(
                'kset_categories_error',
                __( 'Error getting categories: ', 'kset-dynamic-shop-filters' ) . $e->getMessage(),
                array( 'status' => 500 )
            );
        }
    }

    /**
     * Get filter endpoint arguments
     *
     * @since 1.0.0
     * @return array Endpoint arguments
     */
    private function get_filter_args() {
        return array(
            'category' => array(
                'description'       => __( 'Product category/range to filter by', 'kset-dynamic-shop-filters' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'system_of_measurement' => array(
                'description'       => __( 'System of measurement (Metric/Imperial)', 'kset-dynamic-shop-filters' ),
                'type'              => 'string',
                'enum'              => array( 'Metric', 'Imperial' ),
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'inside_diameter' => array(
                'description'       => __( 'Inside diameter value', 'kset-dynamic-shop-filters' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'outside_diameter' => array(
                'description'       => __( 'Outside diameter value', 'kset-dynamic-shop-filters' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'length' => array(
                'description'       => __( 'Length value', 'kset-dynamic-shop-filters' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'width' => array(
                'description'       => __( 'Width value', 'kset-dynamic-shop-filters' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'type' => array(
                'description'       => __( 'Product type', 'kset-dynamic-shop-filters' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'page' => array(
                'description'       => __( 'Page number for pagination', 'kset-dynamic-shop-filters' ),
                'type'              => 'integer',
                'default'           => 1,
                'minimum'           => 1,
                'sanitize_callback' => 'absint',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'per_page' => array(
                'description'       => __( 'Number of products per page', 'kset-dynamic-shop-filters' ),
                'type'              => 'integer',
                'default'           => 12,
                'minimum'           => 1,
                'maximum'           => 100,
                'sanitize_callback' => 'absint',
                'validate_callback' => 'rest_validate_request_arg',
            ),
        );
    }

    /**
     * Get attributes endpoint arguments
     *
     * @since 1.0.0
     * @return array Endpoint arguments
     */
    private function get_attributes_args() {
        return array(
            'category' => array(
                'description'       => __( 'Product category/range to get attributes for', 'kset-dynamic-shop-filters' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'system_of_measurement' => array(
                'description'       => __( 'System of measurement to filter attributes', 'kset-dynamic-shop-filters' ),
                'type'              => 'string',
                'enum'              => array( 'Metric', 'Imperial' ),
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'inside_diameter' => array(
                'description'       => __( 'Inside diameter to filter subsequent attributes', 'kset-dynamic-shop-filters' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'outside_diameter' => array(
                'description'       => __( 'Outside diameter to filter subsequent attributes', 'kset-dynamic-shop-filters' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
            'attribute' => array(
                'description'       => __( 'Specific attribute to get values for', 'kset-dynamic-shop-filters' ),
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'rest_validate_request_arg',
            ),
        );
    }
}

// Initialize the REST routes
new KSET_Dynamic_Shop_Filters_REST_Routes();
