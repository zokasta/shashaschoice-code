<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Special_Offer extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-special-offer';
    }
    public function get_title() {
        return 'WC Special Offer (N)';
    }
    public function get_icon() {
        return 'eicon-image-box';
    }
    public function get_categories() {
        return [ 'goldsmith-woo' ];
    }
    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'wc', 'woo', 'product', 'special' ];
    }
    public function get_style_depends() {
        return [ 'goldsmith-deals' ];
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
                'default' => 1
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
                    'title' => esc_html__( 'Title', 'goldsmith' )
                ],
                'default' => 'id'
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
            'name' => 'thumbnail',
            'default' => 'woocommerce-thumbnail'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('post_section',
            [
                'label'=> esc_html__( 'LAYOUT & TEXT', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'type',
            [
                'label' => esc_html__( 'Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'slider' => esc_html__( 'Slider', 'goldsmith' ),
                    'grid' => esc_html__( 'Grid', 'goldsmith' ),
                ],
                'default' => 'grid'
            ]
        );
        $this->add_responsive_control( 'col',
            [
                'label' => esc_html__( 'Column', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'default' => 2,
                'selectors' => ['{{WRAPPER}} .goldsmith-product-special-offers .row>.item-col' => '-ms-flex: 0 0 calc(100% / {{VALUE}} );flex: 0 0 calc(100% / {{VALUE}} );width: calc(100% / {{VALUE}} );'],
                'condition' => [ 'type' => 'grid' ]
            ]
        );
        $this->add_control('column_gap',
            [
                'label' => __( 'Columns Gap', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-product-special-offers .row>.item-col' => 'padding: 0 {{SIZE}}px;margin-bottom: {{SIZE}}px;',
                    '{{WRAPPER}} .goldsmith-product-special-offers .row' => 'margin: 0 -{{SIZE}}px -{{SIZE}}px -{{SIZE}}px;'
                ],
                'condition' => [ 'type' => 'grid' ]
            ]
        );
        $this->add_control( 'layout',
            [
                'label' => esc_html__( 'Item Layout', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => esc_html__( 'Horizontal', 'goldsmith' ),
                    'vertical' => esc_html__( 'Vertical', 'goldsmith' ),
                ],
                'default' => 'horizontal'
            ]
        );
        $this->add_control( 'item_style',
            [
                'label' => esc_html__( 'Item Style', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'style-1' => esc_html__( 'Style 1', 'goldsmith' ),
                    'style-2' => esc_html__( 'Style 2', 'goldsmith' ),
                ],
                'default' => 'style-1'
            ]
        );
        $this->add_control( 'title',
            [
                'label' => esc_html__( 'Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Timed special offer for you',
                'label_block' => true,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'desc',
            [
                'label' => esc_html__( 'Short Description', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => 'Time remaining until the end of the offer; Hurry to take advantage of the offer'
            ]
        );
        $this->add_control( 'excerpt',
            [
                'label' => esc_html__( 'Product Short Description', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'progressbar',
            [
                'label' => esc_html__( 'Progressbar', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control( 'sold_title',
            [
                'label' => esc_html__( 'Sold Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Sold:',
                'condition' => [ 'progressbar' => 'yes' ]
            ]
        );
        $this->add_control( 'available_title',
            [
                'label' => esc_html__( 'Available Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Available:',
                'condition' => [ 'progressbar' => 'yes' ]
            ]
        );
        $this->add_control( 'expired',
            [
                'label' => esc_html__( 'Timer Expired Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Expired'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
       /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('slider_options_section',
            [
                'label'=> esc_html__( 'SLIDER OPTIONS', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [ 'type' => 'slider' ]
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
        $this->add_responsive_control( 'box_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .deals-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .deals-item'
            ]
        );
        $this->add_responsive_control( 'box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .deals-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_hvrborder',
                'label' => esc_html__( 'Hover Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .deals-item:before'
            ]
        );
        $this->add_responsive_control( 'hvrbox_border_radius',
            [
                'label' => esc_html__( 'Hover Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .deals-item:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_bxshodow',
                'label' => esc_html__( 'Box Shadow', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .deals-item'
            ]
        );
        $this->add_control( 'box_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .deals-item' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'box_hvrbgcolor',
            [
                'label' => esc_html__( 'Hover Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .deals-item:hover' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'image_sdivider',
            [
                'label' => esc_html__( 'IMAGE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .deals-item .deals-thumb'
            ]
        );
        $this->add_responsive_control( 'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .deals-item .deals-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
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
                'selectors' => [ '{{WRAPPER}} .deals-top .title' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .deals-top .title'
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
                'selectors' => [ '{{WRAPPER}} .deals-top .view-all' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'toplink_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .deals-top .view-all:hover' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'toplink_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .deals-top .view-all'
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
                'selectors' => [ '{{WRAPPER}} .details-wrapper .title a' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'title_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .details-wrapper .title a:hover' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .details-wrapper .title a'
            ]
        );
        $this->add_control( 'stars_heading',
            [
                'label' => esc_html__( 'STARS', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'stars_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .details-wrapper .star-rating>span::before' => 'color: {{VALUE}};' ]
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
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .details-wrapper .price' => 'color: {{VALUE}};' ]
            ]
        );
        $this->add_control( 'sale_price_color',
            [
                'label' => esc_html__( 'Save Price Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .details-wrapper .price span' => 'color: {{VALUE}};' ]
            ]
        );
        $this->add_control( 'progressbar_heading',
            [
                'label' => esc_html__( 'PROGRESSBAR', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [ 'progressbar' => 'yes' ]
            ]
        );
        $this->add_control( 'progressbar_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .deals-inner .stock-progress' => 'background-color: {{VALUE}};' ],
                'condition' => [ 'progressbar' => 'yes' ]
            ]
        );
        $this->add_control( 'progressbar_color',
            [
                'label' => esc_html__( 'Bar Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .deals-inner .stock-progressbar' => 'background-color: {{VALUE}};' ],
                'condition' => [ 'progressbar' => 'yes' ]
            ]
        );
        $this->add_control( 'progressbar_label_color',
            [
                'label' => esc_html__( 'Details Label Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .deals-inner .stock-details .status-label' => 'color: {{VALUE}};' ],
                'condition' => [ 'progressbar' => 'yes' ]
            ]
        );
        $this->add_control( 'progressbar_value_color',
            [
                'label' => esc_html__( 'Details Value Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .deals-inner .stock-details .status-value' => 'color: {{VALUE}};' ],
                'condition' => [ 'progressbar' => 'yes' ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'style_section',
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

    protected function render() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }

        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();

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

        } elseif( 'on-sale' == $settings['scenario'] ) {

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

        } elseif( 'best' == $settings['scenario'] ) {

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

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'woocommerce-thumbnail';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size = [ $sizew, $sizeh ];
        }

        $editmode = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';
        $btn_icon = 'vertical' == $settings['type'] ? 'icon' : 'button';

        if ( 'slider' == $settings['type'] ) {
            $slider_options = json_encode( array(
                    "slidesPerView" => 1,
                    "touchRatio"    => 2,
                    "loop"          => 'yes' == $settings['loop'] ? true: false,
                    "autoHeight"    => false,
                    "rewind"        => true,
                    "autoplay"      => 'yes' == $settings['autoplay'] ? [ "pauseOnMouseEnter" => true,"disableOnInteraction" => false ] : false,
                    "wrapperClass"  => "goldsmith-swiper-wrapper",
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
        }

        $the_query = new \WP_Query( $args );
        if ( $the_query->have_posts() ) {
            echo '<div class="goldsmith-product-special-offers type-'.$settings['type'].'">';

                if ( 'grid' == $settings['type']) {
                    echo '<div class="row">';
                        while ( $the_query->have_posts() ) {
                            $the_query->the_post();
                            global $product;
                            $pid           = $product->get_id();
                            $short_desc    = $product->get_short_description();
                            $current_stock = get_post_meta( $pid, '_stock', true );
                            $total_sold    = $product->get_total_sales();
                            $percentage    = $total_sold > 0 && $current_stock > 0 ? round( $total_sold / $current_stock * 100 ) : 0;
                            $date          = get_post_meta( $pid, '_sale_price_dates_to', true );
                            $time          = date_i18n( 'Y/m/d', $date );

                            echo '<div class="col-12 col-md-6 item-col">';
                                echo '<div class="woocommerce deals-item">';
                                    echo '<div class="deals-inner style-'.$settings['layout'].'">';
                                        echo '<div class="thumb-wrapper parent-loading">';
                                            echo goldsmith_wishlist_button();
                                            echo '<div class="deals-product-labels">';
                                                goldsmith_product_badge();
                                                goldsmith_product_discount();
                                            echo '</div>';
                                            echo '<a class="deals-thumb" href="'.get_permalink().'" title="'.get_the_title().'">';
                                                echo get_the_post_thumbnail( $pid, $size );
                                            echo '</a>';

                                            if ( 'style-1' == $settings['item_style'] ) {
                                    			echo '<div class="deals-buttons">';
                                                    echo goldsmith_compare_button();
                                                    echo goldsmith_quickview_button();
                                                    echo goldsmith_add_to_cart('icon');
                                    			echo '</div>';
                                            }
                                            echo '<span class="loading-wrapper"><span class="ajax-loading"></span></span>';
                                        echo '</div>';

                                        echo '<div class="details-wrapper">';
                                            echo '<h6 class="title deals-part"><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h6>';
                                            if ( wc_review_ratings_enabled() ) {
                                                echo '<div class="rating deals-part">';
                                                    woocommerce_template_single_rating();
                                                echo '</div>';
                                            }
                                            echo '<p class="deals-price deals-part">';woocommerce_template_loop_price();echo '</p>';
                                            if ( 'yes' == $settings['progressbar'] ) {
                                                echo '<div class="progressbar-wrapper deals-part">';
                                                    echo '<div class="stock-progress">';
                                                        echo '<div class="stock-progressbar" style="width:'.$percentage.'%"></div>';
                                                    echo '</div>';
                                                    echo '<div class="stock-details">';
                                                        echo '<div class="stock-sold"><span class="status-label">'.$settings['sold_title'].' </span><span class="status-value">'.$total_sold.'</span></div>';
                                                        if ( $current_stock>0  ) {
                                                            echo '<div class="current-stock"><span class="status-label">'.$settings['available_title'].' </span><span class="status-value">'.wc_trim_zeros($current_stock).'</span></div>';
                                                        }
                                                    echo '</div>';
                                                echo '</div>';
                                            }
                                            if ( 'yes' == $settings['excerpt'] && $short_desc ) {
                                        		echo '<div class="product-details deals-part">'.$short_desc.'</div>';
                                            }
                                            if ( 'style-2' == $settings['item_style'] ) {
                                    			echo '<div class="deals-buttons deals-part style-2 hints-top">';
                                    			    echo goldsmith_add_to_cart($btn_icon);
                                                    echo goldsmith_compare_button();
                                                    echo goldsmith_quickview_button();
                                    			echo '</div>';
                                            }
                                        echo '</div>';
                                    echo '</div>';
                                    if ( $settings['title'] ) {
                                        echo '<div class="deals-heading deals-heading-bottom">';
                                            if ( $settings['title'] || $settings['desc'] ) {
                                                echo '<div class="title-wrapper">';
                                                    echo '<h4 class="title">'.$settings['title'].'</h4>';
                                                    if ( $settings['desc'] ) {
                                                        echo '<p class="short-description">'.$settings['desc'].'</p>';
                                                    }
                                                echo '</div>';
                                            }
                                            if ( $time ) {
                                                $sep = 'yes' == $settings['hide_sep'] ? ' separator-none' : '';
                                                $time = '"date":"'.$time.'","expired":"'.$s['expired'].'"';
                                                echo '<div class="goldsmith-coming-time goldsmith-widget-coming-time'.$sep.' countdown-'.$id.'" data-countdown=\'{'.$time.'}\'>';
                                                    echo '<div class="time-count days"></div>';
                                                    echo '<span class="separator">:</span>';
                                                    echo '<div class="time-count hours"></div>';
                                                    echo '<span class="separator">:</span>';
                                                    echo '<div class="time-count minutes"></div>';
                                                    echo '<span class="separator">:</span>';
                                                    echo '<div class="time-count second"></div>';
                                                echo '</div>';
                                            }
                                        echo '</div>';
                                    }
                                echo '</div>';
                            echo '</div>';
                        }
                    echo '</div>';

                } else {

                    echo '<div class="goldsmith-products-widget-slider goldsmith-swiper-container goldsmith-swiper-slider'.$editmode.' nav-vertical-centered" data-swiper-options=\''.$slider_options.'\'>';
                        echo '<div class="goldsmith-swiper-wrapper">';
                            while ( $the_query->have_posts() ) {
                                $the_query->the_post();
                                global $product;
                                $pid           = $product->get_id();
                                $current_stock = get_post_meta( $pid, '_stock', true );
                                $total_sold    = $product->get_total_sales();
                                $percentage    = $total_sold > 0 && $current_stock > 0 ? round( $total_sold / $current_stock * 100 ) : 0;
                                $date          = get_post_meta( $pid, '_sale_price_dates_to', true );
                                $time          = $settings['date'] ? $settings['date'] : date_i18n( 'Y/m/d', $date );

                                echo '<div class="swiper-slide">';
                                    echo '<div class="woocommerce deals-item">';
                                        echo '<div class="deals-inner style-'.$settings['layout'].'">';
                                            echo '<div class="thumb-wrapper parent-loading">';
                                                echo goldsmith_wishlist_button();
                                                echo '<div class="deals-product-labels">';
                                                    goldsmith_product_badge();
                                                    goldsmith_product_discount();
                                                echo '</div>';
                                                echo '<a class="deals-thumb" href="'.get_permalink().'" title="'.get_the_title().'">';
                                                    echo get_the_post_thumbnail( $pid, $size );
                                                echo '</a>';
                                                if ( 'style-1' == $settings['item_style'] ) {
                                        			echo '<div class="deals-buttons">';
                                                        echo goldsmith_compare_button();
                                                        echo goldsmith_quickview_button();
                                                        echo goldsmith_add_to_cart('button');
                                        			echo '</div>';
                                                }
                                                goldsmith_loop_product_nostock();
                                                echo '<span class="loading-wrapper"><span class="ajax-loading"></span></span>';
                                            echo '</div>';

                                            echo '<div class="details-wrapper">';
                                                echo '<h6 class="title deals-part"><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h6>';
                                                if ( wc_review_ratings_enabled() ) {
                                                    echo '<div class="rating deals-part">';
                                                        woocommerce_template_single_rating();
                                                    echo '</div>';
                                                }
                                                echo '<p class="deals-price deals-part">';woocommerce_template_loop_price();echo '</p>';
                                                if ( 'yes' == $settings['progressbar'] ) {
                                                    echo '<div class="progressbar-wrapper deals-part">';
                                                        echo '<div class="stock-progress">';
                                                            echo '<div class="stock-progressbar" style="width:'.$percentage.'%"></div>';
                                                        echo '</div>';
                                                        echo '<div class="stock-details">';
                                                            echo '<div class="stock-sold"><span class="status-label">'.$settings['sold_title'].' </span><span class="status-value">'.$total_sold.'</span></div>';
                                                            if ( $current_stock>0  ) {
                                                                echo '<div class="current-stock"><span class="status-label">'.$settings['available_title'].' </span><span class="status-value">'.wc_trim_zeros($current_stock).'</span></div>';
                                                            }
                                                        echo '</div>';
                                                    echo '</div>';
                                                }
                                                if ( 'yes' == $settings['excerpt'] && $short_desc ) {
                                            		echo '<div class="product-details deals-part">'.$short_desc.'</div>';
                                                }
                                                if ( 'style-2' == $settings['item_style'] ) {
                                        			echo '<div class="deals-buttons deals-part hints-top">';
                                        			    echo goldsmith_add_to_cart($btn_icon);
                                                        echo goldsmith_compare_button();
                                                        echo goldsmith_quickview_button();
                                        			echo '</div>';
                                                }
                                            echo '</div>';
                                        echo '</div>';

                                        if ( $settings['title'] ) {
                                            echo '<div class="deals-heading deals-heading-bottom">';
                                                if ( $settings['title'] || $settings['desc'] ) {
                                                    echo '<div class="title-wrapper">';
                                                        echo '<h4 class="title">'.$settings['title'].'</h4>';
                                                        if ( $settings['desc'] ) {
                                                            echo '<p class="short-description">'.$settings['desc'].'</p>';
                                                        }
                                                    echo '</div>';
                                                }
                                                if ( $time ) {
                                                    $sep = 'yes' == $settings['hide_sep'] ? ' separator-none' : '';
                                                    $time = '"date":"'.$time.'","expired":"'.$s['expired'].'"';
                                                    echo '<div class="goldsmith-coming-time goldsmith-widget-coming-time gradient-bg '.$sep.' countdown-'.$id.'" data-countdown=\'{'.$time.'}\'>';
                                                        echo '<div class="time-count days"></div>';
                                                        echo '<span class="separator">:</span>';
                                                        echo '<div class="time-count hours"></div>';
                                                        echo '<span class="separator">:</span>';
                                                        echo '<div class="time-count minutes"></div>';
                                                        echo '<span class="separator">:</span>';
                                                        echo '<div class="time-count second"></div>';
                                                    echo '</div>';
                                                }
                                            echo '</div>';
                                        }

                                    echo '</div>';
                                echo '</div>';
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
            echo '</div>';
        } else {
            echo '<p>No product found</p>';
        }
        wp_reset_postdata();

        if ( 'slider' == $settings['type'] && \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
            <script>
                jQuery( document ).ready( function($) {
                    const mySlider = new NTSwiper('.goldsmith-swiper-slider-<?php echo $id; ?>', $('.goldsmith-swiper-slider-<?php echo $id; ?>').data('swiper-options'));
                });
            </script>
            <?php
        }

        if (  \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
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
