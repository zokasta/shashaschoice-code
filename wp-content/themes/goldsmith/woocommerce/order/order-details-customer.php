<?php
/**
* Order Customer Details
*
* This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see     https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 8.7.0
*/

defined( 'ABSPATH' ) || exit;

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<div class="woocommerce-customer-details">

    <?php if ( $show_shipping ) : ?>

        <div class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses row">
            <div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-12 col-lg-6">

    <?php endif; ?>

                <h4 class="woocommerce-column__title goldsmith-section-title"><?php esc_html_e( 'Billing address', 'goldsmith' ); ?></h4>

                <address>
                    <?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'goldsmith' ) ) ); ?>

                    <?php if ( $order->get_billing_phone() ) : ?>
                        <p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?></p>
                    <?php endif; ?>

                    <?php if ( $order->get_billing_email() ) : ?>
                        <p class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?></p>
                    <?php endif; ?>

                    <?php do_action( 'woocommerce_order_details_after_customer_address', 'billing', $order ); ?>
                </address>

    <?php if ( $show_shipping ) : ?>

            </div><!-- /.col-12 col-lg-6 -->

            <div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-12 col-lg-6">
                <h4 class="woocommerce-column__title goldsmith-section-title"><?php esc_html_e( 'Shipping address', 'goldsmith' ); ?></h4>
                <address>
                    <?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'goldsmith' ) ) ); ?>

                    <?php if ( $order->get_shipping_phone() ) : ?>
                        <p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_shipping_phone() ); ?></p>
                    <?php endif; ?>
                    <?php do_action( 'woocommerce_order_details_after_customer_address', 'shipping', $order ); ?>
                </address>
            </div>
        </div>

    <?php endif; ?>

    <?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

</div>
