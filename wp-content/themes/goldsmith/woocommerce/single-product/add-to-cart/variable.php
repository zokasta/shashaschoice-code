<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( '1' == goldsmith_settings( 'woo_catalog_mode', '0' ) && '1' == goldsmith_settings( 'woo_disable_product_addtocart', '0' ) ) {
    return;
}

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );


do_action( 'woocommerce_before_add_to_cart_form' );

?>

<form class="goldsmith-summary-item goldsmith-flex variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo esc_attr( $variations_attr ); // WPCS: XSS ok. ?>">
    <?php do_action( 'woocommerce_before_variations_form' ); ?>

    <?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
        <p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'goldsmith' ) ) ); ?></p>
    <?php else : ?>
        <div class="goldsmith-variations variations">
            <?php if ( '1' == goldsmith_settings('selected_variations_terms_visibility', '1' ) ) : ?>
                <div class="goldsmith-selected-variations-terms-wrapper">
                    <?php if ( '' != goldsmith_settings('selected_variations_terms_title', '' ) ) : ?>
                        <span class="goldsmith-selected-variations-terms-title"><?php echo goldsmith_settings('selected_variations_terms_title'); ?></span>
                    <?php else : ?>
                        <span class="goldsmith-selected-variations-terms-title"><?php esc_html_e( 'Selected Features', 'goldsmith' ); ?></span>
                    <?php endif; ?>
                    <div class="goldsmith-selected-variations-terms"></div>
                </div>
            <?php endif; ?>
            <?php foreach ( $attributes as $attribute_name => $options ) : ?>
                <div class="goldsmith-variations-items variations-items attr-<?php echo esc_attr( $attribute_name ); ?>">
                    <div class="goldsmith-flex goldsmith-align-center variations-item">
                        <span class="goldsmith-small-title"><?php echo wc_attribute_label( $attribute_name ); ?></span>
                        <div class="goldsmith-flex value">
                            <?php
                            wc_dropdown_variation_attribute_options(
                                array(
                                    'options'   => $options,
                                    'attribute' => $attribute_name,
                                    'product'   => $product,
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<div class="goldsmith-flex goldsmith-align-center goldsmith-btn-reset-wrapper variations-item"><span class="goldsmith-small-title reset-title">reset</span><a class="goldsmith-btn-reset reset_variations" href="#">' . esc_html__( 'Clear', 'goldsmith' ) . '</a></div>' ) ) : '';
                ?>
            <?php endforeach; ?>
        </div>
        <div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>

        <?php do_action( 'woocommerce_after_variations_table' ); ?>

        <div class="goldsmith-product-info">
            <div class="goldsmith-product-info-top">
                <?php
                /**
                * Hook: woocommerce_before_single_variation.
                */
                do_action( 'woocommerce_before_single_variation' );

                /**
                * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
                *
                * @since 2.4.0
                * @hooked woocommerce_single_variation - 10 Empty div for variation data.
                * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
                */
                do_action( 'woocommerce_single_variation' );

                /**
                * Hook: woocommerce_after_single_variation.
                */
                do_action( 'woocommerce_after_single_variation' );
                ?>
            </div>
            <?php if ( goldsmith_shipping_class_name() && '1' == goldsmith_settings( 'single_shop_shipping_delivery_visibility', '1' ) ) { ?>
                <div class="goldsmith-product-info-bottom">
                    <div class="info-message shipping-class"><?php echo goldsmith_svg_lists( 'delivery-return', 'goldsmith-svg-icon' ); ?> <strong><?php echo goldsmith_shipping_class_name(); ?></strong></div>
                    <div class="info-message shipping-description"><?php echo goldsmith_shipping_class_name('desc'); ?></div>
                </div>
            <?php } ?>
        </div>
    <?php endif; ?>
    <?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
