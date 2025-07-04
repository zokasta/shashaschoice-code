<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
    return;
}
if ( '1' == goldsmith_settings( 'woo_catalog_mode', '0' ) && '1' == goldsmith_settings( 'woo_disable_product_addtocart', '0' ) ) {
    return;
}
if ( $product->is_in_stock() ) : ?>
<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<div class="goldsmith-summary-item goldsmith-product-info">
	<div class="goldsmith-product-info-top">
		<form class="goldsmith-flex cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		    <?php
		    do_action( 'woocommerce_before_add_to_cart_quantity' );

		    woocommerce_quantity_input(
		        array(
		            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
		            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
		            'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		        )
		    );

		    do_action( 'woocommerce_after_add_to_cart_quantity' );
		    ?>

		    <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"
		    class="single_add_to_cart_button goldsmith-btn goldsmith-btn-medium goldsmith-btn-dark goldsmith-btn-border"
		    data-added-title="<?php esc_attr_e( 'Added to Cart','goldsmith' ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?>
		    <div class="loading-wrapper"><span class="ajax-loading"></span></div>
		    </button>

		    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
		</form>
	</div>
	<?php if ( goldsmith_shipping_class_name() && '1' == goldsmith_settings( 'single_shop_shipping_delivery_visibility', '1' ) ) { ?>
	    <div class="goldsmith-product-info-bottom">
	        <div class="info-message shipping-class"><?php echo goldsmith_svg_lists( 'delivery-return', 'goldsmith-svg-icon' ); ?> <strong><?php echo goldsmith_shipping_class_name(); ?></strong></div>
	        <div class="info-message shipping-description"><?php echo goldsmith_shipping_class_name('desc'); ?></div>
	    </div>
	<?php } ?>
</div>
<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
<?php endif; ?>
