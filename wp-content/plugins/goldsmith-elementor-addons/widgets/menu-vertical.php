<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Vertical_Menu extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-menu-vertical';
    }
    public function get_title() {
        return 'Menu Vertical (N)';
    }
    public function get_icon() {
        return 'eicon-nav-menu';
    }
    public function get_categories() {
        return [ 'goldsmith' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('menu_general_settings',
            [
                'label' => esc_html__( 'General', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'menu_type',
            [
                'label' => esc_html__( 'Menu Content Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'wp' => esc_html__( 'Wp Menu', 'goldsmith' ),
                    'custom' => esc_html__( 'Custom', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'register_menus',
            [
                'label' => esc_html__( 'Select Menu', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'label_block' => true,
                'options' => $this->registered_nav_menus(),
                'condition' => ['menu_type' => 'wp'],
            ]
        );
        $this->add_control( 'menu_title',
            [
                'label' => esc_html__( 'Menu Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true
            ]
        );
        $this->add_control( 'icon',
            [
                'label' => esc_html__( 'Menu Icon', 'goldsmith' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'solid'
                ]
            ]
        );
        $this->add_control( 'icon_pos',
            [
                'label' => esc_html__( 'Menu Icon Position', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__( 'Before', 'goldsmith' ),
                    'right' => esc_html__( 'After', 'goldsmith' )
                ]
            ]
        );
        $repeater = new Repeater();
        $repeater->add_control( 'title',
            [
                'label' => esc_html__( 'Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Menu Title',
                'label_block' => true
            ]
        );
        $repeater->add_control( 'image',
            [
                'label' => esc_html__( 'Image', 'goldsmith' ),
                'type' => Controls_Manager::MEDIA
            ]
        );
        $repeater->add_control( 'link',
            [
                'label' => esc_html__( 'Link', 'goldsmith' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#0',
                    'is_external' => 'true'
                ],
                'placeholder' => esc_html__( 'Place URL here', 'goldsmith' ),
                'condition' => ['mega!' => 'yes']
            ]
        );
        $repeater->add_control( 'mega',
            [
                'label' => esc_html__( 'Mega Menu', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $repeater->add_control( 'template',
            [
                'label' => esc_html__( 'Mega Menu Content', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => false,
                'options' => $this->goldsmith_get_elementor_templates('section'),
                'condition' => ['mega' => 'yes']
            ]
        );
        $this->add_control( 'menu',
            [
                'label' => esc_html__( 'Items', 'goldsmith' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{title}}',
                'condition' => ['menu_type!' => 'wp'],
                'default' => [
                    [ 'title' => 'Menu Title' ],
                    [ 'title' => 'Menu Title' ],
                    [ 'title' => 'Menu Title' ]
                ]
            ]
        );
        $this->add_control( 'more',
            [
                'label' => esc_html__( 'More Items?', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'separator' => 'before',
                'condition' => ['menu_type!' => 'wp'],
            ]
        );
        $this->add_control( 'more_title',
            [
                'label' => esc_html__( 'More Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'condition' => ['more' => 'yes']
            ]
        );
        $repeater = new Repeater();
        $repeater->add_control( 'title2',
            [
                'label' => esc_html__( 'Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Menu Title',
                'label_block' => true
            ]
        );
        $repeater->add_control( 'image2',
            [
                'label' => esc_html__( 'Image', 'goldsmith' ),
                'type' => Controls_Manager::MEDIA
            ]
        );
        $repeater->add_control( 'link2',
            [
                'label' => esc_html__( 'Link', 'goldsmith' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#0',
                    'is_external' => 'true'
                ],
                'placeholder' => esc_html__( 'Place URL here', 'goldsmith' ),
                'condition' => ['mega!' => 'yes']
            ]
        );
        $repeater->add_control( 'mega2',
            [
                'label' => esc_html__( 'Mega Menu', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $repeater->add_control( 'template2',
            [
                'label' => esc_html__( 'Mega Menu Content Template', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => false,
                'options' => $this->goldsmith_get_elementor_templates('section'),
                'condition' => ['mega2' => 'yes']
            ]
        );
        $this->add_control( 'menu2',
            [
                'label' => esc_html__( 'More Items', 'goldsmith' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{title2}}',
                'default' => [],
                'condition' => ['more' => 'yes']
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
        $this->add_responsive_control( 'alignment',
            [
                'label' => esc_html__( 'Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .goldsmith-vertical-menu-wrapper-outer' => 'display:flex;justify-content: {{VALUE}};'],
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
                'default' => ''
            ]
        );
        $this->add_control( 'menu_heading',
            [
                'label' => esc_html__( 'BOX', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $this->add_responsive_control( 'menu_toggle_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-vertical-menu-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-vertical-menu-toggle' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-vertical-menu-toggle'
            ]
        );
        $this->add_responsive_control( 'box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-vertical-menu-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'box_title_typo',
                'label' => esc_html__( 'Title Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-vertical-menu-toggle .goldsmith-vertical-menu-title'
            ]
        );
        $this->add_control( 'box_title_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-vertical-menu-toggle' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-vertical-menu-toggle i' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [ '{{WRAPPER}} .goldsmith-vertical-menu-toggle i' => 'font-size:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'icon_space',
            [
                'label' => esc_html__( 'Icon Space', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-vertical-menu-toggle.icon-right i' => 'margin-left:{{SIZE}}px;',
                    '{{WRAPPER}} .goldsmith-vertical-menu-toggle.icon-left i' => 'margin-right:{{SIZE}}px;'
                ]
            ]
        );
        $this->add_control( 'dropdown_heading',
            [
                'label' => esc_html__( 'DROPDOWN', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'active',
            [
                'label' => esc_html__( 'Active', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'dropdown_alignment',
            [
                'label' => esc_html__( 'Dropdown Direction', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'dropdown-right' => [
                        'title' => esc_html__( 'Left', 'goldsmith' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'dropdown-left' => [
                        'title' => esc_html__( 'Right', 'goldsmith' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => ''
            ]
        );
        $this->add_responsive_control( 'submenu_minwidth',
            [
                'label' => esc_html__( 'Min Width', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 4000,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-vertical-menu, {{WRAPPER}} .goldsmith-vertical-menu .submenu' => 'min-width:{{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->add_responsive_control( 'submenu_topoffset',
            [
                'label' => esc_html__( 'Top Offset', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-vertical-menu' => 'top:{{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->add_responsive_control( 'menu_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-vertical-menu, {{WRAPPER}} .goldsmith-vertical-menu .submenu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'dropdown_bgcolor',
            [
                'label' => esc_html__( 'Dropdown Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-vertical-menu, {{WRAPPER}} .goldsmith-vertical-menu .submenu' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_brd',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-vertical-menu'
            ]
        );
        $this->add_control( 'menu_image_heading',
            [
                'label' => esc_html__( 'MENU ITEMS IMAGE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['menu_type!' => 'wp'],
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
                'condition' => ['menu_type!' => 'wp']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_brd',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .header-style-two .goldsmith-vertical-menu-item-img',
                'condition' => ['menu_type!' => 'wp']
            ]
        );
        $this->add_control( 'menu_items_heading',
            [
                'label' => esc_html__( 'MENU ITEMS', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_items_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-vertical-menu-item, {{WRAPPER}} .goldsmith-vertical-menu ul > li.menu-item > a'
            ]
        );
        $this->add_responsive_control( 'menu_items_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-vertical-menu-item, {{WRAPPER}} .goldsmith-vertical-menu ul > li.menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->start_controls_tabs( 'menu_tabs');
        $this->start_controls_tab( 'menu_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'goldsmith' ) ]
        );
        $this->add_control( 'menu_item_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-vertical-menu-item, {{WRAPPER}} .goldsmith-vertical-menu ul > li.menu-item > a' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'menu_item_bgcolor',
            [
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-vertical-menu-item, {{WRAPPER}} .goldsmith-vertical-menu ul > li.menu-item' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'menu_item_brd',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-vertical-menu-item, {{WRAPPER}} .goldsmith-vertical-menu ul > li.menu-item'
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'menu_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'goldsmith' ) ]
        );
        $this->add_control( 'menu_item_hvrcolor',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-vertical-menu-item:hover, {{WRAPPER}} .goldsmith-vertical-menu ul > li.menu-item:hover > a' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'menu_item_hvrbgcolor',
            [
                'label' => esc_html__( 'Background', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-vertical-menu-item:hover, {{WRAPPER}} .goldsmith-vertical-menu ul > li.menu-item:hover' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'menu_item_hvrbrd',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-vertical-menu-item:hover, {{WRAPPER}} .goldsmith-vertical-menu ul > li.menu-item:hover'
            ]
        );
        $this->add_control( 'megamenu_content_heading',
            [
                'label' => esc_html__( 'MEGA MENU', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control( 'megamenu_width',
            [
                'label' => esc_html__( 'Mega Menu Container Width', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 4000,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-mega-menu-content, {{WRAPPER}} .goldsmith-vertical-menu-item ul.submenu' => 'width:{{SIZE}}{{UNIT}};',
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'full';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size  = [ $sizew, $sizeh ];
        }
        $active     = 'yes' == $settings['active'] ? ' drop-active' : '';
        $is_edit    = \Elementor\Plugin::$instance->editor->is_edit_mode() ? 'goldsmith-vertical-menu-wrapper-edit-mode goldsmith-vertical-menu-wrapper-'.$id : 'goldsmith-vertical-menu-wrapper';
        $menu_title = $settings['menu_title'] ? $settings['menu_title'] : esc_html__('All Categories', 'goldsmith');

        echo '<div class="goldsmith-vertical-menu-wrapper-outer">';
            echo '<div class="'.$is_edit.'">';
                echo '<div class="goldsmith-vertical-menu-toggle icon-'.$settings['icon_pos'].'">';
                    if ( $settings['icon_pos'] == 'left' ) {
                        echo '<span class="goldsmith-vertical-menu-title">';
                        if ( !empty( $settings['icon']['value'] ) ) {
                            Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                        }
                        echo $menu_title.'</span>';
                    } else {
                        echo '<span class="goldsmith-vertical-menu-title"> '.$menu_title;
                        if ( !empty( $settings['icon']['value'] ) ) {
                            Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                        }
                        echo '</span>';
                    }
                echo '</div>';
                echo '<div class="goldsmith-vertical-menu '.$settings['dropdown_alignment'].''.$active.'">';
                    if ( 'wp' == $settings['menu_type'] ) {
                        echo '<ul class="navigation">';
                            echo wp_nav_menu(
                                array(
                                    'menu' => $settings['register_menus'],
                                    'container' => '',
                                    'container_class' => '',
                                    'container_id' => '',
                                    'menu_class' => '',
                                    'menu_id' => '',
                                    'items_wrap' => '%3$s',
                                    'before' => '',
                                    'after' => '',
                                    'link_before' => '',
                                    'link_after' => '',
                                    'depth' => 4,
                                    'echo' => true,
                                    'fallback_cb' => 'Goldsmith_Wp_Bootstrap_Navwalker::fallback',
                                    'walker' => new \Goldsmith_Wp_Bootstrap_Navwalker()
                                )
                            );
                        echo '</ul>';
                    } else {
                        foreach ( $settings['menu'] as $m ) {
                            $attr      = !empty( $m['link']['is_external'] ) ? ' target="_blank"' : '';
                            $attr     .= !empty( $m['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                            $attr     .= !empty( $m['link']['url'] ) ? ' href="'.$m['link']['url'].'"' : ' href="#0"';
                            $attr     .= !empty( $m['title'] ) ? ' title="'.$m['title'].'"' : '';
                            $hasmega   = 'yes' == $m['mega'] ? ' goldsmith-has-mega-menu' : '';
                            $righticon = 'yes' == $m['mega'] ? ' <i class="fas fa-angle-right"></i>' : '';
                            $image     = !empty( $m['image']['url'] ) ? '<div class="goldsmith-vertical-menu-item-img">'.wp_get_attachment_image( $m['image']['id'], $size, false ).'</div>' : '';
                            if ( !empty( $m['title'] ) ) {
                                echo '<div class="goldsmith-vertical-menu-item'.$hasmega.'">';
                                    echo '<a'.$attr.'>'.$image.'<span class="goldsmith-vertical-menu-item-title">'.$m['title'].$righticon.'</span></a>';
                                    if ( 'yes' == $m['mega'] && !empty( $m['template'] ) ) {
                                        echo '<div class="goldsmith-mega-menu-content">';
                                            $style = \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false;
                                            $template_id = $m['template'];
                                            $header_content = new Frontend;
                                            echo $header_content->get_builder_content_for_display( $template_id, $style );
                                        echo '</div>';
                                    }
                                echo '</div>';
                            }
                        }
                        if ( 'yes' == $settings['more'] && !empty( $settings['menu2'] ) ) {
                            $more_title = $settings['more_title'] ? $settings['more_title'] : esc_html__('More Categories', 'goldsmith');
                            echo '<div class="goldsmith-vertical-menu-more-toggle goldsmith-vertical-menu-item"><span class="goldsmith-vertical-more-title">'.$more_title.'</span> <i class="fas fa-angle-down"></i></div>';
                            echo '<div class="goldsmith-vertical-menu-more-items">';
                                foreach ( $settings['menu2'] as $m ) {
                                    $attr2     = !empty( $m['link2']['is_external'] ) ? ' target="_blank"' : '';
                                    $attr2    .= !empty( $m['link2']['nofollow'] ) ? ' rel="nofollow"' : '';
                                    $attr2    .= !empty( $m['link2']['url'] ) ? ' href="'.$m['link2']['url'].'"' : ' href="#0"';
                                    $attr2    .= !empty( $m['title2'] ) ? ' title="'.$m['title2'].'"' : '';
                                    $hasmega2  = 'yes' == $m['mega2'] ? ' goldsmith-has-mega-menu' : '';
                                    $image2    = $m['image2']['url'] ? '<div class="goldsmith-vertical-menu-item-img">'.wp_get_attachment_image( $m['image2']['id'], $size, false ).'</div>' : '';
                                    $righticon = 'yes' == $m['mega'] ? ' <i class="fas fa-angle-right"></i>' : '';
                                    echo '<div class="goldsmith-vertical-menu-item'.$hasmega2.'">';
                                        echo '<a'.$attr2.'>'.$image2.'<span class="goldsmith-vertical-menu-item-title">'.$m['title2'].$righticon.'</span></a>';
    
                                        if ( 'yes' == $m['mega2'] && !empty( $m['template2'] ) ) {
                                            echo '<div class="goldsmith-mega-menu-content">';
                                                $style = \Elementor\Plugin::$instance->editor->is_edit_mode() ? true : false;
                                                $template_id = $m['template2'];
                                                $header_content = new Frontend;
                                                echo $header_content->get_builder_content_for_display( $template_id, $style );
                                            echo '</div>';
                                        }
                                    echo '</div>';
                                }
                            echo '</div>';
                        }
                    }
                echo '</div>';
            echo '</div>';
        echo '</div>';
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            ?>
            <script>
            jQuery(document).ready( function ($) {

                const $this    = $('.goldsmith-vertical-menu-wrapper-<?php echo esc_attr($id); ?>');
                const menu     = $this.find('.goldsmith-vertical-menu');
                const toggle   = $this.find('.goldsmith-vertical-menu-toggle');
                const more     = $this.find('.goldsmith-vertical-menu-more-items');
                const morecats = $this.find('.goldsmith-vertical-menu-more-toggle');
                /*=============================================
                Toggle Active
                =============================================*/
                $(toggle).on('click', function () {
                    $(menu).slideToggle(500);
                    return false;
                });
                $(more).slideUp();
                $(morecats).on('click', function () {
                    $(this).toggleClass('show');
                    $(more).slideToggle();
                });

            });
        </script>
        <?php
        }
    }
}
