<?php
/**
* Thankyou page
*
* This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 8.1.0
*/

defined( 'ABSPATH' ) || exit;

wp_enqueue_style( 'goldsmith-wc' );
?>

<div class="woocommerce-order">
    <div class="row">
        <div class="col-12 col-lg-8">

            <?php
            if ( $order ) :

                do_action( 'woocommerce_before_thankyou', $order->get_id() );
                ?>

                <?php if ( $order->has_status( 'failed' ) ) : ?>

                    <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'goldsmith' ); ?></p>

                    <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                        <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'goldsmith' ); ?></a>
                        <?php if ( is_user_logged_in() ) : ?>
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'goldsmith' ); ?></a>
                        <?php endif; ?>
                    </p>

                <?php else : ?>

                    <?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>

                    <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

                        <li class="woocommerce-order-overview__order order">
                            <?php esc_html_e( 'Order number:', 'goldsmith' ); ?>
                            <strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
                        </li>

                        <li class="woocommerce-order-overview__date date">
                            <?php esc_html_e( 'Date:', 'goldsmith' ); ?>
                            <strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
                        </li>

                        <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                            <li class="woocommerce-order-overview__email email">
                                <?php esc_html_e( 'Email:', 'goldsmith' ); ?>
                                <strong><?php echo esc_html( $order->get_billing_email() ); ?></strong>
                            </li>
                        <?php endif; ?>

                        <li class="woocommerce-order-overview__total total">
                            <?php esc_html_e( 'Total:', 'goldsmith' ); ?>
                            <?php printf('<strong>%s</strong>', $order->get_formatted_order_total() ); ?>
                        </li>

                        <?php if ( $order->get_payment_method_title() ) : ?>
                            <li class="woocommerce-order-overview__payment-method method">
                                <?php esc_html_e( 'Payment method:', 'goldsmith' ); ?>
                                <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
                            </li>
                        <?php endif; ?>

                    </ul>
                <?php endif; ?>

                <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
                <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

            <?php else : ?>

                <?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

            <?php endif; ?>
        </div>
    </div>
</div>
