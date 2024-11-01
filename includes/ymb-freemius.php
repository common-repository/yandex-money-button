<?php

/**
 * Plugin runs only from wordpress
 */
defined( 'ABSPATH' ) || exit;

if ( !function_exists( 'ymb_fs' ) ) {
    // Create a helper function for easy SDK access.
    function ymb_fs()
    {
        global  $ymb_fs ;
        
        if ( !isset( $ymb_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_3026_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_3026_MULTISITE', true );
            }
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/../freemius/start.php';
            $ymb_fs = fs_dynamic_init( array(
                'id'              => '3026',
                'slug'            => 'yandex-money-button',
                'type'            => 'plugin',
                'public_key'      => 'pk_5777ffd58e175faf1494834a8bd16',
                'is_premium'      => false,
                'premium_suffix'  => 'Premium',
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'trial'           => array(
                'days'               => 7,
                'is_require_payment' => true,
            ),
                'has_affiliation' => 'selected',
                'menu'            => array(
                'first-path' => 'plugins.php',
            ),
                'is_live'         => true,
            ) );
        }
        
        return $ymb_fs;
    }
    
    // Init Freemius.
    ymb_fs();
    // Signal that SDK was initiated.
    do_action( 'ymb_fs_loaded' );
}

/**
 * [ymb_fs_custom_connect_message_on_update description]
 * @since  2.0.0
 */
function ymb_fs_custom_connect_message_on_update(
    $message,
    $user_first_name,
    $product_title,
    $user_login,
    $site_link,
    $freemius_link
)
{
    return sprintf(
        'Привет %1$s' . ',<br>' . 'Пожалуйста, помогите нам улучшить плагин «%2$s»! Если вы согласны, данные об ипользовании плагина будут отправляться в %5$s. Это позволит нам делать плагин лучше. Если вы не хотите отправлять данные — ничего страшного! Плагин все равно будет работать.',
        $user_first_name,
        '<b>' . $product_title . '</b>',
        '<b>' . $user_login . '</b>',
        $site_link,
        $freemius_link
    );
}

/**
 * [ymb_fs_custom_connect_message description]
 * @since  2.0.0
 */
function ymb_fs_custom_connect_message(
    $message,
    $user_first_name,
    $product_title,
    $user_login,
    $site_link,
    $freemius_link
)
{
    return sprintf(
        'Привет %1$s' . ',<br>' . 'Не пропускайте важные обновления безопасности и выход новых функций — подпишитесь на уведомления и диагностику использования плагина с помощю %5$s.',
        $user_first_name,
        '<b>' . $product_title . '</b>',
        '<b>' . $user_login . '</b>',
        $site_link,
        $freemius_link
    );
}

/**
 * @since 2.0.0
 */
ymb_fs()->add_filter(
    'connect_message_on_update',
    'ymb_fs_custom_connect_message_on_update',
    10,
    6
);
/**
 * @since 2.0.0
 */
ymb_fs()->add_filter(
    'connect_message',
    'ymb_fs_custom_connect_message',
    10,
    6
);
/**
 * Translate some Freemius strings
 * @since  2.0.0
 */
if ( function_exists( 'fs_override_i18n' ) ) {
    fs_override_i18n( array(
        'opt-in-connect' => 'Продолжить',
        'skip'           => 'Позже',
    ), 'yandex-money-button' );
}