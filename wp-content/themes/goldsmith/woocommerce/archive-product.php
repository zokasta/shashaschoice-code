<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

// Elementor `archive` location
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'archive' ) ) {
    get_header();
}

// Elementor `archive` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
    $loop_mode  = woocommerce_get_loop_display_mode();
    $layout     = apply_filters('goldsmith_shop_layout', goldsmith_settings( 'shop_layout', 'left-sidebar' ) );
    $column     = ( $layout == 'left-sidebar' || $layout == 'right-sidebar' ) && is_active_sidebar( 'shop-page-sidebar' ) && 'subcategories' != $loop_mode ? 'col-lg-9' : 'col-lg-12';
    $container  = goldsmith_settings( 'shop_container_width', 'default' );
    $container  = 'default' == $container ? 'container' : 'container-fluid';
    $pagination = apply_filters('goldsmith_shop_pagination_type', goldsmith_settings('shop_paginate_type') );

    wp_enqueue_script( 'jquery-nice-select' );
    wp_enqueue_style( 'goldsmith-nice-select' );

    wp_enqueue_style( 'goldsmith-wc-masonry-layout' );
    wp_enqueue_style( 'goldsmith-wc-filter-top' );

    if ( !goldsmith_is_pjax() ) {
        get_header();
    }

    ?>
    <div class="nt-shop-page-wrapper">
        <div id="nt-shop-page" class="nt-shop-page nt-inner-page-wrapper">
            <?php
            /**
            * Hook: goldsmith_before_shop_content.
            *
            * @hooked goldsmith_wc_hero_section - 10
            * @hooked goldsmith_before_shop_elementor_templates - 15
            */
            do_action( 'goldsmith_before_shop_content' );
            ?>

            <div class="nt-goldsmith-inner-container shop-area section-padding loop-mode-<?php echo esc_attr( $loop_mode ); ?>">
                <div class="<?php echo esc_attr( $container ); ?>">

                    <div class="row">

                        <?php
                        if ( 'subcategories' != $loop_mode ) {
                            /**
                            * Hook: goldsmith_shop_sidebar.
                            *
                            * @hooked goldsmith_shop_sidebar - 10
                            */
                            do_action( 'goldsmith_shop_sidebar' );
                        }
                        ?>

                        <div class="<?php echo esc_attr( $column ); ?> goldsmith-products-column">
                            <?php
                            if ( 'subcategories' == $loop_mode ) {
                                remove_action( 'goldsmith_shop_before_loop', 'goldsmith_print_category_banner', 10 );
                                remove_action( 'goldsmith_shop_before_loop', 'goldsmith_shop_top_fast_filters', 12 );
                                remove_action( 'goldsmith_shop_before_loop', 'shop_loop_filters_layouts', 15 );
                                remove_action( 'goldsmith_shop_before_loop', 'goldsmith_shop_top_hidden_sidebar', 20 );
                            }
                            /**
                            * Hook: goldsmith_shop_before_loop.
                            *
                            * @hooked goldsmith_print_category_banner - 10
                            * @hooked shop_loop_filters_layouts - 15
                            * @hooked goldsmith_shop_top_hidden_sidebar - 20
                            */
                            do_action( 'goldsmith_shop_before_loop' );

                            /**
                            * Hook: goldsmith_shop_main_loop.
                            *
                            * @hooked goldsmith_shop_main_loop - 10
                            */
                            do_action( 'goldsmith_shop_main_loop' );

                            /**
                            * Hook: goldsmith_after_shop_loop.
                            *
                            * @hooked goldsmith_after_shop_loop_elementor_templates - 10
                            */
                            do_action( 'goldsmith_after_shop_loop' );
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <?php
        /**
        * Hook: goldsmith_after_shop_page.
        *
        * @hooked goldsmith_after_shop_page_elementor_templates - 10
        */
        do_action('goldsmith_after_shop_page');
        ?>
    </div>
    <?php
    if ( !goldsmith_is_pjax() ) {
        get_footer();
    }
}
// Elementor `archive` location
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'archive' ) ) {
    get_footer();
}
?>
