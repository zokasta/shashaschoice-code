<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Brands extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-brands';
    }
    public function get_title() {
        return 'Product Brands (N)';
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
                'label' => esc_html__('Product Brands', 'goldsmith'),
                'tab' => Controls_Manager::TAB_CONTENT,
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-brands-item' => '-ms-flex: 0 0 calc(100% / {{VALUE}} );flex: 0 0 calc(100% / {{VALUE}} );max-width: calc(100% / {{VALUE}} );' ],
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
                'default' => 30,
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-brands-grid .goldsmith-brands-item' => 'padding-left: calc({{VALUE}}px / 2 ); padding-right: calc({{VALUE}}px / 2 ); margin-bottom: {{VALUE}}px',
                    '{{WRAPPER}} .goldsmith-brands-grid.row' => 'margin: 0 calc(-{{VALUE}}px / 2 ) -{{VALUE}}px calc(-{{VALUE}}px / 2 );',
                ],
                'condition' => ['type' => 'grid']
            ]
        );
        $this->add_control( 'brands_exclude',
            [
                'label' => esc_html__( 'Exclude Brands', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->goldsmith_cpt_taxonomies('goldsmith_product_brands'),
                'description' => 'Select brands(s) to Exclude'
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
        $this->add_control( 'brand_desc',
            [
                'label' => esc_html__( 'Brands Description', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
            'name' => 'thumbnail',
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
        $this->add_control( 'item_opacity',
            [
                'label' => esc_html__( 'Inactive Item Opacity', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1,
                'step' => 0.01,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .swiper-slide:not(.swiper-slide-active)' => 'opacity:{{SIZE}};' ],
                'condition' => ['centermode' => 'yes']
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
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-brands-item-inner,
                    {{WRAPPER}} .goldsmith-brands-content .goldsmith-brands-title,
                    {{WRAPPER}} .goldsmith-brands-content .goldsmith-brands-description' => 'text-align: {{VALUE}};'
                ]
            ]
        );
        $this->add_control( 'img_alignment',
            [
                'label' => esc_html__( 'Image Alignment', 'goldsmith' ),
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
                'default' => ''
            ]
        );
        $this->add_control( 'box_content_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-brands-item-inner' => 'background-color:{{VALUE}};' ],
            ]
        );
        $this->add_responsive_control( 'post_item_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-brands-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'post_item_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-brands-item-inner',
            ]
        );
        $this->add_responsive_control( 'post_item_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-brands-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'post_item_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-brands-item-inner'
            ]
        );
        $this->add_control( 'text_content_sdivider',
            [
                'label' => esc_html__( 'TEXT CONTENT', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'text_content_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-brands-content' => 'background-color:{{VALUE}};' ],
            ]
        );
        $this->add_responsive_control( 'text_content_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-brands-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'text_sdivider',
            [
                'label' => esc_html__( 'DESCRIPTION', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['brand_desc' => 'yes']
            ]
        );
        $this->add_control( 'text_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-brands-description' => 'color:{{VALUE}};' ],
                'condition' => ['brand_desc' => 'yes']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-brands-description',
                'condition' => ['brand_desc' => 'yes']
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('navs_style_section',
            [
                'label'=> esc_html__( 'SLIDER NAV STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['nav' => 'yes']
            ]
        );
        $this->add_control( 'navs_size',
            [
                'label' => esc_html__( 'Size', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-swiper-theme-style .swiper-button-prev:after,{{WRAPPER}} .goldsmith-swiper-theme-style .swiper-button-next:after' => 'font-size:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'navs_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-theme-style .swiper-button-prev:after,{{WRAPPER}} .goldsmith-swiper-theme-style .swiper-button-next:after' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-theme-style .swiper-button-prev:hover:after,{{WRAPPER}} .goldsmith-swiper-theme-style .swiper-button-next:hover:after' => 'color:{{VALUE}};' ]
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
        $this->add_control( 'dots_top_offset',
            [
                'label' => esc_html__( 'Top Offset', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -300,
                'max' => 300,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-swiper-theme-style .swiper-pagination-bullets' => 'margin-top:{{SIZE}}px;' ]
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
        $this->add_control( 'dots_size',
            [
                'label' => esc_html__( 'Size', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet:before' => 'width:{{SIZE}}px;height:{{SIZE}}px;' ]
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
                    '{{WRAPPER}} .swiper-horizontal > .swiper-pagination-bullets .swiper-pagination-bullet + .swiper-pagination-bullet' => 'margin: 0;margin-left: {{SIZE}}px;',
                    '{{WRAPPER}} .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet + .swiper-pagination-bullet' => 'margin: 0;margin-left: {{SIZE}}px;',
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
                'selectors' => ['{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet',
            ]
        );
        $this->add_responsive_control( 'dots_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet:before,{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
                'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_hvrborder',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active'
            ]
        );
        $this->add_responsive_control( 'dots_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active:before,{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : [100,100];
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size  = [ $sizew, $sizeh ];
        }
        $editmode = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

        $brands = get_terms(
            array(
                'taxonomy' => 'goldsmith_product_brands',
                'number'   => $settings['post_per_page'],
                'order'    => $settings['order'],
                'orderby'  => $settings['orderby'],
                'exclude'  => $settings['brands_exclude']
            )
        );

        if ( 'slider' == $settings['type'] ) {
            $slider_options = json_encode( array(
                    "slidesPerView" => 1,
                    "loop"          => 'yes' == $settings['loop'] ? true: false,
                    "autoHeight"    => false,
                    "autoplay"      => 'yes' == $settings['autoplay'] ? true: false,
                    "speed"         => $settings['speed'],
                    "spaceBetween"  => '0' == $settings['space'] ? 0 : $settings['space'],
                    "direction"     => "horizontal",
                    "lazy"          => false,
                    "navigation" => [
                        "nextEl" => ".goldsmith-image-slider .slide-next-{$id}",
                        "prevEl" => ".goldsmith-image-slider .slide-prev-{$id}"
                    ],
                    "pagination" => [
                        "el" => ".goldsmith-image-slider .goldsmith-pagination-$id",
                        "type" => "bullets",
                        "clickable" => true
                    ],
                    "breakpoints" => [
                        "0" => [
                            "slidesPerView" => $settings['xsitems'],
                            "slidesPerGroup" => $settings['xsitems']
                        ],
                        "768" => [
                            "slidesPerView" => $settings['smitems'],
                            "slidesPerGroup" => $settings['smitems']
                        ],
                        "1024" => [
                            "slidesPerView" => $settings['mditems'],
                            "slidesPerGroup" => $settings['mditems']
                        ]
                    ]
                )
            );

            echo '<div class="goldsmith-image-slider goldsmith-swiper-theme-style swiper-container goldsmith-swiper-slider'.$editmode.'" data-swiper-options=\''.$slider_options.'\'>';
                echo '<div class="swiper-wrapper">';
                    foreach ( $brands as $brand ) {
                        $term_meta = get_option( "taxonomy_$brand->term_id" );
                        $image_id  = !empty($term_meta['brand_thumbnail_id']) ? absint( $term_meta['brand_thumbnail_id'] ) : '';
                        if ( $image_id ) {
                            echo '<div class="goldsmith-brands-item swiper-slide">';
                                echo '<div class="goldsmith-brands-item-inner">';
                                    echo '<a href="'.esc_url( get_term_link( $brand ) ).'" title="'.$brand->name.'">';
                                        echo '<div class="goldsmith-brands-thumb img-'.$settings['img_alignment'].'">';
                                            echo wp_get_attachment_image( $image_id, $size, false, ['srcset'=> '', 'class'=>'goldsmith-brands-item-image'] );
                                        echo '</div>';
                                        if ( $brand->description && 'yes' == $settings['brand_desc'] ) {
                                            echo '<div class="goldsmith-brands-content">';
                                                echo '<p class="goldsmith-brands-description">'.$brand->description.'</p>';
                                            echo '</div>';
                                        }
                                    echo '</a>';
                                echo '</div>';
                            echo '</div>';
                        }
                    }
                echo '</div>';
                if ( 'yes' == $settings['dots'] ) {
                    echo '<div class="swiper-pagination goldsmith-pagination-'.$id.'"></div>';
                }
                if ( 'yes' == $settings['nav'] ) {
                    if ( is_rtl() ) {
                        echo '<div class="swiper-button-next slide-next-'.$id.'"></div>';
                        echo '<div class="swiper-button-prev slide-prev-'.$id.'"></div>';
                    } else {
                        echo '<div class="swiper-button-prev slide-prev-'.$id.'"></div>';
                        echo '<div class="swiper-button-next slide-next-'.$id.'"></div>';
                    }
                }
            echo '</div>';
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
                <script>
                jQuery( document ).ready( function($) {
                    var options =  $('.goldsmith-swiper-slider-<?php echo $id ?>').data('swiper-options');
                    const mySlider<?php echo $id ?> = new NTSwiper('.goldsmith-swiper-slider-<?php echo $id ?>', options);

                    $(this).hover(function() {
                        if ( options.autoplay == true ) {
                            mySlider<?php echo $id ?>.autoplay.stop();
                        }
                    }, function() {
                        if ( options.autoplay == true ) {
                            mySlider<?php echo $id ?>.autoplay.start();
                        }
                    });
                });
                </script>
                <?php
            }

        } else {

            echo '<div class="goldsmith-brands-grid row">';
                foreach ( $brands as $brand ) {
                    $term_meta = get_option( "taxonomy_$brand->term_id" );
                    $image_id  = !empty($term_meta['brand_thumbnail_id']) ? absint( $term_meta['brand_thumbnail_id'] ) : '';
                    if ( $image_id ) {
                        echo '<div class="goldsmith-brands-item col-12 col-sm-2 col-md-3">';
                            echo '<div class="goldsmith-brands-item swiper-slide">';
                                echo '<div class="goldsmith-brands-item-inner">';
                                    echo '<a href="'.esc_url( get_term_link( $brand ) ).'" title="'.$brand->name.'">';
                                        echo '<div class="goldsmith-brands-thumb img-'.$settings['img_alignment'].'">';
                                            echo wp_get_attachment_image( $image_id, $size, false, ['srcset'=> '', 'class'=>'goldsmith-brands-item-image'] );
                                        echo '</div>';
                                        if ( $brand->description && 'yes' == $settings['brand_desc'] ) {
                                            echo '<div class="goldsmith-brands-content">';
                                                echo '<p class="goldsmith-brands-description">'.$brand->description.'</p>';
                                            echo '</div>';
                                        }
                                    echo '</a>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }
                }
            echo '</div>';
        }
    }
}
