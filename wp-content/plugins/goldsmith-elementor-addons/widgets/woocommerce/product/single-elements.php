<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_WC_Single_Elements extends Widget_Base {

	public function get_name() {
		return 'goldsmith-wc-single-elements';
	}

	public function get_title() {
		return __( 'Woo - Single Elements', 'goldsmith' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

    public function get_categories() {
		return [ 'goldsmith-woo-product' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_product',
			[
				'label' => __( 'Element', 'goldsmith' ),
			]
		);

		$this->add_control(
			'element',
			[
				'label' => __( 'Element', 'goldsmith' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => '— ' . __( 'Select', 'goldsmith' ) . ' —',
					'woocommerce_output_product_data_tabs' => __( 'Data Tabs', 'goldsmith' ),
					'woocommerce_template_single_title' => __( 'Title', 'goldsmith' ),
					'woocommerce_template_single_rating' => __( 'Rating', 'goldsmith' ),
					'woocommerce_template_single_price' => __( 'Price', 'goldsmith' ),
					'woocommerce_template_single_excerpt' => __( 'Excerpt', 'goldsmith' ),
					'woocommerce_template_single_meta' => __( 'Meta', 'goldsmith' ),
					'woocommerce_template_single_sharing' => __( 'Sharing', 'goldsmith' ),
					'woocommerce_show_product_sale_flash' => __( 'Sale Flash', 'goldsmith' ),
					'woocommerce_product_additional_information_tab' => __( 'Additional Information Tab', 'goldsmith' ),
					'woocommerce_upsell_display' => __( 'Upsell', 'goldsmith' ),
					'wc_get_stock_html' => __( 'Stock Status', 'goldsmith' ),
				],
			]
		);

		$this->end_controls_section();
	}

	public function remove_description_tab( $tabs ) {
		unset( $tabs['description'] );

		return $tabs;
	}

	private function get_element() {
        global $product;

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}
        
		$settings = $this->get_settings();
		$html = '';

		switch ( $settings['element'] ) {
			case '':
				break;

			case 'wc_get_stock_html':
				$html = wc_get_stock_html( $product );
				break;

			case 'woocommerce_output_product_data_tabs':
				add_filter( 'woocommerce_product_tabs', [ $this, 'remove_description_tab' ], 11 /* after default tabs*/ );
				ob_start();
				woocommerce_output_product_data_tabs();
				// Wrap with the internal woocommerce `product` class
				$html = '<div class="product">' . ob_get_clean() . '</div>';
				remove_filter( 'woocommerce_product_tabs', [ $this, 'remove_description_tab' ], 11 );
				break;

			case 'woocommerce_template_single_rating':
				$is_edit_mode = Plugin::elementor()->editor->is_edit_mode();

				if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
					if ( $is_edit_mode ) {
						$html = __( 'Admin Notice:', 'goldsmith' ) . ' ' . __( 'Please enable the Review Rating', 'goldsmith' );
					}
					break;
				}

				ob_start();
				woocommerce_template_single_rating();
				$html = ob_get_clean();
				if ( '' === $html && $is_edit_mode ) {
					$html = __( 'Admin Notice:', 'goldsmith' ) . ' ' . __( 'No Rating Reviews', 'goldsmith' );
				}
				break;

			default:
				if ( is_callable( $settings['element'] ) ) {
					$html = call_user_func( $settings['element'] );
				}
		}

		return $html;
	}

	protected function render() {
		echo $this->get_element();
	}

	public function render_plain_content() {}
}
