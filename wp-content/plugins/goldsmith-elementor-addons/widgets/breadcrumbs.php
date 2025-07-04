<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Breadcrumbs extends Widget_Base {
    public function get_name() {
        return 'goldsmith-breadcrumbs';
    }
    public function get_title() {
        return 'Breadcrumbs (N)';
    }
    public function get_icon() {
        return 'eicon-yoast';
    }
    public function get_categories() {
        return [ 'goldsmith' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'style_section',
            [
                'label' => esc_html__( 'Style', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .breadcrumb-content .goldsmith-breadcrumb li, {{WRAPPER}} .breadcrumb-content .goldsmith-breadcrumb li a'
            ]
        );
        $this->add_control( 'bread_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .breadcrumb-content .goldsmith-breadcrumb li, {{WRAPPER}} .breadcrumb-content .goldsmith-breadcrumb li a' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'bread_hvrcolor',
            [
                'label' => esc_html__( 'Hover Link Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .breadcrumb-content .goldsmith-breadcrumb li:not(.active) a:hover' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'bread_actcolor',
            [
                'label' => esc_html__( 'Current Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .breadcrumb-content .goldsmith-breadcrumb li.active span' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'bread_sepcolor',
            [
                'label' => esc_html__( 'Separator Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-breadcrumb .breadcrumb-item+.breadcrumb-item::before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'space_item',
            [
                'label' => esc_html__( 'Space Between Items', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                    'min' => 0,
                        'max' => 200,
                        'step' => 1
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .breadcrumb-item+.breadcrumb-item::before' => 'margin:0 {{SIZE}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'alignment',
            [
                'label' => esc_html__( 'Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .breadcrumb-content .goldsmith-breadcrumb' => 'text-align: {{VALUE}};'],
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
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();

        if ( !function_exists('goldsmith_breadcrumbs') ) {
            return;
        }
        echo '<div class="breadcrumb-content text-center">';
            echo goldsmith_breadcrumbs();
        echo '</div>';
    }
}
