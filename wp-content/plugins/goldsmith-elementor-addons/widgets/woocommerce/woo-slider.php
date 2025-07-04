<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Slider extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-slider';
    }
    public function get_title() {
        return 'WC Slider (N)';
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
        return [ 'goldsmith-swiper' ];
    }
    // Registering Controls
    protected function register_controls() {

        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'post_query_section',
            [
                'label' => esc_html__( 'Query', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'scenario',
            [
                'label' => esc_html__( 'Select Scenario', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Newest', 'goldsmith' ),
                    'featured' => esc_html__( 'Featured', 'goldsmith' ),
                    'on-sale' => esc_html__( 'On Sale', 'goldsmith' ),
                    'best' => esc_html__( 'Best Selling', 'goldsmith' ),
                    'custom' => esc_html__( 'Specific Categories', 'goldsmith' ),
                ],
                'default' => ''
            ]
        );
        $this->add_control( 'post_per_page',
            [
                'label' => esc_html__( 'Posts Per Page', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'default' => 20
            ]
        );
        $this->add_control( 'category_filter_heading',
            [
                'label' => esc_html__( 'Category Filter', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $this->add_control( 'category_include',
            [
                'label' => esc_html__( 'Category', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->goldsmith_cpt_taxonomies('product_cat'),
                'description' => 'Select Category(s)',
                'condition' => [ 'scenario' => 'custom' ]
            ]
        );
        $this->add_control( 'category_exclude',
            [
                'label' => esc_html__( 'Exclude Category', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->goldsmith_cpt_taxonomies('product_cat'),
                'description' => 'Select Category(s) to Exclude',
            ]
        );
        $this->add_control( 'post_filter_heading',
            [
                'label' => esc_html__( 'Post Filter', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'post_include',
            [
                'label' => esc_html__( 'Specific Post(s)', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_posts_by_type('product'),
                'description' => 'Select Specific Post(s)',
                'condition' => [ 'scenario' => 'custom' ]
            ]
        );
        $this->add_control( 'post_exclude',
            [
                'label' => esc_html__( 'Exclude Post', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_posts_by_type('product'),
                'description' => 'Select Post(s) to Exclude',
                'separator' => 'after',
            ]
        );
        $this->add_control( 'post_other_heading',
            [
                'label' => esc_html__( 'Other Filter', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
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
                'default' => 'DESC'
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
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'goldsmith-mini',
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
                'max' => 8,
                'step' => 1,
                'default' => 5
            ]
        );
        $this->add_control( 'smitems',
            [
                'label' => esc_html__( 'Items Tablet', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 3,
                'step' => 1,
                'default' => 3
            ]
        );
        $this->add_control( 'xsitems',
            [
                'label' => esc_html__( 'Items Phone', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 2,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('product_style_section',
            [
                'label'=> esc_html__( 'PRODUCT STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'item_style',
            [
                'label' => esc_html__( 'Style', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'style-bordered' => esc_html__( 'Bordered', 'goldsmith' ),
                    'style-default' => esc_html__( 'Default', 'goldsmith' ),
                ],
                'default' => 'style-default'
            ]
        );
        $this->add_responsive_control( 'title_color',
            [
                'label' => esc_html__( 'Title Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-product-name,
                    {{WRAPPER}} .goldsmith-product-cart' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'addto_color',
            [
                'label' => esc_html__( 'Add to Cart Title Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-product .goldsmith-btn-small,
                    {{WRAPPER}} .goldsmith-has-hidden-cart .goldsmith-btn-small,
                    {{WRAPPER}} .goldsmith-product-cart,
                    {{WRAPPER}} .goldsmith-product-cart a:hover,
                    {{WRAPPER}} .goldsmith-block-right .goldsmith-btn-small' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'price_color',
            [
                'label' => esc_html__( 'Price Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-price,
                    {{WRAPPER}} .woocommerce-variation-price .price span.del>span,
                    {{WRAPPER}} .goldsmith-price span.del>span' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'stars_color',
            [
                'label' => esc_html__( 'Star Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .star-rating::before' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'stars_rated_color',
            [
                'label' => esc_html__( 'Star Rated Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .star-rating>span::before' => 'color:{{VALUE}};' ]
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
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-swiper-prev:after,{{WRAPPER}} .goldsmith-swiper-next:after' => 'font-size:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'navs_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-prev:after,{{WRAPPER}} .goldsmith-swiper-next:after' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-swiper-prev:hover:after,{{WRAPPER}} .goldsmith-swiper-next:hover:after' => 'color:{{VALUE}};' ]
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
                    '{{WRAPPER}} .goldsmith-swiper-bullet:before,{{WRAPPER}} .goldsmith-swiper-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
                    '{{WRAPPER}} .swiper-pagination-bullets .goldsmith-swiper-bullet.active:before,{{WRAPPER}} ..goldsmith-swiper-bullet.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
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
        global $wp_query;
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();

        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $settings['post_per_page'],
            'post__in'       => $settings['post_include'],
            'post__not_in'   => $settings['post_exclude'],
            'order'          => $settings['order']
        );

        if ( 'featured' == $settings['scenario'] ) {
           $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured'
                )
            );

        } elseif('on-sale' == $settings['scenario']) {

            $args['meta_query'] = array(
                'relation' => 'OR',
                array( // Simple products type
                    'key' => '_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'numeric'
                ),
                array( // Variable products type
                    'key' => '_min_variation_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'numeric'
                )
            );

        } elseif('best' == $settings['scenario']) {

            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'total_sales';

        } else {

            $args['orderby'] = $settings['orderby'];

        }

        if ( $settings['category_include'] ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $settings['category_include'],
                    'operator' => 'IN'
                )
            );
        }
        if ( $settings['category_exclude'] ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $settings['category_exclude'],
                    'operator' => 'NOT IN'
                )
            );
        }

        $editmode   = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

        $slider_options = json_encode( array(
                "slidesPerView" => 1,
                "touchRatio"    => 2,
                "loop"          => 'yes' == $settings['loop'] ? true: false,
                "autoHeight"    => false,
                "rewind"        => true,
                "autoplay"      => 'yes' == $settings['autoplay'] ? [ "pauseOnMouseEnter" => true,"disableOnInteraction" => false ] : false,
                "wrapperClass"  => "goldsmith-swiper-wrapper",
                "centeredSlides"=> 'yes' == $settings['centermode'] ? true: false,
                "speed"         => $settings['speed'],
                "spaceBetween"  => $settings['space'] ? $settings['space'] : 30,
                "direction"     => "horizontal",
                "navigation" => [
                    "nextEl" => ".slide-next-$id",
                    "prevEl" => ".slide-prev-$id"
                ],
                "pagination" => [
                    "el"                => ".goldsmith-pagination-$id",
                    "bulletClass"       => "goldsmith-swiper-bullet",
                    "bulletActiveClass" => "active",
                    "type"              => "bullets",
                    "clickable"         => true
                ],
                "breakpoints" => [
                    "0" => [
                        "slidesPerView"  => $settings['xsitems'],
                        "slidesPerGroup" => $settings['xsitems']
                    ],
                    "768" => [
                        "slidesPerView"  => $settings['smitems'],
                        "slidesPerGroup" => $settings['smitems']
                    ],
                    "1024" => [
                        "freeMode"       => false,
                        "slidesPerView"  => $settings['mditems'],
                        "slidesPerGroup" => $settings['mditems']
                    ]
                ]
            )
        );

        add_filter( 'single_product_archive_thumbnail_size', [$this, 'image_custom_size' ] );
        $the_query = new \WP_Query( $args );
        if ( $the_query->have_posts() ) {
            echo '<div class="goldsmith-products-widget-slider goldsmith-swiper-container goldsmith-swiper-slider'.$editmode.' nav-vertical-centered" data-swiper-options=\''.$slider_options.'\'>';
                echo '<div class="goldsmith-swiper-wrapper">';
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post();
                        $product = new \WC_Product(get_the_ID());
                        $visibility = $product->get_catalog_visibility();

                        if ( $product->is_visible() ) {
                            echo '<div class="swiper-slide product_item visibility-'.$visibility.' '.$settings['item_style'].'">';
                                wc_get_template_part( 'content', 'product' );
                            echo '</div>';
                        }
                    }
                echo '</div>';
                
                if ( 'yes' == $settings['dots'] ) {
                    echo '<div class="goldsmith-swiper-pagination goldsmith-pagination-'.$id.' position-relative"></div>';
                }
                
                if ( 'yes' == $settings['nav'] ) {
                    echo '<div class="goldsmith-swiper-prev goldsmith-nav-bg goldsmith-nav-small slide-prev-'.$id.'"></div>';
                    echo '<div class="goldsmith-swiper-next goldsmith-nav-bg goldsmith-nav-small slide-next-'.$id.'"></div>';
                }

            echo '</div>';

        }
        wp_reset_postdata();

        remove_filter( 'single_product_archive_thumbnail_size', [$this, 'image_custom_size' ] );

        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
            <script>
            jQuery( document ).ready( function($) {
                const mySlider = new NTSwiper('.goldsmith-swiper-slider-<?php echo $id; ?>', $('.goldsmith-swiper-slider-<?php echo $id; ?>').data('swiper-options'));
            });
            </script>
            <?php
        }
    }
}
