<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Grid_Two extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-grid-two';
    }
    public function get_title() {
        return 'WC Products Masonry (N)';
    }
    public function get_icon() {
        return 'eicon-gallery-grid';
    }
    public function get_categories() {
        return [ 'goldsmith-woo' ];
    }
    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'cat', 'product', 'wc' ];
    }
    public function get_style_depends() {
        return [ 'goldsmith-product-box-style' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'post_query_scenario_section',
            [
                'label' => esc_html__( 'Query', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_responsive_control( 'col',
            [
                'label' => esc_html__( 'Column', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'default' => 4,
                'selectors' => ['{{WRAPPER}} .woo-products-grid-masonry .goldsmith-products.row' => 'grid-template-columns: repeat(calc({{SIZE}} + 1),1fr)']
            ]
        );
        $this->add_responsive_control( 'first_col',
            [
                'label' => esc_html__( 'Big Column Width', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'default' => 2,
                'selectors' => ['{{WRAPPER}} .woo-products-grid-masonry .goldsmith-products.row .product:not(.goldsmith-product-type-7):nth-child(1)' => 'grid-column: span {{SIZE}};grid-row: span {{SIZE}};']
            ]
        );
		$this->add_control('custom_imgs_height',
			[
				'label' => esc_html__( 'Custom Column Height', 'goldsmith' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => esc_html__( 'Default', 'goldsmith' ),
				'label_on' => esc_html__( 'Custom', 'goldsmith' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$this->start_popover();
        $this->add_responsive_control('first_col_height',
            [
                'label' => __( 'Big Column Height', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 200
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .woo-products-grid-masonry.custom-img-height .goldsmith-products.row .product:not(.goldsmith-product-type-7):nth-child(1) .product-link' => 'position:relative;display:block;padding-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .woo-products-grid-masonry.custom-img-height .goldsmith-products.row .product:not(.goldsmith-product-type-7):nth-child(1) .product-link img' => 'position:absolute;width:100%;height:100%;object-fit:cover;'
                ]
            ]
        );
        $this->add_responsive_control('other_col_height',
            [
                'label' => __( 'Other Column Height', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 200
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .woo-products-grid-masonry.custom-imgs-height .goldsmith-products.row .product:not(:nth-child(1)) .product-link' => 'position:relative;display:block;padding-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .woo-products-grid-masonry.custom-imgs-height .goldsmith-products.row .product:not(:nth-child(1)) .product-link img' => 'position:absolute;width:100%;height:100%;object-fit:cover;'
                ]
            ]
        );
        $this->end_popover();
        $this->add_control('column_gap',
            [
                'label' => __( 'Columns Gap', 'goldsmith' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .goldsmith-products.row>div' => 'padding: 0 {{SIZE}}px;margin-bottom: {{SIZE}}px;',
                    '{{WRAPPER}} .goldsmith-products.row' => 'margin: 0 -{{SIZE}}px -{{SIZE}}px -{{SIZE}}px;'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'defult' => 'woocommerce_thumbnail'
            ]
        );
        $this->add_control( 'limit',
            [
                'label' => esc_html__( 'Posts Per Page', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 11,
                'default' => 5
            ]
        );
        $this->add_control( 'scenario',
            [
                'label' => esc_html__( 'Select Scenerio', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'newest' => esc_html__( 'Newest', 'goldsmith' ),
                    'featured' => esc_html__( 'Featured', 'goldsmith' ),
                    'popularity' => esc_html__( 'Popularity', 'goldsmith' ),
                    'best' => esc_html__( 'Best Selling', 'goldsmith' ),
                    'attr' => esc_html__( 'Attribute Display', 'goldsmith' ),
                    'custom_cat' => esc_html__( 'Specific Categories', 'goldsmith' ),
                ],
                'default' => 'newest'
            ]
        );
        $this->add_control( 'hr0',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => [ 'scenario' => 'attr' ]
            ]
        );
        $this->add_control( 'attribute',
            [
                'label' => esc_html__( 'Select Attribute', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->goldsmith_woo_attributes(),
                'description' => 'Select Attribute(s)',
                'condition' => [ 'scenario' => 'attr' ]
            ]
        );
        $this->add_control( 'attr_terms',
            [
                'label' => esc_html__( 'Select Attribute Terms', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->goldsmith_woo_attributes_taxonomies(),
                'description' => 'Select Attribute(s)',
                'condition' => [ 'scenario' => 'attr' ]
            ]
        );
        $this->add_control( 'hr1',['type' => Controls_Manager::DIVIDER]);

        $this->add_control( 'cat_filter',
            [
                'label' => esc_html__( 'Filter Category', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->goldsmith_cpt_taxonomies('product_cat'),
                'description' => 'Select Category(s)',
            ]
        );
        $this->add_control( 'cat_operator',
            [
                'label' => esc_html__( 'Category Operator', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'AND' => esc_html__( 'display all of the chosen categories', 'goldsmith' ),
                    'IN' => esc_html__( 'display goldsmith-products within the chosen category', 'goldsmith' ),
                    'NOT IN' => esc_html__( 'display goldsmith-products that are not in the chosen category.', 'goldsmith' ),
                ],
                'default' => 'AND',
                'condition' => [ 'scenario' => 'custom_cat' ]
            ]
        );

        $this->add_control( 'hr2',['type' => Controls_Manager::DIVIDER]);

        $this->add_control( 'tag_filter',
            [
                'label' => esc_html__( 'Filter Tag(s)', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->goldsmith_cpt_taxonomies('product_tag','name'),
                'description' => 'Select Tag(s)',
            ]
        );
        $this->add_control( 'tag_operator',
            [
                'label' => esc_html__( 'Tags Operator', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'AND' => esc_html__( 'display all of the chosen tags', 'goldsmith' ),
                    'IN' => esc_html__( 'display goldsmith-products within the chosen tags', 'goldsmith' ),
                    'NOT IN' => esc_html__( 'display goldsmith-products that are not in the chosen tags.', 'goldsmith' ),
                ],
                'default' => 'AND',
            ]
        );

        $this->add_control( 'hr3',['type' => Controls_Manager::DIVIDER]);

        $this->add_control( 'order',
            [
                'label' => esc_html__( 'Select Order', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ASC' => esc_html__( 'Ascending', 'goldsmith' ),
                    'DESC' => esc_html__( 'Descending', 'goldsmith' )
                ],
                'default' => 'DESC'
            ]
        );
        $this->add_control( 'orderby',
            [
                'label' => esc_html__( 'Order By', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'id' => esc_html__( 'Post ID', 'goldsmith' ),
                    'menu_order' => esc_html__( 'Menu Order', 'goldsmith' ),
                    'popularity' => esc_html__( 'Popularity', 'goldsmith' ),
                    'rand' => esc_html__( 'Random', 'goldsmith' ),
                    'rating' => esc_html__( 'Rating', 'goldsmith' ),
                    'date' => esc_html__( 'Date', 'goldsmith' ),
                    'title' => esc_html__( 'Title', 'goldsmith' ),
                ],
                'default' => 'id',
                'condition' => [ 'scenario!' => 'custom_cat' ]
            ]
        );
        $this->add_control( 'cat_orderby',
            [
                'label' => esc_html__( 'Order By', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'id' => esc_html__( 'Post ID', 'goldsmith' ),
                    'menu_order' => esc_html__( 'Menu Order', 'goldsmith' ),
                    'name' => esc_html__( 'Name', 'goldsmith' ),
                    'slug' => esc_html__( 'Slug', 'goldsmith' ),
                ],
                'default' => 'id',
                'condition' => [ 'scenario' => 'custom_cat' ]
            ]
        );
        $this->add_control( 'show_cat_empty',
            [
                'label' => esc_html__( 'Show Empty Categories', 'goldsmith' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'goldsmith' ),
                'label_off' => esc_html__( 'No', 'goldsmith' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [ 'scenario' => 'custom_cat' ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('post_style_section',
            [
                'label' => esc_html__( 'Post Style', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control( 'post_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .woocommerce.goldsmith-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'post_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .woocommerce.goldsmith-product' => 'background-color: {{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'post_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .woocommerce.goldsmith-product'
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'post_item_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .woocommerce.goldsmith-product'
            ]
        );
        $this->add_control( 'title_heading',
            [
                'label' => esc_html__( 'TITLE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .woocommerce.goldsmith-product .goldsmith-product-name'
            ]
        );
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .woocommerce.goldsmith-product .goldsmith-product-name' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'price_heading',
            [
                'label' => esc_html__( 'PRICE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'price_color',
            [
                'label' => esc_html__( 'Price Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .woocommerce.goldsmith-product span.del > span' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'price_color2',
            [
                'label' => esc_html__( 'Price Color 2', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}}  div.product .woocommerce.goldsmith-product .goldsmith-price' => 'color: {{VALUE}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} div.product .woocommerce.goldsmith-product .goldsmith-price'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    public function thumb_size() {
        $settings = $this->get_settings_for_display();
        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'woocommerce_thumbnail';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size  = [ $sizew, $sizeh ];
        }
        return $size;
    }

    protected function render() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        $settings  = $this->get_settings_for_display();
        $elementid = $this->get_id();

        $limit        = 'limit="'.$settings['limit'].'"';
        $order        = ' order="'.$settings['order'].'"';
        $orderby      = ' orderby="'.$settings['orderby'].'"';
        $paginate     = ' paginate="false"';
        $hide_empty   = 'yes'== $settings['show_cat_empty'] ? ' hide_empty="0"' : '';
        $operator     = ' cat_operator="'.$settings['cat_operator'].'"';
        $tag_operator = ' tag_operator="'.$settings['tag_operator'].'"';
        $cat_orderby  = ' orderby="'.$settings['cat_orderby'].'"';
        $cat_filter   = is_array($settings['cat_filter']) ? ' category="'.implode(', ',$settings['cat_filter']).'"' : '';
        $hide_empty   = 'yes'== $settings['show_cat_empty'] ? ' hide_empty="0"' : '';
        $tag_filter   = is_array($settings['tag_filter']) ? ' tag="'.implode(', ',$settings['tag_filter']).'"' : '';
        $attr_filter  = is_array($settings['attribute']) ? ' attribute="'.implode(', ',$settings['attribute']).'"' : '';
        $attr_terms   = is_array($settings['attr_terms']) ? ' terms="'.implode(', ',$settings['attr_terms']).'"' : '';
        $class        = 'yes' == $settings['custom_imgs_height'] && !empty($settings['first_col_height']['size']) ? ' custom-img-height' : '';
        $class       .= 'yes' == $settings['custom_imgs_height'] && !empty($settings['other_col_height']['size']) ? ' custom-imgs-height' : '';
		add_filter( 'single_product_archive_thumbnail_size', [ $this, 'thumb_size' ] );
        echo '<div class="woo-products-grid-masonry grid-col-'.$settings['col'].$class.'">';
            if ( 'newest' == $settings['scenario'] ) {
                echo do_shortcode('[products '.$limit.$orderby.$order.$tag_filter.$paginate.' visibility="visible"]');
            } elseif ( 'featured' == $settings['scenario'] ) {
                echo do_shortcode('[products '.$limit.$orderby.$order.$tag_filter.$paginate.' visibility="featured"]');
            } elseif ( 'popularity' == $settings['scenario'] ) {
                echo do_shortcode('[products '.$limit.$order.$tag_filter.$paginate.' orderby="popularity" on_sale="true"]');
            } elseif ( 'best' == $settings['scenario'] ) {
                echo do_shortcode('[products '.$limit.$orderby.$order.$cat_filter.$operator.$hide_empty.$tag_filter.$paginate.' best_selling="true"]');
            } elseif ( 'custom_cat' == $settings['scenario'] ) {
                echo do_shortcode('[products '.$limit.$cat_orderby.$order.$cat_filter.$operator.$hide_empty.$tag_filter.$paginate.']');
            } elseif ( 'attr' == $settings['scenario'] ) {
                echo do_shortcode('[products '.$limit.$attr_filter.$attr_terms.$limit.$orderby.$order.$paginate.']');
            } else {
                echo do_shortcode('[products '.$limit.$orderby.$order.$tag_filter.$operator.$paginate.' visibility="visible"]');
            }
        echo '</div>';
        remove_filter( 'single_product_archive_thumbnail_size', [ $this, 'thumb_size' ] );
    }
}
