<?php
/**
* Goldsmith Quick View
*/
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
if ( ! class_exists( 'Goldsmith_QuickView' ) ) {
    class Goldsmith_QuickView
    {
        private static $instance = null;

        function __construct()
        {
            // frontend scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            // frontend template
            add_action( 'wp_ajax_goldsmith_quickview', array( $this, 'quick_view_template' ) );
            add_action( 'wp_ajax_nopriv_goldsmith_quickview', array( $this, 'quick_view_template' ) );
        }

        public function enqueue_scripts()
        {
            wp_enqueue_script( 'magnific' );
            wp_enqueue_style( 'goldsmith-wc-quick-view' );
            wp_enqueue_script( 'goldsmith-quickview', GOLDSMITH_PLUGIN_URL . 'assets/front/js/quickview/quickview.js', array( 'jquery' ), GOLDSMITH_PLUGIN_VERSION, true );
        }

        public function quick_view_template()
        {
            global $post, $product;
            $product_id = absint( $_GET['product_id'] );
            $product    = wc_get_product( $product_id );

            if ( $product ) {

                $post = get_post( $product_id );
                setup_postdata( $post );

                $images = $product->get_gallery_image_ids();
                $size   = apply_filters( 'goldsmith_product_thumb_size', 'woocommerce_thumbnail' );

                ?>
                <div id="product-<?php echo $product_id; ?>" <?php wc_product_class( 'goldsmith-quickview-wrapper single-content zoom-anim-dialog', $product ); ?>>
                    <div class="container-full goldsmith-container-full">
                        <div class="row">

                            <div class="col-lg-7">
                                <div class="goldsmith-swiper-slider-wrapper">
                                    <?php if ( !empty( $images ) ) { ?>
                                    <div class="goldsmith-quickview-main goldsmith-swiper-main nav-vertical-center">
                                        <?php goldsmith_single_product_labels(); ?>
                                        <div class="goldsmith-swiper-wrapper">
                                            <?php
                                            echo '<div class="swiper-slide first-slide">'.get_the_post_thumbnail( $product->get_id(), $size ).'</div>';
                                            foreach( $images as $image ) {
                                                echo '<div class="swiper-slide"><img src="'.wp_get_attachment_image_url($image,$size).'" alt="'.esc_html( $product->get_name() ).'"/></div>';
                                            }
                                            ?>
                                        </div>
                                        <div class="goldsmith-swiper-prev goldsmith-swiper-btn goldsmith-nav-bg"></div>
                                        <div class="goldsmith-swiper-next goldsmith-swiper-btn goldsmith-nav-bg"></div>
                                    </div>

                                    <div class="goldsmith-quickview-thumbnails goldsmith-swiper-thumbnails">
                                        <div class="goldsmith-swiper-wrapper"></div>
                                    </div>
                                <?php } else { ?>
                                    <?php echo get_the_post_thumbnail( $product->get_id(), $size ); ?>
                                <?php } ?>
                                </div>
                            </div>


                            <div class="col-lg-5">
                                <div class="goldsmith-quickview-product-details goldsmith-product-summary">
                                    <?php the_title( '<h4 class="goldsmith-product-title">', '</h4>' );?>
                                    <?php woocommerce_template_single_price(); ?>
                                    <?php if ( has_excerpt() ) { ?>
                                        <div class="goldsmith-summary-item"><?php the_excerpt(); ?></div>
                                    <?php } ?>
                                    <?php woocommerce_template_single_add_to_cart($product); ?>
                                    <div class="goldsmith-summary-item">
                                        <?php woocommerce_template_single_meta(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
                wp_reset_postdata();
            }
            die();
        }

        public function get_product_attributes( $product )
        {
            $product_attributes = array();

            // Display weight and dimensions before attribute list.
            $display_dimensions = apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() );

            if ( $display_dimensions && $product->has_weight() )
            {
                $product_attributes['weight'] = array(
                    'label' => __( 'Weight', 'goldsmith' ),
                    'value' => wc_format_weight( $product->get_weight() ),
                );
            }

            if ( $display_dimensions && $product->has_dimensions() ) {
                $product_attributes['dimensions'] = array(
                    'label' => __( 'Dimensions', 'goldsmith' ),
                    'value' => wc_format_dimensions( $product->get_dimensions( false ) ),
                );
            }

            // Add product attributes to list.
            $attributes = array_filter( $product->get_attributes(), 'wc_attributes_array_filter_visible' );
            if ( !empty( $attributes ) ) {
                echo '<ul class="goldsmith-attr-list">';
                foreach ( $attributes as $attribute ) {
                    $values = array();

                    if ( $attribute->is_taxonomy() ) {
                        $attribute_taxonomy = $attribute->get_taxonomy_object();
                        $attribute_values   = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

                        foreach ( $attribute_values as $attribute_value ) {
                            $value_name = esc_html( $attribute_value->name );

                            if ( $attribute_taxonomy->attribute_public ) {
                                $values[] = '<a href="' . esc_url( get_term_link( $attribute_value->term_id, $attribute->get_name() ) ) . '" rel="tag">' . $value_name . '</a>';
                            } else {
                                $values[] = $value_name;
                            }
                        }
                    }

                    $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ] = array(
                        'label' => wc_attribute_label( $attribute->get_name() ),
                        'value' => apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values )
                    );
                    $label = $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ]['label'];
                    $value = $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ]['value'];
                    echo '<li class="goldsmith-attr-item">';
                    echo !empty( $label ) ? '<span class="goldsmith-attr-label">'.$label.': </span>' : '';
                    echo !empty( $value ) ? '<div class="goldsmith-attr-value">'.$value.'</div>' : '';
                    echo '</li>';
                }
                echo '</ul>';
            }
        }

        public static function get_instance()
        {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }
    }
    Goldsmith_QuickView::get_instance();
}
