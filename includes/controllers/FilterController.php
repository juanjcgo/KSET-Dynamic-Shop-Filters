<?php
/**
 * Filter Controller for KSET Dynamic Shop Filters
 *
 * @package KSET_Dynamic_Shop_Filters
 * @subpackage Controllers
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class KSET_Dynamic_Shop_Filters_Filter_Controller
 * 
 * Handles REST API requests and business logic for the KSET Dynamic Shop Filters plugin.
 * 
 * @since 1.0.0
 */
class KSET_Dynamic_Shop_Filters_Filter_Controller {

    /**
     * Product model instance
     *
     * @var KSET_Dynamic_Shop_Filters_Product_Model
     */
    private $product_model;

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct() {
        // Load the product model
        require_once KSET_DSF_PLUGIN_PATH . 'includes/models/ProductModel.php';
        $this->product_model = new KSET_Dynamic_Shop_Filters_Product_Model();
    }

    /**
     * Handle product filtering request
     *
     * @since 1.0.0
     * @param WP_REST_Request $request REST request object
     * @return WP_REST_Response REST response
     */
    public function filter_products( $request ) {
        try {
            // Validate WooCommerce
            if ( ! $this->is_woocommerce_active() ) {
                return $this->error_response( 
                    'woocommerce_inactive', 
                    __( 'WooCommerce is not active', 'kset-dynamic-shop-filters' ),
                    503
                );
            }

            // Get and sanitize parameters
            $filters = $this->sanitize_filters( $request );
            $page = max( 1, (int) $request->get_param( 'page' ) ?: 1 );
            $per_page = max( 1, min( 100, (int) $request->get_param( 'per_page' ) ?: 12 ) );

            // Validate filters
            $validation_error = $this->validate_filters( $filters );
            if ( is_wp_error( $validation_error ) ) {
                return $this->error_response( 
                    $validation_error->get_error_code(),
                    $validation_error->get_error_message(),
                    400
                );
            }

            // Get filtered products
            $result = $this->product_model->get_filtered_products( $filters, $page, $per_page );

            // Build response
            $response_data = array(
                'success' => true,
                'filters' => $this->format_applied_filters( $filters ),
                'results' => $result['products'],
                'pagination' => array(
                    'page'      => $result['page'],
                    'per_page'  => $result['per_page'],
                    'total'     => $result['total'],
                    'pages'     => $result['pages'],
                ),
                'timestamp' => current_time( 'mysql' ),
            );

            return rest_ensure_response( $response_data );

        } catch ( Exception $e ) {
            return $this->error_response(
                'filter_exception',
                sprintf( 
                    __( 'Error filtering products: %s', 'kset-dynamic-shop-filters' ),
                    $e->getMessage()
                ),
                500
            );
        }
    }

    /**
     * Handle attributes request
     *
     * @since 1.0.0
     * @param WP_REST_Request $request REST request object
     * @return WP_REST_Response REST response
     */
    public function get_attributes( $request ) {
        try {
            // Validate WooCommerce
            if ( ! $this->is_woocommerce_active() ) {
                return $this->error_response( 
                    'woocommerce_inactive', 
                    __( 'WooCommerce is not active', 'kset-dynamic-shop-filters' ),
                    503
                );
            }

            // Get and sanitize parameters
            $filters = $this->sanitize_filters( $request );
            $attribute = sanitize_text_field( $request->get_param( 'attribute' ) ?: '' );

            // Get available attributes
            $attributes = $this->product_model->get_available_attributes( $filters, $attribute );

            // Build response
            $response_data = array(
                'success' => true,
                'filters' => $this->format_applied_filters( $filters ),
                'attributes' => $attributes,
                'timestamp' => current_time( 'mysql' ),
            );

            return rest_ensure_response( $response_data );

        } catch ( Exception $e ) {
            return $this->error_response(
                'attributes_exception',
                sprintf( 
                    __( 'Error getting attributes: %s', 'kset-dynamic-shop-filters' ),
                    $e->getMessage()
                ),
                500
            );
        }
    }

    /**
     * Handle categories request
     *
     * @since 1.0.0
     * @param WP_REST_Request $request REST request object
     * @return WP_REST_Response REST response
     */
    public function get_categories( $request ) {
        try {
            // Validate WooCommerce
            if ( ! $this->is_woocommerce_active() ) {
                return $this->error_response( 
                    'woocommerce_inactive', 
                    __( 'WooCommerce is not active', 'kset-dynamic-shop-filters' ),
                    503
                );
            }

            // Get categories
            $categories = $this->product_model->get_product_categories();

            // Build response
            $response_data = array(
                'success' => true,
                'categories' => $categories,
                'timestamp' => current_time( 'mysql' ),
            );

            return rest_ensure_response( $response_data );

        } catch ( Exception $e ) {
            return $this->error_response(
                'categories_exception',
                sprintf( 
                    __( 'Error getting categories: %s', 'kset-dynamic-shop-filters' ),
                    $e->getMessage()
                ),
                500
            );
        }
    }

