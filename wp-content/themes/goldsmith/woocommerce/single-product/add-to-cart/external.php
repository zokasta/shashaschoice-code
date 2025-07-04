<?php
/**
 * External product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/external.php.
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
if ( '1' == goldsmith_settings( 'woo_catalog_mode', '0' ) && '1' == goldsmith_settings( 'woo_disable_product_addtocart', '0' ) ) {
    return;
}
?>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<div class="goldsmith-summary-item goldsmith-product-info">
	<div class="goldsmith-product-info-top">
		<form class="goldsmith-flex cart form-external" action="<?php echo esc_url( $product_url ); ?>" method="get">
			<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

			<button type="submit" class="single_add_to_cart_button goldsmith-btn goldsmith-btn-medium goldsmith-btn-dark goldsmith-btn-border"><?php echo esc_html( $button_text ); ?></button>

			<?php wc_query_string_form_fields( $product_url ); ?>

			<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
		</form>
	</div>
	<?php if ( goldsmith_shipping_class_name() && '1' == goldsmith_settings( 'single_shop_shipping_delivery_visibility', '1' ) ) { ?>
	    <div class="goldsmith-product-info-bottom">
	        <div class="info-message"><?php echo goldsmith_svg_lists( 'delivery-return', 'goldsmith-svg-icon' ); ?> <strong><?php echo goldsmith_shipping_class_name(); ?></strong></div>
	        <div class="info-message"><?php echo goldsmith_shipping_class_name('desc'); ?></div>
	    </div>
	<?php } ?>
</div>
<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
