<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Testimonials extends Widget_Base {
    public function get_name() {
        return 'goldsmith-testimonials';
    }
    public function get_title() {
        return 'Testimonials Carousel (N)';
    }
    public function get_icon() {
        return 'eicon-testimonial';
    }
    public function get_categories() {
        return [ 'goldsmith' ];
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
        $this->start_controls_section( 'items_settings',
            [
                'label' => esc_html__('Testimonials Items', 'goldsmith'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'type',
            [
                'label' => esc_html__( 'Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__( 'Type 1', 'goldsmith' ),
                    '2' => esc_html__( 'Type 2', 'goldsmith' ),
                    '3' => esc_html__( 'Type 3', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'alignment',
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
        $repeater = new Repeater();
        $repeater->add_control( 'name',
            [
                'label' => esc_html__( 'Name', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Sam Peters',
                'label_block' => true
            ]
        );
        $repeater->add_control( 'pos',
            [
                'label' => esc_html__( 'Position', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'CEO Solar Systems LLC',
                'label_block' => true
            ]
        );
        $repeater->add_control( 'text',
            [
                'label' => esc_html__( 'Quote', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'label_block' => true
            ]
        );
        $repeater->add_control( 'image',
            [
                'label' => esc_html__( 'Avatar', 'goldsmith' ),
                'type' => Controls_Manager::MEDIA
            ]
        );
        $this->add_control( 'items',
            [
                'label' => esc_html__( 'Items', 'goldsmith' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{name}}',
                'default' => [
                    [
                        'name' => 'Jessica Brown',
                        'pos' => 'Customer',
                        'text' => 'This is due to their excellent service, competitive pricing and customer support. It’s throughly refresing to get such a personal touch. Duis aute lorem ipsum is simply free text irure dolor in reprehenderit in esse nulla pariatur'
                    ],
                    [
                        'name' => 'Caleb Hoffman',
                        'pos' => 'Customer',
                        'text' => 'This is due to their excellent service, competitive pricing and customer support. It’s throughly refresing to get such a personal touch. Duis aute lorem ipsum is simply free text irure dolor in reprehenderit in esse nulla pariatur'
                    ],
                    [
                        'name' => 'Bradley Kim',
                        'pos' => 'Customer',
                        'text' => 'This is due to their excellent service, competitive pricing and customer support. It’s throughly refresing to get such a personal touch. Duis aute lorem ipsum is simply free text irure dolor in reprehenderit in esse nulla pariatur'
                    ]
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
            'name' => 'thumbnail',
            ]
        );
        $this->add_responsive_control( 'image_border_radius',
            [
                'label' => esc_html__( 'Image Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-testimonials img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->add_control( 'ntag',
            [
                'label' => esc_html__( 'Name Tag', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h5',
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
        $this->add_control( 'slider_options_divider',
            [
                'label' => esc_html__( 'SLIDER OPTIONS', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
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
        $this->add_control('column_gap',
            [
                'label' => __( 'Space Between Items', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-track' => 'margin-left: calc(-{{SIZE}}px / 2 );margin-right: calc(-{{SIZE}}px / 2);',
                    '{{WRAPPER}} .goldsmith-testimonial-item' => 'padding-left: calc({{SIZE}}px / 2);padding-right: calc({{SIZE}}px / 2);'
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('style_section',
            [
                'label'=> esc_html__( 'STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_STYLE
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-testimonial-item' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'box_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .testimonial-item'
            ]
        );
        $this->add_responsive_control( 'box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-testimonial-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'name_sdivider',
            [
                'label' => esc_html__( 'NAME', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'name_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-testimonial-info .name' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-testimonial-info .name'
            ]
        );
        $this->add_control( 'pos_sdivider',
            [
                'label' => esc_html__( 'POSITION / JOB', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'pos_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-testimonial-info span' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pos_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-testimonial-info span'
            ]
        );
        $this->add_control( 'text_sdivider',
            [
                'label' => esc_html__( 'TEXT', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'text_spacing',
            [
                'label' => esc_html__( 'Text Content Spacing', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-testimonial-1 .goldsmith-testimonial-content' => 'margin-top:{{SIZE}};',
                    '{{WRAPPER}} .goldsmith-testimonial-2 .goldsmith-testimonial-content' => 'margin-bottom:{{SIZE}};',
                    '{{WRAPPER}} .goldsmith-testimonial-3 .goldsmith-testimonial-content' => 'margin-top:{{SIZE}};'
                ]
            ]
        );
        $this->add_control( 'text_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-testimonial-content p' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-testimonial-content p'
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
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
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
                'label' => esc_html__( 'Space', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
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
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : [100,100];
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size  = [ $sizew, $sizeh ];
        }
        $rtl        = is_rtl() ? 'true' : 'false';
        $isrtl      = is_rtl() ? ' is-rtl' : '';
        $loop       = 'yes' == $settings['loop'] ? 'true': 'false';
        $dots       = 'yes' == $settings['dots'] ? 'true': 'false';
        $autoplay   = 'yes' == $settings['autoplay'] ? 'true': 'false';
        $centermode = 'yes' == $settings['centermode'] ? 'true': 'false';
        $editmode   = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

        echo '<div class="goldsmith-testimonials goldsmith-testimonial-'.$settings['type'].' goldsmith-slick goldsmith-slick-slider'.$editmode.$isrtl.' align-'.$settings['alignment'].'" data-slick=\'{"rtl":'.$rtl.',"autoplay":'.$autoplay.',"dots": '.$dots.',"arrows": false,"centerMode":'.$centermode.',"infinite": '.$loop.',"speed": '.$settings['speed'].',"slidesToShow": '.$settings['mditems'].',"slidesToScroll": 1,"adaptiveHeight": false,"responsive": [{"breakpoint": 1025,"settings": {"slidesToShow": '.$settings['mditems'].',"slidesToScroll": 1}},{"breakpoint": 790,"settings": {"slidesToShow": '.$settings['smitems'].',"slidesToScroll": 1}},{"breakpoint": 576,"settings": {"slidesToShow": '.$settings['xsitems'].',"slidesToScroll": 1}}]}\'>';
            foreach ( $settings['items'] as $item ) {
                if ( '1' == $settings['type'] ) {
                    echo '<div class="goldsmith-testimonial-item">';
                        echo '<div class="goldsmith-testimonial-info">';
                            if ( !empty( $item['image']['id'] ) ) {
                                echo '<div class="goldsmith-testimonial-avatar">';
                                    echo wp_get_attachment_image( $item['image']['id'], $size, false, ['class'=>'t-img'] );
                                echo '</div>';
                            }
                            if ( !empty( $item['name'] ) ) {
                                echo '<div class="goldsmith-testimonial-info">';
                                    if ( !empty( $item['name'] ) ) {
                                        if ( is_rtl() ) {
                                            $position = !empty( $item['pos'] ) ? ' <span class="goldsmith-small-title position">'.$item['pos'].' \ </span> ' : '';
                                            echo '<'.$settings['ntag'].' class="name">'.$position.$item['name'].'</'.$settings['ntag'].'>';
                                        } else {
                                            $position = !empty( $item['pos'] ) ? '<span class="goldsmith-small-title position"> / '.$item['pos'].'</span>' : '';
                                            echo '<'.$settings['ntag'].' class="name">'.$item['name'].$position.'</'.$settings['ntag'].'>';
                                        }
                                    }
                                echo '</div>';
                            }
                        echo '</div>';
                        if ( !empty( $item['text'] ) ) {
                            echo '<div class="goldsmith-testimonial-content">';
                                echo '<p>'.$item['text'].'</p>';
                            echo '</div>';
                        }
                    echo '</div>';
                }

                if ( '2' == $settings['type'] ) {
                    echo '<div class="goldsmith-testimonial-item">';
                        if ( !empty( $item['text'] ) ) {
                            echo '<div class="goldsmith-testimonial-content">';
                                echo '<p>'.$item['text'].'</p>';
                            echo '</div>';
                        }
                        echo '<div class="goldsmith-testimonial-info goldsmith-flex goldsmith-align-center goldsmith-flex-center">';
                            if ( !empty( $item['image']['id'] ) ) {
                                echo '<div class="goldsmith-testimonial-avatar">';
                                    echo wp_get_attachment_image( $item['image']['id'], $size, false, ['class'=>'t-img'] );
                                echo '</div>';
                            }
                            if ( !empty( $item['name'] ) || !empty( $item['pos'] ) ) {
                                echo '<div class="goldsmith-testimonial-text">';
                                    if ( !empty( $item['name'] ) ) {
                                        echo '<'.$settings['ntag'].' class="name mb-0">'.$item['name'].'</'.$settings['ntag'].'>';
                                    }
                                    if ( !empty( $item['pos'] ) ) {
                                        echo '<span class="goldsmith-small-title position">'.$item['pos'].'</span>';
                                    }
                                echo '</div>';
                            }
                        echo '</div>';
                    echo '</div>';
                }

                if ( '3' == $settings['type'] ) {
                    echo '<div class="goldsmith-testimonial-item">';
                        echo '<div class="goldsmith-testimonial-info goldsmith-flex goldsmith-align-center goldsmith-flex-center">';
                            if ( !empty( $item['image']['id'] ) ) {
                                echo '<div class="goldsmith-testimonial-avatar">';
                                    echo wp_get_attachment_image( $item['image']['id'], $size, false, ['class'=>'t-img'] );
                                echo '</div>';
                            }
                            if ( !empty( $item['name'] ) || !empty( $item['pos'] ) ) {
                                echo '<div class="goldsmith-testimonial-text">';
                                    if ( !empty( $item['name'] ) ) {
                                        echo '<'.$settings['ntag'].' class="name mb-0">'.$item['name'].'</'.$settings['ntag'].'>';
                                    }
                                    if ( !empty( $item['pos'] ) ) {
                                        echo '<span class="goldsmith-small-title position">'.$item['pos'].'</span>';
                                    }
                                echo '</div>';
                            }
                        echo '</div>';
                        if ( !empty( $item['text'] ) ) {
                            echo '<div class="goldsmith-testimonial-content">';
                                echo '<p>'.$item['text'].'</p>';
                            echo '</div>';
                        }
                    echo '</div>';
                }
            }
        echo '</div>';
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
            <script>
            jQuery( document ).ready( function($) {
                $('.goldsmith-slick-slider-<?php echo $id ?>').not('.slick-initialized').slick();
            });
            </script>
            <?php
        }
    }
}
