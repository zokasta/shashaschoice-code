<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Core\Base\Document;
use Elementor\Core\Base\Module as BaseModule;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Core\DocumentTypes\PageBase as PageBase;
use Elementor\Modules\Library\Documents\Page as LibraryPageDocument;

if( !defined( 'ABSPATH' ) ) exit;

class Goldsmith_Customizing_Page_Settings {
    use Goldsmith_Helper;
    private static $instance = null;

    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new Goldsmith_Customizing_Page_Settings();
        }
        return self::$instance;
    }

    public function __construct() {
        if ( !is_customize_preview() ) {
            add_action( 'elementor/documents/register_controls',[ $this,'goldsmith_page_settings'], 10 );
        }
    }

    public function goldsmith_page_settings( $document )
    {
        $document->start_controls_section( 'goldsmith_page_header_settings',
            [
                'label' => esc_html__( 'GOLDSMITH PAGE HEADER-FOOTER', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );
        $document->add_control( 'goldsmith_page_header_settings_heading',
            [
                'label' => esc_html__( 'GOLDSMITH PAGE HEADER', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'goldsmith_page_header_bg_type',
            [
                'label' => esc_html__( 'Header Background Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'multiple' => false,
                'options' => array(
                    '' => esc_html__( 'Select an option', 'goldsmith' ),
                    'default' => esc_html__( 'Deafult', 'goldsmith' ),
                    'dark' => esc_html__( 'Dark', 'goldsmith' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'goldsmith' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'goldsmith' )
                )
            ]
        );
        $document->add_control( 'goldsmith_page_header_template',
            [
                'label' => esc_html__( 'Select Header Template', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => false,
                'options' => $this->goldsmith_get_elementor_templates()
            ]
        );
        $document->add_control( 'goldsmith_page_footer_settings_heading',
            [
                'label' => esc_html__( 'GOLDSMITH PAGE FOOTER', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $document->add_control( 'goldsmith_page_footer_template',
            [
                'label' => esc_html__( 'Select Footer Template', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => false,
                'options' => $this->goldsmith_get_elementor_templates()
            ]
        );
        $document->end_controls_section();

        $document->start_controls_section( 'goldsmith_page_header_logo_settings',
            [
                'label' => esc_html__( 'GOLDSMITH PAGE HEADER LOGO', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );
        $document->add_control( 'goldsmith_page_header_logo_update',
            [
                'label' => '<div class="elementor-update-preview" style="background-color: #fff;display: block;"><div class="elementor-update-preview-button-wrapper" style="display:block;"><button class="elementor-update-preview-button elementor-button elementor-button-success" style="background: #d30c5c; margin: 0 auto; display:block;">Apply Changes</button></div><div class="elementor-update-preview-title" style="display:block;text-align:center;margin-top: 10px;">Update changes to pages</div></div>',
                'type' => Controls_Manager::RAW_HTML
            ]
        );
        $document->add_control( 'goldsmith_page_header_logo',
            [
                'label' => esc_html__( 'Logo', 'goldsmith' ),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => '']
            ]
        );
        $document->add_control( 'goldsmith_page_header_logo_max_width',
            [
                'label' => esc_html__( 'Image Max-Width', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 1000]
                ],
                'selectors' => [ '{{WRAPPER}} .nt-logo.header-logo .main-logo:not(.sticky-logo)' => 'max-width: {{SIZE}}{{UNIT}};' ]
            ]
        );
        $document->add_control( 'goldsmith_page_header_logo_max_height',
            [
                'label' => esc_html__( 'Image Max-Height', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 1000]
                ],
                'selectors' => [ '{{WRAPPER}} .nt-logo.header-logo .main-logo:not(.sticky-logo)' => 'max-height: {{SIZE}}{{UNIT}};' ]
            ]
        );
        $document->add_control( 'goldsmith_page_header_sticky_logo',
            [
                'label' => esc_html__( 'Sticky Logo', 'goldsmith' ),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => '']
            ]
        );
        $document->add_control( 'goldsmith_page_header_sticky_logo_max_width',
            [
                'label' => esc_html__( 'Sticky Logo Max-Width', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 1000],
                ],
                'selectors' => [ '{{WRAPPER}} .nt-logo.header-logo .main-logo.sticky-logo' => 'max-width: {{SIZE}}{{UNIT}};' ]
            ]
        );
        $document->add_control( 'goldsmith_page_header_sticky_logo_max_height',
            [
                'label' => esc_html__( 'Sticky Logo Max-Height', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 1000]
                ],
                'selectors' => [ '{{WRAPPER}} .nt-logo.header-logo .main-logo.sticky-logo' => 'max-height: {{SIZE}}{{UNIT}};' ]
            ]
        );
        $document->add_control( 'goldsmith_page_header_text_logo_color',
            [
                'label' => esc_html__( 'Text Logo Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .nt-logo.header-logo .header-text-logo' => 'color:{{VALUE}};' ]
            ]
        );
        $document->add_control( 'goldsmith_page_header_sticky_text_logo_color',
            [
                'label' => esc_html__( 'Sticky Text Logo Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}}.scroll-start .nt-logo.header-logo .header-text-logo' => 'color:{{VALUE}};' ]
            ]
        );
        $document->end_controls_section();

        $document->start_controls_section( 'goldsmith_page_header_customize_settings',
            [
                'label' => esc_html__( 'GOLDSMITH PAGE HEADER CUSTOMIZE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );
        $document->add_control( 'goldsmith_page_header_bgcolor',
            [
                'label' => esc_html__( 'Header Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.has-default-header-type-default header.goldsmith-header-default,
                    {{WRAPPER}}.has-default-header-type-dark header.goldsmith-header-default,
                    {{WRAPPER}} .goldsmith-header-top-menu-area ul li .submenu,
                    {{WRAPPER}} .goldsmith-header-top-menu-area ul li>.item-shortcode-wrapper,
                    {{WRAPPER}} .goldsmith-header-wc-categories .submenu,
                    {{WRAPPER}} .goldsmith-header-mobile-top,
                    {{WRAPPER}} .goldsmith-header-mobile,
                    {{WRAPPER}}.has-default-header-type-trans header.goldsmith-header-default' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_header_menu_settings',
            [
                'label' => esc_html__( 'Menu Items', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'goldsmith_page_header_menu_item_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-top-menu-area>ul>li.menu-item>a,
                    {{WRAPPER}}.has-default-header-type-trans:not(.scroll-start) .goldsmith-header-top-menu-area>ul>li.menu-item>a,
                    {{WRAPPER}} .goldsmith-header-wc-categories .product_cat,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu .sliding-menu-inner li a,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li .sliding-menu__nav,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li.current-menu-parent>.sliding-menu__nav,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu .sliding-menu__back:before,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu .sliding-menu__nav:before,
                    {{WRAPPER}} .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_header_menu_item_hvrcolor',
            [
                'label' => esc_html__( 'Color ( Hover/Active )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-top-menu-area>ul>li.menu-item:hover>a,
                    {{WRAPPER}}.has-default-header-type-trans:not(.scroll-start) .goldsmith-header-top-menu-area>ul>li.menu-item:hover>a,
                    {{WRAPPER}} .goldsmith-header-top-menu-area>ul>li.menu-item.active>a,
                    {{WRAPPER}}.has-default-header-type-trans:not(.scroll-start) .goldsmith-header-top-menu-area>ul>li.menu-item.active>a,
                    {{WRAPPER}} .current-menu-parent>a,
                    {{WRAPPER}} .current-menu-item>a,
                    {{WRAPPER}} .goldsmith-header-top-menu-area>ul>li.menu-item.active>a,
                    {{WRAPPER}} .goldsmith-header-top-menu-area>ul>li.menu-item>a:hover,
                    {{WRAPPER}} .goldsmith-header-wc-categories .product_cat:hover,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li.current-menu-item>.sliding-menu__nav:hover,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li.current-menu-item>a:hover,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li a:hover,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li.active a,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li .sliding-menu__nav:hover,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu .sliding-menu__back:hover:before,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu .sliding-menu__nav:hover:before,
                    {{WRAPPER}} .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_header_settings',
            [
                'label' => esc_html__( 'STICKY HEADER', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_header_bgcolor',
            [
                'label' => esc_html__( 'Sticky Header Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start header.goldsmith-header-default,
                    {{WRAPPER}}.scroll-start .goldsmith-header-top-menu-area ul li .submenu,
                    {{WRAPPER}}.scroll-start .goldsmith-header-top-menu-area ul li>.item-shortcode-wrapper,
                    {{WRAPPER}}.scroll-start .goldsmith-header-wc-categories .submenu,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile-top,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile,
                    {{WRAPPER}}.has-default-header-type-trans.scroll-start header.goldsmith-header-default' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_header_menu_settings',
            [
                'label' => esc_html__( 'Sticky Menu Items', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_header_menu_item_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item>a,
                    {{WRAPPER}}.has-default-header-type-trans.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item>a,
                    {{WRAPPER}}.scroll-start .goldsmith-header-wc-categories .product_cat,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu .sliding-menu-inner li a,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li .sliding-menu__nav,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li.current-menu-parent>.sliding-menu__nav,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu .sliding-menu__back:before,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu .sliding-menu__nav:before,
                    {{WRAPPER}}.scroll-start .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_header_menu_item_hvrcolor',
            [
                'label' => esc_html__( 'Color ( Hover/Active )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item:hover>a,
                    {{WRAPPER}}.has-default-header-type-trans.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item:hover>a,
                    {{WRAPPER}}.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item.active>a,
                    {{WRAPPER}}.has-default-header-type-trans.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item.active>a,
                    {{WRAPPER}}.scroll-start .current-menu-parent>a,
                    {{WRAPPER}}.scroll-start .current-menu-item>a,
                    {{WRAPPER}}.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item.active>a,
                    {{WRAPPER}}.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item>a:hover,
                    {{WRAPPER}}.scroll-start .goldsmith-header-wc-categories .product_cat:hover,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li.current-menu-item>.sliding-menu__nav:hover,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li.current-menu-item>a:hover,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li a:hover,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li.active a,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li .sliding-menu__nav:hover,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu .sliding-menu__back:hover:before,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu .sliding-menu__nav:hover:before,
                    {{WRAPPER}}.scroll-start .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_header_svg_icons_settings',
            [
                'label' => esc_html__( 'HEADER SVG ICONS', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $document->add_control( 'goldsmith_page_header_svg_icons_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} header.goldsmith-header-default .goldsmith-svg-icon,
                    {{WRAPPER}} .goldsmith-header-mobile-top .goldsmith-svg-icon' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_header_svg_counter_bgcolor',
            [
                'label' => esc_html__( 'Counter Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} header.goldsmith-header-default .goldsmith-wc-count,
                    {{WRAPPER}} .goldsmith-header-mobile-top .goldsmith-wc-count' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_header_svg_counter_color',
            [
                'label' => esc_html__( 'Counter Number Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} header.goldsmith-header-default .goldsmith-wc-count,
                    {{WRAPPER}} .goldsmith-header-mobile-top .goldsmith-wc-count' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_header_svg_icons_settings',
            [
                'label' => esc_html__( 'Sticky Header Color', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_header_svg_icons_color',
            [
                'label' => esc_html__( 'Sticky Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start header.goldsmith-header-default .goldsmith-svg-icon,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile-top .goldsmith-svg-icon' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_header_svg_counter_bgcolor',
            [
                'label' => esc_html__( 'Sticky Counter Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start header.goldsmith-header-default .goldsmith-wc-count,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile-top .goldsmith-wc-count' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_header_svg_counter_color',
            [
                'label' => esc_html__( 'Sticky Counter Number Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start header.goldsmith-header-default .goldsmith-wc-count,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile-top .goldsmith-wc-count' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->end_controls_section();

        $document->start_controls_section( 'goldsmith_page_mobile_header_customize_settings',
            [
                'label' => esc_html__( 'GOLDSMITH PAGE MOBILE HEADER', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );
        $document->add_control( 'goldsmith_page_mobile_header_bgcolor',
            [
                'label' => esc_html__( 'Header Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-top' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_mobile_header_toggle_btn_color',
            [
                'label' => esc_html__( 'Toggle Button Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-top .mobile-toggle' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_mobile_header_toggle_btn_hvrcolor',
            [
                'label' => esc_html__( 'Toggle Button Color ( Hover )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-top .mobile-toggle:hover' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_mobile_header_settings',
            [
                'label' => esc_html__( 'Sticky Header Color', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_mobile_header_bgcolor',
            [
                'label' => esc_html__( 'Header Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .goldsmith-header-mobile-top' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_mobile_header_toggle_btn_color',
            [
                'label' => esc_html__( 'Toggle Button Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .goldsmith-header-mobile-top .mobile-toggle' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_mobile_header_toggle_btn_hvrcolor',
            [
                'label' => esc_html__( 'Toggle Button Color ( Hover )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .goldsmith-header-mobile-top .mobile-toggle:hover' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_mobile_header_svg_icons_settings',
            [
                'label' => esc_html__( 'HEADER SVG ICONS', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $document->add_control( 'goldsmith_page_mobile_header_svg_icons_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-top .goldsmith-svg-icon' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_mobile_header_svg_counter_bgcolor',
            [
                'label' => esc_html__( 'Counter Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-top .goldsmith-wc-count' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_mobile_header_svg_counter_color',
            [
                'label' => esc_html__( 'Counter Number Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-top .goldsmith-wc-count' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_mobile_header_svg_icons_settings',
            [
                'label' => esc_html__( 'Sticky Header Color', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_mobile_header_svg_icons_color',
            [
                'label' => esc_html__( 'Sticky Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .goldsmith-header-mobile-top .goldsmith-svg-icon' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_mobile_header_svg_counter_bgcolor',
            [
                'label' => esc_html__( 'Sticky Counter Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .goldsmith-header-mobile-top .goldsmith-wc-count' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_mobile_header_svg_counter_color',
            [
                'label' => esc_html__( 'Sticky Counter Number Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .goldsmith-header-mobile-top .goldsmith-wc-count' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->end_controls_section();

        $document->start_controls_section( 'goldsmith_page_slide_menu_customize_settings',
            [
                'label' => esc_html__( 'GOLDSMITH PAGE MOBILE SLIDE MENU', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );
        $document->add_control( 'goldsmith_page_slide_menu_close_btn_bgcolor',
            [
                'label' => esc_html__( 'Close Button Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile .goldsmith-panel-close.no-bar,
                    {{WRAPPER}} .goldsmith-header-mobile .goldsmith-panel-close' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_menu_close_btn_color',
            [
                'label' => esc_html__( 'Close Button Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile .goldsmith-panel-close-button:before,
                    {{WRAPPER}} .goldsmith-header-mobile .goldsmith-panel-close-button:after,
                    {{WRAPPER}} .goldsmith-header-mobile .goldsmith-panel-close.no-bar:before,
                    {{WRAPPER}} .goldsmith-header-mobile .goldsmith-panel-close.no-bar:after' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_menu_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile' => 'background-color:{{VALUE}};',
                    '{{WRAPPER}} .goldsmith-header-mobile .goldsmith-header-mobile-content .action-content' => 'background-color:transparent;',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_menu_minibar_bgcolor',
            [
                'label' => esc_html__( 'Minibar Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile .goldsmith-header-mobile-sidebar' => 'background-color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_menu_minibar_svg_icons_color',
            [
                'label' => esc_html__( 'SVG Icon Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile .goldsmith-svg-icon' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_menu_minibar_svg_icons_hvrbgcolor',
            [
                'label' => esc_html__( 'SVG Icon Background Color ( Active )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile .sidebar-top-action .top-action-btn.active' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_menu_minibar_svg_icons_hvrcolor',
            [
                'label' => esc_html__( 'SVG Icon Color ( Active )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile .sidebar-top-action .top-action-btn.active' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_menu_minibar_svg_counter_bgcolor',
            [
                'label' => esc_html__( 'Icon Counter Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile .goldsmith-wc-count' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_menu_minibar_svg_counter_color',
            [
                'label' => esc_html__( 'Icon Counter Number Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile .goldsmith-wc-count' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_menu_items_settings',
            [
                'label' => esc_html__( 'Menu Items', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'goldsmith_page_slide_menu_item_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile .sliding-menu .sliding-menu-inner li a,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li .sliding-menu__nav,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li.current-menu-parent>.sliding-menu__nav,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu .sliding-menu__back:before,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu .sliding-menu__nav:before,
                    {{WRAPPER}} .goldsmith-header-mobile .account-area li.menu-item a' => 'color:{{VALUE}};'
                ]
            ]
        );

        $document->add_control( 'goldsmith_page_slide_menu_item_hvrcolor',
            [
                'label' => esc_html__( 'Color ( Hover/Active )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile .sliding-menu li.current-menu-item>.sliding-menu__nav:hover,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li.current-menu-item>a:hover,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li a:hover,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li.active a,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu li .sliding-menu__nav:hover,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu .sliding-menu__back:hover:before,
                    {{WRAPPER}} .goldsmith-header-mobile .sliding-menu .sliding-menu__nav:hover:before,
                    {{WRAPPER}} .goldsmith-header-mobile .account-area li.menu-item a:hover' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_slide_menu_settings',
            [
                'label' => esc_html__( 'STICKY HEADER', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_slide_menu_bgcolor',
            [
                'label' => esc_html__( 'Sticky Header Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .goldsmith-header-mobile' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_menu_back_brdcolor',
            [
                'label' => esc_html__( 'Border Separator Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .sliding-menu .sliding-menu__back:after' => 'border-bottom-color:{{VALUE}};',
                    '{{WRAPPER}} .goldsmith-sidemenu-lang-switcher' => 'border-top-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_slide_menu_items_settings',
            [
                'label' => esc_html__( 'Sticky Menu Items', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_slide_menu_item_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu .sliding-menu-inner li a,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li .sliding-menu__nav,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li.current-menu-parent>.sliding-menu__nav,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu .sliding-menu__back:before,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu .sliding-menu__nav:before,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .account-area li.menu-item a' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_slide_menu_item_hvrcolor',
            [
                'label' => esc_html__( 'Color ( Hover/Active )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li.current-menu-item>.sliding-menu__nav:hover,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li.current-menu-item>a:hover,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li a:hover,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li.active a,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu li .sliding-menu__nav:hover,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu .sliding-menu__back:hover:before,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .sliding-menu .sliding-menu__nav:hover:before,
                    {{WRAPPER}}.scroll-start .goldsmith-header-mobile .account-area li.menu-item a:hover' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_sticky_slide_menu_back_brdcolor',
            [
                'label' => esc_html__( 'Border Separator Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .sliding-menu .sliding-menu__back:after' => 'border-bottom-color:{{VALUE}};',
                    '{{WRAPPER}}.scroll-start .goldsmith-sidemenu-lang-switcher' => 'border-top-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_minibar_social_settings',
            [
                'label' => esc_html__( 'SOCIAL ICONS', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'goldsmith_page_minibar_social_color',
            [
                'label' => esc_html__( 'Minibar Social Icon Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .sidebar-bottom-socials a' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_minibar_social_hvrcolor',
            [
                'label' => esc_html__( 'Minibar Social Icon Color ( Hover )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .sidebar-bottom-socials a:hover' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_title_color_settings',
            [
                'label' => esc_html__( 'PANEL WOOCOMMERCE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_top_title_color',
            [
                'label' => esc_html__( 'Top Title Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .panel-top-title' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_top_title_brdcolor',
            [
                'label' => esc_html__( 'Top Title Border Bottom Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .panel-top-title:after' => 'border-bottom-color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_product_title_color',
            [
                'label' => esc_html__( 'Product Title Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .goldsmith-content-info .product-name' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_product_stock_color',
            [
                'label' => esc_html__( 'Product Stock Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .goldsmith-content-info .product-stock' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_product_addtocart_color',
            [
                'label' => esc_html__( 'Add to Cart Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .goldsmith-content-item .goldsmith-content-info .goldsmith-btn-small' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_product_trash_icon_color',
            [
                'label' => esc_html__( 'Trash Icon Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .goldsmith-content-item .goldsmith-svg-icon.mini-icon' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_product_subtotal_color',
            [
                'label' => esc_html__( 'Cart Subtotal Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .cart-area .cart-total-price' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_product_text_color',
            [
                'label' => esc_html__( 'Extra Text Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .minicart-extra-text' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_cart_buttons_settings',
            [
                'label' => esc_html__( 'Buttons', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_buttons_color',
            [
                'label' => esc_html__( 'Buttons Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .cart-bottom-btn .goldsmith-btn' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_buttons_hvrcolor',
            [
                'label' => esc_html__( 'Buttons Color ( Hover )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .cart-bottom-btn .goldsmith-btn:hover' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_buttons_bgcolor',
            [
                'label' => esc_html__( 'Buttons Backgroud Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .cart-bottom-btn .goldsmith-btn' => 'background-color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_buttons_hvrbgcolor',
            [
                'label' => esc_html__( 'Buttons Backgroud Color ( Hover )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .cart-bottom-btn .goldsmith-btn:hover' => 'background-color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_buttons_brdcolor',
            [
                'label' => esc_html__( 'Buttons Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .cart-bottom-btn .goldsmith-btn' => 'border-color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'goldsmith_page_slide_left_panel_buttons_hvrbrdcolor',
            [
                'label' => esc_html__( 'Buttons Border Color ( Hover )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-header-mobile-content .cart-bottom-btn .goldsmith-btn:hover' => 'border-color:{{VALUE}};',
                ]
            ]
        );
        $document->end_controls_section();

    }
}
Goldsmith_Customizing_Page_Settings::get_instance();
