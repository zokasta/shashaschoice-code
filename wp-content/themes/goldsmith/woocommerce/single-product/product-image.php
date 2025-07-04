<?php
/**
* Single Product Image
*
* This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see     https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 9.0.0
*/

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
    return;
}

global $product;

$thumb_id = $product->get_image_id();
$columns  = apply_filters( 'woocommerce_product_thumbnails_columns', 7 );
$video    = get_post_meta( $product->get_id(), 'goldsmith_product_popup_video', true );
$layout   = get_post_meta( $product->get_id(), 'goldsmith_gallery', true );
$layout   = '' != $layout ? $layout : goldsmith_settings('product_thumbs_layout', 'default');

$wrapper_classes = apply_filters(
    'woocommerce_single_product_image_gallery_classes',
    array(
        'woocommerce-product-gallery',
        'goldsmith_product_images',
        'nav-vertical-center',
        'images_'.$layout,
        'woocommerce-product-gallery--' . ( $thumb_id ? 'with-images' : 'without-images' ),
        'woocommerce-product-gallery--columns-' . absint( $columns ),
        'images'
    )
);

?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">
    <?php
    if ( $video ) {
        echo '<a data-fancybox href="'.esc_url( $video ).'" class="goldsmith-product-video-button" data-product_id="'.$thumb_id.'"><i class="nt-icon-button-play-2"></i></a>';
    }
    ?>
    <div class="woocommerce-product-gallery__wrapper">
        <?php
        if ( $thumb_id ) {
            $html = wc_get_gallery_image_html( $thumb_id, true );
        } else {
            $wrapper_classname = $product->is_type( 'variable' ) && ! empty( $product->get_available_variations( 'image' ) ) ?
				'woocommerce-product-gallery__image woocommerce-product-gallery__image--placeholder' :
				'woocommerce-product-gallery__image--placeholder';
			$html  = sprintf( '<div class="%s">', esc_attr( $wrapper_classname ) );
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'goldsmith' ) );
			$html .= '</div>';
        }
        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $thumb_id );
        do_action( 'woocommerce_product_thumbnails' );
        ?>
    </div>

</div>
