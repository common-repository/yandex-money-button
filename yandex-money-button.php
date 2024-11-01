<?php

/**
 * Plugin Name: Кнопка ЮMoney
 * Plugin URI: http://yandex-money-button.milukove.ru/
 * Description: Комплексное решение для приема платежей для физических лиц: платежный шлюз Woocommerce, блок для Гутенберга и виджет.
 * Version: 2.4.0
 * Author: Egor Milyukov
 * Author URI: http://milukove.ru/
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * 
 */
/**
 * Plugin runs only from wordpress
 */
defined( 'ABSPATH' ) || exit;

if ( function_exists( 'ymb_fs' ) ) {
    ymb_fs()->set_basename( false, __FILE__ );
    return;
}

/**
 * Freemius integration
 * @since 2.3.0 moved to separate file
 * @since 2.0.0
 */
require_once dirname( __FILE__ ) . '/includes/ymb-freemius.php';
/**
 * Init admin page
 * @since 2.1.0
 * @since 2.4.0 Remove Titan Framework and custom Admin page
 * require_once dirname(__FILE__) . '/includes/ymb-admin.php';
 */
/**
 * Init gutenberg blocks
 * @since 2.3.0 moved to separate file
 * @since 1.0.0
 */
require_once dirname( __FILE__ ) . '/includes/ymb-blocks.php';
/**
 * Add button styles to public pages
 * @since 2.2.0
 */
if ( !function_exists( 'ymb_enqueue_button_style' ) ) {
    function ymb_enqueue_button_style()
    {
        wp_enqueue_style( 'ymb-style', plugins_url( 'css/ymb.min.css', __FILE__ ) );
    }

}
add_action( 'wp_enqueue_scripts', 'ymb_enqueue_button_style' );
/**
 * Init button widget
 * @since 2.2.0
 */
require_once dirname( __FILE__ ) . '/includes/ymb-widget.php';
/**
 * Init Woocommerce payment gateway
 * @since 2.3.0
 */
require_once dirname( __FILE__ ) . '/includes/ymb-gateway.php';