<?php
/**
 * Product Model for KSET Dynamic Shop Filters
 *
 * @package KSET_Dynamic_Shop_Filters
 * @subpackage Models
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class KSET_Dynamic_Shop_Filters_Product_Model
 * 
 * Handles product queries and data retrieval for the KSET Dynamic Shop Filters plugin.
 * 
 * @since 1.0.0
 */
class KSET_Dynamic_Shop_Filters_Product_Model {

    /**
     * Get filtered products based on criteria
     *
     * @since 1.0.0
     * @param array $filters Array of filter criteria
     * @param int   $page Current page number
     * @param int   $per_page Number of products per page
     * @return array Array containing products and pagination info
     */
    public function get_filtered_products( $filters = array(), $page = 1, $per_page = 12 ) {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return array(
                'products' => array(),
                'total'    => 0,
                'pages'    => 0,
            );
        }

        // Calculate offset
        $offset = ( $page - 1 ) * $per_page;

        // Build WooCommerce product query arguments
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'offset'         => $offset,
            'meta_query'     => array(),
            'tax_query'      => array(),
        );

        // Add category filter
        if ( ! empty( $filters['category'] ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => sanitize_title( $filters['category'] ),
            );
        }

        // Add attribute filters
        $this->add_attribute_filters( $args, $filters );

        // Execute the query
        $query = new WP_Query( $args );

        // Get total products for pagination
        $total_products = $query->found_posts;
        $total_pages    = ceil( $total_products / $per_page );

        // Process products
        $products = array();
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $product = wc_get_product( get_the_ID() );
                
                if ( $product ) {
                    $products[] = $this->format_product_data( $product );
                }
            }
            wp_reset_postdata();
        }

        return array(
            'products' => $products,
            'total'    => $total_products,
            'pages'    => $total_pages,
            'page'     => $page,
            'per_page' => $per_page,
        );
    }

    /**
     * Get available attribute values based on current filters
     *
     * @since 1.0.0
     * @param array  $filters Current filters applied
     * @param string $attribute Specific attribute to get values for
     * @return array Available attribute values
     */
    public function get_available_attributes( $filters = array(), $attribute = '' ) {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return array();
        }

        $attributes = array();

        // If no specific attribute requested, get all available attributes
        if ( empty( $attribute ) ) {
            $attributes = array(
                'system_of_measurement' => $this->get_measurement_systems( $filters ),
                'inside_diameter'       => $this->get_diameter_values( $filters, 'inside_diameter' ),
                'outside_diameter'      => $this->get_diameter_values( $filters, 'outside_diameter' ),
                'length'                => $this->get_dimension_values( $filters, 'length' ),
                'width'                 => $this->get_dimension_values( $filters, 'width' ),
                'type'                  => $this->get_type_values( $filters ),
            );
        } else {
            // Get specific attribute values
            switch ( $attribute ) {
                case 'system_of_measurement':
                    $attributes = $this->get_measurement_systems( $filters );
                    break;
                case 'inside_diameter':
                    $attributes = $this->get_diameter_values( $filters, 'inside_diameter' );
                    break;
                case 'outside_diameter':
                    $attributes = $this->get_diameter_values( $filters, 'outside_diameter' );
                    break;
                case 'length':
                    $attributes = $this->get_dimension_values( $filters, 'length' );
                    break;
                case 'width':
                    $attributes = $this->get_dimension_values( $filters, 'width' );
                    break;
                case 'type':
                    $attributes = $this->get_type_values( $filters );
                    break;
            }
        }

        return $attributes;
    }

    /**
     * Get product categories
     *
     * @since 1.0.0
     * @return array Product categories
     */
    public function get_product_categories() {
        $categories = get_terms( array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => 0, // Only top-level categories
        ) );

        $formatted_categories = array();
        if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
            foreach ( $categories as $category ) {
                $formatted_categories[] = array(
                    'id'    => $category->term_id,
                    'name'  => $category->name,
                    'slug'  => $category->slug,
                    'count' => $category->count,
                );
            }
        }

        return $formatted_categories;
    }

    /**
     * Add attribute filters to WP_Query arguments
     *
     * @since 1.0.0
     * @param array $args WP_Query arguments (passed by reference)
     * @param array $filters Filter criteria
     * @return void
     */
    private function add_attribute_filters( &$args, $filters ) {
        // System of Measurement (global attribute)
        if ( ! empty( $filters['system_of_measurement'] ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'pa_system-of-measurement',
                'field'    => 'slug',
                'terms'    => sanitize_title( $filters['system_of_measurement'] ),
            );
        }

        // Inside Diameter
        if ( ! empty( $filters['inside_diameter'] ) ) {
            $args['meta_query'][] = array(
                'key'     => '_inside_diameter',
                'value'   => sanitize_text_field( $filters['inside_diameter'] ),
                'compare' => '=',
            );
        }

        // Outside Diameter
        if ( ! empty( $filters['outside_diameter'] ) ) {
            $args['meta_query'][] = array(
                'key'     => '_outside_diameter',
                'value'   => sanitize_text_field( $filters['outside_diameter'] ),
                'compare' => '=',
            );
        }

        // Length
        if ( ! empty( $filters['length'] ) ) {
            $args['meta_query'][] = array(
                'key'     => '_length',
                'value'   => sanitize_text_field( $filters['length'] ),
                'compare' => '=',
            );
        }

        // Width
        if ( ! empty( $filters['width'] ) ) {
            $args['meta_query'][] = array(
                'key'     => '_width',
                'value'   => sanitize_text_field( $filters['width'] ),
                'compare' => '=',
            );
        }

        // Type
        if ( ! empty( $filters['type'] ) ) {
            $args['meta_query'][] = array(
                'key'     => '_type',
                'value'   => sanitize_text_field( $filters['type'] ),
                'compare' => '=',
            );
        }

        // Set meta_query relation to AND if multiple meta queries exist
        if ( count( $args['meta_query'] ) > 1 ) {
            $args['meta_query']['relation'] = 'AND';
        }

        // Set tax_query relation to AND if multiple tax queries exist
        if ( count( $args['tax_query'] ) > 1 ) {
            $args['tax_query']['relation'] = 'AND';
        }
    }

    /**
     * Format product data for API response
     *
     * @since 1.0.0
     * @param WC_Product $product WooCommerce product object
     * @return array Formatted product data
     */
    private function format_product_data( $product ) {
        // Get product image
        $image_id  = $product->get_image_id();
        $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : '';

        // Get product categories
        $categories = wp_get_post_terms( $product->get_id(), 'product_cat' );
        $category_name = ! empty( $categories ) ? $categories[0]->name : '';

        // Get product attributes
        $attributes = $this->get_product_attributes( $product );

        return array(
            'id'         => $product->get_id(),
            'name'       => $product->get_name(),
            'sku'        => $product->get_sku(),
            'price'      => $product->get_price(),
            'image'      => $image_url,
            'category'   => $category_name,
            'url'        => $product->get_permalink(),
            'attributes' => $attributes,
        );
    }

    /**
     * Get product attributes
     *
     * @since 1.0.0
     * @param WC_Product $product WooCommerce product object
     * @return array Product attributes
     */
    private function get_product_attributes( $product ) {
        $attributes = array();

        // Get global attributes
        $product_attributes = $product->get_attributes();
        
        foreach ( $product_attributes as $attribute ) {
            if ( $attribute->is_taxonomy() ) {
                $taxonomy = $attribute->get_taxonomy();
                $terms = wc_get_product_terms( $product->get_id(), $taxonomy );
                
                if ( ! empty( $terms ) ) {
                    $attribute_name = wc_attribute_label( $taxonomy );
                    $attributes[ $attribute_name ] = $terms[0]->name;
                }
            }
        }

        // Get custom meta attributes
        $meta_attributes = array(
            'Inside Diameter (ID)' => get_post_meta( $product->get_id(), '_inside_diameter', true ),
            'Outside Diameter (OD)' => get_post_meta( $product->get_id(), '_outside_diameter', true ),
            'Length' => get_post_meta( $product->get_id(), '_length', true ),
            'Width' => get_post_meta( $product->get_id(), '_width', true ),
            'Type' => get_post_meta( $product->get_id(), '_type', true ),
        );

        foreach ( $meta_attributes as $name => $value ) {
            $attributes[ $name ] = ! empty( $value ) ? $value : null;
        }

        return $attributes;
    }

    /**
     * Get available measurement systems
     *
     * @since 1.0.0
     * @param array $filters Current filters
     * @return array Available measurement systems
     */
    private function get_measurement_systems( $filters ) {
        $terms = get_terms( array(
            'taxonomy'   => 'pa_system-of-measurement',
            'hide_empty' => true,
        ) );

        $systems = array();
        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                $systems[] = array(
                    'value' => $term->name,
                    'label' => $term->name,
                    'slug'  => $term->slug,
                );
            }
        }

        return $systems;
    }

    /**
     * Get available diameter values
     *
     * @since 1.0.0
     * @param array  $filters Current filters
     * @param string $diameter_type Type of diameter (inside_diameter or outside_diameter)
     * @return array Available diameter values
     */
    private function get_diameter_values( $filters, $diameter_type ) {
        global $wpdb;

        $meta_key = '_' . $diameter_type;
        $values = array();

        // Build query to get unique diameter values
        $sql = "SELECT DISTINCT meta_value 
                FROM {$wpdb->postmeta} pm
                INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                WHERE pm.meta_key = %s 
                AND p.post_type = 'product' 
                AND p.post_status = 'publish'
                AND pm.meta_value != ''
                ORDER BY CAST(pm.meta_value AS DECIMAL(10,2))";

        $results = $wpdb->get_col( $wpdb->prepare( $sql, $meta_key ) );

        if ( ! empty( $results ) ) {
            foreach ( $results as $value ) {
                $values[] = array(
                    'value' => $value,
                    'label' => $value,
                );
            }
        }

        return $values;
    }

    /**
     * Get available dimension values (length/width)
     *
     * @since 1.0.0
     * @param array  $filters Current filters
     * @param string $dimension Type of dimension (length or width)
     * @return array Available dimension values
     */
    private function get_dimension_values( $filters, $dimension ) {
        global $wpdb;

        $meta_key = '_' . $dimension;
        $values = array();

        // Build query to get unique dimension values
        $sql = "SELECT DISTINCT meta_value 
                FROM {$wpdb->postmeta} pm
                INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                WHERE pm.meta_key = %s 
                AND p.post_type = 'product' 
                AND p.post_status = 'publish'
                AND pm.meta_value != ''
                ORDER BY CAST(pm.meta_value AS DECIMAL(10,2))";

        $results = $wpdb->get_col( $wpdb->prepare( $sql, $meta_key ) );

        if ( ! empty( $results ) ) {
            foreach ( $results as $value ) {
                $values[] = array(
                    'value' => $value,
                    'label' => $value,
                );
            }
        }

        return $values;
    }

    /**
     * Get available type values
     *
     * @since 1.0.0
     * @param array $filters Current filters
     * @return array Available type values
     */
    private function get_type_values( $filters ) {
        global $wpdb;

        $meta_key = '_type';
        $values = array();

        // Build query to get unique type values
        $sql = "SELECT DISTINCT meta_value 
                FROM {$wpdb->postmeta} pm
                INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                WHERE pm.meta_key = %s 
                AND p.post_type = 'product' 
                AND p.post_status = 'publish'
                AND pm.meta_value != ''
                ORDER BY pm.meta_value";

        $results = $wpdb->get_col( $wpdb->prepare( $sql, $meta_key ) );

        if ( ! empty( $results ) ) {
            foreach ( $results as $value ) {
                $values[] = array(
                    'value' => $value,
                    'label' => $value,
                );
            }
        }

        return $values;
    }
}
