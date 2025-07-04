<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}

$css_class    = 'goldsmith-loop-product';
$css_class   .= wp_doing_ajax() ? ' animated '.apply_filters( 'goldsmith_loop_product_animation', goldsmith_settings( 'shop_product_animation_type', 'fadeInUp' ) ) : '';
$animation    = apply_filters( 'goldsmith_loop_product_animation', goldsmith_settings( 'shop_product_animation_type', 'fadeInUp' ) );
$type         = apply_filters( 'goldsmith_loop_product_type', goldsmith_settings( 'shop_product_type', '2' ) );
$catalog_mode = goldsmith_settings( 'woo_catalog_mode', '0' );

?>
<div <?php wc_product_class( $css_class, $product ); ?> data-product-animation="<?php echo esc_attr( $animation ); ?>">

    <?php
    if ( '1' == $catalog_mode ) {
        goldsmith_loop_product_type_catalog();
    } elseif ( '1' == goldsmith_get_shop_column() ) {
        goldsmith_loop_product_type_list();
    } elseif ( '2' == $type ) {
        goldsmith_loop_product_type2();
    } elseif ( '3' == $type ){
        goldsmith_loop_product_type3();
    } elseif ( 'woo' == $type ){
        goldsmith_loop_product_type_woo_default();
    } elseif ( 'custom' == $type ) {
        goldsmith_loop_product_layout_manager();
    } else {
        goldsmith_loop_product_type1();
    }
    ?>

</div>
<?php