    /**
     * Sanitize filter parameters
     *
     * @since 1.0.0
     * @param WP_REST_Request $request REST request object
     * @return array Sanitized filters
     */
    private function sanitize_filters( $request ) {
        $filters = array();

        // List of filter parameters to sanitize
        $filter_params = array(
            'category',
            'system_of_measurement',
            'inside_diameter',
            'outside_diameter',
            'length',
            'width',
            'type',
        );

        foreach ( $filter_params as $param ) {
            $value = $request->get_param( $param );
            if ( ! empty( $value ) ) {
                $filters[ $param ] = sanitize_text_field( $value );
            }
        }

        return $filters;
    }

    /**
     * Validate filter parameters
     *
     * @since 1.0.0
     * @param array $filters Filter parameters
     * @return true|WP_Error True if valid, WP_Error if invalid
     */
    private function validate_filters( $filters ) {
        // Validate system of measurement
        if ( ! empty( $filters['system_of_measurement'] ) ) {
            $valid_systems = array( 'Metric', 'Imperial' );
            if ( ! in_array( $filters['system_of_measurement'], $valid_systems, true ) ) {
                return new WP_Error(
                    'invalid_measurement_system',
                    __( 'Invalid system of measurement. Must be Metric or Imperial.', 'kset-dynamic-shop-filters' )
                );
            }
        }

        // Validate numeric values for dimensions
        $numeric_fields = array( 'inside_diameter', 'outside_diameter', 'length', 'width' );
        foreach ( $numeric_fields as $field ) {
            if ( ! empty( $filters[ $field ] ) ) {
                // Extract numeric value (remove units like 'mm', 'in', etc.)
                $numeric_value = preg_replace( '/[^0-9.]/', '', $filters[ $field ] );
                if ( ! is_numeric( $numeric_value ) || $numeric_value < 0 ) {
                    return new WP_Error(
                        'invalid_dimension',
                        sprintf( 
                            __( 'Invalid %s value. Must be a positive number.', 'kset-dynamic-shop-filters' ),
                            str_replace( '_', ' ', $field )
                        )
                    );
                }
            }
        }

        return true;
    }

    /**
     * Format applied filters for response
     *
     * @since 1.0.0
     * @param array $filters Applied filters
     * @return array Formatted filters
     */
    private function format_applied_filters( $filters ) {
        $formatted = array(
            'category'              => $filters['category'] ?? null,
            'system_of_measurement' => $filters['system_of_measurement'] ?? null,
            'inside_diameter'       => $filters['inside_diameter'] ?? null,
            'outside_diameter'      => $filters['outside_diameter'] ?? null,
            'length'                => $filters['length'] ?? null,
            'width'                 => $filters['width'] ?? null,
            'type'                  => $filters['type'] ?? null,
        );

        return $formatted;
    }

    /**
     * Check if WooCommerce is active
     *
     * @since 1.0.0
     * @return bool True if WooCommerce is active
     */
    private function is_woocommerce_active() {
        return class_exists( 'WooCommerce' );
    }

    /**
     * Generate error response
     *
     * @since 1.0.0
     * @param string $code Error code
     * @param string $message Error message
     * @param int    $status HTTP status code
     * @return WP_REST_Response Error response
     */
    private function error_response( $code, $message, $status = 400 ) {
        $error_data = array(
            'success'   => false,
            'error'     => array(
                'code'    => $code,
                'message' => $message,
            ),
            'timestamp' => current_time( 'mysql' ),
        );

        return new WP_REST_Response( $error_data, $status );
    }

    /**
     * Log debug information (for development)
     *
     * @since 1.0.0
     * @param mixed  $data Data to log
     * @param string $context Log context
     * @return void
     */
    private function debug_log( $data, $context = 'KSET_DSF' ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
            error_log( sprintf( '[%s] %s: %s', $context, current_time( 'mysql' ), print_r( $data, true ) ) );
        }
    }

    /**
     * Get filter hierarchy based on category
     *
     * @since 1.0.0
     * @param string $category Product category
     * @return array Filter hierarchy for the category
     */
    private function get_filter_hierarchy( $category ) {
        $hierarchies = array(
            'bushings' => array(
                'system_of_measurement',
                'inside_diameter',
                'outside_diameter',
                'length',
                'type',
            ),
            'seals' => array(
                'system_of_measurement',
                'inside_diameter',
                'outside_diameter',
                'width',
                'type',
            ),
            'shims' => array(
                'system_of_measurement',
                'inside_diameter',
                'outside_diameter',
                'width',
                'type',
            ),
        );

        $category_slug = sanitize_title( strtolower( $category ) );
        
        return $hierarchies[ $category_slug ] ?? array(
            'system_of_measurement',
            'inside_diameter',
            'outside_diameter',
            'length',
            'width',
            'type',
        );
    }

    /**
     * Check if a filter level should be shown based on hierarchy
     *
     * @since 1.0.0
     * @param string $filter_name Filter name to check
     * @param array  $applied_filters Currently applied filters
     * @param string $category Product category
     * @return bool True if filter should be shown
     */
    private function should_show_filter( $filter_name, $applied_filters, $category ) {
        $hierarchy = $this->get_filter_hierarchy( $category );
        $filter_index = array_search( $filter_name, $hierarchy, true );
        
        if ( $filter_index === false ) {
            return false;
        }

        // Check if all previous filters in hierarchy are applied
        for ( $i = 0; $i < $filter_index; $i++ ) {
            $required_filter = $hierarchy[ $i ];
            if ( empty( $applied_filters[ $required_filter ] ) ) {
                return false;
            }
        }

        return true;
    }
}
