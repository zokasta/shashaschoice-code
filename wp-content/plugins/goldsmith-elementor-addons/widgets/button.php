<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Button extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-button';
    }
    public function get_title() {
        return 'Button (N)';
    }
    public function get_icon() {
        return 'eicon-button';
    }
    public function get_categories() {
        return [ 'goldsmith' ];
    }

    // Registering Controls
    protected function register_controls() {
        $is_left = is_rtl() ? 'left' : 'right';
        $is_right = is_rtl() ? 'right' : 'left';
        /*****   Button Options   ******/
        $this->start_controls_section('btn_settings',
            [
                'label' => esc_html__( 'Button', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'btn_action',
            [
                'label' => esc_html__( 'Action Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'link',
                'options' => [
                    'link' => esc_html__( 'Link', 'goldsmith' ),
                    'image' => esc_html__( 'Single Image', 'goldsmith' ),
                    'youtube' => esc_html__( 'Youtube', 'goldsmith' ),
                    'vimeo' => esc_html__( 'Vimeo', 'goldsmith' ),
                    'map' => esc_html__( 'Google Map', 'goldsmith' ),
                    'html5' => esc_html__( 'HTML5 Video', 'goldsmith' ),
                    'modal' => esc_html__( 'Modal Content', 'goldsmith' ),
                    'gallery' => esc_html__( 'Gallery Images', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'link_type',
            [
                'label' => esc_html__( 'Link Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'external',
                'options' => [
                    'external' => esc_html__( 'External', 'goldsmith' ),
                    'internal' => esc_html__( 'Internal', 'goldsmith' ),
                ],
                'condition' => ['btn_action' => 'link']
            ]
        );
        $this->add_control( 'text',
            [
                'label' => esc_html__( 'Button Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__( 'Button Text', 'goldsmith' )
            ]
        );
        $this->add_control( 'btn_id',
            [
                'label' => esc_html__( 'Button ID', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $this->add_control( 'link',
            [
                'label' => esc_html__( 'Button Link', 'goldsmith' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => ''
                ],
                'show_external' => true,
                'condition' => ['btn_action' => 'link']
            ]
        );
        $this->add_control( 'images',
            [
                'label' => esc_html__( 'Images', 'goldsmith' ),
                'type' => Controls_Manager::GALLERY,
                'condition' => ['btn_action' => 'gallery']
            ]
        );
        $this->add_control( 'image',
            [
                'label' => esc_html__( 'Image', 'goldsmith' ),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => Utils::get_placeholder_image_src()],
                'condition' => ['btn_action' => 'image']
            ]
        );
        $this->add_control( 'ltitle',
            [
                'label' => esc_html__( 'Lightbox Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => 'Phone Name',
                'condition' => ['btn_action' => 'image']
            ]
        );
        $this->add_control( 'youtube',
            [
                'label' => esc_html__( 'Youtube Video URL', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => 'http://www.youtube.com/watch?v=AeeE6PyU-dQ',
                'condition' => ['btn_action' => 'youtube']
            ]
        );
        $this->add_control( 'vimeo',
            [
                'label' => esc_html__( 'Vimeo Video URL', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => 'https://vimeo.com/39493181',
                'condition' => ['btn_action' => 'vimeo']
            ]
        );
        $this->add_control( 'map',
            [
                'label' => esc_html__( 'Iframe Map URL', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => 'https://maps.google.com/maps?q=221B+Baker+Street,+London,+United+Kingdom&amp;hl=en&amp;t=v&amp;hnear=221B+Baker+St,+London+NW1+6XE,+United+Kingdom',
                'condition' => ['btn_action' => 'map']
            ]
        );
        $this->add_control( 'html5',
            [
                'label' => esc_html__( 'HTML5 Video URL', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => '',
                'pleaceholder' => esc_html__( 'Add your local video here', 'goldsmith' ),
                'condition' => ['btn_action' => 'html5']
            ]
        );
        $this->add_control( 'modal_content',
            [
                'label' => esc_html__( 'Modal Content', 'goldsmith' ),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default' => '<h3>Modal</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla rhoncus pharetra dui, nec tempus tellus maximus et. Sed sed elementum ligula, id cursus leo. Duis imperdiet tortor id condimentum hendrerit.</p>',
                'pleaceholder' => esc_html__( 'Add html content here', 'goldsmith' ),
                'condition' => ['btn_action' => 'modal']
            ]
        );
        $this->add_control( 'modal_width',
            [
                'label' => esc_html__( 'Modal Content Width', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 2000
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 600,
                ],
                'condition' => ['btn_action' => 'modal']
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
        $this->add_control( 'use_icon',
            [
                'label' => esc_html__( 'Use Icon', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );
        $this->add_control( 'icon',
            [
                'label' => esc_html__( 'Button Icon', 'goldsmith' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'solid'
                ],
                'condition' => ['use_icon' => 'yes']
            ]
        );
        $this->add_control( 'icon_pos',
            [
                'label' => esc_html__( 'Icon Position', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'btn-icon-right',
                'options' => [
                    'btn-icon-'.$is_right => esc_html__( 'Before', 'goldsmith' ),
                    'btn-icon-'.$is_left => esc_html__( 'After', 'goldsmith' ),
                    'btn-icon-top' => esc_html__( 'Top', 'goldsmith' ),
                    'btn-icon-bottom' => esc_html__( 'Bottom', 'goldsmith' )
                ],
                'condition' => ['use_icon' => 'yes']
            ]
        );
        $this->add_control( 'icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 300
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .btn-icon-left .goldsmith-button-icon' => 'margin-right: {{SIZE}}px;',
                    '{{WRAPPER}} .btn-icon-right .goldsmith-button-icon' => 'margin-left: {{SIZE}}px;',
                    '{{WRAPPER}} .btn-icon-top .goldsmith-button-icon' => 'margin-bottom: {{SIZE}}px;',
                    '{{WRAPPER}} .btn-icon-bottom .goldsmith-button-icon' => 'margin-top: {{SIZE}}px;'
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 6
                ],
                'condition' => ['use_icon' => 'yes']
            ]
        );
        $this->add_control( 'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 300
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-button-icon i' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .goldsmith-button-icon svg' => 'width: {{SIZE}}px;height:auto;'
                ],
                'condition' => ['use_icon' => 'yes']
            ]
        );
        $this->add_control( 'full',
            [
                'label' => esc_html__( 'Full width', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'separator' => 'before'
            ]
        );
        $this->add_control( 'bg_type',
            [
                'label' => esc_html__( 'Background Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'goldsmith-btn-border',
                'options' => [
                    'goldsmith-btn-border' => esc_html__( 'Bordered', 'goldsmith' ),
                    'goldsmith-btn-solid' => esc_html__( 'Solid', 'goldsmith' ),
                    'goldsmith-btn-text' => esc_html__( 'Simple Text', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'color_type',
            [
                'label' => esc_html__( 'Color Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'goldsmith-btn-dark',
                'options' => [
                    'goldsmith-btn-primary' => esc_html__( 'Primary', 'goldsmith' ),
                    'goldsmith-btn-white' => esc_html__( 'White', 'goldsmith' ),
                    'goldsmith-btn-dark' => esc_html__( 'Black', 'goldsmith' ),
                    'goldsmith-btn-brown' => esc_html__( 'Brown', 'goldsmith' ),
                    'goldsmith-btn-red' => esc_html__( 'Red', 'goldsmith' ),
                    'goldsmith-btn-gray' => esc_html__( 'Gray', 'goldsmith' ),
                    'goldsmith-btn-gray-soft' => esc_html__( 'Gray Soft', 'goldsmith' ),
                    'goldsmith-btn-green' => esc_html__( 'Green', 'goldsmith' ),
                    'goldsmith-btn-green-soft' => esc_html__( 'Green Soft', 'goldsmith' ),
                    'goldsmith-btn-blue' => esc_html__( 'Blue', 'goldsmith' ),
                    'goldsmith-btn-blue-dark' => esc_html__( 'Blue Dark', 'goldsmith' ),
                    'goldsmith-btn-blue-soft' => esc_html__( 'Blue Soft', 'goldsmith' ),
                    'goldsmith-btn-purple' => esc_html__( 'Purple', 'goldsmith' ),
                    'goldsmith-btn-purple-soft' => esc_html__( 'Purple Soft', 'goldsmith' ),
                    'goldsmith-btn-yellow' => esc_html__( 'Yellow', 'goldsmith' ),
                    'goldsmith-btn-yellow-soft' => esc_html__( 'Yellow Soft', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'radius_type',
            [
                'label' => esc_html__( 'Radius Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'goldsmith-btn-square',
                'options' => [
                    'goldsmith-btn-radius' => esc_html__( 'Radius', 'goldsmith' ),
                    'goldsmith-btn-square' => esc_html__( 'Square', 'goldsmith' ),
                ],
                'condition' => ['bg_type!' => 'goldsmith-btn-text']
            ]
        );
        $this->add_control( 'size',
            [
                'label' => esc_html__( 'Size', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'goldsmith-btn-medium',
                'options' => [
                    'goldsmith-btn-large' => esc_html__( 'Large', 'goldsmith' ),
                    'goldsmith-btn-medium' => esc_html__( 'Medium', 'goldsmith' ),
                    'goldsmith-btn-small' => esc_html__( 'Small', 'goldsmith' )
                ],
                'condition' => ['bg_type!' => 'goldsmith-btn-text']
            ]
        );
        $this->add_responsive_control( 'btn_minw',
            [
                'label' => esc_html__( 'Min Width ( px )', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
                'range' => [
                    'px' => [
                        'max' => 2000,
                        'min' => 0
                    ],
                    '%' => [
                        'max' => 100,
                        'min' => 0
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-button-inner' => 'min-width: {{SIZE}}{{UNIT}};'
                ],
                'condition' => ['bg_type!' => 'goldsmith-btn-text']
            ]
        );
        $this->add_responsive_control( 'btn_minh',
            [
                'label' => esc_html__( 'Min Height ( px )', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 1500]
                ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-button-inner' => 'min-height: {{SIZE}}px;',
                ],
                'condition' => ['bg_type!' => 'goldsmith-btn-text']
            ]
        );
        $this->add_control( 'btn_radius',
            [
                'label' => esc_html__( 'Border Radius ( px )', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 2000]
                ],
                'selectors' => ['{{WRAPPER}} .goldsmith-btn' => 'border-radius: {{SIZE}}px;'],
                'condition' => ['bg_type!' => 'goldsmith-btn-text']
            ]
        );
        $this->end_controls_section();
        /*****   End Button Options   ******/

        /***** Tooltips Style ******/
        $this->start_controls_section('btn_tooltips_styling',
            [
                'label' => esc_html__( 'Tooltips', 'goldsmith' )
            ]
        );
        $this->add_control( 'tooltips',
            [
                'label' => esc_html__( 'Tooltips', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'separator' => 'before'
            ]
        );
        $this->add_control( 'tooltiptext',
            [
                'label' => esc_html__( 'Tooltip Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => esc_html__( 'Button Text', 'goldsmith' ),
                'condition' => ['tooltips' => 'yes']
            ]
        );
        $this->add_control( 'tooltip_pos',
            [
                'label' => esc_html__( 'Tooltip Position', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => esc_html__( 'Top', 'goldsmith' ),
                    'right' => esc_html__( 'Right', 'goldsmith' ),
                    'bottom' => esc_html__( 'Bottom', 'goldsmith' ),
                    'left' => esc_html__( 'Left', 'goldsmith' )
                ],
                'condition' => ['tooltips' => 'yes']
            ]
        );
        $this->add_control( 'tooltip_trigger',
            [
                'label' => esc_html__( 'Tooltip Open Action', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'click',
                'options' => [
                    'hover' => esc_html__( 'Hover', 'goldsmith' ),
                    'click' => esc_html__( 'Click', 'goldsmith' ),
                ],
                'condition' => ['tooltips' => 'yes']
            ]
        );
        $this->add_control( 'tooltip_animation',
            [
                'label' => esc_html__( 'Tooltip Animation', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'fade' => esc_html__( 'fade', 'goldsmith' ),
                    'grow' => esc_html__( 'grow', 'goldsmith' ),
                    'swing' => esc_html__( 'swing', 'goldsmith' ),
                    'slide' => esc_html__( 'slide', 'goldsmith' ),
                    'fall' => esc_html__( 'fall', 'goldsmith' ),
                ],
                'condition' => ['tooltips' => 'yes']
            ]
        );
        $this->add_control( 'tooltip_theme',
            [
                'label' => esc_html__( 'Tooltip Theme', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__( 'default', 'goldsmith' ),
                    'light' => esc_html__( 'light', 'goldsmith' ),
                    'punk' => esc_html__( 'punk', 'goldsmith' ),
                    'noir' => esc_html__( 'noir', 'goldsmith' ),
                    'shadow' => esc_html__( 'shadow', 'goldsmith' ),
                ],
                'condition' => ['tooltips' => 'yes']
            ]
        );
        $this->add_control( 'tooltip_arrow',
            [
                'label' => esc_html__( 'Tooltips Arrow', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['tooltips' => 'yes']
            ]
        );
        $this->add_control( 'tooltiptext_style_heading',
            [
                'label' => esc_html__( 'TOOLTIP STYLE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['tooltips' => 'yes']
            ]
        );
        $this->add_control( 'tooltip_maxWidth',
            [
                'label' => esc_html__( 'Max Width', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3000
                    ]
                ],
                'condition' => ['tooltips' => 'yes']
            ]
        );
        $this->add_control( 'tooltip_offsetx',
            [
                'label' => esc_html__( 'Spacing X', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300
                    ]
                ],
                'condition' => ['tooltips' => 'yes']
            ]
        );
        $this->add_control( 'tooltip_offsety',
            [
                'label' => esc_html__( 'Spacing Y', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300
                    ]
                ],
                'condition' => ['tooltips' => 'yes']
            ]
        );
        $this->add_control( 'tooltip_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
            ]
        );
        $this->add_control( 'tooltip_brdcolor',
            [
                'label' => esc_html__( 'Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
            ]
        );
        $this->end_controls_section();
        /*****   End Button Options   ******/

        /***** Button Style ******/
        $this->start_controls_section('btn_styling',
            [
                'label' => esc_html__( 'Button Style', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $this->start_controls_tabs('btn_tabs');
        $this->start_controls_tab( 'btn_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'goldsmith' ) ]
        );
        $this->add_control( 'btn_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-btn' => 'color: {{VALUE}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-btn-text'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-btn',
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control( 'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_background',
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .goldsmith-btn',
                'separator' => 'before'
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab('btn_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'goldsmith' ) ]
        );
        $this->add_control( 'btn_hvr_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-btn:hover' => 'color: {{VALUE}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_hvr_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-btn:hover',
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_hvr_background',
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .goldsmith-btn:hover',
                'separator' => 'before'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control( 'btn_overlaycolor',
            [
                'label' => esc_html__( 'Background Image Overlay Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-btn' => 'position:relative;overflow:hidden;',
                    '{{WRAPPER}} .goldsmith-btn:before' => 'content:"";position:absolute;width:100%;height:100%;top:0;left:0;background-color: {{VALUE}};z-index:0;',
                    '{{WRAPPER}} .goldsmith-btn i' => 'position:relative;z-index:1;',
                    '{{WRAPPER}} .goldsmith-btn .btn-text' => 'position:relative;z-index:2;',
                ]
            ]
        );
        $this->end_controls_section();
        /***** End Button Styling *****/
    }

    protected function render() {
        $settings   = $this->get_settings_for_display();
        $settingsid = $this->get_id();
        $edit       = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-edit' : '';
        $iconpos    = isset( $settings['icon']['value'] ) != '' ? ' '.$settings['icon_pos'] : '';
        $btnicon    = $settings['use_icon'] == 'yes' ? ' has-icon' : '';
        $full       = $settings['full'] == 'yes' ? ' is-full' : '';
        $btn_id     = $settings['btn_id'] ? ' id="'.$settings['btn_id'].'"' : '';
        $target     = !empty( $settings['link']['is_external'] ) ? ' target="_blank"' : '';
        $nofollow   = !empty( $settings['link']['nofollow'] ) ? ' rel="nofollow"' : '';
        $href       = !empty( $settings['link']['url'] ) ? $settings['link']['url'] : '';
        $data       = $target.$nofollow;

        $tooltipsdata = json_encode(array(
            'position'  => $settings['tooltip_pos'],
            'arrow'     => 'yes' == $settings['tooltip_arrow'] ? true : false,
            'maxwidth'  => empty( $settings['tooltip_maxWidth']['size'] ) || 0 == $settings['tooltip_maxWidth']['size'] ? 'auto' : $settings['tooltip_maxWidth']['size'],
            'trigger'   => $settings['tooltip_trigger'],
            'theme'     => 'tooltipster-'.$settings['tooltip_theme'],
            'animation' => $settings['tooltip_animation'],
            'offsetx'   => !empty( $settings['tooltip_offsetx']['size'] ) ? $settings['tooltip_offsetx']['size'] : '',
            'offsety'   => !empty( $settings['tooltip_offsety']['size'] ) ? $settings['tooltip_offsety']['size'] : '',
            'bgcolor'   => $settings['tooltip_bgcolor'],
            'brdcolor'  => $settings['tooltip_brdcolor'],
            'content'   => !empty( $settings['tooltiptext'] ) ? do_shortcode($settings['tooltiptext']) : ''
        ));

        $tooltips = '';
        if ( $settings['tooltips'] == 'yes' ) {
            wp_enqueue_style( 'tooltipster');
            wp_enqueue_script( 'tooltipster');
            $tooltips = ' data-goldsmith-tooltip'.$edit.'=\''.$tooltipsdata.'\'';
        }

        $data_imgs = '';
        if ( $settings['btn_action'] != 'link' ) {
            wp_enqueue_script( 'magnific');
            switch ($settings['btn_action']) {
                case 'image':
                    $title = $settings['ltitle'] ? ' title="'.$settings['ltitle'].'"' : '';
                    $data = ' data-goldsmith-lightbox'.$edit.'=\'{"type":"image"}\'';
                    $href = $settings['image']['url'];
                    break;
                case 'gallery':
                    $title = $settings['ltitle'] ? ' title="'.$settings['ltitle'].'"' : '';
                    $data = ' data-goldsmith-lightbox'.$edit.'=\'{"type":"gallery"}\'';
                    $dataimgs = array();

                    if ( !empty( $settings['images'] ) ){
                		foreach ( $settings['images'] as $image ) {
                			array_push($dataimgs, ["src" => $image['url'] ]);
                		}
                        $data_imgs = ' data-goldsmith-lightbox-gallery'.$edit.'=\''.json_encode($dataimgs).'\'';
                        $href = $settings['images'][0]['url'];
            		}
                    break;
                case 'youtube':
                    $data = ' data-goldsmith-lightbox'.$edit.'=\'{"type":"iframe"}\'';
                    $href = $settings['youtube'] ? $settings['youtube'] : 'http://www.youtube.com/watch?v=AeeE6PyU-dQ';
                    break;
                case 'vimeo':
                    $data = ' data-goldsmith-lightbox'.$edit.'=\'{"type":"iframe"}\'';
                    $href = $settings['vimeo'] ? $settings['vimeo'] : 'https://vimeo.com/39493181';
                    break;
                case 'map':
                    $data = ' data-goldsmith-lightbox'.$edit.'=\'{"type":"iframe"}\'';
                    $href = $settings['map'] ? $settings['map'] : 'https://maps.google.com/maps?q=221B+Baker+Street,+London,+United+Kingdom&amp;hl=en&amp;t=v&amp;hnear=221B+Baker+St,+London+NW1+6XE,+United+Kingdom';
                    break;
                case 'html5':
                    $data = ' data-goldsmith-lightbox'.$edit.'=\'{"type":"iframe"}\'';
                    $href = $settings['html5'] ? $settings['html5'] : '';
                    break;
                case 'modal':
                    $data = ' data-goldsmith-lightbox'.$edit.'=\'{"type":"inline"}\'';
                    $href = '#modal_'.$settingsid;
                    break;
                default:
                    $data = $target.$nofollow;
                    $href = $href;
                    break;
            }
        }

        $text = '<span class="goldsmith-btn-text">'.$settings['text'].'</span>';

        if ( $settings['icon_pos'] == 'btn-icon-left' || $settings['icon_pos'] == 'btn-icon-top' ) {
            echo '<a'.$btn_id.' data-id="btn-'.$settingsid.'" class="type-widget goldsmith-btn '.$settings['color_type'].' '.$settings['bg_type'].' '.$settings['radius_type'].' '.$settings['size'].''.$iconpos.$btnicon.$full.'" href="'.$href.'"'.$data.$tooltips.$data_imgs.'><span class="goldsmith-button-inner">';
            if ( !empty( $settings['icon']['value'] ) ) {
                echo '<span class="goldsmith-button-icon">';
                Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                echo '</span>';
            } echo $text.'</span></a>';
        } else {
            echo '<a'.$btn_id.' data-id="btn-'.$settingsid.'" class="type-widget goldsmith-btn '.$settings['color_type'].' '.$settings['bg_type'].' '.$settings['radius_type'].' '.$settings['size'].''.$iconpos.$btnicon.$full.'" href="'.$href.'"'.$data.$tooltips.$data_imgs.'><span class="goldsmith-button-inner">'.$text.' ';
            if ( !empty( $settings['icon']['value'] ) ) {
                echo '<span class="goldsmith-button-icon">';
                Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                echo '</span>';
            } echo '</span></a>';
        }
        if ( $settings['btn_action'] == 'modal' && !empty( $settings['modal_content'] ) ) {
            echo '<div id="modal_'.$settingsid.'" class="mfp-hide modal_content" style="position:relative; max-width:'.$settings['modal_width']['size'].'px; margin:auto; padding:30px; background-color:#ffffff;">';
                echo !empty( $settings['modal_content'] ) ? do_shortcode($settings['modal_content']) : '';
            echo '</div>';
        }

        // in edit mode
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            ?>
            <script>
            jQuery(document).ready(function($){

                var myButton  = $('.goldsmith-btn[data-id="btn-<?php echo $settingsid;?>"]'),
                    myData    = myButton.data('goldsmith-lightbox-edit'),
                    myTooltip = myButton.data('goldsmith-tooltip-edit');

                if (myData && myData.type) {
                    myButton.magnificPopup({
                        type: myData.type,
                        modal: false
                    });
                }

                if (myTooltip) {
                    myButton.tooltipster({
                        position      : myTooltip.position,
                        content       : myTooltip.content,
                        animation     : myTooltip.animation,
                        theme         : myTooltip.theme,
                        trigger       : myTooltip.trigger,
                        offsetX       : myTooltip.offsetx,
                        offsetY       : myTooltip.offsety,
                        arrow         : myTooltip.arrow,
                        maxWidth      : myTooltip.maxwidth,
                        contentAsHTML : true,
                        hideOnClick   : true,
                        interactive   : true,
                        touchDevices  : true,
                        functionReady : function(){
                            var id  = this.__namespace,
                                bg  = myTooltip.bgcolor != '' ? myTooltip.bgcolor : '',
                                brd = myTooltip.brdcolor != '' ? myTooltip.brdcolor : '',
                                pos = myData.position;

                            $('#'+id+' .tooltipster-box').css({
                                'background-color' : bg,
                                'border-color' : brd
                            });

                            if ( myTooltip.arrow ) {
                                $('#'+id+' .tooltipster-arrow-background').css('border-'+pos+'-color', bg);
                                $('#'+id+' .tooltipster-arrow-border').css('border-'+pos+'-color', brd);
                            }
                        }
                    });
                }
            });
            </script>
            <?php
        }
    }
}
