<?php
/**
* The template for displaying product content in the single-product.php template
*
* This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see     https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 3.6.0
*/

defined( 'ABSPATH' ) || exit;

global $product;

remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
/**
* Hook: woocommerce_before_single_product.
*
* @hooked woocommerce_output_all_notices - 10
*/
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}

$class  = 'goldsmith-section first-section goldsmith-product-strech-type';
$class .= ' gallery-position-'.apply_filters( 'goldsmith_product_gallery_position', goldsmith_settings( 'product_gallery_position', 'right' ) );

?>
<div id="nt-woo-single" class="nt-woo-single goldsmith-single-product-type-stretch">

    <div id="product-<?php the_ID(); ?>" <?php wc_product_class( $class, $product ); ?>>

        <div class="gallery-col">
            <?php goldsmith_product_gallery_stretch(); ?>
        </div>

        <div class="summary-col">
            <div class="goldsmith-product-summary">
                <?php if ( 'custom' != goldsmith_settings( 'single_shop_summary_layout_type', 'default' ) ) { ?>
                    <div class="goldsmith-summary-item goldsmith-product-top-nav">
                        <?php echo goldsmith_breadcrumbs(); ?>
                    </div>
                <?php } ?>

                <?php
                if ( 'custom' == goldsmith_settings( 'single_shop_summary_layout_type', 'default' ) ) {

                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
                    //theme actions
                    remove_action( 'woocommerce_single_product_summary', 'goldsmith_single_stretch_type_product_labels', 15 );
                    remove_action( 'woocommerce_single_product_summary', 'goldsmith_product_countdown', 25 );
                    remove_action( 'woocommerce_single_product_summary', 'goldsmith_product_stock_progress_bar', 26 );
                    remove_action( 'woocommerce_single_product_summary', 'goldsmith_product_visitiors_message',39 );
                    remove_action( 'woocommerce_single_product_summary', 'goldsmith_product_trust_image', 100 );
                    remove_action( 'woocommerce_single_product_summary', 'goldsmith_product_trust_image', 100 );
                    goldsmith_product_summary_layouts_manager();

                    do_action( 'woocommerce_single_product_summary' );

                } else {
                    /**
                    * Hook: woocommerce_single_product_summary.
                    *
                    * @hooked woocommerce_template_single_title - 5
                    * @hooked woocommerce_template_single_rating - 10
                    * @hooked woocommerce_template_single_price - 10
                    * @hooked woocommerce_template_single_excerpt - 20
                    * @hooked woocommerce_template_single_add_to_cart - 30
                    * @hooked woocommerce_template_single_meta - 40
                    * @hooked woocommerce_template_single_sharing - 50
                    * @hooked WC_Structured_Data::generate_product_data() - 60
                    */
                    do_action( 'woocommerce_single_product_summary' );
                }
                ?>
            </div>
        </div>
    </div>
    <div class="content-container">
        <?php
        /**
        * Hook: woocommerce_after_single_product_summary.
        *
        * @hooked woocommerce_output_product_data_tabs - 10
        * @hooked woocommerce_upsell_display - 15
        * @hooked woocommerce_output_related_products - 20
        */
        do_action( 'woocommerce_after_single_product_summary' );
        ?>
    </div>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
