<?php

global $product;

$popup_video = get_post_meta( get_the_ID(), 'goldsmith_product_popup_video', true );
$video_type  = apply_filters( 'goldsmith_product_video_type', get_post_meta( get_the_ID(), 'goldsmith_product_video_type', true ) );
$tabs_type   = apply_filters( 'goldsmith_product_tabs_type', goldsmith_settings( 'product_tabs_type', 'tabs' ) );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

if ( 'accordion' == $tabs_type ) {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 31 );
    add_action( 'woocommerce_after_single_product_summary', 'goldsmith_wc_move_product_reviews', 21 );
}

if ( '1' == apply_filters( 'goldsmith_product_showcase_carousel_thumbs', goldsmith_settings('single_shop_showcase_carousel_thumbs', '0' ) ) ) {
    wp_enqueue_script( 'goldsmith-product-carousel-thumbs' );
} else {
    wp_enqueue_script( 'goldsmith-product-page-showcase-carousel' );
}

?>
<div id="nt-woo-single" class="nt-woo-single goldsmith-product-showcase-fullwidth">
    <div id="product-<?php the_ID(); ?>" <?php wc_product_class(  'goldsmith-single-product-type-2', $product ); ?>>
        <div class="goldsmith-product-showcase">

            <?php if ( '0' != goldsmith_settings( 'single_shop_hero_visibility', '1' ) ) { ?>
                <div class="goldsmith-product-breadcrumb-nav">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="goldsmith-flex goldsmith-align-center">
                                    <?php echo goldsmith_breadcrumbs(); ?>
                                    <?php goldsmith_single_product_nav_two(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php goldsmith_product_gallery_carousel_slider(); ?>

            <div class="goldsmith-product-summary nt-goldsmith-inner-container">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-6 pr-lg-35">
                            <?php
                            if (  $popup_video && 'popup' == $video_type ) {
                                echo '<a href="'.$popup_video.'" class="goldsmith-product-video-button goldsmith-in-content mfp-iframe"><i class="nt-icon-button-play-2"></i></a>';
                            }
                            woocommerce_template_single_title();
                            woocommerce_template_single_rating();
                            woocommerce_template_single_price();
                            woocommerce_template_single_add_to_cart();
                            woocommerce_template_single_excerpt();
                            ?>
                        </div>
                        <div class="col-12 col-lg-6">
                            <?php
                            /**
                            * Hook: woocommerce_single_product_summary.
                            *
                            * @hooked woocommerce_template_single_meta - 40
                            * @hooked woocommerce_template_single_sharing - 50
                            * @hooked WC_Structured_Data::generate_product_data() - 60
                            */
                            do_action( 'woocommerce_single_product_summary' );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="nt-goldsmith-inner-container pb-100">
        <div class="container">
            <div class="row goldsmith-row-after-summary">
                <div class="col-12">
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
        </div>
    </div>
</div>
