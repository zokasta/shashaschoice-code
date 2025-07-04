<?php
/**
* Mini-cart
*
* Contains the markup for the mini-cart, used by the cart widget.
*
* This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if ( ! class_exists('WooCommerce') ) {
    return;
}

do_action( 'woocommerce_before_mini_cart' );

$checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() );
$show_quantity = goldsmith_settings('minicart_quantity_visibility', '1');
$clearbtn      = goldsmith_settings('minicart_clearbtn_visibility', '1');
$has_clear_btn = $clearbtn == '1' && ! WC()->cart->is_empty() && count( WC()->cart->get_cart() ) >= 2 ? 'has-clear-btn' : '';
$icon = '<svg
class="svgTrash goldsmith-svg-icon mini-icon"
height="427pt"
viewBox="-40 0 427 427.00131"
width="427pt"
xmlns="http://www.w3.org/2000/svg"><use href="#shopTrash"></use></svg>';
?>
<div class="minicart-panel goldsmith-minicart">
    <?php if ( ! WC()->cart->is_empty() ) : ?>
        <?php do_action( 'goldsmith_before_mini_cart_contents' ); ?>
        <div class="goldsmith-header-cart-details goldsmith-minicart <?php echo esc_attr( $has_clear_btn ); ?>">
            <div class="woocommerce-mini-cart <?php echo !empty($args['list_class']) ? ' '.esc_attr( $args['list_class'] ) : ''; ?>">
                <?php
                do_action( 'woocommerce_before_mini_cart_contents' );
                foreach ( WC()->cart->get_cart() as $key => $item ) {
                    $p   = apply_filters( 'woocommerce_cart_item_product', $item['data'], $item, $key );
                    $pid = apply_filters( 'woocommerce_cart_item_product_id', $item['product_id'], $item, $key );
                    $vis = apply_filters( 'woocommerce_widget_cart_item_visible', true, $item, $key );
                    $qty = apply_filters( 'woocommerce_widget_cart_item_quantity', $item['quantity'], $item, $key );
                    if ( $p && $p->exists() && $qty > 0 && $vis ) {
                        $name  = apply_filters( 'woocommerce_cart_item_name', $p->get_name(), $item, $key );
                        $thumb = apply_filters( 'woocommerce_cart_item_thumbnail', $p->get_image([80,80]), $item, $key );
                        $price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $p ), $item, $key );
                        $link  = apply_filters( 'woocommerce_cart_item_permalink', $p->is_visible() ? $p->get_permalink( $item ) : '', $item, $key );
                        $class = apply_filters( 'woocommerce_mini_cart_item_class', 'mini-cart-item', $item, $key );
                        ?>
                        <div class="woocommerce-mini-cart-item goldsmith-cart-item <?php echo esc_attr( $class ); ?>">
                            <div class="cart-item-details">
                                <a class="product-link" href="<?php echo esc_url( $link ) ?>"><?php printf( '%s', $thumb ); ?></a>
                                <div class="cart-item-title goldsmith-small-title">
                                    <a class="product-link" href="<?php echo esc_url( $link ); ?>">
                                        <?php printf( '<span class="cart-name">%s</span>', $name ); ?>
                                        <span class="goldsmith-price price">
                                            <span class="new"><?php printf( '%s', $price ); ?></span>
                                            <span class="cart-quantity"><?php printf( esc_html__( 'X %s', 'goldsmith' ), $qty ); ?></span>
                                        </span>
                                    </a>
                                    <div class="cart-quantity-wrapper ajax-quantity" data-product_id="<?php echo esc_attr( $pid ); ?>" data-key="<?php echo esc_attr( $key ); ?>">
                                        <?php
                                        if ( $show_quantity == '1' ) {
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
                                            echo apply_filters( 'woocommerce_widget_cart_item_quantity', $quantity, $key, $item );
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="del-icon" data-id="<?php echo esc_attr( $pid ); ?>" data-key="<?php echo esc_attr( $key ); ?>">
                                <?php
                                echo apply_filters(
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="goldsmith_remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-name="%s" data-qty="%s">'.$icon.'</a>',
                                        esc_url( wc_get_cart_remove_url( $key ) ),
                                        esc_attr__( 'Remove this item', 'goldsmith' ),
                                        esc_attr( $pid ),
                                        esc_attr( $key ),
                                        esc_attr( $name ),
                                        esc_attr( $p->get_sku() )
                                    ),
                                    $key
                                );
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                do_action( 'woocommerce_mini_cart_contents' );
                ?>
                <?php if ( $has_clear_btn == 'has-clear-btn' ) { ?>
                    <div class="goldsmith_clear_cart_button"><?php esc_html_e('Clear All', 'goldsmith'); ?></div>
                <?php } ?>
            </div>
            <div class="header-cart-footer">
                <div class="cart-total">
                    <div class="cart-total-price subtotal">
                        <div class="cart-total-price-left"><?php echo esc_html_e( 'Subtotal: ', 'goldsmith' ); ?></div>
                        <div class="cart-total-price-right"><?php printf( '%s', WC()->cart->get_cart_subtotal() ); ?></div>
                        <?php if ( '1' == goldsmith_settings('minicart_total_visibility', '0') ) { ?>
                            <div class="price-total">
                                <div class="cart-total-price-left"><?php echo esc_html_e( 'Total: ', 'goldsmith' ); ?></div>
                                <div class="cart-total-price-right"><?php printf( '%s', WC()->cart->get_cart_total() ); ?></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
                <div class="cart-bottom-btn">
                    <a class="goldsmith-btn-medium goldsmith-btn goldsmith-btn-dark goldsmith-btn-border" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php echo esc_html_e( 'View Cart', 'goldsmith' ); ?></a>
                    <a class="goldsmith-btn-medium goldsmith-btn goldsmith-btn-dark goldsmith-checkout-page-link" href="<?php echo esc_url( $checkout_url ); ?>"><?php echo esc_html_e( 'Checkout', 'goldsmith' ); ?></a>
                </div>
                <?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
            </div>
        </div>
    <?php else : ?>
        <div class="goldsmith-header-cart-details goldsmith-minicart row row-cols-1">
            <div class="cart-empty-content col">
                <?php echo goldsmith_svg_lists( 'bag' ); ?>
                <?php if ( '' != goldsmith_settings('sidebar_panel_cart_custom_title') ) { ?>
                    <span class="minicart-title"><?php echo esc_html( goldsmith_settings('sidebar_panel_cart_custom_title') ); ?></span>
                <?php } else { ?>
                    <span class="minicart-title"><?php esc_html_e( 'Your Cart', 'goldsmith' ); ?></span>
                <?php } ?>
                <p class="empty-title goldsmith-small-title"><?php esc_html_e( 'No products in the cart.', 'goldsmith' ); ?></p>
                <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
                <div class="cart-empty-actions cart-bottom-btn">
                    <a class="goldsmith-btn-medium goldsmith-btn goldsmith-btn-dark goldsmith-btn-border" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Start Shopping', 'goldsmith' ); ?></a>
                    <a class="goldsmith-btn-medium goldsmith-btn goldsmith-btn-dark" href="<?php echo esc_url( get_permalink( get_option( 'wp_page_for_privacy_policy' ) ) ); ?>"><?php esc_html_e( 'Return Policy', 'goldsmith' ); ?></a>
                </div>
                <?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php do_action( 'woocommerce_after_mini_cart' ); ?>
