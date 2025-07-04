<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Custom_Reviews extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-custom-reviews';
    }
    public function get_title() {
        return 'Custom Reviews (N)';
    }
    public function get_icon() {
        return 'eicon-site-search';
    }
    public function get_categories() {
        return [ 'goldsmith-woo' ];
    }
    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'cat', 'wc', 'woo', 'product', 'search' ];
    }
    public function get_style_depends() {
        return [ 'goldsmith-wc-custom-reviews-slider' ];
    }
    // Registering Controls
    protected function register_controls() {

        /* HEADER MINICART SETTINGS */
        $this->start_controls_section( 'general_section',
            [
                'label' => esc_html__( 'Reviews', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'type',
            [
                'label' => esc_html__( 'Reviews Layout Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'slider',
                'options' => [
                    'slider' => esc_html__( 'Slider', 'goldsmith' ),
                    'grid' => esc_html__( 'Grid', 'goldsmith' )
                ]
            ]
        );
		$this->add_control('count',
			[
				'label' => esc_html__( 'Perpage Count', 'goldsmith' ),
				'type' => Controls_Manager::NUMBER,
				'max' => 20,
				'min' => 0,
				'default' => 5,
				'description' => 'It is recommended to keep it between "0" and "5"'
			]
		);
		$this->add_control('show_products',
			[
				'label' => esc_html__( 'Show Products', 'goldsmith' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);
		$this->add_control('product_links',
			[
				'label' => esc_html__( 'Products Links', 'goldsmith' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);
        $this->add_control( 'sort',
            [
                'label' => esc_html__( 'Sort', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => esc_html__( 'Date', 'goldsmith' ),
                    'rating' => esc_html__( 'Rating', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'sort_by',
            [
                'label' => esc_html__( 'Sort By', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC' => esc_html__( 'ASC', 'goldsmith' ),
                    'DESC' => esc_html__( 'DESC', 'goldsmith' ),
                    'RAND' => esc_html__( 'RAND', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'categories',
            [
                'label' => esc_html__( 'Filter Category(s)', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->goldsmith_cpt_taxonomies('product_cat'),
                'description' => 'Select Category(s)'
            ]
        );
        $this->add_control( 'products',
            [
                'label' => esc_html__( 'Filter Post(s)', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_posts_by_type('product'),
                'description' => 'Select Product(s)'
            ]
        );
        $this->add_control( 'product_tags',
            [
                'label' => esc_html__( 'Filter Tag(s)', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->goldsmith_cpt_taxonomies('product_tag','name'),
                'description' => 'Select Tag(s)'
            ]
        );
        $this->add_control( 'avatars',
            [
                'label' => esc_html__( 'Avatars', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'initials',
                'options' => [
                    'initials' => esc_html__( 'Initials', 'goldsmith' ),
                    'standard' => esc_html__( 'Standard', 'goldsmith' ),
                    'false' => esc_html__( 'None', 'goldsmith' )
                ]
            ]
        );
		$this->add_control('max_chars',
			[
				'label' => esc_html__( 'Max Chars', 'goldsmith' ),
				'type' => Controls_Manager::NUMBER,
				'default' => ''
			]
		);
		$this->add_control('min_chars',
			[
				'label' => esc_html__( 'Min Chars', 'goldsmith' ),
				'type' => Controls_Manager::NUMBER,
				'default' => ''
			]
		);
		$this->add_control('style_options',
			[
				'label' => esc_html__( 'STYLE', 'goldsmith' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
        $this->add_responsive_control( 'box_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .cr-reviews-slider .cr-review-card .cr-review-card-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'label' => esc_html__( 'Item Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .cr-reviews-slider .cr-review-card .cr-review-card-inner'
            ]
        );
        $this->add_responsive_control( 'box_border_radius',
            [
                'label' => esc_html__( 'Item Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .cr-reviews-slider .cr-review-card .cr-review-card-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'color_ex_brdr',
           [
               'label' => esc_html__( 'Grid of reviews', 'goldsmith' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .cr-reviews-slider .cr-review-card .cr-review-card-inner' => 'color: {{VALUE}};',
                   '{{WRAPPER}} .goldsmith-asform .header-search-wrap form button svg' => 'fill: {{VALUE}};'
               ]
           ]
        );
		$this->add_control('box_top_options',
			[
				'label' => esc_html__( 'Top Box', 'goldsmith' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
        $this->add_responsive_control( 'box_top_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .cr-reviews-slider .cr-review-card .top-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'box_top_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .cr-reviews-slider .cr-review-card .top-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_top_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .cr-reviews-slider .cr-review-card .top-row'
            ]
        );
        $this->add_control('avatar_offset',
            [
                'label' => __( 'Avatar offset', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .top-row .review-thumbnail,
                    {{WRAPPER}} .cr-reviews-slider .cr-review-card .top-row .review-thumbnail' => 'margin-right: {{SIZE}}px;'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typo',
                'label' => esc_html__( 'Name Typography', 'goldsmith' ),
                'selector' => '
                {{WRAPPER}} .ivole-reviews-grid .cr-review-card .top-row .reviewer .reviewer-name,
                {{WRAPPER}}  .cr-reviews-slider .cr-review-card .top-row .reviewer .reviewer-name'
            ]
        );
        $this->add_control( 'name_color',
           [
               'label' => esc_html__( 'Name Color', 'goldsmith' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .top-row .reviewer .reviewer-name,
                   {{WRAPPER}}  .cr-reviews-slider .cr-review-card .top-row .reviewer .reviewer-name' => 'color: {{VALUE}};'
               ]
           ]
        );
		$this->add_control('box_rating_options',
			[
				'label' => esc_html__( 'Stars & Rating', 'goldsmith' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
        $this->add_responsive_control( 'box_rating_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .cr-reviews-slider .cr-review-card .rating-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'box_rating_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .cr-reviews-slider .cr-review-card .rating-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control('rating_size',
            [
                'label' => __( 'Stars Size', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .crstar-rating,
                    {{WRAPPER}} .cr-reviews-slider .cr-review-card .crstar-rating,
                    {{WRAPPER}} .ivole-reviews-grid .cr-review-card .crstar-rating::before,
                    {{WRAPPER}} .cr-reviews-slider .cr-review-card .crstar-rating::before,
                    {{WRAPPER}} .ivole-reviews-grid .cr-review-card .crstar-rating span,
                    {{WRAPPER}} .cr-reviews-slider .cr-review-card .crstar-rating span' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .crstar-rating,
                    {{WRAPPER}} .cr-reviews-slider .cr-review-card .crstar-rating' => 'height: {{SIZE}}px;'
                ]
            ]
        );
        $this->add_control('rating_width',
            [
                'label' => __( 'Stars Width', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .crstar-rating,
                    {{WRAPPER}} .cr-reviews-slider .cr-review-card .crstar-rating' => 'width: {{SIZE}}px;'
                ]
            ]
        );
        $this->add_control( 'rating_color',
           [
               'label' => esc_html__( 'Stars Color', 'goldsmith' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .crstar-rating,
                   {{WRAPPER}} .cr-reviews-slider .cr-review-card .crstar-rating' => 'color: {{VALUE}}!important;'
               ]
           ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'rating_number_typo',
                'label' => esc_html__( 'Rating Number Typography', 'goldsmith' ),
                'selector' => '
                {{WRAPPER}} .ivole-reviews-grid .cr-review-card .rating-row .rating-label,
                {{WRAPPER}} .cr-reviews-slider .cr-review-card .rating-row .rating-label'
            ]
        );
        $this->add_control( 'rating_number__color',
           [
               'label' => esc_html__( 'Rating Number Color', 'goldsmith' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .rating-row .rating-label,
                   {{WRAPPER}} .cr-reviews-slider .cr-review-card .rating-row .rating-label' => 'color: {{VALUE}};'
               ]
           ]
        );
		$this->add_control('reviews_text_options',
			[
				'label' => esc_html__( 'Review Text', 'goldsmith' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
        $this->add_responsive_control( 'reviews_text_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .cr-reviews-slider .cr-review-card .middle-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'reviews_text_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .cr-reviews-slider .cr-review-card .middle-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'reviews_text_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .cr-reviews-slider .cr-review-card .middle-row'
            ]
        );
        $this->add_responsive_control( 'reviews_text_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .cr-reviews-slider .cr-review-card .middle-row' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'reviews_text_bgcolor',
           [
               'label' => esc_html__( 'Background Color', 'goldsmith' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .cr-reviews-slider .cr-review-card .middle-row' => 'background-color: {{VALUE}}!important;'
               ]
           ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reviews_text_typo',
                'label' => esc_html__( 'Review Text Typography', 'goldsmith' ),
                'selector' => '
                {{WRAPPER}} .ivole-reviews-grid .cr-review-card .middle-row .review-content p,
                {{WRAPPER}} .cr-reviews-slider .cr-review-card .middle-row p'
            ]
        );
        $this->add_control( 'reviews_text_color',
           [
               'label' => esc_html__( 'Review Text Color', 'goldsmith' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .middle-row .review-content p,
                	{{WRAPPER}} .cr-reviews-slider .cr-review-card .middle-row p' => 'color: {{VALUE}};'
               ]
           ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'reviews_date_typo',
                'label' => esc_html__( 'Date Typography', 'goldsmith' ),
                'selector' => '
                {{WRAPPER}} .ivole-reviews-grid .cr-review-card .middle-row .datetime,
                {{WRAPPER}} .cr-reviews-slider .cr-review-card .middle-row .datetime'
            ]
        );
        $this->add_control( 'reviews_date_color',
           [
               'label' => esc_html__( 'Date Text Color', 'goldsmith' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .middle-row .datetime,
                	{{WRAPPER}} .cr-reviews-slider .cr-review-card .middle-row .datetime' => 'color: {{VALUE}};'
               ]
           ]
        );
		$this->add_control('bottom_product_options',
			[
				'label' => esc_html__( 'Bottom Product', 'goldsmith' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
        $this->add_responsive_control( 'bottom_product_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['
                {{WRAPPER}} .ivole-reviews-grid .cr-review-card .review-product,
                {{WRAPPER}} .cr-reviews-slider .cr-review-card .review-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'bottom_product_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .review-product,
                {{WRAPPER}} .cr-reviews-slider .cr-review-card .review-product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'bottom_product_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .review-product,
                {{WRAPPER}} .cr-reviews-slider .cr-review-card .review-product',
            ]
        );
        $this->add_responsive_control( 'bottom_product_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .ivole-reviews-grid .cr-review-card .review-product,
                {{WRAPPER}} .cr-reviews-slider .cr-review-card .review-product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'bottom_product_bgcolor',
           [
               'label' => esc_html__( 'Background Color', 'goldsmith' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .review-product,
                	{{WRAPPER}} .cr-reviews-slider .cr-review-card .review-product' => 'background-color: {{VALUE}}!important;'
               ]
           ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'bottom_product_name_typo',
                'label' => esc_html__( 'Review Text Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .review-product .product-title,
                {{WRAPPER}} .cr-reviews-slider .cr-review-card .review-product .product-title'
            ]
        );
        $this->add_control( 'bottom_product_name_color',
           [
               'label' => esc_html__( 'Review Text Color', 'goldsmith' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .ivole-reviews-grid .cr-review-card .review-product .product-title,
                	{{WRAPPER}} .cr-reviews-slider .cr-review-card .review-product .product-title' => 'color: {{VALUE}};'
               ]
           ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('slider_options_section',
            [
                'label'=> esc_html__( 'SLIDER OPTIONS', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['type' => 'slider']
            ]
        );
        $this->add_responsive_control( 'slide_item_space',
            [
                'label' => esc_html__( 'Space Between Items', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 300
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .cr-reviews-slider .cr-review-card' => 'padding: 0 calc({{SIZE}}px / 2 );',
                    '{{WRAPPER}} .cr-reviews-slider .slick-list' => 'margin: 0 calc(-{{SIZE}}px / 2 );'
                ]
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
        $this->add_control( 'large',
            [
                'label' => esc_html__( 'Large', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 3
            ]
        );
        $this->add_control( 'desktop',
            [
                'label' => esc_html__( 'Desktop', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 3
            ]
        );
        $this->add_control( 'laptop',
            [
                'label' => esc_html__( 'Laptop', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 3
            ]
        );
        $this->add_control( 'tablet_extra',
            [
                'label' => esc_html__( 'Tablet Extra', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 12,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->add_control( 'tablet',
            [
                'label' => esc_html__( 'Tablet', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 3
            ]
        );
        $this->add_control( 'phone_extra',
            [
                'label' => esc_html__( 'Phone Extra', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control( 'phone',
            [
                'label' => esc_html__( 'Phone', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 4,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('dots_style_section',
            [
                'label'=> esc_html__( 'SLIDER DOTS STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'type',
                            'operator' => '==',
                            'value' => 'slider'
                        ],
                        [
                            'name' => 'dots',
                            'operator' => '=',
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $this->add_responsive_control( 'dots_top_offset',
            [
                'label' => esc_html__( 'Top Offset', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 300
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .slick-dots' => 'margin-top:{{SIZE}}px;' ]
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
                'selectors' => [ '{{WRAPPER}} .slick-dots' => 'text-align:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'dots_size',
            [
                'label' => esc_html__( 'Size', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .slick-dots li button' => 'width:{{SIZE}}px;height:{{SIZE}}px;' ]
            ]
        );
        $this->add_responsive_control( 'dots_space',
            [
                'label' => esc_html__( 'Space', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li' => 'margin:0 {{SIZE}}px;',
                    '{{WRAPPER}} .slick-dots' => 'margin:0 -{{SIZE}}px;'
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
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li' => 'border-color:{{VALUE}};',
                    '{{WRAPPER}} .slick-dots li button' => 'background-color:{{VALUE}};'
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
        if ( ! class_exists('WooCommerce') || ! class_exists('CR_Reviews_Slider') ) {
            return;
        }
        
        $settings = $this->get_settings_for_display();
        
        if (  'slider' == $settings['type'] ) {
            wp_enqueue_style( 'goldsmith-wc-custom-reviews-slider' );
            wp_enqueue_style( 'goldsmith-slick' );
            wp_enqueue_script( 'slick' );
        }

        $categories = !empty($settings['categories']) ? implode(',',$settings['categories']) : '';
        $products = !empty($settings['products']) ? implode(',',$settings['products']) : '';
        $product_tags = !empty($settings['product_tags']) ? implode(',',$settings['product_tags']) : '';
		//$attr  = ' slides_to_show="'.$settings['slides_to_show'].'"';
		$attr  = ' count="'.$settings['count'].'"';
		$attr .= 'yes' == $settings['show_products'] ? ' show_products="true"' : ' show_products="false"';
		$attr .= 'yes' == $settings['product_links'] ? ' product_links="true"' : ' product_links="false"';
		$attr .= ' sort_by="'.$settings['sort_by'].'"';
		$attr .= ' sort="'.$settings['sort'].'"';
		$attr .= ' categories="'.$categories.'"';
		$attr .= ' products="'.$products.'"';
		$attr .= ' product_tags="'.$product_tags.'"';
		$attr .= ' color_ex_brdr=""';
		$attr .= ' color_brdr=""';
		$attr .= ' color_ex_bcrd=""';
		$attr .= ' color_bcrd=""';
		$attr .= ' color_pr_bcrd=""';
		$attr .= ' color_stars=""';
		//$attr .= 'yes' == $settings['autoplay'] ? ' autoplay="true"' : ' autoplay="false"';
		//$attr .= 'yes' == $settings['show_dots'] ? ' show_dots="true"' : ' show_dots="false"';
		$attr .= ' avatars="'.$settings['avatars'].'"';
		$attr .= ' max_chars="'.$settings['max_chars'].'"';
		$attr .= ' min_chars="'.$settings['min_chars'].'"';
		
        $rtl        = is_rtl() ? 'true' : 'false';
        $isrtl      = is_rtl() ? 'is-rtl' : '';
        $dots       = 'yes' == $settings['dots'] ? 'true': 'false';
        $autoplay   = 'yes' == $settings['autoplay'] ? 'true': 'false';
        $centermode = 'yes' == $settings['centermode'] ? 'true': 'false';

        $slick = ' data-slick-options=\'{ "rtl":'.$rtl.', "autoplay":'.$autoplay.', "infinite": false, "speed": '.$settings['speed'].', "slidesToShow": '.$settings['large'].', "slidesToScroll": '.$settings['large'].', "adaptiveHeight": false, "dots": '.$dots.', "arrows": false, "centerMode":'.$centermode.', "responsive": [{"breakpoint": 2400,"settings": {"slidesToShow": '.$settings['large'].',"slidesToScroll": '.$settings['large'].'}},{"breakpoint": 1920,"settings": { "slidesToShow": '.$settings['desktop'].', "slidesToScroll": '.$settings['desktop'].' } }, { "breakpoint": 1600, "settings": { "slidesToShow": '.$settings['laptop'].', "slidesToScroll": '.$settings['laptop'].' } }, { "breakpoint": 1200, "settings": { "slidesToShow": '.$settings['tablet_extra'].', "slidesToScroll": '.$settings['tablet_extra'].' } }, { "breakpoint": 1024, "settings": { "slidesToShow": '.$settings['tablet'].', "slidesToScroll": '.$settings['tablet'].' } }, { "breakpoint": 881, "settings": { "slidesToShow": '.$settings['phone_extra'].', "slidesToScroll": '.$settings['phone_extra'].' } }, { "breakpoint": 576, "settings": { "slidesToShow": '.$settings['phone'].', "slidesToScroll": '.$settings['phone'].' } } ] }\'';

        echo'<div class="goldsmith-custom-reviews-wrapper"'.$slick.'>';
            echo do_shortcode('[cusrev_reviews_'.$settings['type'].$attr.']');
        echo'</div>';
        if (  'slider' == $settings['type'] ) { ?>
            <script>
            jQuery( document ).ready( function($) {
            	var options = $('.goldsmith-custom-reviews-wrapper').data('slick-options');
                $('.cr-reviews-slider').data('slick',options);
                <?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
                	$('.cr-reviews-slider').not('.slick-initialized').slick();
                <?php } ?>
            });
            </script>
            <?php
        }
        
    }
}