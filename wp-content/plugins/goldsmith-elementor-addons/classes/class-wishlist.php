<?php
/**
* Goldsmith_Wishlist
*/
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
if ( ! class_exists( 'Goldsmith_Wishlist' ) ) {
    class Goldsmith_Wishlist {
        private static $instance = null;

        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public static function get_ot_settings($settingid='',$defaultopt='') {
            $settings = get_option('goldsmith');

            if ( $settingid && isset($settings[$settingid]) ) {
                return $settings[$settingid];
            }
            return $defaultopt;
        }

        public static function get_wishlist_page_id() {
            $settings = get_option('goldsmith');

            if ( isset($settings['wishlist_page_id']) && '' != $settings['wishlist_page_id'] ) {
                return $settings['wishlist_page_id'];
            }
        }

        function __construct()
        {
            if ( ! class_exists('WooCommerce') ) {
                return;
            }
            $settings = get_option('goldsmith');
            // add query var
            add_filter( 'query_vars', [ $this, 'query_vars' ], 1 );
            add_action( 'init', [ $this, 'init' ] );

            // my account
            if (  '1' == self::get_ot_settings('wishlist_page_myaccount') ) {
                add_filter( 'woocommerce_account_menu_items', [ $this, 'account_items' ], 99 );
                add_action( 'woocommerce_account_wishlist_endpoint', [ $this, 'account_endpoint' ], 99 );
            }

            // frontend scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
            // print template
            add_action( 'goldsmith_side_panel_header_after_cart', array( $this, 'template_header' ), 10 );
            add_action( 'goldsmith_side_panel_content_after_cart', array( $this, 'template_content' ) );
            add_action( 'goldsmith_mobile_panel_content_after_cart', array( $this, 'template_mobile_header_content' ) );
            // add wishlist
            add_action( 'wp_ajax_goldsmith_wishlist_add', array( $this, 'wishlist_add' ) );
            add_action( 'wp_ajax_nopriv_goldsmith_wishlist_add', array( $this, 'wishlist_add' ) );
            // remove wishlist
            add_action( 'wp_ajax_goldsmith_wishlist_remove', array( $this, 'wishlist_remove' ) );
            add_action( 'wp_ajax_nopriv_goldsmith_wishlist_remove', array( $this, 'wishlist_remove' ) );

            // clear all wishlist
            add_action( 'wp_ajax_goldsmith_wishlist_clear', array( $this, 'wishlist_clear' ) );
            add_action( 'wp_ajax_nopriv_goldsmith_wishlist_clear', array( $this, 'wishlist_clear' ) );

            // user login & logout
            add_action( 'wp_login', array( $this, 'wishlist_wp_login' ), 10, 2 );
            add_action( 'wp_logout', array( $this, 'wishlist_wp_logout' ), 10, 1 );

            add_shortcode( 'goldsmith_wishlist', [ $this, 'template_wishlist_page' ] );
        }

        function wp_enqueue_scripts()
        {
            // localize
            $is_login = ! is_user_logged_in() && '1' == self::get_ot_settings('wishlist_disable_unauthenticated') ? 'yes' : 'no';
            wp_enqueue_script( 'goldsmith-wishlist', GOLDSMITH_PLUGIN_URL . 'assets/front/js/wishlist/wishlist.js', array( 'jquery' ), GOLDSMITH_PLUGIN_VERSION, true );
            wp_localize_script( 'goldsmith-wishlist', 'wishlist_vars', array(
                    'ajax_url'          => admin_url( 'admin-ajax.php' ),
                    'count'             => $this->get_count(),
                    'max_count'         => self::get_ot_settings('wishlist_max_count'),
                    'max_message'       => esc_html__( 'Sorry, you\'ve reached the max product limit.You can\'t add more products.', 'goldsmith' ),
                    'is_login'          => $is_login,
                    'login_mesage'      => esc_html__( 'Please log in to use the wishlist!', 'goldsmith' ),
                    'already'           => esc_html__( 'Already in the wishlist!', 'goldsmith' ),
                    'products'          => $this->get_products(),
                    'nonce'             => wp_create_nonce( 'goldsmith-wishlist-nonce' ),
                    'user_id'           => md5( 'goldsmith_wishlist_' . get_current_user_id() ),
                    'btn_action'        => self::get_ot_settings('wishlist_btn_action','panel'),
                    'header_btn_action' => self::get_ot_settings('header_wishlist_btn_action','panel'),
                    'wishlist_page'     => get_the_ID() == self::get_wishlist_page_id() ? 'yes' : 'no'
                )
            );
        }

        function query_vars( $vars ) {
            $vars[] = 'goldsmithwl_id';

            return $vars;
        }
        function init() {
            // get key
            $key = isset( $_COOKIE['goldsmith_wishlist_key'] ) ? sanitize_text_field( $_COOKIE['goldsmith_wishlist_key'] ) : '#';

            // rewrite
            $page_id = self::get_wishlist_page_id();
            if ( $page_id ) {
                $page_slug = get_post_field( 'post_name', $page_id );

                if ( $page_slug !== '' ) {
                    add_rewrite_rule( '^' . $page_slug . '/([\w]+)/?', 'index.php?page_id=' . $page_id . '&goldsmithwl_id=$matches[1]', 'top' );
                    add_rewrite_rule( '(.*?)/' . $page_slug . '/([\w]+)/?', 'index.php?page_id=' . $page_id . '&goldsmithwl_id=$matches[2]', 'top' );
                }
            }

            // my account page
            if ( '1' == self::get_ot_settings('wishlist_page_myaccount') ) {
                add_rewrite_endpoint( 'wishlist', EP_PAGES );
            }
        }

        function account_items( $items ) {
            if ( isset( $items['customer-logout'] ) ) {
                $logout = $items['customer-logout'];
                unset( $items['customer-logout'] );
            } else {
                $logout = '';
            }

            if ( ! isset( $items['wishlist'] ) ) {
                $items['wishlist'] = apply_filters( 'goldsmithwl_myaccount_wishlist_label', esc_html__( 'Wishlist', 'goldsmith' ) );
            }

            if ( $logout ) {
                $items['customer-logout'] = $logout;
            }

            return $items;
        }

        function account_endpoint() {
            echo apply_filters( 'goldsmithwl_myaccount_content', do_shortcode( '[goldsmith_wishlist]' ) );
        }

        function template_header()
        {
            ?>
            <div class="panel-header-wishlist panel-header-btn" data-name="wishlist">
                <span class="goldsmith-wishlist-count goldsmith-wc-count"><?php echo esc_html( $this->get_count() ); ?></span>
                <?php echo goldsmith_svg_lists( 'love', 'goldsmith-svg-icon' ); ?>
            </div>
            <?php
        }

        function template_wishlist_page()
        {
            $key = self::get_key();
            if ( get_query_var( 'goldsmithwl_id' ) ) {
                $key = get_query_var( 'goldsmithwl_id' );
            } else {
                $key = self::get_key();
            }

            $share_url = self::get_url( $key, true );

            $html= '';
            $html .='<div class="wishlist-content wishlist-all-items">';
                if ( $this->get_count() ) {
                    $html .='<div class="goldsmith-wishlist-items">';
                        ob_start();
                        $this->print_wishlist();
                    $html .= ob_get_clean().'</div>';
                    if ( $share_url && '1' == self::get_ot_settings('wishlist_page_copy') ) {
                        $html .='<div class="goldsmith-wishlist-copy">';
                            $html .='<span class="goldsmith-wishlist-copy-label">'.esc_html__( 'Wishlist link:', 'goldsmith' ).'</span> ';
                            $html .='<span class="goldsmith-wishlist_copy_url"><input id="goldsmith-wishlist_copy_url" type="url" value="'.esc_attr( $share_url ).'" readonly/></span>';
                            $html .=' <span class="goldsmith-wishlist_copy_btn"><input id="goldsmith-wishlist_copy_btn" type="button" value="'.esc_attr__( 'Copy', 'goldsmith' ).'"/></span>';
                        $html .='</div>';
                    }
                } else {
                    $html .='<div class="goldsmith-panel-content-notice goldsmith-wishlist-content-notice goldsmith-empty-content">';
                        $html .= goldsmith_svg_lists( 'love', 'goldsmith-big-svg-icon' );
                        $html .='<div class="goldsmith-small-title">'.esc_html__( 'There are no products on the wishlist!', 'goldsmith' ).'</div>';
                        $html .='<a class="goldsmith-btn-small mt-10" href="'.esc_url( wc_get_page_permalink( 'shop' ) ).'">'.esc_html__( 'Start Shopping', 'goldsmith' ).'</a>';
                    $html .='</div>';
                }
            $html .='</div>';
            return $html;
        }

        function template_mobile_header_content()
        {
            $url        = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $btn_class  = is_shop() ? ' goldsmith-panel-close' : '';
            $key        = self::get_key();
            $products   = get_option( 'goldsmith_wishlist_' . $key );
            $count      = is_array( $products ) && ( count( $products ) > 0 ) ? count( $products ) : '0';
            $page_id    = self::get_wishlist_page_id();
            $page_link  = $page_id ? get_page_link($page_id) : wc_get_page_permalink( 'shop' );
            $show_clear = self::get_ot_settings('sidebar_panel_wishlist_clear_btn','0');
            $clear_btn  = '1' == $show_clear ? '<span class="clear-all-wishlist">'.esc_html__( 'Clear All', 'goldsmith' ).'</span>' : '';
            $has_clear  = '1' == $show_clear ? ' has-clear-btn' : '';
            ?>
            <div class="wishlist-area action-content" data-target-name="wishlist" data-wishlist-count="<?php echo esc_attr( $count ); ?>">
                <div class="wishlist-content">
                    <?php if ( '' != self::get_ot_settings('sidebar_panel_wishlist_custom_title') ) { ?>
                        <span class="panel-top-title<?php echo esc_attr( $has_clear ); ?>"><?php echo esc_html( self::get_ot_settings('sidebar_panel_wishlist_custom_title') ); ?><?php printf( '%s',$clear_btn ); ?></span>
                    <?php } else { ?>
                        <span class="panel-top-title<?php echo esc_attr( $has_clear ); ?>"><?php esc_html_e( 'Your Wishlist', 'goldsmith' ); ?><?php printf( '%s',$clear_btn ); ?></span>
                    <?php } ?>
                    <div class="goldsmith-panel-content-items goldsmith-wishlist-content-items goldsmith-perfect-scrollbar">
                        <?php $this->print_wishlist(); ?>
                    </div>
                    <div class="goldsmith-panel-content-notice goldsmith-wishlist-content-notice goldsmith-empty-content">
                        <?php if ( !$this->get_count() ) { ?>
                            <?php echo goldsmith_svg_lists( 'love', 'goldsmith-big-svg-icon' ); ?>
                            <div class="goldsmith-small-title"><?php esc_html_e( 'There are no products on the wishlist!', 'goldsmith' ); ?></div>
                            <a class="goldsmith-btn-small mt-10<?php echo esc_attr( $btn_class ); ?>" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Start Shopping', 'goldsmith' ); ?></a>
                        <?php } ?>
                    </div>
                    <?php if ( '' != self::get_wishlist_page_id() ) { ?>
                        <a class="goldsmith-btn goldsmith-btn-dark wishlist-page-link" href="<?php echo esc_url( $page_link ); ?>"><?php esc_html_e( 'Open Wishlist Page', 'goldsmith' ); ?></a>
                    <?php } ?>
                </div>
            </div>
            <?php
        }

        function template_content()
        {
            $url        = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $btn_class  = is_shop() ? ' goldsmith-panel-close' : '';
            $key        = self::get_key();
            $products   = get_option( 'goldsmith_wishlist_' . $key );
            $count      = is_array( $products ) && ( count( $products ) > 0 ) ? count( $products ) : '0';
            $page_id    = self::get_wishlist_page_id();
            $page_link  = $page_id ? get_page_link($page_id) : wc_get_page_permalink( 'shop' );
            $show_clear = self::get_ot_settings('sidebar_panel_wishlist_clear_btn','0');
            $clear_btn  = '1' == $show_clear ? '<span class="clear-all-wishlist">'.esc_html__( 'Clear All', 'goldsmith' ).'</span>' : '';
            $has_clear  = '1' == $show_clear ? ' has-clear-btn' : '';
            ?>
            <div class="wishlist-area panel-content-item" data-name="wishlist" data-wishlist-count="<?php echo esc_attr( $count ); ?>">
                <div class="wishlist-content">
                    <?php if ( '' != self::get_ot_settings('sidebar_panel_wishlist_custom_title') ) { ?>
                        <span class="panel-top-title<?php echo esc_attr( $has_clear ); ?>"><?php echo esc_html( self::get_ot_settings('sidebar_panel_wishlist_custom_title') ); ?><?php printf( '%s',$clear_btn ); ?></span>
                    <?php } else { ?>
                        <span class="panel-top-title<?php echo esc_attr( $has_clear ); ?>"><?php esc_html_e( 'Your Wishlist', 'goldsmith' ); ?><?php printf( '%s',$clear_btn ); ?></span></span>

                    <?php } ?>
                    <div class="goldsmith-panel-content-items goldsmith-wishlist-content-items goldsmith-perfect-scrollbar">
                        <?php $this->print_wishlist(); ?>
                    </div>
                    <div class="goldsmith-panel-content-notice goldsmith-wishlist-content-notice goldsmith-empty-content">
                        <?php if ( !$this->get_count() ) { ?>
                            <?php echo goldsmith_svg_lists( 'love', 'goldsmith-big-svg-icon' ); ?>
                            <div class="goldsmith-small-title"><?php esc_html_e( 'There are no products on the wishlist!', 'goldsmith' ); ?></div>
                            <a class="goldsmith-btn-small mt-10<?php echo esc_attr( $btn_class ); ?>" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Start Shopping', 'goldsmith' ); ?></a>
                        <?php } ?>
                    </div>
                    <?php if ( '' != self::get_wishlist_page_id() ) { ?>
                        <a class="goldsmith-btn goldsmith-btn-dark wishlist-page-link" href="<?php echo esc_url( $page_link ); ?>"><?php esc_html_e( 'Open Wishlist Page', 'goldsmith' ); ?></a>
                    <?php } ?>
                </div>
            </div>
            <?php
        }

        function print_wishlist()
        {
            $key = self::get_key();
            $products = get_option( 'goldsmith_wishlist_' . $key );

            if ( is_array( $products ) && ( count( $products ) > 0 ) ) {

                foreach ( $products as $product_id => $product_data ) {
                    $product = wc_get_product( $product_id );

                    if ( ! $product ) {
                        continue;
                    }
                    $stock_status = $product->is_in_stock() ? esc_html__( 'In stock', 'goldsmith' ) : esc_html__( 'Out of stock', 'goldsmith' );
                    ?>
                    <div class="goldsmith-content-item goldsmith-wishlist-item" data-id="<?php echo esc_attr( $product_id ); ?>" data-key="<?php echo esc_attr( $key ); ?>">
                        <div class="goldsmith-content-item-inner">
                            <?php
                            $imgurl = get_the_post_thumbnail_url($product->get_id(),'thumbnail');
                            $imgsrc = $imgurl ? $imgurl : wc_placeholder_img_src();
                            $img    = '<img width="80" height="80" src="'.$imgsrc.'" alt="'.$product->get_name().'"/>';
                            echo sprintf( '<a href="%s">%s</a>',
                                esc_url( $product->get_permalink() ),
                                $img
                            );
                            ?>
                            <div class="goldsmith-content-info">
                                <div class="goldsmith-small-title">
                                    <a class="goldsmith-content-link" data-id="<?php echo esc_attr( $product_id ); ?>" href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                        <span class="product-name"><?php echo esc_html( $product->get_name() ); ?></span>
                                        <span>
                                            <?php if ( $product->get_price_html() ) { ?>
                                                <span class="product-price goldsmith-price"><?php printf('%s', $product->get_price_html() ); ?></span> /
                                            <?php } ?>
                                            <span class="product-stock goldsmith-stock"> <?php echo esc_html( $stock_status ); ?></span>
                                        </span>
                                    </a>
                                </div>
                                <?php echo goldsmith_add_to_cart( 'text', $product_id ); ?>
                            </div>
                            <div class="goldsmith-content-del-icon goldsmith-wishlist-del-icon"><?php echo goldsmith_svg_lists( 'trash', 'goldsmith-svg-icon mini-icon' ); ?></div>
                        </div>
                    </div>
                    <?php
                }
            }
        }

        function get_items( $key )
        {
            $key = self::get_key();
            $products = get_option( 'goldsmith_wishlist_' . $key );

            ob_start();

            if ( is_array( $products ) && ( count( $products ) > 0 ) ) {

                foreach ( $products as $product_id => $product_data ) {
                    $product = wc_get_product( $product_id );

                    if ( ! $product ) {
                        continue;
                    }
                    $stock_status = $product->is_in_stock() ? esc_html__( 'In stock', 'goldsmith' ) : esc_html__( 'Out of stock', 'goldsmith' );
                    ?>
                    <div class="goldsmith-content-item goldsmith-wishlist-item" data-id="<?php echo esc_attr( $product_id ); ?>" data-key="<?php echo esc_attr( $key ); ?>">
                        <div class="goldsmith-content-item-inner">
                            <?php
                            $imgurl = get_the_post_thumbnail_url($product->get_id(),'thumbnail');
                            $imgsrc = $imgurl ? $imgurl : wc_placeholder_img_src();
                            $img    = '<img width="80" height="80" src="'.$imgsrc.'" alt="'.$product->get_name().'"/>';
                            echo sprintf( '<a href="%s">%s</a>',
                                esc_url( $product->get_permalink() ),
                                $img
                            );
                            ?>
                            <div class="goldsmith-content-info">
                                <div class="goldsmith-small-title">
                                    <a class="goldsmith-content-link" data-id="<?php echo esc_attr( $product_id ); ?>" href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                        <span class="product-name"><?php echo esc_html( $product->get_name() ); ?></span>
                                        <span>
                                            <?php if ( $product->get_price_html() ) { ?>
                                                <span class="product-price goldsmith-price"><?php printf('%s', $product->get_price_html() ); ?></span> /
                                            <?php } ?>
                                            <span class="product-stock goldsmith-stock"> <?php echo esc_html( $stock_status ); ?></span>
                                        </span>
                                    </a>
                                </div>
                                <?php echo goldsmith_add_to_cart( 'text', $product_id ); ?>
                            </div>
                            <div class="goldsmith-content-del-icon goldsmith-wishlist-del-icon"><?php echo goldsmith_svg_lists( 'trash', 'goldsmith-svg-icon mini-icon' ); ?></div>
                        </div>
                    </div>
                    <?php
                }
            }
            $html = ob_get_clean();

            return $html;
        }

        function wishlist_add()
        {
            $return = array( 'status' => 0 );
            $product_id = absint( $_POST['product_id'] );
            $max_count  = self::get_ot_settings('wishlist_max_count',-1);
            $btn_action = self::get_ot_settings('wishlist_btn_action','panel');
            if ( $product_id > 0 ) {
                $key = self::get_key();

                if ( $key === '#' ) {
                    $return['status'] = 0;
                    $return['notice'] = esc_html__( 'Please log in to use the wishlist!', 'goldsmith' );
                    $return['value']  = esc_html__( 'Please log in to use the wishlist!', 'goldsmith' );
                } else {
                    $products = get_option( 'goldsmith_wishlist_' . $key ) ? get_option( 'goldsmith_wishlist_' . $key ) : array();
                    $product  = wc_get_product( $product_id );

                    if ( ! array_key_exists( $product_id, $products ) ) {
                        $products = array(
                            $product_id => array('time' => time() )
                        ) + $products;

                        update_option( 'goldsmith_wishlist_' . $key, $products );
                        $this->update_meta( $product_id, 'goldsmith_wishlist_add' );

                        if ( $btn_action == 'message' ) {
                            $imgurl = get_the_post_thumbnail_url($product_id,'thumbnail');
                            $imgsrc = $imgurl ? $imgurl : wc_placeholder_img_src();
                            $return['notice'] = sprintf('<div class="woocommerce-message wishlist-message"><img width="50" height="50" src="'.$imgsrc.'"/><div class="goldsmith-small-title"><strong>%s</strong> <span>%s</span></div></div>',
                                esc_html( $product->get_name() ),
                                esc_html__( 'Added to the wishlist!', 'goldsmith' )
                            );
                        } else {
                            $return['notice'] = sprintf('<div class="goldsmith-small-title"><span class="product-name">%s</span> <span>%s</span></div>',
                                esc_html( $product->get_name() ),
                                esc_html__( 'Added to the wishlist!', 'goldsmith' )
                            );
                        }
                    } else {
                        if ( $btn_action == 'message' ) {
                            $return['notice'] = sprintf('<div class="woocommerce-message"><div class="goldsmith-small-title"><strong class="product-name">%s</strong> <span>%s</span></div></div>',
                                esc_html( $product->get_name() ),
                                esc_html__( 'Already in the wishlist!', 'goldsmith' )
                            );
                        } else {
                            $return['notice'] = sprintf('<div class="goldsmith-small-title"><span class="product-name">%s</span> <span>%s</span></div>',
                                esc_html( $product->get_name() ),
                                esc_html__( 'Already in the wishlist!', 'goldsmith' )
                            );
                        }
                    }

                    $return['status']   = 1;
                    $return['count']    = count( $products );
                    $return['value']    = $this->get_items( $key );
                    $return['products'] = $products;
                }
            } else {
                $product_id       = 0;
                $return['status'] = 0;
                $return['notice'] = esc_html__( 'Have an error, please try again!', 'goldsmith' );
            }

            echo json_encode( $return );
            die();
        }

        function wishlist_remove()
        {
            $return     = array( 'status' => 0 );
            $product_id = absint( $_POST['product_id'] );
            $icon       = goldsmith_svg_lists( 'love', 'goldsmith-big-svg-icon' );
            $url        = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $btn_class  = is_shop() ? ' goldsmith-panel-close' : '';

            if ( $product_id > 0 ) {
                $key = self::get_key();

                if ( $key === '#' ) {
                    $return['notice'] = esc_html__( 'Please log in to use the wishlist!', 'goldsmith' );
                } else {

                    $products = get_option( 'goldsmith_wishlist_' . $key ) ? get_option( 'goldsmith_wishlist_' . $key ) : array();
                    $product  = wc_get_product( $product_id );
                    $name     = '<span class="product-name">'.esc_html( $product->get_name() ).'</span>';

                    if ( array_key_exists( $product_id, $products ) ) {
                        unset( $products[ $product_id ] );
                        update_option( 'goldsmith_wishlist_' . $key, $products );
                        $this->update_meta( $product_id, 'goldsmith_wishlist_remove' );
                        $return['count']  = count( $products );
                        $return['status'] = 1;

                        if ( count( $products ) > 0 ) {
                            $return['notice'] = sprintf('<div class="goldsmith-small-title"><span class="product-name">%s</span> <span>%s</span></div>',
                                esc_html( $product->get_name() ),
                                esc_html__( 'Removed from wishlist!', 'goldsmith' )
                            );
                        } else {

                            $return['notice_type'] = 'empty';
                            $return['notice']      = sprintf('%s<div class="goldsmith-small-title">%s</div><a class="goldsmith-btn-small mt-10%s" href="%s">%s</a>',
                                $icon,
                                esc_html__( 'There are no products on the wishlist!', 'goldsmith' ),
                                $btn_class,
                                esc_url( $url ),
                                esc_html__( 'Start Shopping', 'goldsmith' )
                            );
                        }
                    } else {
                        $return['notice'] = sprintf('%s<div class="goldsmith-small-title">%s</div><a class="goldsmith-btn-small mt-10%s" href="%s">%s</a>',
                            $icon,
                            esc_html__( 'The product does not exist on the wishlist!', 'goldsmith' ),
                            $btn_class,
                            esc_url( $url ),
                            esc_html__( 'Start Shopping', 'goldsmith' )
                        );
                    }
                }
            } else {
                $product_id = 0;
                $return['notice'] = esc_html__( 'Have an error, please try again!', 'goldsmith' );
            }

            echo json_encode( $return );
            die();
        }

        function wishlist_clear()
        {
            if ( '0' == self::get_ot_settings('sidebar_panel_wishlist_clear_btn') ) {
                return;
                die();
            }

            $return    = array( 'status' => 0 );
            $icon      = goldsmith_svg_lists( 'love', 'goldsmith-big-svg-icon' );
            $url       = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $btn_class = is_shop() ? ' goldsmith-panel-close' : '';
            $key       = self::get_key();

            if ( $key === '#' ) {
                $return['notice'] = esc_html__( 'Please log in to use the wishlist!', 'goldsmith' );
            } else {

                $products = get_option( 'goldsmith_wishlist_'.$key );

                foreach ( $products as $keyy => $value ) {
                    $this->update_meta( $keyy, 'goldsmith_wishlist_clear' );
                }
                update_option( 'goldsmith_wishlist_'.$key, 0 );

                $return['status']      = 1;
                $return['notice_type'] = 'empty';
                $return['notice']      = sprintf('%s<div class="goldsmith-small-title">%s</div><a class="goldsmith-btn-small mt-10%s" href="%s">%s</a>',
                    $icon,
                    esc_html__( 'There are no products on the wishlist!', 'goldsmith' ),
                    $btn_class,
                    esc_url( $url ),
                    esc_html__( 'Start Shopping', 'goldsmith' )
                );
            }

            echo json_encode( $return );
            die();
        }

        function update_meta( $product_id, $action = 'goldsmith_wishlist_add' )
        {
            $meta_count = 'goldsmith_wishlist_count';
            $count      = get_post_meta( $product_id, $meta_count, true );
            $new_count  = 0;

            if ( $action === 'goldsmith_wishlist_add' ) {
                if ( $count ) {
                    $new_count = absint( $count ) + 1;
                } else {
                    $new_count = 1;
                }
            } elseif ( $action === 'goldsmith_wishlist_remove' ) {
                if ( $count && ( absint( $count ) > 1 ) ) {
                    $new_count = absint( $count ) - 1;
                } else {
                    $new_count = 0;
                }
            } elseif ( $action === 'goldsmith_wishlist_clear' ) {
                if ( $count && ( absint( $count ) > 1 ) ) {
                    $new_count = absint( $count ) - 1;
                } else {
                    $new_count = 0;
                }
            }

            update_post_meta( $product_id, $meta_count, $new_count );
            update_post_meta( $product_id, $action, time() );
        }

        public function wishlist_wp_login( $user_login, $user ) {
            if ( isset( $user->data->ID ) ) {
                $user_key = get_user_meta( $user->data->ID, 'goldsmith_wishlist_key', true );

                if ( empty( $user_key ) ) {
                    $user_key = self::generate_key();

                    while ( self::exists_key( $user_key ) ) {
                        $user_key = self::generate_key();
                    }

                    // set a new key
                    update_user_meta( $user->data->ID, 'goldsmith_wishlist_key', $user_key );
                }

                $secure   = apply_filters( 'goldsmith_wishlist_cookie_secure', wc_site_is_https() && is_ssl() );
                $httponly = apply_filters( 'goldsmith_wishlist_cookie_httponly', true );

                if ( isset( $_COOKIE['goldsmith_wishlist_key'] ) && ! empty( $_COOKIE['goldsmith_wishlist_key'] ) ) {
                    wc_setcookie( 'goldsmith_wishlist_key_ori', $_COOKIE['goldsmith_wishlist_key'], time() + 604800, $secure, $httponly );
                }

                wc_setcookie( 'goldsmith_wishlist_key', $user_key, time() + 604800, $secure, $httponly );
            }
        }

        public function wishlist_wp_logout( $user_id ) {
            if ( isset( $_COOKIE['goldsmith_wishlist_key_ori'] ) && ! empty( $_COOKIE['goldsmith_wishlist_key_ori'] ) ) {
                $secure   = apply_filters( 'goldsmith_wishlist_cookie_secure', wc_site_is_https() && is_ssl() );
                $httponly = apply_filters( 'goldsmith_wishlist_cookie_httponly', true );

                wc_setcookie( 'goldsmith_wishlist_key', $_COOKIE['goldsmith_wishlist_key_ori'], time() + 604800, $secure, $httponly );
            } else {
                unset( $_COOKIE['goldsmith_wishlist_key_ori'] );
                unset( $_COOKIE['goldsmith_wishlist_key'] );
            }
        }

        public static function generate_key()
        {
            $key         = '';
            $key_str     = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $key_str_len = strlen( $key_str );

            for ( $i = 0; $i < 6; $i ++ ) {
                $key .= $key_str[ random_int( 0, $key_str_len - 1 ) ];
            }

            return $key;
        }

        public static function get_key()
        {
            if ( ! is_user_logged_in() && '1' == self::get_ot_settings('wishlist_disable_unauthenticated', '0') ) {
                return '#';
            }

            if ( is_user_logged_in() && ( ( $user_id = get_current_user_id() ) > 0 ) ) {
                $user_key = get_user_meta( $user_id, 'goldsmith_wishlist_key', true );

                if ( empty( $user_key ) ) {
                    $user_key = self::generate_key();

                    while ( self::exists_key( $user_key ) ) {
                        $user_key = self::generate_key();
                    }

                    // set a new key
                    update_user_meta( $user_id, 'goldsmith_wishlist_key', $user_key );
                }

                return $user_key;
            }

            if ( isset( $_COOKIE['goldsmith_wishlist_key'] ) ) {
                return esc_attr( $_COOKIE['goldsmith_wishlist_key'] );
            }

            return 'GOLDSMITHWL';
        }

        public static function exists_key( $key )
        {
            return get_option( 'goldsmith_list_' . $key ) ? true : false;
        }

        public static function get_ids( $key = null ) {
            if ( ! $key ) {
                $key = self::get_key();
            }

            return (array) get_option( 'goldsmith_list_' . $key, [] );
        }

        public static function get_url( $key = null, $full = false ) {
            $url = home_url( '/' );
            $page_id = self::get_wishlist_page_id();
            if ( $page_id ) {
                if ( $full ) {
                    if ( ! $key ) {
                        $key = self::get_key();
                    }

                    if ( get_option( 'permalink_structure' ) !== '' ) {
                        $url = trailingslashit( get_permalink( $page_id ) ) . $key;
                    } else {
                        $url = get_permalink( $page_id ) . '&goldsmithwl_id=' . $key;
                    }
                } else {
                    $url = get_permalink( $page_id );
                }
            }

            return esc_url( apply_filters( 'goldsmith_wishlist_url', $url, $key, $full ) );
        }

        public static function get_count( $key = null )
        {
            if ( ! $key ) {
                $key = self::get_key();
            }
            $products = get_option( 'goldsmith_wishlist_' . $key );

            if ( ( $key != '' ) && $products && is_array( $products ) ) {
                $count = count( $products );
            } else {
                $count = 0;
            }

            return $count;
        }

        public static function get_products( $key = null )
        {
            if ( ! $key ) {
                $key = self::get_key();
            }
            $products = get_option( 'goldsmith_wishlist_' . $key );
            $ids = array();
            if ( ( $key != '' ) && $products && is_array( $products ) ) {
                foreach ( $products as $key => $id ) {
                    $ids[] = $key;
                }
                return $ids;
            }
        }
    }
    Goldsmith_Wishlist::get_instance();
}
