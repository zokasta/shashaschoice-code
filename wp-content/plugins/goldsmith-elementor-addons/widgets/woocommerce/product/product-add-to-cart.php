<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_WC_Product_Add_To_Cart extends Widget_Base {

    public function get_name() {
        return 'goldsmith-wc-product-add-to-cart';
    }
    public function get_title() {
        return __( 'Add To Cart', 'goldsmith' );
    }
    public function get_icon() {
        return 'eicon-product-add-to-cart';
    }
    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'cart', 'product', 'button', 'add to cart' ];
    }
    public function get_categories() {
        return [ 'goldsmith-woo-product' ];
    }
    protected function register_controls() {

        $this->start_controls_section(
            'section_atc_button_style',
            [
                'label' => __( 'Button', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'alignment',
            [
                'label' => __( 'Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'goldsmith' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'goldsmith' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'goldsmith' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'goldsmith' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'prefix_class' => 'elementor-add-to-cart%s--align-',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .cart button',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .cart button',
                'exclude' => [ 'color' ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .cart button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .cart button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'button_style_tabs' );

        $this->start_controls_tab( 'button_style_normal',
            [
                'label' => __( 'Normal', 'goldsmith' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __( 'Text Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_border_color',
            [
                'label' => __( 'Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart button' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'button_style_hover',
            [
                'label' => __( 'Hover', 'goldsmith' ),
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label' => __( 'Text Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => __( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label' => __( 'Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_transition',
            [
                'label' => __( 'Transition Duration', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.2,
                ],
                'range' => [
                    'px' => [
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cart button' => 'transition: all {{SIZE}}s',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_atc_quantity_style',
            [
                'label' => __( 'Quantity', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'spacing',
            [
                'label' => __( 'Spacing', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .quantity + .button' => 'margin-left: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}} .quantity + .button' => 'margin-right: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'quantity_typography',
                'selector' => '{{WRAPPER}} .quantity .qty',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'quantity_border',
                'selector' => '{{WRAPPER}} .quantity .qty',
                'exclude' => [ 'color' ],
            ]
        );

        $this->add_control(
            'quantity_border_radius',
            [
                'label' => __( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'quantity_padding',
            [
                'label' => __( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'quantity_style_tabs' );

        $this->start_controls_tab( 'quantity_style_normal',
            [
                'label' => __( 'Normal', 'goldsmith' ),
            ]
        );

        $this->add_control(
            'quantity_text_color',
            [
                'label' => __( 'Text Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'quantity_bg_color',
            [
                'label' => __( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'quantity_border_color',
            [
                'label' => __( 'Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'quantity_style_focus',
            [
                'label' => __( 'Focus', 'goldsmith' ),
            ]
        );

        $this->add_control(
            'quantity_text_color_focus',
            [
                'label' => __( 'Text Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty:focus' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'quantity_bg_color_focus',
            [
                'label' => __( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'quantity_border_color_focus',
            [
                'label' => __( 'Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty:focus' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'quantity_transition',
            [
                'label' => __( 'Transition Duration', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.2,
                ],
                'range' => [
                    'px' => [
                        'max' => 2,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'transition: all {{SIZE}}s',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_atc_variations_style',
            [
                'label' => __( 'Variations', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'variations_width',
            [
                'label' => __( 'Width', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} form.cart .variations' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'variations_spacing',
            [
                'label' => __( 'Spacing', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} form.cart .variations' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'variations_space_between',
            [
                'label' => __( 'Space Between', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} form.cart table.variations tr:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'heading_variations_label_style',
            [
                'label' => __( 'Label', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'variations_label_color_focus',
            [
                'label' => __( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} form.cart table.variations label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'variations_label_typography',
                'selector' => '{{WRAPPER}} form.cart table.variations label',
            ]
        );

        $this->add_control(
            'heading_variations_select_style',
            [
                'label' => __( 'Select field', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'variations_select_color',
            [
                'label' => __( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} form.cart table.variations td.value select' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'variations_select_bg_color',
            [
                'label' => __( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} form.cart table.variations td.value:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'variations_select_border_color',
            [
                'label' => __( 'Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} form.cart table.variations td.value:before' => 'border: 1px solid {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'variations_select_typography',
                'selector' => '{{WRAPPER}} form.cart table.variations td.value select, .woocommerce div.product.elementor{{WRAPPER}} form.cart table.variations td.value:before',
            ]
        );

        $this->add_control(
            'variations_select_border_radius',
            [
                'label' => __( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} form.cart table.variations td.value:before' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        global $product;
        $product = wc_get_product();

        if ( empty( $product ) ) {
            return;
        }

        ?>

        <div class="goldsmith-add-to-cart goldsmith-product-<?php echo esc_attr( $product->get_type() ); ?>">
            <?php woocommerce_template_single_add_to_cart(); ?>
        </div>

        <?php
    }
}
