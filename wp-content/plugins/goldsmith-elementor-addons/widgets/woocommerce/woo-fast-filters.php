<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Fast_Filters extends Widget_Base {
	use Goldsmith_Helper;
	public function get_name() {
		return 'goldsmith-wc-fast-filters';
	}
	public function get_title() {
		return __( 'Woo Fast Filters (N)', 'goldsmith' );
	}
	public function get_icon() {
		return 'eicon-site-search';
	}
	public function get_keywords() {
		return [ 'woocommerce', 'woo','filter','title','heading','wc', 'shop', 'store', 'text', 'description', 'category', 'product', 'archive' ];
	}
    public function get_categories() {
		return [ 'goldsmith-woo' ];
	}
    public function get_style_depends() {
        return [ 'goldsmith-wc-fast-filters' ];
    }
	protected function register_controls() {

		$this->start_controls_section('section_settings',
			[
				'label' => esc_html__( 'Filters Settings', 'goldsmith' ),
				'tab' => Controls_Manager::TAB_CONTENT
			]
		);
        $this->add_control( 'use_ajax',
            [
                'label' => esc_html__( 'Show Product by Ajax', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control( 'main_title',
            [
                'label' => esc_html__( 'Filters Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Fast Filters:'
            ]
        );
        $repeater = new Repeater();
        $repeater->add_control( 'filter_type',
            [
                'label' => esc_html__( 'Filter Type ', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'featured',
                'options' => [
                    'featured' => esc_html__( 'Featured', 'goldsmith' ),
                    'bestseller' => esc_html__( 'Best Seller', 'goldsmith' ),
                    'toprated' => esc_html__( 'Top Rated', 'goldsmith' )
                ]
            ]
        );
        $repeater->add_control( 'title',
            [
                'label' => esc_html__( 'Filter Button Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'icon',
            [
                'label' => esc_html__( 'Icon', 'goldsmith' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'solid'
                ]
            ]
        );
        $this->add_control( 'main_filters',
            [
                'label' => esc_html__( 'Items', 'goldsmith' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{filter_type}}',
                'separator' => 'before',
                'default' => [
                	[
                		'filter_type' => 'featured',
                		'title' => 'Featured'
                	],
                	[
                		'filter_type' => 'bestseller',
                		'title' => 'Best Seller'
                	],
                	[
                		'filter_type' => 'toprated',
                		'title' => 'Top Rated'
                	]
                ]
            ]
        );

        $attribute_taxonomies = wc_get_attribute_taxonomies();
        if ( !empty( $attribute_taxonomies ) && !is_wp_error( $attribute_taxonomies ) ) {
			$this->add_control( 'terms',
				[
					'label' => esc_html__( 'Filter By Terms', 'goldsmith' ),
					'type' => Controls_Manager::SELECT2,
					'options' => $this->goldsmith_woo_attributes(),
					'default' => [],
					'label_block' => true,
					'multiple' => true,
					'separator' => 'before'
				]
			);
			foreach ( $attribute_taxonomies as $tax ) {
				$options = array();
				$tax_name = $tax->attribute_name;
				$tax_label = $tax->attribute_label;
	            $terms = get_terms( 'pa_'.$tax_name, 'orderby=name&hide_empty=0' );
	            foreach ($terms as $term) {
	                $options[$term->slug] = $term->name;
	            }
				$this->add_control( 'terms_attr_'.$tax_name,
					[
						'label' => esc_html__( 'Terms '.$tax_label, 'goldsmith' ),
						'type' => Controls_Manager::SELECT2,
						'options' => $options,
						'default' => [],
						'label_block' => true,
						'multiple' => true,
						'condition' => [
							'terms' => $tax_name
						]
					]
				);
		        $this->add_control( 'terms_title_'.$tax_name,
		            [
		                'label' => esc_html__( 'Terms Title for '.$tax_label, 'goldsmith' ),
		                'type' => Controls_Manager::TEXT,
		                'default' => 'Select '.$tax_label,
		                'label_block' => true,
						'condition' => [
							'terms' => $tax_name
						]
		            ]
		        );
			}
        }
        $this->add_control( 'stock_sale_status',
            [
                'label' => esc_html__( 'Instock & Onsale Display Status ', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'show-always',
                'options' => [
                    'show-always' => esc_html__( 'Show Always', 'goldsmith' ),
                    'show-after-filter' => esc_html__( 'Show After Filtering', 'goldsmith' )
                ]
            ]
        );
        $this->add_control( 'onsale_title',
            [
                'label' => esc_html__( 'On Sale Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'On Sale'
            ]
        );
        $this->add_control( 'onsale_icon',
            [
                'label' => esc_html__( 'Icon', 'goldsmith' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'solid'
                ]
            ]
        );
        $this->add_control( 'instock_title',
            [
                'label' => esc_html__( 'In Stock Title', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'In Stock'
            ]
        );
        $this->add_control( 'instock_icon',
            [
                'label' => esc_html__( 'Icon', 'goldsmith' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'solid'
                ]
            ]
        );
		$this->end_controls_section();
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
                'selectors' => ['{{WRAPPER}} .goldsmith-shop-fast-filters' => 'justify-content: {{VALUE}};'],
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
        $is_rtl = is_rtl() ? 'left' : 'right';
        $this->add_responsive_control( 'item_spacing',
            [
                'label' => esc_html__( 'Spacing Between Items ( px )', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 100]
                ],
				'default' => [],
                'selectors' => [
                    '{{WRAPPER}}.goldsmith-fast-filters-list li:not(:last-child)' => 'margin-'.$is_rtl.': {{SIZE}}px;'
                ]
            ]
        );
        $this->add_control( 'filter_main_title_divider',
            [
                'label' => esc_html__( 'FILTER MAIN TITLE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control( 'filter_main_title_color',
            [
                'label' => esc_html__( 'Filter Main Title Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .fast-filters-label' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_main_title_typo',
                'label' => esc_html__( 'Filter Main Title Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-shop-fast-filters .fast-filters-label'
            ]
        );
        $this->add_control( 'filter_btn_divider',
            [
                'label' => esc_html__( 'FILTER BUTTONS', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li > a'
            ]
        );
        $this->add_control( 'btn_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li > a' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_hvrbgcolor',
            [
                'label' => esc_html__( 'Background Color ( Hover/Active )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:hover > a' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li > a' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_hvrcolor',
            [
                'label' => esc_html__( 'Color ( Hover/Active )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:hover > a,
                {{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.active > a' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_brdcolor',
            [
                'label' => esc_html__( 'Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li > a' => 'border-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_hvrbrdcolor',
            [
                'label' => esc_html__( 'Border Color ( Hover/Active )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:hover > a,
                {{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.active > a' => 'border-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'btn_close_bgcolor',
            [
                'label' => esc_html__( 'Close Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:not(.remove-fast-filter) .remove-filter' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_close_color',
            [
                'label' => esc_html__( 'Close Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:not(.remove-fast-filter) .remove-filter:before,
                {{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:not(.remove-fast-filter) .remove-filter:after' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'clearall_btn_divider',
            [
                'label' => esc_html__( 'CLEAR ALL BUTTON', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'clearall_btn_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li > a' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'clearall_btn_hvrbgcolor',
            [
                'label' => esc_html__( 'Background Color ( Hover/Active )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:hover > a' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'clearall_btn_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li > a' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'clearall_btn_hvrcolor',
            [
                'label' => esc_html__( 'Color ( Hover/Active )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:hover > a,
                {{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.active > a' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'clearall_btn_brdcolor',
            [
                'label' => esc_html__( 'Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li > a' => 'border-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'clearall_btn_hvrbrdcolor',
            [
                'label' => esc_html__( 'Border Color ( Hover/Active )', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:hover > a,
                {{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.active > a' => 'border-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'clearall_btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.remove-fast-filter .remove-filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'clearall_btn_close_bgcolor',
            [
                'label' => esc_html__( 'Close Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.remove-fast-filter .remove-filter' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'clearall_btn_close_color',
            [
                'label' => esc_html__( 'Close Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.remove-fast-filter .remove-filter:before,
                {{WRAPPER}} .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.remove-fast-filter .remove-filter:after' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'all_btn_icon_divider',
            [
                'label' => esc_html__( 'CUSTOM FONT ICONS', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'btn_icon_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .goldsmith-shop-fast-filters goldsmith-fast-filter-icon' => 'color:{{VALUE}};fill:{{VALUE}};' ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
	}

	protected function render() {
	    $settings = $this->get_settings_for_display();
        global $wp;

        if ( '' === get_option( 'permalink_structure' ) ) {
            $baselink = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
        } else {
            $baselink = preg_replace( '%\/page/[0-9]+%', '', home_url( add_query_arg( null, null ) ) );
        }

        $shoplink = wc_get_page_permalink( 'shop' );

        // icons
        $featured_icon    = goldsmith_settings( 'shop_fast_filter_featured_icon' );
        $bestseller_icon  = goldsmith_settings( 'shop_fast_filter_bestseller_icon' );
        $toprated_icon    = goldsmith_settings( 'shop_fast_filter_toprated_icon' );
        $onsale_icon      = goldsmith_settings( 'shop_fast_filter_onsale_icon' );
        $instock_icon     = goldsmith_settings( 'shop_fast_filter_instock_icon' );

        $featured_icon    = $featured_icon ? $featured_icon : goldsmith_svg_lists( 'featured', 'goldsmith-svg-icon' );
        $bestseller_icon  = $bestseller_icon ? $bestseller_icon :  goldsmith_svg_lists( 'best-seller', 'goldsmith-svg-icon' );
        $toprated_icon    = $toprated_icon ? $toprated_icon : goldsmith_svg_lists( 'top-rated', 'goldsmith-svg-icon' );
        $onsale_icon      = $onsale_icon ? $onsale_icon : goldsmith_svg_lists( 'onsale', 'goldsmith-svg-icon' );
        $instock_icon     = $instock_icon ? $instock_icon : goldsmith_svg_lists( 'instock-2', 'goldsmith-svg-icon' );

        $has_filter = goldsmith_shop_check_fast_filters();
        $is_filter = $has_filter ? ' has-filter' : '';

	    //$removeAll = '' != $settings['remove_all'] ? $settings['remove_all'] : esc_html__('Remove All', 'goldsmith');
	    $is_ajax = 'yes' == $settings['use_ajax'] ? ' goldsmith-widget-is-ajax' : '';

	    $stock_sale  = 'show-always' == $settings[ 'stock_sale_status' ] ? ' show-always' : ' show-after-filter';

        echo'<div class="goldsmith-shop-fast-filters'.$is_ajax.$stock_sale.'">';
			if ( '' != $settings['main_title'] ) {
				echo'<span class="fast-filters-label"><strong>'.$settings['main_title'].'</strong></span>';
			}
        	echo'<ul class="goldsmith-fast-filters-list filters-first'.$is_filter.'">';

        		if ( !empty( $settings['main_filters'] ) ) {

					foreach ( $settings['main_filters'] as $filter ) {
						$title = !empty( $filter['title'] ) ? $filter['title'] : '';
						$filter_type = !empty( $filter['filter_type'] ) ? $filter['filter_type'] : '';

				        if ( $filter_type == 'featured' && $title ) {
				            if ( !empty( $filter['icon']['value'] ) ) {
				                $icon = '<span class="goldsmith-fast-filter-icon">';
				                	ob_start();
				                	Icons_Manager::render_icon( $filter['icon'], [ 'aria-hidden' => 'true' ] );
				                echo ob_get_clean().'</span>';
				            } else {
				            	$icon = $featured_icon;
				            }
							if ( isset( $_GET['featured'] ) && $_GET['featured'] == 'yes' ) {
								echo '<li class="active">'.$icon.'<a href="'.esc_url( remove_query_arg( 'featured', $baselink ) ).'"><span class="remove-filter"></span>'.$title.'</a></li>';
							} else {
								echo '<li>'.$icon.'<a href="'.esc_url( add_query_arg( 'featured','yes', $baselink ) ).'">'.$title.'</a></li>';
							}
				        }

				        if ( $filter_type == 'bestseller' && $title ) {
				            if ( !empty( $filter['icon']['value'] ) ) {
				                $icon = '<span class="goldsmith-fast-filter-icon">';
				                	ob_start();
				                	Icons_Manager::render_icon( $filter['icon'], [ 'aria-hidden' => 'true' ] );
				                echo ob_get_clean().'</span>';
				            } else {
				            	$icon = $bestseller_icon;
				            }
							if ( isset( $_GET['best_seller'] ) && $_GET['best_seller'] == 'yes' ) {
								echo '<li class="active">'.$icon.'<a href="'.esc_url( remove_query_arg( 'best_seller', $baselink ) ).'"><span class="remove-filter"></span>'.$title.'</a></li>';
							} else {
								echo '<li>'.$icon.'<a href="'.esc_url( add_query_arg( 'best_seller','yes', $baselink ) ).'">'.$title.'</a></li>';
							}
				        }

				        if ( $filter_type == 'toprated' && $title ) {
				            if ( !empty( $filter['icon']['value'] ) ) {
				                $icon = '<span class="goldsmith-fast-filter-icon">';
				                	ob_start();
				                	Icons_Manager::render_icon( $filter['icon'], [ 'aria-hidden' => 'true' ] );
				                echo ob_get_clean().'</span>';
				            } else {
				            	$icon = $toprated_icon;
				            }
							if ( isset( $_GET['rating_filter'] ) && $_GET['rating_filter'] == '5' ) {
								echo '<li class="active">'.$icon.'<a href="'.esc_url( remove_query_arg( 'rating_filter', $baselink ) ).'"><span class="remove-filter"></span>'.$title.'</a></li>';
							} else {
								echo '<li>'.$icon.'<a href="'.esc_url( add_query_arg( 'rating_filter','5', $baselink ) ).'">'.$title.'</a></li>';
							}
				        }
					}
				}

                if ( $has_filter || 'show-always' == $settings['stock_sale_status'] ) {

					if ( '' != $settings['onsale_title'] ) {
			            if ( !empty( $filter['onsale_icon']['value'] ) ) {
			                $onsale_icon = '<span class="goldsmith-fast-filter-icon">';
			                	ob_start();
			                	Icons_Manager::render_icon( $filter['onsale_icon'], [ 'aria-hidden' => 'true' ] );
			                echo ob_get_clean().'</span>';
			            } else {
			            	$onsale_icon = $onsale_icon;
			            }
						if ( isset( $_GET['on_sale'] ) && $_GET['on_sale'] == 'onsale' ) {
							echo '<li class="on-sale active'.esc_attr( $stock_sale ).'">'.$onsale_icon.'<a href="'.esc_url( remove_query_arg( 'on_sale', $baselink ) ).'"><span class="remove-filter"></span> '.$settings['onsale_title'].'</a></li>';
						} else {
							echo '<li class="on-sale'.esc_attr( $stock_sale ).'">'.$onsale_icon.'<a href="'.esc_url( add_query_arg( 'on_sale','onsale', $baselink ) ).'">'.$settings['onsale_title'].'</a></li>';
						}
					}

					if ( '' != $settings['instock_title'] ) {
			            if ( !empty( $settings['instock_icon']['value'] ) ) {
			                $instock_icon = '<span class="goldsmith-fast-filter-icon">';
			                	ob_start();
			                	Icons_Manager::render_icon( $settings['instock_icon'], [ 'aria-hidden' => 'true' ] );
			                echo ob_get_clean().'</span>';
			            } else {
			            	$instock_icon = $instock_icon;
			            }
						if ( isset( $_GET['stock_status'] ) && $_GET['stock_status'] == 'instock' ) {
							echo '<li class="instock active'.esc_attr( $stock_sale ).'">'.$instock_icon.'<a href="'.esc_url( remove_query_arg( 'stock_status', $baselink ) ).'"><span class="remove-filter"></span> '.$settings['instock_title'].'</a></li>';
						} else {
							echo '<li class="instock'.esc_attr( $stock_sale ).'">'.$instock_icon.'<a href="'.esc_url( add_query_arg( 'stock_status','instock', $baselink ) ).'">'.$settings['instock_title'].'</a></li>';
						}
					}
				}

				if ( !empty( $settings['terms'] ) ) {
					foreach ( $settings['terms'] as $tax ) {
						if ( !empty($settings['terms_title_'.$tax]) ) {
							$terms_active = $check_filters == TRUE ? ' active' : '';
							echo'<li class="goldsmith-has-submenu'. $terms_active .'">';
								echo'<a href="#" class="goldsmith-fast-filter">'.$settings['terms_title_'.$tax].'</a>';
								echo'<ul class="goldsmith-fast-filters-submenu">';

									foreach ( $settings['terms_attr_'.$tax] as $term ) {
										$term_name = get_term_by( 'slug', $term, 'pa_'.$tax);
										echo'<li><a href="'.esc_url( add_query_arg( 'filter_'.$tax, $term, $shoplink ) ).'" class="goldsmith-fast-filter">'.$term_name->name.'</a></li>';
									}
								echo'</ul>';
							echo'</li>';
						}
					}
				}

        	echo'</ul>';
        echo'</div>';

        if ( 'yes' == $settings['use_ajax'] ) {
        	echo'<div class="goldsmith-shop-fast-filters-ajax-content"></div>';
        }
	}
}
