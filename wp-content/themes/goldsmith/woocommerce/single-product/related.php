<?php
/**
* Related Products
*
* This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see         https://docs.woocommerce.com/document/template-structure/
* @package     WooCommerce\Templates
* @version     3.9.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( '1' != goldsmith_settings('single_shop_ralated_visibility', '1') ) {
    return;
}

$heading = goldsmith_settings('single_shop_related_title', '');
$heading = $heading ? esc_html( $heading ) : apply_filters( 'woocommerce_product_related_products_heading', esc_html__( 'Viewers Also Liked', 'goldsmith' ) );

$perview   = goldsmith_settings( 'shop_related_perview', 4 );
$mdperview = goldsmith_settings( 'shop_related_mdperview', 3 );
$smperview = goldsmith_settings( 'shop_related_smperview', 2 );
$sattr    = array();
$sattr[] .= '"speed":'.goldsmith_settings( 'shop_related_speed', 1000 );
$sattr[] .= '"slidesPerView":1,"slidesPerGroup":1';
$sattr[] .= '"spaceBetween":'.goldsmith_settings( 'shop_related_gap', 30 );
$sattr[] .= '"wrapperClass": "goldsmith-swiper-wrapper"';
$sattr[] .= '1' == goldsmith_settings( 'shop_related_loop', 0 ) ? '"loop":true' : '"loop":false';
$sattr[] .= '1' == goldsmith_settings( 'shop_related_autoplay', 1 ) ? '"autoplay":{"pauseOnMouseEnter":true,"disableOnInteraction":false}' : '"autoplay":false';
$sattr[] .= '1' == goldsmith_settings( 'shop_related_mousewheel', 0 ) ? '"mousewheel":true' : '"mousewheel":false';
$sattr[] .= '1' == goldsmith_settings( 'shop_related_freemode', 1 ) ? '"freeMode":true' : '"freeMode":false';
$sattr[] .= '"navigation": {"nextEl": ".related-slider-nav .goldsmith-swiper-next","prevEl": ".related-slider-nav .goldsmith-swiper-prev"}';
$sattr[] .= '"breakpoints": {"0": {"slidesPerView": '.$smperview.',"slidesPerGroup":'.$smperview.'},"768": {"slidesPerView": '.$mdperview.',"slidesPerGroup":'.$mdperview.'},"1024": {"slidesPerView": '.$perview.',"slidesPerGroup":'.$perview.'}}';
$rtl = is_rtl() ? '-rtl' : '';

$tag = goldsmith_settings( 'product_related_title_tag', 'h4' );

if ( $related_products ) {
    ?>
    <div class="goldsmith-product-related goldsmith-related-product-wrapper goldsmith-section">
        <div class="section-title-wrapper">
            <?php if ( $heading ) : ?>
                <<?php echo esc_attr( $tag ); ?> class="section-title"><?php echo esc_html( $heading ); ?></<?php echo esc_attr( $tag ); ?>>
            <?php endif; ?>
            <div class="related-slider-nav">
            	<div class="goldsmith-slide-nav goldsmith-swiper-prev"></div>
                <div class="goldsmith-slide-nav goldsmith-swiper-next"></div>
            </div>
        </div>
        <div class="goldsmith-wc-swipper-wrapper woocommerce">
            <div class="goldsmith-swiper-slider goldsmith-swiper-container" data-swiper-options='{<?php echo implode( ',',$sattr ); ?>}'>
                <div class="goldsmith-swiper-wrapper">

                    <?php foreach ( $related_products as $related_product ) : ?>
                        <div class="swiper-slide">
                            <?php
                            $post_object = get_post( $related_product->get_id() );

                            setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

                            wc_get_template_part( 'content', 'product' );
                            ?>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

wp_reset_postdata();
