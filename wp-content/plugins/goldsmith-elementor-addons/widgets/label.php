<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Label extends Widget_Base {
    public function get_name() {
        return 'goldsmith-widget-label';
    }
    public function get_title() {
        return 'Label (N)';
    }
    public function get_icon() {
        return 'eicon-animated-headline';
    }
    public function get_categories() {
        return [ 'goldsmith' ];
    }
    // Registering Controls
    protected function register_controls() {

        /*****   label Options   ******/
        $this->start_controls_section('label_settings',
            [
                'label' => esc_html__( 'Label', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'text',
            [
                'label' => esc_html__( 'Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__( 'This is label', 'goldsmith' )
            ]
        );
        $this->add_control( 'bg_type',
            [
                'label' => esc_html__( 'Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'label-solid',
                'options' => [
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
        $this->add_responsive_control( 'alignment',
            [
                'label' => esc_html__( 'Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}}' => 'text-align: {{VALUE}};'],
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
        $this->end_controls_section();
        /*****   End label Options   ******/

        /***** label Style ******/
        $this->start_controls_section('label_styling',
            [
                'label' => esc_html__( 'STYLE', 'goldsmith' ),
            ]
        );
        $this->add_control( 'label_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-widget-label' => 'color: {{VALUE}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-widget-label'
            ]
        );
        $this->add_responsive_control( 'label_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-widget-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'label_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-widget-label',
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control( 'label_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-widget-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'label_background',
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .goldsmith-widget-label',
                'separator' => 'before'
            ]
        );
        $this->end_controls_section();
        /***** End label Styling *****/
    }

    protected function render() {
        $settings   = $this->get_settings_for_display();
        $settingsid = $this->get_id();

        echo '<span class="goldsmith-widget-label '.$settings['bg_type'].' '.$settings['radius_type'].' '.$settings['size'].' '.$settings['color_type'].'">'.$settings['text'].'</span>';

    }
}
