<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Category_Grid extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-category-grid';
    }
    public function get_title() {
        return 'WC Categories (N)';
    }
    public function get_icon() {
        return 'eicon-gallery-grid';
    }
    public function get_categories() {
        return [ 'goldsmith-woo' ];
    }
    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'cat', 'wc', 'woo', 'product' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'general_settings',
            [
                'label' => esc_html__('Product Categories', 'goldsmith'),
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
        $this->add_control( 'post_per_page',
            [
                'label' => esc_html__( 'Per Page', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'default' => 10,
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-category-item' => '-ms-flex: 0 0 calc(100% / {{VALUE}} );flex: 0 0 calc(100% / {{VALUE}} );max-width: calc(100% / {{VALUE}} );' ],
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
                    '{{WRAPPER}} .goldsmith-category-grid .goldsmith-category-item' => 'padding-left: calc({{VALUE}}px / 2 ); padding-right: calc({{VALUE}}px / 2 ); margin-bottom: {{VALUE}}px',
                    '{{WRAPPER}} .goldsmith-category-grid.row' => 'margin: 0 calc(-{{VALUE}}px / 2 ) -{{VALUE}}px calc(-{{VALUE}}px / 2 );',
                ],
                'condition' => ['type' => 'grid']
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail'
            ]
        );
        $this->add_control( 'order',
            [
                'label' => esc_html__( 'Select Order', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ASC' => esc_html__( 'Ascending', 'goldsmith' ),
                    'DESC' => esc_html__( 'Descending', 'goldsmith' )
                ],
                'default' => 'ASC'
            ]
        );
        $this->add_control( 'orderby',
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
                'default' => 'id',
            ]
        );
        $this->add_control( 'category_exclude',
            [
                'label' => esc_html__( 'Exclude Category', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->goldsmith_cpt_taxonomies('product_cat'),
                'description' => 'Select Category(s) to Exclude'
            ]
        );
        $this->add_control( 'cat_count',
            [
                'label' => esc_html__( 'Category Count', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'cat_desc',
            [
                'label' => esc_html__( 'Category Description', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
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
        $this->add_control( 'image_sdivider',
            [
                'label' => esc_html__( 'ITEM BOX', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control( 'alignment',
            [
                'label' => esc_html__( 'Content Alignment', 'goldsmith' ),
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
                'selectors' => ['{{WRAPPER}} .goldsmith-category-item-inner' => 'text-align: {{VALUE}};']
            ]
        );
        $this->add_control( 'box_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-category-item-inner' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'post_item_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-category-item-inner',
            ]
        );
        $this->add_responsive_control( 'post_item_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-category-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'post_item_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-category-item-inner'
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
                'default' => 'h6',
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
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-category-title' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-category-title'
            ]
        );
        $this->add_responsive_control( 'title_offset',
            [
                'label' => esc_html__( 'top Offset', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => ['{{WRAPPER}} .goldsmith-category-title' => 'margin-top:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'count_sdivider',
            [
                'label' => esc_html__( 'CATEGORY COUNT', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['cat_count' => 'yes']
            ]
        );
        $this->add_responsive_control( 'count_top_pos',
            [
                'label' => esc_html__( 'Top Position', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500
                    ]
                ],
                'selectors' => ['{{WRAPPER}} .goldsmith-category-count' => 'top:{{SIZE}}px;' ],
                'condition' => ['cat_count' => 'yes']
            ]
        );
        $this->add_responsive_control( 'count_right_pos',
            [
                'label' => esc_html__( 'Right Position', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500
                    ]
                ],
                'selectors' => ['{{WRAPPER}} .goldsmith-category-count' => 'right:{{SIZE}}px;' ],
                'condition' => ['cat_count' => 'yes']
            ]
        );
        $this->add_control( 'count_bgcolor',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-category-count' => 'background-color:{{VALUE}};' ],
                'condition' => ['cat_count' => 'yes']
            ]
        );
        $this->add_control( 'count_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-category-count' => 'color:{{VALUE}};' ],
                'condition' => ['cat_count' => 'yes']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-category-count',
                'condition' => ['cat_count' => 'yes']
            ]
        );
        $this->add_control( 'text_sdivider',
            [
                'label' => esc_html__( 'DESCRIPTION', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['description' => 'yes']
            ]
        );
        $this->add_control( 'text_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-category-description' => 'color:{{VALUE}};' ],
                'condition' => ['description' => 'yes']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-category-description',
                'condition' => ['description' => 'yes']
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
                    '{{WRAPPER}} .goldsmith-product-category-slider .goldsmith-category-item' => 'padding: 0 calc({{SIZE}}px / 2 );',
                    '{{WRAPPER}} .goldsmith-product-category-slider .slick-list' => 'margin: 0 calc(-{{SIZE}}px / 2 );',
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

        $cats = get_terms(
            array(
                'taxonomy' => 'product_cat',
                'number'   => $settings['post_per_page'],
                'order'    => $settings['order'],
                'orderby'  => $settings['orderby'],
                'exclude'  => $settings['category_exclude']
            )
        );

        $type = 'class="goldsmith-category-grid row"';
        $gridCol = 'grid' == $settings['type'] ? ' col-12 col-sm-2 col-md-3' : '';
        if ( 'slider' == $settings['type'] ) {
            wp_enqueue_style( 'goldsmith-slick' );
            wp_enqueue_script( 'slick' );
            $rtl        = is_rtl() ? 'true' : 'false';
            $isrtl      = is_rtl() ? ' is-rtl' : '';
            $dots       = 'yes' == $settings['dots'] ? 'true': 'false';
            $autoplay   = 'yes' == $settings['autoplay'] ? 'true': 'false';
            $centermode = 'yes' == $settings['centermode'] ? 'true': 'false';
            $editmode   = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

            $type = 'class="goldsmith-product-category-slider goldsmith-slick goldsmith-slick-slider'.$editmode.$isrtl.'" data-slick=\'{"rtl":'.$rtl.',"autoplay":'.$autoplay.',"infinite": false,"speed": '.$settings['speed'].',"slidesToShow": '.$settings['large'].',"slidesToScroll": '.$settings['large'].',"adaptiveHeight": false,"dots": '.$dots.',"arrows": false,"centerMode":'.$centermode.',"responsive": [{"breakpoint": 2400,"settings": {"slidesToShow": '.$settings['large'].',"slidesToScroll": '.$settings['large'].'}},{"breakpoint": 1920,"settings": {"slidesToShow": '.$settings['desktop'].',"slidesToScroll": '.$settings['desktop'].'}},{"breakpoint": 1600,"settings": {"slidesToShow": '.$settings['laptop'].',"slidesToScroll": '.$settings['laptop'].'}},{"breakpoint": 1200,"settings": {"slidesToShow": '.$settings['tablet_extra'].',"slidesToScroll": '.$settings['tablet_extra'].'}},{"breakpoint": 1024,"settings": {"slidesToShow": '.$settings['tablet'].',"slidesToScroll": '.$settings['tablet'].'}},{"breakpoint": 881,"settings": {"slidesToShow": '.$settings['phone_extra'].',"slidesToScroll": '.$settings['phone_extra'].'}},{"breakpoint": 576,"settings": {"slidesToShow": '.$settings['phone'].',"slidesToScroll": '.$settings['phone'].'}}]}\'';
        }

        echo '<div '.$type.'>';
            foreach ( $cats as $cat ) {
                $imgid = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                echo '<div class="goldsmith-category-item'.$gridCol.'">';
                    echo '<div class="goldsmith-category-item-inner">';
                        echo '<a class="goldsmith-category-link" href="'.esc_url( get_term_link( $cat ) ).'" title="'.$cat->name.'">';
                            echo 'yes' == $settings['cat_count'] ? '<span class="goldsmith-category-count">'.$cat->count.'</span>' : '';
                            if ( $imgid ) {
                                echo '<div class="goldsmith-category-thumb">';
                                    echo wp_get_attachment_image( $imgid, $size, false, ['srcset'=> '', 'class'=>'goldsmith-category-item-image'] );
                                echo '</div>';
                            }
                            echo '<div class="goldsmith-category-content">';
                                echo '<'.$settings['tag'].' class="goldsmith-category-title">'.$cat->name.'</'.$settings['tag'].'>';
                                if ( $cat->description && 'yes' == $settings['cat_desc'] ) {
                                    echo '<p class="goldsmith-category-description">'.$cat->description.'</p>';
                                }
                            echo '</div>';
                        echo '</a>';
                    echo '</div>';
                echo '</div>';
            }
        echo '</div>';
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && 'slider' == $settings['type'] ) { ?>
            <script>
            jQuery( document ).ready( function($) {
                $('.goldsmith-slick-slider-<?php echo $id ?>').not('.slick-initialized').slick();
            });
            </script>
            <?php
        }
    }
}
