<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Ajax_Tab_Slider extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-tab-slider';
    }
    public function get_title() {
        return 'WC Tab Slider (N)';
    }
    public function get_icon() {
        return 'eicon-slider-push';
    }
    public function get_categories() {
        return [ 'goldsmith-woo' ];
    }
    public function get_style_depends() {
        return [ 'goldsmith-swiper','goldsmith-product-box-style' ];
    }
    public function get_script_depends() {
        return [ 'goldsmith-swiper','widget-tab-slider' ];
    }

    // Registering Controls
    protected function register_controls() {

        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'post_query_section',
            [
                'label' => esc_html__( 'QUERY', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'slider_title',
            [
                'label' => esc_html__( 'Slider Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'title_tag',
            [
                'label' => esc_html__( 'Title Tag', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h4',
                'options' => [
                    'h1' => esc_html__( 'H1', 'goldsmith' ),
                    'h2' => esc_html__( 'H2', 'goldsmith' ),
                    'h3' => esc_html__( 'H3', 'goldsmith' ),
                    'h4' => esc_html__( 'H4', 'goldsmith' ),
                    'h5' => esc_html__( 'H5', 'goldsmith' ),
                    'h6' => esc_html__( 'H6', 'goldsmith' ),
                    'div' => esc_html__( 'div', 'goldsmith' ),
                    'p' => esc_html__( 'p', 'goldsmith' )
                ],
                'condition' => [ 'slider_title!' => '' ]
            ]
        );
        $this->add_control( 'title_position',
            [
                'label' => esc_html__( 'Title Position', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'before' => esc_html__( 'Before Tabs', 'goldsmith' ),
                    'after' => esc_html__( 'After Tabs', 'goldsmith' ),
                    'block' => esc_html__( 'Top', 'goldsmith' ),
                ],
                'condition' => [ 'slider_title!' => '' ]
            ]
        );
        $repeater = new Repeater();
        $repeater->add_control( 'title',
            [
                'label' => esc_html__( 'Tab Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Tab Title',
                'label_block' => true,
            ]
        );
        $repeater->add_control( 'category',
            [
                'label' => esc_html__( 'Category', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $this->goldsmith_cpt_taxonomies('product_cat'),
                'description' => esc_html__( 'Select Category', 'goldsmith' ),
            ]
        );
        $repeater->add_control( 'post_per_page',
            [
                'label' => esc_html__( 'Posts Per Page for This Tab', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'default' => 10,
                'separator' => 'before'

            ]
        );
        $repeater->add_control( 'order',
            [
                'label' => esc_html__( 'Select Order', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ASC' => esc_html__( 'Ascending', 'goldsmith' ),
                    'DESC' => esc_html__( 'Descending', 'goldsmith' )
                ],
                'default' => 'DESC'
            ]
        );
        $repeater->add_control( 'orderby',
            [
                'label' => esc_html__( 'Order By', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'id' => esc_html__( 'Post ID', 'goldsmith' ),
                    'menu_order' => esc_html__( 'Menu Order', 'goldsmith' ),
                    'rand' => esc_html__( 'Random', 'goldsmith' ),
                    'date' => esc_html__( 'Date', 'goldsmith' ),
                    'title' => esc_html__( 'Title', 'goldsmith' ),
                ],
                'default' => 'id'
            ]
        );
        $this->add_control( 'tabs',
            [
                'label' => esc_html__( 'Tabs', 'goldsmith' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{title}}',
                //'default' => ['']
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'goldsmith-mini'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'slider_settings_section',
            [
                'label' => esc_html__( 'SLIDER OPTIONS', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'perview',
            [
                'label' => esc_html__( 'Per View ( Desktop )', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 6,
                'step' => 1,
                'default' => 3
            ]
        );
        $this->add_control( 'mdperview',
            [
                'label' => esc_html__( 'Per View ( Tablet )', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->add_control( 'smperview',
            [
                'label' => esc_html__( 'Per View  ( Mobile )', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control( 'speed',
            [
                'label' => esc_html__( 'Speed', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5000,
                'step' => 100,
                'default' => 1000,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control( 'navs',
            [
                'label' => esc_html__( 'Nav', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control( 'dots',
            [
                'label' => esc_html__( 'Dots', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'space',
            [
                'label' => esc_html__( 'Space Between Items', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 30
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('navs_style_section',
            [
                'label'=> esc_html__( 'SLIDER NAV STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [ 'nav' => 'yes' ]
            ]
        );
        $this->add_control( 'navs_size',
            [
                'label' => esc_html__( 'Size', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 300,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-swiper-prev,{{WRAPPER}} .goldsmith-swiper-next' => 'width:{{SIZE}}px;height:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'navs_arrow_size',
            [
                'label' => esc_html__( 'Arrow Size', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-swiper-prev:after,{{WRAPPER}} .goldsmith-swiper-next:after' => 'font-size:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'navs_color',
            [
                'label' => esc_html__( 'Arrow Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-prev:after,{{WRAPPER}} .goldsmith-swiper-next:after' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_hvrcolor',
            [
                'label' => esc_html__( 'Hover Arrow Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-prev:hover:after,{{WRAPPER}} .goldsmith-swiper-next:hover:after' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-nav-bg' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_hvrbgcolor',
            [
                'label' => esc_html__( 'Hover Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-nav-bg:hover' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'navs_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-nav-bg',
            ]
        );
        $this->add_control( 'navs_hvrbrdcolor',
            [
                'label' => esc_html__( 'Hover Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-nav-bg:hover' => 'border-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'navs_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-nav-bg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('dots_style_section',
            [
                'label'=> esc_html__( 'SLIDER DOTS STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [ 'dots' => 'yes' ]
            ]
        );
        $this->add_control( 'dots_top_offset',
            [
                'label' => esc_html__( 'Top Offset', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullets' => 'margin-top:{{SIZE}}px;' ]
            ]
        );
        $this->add_responsive_control( 'dots_alignment',
            [
                'label' => esc_html__( 'Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'goldsmith' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'goldsmith' ),
                        'icon' => 'eicon-h-align-center'
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'goldsmith' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullets' => 'text-align:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'dots_size',
            [
                'label' => esc_html__( 'Size', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-swiper-bullet:before' => 'width:{{SIZE}}px;height:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'dots_space',
            [
                'label' => esc_html__( 'Space', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-swiper-bullet + .goldsmith-swiper-bullet' => 'margin: 0;margin-left: {{SIZE}}px;',
                ]
            ]
        );
        $this->start_controls_tabs( 'dots_nav_tabs');
        $this->start_controls_tab( 'dots_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'goldsmith' ) ]
        );
        $this->add_control( 'dots_bgcolor',
            [
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-bullet:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-swiper-bullet',
            ]
        );
        $this->add_responsive_control( 'dots_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-swiper-bullet:before,
                    {{WRAPPER}} .goldsmith-swiper-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'dots_hover_tab',
            [ 'label' => esc_html__( 'Active', 'goldsmith' ) ]
        );
        $this->add_control( 'dots_hvrbgcolor',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-swiper-bullet.active:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_hvrborder',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-swiper-bullet.active'
            ]
        );
        $this->add_responsive_control( 'dots_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-swiper-bullet.active:before,
                    {{WRAPPER}} .goldsmith-swiper-bullet.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('tab_style_section',
            [
                'label'=> esc_html__( 'TAB STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_responsive_control( 'section_title_divider',
            [
                'label' => esc_html__( 'SECTION TITLE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'selectors' => ['{{WRAPPER}} .goldsmith-tab-nav' => 'justify-content: {{VALUE}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'slider_title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-section-title',
                'condition' => [ 'slider_title!' => '' ]
            ]
        );
        $this->add_control( 'slider_title_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-section-title' => 'color:{{VALUE}};' ],
                'condition' => [ 'slider_title!' => '' ]
            ]
        );
        $this->add_responsive_control( 'title_spacing',
            [
                'label' => esc_html__( 'Title Spacing ( px )', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 300]
                ],
				'default' => [
					'unit' => 'px',
					'size' => 30
				],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-section-title-wrapper.title-top' => 'margin-bottom: {{SIZE}}px;'
                ],
                'condition' => [ 'slider_title!' => '' ]
            ]
        );
        $this->add_responsive_control( 'title_alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'goldsmith' ),
                        'icon' => 'eicon-text-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'goldsmith' ),
                        'icon' => 'eicon-text-align-center'
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'goldsmith' ),
                        'icon' => 'eicon-text-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-section-title-wrapper.title-top' => 'text-align: {{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'tabs_nav_divider',
            [
                'label' => esc_html__( 'TABS STYLE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $this->add_responsive_control( 'alignment',
            [
                'label' => esc_html__( 'Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .goldsmith-tab-nav' => 'justify-content: {{VALUE}};'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'goldsmith' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'goldsmith' ),
                        'icon' => 'eicon-h-align-center'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'goldsmith' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => ''
            ]
        );
        $this->add_responsive_control( 'tab_clr',
           [
               'label' => esc_html__( 'Color', 'goldsmith' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => ['{{WRAPPER}} .goldsmith-tab-nav-item' => 'color: {{VALUE}};']
            ]
        );
        $this->add_responsive_control( 'tab_hvrclr',
           [
               'label' => esc_html__( 'Hover/Active Color', 'goldsmith' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .goldsmith-tab-nav-item:hover,{{WRAPPER}} .goldsmith-tab-nav-item.is-active ' => 'color: {{VALUE}};',
                   '{{WRAPPER}} .goldsmith-tab-nav-item:after' => 'background-color: {{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control( 'tab_spacing',
            [
                'label' => esc_html__( 'Space', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 200,
                'step' => 1,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-tab-nav-item + .goldsmith-tab-nav-item' => 'margin-left: {{SIZE}}px;']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-tab-nav-item'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('post_style_section',
            [
                'label' => esc_html__( 'POST STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_responsive_control( 'post_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-loop-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'post_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-loop-product' => 'background-color: {{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'post_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-loop-product'
            ]
        );
        $this->add_control( 'title_heading',
            [
                'label' => esc_html__( 'TITLE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-loop-product .goldsmith-product-name'
            ]
        );
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-loop-product .goldsmith-product-name' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'price_heading',
            [
                'label' => esc_html__( 'PRICE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'price_color',
            [
                'label' => esc_html__( 'Price Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-loop-product span.del > span' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'price_color2',
            [
                'label' => esc_html__( 'Price Color 2', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-loop-product .goldsmith-price' => 'color: {{VALUE}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-loop-product .goldsmith-price'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }
    public function image_custom_size() {
        $settings = $this->get_settings_for_display();
        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : goldsmith_settings('product_imgsize','goldsmith-mini');
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size = [ $sizew, $sizeh ];
        }
        return $size;
    }
    protected function render() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        $settings  = $this->get_settings_for_display();
        $elementid = $this->get_id();

        $speed     = $settings['speed'] ? $settings['speed'] : 1000;
        $perview   = $settings['perview'] ? $settings['perview'] : 3;
        $mdperview = $settings['mdperview'] ? $settings['mdperview'] : 3;
        $smperview = $settings['smperview'] ? $settings['smperview'] : 2;
        $space     = $settings['space'] ? $settings['space'] : 15;
        $id        = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-edit-mode' : '';
        $count     = 1;
        $counttwo  = 1;
        
        add_filter( 'single_product_archive_thumbnail_size', [$this, 'image_custom_size' ] );

        echo '<div class="goldsmith-wc-tab-slider'.$id.' goldsmith-swiper-slider-wrapper">';
        	if ( $settings['slider_title'] && 'block' == $settings['title_position'] ) {
				echo '<div class="goldsmith-section-title-wrapper title-top">';
	                echo '<'.$settings['title_tag'].' class="goldsmith-section-title">'.$settings['slider_title'].'</'.$settings['title_tag'].'>';
	            echo '</div>';
        	}
            echo '<div class="goldsmith-tabs-wrapper">';
                if ( $settings['tabs'] ) {

                    echo '<div class="goldsmith-tab-nav-wrapper title-'.$settings['title_position'].'">';
                    	if ( $settings['slider_title'] && 'before' == $settings['title_position'] ) {
            				echo '<div class="goldsmith-section-title-wrapper">';
            	                echo '<'.$settings['title_tag'].' class="goldsmith-section-title">'.$settings['slider_title'].'</'.$settings['title_tag'].'>';
            	            echo '</div>';
                    	}
                        echo '<div class="goldsmith-tab-nav">';
                            foreach ( $settings['tabs'] as $tab ) {
                                $terms = json_encode(
                                    array(
                                        'ajaxurl'  => admin_url( 'admin-ajax.php' ),
                                        'id'       => $tab['category'],
                                        'per_page' => $tab['post_per_page'],
                                        'order'    => $tab['order'],
                                        'orderby'  => $tab['orderby'],
                                        'imgsize'  => $this->image_custom_size()
                                    )
                                );
                                $is_active = 1 == $count ? ' is-active loaded' : '';
                                if ( $tab['title'] ) {
    
                                    echo '<span class="goldsmith-tab-nav-item'.$is_active.'" data-tab-terms=\''.$terms.'\'>'.$tab['title'].'</span>';
                                }
                                $count++;
                            }
                        echo '</div>';
                        
                    	if ( $settings['slider_title'] && 'after' == $settings['title_position'] ) {
            				echo '<div class="goldsmith-section-title-wrapper">';
            	                echo '<'.$settings['title_tag'].' class="goldsmith-section-title">'.$settings['slider_title'].'</'.$settings['title_tag'].'>';
            	            echo '</div>';
                    	}
                        
                    echo '</div>';
                }

                foreach ( $settings['tabs'] as $tab ) {
                    $cat = $tab['category'];
                    $slider_options = json_encode(array(
                        "autoHeight"    => false,
                        "slidesPerView" => 1,
                        "spaceBetween"  => $space,
                        "speed"         => $speed,
                        "loop"          => false,
                        "rewind"        => true,
                        "autoplay"      => 'yes' == $settings['autoplay'] ? [ "pauseOnMouseEnter" => true,"disableOnInteraction" => false ] : false,
                        "wrapperClass"  => "goldsmith-swiper-wrapper",
                        "navigation"    => [
                            "nextEl" => ".slide-prev-$cat",
                            "prevEl" => ".slide-next-$cat"
                        ],
                        "pagination" => [
                            "el"                => ".goldsmith-pagination-$cat",
                            "bulletClass"       => "goldsmith-swiper-bullet",
                            "bulletActiveClass" => "active",
                            "type"              => "bullets",
                            "clickable"         => true
                        ],
                        "breakpoints" => [
                            "0" => [
                                "slidesPerView"  => $smperview,
                                "slidesPerGroup" => $smperview
                            ],
                            "768" => [
                                "slidesPerView"  => $mdperview,
                                "slidesPerGroup" => $mdperview
                            ],
                            "1024" => [
                                "slidesPerView"  => $perview,
                                "slidesPerGroup" => $perview
                            ]
                        ]
                    ));
                    
                    $is_active = 1 == $counttwo ? ' is-active loaded' : '';
                    echo '<div class="goldsmith-tab-slider goldsmith-tab-page'.$is_active.'" data-cat-id="'.$cat.'">';
                        echo '<div class="thm-tab-slider goldsmith-swiper-slider goldsmith-swiper-container nav-vertical-centered" data-swiper-options=\''.$slider_options.'\'>';
                            echo '<div class="goldsmith-swiper-wrapper">';
                                $args = array(
                                    'post_type'      => 'product',
                                    'posts_per_page' => $tab['post_per_page'],
                                    'order'          => $tab['order'],
                                    'orderby'        => $tab['orderby'],
                                    'tax_query'      => array(
                                        array(
                                            'taxonomy' => 'product_cat',
                                            'field' => 'id',
                                            'terms' => $cat
                                        )
                                    )
                                );
                                
                                $the_query = new \WP_Query( $args );
                                if ( $the_query->have_posts() && 1 == $counttwo ) {
                                    while ( $the_query->have_posts() ) {
                                        $the_query->the_post();
                                        $product = new \WC_Product(get_the_ID());
                                        $visibility = $product->get_catalog_visibility();

                                        if ( $product->is_visible() ) {
                                            echo '<div class="swiper-slide product_item visibility-'.$visibility.'">';
                                                wc_get_template_part( 'content', 'product' );
                                            echo '</div>';
                                        }
                                    }
                                }
                                wp_reset_postdata();
                            echo '</div>';
                            
                            if ( 'yes' == $settings['dots'] ) {
                                echo '<div class="goldsmith-swiper-pagination goldsmith-pagination-'.$cat.' position-relative"></div>';
                            }
                            
                            if ( 'yes' == $settings['navs'] ) {
                                echo '<div class="goldsmith-swiper-prev goldsmith-nav-bg goldsmith-nav-small slide-prev-'.$cat.'"></div>';
                                echo '<div class="goldsmith-swiper-next goldsmith-nav-bg goldsmith-nav-small slide-next-'.$cat.'"></div>';
                            }
                            
                        echo '</div>';
                        echo '<div class="loading-wrapper"><span class="ajax-loading"></span></div>';
                    echo '</div>';
                    $counttwo++;
                }
            echo '</div>';
        echo '</div>';

        remove_filter( 'single_product_archive_thumbnail_size', [$this, 'image_custom_size' ] );
        
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
            <script>
                jQuery(document).ready(function($) {
                    $('.goldsmith-wc-tab-slider-edit-mode').each(function () {
                        var myWrapper = $( this ),
                            ajaxTab   = myWrapper.find('.goldsmith-tab-nav-item:not(.loaded)'),
                            loadedTab = myWrapper.find('.goldsmith-tab-nav-item');
                
                        myWrapper.find('.goldsmith-tab-slider.is-active .thm-tab-slider').each(function (el,i) {
                            let mySwiper = new NTSwiper(this, JSON.parse(this.dataset.swiperOptions));
                        });
                
                        loadedTab.on('click', function(event){
                            var $this = $(this),
                                terms = $this.data('tab-terms'),
                                id    = terms.id;
                            myWrapper.find('.goldsmith-tab-nav-item').removeClass('is-active');
                            $this.addClass('is-active');
                            $('.goldsmith-tab-slider:not([data-cat-id="'+id+'"])').removeClass('is-active');
                            $('.goldsmith-tab-slider[data-cat-id="'+id+'"]').addClass('is-active');
                        });
                        
                        var height = myWrapper.find('.goldsmith-tabs-wrapper .thm-tab-slider').height();
                        
                        ajaxTab.on('click', function(event){
                            var $this    = $(this),
                                terms    = $this.data('tab-terms'),
                                cat_id   = terms.id,
                                per_page = terms.per_page,
                                order    = terms.order,
                                orderby  = terms.orderby,
                                imgsize  = terms.imgsize,
                                ajaxurl  = terms.ajaxurl,
                                data     = {
                                    action     : 'goldsmith_ajax_tab_slider',
                                    cat_id     : cat_id,
                                    per_page   : per_page,
                                    order      : order,
                                    orderby    : orderby,
                                    img_size   : imgsize,
                                    beforeSend : function() {
                                        $('.goldsmith-tab-slider[data-cat-id="'+cat_id+'"]').css('min-height', height ).addClass('tab-loading');
                                        myWrapper.find('.goldsmith-tab-nav-item').removeClass('is-active');
                                        $this.addClass('is-active');
                                    }
                                };
                                
                            if ( !$this.hasClass('loaded') && $('.goldsmith-tab-slider:not([data-cat-id="'+cat_id+'"])').length ) {
                
                                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                                $.post(ajaxurl, data, function(response) {
                                    
                                    $('.goldsmith-tab-slider:not([data-cat-id="'+cat_id+'"])').removeClass('is-active');
                                    $('.goldsmith-tab-slider[data-cat-id="'+cat_id+'"]').addClass('is-active loaded');
                                    $('.goldsmith-tab-slider[data-cat-id="'+cat_id+'"] .goldsmith-swiper-wrapper').append(response);
                
                                    $this.addClass('loaded');
                
                                    $('.goldsmith-tab-slider[data-cat-id="'+cat_id+'"] .thm-tab-slider').each(function () {
                                        const options = JSON.parse(this.dataset.swiperOptions);
                                        var mySwiper  = new NTSwiper( this, options );
                                        $('body').trigger('goldsmith_lazy_load');
                                    });
                
                                    $('.goldsmith-tab-slider[data-cat-id="'+cat_id+'"] .variations_form').each(function () {
                                        $(this).wc_variation_form();
                                    });
                                    
                                    $('.goldsmith-tab-slider[data-cat-id="'+cat_id+'"]').removeClass('tab-loading');
                                    
                                    $(document.body).trigger('goldsmith_quick_shop');
                                    $('body').trigger('goldsmith_quick_init');
                                    $(document.body).trigger('goldsmith_variations_init');
                                });
                            }
                        });
                    });
                });
            </script>
            <?php
        }

    }
}
