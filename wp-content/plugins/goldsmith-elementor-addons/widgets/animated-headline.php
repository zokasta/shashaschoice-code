<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Animated_Headline extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-animated-headline';
    }
    public function get_title() {
        return 'Animated Headline (N)';
    }
    public function get_icon() {
        return 'eicon-animated-headline';
    }
    public function get_categories() {
        return [ 'goldsmith' ];
    }
    public function get_style_depends() {
        return [ 'animated-headline' ];
    }
    public function get_script_depends() {
        return [ 'animated-headline' ];
    }
    // Registering Controls
    protected function register_controls() {
        $this->start_controls_section('animated_headline_settings',
            [
                'label' => esc_html__( 'Typed Title Settings', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'tag',
            [
                'label' => esc_html__( 'Tag', 'goldsmith' ),
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
                    'p' => esc_html__( 'p', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'type',
            [
                'label' => esc_html__( 'Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'rotate-1',
                'options' => [
                    'rotate-1' => esc_html__( 'rotate-1', 'goldsmith' ),
                    'letters type' => esc_html__( 'letters type', 'goldsmith' ),
                    'letters rotate-2' => esc_html__( 'letters rotate-2', 'goldsmith' ),
                    'loading-bar' => esc_html__( 'loading-bar', 'goldsmith' ),
                    'slide' => esc_html__( 'slide', 'goldsmith' ),
                    'clip is-full-width' => esc_html__( 'clip is-full-width', 'goldsmith' ),
                    'zoom' => esc_html__( 'zoom', 'goldsmith' ),
                    'letters rotate-3' => esc_html__( 'letters rotate-3', 'goldsmith' ),
                    'letters scale' => esc_html__( 'letters scale', 'goldsmith' ),
                    'push' => esc_html__( 'push', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'headline_before',
            [
                'label' => esc_html__( 'Text Before', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'We are',
                'label_block' => true
            ]
        );
        $this->add_control( 'headline_after',
            [
                'label' => esc_html__( 'Text After', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true
            ]
        );
        $repeater = new Repeater();
        $repeater->add_control( 'typed',
            [
                'label' => esc_html__( 'Typed Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true
            ]
        );
        $repeater->add_control( 'color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}}.typed-item' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'texts',
            [
                'label' => esc_html__( 'Items', 'goldsmith' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{typed}}',
                'default' => [
                    [
                        'typed' => 'Best'
                    ],
                    [
                        'typed' => 'Awesome'
                    ],
                    [
                        'typed' => 'Important'
                    ]
                ]
            ]
        );
        $this->end_controls_section();
        /*****   Style   ******/
        $this->start_controls_section( 'style_section',
            [
                'label' => esc_html__( 'STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'general_heading',
            [
                'label' => esc_html__( 'GENERAL', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'headline_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .animated_headline_wrapper,
                {{WRAPPER}} .animated_headline_wrapper b,
                {{WRAPPER}} .animated_headline_wrapper .typed-cursor,
                {{WRAPPER}} .animated_headline_wrapper .typed_before,
                {{WRAPPER}} .animated_headline_wrapper .typed_after'
            ]
        );
        $this->add_control( 'headline_color',
            [
                'label' => esc_html__( 'Before Text Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .animated_headline_wrapper' => 'color:{{VALUE}};',
                    '{{WRAPPER}} .animated_headline_wrapper b' => 'color:{{VALUE}};',
                    '{{WRAPPER}} .animated_headline_wrapper .typed-cursor' => 'color:{{VALUE}};',
                    '{{WRAPPER}} .animated_headline_wrapper .typed_before' => 'color:{{VALUE}};',
                    '{{WRAPPER}} .animated_headline_wrapper .typed_after' => 'color:{{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control( 'alignment',
            [
                'label' => esc_html__( 'Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .animated_headline_wrapper .headline' => 'justify-content: {{VALUE}};'],
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
                'default' => 'flex-start'
            ]
        );
        $this->add_responsive_control( 'alignment2',
            [
                'label' => esc_html__( 'Text Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => [
                '{{WRAPPER}} .animated_headline_wrapper .words-wrapper' => 'text-align: {{VALUE}};',
                '{{WRAPPER}} .animated_headline_wrapper .words-wrapper b' => 'width:100%;'
                ],
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
        $this->add_control( 'typed_heading',
            [
                'label' => esc_html__( 'ANIMATED TEXT', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typed_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .animated_headline_wrapper b'
            ]
        );
        $this->add_control( 'typed_cursor_heading',
            [
                'label' => esc_html__( 'CURSOR', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'headline_cursor_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .animated_headline_wrapper .typed-cursor' => 'color:{{VALUE}};',
                    '{{WRAPPER}} .headline.loading-bar .words-wrapper::after' => 'background-color:{{VALUE}};',
                    '{{WRAPPER}} .headline.clip .words-wrapper::after' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $this->add_responsive_control( 'cursor_size',
            [
                'label' => esc_html__( 'Size', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 300
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .animated_headline_wrapper .typed-cursor' => 'font-size: {{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'typed_before_after_heading',
            [
                'label' => esc_html__( 'BEFORE & AFTER TEXT', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'before_color',
            [
                'label' => esc_html__( 'Before Text Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .animated_headline_wrapper .typed_before' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'after_color',
            [
                'label' => esc_html__( 'After Text Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .animated_headline_wrapper .typed_after' => 'color:{{VALUE}};' ]
            ]
        );
        $this->end_controls_section();
        /*****   Style   ******/
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo '<div class="animated_headline_wrapper">';
            echo '<'.$settings['tag'].' class="headline '.$settings['type'].'">';
            	echo $settings['headline_before'] ? '<span class="typed_before">'.$settings['headline_before'].'</span>&nbsp;' : '';
            	echo '<span class="words-wrapper">';
            	    $count = 0;
                    foreach ($settings['texts'] as $item) {
                        $visible = 0 == $count ? ' is-visible' : '';
                        echo '<b class="typed-item elementor-repeater-item-' . $item['_id'] .$visible. '">'.$item['typed'].'</b>';
                        $count++;
                    }
            	echo '</span> ';
                echo $settings['headline_after'] ? '&nbsp;<span class="typed_after">'.$settings['headline_after'].'</span>' : '';
            echo '</'.$settings['tag'].'>';
        echo '</div>';
    }
}
