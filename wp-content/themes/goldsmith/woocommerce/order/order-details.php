<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.0.0
 *
 * @var bool $show_downloads Controls whether the downloads table should be rendered.
 */

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id );

if ( ! $order ) {
    return;
}

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$downloads             = $order->get_downloadable_items();
$show_customer_details = $order->get_user_id() === get_current_user_id();

if ( $show_downloads ) {
    wc_get_template(
        'order/order-downloads.php',
        array(
            'downloads'  => $downloads,
            'show_title' => true,
        )
    );
}
?>

<div class="woocommerce-order-details">
    <?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

    <h4 class="woocommerce-order-details__title goldsmith-section-title"><?php esc_html_e( 'Order details', 'goldsmith' ); ?></h4>

    <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

        <thead>
            <tr>
                <th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'Product', 'goldsmith' ); ?></th>
                <th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'Total', 'goldsmith' ); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php
            do_action( 'woocommerce_order_details_before_order_table_items', $order );

            foreach ( $order_items as $item_id => $item ) {
                $product = $item->get_product();

                wc_get_template(
                    'order/order-details-item.php',
                    array(
                        'order'              => $order,
                        'item_id'            => $item_id,
                        'item'               => $item,
                        'show_purchase_note' => $show_purchase_note,
                        'purchase_note'      => $product ? $product->get_purchase_note() : '',
                        'product'            => $product,
                    )
                );
            }

            do_action( 'woocommerce_order_details_after_order_table_items', $order );
            ?>
        </tbody>

        <tfoot>
            <?php
            foreach ( $order->get_order_item_totals() as $key => $total ) {
                ?>
                <tr>
                    <th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
                    <td><?php echo wp_kses_post( $total['value'] ); ?></td>
                </tr>
                <?php
            }
            ?>
            <?php if ( $order->get_customer_note() ) : ?>
                <tr>
                    <th><?php esc_html_e( 'Note:', 'goldsmith' ); ?></th>
                    <td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
                </tr>
            <?php endif; ?>
        </tfoot>
    </table>

    <?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</div>


<?php
/**
* Action hook fired after the order details.
*
* @since 4.4.0
* @param WC_Order $order Order data.
*/
do_action( 'woocommerce_after_order_details', $order );

if ( $show_customer_details ) {
    wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}
