<?php
/**
 * Pay for order form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-pay.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.2.0
 */

defined( 'ABSPATH' ) || exit;

$totals = $order->get_order_item_totals();
?>
<form id="order_review" method="post">

    <table class="shop_table">
        <thead>
            <tr>
                <th class="product-name"><?php esc_html_e( 'Product', 'goldsmith' ); ?></th>
                <th class="product-quantity"><?php esc_html_e( 'Qty', 'goldsmith' ); ?></th>
                <th class="product-total"><?php esc_html_e( 'Totals', 'goldsmith' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ( count( $order->get_items() ) > 0 ) : ?>
                <?php foreach ( $order->get_items() as $item_id => $item ) : ?>
                    <?php
                    if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
                        continue;
                    }
                    ?>
                    <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
                        <td class="product-name">
                            <?php
                            echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

                            do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

                            wc_display_item_meta( $item );

                            do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
                            ?>
                        </td>
                        <td class="product-quantity"><?php echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', esc_html( $item->get_quantity() ) ) . '</strong>', $item ); ?></td>
                        <td class="product-subtotal"><?php printf('%s', $order->get_formatted_line_subtotal( $item ) ); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <?php if ( $totals ) : ?>
                <?php foreach ( $totals as $total ) : ?>
                    <tr>
                        <th scope="row" colspan="2"><?php printf('%s', $total['label'] ); ?></th>
                        <td class="product-total"><?php printf('%s', $total['value'] ); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tfoot>
    </table>
    <?php
	/**
	 * Triggered from within the checkout/form-pay.php template, immediately before the payment section.
	 *
	 * @since 8.2.0
	 */
	do_action( 'woocommerce_pay_order_before_payment' );
	?>
    <div id="payment">
        <?php if ( $order->needs_payment() ) : ?>
            <ul class="wc_payment_methods payment_methods methods">
                <?php
                if ( ! empty( $available_gateways ) ) {
                    foreach ( $available_gateways as $gateway ) {
                        wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                    }
                } else {
                    echo '<li>';
                    wc_print_notice( apply_filters( 'woocommerce_no_available_payment_methods_message', esc_html__( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'goldsmith' ) ), 'notice' );
                    echo '</li>';
                }
                ?>
            </ul>
        <?php endif; ?>
        <div class="form-row">
            <input type="hidden" name="woocommerce_pay" value="1" />

            <?php wc_get_template( 'checkout/terms.php' ); ?>

            <?php do_action( 'woocommerce_pay_order_before_submit' ); ?>

            <?php echo apply_filters( 'woocommerce_pay_order_button_html', '<button type="submit" class="button alt goldsmith-btn-medium goldsmith-btn goldsmith-bg-black" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); ?>

            <?php do_action( 'woocommerce_pay_order_after_submit' ); ?>

            <?php wp_nonce_field( 'woocommerce-pay', 'woocommerce-pay-nonce' ); ?>
        </div>
    </div>
</form>
