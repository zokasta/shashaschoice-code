<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

get_header();

do_action( "goldsmith_before_wc_single" );

$layout    = apply_filters('goldsmith_product_layout', goldsmith_settings( 'single_shop_layout', 'full-width' ) );
$tabs_type = apply_filters( 'goldsmith_product_tabs_type', goldsmith_settings( 'product_tabs_type', 'tabs' ) );

if ( 'accordion' == $tabs_type ) {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 31 );
    add_action( 'woocommerce_after_single_product_summary', 'goldsmith_wc_move_product_reviews', 21 );
}

if ( 'custom' != goldsmith_settings( 'single_shop_summary_layout_type', 'default' ) ) {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );
}

// Elementor `single` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
    
    if ( 'left-sidebar' == $layout || 'right-sidebar' == $layout ) {

        wc_get_template_part( 'content', 'single-product-with-sidebar' );

    } elseif ( 'stretch' == $layout ) {

        while ( have_posts() ) {
            the_post();
            wc_get_template_part( 'content', 'single-product-showcase-stretch' );
        }

    } elseif ( 'showcase' == $layout ) {

        while ( have_posts() ) {
            the_post();
            wc_get_template_part( 'content', 'single-product-showcase-carousel' );
        }

    } else {

        ?>
        <!-- WooCommerce product page container -->
        <div id="nt-woo-single" class="nt-woo-single">
            <div class="nt-goldsmith-inner-container section-padding">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <?php
                            while ( have_posts() ) {
                                the_post();
                                wc_get_template_part( 'content', 'single-product' );
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

do_action( "goldsmith_after_wc_single" );

get_footer();

?>
