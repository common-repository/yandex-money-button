<?php

/**
 * Plugin runs only from wordpress
 */
defined( 'ABSPATH' ) || exit;
/**
 * Include yandex money block js and css files
 * @since  1.0.1 Include minifyed styles and scripts instead of regular
 * @since  1.0.0
 */
if ( !function_exists( 'ymb_enqueue' ) ) {
    function ymb_enqueue()
    {
        if ( !function_exists( 'register_block_type' ) ) {
            // Gutenberg is not active.
            return;
        }
        /**
         * Prepare plugin options
         * @since 2.1.0
         */
        $ymb_options = array(
            'receiver' => '',
            'sum'      => '',
            'targets'  => '',
        );
        $ymb_cupc = 'ymb';
        wp_enqueue_script( 'ymb-script', plugins_url( '../js/ymb.min.js', __FILE__ ), array(
            'wp-blocks',
            'wp-editor',
            'wp-element',
            'wp-components'
        ) );
        wp_localize_script( 'ymb-script', 'cupc', $ymb_cupc );
        /**
         * Adds plugin options to block register
         * @since 2.1.0
         */
        wp_localize_script( 'ymb-script', 'options', $ymb_options );
        wp_enqueue_style( 'ymb-style', plugins_url( '../css/ymb.min.css', __FILE__ ) );
    }

}
/**
 * Fires yandex money block
 * @since 1.0.0
 */
add_action( 'enqueue_block_assets', 'ymb_enqueue' );