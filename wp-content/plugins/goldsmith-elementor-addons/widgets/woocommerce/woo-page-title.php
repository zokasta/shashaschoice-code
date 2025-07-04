<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_WC_Page_Title extends Widget_Base {

	public function get_name() {
		return 'goldsmith-wc-page-title';
	}

	public function get_title() {
		return __( 'Woo Archive Title', 'goldsmith' );
	}

	public function get_icon() {
		return 'eicon-product-description';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'woo','title','heading','wc', 'shop', 'store', 'text', 'description', 'category', 'product', 'archive' ];
	}

    public function get_categories() {
		return [ 'goldsmith-woo' ];
	}

	protected function register_controls() {

		$this->start_controls_section( 'section_product_title_style',
			[
				'label' => __( 'Style', 'goldsmith' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control( 'tag',
            [
                'label' => esc_html__( 'Title Tag for SEO', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => esc_html__( 'H1', 'goldsmith' ),
                    'h2' => esc_html__( 'H2', 'goldsmith' ),
                    'h3' => esc_html__( 'H3', 'goldsmith' ),
                    'h4' => esc_html__( 'H4', 'goldsmith' ),
                    'h5' => esc_html__( 'H5', 'goldsmith' ),
                    'h6' => esc_html__( 'H6', 'goldsmith' ),
                    'div' => esc_html__( 'div', 'goldsmith' ),
                    'p' => esc_html__( 'p', 'goldsmith' )
                ]
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
						'icon' => 'eicon-text-align-center'
					],
					'right' => [
						'title' => __( 'Right', 'goldsmith' ),
						'icon' => 'eicon-text-align-right'
					],
					'justify' => [
						'title' => __( 'Justified', 'goldsmith' ),
						'icon' => 'eicon-text-align-justify'
					]
				],
				'selectors' => ['{{WRAPPER}} .goldsmith-page-title' => 'text-align: {{VALUE}}']
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'goldsmith' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .goldsmith-page-title' => 'color: {{VALUE}}' ]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __( 'Typography', 'goldsmith' ),
				'selector' => '{{WRAPPER}} .goldsmith-page-title'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
	    $settings = $this->get_settings_for_display();
        global $post;
        $post_type = get_post_type( $post->ID );
        if ( $post_type == 'elementor_library' ) {
            echo '<'.$settings['tag'].' class="goldsmith-page-title">'.get_the_title().'</'.$settings['tag'].'>';
        } else {
            echo '<'.$settings['tag'].' class="goldsmith-page-title">'.get_the_archive_title().'</'.$settings['tag'].'>';
        }
	}
}
