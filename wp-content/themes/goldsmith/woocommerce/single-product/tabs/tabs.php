<?php
/**
* Single Product tabs
*
* This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see     https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 3.8.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
* Filter tabs and allow third parties to add their own.
*
* Each tab is an array containing title, callback and priority.
*
* @see woocommerce_default_product_tabs()
*/
if ( '0' == goldsmith_settings( 'product_tabs_visibility', '1' ) ) {
    return;
}

$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );
if ( !empty( goldsmith_wc_extra_tabs_array() ) ) {
    $extra_tabs   = goldsmith_wc_extra_tabs_array();
    $product_tabs = array_merge($product_tabs, $extra_tabs);
}

if ( ! empty( $product_tabs ) ) {

    $count = $count2 = 0;
    $layout         = apply_filters('goldsmith_single_shop_layout', goldsmith_settings( 'single_shop_layout', 'full-width' ) );
    $stretch_temp   = apply_filters('goldsmith_single_shop_stretch_elementor_template', goldsmith_settings( 'single_shop_stretch_elementor_template', null ) );
    $tabs_type      = apply_filters( 'goldsmith_product_tabs_type', goldsmith_settings( 'product_tabs_type', 'tabs' ) );
    $accordion_type = 'accordion-2' == $tabs_type ? 'goldsmith-section goldsmith-accordion-after-summary' : 'goldsmith-summary-item goldsmith-accordion-in-summary';

    if ( 'accordion' == $tabs_type || 'accordion-2' == $tabs_type ) { ?>

        <div class="goldsmith-product-accordion-wrapper <?php echo esc_attr( $accordion_type ); ?>" id="accordionProduct">
            <?php if ( 'tabs' == $tabs_type && $stretch_temp ) { ?>
                <div class="row">
                    <div class="col-12 col-xl-6">
            <?php } ?>
            <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                <?php if ( !empty($product_tab['title']) ) { ?>
                    <div class="goldsmith-accordion-item">
                        <div class="goldsmith-accordion-header" data-id="accordion-<?php echo esc_attr( $key ); ?>">
                            <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                        </div>
                        <div data-id="accordion-<?php echo esc_attr( $key ); ?>" class="goldsmith-accordion-body">
                            <?php
                            if ( isset( $product_tab['callback'] ) ) {
                                call_user_func( $product_tab['callback'], $key, $product_tab );
                            } elseif( isset( $product_tab['content'] ) ){
                                echo do_shortcode($product_tab['content'] );
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>
            <?php endforeach; ?>

            <?php if ( 'stretch' == $layout && $stretch_temp ) { ?>
                    </div>
                    <div class="col-12 col-xl-6 goldsmith-section">
                        <?php echo goldsmith_print_elementor_templates( $stretch_temp, 'goldsmith-after-tabs', true ); ?>
                    </div>
                </div>
            <?php } ?>
        </div>

    <?php } else { ?>

        <div class="goldsmith-product-tabs-wrapper tabs-type-1 goldsmith-section" id="productTabContent">
        <?php if ( 'tabs' == $tabs_type && $stretch_temp ) { ?>
            <div class="row">
            <div class="col-12 col-xl-6">
        <?php } ?>
            <div class="goldsmith-product-tab-title">
                <?php foreach ( $product_tabs as $key => $product_tab ) :
                    $active = $count == 0 ? ' active' : '';
                    $count++;
                    if ( !empty($product_tab['title']) ) {
                    ?>
                    <div class="goldsmith-product-tab-title-item <?php echo esc_attr( $active ); ?>" data-id="tab-<?php echo esc_attr( $key ); ?>">
                        <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                    </div>
                    <?php } ?>
                <?php endforeach; ?>
                <?php do_action( 'goldsmith_product_extra_tabs_title' ); ?>
            </div>
            <div class="goldsmith-product-tabs-content">
                <?php foreach ( $product_tabs as $key => $product_tab ) :
                    $active = $count2 == 0 ? ' show active' : '';
                    $count2++;
                    if ( !empty($product_tab['title']) ) {
                    ?>
                    <div class="goldsmith-product-tab-content-item <?php echo esc_attr( $active ); ?>" data-id="tab-<?php echo esc_attr( $key ); ?>">
                        <?php
                        if ( isset( $product_tab['callback'] ) ) {
                            call_user_func( $product_tab['callback'], $key, $product_tab );
                        } elseif( isset( $product_tab['content'] ) ){
                            echo do_shortcode($product_tab['content'] );
                        }
                        ?>
                    </div>
                    <?php } ?>
                <?php endforeach; ?>

                <?php do_action( 'goldsmith_product_extra_tabs_content' ); ?>

                <?php do_action( 'woocommerce_product_after_tabs' ); ?>
            </div>
            <?php if ( 'tabs' == $tabs_type && $stretch_temp ) { ?>
                </div>
                <div class="col-12 col-xl-6">
                    <?php echo goldsmith_print_elementor_templates( $stretch_temp, 'goldsmith-after-tabs', true ); ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php
    }
}
?>
