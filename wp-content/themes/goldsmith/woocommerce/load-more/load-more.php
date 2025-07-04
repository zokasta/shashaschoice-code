<?php

/*************************************************
## Load More Button
*************************************************/
function goldsmith_load_more_button(){
    echo '<div class="row row-more goldsmith-more mt-30">
    <div class="col-12 nt-pagination goldsmith-justify-center">
    <div class="button goldsmith-load-more" data-title="'.esc_html__('Loading...','goldsmith').'">'.esc_html__('Load More','goldsmith').'</div>
    </div>
    </div>';
}

/*************************************************
## Infinite Pagination
*************************************************/
function goldsmith_infinite_scroll(){
    echo '<div class="row row-infinite goldsmith-more mt-30">
    <div class="col-12 nt-pagination goldsmith-justify-center">
    <div class="goldsmith-load-more" data-title="'.esc_html__('Loading...','goldsmith').'">'.esc_html__('Loading...','goldsmith').'</div>
    </div>
    </div>';
}

/*************************************************
## Load More CallBack
*************************************************/
add_action( 'wp_ajax_nopriv_goldsmith_shop_load_more', 'goldsmith_load_more_callback' );
add_action( 'wp_ajax_goldsmith_shop_load_more', 'goldsmith_load_more_callback' );
function goldsmith_load_more_callback() {

    $args = array(
        's'              => $_POST['s'],
        'post_type'      => 'product',
        'posts_per_page' => $_POST['per_page'],
        'post_status'    => 'publish',
        'paged'          => $_POST['current_page'] + 1
    );

    if ( $_POST['is_shop'] == 'yes' && '1' == goldsmith_settings( 'shop_custom_query_visibility', '0' ) ) {

        $scenario      = goldsmith_settings( 'shop_custom_query_scenario' );
        $cats          = goldsmith_settings( 'shop_custom_query_cats', null );
        $tags          = goldsmith_settings( 'shop_custom_query_tags', null );
        $attrs         = goldsmith_settings( 'shop_custom_query_attr', null );
        $order         = goldsmith_settings( 'shop_custom_query_order' );
        $orderby       = goldsmith_settings( 'shop_custom_query_orderby' );
        $cats_operator = 'include' == goldsmith_settings( 'shop_custom_query_cats_operator' ) ? 'IN' : 'NOT IN';
        $tags_operator = 'include' == goldsmith_settings( 'shop_custom_query_tags_operator' ) ? 'IN' : 'NOT IN';

        if ( !empty( $cats ) || !empty( $tags ) ) {
            $args['tax_query'] = array(
                'relation' => 'AND'
            );
        }

        if ( 'featured' == $scenario ) {

           $args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN'
            );

        } elseif ( 'on-sale' == $scenario ) {

            $args['meta_query'] = array(
                'relation' => 'OR',
                array( // Simple products type
                    'key'     => '_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'numeric'
                ),
                array( // Variable products type
                    'key'     => '_min_variation_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'numeric'
                )
            );

        } elseif ( 'best' == $scenario ) {

            $args['orderby']  = 'meta_value_num';
            $args['meta_key'] = 'total_sales';

        } elseif ( 'rated' == $scenario ) {

            $args['meta_key'] = '_wc_average_rating';
            $args['order']    = 'DESC';
            $args['orderby']  = 'meta_value_num';

        } elseif ( 'popularity' == $scenario ) {

            $args['meta_key'] = 'total_sales';
            $args['order']    = 'DESC';
            $args['orderby']  = 'meta_value_num';

        } else {

            $args['order'] = $order;
            $args['orderby'] = $orderby;
        }

        if ( !empty( $cats ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $cats,
                'operator' => $cats_operator
            );
        }

        if ( !empty( $tags ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_tag',
                'field'    => 'term_id',
                'terms'    => $tags,
                'operator' => $tags_operator
            );
        }

        if ( !empty( $attrs ) ) {
            foreach ( $attrs as $key ) {

                $attr_terms     = goldsmith_settings( 'shop_custom_query_attr_terms_'.$key );
                $terms_operator = 'include' == goldsmith_settings( 'shop_custom_query_attr_terms_operator_'.$key ) ? 'IN' : 'NOT IN';
                $attr_id        = wc_attribute_taxonomy_id_by_name( $key );
                $attr_info      = wc_get_attribute( $attr_id );

                if ( !empty( $attr_terms ) ) {
                    $args['tax_query'][] = array(
                        'taxonomy' => $attr_info->slug,
                        'field'    => 'term_id',
                        'terms'    => $attr_terms,
                        'operator' => $terms_operator
                    );
                }
            }
        }
    }

    // Price Slider
    if ( $_POST['min_price'] != null || $_POST['max_price'] != null ) {
        $args['meta_query'][] = wc_get_min_max_price_meta_query( array(
          'min_price' => $_POST['min_price'],
          'max_price' => $_POST['max_price']
        ));
    }

    // On Sale Products
    if ( isset( $_POST['on_sale'] ) && $_POST['on_sale'] == 'yes' ) {
        $args['post__in'] = wc_get_product_ids_on_sale();
    }

    // In Stock Products
    if ( isset( $_POST['in_stock'] ) && $_POST['in_stock'] == 'yes' ) {
        $args['meta_query'][] = array(
            'key'     => '_stock_status',
            'value'   => 'instock',
            'compare' => '='
        );
    }
    // Best Seller Products
    if ( isset( $_POST['best_seller'] ) ) {
        $args['meta_key'] = 'total_sales';
        $args['orderby']  = 'meta_value_num';
    }

    // Featured Products
    if ( isset( $_POST['featured'] ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured'
            )
        );
    }

    // Orderby
    $orderby_value = isset( $_POST['orderby'] ) ? wc_clean( (string) wp_unslash( $_POST['orderby'] ) ) : wc_clean( get_query_var( 'orderby' ) );

    if ( ! $orderby_value ) {
        if ( $_POST['is_search'] == 'yes' ) {
            $orderby_value = 'relevance';
        } else {
            $orderby_value = apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
        }
    }

    switch ( $orderby_value ) {
        case 'menu_order':
        $args['orderby'] = 'menu_order title';
        $args['order']   = 'ASC';
        break;
        case 'relevance':
        $args['orderby'] = 'relevance';
        $args['order']   = 'DESC';
        break;
        case 'price':
        add_filter( 'posts_clauses', array( WC()->query, 'order_by_price_asc_post_clauses' ) );
        break;
        case 'price-desc':
        add_filter( 'posts_clauses', array( WC()->query, 'order_by_price_desc_post_clauses' ) );
        break;
        case 'popularity':
        $args['meta_key'] = 'total_sales';
        add_filter( 'posts_clauses', array( WC()->query, 'order_by_popularity_post_clauses' ) );
        break;
        case 'rating':
        $args['meta_key'] = '_wc_average_rating';
        $args['order']    = 'DESC';
        $args['orderby']  = 'meta_value_num';
        add_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );
        break;
    }

    // Product Category Filter Widget on shop page
    if ( $_POST['filter_cat'] != null ) {
        if ( !empty( $_POST['filter_cat'] ) ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'id',
                'terms'    => explode( ',', $_POST['filter_cat'] )
            );
        }
    }

    // Product Category Page
    if ( $_POST['is_cat'] == 'yes' && $_POST['cat_id'] != null ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'id',
            'terms'    => $_POST['cat_id']
        );
    }

    // Product Brands Filter Widget on shop page
    if ( $_POST['filter_brand'] != null ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'goldsmith_product_brands',
                'field'    => 'id',
                'terms'    => explode( ',', $_POST['filter_brand'] )
            );
    }

    // Product Brands Page
    if ( $_POST['is_brand'] == '' && $_POST['brand_id'] != null ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'goldsmith_product_brands',
                'field'    => 'id',
                'terms'    => $_POST['brand_id']
            )
        );
    }

    // Product Filter By widget
    if ( isset( $_POST['layered_nav'] ) ) {
        $choosen_attributes = $_POST['layered_nav'];

        foreach ( $choosen_attributes as $taxonomy => $data ) {
            $args['tax_query'][] = array(
                'taxonomy'         => $taxonomy,
                'field'            => 'slug',
                'terms'            => $data['terms'],
                'operator'         => 'and' === $data['query_type'] ? 'AND' : 'IN',
                'include_children' => false
            );
        }
    }

    $type         = goldsmith_settings( 'shop_product_type', 2 );
    $catalog_mode = goldsmith_settings( 'woo_catalog_mode', '0' );
    $column       = '';

    if ( isset( $_POST['product_style'] ) && $_POST['product_style'] ) {
        $type = $_POST['product_style'];
    }
    if ( isset( $_POST['column'] ) && $_POST['column'] ) {
        $column = $_POST['column'];
    }

    $animation  = apply_filters( 'goldsmith_loop_product_animation', goldsmith_settings( 'shop_product_animation_type', 'fadeInUp' ) );
    $css_class  = 'goldsmith-loop-product';
    $css_class .= $column == '1' ? '' : ' animated '.$animation;

    //Loop
    $loop = new WP_Query( $args );
    if ( $loop->have_posts() ) {
        while ( $loop->have_posts() ) {
            $loop->the_post();
            global $product;

            // Ensure visibility.
            if ( !empty( $product ) && $product->is_visible() ) {
                ?>
                <div <?php wc_product_class( $css_class, $product ); ?> data-product-animation="<?php echo esc_attr( $animation ); ?>">
                    <?php
                    if ( '1' == $catalog_mode ) {
                        goldsmith_loop_product_type_catalog();
                    } elseif ( '1' == $column ) {
                        goldsmith_loop_product_type_list();
                    } elseif ( '2' == $type ) {
                        goldsmith_loop_product_type2();
                    } elseif ( '3' == $type ){
                        goldsmith_loop_product_type3();
                    } elseif ( 'woo' == $type ){
                        goldsmith_loop_product_type_woo_default();
                    } elseif ( 'custom' == $type ) {
                        goldsmith_loop_product_layout_manager();
                    } else {
                        goldsmith_loop_product_type1();
                    }
                    ?>
                </div>
                <?php
            }
        }
    }
    wp_reset_postdata();

    wp_die();
}
