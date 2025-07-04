<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

wp_enqueue_style( 'goldsmith-wc-cart-page' );

do_action( 'woocommerce_before_cart' );

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
$icon = '<svg
class="svgTrash goldsmith-svg-icon mini-icon"
height="427pt"
viewBox="-40 0 427 427.00131"
width="427pt"
xmlns="http://www.w3.org/2000/svg"><use href="#shopTrash"></use></svg>';
?>
<div class="row goldsmith-cart-row">
    <div class="col-lg-8">
        <form class="woocommerce-cart-form goldsmith-woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>
            <div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                <?php
                do_action( 'woocommerce_before_cart_contents' );

                foreach ( WC()->cart->get_cart() as $key => $item ) {
                    $p     = apply_filters( 'woocommerce_cart_item_product', $item['data'], $item, $key );
                    $pid   = apply_filters( 'woocommerce_cart_item_product_id', $item['product_id'], $item, $key );
                    $size  = apply_filters( 'goldsmith_cart_item_img_size', [80,80] );
                    $thumb = apply_filters( 'woocommerce_cart_item_thumbnail', $p->get_image( $size ), $item, $key );
                    $price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $p ), $item, $key );
                    $name  = apply_filters( 'woocommerce_cart_item_name', $p->get_name(), $item, $key );
                    $qty   = $item['quantity'];
                    $visible = apply_filters( 'woocommerce_cart_item_visible', true, $item, $key );
                    if ( $p && $p->exists() && $qty > 0 && $visible ) {
                        $link = apply_filters( 'woocommerce_cart_item_permalink', $p->is_visible() ? $p->get_permalink( $item ) : '', $item, $key );
                        $class = apply_filters( 'woocommerce_cart_item_class', 'cart_item', $item, $key );
                        ?>
                        <div class="row goldsmith-cart-item goldsmith-align-center woocommerce-cart-form__cart-item <?php echo esc_attr( $class ); ?>">
                            <div class="col-12 col-sm-6">
                                <div class="row goldsmith-meta-left goldsmith-flex goldsmith-align-center">
                                    <div class="col-3 product-thumbnail">
                                        <?php
                                        if ( ! $link ) {
                                            printf( '%s', $thumb );
                                        } else {
                                            printf( '<a href="%s">%s</a>', esc_url( $link ), $thumb );
                                        }
                                        ?>
                                    </div>
                                    <div class="col-9 product-name goldsmith-small-title" data-title="<?php esc_attr_e( 'Product', 'goldsmith' ); ?>">
                                        <?php
                                        if ( ! $link ) {
                                            printf( '%s',$name );
                                        } else {
                                            printf( '<a href="%s">%s</a>', esc_url( $link ), $name );
                                        }
                                        do_action( 'woocommerce_after_cart_item_name', $item, $key );
                                        // Meta data.
                                        echo wc_get_formatted_cart_item_data( $item );
                                        // Backorder notification.
                                        if ( $p->backorders_require_notification() && $p->is_on_backorder( $qty ) ) {
                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'goldsmith' ) . '</p>', $pid ) );
                                        }
                                        ?>
                                        <div class="product-price goldsmith-price" data-title="<?php esc_attr_e( 'Price', 'goldsmith' ); ?>">
                                            <span class="price"><?php printf( '%s', $price ); ?></span>
                                            <span class="cart-quantity"><?php printf( esc_html__( 'X %s', 'goldsmith' ), $qty ); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="row goldsmith-meta-right goldsmith-align-center">
                                    <div class="col-auto product-quantity goldsmith-quantity-small" data-title="<?php esc_attr_e( 'Quantity', 'goldsmith' ); ?>">
                                        <?php
                                        if ( $p->is_sold_individually() ) {
                                            $min = 1;
                                            $max = 1;
                                        } else {
                                            $min = 0;
                                            $max = $p->get_max_purchase_quantity();
                                        }
                                        $quantity = woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$key}][qty]",
                                                'input_value'  => $qty,
                                                'max_value'    => $max,
                                                'min_value'    => $min,
                                                'product_name' => $name
                                            ),
                                            $p,
                                            false
                                        );
                                        echo apply_filters( 'woocommerce_cart_item_quantity', $quantity, $key, $item );
                                        ?>
                                    </div>
                                    <div class="col-auto product-subtotal goldsmith-price" data-title="<?php esc_attr_e( 'Subtotal', 'goldsmith' ); ?>">
                                        <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $p, $qty ), $item, $key ); ?>
                                    </div>
                                    <div class="col-auto product-remove">
                                        <?php
                                        echo apply_filters(
                                            'woocommerce_cart_item_remove_link',
                                            sprintf(
                                                '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-name="%s" data-qty="%s">'.$icon.'</a>',
                                                esc_url( wc_get_cart_remove_url( $key ) ),
                                                esc_attr( sprintf( __( 'Remove %s from cart', 'goldsmith' ), $name ) ),
                                                esc_attr( $pid ),
                                                esc_attr( $p->get_sku() ),
                                                esc_attr( $name ),
                                                $qty
                                            ),
                                            $key
                                        );
                                        ?>
                                        <span class="loading-wrapper"><span class="ajax-loading"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                do_action( 'woocommerce_cart_contents' );
                ?>
                <div class="goldsmith-cart-item goldsmith-actions">
                    <div class="row">
                        <?php if ( wc_coupons_enabled() ) { ?>
                            <div class="col col-12 col-7 col-lg-8">
                                <div class="goldsmith-flex">
                                    <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'goldsmith' ); ?></label>
                                    <input type="text" name="coupon_code" class="input-text goldsmith-input goldsmith-input-small" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'goldsmith' ); ?>" />
                                    <button type="submit" class="goldsmith-btn goldsmith-bg-black goldsmith-btn-medium cart-apply-button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'goldsmith' ); ?>"><?php esc_html_e( 'Apply coupon', 'goldsmith' ); ?></button>
                                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col col-12 col-lg-4">
                            <div class="goldsmith-hidden goldsmith-flex goldsmith-flex-right">
                                <button type="submit" class="goldsmith-btn goldsmith-bg-black goldsmith-btn-large cart-update-button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'goldsmith' ); ?>"><?php esc_html_e( 'Update cart', 'goldsmith' ); ?></button>
                                <?php do_action( 'woocommerce_cart_actions' ); ?>
                                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php do_action( 'woocommerce_after_cart_contents' ); ?>
            </div>
            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>
    </div>
    <div class="col-lg-4"><?php woocommerce_cart_totals(); ?></div>
    <div class="col-lg-12">
        <?php
        do_action( 'woocommerce_before_cart_collaterals' );
        /**
        * Cart collaterals hook.
        *
        * @hooked woocommerce_cross_sell_display
        * @hooked woocommerce_cart_totals - 10
        */
        do_action( 'woocommerce_cart_collaterals' );
        ?>
    </div>
</div>
<?php do_action( 'woocommerce_after_cart' ); ?>
