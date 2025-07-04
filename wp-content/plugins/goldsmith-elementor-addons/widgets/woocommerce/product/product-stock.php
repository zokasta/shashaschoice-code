<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_WC_Product_Stock extends Widget_Base {

	public function get_name() {
		return 'goldsmith-wc-product-stock';
	}

	public function get_title() {
		return __( 'Product Stock', 'goldsmith' );
	}

	public function get_icon() {
		return 'eicon-product-stock';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'stock', 'quantity', 'product' ];
	}
    public function get_categories() {
		return [ 'goldsmith-woo-product' ];
	}
	protected function register_controls() {

		$this->start_controls_section(
			'section_product_stock_style',
			[
				'label' => __( 'Style', 'goldsmith' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'goldsmith' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .stock' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __( 'Typography', 'goldsmith' ),
				'selector' => '{{WRAPPER}} .stock',
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

		echo wc_get_stock_html( $product );
	}
}
