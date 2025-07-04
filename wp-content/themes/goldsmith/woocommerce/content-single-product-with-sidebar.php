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

$layout = apply_filters('goldsmith_product_layout', goldsmith_settings( 'single_shop_layout', 'full-width' ) );
$column = is_active_sidebar( 'shop-single-sidebar' ) ? 'col-xl-9 summary-column' : 'col-12';

?>
<!-- WooCommerce product page container -->
<div id="nt-woo-single" class="nt-woo-single">

    <div class="nt-goldsmith-inner-container section-padding">
        <div class="container">
            <div class="row">

                <div class="<?php echo esc_attr( $column ); ?>">
                    <?php
                    while ( have_posts() ) {
                        the_post();
                        wc_get_template_part( 'content', 'single-product' );
                    }
                    ?>
                </div>

                <?php if ( is_active_sidebar( 'shop-single-sidebar' ) ) { ?>
                    <div id="nt-sidebar" class="col-12 col-lg-6 col-xl-3">
                        <div class="shop-sidebar nt-sidebar-inner">
                            <?php dynamic_sidebar( 'shop-single-sidebar' ); ?>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>
<?php

do_action( "goldsmith_after_wc_single" );

get_footer();

?>
