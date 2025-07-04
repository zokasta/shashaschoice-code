<?php

/**
 *
 * @package WordPress
 * @subpackage goldsmith
 * @since Goldsmith 1.0
 *
**/

define('GOLDSMITH_DIRECTORY_URI', get_template_directory_uri());
define('GOLDSMITH_DIRECTORY', get_template_directory());

/*************************************************
## GOOGLE FONTS
*************************************************/
if ( ! function_exists( 'goldsmith_fonts_url' ) ) {
    function goldsmith_fonts_url()
    {
        $fonts_url = '';
        $jost      = _x( 'on', 'Jost font: on or off', 'goldsmith' );
        $manrope   = _x( 'on', 'Manrope font: on or off', 'goldsmith' );

        if (  'off' !== $jost || 'off' !== $manrope ) {

            $font_families = array();

            if ( 'off' !== $jost ) {
                $font_families[] = 'Jost:300,400,500,600,700';
            }

            if ( 'off' !== $manrope ) {
                $font_families[] = 'Manrope:400,500,600,700,800';
            }

            $query_args = array(
                'family' => urlencode( implode( '|', $font_families ) ),
                'subset' => urlencode( 'latin,latin-ext' ),
                'display' => urlencode( 'swap' ),
            );

            $fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
        }

        return esc_url_raw( $fonts_url );
    }
}

/*************************************************
## STYLES AND SCRIPTS
*************************************************/

