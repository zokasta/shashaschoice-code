<?php

/*
** theme options panel and metabox settings
** will change some parts of theme via custom style
*/

function goldsmith_custom_css()
{

  // stop on admin pages
    if (is_admin()) {
        return false;
    }

    // Redux global
    global $goldsmith;

    $is_right = is_rtl() ? 'right' : 'left';
    $is_left = is_rtl() ? 'left' : 'right';
    /* CSS to output */
    $theCSS = '';


    /*************************************************
    ## HEADER SETTINGS
    *************************************************/

    if ( '0' == goldsmith_settings('boxed_max_width', '') && goldsmith_settings('content_width', '') ) {
        $theCSS .= '@media (min-width: 1200px){
        .container {
            max-width: '.goldsmith_settings('content_width', '').'px;
        }}';
    }
    if ( '1' == goldsmith_settings('boxed_max_width', '') && goldsmith_settings('boxed_max_width', '') ) {
        $theCSS .= '.layout-boxed #wrapper,.layout-boxed .goldsmith-header-default {
            max-width: '.goldsmith_settings('boxed_max_width', '').'px;
        }';
    }
    if ( goldsmith_settings('quick_view_width_sm', '') ) {
        $theCSS .= '@media (min-width: 1024px){
        .goldsmith-quickview-wrapper {
            max-width: '.goldsmith_settings('quick_view_width_sm', '').'px;
        }}';
    }
    if ( goldsmith_settings('quick_view_width', '') ) {
        $theCSS .= '@media (min-width: 1200px){
        .goldsmith-quickview-wrapper {
            max-width: '.goldsmith_settings('quick_view_width', '').'px;
        }}';
    }
    if ( goldsmith_settings('quick_shop_width', '') ) {
        $theCSS .= '@media (min-width: 1024px){
        .goldsmith-quickshop-wrapper {
            max-width: '.goldsmith_settings('quick_shop_width', '').'px;
        }}';
    }
    if ( goldsmith_settings('quick_shop_width_sm', '') ) {
        $theCSS .= '@media (min-width: 1200px){
        .goldsmith-quickshop-wrapper {
            max-width: '.goldsmith_settings('quick_shop_width_sm', '').'px;
        }}';
    }

    if ( goldsmith_settings('header_height', '') ) {
        $theCSS .= '.header-spacer,
        .goldsmith-header-top-menu-area>ul>li.menu-item {
            min-height: '.goldsmith_settings('header_height', '').'px;
        }';
    }
    if ( goldsmith_settings('header_right_item_spacing', '') ) {
        $theCSS .= '.goldsmith-header-top-right .goldsmith-header-default-inner>div:not(:first-child) {
            margin-'.$is_right.': '.goldsmith_settings('header_right_item_spacing', '').'px;
        }';
    }
    if ( goldsmith_settings('header_left_item_spacing', '') ) {
        $theCSS .= '.goldsmith-header-top-left .goldsmith-header-default-inner>div:not(:last-child) {
            margin-'.$is_left.': '.goldsmith_settings('header_right_item_spacing', '').'px;
        }';
    }

    if ( goldsmith_settings('header_buttons_spacing', '') ) {
        $theCSS .= '.goldsmith-header-default .top-action-btn {
            margin-'.$is_right.': '.goldsmith_settings('header_buttons_spacing', '').'px;
        }';
    }
    if ( goldsmith_settings('sidebar_menu_content_width', '') ) {
        $theCSS .= '.goldsmith-header-mobile {
            max-width: '.goldsmith_settings('sidebar_menu_content_width', '').'px;
        }';
    }
    if ( goldsmith_settings('sidebar_menu_bar_width', '') ) {
        $theCSS .= '.goldsmith-header-mobile-sidebar {
            min-width: '.goldsmith_settings('sidebar_menu_bar_width', '').'px;
        }';
    }
    // logo size
    if ( goldsmith_settings('logo_size', '') ) {
        $theCSS .= '.nt-logo img {
            max-width: '.goldsmith_settings('logo_size', '').'px;
        }';
    }
    if ( goldsmith_settings('sticky_logo_size', '') ) {
        $theCSS .= '.nt-logo img.sticky-logo {
            max-width: '.goldsmith_settings('sticky_logo_size', '').'px;
        }';
    }
    if ( goldsmith_settings('mobile_logo_size', '') ) {
        $theCSS .= '.nt-logo img.mobile-menu-logo {
            max-width: '.goldsmith_settings('mobile_logo_size', '').'px;
        }';
    }
    if ( goldsmith_settings('sidebar_logo_size', '') ) {
        $theCSS .= '.goldsmith-header-mobile-sidebar-logo .nt-logo img {
            max-width: '.goldsmith_settings('sidebar_logo_size', '').'px;
        }';
    }
    if ( 'custom' == goldsmith_settings('shop_container_width', '') && goldsmith_settings('shop_custom_container_width', '') ) {
        $theCSS .= '@media (min-width: 1200px){.nt-shop-page .shop-area >.container,.nt-shop-page .shop-area>.container-fluid {
            max-width: '.goldsmith_settings('shop_custom_container_width', '').'px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 20px;
            padding-right: 20px;
        }}';
    }
    if ( goldsmith_settings('single_shop_custom_container_width', '') ) {
        $theCSS .= '@media (min-width: 1200px){.nt-woo-single .nt-goldsmith-inner-container >.container {
            max-width: '.goldsmith_settings('single_shop_custom_container_width', '').'px;
        }}';
    }

    $mobile_breakpoint = goldsmith_settings('mobile_header_breakpoint');
    if ( $mobile_breakpoint != 1280 ) {
        $theCSS .= '@media (min-width: '.$mobile_breakpoint.'px) {header.goldsmith-header-default {display: flex;}}';
        $theCSS .= '@media (min-width: '.$mobile_breakpoint.'px) {.goldsmith-header-mobile-top {display: none;}}';
        $theCSS .= '@media (max-width: '.$mobile_breakpoint.'px) {
            header.goldsmith-header-default {display: none;}
            .goldsmith-header-mobile-top {display: flex;}
        }';
    }

    /*************************************************
    ## PRELOADER SETTINGS
    *************************************************/
    if ( '0' != goldsmith_settings('preloader_visibility') ) {

        $pretype = goldsmith_settings('pre_type', 'default');
        $prebg = goldsmith_settings('pre_bg', '#fff');
        $prebg = $prebg ? $prebg : '#f1f1f1';
        $spinclr = goldsmith_settings('pre_spin', '#000');
        $spinclr = $spinclr ? $spinclr : '#000';
        if ( 'default' == $pretype ) {
            $theCSS .= 'body.dark .pace, body.light .pace { background-color: '. esc_attr( $spinclr ) .';}';
            $theCSS .= '#preloader:after, #preloader:before{ background-color:'. esc_attr( $prebg ) .';}';
        }

        $theCSS .= 'div#nt-preloader {background-color: '. esc_attr($prebg) .';overflow: hidden;background-repeat: no-repeat;background-position: center center;height: 100%;left: 0;position: fixed;top: 0;width: 100%;z-index: 9999999;}';
        $spin_rgb = goldsmith_hex2rgb($spinclr);

        if ('01' == $pretype) {
            $theCSS .= '.loader01 {width: 56px;height: 56px;border: 8px solid '. $spinclr .';border-right-color: transparent;border-radius: 50%;position: relative;animation: loader-rotate 1s linear infinite;top: 50%;margin: -28px auto 0; }.loader01::after {content: "";width: 8px;height: 8px;background: '. $spinclr .';border-radius: 50%;position: absolute;top: -1px;left: 33px; }@keyframes loader-rotate {0% {transform: rotate(0); }100% {transform: rotate(360deg); } }';
        }
        if ('02' == $pretype) {
            $theCSS .= '.loader02 {width: 56px;height: 56px;border: 8px solid rgba('. $spin_rgb .', 0.25);border-top-color: '. $spinclr .';border-radius: 50%;position: relative;animation: loader-rotate 1s linear infinite;top: 50%;margin: -28px auto 0; }@keyframes loader-rotate {0% {transform: rotate(0); }100% {transform: rotate(360deg); } }';
        }
        if ('03' == $pretype) {
            $theCSS .= '.loader03 {width: 56px;height: 56px;border: 8px solid transparent;border-top-color: '. $spinclr .';border-bottom-color: '. $spinclr .';border-radius: 50%;position: relative;animation: loader-rotate 1s linear infinite;top: 50%;margin: -28px auto 0; }@keyframes loader-rotate {0% {transform: rotate(0); }100% {transform: rotate(360deg); } }';
        }
        if ('04' == $pretype) {
            $theCSS .= '.loader04 {width: 56px;height: 56px;border: 2px solid rgba('. $spin_rgb .', 0.5);border-radius: 50%;position: relative;animation: loader-rotate 1s ease-in-out infinite;top: 50%;margin: -28px auto 0; }.loader04::after {content: "";width: 10px;height: 10px;border-radius: 50%;background: '. $spinclr .';position: absolute;top: -6px;left: 50%;margin-left: -5px; }@keyframes loader-rotate {0% {transform: rotate(0); }100% {transform: rotate(360deg); } }';
        }
        if ('05' == $pretype) {
            $theCSS .= '.loader05 {width: 56px;height: 56px;border: 4px solid '. $spinclr .';border-radius: 50%;position: relative;animation: loader-scale 1s ease-out infinite;top: 50%;margin: -28px auto 0; }@keyframes loader-scale {0% {transform: scale(0);opacity: 0; }50% {opacity: 1; }100% {transform: scale(1);opacity: 0; } }';
        }
        if ('06' == $pretype) {
            $theCSS .= '.loader06 {width: 56px;height: 56px;border: 4px solid transparent;border-radius: 50%;position: relative;top: 50%;margin: -28px auto 0; }.loader06::before {content: "";border: 4px solid rgba('. $spin_rgb .', 0.5);border-radius: 50%;width: 67.2px;height: 67.2px;position: absolute;top: -9.6px;left: -9.6px;animation: loader-scale 1s ease-out infinite;animation-delay: 1s;opacity: 0; }.loader06::after {content: "";border: 4px solid '. $spinclr .';border-radius: 50%;width: 56px;height: 56px;position: absolute;top: -4px;left: -4px;animation: loader-scale 1s ease-out infinite;animation-delay: 0.5s; }@keyframes loader-scale {0% {transform: scale(0);opacity: 0; }50% {opacity: 1; }100% {transform: scale(1);opacity: 0; } }';
        }
        if ('07' == $pretype) {
            $theCSS .= '.loader07 {width: 16px;height: 16px;border-radius: 50%;position: relative;animation: loader-circles 1s linear infinite;top: 50%;margin: -8px auto 0; }@keyframes loader-circles {0% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.05), 19px -19px 0 0 rgba('. $spin_rgb .', 0.1), 27px 0 0 0 rgba('. $spin_rgb .', 0.2), 19px 19px 0 0 rgba('. $spin_rgb .', 0.3), 0 27px 0 0 rgba('. $spin_rgb .', 0.4), -19px 19px 0 0 rgba('. $spin_rgb .', 0.6), -27px 0 0 0 rgba('. $spin_rgb .', 0.8), -19px -19px 0 0 '. $spinclr .'; }12.5% {box-shadow: 0 -27px 0 0 '. $spinclr .', 19px -19px 0 0 rgba('. $spin_rgb .', 0.05), 27px 0 0 0 rgba('. $spin_rgb .', 0.1), 19px 19px 0 0 rgba('. $spin_rgb .', 0.2), 0 27px 0 0 rgba('. $spin_rgb .', 0.3), -19px 19px 0 0 rgba('. $spin_rgb .', 0.4), -27px 0 0 0 rgba('. $spin_rgb .', 0.6), -19px -19px 0 0 rgba('. $spin_rgb .', 0.8); }25% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.8), 19px -19px 0 0 '. $spinclr .', 27px 0 0 0 rgba('. $spin_rgb .', 0.05), 19px 19px 0 0 rgba('. $spin_rgb .', 0.1), 0 27px 0 0 rgba('. $spin_rgb .', 0.2), -19px 19px 0 0 rgba('. $spin_rgb .', 0.3), -27px 0 0 0 rgba('. $spin_rgb .', 0.4), -19px -19px 0 0 rgba('. $spin_rgb .', 0.6); }37.5% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.6), 19px -19px 0 0 rgba('. $spin_rgb .', 0.8), 27px 0 0 0 '. $spinclr .', 19px 19px 0 0 rgba('. $spin_rgb .', 0.05), 0 27px 0 0 rgba('. $spin_rgb .', 0.1), -19px 19px 0 0 rgba('. $spin_rgb .', 0.2), -27px 0 0 0 rgba('. $spin_rgb .', 0.3), -19px -19px 0 0 rgba('. $spin_rgb .', 0.4); }50% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.4), 19px -19px 0 0 rgba('. $spin_rgb .', 0.6), 27px 0 0 0 rgba('. $spin_rgb .', 0.8), 19px 19px 0 0 '. $spinclr .', 0 27px 0 0 rgba('. $spin_rgb .', 0.05), -19px 19px 0 0 rgba('. $spin_rgb .', 0.1), -27px 0 0 0 rgba('. $spin_rgb .', 0.2), -19px -19px 0 0 rgba('. $spin_rgb .', 0.3); }62.5% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.3), 19px -19px 0 0 rgba('. $spin_rgb .', 0.4), 27px 0 0 0 rgba('. $spin_rgb .', 0.6), 19px 19px 0 0 rgba('. $spin_rgb .', 0.8), 0 27px 0 0 '. $spinclr .', -19px 19px 0 0 rgba('. $spin_rgb .', 0.05), -27px 0 0 0 rgba('. $spin_rgb .', 0.1), -19px -19px 0 0 rgba('. $spin_rgb .', 0.2); }75% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.2), 19px -19px 0 0 rgba('. $spin_rgb .', 0.3), 27px 0 0 0 rgba('. $spin_rgb .', 0.4), 19px 19px 0 0 rgba('. $spin_rgb .', 0.6), 0 27px 0 0 rgba('. $spin_rgb .', 0.8), -19px 19px 0 0 '. $spinclr .', -27px 0 0 0 rgba('. $spin_rgb .', 0.05), -19px -19px 0 0 rgba('. $spin_rgb .', 0.1); }87.5% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.1), 19px -19px 0 0 rgba('. $spin_rgb .', 0.2), 27px 0 0 0 rgba('. $spin_rgb .', 0.3), 19px 19px 0 0 rgba('. $spin_rgb .', 0.4), 0 27px 0 0 rgba('. $spin_rgb .', 0.6), -19px 19px 0 0 rgba('. $spin_rgb .', 0.8), -27px 0 0 0 '. $spinclr .', -19px -19px 0 0 rgba('. $spin_rgb .', 0.05); }100% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.05), 19px -19px 0 0 rgba('. $spin_rgb .', 0.1), 27px 0 0 0 rgba('. $spin_rgb .', 0.2), 19px 19px 0 0 rgba('. $spin_rgb .', 0.3), 0 27px 0 0 rgba('. $spin_rgb .', 0.4), -19px 19px 0 0 rgba('. $spin_rgb .', 0.6), -27px 0 0 0 rgba('. $spin_rgb .', 0.8), -19px -19px 0 0 '. $spinclr .'; } }';
        }
        if ('08' == $pretype) {
            $theCSS .= '.loader08 {width: 20px;height: 20px;position: relative;animation: loader08 1s ease infinite;top: 50%;margin: -46px auto 0; }@keyframes loader08 {0%, 100% {box-shadow: -13px 20px 0 '. $spinclr .', 13px 20px 0 rgba('. $spin_rgb .', 0.2), 13px 46px 0 rgba('. $spin_rgb .', 0.2), -13px 46px 0 rgba('. $spin_rgb .', 0.2); }25% {box-shadow: -13px 20px 0 rgba('. $spin_rgb .', 0.2), 13px 20px 0 '. $spinclr .', 13px 46px 0 rgba('. $spin_rgb .', 0.2), -13px 46px 0 rgba('. $spin_rgb .', 0.2); }50% {box-shadow: -13px 20px 0 rgba('. $spin_rgb .', 0.2), 13px 20px 0 rgba('. $spin_rgb .', 0.2), 13px 46px 0 '. $spinclr .', -13px 46px 0 rgba('. $spin_rgb .', 0.2); }75% {box-shadow: -13px 20px 0 rgba('. $spin_rgb .', 0.2), 13px 20px 0 rgba('. $spin_rgb .', 0.2), 13px 46px 0 rgba('. $spin_rgb .', 0.2), -13px 46px 0 '. $spinclr .'; } }';
        }
        if ('09' == $pretype) {
            $theCSS .= '.loader09 {width: 10px;height: 48px;background: '. $spinclr .';position: relative;animation: loader09 1s ease-in-out infinite;animation-delay: 0.4s;top: 50%;margin: -28px auto 0; }.loader09::after, .loader09::before {content:  "";position: absolute;width: 10px;height: 48px;background: '. $spinclr .';animation: loader09 1s ease-in-out infinite; }.loader09::before {right: 18px;animation-delay: 0.2s; }.loader09::after {left: 18px;animation-delay: 0.6s; }@keyframes loader09 {0%, 100% {box-shadow: 0 0 0 '. $spinclr .', 0 0 0 '. $spinclr .'; }50% {box-shadow: 0 -8px 0 '. $spinclr .', 0 8px 0 '. $spinclr .'; } }';
        }
        if ('10' == $pretype) {
            $theCSS .= '.loader10 {width: 28px;height: 28px;border-radius: 50%;position: relative;animation: loader10 0.9s ease alternate infinite;animation-delay: 0.36s;top: 50%;margin: -42px auto 0; }.loader10::after, .loader10::before {content: "";position: absolute;width: 28px;height: 28px;border-radius: 50%;animation: loader10 0.9s ease alternate infinite; }.loader10::before {left: -40px;animation-delay: 0.18s; }.loader10::after {right: -40px;animation-delay: 0.54s; }@keyframes loader10 {0% {box-shadow: 0 28px 0 -28px '. $spinclr .'; }100% {box-shadow: 0 28px 0 '. $spinclr .'; } }';
        }
        if ('11' == $pretype) {
            $theCSS .= '.loader11 {width: 20px;height: 20px;border-radius: 50%;box-shadow: 0 40px 0 '. $spinclr .';position: relative;animation: loader11 0.8s ease-in-out alternate infinite;animation-delay: 0.32s;top: 50%;margin: -50px auto 0; }.loader11::after, .loader11::before {content:  "";position: absolute;width: 20px;height: 20px;border-radius: 50%;box-shadow: 0 40px 0 '. $spinclr .';animation: loader11 0.8s ease-in-out alternate infinite; }.loader11::before {left: -30px;animation-delay: 0.48s;}.loader11::after {right: -30px;animation-delay: 0.16s; }@keyframes loader11 {0% {box-shadow: 0 40px 0 '. $spinclr .'; }100% {box-shadow: 0 20px 0 '. $spinclr .'; } }';
        }
        if ('12' == $pretype) {
            $theCSS .= '.loader12 {width: 20px;height: 20px;border-radius: 50%;position: relative;animation: loader12 1s linear alternate infinite;top: 50%;margin: -50px auto 0; }@keyframes loader12 {0% {box-shadow: -60px 40px 0 2px '. $spinclr .', -30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 0 40px 0 0 rgba('. $spin_rgb .', 0.2), 30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 60px 40px 0 0 rgba('. $spin_rgb .', 0.2); }25% {box-shadow: -60px 40px 0 0 rgba('. $spin_rgb .', 0.2), -30px 40px 0 2px '. $spinclr .', 0 40px 0 0 rgba('. $spin_rgb .', 0.2), 30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 60px 40px 0 0 rgba('. $spin_rgb .', 0.2); }50% {box-shadow: -60px 40px 0 0 rgba('. $spin_rgb .', 0.2), -30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 0 40px 0 2px '. $spinclr .', 30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 60px 40px 0 0 rgba('. $spin_rgb .', 0.2); }75% {box-shadow: -60px 40px 0 0 rgba('. $spin_rgb .', 0.2), -30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 0 40px 0 0 rgba('. $spin_rgb .', 0.2), 30px 40px 0 2px '. $spinclr .', 60px 40px 0 0 rgba('. $spin_rgb .', 0.2); }100% {box-shadow: -60px 40px 0 0 rgba('. $spin_rgb .', 0.2), -30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 0 40px 0 0 rgba('. $spin_rgb .', 0.2), 30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 60px 40px 0 2px '. $spinclr .'; } }';
        }
    }

    $root_clr1 = goldsmith_settings( 'theme_clr1' );
    $root_clr2 = goldsmith_settings( 'theme_clr2' );
    $root_clr3 = goldsmith_settings( 'theme_clr3' );
    $root_clr4 = goldsmith_settings( 'theme_clr4' );

    if( $root_clr1 || $root_clr2 || $root_clr3 || $root_clr4 ) {
        $theCSS .= ':root {';
            $theCSS .= $root_clr1 ? '--goldsmith-base: '.$root_clr1.';' : '';
            $theCSS .= $root_clr1 ? '--goldsmith-base-rgb: '.goldsmith_hex2rgb($root_clr1).';' : '';
            $theCSS .= $root_clr2 ? '--goldsmith-primary: '.$root_clr2.';' : '';
            $theCSS .= $root_clr2 ? '--goldsmith-primary-rgb: '.goldsmith_hex2rgb($root_clr2).';' : '';
            $theCSS .= $root_clr3 ? '--goldsmith-black: '.$root_clr3.';' : '';
            $theCSS .= $root_clr3 ? '--goldsmith-black-rgb: '.goldsmith_hex2rgb($root_clr3).';' : '';
            $theCSS .= $root_clr4 ? '--goldsmith-black2: '.$root_clr4.';' : '';
            $theCSS .= $root_clr4 ? '--goldsmith-black2-rgb: '.goldsmith_hex2rgb($root_clr4).';' : '';
        $theCSS .= '}';
    }

    // use page/post ID for page settings
    $page_id = get_the_ID();

    /*************************************************
    ## THEME PAGINATION
    *************************************************/
    // pagination color
    $pag_radius = goldsmith_settings('pagination_border_radius');
    $pag_align  = goldsmith_settings('pagination_alignment');

    // pagination border radius
    if ( $pag_radius ) {
        $theCSS .= '.nt-pagination .nt-pagination-item .nt-pagination-link,
        .goldsmith-woocommerce-pagination ul li a,
        .goldsmith-woocommerce-pagination ul li span { border-radius: '. esc_attr( $pag_radius ) .'px; }';
    }
    // pagination border radius
    if ( $pag_align ) {
        $theCSS .= 'body .goldsmith-woocommerce-pagination ul {justify-content: '. esc_attr( $pag_align ) .';}';
    }


    /*************************************************
    ## PAGE METABOX SETTINGS
    *************************************************/

    if ( class_exists( 'WooCommerce' ) && is_shop() ) {
        $shop_hero_bg_type = goldsmith_settings( 'shop_hero_bg_type', 'img' );
        $shop_hero_bg      = goldsmith_settings( 'shop_hero_bg' );
        $shop_hero_bgsize  = goldsmith_settings( 'shop_hero_bg_imgsize', 'large' );

        if ( 'bg' == $shop_hero_bg_type && !empty( $shop_hero_bg['background-image'] ) ) {
            $shop_hero_bg_id    = $shop_hero_bg['media']['id'];
            $shop_hero_bg_image = '' != $shop_hero_bgsize ? wp_get_attachment_image_url($shop_hero_bg_id,$shop_hero_bgsize) : $shop_hero_bg['background-image'];
            $theCSS .= '#nt-shop-page .goldsmith-page-hero{';
                $theCSS .= !empty( $shop_hero_bg['background-color'] ) ? 'background-color:'.$shop_hero_bg['background-color'].';' : '';
                $theCSS .= 'background-image:url('.$shop_hero_bg_image.');';
                $theCSS .= !empty( $shop_hero_bg['background-repeat'] ) ? 'background-repeat:'.$shop_hero_bg['background-repeat'].';' : '';
                $theCSS .= !empty( $shop_hero_bg['background-size'] ) ? 'background-size:'.$shop_hero_bg['background-size'].';' : '';
                $theCSS .= !empty( $shop_hero_bg['background-position'] ) ? 'background-position:'.$shop_hero_bg['background-position'].';' : '';
                $theCSS .= !empty( $shop_hero_bg['background-attachment'] ) ? 'background-attachment:'.$shop_hero_bg['background-attachment'].';' : '';
            $theCSS .= '}';
        }
        $hero_height_laptop = goldsmith_settings( 'shop_hero_height_laptop' );
        if ( !empty( $hero_height_laptop['height'] ) ) {
            $theCSS .= '@media(max-width:1200px){body #nt-shop-page .goldsmith-page-hero {height: '.$hero_height_laptop['height'].'px;}}';
        }
        $hero_height_tablet = goldsmith_settings( 'shop_hero_height_tablet' );
        if ( !empty( $hero_height_tablet['height'] ) ) {
            $theCSS .= '@media(max-width:1024px){body #nt-shop-page .goldsmith-page-hero {height: '.$hero_height_tablet['height'].'px;}}';
        }
        $hero_height_phone = goldsmith_settings( 'shop_hero_height_phone' );
        if ( !empty( $hero_height_phone['height'] ) ) {
            $theCSS .= '@media(max-width:576px){body #nt-shop-page .goldsmith-page-hero {height: '.$hero_height_phone['height'].'px;}}';
        }
    }

    if ( class_exists( 'WooCommerce' ) && is_product() ) {

        $summarybg_type_ot = goldsmith_settings( 'single_shop_showcase_bg_type', '' );
        $summarybg_type_mb = get_post_meta( $page_id, 'goldsmith_showcase_bg_type', true );
        $summarybg_type    = $summarybg_type_mb ? $summarybg_type_mb : $summarybg_type_ot;
        $summarybg_type    = apply_filters('goldsmith_showcase_bg_type', $summarybg_type );

        $summarybg_ot   = goldsmith_settings( 'single_shop_showcase_custom_bgcolor', '' );
        $summarybg_mb   = get_post_meta( $page_id, 'goldsmith_showcase_custom_bgcolor', true );
        $summarybg      = $summarybg_mb ? $summarybg_mb : $summarybg_ot;
        $summarybg      = apply_filters('goldsmith_showcase_custom_bgcolor', $summarybg );

        $summarytext_ot = goldsmith_settings( 'single_shop_showcase_custom_textcolor', '' );
        $summarytext_mb = get_post_meta( $page_id, 'goldsmith_showcase_custom_textcolor', true );
        $summarytext    = $summarytext_mb ? $summarytext_mb : $summarytext_ot;
        $summarytext    = apply_filters('goldsmith_showcase_custom_textcolor', $summarytext );
        $hint_bg        = goldsmith_settings('product_cart_svg_icon_hint_bgcolor', '' );
        $countdown_width = goldsmith_settings('product_countdown_width', '' );
        $countdown_align = goldsmith_settings('product_countdown_alignment', '' );
        if ( '' != $hint_bg ) {
            $theCSS .= '.page-'.$page_id.' .goldsmith-summary-item .goldsmith-product-hint:after { border-top-color:'.esc_url( $hint_bg ).'; }';
        }
        if ( 'boxed' == $countdown_width ) {
            $theCSS .= '.page-'.$page_id.' .goldsmith-summary-item.goldsmith-viewed-offer-time { display:inline-block; }';
        }
        if ( '' != $countdown_align ) {
            $theCSS .= '.page-'.$page_id.' .goldsmith-summary-item.goldsmith-viewed-offer-time { justify-content:'.$countdown_align.'; }';
        }
        if ( 'custom' == $summarybg_type ) {
            if ( $summarybg ) {
                $theCSS .= '.page-'.$page_id.' .goldsmith-product-showcase, .postid-'.$page_id.' .goldsmith-product-showcase.goldsmith-bg-custom { background-color:'.esc_url( $summarybg ).'; }';
            }
            if ( $summarytext ) {
                $theCSS .= '.goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-summary-item.goldsmith-product-title,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-product-summary .goldsmith-summary-item.goldsmith-price,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-price span.del > span,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-summary-item .woocommerce-product-details__short-description,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-small-title,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-small-title a,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-product-view,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-product-view span,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-estimated-delivery,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-estimated-delivery span,
                .goldsmith-product-showcase.goldsmith-bg-custom a.goldsmith-open-popup,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-product-summary .goldsmith-product-meta .posted_in,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-product-summary .goldsmith-product-meta .tagged_as,
                .goldsmith-product-showcase.goldsmith-bg-custom .quantity-button.plus,
                .goldsmith-product-showcase.goldsmith-bg-custom .quantity-button.minus,
                .goldsmith-product-showcase.goldsmith-bg-custom .input-text.qty,
                .goldsmith-product-showcase.goldsmith-bg-custom .woocommerce-product-details__short-description,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-single-product-stock .stock-details span,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-breadcrumb li,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-breadcrumb li a,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-product-summary .goldsmith-product-meta .goldsmith-brands a,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-product-summary .goldsmith-product-meta .posted_in a,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-product-summary .goldsmith-product-meta .tagged_as a,
                .goldsmith-product-showcase.goldsmith-bg-custom span.goldsmith-shop-link-icon,
                .goldsmith-product-showcase.goldsmith-bg-custom .product-nav-link,
                .goldsmith-product-showcase.goldsmith-bg-custom .goldsmith-product-summary .goldsmith-product-meta .goldsmith-brands {
                    color: '.esc_url( $summarytext ).';
                }
                .goldsmith-product-showcase.goldsmith-bg-custom span.goldsmith-shop-link-icon:before,
                .goldsmith-product-showcase.goldsmith-bg-custom span.goldsmith-shop-link-icon:after {
                    border-color: '.esc_url( $summarytext ).';
                }';
            }
        }

        $product_header_mb = get_post_meta( $page_id, 'goldsmith_product_header_type', true );
        if ( 'custom' == $product_header_mb ) {
            $header_bgcolor           = get_post_meta( $page_id, 'goldsmith_product_header_bgcolor', true );
            $menuitem_color           = get_post_meta( $page_id, 'goldsmith_product_header_menuitem_color', true );
            $menuitem_hvrcolor        = get_post_meta( $page_id, 'goldsmith_product_header_menuitem_hvrcolor', true );
            $svgicon_color            = get_post_meta( $page_id, 'goldsmith_product_header_svgicon_color', true );
            $counter_bgcolor          = get_post_meta( $page_id, 'goldsmith_product_header_counter_bgcolor', true );
            $counter_color            = get_post_meta( $page_id, 'goldsmith_product_header_counter_color', true );
            $sticky_header_bgcolor    = get_post_meta( $page_id, 'goldsmith_product_sticky_header_bgcolor', true );
            $sticky_menuitem_color    = get_post_meta( $page_id, 'goldsmith_product_sticky_header_menuitem_color', true );
            $sticky_menuitem_hvrcolor = get_post_meta( $page_id, 'goldsmith_product_sticky_header_menuitem_hvrcolor', true );
            $sticky_svgicon_color     = get_post_meta( $page_id, 'goldsmith_product_sticky_header_svgicon_color', true );
            $sticky_counter_bgcolor   = get_post_meta( $page_id, 'goldsmith_product_sticky_header_counter_bgcolor', true );
            $sticky_counter_color     = get_post_meta( $page_id, 'goldsmith_product_sticky_header_counter_color', true );

            if ( $header_bgcolor ) {
                $theCSS .= '.single-product.postid-'.$page_id.' header.goldsmith-header-default {
                    background-color:'.esc_url( $header_bgcolor ).'!important;
                }';
            }
            if ( $menuitem_color ) {
                $theCSS .= '.single-product.postid-'.$page_id.' .goldsmith-header-default .goldsmith-header-top-menu-area>ul>li.menu-item>a,
                .single-product.postid-'.$page_id.' .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a {
                    color:'.esc_url( $menuitem_color ).'!important;
                }';
            }
            if ( $menuitem_hvrcolor ) {
                $theCSS .= '.single-product.postid-'.$page_id.' .goldsmith-header-default .goldsmith-header-top-menu-area>ul>li.menu-item>a:hover,
                .single-product.postid-'.$page_id.' .goldsmith-header-default .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover,
                .single-product.postid-'.$page_id.' .goldsmith-header-default .goldsmith-header-top-menu-area>ul>li.menu-item.active>a,
                .single-product.postid-'.$page_id.' .goldsmith-header-default .goldsmith-header-top-menu-area ul li .submenu>li.menu-item.active>a {
                    color:'.esc_url( $menuitem_hvrcolor ).'!important;
                }';
            }
            if ( $svgicon_color ) {
                $theCSS .= '.single-product.postid-'.$page_id.' .goldsmith-header-default .goldsmith-svg-icon {
                    fill:'.esc_url( $svgicon_color ).'!important;
                    color:'.esc_url( $svgicon_color ).'!important;
                }';
            }
            if ( $counter_bgcolor ) {
                $theCSS .= '.single-product.postid-'.$page_id.' .goldsmith-header-default .goldsmith-wc-count {
                    background-color:'.esc_url( $counter_bgcolor ).'!important;
                }';
            }
            if ( $counter_color ) {
                $theCSS .= '.single-product.postid-'.$page_id.' .goldsmith-header-default .goldsmith-wc-count {
                    color:'.esc_url( $counter_color ).'!important;
                }';
            }
            if ( $sticky_header_bgcolor ) {
                $theCSS .= '.single-product.postid-'.$page_id.' header.goldsmith-header-default.sticky-start {
                    background-color:'.esc_url( $sticky_header_bgcolor ).'!important;
                }';
            }
            if ( $sticky_menuitem_color ) {
                $theCSS .= '.single-product.postid-'.$page_id.' .goldsmith-header-default.sticky-start .goldsmith-header-top-menu-area>ul>li.menu-item>a,
                .single-product.postid-'.$page_id.' .goldsmith-header-default.sticky-start .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a {
                    color:'.esc_url( $sticky_menuitem_color ).'!important;
                }';
            }
            if ( $sticky_menuitem_hvrcolor ) {
                $theCSS .= '.single-product.postid-'.$page_id.' .goldsmith-header-default.sticky-start .goldsmith-header-top-menu-area>ul>li.menu-item>a:hover,
                .single-product.postid-'.$page_id.' .goldsmith-header-default.sticky-start .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover,
                .single-product.postid-'.$page_id.' .goldsmith-header-default.sticky-start .goldsmith-header-top-menu-area>ul>li.menu-item.active>a,
                .single-product.postid-'.$page_id.' .goldsmith-header-default.sticky-start .goldsmith-header-top-menu-area ul li .submenu>li.menu-item.active>a {
                    color:'.esc_url( $sticky_menuitem_hvrcolor ).'!important;
                }';
            }
            if ( $sticky_svgicon_color ) {
                $theCSS .= '.single-product.postid-'.$page_id.' .goldsmith-header-default.sticky-start .goldsmith-svg-icon {
                    fill:'.esc_url( $sticky_svgicon_color ).'!important;
                    color:'.esc_url( $sticky_svgicon_color ).'!important;
                }';
            }
            if ( $sticky_counter_bgcolor ) {
                $theCSS .= '.single-product.postid-'.$page_id.' .goldsmith-header-default.sticky-start .goldsmith-wc-count {
                    background-color:'.esc_url( $sticky_counter_bgcolor ).'!important;
                }';
            }
            if ( $sticky_counter_color ) {
                $theCSS .= '.single-product.postid-'.$page_id.' .goldsmith-header-default.sticky-start .goldsmith-wc-count {
                    color:'.esc_url( $sticky_counter_color ).'!important;
                }';
            }
        }
        if ( 'auto' == goldsmith_settings('gallery_slider_imgsize', 'full') ) {
            $theCSS .= '.goldsmith-product-gallery-main-slider .swiper-slide img,
            .goldsmith-product-gallery-main-image .swiper-slide img {
                width: auto;
            }';
        }

        $share_shape_type = goldsmith_settings( 'single_shop_share_shape_type', '' );
        $active_tab       = goldsmith_settings( 'product_tabs_active_tab', '' );

        if ( 'square' == $share_shape_type ) {
            $theCSS .= '.postid-'.$page_id.' .goldsmith-product-summary .goldsmith-social-icons a { border-radius: 0; }';
        }
        if ( 'round' == $share_shape_type ) {
            $theCSS .= '.postid-'.$page_id.' .goldsmith-product-summary .goldsmith-social-icons a { border-radius: 4px; }';
        }
        if ( $active_tab != '' && $active_tab != 'all' ) {
            $theCSS .= '.goldsmith-product-accordion-wrapper .goldsmith-accordion-item'.$active_tab.' .goldsmith-accordion-body { display: block; }';
        }
        if ( $active_tab == 'all' ) {
            $theCSS .= '.goldsmith-product-accordion-wrapper .goldsmith-accordion-item .goldsmith-accordion-body { display: block; }';
        }

        $terms_shape            = goldsmith_settings( 'variations_terms_shape', '' );
        $terms_brd_radius       = goldsmith_settings( 'selected_variations_terms_brd_radius', '' );
        $disabled_terms_opacity = goldsmith_settings( 'product_attr_term_inactive_opacity', '' );
        $checked_terms_icon     = goldsmith_settings( 'variations_terms_checked_closed_icon_visibility', '1' );

        if ( '1' == goldsmith_settings( 'swatches_visibility', '1' ) ) {
            if ( $terms_brd_radius ) {
                $theCSS .= '.postid-'.$page_id.' .goldsmith-selected-variations-terms-wrapper .goldsmith-selected-variations-terms { border-radius:'.esc_attr( $terms_brd_radius ).'px; }';
            }
            if ( 'cicle' == $terms_shape ) {
                $theCSS .= '.goldsmith-terms.goldsmith-type-color .goldsmith-term-wrapper,.goldsmith-terms.goldsmith-type-color .goldsmith-term,.goldsmith-terms.goldsmith-type-image .goldsmith-term,.goldsmith-terms.goldsmith-type-button .goldsmith-term { border-radius: 100%;}';
            }
            if ( 'square' == $terms_shape ) {
                $theCSS .= '.goldsmith-terms.goldsmith-type-color .goldsmith-term-wrapper,.goldsmith-terms.goldsmith-type-color .goldsmith-term,.goldsmith-terms.goldsmith-type-image .goldsmith-term,.goldsmith-terms.goldsmith-type-button .goldsmith-term { border-radius: 0;}';
            }
            if ( 'radius' == $terms_shape ) {
                $theCSS .= '.goldsmith-terms.goldsmith-type-color .goldsmith-term-wrapper,.goldsmith-terms.goldsmith-type-color .goldsmith-term,.goldsmith-terms.goldsmith-type-image .goldsmith-term,.goldsmith-terms.goldsmith-type-button .goldsmith-term { border-radius: 3px;}';
            }
            if ( $disabled_terms_opacity ) {
                $theCSS .= '.goldsmith-terms.goldsmith-type-color .goldsmith-term.goldsmith-disabled,.goldsmith-terms.goldsmith-type-image .goldsmith-term.goldsmith-disabled,.goldsmith-terms.goldsmith-type-button .goldsmith-term.goldsmith-disabled { opacity:'.$disabled_terms_opacity.';}';
            }
            if ( '0' == $checked_terms_icon ) {
                $theCSS .= '.goldsmith-variations .goldsmith-terms .goldsmith-term.goldsmith-selected:before { display:none;}';
            }
        }
    }
    if ( 'custom' == goldsmith_settings('header_width', 'default') && goldsmith_settings('header_top_custom_width', '') ) {
        $theCSS .= '@media (min-width: 1200px){.goldsmith-header-default >.container {
            max-width: '.goldsmith_settings('header_top_custom_width', '').'px;
        }}';
    }
    if ( 'custom' == goldsmith_settings('header_width', 'default') && goldsmith_settings('sticky_header_top_custom_width', '') ) {
        $theCSS .= '@media (min-width: 1200px){.goldsmith-header-default.sticky-start >.container {
            max-width: '.goldsmith_settings('sticky_header_top_custom_width', '').'px;
        }}';
    }
    if ( goldsmith_settings('page_custom_width', '') ) {
        $theCSS .= '@media (min-width: 1200px){.nt-goldsmith-inner-container >.container {
            max-width: '.goldsmith_settings('page_custom_width', '').'px;
        }}';
    }
    if ( 'left' != goldsmith_settings('shop_hero_mini_text_align', 'left') ) {
        $theCSS .= '.page-hero-mini .goldsmith-page-hero-content {
            text-align: '.goldsmith_settings('shop_hero_mini_text_align', '').';
        }';
    }
    /*************************************************
    ## PAGE METABOX SETTINGS
    *************************************************/

    if ( is_page() && ! goldsmith_check_is_elementor() ) {

        $heroimg = wp_get_attachment_image_src( get_post_thumbnail_id( $page_id ), 'full' );
        if ( $heroimg && '1' == goldsmith_settings('page_hero_use_featured_image', '0') ) {
            $theCSS .= '.page-id-'.$page_id.' .nt-page-layout .goldsmith-page-hero { background-image:url('.esc_url( $heroimg[0] ).'); }';
        }
        if ( '' != goldsmith_settings('page_hero_text_align', '') ) {
            $theCSS .= '.page-template-default .goldsmith-page-hero-content { text-align:'.goldsmith_settings('page_hero_text_align', '').'; }';
        }
        if ( goldsmith_settings('page_hero_height', '') ) {
            $theCSS .= '@media (min-width: 1200px){.page-template-default .goldsmith-page-hero {
                min-height: '.goldsmith_settings('page_hero_height', '').'px;
            }}';
        }
        if ( goldsmith_settings('page_hero_height_tablet', '') ) {
            $theCSS .= '@media (max-width: 1199px){.page-template-default .goldsmith-page-hero {
                min-height: '.goldsmith_settings('page_hero_height_tablet', '').'px;
            }}';
        }
        if ( goldsmith_settings('page_hero_height_phone', '') ) {
            $theCSS .= '@media (max-width: 767px){.page-template-default .goldsmith-page-hero {
                min-height: '.goldsmith_settings('page_hero_height_phone', '').'px;
            }}';
        }
    }
    if ( goldsmith_settings('blog_hero_height', '') ) {
        $theCSS .= '@media (min-width: 1200px){#nt-index .goldsmith-page-hero {
            min-height: '.goldsmith_settings('blog_hero_height', '').'px;
        }}';
    }
    if ( goldsmith_settings('blog_hero_height_tablet', '') ) {
        $theCSS .= '@media (max-width: 1199px){#nt-index .goldsmith-page-hero {
            min-height: '.goldsmith_settings('blog_hero_height_tablet', '').'px;
        }}';
    }
    if ( goldsmith_settings('blog_hero_height_phone', '') ) {
        $theCSS .= '@media (max-width: 767px){#nt-index .goldsmith-page-hero {
            min-height: '.goldsmith_settings('blog_hero_height_phone', '').'px;
        }}';
    }
    if ( '' != goldsmith_settings('blog_hero_text_align', '') ) {
        $theCSS .= '#nt-index .goldsmith-page-hero .goldsmith-page-hero-content { text-align:'.goldsmith_settings('blog_hero_text_align', '').'; }';
    }

    if ( is_single() ) {
        if ( goldsmith_settings('single_hero_height', '') ) {
            $theCSS .= '@media (min-width: 1200px){#nt-single .goldsmith-page-hero {
                min-height: '.goldsmith_settings('single_hero_height', '').'px;
            }}';
        }
        if ( goldsmith_settings('single_hero_height_tablet', '') ) {
            $theCSS .= '@media (max-width: 1199px){#nt-single .goldsmith-page-hero {
                min-height: '.goldsmith_settings('single_hero_height_tablet', '').'px;
            }}';
        }
        if ( goldsmith_settings('single_hero_height_phone', '') ) {
            $theCSS .= '@media (max-width: 767px){#nt-single .goldsmith-page-hero {
                min-height: '.goldsmith_settings('single_hero_height_phone', '').'px;
            }}';
        }
        if ( '' != goldsmith_settings('single_hero_text_align', '') ) {
            $theCSS .= '#nt-single .goldsmith-page-hero .goldsmith-page-hero-content { text-align:'.goldsmith_settings('single_hero_text_align', '').'; }';
        }
    }

    if ( is_archive() ) {
        if ( goldsmith_settings('archive_hero_height', '') ) {
            $theCSS .= '@media (min-width: 1200px){#nt-archive .goldsmith-page-hero {
                min-height: '.goldsmith_settings('archive_hero_height', '').'px;
            }}';
        }
        if ( goldsmith_settings('archive_hero_height_tablet', '') ) {
            $theCSS .= '@media (max-width: 1199px){#nt-archive .goldsmith-page-hero {
                min-height: '.goldsmith_settings('archive_hero_height_tablet', '').'px;
            }}';
        }
        if ( goldsmith_settings('archive_hero_height_phone', '') ) {
            $theCSS .= '@media (max-width: 767px){#nt-archive .goldsmith-page-hero {
                min-height: '.goldsmith_settings('archive_hero_height_phone', '').'px;
            }}';
        }
        if ( '' != goldsmith_settings('archive_hero_text_align', '') ) {
            $theCSS .= '#nt-archive .goldsmith-page-hero .goldsmith-page-hero-content { text-align:'.goldsmith_settings('archive_hero_text_align', '').'; }';
        }
    }

    $extraCSS = '';
    $extraCSS = apply_filters( 'goldsmith_add_custom_css', $extraCSS );
    $theCSS .= $extraCSS;

    /* Add CSS to style.css */
    wp_register_style('goldsmith-custom-style', false);
    wp_enqueue_style('goldsmith-custom-style');
    wp_add_inline_style('goldsmith-custom-style', $theCSS );
}

