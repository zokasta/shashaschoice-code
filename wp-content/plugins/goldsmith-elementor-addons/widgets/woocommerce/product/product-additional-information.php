<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_WC_Product_Additional_Information extends Widget_Base {

	public function get_name() {
		return 'goldsmith-wc-product-additional-information';
	}

	public function get_title() {
		return __( 'Additional Information', 'goldsmith' );
	}

	public function get_icon() {
		return ' eicon-product-info';
	}
    public function get_categories() {
		return [ 'goldsmith-woo-product' ];
	}
	protected function register_controls() {

		$this->start_controls_section( 'section_additional_info_style', [
			'label' => __( 'General', 'goldsmith' ),
			'tab' => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control(
			'show_heading',
			[
				'label' => __( 'Heading', 'goldsmith' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'goldsmith' ),
				'label_off' => __( 'Hide', 'goldsmith' ),
				'render_type' => 'ui',
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'elementor-show-heading-',
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => __( 'Color', 'goldsmith' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} h2' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_heading!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'label' => __( 'Typography', 'goldsmith' ),
				'selector' => '{{WRAPPER}} h2',
				'condition' => [
					'show_heading!' => '',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Color', 'goldsmith' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .shop_attributes' => 'color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __( 'Typography', 'goldsmith' ),
				'selector' => '{{WRAPPER}} .shop_attributes',
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

		wc_get_template( 'single-product/tabs/additional-information.php' );
	}

	public function render_plain_content() {}
}
