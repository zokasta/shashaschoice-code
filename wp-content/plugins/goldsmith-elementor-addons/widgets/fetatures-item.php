<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Features_Item extends Widget_Base {
    public function get_name() {
        return 'goldsmith-features-item';
    }
    public function get_title() {
        return 'Features Item (N)';
    }
    public function get_icon() {
        return 'eicon-icon-box';
    }
    public function get_categories() {
        return [ 'goldsmith' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'general_section',
            [
                'label'=> esc_html__( 'Text', 'goldsmith' ),
                'tab'=> Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'icon_type',
            [
                'label' => esc_html__( 'Icon Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'img',
                'options' => [
                    'img' => esc_html__( 'Image', 'goldsmith' ),
                    'icon' => esc_html__( 'Icon', 'goldsmith' ),
                ],
            ]
        );
        $this->add_control( 'image',
            [
                'label' => esc_html__( 'Image', 'agrikon' ),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => ''],
                'condition' => ['icon_type' => 'img']
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
                'condition' => ['icon_type' => 'img']
            ]
        );
        $this->add_control( 'icon',
            [
                'label' => esc_html__( 'Icon', 'goldsmith' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'solid'
                ],
                'condition' => ['icon_type' => 'icon']
            ]
        );
        $this->add_control( 'title',
            [
                'label' => esc_html__( 'Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Free Shipping On Over $ 50',
                'label_block' => true,
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
                    'p' => esc_html__( 'p', 'goldsmith' ),
                ],
            ]
        );
        $this->add_control( 'desc',
            [
                'label' => esc_html__( 'Short Description', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Agricultural mean crops livestock',
                'label_block' => true,
            ]
        );
        $this->add_control( 'link',
            [
                'label' => esc_html__( 'Add Link', 'goldsmith' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '',
                    'is_external' => ''
                ],
                'show_external' => true,
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'style_section',
            [
                'label' => esc_html__( 'STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'box_divider',
            [
                'label' => esc_html__( 'BOX', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $this->add_responsive_control( 'text_alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'goldsmith' ),
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
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-item' => 'text-align:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'box_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-item' => 'background-color:{{VALUE}};']
            ]
        );
        $this->add_control( 'box_hvrbgcolor',
            [
                'label' => esc_html__( 'Hover Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-item:hover' => 'background-color:{{VALUE}};']
            ]
        );
        $this->add_responsive_control( 'box_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-features-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-features-item'
            ]
        );
        $this->add_control( 'box_hvrcolor',
            [
                'label' => esc_html__( 'Hover Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-item:hover' => 'border-color:{{VALUE}};']
            ]
        );
        $this->add_responsive_control( 'box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-features-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->add_control( 'icon_divider',
            [
                'label' => esc_html__( 'ICON', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'icon_style',
            [
                'label' => esc_html__( 'Icon Style', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'img',
                'options' => [
                    'border' => esc_html__( 'Border', 'goldsmith' ),
                    'simple' => esc_html__( 'Simple', 'goldsmith' ),
                ],
            ]
        );
        $this->add_responsive_control( 'icon_svg_imgsize',
            [
                'label' => esc_html__( 'Image Icon Max Width', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-item .goldsmith-features-icon img' => 'max-width:{{SIZE}}px;' ],
                'condition' => ['icon_type' => 'img']
            ]
        );
        $this->add_responsive_control( 'icon_size',
            [
                'label' => esc_html__( 'Font Icon Size', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-item .goldsmith-features-icon' => 'font-size:{{SIZE}}px;' ],
                'condition' => ['icon_type' => 'icon']
            ]
        );
        $this->add_responsive_control( 'icon_svg_maxwidth',
            [
                'label' => esc_html__( 'SVG Icon Max Width', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 2000,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-item .goldsmith-features-icon svg' => 'width:{{SIZE}}px;max-width:{{SIZE}}px;' ],
                'condition' => ['icon_type' => 'icon']
            ]
        );
        $this->add_responsive_control( 'icon_svg_maxheight',
            [
                'label' => esc_html__( 'SVG Icon Max Height', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 2000,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-item .goldsmith-features-icon svg' => 'height:{{SIZE}}px;max-height:{{SIZE}}px;' ],
                'condition' => ['icon_type' => 'icon']
            ]
        );
        $this->add_responsive_control( 'icon_minh',
            [
                'label' => esc_html__( 'Icon Wrapper Min Height', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-item .goldsmith-features-icon' => 'min-height:{{SIZE}}px;' ],
            ]
        );
        $this->add_control( 'icon_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ 
                    '{{WRAPPER}} .goldsmith-features-item .goldsmith-features-icon' => 'color:{{VALUE}};',
                    '{{WRAPPER}} .goldsmith-features-item .goldsmith-features-icon svg' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $this->add_control( 'icon_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ 
                    '{{WRAPPER}} .goldsmith-features-item:hover .goldsmith-features-icon' => 'color:{{VALUE}};',
                    '{{WRAPPER}} .goldsmith-features-item:hover .goldsmith-features-icon svg' => 'fill:{{VALUE}};',
                ]
            ]
        );
        $this->add_responsive_control( 'icon_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-features-item .goldsmith-features-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_responsive_control( 'icon_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-features-item .goldsmith-features-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-features-item .goldsmith-features-icon'
            ]
        );
        $this->add_responsive_control( 'icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-features-item .goldsmith-features-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->add_control( 'title_divider',
            [
                'label' => esc_html__( 'TITLE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-content .features-title' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'title_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-item:hover .goldsmith-features-content .features-title' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-features-content .features-title'
            ]
        );
        $this->add_responsive_control( 'title_margin',
            [
                'label' => esc_html__( 'Margin', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-features-content .features-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'desc_divider',
            [
                'label' => esc_html__( 'DESCRIPTION', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'desc_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-content .features-desc' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'desc_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-features-item:hover .features-desc' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-features-content .features-desc'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        $settings  = $this->get_settings_for_display();

        echo '<div class="goldsmith-features-item">';
            if ( $settings['link']['url'] ) {
                $target = $settings['link']['is_external'] ? ' target="_blank"' : '';
                $rel = $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
                echo '<a class="features-link" href="'.$settings['link']['url'].'"'.$target.$rel.'></a>';
            }
            if ( 'img' == $settings['icon_type'] ) {
                $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'thumbnail';
                if ( 'custom' == $size ) {
                    $sizew = $settings['thumbnail_custom_dimension']['width'];
                    $sizeh = $settings['thumbnail_custom_dimension']['height'];
                    $size = [ $sizew, $sizeh ];
                }
                echo '<div class="goldsmith-features-icon icon-'.$settings['icon_style'].'">';
                    echo wp_get_attachment_image( $settings['image']['id'], $size, false, ['class'=>'f-icon'] );
                echo '</div>';
            }
            if ( !empty( $settings['icon']['value'] ) && 'img' != $settings['icon_type'] ) {
                echo '<div class="goldsmith-features-icon icon-'.$settings['icon_style'].'">';Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );echo '</div>';
            }
            echo '<div class="goldsmith-features-content">';
                if ( $settings['title'] ) {
                    echo '<'.$settings['tag'].' class="features-title">'.$settings['title'].'</'.$settings['tag'].'>';
                }
                if ( $settings['desc'] ) {
                    echo '<span class="features-desc">'.$settings['desc'].'</span>';
                }
            echo '</div>';
        echo '</div>';

    }
}
