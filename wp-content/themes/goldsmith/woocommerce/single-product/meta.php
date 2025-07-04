<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( '1' != goldsmith_settings('single_shop_meta_visibility', '1') ) {
    return;
}

?>
<div class="goldsmith-summary-item goldsmith-product-meta">

    <?php do_action( 'woocommerce_product_meta_start' ); ?>

    <?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) :
        $sku = $product->get_sku() ? $product->get_sku() : esc_html__( 'N/A', 'goldsmith' );
        ?>

        <div class="goldsmith-sku-wrapper goldsmith-meta-wrapper"><span class="goldsmith-meta-label"><?php esc_html_e( 'SKU:', 'goldsmith' ); ?></span> <span class="sku"><?php echo esc_html( $sku ); ?></span></div>

    <?php endif; ?>

    <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<div class="goldsmith-small-title goldsmith-meta-wrapper posted_in"><span class="goldsmith-meta-label">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'goldsmith' ) . '</span>', '</div>' ); ?>

    <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<div class="goldsmith-small-title goldsmith-meta-wrapper tagged_as"><span class="goldsmith-meta-label">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'goldsmith' ) . '</span>', '</div>' ); ?>

    <?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
