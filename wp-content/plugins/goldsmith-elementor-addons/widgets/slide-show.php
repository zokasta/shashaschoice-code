<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Slide_Show extends Widget_Base {
    public function get_name() {
        return 'goldsmith-slide-show';
    }
    public function get_title() {
        return 'Animated Frame Slideshow (N)';
    }
    public function get_icon() {
        return 'eicon-carousel';
    }
    public function get_categories() {
        return [ 'goldsmith' ];
    }
    public function get_script_depends() {
        return [ 'anime','imagesloaded','slide-show' ];
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
        $repeater->add_control( 'image',
            [
                'label' => esc_html__( 'Image', 'goldsmith' ),
                'type' => Controls_Manager::MEDIA
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
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}}.goldsmith-slide' => 'align-items: {{VALUE}};'],
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
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}}.goldsmith-slide' => 'justify-content: {{VALUE}};'],
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
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}}.goldsmith-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'ioverlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-img:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ititle_color',
            [
                'label' => esc_html__( 'Title Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-title' => 'color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'idesc_color',
            [
                'label' => esc_html__( 'Description Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .goldsmith-slide-desc' => 'color:{{VALUE}};' ]
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-slideshow' => 'height:{{SIZE}}{{UNIT}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
            'name' => 'thumbnail',
            'separator' => 'before',
            ]
        );
        $this->add_control( 'tag',
            [
                'label' => esc_html__( 'Title Tag for SEO', 'goldsmith' ),
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
        $this->add_control( 'prev_title',
            [
                'label' => esc_html__( 'Button Prev Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Previous'
            ]
        );
        $this->add_control( 'next_title',
            [
                'label' => esc_html__( 'Button Next Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Next'
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
                'selectors' => ['{{WRAPPER}} .goldsmith-slide' => 'align-items: {{VALUE}};'],
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
                'selectors' => ['{{WRAPPER}} .goldsmith-slide' => 'justify-content: {{VALUE}};'],
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-slide .goldsmith-slide-img:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'shape_color',
            [
                'label' => esc_html__( 'Shape Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-slideshow .shape path' => 'fill:{{VALUE}}!important;' ]
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-slide-title' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-slide-title'
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-slide-desc' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-slide-desc'
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
        $this->add_control( 'nav_divider',
            [
                'label' => esc_html__( 'NAVIGATION', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'nav_top_offset',
            [
                'label' => esc_html__( 'Bottom Offset', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -300,
                'max' => 300,
                'step' => 1,
                'default' => 50,
                'selectors' => [ '{{WRAPPER}} .goldsmith-slidenav' => 'bottom:{{SIZE}}px;' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'nav_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-slidenav-item'
            ]
        );
        $this->add_control( 'nav_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-slidenav-item' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'nav_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-slidenav-item:hover' => 'color: {{VALUE}};']
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : [1000,1000];
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size  = [ $sizew, $sizeh ];
        }
        

        $count = 1;
        echo '<div class="goldsmith-slideshow">';
            echo '<div class="goldsmith-slides">';
                foreach ( $settings['items'] as $item ) {
                    if ( !empty( $item['image']['id'] ) ) {
                        $imgurl = wp_get_attachment_image_url( $item['image']['id'], $size );
                    }
                    $current = $count == 1 ? ' goldsmith-slide-current' : ''; 
                    echo '<div class="goldsmith-slide'.$current.' elementor-repeater-item-' . esc_attr( $item['_id'] ) . '">';
                        
                        if ( !empty( $item['image']['id'] ) ) {
                            echo '<div class="goldsmith-slide-img" style="background-image: url('.$imgurl.')"></div>';
                            if ( !empty( $item['title']) ) {
                                echo '<'.$settings['tag'].' class="goldsmith-slide-title">'.$item['title'].'</'.$settings['tag'].'>';
                            }
                            if ( !empty( $item['desc'] ) ) {
                                echo '<p class="goldsmith-slide-desc">'.$item['desc'].'</p>';
                            }
                            if ( !empty( $item['btn_title'] ) && !empty( $item['btn_title2'] ) ) {
                                echo '<div class="goldsmith-slide-link-wrapper">';
                            }
                            if ( !empty( $item['btn_title'] ) ) {
                                $target   = !empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                                $nofollow = !empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                                $btn_id   = !empty( $item['btn_id'] ) ? ' id="'.$item['btn_id'].'"' : '';
                                echo '<a class="goldsmith-btn-large goldsmith-btn goldsmith-bg-black goldsmith-slide-link" href="'.$item['link']['url'].'"'.$btn_id.$target.$nofollow.'>'.$item['btn_title'].'</a>';
                            }
                            if ( !empty( $item['btn_title2'] ) ) {
                                $target2   = !empty( $item['link2']['is_external'] ) ? ' target="_blank"' : '';
                                $nofollow2 = !empty( $item['link2']['nofollow'] ) ? ' rel="nofollow"' : '';
                                $btn_id2   = !empty( $item['btn_id2'] ) ? ' id="'.$item['btn_id2'].'"' : '';
                                echo '<a class="goldsmith-btn-large goldsmith-btn goldsmith-bg-black goldsmith-slide-link goldsmith-slide-link2" href="'.$item['link2']['url'].'"'.$btn_id2.$target2.$nofollow2.'>'.$item['btn_title2'].'</a>';
                            }
                            if ( !empty( $item['btn_title'] ) && !empty( $item['btn_title2'] ) ) {
                                echo '</div>';
                            }
                        }
                    echo '</div>';
                    $count++;
                }
            echo '</div>';

            echo '<div class="goldsmith-slidenav">';
                echo '<div class="goldsmith-slidenav-item goldsmith-slidenav-item-prev">'.$settings['prev_title'].'</div>';
                echo '<span>/</span>';
                echo '<div class="goldsmith-slidenav-item goldsmith-slidenav-item-next">'.$settings['next_title'].'</div>';
            echo '</div>';
            
        echo '</div>';
    }
}