add_action('wp_enqueue_scripts', 'goldsmith_custom_css',9999999);


// customization on admin pages
function goldsmith_admin_custom_css()
{
    if ( ! is_admin() ) {
        return false;
    }

    /* CSS to output */
    $theCSS = '';
    $is_right = is_rtl() ? 'right' : 'left';
    $is_left = is_rtl() ? 'left' : 'right';
    $theCSS .= '
    #setting-error-tgmpa, #setting-error-goldsmith {
        display: block !important;
    }
    .menu-item.menu-item-depth-0 .et_menu_options .goldsmith-field-link-shortcode,
    .menu-item.menu-item-depth-0 .et_menu_options .goldsmith-field-link-hidetitle,
    .menu-item.menu-item-depth-0 .et_menu_options .goldsmith-field-link-title,
    .menu-item.menu-item-depth-0 .et_menu_options .goldsmith-field-link-label,
    .menu-item.menu-item-depth-0 .et_menu_options .goldsmith-field-link-labelcolor,
    .menu-item.menu-item-depth-0 .et_menu_options .goldsmith-field-link-image,
    .menu-item:not(.menu-item-depth-0) .et_menu_options .goldsmith-field-link-mega,
    .menu-item:not(.menu-item-depth-0) .et_menu_options .goldsmith-field-link-mega-columns{
        display: none;
    }
    .goldsmith_menu_options .small-tag {
        font-size: 10px;
        font-weight: 400;
        position: relative;
        top: -2px;
        display: inline-block;
        margin-'.$is_right.': 4px;
        color: #fff;
        background-color: #bbb;
        line-height: 1;
        padding: 3px 6px;
        border-radius: 3px;
    }
    .goldsmith-panel-heading {
        padding: 10px 12px;
        border-bottom: 1px solid #ddd;
    }
    .goldsmith-panel-subheading {
        padding: 0px 12px;
    }
    .goldsmith-panel-divider {
        margin: 10px 0;
        border-bottom: 1px solid #ddd;
        display: block;
    }
    div#message.updated.woocommerce-message {
        display: none;
    }
    .reduxd_field_th {
        color: #191919;
        font-weight: 700;
    }
    .redux-container .redux-main .form-table tr {
        position: relative;
    }
    .redux-container .redux-main .form-table tr.hide-field {
        position: relative;
        min-height: 40px;
    }
    .toggle-field {
        position: absolute;
        top: 10px;
        right: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        cursor: pointer;
        background: #fff;
        border: 1px solid #7e8993;
    }
    .toggle-field.hide-field {
        background: #000;
        color: #fff;
    }
    .toggle-field.hide-field i {
        transform:rotate(180deg);
    }
    fieldset#goldsmith-shop_hero_custom_layout,
    fieldset#goldsmith-shop_loop_product_layouts {
        padding-right: 50px;
    }
    fieldset#shop_hero_custom_layout,
    fieldset#shop_loop_product_layouts {
        display: flex;
    }
    fieldset#shop_hero_custom_layout {
        flex-wrap: wrap;
    }
    fieldset#shop_hero_custom_layout ul {
        flex: auto;
        float: none;
        min-width: 200px;
        width: auto!important;
    }
    fieldset#shop_hero_custom_layout ul {
        min-width: auto;
    }
    @media screen and (max-width: 768px) {
        fieldset#shop_hero_custom_layout {
            flex-wrap: wrap;
            flex-direction: column;
        }
        fieldset#shop_hero_custom_layout ul {
            margin-top: 15px!important;
        }
    }
    ul#shop_loop_product_layouts_hide .shop_loop_product_layouts_inner {
        max-height: 400px;
        overflow: auto;
        display: flex;
        flex-wrap: wrap;
    }
    .shop_loop_product_layouts_inner li {
        flex: auto;
        margin: 10px 5px 10px 5px;
    }
    .redux-container .redux-main #goldsmith-shop_product_type img {
        max-width: 175px!important;
        max-height: 220px;
    }
    #goldsmith-shop_product_type.redux-container-image_select ul.redux-image-select li {
        padding: 15px!important;
    }
    #goldsmith-shop_product_type.redux-container-image_select ul.redux-image-select{
        margin-left: -15px!important;
        margin-right: -15px!important;
    }
    input#goldsmith_badge_color {
        margin: 0!important;
    }
    p.form-field.goldsmith_wc_cat_banner_field span {
        display: block;
        max-width: 95%;
    }
    td.goldsmith_cat_banner.column-goldsmith_cat_banner {
        position: relative;
    }
    .goldsmith_cat_banner span.wc-banner:before {
        font-family: Dashicons;
        font-weight: 400;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        text-indent: 0px;
        color: #2271b1;
        content: "\f155";
        font-variant: normal;
        margin: 0px;
        font-size: 18px;
    }
    span.wc-banner {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 25px;
        text-align: center;
    }
    .woocommerce_options_panel .goldsmith-color-field-wrapper .wp-picker-input-wrap label{
        margin: 0;
        width: auto;
    }
    th#taxonomy-goldsmith_product_brands,th#woosw {
        width: 11%!important;
    }
    .image-preview-wrapper {
        max-width: 100px;
    }
    .image-preview-wrapper img {
        max-width: 100%;
    }
    .redux-main .description {
        display: block;
        font-weight: normal;
    }
    li#toplevel_page_wpclever,
    #redux-connect-message {
        opacity: 0 !important;
        display: none !important;
        visibility : hidden;
    }
    .redux-main .wp-picker-container .wp-color-result-text {
        line-height: 28px;
    }
    .redux-container .redux-main .input-append .add-on,
    .redux-container .redux-main .input-prepend .add-on {
        line-height: 22px;
    }
    .redux-main .redux-field-container {
        max-width: calc(100% - 40px);
    }
  	#customize-controls img {
  		max-width: 75%;
  	}
    .redux-info-desc .thm-btn:hover {
        color: #000;
    }
    .redux-info-desc .thm-btn i {
        margin-'.$is_right.': 10px;
    }
    .redux-info-desc .thm-btn {
        -moz-user-select: none;
        border: medium none;
        border-radius: 4px;
        color: #fff;
        background-color: #2271b1;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        height: 40px;
        min-width: 160px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0;
        line-height: 1;
        margin-bottom: 0;
        text-align: center;
        text-transform: uppercase;
        touch-action: manipulation;
        transition: all 0.3s ease 0s;
        vertical-align: middle;
        white-space: nowrap;
    }
    .goldsmith-column-item {
        display: inline-block;
        width: 40px;
        height: 40px;
        background-color: #eee;
        box-sizing: border-box;
        border: 1px solid #eee;
    }
    #goldsmith_swatches_image_thumbnail {
        float: left;
        margin-'.$is_left.': 10px;
    }
    #goldsmith_swatches_image_wrapper {
        line-height: 60px;
    }
    li.menu-item.mega-parent .goldsmith-field-link-label,
    li.menu-item.mega-parent .goldsmith-field-link-labelcolor,
    li.menu-item.mega-parent .goldsmith-field-link-image,
    li.menu-item:not(.menu-item-depth-0):not(.mega-parent) .goldsmith-field-link-mega,
    li.menu-item:not(.menu-item-depth-0) .goldsmith-field-link-mega-columns {
        display: none;
    }
    span.goldsmith-mega-menu-item-title,
    span.goldsmith-mega-column-menu-item-title {
        margin-'.$is_right.': 10px;
        padding: 2px 4px;
        background: #2271b1;
        color: #fff;
        line-height: 1;
        font-size: 9px;
    }
    .goldsmith-panel-subheading.menu-customize:not(.show_if_header_custom),
    .goldsmith_product_header_bgcolor_field:not(.show_if_header_custom),
    .goldsmith_product_header_menuitem_color_field:not(.show_if_header_custom),
    .goldsmith_product_header_menuitem_hvrcolor_field:not(.show_if_header_custom),
    .goldsmith_product_header_svgicon_color_field:not(.show_if_header_custom),
    .goldsmith_product_header_counter_bgcolor_field:not(.show_if_header_custom),
    .goldsmith_product_header_counter_color_field:not(.show_if_header_custom),
    .goldsmith_product_sticky_header_type_field:not(.show_if_header_custom),
    .goldsmith_product_sticky_header_bgcolor_field:not(.show_if_header_custom),
    .goldsmith_product_sticky_header_menuitem_color_field:not(.show_if_header_custom),
    .goldsmith_product_sticky_header_menuitem_hvrcolor_field:not(.show_if_header_custom),
    .goldsmith_product_sticky_header_svgicon_color_field:not(.show_if_header_custom),
    .goldsmith_product_sticky_header_counter_bgcolor_field:not(.show_if_header_custom),
    .goldsmith_product_sticky_header_counter_color_field:not(.show_if_header_custom) {
        display: none;
    }';
    // end $theCSS

    /* Add CSS to style.css */
    wp_register_style('goldsmith-admin-custom-style', false);
    wp_enqueue_style('goldsmith-admin-custom-style');
    wp_add_inline_style('goldsmith-admin-custom-style', $theCSS);
}
add_action('admin_enqueue_scripts', 'goldsmith_admin_custom_css');
