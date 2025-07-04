<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Home_Slider extends Widget_Base {
    public function get_name() {
        return 'goldsmith-home-slider';
    }
    public function get_title() {
        return 'Home Main Slider (N)';
    }
    public function get_icon() {
        return 'eicon-carousel';
    }
    public function get_categories() {
        return [ 'goldsmith' ];
    }
    public function get_style_depends() {
        return [ 'goldsmith-swiper' ];
    }
    public function get_script_depends() {
        return [ 'goldsmith-swiper' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'items_settings',
            [
                'label' => esc_html__('Slide Items', 'goldsmith'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control( 'iautoplay_delay',
            [
                'label' => esc_html__( 'Item Autoplay Delay', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 50000,
                'step' => 100,
                'default' => ''
            ]
        );
        $repeater->add_control( 'anim_in',
            [
                'label' => esc_html__( 'Content Items Entrance Animation', 'goldsmith' ),
                'type' => Controls_Manager::ANIMATION,
                'prefix_class' => '',
            ]
        );
        $repeater->add_control( 'hanim_delay',
            [
                'label' => esc_html__( 'Heading Animation Delay', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 10,
                'default' => 500,
                'condition' => [ 'anim_in!' => '' ]
            ]
        );
        $repeater->add_control( 'descanim_delay',
            [
                'label' => esc_html__( 'Description Animation Delay', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 10,
                'default' => 750,
                'condition' => [ 'anim_in!' => '' ]
            ]
        );
        $repeater->add_control( 'btnanim_delay',
            [
                'label' => esc_html__( 'Buttons Animation Delay', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 10,
                'default' => 1000,
                'condition' => [ 'anim_in!' => '' ]
            ]
        );
        $repeater->add_control( 'image',
            [
                'label' => esc_html__( 'Image', 'goldsmith' ),
                'type' => Controls_Manager::MEDIA
            ]
        );
        $repeater->add_control( 'mob_image',
            [
                'label' => esc_html__( 'Mobile Image', 'goldsmith' ),
                'type' => Controls_Manager::MEDIA
            ]
        );
        $repeater->add_control( 'use_video',
            [
                'label' => esc_html__( 'Use Background Video', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $repeater->add_control( 'video_url',
            [
                'label' => esc_html__( 'Hosted Video URL', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => '',
                'condition' => [ 'use_video' => 'yes' ]
            ]
        );
        $repeater->add_control( 'add_link',
            [
                'label' => esc_html__( 'Add Link to Slide Item', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $repeater->add_control( 'slink',
            [
                'label' => esc_html__( 'Slide Item Link', 'goldsmith' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => ''
                ],
                'show_external' => true,
                'condition' => [ 'add_link' => 'yes' ]
            ]
        );
        $repeater->add_control( 'title',
            [
                'label' => esc_html__( 'Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'desc',
            [
                'label' => esc_html__( 'Description', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'btn_title',
            [
                'label' => esc_html__( 'Button Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'link',
            [
                'label' => esc_html__( 'Button Link', 'goldsmith' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => ''
                ],
                'show_external' => true
            ]
        );
        $repeater->add_control( 'btn_id',
            [
                'label' => esc_html__( 'Button ID', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'btn_title2',
            [
                'label' => esc_html__( 'Button 2 Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'link2',
            [
                'label' => esc_html__( 'Button 2 Link', 'goldsmith' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => ''
                ],
                'show_external' => true
            ]
        );
        $repeater->add_control( 'btn_id2',
            [
                'label' => esc_html__( 'Button 2 ID', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_responsive_control( 'ihorz_alignment',
            [
                'label' => esc_html__( 'Horizontal Text Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-inner' => 'align-items: {{VALUE}};'],
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
                'default' => 'center'
            ]
        );
        $repeater->add_responsive_control( 'ivert_alignment',
            [
                'label' => esc_html__( 'Vertical Text Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-inner' => 'justify-content: {{VALUE}};'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'goldsmith' ),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'goldsmith' ),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'goldsmith' ),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'toggle' => true,
                'default' => 'center'
            ]
        );
        $repeater->add_responsive_control( 'islide_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'ioverlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-inner:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ititle_color',
            [
                'label' => esc_html__( 'Title Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-head' => 'color:{{VALUE}};' ]
            ]
        );
		$repeater->add_control('ititle_stroke_popover_toggle',
			[
				'label' => esc_html__( 'Title Stroke', 'goldsmith' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => esc_html__( 'Default', 'goldsmith' ),
				'label_on' => esc_html__( 'Custom', 'goldsmith' ),
			]
		);
		$repeater->start_popover();
        $repeater->add_control( 'ititle_stroke',
            [
                'label' => esc_html__( 'Stroke Width', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'default' => 1,
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-head' => '-webkit-text-stroke-width:{{VALUE}}px;stroke-width:{{VALUE}}px;' ]
            ]
        );
        $repeater->add_control( 'ititle_stroke_color',
            [
                'label' => esc_html__( 'Stroke Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-head' => '-webkit-text-stroke-color:{{VALUE}};stroke:{{VALUE}};' ]
            ]
        );
        $repeater->end_popover();

        $repeater->add_control( 'idesc_color',
            [
                'label' => esc_html__( 'Description Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-text' => 'color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ibtn_bgcolor',
            [
                'label' => esc_html__( 'Button Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-link' => 'background-color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ibtn_color',
            [
                'label' => esc_html__( 'Button Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-link' => 'color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ibtn_bgcolor2',
            [
                'label' => esc_html__( 'Button 2 Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-link2' => 'background-color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ibtn_color2',
            [
                'label' => esc_html__( 'Button 2 Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-link2' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'items',
            [
                'label' => esc_html__( 'Items', 'goldsmith' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => 'Image',
                'default' => [
                    [
                        'title' => 'Title 1',
                        'desc' => 'This is a description',
                        'btn_title' => 'Button Title 1'
                    ],
                    [
                        'title' => 'Title 2',
                        'desc' => 'This is a description',
                        'btn_title' => 'Button Title 1'
                    ],
                    [
                        'title' => 'Title 3',
                        'desc' => 'This is a description',
                        'btn_title' => 'Button Title 1'
                    ]
                ]
            ]
        );
        $this->add_responsive_control( 'box_height',
            [
                'label' => esc_html__( 'Slider Height', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 5,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'vh',
                    'size' => 100,
                ],
                'selectors' => [ '{{WRAPPER}} .goldsmith-main-slider' => 'height:{{SIZE}}{{UNIT}};' ]
            ]
        );
        $this->add_responsive_control( 'slide_item_width',
            [
                'label' => esc_html__( 'Slider Item Container Width', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 4000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 800
                ],
                'selectors' => [ '{{WRAPPER}} .goldsmith-main-slider .slide-item-content' => 'max-width:{{SIZE}}{{UNIT}};' ]
            ]
        );
        $this->add_control( 'tag',
            [
                'label' => esc_html__( 'Slider Heading Tag ( for SEO )', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => esc_html__( 'h1', 'goldsmith' ),
                    'h2' => esc_html__( 'h2', 'goldsmith' ),
                    'h3' => esc_html__( 'h3', 'goldsmith' ),
                    'h4' => esc_html__( 'h4', 'goldsmith' ),
                    'h5' => esc_html__( 'h5', 'goldsmith' ),
                    'h6' => esc_html__( 'h6', 'goldsmith' ),
                    'div' => esc_html__( 'div', 'goldsmith' ),
                    'p' => esc_html__( 'p', 'goldsmith' ),
                    'span' => esc_html__( 'span', 'goldsmith' )
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
            'name' => 'thumbnail',
            'default' => 'large'
            ]
        );
        $this->add_control( 'mobile_thumbnail_divider',
            [
                'label' => esc_html__( 'MOBILE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
            'name' => 'mobile_thumbnail',
            'default' => 'medium_large'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('slider_options_section',
            [
                'label'=> esc_html__( 'SLIDER OPTIONS', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
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
        $this->add_control( 'autoplay_delay',
            [
                'label' => esc_html__( 'Autoplay Delay', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 50000,
                'step' => 100,
                'default' => 5000
            ]
        );
        $this->add_control( 'nav',
            [
                'label' => esc_html__( 'Navigation', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'dots',
            [
                'label' => esc_html__( 'Dots', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
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
                'max' => 6,
                'step' => 1,
                'default' => 3
            ]
        );
        $this->add_control( 'smitems',
            [
                'label' => esc_html__( 'Items Tablet', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 3,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->add_control( 'xsitems',
            [
                'label' => esc_html__( 'Items Phone', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 2,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('image_style_section',
            [
                'label'=> esc_html__( 'STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_responsive_control( 'horz_alignment',
            [
                'label' => esc_html__( 'Horizontal Text Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .goldsmith-slide-inner' => 'align-items: {{VALUE}};'],
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
                'default' => 'center'
            ]
        );
        $this->add_responsive_control( 'vert_alignment',
            [
                'label' => esc_html__( 'Vertical Text Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .goldsmith-slide-inner' => 'justify-content: {{VALUE}};'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'goldsmith' ),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'goldsmith' ),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'goldsmith' ),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'toggle' => true,
                'default' => 'center'
            ]
        );
        $this->add_responsive_control( 'slide_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_control( 'overlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-slide .goldsmith-slide-inner:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'title_divider',
            [
                'label' => esc_html__( 'TITLE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-slide-head' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-slide-head'
            ]
        );
        $this->add_control( 'desc_divider',
            [
                'label' => esc_html__( 'DESCRIPTION', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'desc_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-slide-text' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-slide-text'
            ]
        );
        $this->add_control( 'btn_divider',
            [
                'label' => esc_html__( 'BUTTON', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-slide-link'
            ]
        );
        $this->start_controls_tabs('goldsmith_btn_tabs');
        $this->start_controls_tab( 'goldsmith_btn_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'goldsmith' ) ]
        );
        $this->add_control( 'btn_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-slide-link' => 'color: {{VALUE}};']
            ]
        );
        $this->add_responsive_control( 'btn_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-slide-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-slide-link',
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control( 'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-slide-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_background',
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .goldsmith-slide-link',
                'separator' => 'before'
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab('goldsmith_btn_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'goldsmith' ) ]
        );
         $this->add_control( 'btn_hvr_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-slide-link:hover' => 'color: {{VALUE}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_hvr_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-slide-link:hover',
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_hvr_background',
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .goldsmith-slide-link:hover',
                'separator' => 'before'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
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
        $this->add_responsive_control( 'navs_size',
            [
                'label' => esc_html__( 'Slider Height', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .goldsmith-swiper-theme-style .slider-btn-nav' => 'width:{{SIZE}}px;height:{{SIZE}}px;' ]
            ]
        );
        $this->add_responsive_control( 'navs_icon_size',
            [
                'label' => esc_html__( 'Nav Icon Size', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .goldsmith-swiper-theme-style .slider-btn-nav:after' => 'font-size:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'navs_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-theme-style .slider-btn-nav' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_hvrbgcolor',
            [
                'label' => esc_html__( 'Background Color ( Hover )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-theme-style .slider-btn-nav:hover' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_brdcolor',
            [
                'label' => esc_html__( 'Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-theme-style .slider-btn-nav' => 'border-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_hvrbrdcolor',
            [
                'label' => esc_html__( 'Border Color ( Hover )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-theme-style .slider-btn-nav:hover' => 'border-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_color',
            [
                'label' => esc_html__( 'Icon Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-theme-style .slider-btn-nav:after' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_hvrcolor',
            [
                'label' => esc_html__( 'Icon Color ( Hover )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-theme-style .slider-btn-nav:hover:after' => 'color:{{VALUE}};' ]
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
        $this->add_responsive_control( 'dots_offset',
            [
                'label' => esc_html__( 'Bottom Offset', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .goldsmith-swiper-theme-style .swiper-pagination-bullets' => 'bottom:{{SIZE}}px;' ]
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-swiper-theme-style .swiper-pagination-bullets' => 'text-align:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'dots_size',
            [
                'label' => esc_html__( 'Size', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullets .goldsmith-swiper-bullet:before' => 'width:{{SIZE}}px;height:{{SIZE}}px;' ]
            ]
        );
        $this->add_responsive_control( 'dots_space',
            [
                'label' => esc_html__( 'Space', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-horizontal > .swiper-pagination-bullets .goldsmith-swiper-bullet + .goldsmith-swiper-bullet' => 'margin: 0;margin-left: {{SIZE}}px;',
                    '{{WRAPPER}} .swiper-pagination-horizontal.swiper-pagination-bullets .goldsmith-swiper-bullet + .goldsmith-swiper-bullet' => 'margin: 0;margin-left: {{SIZE}}px;',
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
                'selectors' => ['{{WRAPPER}} .swiper-pagination-bullets .goldsmith-swiper-bullet:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .swiper-pagination-bullets .goldsmith-swiper-bullet',
            ]
        );
        $this->add_responsive_control( 'dots_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullets .goldsmith-swiper-bullet:before,
                    {{WRAPPER}} .swiper-pagination-bullets .goldsmith-swiper-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
                    '{{WRAPPER}} .swiper-pagination-bullets .goldsmith-swiper-bullet.active:before,
                    {{WRAPPER}} .swiper-pagination-bullets .goldsmith-swiper-bullet.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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

        $editmode = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

        $slider_options = json_encode( array(
                "slidesPerView" => 1,
                "loop"          => 'yes' == $settings['loop'] ? true : false,
                "autoHeight"    => false,
                "autoplay"      => 'yes' == $settings['autoplay'] ? [ "pauseOnMouseEnter" => true,"disableOnInteraction" => false,"delay" => $settings['autoplay_delay'] ] : false,
                "speed"         => $settings['speed'],
                "spaceBetween"  => 0,
                "direction"     => "horizontal",
                "wrapperClass"  => "goldsmith-swiper-wrapper",
                "navigation" => [
                    "nextEl" => ".slide-next-$id",
                    "prevEl" => ".slide-prev-$id"
                ],
                "pagination" => [
                    "el"                => ".goldsmith-main-slider .goldsmith-pagination-$id",
                    "bulletClass"       => "goldsmith-swiper-bullet",
                    "bulletActiveClass" => "active",
                    "type"              => "bullets",
                    "clickable"         => true
                ]
            )
        );

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'large';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size  = [ $sizew, $sizeh ];
        }

        $mobile_size = $settings['mobile_thumbnail_size'] ? $settings['mobile_thumbnail_size'] : 'medium_large';
        if ( 'custom' == $size ) {
            $mobile_sizew = $settings['mobile_thumbnail_custom_dimension']['width'];
            $mobile_sizeh = $settings['mobile_thumbnail_custom_dimension']['height'];
            $mobile_size  = [ $mobile_sizew, $mobile_sizeh ];
        }
        echo '<div class="goldsmith-main-slider goldsmith-swiper-theme-style goldsmith-swiper-container goldsmith-swiper-slider'.$editmode.' nav-vertical-center" data-swiper-options=\''.$slider_options.'\'>';
            echo '<div class="goldsmith-swiper-wrapper">';
                foreach ( $settings['items'] as $item ) {
                    $attr = !empty( $item['iautoplay_delay']) ? ' data-swiper-autoplay="'.$item['iautoplay_delay'].'"' : '';
                    $attr .= !empty( $item['anim_in'] ) ? ' data-anim-in="'.$item['anim_in'].'"' : '';
                    $is_anim = !empty( $item['anim_in'] ) ? ' has-animation animated '.$item['anim_in'] : '';
                    $hanim_delay = !empty( $item['anim_in'] ) && !empty( $item['hanim_delay'] ) ? ' style="animation-delay:'.$item['hanim_delay'].'ms"' : '';
                    $descanim_delay = !empty( $item['anim_in'] ) && !empty( $item['descanim_delay'] ) ? ' style="animation-delay:'.$item['descanim_delay'].'ms"' : '';
                    $btnanim_delay = !empty( $item['anim_in'] ) && !empty( $item['btnanim_delay'] ) ? ' style="animation-delay:'.$item['btnanim_delay'].'ms"' : '';
                    $overlay = !empty( $item['ioverlay_color'] ) ? ' has-overlay' : '';
                    $has_video = 'yes' == $item['use_video'] && !empty( $item['video_url'] ) ? ' has-video' : '';
                    echo '<div class="swiper-slide elementor-repeater-item-'.$item['_id'].'"'.$attr.'>';
                        if ( $item['add_link'] == 'yes' && !empty( $item['slink']['url'] ) ) {
                            $target   = !empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                            $nofollow = !empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                            echo '<a class="goldsmith-slide-item-link" href="'.$item['slink']['url'].'"'.$target.$nofollow.'></a>';
                        }
                        echo '<div class="goldsmith-slide-inner'.$overlay.$has_video.'">';
                            if ( 'yes' == $item['use_video'] && !empty( $item['video_url'] ) ) {
                                echo '<video autoplay muted loop><source src="'.$item['video_url'].'" type="video/mp4"></video>';
                            } else {
                                if ( wp_is_mobile() && !empty( $item['mob_image']['id'] ) ) {
                                    echo wp_get_attachment_image( $item['mob_image']['id'], $mobile_size, false );
                                } else {
                                    if ( !empty( $item['image']['id'] ) ) {
                                        echo wp_get_attachment_image( $item['image']['id'], $size, false );
                                    }
                                }
                            }

                            echo '<div class="slide-item-content">';
                                if ( !empty( $item['title']) ) {
                                    echo '<'.$settings['tag'].' class="goldsmith-slide-head'.$is_anim.'"'.$hanim_delay.'>'.$item['title'].'</'.$settings['tag'].'>';
                                }
                                if ( !empty( $item['desc'] ) ) {
                                    echo '<p class="goldsmith-slide-text'.$is_anim.'"'.$descanim_delay.'>'.$item['desc'].'</p>';
                                }
                                if ( !empty( $item['btn_title'] ) || !empty( $item['btn_title2'] ) ) {
                                    echo '<div class="goldsmith-slide-link-wrapper'.$is_anim.'"'.$btnanim_delay.'>';
                                }
                                    if ( !empty( $item['btn_title'] ) ) {
                                        $target   = !empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                                        $nofollow = !empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                                        $btn_id   = !empty( $item['btn_id'] ) ? ' id="'.$item['btn_id'].'"' : '';
                                        echo '<a class="goldsmith-btn-large goldsmith-btn goldsmith-btn-dark goldsmith-slide-link" href="'.$item['link']['url'].'"'.$btn_id.$target.$nofollow.'>'.$item['btn_title'].'</a>';
                                    }
                                    if ( !empty( $item['btn_title2'] ) ) {
                                        $target2   = !empty( $item['link2']['is_external'] ) ? ' target="_blank"' : '';
                                        $nofollow2 = !empty( $item['link2']['nofollow'] ) ? ' rel="nofollow"' : '';
                                        $btn_id2   = !empty( $item['btn_id2'] ) ? ' id="'.$item['btn_id2'].'"' : '';
                                        echo '<a class="goldsmith-btn-large goldsmith-btn goldsmith-btn-dark goldsmith-slide-link goldsmith-slide-link2" href="'.$item['link2']['url'].'"'.$btn_id2.$target2.$nofollow2.'>'.$item['btn_title2'].'</a>';
                                    }
                                if ( !empty( $item['btn_title'] ) || !empty( $item['btn_title2'] ) ) {
                                    echo '</div>';
                                }
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            echo '</div>';

            if ( 'yes' == $settings['dots'] ) {
                echo '<div class="goldsmith-swiper-pagination goldsmith-pagination-'.$id.' position-absolute-bottom"></div>';
            }

            if ( 'yes' == $settings['nav'] ) {
                echo '<div class="goldsmith-swiper-prev goldsmith-nav-bg slider-btn-nav slide-prev-'.$id.'"></div>';
                echo '<div class="goldsmith-swiper-next goldsmith-nav-bg slider-btn-nav slide-next-'.$id.'"></div>';
            }

        echo '</div>';

        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
            <script>
            jQuery( document ).ready( function($) {
                var options =  $('.goldsmith-swiper-slider-<?php echo $id ?>').data('swiper-options');
                const mySlider<?php echo $id ?> = new NTSwiper('.goldsmith-swiper-slider-<?php echo $id ?>', options);

                mySlider<?php echo $id ?>.on('transitionEnd', function () {
                    var animIn = $('.goldsmith-swiper-slider-<?php echo $id ?> .swiper-slide').data('anim-in');
                    var active = $('.goldsmith-swiper-slider-<?php echo $id ?>').find('.swiper-slide-active');
                    var inactive = $('.goldsmith-swiper-slider-<?php echo $id ?>').find('.swiper-slide:not(.swiper-slide-active)');

                    if( typeof animIn != 'undefined' ) {
                        inactive.find('.has-animation').each(function(e){
                            $(this).removeClass('animated '+animIn);
                        });
                        active.find('.has-animation').each(function(e){
                            $(this).addClass('animated '+animIn);
                        });
                    }
                });
            });
            </script>
            <?php
        }
    }
}
