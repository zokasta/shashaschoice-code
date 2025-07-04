<?php

if ( ! class_exists( 'GoldsmithWooCartNotice' ) && class_exists( 'WC_Product' ) ) {
    class GoldsmithWooCartNotice {
        function __construct() {
            // frontend scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'goldsmithcn_enqueue_scripts' ) );
            // add the time
            add_action( 'woocommerce_add_to_cart', array( $this, 'goldsmithcn_add_to_cart' ), 10 );
            // fragments
            add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'goldsmithcn_cart_fragment' ) );
            // footer
            add_action( 'wp_footer', array( $this, 'goldsmithcn_footer' ) );
        }
        function goldsmithcn_enqueue_scripts() {

            wp_enqueue_script( 'goldsmithcn-frontend', GOLDSMITH_PLUGIN_URL . 'widgets/woocommerce/popup-cart-notices/script.js', array( 'jquery' ), GOLDSMITH_PLUGIN_VERSION, true );
        }

        function goldsmithcn_get_product() {
            $items = WC()->cart->get_cart();
            $html  = '<div class="goldsmith-popup-notices">';

            if ( count( $items ) > 0 ) {
                foreach ( $items as $key => $item ) {
                    if ( ! isset( $item['goldsmith_popup_notices_time'] ) ) {
                        $items[ $key ]['goldsmith_popup_notices_time'] = time() - 10000;
                    }
                }
                
                array_multisort( array_column( $items, 'goldsmith_popup_notices_time' ), SORT_ASC, $items );
                $goldsmith_product = end( $items )['data'];

                if ( $goldsmith_product && ( $goldsmith_product_id = $goldsmith_product->get_id() ) ) {
                    if ( ! in_array( $goldsmith_product_id, apply_filters( 'goldsmith_exclude_ids', array( 0 ) ), true ) ) {
                        $html .= '<div class="goldsmith-text">' . sprintf( esc_html__( '%s was added to the cart.', 'goldsmith' ), '<a href="' . $goldsmith_product->get_permalink() . '" target="_blank">' . $goldsmith_product->get_name() . '</a>' ) . '</div>';
                        $cart_content_data = '<span class="goldsmith-popup-cart-content-total">' . wp_kses_post( WC()->cart->get_cart_subtotal() ) . '</span> <span class="goldsmith-cart-content-count">' . wp_kses_data( sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'goldsmith' ), WC()->cart->get_cart_contents_count() ) ) . '</span>';
                        $cart_content = sprintf( esc_html__( 'Your cart: %s', 'goldsmith' ), $cart_content_data );
                        $html .= '<div class="goldsmith-cart-content">' . $cart_content . '</div>';
                    }
                }
            }

            $html .= '</div>';

            return $html;
        }

        function goldsmithcn_add_to_cart( $cart_item_key ) {

            WC()->cart->cart_contents[ $cart_item_key ]['goldsmith_popup_notices_time'] = time();

        }

        function goldsmithcn_cart_fragment( $fragments ) {
            $fragments['.goldsmith-popup-notices'] = $this->goldsmithcn_get_product();

            return $fragments;
        }

        function goldsmithcn_footer() {
            echo '<div class="goldsmith-popup-notices"></div>';
        }
    }
    new GoldsmithWooCartNotice();
}
