<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Banner_Hero_Slider extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-banner-hero-slider';
    }
    public function get_title() {
        return 'Single Product Slider (N)';
    }
    public function get_icon() {
        return 'eicon-slider-push';
    }
    public function get_categories() {
        return [ 'goldsmith-woo' ];
    }
    public function get_style_depends() {
        return [ 'goldsmith-slick' ];
    }
    public function get_script_depends() {
        return [ 'slick' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'general_section',
            [
                'label'=> esc_html__( 'Banner', 'goldsmith' ),
                'tab'=> Controls_Manager::TAB_CONTENT,
            ]
        );
        $repeater = new Repeater();

        $repeater->add_control( 'post_filter',
            [
                'label' => esc_html__( 'Select Post', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $this->get_all_posts_by_type('product')
            ]
        );
        $repeater->add_control( 'cats',
            [
                'label' => esc_html__( 'Category(s)', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->goldsmith_cpt_taxonomies('product_cat'),
                'description' => esc_html__( 'Select category(s) to show for this product', 'goldsmith' ),
            ]
        );
        $repeater->add_control( 'reverse',
            [
                'label' => esc_html__( 'Reverse Column', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $repeater->add_control( 'column_width',
            [
                'label' => esc_html__( 'Image Column Width', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 12,
                'step' => 1,
                'default' => 6
            ]
        );
		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'item_background',
				'label' => esc_html__( 'Background', 'goldsmith' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			]
		);
        $repeater->add_control( 'item_bg_overlay_color',
            [
                'label' => esc_html__( 'Background Image Overlay Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}}:before' => 'position:absolute;width:100%;height:100%;top:0;left:0;background-color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'item_cats_color',
            [
                'label' => esc_html__( 'Category Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-banner-category-link' => 'color:{{VALUE}};' ],
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'item_title_color',
            [
                'label' => esc_html__( 'Title Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-banner-title' => 'color:{{VALUE}};' ],
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'item_price_color',
            [
                'label' => esc_html__( 'Price Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-banner-price' => 'color:{{VALUE}};' ],
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'item_btn_color',
            [
                'label' => esc_html__( 'Button Title Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-banner-button a' => 'color:{{VALUE}};' ],
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'details_divider',
            [
                'label' => esc_html__( 'DETAILS', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'details_text_alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'goldsmith' ),
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
                'default' => is_rtl() ? 'right' : 'left',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .banner-details-wrapper' => 'text-align:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'details_text_padding',
            [
                'label' => esc_html__( 'Text Content Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .banner-details-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $repeater->add_control( 'details_horizontal_alignment',
            [
                'label' => esc_html__( 'Horizontal Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
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
                'default' => 'flex-start',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .banner-details-col' => 'justify-content:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'details_vertical_alignment',
            [
                'label' => esc_html__( 'Vertical Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'goldsmith' ),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'goldsmith' ),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'goldsmith' ),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'toggle' => true,
                'default' => 'center',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .banner-details-col' => 'align-items:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'image_divider',
            [
                'label' => esc_html__( 'IMAGE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'image_horizontal_alignment',
            [
                'label' => esc_html__( 'Horizontal Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
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
                'default' => 'center',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .banner-image-col' => 'justify-content:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'image_vertical_alignment',
            [
                'label' => esc_html__( 'Vertical Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'goldsmith' ),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'goldsmith' ),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'goldsmith' ),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'toggle' => true,
                'default' => 'center',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .banner-image-col' => 'align-items:{{VALUE}};' ]
            ]
        );
		$repeater->add_control('animation',
			[
				'label' => esc_html__( 'Entrance Animation', 'goldsmith' ),
				'type' => Controls_Manager::ANIMATION
			]
		);
        $this->add_control('all_products',
            [
                'label' => esc_html__( 'All Categories', 'goldsmith' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
                'title_field' => "ID : {{{ post_filter }}}",
            ]
        );
        $this->add_control( 'fullwidth',
            [
                'label' => esc_html__( 'Fullwidth', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'tag',
            [
                'label' => esc_html__( 'Title Tag for SEO', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => esc_html__( 'H1', 'goldsmith' ),
                    'h2' => esc_html__( 'H2', 'goldsmith' ),
                    'h3' => esc_html__( 'H3', 'goldsmith' ),
                    'h4' => esc_html__( 'H4', 'goldsmith' ),
                    'h5' => esc_html__( 'H5', 'goldsmith' ),
                    'h6' => esc_html__( 'H6', 'goldsmith' ),
                    'div' => esc_html__( 'div', 'goldsmith' ),
                    'p' => esc_html__( 'p', 'goldsmith' )
                ]
            ]
        );
        $this->add_responsive_control( 'box_padding',
            [
                'label' => esc_html__( 'Slide Item Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .hero-banner-slide-item .banner-details-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
            ]
        );
        $this->add_control( 'hideexcerpt',
            [
                'label' => esc_html__( 'Hide Excerpt', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_control( 'excerpt_limit',
            [
                'label' => esc_html__( 'Excerpt Word Limit', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'default' => 20,
                'condition' => ['hideexcerpt!' => 'yes']
            ]
        );
        $this->add_control( 'space_content_items',
            [
                'label' => esc_html__( 'Space Between Text Content Item', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .hero-banner-slide-item .banner-content-item + .banner-content-item' => 'margin-top:{{SIZE}}px;' ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'style_section',
            [
                'label' => esc_html__( 'STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'cat_divider',
            [
                'label' => esc_html__( 'CATEGORY', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'cat_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-categories , {{WRAPPER}} .goldsmith-banner-categories a' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cat_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-banner-categories a'
            ]
        );
        $this->add_responsive_control( 'cat_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-categories' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'cat_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-title' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-banner-title'
            ]
        );
        $this->add_responsive_control( 'title_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'title_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-desc' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-banner-desc'
            ]
        );
        $this->add_responsive_control( 'desc_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'desc_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'price_divider',
            [
                'label' => esc_html__( 'PRICE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'price_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-price' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-banner-price'
            ]
        );
        $this->add_responsive_control( 'price_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'price_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'btn_divider',
            [
                'label' => esc_html__( 'BUTTON', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'btn_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-button a' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-button a:hover' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-button a' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_hvrbgcolor',
            [
                'label' => esc_html__( 'Hover Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-button a:hover' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-banner-button'
            ]
        );
        $this->add_responsive_control( 'btn_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'btn_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} .goldsmith-banner-button a'
            ]
        );
        $this->add_responsive_control( 'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']
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
        $this->add_control( 'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'goldsmith' ),
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
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('dots_style_section',
            [
                'label'=> esc_html__( 'SLIDER DOTS STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['dots' => 'yes']
            ]
        );
        $this->add_responsive_control( 'dots_top_space',
            [
                'label' => esc_html__( 'Dots Top Offset', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -300,
                'max' => 300,
                'step' => 1,
                'default' => -50,
                'selectors' => [ '{{WRAPPER}} .slick-dots' => 'top:{{SIZE}}px;' ]
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
                'default' => 'center'
            ]
        );
        $this->add_responsive_control( 'dots_left_right_space',
            [
                'label' => esc_html__( 'Dots Left/Right Spacing', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dots-alingment-left .slick-dots' => 'left:{{SIZE}}px;',
                    '{{WRAPPER}} .dots-alingment-right .slick-dots' => 'right:{{SIZE}}px;'
                ],
                'condition' => ['dots_alignment!' => 'center']
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
                'selectors' => [ '{{WRAPPER}} .slick-dots li button' => 'width:{{SIZE}}px;height:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'dots_space',
            [
                'label' => esc_html__( 'Dots Space', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .slick-dots li + li' => 'margin-left: {{SIZE}}px;' ]
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
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li' => 'border-color:{{VALUE}};',
                    '{{WRAPPER}} .slick-dots li button' => 'background-color:{{VALUE}};',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .slick-dots li',
            ]
        );
        $this->add_responsive_control( 'dots_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .slick-dots li,{{WRAPPER}} .slick-dots li button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'dots_hover_tab',
            [ 'label' => esc_html__( 'Active', 'goldsmith' ) ]
        );
        $this->add_control( 'dots_hvrbgcolor',
            [
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li:hover button' => 'background-color:{{VALUE}};',
                    '{{WRAPPER}} .slick-dots li.slick-active button' => 'background-color:{{VALUE}};',
                    '{{WRAPPER}} .slick-dots li.slick-active' => 'border-color:{{VALUE}};'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_hvrborder',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .slick-dots li.slick-active'
            ]
        );
        $this->add_responsive_control( 'dots_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li.slick-active, {{WRAPPER}} .slick-dots li.slick-active button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'full';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size  = [ $sizew, $sizeh ];
        }

        $rtl        = is_rtl() ? 'true' : 'false';
        $isrtl      = is_rtl() ? ' is-rtl' : '';
        $dots       = 'yes' == $settings['dots'] ? 'true': 'false';
        $autoplay   = 'yes' == $settings['autoplay'] ? 'true': 'false';
        //$centermode = 'yes' == $settings['centermode'] ? 'true': 'false';
        $dots_align =  ' dots-alingment-'. $settings['dots_alignment'];
        $fullwidth  = 'yes' == $settings['fullwidth'] ? 'container-off' : 'goldsmith-container-xl container-xl';

        $editmode   = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

        echo '<div class="goldsmith-hero-banner-product-slider goldsmith-slick goldsmith-slick-slider'.$editmode.$isrtl.$dots_align.'" data-slick=\'{"rtl":'.$rtl.',"infinite":true,"autoplay":'.$autoplay.',"infinite": false,"speed": '.$settings['speed'].',"slidesToShow": 1,"adaptiveHeight": false,"dots": '.$dots.',"arrows": false}\'>';
            foreach ( $settings['all_products'] as $p ) {
                $product = wc_get_product( $p['post_filter'] );
                $delay = isset($p['delay']) != '' ? ' data-banner-animation-delay="'.$p['delay'].'"' : '';
                $data_anim = isset($p['animation']) != '' ? $p['animation'] : '';
                $data_anim = $data_anim != '' && $data_anim != 'none' ? ' data-banner-animation="'.$data_anim.'"' : '';
                $reverse = 'yes' == $p['reverse'] ? ' flex-lg-row-reverse' : '';
                $img_column = '0' == $p['column_width'] ? 12 : $p['column_width'];
                $text_column = 12 - $img_column;
                if ( $product ) {
                    $pid = $product->get_id();
                    echo '<div class="hero-banner-slide-item elementor-repeater-item-' . esc_attr( $p['_id'] ) . '"'.$data_anim.$delay.'>';
                        echo '<div class="'.$fullwidth.'">';
                            echo '<div class="row hero-banner-slide-row'.$reverse.'">';

                                echo '<div class="col-12 col-lg-'.$text_column.' banner-details-col">';
                                    echo '<div class="banner-details-wrapper">';
                                        $cats = array();
                                        if ( !empty( $p['cats'] ) ){
                                            foreach ( $p['cats'] as $cat ) {
                                                $term  = get_term( $cat, 'product_cat' );
                                                if ( $term ){
                                                    $cats[] .= '<a class="goldsmith-banner-category-link" href="'.get_category_link( $cat ).'">'.$term->name.'</a>';

                                                }
                                            }
                                            if ( !empty( $cats ) ){
                                                echo '<div class="goldsmith-banner-categories banner-content-item">'.implode( ' - ', $cats ).'</div>';
                                            }
                                        }
                                        echo '<'.$settings['tag'].' class="goldsmith-banner-title banner-content-item">'.$product->get_name().'</'.$settings['tag'].'>';
                                        if ( 'yes' != $settings[ 'hideexcerpt' ] && $product->get_short_description() ) {
                                            echo '<div class="goldsmith-banner-desc banner-content-item">'.wp_trim_words( $product->get_short_description(), $settings['excerpt_limit'] ).'</div>';
                                        }
                                        echo '<div class="goldsmith-banner-price banner-content-item">'.$product->get_price_html().'</div>';
                                        echo '<div class="goldsmith-banner-button banner-content-item">'.do_shortcode('[add_to_cart id="'.$pid.'" show_price="false" style=""]').'</div>';
                                    echo '</div>';
                                echo '</div>';

                                echo '<div class="col-12 col-lg-'.$img_column.' banner-image-col">';
                                    echo '<div class="product-image-wrapper">';
                                        echo get_the_post_thumbnail( $pid, $size );
                                    echo '</div>';
                                echo '</div>';

                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            }
        echo '</div>';

        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
            <script>
            jQuery( document ).ready( function($) {
                $('.goldsmith-slick-slider-<?php echo $id ?>').not('.slick-initialized').slick();

                var inactiveSlideItem = $('.goldsmith-slick-slider-<?php echo $id ?> .hero-banner-slide-item:not(.slick-active)[data-banner-animation]');
                var activeSlideItem = $('.goldsmith-slick-slider-<?php echo $id ?> .hero-banner-slide-item.slick-active[data-banner-animation]');

                $('.goldsmith-slick-slider-<?php echo $id ?> .hero-banner-slide-item[data-banner-animation]').each(function(index,el){
                    var $this = $(el);
                    var anim = $this.data('banner-animation');

                    $this.find('.banner-content-item').each(function(index,ell){
                        var delay = index*100;
                        $(ell).addClass(anim).css('animation-delay', delay+'ms');
                    });
                });

                $('.goldsmith-slick-slider-<?php echo $id ?>').on('afterChange', function(event, slick, currentSlide, nextSlide){

                    $('.goldsmith-slick-slider-<?php echo $id ?> .hero-banner-slide-item.slick-active[data-banner-animation]').each(function(index,el){
                        var $this = $(el);
                        var anim = $this.data('banner-animation');
                        var delay = $this.data('banner-animation-delay');

                        $this.find('.banner-content-item').each(function(index,ell){
                            console.log(index);

                            $(ell).addClass('animated '+anim).removeClass( 'elementor-invisible' );
                        });

                    });

                    $('.goldsmith-slick-slider-<?php echo $id ?> .hero-banner-slide-item:not(.slick-active)[data-banner-animation]').each(function(index,el){
                        var $this = $(el);
                        var anim = $this.data('banner-animation');

                        $this.find('.banner-content-item').each(function(index,ell){
                            $(ell).addClass( 'elementor-invisible' ).removeClass('animated '+anim);
                        });
                    });

                });
            });
            </script>
            <?php
        }
    }
}
