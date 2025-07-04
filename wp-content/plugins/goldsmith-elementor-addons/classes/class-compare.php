<?php


if ( ! class_exists( 'Goldsmith_Compare' ) && class_exists( 'WC_Product' ) ) {
    class Goldsmith_Compare {

        private static $instance = null;

        function __construct() {
            // enqueue scripts
            //add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            // after user login
            //add_action( 'wp_login', array( $this, 'set_user_cookie' ), 10, 2 );

            // load content to side panel
            add_action( 'goldsmith_side_panel_header_after_cart', array( $this, 'side_panel_header' ), 20 );
            //add_action( 'goldsmith_side_panel_content_after_cart', array( $this, 'side_panel_content' ) );
            //add_action( 'goldsmith_mobile_panel_content_after_cart', array( $this, 'side_mobile_panel_content' ) );

            // ajax load compare table
            //add_action( 'wp_ajax_goldsmith_add_compare', array( $this, 'load_table' ) );
            //add_action( 'wp_ajax_nopriv_goldsmith_add_compare', array( $this, 'load_table' ) );
        }

        public function enqueue_scripts() {
            wp_enqueue_script( 'goldsmith-compare', GOLDSMITH_PLUGIN_URL . 'assets/front/js/compare/compare.js', array( 'jquery' ), GOLDSMITH_PLUGIN_VERSION, true );
            wp_localize_script( 'goldsmith-compare', 'compare_vars', array(
                'ajaxurl'  => admin_url( 'admin-ajax.php' ),
                'limit'    => 100,
                'notice'   => esc_html__( 'You can add a maximum of {max_limit} products to the compare table.', 'goldsmith' ),
                'empty'    => esc_html__( 'There are no products on the compare!', 'goldsmith' ),
                'count'    => self::get_count(),
                'nonce'    => wp_create_nonce( 'goldsmith-compare-nonce' ),
                'user_id'  => md5( 'goldsmith' . get_current_user_id() ),
                'products' => self::get_products_ids()
            ));
        }

        public function set_user_cookie( $user_login, $user ) {
            if ( isset( $user->data->ID ) ) {
                $user_products = get_user_meta( $user->data->ID, 'goldsmith_products', true );
                $user_fields   = get_user_meta( $user->data->ID, 'goldsmith_fields', true );

                if ( ! empty( $user_products ) ) {
                    setcookie( 'goldsmith_products_' . md5( 'goldsmith' . $user->data->ID ), $user_products, time() + 604800, '/' );
                }

                if ( ! empty( $user_fields ) ) {
                    setcookie( 'goldsmith_fields_' . md5( 'goldsmith' . $user->data->ID ), $user_fields, time() + 604800, '/' );
                }
            }
        }

        public function load_table() {
            self::get_compare();
            wp_die();
        }

        public function side_panel_header()
        {
            ?>
            <div class="panel-header-compare panel-header-btn open-compare-btn" data-name="compare">
                <span class="goldsmith-compare-count goldsmith-wc-count"><?php echo esc_html( self::get_count() ); ?></span>
                <?php echo goldsmith_svg_lists( 'compare', 'goldsmith-svg-icon' ); ?>
            </div>
            <?php
        }

        public function side_mobile_panel_content()
        {
            $has_product = self::get_count() ? ' has-product' : '';
            $url = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $btn_class = is_shop() ? ' goldsmith-panel-close' : '';
            ?>
            <div class="compare-area action-content<?php echo esc_attr( $has_product ); ?>" data-target-name="compare" data-compare-count="<?php echo esc_attr( self::get_count() ); ?>">
                <div class="compare-content">
                    <?php if ( function_exists('goldsmith_settings') && '' != goldsmith_settings('sidebar_panel_compare_custom_title') ) { ?>
                        <span class="panel-top-title"><?php echo esc_html( goldsmith_settings('sidebar_panel_compare_custom_title') ); ?></span>
                    <?php } else { ?>
                        <span class="panel-top-title"><?php esc_html_e( 'Your compared products', 'goldsmith' ); ?></span>
                    <?php } ?>

                    <div class="goldsmith-panel-content-items goldsmith-compare-content-items goldsmith-perfect-scrollbar">
                        <?php self::get_compare(); ?>
                    </div>
                    <div class="goldsmith-panel-content-notice goldsmith-compare-content-notice">
                        <div class="goldsmith-empty-content">
                            <?php echo goldsmith_svg_lists( 'compare', 'goldsmith-big-svg-icon' ); ?>
                            <div class="goldsmith-small-title"><?php echo esc_html_e( 'No product is added to the compare list!', 'goldsmith' ); ?></div>
                            <a class="goldsmith-btn-small mt-10<?php echo esc_attr( $btn_class ); ?>" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Start Shopping', 'goldsmith' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        public function side_panel_content()
        {
            $has_product = self::get_count() ? ' has-product' : '';
            $url = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $btn_class = is_shop() ? ' goldsmith-panel-close' : '';
            ?>
            <div class="compare-area panel-content-item<?php echo esc_attr( $has_product ); ?>" data-name="compare" data-compare-count="<?php echo esc_attr( self::get_count() ); ?>">
                <div class="compare-content">
                    <?php if ( function_exists('goldsmith_settings') && '' != goldsmith_settings('sidebar_panel_compare_custom_title') ) { ?>
                        <span class="panel-top-title"><?php echo esc_html( goldsmith_settings('sidebar_panel_compare_custom_title') ); ?></span>
                    <?php } else { ?>
                        <span class="panel-top-title"><?php esc_html_e( 'Your compared products', 'goldsmith' ); ?></span>
                    <?php } ?>
                    <div class="goldsmith-panel-content-items goldsmith-compare-content-items goldsmith-perfect-scrollbar">
                        <?php self::get_compare(); ?>
                    </div>
                    <div class="goldsmith-panel-content-notice goldsmith-compare-content-notice goldsmith-empty-content">
                        <?php echo goldsmith_svg_lists( 'compare', 'goldsmith-big-svg-icon' ); ?>
                        <div class="goldsmith-small-title"><?php echo esc_html_e( 'No product is added to the compare list!', 'goldsmith' ); ?></div>
                        <a class="goldsmith-btn-small mt-10<?php echo esc_attr( $btn_class ); ?>" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Start Shopping', 'goldsmith' ); ?></a>
                    </div>
                </div>
            </div>
            <?php
        }

        public static function get_cookie()
        {
            $products = array();
            if ( isset( $_POST['products'] ) && ( $_POST['products'] !== '' ) ) {
                $products = explode( ',', $_POST['products'] );
            } else {
                $cookie = 'goldsmith_products_' . md5( 'goldsmith' . get_current_user_id() );

                if ( isset( $_COOKIE[ $cookie ] ) && ! empty( $_COOKIE[ $cookie ] ) ) {
                    if ( is_user_logged_in() ) {
                        update_user_meta( get_current_user_id(), 'goldsmith_products', $_COOKIE[ $cookie ] );
                    }

                    $products = explode( ',', $_COOKIE[ $cookie ] );
                }
            }
            return $products;
        }

        public static function get_products_ids()
        {
            $ids       = array();
            $products  = self::get_cookie();

            if ( is_array( $products ) && ( count( $products ) > 0 ) ) {

                foreach ( $products as $product ) {
                    $ids[] = $product;
                }
                return $ids;
            }
        }

        public static function get_compare()
        {
            // get items
            $products_data = array();
            $products      = self::get_cookie();

            if ( is_array( $products ) && ( count( $products ) > 0 ) ) {

                foreach ( $products as $p ) {
                    $product = wc_get_product( $p );

                    if ( ! $product ) {
                        continue;
                    }
                    $products_data[$p]['id']    = $product->get_id();
                    $products_data[$p]['link']  = $product->get_permalink();
                    $products_data[$p]['name']  = $product->get_name();
                    $products_data[$p]['image'] = $product->get_image( 'goldsmith-panel', array( 'class' => 'compare-thumb' ) );
                    $products_data[$p]['price'] = $product->get_price_html();
                    $products_data[$p]['stock'] = $product->is_in_stock() ? esc_html__( 'In stock', 'goldsmith' ) : esc_html__( 'Out of stock', 'goldsmith' );
                }

                foreach ( $products_data as $cproduct ) {
                    $imgurl = get_the_post_thumbnail_url($cproduct['id'],'goldsmith-panel');
                    $imgsrc = $imgurl ? $imgurl : wc_placeholder_img_src();
                    $img    = '<img width="80" height="80" src="'.$imgsrc.'" alt="'.esc_html( $cproduct['name'] ).'"/>';
                    ?>
                    <div class="goldsmith-content-item goldsmith-compare-item" data-id="<?php echo esc_attr( $cproduct['id'] ); ?>">
                        <div class="goldsmith-content-item-inner">
                            <?php printf( '<a href="%s">%s</a>',esc_url( $cproduct['link'] ), $img ); ?>
                            <div class="goldsmith-content-info">
                                <div class="goldsmith-small-title">
                                    <a class="goldsmith-content-link" data-id="<?php echo esc_attr( $cproduct['id'] ); ?>" href="<?php echo esc_url( $cproduct['link'] ); ?>">
                                        <span class="product-name"><?php echo esc_html( $cproduct['name'] ); ?></span>
                                        <span>
                                            <?php if ( $cproduct['price'] ) { ?>
                                                <span class="product-price goldsmith-price"><?php printf('%s', $cproduct['price'] ); ?></span> /
                                            <?php } ?>
                                            <span class="product-stock goldsmith-stock"> <?php echo esc_html( $cproduct['stock'] ); ?></span>
                                        </span>
                                    </a>
                                </div>
                                <?php echo do_shortcode('[add_to_cart style="" show_price="false" id="'.$cproduct['id'].'"]'); ?>
                            </div>
                            <div class="goldsmith-content-del-icon goldsmith-compare-del-icon"><?php echo goldsmith_svg_lists( 'trash', 'goldsmith-svg-icon mini-icon' ); ?></div>
                        </div>
                    </div>
                    <?php
                }
            }
        }

        public static function compare_shortcode_list( $atts )
        {
            return '<div class="goldsmith_list goldsmith_page">' . self::get_compare() . '</div>';
        }

        public static function get_count()
        {
            $products = array();

            if ( isset( $_POST['products'] ) && ( $_POST['products'] !== '' ) ) {
                $products = explode( ',', $_POST['products'] );
            } else {
                $cookie = 'goldsmith_products_' . md5( 'goldsmith' . get_current_user_id() );
                if ( isset( $_COOKIE[ $cookie ] ) && ! empty( $_COOKIE[ $cookie ] ) ) {
                    $products = explode( ',', $_COOKIE[ $cookie ] );
                }
            }

            return count( $products );
        }

        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }
    }
    Goldsmith_Compare::get_instance();
}
