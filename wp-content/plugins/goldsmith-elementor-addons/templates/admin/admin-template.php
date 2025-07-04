<?php
/**
* Goldsmith Admin Page Template
*/


?>

    <div class="goldsmith-admin-wrapper">
        <div class="container">
            <div class="page-heading">
                <h1 class="page-title"><?php _e( 'Goldsmith Addons', 'goldsmith' ); ?></h1>
                <p class="page-description">
                    <?php _e( 'Premium & Advanced Essential Elements for Elementor', 'goldsmith' ); ?>
                </p>
            </div>
            <form class="goldsmith-form" method="post">

                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-widget-tab" data-toggle="tab" href="#nav-widget" role="tab" aria-controls="nav-widget" aria-selected="false"><?php _e( 'Widgets', 'goldsmith' ); ?></a>
                        <a class="nav-item nav-link" id="nav-general-tab" data-toggle="tab" href="#nav-general" role="tab" aria-controls="nav-general" aria-selected="true"><?php _e( 'Extra', 'goldsmith' ); ?></a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade show active" id="nav-widget" role="tabpanel" aria-labelledby="nav-widget-tab">
                        <div class="row widget-row">
                            <?php

                            $list = array(
                                'header-menu-simple',
                                'menu-vertical',
                                'button',
                                'animated-headline',
                                'swiper-template',
                                'slide-show',
                                'posts-base',
                                'breadcrumbs',
                                'image-slider',
                                'instagram-slider',
                                'fetatures-item',
                                'timer',
                                'contact-form-7',
                                'testimonials-slider',
                                'sidebar-widgets',
                                'vegas-slider',
                                'vegas-template',
                                'gallery',
                                // wocommerce widgets
                                'woo-tab-slider',
                                'woo-grid',
                                'woo-category-grid',
                                'woo-list',
                                'woo-slider',
                                'woo-gallery',
                                'woo-banner',
                                'woo-banner-slider',
                                'woo-banner-hero-slider',
                                'woo-ajax-search',
                                'woo-archive-description',
                                'woo-page-title',
                                'woo-categories',
                                'woo-product-item',
                                // single post data
                                'post-data',
                                // wocommerce product page widgets
                                'add-to-cart',
                                'breadcrumb',
                                'product-add-to-cart',
                                'product-additional-information',
                                'product-data-tabs',
                                'product-images',
                                'product-meta',
                                'product-price',
                                'product-rating',
                                'product-related',
                                'product-short-description',
                                'product-stock',
                                'product-title',
                                'product-upsell',
                                'single-elements'
                            );

                            foreach ( $list as $widget ) {

                                $option = 'disable_'.str_replace( '-', '_', $widget );
                                $name = mb_strtoupper( str_replace( '-', ' ', $widget ) );

                                add_option( $option, 0 );
                                if ( isset( $_POST[ $option ] ) ) {
                                    update_option( $option, $_POST[ $option ] );
                                }

                                 ?>

                                <div class="col-md-4">
                                    <div class="widget-toggle">
                                        <div class="custom-control custom-switch">
                                            <input type="hidden" name="<?php echo esc_attr( $option ); ?>" value="1">
                                            <input type="checkbox" class="custom-control-input" id="<?php echo esc_attr( $option ); ?>" name="<?php echo esc_attr( $option ); ?>" value="0" <?php checked( 0, get_option( $option ), true ); ?>>
                                            <label class="custom-control-label" for="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $name ); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-general" role="tabpanel" aria-labelledby="nav-general-tab">
                        <div class="row widget-row">
                            <div class="col-md-4">
                                <div class="widget-toggle">
                                    <?php
                                    add_option( 'disable_goldsmith_list_shortcodes', 0 );
                                    if ( isset( $_POST['disable_goldsmith_list_shortcodes'] ) ) {
                                        update_option( 'disable_goldsmith_list_shortcodes', $_POST['disable_goldsmith_list_shortcodes'] );
                                    }
                                    ?>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="disable_goldsmith_list_shortcodes" value="1">
                                        <input type="checkbox" class="custom-control-input" id="disable_goldsmith_list_shortcodes" name="disable_goldsmith_list_shortcodes" value="0" <?php checked( 0, get_option( 'disable_goldsmith_list_shortcodes' ), true ); ?>>
                                        <label class="custom-control-label" for="disable_goldsmith_list_shortcodes"><?php _e( 'Shortcode Creator', 'goldsmith' ); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="widget-toggle">
                                    <?php
                                    add_option( 'disable_goldsmith_wc_brands', 0 );
                                    if ( isset( $_POST['disable_goldsmith_wc_brands'] ) ) {
                                        update_option( 'disable_goldsmith_wc_brands', $_POST['disable_goldsmith_wc_brands'] );
                                    }
                                    ?>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="disable_goldsmith_wc_brands" value="1">
                                        <input type="checkbox" class="custom-control-input" id="disable_goldsmith_wc_brands" name="disable_goldsmith_wc_brands" value="0" <?php checked( 0, get_option( 'disable_goldsmith_wc_brands' ), true ); ?>>
                                        <label class="custom-control-label" for="disable_goldsmith_wc_brands"><?php _e( 'Goldsmith Brands', 'goldsmith' ); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="widget-toggle">
                                    <?php
                                    add_option( 'disable_goldsmith_wc_wishlist', 0 );
                                    if ( isset( $_POST['disable_goldsmith_wc_wishlist'] ) ) {
                                        update_option( 'disable_goldsmith_wc_wishlist', $_POST['disable_goldsmith_wc_wishlist'] );
                                    }
                                    ?>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="disable_goldsmith_wc_wishlist" value="1">
                                        <input type="checkbox" class="custom-control-input" id="disable_goldsmith_wc_wishlist" name="disable_goldsmith_wc_wishlist" value="0" <?php checked( 0, get_option( 'disable_goldsmith_wc_wishlist' ), true ); ?>>
                                        <label class="custom-control-label" for="disable_goldsmith_wc_wishlist"><?php _e( 'Goldsmith Wishlist', 'goldsmith' ); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="widget-toggle">
                                    <?php
                                    add_option( 'disable_goldsmith_wc_compare', 0 );
                                    if ( isset( $_POST['disable_goldsmith_wc_compare'] ) ) {
                                        update_option( 'disable_goldsmith_wc_compare', $_POST['disable_goldsmith_wc_compare'] );
                                    }
                                    ?>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="disable_goldsmith_wc_compare" value="1">
                                        <input type="checkbox" class="custom-control-input" id="disable_goldsmith_wc_compare" name="disable_goldsmith_wc_compare" value="0" <?php checked( 0, get_option( 'disable_goldsmith_wc_compare' ), true ); ?>>
                                        <label class="custom-control-label" for="disable_goldsmith_wc_compare"><?php _e( 'Goldsmith Compare', 'goldsmith' ); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="widget-toggle">
                                    <?php
                                    add_option( 'disable_goldsmith_wc_swatches', 0 );
                                    if ( isset( $_POST['disable_goldsmith_wc_swatches'] ) ) {
                                        update_option( 'disable_goldsmith_wc_swatches', $_POST['disable_goldsmith_wc_swatches'] );
                                    }
                                    ?>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="disable_goldsmith_wc_swatches" value="1">
                                        <input type="checkbox" class="custom-control-input" id="disable_goldsmith_wc_swatches" name="disable_goldsmith_wc_swatches" value="0" <?php checked( 0, get_option( 'disable_goldsmith_wc_swatches' ), true ); ?>>
                                        <label class="custom-control-label" for="disable_goldsmith_wc_swatches"><?php _e( 'Goldsmith Swatches', 'goldsmith' ); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="widget-toggle">
                                    <?php
                                    add_option( 'disable_goldsmith_wc_quickview', 0 );
                                    if ( isset( $_POST['disable_goldsmith_wc_quickview'] ) ) {
                                        update_option( 'disable_goldsmith_wc_quickview', $_POST['disable_goldsmith_wc_quickview'] );
                                    }
                                    ?>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="disable_goldsmith_wc_quickview" value="1">
                                        <input type="checkbox" class="custom-control-input" id="disable_goldsmith_wc_quickview" name="disable_goldsmith_wc_quickview" value="0" <?php checked( 0, get_option( 'disable_goldsmith_wc_quickview' ), true ); ?>>
                                        <label class="custom-control-label" for="disable_goldsmith_wc_quickview"><?php _e( 'Goldsmith Quick View', 'goldsmith' ); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="widget-toggle">
                                    <?php
                                    add_option( 'disable_goldsmith_wc_ajax_search', 0 );
                                    if ( isset( $_POST['disable_goldsmith_wc_ajax_search'] ) ) {
                                        update_option( 'disable_goldsmith_wc_ajax_search', $_POST['disable_goldsmith_wc_ajax_search'] );
                                    }
                                    ?>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="disable_goldsmith_wc_ajax_search" value="1">
                                        <input type="checkbox" class="custom-control-input" id="disable_goldsmith_wc_ajax_search" name="disable_goldsmith_wc_ajax_search" value="0" <?php checked( 0, get_option( 'disable_goldsmith_wc_ajax_search' ), true ); ?>>
                                        <label class="custom-control-label" for="disable_goldsmith_wc_ajax_search"><?php _e( 'Goldsmith WC Ajax Search', 'goldsmith' ); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="widget-toggle">
                                    <?php
                                    add_option( 'disable_goldsmith_popups_builder', 0 );
                                    if ( isset( $_POST['disable_goldsmith_popups_builder'] ) ) {
                                        update_option( 'disable_goldsmith_popups_builder', $_POST['disable_goldsmith_popups_builder'] );
                                    }
                                    ?>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="disable_goldsmith_popups_builder" value="1">
                                        <input type="checkbox" class="custom-control-input" id="disable_goldsmith_popups_builder" name="disable_goldsmith_popups_builder" value="0" <?php checked( 0, get_option( 'disable_goldsmith_popups_builder' ), true ); ?>>
                                        <label class="custom-control-label" for="disable_goldsmith_popups_builder"><?php _e( 'Goldsmith Popup Builder', 'goldsmith' ); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="widget-toggle">
                                    <?php
                                    add_option( 'disable_goldsmith_product360_builder', 0 );
                                    if ( isset( $_POST['disable_goldsmith_product360_builder'] ) ) {
                                        update_option( 'disable_goldsmith_product360_builder', $_POST['disable_goldsmith_product360_builder'] );
                                    }
                                    ?>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="disable_goldsmith_product360_builder" value="1">
                                        <input type="checkbox" class="custom-control-input" id="disable_goldsmith_product360_builder" name="disable_goldsmith_product360_builder" value="0" <?php checked( 0, get_option( 'disable_goldsmith_product360_builder' ), true ); ?>>
                                        <label class="custom-control-label" for="disable_goldsmith_product360_builder"><?php _e( 'Goldsmith Product 360 Degree', 'goldsmith' ); ?></label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="page-actions">
                    <div class="row">
                        <div class="col-sm-12 submit-container">
                            <?php wp_nonce_field( 'goldsmith_admin_nonce_field' ); ?>
                            <button type="submit" class="btn btn-primary"><?php _e( 'Save Settings', 'goldsmith' ); ?></button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
