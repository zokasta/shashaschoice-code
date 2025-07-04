<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_WC_Product_Price extends Widget_Base {

	public function get_name() {
		return 'goldsmith-wc-product-price';
	}

	public function get_title() {
		return __( 'Product Price', 'goldsmith' );
	}

	public function get_icon() {
		return 'eicon-product-price';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'price', 'product', 'sale' ];
	}
    public function get_categories() {
		return [ 'goldsmith-woo-product' ];
	}
	protected function register_controls() {

		$this->start_controls_section(
			'section_price_style',
			[
				'label' => __( 'Price', 'goldsmith' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'text_align',
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
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => __( 'Color', 'goldsmith' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .price' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .price',
			]
		);

		$this->add_control(
			'sale_heading',
			[
				'label' => __( 'Sale Price', 'goldsmith' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sale_price_color',
			[
				'label' => __( 'Color', 'goldsmith' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .price ins' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sale_price_typography',
				'selector' => '{{WRAPPER}} .price ins',
			]
		);

		$this->add_control(
			'price_block',
			[
				'label' => __( 'Stacked', 'goldsmith' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'goldsmithduct-price-block-',
			]
		);

		$this->add_responsive_control(
			'sale_price_spacing',
			[
				'label' => __( 'Spacing', 'goldsmith' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}}:not(.goldsmithduct-price-block-yes) del' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}}:not(.goldsmithduct-price-block-yes) del' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.goldsmithduct-price-block-yes del' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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

		wc_get_template( '/single-product/price.php' );
	}

	public function render_plain_content() {}
}
