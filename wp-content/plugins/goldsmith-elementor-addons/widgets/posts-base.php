<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Posts_Base extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-posts-base';
    }
    public function get_title() {
        return 'Posts Base (N)';
    }
    public function get_icon() {
        return 'eicon-gallery-grid';
    }
    public function get_categories() {
        return [ 'goldsmith' ];
    }
    public function get_style_depends() {
        return [ 'goldsmith-swiper', 'goldsmith-blog-post' ];
    }
    public function get_script_depends() {
        return [ 'goldsmith-swiper' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'post_query',
            [
                'label' => esc_html__( 'Query', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->goldsmith_query_controls( 'post' );

        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'post_options',
            [
                'label' => esc_html__( 'Post Options', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'type',
            [
                'label' => esc_html__( 'Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => esc_html__( 'Grid', 'goldsmith' ),
                    'slider' => esc_html__( 'Slider', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'style',
            [
                'label' => esc_html__( 'Style', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__( 'Classic', 'goldsmith' ),
                    'card' => esc_html__( 'Card', 'goldsmith' )
                ]
            ]
        );
        $this->add_responsive_control( 'card_min_height',
            [
                'label' => esc_html__( 'Min Height', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 1000,
                'step' => 1,
                'default' => 450,
                'selectors' => [ '{{WRAPPER}} .style-card .goldsmith-blog-post-item-inner' => 'min-height: {{VALUE}}px;' ],
                'condition' => ['style' => 'card']
            ]
        );
        $this->add_control( 'overlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .style-card .goldsmith-blog-post-item-inner:before' => 'background-color:{{VALUE}};' ],
                'condition' => ['style' => 'card']
            ]
        );
        $this->add_control( 'bg_image_size',
            [
                'label' => esc_html__( 'Background Image Size', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => esc_html__( 'Cover', 'goldsmith' ),
                    'contain' => esc_html__( 'Contain', 'goldsmith' ),
                    'auto' => esc_html__( 'Auto', 'goldsmith' ),
                ],
                'selectors' => ['{{WRAPPER}} .style-card .goldsmith-blog-post-item-inner' => 'background-size:{{VALUE}};' ],
            ]
        );
        $this->add_responsive_control( 'column',
            [
                'label' => esc_html__( 'Column Width', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 12,
                'step' => 1,
                'default' => 3,
                'selectors' => [ '{{WRAPPER}} .goldsmith-blog-post-item' => '-ms-flex: 0 0 calc(100% / {{VALUE}} );flex: 0 0 calc(100% / {{VALUE}} );max-width: calc(100% / {{VALUE}} );' ],
                'condition' => ['type' => 'grid']
            ]
        );
        $this->add_responsive_control( 'item_space',
            [
                'label' => esc_html__( 'Space Between Column', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-blog-post-item' => 'padding-left: calc({{VALUE}}px / 2 ); padding-right: calc({{VALUE}}px / 2 ); margin-bottom: {{VALUE}}px',
                    '{{WRAPPER}} .goldsmith-blog-grid.row' => 'margin: 0 calc(-{{VALUE}}px / 2 ) -{{VALUE}}px calc(-{{VALUE}}px / 2 );',
                ],
                'condition' => ['type' => 'grid']
            ]
        );
        $this->add_responsive_control( 'content_alignment',
            [
                'label' => esc_html__( 'Post Alignment', 'goldsmith' ),
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
                'condition' => ['type' => 'grid'],
                'selectors' => [ '{{WRAPPER}} .goldsmith-blog-grid' => 'justify-content: {{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'alignment',
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-blog-post-item' => 'text-align: {{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'cat_alignment',
            [
                'label' => esc_html__( 'Category Alignment', 'goldsmith' ),
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
                'selectors' => [ '{{WRAPPER}} .style-card .goldsmith-blog-post-category' => 'text-align: {{VALUE}};' ],
                'condition' => ['style' => 'card']
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => '',
            ]
        );
        $this->add_control( 'hidecat',
            [
                'label' => esc_html__( 'Hide Category', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'hidetitle',
            [
                'label' => esc_html__( 'Hide Title', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_control( 'hideauthor',
            [
                'label' => esc_html__( 'Hide Author', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_control( 'hidedate',
            [
                'label' => esc_html__( 'Hide Date', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER
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
        $this->add_control( 'pagination',
            [
                'label' => esc_html__( 'Pagination', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'condition' => ['type' => 'grid']
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'style_section',
            [
                'label'=> esc_html__( 'STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'post_content_sdivider',
            [
                'label' => esc_html__( 'POST CONTENT', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control( 'post_item_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-blog-post-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'default' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'post_item_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-blog-post-item-inner',
            ]
        );
        $this->add_responsive_control( 'post_item_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-blog-post-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'post_item_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-blog-post-item-inner'
            ]
        );
        $this->add_control( 'text_content_sdivider',
            [
                'label' => esc_html__( 'TEXT CONTENT', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control( 'text_content_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-blog-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'default' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ]
            ]
        );
        $this->add_control( 'cat_sdivider',
            [
                'label' => esc_html__( 'CATEGORY', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'cat_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-blog-post-category span' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_control( 'cat_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-blog-post-category span' => 'background-color:{{VALUE}};' ],
            ]
        );
        $this->add_responsive_control( 'post_cat_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-blog-post-category span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_control( 'title_sdivider',
            [
                'label' => esc_html__( 'TITLE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'tag',
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
                    'p' => esc_html__( 'p', 'goldsmith' ),
                ]
            ]
        );
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-post-title a' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_control( 'title_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-post-title a:hover' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-post-title'
            ]
        );
        $this->add_control( 'meta_sdivider',
            [
                'label' => esc_html__( 'POST AUTHOR', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'meta_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-post-meta-title' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-blog-post-content .goldsmith-post-meta-title'
            ]
        );
        $this->add_control( 'meta_date_sdivider',
            [
                'label' => esc_html__( 'POST DATE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'meta_date_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-post-meta-date' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_date_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-blog-post-content .goldsmith-post-meta-date'
            ]
        );
        $this->add_control( 'text_sdivider',
            [
                'label' => esc_html__( 'EXCERPT', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'text_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-post-excerpt' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-post-excerpt'
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
        $this->add_control( 'space',
            [
                'label' => esc_html__( 'Space Between Items', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 0
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
        $this->add_control( 'effect',
            [
                'label' => esc_html__( 'Effect', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => esc_html__( 'Slide', 'goldsmith' ),
                    'flip' => esc_html__( 'flip', 'goldsmith' ),
                    'coverflow' => esc_html__( 'Coverflow', 'goldsmith' ),
                    'creative' => esc_html__( 'Creative', 'goldsmith' ),
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('navs_style_section',
            [
                'label'=> esc_html__( 'SLIDER NAV STYLE', 'goldsmith' ),
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
                            'name' => 'nav',
                            'operator' => '=',
                            'value' => 'yes'
                        ]
                    ]
                ]
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
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'pagination_style_section',
            [
                'label'=> esc_html__( 'PAGINATION STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['pagination' => 'yes']
            ]
        );
        $this->add_responsive_control( 'pag_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_responsive_control( 'pag_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_control( 'pag_sdivider',
            [
                'label' => esc_html__( 'BUTTON', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'pag_size',
            [
                'label' => esc_html__( 'Size', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .page-numbers' => 'width: {{SIZE}}px;height: {{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'pag_space',
            [
                'label' => esc_html__( 'Spacing', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .page-numbers:not(:last-child)' => 'margin-right: {{SIZE}}px;' ],
                'condition' => ['type' => '1']
            ]
        );
        $this->start_controls_tabs( 'pag_tabs');
        $this->start_controls_tab( 'pag_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'goldsmith' ) ]
        );
        $this->add_control( 'pag_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .page-numbers' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'pag_bgcolor',
            [
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .page-numbers' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pag_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .page-numbers'
            ]
        );
        $this->add_responsive_control( 'pag_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'pag_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'goldsmith' ) ]
        );
        $this->add_control( 'pag_hvrcolor',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .page-numbers:hover,{{WRAPPER}} .page-numbers.current' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'pag_hvrbgcolor',
            [
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .page-numbers:hover,{{WRAPPER}} .page-numbers.current' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pag_hvrborder',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .page-numbers:hover,{{WRAPPER}} .page-numbers.current'
            ]
        );
        $this->add_responsive_control( 'pag_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .page-numbers:hover,{{WRAPPER}} .page-numbers.current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();

        if ( is_home() || is_front_page() ) {
            $paged = get_query_var( 'page') ? get_query_var('page') : 1;
        } else {
            $paged = get_query_var( 'paged') ? get_query_var('paged') : 1;
        }

        $args['post_type']      = $settings['post_type'];
        $args['posts_per_page'] = $settings['posts_per_page'];
        $args['offset']         = $settings['offset'];
        $args['order']          = $settings['order'];
        $args['orderby']        = $settings['orderby'];
        $args['paged']          = $paged;
        $args[$settings['author_filter_type']] = $settings['author'];


        $post_type = $settings['post_type'];

        if ( ! empty( $settings[ $post_type . '_filter' ] ) ) {
            $args[ $settings[ $post_type . '_filter_type' ] ] = $settings[ $post_type . '_filter' ];
        }

        // Taxonomy Filter.
        $taxonomy = $this->get_post_taxonomies( $post_type );

        if ( ! empty( $taxonomy ) && ! is_wp_error( $taxonomy ) ) {

            foreach ( $taxonomy as $index => $tax ) {

                $tax_control_key = $index . '_' . $post_type;

                if ( $post_type == 'post' ) {
                    if ( $index == 'post_tag' ) {
                        $tax_control_key = 'tags';
                    } elseif ( $index == 'category' ) {
                        $tax_control_key = 'categories';
                    }
                }

                if ( ! empty( $settings[ $tax_control_key ] ) ) {

                    $operator = $settings[ $index . '_' . $post_type . '_filter_type' ];

                    $args['tax_query'][] = array(
                        'taxonomy' => $index,
                        'field' => 'term_id',
                        'terms' => $settings[ $tax_control_key ],
                        'operator' => $operator,
                    );
                }
            }
        }

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'full';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size  = [ $sizew, $sizeh, true ];
        }

        $type = 'class="goldsmith-blog-post-items goldsmith-blog-grid row"';
        $column = 'goldsmith-blog-post-item col-12 col-sm-2 col-md-3';
        $effect = $settings['effect'];
        if ( 'slider' == $settings['type'] ) {

            $editmode = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

            $slider_options = json_encode( array(
                    "slidesPerView" => 1,
                    "loop"          => 'yes' == $settings['loop'] ? true: false,
                    "autoHeight"    => false,
                    "autoplay"      => 'yes' == $settings['autoplay'] ? [ "pauseOnMouseEnter" => true,"disableOnInteraction" => false ] : false,
                    "centeredSlides"=> 'yes' == $settings['centermode'] ? true: false,
                    "speed"         => $settings['speed'],
                    "spaceBetween"  => $settings['space'] ? $settings['space'] : 0,
                    "direction"     => "horizontal",
                    "wrapperClass"  => "goldsmith-swiper-wrapper",
                    "lazy"          => true,
                    "navigation" => [
                        "nextEl" => ".slide-next-{$id}",
                        "prevEl" => ".slide-prev-{$id}"
                    ],
                    "effect" => "{$effect}",
                    "flipEffect" => [
                        "slideShadows" => false
                    ],
                    "fadeEffect" => [
                        "crossFade" => true
                    ],
                    "creativeEffect" => [
                        "prev" => [ "translate" => [0, 0, -400] ],
                        "next" => [ "translate" => ['100%', 0, 0] ]
                    ],
                    "coverflowEffect" => [
                        "rotate" => 30,
                        "slideShadows" => false
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
                            "slidesPerView" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['xsitems'],
                            "slidesPerGroup" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['xsitems']
                        ],
                        "768" => [
                            "slidesPerView" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['smitems'],
                            "slidesPerGroup" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['smitems']
                        ],
                        "1024" => [
                            "slidesPerView" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['mditems'],
                            "slidesPerGroup" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['mditems']
                        ]
                    ]
                )
            );

            $type = 'class="goldsmith-blog-slider goldsmith-swiper-container goldsmith-swiper-slider'.$editmode.' nav-vertical-centered" data-swiper-options=\''.$slider_options.'\'';
            $column = 'goldsmith-blog-post-item swiper-slide';
        }

        $the_query = new \WP_Query( $args );
        if ( $the_query->have_posts() ) {
            echo '<div '.$type.'>';
                echo 'slider' == $settings['type'] ? '<div class="goldsmith-swiper-wrapper">' : '';
                while ( $the_query->have_posts() ) {
                    $the_query->the_post();
                    $postid = get_the_ID();
                    $categories = get_the_category($postid);
                    $bg = get_the_post_thumbnail_url($postid, $size);
                    $databg = 'card' == $settings['style'] && has_post_thumbnail() ? ' swiper-lazy" data-background="'.$bg.'"' : '"';
                    $catnone = 'yes' == $settings[ 'hidecat' ] ? ' category-none' : '';

                    echo '<div class="'.$column.' style-'.$settings['style'].' content-alignment-'.$settings[ 'alignment' ].'">';

                        echo '<div class="goldsmith-blog-post-item-inner'.$catnone.$databg.'>';
                            if ( has_post_thumbnail() ) {
                                if ( 'card' == $settings['style']  ) {
                                    if ( !empty( $categories ) && 'yes' != $settings[ 'hidecat' ] ) {
                                        echo '<a class="goldsmith-blog-post-category" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '"><span>' . esc_html( $categories[0]->name ) . '</span></a>';
                                    }
                                } else {
                                    echo '<div class="goldsmith-blog-thumb">';
                                        echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail($postid, $size). '</a>';
                                    if ( !empty( $categories ) && 'yes' != $settings[ 'hidecat' ] ) {
                                        echo '<a class="goldsmith-blog-post-category" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '"><span>' . esc_html( $categories[0]->name ) . '</span></a>';
                                    }
                                    echo '</div>';
                                }
                            }
                            if ( 'yes' != $settings['hideauthor'] || 'yes' != $settings[ 'hidetitle' ] || 'yes' != $settings[ 'hideexcerpt' ] && has_excerpt() ) {
                                echo '<div class="goldsmith-blog-post-content">';
                                    echo '<div class="goldsmith-blog-post-meta goldsmith-inline-two-block">';
                                        if ( 'yes' != $settings['hideauthor'] ) {
                                            echo '<h6 class="goldsmith-post-author">'.get_the_author().'</h6>';
                                        }
                                        if ( 'yes' != $settings['hidedate'] ) {
                                            echo '<h6 class="goldsmith-post-date">'.get_the_date().'</h6>';
                                        }
                                    echo '</div>';
                                    if ( 'yes' != $settings[ 'hidetitle' ] ) {
                                        echo '<'.$settings['tag'].' class="goldsmith-post-title"><a href="'.get_permalink().'">'.get_the_title().'</a></'.$settings['tag'].'>';
                                    }
                                    if ( 'yes' != $settings[ 'hideexcerpt' ] && has_excerpt() ) {
                                        echo '<p class="goldsmith-post-excerpt">'.wp_trim_words( get_the_excerpt(), $settings['excerpt_limit'] ).'</p>';
                                    }
                                echo '</div>';
                            }
                        echo '</div>';
                    echo '</div>';
                }
                wp_reset_postdata();

                if ( 'slider' != $settings['type'] && 'yes' == $settings['pagination'] && $the_query->max_num_pages > 1 ) {
                    echo '<div class="goldsmith-pagination goldsmith-flex goldsmith-align-center">';
                        echo paginate_links(array(
                            'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                            'format' => '?paged=%#%',
                            'current' => max(1, $paged ),
                            'total' => $the_query->max_num_pages,
                            'type' => '',
                            'prev_text' => '<i class="fa fa-angle-left"></i>',
                            'next_text' => '<i class="fa fa-angle-right"></i>',
                            'before_page_number' => '<div class="nt-pagination-item">',
                            'after_page_number' => '</div>'
                        ));
                    echo '</div>';
                }

                if ( 'slider' == $settings['type'] ) {
                    echo '</div>';
                    if ( 'yes' == $settings['dots'] ) {
                        echo '<div class="goldsmith-swiper-pagination goldsmith-pagination-'.$id.' position-relative"></div>';
                    }
                    if ( 'yes' == $settings['nav'] ) {
                        echo '<div class="goldsmith-swiper-prev goldsmith-nav-bg goldsmith-nav-small slide-prev-'.$id.'"></div>';
                        echo '<div class="goldsmith-swiper-next goldsmith-nav-bg goldsmith-nav-small slide-next-'.$id.'"></div>';
                    }
                }
            echo '</div>';

        } else {
            echo '<p class="goldsmith-not-found-info">' . esc_html__( 'No post found!', 'goldsmith' ) . '</p>';
        }
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && 'slider' == $settings['type'] ) { ?>
            <script>
            jQuery( document ).ready( function($) {
                const options  = $('.goldsmith-swiper-slider-<?php echo $id ?>').data('swiper-options');
                const mySlider = new NTSwiper('.goldsmith-swiper-slider-<?php echo $id ?>', options);
            });
            </script>
            <?php
        }
    }
}
