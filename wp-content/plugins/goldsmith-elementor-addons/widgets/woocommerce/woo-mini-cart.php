<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Minicart extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-mini-cart';
    }
    public function get_title() {
        return 'WC Header Cart (N)';
    }
    public function get_icon() {
        return 'eicon-cart';
    }
    public function get_categories() {
        return [ 'goldsmith-woo' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'general_section',
            [
                'label' => esc_html__( 'Cart Buttons', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'myaccount_btn',
            [
                'label' => esc_html__( 'Myaccount Button?', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_control( 'myaccount_url',
            [
                'label' => esc_html__( 'Custom Myaccount Link', 'goldsmith' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '',
                    'is_external' => ''
                ],
                'show_external' => true,
                'condition' => ['myaccount_btn' => 'yes']
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'style_section',
            [
                'label' => esc_html__( 'Style', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} span.header_cart_label_icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .widget-header-action svg' => 'fill: {{VALUE}};'
                ]
            ]
        );
        $this->add_control( 'icon_hvrcolor',
            [
                'label' => esc_html__( 'Hover Icon Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} span.header_cart_label_icon:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .widget-header-action li:hover svg' => 'fill: {{VALUE}};'
                ]
            ]
        );
        $this->add_control( 'icon_text_color',
            [
                'label' => esc_html__( 'Price Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .cart-total-price' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'icon_counter_bgcolor',
            [
                'label' => esc_html__( 'Counter Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .header-shop-cart a span.cart-count' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .header-shop-wishlist.woosw-menu-item .woosw-menu-item-inner:after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .header-shop-compare.woosc-menu-item .woosc-menu-item-inner:after' => 'background-color: {{VALUE}};'
                ]
            ]
        );
        $this->add_control( 'icon_counter_color',
            [
                'label' => esc_html__( 'Count Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .header-shop-cart a span.cart-count' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .header-shop-wishlist.woosw-menu-item .woosw-menu-item-inner:after' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .header-shop-compare.woosc-menu-item .woosc-menu-item-inner:after' => 'color: {{VALUE}};'
                ]
            ]
        );
        $this->add_control( 'cart_tooltip_bgcolor',
            [
                'label' => esc_html__( 'Tooltip Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .hint--top:after' => 'background-color:{{VALUE}};',
                    '{{WRAPPER}} .hint--top:before' => 'border-top-color:{{VALUE}};',
                ]
            ]
        );
        $this->add_control( 'cart_tooltip_color',
            [
                'label' => esc_html__( 'Tooltip Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .hint--top:after, {{WRAPPER}} .hint--top:before' => 'color:{{VALUE}};' ]
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        $settings = $this->get_settings_for_display();

        $count = WC()->cart? WC()->cart->cart_contents_count : '0';
        $total = WC()->cart ? WC()->cart->subtotal : '';

        echo'<div class="header-action widget-header-action">';
            echo'<ul>';
                if ( class_exists( 'WooCommerce' ) && 'yes' == $settings['myaccount_btn'] ) {
                    $myaccount = esc_html__( 'My Account', 'goldsmith' );
                    $redirecturl = $settings['myaccount_url'] ? array( 'redirect' => $settings['myaccount_url'] ) : '';
                    $myaccount_url = $settings['myaccount_url']['url'] ? $settings['myaccount_url']['url'] : wc_get_page_permalink( 'myaccount' );
                    echo'<li class="header-shop-account">';
                        $url = wc_get_page_permalink( 'myaccount' );
                        echo'<a class="goldsmith_header_account hint--top user goldsmith-open-popup" href="#goldsmith_myaccount" data-label="'.$myaccount.'">';
                            echo goldsmith_svg_lists( 'user-1' );
                        echo'</a>';
                        echo'<div class="goldsmith_mini_account_form zoom-anim-dialog mfp-hide" id="goldsmith_myaccount">';
                            if ( is_user_logged_in() ) {
                                echo'<nav class="menu-form menu_logged_in">';
                                    echo'<div class="account-dropdown">';
                                        echo do_shortcode('[woocommerce_my_account]');
                                    echo'</div>';
                                echo'</nav>';
                            } else {
                                echo'<div class="account-dropdown">';
                                    echo'<div class="account-wrap">';
                                        echo'<div class="login-form-head">';
                                            echo'<span class="login-form-title">'.esc_html__( 'Sign in', 'goldsmith' ).'</span>';
                                            echo'<span class="register-form-title">';
                                            echo'<a class="register-link" href="'.$url.'" title="'.esc_html__( 'Register', 'goldsmith' ).'">'.esc_html__( 'Create an Account', 'goldsmith' ).'</a>';
                                            echo'</span>';
                                        echo'</div>';
                                        $redirecturl = !empty( $settings['link']['url'] ) ? array( 'redirect' => $settings['link']['url'] ) : '';
                                        woocommerce_login_form( $redirecturl );
                                    echo'</div>';
                                echo'</div>';
                            }
                        echo'</div>';
                    echo'</li>';
                }
                if ( '1' != goldsmith_settings( 'woo_catalog_mode', '0' ) ) {
                    if ( shortcode_exists( 'woosc' ) ) {
                        $clabel = esc_html__('Compare', 'goldsmith');
                        echo'<li class="header-shop-compare woosc-menu-item">';
                            echo goldsmith_svg_lists( 'compare' );
                            echo'<a href="#" class="open-compare-btn hint--top" data-label="'.$clabel.'"></a>';
                        echo'</li>';
                    }

                    if ( class_exists('WPCleverWoosw') ) {
                        $url = \WPcleverWoosw::get_url();
                        $count = \WPcleverWoosw::get_count();
                        $wlabel = esc_html__('Wishlist', 'goldsmith');
                        echo'<li class="header-shop-wishlist menu-item woosw-menu-item menu-item-type-woosw">';
                            echo goldsmith_svg_lists( 'love' );
                            echo'<a class="goldsmith-wishlist-link hint--top" href="'.$url.'" data-label="'.$wlabel.'"><span class="woosw-menu-item-inner" data-count="'.$count.'"></span></a>';
                        echo'</li>';
                    }

                    $cartlabel = esc_html__('Cart', 'goldsmith');
                    echo'<li class="header-shop-cart">';
                        echo'<a class="goldsmith-cart-btn hint--top" href="'.wc_get_cart_url().'" data-label="'.$cartlabel.'">';
                                echo goldsmith_svg_lists( 'bag' );
                                echo'<span class="goldsmith-cart-count cart-count">'.esc_html( $count ).'</span>';
                        echo'</a>';
                            if ( $total ) {
                                echo'<span class="goldsmith-cart-total-price cart-total-price">'.wc_price( $total ).'</span>';
                            }
                        echo get_template_part( 'woocommerce/minicart/header', 'minicart' );
                    echo'</li>';
                }
            echo'</ul>';
        echo'</div>';

    }
}
