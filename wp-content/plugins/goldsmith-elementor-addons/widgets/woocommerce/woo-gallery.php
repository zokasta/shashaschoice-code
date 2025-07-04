<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Gallery extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-gallery';
    }
    public function get_title() {
        return 'WC Gallery (N)';
    }
    public function get_icon() {
        return 'eicon-gallery-grid';
    }
    public function get_categories() {
        return [ 'goldsmith-woo' ];
    }
    public function get_style_depends() {
        return [ 'goldsmith-product-box-style' ];
    }
    public function get_script_depends() {
        return [ 'imagesloaded','isotope' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'post_query_section',
            [
                'label' => esc_html__( 'Query', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'post_per_page',
            [
                'label' => esc_html__( 'Posts Per Page', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'default' => 20
            ]
        );
        $this->add_responsive_control( 'column',
            [
                'label' => esc_html__( 'Column Width', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 12,
                'step' => 1,
                'default' => 3,
                'selectors' => [ '{{WRAPPER}} .grid-item' => '-ms-flex: 0 0 calc(100% / {{VALUE}} );flex: 0 0 calc(100% / {{VALUE}} );max-width: calc(100% / {{VALUE}} );']
            ]
        );
        $this->add_control( 'all_text',
            [
                'label' => esc_html__( 'All Text', 'goldsmith' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'All Products',
                'label_block' => true
            ]
        );
        $this->add_control( 'category_filter_heading',
            [
                'label' => esc_html__( 'CATEGORY', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'category_exclude',
            [
                'label' => esc_html__( 'Category Exclude', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->goldsmith_cpt_taxonomies('product_cat'),
                'description' => 'Select Category(s) to Exclude'
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'defult' => 'woocommerce_thumbnail'
            ]
        );
        $this->add_control( 'post_filter_heading',
            [
                'label' => esc_html__( 'POST', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'post_exclude',
            [
                'label' => esc_html__( 'Exclude Post', 'goldsmith' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_posts_by_type('product'),
                'description' => 'Select Post(s) to Exclude'
            ]
        );
        $this->add_control( 'post_other_heading',
            [
                'label' => esc_html__( 'OTHER FILTER', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
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
                    'rand' => esc_html__( 'Random', 'goldsmith' ),
                    'date' => esc_html__( 'Date', 'goldsmith' ),
                    'title' => esc_html__( 'Title', 'goldsmith' )
                ],
                'default' => 'id'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('style_section',
            [
                'label' => esc_html__( 'STYLE', 'goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'filter_heading',
            [
                'label' => esc_html__( 'FILTER', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $this->add_responsive_control( 'alignment',
            [
                'label' => esc_html__( 'Filter Alignment', 'goldsmith' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'goldsmith' ),
                        'icon' => 'eicon-text-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'goldsmith' ),
                        'icon' => 'eicon-text-align-center'
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'goldsmith' ),
                        'icon' => 'eicon-text-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .gallery-menu' => 'text-align: {{VALUE}};']
            ]
        );
        $this->add_responsive_control( 'filter_bottom_space',
            [
                'label' => esc_html__( 'Filter bottom spacing', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 200,
                'step' => 1,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .gallery-menu' => 'margin-bottom: {{VALUE}}px']
            ]
        );
        $this->add_responsive_control( 'filter_space',
            [
                'label' => esc_html__( 'Space filter items', 'goldsmith' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .gallery-menu span + span' => 'margin-left: {{VALUE}}px',
                    '.rtl {{WRAPPER}} .gallery-menu span + span' => 'margin-right: {{VALUE}}px;'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .gallery-menu span'
            ]
        );
        $this->add_control( 'filter_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .gallery-menu span' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'filter_hvrcolor',
            [
                'label' => esc_html__( 'Active Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .gallery-menu span:hover, {{WRAPPER}} .gallery-menu span.active' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'box_heading',
            [
                'label' => esc_html__( 'POST BOX', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'post_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-product' => 'background-color: {{VALUE}};']
            ]
        );
        $this->add_responsive_control( 'box_padding',
            [
                'label' => esc_html__( 'Padding', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'post_border',
                'label' => esc_html__( 'Border', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-product',
            ]
        );
        $this->add_responsive_control( 'post_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'goldsmith' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .goldsmith-product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'post_hvrbordercolor',
            [
                'label' => esc_html__( 'Hover Border Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-product:hover' => 'border-color: {{VALUE}};']
            ]
        );
        $this->add_control( 'title_heading',
            [
                'label' => esc_html__( 'TITLE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'goldsmith' ),
                'selector' => '{{WRAPPER}} .goldsmith-product-name'
            ]
        );
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-product-name' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'price_heading',
            [
                'label' => esc_html__( 'PRICE', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $this->add_control( 'price_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-product .goldsmith-price,{{WRAPPER}} .woocommerce-variation-price .price span.del>span,{{WRAPPER}} .goldsmith-price span.del>span' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'labels_heading',
            [
                'label' => esc_html__( 'LABELS/DISCOUNT', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $this->add_control( 'labels_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-label' => 'background-color: {{VALUE}};']
            ]
        );
        $this->add_control( 'labels_color',
            [
                'label' => esc_html__( 'Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-label' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'buttons_heading',
            [
                'label' => esc_html__( 'BUTTONS', 'goldsmith' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $this->add_control( 'buttons_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-product-button' => 'background-color: {{VALUE}};']
            ]
        );
        $this->add_control( 'buttons_hvrbgcolor',
            [
                'label' => esc_html__( 'Hover/Active Background Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-product-button:hover,{{WRAPPER}} .goldsmith-product-button.added' => 'background-color: {{VALUE}};']
            ]
        );
        $this->add_control( 'buttons_color',
            [
                'label' => esc_html__( 'Icon Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-product-button svg' => 'fill: {{VALUE}};']
            ]
        );
        $this->add_control( 'buttons_hvrcolor',
            [
                'label' => esc_html__( 'Hover/Active Icon Color', 'goldsmith' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .goldsmith-product-button:hover svg,{{WRAPPER}} .goldsmith-product-button.added' => 'fill: {{VALUE}};']
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
        //global $wp_query;
        $settings  = $this->get_settings_for_display();
        $elementid = $this->get_id();

        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $settings['post_per_page'],
            'post__not_in'   => $settings['post_exclude'],
            'order'          => $settings['order'],
            'orderby'        => $settings['orderby'],
        );

        if ( $settings['category_exclude'] ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'id',
                    'terms'    => $settings['category_exclude'],
                    'operator' => 'NOT IN'
                )
            );
        }

        $product_cat_args = array (
            'taxonomy'   => 'product_cat',
            'order'      => $settings['order'],
            'orderby'    => $settings['orderby'],
            'hide_empty' => true,
            'parent'     => 0,
            'exclude'    => $settings['category_exclude']
        );

        $isedit = \Elementor\Plugin::$instance->editor->is_edit_mode() ? ' gallery_editor_'.$elementid : ' gallery_front';

        echo '<div class="gallery-products'.$isedit.'" data-isotope-options=\'{"itemSelector": ".grid-item","percentPosition": true,"masonry": {"columnWidth": ".grid-sizer"}}\'>';

            $cats = get_terms( $product_cat_args );

            if ( $cats > 1 ) {
                echo '<div class="gallery-menu">';
                    if ( $settings['all_text'] ) {
                        echo '<span class="gallery-menu-item active" data-filter="*">'.$settings['all_text'].'</span>';
                    }
                    foreach ($cats as $cat) {
                        $filter_item = strtolower( str_replace(' ', '-', $cat->name) );
                        echo '<span class="gallery-menu-item" data-filter=".'.$filter_item.'">'.$cat->name.'</span>';
                    }
                echo '</div>';
            }

            add_filter( 'single_product_archive_thumbnail_size', [ $this, 'thumb_size' ] );

            $the_query = new \WP_Query( $args );
            if( $the_query->have_posts() ) {

                echo '<div class="goldsmith-wc-gallery">';
                    echo '<div class="row">';
                        while ( $the_query->have_posts() ) {
                            $the_query->the_post();
                            global $product;
                            $terms = $product->get_category_ids();
                            $termname = array();
                            foreach ( $terms as $term ) {
                                $term = get_term_by( 'id', $term, 'product_cat' );
                                array_push( $termname, strtolower( str_replace(' ', '-', $term->name) ) );
                            }
                            echo '<div class="grid-item grid-sizer '.implode(' ', $termname).'">';
                                wc_get_template_part( 'content', 'product' );
                            echo '</div>';
                        }
                    echo '</div>';
                echo '</div>';
            }
        echo '</div>';
        wp_reset_postdata();
        remove_filter( 'single_product_archive_thumbnail_size', [ $this, 'thumb_size' ] );

        // Not in edit mode
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
            <script>

            jQuery(document).ready(function ($) {
                function goldsmithIsotopee() {
                    const $this     = $('.gallery_editor_<?php echo $elementid; ?>');
                    const gallery   = $this.find('.goldsmith-wc-gallery .row');
                    const filter    = $this.find('.gallery-menu');
                    const filterbtn = $this.find('.gallery-menu span');
                    //gallery.imagesLoaded(function () {
                        // init Isotope
                        var $grid = gallery.isotope({
                            itemSelector: '.grid-item',
                            percentPosition: true,
                            masonry: {columnWidth: '.grid-sizer'}
                        });

                        // filter items on button click
                        filter.on('click', 'span', function () {
                            var filterValue = $(this).attr('data-filter');
                            $grid.isotope({ filter: filterValue });
                        });
                    //});
                    //for menu active class
                    filterbtn.on('click', function (event) {
                        $(this).siblings('.active').removeClass('active');
                        $(this).addClass('active');
                        event.preventDefault();
                    });
                    setTimeout(function(){$grid.isotope('layout')}, 3000);
                }
                goldsmithIsotopee();
            });

            </script>
            <?php
        }
    }
}
