<?php
/**
 * Assets management class for KSET Dynamic Shop Filters
 *
 * @package KSET_Dynamic_Shop_Filters
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Assets Management Class
 */
class KSET_Dynamic_Shop_Filters_Assets {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
    }

    /**
     * Enqueue frontend scripts and styles.
     */
    public function enqueue_frontend_scripts() {
        // Enqueue WordPress React packages
        wp_enqueue_script( 'wp-element' );
        wp_enqueue_script( 'wp-api-fetch' );
        
        // Frontend React bundle
        wp_enqueue_script(
            'kset-frontend',
            KSET_DSF_PLUGIN_URL . 'assets/js/dist/frontend/bundle.js',
            array( 'jquery', 'wp-element' ),
            KSET_DSF_VERSION,
            true
        );

        // Localize script
        wp_localize_script(
            'kset-frontend',
            'ksetDSF',
            array(
                'apiUrl'    => home_url( '/wp-json' ),
                'nonce'     => wp_create_nonce( 'kset_rest' ),
                'imgUrl'    => KSET_DSF_PLUGIN_URL . 'assets/img/',
                'homeUrl'   => home_url(),
                'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
            )
        );
    }
}