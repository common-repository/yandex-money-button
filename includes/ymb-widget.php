<?php

/**
 * Plugin runs only from wordpress
 */
defined( 'ABSPATH' ) || exit;
/**
 * Yandex Money Button Widget
 * @since 2.2.0
 */
class Ymb_button extends WP_Widget
{
    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname'   => 'ymb-widget',
            'description' => 'Кнопка ЮMoney',
        );
        parent::__construct( 'ymb_button', 'Кнопка ЮMoney', $widget_ops );
    }
    
    /**
     * Outputs the content of the widget
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance )
    {
        echo  '<div class="widget wp-block-ymb-button"><form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml" target="_blank">' ;
        echo  '<input type="hidden" name="receiver" value="' . esc_attr($instance['receiver']) . '">' ;
        echo  '<input type="hidden" name="quickpay-form" value="small">' ;
        echo  '<input type="hidden" name="targets" value="' . esc_attr($instance['targets']) . '">' ;
        echo  '<input type="hidden" name="sum" value="' . esc_attr($instance['sum']) . '">' ;
        echo  '<input type="hidden" name="paymentType" value="' . esc_attr($instance['paymenttype']) . '">' ;
        echo  '<button type="submit" style="background: #ffdb4d; background-color: #ffdb4d; color: #ffffff;">' ;
        echo  '<div class="wp-block-ymb-button-div">' ;
        switch ($instance['paymenttype']) {
            case 'PC':
                echo '<svg width="24" height="24" viewBox"0 0 24 24">
                    <g fill="none">
                        <path d="M19.419 9.152H4.583C3.712 9.152 3 9.864 3 10.735v9.574c0 .87.712 1.582 1.583 1.582h14.836V9.152z" fill="#FAC514"></path>
                        <path d="M3 20.309v-9.574c0-.87.712-1.583 1.583-1.583h12.23v7.642L4.75 20.719 3 20.309z" fill="#D7AB05"></path>
                        <path d="M14.353 0v13.98L4.359 20.79 3 20.012V10.71c0-1.212.102-1.982 2.612-3.856C7.691 5.303 14.352 0 14.352 0" fill="#FAC514"></path>
                        <path d="M10.506 8.694c.545-.65 1.34-.878 1.777-.511.436.366.349 1.189-.195 1.838-.545.648-1.34.878-1.776.51-.437-.365-.35-1.189.194-1.837" fill"#020202"></path>
                    </g>
                </svg>';
                break;
            case 'AC':
                echo '<svg width="24" height"24" viewBox="0 0 24 24">
                    <g fill="#3C3C3C" fill-rule="evenodd">
                        <path d="M15.948 7l-1.94-3.358a1.997 1.997 0 0 0-2.72-.73l-9.54 5.509a1.993 1.993 0 0 0-.73 2.72l3.508 6.077c.154.266.36.483.6.646A2.998 2.998 0 0 1 5 17.001V10A2.994 2.994 0 0 1 8 7h7.948z"></path>
                        <path d="M6 9.992C6 8.892 6.893 8 7.992 8h11.016C20.108 8 21 8.9 21 9.992v7.016c0 1.1-.893 1.992-1.992 1.992H7.992C6.892 19 6 18.1 6 17.008V9.992zm1 .006C7 9.447 7.447 9 7.999 9H19c.552 0 .999.446.999.998v7.004c0 .551-.447.998-.999.998H8A.998.998 0 0 1 7 17.002V9.998zM7 11h13v2H7v-2z"></path>
                    </g>
                </svg>';
                break;
            case 'MC':
                echo '<svg width="24" height="24" viewBox"0 0 24 24">
                    <path d="M5 1.991C5 .891 5.902 0 7.009 0h7.982C16.101 0 17 .89 17 1.991V20.01c0 1.1-.902 1.991-2.009 1.991H7.01C5.899 22 5 21.11 5 20.009V1.99zM6 2h10v15H6V2zm4 17h2v1h-2v-1z" fill="#3C3C3C" fill-rule="evenodd"></path>
                </svg>';
                break;
        }
        echo  '</div>' ;
        echo  '<span class="wp-block-ymb-button-span" style="color: #000;">' . (( '' != $instance['content'] ? esc_html($instance['content']) : 'Перевести' )) . '</span>' ;
        echo  '</button>' ;
        echo  '</form></div>' ;
    }
    
    /**
     * Outputs the options form on admin
     * @param array $instance The widget options
     */
    public function form( $instance )
    {
        /**
         * Prepare plugin options
         * @since 2.2.0
         */
        $ymb_options = array(
            'receiver' => '',
            'sum'      => '',
            'targets'  => '',
        );
        $receiver = ( !empty($instance['receiver']) ? $instance['receiver'] : $ymb_options['receiver'] );
        ?>
		<p>
		<label for="<?php 
        echo  esc_attr( $this->get_field_id( 'receiver' ) ) ;
        ?>"><?php 
        echo  'Номер счета ЮMoney на который вы хотите принимать переводы' ;
        ?></label> 
		<input class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'receiver' ) ) ;
        ?>" name="<?php 
        echo  esc_attr( $this->get_field_name( 'receiver' ) ) ;
        ?>" type="text" value="<?php 
        echo  esc_attr( $receiver ) ;
        ?>" placeholder="41001xxxxxxxxxxxx">
		</p>
		<?php 
        $targets = ( !empty($instance['targets']) ? $instance['targets'] : $ymb_options['targets'] );
        ?>
		<p>
		<label for="<?php 
        echo  esc_attr( $this->get_field_id( 'targets' ) ) ;
        ?>"><?php 
        echo  'Назначение платежа' ;
        ?></label> 
		<input class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'targets' ) ) ;
        ?>" name="<?php 
        echo  esc_attr( $this->get_field_name( 'targets' ) ) ;
        ?>" type="text" value="<?php 
        echo  esc_attr( $targets ) ;
        ?>" placeholder="На реактор холодного ядерного синтеза">
		</p>
		<?php 
        $sum = ( !empty($instance['sum']) ? $instance['sum'] : $ymb_options['sum'] );
        ?>
		<p>
		<label for="<?php 
        echo  esc_attr( $this->get_field_id( 'sum' ) ) ;
        ?>"><?php 
        echo  'Сумма платежа в рублях' ;
        ?></label> 
		<input class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'sum' ) ) ;
        ?>" name="<?php 
        echo  esc_attr( $this->get_field_name( 'sum' ) ) ;
        ?>" type="text" value="<?php 
        echo  esc_attr( $sum ) ;
        ?>" placeholder="100">
		</p>
		<?php 
        $paymenttype = ( !empty($instance['paymenttype']) ? $instance['paymenttype'] : '' );
        ?>
		<p>
		<label for="<?php 
        echo  esc_attr( $this->get_field_id( 'paymenttype' ) ) ;
        ?>"><?php 
        echo  'Способ оплаты' ;
        ?></label> 
		<select class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'paymenttype' ) ) ;
        ?>" name="<?php 
        echo  esc_attr( $this->get_field_name( 'paymenttype' ) ) ;
        ?>">
			<option value="PC" <?php 
        echo  ( 'PC' == $paymenttype ? 'selected' : '' ) ;
        ?>>Оплата из кошелька ЮMoney</option>
			<option value="AC" <?php 
        echo  ( 'AC' == $paymenttype ? 'selected' : '' ) ;
        ?>>С банковской карты</option>
			<option value="MC" <?php 
        echo  ( 'MC' == $paymenttype ? 'selected' : '' ) ;
        ?>>С баланса мобильного телефона</option>
		</select>
		</p>
		<?php 
        $content = ( !empty($instance['content']) ? esc_html($instance['content']) : 'Перевести' );
        ?>
		<p>
		<label for="<?php 
        echo  esc_attr( $this->get_field_id( 'content' ) ) ;
        ?>"><?php 
        echo  'Текст на кнопке' ;
        ?></label> 
		<input class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'content' ) ) ;
        ?>" name="<?php 
        echo  esc_attr( $this->get_field_name( 'content' ) ) ;
        ?>" type="text" value="<?php 
        echo  esc_attr( $content ) ;
        ?>" placeholder="Перевести">
		</p>
		<?php 
        echo  '<p><a href="' . ymb_fs()->get_upgrade_url() . '"><strong>Активируйте ПРО версию</strong></a> чтобы получить доступ к дополнительным настройкам (цвет кнопки, запрос на сбор дополнительной информации с плательщика (ФИО, телефон, почта, адрес), адрес для перенаправления после совершения платежа).</p>' ;
    }
    
    /**
     * Processing widget options on save
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     * @return array
     */
    public function update( $new_instance, $old_instance )
    {
        $instance = array();
        $instance['receiver'] = ( !empty($new_instance['receiver']) ? sanitize_text_field( $new_instance['receiver'] ) : '' );
        $instance['targets'] = ( !empty($new_instance['targets']) ? sanitize_text_field( $new_instance['targets'] ) : '' );
        $instance['sum'] = ( !empty($new_instance['sum']) ? sanitize_text_field( $new_instance['sum'] ) : '' );
        $instance['paymenttype'] = ( !empty($new_instance['paymenttype']) ? sanitize_text_field( $new_instance['paymenttype'] ) : '' );
        $instance['content'] = ( !empty($new_instance['content']) ? sanitize_text_field( $new_instance['content'] ) : '' );
        return $instance;
    }

}
/**
 * Init YMB Widget
 * @since 2.2.0
 */
add_action( 'widgets_init', function () {
    register_widget( 'Ymb_button' );
} );