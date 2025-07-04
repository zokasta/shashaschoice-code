<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Instagram_Slider extends Widget_Base {
    public function get_name() {
        return 'goldsmith-instagram-slider';
    }
    public function get_title() {
        return 'Instagram Carousel (N)';
    }
    public function get_icon() {
        return 'eicon-carousel';
    }
    public function get_categories() {
        return [ 'goldsmith' ];
    }
    public function get_script_depends() {
        return [ 'goldsmith-swiper' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'items_settings',
            [
                'label' => esc_html__('Instagram', 'goldsmith'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'data_source',
            [
                'label' => esc_html__( 'Source ', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'instagram',
                'options' => [
                    'instagram' => esc_html__( 'Instagram', 'goldsmith' ),
                    'custom' => esc_html__( 'Custom', 'goldsmith' ),
                ]
            ]
        );
        $this->add_control( 'token',
            [
                'label' => esc_html__( 'Access Token', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'condition' => [ 'data_source' => 'instagram' ]
            ]
        );
        $this->add_control( 'limit',
            [
                'label' => esc_html__( 'Limit', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -1,
                'max' => 100,
                'step' => 1,
                'default' => 6,
                'condition' => [ 'data_source' => 'instagram' ]
            ]
        );
        $this->add_control( 'target',
            [
                'label' => esc_html__( 'Open Link', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => '_self',
                'options' => [
                    '_blank' => esc_html__( 'New window or tab', 'goldsmith' ),
                    '_self' => esc_html__( 'In the same frame', 'goldsmith' )
                ],
                'condition' => [ 'data_source' => 'instagram' ]
            ]
        );
        $repeater = new Repeater();
        $repeater->add_control( 'image',
            [
                'label' => esc_html__( 'Image', 'goldsmith' ),
                'type' => Controls_Manager::MEDIA
            ]
        );
        $repeater->add_control( 'link',
            [
                'label' => esc_html__( 'Link', 'goldsmith' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '',
                    'is_external' => ''
                ],
                'show_external' => true,
            ]
        );
        $repeater->add_control( 'custom_text',
            [
                'label' => esc_html__( 'Custom text', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '#goldsmith',
            ]
        );
        $this->add_control( 'items',
            [
                'label' => esc_html__( 'Items', 'goldsmith' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => 'Image',
                'default' => [],
                'condition' => [ 'data_source' => 'custom' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
            'name' => 'thumbnail',
            'condition' => [ 'data_source' => 'custom' ]
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
                'default' => 'h3',
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
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'slider_title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-slider-nav-title',
                'condition' => [ 'slider_title!' => '' ]
            ]
        );
        $this->add_control( 'slider_title_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-slider-nav-title' => 'color:{{VALUE}};' ],
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
                    '{{WRAPPER}} .goldsmith-swiper-theme-style .goldsmith-slider-title-wrapper.nav-top' => 'margin-bottom: {{SIZE}}px;'
                ],
                'condition' => [ 'slider_title!' => '' ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/

        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('image_style_section',
            [
                'label'=> esc_html__( 'IMAGE STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_responsive_control( 'image_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .swiper-slide img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->start_controls_tabs( 'image_tabs');
        $this->start_controls_tab( 'image_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'goldsmith' ) ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .swiper-slide img'
            ]
        );
        $this->add_responsive_control( 'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .swiper-slide img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'image_hover_tab',
            [ 'label' => esc_html__( 'Active', 'goldsmith' ) ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_hvrborder',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .swiper-slide.swiper-slide-active img'
            ]
        );
        $this->add_responsive_control( 'image_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .swiper-slide.swiper-slide-active img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control( 'show_icon',
            [
                'label' => esc_html__( 'Show Instagram Icon', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control( 'icon_position',
            [
                'label' => esc_html__( 'Icon Position', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'top-left',
                'options' => [
                    'top-left' => esc_html__( 'Top-Left', 'goldsmith' ),
                    'top-right' => esc_html__( 'Top-Right', 'goldsmith' ),
                    'bottom-left' => esc_html__( 'Bottom-Left', 'goldsmith' ),
                    'bottom-right' => esc_html__( 'Bottom-Right', 'goldsmith' ),
                    'center' => esc_html__( 'Center', 'goldsmith' )
                ],
                'condition' => [ 'show_icon' => 'yes' ]
            ]
        );
        $this->add_responsive_control( 'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 300]
                ],
				'default' => [
					'unit' => 'px',
					'size' => 32
				],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-instagram-link svg' => 'max-width: {{SIZE}}px;max-height: {{SIZE}}px;',
                ],
                'condition' => [ 'show_icon' => 'yes' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_text_typo',
                'label' => esc_html__( 'Custom Text Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-instagram-text',
                'condition' => [ 'data_source' => 'custom' ]
            ]
        );
        $this->add_control( 'custom_text_color',
            [
                'label' => esc_html__( 'Custom Text Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-instagram-text' => 'color:{{VALUE}};' ],
                'condition' => [ 'data_source' => 'custom' ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/

       /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('slider_options_section',
            [
                'label'=> esc_html__( 'SLIDER OPTIONS', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'loop',
            [
                'label' => esc_html__( 'Infinite', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control( 'centermode',
            [
                'label' => esc_html__( 'Center Mode', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'nav',
            [
                'label' => esc_html__( 'Navigation', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control( 'dots',
            [
                'label' => esc_html__( 'Dots', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
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
        $this->add_control( 'speed',
            [
                'label' => esc_html__( 'Speed', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 100,
                'default' => 1000
            ]
        );
        $this->add_control( 'mditems',
            [
                'label' => esc_html__( 'Items', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 5
            ]
        );
        $this->add_control( 'smitems',
            [
                'label' => esc_html__( 'Items Tablet', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 3
            ]
        );
        $this->add_control( 'xsitems',
            [
                'label' => esc_html__( 'Items Phone', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 2
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
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-bullet + .goldsmith-swiper-bullet' => 'margin: 0;margin-left: {{SIZE}}px;']
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
                'selector' => '{{WRAPPER}} .swiper-pagination-bullet',
            ]
        );
        $this->add_responsive_control( 'dots_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-swiper-bullet:before,{{WRAPPER}} .sgoldsmith-swiper-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
                    '{{WRAPPER}} .goldsmith-swiper-bullet.active:before,{{WRAPPER}} .sgoldsmith-swiper-bullet.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();
        
        if ( 'instagram' == $settings['data_source'] ) {
            wp_enqueue_script( 'instafeed');
        }

        $editmode   = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

        $slider_options = json_encode( array(
                "slidesPerView" => 1,
                //"touchRatio"    => 2,
                "loop"          => 'yes' == $settings['loop'] ? true: false,
                "autoHeight"    => false,
                "autoplay"      => 'yes' == $settings['autoplay'] ? [ "pauseOnMouseEnter" => true,"disableOnInteraction" => false ] : false,
                "centeredSlides"=> 'yes' == $settings['centermode'] ? true: false,
                "speed"         => $settings['speed'],
                "spaceBetween"  => $settings['space'] ? $settings['space'] : 30,
                "direction"     => "horizontal",
                "wrapperClass"  => "goldsmith-swiper-wrapper",
                "navigation" => [
                    "nextEl" => ".slide-next-$id",
                    "prevEl" => ".slide-prev-$id"
                ],
                "pagination" => [
                    "el" => ".goldsmith-pagination-$id",
                    "bulletClass" => "goldsmith-swiper-bullet",
                    "bulletActiveClass" => "active",
                    "type" => "bullets",
                    "clickable" => true
                ],
                "breakpoints" => [
                    "0" => [
                        "slidesPerView" => $settings['xsitems'],
                    ],
                    "768" => [
                        "slidesPerView" => $settings['smitems'],
                    ],
                    "1024" => [
                        "slidesPerView" => $settings['mditems'],
                    ]
                ]
            )
        );
        $next = is_rtl() ? 'next' : 'prev';
        $prev = is_rtl() ? 'prev' : 'next';
        $icon_position = 'yes' == $settings['show_icon'] ? ' icon-'.$settings['icon_position'] : '';
        $insta_icon = 'yes' == $settings['show_icon'] ? '<svg height="512" viewBox="0 0 64 64" width="512" xmlns="http://www.w3.org/2000/svg"><g fill-rule="evenodd"><path d="m48 64h-32a16.0007 16.0007 0 0 1 -16-16v-32a16.0007 16.0007 0 0 1 16-16h32a16 16 0 0 1 16 16v32a16 16 0 0 1 -16 16" fill="#ff3a55"/><path d="m30 18h18a9.0006 9.0006 0 0 0 .92-17.954c-.306-.017-.609-.046-.92-.046h-32a16.0007 16.0007 0 0 0 -16 16v32a30.0007 30.0007 0 0 1 30-30" fill="#ff796c"/><path d="m48 32a16 16 0 1 0 16 16v-32a16 16 0 0 1 -16 16" fill="#e00047"/></g><circle cx="44.5" cy="19.5" fill="#fff" r="2.5"/><path d="m32 24a8 8 0 1 1 -8 8 8.0042 8.0042 0 0 1 8-8zm0-4a12 12 0 1 1 -12 12 12.0057 12.0057 0 0 1 12-12z" fill="#fff" fill-rule="evenodd"/><path d="m52 22a10 10 0 0 0 -10-10h-20a10 10 0 0 0 -10 10v20a10 10 0 0 0 10 10h20a10 10 0 0 0 10-10zm4 0a14 14 0 0 0 -14-14h-20a14 14 0 0 0 -14 14v20a14 14 0 0 0 14 14h20a14 14 0 0 0 14-14z" fill="#fff" fill-rule="evenodd"/></svg>' : '';
        $data_insta = 'instagram' == $settings['data_source'] ? ' data-insta=\'{"token":"'.$settings['token'].'","limit":'.$settings['limit'].',"target":"swiper-wrapper_'.$id.'","blankimage":"'.$blank_img.'","link_target":"'.$settings['target'].'"}\'' : '';

        $blank_img = get_template_directory().'/images/blank.gif';
        $blank_img = file_exists( $blank_img ) ? get_template_directory_uri().'/images/blank.gif' : $placeholder;
        echo '<div class="goldsmith-instagram-slider'.$editmode.'  goldsmith-swiper-container goldsmith-swiper-slider'.$editmode.' nav-vertical-centered"  data-swiper-options=\''.$slider_options.'\'>';
            if ( 'custom' == $settings['data_source'] ) {
                $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : [100,100];
                if ( 'custom' == $size ) {
                    $sizew = $settings['thumbnail_custom_dimension']['width'];
                    $sizeh = $settings['thumbnail_custom_dimension']['height'];
                    $size  = [ $sizew, $sizeh ];
                }
                echo '<div class="goldsmith-swiper-wrapper">';
                    foreach ( $settings['items'] as $item ) {
                        echo '<div class="goldsmith-image-wrapper swiper-slide'.$icon_position.'">';
                            if ( !empty( $item['image']['id'] ) ) {
                                $target     = !empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                                $nofollow   = !empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                                $href       = !empty( $item['link']['url'] ) ? $item['link']['url'] : '';
                                echo $href ? '<a class="goldsmith-instagram-link" href="'.$href.'"'.$target.$nofollow.'>' : '<div class="goldsmith-instagram-link">';
                                echo !empty( $item['custom_text'] ) ? '<span class="goldsmith-instagram-text">'.$item['custom_text'].'</span>' : '';
                                echo $insta_icon;
                                echo wp_get_attachment_image( $item['image']['id'], $size );
                                echo $href ? '</a>' : '</div>';
                            }
                        echo '</div>';
                    }
                echo '</div>';
            } else {
                echo '<div class="goldsmith-swiper-wrapper" id="swiper-wrapper_'.$id.'"></div>';
            }

            if ( 'yes' == $settings['dots'] ) {
                echo '<div class="goldsmith-swiper-pagination goldsmith-pagination-'.$id.'"></div>';
            }
            if ( 'yes' == $settings['nav'] ) {
                echo '<div class="goldsmith-swiper-prev goldsmith-nav-bg goldsmith-nav-small slide-prev-'.$id.'"></div>';
                echo '<div class="goldsmith-swiper-next goldsmith-nav-bg goldsmith-nav-small slide-next-'.$id.'"></div>';
            }
        echo '</div>';
        
        if ( 'instagram' == $settings['data_source'] ) { ?>
            <script>
            jQuery( document ).ready( function($) {

                var feed = new Instafeed({
                    target: '<?php echo 'swiper-wrapper_'.$id; ?>',
                    accessToken: '<?php echo $settings['token']; ?>',
                    limit: <?php echo (-1 == $settings['limit'] ) ? 100 : $settings['limit'] ?>,
                    resolution: 'low_resolution',
                    template: '<div class="goldsmith-image-wrapper swiper-slide<?php echo $icon_position; ?>"><a class="goldsmith-instagram-link" href="{{link}}" title="{{caption}}"><?php echo $insta_icon; ?><img title="{{caption}}" src="{{image}}" /></a></div>'
                });
                feed.run();

                var options =  $('.goldsmith-swiper-slider-<?php echo $id ?>').data('swiper-options');
                const mySlider<?php echo esc_html($id); ?> = new NTSwiper('.goldsmith-swiper-slider-<?php echo esc_html($id); ?>', options);

            });
            </script>
            <?php
        } else {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
            <script>
            jQuery( document ).ready( function($) {
                var options =  $('.goldsmith-swiper-slider-<?php echo $id ?>').data('swiper-options');
                const mySlider<?php echo esc_html($id); ?> = new NTSwiper('.goldsmith-swiper-slider-<?php echo esc_html($id); ?>', options);
            });
            </script>
            <?php
            }
        }
    }
}
