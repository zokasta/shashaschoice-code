<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Products_List extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-products-list';
    }
    public function get_title() {
        return 'WC Products List (N)';
    }
    public function get_icon() {
        return 'eicon-post-list';
    }
    public function get_categories() {
        return [ 'goldsmith-woo' ];
    }
    public function get_script_depends() {
        return [ 'goldsmith-countdown' ];
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
                    'featured' => esc_html__( 'Featured', 'goldsmith' ),
                    'on-sale' => esc_html__( 'On Sale', 'goldsmith' ),
                    'best' => esc_html__( 'Best Selling', 'goldsmith' ),
                    'custom' => esc_html__( 'Specific Categories', 'goldsmith' )
                ],
                'default' => 'custom'
            ]
        );
        $this->add_control( 'post_per_page',
            [
                'label' => esc_html__( 'Posts Per Page', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'default' => 2
            ]
        );
        $this->add_control( 'category_filter_heading',
            [
                'label' => esc_html__( 'CATEGORY', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
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
                'label' => esc_html__( 'POST', 'goldsmith' ),
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
            ]
        );
        $this->add_control( 'post_other_heading',
            [
                'label' => esc_html__( 'OTHER FILTER', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
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
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('post_section',
            [
                'label'=> esc_html__( 'POST', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_responsive_control( 'spacing',
            [
                'label' => esc_html__( 'Space Between Items', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .product-list-item + .product-list-item' => 'margin-top: {{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'title',
            [
                'label' => esc_html__( 'Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'All Products',
                'label_block' => true
            ]
        );
        $this->add_control( 'link_title',
            [
                'label' => esc_html__( 'Link Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'View All'
            ]
        );
        $this->add_control( 'link',
            [
                'label' => esc_html__( 'Link', 'goldsmith' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => ''
                ],
                'show_external' => true
            ]
        );
        $this->add_control( 'expired',
            [
                'label' => esc_html__( 'Timer Expired Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Expired'
            ]
        );
        $this->add_control( 'desc',
            [
                'label' => esc_html__( 'Timer Short Description', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => 'Remains until the end of the offer'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('style_section',
            [
                'label'=> esc_html__( 'STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control( 'box_sdivider',
            [
                'label' => esc_html__( 'ITEM BOX', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control( 'box_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .product-list-item' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'box_hvrbgcolor',
            [
                'label' => esc_html__( 'Hover Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .product-list-item:hover' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'box_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .product-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .product-list-item'
            ]
        );
        $this->add_responsive_control( 'box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .product-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_bxshodow',
                'label' => esc_html__( 'Box Shadow', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .product-list-item'
            ]
        );
        $this->add_control( 'boxtitle_sdivider',
            [
                'label' => esc_html__( 'TITLE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'boxtitle_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .list-heading .title' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .list-heading .title'
            ]
        );
        $this->add_control( 'toplink_sdivider',
            [
                'label' => esc_html__( 'LINK', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'toplink_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .list-heading .view-all' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'toplink_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .list-heading .view-all:hover' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'toplink_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .list-heading .view-all'
            ]
        );
        $this->add_control( 'title_sdivider',
            [
                'label' => esc_html__( 'ITEM TITLE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .product-list-details .title a' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'title_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .product-list-details .title a:hover' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .product-list-details .title a'
            ]
        );
        $this->add_control( 'stars_heading',
            [
                'label' => esc_html__( 'STARS', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'stars_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .product-list-details .rating i' => 'color: {{VALUE}};',
                ]
            ]
        );
        $this->add_control( 'price_heading',
            [
                'label' => esc_html__( 'PRICE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'price_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .product-list-details p' => 'color: {{VALUE}};',
                ]
            ]
        );
        $this->add_control( 'sale_price_color',
            [
                'label' => esc_html__( 'Save Price Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .product-list-details p span' => 'color: {{VALUE}};',
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'timer_style_section',
            [
                'label'=> esc_html__( 'TIMER STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'time_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-coming-time .time-count'
            ]
        );
        $this->add_responsive_control( 'time_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-coming-time .time-count' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_responsive_control( 'time_last_color',
            [
                'label' => esc_html__( 'Last Item Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-coming-time .time-count:last-child' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_control( 'hide_sep',
            [
                'label' => esc_html__( 'Hide Separator', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );
        $this->add_control( 'time_sep',
            [
                'label' => esc_html__( 'Seperator', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => ':',
                'condition' => ['hide_sep' => 'no']
            ]
        );
        $this->add_responsive_control( 'time_sep_color',
            [
                'label' => esc_html__( 'Seperator Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-coming-time .separator' => 'color:{{VALUE}};' ],
                'condition' => ['hide_sep' => 'no']
            ]
        );
        $this->add_responsive_control( 'time_sep_size',
            [
                'label' => esc_html__( 'Seperator Size', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 15,
                'selectors' => ['{{WRAPPER}} .goldsmith-coming-time .separator' => 'font-size:{{SIZE}}px;' ],
                'condition' => ['hide_sep' => 'no']
            ]
        );
        $this->add_responsive_control( 'time_min_width',
            [
                'label' => esc_html__( 'Item Min Width', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1000,
                'step' => 1,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-coming-time .time-count' => 'min-width:{{SIZE}}px;' ],
            ]
        );
        $this->add_responsive_control( 'time_min_height',
            [
                'label' => esc_html__( 'Item Min height', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1000,
                'step' => 1,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-coming-time .time-count' => 'min-height:{{SIZE}}px;' ],
            ]
        );
        $this->add_responsive_control( 'time_sep_space',
            [
                'label' => esc_html__( 'Space Between Items', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-coming-time .separator' => 'margin:0 {{SIZE}}px;',
                    '{{WRAPPER}} .goldsmith-coming-time.separator-none .time-count + .time-count' => 'margin-left:{{SIZE}}px;'
                ]
            ]
        );
        $this->add_responsive_control( 'time_padding',
            [
                'label' => esc_html__( 'Item Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-coming-time .time-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'time_bgcolor',
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .goldsmith-coming-time .time-count'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'time_last_bgcolor',
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .goldsmith-coming-time .time-count:last-child'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'time_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-coming-time .time-count'
            ]
        );
        $this->add_responsive_control( 'time_last_brdcolor',
            [
                'label' => esc_html__( 'Last Item Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-coming-time .time-count:last-child' => 'border-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'time_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-coming-time .time-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }

        $settings  = $this->get_settings_for_display();
        $elementid = $this->get_id();

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
                    'field'    => 'name',
                    'terms'    => 'featured'
                )
            );

        } elseif ( 'on-sale' == $settings['scenario'] ) {

            $args['meta_query'] = array(
                'relation' => 'OR',
                array( // Simple products type
                    'key'     => '_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'numeric'
                ),
                array( // Variable products type
                    'key'     => '_min_variation_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'numeric'
                )
            );

        } elseif ( 'best' == $settings['scenario'] ) {

            $args['orderby']  = 'meta_value_num';
            $args['meta_key'] = 'total_sales';

        } else {

            $args['orderby'] = $settings['orderby'];

        }

        if ( $settings['category_include'] ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'id',
                    'terms'    => $settings['category_include'],
                    'operator' => 'IN'
                )
            );
        }

        if ( $settings['category_exclude'] ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'id',
                    'terms'    => $settings['category_exclude'],
                    'operator' => 'NOT IN'
                )
            );
        }

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'thumbnail';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size = [ $sizew, $sizeh ];
        }

        $the_query = new \WP_Query( $args );
        if ( $the_query->have_posts() ) {
            echo '<div class="goldsmith-product-list">';
                if ( $settings['title'] ) {
                    echo '<div class="list-heading">';
                        echo '<h4 class="title">'.$settings['title'].'</h4>';
                        echo '<a href="'.$settings['link']['url'].'" class="view-all" title="'.$settings['link_title'].'">'.$settings['link_title'].'</a>';
                    echo '</div>';
                }

                while ( $the_query->have_posts() ) {
                    $the_query->the_post();
                    global $product;
                    $pid  = $product->get_id();
                    $date = get_post_meta( $pid, '_sale_price_dates_to', true );
                    $time = date_i18n( 'Y/m/d', $date );
                    $text = get_post_meta( $pid, 'goldsmith_countdown_text', true);
                    $desc = $settings['desc'] ? $settings['desc'] : $text;

                    echo '<div class="woocommerce product-list-item parent-loading">';
                        echo '<div class="product-list-inner">';
                            echo '<div class="thumb-wrapper">';
                                goldsmith_product_badge();
                                echo '<a class="product-thumb" href="'.get_permalink().'" title="'.get_the_title().'">';
                                    echo get_the_post_thumbnail( $product->get_id(), $size );
                                echo '</a>';
                                goldsmith_loop_product_nostock();
                            echo '</div>';

                            echo '<div class="product-list-details">';
                                if ( wc_review_ratings_enabled() ) {
                                    echo '<div class="rating list-part">';
                                        woocommerce_template_single_rating();
                                    echo '</div>';
                                }
                                echo '<h6 class="title list-part"><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h6>';
                                echo '<p class="list-price list-part">';woocommerce_template_loop_price();echo '</p>';
                    			echo '<div class="list-buttons list-part">';
                                    echo goldsmith_add_to_cart('text');
                    			echo '</div>';
                            echo '</div>';
                            echo '<span class="loading-wrapper"><span class="ajax-loading"></span></span>';
                        echo '</div>';
                        if ( $time ) {
                            echo '<div class="product-list-timer">';
                                $sep = 'yes' == $settings['hide_sep'] ? ' separator-none' : '';
                                $time_opt = '"date":"'.$time.'","expired":"'.$settings['expired'].'"';
                                echo '<div class="goldsmith-coming-time coming-time-mini'.$sep.'" data-countdown=\'{'.$time_opt.'}\'>';
                                    echo '<div class="time-count days"></div>';
                                    echo '<span class="separator">:</span>';
                                    echo '<div class="time-count hours"></div>';
                                    echo '<span class="separator">:</span>';
                                    echo '<div class="time-count minutes"></div>';
                                    echo '<span class="separator">:</span>';
                                    echo '<div class="time-count second"></div>';
                                echo '</div>';
                                if ( $desc ) {
                                    echo '<p class="timer-description">'.$desc.'</p>';
                                }
                            echo '</div>';
                        }
                    echo '</div>';
                }
            echo '</div>';
        } else {
            echo '<p>No product found</p>';
        }
        wp_reset_postdata();

        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
                <script>
                jQuery(document).ready(function($){

                    $('[data-countdown]').each(function () {
                        var self      = $(this),
                            data      = self.data('countdown'),
                            countDate = data.date,
                            expired   = data.expired;

                        let countDownDate = new Date( countDate ).getTime();

                        const d = self.find( '.days' );
                        const h = self.find( '.hours' );
                        const m = self.find( '.minutes' );
                        const s = self.find( '.second' );

                        var x = setInterval(function() {

                            var now = new Date().getTime();

                            var distance = countDownDate - now;

                            var days    = ('0' + Math.floor(distance / (1000 * 60 * 60 * 24))).slice(-2);
                            var hours   = ('0' + Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).slice(-2);
                            var minutes = ('0' + Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).slice(-2);
                            var seconds = ('0' + Math.floor((distance % (1000 * 60)) / 1000)).slice(-2);

                            d.text( days );
                            h.text( hours );
                            m.text( minutes );
                            s.text( seconds );

                            if (distance < 0) {
                                clearInterval(x);
                                console.log( 'expired' );
                                self.html('<div class="expired">' + expired + '</div>');
                            }
                        }, 1000);
                    });
                });
                </script>
            <?php
        }
    }
}
