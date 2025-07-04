<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Banner extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-banner';
    }
    public function get_title() {
        return 'Banner (N)';
    }
    public function get_icon() {
        return 'eicon-icon-box';
    }
    public function get_categories() {
        return [ 'goldsmith-woo' ];
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
        $this->add_control( 'category',
            [
                'label' => esc_html__( 'Select Category', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $this->goldsmith_cpt_taxonomies('product_cat')
            ]
        );
        $this->add_control( 'image',
            [
                'label' => esc_html__( 'Image', 'goldsmith' ),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => '']
            ]
        );
        $this->add_control( 'use_video',
            [
                'label' => esc_html__( 'Use Background Video', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'video_provider',
            [
                'label' => esc_html__( 'Video Source', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube' => esc_html__( 'YouTube', 'goldsmith' ),
                    'vimeo' => esc_html__( 'Vimeo', 'goldsmith' ),
                    'local' => esc_html__( 'Local', 'goldsmith' ),
                    'iframe' => esc_html__( 'Custom Iframe Embed', 'goldsmith' ),
                ],
                'condition' => ['use_video' => 'yes']
            ]
        );
        $this->add_control( 'iframe_embed',
            [
                'label' => esc_html__( 'Custom Iframe Embed Code', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'label_block' => true,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'video_provider','operator' => '==','value' => 'iframe' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'loacal_video_url',
            [
                'label' => esc_html__( 'Loacal Video URL', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'label_block' => true,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'video_provider','operator' => '==','value' => 'local' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'video_id',
            [
                'label' => esc_html__( 'Video ID', 'goldsmith' ),
                'placeholder' => '',
                'description' => esc_html__( 'YouTube/Vimeo video ID.', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'video_provider','operator' => '!=','value' => 'iframe' ],
                        [ 'name' => 'video_provider','operator' => '!=','value' => 'local' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'video_loop',
            [
                'label' => esc_html__( 'Loop', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'video_provider','operator' => '!=','value' => 'iframe' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'video_start',
            [
                'label' => esc_html__( 'Video Start', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10000,
                'step' => 1,
                'default' => '',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'video_provider','operator' => '!=','value' => 'iframe' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'video_end',
            [
                'label' => esc_html__( 'Video Start', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10000,
                'step' => 1,
                'default' => '',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'video_provider','operator' => '!=','value' => 'iframe' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'auto_calculate',
            [
                'label' => esc_html__( 'Auto Calculate Video Size', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        $this->add_control( 'aspect_ratio',
            [
                'label' => esc_html__( 'Aspect Ratio', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '16:9' => esc_html__( '16:9 ( Standard Video )', 'goldsmith' ),
                    '9:16' => esc_html__( '9:16 ( for vertical video )', 'goldsmith' ),
                    '1:1' =>esc_html__( '1:1', 'goldsmith' ),
                    '4:3' => esc_html__( '4:3', 'goldsmith' ),
                    '3:2' => esc_html__( '3:2', 'goldsmith' ),
                    '21:9' => esc_html__( '21:9', 'goldsmith' ),
                ],
                'default' => '16:9',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'auto_calculate','operator' => '==','value' => 'yes' ]
                    ]
                ]
            ]
        );
        $this->add_responsive_control( 'video_box_size',
            [
                'label' => esc_html__( 'Video Box Height', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 4000,
                'step' => 1,
                'default' => 100,
                'selectors' => [ '{{WRAPPER}} .goldsmith-woo-banner-wrapper' => 'height:{{SIZE}}px;' ],
                'condition' => ['use_video' => 'yes']
            ]
        );
        $this->add_responsive_control( 'video_width',
            [
                'label' => esc_html__( 'Custom Video Width', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 4000,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-woo-banner-iframe-wrapper iframe' => 'width:{{SIZE}}px;max-width:none;' ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'auto_calculate','operator' => '!=','value' => 'yes' ]
                    ]
                ]
            ]
        );
        $this->add_responsive_control( 'video_height',
            [
                'label' => esc_html__( 'Custom Video Height', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 4000,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-woo-banner-iframe-wrapper iframe' => 'height:{{SIZE}}px;max-width:none;' ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'auto_calculate','operator' => '!=','value' => 'yes' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'full_height',
            [
                'label' => esc_html__( 'Full Height', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['use_video!' => 'yes']
            ]
        );
        $this->add_responsive_control( 'fit_size',
            [
                'label' => esc_html__( 'Min Image Box Height (px)', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 2000,
                'step' => 1,
                'default' => 200,
                'selectors' => [ '{{WRAPPER}} .goldsmith-woo-banner-wrapper .goldsmith-banner-image' => 'min-height:{{SIZE}}px;padding-top:0;' ],
                'condition' => ['use_video!' => 'yes']
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
                'condition' => ['use_video!' => 'yes']
            ]
        );
        $this->add_control('img_bottom_position',
            [
                'label' => esc_html__( 'Image Custom Vertical Position', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000
                    ]
                ],
                'selectors' => ['{{WRAPPER}} .goldsmith-woo-banner-wrapper .goldsmith-banner-image img' => 'bottom: {{SIZE}}px;top: auto;']
            ]
        );
        $this->add_control('img_left_position',
            [
                'label' => esc_html__( 'Image Custom Horizontal Position', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000
                    ]
                ],
                'selectors' => ['{{WRAPPER}} .goldsmith-woo-banner-wrapper .goldsmith-banner-image img' => 'left: {{SIZE}}px;right: auto;']
            ]
        );
        $this->add_control( 'title',
            [
                'label' => esc_html__( 'Custom Title/Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Free Shipping On Over $ 50',
                'label_block' => true,
                'separator' => 'before'
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
        $this->add_control( 'desc',
            [
                'label' => esc_html__( 'Short Description', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Agricultural mean crops livestock',
                'label_block' => true
            ]
        );
        $this->add_control( 'count_text',
            [
                'label' => esc_html__( 'After Count Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Products',
                'label_block' => true
            ]
        );
        $this->add_control( 'btn_title',
            [
                'label' => esc_html__( 'Button Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'See More Products',
                'label_block' => true
            ]
        );
        $this->add_control( 'icon',
            [
                'label' => esc_html__( 'Button Icon', 'goldsmith' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'solid'
                ]
            ]
        );
        $this->add_control( 'icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50
                    ]
                ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-button i' => 'margin-left: {{SIZE}}px;']
            ]
        );
        $this->add_control( 'link',
            [
                'label' => esc_html__( 'Custom Link', 'goldsmith' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '',
                    'is_external' => ''
                ],
                'show_external' => true
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
        $this->add_control( 'baner_style',
            [
                'label' => esc_html__( 'Banner Style', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'multiple' => false,
                'options' => [
                    'card'  => esc_html__( 'Card', 'goldsmith' ),
                    'card-hover'  => esc_html__( 'Card Hover', 'goldsmith' ),
                    'classic' => esc_html__( 'Classic', 'goldsmith' )
                ],
                'default' => 'card'
            ]
        );
        $repeater = new Repeater();
        $repeater->add_control( 'item_order',
            [
                'label' => esc_html__( 'Content Item order', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'multiple' => false,
                'options' => [
                    'cat'  => esc_html__( 'Category', 'goldsmith' ),
                    'title'  => esc_html__( 'Title', 'goldsmith' ),
                    'desc' => esc_html__( 'Description', 'goldsmith' ),
                    'count' => esc_html__( 'Count', 'goldsmith' ),
                    'button' => esc_html__( 'Button', 'goldsmith' ),
                ],
                'default' => 'cat',
            ]
        );
        $repeater->add_control( 'item_position',
            [
                'label' => esc_html__( 'Select Item Position', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'multiple' => false,
                'options' => [
                    'top'  => esc_html__( 'Top', 'goldsmith' ),
                    'center'  => esc_html__( 'Center', 'goldsmith' ),
                    'bottom' => esc_html__( 'Bottom', 'goldsmith' ),
                ],
                'default' => 'top',
            ]
        );
        $this->add_control('content_orders',
            [
                'label' => esc_html__( 'Content Items Order', 'goldsmith' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_order' => 'cat',
                        'item_position' => 'top'
                    ],
                    [
                        'item_order' => 'title',
                        'item_position' => 'top'
                    ],
                ],
                'title_field' => '{{{item_order}}} - {{{item_position}}}',
            ]
        );
        $this->add_control( 'box_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-woo-banner-wrapper' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'box_padding',
            [
                'label' => esc_html__( 'Box Content Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-woo-banner-wrapper .goldsmith-banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .goldsmith-woo-banner-wrapper'
            ]
        );
        $this->add_responsive_control( 'box_border_radius',
            [
                'label' => esc_html__( 'Box Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-woo-banner-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']
            ]
        );
        $this->add_responsive_control( 'overlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-woo-banner-wrapper:not(.banner-style-classic):before,
                    {{WRAPPER}} .goldsmith-woo-banner-wrapper.banner-style-classic .goldsmith-banner-image:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'overlay_hvrcolor',
            [
                'label' => esc_html__( 'Hover Overlay Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-woo-banner-wrapper:not(.banner-style-classic):hover::before,
                    {{WRAPPER}} .goldsmith-woo-banner-wrapper.banner-style-classic .goldsmith-banner-image:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'text_hvrcolor',
            [
                'label' => esc_html__( 'Hover Text Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-woo-banner-wrapper:hover .goldsmith-banner-content .goldsmith-banner-title,{{WRAPPER}} .goldsmith-woo-banner-wrapper:hover .goldsmith-banner-content ' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'image_hvrscale',
            [
                'label' => esc_html__( 'Hover Image Scale', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 2,
                'step' => 0.1,
                'default' => 1.2,
                'selectors' => [ '{{WRAPPER}} .goldsmith-woo-banner-wrapper:hover .goldsmith-banner-image img' => 'transform: scale( {{SIZE}} );' ],
            ]
        );
        $this->add_responsive_control( 'alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .goldsmith-woo-banner-wrapper .goldsmith-banner-content' => 'text-align: {{VALUE}};'],
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
                'default' => 'left'
            ]
        );
        $this->add_control( 'cat_divider',
            [
                'label' => esc_html__( 'CATEGORY', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'bg_type',
            [
                'label' => esc_html__( 'Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'label-trans',
                'options' => [
                    'label-trans' => esc_html__( 'Transparent', 'goldsmith' ),
                    'label-border' => esc_html__( 'Bordered', 'goldsmith' ),
                    'label-solid' => esc_html__( 'Solid', 'goldsmith' ),
                ]
            ]
        );
        $this->add_control( 'color_type',
            [
                'label' => esc_html__( 'Color Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'goldsmith-label-yellow',
                'options' => [
                    'goldsmith-label-primary' => esc_html__( 'Primary', 'goldsmith' ),
                    'goldsmith-label-dark' => esc_html__( 'Black', 'goldsmith' ),
                    'goldsmith-label-gray' => esc_html__( 'Gray', 'goldsmith' ),
                    'goldsmith-label-gray-soft' => esc_html__( 'Gray Soft', 'goldsmith' ),
                    'goldsmith-label-green' => esc_html__( 'Green', 'goldsmith' ),
                    'goldsmith-label-green-soft' => esc_html__( 'Green Soft', 'goldsmith' ),
                    'goldsmith-label-brown' => esc_html__( 'Brown', 'goldsmith' ),
                    'goldsmith-label-red' => esc_html__( 'Red', 'goldsmith' ),
                    'goldsmith-label-blue' => esc_html__( 'Blue', 'goldsmith' ),
                    'goldsmith-label-blue-dark' => esc_html__( 'Blue Dark', 'goldsmith' ),
                    'goldsmith-label-blue-soft' => esc_html__( 'Blue Soft', 'goldsmith' ),
                    'goldsmith-label-purple' => esc_html__( 'Purple', 'goldsmith' ),
                    'goldsmith-label-purple-soft' => esc_html__( 'Purple Soft', 'goldsmith' ),
                    'goldsmith-label-yellow' => esc_html__( 'Yellow', 'goldsmith' ),
                    'goldsmith-label-yellow-soft' => esc_html__( 'Yellow Soft', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'radius_type',
            [
                'label' => esc_html__( 'Radius Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'label-square',
                'options' => [
                    'label-radius' => esc_html__( 'Radius', 'goldsmith' ),
                    'label-square' => esc_html__( 'Square', 'goldsmith' ),
                ]
            ]
        );
        $this->add_control( 'size',
            [
                'label' => esc_html__( 'Size', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'label-small',
                'options' => [
                    'label-large' => esc_html__( 'Large', 'goldsmith' ),
                    'label-medium' => esc_html__( 'Medium', 'goldsmith' ),
                    'label-small' => esc_html__( 'Small', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'cat_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catname' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'cat_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catname' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cat_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catname'
            ]
        );
        $this->add_responsive_control( 'cat_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catname' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'cat_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catname' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'cat_border',
                'selector' => '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catname'
            ]
        );
        $this->add_responsive_control( 'cat_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catname' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']
            ]
        );
        $this->add_control( 'catcount_divider',
            [
                'label' => esc_html__( 'CATEGORY COUNT', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'catcount_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catcount' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'catcount_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catcount' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'catcount_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catcount'
            ]
        );
        $this->add_responsive_control( 'catcount_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catcount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'catcount_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catcount' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'catcount_border',
                'selector' => '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catcount'
            ]
        );
        $this->add_responsive_control( 'catcount_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-catcount' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-title' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-title'
            ]
        );
        $this->add_responsive_control( 'title_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'title_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-desc' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-desc'
            ]
        );
        $this->add_responsive_control( 'desc_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'desc_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-button' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-woo-banner-wrapper:hover .goldsmith-banner-content .goldsmith-banner-button' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-button' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_hvrbgcolor',
            [
                'label' => esc_html__( 'Hover Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-woo-banner-wrapper:hover .goldsmith-banner-content .goldsmith-banner-button' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-button'
            ]
        );
        $this->add_responsive_control( 'btn_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'btn_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-button'
            ]
        );
        $this->add_responsive_control( 'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-banner-content .goldsmith-banner-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {

        if ( ! class_exists('WooCommerce') ) {
            return;
        }

        $settings = $this->get_settings_for_display();
        $term     = get_term( $settings['category'], 'product_cat' );
        $name     = '' != $settings['category'] && !empty( $term ) ? $term->name : '';
        $count    = '' != $settings['category'] && !empty( $term ) ? $term->count : '';

        if ( !empty( $settings['link']['url'] ) ) {
            $target = !empty( $settings['link']['is_external'] ) ? ' target="_blank"' : '';
            $rel    = !empty( $settings['link']['nofollow'] ) ? ' rel="nofollow"' : '';
            $link   = $settings['link']['url'];
        } else {
            $link = '' != $settings['category'] ? get_category_link( $settings['category'] ) : '';
        }

        $full_height = $settings['full_height'] ? ' full-height' : '';

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'thumbnail';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size = [ $sizew, $sizeh ];
        }
        $html = $icon = '';

        $count_text = $settings['count_text'] ? ' '.$settings['count_text'] : '';

        if ( !empty( $settings['link']['url'] ) ) {
            $target = !empty( $settings['link']['is_external'] ) ? ' target="_blank"' : '';
            $rel    = !empty( $settings['link']['nofollow'] ) ? ' rel="nofollow"' : '';
            $html .= '<a class="goldsmith-banner-link" href="'.$settings['link']['url'].'"'.$target.$rel.'></a>';
        } else {
            $html .= '' != $settings['category'] ? '<a class="goldsmith-banner-link" href="'.get_category_link( $settings['category'] ).'"></a>' : '';
        }
        if ( $settings['use_video'] == 'yes' ) {
            $vid      = $settings['video_id'];
            $as_ratio = $settings['aspect_ratio'] ? $settings['aspect_ratio'] : '16:9';
            $provider = $settings['video_provider'];
            $start    = $settings['video_start'] ? '&start='.$settings['video_start'] : '';
            $end      = $settings['video_end'] ? '&end='.$settings['video_end'] : '';
            $vstart   = $settings['video_start'] ? $settings['video_start'].',' : '';
            $vend     = $settings['video_end'] ? $settings['video_end'] : '';
            $vtime    = $vstart || $vend ? '#t='.$vstart.$vend : '';
            $playlist = $settings['video_loop'] ? 'playlist='.$vid : '';
            $loop     = $settings['video_loop'] ? 1 : 0;
            $autocalc = $settings['auto_calculate'] == 'yes' ? ' goldsmith-video-calculate' : '';

            $html .= '<div class="goldsmith-woo-banner-iframe-wrapper goldsmith-video-'.$provider.$autocalc.'" data-goldsmith-bg-video="'.$vid.'">';
                if ( $provider == 'vimeo' && $vid ) {
                    $html .= '<iframe data-bg-aspect-ratio="'.$as_ratio.'" class="lazy" loading="lazy" src="https://player.vimeo.com/video/'.$vid.'?autoplay=1&loop='.$loop.'&title=0&byline=0&portrait=0&sidedock=0&controls=0&playsinline=1&muted=1" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                } elseif ( $provider == 'youtube' && $vid ) {
                    $html .= '<iframe data-bg-aspect-ratio="'.$as_ratio.'" class="lazy" loading="lazy" src="https://www.youtube.com/embed/'.$vid.'?'.$playlist.'&modestbranding=0&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop='.$loop.$start.$end.'" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                } elseif ( $provider == 'iframe' && $settings['iframe_embed'] ) {
                    $html .= do_shortcode( $settings['iframe_embed'] );
                } elseif ( $provider == 'local' && $settings['loacal_video_url'] ) {
                    $html .= '<video data-bg-aspect-ratio="'.$as_ratio.'" class="lazy" loading="lazy" controls="0" autoplay="true" loop="true" muted="true" playsinline="true" src="'.$settings['loacal_video_url'].$vtime.'"></video>';
                }
            $html .= '</div>';
        } else {
            if ( !empty( $settings['image']['id'] ) ) {
                $html .= '<div class="goldsmith-banner-image">';
                    $html .= wp_get_attachment_image( $settings['image']['id'], $size, false );
                $html .= '</div>';
            }
        }
        $html .= '<div class="goldsmith-banner-content">';
            $html .= '<div class="goldsmith-banner-content-top">';
                foreach (  $settings['content_orders'] as $item ) {
                    if ( !empty( $settings['icon']['value'] ) ) {
                        ob_start();
                        Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                        $icon = ob_get_clean();
                    }
                    if ( $name && $item['item_order'] == 'cat' && $item['item_position'] == 'top' ) {
                        $html .= '<span class="goldsmith-banner-catname banner-content-item goldsmith-widget-label '.$settings['bg_type'].' '.$settings['radius_type'].' '.$settings['size'].' '.$settings['color_type'].'">'.$name.'</span>';
                    }
                    if ( $name && $item['item_order'] == 'count' && $item['item_position'] == 'top' ) {
                        $html .= '<span class="goldsmith-banner-catcount banner-content-item">'.$count.$count_text.'</span>';
                    }
                    if ( $settings['title'] && $item['item_order'] == 'title' && $item['item_position'] == 'top' ) {
                        $html .= '<'.$settings['tag'].' class="goldsmith-banner-title banner-content-item">'.$settings['title'].'</'.$settings['tag'].'>';
                    }
                    if ( $settings['desc'] && $item['item_order'] == 'desc' && $item['item_position'] == 'top' ) {
                        $html .= '<span class="goldsmith-banner-desc banner-content-item">'.$settings['desc'].'</span>';
                    }
                    if ( $settings['btn_title'] && $item['item_order'] == 'button' && $item['item_position'] == 'top' ) {
                        $html .= '<span class="goldsmith-banner-button banner-content-item">'.$settings['btn_title'].' '.$icon.'</span>';
                    }
                }
            $html .= '</div>';
            $html .= '<div class="goldsmith-banner-content-center">';
                foreach (  $settings['content_orders'] as $item ) {
                    if ( !empty( $settings['icon']['value'] ) ) {
                        ob_start();
                        Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                        $icon = ob_get_clean();
                    }
                    if ( $name && $item['item_order'] == 'cat' && $item['item_position'] == 'center' ) {
                        $html .= '<span class="goldsmith-banner-catname banner-content-item goldsmith-widget-label '.$settings['bg_type'].' '.$settings['radius_type'].' '.$settings['size'].' '.$settings['color_type'].'">'.$name.'</span>';
                    }
                    if ( $name && $item['item_order'] == 'count' && $item['item_position'] == 'center' ) {
                        $html .= '<span class="goldsmith-banner-catcount banner-content-item">'.$count.$count_text.'</span>';
                    }
                    if ( $settings['title'] && $item['item_order'] == 'title' && $item['item_position'] == 'center' ) {
                        $html .= '<'.$settings['tag'].' class="goldsmith-banner-title banner-content-item">'.$settings['title'].'</'.$settings['tag'].'>';
                    }
                    if ( $settings['desc'] && $item['item_order'] == 'desc' && $item['item_position'] == 'center' ) {
                        $html .= '<span class="goldsmith-banner-desc banner-content-item">'.$settings['desc'].'</span>';
                    }
                    if ( $settings['btn_title'] && $item['item_order'] == 'button' && $item['item_position'] == 'center' ) {
                        $html .= '<span class="goldsmith-banner-button banner-content-item">'.$settings['btn_title'].' '.$icon.'</span>';
                    }
                }
            $html .= '</div>';
            $html .= '<div class="goldsmith-banner-content-bottom">';
                foreach (  $settings['content_orders'] as $item ) {
                    if ( !empty( $settings['icon']['value'] ) ) {
                        ob_start();
                        Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                        $icon = ob_get_clean();
                    }
                    if ( $name && $item['item_order'] == 'cat' && $item['item_position'] == 'bottom' ) {
                        $html .= '<span class="goldsmith-banner-catname banner-content-item goldsmith-widget-label '.$settings['bg_type'].' '.$settings['radius_type'].' '.$settings['size'].' '.$settings['color_type'].'">'.$name.'</span>';
                    }
                    if ( $name && $item['item_order'] == 'count' && $item['item_position'] == 'bottom' ) {
                        $html .= '<span class="goldsmith-banner-catcount banner-content-item">'.$count.$count_text.'</span>';
                    }
                    if ( $settings['title'] && $item['item_order'] == 'title' && $item['item_position'] == 'bottom' ) {
                        $html .= '<'.$settings['tag'].' class="goldsmith-banner-title banner-content-item">'.$settings['title'].'</'.$settings['tag'].'>';
                    }
                    if ( $settings['desc'] && $item['item_order'] == 'desc' && $item['item_position'] == 'bottom' ) {
                        $html .= '<span class="goldsmith-banner-desc banner-content-item">'.$settings['desc'].'</span>';
                    }
                    if ( $settings['btn_title'] && $item['item_order'] == 'button' && $item['item_position'] == 'bottom' ) {
                        $html .= '<span class="goldsmith-banner-button banner-content-item">'.$settings['btn_title'].' '.$icon.'</span>';
                    }
                }
            $html .= '</div>';
        $html .= '</div>';
        echo '<div class="goldsmith-woo-banner-wrapper banner-style-'.$settings['baner_style'].$full_height.'">'.$html.'</div>';
    }
}
