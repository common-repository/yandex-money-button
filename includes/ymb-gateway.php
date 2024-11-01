<?php

/**
 * Plugin runs only from wordpress
 */
defined( 'ABSPATH' ) || exit;
function ymb_init_gateway_class()
{
    /**
     * Init gateway only if Woocommerce payment gateway class exists
     * @since 2.3.0
     */
    if ( !class_exists( 'WC_Payment_Gateway' ) ) {
        return;
    }
    if ( class_exists( 'WC_Gateway_Yandex_Button' ) ) {
        return;
    }
    /**
     * Yandex Money Button Payment Gateway.
     * 
     * @class       WC_Gateway_Yandex_Button
     * @extends     WC_Payment_Gateway
     * @since 2.3.1 fixed success redirect issue
     * @since 2.3.0
     */
    class WC_Gateway_Yandex_Button extends WC_Payment_Gateway
    {
        public function __construct()
        {
            $this->id = 'ymb-payments-gateway';
            $this->has_fields = false;
            $this->method_title = 'ЮMoney для физических лиц';
            $this->method_description = 'Принимайте платежи с помощью ЮMoney с банковской карты на кошелек ЮMoney.';
            $this->init_form_fields();
            $this->init_settings();
            $this->title = $this->get_option( 'title' );
            $this->description = $this->get_option( 'description' );
            $this->receiver = $this->get_option( 'receiver' );
            $this->form_fields = array(
                'enabled'     => array(
                'title'       => __( 'Enable/Disable', 'woocommerce' ),
                'type'        => 'checkbox',
                'label'       => 'Включить оплату с помощью сервиса ЮMoney	',
                'default'     => 'yes',
                'description' => 'Ознакомьтесь с <a href="https://yoomoney.ru/page?id=536316#fees-n-limits" target="_blank">комиссиями ЮMoney</a> перед использованием шлюза',
            ),
                'receiver'    => array(
                'title'       => 'Номер кошелька получателя (обязательно)',
                'description' => 'Укажите номер кошелька ЮMoney. Уведомления о поступивших платежах будут приходить на адрес владельца этого кошелька.',
                'type'        => 'text',
                'default'     => '',
            ),
                'title'       => array(
                'title'       => __( 'Title', 'woocommerce' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
                'default'     => 'Банковская карта',
                'desc_tip'    => true,
            ),
                'description' => array(
                'title'       => 'Информация для клиента',
                'type'        => 'textarea',
                'description' => 'Этот текст будет показан клиенту при выборе способа оплаты на странице оформления заказа',
            ),
            );
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'pay_for_order' ) );
        }
        
        function admin_options()
        {
            ?>
			<h2><?php 
            echo  $this->method_title ;
            ?></h2>
			<p><?php 
            echo  $this->method_description ;
            ?></p>
			<div style="float: left; max-width: 645px; padding-right: 20px;">
				<table class="form-table">
					<?php 
            $this->generate_settings_html();
            ?>
				</table>
			</div>
			<?php 
            ?>

			<?php 
            
            if ( ymb_fs()->is_not_paying() ) {
                ?>
				<div style="float: left; max-width: 350px;">
					<div style="padding: 10px 20px; font-size: 1em; background: #ffffff;">
						<p>
							<strong>Активируйте ПРО версию</strong>, чтобы настроить автоматическое обновление статуса заказа и информации об оплате при успешном платеже
						</p>
						<p style="text-align: center;"><a href="<?php 
                echo  ymb_fs()->get_upgrade_url() ;
                ?>" class="button-primary">Активировать ПРО</a></p>
					</div>
				</div>
			<?php 
            }
            
            ?>
			<div style="clear: both;"></div>
			<?php 
        }
        
        public function process_payment( $order_id )
        {
            $order = new WC_Order( $order_id );
            return array(
                'result'   => 'success',
                'redirect' => $order->get_checkout_payment_url( true ),
            );
        }
        
        public function pay_for_order( $order_id )
        {
            $order = new WC_Order( $order_id );
            $order->add_order_note( 'Заказ оформлен, клиенту предложено перейти к оплате заказа с помощью сервиса ЮMoney' );
            $order->update_status( 'pending', 'Ожидается оплата' );
            WC()->cart->empty_cart();
            $order_url = false;
            echo  '<form action="https://money.yandex.ru/quickpay/confirm.xml" method="post" target="_top">
				<input type="hidden" name="receiver" value="' . $this->receiver . '">
				<input type="hidden" name="quickpay-form" value="shop">
				<input type="hidden" name="targets" value="' . get_bloginfo( 'title' ) . ' — Заказ № ' . $order->get_id() . '">
				<input type="hidden" name="paymentType" value="AC">
				<input type="hidden" name="successURL" value="' . (( $order_url ? $order_url : (( isset( $this->successurl ) && $this->successurl != '' ? $this->successurl : home_url() )) )) . '">
				<input type="hidden" name="sum" value="' . $order->get_total() . '">
				<input type="hidden" name="label" value="' . $order->get_id() . '">
				<p>Вы будете перенаправлены на сайт ЮMoney для совершения оплаты</p>
				<div class="btn-submit-payment" style="padding-bottom: 15px;">
					<button type="submit" id="ymb-submit-form">Оплатить</button>
				</div>
			</form>' ;
        }
    
    }
    add_filter( 'woocommerce_payment_gateways', function ( $methods ) {
        $methods[] = 'WC_Gateway_Yandex_Button';
        return $methods;
    } );
}

add_action( 'plugins_loaded', 'ymb_init_gateway_class' );