function goldsmith_theme_scripts()
{
    $rtl = is_rtl() ? 'rtl/' : '';
    // theme inner pages files

    wp_enqueue_style( 'goldsmith-fonts', goldsmith_fonts_url(), array(), null );

    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style( 'goldsmith-wc-page-hero', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-page-hero.css',false, '1.0');
    }
    // bootstrap
    wp_enqueue_style( 'bootstrap-grid', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'bootstrap-light.min.css', false, '1.0' );
    wp_enqueue_style( 'goldsmith-default', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'default.css', false, '1.0' );
    // goldsmith-framework-style
    wp_enqueue_style( 'goldsmith-framework-style', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'framework-style.css', false, '1.0' );
    // goldsmith-main-style
    wp_enqueue_style( 'goldsmith-magnific', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'style-magnific.css', false, '1.0' );
    wp_register_style( 'goldsmith-nice-select', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'style-nice-select.css', false, '1.0' );
    wp_register_style( 'goldsmith-slick', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'style-slick.css', false, '1.0' );
    wp_enqueue_style( 'goldsmith-swiper', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'style-swiper.css', false, '1.0' );
    wp_register_style( 'goldsmith-blog-post', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'style-blog-post-item.css', false, '1.0' );
    wp_enqueue_style( 'goldsmith-side-panel-cart', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'style-side-panel-cart.css', false, '1.0' );
    wp_enqueue_style( 'goldsmith-side-panel-account', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'style-side-panel-account.css', false, '1.0' );
    wp_enqueue_style( 'goldsmith-side-panel', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'style-side-panel.css', false, '1.0' );
    wp_enqueue_style( 'goldsmith-style', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'style.css', false, '1.0' );
    wp_enqueue_style( 'goldsmith-main-style', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'style-main.css', false, '1.0' );

    wp_register_style( 'goldsmith-deals', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'product-deals.css', false, '1.0' );

    // lazy load
    wp_register_script( 'lazyload', GOLDSMITH_DIRECTORY_URI. '/js/lazy/lazyload.min.js', array( 'jquery' ), '1.0', false );
    // nice-select
    wp_register_script( 'jquery-nice-select', GOLDSMITH_DIRECTORY_URI . '/js/nice-select/jquery-nice-select.min.js', array( 'jquery' ), '1.0', true );
    // slick slider
    wp_register_script( 'slick', GOLDSMITH_DIRECTORY_URI. '/js/slick/slick.min.js', array( 'jquery' ), '1.0', true );
    // magnific
    wp_register_script( 'magnific', GOLDSMITH_DIRECTORY_URI. '/js/magnific/magnific-popup.min.js', array( 'jquery' ), '1.0', true );

    // swiper
    wp_register_script( 'goldsmith-swiper', GOLDSMITH_DIRECTORY_URI . '/js/swiper/swiper-bundle.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_script( 'goldsmith-swiper' );
    // jquery-cookie
    wp_register_script( 'jquery-cookie', GOLDSMITH_DIRECTORY_URI . '/js/jquery/jquery-cookie.min.js', array( 'jquery' ), '1.0', true );
    // sliding-menu
    wp_register_script( 'sliding-menu', GOLDSMITH_DIRECTORY_URI . '/js/sliding-menu/sliding-menu.js', array( 'jquery' ), '1.0', true );
    // jquery-countdown
    wp_register_script( 'jquery-countdown', GOLDSMITH_DIRECTORY_URI. '/js/countdown/jquery.countdown.min.js', array( 'jquery' ), '1.0', true );
    wp_register_script( 'goldsmith-countdown', GOLDSMITH_DIRECTORY_URI. '/js/countdown/script.js', array( 'jquery' ), '1.0', true );

    wp_enqueue_script( 'goldsmith-main', GOLDSMITH_DIRECTORY_URI . '/js/scripts.js', array( 'jquery' ), '1.0', true );
    wp_localize_script( 'goldsmith-main', 'goldsmith_vars', goldsmith_theme_all_settings() );

    // select2-full
    wp_register_style( 'select2-full', GOLDSMITH_DIRECTORY_URI . '/js/select2/select2.min.css' );
    wp_register_script( 'select2-full', GOLDSMITH_DIRECTORY_URI . '/js/select2/select2.full.min.js', array( 'jquery' ), '1.0', true );

    if ( class_exists( 'WooCommerce' ) ) {
        $is_woo = is_woocommerce() || is_tax( 'goldsmith_product_brands' ) ? true : false;
        wp_enqueue_style( 'goldsmith-product-box-style', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-product-box-style.css',false, '1.0');

        if ( is_cart() ) {
            wp_enqueue_style( 'goldsmith-wc-cart-page', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-cart-page.css',false, '1.0');
        }
        if ( is_checkout() ) {
            wp_enqueue_style( 'goldsmith-wc-checkout-page', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-checkout-page.css',false, '1.0');

            $goldsmith_checkout_type = apply_filters( 'goldsmith_checkout_enable_multistep', goldsmith_settings( 'checkout_enable_multistep', 'default' ) );
            if ( 'multisteps' == $goldsmith_checkout_type ) {
                wp_enqueue_script( 'goldsmith-multi-step-checkout', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/multi-step-checkout.js', array('jquery'), '1.0', true);
            }
        }

        wp_register_style( 'goldsmith-wc-account-page', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-account-page.css',false, '1.0');

        wp_enqueue_style( 'goldsmith-wc-popup-notices', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-popup-notices.css',false, '1.0');

        if ( '1' == goldsmith_settings( 'quick_view_visibility', '1' ) ) {
            wp_enqueue_style( 'goldsmith-wc-quick-view', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-quick-view.css',false, '1.0');
        }

        wp_enqueue_style( 'goldsmith-wc-ajax-search', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-ajax-search.css',false, '1.0');
        if ( '1' == goldsmith_settings( 'free_shipping_progressbar_visibility', '1' ) ) {
            wp_enqueue_style( 'goldsmith-wc-free-shipping-progressbar', GOLDSMITH_DIRECTORY_URI . '/css/woocommerce-free-shipping-progressbar.css',false, '1.0');
        }
        wp_enqueue_style( 'goldsmith-wc-quantity', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-quantity.css',false, '1.0');
        wp_enqueue_style( 'goldsmith-wc-stars', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-stars.css',false, '1.0');

        $layout = apply_filters('goldsmith_shop_layout', goldsmith_settings( 'shop_layout', 'left-sidebar' ) );
        $pagination = apply_filters('goldsmith_shop_pagination_type', goldsmith_settings('shop_paginate_type') );

        if ( $is_woo ) {

            if ( 'no-sidebar' != $layout ) {
                wp_enqueue_style( 'goldsmith-wc-sidebar', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-sidebar.css',false, '1.0');
            }
            if ( $pagination == 'pagination' || $pagination == 'ajax-pagination' ) {
                wp_enqueue_style( 'goldsmith-wc-pagination', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-pagination.css',false, '1.0');
            }
            if ( $pagination == 'infinite' ) {
                wp_enqueue_script( 'goldsmith-infinite-scroll', GOLDSMITH_DIRECTORY_URI. '/woocommerce/assets/js/infinite-scroll.js', array( 'jquery' ), false, '1.0' );
            }
            if ( $pagination == 'loadmore' ) {
                wp_enqueue_script( 'goldsmith-load-more', GOLDSMITH_DIRECTORY_URI. '/woocommerce/assets/js/load_more.js', array( 'jquery' ), false, '1.0' );
            }

            if ( !is_product() ) {
                wp_register_style( 'goldsmith-wc-masonry-layout', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-masonry-layout.css',false, '1.0');
                wp_enqueue_style( 'goldsmith-wc-filter-top', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-filter-top.css',false, '1.0');
            }
        }

        if ( '1' == goldsmith_settings('shop_fast_filter_visibility', '1' ) ) {
            wp_enqueue_style( 'goldsmith-wc-fast-filters', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-fast-filters.css',false, '1.0');
        }
        wp_enqueue_style( 'goldsmith-wc', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/css/'.$rtl.'woocommerce-general.css',false, '1.0');

        wp_enqueue_script( 'goldsmith-wc', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/woocommerce-general.js', array('jquery'), '1.0', true);

        if ( '1' == goldsmith_settings('shop_fast_filter_visibility', '1' ) ) {
            wp_enqueue_script( 'goldsmith-shop-cats-slider', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/shop-cats-slider.js', array('jquery'), '1.0', true);
        }

        wp_enqueue_script( 'goldsmith-wc-ajax-addtocart', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/ajax-addtocart.js', array('jquery'), '1.0', true);

        if ( '1' == goldsmith_settings('cart_limited_timer_visibility', '1' ) ) {
            wp_enqueue_script( 'goldsmith-sidepanel-timer', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/sidepanel-timer.js', array('jquery'), '1.0', true);
        }

        if ( '1' == goldsmith_settings( 'quick_shop_visibility', '1' ) ) {
            wp_enqueue_script( 'wc-add-to-cart-variation' );
            wp_enqueue_style( 'goldsmith-wc-quick-shop', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-quick-shop.css',false, '1.0');
            wp_enqueue_script( 'goldsmith-wc-ajax-quick-shop', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/quick-shop.js', array('jquery'), '1.0', true);
        }

        wp_register_style( 'goldsmith-wc-custom-reviews-slider', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-custom-reviews-slider.css',false, '1.0');

        wp_enqueue_style( 'goldsmith-wc-product-variatons', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-product-variatons.css',false, '1.0');
        wp_enqueue_style( 'goldsmith-wc-product-page', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-product-page.css',false, '1.0');

        if ( is_product() ) {
            // fancybox
            wp_enqueue_style( 'fancybox', GOLDSMITH_DIRECTORY_URI . '/js/fancybox/jquery.fancybox.css', false, '1.0' );
            wp_enqueue_script( 'fancybox', GOLDSMITH_DIRECTORY_URI . '/js/fancybox/jquery.fancybox.min.js', array(), '1.0', true );

            wp_enqueue_script( 'goldsmith-product-page', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/product-page.js', array('jquery'), '1.0', true);
            wp_register_script( 'flex-thumbs', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/product-page-flex-thumbs.js', array('jquery'), '1.0', true);

            if ( '1' == goldsmith_settings( 'goldsmith_product_bottom_popup_cart', '1' ) ) {
                wp_enqueue_script( 'goldsmith-product-bottom-popup-cart', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/product-bottom-popup-cart.js', array('jquery'), '1.0', true);
            }

            wp_register_script( 'goldsmith-product-page-carousel', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/product-page-gallery-carousel.js', array('jquery'), '1.0', true);
            wp_register_script( 'goldsmith-product-page-showcase-carousel', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/product-page-showcase-carousel.js', array('jquery'), '1.0', true);
            wp_register_script( 'goldsmith-product-carousel-thumbs', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/product-page-showcase-carousel-thumbs.js', array('jquery'), '1.0', true);

            if ( class_exists( 'Ivole' ) && comments_open() && '1' == goldsmith_settings('single_shop_review_visibility', '1' ) ) {
                wp_enqueue_style( 'goldsmith-wc-custom-reviews', GOLDSMITH_DIRECTORY_URI . '/css/'.$rtl.'woocommerce-custom-reviews.css',false, '1.0');
            }
        }

        if ( function_exists('goldsmith_pjax') && ( is_shop() || is_product_category() || is_product_tag() || is_tax( 'goldsmith_product_brands' ) ) ) {
            if ( '1' == goldsmith_settings('shop_ajax_filter', '1' ) ) {
                wp_register_script( 'pjax', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/pjax.min.js', array('jquery'), '1.0', true );
                wp_register_script( 'shopAjaxFilter', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/shopAjaxFilter.js', array('jquery', 'pjax'), '1.0', true );
            }
        }

        wp_register_script( 'goldsmith-quantity-button', GOLDSMITH_DIRECTORY_URI . '/woocommerce/assets/js/quantity_button.js', array('jquery'), '1.0.0', true );
    }

    if ( '1' == goldsmith_settings( 'theme_blocks_styles', '0' ) ) {
        wp_dequeue_style( 'cr-badges-css' );
        wp_deregister_style( 'cr-badges-css' );
        wp_dequeue_style( 'ivole-frontend-css' );
        wp_deregister_style( 'ivole-frontend-css' );
        wp_dequeue_style( 'wc-blocks-vendors-style' );
        wp_dequeue_style( 'wc-blocks-editor' );
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
        wp_deregister_style( 'wc-blocks-vendors-style' );
        wp_deregister_style( 'wc-blocks-editor' );
        wp_deregister_style( 'wp-block-library' );
        wp_deregister_style( 'wp-block-library-theme' );
        wp_deregister_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
    }
}
add_action( 'wp_enqueue_scripts', 'goldsmith_theme_scripts', 9999999 );



/*************************************************
## ADMIN STYLE AND SCRIPTS
*************************************************/

function goldsmith_admin_scripts()
{
    wp_register_style( 'select2-full', GOLDSMITH_DIRECTORY_URI . '/js/select2/select2.min.css' );
    wp_register_script( 'select2-full', GOLDSMITH_DIRECTORY_URI . '/js/select2/select2.full.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'goldsmith-framework-admin', GOLDSMITH_DIRECTORY_URI . '/js/framework-admin.js', array('jquery', 'wp-color-picker' ) );
}
add_action('admin_enqueue_scripts', 'goldsmith_admin_scripts');


// Theme admin menu
require_once get_parent_theme_file_path( '/inc/core/merlin/admin-menu.php' );

// Template-functions
include get_template_directory() . '/inc/template-functions.php';

// Theme parts
include GOLDSMITH_DIRECTORY . '/inc/template-parts/menu.php';
include GOLDSMITH_DIRECTORY . '/inc/template-parts/post-formats.php';
include GOLDSMITH_DIRECTORY . '/inc/template-parts/single-post-formats.php';
include GOLDSMITH_DIRECTORY . '/inc/template-parts/paginations.php';
include GOLDSMITH_DIRECTORY . '/inc/template-parts/comment-parts.php';
include GOLDSMITH_DIRECTORY . '/inc/template-parts/small-parts.php';
include GOLDSMITH_DIRECTORY . '/inc/template-parts/header-parts.php';
include GOLDSMITH_DIRECTORY . '/inc/template-parts/footer-parts.php';
include GOLDSMITH_DIRECTORY . '/inc/template-parts/page-hero.php';
include GOLDSMITH_DIRECTORY . '/inc/template-parts/breadcrumbs.php';
include GOLDSMITH_DIRECTORY . '/inc/template-parts/custom-style.php';

// TGM plugin activation
include GOLDSMITH_DIRECTORY . '/inc/core/class-tgm-plugin-activation.php';

// Redux theme options panel
include GOLDSMITH_DIRECTORY . '/inc/core/theme-options/options.php';

// WooCommerce init
if ( class_exists( 'WooCommerce' ) ) {
    include GOLDSMITH_DIRECTORY . '/woocommerce/init.php';
}

/*************************************************
## THEME SETUP
*************************************************/

if ( ! isset( $content_width ) ) {
    $content_width = 960;
}

function goldsmith_theme_setup()
{
    /*
    * This theme styles the visual editor to resemble the theme style,
    * specifically font, colors, icons, and column width.
    */
    add_editor_style( 'custom-editor-style.css' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );
    add_image_size( 'goldsmith-quickview', 60, 60, true );
    add_image_size( 'goldsmith-panel', 80, 80, true );
    add_image_size( 'goldsmith-mini', 300, 300, true );
    add_image_size( 'goldsmith-medium', 370, 370, true );
    add_image_size( 'goldsmith-square', 500, 500, true );
    add_image_size( 'goldsmith-grid', 767, 767, true );
    /*
    * Enable support for Post Thumbnails on posts and pages.
    *
    * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
    */
    add_theme_support( 'post-thumbnails' );

    // theme supports
    add_theme_support( 'title-tag' );
    add_theme_support( 'custom-background' );
    add_theme_support( 'custom-header' );
    add_theme_support( 'html5', array( 'search-form' ) );
    add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );
    remove_theme_support( 'widgets-block-editor' );
    add_filter( 'use_widgets_block_editor', '__return_false' );
    add_filter('use_block_editor_for_post', '__return_false', 10);
    add_filter('use_block_editor_for_page', '__return_false', 10);

    // Make theme available for translation
    // Translations can be filed in the /languages/ directory
    load_theme_textdomain( 'goldsmith', GOLDSMITH_DIRECTORY . '/languages' );
    if ( class_exists('Redux' ) ) {
        register_nav_menus(array(
            'header_menu' => esc_html__( 'Header Menu', 'goldsmith' ),
            'sidebar_menu' => esc_html__( 'Sidebar Menu', 'goldsmith' ),
            'sidebar_second_menu' => esc_html__( 'Sidebar Header Second Menu ( For Sidebar Header )', 'goldsmith' ),
            'left_menu' => esc_html__( 'Left Menu ( for logo center )', 'goldsmith' ),
            'rigt_menu' => esc_html__( 'Right Menu ( for logo center )', 'goldsmith' ),
            'header_mini_menu' => esc_html__( 'Secondary Mini Menu', 'goldsmith' ),
            'header_lang_menu' => esc_html__( 'Header Lang Menu', 'goldsmith' ),
            'mobile_bottom_menu' => esc_html__( 'Mobile Bottom Menu', 'goldsmith' ),
        ) );
    } else {
        register_nav_menus(array(
            'header_menu' => esc_html__( 'Header Menu', 'goldsmith' )
        ) );
    }
}
add_action( 'after_setup_theme', 'goldsmith_theme_setup' );

// disable srcset on frontend
if ( !function_exists('goldsmith_disable_wp_responsive_images') ){
    function goldsmith_disable_wp_responsive_images() {
        return 1;
    }
    add_filter('max_srcset_image_width', 'goldsmith_disable_wp_responsive_images');
}

add_filter('wpcf7_autop_or_not', '__return_false');

/*************************************************
## WIDGET COLUMNS
*************************************************/

function goldsmith_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__( 'Blog Sidebar', 'goldsmith' ),
        'id' => 'sidebar-1',
        'description' => esc_html__( 'These widgets for the Blog page.', 'goldsmith' ),
        'before_widget' => '<div class="nt-sidebar-inner-widget widget blog-sidebar-widget mb-40 %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="nt-sidebar-inner-widget-title blog-sidebar-title"><h5>',
        'after_title' => '</h5></div>'
    ));
    if ( class_exists( 'Redux' ) ) {
        if ( 'full-width' != goldsmith_settings( 'goldsmith_page_layout' ) ) {
            register_sidebar(array(
                'name' => esc_html__( 'Default Page Sidebar', 'goldsmith' ),
                'id' => 'goldsmith-page-sidebar',
                'description' => esc_html__( 'These widgets for the Default Page pages.', 'goldsmith' ),
                'before_widget' => '<div class="nt-sidebar-inner-widget widget blog-sidebar-widget mb-40 %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="nt-sidebar-inner-widget-title blog-sidebar-title"><h5>',
                'after_title' => '</h5></div>'
            ));
        }
        if ( 'full-width' != goldsmith_settings( 'archive_layout', 'full-width' ) ) {
            register_sidebar(array(
                'name' => esc_html__( 'Archive Sidebar', 'goldsmith' ),
                'id' => 'goldsmith-archive-sidebar',
                'description' => esc_html__( 'These widgets for the Archive pages.', 'goldsmith' ),
                'before_widget' => '<div class="nt-sidebar-inner-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="nt-sidebar-inner-widget-title blog-sidebar-title"><h5>',
                'after_title' => '</h5></div>'
            ));
        }
        if ( 'full-width' != goldsmith_settings( 'search_layout', 'full-width' ) ) {
            register_sidebar(array(
                'name' => esc_html__( 'Search Sidebar', 'goldsmith' ),
                'id' => 'goldsmith-search-sidebar',
                'description' => esc_html__( 'These widgets for the Search pages.', 'goldsmith' ),
                'before_widget' => '<div class="nt-sidebar-inner-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="nt-sidebar-inner-widget-title blog-sidebar-title"><h5>',
                'after_title' => '</h5></div>'
            ));
        }
        if ( 'full-width' != goldsmith_settings( 'single_layout', 'right-sidebar' ) ) {
            register_sidebar(array(
                'name' => esc_html__( 'Blog Single Sidebar', 'goldsmith' ),
                'id' => 'goldsmith-single-sidebar',
                'description' => esc_html__( 'These widgets for the Blog single page.', 'goldsmith' ),
                'before_widget' => '<div class="nt-sidebar-inner-widget widget blog-sidebar-widget mb-40 %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="nt-sidebar-inner-widget-title blog-sidebar-title"><h5>',
                'after_title' => '</h5></div>'
            ));
        }
    } // end if redux exists
} // end goldsmith_widgets_init
add_action( 'widgets_init', 'goldsmith_widgets_init' );


/*************************************************
## INCLUDE THE TGM_PLUGIN_ACTIVATION CLASS.
*************************************************/

function goldsmith_register_required_plugins()
{
    $plugins = array(
        array(
            'name' => esc_html__( 'Contact Form 7', 'goldsmith' ),
            'slug' => 'contact-form-7'
        ),
        array(
            'name' => esc_html__( 'Safe SVG', 'goldsmith' ),
            'slug' => 'safe-svg'
        ),
        array(
            'name' => esc_html__( 'Theme Options Panel', 'goldsmith' ),
            'slug' => 'redux-framework',
            'required' => true
        ),
        array(
            'name' => esc_html__( 'Elementor', 'goldsmith' ),
            'slug' => 'elementor',
            'required' => true
        ),
        array(
            'name' => esc_html__( 'WooCommerce', 'goldsmith' ),
            'slug' => 'woocommerce',
            'required' => true
        ),
        array(
            'name' => esc_html__( 'Customer Reviews for WooCommerce', 'goldsmith' ),
            'slug' => 'customer-reviews-woocommerce',
            'required' => false
        ),
        array(
            'name' => esc_html__( 'WPC Smart Compare for WooCommerce', 'goldsmith' ),
            'slug' => 'woo-smart-compare',
            'required' => false
        ),
        array(
            'name' => esc_html__( 'WPC Bought Together for WooCommerce', 'goldsmith' ),
            'slug' => 'woo-bought-together',
            'required' => false
        ),
        array(
            'name' => esc_html__( 'Envato Auto Update Theme', 'goldsmith' ),
            'slug' => 'envato-market',
            'source' => 'https://ninetheme.com/documentation/plugins/envato-market.zip',
            'required' => false
        ),
        array(
            'name' => esc_html__( 'Goldsmith Elementor Addons', 'goldsmith' ),
            'slug' => 'goldsmith-elementor-addons',
            'source' => GOLDSMITH_DIRECTORY . '/plugins/goldsmith-elementor-addons.zip',
            'required' => true,
            'version' => '1.1.5'
        )
        // end plugins list
    );

    $config = array(
        'id' => 'tgmpa',
        'default_path' => '',
        'menu' => 'tgmpa-install-plugins',
        'parent_slug' => apply_filters( 'ninetheme_parent_slug', 'themes.php' ),
        'has_notices' => true,
        'dismissable' => true,
        'dismiss_msg' => '',
        'is_automatic' => true,
        'message' => ''
    );

    tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'goldsmith_register_required_plugins' );



/*************************************************
## ONE CLICK DEMO IMPORT
*************************************************/


/*************************************************
## THEME SETUP WIZARD
    https://github.com/richtabor/MerlinWP
*************************************************/

require_once get_parent_theme_file_path( '/inc/core/merlin/class-merlin.php' );
require_once get_parent_theme_file_path( '/inc/core/demo-wizard-config.php' );

function goldsmith_merlin_local_import_files() {
    $rtl = is_rtl() ? '-rtl' : '';
    return array(
        array(
            'landing_page' => 'https://landing.ninetheme.com/goldsmith/',
        ),
        array(
            'import_file_name' => esc_html__( 'Home 1','goldsmith' ),
            'import_preview_url' => 'https://ninetheme.com/themes/goldsmith/v1/',
            // XML data
            'local_import_file' => get_parent_theme_file_path( 'inc/core/merlin/demodata/demo1/data'.$rtl.'.xml' ),
            // Widget data
            'local_import_widget_file' => get_parent_theme_file_path( 'inc/core/merlin/demodata/demo1/widgets.wie' ),
            // Theme options
            'local_import_redux' => array(
                array(
                    'file_path' => trailingslashit( GOLDSMITH_DIRECTORY ). 'inc/core/merlin/demodata/demo1/redux.json',
                    'option_name' => 'goldsmith'
                )
            )
        )
    );
}
add_filter( 'merlin_import_files', 'goldsmith_merlin_local_import_files' );


function goldsmith_disable_size_images_during_import() {
    add_filter( 'intermediate_image_sizes_advanced', function( $sizes ){
        unset( $sizes['medium'] );
        unset( $sizes['large'] );
        unset( $sizes['1536x1536'] );
        unset( $sizes['2048x2048'] );
        unset( $sizes['goldsmith-quickview'] );
        unset( $sizes['goldsmith-panel'] );
        unset( $sizes['goldsmith-grid'] );
        unset( $sizes['goldsmith-single'] );
        unset( $sizes['shop_single'] );
        unset( $sizes['woocommerce_single'] );
        unset( $sizes['woocommerce_gallery_thumbnail'] );
        return $sizes;
    });
}
add_action( 'import_start', 'goldsmith_disable_size_images_during_import');


/**
 * Execute custom code after the whole import has finished.
 */
function goldsmith_merlin_after_import_setup() {
    // Assign menus to their locations.
    $primary   = get_term_by( 'name', 'Menu 1', 'nav_menu' );
    $left_menu = get_term_by( 'name', 'Left Menu', 'nav_menu' );
    $rigt_menu = get_term_by( 'name', 'Right Menu', 'nav_menu' );
    $mini_menu = get_term_by( 'name', 'Header Secondary Mini Menu', 'nav_menu' );

    wp_update_term_count( $primary->term_id, 'nav_menu', true );
    wp_update_term_count( $left_menu->term_id, 'nav_menu', true );
    wp_update_term_count( $rigt_menu->term_id, 'nav_menu', true );
    wp_update_term_count( $mini_menu->term_id, 'nav_menu', true );

    set_theme_mod( 'nav_menu_locations', array(
        'header_menu' => $primary->term_id,
        'left_menu'   => $left_menu->term_id,
        'rigt_menu'   => $rigt_menu->term_id,
        'mini_menu'   => $mini_menu->term_id
    ));

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home - Left Sidebar' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

    if ( did_action( 'elementor/loaded' ) ) {
        // update some default elementor global settings after setup theme
        $kit = get_page_by_title( 'Imported Kit', OBJECT, 'elementor_library' );
        update_option( 'elementor_active_kit', $kit->ID );
        update_option( 'elementor_experiment-e_font_icon_svg', 'active' );
        update_option( 'elementor_experiment-container', 'active' );
        update_option( 'elementor_experiment-e_dom_optimization', 'active' );
        update_option( 'elementor_experiment-e_optimized_assets_loading', 'active' );
        update_option( 'elementor_experiment-a11y_improvements', 'active' );
        update_option( 'elementor_experiment-additional_custom_breakpoints', 'active' );
        update_option( 'elementor_experiment-e_import_export', 'active' );
        update_option( 'elementor_experiment-e_hidden_wordpress_widgets', 'active' );
        update_option( 'elementor_experiment-landing-pages', 'inactive' );
        update_option( 'elementor_experiment-elements-color-picker', 'active' );
        update_option( 'elementor_experiment-favorite-widgets', 'active' );
        update_option( 'elementor_experiment-admin-top-bar', 'active' );
        update_option( 'elementor_disable_color_schemes', 'yes' );
        update_option( 'elementor_disable_typography_schemes', 'yes' );
        update_option( 'elementor_global_image_lightbox', 'yes' );
        update_option( 'elementor_load_fa4_shim', 'yes' );

        $cpt_support = get_option( 'elementor_cpt_support' );
        if ( !is_array( $cpt_support ) || ! in_array( ['goldsmith_popups','post','page','product'], $cpt_support ) ) {
            $cpt_support = ['goldsmith_popups','post','page','product'];
            update_option( 'elementor_cpt_support', $cpt_support );
        }
    }

    if ( class_exists( 'WPCleverWoosc' ) ) {
        $woosc_support = get_option('woosc_settings');
        if ( is_array( $woosc_support ) ) {
            $woosc_support['button_type'] = 'link';
            $woosc_support['quick_table_enable'] = 'no';
            $woosc_support['button_archive'] = '0';
            $woosc_support['button_single'] = '0';
            $woosc_support['open_button'] = '.open-compare-btn';
            update_option( 'woosc_settings', $woosc_support );
        } else {
            $woosc_support = array();
            $woosc_support['button_type'] = 'link';
            $woosc_support['quick_table_enable'] = 'no';
            $woosc_support['button_archive'] = '0';
            $woosc_support['button_single'] = '0';
            $woosc_support['open_button'] = '.open-compare-btn';
            update_option( 'woosc_settings', $woosc_support );
        }
    }

    if ( class_exists( 'WPCleverWoosw' ) ) {
        $woosw_support = get_option('woosw_settings');
        if ( is_array( $woosw_support ) ) {
            $woosw_support['button_type'] = 'link';
            $woosw_support['menu_action'] = 'open_popup';
            $woosw_support['button_position_archive'] = '0';
            $woosw_support['button_position_single'] = '0';
            update_option( 'woosw_settings', $woosw_support );
        } else {
            $woosw_support = array();
            $woosw_support['button_type'] = 'link';
            $woosw_support['menu_action'] = 'open_popup';
            $woosw_support['button_position_archive'] = '0';
            $woosw_support['button_position_single'] = '0';
            update_option( 'woosw_settings', $woosw_support );
        }
    }

    if ( class_exists( 'WPCleverWoosw' ) ) {
        $woobt_support = get_option('woobt_settings');
        if ( is_array( $woobt_support ) ) {
            $woobt_support['default'] = [ 0 => 'default', 1 => 'related', 2 => 'upsells' ];
            $woobt_support['default_limit'] = '4';
            $woobt_support['position'] = 'after';
            $woobt_support['search_same'] = 'yes';
            update_option( 'woobt_settings', $woobt_support );
        } else {
            $woobt_support = array();
            $woobt_support['default'] = [ 0 => 'default', 1 => 'related', 2 => 'upsells' ];
            $woobt_support['default_limit'] = '4';
            $woobt_support['position'] = 'after';
            $woobt_support['search_same'] = 'yes';
            update_option( 'woobt_settings', $woobt_support );
        }
    }
    /*
    * Customer Reviews for WooCommerce Plugins Settings
    * update some options after demodata insall
    */
    if ( class_exists( 'Ivole' ) ) {
        update_option( 'ivole_attach_image', 'yes' );
        update_option( 'ivole_attach_image_quantity', 2 );
        update_option( 'ivole_attach_image_size', 2 );
        update_option( 'ivole_ajax_reviews_per_page', 3 );
        update_option( 'ivole_disable_lightbox', 'yes' );
        update_option( 'ivole_reviews_histogram', 'yes' );
        update_option( 'ivole_reviews_voting', 'yes' );
        update_option( 'ivole_reviews_nobranding', 'yes' );
        update_option( 'ivole_ajax_reviews', 'yes' );
        update_option( 'ivole_ajax_reviews_form', 'yes' );
        update_option( 'ivole_questions_answers', 'yes' );
        update_option( 'ivole_qna_count', 'yes' );
        update_option( 'ivole_reviews_shortcode', 'yes' );
    }

    if ( class_exists( 'WooCommerce' ) ) {
        $args = array(
            'post_type'   => 'product',
            'numberposts' => -1
        );
        $all_posts = get_posts($args);
        foreach ( $all_posts as $single_post ) {
            wp_update_post( $single_post );
            wp_update_term_count( $single_post->ID, 'product_cat', true );
        }
        wp_reset_postdata();

        $cartPage = get_option('woocommerce_cart_page_id');
        $cart_page_data = array(
            'ID' => $cartPage,
            'post_content' => '[woocommerce_cart]'
        );
        wp_update_post( $cart_page_data );

        $checkoutPage = get_option('woocommerce_checkout_page_id');
        $checkout_page_data = array(
            'ID' => $checkoutPage,
            'post_content' => '[woocommerce_checkout]'
        );
        wp_update_post( $checkout_page_data );
    }

    // removes block widgets from sidebars after demodata install
    if ( is_active_sidebar( 'sidebar-1' ) ) {
        $sidebars_widgets = get_option( 'sidebars_widgets' );
        $sidebar_1_array  = $sidebars_widgets['sidebar-1'];
        foreach( $sidebar_1_array as $k => $v ) {
            if( substr( $v, 0, strlen("block-") ) === "block-" ) {
                unset($sidebars_widgets['sidebar-1'][$k]);
            }
        }
        update_option( 'sidebars_widgets', $sidebars_widgets);
    }
}
add_action( 'merlin_after_all_import', 'goldsmith_merlin_after_import_setup' );

add_action('init', 'do_output_buffer'); function do_output_buffer() { ob_start(); }

add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_true' );

add_action( 'admin_init', function() {
    if ( did_action( 'elementor/loaded' ) ) {
        remove_action( 'admin_init', [ \Elementor\Plugin::$instance->admin, 'maybe_redirect_to_getting_started' ] );
    }
}, 1 );

function goldsmith_register_elementor_locations( $elementor_theme_manager )
{
    $elementor_theme_manager->register_location( 'header' );
    $elementor_theme_manager->register_location( 'footer' );
    $elementor_theme_manager->register_location( 'single' );
    $elementor_theme_manager->register_location( 'archive' );

}
add_action( 'elementor/theme/register_locations', 'goldsmith_register_elementor_locations' );
