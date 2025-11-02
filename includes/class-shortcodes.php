<?php
/**
 * Shortcodes class for KSET Dynamic Shop Filters
 *
 * @package KSET_Dynamic_Shop_Filters
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * KSET Dynamic Shop Filters Shortcodes Class
 */
class KSET_Dynamic_Shop_Filters_Shortcodes {

    /**
     * The single instance of the class.
     *
     * @var KSET_Dynamic_Shop_Filters_Shortcodes
     */
    protected static $_instance = null;

    /**
     * Main Instance.
     *
     * @return KSET_Dynamic_Shop_Filters_Shortcodes - Main instance.
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
        add_action( 'init', array( $this, 'init_shortcodes' ) );
    }

    /**
     * Initialize shortcodes.
     */
    public function init_shortcodes() {
        add_shortcode( 'kset_shop_filters', array( $this, 'render_shop_filters' ) );
        add_shortcode( 'kset_shop_search_results', array( $this, 'render_shop_search_results' ) );
    }

    /**
     * Render main shop filters shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public function render_shop_filters( $atts ) {
        $atts = shortcode_atts( array(
            'id' => 'kset-shop-filters',
            'class' => 'kset-shop-filters-container'
        ), $atts );

        return sprintf(
            '<div id="%s" class="%s">F</div>',
            esc_attr( $atts['id'] ),
            esc_attr( $atts['class'] )
        );
    }

    /**
     * Render search results shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public function render_shop_search_results( $atts ) {
        $atts = shortcode_atts( array(
            'id' => 'kset-search-results',
            'class' => 'kset-search-results-container'
        ), $atts );

        return sprintf(
            '<div id="%s" class="%s">R</div>',
            esc_attr( $atts['id'] ),
            esc_attr( $atts['class'] )
        );
    }
}
