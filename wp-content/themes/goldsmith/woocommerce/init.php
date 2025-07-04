<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


/*************************************************
## ADD THEME SUPPORT FOR WOOCOMMERCE
*************************************************/
if ( ! function_exists( 'goldsmith_wc_shop_per_page' ) ) {
    add_action( 'after_setup_theme', 'goldsmith_wc_theme_setup' );
    function goldsmith_wc_theme_setup()
    {
        $single_image_width = goldsmith_settings( 'product_gallery_imgsize', 980 );
        $single_image_width = $single_image_width ? $single_image_width : 980;
        add_theme_support( 'goldsmith' );
        add_theme_support( 'woocommerce', array(
            'thumbnail_image_width' => 450,
            'single_image_width'    => $single_image_width
        ));

        if ( '1' == goldsmith_settings('goldsmith_product_zoom', '1') ) {
            add_theme_support( 'wc-product-gallery-zoom' );
        }

        $thumbs_layout = apply_filters( 'goldsmith_product_thumbs_layout', goldsmith_settings( 'product_thumbs_layout', 'slider' ) );
        if ( $thumbs_layout == 'woo' ) {
            add_theme_support( 'wc-product-gallery-zoom' );
            add_theme_support( 'wc-product-gallery-lightbox' );
            add_theme_support( 'wc-product-gallery-slider' );
        }
    }
}


// Remove each style one by one
if ( ! function_exists( 'goldsmith_dequeue_wc_styles' ) ) {
    add_filter( 'woocommerce_enqueue_styles', 'goldsmith_dequeue_wc_styles' );
    function goldsmith_dequeue_wc_styles( $styles ) {
        unset( $styles['woocommerce-general'] ); // Remove the gloss
        unset( $styles['woocommerce-layout'] ); // Remove the layout
        unset( $styles['woocommerce-smallscreen'] ); // Remove the smallscreen optimisation
        return $styles;
    }
}


/*************************************************
## THEME CUSTOM CSS AND JS FOR WOOCOMMERCE
*************************************************/

if ( ! function_exists( 'goldsmith_wc_widgets_init' ) ) {
    function ajax_login_init()
    {
        if ( '1' != goldsmith_settings( 'wc_ajax_login_register', '1' ) ) {
            return;
        }

        wp_enqueue_script( 'goldsmith-login-register-ajax', get_template_directory_uri() . '/woocommerce/assets/js/ajax-login-register-script.js', array( 'jquery' ), false, '1.0' );

        add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
        add_action( 'wp_ajax_nopriv_ajaxregister', 'ajax_register' );
        add_action( "woocommerce_register_form_end", 'goldsmith_register_message' );
    }
}

// Execute the action only if the user isn't logged in
if ( !is_user_logged_in() ) {
    add_action('init', 'ajax_login_init');
}

//ajax login function
function ajax_login(){

    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'woocommerce-login', 'woocommerce-login-nonce' );

    if ( ! empty( $_POST['username'] ) && ! empty( $_POST['password'] ) ) {
        // Nonce is checked, get the POST data and sign user on
        $info = array();
        $info['user_login']    = wc_clean($_POST['username']);
        $info['user_password'] = $_POST['password'];
        $info['remember']      = false;
        if ( isset( $_POST['rememberme'] ) ) {
            $info['remember'] = true;
        }

        $user_signon = wp_signon( $info, false );
        if ( is_wp_error($user_signon) ) {

            if ( isset( $user_signon->errors[ 'invalid_username' ] ) ) {
                $username_error = true;
            } else{
                $username_error = false;
            }
            if ( isset( $user_signon->errors[ 'incorrect_password' ] ) ) {
                $password_error = true;
            } else {
                $password_error = false;
            }
            $error_string = $user_signon->get_error_message();

            echo json_encode( array(
                'loggedin'           => false,
                'message'            => $error_string,
                'invalid_username'   => $username_error,
                'incorrect_password' => $password_error,
            ));

        } else {
            // hook after successfull login
            do_action( "goldsmith_after_login", $user_signon );
            $args = array(
                'loggedin' => true,
                'message'  => esc_html__( 'Login successful, redirecting...', 'goldsmith' ),
                'redirect' => apply_filters( "goldsmith_login_redirect", false )
            );

            echo json_encode( $args );
        }
        die();
    } else {
        echo json_encode( array('loggedin'=>false, 'message'=>esc_html__('Please fill all required fields.','goldsmith') ) );
        die();
    }
}

/*
* Ajax register function
*/
function ajax_register(){

    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'woocommerce-register', 'woocommerce-register-nonce' );

    $generate_password = get_option( 'woocommerce_registration_generate_password' );

    if ( ! empty( $_POST['email'] ) && ! empty( $_POST['password'] ) ) {
        $username = 'no' === get_option( 'woocommerce_registration_generate_username' ) ? $_POST['username'] : '';
        $password = 'no' === get_option( 'woocommerce_registration_generate_password' ) ? $_POST['password'] : '';
        $email    = $_POST['email'];

        $validation_error = new WP_Error();
        $validation_error = apply_filters( 'woocommerce_process_registration_errors', $validation_error, $username, $password, $email );

        if ( $validation_error->get_error_code() ) {

            $error_array = array(
                'code'    => $validation_error->get_error_code(),
                'message' => $validation_error->get_error_message()
            );
        } else {
            $new_customer = wc_create_new_customer( sanitize_email( $email ), wc_clean( $username ), $password );
            if ( is_wp_error( $new_customer ) ) {
                $error_array = array(
                    'code'    => $new_customer->get_error_code(),
                    'message' => $new_customer->get_error_message()
                );
            } else {
                if ( apply_filters( 'woocommerce_registration_auth_new_customer', true, $new_customer ) ) {
                    wc_set_customer_auth_cookie( $new_customer );
                }

                $args = array(
                    'code'     => 200,
                    'message'  =>esc_html__( 'Account created successfully. redirecting...', 'goldsmith' ),
                    'redirect' => apply_filters( "goldsmith_register_redirect", false )
                );
                apply_filters( "goldsmith_register_user_successful", false );
                echo json_encode( $args );
                die();
            }
        }
    }
    elseif ( $generate_password == 'yes' ) {
        if ( empty( $_POST['email'] ) ) {
            $error_array = array(
                'code'    => 'error',
                'message' => esc_html__('Please fill all required fields.','goldsmith')
            );
        } else {
            $username         = 'no' === get_option( 'woocommerce_registration_generate_username' ) ? $_POST['username'] : '';
            $email            = $_POST['email'];
            $validation_error = new WP_Error();
            $validation_error = apply_filters( 'woocommerce_process_registration_errors', $validation_error, $username, $password, $email );

            if ( $validation_error->get_error_code() ) {
                $error_array = array(
                    'code'    => $validation_error->get_error_code(),
                    'message' => $validation_error->get_error_message()
                );
            } else {
                $new_customer = wc_create_new_customer( sanitize_email( $email ), wc_clean( $username ));
                if ( is_wp_error( $new_customer ) ) {
                    $error_array = array(
                        'code'    => $new_customer->get_error_code(),
                        'message' => $new_customer->get_error_message()
                    );
                } else {
                    if ( apply_filters( 'woocommerce_registration_auth_new_customer', true, $new_customer ) ) {
                        wc_set_customer_auth_cookie( $new_customer );
                    }

                    $args = array(
                        'code'     => 200,
                        'message'  => esc_html__( 'Account created successfully. redirecting...', 'goldsmith' ),
                        'redirect' => apply_filters( "goldsmith_register_redirect", false )
                    );
                    echo json_encode( $args );
                    die();
                }
            }
        }
    }
    else {
        $error_array = array(
            'code' => 'error',
            'message' => esc_html__('Please fill all required fields.','goldsmith')
        );
    }
    echo json_encode($error_array);
    die();
}

function goldsmith_register_message(){
    global $woocommerce;
    ?>
    <input type="hidden" name="action" value="ajaxregister">
    <?php
}

/*************************************************
## REGISTER SIDEBAR FOR WOOCOMMERCE
*************************************************/

if ( ! function_exists( 'goldsmith_wc_widgets_init' ) ) {
    add_action( 'widgets_init', 'goldsmith_wc_widgets_init' );
    function goldsmith_wc_widgets_init()
    {
        //Shop page sidebar
        register_sidebar( array(
            'id' => 'shop-page-sidebar',
            'name' => esc_html__( 'Shop Page Sidebar', 'goldsmith' ),
            'description' => esc_html__( 'These widgets for the Shop page.','goldsmith' ),
            'before_widget' => '<div class="nt-sidebar-inner-widget shop-widget goldsmith-widget-show mb-40 %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="nt-sidebar-inner-widget-title shop-widget-title"><span class="nt-sidebar-widget-title">',
            'after_title' => '</span><span class="nt-sidebar-widget-toggle"></span></h5>'
        ) );
        //Single product sidebar
        register_sidebar( array(
            'id' => 'shop-single-sidebar',
            'name' => esc_html__( 'Shop Single Page Sidebar', 'goldsmith' ),
            'description' => esc_html__( 'These widgets for the Shop Single page.','goldsmith' ),
            'before_widget' => '<div class="nt-sidebar-inner-widget shop-widget goldsmith-widget-show mb-40 %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h5 class="nt-sidebar-inner-widget-title shop-widget-title"><span class="goldsmith-sidebar-widget-title">',
            'after_title' => '</span><span class="goldsmith-sidebar-widget-toggle"></span></h5>'
        ) );
    }
}


/*************************************************
## WOOCOMMERCE PAGE TITLE FUNCTION
*************************************************/

if ( ! function_exists( 'goldsmith_shop_page_title' ) ) {
    add_filter( 'woocommerce_page_title', 'goldsmith_shop_page_title');
    function goldsmith_shop_page_title( $page_title )
    {
    	$tag = is_product() ? goldsmith_settings( 'shop_product_page_title_tag', 'h2' ) : goldsmith_settings( 'shop_product_page_title_tag', 'h2' );
        if ( 'Shop' == $page_title && goldsmith_settings( 'shop_title' ) ) {
            return '<'.$tag.' class="nt-hero-title page-title">'.goldsmith_settings( 'shop_title' ).'</'.$tag.'>';
        } else {
            return '<'.$tag.' class="nt-hero-title page-title">'.$page_title.'</'.$tag.'>';
        }
    }
}


/*************************************************
## WOOCOMMERCE HERO FUNCTION
*************************************************/

if ( ! function_exists( 'goldsmith_wc_hero_section' ) ) {
    add_action( 'goldsmith_before_shop_content', 'goldsmith_wc_hero_section', 10 );
    function goldsmith_wc_hero_section()
    {
        $name      = is_product() ? 'single_shop' : 'shop';
        $hero_type = goldsmith_settings( $name.'_hero_layout_type', 'mini' );
        $hero_type = isset( $_GET['hero_type'] ) ? $_GET['hero_type'] : $hero_type;
        $is_big    = 'big' == $hero_type || 'cat-slider' == $hero_type ? ' page-hero-static' : '';

        $template_id      = apply_filters( 'goldsmith_shop_hero_template_id', intval( goldsmith_settings( 'shop_hero_elementor_templates' ) ) );
		$template_id      = apply_filters( 'goldsmith_translated_template_id', $template_id );
        $cats_template_id = apply_filters( 'goldsmith_shop_category_hero_template_id', intval( goldsmith_settings( 'shop_cats_hero_elementor_templates' ) ) );
		$cats_template_id = apply_filters( 'goldsmith_translated_template_id', $cats_template_id );
        $tax_template_id  = apply_filters( 'goldsmith_shop_tags_hero_template_id', intval( goldsmith_settings( 'shop_tax_hero_elementor_templates' ) ) );
		$tax_template_id  = apply_filters( 'goldsmith_translated_template_id', $tax_template_id );
        $is_elementor     = class_exists( '\Elementor\Frontend' ) ? true : false;
        $frontend         = $is_elementor ? new \Elementor\Frontend : false;

        $shop_hero_bg_type = goldsmith_settings( 'shop_hero_bg_type', 'img' );
        $shop_hero_bg      = goldsmith_settings( 'shop_hero_bg' );
        $has_hero_bg       = !empty( $shop_hero_bg['background-image'] ) ? ' has-bg-image' : '';

        if ( $hero_type == 'no-title' || '0' == goldsmith_settings( 'shop_hero_visibility', '1' ) ) {
            return;
        }

        if ( is_product_category() ) {

            goldsmith_wc_archive_category_page_hero_section();

        } elseif ( is_product_tag() && $is_elementor && $tax_template_id  ) {

            printf( '<div class="goldsmith-shop-hero-tag">%1$s</div>', $frontend->get_builder_content_for_display( $tax_template_id, false ) );

        } elseif ( ( is_shop() || is_product() ) && $is_elementor && $template_id  ) {

            printf( '<div class="goldsmith-shop-custom-hero">%1$s</div>', $frontend->get_builder_content_for_display( $template_id, false ) );

        } else {
            ?>
            <div class="goldsmith-shop-hero-wrapper<?php echo esc_attr( $is_big ); ?>">
                <div class="goldsmith-shop-hero goldsmith-page-hero page-hero-<?php echo esc_attr( $hero_type.$has_hero_bg ); ?>">
                    <?php
                        if ( 'img' == $shop_hero_bg_type && !empty( $shop_hero_bg['background-image'] ) ) {
                            $shop_hero_bgsize = goldsmith_settings( 'shop_hero_bg_imgsize', 'large' );
                            $shop_hero_bg_id  = $shop_hero_bg['media']['id'];
                            echo wp_get_attachment_image($shop_hero_bg_id,$shop_hero_bgsize);
                        }
                    ?>
                    <div class="goldsmith-page-hero-content container">
                        <?php

                        echo goldsmith_breadcrumbs();

                        woocommerce_page_title();

                        do_action( 'woocommerce_archive_description' );

                        if ( $hero_type == 'big' ) {
                            echo goldsmith_wc_category_list();
                        }

                        if ( $hero_type == 'cat-slider' ) {
                            goldsmith_wc_hero_category_slider();
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if ( ! function_exists( 'goldsmith_wc_default_hero_section' ) ) {

    function goldsmith_wc_default_hero_section()
    {
        ?>
        <div class="goldsmith-shop-hero goldsmith-page-hero">
            <div class="goldsmith-page-hero-content container">
                <?php
                    echo goldsmith_breadcrumbs();
                    woocommerce_page_title();
                    do_action( 'woocommerce_archive_description' );
                ?>
            </div>
        </div>
        <?php
    }
}

if ( ! function_exists( 'goldsmith_wc_archive_category_page_hero_section' ) ) {
    function goldsmith_wc_archive_category_page_hero_section()
    {
        $cats_template_id  = apply_filters( 'goldsmith_shop_category_hero_template_id', intval( goldsmith_settings( 'shop_cats_hero_elementor_templates' ) ) );
        $is_elementor      = class_exists( '\Elementor\Frontend' ) ? true : false;
        $frontend          = $is_elementor ? new \Elementor\Frontend : false;
        $term_bg_id        = get_term_meta( get_queried_object_id(), 'goldsmith_product_cat_hero_bgimage_id', true );
        $term_bg_url       = wp_get_attachment_image_url( $term_bg_id, 'large' );
        $cat_template      = get_term_meta( get_queried_object_id(), 'goldsmith_wc_cat_hero', true );

        if ( $cat_template ) {
            printf( '<div class="goldsmith-shop-hero-cats">%s</div>', do_shortcode( $cat_template ) );
        } else {
            if ( $term_bg_url ) {
                ?>
                <div class="goldsmith-shop-hero goldsmith-page-hero has-bg-image" data-bg="<?php echo esc_url( $term_bg_url ); ?>">
                    <div class="goldsmith-page-hero-content container">
                        <?php
                            echo goldsmith_breadcrumbs();
                            woocommerce_page_title();
                            do_action( 'woocommerce_archive_description' );
                        ?>
                    </div>
                </div>
                <?php
            } else {
                if ( $is_elementor && $cats_template_id ) {
                    printf( '<div class="goldsmith-shop-hero-cats">%1$s</div>', $frontend->get_builder_content_for_display( $cats_template_id, false ) );
                } else {
                    goldsmith_wc_default_hero_section();
                }
            }
        }
    }
}



/*************************************************
## WOOCOMMERCE HERO CATEGORY SLIDER FUNCTION
*************************************************/

if ( ! function_exists( 'goldsmith_wc_hero_category_slider' ) ) {
    function goldsmith_wc_hero_category_slider()
    {
        $args = array(
            'order'      => goldsmith_settings( 'shop_hero_carousel_catorder', 'ASC' ),
            'orderby'    => goldsmith_settings( 'shop_hero_carousel_catorderby', 'name' ),
            'hide_empty' => '1' == goldsmith_settings( 'shop_hero_carousel_cathideempty', '1' ) ? true : false
        );

        if ( '1' == goldsmith_settings( 'shop_hero_carousel_catparent', '1' ) ) {
            $args['parent'] = 0;
        }

        $filter = goldsmith_settings( 'shop_hero_carousel_catfilter', 'include' );
        $cats   = goldsmith_settings( 'shop_hero_carousel_cats', null );

        if ( !empty($cats) ) {
            $args[$filter] = $cats;
        }

        $categories = get_terms( 'product_cat', $args);

        $options = json_encode( array(
            "slidesPerView"        => 1,
            "spaceBetween"         => absint(goldsmith_settings( 'shop_hero_carousel_gap', 1 )),
            "speed"                => absint(goldsmith_settings( 'shop_hero_carousel_speed', 2000 )),
            "loop"                 => '1' == goldsmith_settings( 'shop_hero_carousel_loop', '0' ) ? true : false,
            "rewind"               => '1' == goldsmith_settings( 'shop_hero_carousel_rewind', '1' ) ? true : false,
            "autoplay"             => '1' == goldsmith_settings( 'shop_hero_carousel_autoplay', '1' ) ? [ "pauseOnMouseEnter" => true,"disableOnInteraction" => false ] : false,
            "centeredSlides"       => '1' == goldsmith_settings( 'shop_hero_carousel_centred', '1' ) ? true : false,
            //"centeredSlidesBounds" => true,
            "watchSlidesProgress"  => true,
            "pagination"           => false,
            "autoHeight"           => false,
            "wrapperClass"         => "goldsmith-swiper-wrapper",
            "direction"            => "horizontal",
            "breakpoints"          => [
                "320"  => ["slidesPerView" => absint(goldsmith_settings( 'shop_hero_carousel_perview6', 3 ))],
                "768"  => ["slidesPerView" => absint(goldsmith_settings( 'shop_hero_carousel_perview5', 7 ))],
                "992"  => ["slidesPerView" => absint(goldsmith_settings( 'shop_hero_carousel_perview4', 5 ))],
                "1200" => ["slidesPerView" => absint(goldsmith_settings( 'shop_hero_carousel_perview3', 7 ))],
                "1400" => ["slidesPerView" => absint(goldsmith_settings( 'shop_hero_carousel_perview2', 8 ))],
                "1500" => ["slidesPerView" => absint(goldsmith_settings( 'shop_hero_carousel_perview1', 6 ))]
            ]
        ));

        $isfilter = 'link' != goldsmith_settings( 'shop_hero_carousel_ajax', 'filter' ) ? ' type-filter' : '';

        wp_enqueue_script( 'goldsmith-swiper' );

        if ( !empty( $categories ) ) {
            ?>
            <div class="goldsmith-category-slider-wrapper container">
                <div class="goldsmith-category-slider goldsmith-container goldsmith-swiper-slider swiper-container" data-swiper-options='<?php echo esc_attr( $options ); ?>'>
                    <div class="goldsmith-swiper-wrapper">
                        <?php
                        foreach ( $categories as $category ) {
                            ?>
                            <div class="goldsmith-category-slide-item swiper-slide<?php echo esc_attr( $isfilter ); ?>">
                                <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" rel="nofollow noreferrer">
                                    <?php
                                    if ( '1' == goldsmith_settings( 'shop_hero_carousel_catthumb', '1' ) ) {
                                        $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                                        echo wp_get_attachment_image( $thumbnail_id, array(100,100), true );
                                    }
                                    ?>
                                    <?php if ( '1' == goldsmith_settings( 'shop_hero_carousel_cattcount', '1' ) ) { ?>
                                        <span class="cat-count"><?php echo esc_html( $category->count ); ?></span>
                                    <?php } ?>
                                    <span class="category-title"><?php echo esc_html( $category->name ); ?></span>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

/*************************************************
## WOOCOMMERCE HERO CATEGORY LIST FUNCTION
*************************************************/

if ( ! function_exists( 'goldsmith_wc_category_list' ) ) {
    function goldsmith_wc_category_list()
    {
        $args = array(
            'order'      => goldsmith_settings( 'shop_hero_carousel_catorder', 'ASC' ),
            'orderby'    => goldsmith_settings( 'shop_hero_carousel_catorderby', 'name' ),
            'hide_empty' => '1' == goldsmith_settings( 'shop_hero_carousel_cathideempty', '1' ) ? true : false
        );

        if ( '1' == goldsmith_settings( 'shop_hero_carousel_catparent', '1' ) ) {
            $args['parent'] = 0;
        }

        $filter   = goldsmith_settings( 'shop_hero_carousel_catfilter', 'include' );
        $cats     = goldsmith_settings( 'shop_hero_carousel_cats', null );
        $isfilter = 'link' != goldsmith_settings( 'shop_hero_carousel_ajax', 'filter' ) ? ' type-filter' : '';

        if ( !empty($cats) ) {
            $args[$filter] = $cats;
        }

        $categories = get_terms( 'product_cat', $args);

        if ( !empty( $categories ) ) {
            ?>
            <ul class="goldsmith-wc-category-list<?php echo esc_attr( $isfilter ); ?>">
                <?php
                foreach ( $categories as $key => $category ) {
                    ?>
                    <li>
                        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" rel="nofollow noreferrer">
                            <span class="category-title"><?php echo esc_html( $category->name ); ?></span>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <?php
        }
    }
}


if ( ! function_exists( 'goldsmith_before_shop_elementor_templates' ) ) {
    add_action( 'goldsmith_before_shop_content', 'goldsmith_before_shop_elementor_templates', 15 );
    function goldsmith_before_shop_elementor_templates()
    {
        $cat_template = goldsmith_settings('shop_category_pages_before_content_templates', null );
        $tag_template = goldsmith_settings('shop_tag_pages_before_content_templates', null );
        if ( ( $cat_template || $tag_template ) && ( is_product_category() || is_product_tag() ) ) {
            if ( $cat_template && is_product_category() ) {
                echo goldsmith_print_elementor_templates( 'shop_category_pages_before_content_templates', 'shop-before-template-wrapper', true );
            } elseif ( $tag_template && is_product_tag() ) {
                echo goldsmith_print_elementor_templates( 'shop_tag_pages_before_content_templates', 'shop-before-template-wrapper', true );
            }
        } else {
            echo goldsmith_print_elementor_templates( 'shop_before_content_templates', 'shop-before-template-wrapper', true );
        }
    }
}

if ( ! function_exists( 'goldsmith_after_shop_loop_elementor_templates' ) ) {
    add_action( 'goldsmith_after_shop_loop', 'goldsmith_after_shop_loop_elementor_templates', 10 );
    function goldsmith_after_shop_loop_elementor_templates()
    {
        $cat_template = goldsmith_settings('shop_category_pages_after_loop_templates', null );
        $tag_template = goldsmith_settings('shop_tag_pages_after_loop_templates', null );
        if ( ( $cat_template || $tag_template ) && ( is_product_category() || is_product_tag() ) ) {
            if ( $cat_template && is_product_category() ) {
                echo goldsmith_print_elementor_templates( 'shop_category_pages_after_loop_templates', 'after-shop-template', true );
            } elseif ( $tag_template && is_product_tag() ) {
                echo goldsmith_print_elementor_templates( 'shop_tag_pages_after_loop_templates', 'after-shop-template', true );
            }
        } else {
            echo goldsmith_print_elementor_templates( 'shop_after_loop_templates', 'after-shop-template', true );
        }
    }
}

if ( ! function_exists( 'goldsmith_after_shop_page_elementor_templates' ) ) {
    add_action( 'goldsmith_after_shop_page', 'goldsmith_after_shop_page_elementor_templates', 10 );
    function goldsmith_after_shop_page_elementor_templates()
    {
        $cat_template = goldsmith_settings('shop_category_pages_after_content_templates', null );
        $tag_template = goldsmith_settings('shop_tag_pages_after_content_templates', null );
        if ( ( $cat_template || $tag_template ) && ( is_product_category() || is_product_tag() ) ) {
            if ( $cat_template && is_product_category() ) {
                echo goldsmith_print_elementor_templates( 'shop_category_pages_after_content_templates', 'shop-after-content-template-wrapper', true );
            } elseif ( $tag_template && is_product_tag() ) {
                echo goldsmith_print_elementor_templates( 'shop_tag_pages_after_content_templates', 'shop-after-content-template-wrapper', true );
            }
        } else {
            echo goldsmith_print_elementor_templates( 'shop_after_content_templates', 'shop-after-content-template-wrapper' );
        }
    }
}


/*************************************************
## Get Columns options
*************************************************/
if ( ! function_exists( 'goldsmith_get_shop_column' ) ) {
    function goldsmith_get_shop_column()
    {
        $column = isset( $_GET['column'] ) ? $_GET['column'] : '';
        return esc_html($column);
    }
}


if ( ! function_exists( 'goldsmith_shop_pagination' ) ) {
    add_action( 'goldsmith_shop_pagination', 'goldsmith_shop_pagination', 15 );
    function goldsmith_shop_pagination()
    {
        $pagination = apply_filters('goldsmith_shop_pagination_type', goldsmith_settings('shop_paginate_type') );
        $loop_mode  = woocommerce_get_loop_display_mode();
        if ( $pagination == 'loadmore' && 'subcategories' != $loop_mode ) {

            goldsmith_load_more_button();

        } elseif ( $pagination == 'infinite' && 'subcategories' != $loop_mode ) {

            goldsmith_infinite_scroll();

        } else  {

            woocommerce_pagination();

        }
    }
}


if ( ! function_exists( 'goldsmith_wc_filters_for_ajax' ) ) {
    function goldsmith_wc_filters_for_ajax()
    {
        if ( '1' == goldsmith_get_shop_column() ) {
            $type = 7;
        } else {
            $type = isset( $_GET['product_style'] ) && $_GET['product_style'] ? esc_html ( $_GET['product_style'] ) : goldsmith_settings( 'shop_product_type', '2' );
            $type = apply_filters( 'goldsmith_loop_product_type', $type );
        }
        $brand_id = is_tax( 'goldsmith_product_brands' ) && isset( get_queried_object()->term_id ) ? get_queried_object()->term_id : '';
        return json_encode(
            array(
                'ajaxurl'        => esc_url( admin_url( 'admin-ajax.php' ) ),
                'current_page'   => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
                'max_page'       => wc_get_loop_prop( 'total_pages' ),
                'per_page'       => isset( $_GET['per_page'] ) ? $_GET['per_page'] : wc_get_loop_prop( 'per_page' ),
                'layered_nav'    => WC_Query::get_layered_nav_chosen_attributes(),
                'cat_id'         => isset( get_queried_object()->term_id ) ? get_queried_object()->term_id : '',
                'brand_id'       => isset( $_GET['brand_id'] ) ? $_GET['brand_id'] : $brand_id,
                'filter_cat'     => isset( $_GET['filter_cat'] ) ? $_GET['filter_cat'] : '',
                'filter_brand'   => isset( $_GET['filter_brand'] ) ? $_GET['filter_brand'] : '',
                'on_sale'        => isset( $_GET['on_sale'] ) ? 'yes' : 'no',
                'in_stock'       => isset( $_GET['stock_status'] ) && $_GET['stock_status'] == 'instock' ? 'yes' : 'no',
                'orderby'        => isset( $_GET['orderby'] ) ? $_GET['orderby'] : '',
                'min_price'      => isset( $_GET['min_price'] ) ? $_GET['min_price'] : '',
                'max_price'      => isset( $_GET['max_price'] ) ? $_GET['max_price'] : '',
                'product_style'  => $type,
                'column'         => goldsmith_get_shop_column(),
                'no_more'        => esc_html__( 'All Products Loaded', 'goldsmith' ),
                'is_search'      => is_search() ? 'yes' : '',
                'is_shop'        => is_shop() ? 'yes' : '',
                'is_brand'       => is_tax( 'goldsmith_product_brands' ) ? 'yes' : '',
                'is_cat'         => is_tax( 'product_cat' ) ? 'yes' : '',
                'is_tag'         => is_tax( 'product_tag' ) ? 'yes' : '',
                's'              => isset($_GET['s']) ? $_GET['s'] : ''
            )
        );
    }
}


if ( ! function_exists( 'goldsmith_get_cat_url' ) ) {
    function goldsmith_get_cat_url( $termid )
    {
        global $wp;
        if ( '' === get_option( 'permalink_structure' ) ) {
            $link = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
        } else {
            $link = preg_replace( '%\/page/[0-9]+%', '', add_query_arg( null, null ) );
        }

        if ( isset( $_GET['filter_cat'] ) ) {
            $explode_old = explode( ',', $_GET['filter_cat'] );
            $explode_termid = explode( ',', $termid );

            if ( in_array( $termid, $explode_old ) ) {
                $data = array_diff( $explode_old, $explode_termid );
                $checkbox = 'checked';
            } else {
                $data = array_merge( $explode_termid , $explode_old );
            }
        } else {
            $data = array( $termid );
        }

        $dataimplode = implode( ',', $data );

        if ( empty( $dataimplode ) ) {
            $link = remove_query_arg( 'filter_cat', $link );
        } else {
            $link = add_query_arg( 'filter_cat', implode( ',', $data ), $link );
        }

        return $link;
    }
}


if ( ! function_exists( 'goldsmith_get_brand_url' ) ) {
    function goldsmith_get_brand_url( $termid )
    {
        global $wp;
        if ( '' === get_option( 'permalink_structure' ) ) {
            $link = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
        } else {
            $link = preg_replace( '%\/page/[0-9]+%', '', add_query_arg( null, null ) );
        }

        if ( isset( $_GET['brand_id'] ) ) {
            $explode_old = explode( ',', $_GET['brand_id'] );
            $explode_termid = explode( ',', $termid );

            if ( in_array( $termid, $explode_old ) ) {
                $data = array_diff( $explode_old, $explode_termid );
                $checkbox = 'checked';
            } else {
                $data = array_merge( $explode_termid , $explode_old );
            }
        } else {
            $data = array( $termid );
        }

        $dataimplode = implode( ',', $data );

        if ( empty( $dataimplode ) ) {
            $link = remove_query_arg( 'brand_id', $link );
        } else {
            $link = add_query_arg( 'brand_id', implode( ',', $data ), $link );
        }

        return $link;
    }
}

/*************************************************
## MINICART AND QUICK-VIEW
*************************************************/

include get_template_directory() . '/woocommerce/minicart/actions.php';
include get_template_directory() . '/woocommerce/load-more/load-more.php';


/**
* Change number of products that are displayed per page (shop page)
*/
if ( ! function_exists( 'goldsmith_wc_shop_per_page' ) ) {
    add_filter( 'loop_shop_per_page', 'goldsmith_wc_shop_per_page', 20 );
    add_filter( 'dokan_store_products_per_page', 'goldsmith_wc_shop_per_page', 20 );
    function goldsmith_wc_shop_per_page( $cols )
    {
        if ( isset( $_GET['per_page'] ) && $_GET['per_page'] ) {
            return $_GET['per_page'];
        }

        $cols = apply_filters( 'goldsmith_wc_shop_per_page', goldsmith_settings( 'shop_perpage', '8' ) );

        if ( class_exists('WeDevs_Dokan') && dokan_is_store_page() ) {
            $store_user  = dokan()->vendor->get( get_query_var( 'author' ) );
            $store_info  = dokan_get_store_info( $store_user->get_id() );
            $cols        = dokan_get_option( 'store_products_per_page', 'dokan_general', 12 );

            return $cols;
        }

        return $cols;
    }
}


/**
* Change product column
*/
if ( ! function_exists( 'goldsmith_wc_product_column' ) ) {

    function goldsmith_wc_product_column()
    {
        if ( '1' == goldsmith_get_shop_column() ) {
            $listcol = goldsmith_settings('shop_list_type_colxl', '2');
            return apply_filters( 'goldsmith_product_column', 'row-cols-2 row-cols-md-3 row-cols-xl-'.$listcol.' goldsmith-product-list' );
        }
        if ( '2' == goldsmith_get_shop_column() ) {
            return apply_filters( 'goldsmith_product_column', 'row-cols-2 row-cols-sm-3 row-cols-lg-2' );
        }
        if ( '3' == goldsmith_get_shop_column() ) {
            return apply_filters( 'goldsmith_product_column', 'row-cols-2 row-cols-sm-3 row-cols-lg-3' );
        }
        if ( '4' == goldsmith_get_shop_column() ) {
            return apply_filters( 'goldsmith_product_column', 'row-cols-2 row-cols-sm-3 row-cols-lg-4' );
        }
        if ( '5' == goldsmith_get_shop_column() ) {
            return apply_filters( 'goldsmith_product_column', 'row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-4 row-cols-xxl-5' );
        }
        if ( '6' == goldsmith_get_shop_column() ) {
            return apply_filters( 'goldsmith_product_column', 'row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-4 row-cols-xxl-6' );
        }

        $col[] = 'row-cols-' . goldsmith_settings('shop_colxs', '2');
        $col[] = 'row-cols-sm-' . goldsmith_settings('shop_colsm', '2');
        $col[] = 'row-cols-lg-' . goldsmith_settings('shop_collg', '3');
        $col[] = 'row-cols-xl-' . goldsmith_settings('shop_colxl', '4');
        $col[] = 'row-cols-xxl-' . goldsmith_settings('shop_colxxl', '5');
        $col = implode( ' ', $col );

        return apply_filters( 'goldsmith_product_column', $col );
    }
}


/**
* Change number of upsells products column
*/
if ( ! function_exists( 'goldsmith_wc_sells_product_column' ) ) {

    function goldsmith_wc_sells_product_column()
    {
        $sells = is_cart() ? 'cross_sells' : 'upsells';
        $col[] = 'cart row-cols-' . goldsmith_settings('shop_'.$sells.'_colxs', '2');
        $col[] = 'row-cols-sm-' . goldsmith_settings('shop_'.$sells.'_colsm', '2');
        $col[] = 'row-cols-lg-' . goldsmith_settings('shop_'.$sells.'_collg', '3');
        $col[] = 'row-cols-xl-' . goldsmith_settings('shop_'.$sells.'_colxl', '4');
        $col   = implode( ' ', $col );
        return apply_filters( 'goldsmith_wc_sells_column', $col );
    }
}


/**
* Change number of related products output
*/
if ( ! function_exists( 'goldsmith_wc_related_products_limit' ) ) {

    add_filter( 'woocommerce_output_related_products_args', 'goldsmith_wc_related_products_limit', 20 );
    function goldsmith_wc_related_products_limit( $args )
    {
        $args['posts_per_page'] = apply_filters( 'goldsmith_wc_related_products_limit', goldsmith_settings('single_shop_related_count', '6') ); // 4 related products
        return $args;
    }
}


/**
* Theme custom filter and actions for woocommerce
*/

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
// add actions
add_action( 'woocommerce_shop_loop_item_title', 'goldsmith_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );
add_action( 'woocommerce_single_product_summary', 'goldsmith_product_countdown', 25 );
add_action( 'woocommerce_single_product_summary', 'goldsmith_product_stock_progress_bar', 26 );
add_action( 'woocommerce_product_meta_end', 'goldsmith_product_brands', 10 );

add_action( 'woocommerce_widget_shopping_cart_before_buttons', 'goldsmith_minicart_before_buttons', 10 );

function goldsmith_template_loop_product_title() {
    echo '<h6 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</h6>';
}

/**
* Clear Filters
*/
if ( ! function_exists( 'goldsmith_clear_filters' ) ) {
    function goldsmith_clear_filters() {

        $url = wc_get_page_permalink( 'shop' );
        $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();

        $min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
        $max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';

        if ( 0 < count( $_chosen_attributes ) || $min_price || $max_price ) {
            $reset_url = strtok( $url, '?' );
            if ( isset( $_GET['post_type'] ) ) {
                $reset_url = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $reset_url );
            }
            ?>
            <div class="goldsmith-clear-filters">
                <a href="<?php echo esc_url( $reset_url ); ?>"><?php echo esc_html__( 'Clear filters', 'goldsmith' ); ?></a>
            </div>
            <?php
        }
    }
    add_action( 'goldsmith_before_choosen_filters', 'goldsmith_clear_filters' );
}



/**
* Product thumbnail
*/
if ( ! function_exists( 'goldsmith_minicart_before_buttons' ) ) {
    function goldsmith_minicart_before_buttons()
    {
        if ( goldsmith_settings('header_cart_before_buttons', '' ) ) {
            ?>
            <div class="minicart-extra-text">
                <?php echo goldsmith_settings('header_cart_before_buttons', '' ); ?>
            </div>
            <?php
        }
    }
}

/**
* wp_get_attachment_image_attributes
*/
if ( ! function_exists( 'goldsmith_wp_get_attachment_image_attributes' ) ) {
    //add_filter( 'wp_lazy_loading_enabled', '__return_false' );
    //add_filter( 'wp_get_attachment_image_attributes', 'goldsmith_wp_get_attachment_image_attributes');
    function goldsmith_wp_get_attachment_image_attributes($attr) {
        if ( '1' != goldsmith_settings('theme_lazyload_images', '1' ) ){
            return $attr;
        }
        $placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
        $blank_img = get_template_directory().'/images/blank.gif';
        $blank_img = file_exists( $blank_img ) ? get_template_directory_uri().'/images/blank.gif' : $placeholder;
        $attr['data-src'] = $attr['src'];

        $attr['src'] = is_admin() || isset($attr['loading']) != 'lazy' ? $attr['src'] : $blank_img;

        $attr['data-srcset'] = isset( $attr['srcset'] ) ? $attr['srcset'] : '';

        unset( $attr['srcset'] );

        $attr['data-sizes']  = isset( $attr['sizes'] ) ? $attr['sizes'] : '';

        unset( $attr['sizes'] );

        return $attr;
    }
}


/**
* Product thumbnail
*/
if ( ! function_exists( 'shop_related_thumb_size' ) ) {
    function shop_related_thumb_size()
    {
        return apply_filters( 'shop_related_thumb_size', [370,370] );
    }
}

/**
* Product thumbnail
*/
if ( ! function_exists( 'goldsmith_loop_product_thumb' ) ) {
    function goldsmith_loop_product_thumb($column='')
    {
        global $product;
        $column     = isset( $_GET['column'] ) ? esc_html( $_GET['column'] ) : $column;
        $size       = goldsmith_settings('product_imgsize','woocommerce-thumbnail');

        $id         = $product->get_id();
        $size       = isset( $_POST['img_size'] ) != null ? $_POST['img_size'] : $size;
        $size       = apply_filters( 'goldsmith_product_thumb_size', $size );
        $attr       = !empty( $gallery ) ? 'product-thumb attachment-woocommerce_thumbnail size-'.$size : 'attachment-woocommerce_thumbnail size-'.$size;
        $show_video = get_post_meta( $id, 'goldsmith_product_video_on_shop', true );
        $iframe_id  = get_post_meta( $id, 'goldsmith_product_iframe_video', true );

        if ( $iframe_id && $show_video == 'yes' && ( is_shop() || is_product_category() || is_product_tag() ) ) {
            $iframe_html = '<iframe class="lazy" loading="lazy" data-src="https://www.youtube.com/embed/'.$iframe_id.'?playlist='.$iframe_id.'&modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" frameborder="0" allowfullscreen></iframe>';
            echo '<div class="goldsmith-loop-product-iframe-wrapper"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'"></a>'.$iframe_html.'</div>';
        } else {
            ?>
            <a href="<?php echo esc_url( get_permalink() ); ?>" class="product-link">
                <?php echo has_post_thumbnail() ? get_the_post_thumbnail( $id, $size, array( 'class' => $attr ) ) : wc_placeholder_img( $size ); ?>
            </a>
            <?php
        }
    }
}

/**
* Product thumbnail
*/
if ( ! function_exists( 'goldsmith_loop_product_thumb_overlay' ) ) {
    function goldsmith_loop_product_thumb_overlay($column='')
    {
        global $product;
        $column = isset( $_GET['column'] ) ? esc_html( $_GET['column'] ) : $column;
        $size   = goldsmith_settings('product_imgsize','woocommerce-thumbnail');

        $id           = $product->get_id();
        $size         = isset( $_POST['img_size'] ) != null ? $_POST['img_size'] : $size;
        $gallery      = $product->get_gallery_image_ids();
        $has_images   = !empty( $gallery ) && !wp_is_mobile() ? 'product-link has-images' : 'product-link';
        $attr         = !empty( $gallery ) ? 'product-thumb attachment-woocommerce_thumbnail size-'.$size : 'attachment-woocommerce_thumbnail size-'.$size;
        $show_video   = get_post_meta( $id, 'goldsmith_product_video_on_shop', true );
        $iframe_video = get_post_meta( $id, 'goldsmith_product_iframe_video', true );
        $show_gallery = get_post_meta( $id, 'goldsmith_loop_product_slider', true );
        $isshop       = is_shop() ? ' is-shop' : '';

        if ( $iframe_video && $show_video == 'yes' && ( is_shop() || wp_doing_ajax() ) ) {
            $iframe_html = '<iframe class="lazy" loading="lazy" data-src="https://www.youtube.com/embed/'.$iframe_video.'?playlist='.$iframe_video.'&modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1&start=5&end=25" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" frameborder="0" allowfullscreen></iframe>';
            echo '<div class="goldsmith-loop-product-iframe-wrapper"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'"></a>'.$iframe_html.'</div>';
        } elseif ( !empty( $gallery ) && $show_gallery == 'yes' ) {
            goldsmith_loop_product_gallery();
        } else {
            ?>
            <a href="<?php echo esc_url( get_permalink() ); ?>" class="<?php echo esc_attr( $has_images ); ?>">
                <?php
                echo has_post_thumbnail() ? get_the_post_thumbnail( $id, $size, array( 'class' => $attr ) ) : wc_placeholder_img( $size );

                if ( !empty( $gallery ) && !wp_is_mobile() ) {
                    echo wp_get_attachment_image( $gallery[0], $size, "", array( "class" => "overlay-thumb ".$isshop ) );
                }
                ?>
            </a>
            <?php
        }
    }
}

/**
* loop product gallery
*/
if ( ! function_exists( 'goldsmith_loop_product_gallery' ) ) {
    function goldsmith_loop_product_gallery($column='')
    {
        global $product;

        $column = isset( $_GET['column'] ) ? esc_html( $_GET['column'] ) : $column;
        $size   = goldsmith_settings('product_imgsize','woocommerce-thumbnail');

        $id           = $product->get_id();
        $data         = array();
        $show_gallery = get_post_meta( $id, 'goldsmith_loop_product_slider', true );
        $autoplay     = get_post_meta( $id, 'goldsmith_loop_product_slider_autoplay', true );
        $speed        = get_post_meta( $id, 'goldsmith_loop_product_slider_speed', true );
        $gallery      = $product->get_gallery_image_ids();
        $size         = isset( $_POST['img_size'] ) != null ? $_POST['img_size'] : $size;
        $size         = apply_filters( 'goldsmith_product_thumb_size', $size );
        $attr         = !empty( $gallery ) ? 'product-thumb attachment-woocommerce_thumbnail size-'.$size : 'attachment-woocommerce_thumbnail size-'.$size;
        $thumburl     = get_the_post_thumbnail_url( $id, $size, array( 'class' => $attr ) );
        $data[]       = 'yes' == $autoplay ? '"autoplay":true' : '"autoplay":false';
        $data[]       = is_numeric($speed) ? '"speed":'.round($speed) : '"speed":500';
        $data[]       = '"slidesPerView":1';
        $data[]       = '"pagination":{"el": ".swiper-pagination","type": "bullets","clickable":true}';
        $data         = apply_filters('goldsmith_loop_product_slider_options', $data);

        wp_enqueue_script( 'swiper' );

        if ( !empty( $gallery ) && 'yes' == $show_gallery ) {
            ?>
            <div class="goldsmith-loop-slider goldsmith-swiper-slider swiper-container" data-swiper-options='{<?php echo implode(',', $data ); ?>}'>
                <div class="swiper-wrapper">
                    <div class="goldsmith-loop-slider-item swiper-slide">
                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="product-link" data-img="<?php echo esc_url( $thumburl ); ?>">
                            <?php echo has_post_thumbnail() ? get_the_post_thumbnail( $id, $size, ['class'=>$attr ] ) : wc_placeholder_img( $size ); ?>
                        </a>
                    </div>
                    <?php
                    foreach ( $gallery as $img ) {
                        $imgurl = wp_get_attachment_image_url( $img, $size );
                        ?>
                        <div class="goldsmith-loop-slider-item swiper-slide">
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="product-link" data-img="<?php echo esc_url( $imgurl ); ?>">
                                <?php echo wp_get_attachment_image( $img, $size ); ?>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <?php
        } else {
            goldsmith_loop_product_thumb($column);
        }
    }
}


/**
* Add stock to loop
*/
if ( ! function_exists( 'goldsmith_loop_product_nostock' ) ) {
    function goldsmith_loop_product_nostock()
    {
        global $product;
        $stock = get_post_meta( $product->get_id(), 'goldsmith_product_hide_stock', true );

        if ( 'yes' == $stock ) {
            return;
        }

        if ( !$product->is_in_stock() ) {
            echo '<span class="goldsmith-small-title goldsmith-stock-status goldsmith-out-of-stock">'.esc_html__('Out of stock', 'goldsmith').'</span>';
        }
    }
}


/**
* Cart Button with Quantity Box
*/
if ( !function_exists( 'goldsmith_cart_with_quantity' ) ) {
    function goldsmith_cart_with_quantity()
    {
        ?>
        <div class="product-cart-with-quantity">
            <div class="quantity ajax-quantity">
                <div class="quantity-button minus">-</div>
                <input type="text" class="input-text qty text" name="quantity" value="1" title="Menge" size="4" inputmode="numeric">
                <div class="quantity-button plus">+</div>
            </div>
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
        <?php
    }
}


/**
* Product wishlist button
*/
if ( ! function_exists( 'goldsmith_single_product_buttons' ) ) {
    add_action( 'woocommerce_after_add_to_cart_button', 'goldsmith_single_product_buttons', 10 );
    function goldsmith_single_product_buttons()
    {
        if ( '1' == goldsmith_settings( 'product_action_wishlist_visibility', '1' ) || '1' == goldsmith_settings( 'product_action_compare_visibility', '1' ) ) {
            echo '<div class="product-after-cart-wrapper">';
            if ( goldsmith_settings( 'product_action_wishlist_visibility', '1' ) ) {
                echo goldsmith_wishlist_button();
            }
            if ( goldsmith_settings( 'product_action_compare_visibility', '1' ) ) {
                echo goldsmith_compare_button();
            }
            echo '</div>';
        }
        goldsmith_add_buy_now_button_single();
    }
}

/**
* Product quickview button
*/
if ( ! function_exists( 'goldsmith_quickview_button' ) ) {
    function goldsmith_quickview_button()
    {
        if ( ! class_exists( 'Goldsmith_QuickView' ) ) {
            return;
        }
        global $product;
        $id   = $product->get_id();
        $text = esc_html__( 'Quick View', 'goldsmith' );
        $icon = '<svg
        class="svgCompare goldsmith-svg-icon"
        width="512"
        height="512"
        fill="currentColor"
        viewBox="0 0 32 32"
        xmlns="http://www.w3.org/2000/svg"><use href="#shopEye"></use></svg>';

        return '<div class="goldsmith-quickview-btn goldsmith-product-button"
        data-id="'.$id.'"
        data-label="'.$text.'"><span class="goldsmith-product-hint">'.$text.'</span>'.$icon.'</div>';
    }
}


/**
* Product wishlist button
*/
if ( ! function_exists( 'goldsmith_wishlist_button' ) ) {
    function goldsmith_wishlist_button()
    {
        if ( ! class_exists( 'Goldsmith_Wishlist' ) ) {
            return;
        }
        global $product;
        $id   = $product->get_id();
        $text = esc_html__( 'Add to Wishlist', 'goldsmith' );
        $icon = '<svg
        class="svgCompare goldsmith-svg-icon"
        width="512"
        height="512"
        fill="currentColor"
        viewBox="0 0 32 32"
        xmlns="http://www.w3.org/2000/svg"><use href="#shopLove"></use></svg>';

        $type     = goldsmith_settings( 'product_action_btntype', 'icon' );
        $hint_pos = is_product() ? ' hint-top' : ' hint-left';
        $hint     = '<span class="goldsmith-product-hint'.$hint_pos.'">'.$text.'</span>';
        $is_btn   = is_product() && 'btn' == $type ? ' <span class="text">'.$text.'</span>' : '';

        return '<div class="goldsmith-wishlist-btn goldsmith-product-button type-'.$type.'"
        data-id="'.$id.'"
        data-label="'.$text.'">'.$hint.$icon.$is_btn.'</div>';
    }
}


/**
* Product compare button
*/
if ( ! function_exists( 'goldsmith_compare_button' ) ) {
    function goldsmith_compare_button()
    {
        global $product;
        $id = $product->get_id();

        if ( class_exists( 'WPCleverWoosc' ) ) {
            echo do_shortcode('[woosc type="link"]');
        } else {
            return goldsmith_compare_theme_button();
        }
    }
}

if ( ! function_exists( 'goldsmith_compare_theme_button' ) ) {
    function goldsmith_compare_theme_button()
    {
        if ( ! class_exists( 'Goldsmith_Compare' ) ) {
            return;
        }
        global $product;
        $id   = $product->get_id();
        $text = esc_html__( 'Compare', 'goldsmith' );
        $icon = '<svg
        class="svgCompare goldsmith-svg-icon"
        width="512"
        height="512"
        fill="currentColor"
        viewBox="0 0 32 32"
        xmlns="http://www.w3.org/2000/svg"><use href="#shopCompare"></use></svg>';

        $type     = goldsmith_settings( 'product_action_btntype', 'icon' );
        $hint_pos = is_product() ? ' hint-top' : ' hint-left';
        $hint     = '<span class="goldsmith-product-hint'.$hint_pos.'">'.$text.'</span>';
        $is_btn   = is_product() && 'btn' == $type ? ' <span class="text">'.$text.'</span>' : '';

        return '<div class="goldsmith-compare-btn goldsmith-product-button type-'.$type.'"
        data-id="'.$id.'"
        data-label="'.$text.'">'.$hint.$icon.$is_btn.'</div>';
    }
}

if ( ! function_exists( 'goldsmith_woosc_compare_button' ) ) {
    add_filter( 'woosc_button_html', 'goldsmith_woosc_compare_button',99,2 );
    function goldsmith_woosc_compare_button( $html, $id )
    {
        global $product;
        $id       = $product->get_id();
        $image_id = $product->get_image_id();
        $image    = wp_get_attachment_image_url( $image_id );
        $text     = esc_html__( 'Compare', 'goldsmith' );
        $icon = '<svg
        class="svgCompare goldsmith-svg-icon"
        width="512"
        height="512"
        fill="currentColor"
        viewBox="0 0 32 32"
        xmlns="http://www.w3.org/2000/svg"><use href="#shopCompare"></use></svg>';

        $type     = goldsmith_settings( 'product_action_btntype', 'icon' );
        $hint_pos = is_product() ? ' hint-top' : ' hint-left';
        $hint     = '<span class="goldsmith-product-hint'.$hint_pos.'">'.$text.'</span>';
        $is_btn   = is_product() && 'btn' == $type ? ' <span class="text">'.$text.'</span>' : '';

        $html = '<div class="goldsmith-product-button goldsmith-compare-btn woosc-btn wooscp-btn-'.esc_attr( $id ).' woosc-btn-has-icon woosc-btn-icon-only type-'.$type.'"
        data-id="'.esc_attr( $id ).'"
        data-product_name="'.esc_html($product->get_name()).'"
        data-product_image="' . esc_attr( $image ) . '" data-label="'.$text.'">'.$hint.$icon.$is_btn.'</div>';

        return $html;
    }
}


/**
* Product add to cart icon button
*/
if ( ! function_exists( 'goldsmith_add_to_cart' ) ) {
    function goldsmith_add_to_cart( $btn_type = 'text', $id = '' )
    {
        global $product;
        if ( $id ) {
            $product = wc_get_product($id);
        }
        if ( $product ) {
            $style      = apply_filters( 'goldsmith_loop_product_type', goldsmith_settings( 'shop_product_type', '2' ) );
            $id         = $product->get_id();
            $type       = $product->get_type();
            $url        = $product->add_to_cart_url();
            $sku        = $product->get_sku();
            $text       = esc_html( $product->add_to_cart_text() );
            $title      = esc_html( get_the_title() );
            $in_stock   = $product->is_purchasable() && $product->is_in_stock();
            $ot_ajax    = goldsmith_settings('ajax_addtocart','1');
            $is_ajax    = $product->supports( 'ajax_add_to_cart' );
            $ajax_class = $is_ajax && '1' == $ot_ajax ? ' goldsmith_ajax_add_to_cart' : ' ajax_add_to_cart';
            $class      = 'type-'.$type;
            $class     .= ($type == 'variable' || $type == 'grouped') && $style != 'woo' ? ' goldsmith-quick-shop-btn' : '';
            $class     .= $is_ajax && $in_stock ? $ajax_class : '';
            $icon       = $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'shopBag' : 'shopArrowRight';
            $icon       = '<svg
            class="svgCompare goldsmith-svg-icon"
            width="512"
            height="512"
            fill="currentColor"
            viewBox="0 0 32 32"
            xmlns="http://www.w3.org/2000/svg"><use href="#shopBag"></use></svg>';

            $in_cart = $p_type = $loader = $qty = '';

            if ( 'simple' == $type ) {

                if ( isset(WC()->cart)) {
                    foreach( WC()->cart->get_cart() as $cart_item ) {
                        if ( $cart_item['product_id'] === $id ){
                            $qty = $cart_item['quantity'];
                        }
                    }
                }

                $class .= $qty ? ' added' : '';
                $in_cart = $qty && $style == 'custom' ? ' in-cart' : '';
            }

            if ( $btn_type == 'button' ) {
                $class .= ' goldsmith-btn goldsmith-btn-dark goldsmith-product-cart';
            }
            if ( $btn_type == 'icon' ) {
                $class .= ' goldsmith-add-to-cart-icon-link';
            }
            if ( $btn_type == 'text' ) {
                $class .= ' goldsmith-btn-text';
            }

            $btn = apply_filters(
                'woocommerce_loop_add_to_cart_link',
                sprintf( '<a href="%s" data-quantity="1" data-product_id="%s" data-product_sku="%s" class="%s" data-oclass="%s" data-otitle="%s" rel="nofollow" title="%s">%s</a>',
                esc_url( $url ),
                $id,
                $sku,
                $class,
                $class,
                $text,
                $title,
                $btn_type == 'icon' ? '' : $text.$loader,
            ),$product);

            if ( $btn_type == 'icon' ) {
                return '<div
                class="goldsmith-add-to-cart-btn goldsmith-product-button btn-type-'.$btn_type.' product-type-'.$type.$in_cart.'"
                data-id="'.$id.'" data-label="'.$text.'">'.$btn.$icon.'</div>';
            } else {
                return $btn;
            }
        }
    }
}


/**
* Product quickview button
*/
if ( ! function_exists( 'goldsmith_quickview_button' ) ) {
    function goldsmith_quickview_button()
    {
        if ( class_exists( 'Goldsmith_QuickView' ) && '1' == goldsmith_settings('quick_view_visibility', '1') ) {
            echo do_shortcode( '[goldsmith_quickview]' );
        }
    }
}


/**
* Product discount label
*/
if ( ! function_exists( 'goldsmith_product_discount' ) ) {
    function goldsmith_product_discount($echo=true)
    {
        global $product;
        if ( '0' == goldsmith_settings('discount_visibility', '1') ) {
            return;
        }
        $discount = get_post_meta( $product->get_id(), 'goldsmith_product_discount', true );
        if ( 'yes' != $discount && $product->is_on_sale() && ! $product->is_type('variable') ) {

            $regular     = (float) $product->get_regular_price();
            $sale        = (float) $product->get_sale_price();
            $saving      = $sale && $regular ? round( 100 - ( $sale / $regular * 100 ), 0 ) : '';
            $saving_html = 'before' == goldsmith_settings('discount_percantage_position', 'after') ? '%'.$saving : $saving.'%';

            if ( $echo == true ) {
                echo !empty( $saving ) ? '<span class="goldsmith-label goldsmith-discount goldsmith-red">'.$saving_html.'</span>' : '';
            } else {
                return !empty( $saving ) ? '<span class="goldsmith-label goldsmith-discount goldsmith-red">'.$saving_html.'</span>' : '';
            }
        }
    }
}


/**
* Get all product categories
*/
if ( ! function_exists( 'goldsmith_product_all_categories' ) ) {
    function goldsmith_product_all_categories()
    {
        $cats = get_terms( 'product_cat' );
        $categories = array();

        if ( empty( $cats ) ) {
            return;
        }

        foreach ( $cats as $cat ) {
            $categories[] = '<a href="'.esc_url( get_term_link( $cat ) ) .'" >'. esc_html( $cat->name ) .'</a>';
        }
        return implode( ', ', $categories );
    }
}


/**
* Get all product tags
*/
if ( ! function_exists( 'goldsmith_product_tags' ) ) {
    function goldsmith_product_tags()
    {
        $tags = get_terms( 'product_tag' );
        $alltags = array();
        if ( empty( $tags ) ) {
            return;
        }
        foreach ( $tags as $tag ) {
            $alltags[] = '<a href="'.esc_url( get_term_link( $tag ) ) .'" >'. esc_html( $tag->name ) .'</a>';
        }
        return implode( ', ', $alltags );
    }
}


if ( ! function_exists( 'goldsmith_product_terms' ) ) {

    /**
    * Function to return list of the terms.
    *
    * @param string 'taxonomy'
    *
    * @return html Returns the list of elements.
    */

    function goldsmith_product_terms( $taxonomy, $label ) {

        $terms = get_the_terms( get_the_ID(), $taxonomy );

        if ( $terms && ! is_wp_error( $terms ) ) {

            $term_links = array();
            echo '<div class="goldsmith-meta-wrapper">';
                foreach ( $terms as $term ) {
                    $term_links[] = '<a href="' . esc_url( get_term_link( $term->slug, $taxonomy ) ) . '">' . $term->name . '</a>';
                }
                $all_terms = join( ', ', $term_links );

                echo !empty( $label ) ? '<span class="goldsmith-terms-label goldsmith-small-title">' . $label . '</span>' : '';
                echo '<span class="goldsmith-small-title terms-' . esc_attr( $term->slug ) . '">' . $all_terms . '</span>';
            echo '</div>';
        }
    }
}


/**
* Add product attribute name
*/
if ( ! function_exists( 'goldsmith_product_attr_label' ) ) {
    function goldsmith_product_attr_label()
    {
        global $product;

        $attributes = $product->get_attributes();
        foreach ( $attributes as $attribute ) {
            $values = array();
            $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ] = array(
                'label' => wc_attribute_label( $attribute->get_name() ),
                'value' => apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ),
            );
            $label = $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ]['label'];
            $value = $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ]['value'];
            echo !empty( $label ) ? '<span class="product-attr_label">'.$label.'</span>' : '';
        }
    }
}

/**
* product page gallery
*/
if ( ! function_exists( 'goldsmith_product_gallery_slider' ) ) {
    function goldsmith_product_gallery_slider()
    {
        global $product;
        $images = $product->get_gallery_image_ids();
        $size   = apply_filters( 'goldsmith_product_thumb_size', 'woocommerce_single' );
        $tsize  = goldsmith_settings( 'gallery_thumb_imgsize', '' );
        $id     = $product->get_id();

        // gallery top first thumbnail
        $img  = get_the_post_thumbnail( $id, $size );
        $full = get_the_post_thumbnail_url( $id, 'full' );
        $turl = get_the_post_thumbnail_url( $id, 'woocommerce_gallery_thumbnail' );

        $layout         = apply_filters('goldsmith_single_shop_layout', goldsmith_settings( 'single_shop_layout', 'full-width' ) );
        $thumb_position = apply_filters( 'goldsmith_product_gallery_thumb_position', goldsmith_settings( 'product_gallery_thumb_position', 'left' ) );

        $iframe_id      = get_post_meta( get_the_ID(), 'goldsmith_product_iframe_video', true );
        $popup_video    = get_post_meta( get_the_ID(), 'goldsmith_product_popup_video', true );
        $video_type     = apply_filters( 'goldsmith_product_video_type', get_post_meta( get_the_ID(), 'goldsmith_product_video_type', true ) );
        $video_src_type = get_post_meta( $id, 'goldsmith_product_video_source_type', true );
        $icon         = '<span class="goldsmith-product-popup small-popup"><svg
        class="svgExpand goldsmith-svg-icon"
        width="512"
        height="512"
        fill="currentColor"
        viewBox="0 0 512 512"
        xmlns="http://www.w3.org/2000/svg"><use href="#shopExpand"></use></svg></span>';

        $slider_class = 'goldsmith-product-gallery-main-image';
        if ( $images ) {
            $slider_class = 'goldsmith-product-gallery-main-slider goldsmith-swiper-main goldsmith-swiper-container goldsmith-swiper-theme-style nav-vertical-center';
        }

        $classes  = $images ? ' has-thumbs thumbs-'.$thumb_position : '';
        $classes .= ' '.$layout;
        ?>
        <div class="goldsmith-swiper-slider-wrapper<?php echo esc_attr( $classes ); ?>">

            <?php if ( $images && $thumb_position == 'top' ) { ?>
                <div class="goldsmith-product-thumbnails goldsmith-swiper-thumbnails goldsmith-swiper-container">
                    <div class="goldsmith-swiper-wrapper"></div>
                </div>
            <?php } ?>

            <div class="<?php echo esc_attr( $slider_class ); ?>">
                <?php
                if ( $popup_video && 'popup' == $video_type ) {
                    echo '<a data-fancybox href="'.$popup_video.'" class="goldsmith-product-video-button"><i class="nt-icon-button-play-2"></i></a>';
                }
                ?>
                <div class="goldsmith-swiper-wrapper goldsmith-gallery-items">
                    <?php
                    $iframe_html = '';
                    if ( 'gallery' == $video_type) {
                        if ( 'vimeo' == $video_src_type && $iframe_id ) {
                            $iframe_html = '<iframe class="lazy vimeo-video" loading="lazy" data-src="https://player.vimeo.com/video/'.$iframe_id.'?h=e1515b84ac&autoplay=1&loop=1&title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe><script src="https://player.vimeo.com/api/player.js"></script>';
                        } elseif ( 'hosted' == $video_src_type && $popup_video ) {
                            $iframe_html = '<video class="lazy hosted-video" autoplay muted loop><source src="'.$popup_video.'" type="video/mp4"></video>';
                        } else {
                            if ( $iframe_id  ) {
                                $iframe_html = '<iframe class="lazy youtube-video" loading="lazy" data-src="https://www.youtube.com/embed/'.$iframe_id.'?playlist='.$iframe_id.'&modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" frameborder="0" allowfullscreen></iframe>';
                            }
                        }
                        if ( $iframe_html ) {
                            echo '<div class="swiper-slide swiper-slide-video-item iframe-video"><div class="goldsmith-slide-iframe-wrapper" data-src="'.esc_url( $popup_video ).'" data-fancybox="gallery">'.$icon.$iframe_html.'</div></div>';
                        }
                    }
                    $data_thumb = 'thumb' == $tsize ? ' data-thumb="'.$turl.'"' : '';
                    echo '<div class="swiper-slide goldsmith-swiper-slide-first" data-src="'.$full.'"'.$data_thumb.' data-fancybox="gallery">'.$icon.$img.'</div>';
                    foreach ( $images as $image ) {
                        $gimg = wp_get_attachment_image( $image, $size );
                        $gurl = wp_get_attachment_image_url( $image, 'full' );
                        $turl = wp_get_attachment_image_url( $image, 'woocommerce_gallery_thumbnail' );
                        $data_thumb = 'thumb' == $tsize ? ' data-thumb="'.$turl.'"' : '';
                        echo '<div class="swiper-slide" data-src="'.$gurl.'"'.$data_thumb.' data-fancybox="gallery">'.$icon.$gimg.'</div>';
                    }
                    ?>
                </div>

                <?php if ( $images ) { ?>
                    <div class="goldsmith-swiper-prev goldsmith-swiper-btn goldsmith-nav-bg"></div>
                    <div class="goldsmith-swiper-next goldsmith-swiper-btn goldsmith-nav-bg"></div>
                <?php } ?>

                <?php do_action( 'goldsmith_product_360_view' ); ?>

            </div>

            <?php if ( $images && $thumb_position != 'top' ) { ?>
                <div class="goldsmith-product-thumbnails goldsmith-swiper-thumbnails goldsmith-swiper-container">
                    <div class="goldsmith-swiper-wrapper"></div>
                </div>
            <?php } ?>

        </div>
        <?php
    }
}


/**
* product page gallery
*/
if ( ! function_exists( 'goldsmith_product_gallery_stretch' ) ) {
    function goldsmith_product_gallery_stretch()
    {
        global $product;
        $images = $product->get_gallery_image_ids();
        $size   = apply_filters( 'goldsmith_product_thumb_size', 'woocommerce_single' );
        $tsize  = goldsmith_settings( 'gallery_thumb_imgsize', '' );
        $id     = $product->get_id();

        // gallery top first thumbnail
        $img  = get_the_post_thumbnail( $id, $size );
        $full = get_the_post_thumbnail_url( $id, 'full' );
        $turl = get_the_post_thumbnail_url( $id, 'woocommerce_gallery_thumbnail' );

        $iframe_video = get_post_meta( get_the_ID(), 'goldsmith_product_iframe_video', true );
        $popup_video  = get_post_meta( get_the_ID(), 'goldsmith_product_popup_video', true );
        $video_type   = apply_filters( 'goldsmith_product_video_type', get_post_meta( get_the_ID(), 'goldsmith_product_video_type', true ) );
        $icon         = '<span class="goldsmith-product-popup small-popup"><svg
        class="svgExpand goldsmith-svg-icon"
        width="512"
        height="512"
        fill="currentColor"
        viewBox="0 0 512 512"
        xmlns="http://www.w3.org/2000/svg"><use href="#shopExpand"></use></svg></span>';

        $slider_class = 'goldsmith-product-gallery-main-image';
        if ( $images ) {
            $slider_class = 'goldsmith-product-gallery-main-slider goldsmith-swiper-main goldsmith-swiper-container goldsmith-swiper-theme-style nav-vertical-center';
        }

        ?>
        <div class="goldsmith-swiper-slider-wrapper">

            <div class="<?php echo esc_attr( $slider_class ); ?>">
                <?php
                if ( $popup_video && 'popup' == $video_type ) {
                    echo '<a data-fancybox href="'.$popup_video.'" class="goldsmith-product-video-button"><i class="nt-icon-button-play-2"></i></a>';
                }
                ?>
                <div class="goldsmith-swiper-wrapper goldsmith-gallery-items">
                    <?php
                    $data_thumb = 'thumb' == $tsize ? ' data-thumb="'.$turl.'"' : '';
                    echo '<div class="swiper-slide goldsmith-swiper-slide-first" data-src="'.$full.'"'.$data_thumb.' data-fancybox="gallery">'.$icon.$img.'</div>';
                    foreach ( $images as $image ) {
                        $gimg = wp_get_attachment_image( $image, $size );
                        $gurl = wp_get_attachment_image_url( $image, 'full' );
                        $turl = wp_get_attachment_image_url( $image, 'woocommerce_gallery_thumbnail' );
                        $data_thumb = 'thumb' == $tsize ? ' data-thumb="'.$turl.'"' : '';
                        echo '<div class="swiper-slide" data-src="'.$gurl.'"'.$data_thumb.' data-fancybox="gallery">'.$icon.$gimg.'</div>';
                    }
                    if ( $iframe_video && 'gallery' == $video_type ) {
                        $iframe_html = '<iframe class="lazy"
                        loading="lazy" data-src="https://www.youtube.com/embed/'.$iframe_video.'?playlist='.$iframe_video.'&modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1"
                        allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        frameborder="0" allowfullscreen></iframe>';
                        echo '<div class="swiper-slide swiper-slide-video-item iframe-video" data-type="iframe" data-src="'.esc_url( $popup_video ).'" data-fancybox="gallery"><div class="goldsmith-slide-iframe-wrapper">'.$icon.$iframe_html.'</div></div>';
                    }
                    ?>
                </div>

                <?php if ( $images ) { ?>
                    <div class="goldsmith-swiper-prev goldsmith-swiper-btn goldsmith-nav-bg"></div>
                    <div class="goldsmith-swiper-next goldsmith-swiper-btn goldsmith-nav-bg"></div>
                <?php } ?>

                <?php do_action( 'goldsmith_product_360_view' ); ?>

            </div>

            <?php if ( $images ) { ?>
                <div class="goldsmith-product-thumbnails goldsmith-swiper-thumbnails goldsmith-swiper-container">
                    <div class="goldsmith-swiper-wrapper"></div>
                </div>
            <?php } ?>

        </div>
        <?php
    }
}


/**
* product page gallery
*/
if ( ! function_exists( 'goldsmith_product_gallery_carousel_slider' ) ) {
    function goldsmith_product_gallery_carousel_slider()
    {
        global $product;
        $images    = $product->get_gallery_image_ids();
        $size      = apply_filters( 'goldsmith_product_thumb_size', 'woocommerce_single' );
        $container = apply_filters( 'goldsmith_product_showcase_carousel_width', goldsmith_settings('single_shop_showcase_carousel_width', 'fullwidth' ) );
        $container = $container == 'boxed' ? ' goldsmith-container' : '';
        $id        = $product->get_id();

        // gallery top first thumbnail
        $img   = get_the_post_thumbnail( $id, $size );
        $url   = get_the_post_thumbnail_url( $id, $size );
        $full  = get_the_post_thumbnail_url( $id, 'full' );

        // gallery bottom first thumbnail
        $tsize = [90,90];
        $timg  = get_the_post_thumbnail( $id, $tsize );

        $iframe_video = get_post_meta( get_the_ID(), 'goldsmith_product_iframe_video', true );
        $popup_video  = get_post_meta( get_the_ID(), 'goldsmith_product_popup_video', true );
        $video_type   = apply_filters( 'goldsmith_product_video_type', get_post_meta( get_the_ID(), 'goldsmith_product_video_type', true ) );
        $icon         = '<span class="goldsmith-product-popup small-popup"><svg
        class="svgExpand goldsmith-svg-icon"
        width="512"
        height="512"
        fill="currentColor"
        viewBox="0 0 512 512"
        xmlns="http://www.w3.org/2000/svg"><use href="#shopExpand"></use></svg></span>';

        $slider_options = json_encode( apply_filters('goldsmith_product_gallery_showcase_js_options',
            array(
                "loop"                 => '1' == goldsmith_settings('single_shop_showcase_carousel_loop', '1') ? true : false,
                "roundLengths"         => true,
                "speed"                 => 800,
                "spaceBetween"          => 0,
                "slidesPerView"         => '1',
                "direction"             => "horizontal",
                "effect"                => "slide",
                "wrapperClass"          => "goldsmith-swiper-wrapper",
                "slideActiveClass"      => "active",
                "centeredSlides"        => true,
                "slideToClickedSlide"   => true,
                "grabCursor"            => true,
                "autoHeight"            => false,
                "autoPlay"              => false,
                "rewind"                => false,
                "observer"              => true,
                "observeParents"        => true,
                "observeSlideChildren"  => true,
                "watchOverflow"         => true,
                "watchSlidesVisibility" => true,
                "watchSlidesProgress"   => true,
                "navigation"            => [
                    "nextEl" => ".goldsmith-product-showcase-main .goldsmith-swiper-next",
                    "prevEl" => ".goldsmith-product-showcase-main .goldsmith-swiper-prev"
                ],
                "pagination"           => [
                    "el"                => ".goldsmith-product-showcase-main .goldsmith-swiper-pagination",
                    "bulletClass"       => "goldsmith-swiper-bullet",
                    "bulletActiveClass" => "active",
                    "type"              => "bullets",
                    "clickable"         => true
                ],
                "effect"               => goldsmith_settings('single_shop_showcase_carousel_effect_type', ''),
                "coverflowEffect"      => [
                    "rotate"       => goldsmith_settings('single_shop_showcase_carousel_coverflow_rotate', ''),
                    "slideShadows" => false
                ],
                "breakpoints"          => [
                    "768" => [
                        "slidesPerView" => 3
                    ],
                    "1024" => [
                        "slidesPerView" => 4
                    ]
                ]
            )
        ));

        ?>
        <div class="goldsmith-swiper-showcase-wrapper<?php echo esc_attr($container); ?>">

            <div class="goldsmith-product-showcase-main goldsmith-swiper-main goldsmith-swiper-container goldsmith-swiper-theme-style nav-vertical-center" data-swiper-options="<?php echo esc_attr( $slider_options ); ?>">

                <div class="goldsmith-swiper-wrapper goldsmith-gallery-items">
                    <?php
                    echo '<div class="swiper-slide goldsmith-swiper-slide-first" data-src="'.$full.'" data-fancybox="gallery">'.$icon.$img.'</div>';
                    $countt = 2;
                    foreach ( $images as $image ) {
                        $gimg = wp_get_attachment_image( $image, $size );
                        $gurl = wp_get_attachment_image_url( $image, 'full' );
                        $turl = wp_get_attachment_image_url( $image, 'woocommerce_gallery_thumbnail' );
                        $data_thumb = 'thumb' == $tsize ? ' data-thumb="'.$turl.'"' : '';
                        echo '<div class="swiper-slide" data-src="'.$gurl.'" data-fancybox="gallery">'.$icon.$gimg.'</div>';
                    }
                    if ( $iframe_video && 'gallery' == $video_type ) {
                        $iframe_html = '<iframe class="lazy"
                        loading="lazy"
                        data-src="https://www.youtube.com/embed/'.$iframe_video.'?playlist='.$iframe_video.'&modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1"
                        allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        frameborder="0" allowfullscreen></iframe>';
                        echo '<div class="swiper-slide swiper-slide-video-item iframe-video" data-type="iframe" data-src="'.esc_url( $popup_video ).'" data-fancybox="gallery"><div class="goldsmith-slide-iframe-wrapper">'.$icon.$iframe_html.'</div></div>';
                    }
                    ?>
                </div>

                <?php if ( '1' == apply_filters( 'goldsmith_product_showcase_carousel_dots', goldsmith_settings('single_shop_showcase_carousel_dots', '1' ) ) ) { ?>
                    <div class="goldsmith-swiper-pagination position-relative"></div>
                <?php } ?>

                <div class="goldsmith-swiper-prev goldsmith-swiper-btn goldsmith-nav-bg"></div>
                <div class="goldsmith-swiper-next goldsmith-swiper-btn goldsmith-nav-bg"></div>

                <?php do_action( 'goldsmith_product_360_view' ); ?>

            </div>

            <?php if ( '1' == apply_filters( 'goldsmith_product_showcase_carousel_thumbs', goldsmith_settings('single_shop_showcase_carousel_thumbs', '0' ) ) ) { ?>
                <div class="goldsmith-product-showcase-thumbnails goldsmith-swiper-thumbnails goldsmith-slider-thumbnails-full goldsmith-swiper-container">
                    <div class="goldsmith-swiper-wrapper goldsmith-justify-center">
                        <?php
                        echo '<div class="swiper-slide goldsmith-swiper-slide-first">'.$timg.'</div>';
                        foreach ( $images as $image ) {
                            echo '<div class="swiper-slide">'.wp_get_attachment_image( $image, $tsize ).'</div>';
                        }
                        if ( $iframe_video && 'gallery' == $video_type ) {
                            echo '<div class="swiper-slide swiper-slide-video-item"><div class="goldsmith-slide-video-item-icon"><i class="nt-icon-button-play-2"></i></div></div>';
                        }
                        ?>
                    </div>
                </div>
            <?php } ?>

        </div>
        <?php
    }
}


/**
* product page gallery
*/
if ( ! function_exists( 'goldsmith_product_gallery_grid' ) ) {
    function goldsmith_product_gallery_grid()
    {
        global $product;
        $column = apply_filters( 'goldsmith_product_gallery_type_column', goldsmith_settings( 'goldsmith_product_gallery_grid_column', '2' ) );
        $images = $product->get_gallery_image_ids();
        $size   = apply_filters( 'goldsmith_product_thumb_size', 'woocommerce_single' );
        $id     = $product->get_id();

        // gallery top first thumbnail
        $img = get_the_post_thumbnail( $product->get_id(), $size );
        $url = get_the_post_thumbnail_url( $product->get_id(), $size );

        $iframe_video = get_post_meta( get_the_ID(), 'goldsmith_product_iframe_video', true );
        $popup_video  = get_post_meta( get_the_ID(), 'goldsmith_product_popup_video', true );
        $video_type   = apply_filters( 'goldsmith_product_video_type', get_post_meta( get_the_ID(), 'goldsmith_product_video_type', true ) );
        $icon         = '<span class="goldsmith-product-popup small-popup"><svg
        class="svgExpand goldsmith-svg-icon"
        width="512"
        height="512"
        fill="currentColor"
        viewBox="0 0 512 512"
        xmlns="http://www.w3.org/2000/svg"><use href="#shopExpand"></use></svg></span>';

        switch ( $column ) {
            case '1':
                $tsize = 'woocommerce_single';
                break;
            case '2':
                $tsize = [500,500];
                break;
            case '3':
                $tsize = [300,300];
                break;
            case '4':
                $tsize = [200,200];
                break;
            default:
                $tsize = [400,400];
                break;
        }
        ?>
        <div class="goldsmith-product-main-gallery-grid grid-column-<?php echo esc_attr( $column ); ?>">
            <?php
            if ( $iframe_video || $popup_video ) {
                if ( 'gallery' == $video_type ) {
                    if ( $iframe_video ) {
                        $iframe_html = '<iframe class="lazy" loading="lazy" src="https://www.youtube.com/embed/'.$iframe_video.'?modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1&start=5&end=25" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" frameborder="0" allowfullscreen></iframe>';
                        echo '<div class="goldsmith-gallery-grid-item goldsmith-gallery-grid-video-item iframe-video" data-type="iframe" data-src="'.esc_url( $popup_video ).'" data-fancybox="gallery">'.$icon.$iframe_html.'</div>';
                    }
                } else {
                    if ( $popup_video ) {
                        echo '<a data-fancybox href="'.$popup_video.'" class="goldsmith-product-video-button"><i class="nt-icon-button-play-2"></i></a>';
                    }
                }
            }

            echo '<div class="goldsmith-gallery-grid-item goldsmith-gallery-grid-item-first first" data-src="'.esc_url($url).'" data-fancybox="gallery">'.$icon.$img.'</div>';
            if ( !empty( $images ) ) {
                echo '<div class="row row-cols-1 row-cols-sm-'.esc_attr( $column ).'">';
                foreach ( $images as $image ) {
                    $gimg = wp_get_attachment_image( $image, $tsize );
                    $gurl = wp_get_attachment_image_url( $image, 'full' );
                    echo '<div class="col goldsmith-gallery-grid-item" data-src="'.esc_url($gurl).'" data-fancybox="gallery">'.$icon.$gimg.'</div>';
                }
                echo '</div>';
            }
            ?>
        </div>
        <?php
    }
}


if ( ! function_exists( 'goldsmith_single_product_nav_two' ) ) {
    function goldsmith_single_product_nav_two() {

        if ( '0' == goldsmith_settings('single_shop_nav_visibility', '1') ) {
            return;
        }
        $prev    = get_previous_post();
        $prevID  = $prev ? $prev->ID : '';
        $next    = get_next_post();
        $nextID  = $next ? $next->ID : '';
        $imgSize = array(40,40,true);
        ?>
        <div class="goldsmith-product-nav goldsmith-flex goldsmith-align-center">
            <?php if ( $prevID ) : ?>
                <a class="product-nav-link goldsmith-nav-prev" href="<?php echo esc_url( get_permalink( $prevID ) ); ?>">
                    <span class="goldsmith-nav-arrow nt-icon-left-arrow-chevron"></span>
                    <span class="product-nav-content">
                        <?php echo apply_filters( 'goldsmith_products_nav_image', get_the_post_thumbnail( $prevID, $imgSize ) ); ?>
                        <span class="product-nav-title"><?php echo get_the_title( $prevID ); ?></span>
                    </span>
                </a>
            <?php else : ?>
                <a class="product-nav-link goldsmith-nav-prev disabled" href="#0">
                    <span class="goldsmith-nav-arrow nt-icon-left-arrow-chevron"></span>
                </a>
            <?php endif ?>

            <a href="<?php echo apply_filters( 'goldsmith_single_product_back_btn_url', get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="product-nav-link goldsmith-nav-shop">
                <span class="goldsmith-shop-link-inner">
                    <span class="goldsmith-shop-link-icon"></span>
                    <span class="goldsmith-shop-link-icon"></span>
                </span>
            </a>

            <?php if ( $nextID ) : ?>
                <a class="product-nav-link goldsmith-nav-next" href="<?php echo esc_url( get_permalink( $nextID ) ); ?>">
                    <span class="goldsmith-nav-arrow nt-icon-right-arrow-chevron"></span>
                    <span class="product-nav-content">
                        <?php echo apply_filters( 'goldsmith_products_nav_image', get_the_post_thumbnail( $nextID, $imgSize ) ); ?>
                        <span class="product-nav-title"><?php echo get_the_title( $nextID ); ?></span>
                    </span>
                </a>
            <?php else : ?>
                <a class="product-nav-link goldsmith-nav-next disabled" href="#0">
                    <span class="goldsmith-nav-arrow nt-icon-right-arrow-chevron"></span>
                </a>
            <?php endif ?>
        </div>
        <?php
    }
}


/**
* Add stock progressbar
*/
if ( ! function_exists( 'goldsmith_product_stock_progress_bar' ) ) {
    function goldsmith_product_stock_progress_bar() {
        if ( '1' == goldsmith_settings( 'woo_catalog_mode', '0' ) ) {
            return;
        }
        global $post,$product;
        $product_id   = $post->ID;
        $progressbar  = goldsmith_settings( 'single_shop_progressbar_visibility', '0' );
        $manage_stock = get_post_meta( $product_id, '_manage_stock', true );

        if ( $manage_stock != 'yes' || '0' == $progressbar ) {
            return;
        }

        $current_stock = get_post_meta( $product_id, '_stock', true );
        $total_sold    = $product->get_total_sales();
        $percentage    = $total_sold > 0 && $current_stock > 0 ? round( $total_sold / $current_stock * 100 ) : 0;

        if ( $current_stock > 0 ) {
            ?>
            <div class="goldsmith-summary-item goldsmith-single-product-stock">
                <div class="stock-details">
                    <div class="stock-sold"><?php esc_html_e( 'Ordered:', 'goldsmith' ); ?><span> <?php echo esc_html( $total_sold ); ?></span></div>
                    <div class="current-stock"><?php esc_html_e( 'Items available:', 'goldsmith' ); ?><span> <?php echo esc_html( wc_trim_zeros( $current_stock ) ); ?></span></div>
                </div>
                <div class="goldsmith-product-stock-progress">
                    <div class="goldsmith-product-stock-progressbar" data-stock-percent="<?php echo esc_attr( $percentage ); ?>%"></div>
                </div>
            </div>
            <?php
        }
    }
}

/**
* Add size guide popup
*/
if ( ! function_exists( 'goldsmith_product_popup_details' ) ) {
    add_action( 'woocommerce_single_product_summary', 'goldsmith_product_popup_details', 35 );
    function goldsmith_product_popup_details()
    {
        $product_id = get_the_ID();
        $guide      = goldsmith_settings( 'single_shop_size_guide_template', null );
        $delivery   = goldsmith_settings( 'single_shop_delivery_template', null );
        if ( $guide || $delivery ) {
            ?>
            <div class="goldsmith-summary-item goldsmith-product-popup-details">
                <?php
                goldsmith_product_delivery_return();
                goldsmith_product_size_guide();
                goldsmith_product_estimated_delivery();
                ?>
            </div>
            <?php
        }
    }
}


/**
* Add question form popup
*/
if ( ! function_exists( 'goldsmith_product_size_guide' ) ) {
    function goldsmith_product_size_guide()
    {
        global $product;
        $id          = $product->get_id();
        $guide_id    = get_post_meta( $id, 'goldsmith_product_size_guide', true );
        $template_id = $guide_id ? $guide_id : goldsmith_settings( 'single_shop_size_guide_template', null );

        if ( null == $template_id || '' == $template_id ) {
            return;
        }

        $cats            = wc_get_product_term_ids( $id, 'product_cat' );
        $tags            = wc_get_product_term_ids( $id, 'product_tag' );
        $total_terms     = !empty( $cats ) && !empty( $tags ) ? array_merge( $cats, $tags ) : $cats;
        $total_terms[]   = $id;
        $cat_exclude     = goldsmith_settings( 'single_shop_size_guide_template_category_exclude', null );
        $cat_exclude     = $cat_exclude ? $cat_exclude : array();
        $tag_exclude     = goldsmith_settings( 'single_shop_size_guide_template_tag_exclude', null );
        $tag_exclude     = $tag_exclude ? $tag_exclude : array();
        $product_exclude = goldsmith_settings( 'single_shop_size_guide_template_product_exclude', null );
        $product_exclude = $product_exclude ? $product_exclude : array();
        $total_exclude   = array_merge( $cat_exclude, $tag_exclude, $product_exclude );

        if ( array_intersect( $total_exclude, $total_terms ) ) {
            return;
        }
        wp_enqueue_script( 'magnific');

        ?>
        <div class="goldsmith-product-question-btn has-svg-icon goldsmith-flex goldsmith-align-center">
            <?php
            if ( '' != goldsmith_settings('size_guide_icon', '') ) {
                echo goldsmith_settings('size_guide_icon', '');
            } else {
                echo goldsmith_svg_lists('ruler', 'goldsmith-svg-icon');
            }
            ?>&nbsp;
            <a href="#goldsmith_product_question_<?php echo esc_attr( $template_id ); ?>" class="goldsmith-open-popup">
            <?php
            if ( '' != goldsmith_settings('size_guide_text', '') ) {
                echo goldsmith_settings('size_guide_text', '');
            } else {
                esc_html_e( 'Size Guide', 'goldsmith' );
            }
            ?></a>
        </div>
        <div class="goldsmith-single-product-question goldsmith-popup-content-big zoom-anim-dialog mfp-hide" id="goldsmith_product_question_<?php echo esc_attr( $template_id ); ?>">
            <?php
            if ( $guide_id ) {
                echo do_shortcode('[goldsmith-template id="'.$guide_id.'" css="yes"]');
            } else {
                echo goldsmith_print_elementor_templates( 'single_shop_size_guide_template', '' );
            }
            ?>
        </div>
        <?php
    }
}


/**
* Add delivery and return popup
*/
if ( ! function_exists( 'goldsmith_product_delivery_return' ) ) {
    function goldsmith_product_delivery_return()
    {
        global $product;

        $template_id = goldsmith_settings( 'single_shop_delivery_template', null );

        if ( null == $template_id || '' == $template_id ) {
            return;
        }

        $id              = $product->get_id();
        $cats            = wc_get_product_term_ids( $id, 'product_cat' );
        $tags            = wc_get_product_term_ids( $id, 'product_tag' );
        $total_terms     = !empty( $cats ) && !empty( $tags ) ? array_merge( $cats, $tags ) : $cats;
        $total_terms[]   = $id;
        $cat_exclude     = goldsmith_settings( 'single_shop_delivery_template_category_exclude', null );
        $cat_exclude     = $cat_exclude ? $cat_exclude : array();
        $tag_exclude     = goldsmith_settings( 'single_shop_delivery_template_tag_exclude', null );
        $tag_exclude     = $tag_exclude ? $tag_exclude : array();
        $product_exclude = goldsmith_settings( 'single_shop_delivery_template_product_exclude', null );
        $product_exclude = $product_exclude ? $product_exclude : array();
        $total_exclude   = array_merge( $cat_exclude, $tag_exclude, $product_exclude );

        if ( array_intersect( $total_exclude, $total_terms ) ) {
            return;
        }
        wp_enqueue_script( 'magnific');
        ?>
        <div class="goldsmith-product-delivery-btn has-svg-icon goldsmith-flex goldsmith-align-center">
            <?php
            if ( '' != goldsmith_settings('delivery_return_icon', '') ) {
                echo goldsmith_settings('delivery_return_icon', '');
            } else {
                echo goldsmith_svg_lists('delivery-return', 'goldsmith-svg-icon');
            }
            ?>&nbsp;
            <a href="#goldsmith_product_delivery_<?php echo esc_attr( $template_id ); ?>" class="goldsmith-open-popup">
                <?php
                if ( '' != goldsmith_settings('delivery_return_text', '') ) {
                    echo goldsmith_settings('delivery_return_text', '');
                } else {
                    esc_html_e( 'Delivery & Return', 'goldsmith' );
                }
            ?></a>
        </div>
        <div class="goldsmith-single-product-delivery goldsmith-popup-content-big zoom-anim-dialog mfp-hide" id="goldsmith_product_delivery_<?php echo esc_attr( $template_id ); ?>">
            <?php echo goldsmith_print_elementor_templates( 'single_shop_delivery_template', '' ); ?>
        </div>
        <?php
    }
}


if ( ! function_exists( 'goldsmith_product_visitiors_message' ) ) {
    add_action( 'woocommerce_single_product_summary', 'goldsmith_product_visitiors_message',39 );
    function goldsmith_product_visitiors_message()
    {
        if ( '1' == goldsmith_settings( 'woo_catalog_mode', '0' ) ) {
            return;
        }
        if ( '0' == goldsmith_settings('product_visitiors_message_visibility', '0' )  ) {
            return;
        }

        $text1 = '' != goldsmith_settings('product_visitiors_message_text1') ? goldsmith_settings('product_visitiors_message_text1') : esc_html__('Other people want this.','goldsmith');
        $text2 = '' != goldsmith_settings('product_visitiors_message_text2') ? goldsmith_settings('product_visitiors_message_text2') : esc_html__('people have this in their carts right now. It\'s running out!','goldsmith');

        if ( 'fake' == goldsmith_settings('product_visitiors_message_type', 'default' ) ) {

            wp_enqueue_script( 'jquery-cookie');
            global $product;

            $data[] = goldsmith_settings( 'visit_count_min' ) ? '"min":' . goldsmith_settings( 'visit_count_min' ) : '"min":10';
            $data[] = goldsmith_settings( 'visit_count_max' ) ? '"max":' . goldsmith_settings( 'visit_count_max' ) : '"max":50';
            $data[] = goldsmith_settings( 'visit_count_delay' ) ? '"delay":' . goldsmith_settings( 'visit_count_delay' ) : '"delay":30000';
            $data[] = goldsmith_settings( 'visit_count_change' ) ? '"change":' . goldsmith_settings( 'visit_count_change' ) : '"change":5';
            $data[] = '"id":' . $product->get_id();

            ?>
            <div class="goldsmith-summary-item goldsmith-product-view goldsmith-visitors-product-message goldsmith-warning" data-product-view='{<?php echo implode(',', $data ); ?>}'>
                <?php echo goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ); ?>
                <div class="goldsmith-visitors-product-text">
                    <span class="goldsmith-view-count"></span> <?php echo esc_html($text2); ?>
                </div>
            </div>
            <?php

        } else {

            global $wpdb, $product;
            $in_basket = 0;
            $wc_session_data = $wpdb->get_results( "SELECT session_key FROM {$wpdb->prefix}woocommerce_sessions" );
            $wc_session_keys = wp_list_pluck( $wc_session_data, 'session_key' );

            if ( $wc_session_keys ) {
                foreach ( $wc_session_keys as $key => $_customer_id ) {
                    // if you want to skip current viewer cart item in counts or else can remove belows checking
                    if( WC()->session->get_customer_id() == $_customer_id ) continue;

                    $session_contents = WC()->session->get_session( $_customer_id, array() );
                    $cart_contents = maybe_unserialize( $session_contents['cart'] );
                    if( $cart_contents ){
                        foreach ( $cart_contents as $cart_key => $item ) {
                            if( $item['product_id'] == $product->get_id() ) {
                                $in_basket += 1;
                            }
                        }
                    }
                }
            }

            if ( $in_basket ) {
                ?>
                <div class="goldsmith-summary-item goldsmith-visitors-product-message goldsmith-warning">
                    <?php echo goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ); ?>
                    <div class="goldsmith-visitors-product-text">
                        <strong><?php echo esc_html($text1); ?> </strong>
                        <?php echo sprintf( '%d  %s', $in_basket,$text2 ); ?>
                    </div>
                </div>
                <?php
            }
        }
    }
}


if ( ! function_exists( 'goldsmith_product_estimated_delivery' ) ) {
    function goldsmith_product_estimated_delivery() {

        if ( '0' == goldsmith_settings('single_shop_estimated_delivery_visibility', '0' ) ) {
            return;
        }

        $min_ed = goldsmith_settings('single_shop_min_estimated_delivery');
        $max_ed = goldsmith_settings('single_shop_max_estimated_delivery');

        $min   = $min_ed ? $min_ed : 3;
        $from  = '+' . $min;
        $from .= ' ' . ( $min = 1 ? 'day' : 'days' );

        $max = $max_ed ? (int) $max_ed : 7;
        $to  = '+' . $max;
        $to .= ' ' . ( $max = 1 ? 'day' : 'days' );

        $now      = get_date_from_gmt( date('Y-m-d H:i:s'), 'Y-m-d' );
        $est_days = array();

        $format     = esc_html__( 'M d', 'goldsmith' );
        $est_days[] = date_i18n( $format, strtotime( $now . $from ), true );
        $est_days[] = date_i18n( $format, strtotime( $now . $to ), true );

        if ( !empty( $est_days ) ) {
            ?>
            <div class="goldsmith-estimated-delivery">
                <?php
                if ( '' != goldsmith_settings('estimated_delivery_icon', '') ) {
                    echo goldsmith_settings('estimated_delivery_icon', '');
                } else {
                    echo goldsmith_svg_lists('shipping', 'goldsmith-svg-icon');
                }
                ?>&nbsp;
                <span><?php
                if ( '' != goldsmith_settings('estimated_delivery_text', '') ) {
                    echo goldsmith_settings('estimated_delivery_text', '');
                } else {
                    esc_html_e( 'Estimated Delivery:', 'goldsmith' );
                }
                ?>&nbsp;</span>
                <?php echo implode( ' ', $est_days ); ?>
            </div>
            <?php
        }
    }
}


/**
* Add product excerpt
*/
if ( ! function_exists( 'goldsmith_product_excerpt' ) ) {
    function goldsmith_product_excerpt()
    {
        global $product;
        if ( $product->get_short_description() ) {
            $limit = goldsmith_settings('shop_loop_excerpt_limit');
            ?>
            <p class="goldsmith-product-excerpt"><?php echo wp_trim_words( $product->get_short_description(), apply_filters( 'goldsmith_loop_excerpt_limit', $limit ) ); ?></p>
            <?php
        }
    }
}

/**
* Add product rating
*/
if ( ! function_exists( 'goldsmith_product_rating' ) ) {

    function goldsmith_product_rating()
    {
        global $product;
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average      = $product->get_average_rating();

        if ( $product->get_average_rating() ) {
            ?>
            <div class="goldsmith-rating star-rating">
                <span data-width="<?php echo esc_attr( ( $average / 5 ) * 100  ); ?>"></span>
                <?php if ( comments_open() ) { ?>
                    <a href="#reviews" class="goldsmith-review-link goldsmith-small-title" rel="nofollow"><?php printf( _n( '%s review', '%s reviews', $review_count, 'goldsmith' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?></a>
                <?php } ?>
            </div>
            <?php
        }
    }
}

/**
* Add product rating
*/
if ( ! function_exists( 'goldsmith_product_meta' ) ) {

    function goldsmith_product_meta()
    {
        global $product;
        ?>
        <div class="goldsmith-summary-item goldsmith-product-meta">
            <?php do_action( 'woocommerce_product_meta_start' ); ?>
            <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="goldsmith-small-title posted_in"><span class="goldsmith-meta-label">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'goldsmith' ) . '</span><span class="goldsmith-meta-links"> ', '</span></span>' ); ?>
            <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="goldsmith-small-title tagged_as"><span class="goldsmith-meta-label">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'goldsmith' ) . '</span> ', '</span>' ); ?>
            <?php do_action( 'woocommerce_product_meta_end' ); ?>
        </div>
        <?php
    }
}


/**
* Get product sku
*/
if ( ! function_exists( 'goldsmith_product_sku' ) ) {
    function goldsmith_product_sku()
    {
        global $product;
        if ( $product->get_sku() ) {
        	echo '<div class="goldsmith-meta-wrapper goldsmith-small-title"><span class="goldsmith-meta-label">'.esc_html__('SKU:', 'goldsmith') .'</span><span class="goldsmith-sku">'.esc_html( $product->get_sku() ).'</span></div>';
        }
    }
}


if ( ! function_exists( 'goldsmith_product_badge' ) ) {
    function goldsmith_product_badge($echo=true)
    {
        if ( '1' == goldsmith_settings('shop_sale_label_visibility', '1' ) ) {
            global $product;
            $title = get_post_meta( $product->get_id(), 'goldsmith_custom_badge', true );
            $color = get_post_meta( $product->get_id(), 'goldsmith_badge_color', true );
            $color = $color ? ' data-label-color="'.$color.'"' : '';

            if ( true == $echo ) {
                if ( '' != $title ) {
                    echo '<span class="goldsmith-label badge-'.esc_attr( $title ).'"'.$color.'>'.esc_html( $title ).'</span>';
                } else {
                    if ( $product->is_on_sale() ) {
                        echo '<span class="goldsmith-label badge-def"'.$color.'>'.esc_html__( 'Sale!', 'goldsmith' ).'</span>';
                    }
                }
            } else {
                if ( '' != $title ) {
                    return '<span class="goldsmith-label badge-'.esc_attr( $title ).'"'.$color.'>'.esc_html( $title ).'</span>';
                } else {
                    if ( $product->is_on_sale() ) {
                        return '<span class="goldsmith-label badge-def"'.$color.'>'.esc_html__( 'Sale!', 'goldsmith' ).'</span>';
                    }
                }
            }
        }
    }
}

/**
* Single product labels
*/
if ( ! function_exists( 'goldsmith_single_product_labels' ) ) {
    function goldsmith_single_product_labels()
    {
        if ( '0' == goldsmith_settings('single_shop_labels_visibility', '1' ) ) {
            return;
        }
        echo '<div class="goldsmith-product-labels">';
            goldsmith_product_badge();
            goldsmith_product_discount();
        echo '</div>';
    }
}
/**
* Single product labels
*/
if ( ! function_exists( 'goldsmith_single_stretch_type_product_labels' ) ) {
	add_action( 'woocommerce_single_product_summary', 'goldsmith_single_stretch_type_product_labels', 15 );
    function goldsmith_single_stretch_type_product_labels()
    {
        if ( '0' == goldsmith_settings('single_shop_labels_visibility', '1' ) ) {
            return;
        }
        echo '<div class="goldsmith-product-labels goldsmith-summary-item">';
            goldsmith_product_badge();
            goldsmith_product_discount();
        echo '</div>';
    }
}


if ( ! function_exists( 'goldsmith_loop_category_title' ) ) {

    /**
    * Show the subcategory title in the product loop.
    *
    * @param object $category Category object.
    */
    function goldsmith_loop_category_title( $category ) {
        ?>
        <h4 class="goldsmith-loop-category-title">
            <?php
            echo esc_html( $category->name );

            if ( $category->count > 0 ) {
                echo '<span class="cat-count">' . esc_html( $category->count ) . '</span>';
            }
            ?>
        </h4>
        <?php
    }
}


/**
* product brand
*/
if ( ! function_exists( 'goldsmith_product_brands' ) ) {
    function goldsmith_product_brands()
    {
        global $product;
        $brands = '';
        $metaid = defined( 'YITH_WCBR' ) ? 'yith_product_brand' : 'goldsmith_product_brands';
        $terms  = !empty( $product ) ? get_the_terms( $product->get_id(), $metaid ) : null;
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $brands = array();
            foreach ( $terms as $term ) {
                if ( $term->parent == 0 ) {
                    $brands[] = sprintf( '<a class="goldsmith-brands" href="%s" itemprop="brand" title="%s">%s</a>',
                        get_term_link( $term ),
                        $term->slug,
                        $term->name
                    );
                }
            }
        }
        $label = !empty( $brands ) && count( $brands ) > 1 ? esc_html__('Brands: ', 'goldsmith' )  : esc_html__('Brand: ', 'goldsmith' );
        echo !empty( $brands ) ? '<div class="goldsmith-meta-wrapper goldsmith-small-title"><span class="goldsmith-meta-label">'.$label.'</span> ' . implode( ', ', $brands ) .'</span></div>' : '';
    }
}


/**
*  add custom color field to for product badge
*/
if ( ! function_exists( 'goldsmith_wc_product_meta_color' ) ) {

    function goldsmith_wc_product_meta_color( $field )
    {
        global $thepostid, $post;

        $thepostid      = empty( $thepostid ) ? $post->ID : $thepostid;
        $field['class'] = isset( $field['class'] ) ? $field['class'] : 'goldsmith-color-field';
        $field['value'] = isset( $field['value'] ) ? $field['value'] : get_post_meta( $thepostid, $field['id'], true );

        echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>
        <input type="text" class="goldsmith-color-field" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" /></p>';
    }
}


/**
*  countdown for product
*/
if ( ! function_exists( 'goldsmith_product_countdown' ) ) {
    function goldsmith_product_countdown()
    {
        if ( '0' != goldsmith_settings('single_shop_countdown_visibility','1') ) {
            global $product;

            $id     = $product->get_id();
            $date   = get_post_meta( $id, '_sale_price_dates_to', true );
            $time   = $date ? date_i18n( 'Y/m/d', $date ) : '';
            $icon   = goldsmith_settings('goldsmith_countdown_icon','');
            $icon   = $icon ? $icon : goldsmith_svg_lists( 'flash', 'goldsmith-svg-icon' );
            $textot = goldsmith_settings('goldsmith_countdown_text','');
            $text   = get_post_meta( $id, 'goldsmith_countdown_text', true);
            $text   = $text ? $text : $textot;
            $countdown = get_post_meta( $id, 'goldsmith_product_hide_countdown', true);
            $expired = goldsmith_settings('goldsmith_countdown_expired','');
            $expired = $expired ? $expired : esc_html__('Expired','goldsmith');

            if ( 'yes' != $countdown && $date ) {
                wp_enqueue_script( 'goldsmith-countdown' );

                echo '<div class="goldsmith-summary-item goldsmith-viewed-offer-time">';
                    if ( $text ) {
                        echo '<p class="offer-time-text">'.$icon.$text.'</p>';
                    }
                    echo '<div class="goldsmith-coming-time" data-countdown=\'{"date":"'.$time.'","expired":"'.$expired.'"}\'>
                    <div class="time-count days"></div>
                    <span class="separator">:</span>
                    <div class="time-count hours"></div>
                    <span class="separator">:</span>
                    <div class="time-count minutes"></div>
                    <span class="separator">:</span>
                    <div class="time-count second"></div>
                    </div>
                </div>';
            }
        }
    }
}


/**
*  custom extra tabs for product page
*/
if ( ! function_exists( 'goldsmith_wc_extra_tabs_array' ) ) {
    function goldsmith_wc_extra_tabs_array()
    {
        global $product;
        $tabs        = array();
        $tab_title   = get_post_meta( $product->get_id(), 'goldsmith_tabs_title', true);
        $tab_content = get_post_meta( $product->get_id(), 'goldsmith_tabs_content', true);
        $tabtitle    = preg_split("/\\r\\n|\\r|\\n/", $tab_title );
        $tabcontent  = preg_split("/\\r\\n|\\r|\\n/", $tab_content );

        $count    = 30;
        foreach( goldsmith_combine_arr($tabtitle, $tabcontent) as $title => $details ) {
            if ( !empty( $title ) && !empty( $details ) ) {
                $replaced_title = preg_replace('/\s+/', '_', strtolower(trim($title)));
                $tabs[$replaced_title] = array(
                    'title' => $title,
                    'priority' => $count,
                    'content' => $details
                );
            }
            $count = $count + 10;
        }
        return $tabs;
    }
}


/*
* Tab
*/
if ( ! function_exists( 'goldsmith_product_settings_tabs' ) ) {
    add_filter('woocommerce_product_data_tabs', 'goldsmith_product_settings_tabs' );
    function goldsmith_product_settings_tabs( $tabs ){
        $tabs['goldsmith_general'] = array(
            'label'    => esc_html__('Goldsmith General', 'goldsmith'),
            'target'   => 'goldsmith_product_general_data',
            'priority' => 100,
        );
        $tabs['goldsmith_product_page'] = array(
            'label'    => esc_html__('Goldsmith Product Page', 'goldsmith'),
            'target'   => 'goldsmith_product_page_data',
            'priority' => 101,
        );
        return $tabs;
    }
}
/*
* Tab content
*/
if ( ! function_exists( 'goldsmith_product_panels' ) ) {
    add_action( 'woocommerce_product_data_panels', 'goldsmith_product_panels' );
    function goldsmith_product_panels(){

        echo '<div id="goldsmith_product_general_data" class="panel woocommerce_options_panel hidden">';
            echo '<h3 class="goldsmith-panel-heading">'.esc_html__('Goldsmith Product General Settings', 'goldsmith').'</h3>';
            woocommerce_wp_checkbox(
                array(
                    'id' => 'goldsmith_loop_product_slider',
                    'label' => esc_html__( 'Show Slider Thumbnails on Archive page?', 'goldsmith' ),
                    'desc_tip' => false,
                )
            );
            woocommerce_wp_checkbox(
                array(
                    'id' => 'goldsmith_loop_product_slider_autoplay',
                    'label' => esc_html__( 'Slider Autoplay?', 'goldsmith' ),
                    'desc_tip' => false,
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => 'goldsmith_loop_product_slider_speed',
                    'label' => esc_html__( 'Slider Speed ( ms )', 'goldsmith' ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'Duration of transition between slides (in ms).Use simple number', 'goldsmith' ),
                )
            );
            echo '<div class="goldsmith-panel-divider"></div>';
            woocommerce_wp_checkbox(
                array(
                    'id' => 'goldsmith_product_discount',
                    'label' => esc_html__( 'Hide Product Discount?', 'goldsmith' ),
                    'wrapper_class' => 'hide_if_variable',
                    'desc_tip' => false,
                )
            );
            woocommerce_wp_checkbox(
                array(
                    'id' => 'goldsmith_product_hide_stock',
                    'label' => esc_html__( 'Hide Product Stock Label?', 'goldsmith' ),
                    'wrapper_class' => 'hide_if_variable',
                    'desc_tip' => false,
                )
            );
            echo '<div class="goldsmith-panel-divider"></div>';
            echo '<h4 class="goldsmith-panel-subheading">'.esc_html__('Badge Settings', 'goldsmith').'</h4>';
            woocommerce_wp_text_input(
                array(
                    'id' => 'goldsmith_custom_badge',
                    'label' => esc_html__( 'Badge Label', 'goldsmith' ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'Add your custom badge label here', 'goldsmith' ),
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_badge_color',
                    'label' => esc_html__( 'Badge Color', 'goldsmith' ),
                )
            );

            echo '<div class="goldsmith-panel-divider"></div>';
            echo '<h4 class="goldsmith-panel-subheading">'.esc_html__('Countdown Settings', 'goldsmith').'</h4>';
            woocommerce_wp_checkbox(
                array(
                    'id' => 'goldsmith_product_hide_countdown',
                    'label' => esc_html__( 'Hide Product Countdown?', 'goldsmith' ),
                    'wrapper_class' => 'hide_if_variable',
                    'desc_tip' => false,
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => 'goldsmith_countdown_text',
                    'label' => esc_html__( 'Countdown Text', 'goldsmith' ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'Add your custom text here', 'goldsmith' ),
                )
            );
        echo '</div>';

        echo '<div id="goldsmith_product_page_data" class="panel woocommerce_options_panel hidden">';
            echo '<h3 class="goldsmith-panel-heading">'.esc_html__('Goldsmith Product Page Settings', 'goldsmith').'</h3>';
            echo '<h4 class="goldsmith-panel-subheading">'.esc_html__('Product Header Type Settings', 'goldsmith').'</h4>';
            woocommerce_wp_select(
                array(
                    'id' => 'goldsmith_product_header_type',
                    'label' => esc_html__( 'Header Type?', 'goldsmith' ),
                    'options' => array(
                        '' => 'Select a type',
                        'default' => esc_html__( 'Theme options settings', 'goldsmith' ),
                        'dark' => esc_html__( 'Dark', 'goldsmith' ),
                        'trans-light' => esc_html__( 'Transparent Light', 'goldsmith' ),
                        'trans-dark' => esc_html__( 'Transparent Dark', 'goldsmith' ),
                        'custom' => esc_html__( 'Custom Color', 'goldsmith' ),
                    ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'You can use this option to use a different header for this product', 'goldsmith' )
                )
            );
            echo '<h4 class="goldsmith-panel-subheading menu-customize">'.esc_html__('Header Custom Color Settings', 'goldsmith').'</h4>';
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_product_header_bgcolor',
                    'label' => esc_html__( 'Header Background Color', 'goldsmith' )
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_product_header_menuitem_color',
                    'label' => esc_html__( 'Menu Item Color', 'goldsmith' )
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_product_header_menuitem_hvrcolor',
                    'label' => esc_html__( 'Menu Item Hover/Active Color', 'goldsmith' )
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_product_header_svgicon_color',
                    'label' => esc_html__( 'Header SVG Buttons Color', 'goldsmith' )
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_product_header_counter_bgcolor',
                    'label' => esc_html__( 'Header SVG Buttons Counter Background Color', 'goldsmith' )
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_product_header_counter_color',
                    'label' => esc_html__( 'Header SVG Buttons Counter Color', 'goldsmith' )
                )
            );
            echo '<h4 class="goldsmith-panel-subheading menu-customize">'.esc_html__('Sticky Header Custom Color Settings', 'goldsmith').'</h4>';
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_product_sticky_header_bgcolor',
                    'label' => esc_html__( 'Sticky Header Background Color', 'goldsmith' )
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_product_sticky_header_menuitem_color',
                    'label' => esc_html__( 'Menu Item Color', 'goldsmith' )
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_product_sticky_header_menuitem_hvrcolor',
                    'label' => esc_html__( 'Menu Item Hover/Active Color', 'goldsmith' )
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_product_sticky_header_svgicon_color',
                    'label' => esc_html__( 'Header SVG Buttons Color', 'goldsmith' )
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_product_sticky_header_counter_bgcolor',
                    'label' => esc_html__( 'Header SVG Buttons Counter Background Color', 'goldsmith' )
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_product_sticky_header_counter_color',
                    'label' => esc_html__( 'Header SVG Buttons Counter Color', 'goldsmith' )
                )
            );
            echo '<div class="goldsmith-panel-divider"></div>';
            echo '<h4 class="goldsmith-panel-subheading">'.esc_html__('Product Showcase Type Settings', 'goldsmith').'</h4>';
            woocommerce_wp_select(
                array(
                    'id' => 'goldsmith_showcase_type',
                    'label' => esc_html__( 'Showcase Type?', 'goldsmith' ),
                    'options' => array(
                        '' => 'Select a type',
                        'full' => esc_html__( 'Full', 'goldsmith' ),
                        'carousel' => esc_html__( 'Carousel', 'goldsmith' ),
                    ),
                    'desc_tip' => false,
                )
            );
            woocommerce_wp_select(
                array(
                    'id' => 'goldsmith_showcase_bg_type',
                    'label' => esc_html__( 'Background Color Type?', 'goldsmith' ),
                    'options' => array(
                        '' => 'Select a type',
                        'dark' => esc_html__( 'Dark', 'goldsmith' ),
                        'light' => esc_html__( 'Light', 'goldsmith' ),
                        'custom' => esc_html__( 'Custom Color', 'goldsmith' ),
                    ),
                    'desc_tip' => false,
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_showcase_custom_bgcolor',
                    'label' => esc_html__( 'Custom Background Color', 'goldsmith' ),
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_showcase_custom_textcolor',
                    'label' => esc_html__( 'Custom Text Color', 'goldsmith' ),
                )
            );
            echo '<div class="goldsmith-panel-divider"></div>';
            echo '<h4 class="goldsmith-panel-subheading">'.esc_html__('Product Showcase Type Settings', 'goldsmith').'</h4>';
            woocommerce_wp_select(
                array(
                    'id' => 'goldsmith_showcase_type',
                    'label' => esc_html__( 'Showcase Type?', 'goldsmith' ),
                    'options' => array(
                        '' => 'Select a type',
                        'full' => esc_html__( 'Full', 'goldsmith' ),
                        'carousel' => esc_html__( 'Carousel', 'goldsmith' ),
                    ),
                    'desc_tip' => false,
                )
            );
            woocommerce_wp_select(
                array(
                    'id' => 'goldsmith_showcase_bg_type',
                    'label' => esc_html__( 'Background Color Type?', 'goldsmith' ),
                    'options' => array(
                        '' => 'Select a type',
                        'dark' => esc_html__( 'Dark', 'goldsmith' ),
                        'light' => esc_html__( 'Light', 'goldsmith' ),
                        'custom' => esc_html__( 'Custom Color', 'goldsmith' ),
                    ),
                    'desc_tip' => false,
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_showcase_custom_bgcolor',
                    'label' => esc_html__( 'Custom Background Color', 'goldsmith' ),
                )
            );
            goldsmith_wc_product_meta_color(
                array(
                    'id' => 'goldsmith_showcase_custom_textcolor',
                    'label' => esc_html__( 'Custom Text Color', 'goldsmith' ),
                )
            );
            echo '<div class="goldsmith-panel-divider"></div>';
            echo '<h4 class="goldsmith-panel-subheading">'.esc_html__('Product Summary Settings', 'goldsmith').'</h4>';

            woocommerce_wp_select(
                array(
                    'id' => 'goldsmith_product_size_guide',
                    'label' => esc_html__( 'Size Guide ( Elementor Template )', 'goldsmith' ),
                    'options' => goldsmith_get_elementorTemplates(),
                    'desc_tip' => true,
                    'description' => esc_html__( 'Please select size guide elementor template for this product.', 'goldsmith' )
                )
            );
            echo '<div class="goldsmith-panel-divider"></div>';
            echo '<h4 class="goldsmith-panel-subheading">'.esc_html__('Product Video Settings', 'goldsmith').'</h4>';
            woocommerce_wp_select(
                array(
                    'id' => 'goldsmith_product_video_type',
                    'label' => esc_html__( 'Product Video Type?', 'goldsmith' ),
                    'options' => array(
                        '' => 'Select a type',
                        'popup' => esc_html__( 'Popup', 'goldsmith' ),
                        'gallery' => esc_html__( 'Gallery Item', 'goldsmith' ),
                    ),
                    'desc_tip' => false
                )
            );
            woocommerce_wp_select(
                array(
                    'id' => 'goldsmith_product_video_source_type',
                    'label' => esc_html__( 'Product Video Source Type?', 'goldsmith' ),
                    'options' => array(
                        '' => 'Select a type',
                        'youtube' => esc_html__( 'Youtube', 'goldsmith' ),
                        'vimeo' => esc_html__( 'Vimeo', 'goldsmith' ),
                        'hosted' => esc_html__( 'Hosted', 'goldsmith' ),
                    ),
                    'desc_tip' => false
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => 'goldsmith_product_popup_video',
                    'label' => esc_html__( 'Popup / Hosted Video URL', 'goldsmith' ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'Add your youtube,vimeo,hosted video URL here', 'goldsmith' )
                )
            );
            woocommerce_wp_text_input(
                array(
                    'id' => 'goldsmith_product_iframe_video',
                    'label' => esc_html__( 'Youtube video ID', 'goldsmith' ),
                    'desc_tip' => true,
                    'description' => esc_html__( 'Add your youtube video ID here for background autoplay video.', 'goldsmith' ),
                    'rows' => 4
                )
            );
            woocommerce_wp_select(
                array(
                    'id' => 'goldsmith_product_video_on_shop',
                    'label' => esc_html__( 'Show this iframe video on shop archive?', 'goldsmith' ),
                    'options' => array(
                        '' => 'Select an option',
                        'no' => esc_html__( 'No', 'goldsmith' ),
                        'yes' => esc_html__( 'Yes', 'goldsmith' ),
                    ),
                    'desc_tip' => false
                )
            );
            echo '<div class="goldsmith-panel-divider"></div>';
            echo '<h4 class="goldsmith-panel-subheading">'.esc_html__('Extra Tabs Settings', 'goldsmith').'</h4>';
            woocommerce_wp_textarea_input(
                array(
                    'id' => 'goldsmith_tabs_title',
                    'label' => esc_html__( 'Extra Tabs Title', 'goldsmith' ),
                    'desc_tip' => true,
                    'description' => esc_html__( '!Important note: One title per line.', 'goldsmith' ),
                    'rows' => 3
                )
            );
            woocommerce_wp_textarea_input(
                array(
                    'id' => 'goldsmith_tabs_content',
                    'label' => esc_html__( 'Extra Tabs Content', 'goldsmith' ),
                    'desc_tip' => true,
                    'description' => esc_html__( '!Important note: One content per line.Iframe,shortcode,HTML content allowed.', 'goldsmith' ),
                    'rows' => 4
                )
            );
        echo '</div>';
    }
}

/**
*  Save Custom Field
*/
if ( ! function_exists( 'goldsmith_save_product_custom_field' ) ) {
    add_action( 'woocommerce_process_product_meta', 'goldsmith_save_product_custom_field' );
    function goldsmith_save_product_custom_field( $_post_id )
    {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
            return;
        }
        $options = array(
            'goldsmith_loop_product_slider',
            'goldsmith_loop_product_slider_autoplay',
            'goldsmith_loop_product_slider_speed',
            'goldsmith_showcase_type',
            'goldsmith_showcase_bg_type',
            'goldsmith_showcase_custom_bgcolor',
            'goldsmith_showcase_custom_textcolor',
            'goldsmith_badge_color',
            'goldsmith_custom_badge',
            'goldsmith_product_discount',
            'goldsmith_product_size_guide',
            'goldsmith_product_hide_stock',
            'goldsmith_product_hide_countdown',
            'goldsmith_countdown_text',
            'goldsmith_product_video_type',
            'goldsmith_product_video_source_type',
            'goldsmith_product_popup_video',
            'goldsmith_product_iframe_video',
            'goldsmith_product_video_on_shop',
            'goldsmith_tabs_title',
            'goldsmith_tabs_content',
            'goldsmith_product_header_type',
            'goldsmith_product_header_bgcolor',
            'goldsmith_product_header_menuitem_color',
            'goldsmith_product_header_menuitem_hvrcolor',
            'goldsmith_product_header_svgicon_color',
            'goldsmith_product_header_counter_bgcolor',
            'goldsmith_product_header_counter_color',
            'goldsmith_product_sticky_header_bgcolor',
            'goldsmith_product_sticky_header_menuitem_color',
            'goldsmith_product_sticky_header_menuitem_hvrcolor',
            'goldsmith_product_sticky_header_svgicon_color',
            'goldsmith_product_sticky_header_counter_bgcolor',
            'goldsmith_product_sticky_header_counter_color'
        );
        foreach ( $options as $option ) {
            if ( isset( $_POST[$option] ) ) {
                update_post_meta( $_post_id, $option, $_POST[$option] );
            } else {
                delete_post_meta( $_post_id, $option );
            }
        }
    }
}


/**
* Remove Reviews tab from tabs
*/
if ( ! function_exists( 'goldsmith_wc_remove_product_tabs' ) ) {
    add_filter( 'woocommerce_product_tabs', 'goldsmith_wc_remove_product_tabs', 98 );
    function goldsmith_wc_remove_product_tabs( $tabs )
    {
        $tabs_type = apply_filters( 'goldsmith_product_tabs_type', goldsmith_settings( 'product_tabs_type', 'tabs' ) );

        if ( 'accordion' == $tabs_type || '0' == goldsmith_settings('product_hide_reviews_tab', '1' ) ) {
            unset($tabs['reviews']);
        }

        $tabs['description']['callback'] = 'goldsmith_wc_custom_description_tab_content'; // Custom description callback

        if ( '0' == goldsmith_settings('product_hide_description_tab', '1' ) ) {
            unset($tabs['description']);
        }
        if ( '0' == goldsmith_settings('product_hide_additional_tab', '1' ) ) {
            unset($tabs['additional_information']);
        }
        if ( '0' == goldsmith_settings('product_hide_crqna_tab', '1' ) ) {
            unset($tabs['cr_qna']);
        }

        if ( '1' == goldsmith_settings( 'product_tabs_custom_order', '0' ) ) {
            $tabs_order = goldsmith_settings( 'product_tabs_order', null );
            if ( !empty( $tabs_order['show'] ) ) {
                unset( $tabs_order['show']['placebo'] );
                $priority = 1;
                foreach ( $tabs_order['show'] as $key => $value ) {
                    $tabs[ $key ][ 'priority' ] = $priority;
                    $priority++;
                }
            }
        }

        return $tabs;
    }
}


/**
 * Customize product data tabs
 */
if ( ! function_exists( 'goldsmith_wc_custom_description_tab_content' ) ) {
    function goldsmith_wc_custom_description_tab_content()
    {
        $desc_tab_title = goldsmith_settings( 'product_description_tab_title', '' );
        $desc_tab_title = '' != $desc_tab_title ? $desc_tab_title : esc_html__( 'Product Details', 'goldsmith' );
        ?>
        <div class="product-desc-content">
            <?php if ( '1' == goldsmith_settings( 'product_description_tab_title_visibility', '0' ) ) { ?>
                <h4 class="title"><?php echo apply_filters( 'ninetheme_description_tab_title', $desc_tab_title ); ?></h4>
            <?php } ?>
            <?php the_content(); ?>
        </div>
        <?php
    }
}


/**
 * Move Reviews tab after product related
 */
if ( ! function_exists( 'goldsmith_wc_move_product_reviews' ) ) {
    function goldsmith_wc_move_product_reviews()
    {
        comments_template();
    }
}


/**
 * woocommerce_layered_nav_term_html WIDGET
 */
if ( !function_exists( 'goldsmith_add_span_wc_layered_nav_term_html' ) ) {
    function goldsmith_add_span_wc_layered_nav_term_html( $links )
    {
        $links = str_replace( '</a> (', '</a> <span class="widget-list-span">', $links );
        $links = str_replace( '</a> <span class="count">(', '</a> <span class="widget-list-span">', $links );
        $links = str_replace( ')', '</span>', $links );

        return $links;
    }
    add_filter( 'woocommerce_layered_nav_term_html', 'goldsmith_add_span_wc_layered_nav_term_html' );
}


/**
* Add to cart handler.
*/
if ( !function_exists( 'goldsmith_ajax_add_to_cart_handler' ) ) {
    function goldsmith_ajax_add_to_cart_handler()
    {
        goldsmith_cart_fragments();
    }
    add_action( 'wc_ajax_goldsmith_ajax_add_to_cart', 'goldsmith_ajax_add_to_cart_handler' );
    add_action( 'wc_ajax_nopriv_goldsmith_ajax_add_to_cart', 'goldsmith_ajax_add_to_cart_handler' );
}

if ( !function_exists( 'goldsmith_remove_from_cart_handler' ) ) {
    function goldsmith_remove_from_cart_handler()
    {
        $cart_item_key = wc_clean( isset( $_POST['cart_item_key'] ) ? wp_unslash( $_POST['cart_item_key'] ) : '' );

        if ( $cart_item_key && false !== WC()->cart->remove_cart_item( $cart_item_key ) ) {
            goldsmith_cart_fragments('remove');
        } else {
            wp_send_json_error();
        }
    }
    add_action( 'wc_ajax_goldsmith_remove_from_cart', 'goldsmith_remove_from_cart_handler' );
    add_action( 'wc_ajax_nopriv_goldsmith_remove_from_cart', 'goldsmith_remove_from_cart_handler' );
}

if ( !function_exists( 'goldsmith_ajax_update_cart_handler' ) ) {
    function goldsmith_ajax_update_cart_handler()
    {
       if ( ( isset( $_GET['id'] ) && $_GET['id'] ) && ( isset( $_GET['qty'] ) ) ) {

           if ( $_GET['qty'] ) {
               WC()->cart->set_quantity( $_GET['id'], $_GET['qty'] );
           } else {
               WC()->cart->remove_cart_item( $_GET['id'] );
           }

           if ( WC()->cart->get_cart_contents_count() == 0 ) {
               $fragments = array(
                   'msg' => esc_html__('Your order has been reset!','goldsmith')
               );
           } else {
               $fragments = array(
                   'msg' => $_GET['qty']
               );
           }

           if ( $_GET['is_cart'] == 'yes' ) {
               ob_start();
               get_template_part('woocommerce/cart/cart');
               $cart = ob_get_clean();
               $fragments['cart'] = $cart;
               goldsmith_cart_fragments('update',$fragments);
           } else {
               goldsmith_cart_fragments('update',$fragments);
           }
       }
    }
    add_action( 'wc_ajax_goldsmith_ajax_update_cart', 'goldsmith_ajax_update_cart_handler' );
    add_action( 'wc_ajax_nopriv_goldsmith_ajax_update_cart', 'goldsmith_ajax_update_cart_handler' );
}

if ( !function_exists( 'goldsmith_clear_cart_handler' ) ) {
    function goldsmith_clear_cart_handler()
    {
        global $woocommerce;

        $fragments = array(
            'status' => 'error',
            'msg'    => esc_html__('Your order could not be emptied','goldsmith')
        );

        WC()->cart->empty_cart();

        if ( WC()->cart->get_cart_contents_count() == 0 ) {
            $fragments = array(
                'status' => 'success',
                'msg'    => esc_html__('Your order has been reset!','goldsmith')
            );
        }

        goldsmith_cart_fragments('clear',$fragments);
    }
    add_action('wc_ajax_goldsmith_clear_cart', 'goldsmith_clear_cart_handler');
    add_action('wc_ajax_nopriv_goldsmith_clear_cart', 'goldsmith_clear_cart_handler');
}

/**
* quantity callback
*/
if ( !function_exists( 'goldsmith_quantity_button' ) ) {
    function goldsmith_quantity_button() {
        if ( ( isset( $_GET['id'] ) && $_GET['id'] ) && ( isset( $_GET['qty'] ) ) ) {

            if ( $_GET['qty'] ) {
                WC()->cart->set_quantity( $_GET['id'], $_GET['qty'] );
            } else {
                WC()->cart->remove_cart_item( $_GET['id'] );
            }

            if ( esc_html( WC()->cart->get_cart_contents_count() ) == 0 ) {
                $fragments = array(
                    'msg' => esc_html__('Your order has been reset!','goldsmith')
                );
            } else {
                $fragments = array(
                    'msg' => $_GET['qty']
                );
            }

            if ( $_GET['is_cart'] == 'yes' ) {
                ob_start();
                get_template_part('woocommerce/cart/cart');
                $cart = ob_get_clean();
                $fragments['cart'] = $cart;
                goldsmith_cart_fragments('update',$fragments);
            } else {
                goldsmith_cart_fragments('update',$fragments);
            }
        }
    }
    add_action( 'wp_ajax_goldsmith_quantity_button', 'goldsmith_quantity_button' );
    add_action( 'wp_ajax_nopriv_goldsmith_quantity_button', 'goldsmith_quantity_button' );
}

if ( !function_exists( 'goldsmith_cart_fragments' ) ) {
    function goldsmith_cart_fragments( $name = '',$fragments = null )
    {
        ob_start();
        get_template_part('woocommerce/minicart/minicart');
        $minicart = ob_get_clean();
        $notices  = wc_print_notices(true);
        $total    = WC()->cart->get_cart_subtotal();
        $count    = esc_html( WC()->cart->get_cart_contents_count() );

        $data = array(
            'fragments' => array(
                'notices'  => $notices,
                'minicart' => $minicart,
                'total'    => $total,
                'count'    => $count,
                'shipping' => goldsmith_freee_shipping_goal_content(),
            ),
            'cart_hash' => WC()->cart->get_cart_hash()
        );

        if ( $name == 'clear' && !empty( $fragments ) ) {
            $data['fragments']['clear'] = $fragments;
        }
        if ( $name == 'update' && !empty( $fragments ) ) {
            $data['fragments']['update'] = $fragments;
        }
        if ( $name == 'add' && !empty( $fragments ) ) {
            $data['fragments']['add'] = $fragments;
        }

        wp_send_json( $data );
    }
}

if ( ! function_exists( 'goldsmith_free_shipping_goal_content' ) ) {
    function goldsmith_freee_shipping_goal_content()
    {
        $amount = round( goldsmith_settings( 'free_shipping_progressbar_amount', 500 ), wc_get_price_decimals() );

        if ( !( $amount > 0 ) || '1' != goldsmith_settings( 'free_shipping_progressbar_visibility', 1 ) ) {
            return;
        }

        $message_initial = goldsmith_settings( 'free_shipping_progressbar_message_initial' );
        $message_success = goldsmith_settings( 'free_shipping_progressbar_message_success' );

        $total     = WC()->cart->get_displayed_subtotal();
        $remainder = ( $amount - $total );
        $value     = $total <= $amount ? ( $total / $amount ) * 100 : 100;

        if ( $total == 0 ) {
            $value = 0;
        }

        if ( $total >= $amount ) {
            if ( $message_success ) {
                $message = sprintf('%s', $message_success );
            } else {
                $message = sprintf('%s <strong>%s</strong>',
                esc_html__('Congrats! You are eligible for', 'goldsmith'),
                esc_html__('more to enjoy FREE Shipping', 'goldsmith'));
            }
        } else {
            if ( $message_initial ) {
                $message = sprintf('%s', str_replace( '[remainder]', wc_price( $remainder ), $message_initial ) );
            } else {
                $message = sprintf('%s %s <strong>%s</strong>',
                esc_html__('Buy', 'goldsmith'),
                wc_price( $remainder ),
                esc_html__('more to enjoy FREE Shipping', 'goldsmith'));
            }
        }
        $shipping = array(
            'value'   => $value,
            'message' => $message
        );

        return $shipping;
    }
}



/**
* ajax quick shop handler.
*/
if ( !function_exists( 'goldsmith_ajax_quick_shop' ) ) {

    add_action( 'wp_ajax_goldsmith_ajax_quick_shop', 'goldsmith_ajax_quick_shop' );
    add_action( 'wp_ajax_nopriv_goldsmith_ajax_quick_shop', 'goldsmith_ajax_quick_shop' );

    function goldsmith_ajax_quick_shop()
    {
        global $post, $product;
        $product_id = absint( $_GET['product_id'] );
        $product    = wc_get_product( $product_id );

        if ( !$product ) {
            return;
        }

        $post = get_post( $product_id );
        setup_postdata( $post );
        ?>
        <div id="product-<?php echo esc_attr( $product_id ); ?>" <?php wc_product_class( 'goldsmith-quickshop-wrapper single-content zoom-anim-dialog', $product ); ?>>

            <?php if ( goldsmith_settings('header_cart_before_buttons', '' ) ) { ?>
                <div class="minicart-extra-text">
                    <?php echo goldsmith_settings('header_cart_before_buttons', '' ); ?>
                </div>
            <?php } ?>

            <div class="goldsmith-quickshop-form-wrapper">
                <h4 class="goldsmith-product-title"><a class="product-link" href="<?php echo esc_url( get_permalink( $product_id ) ) ?>"><?php the_title();?></a></h4>
                <?php woocommerce_template_single_add_to_cart( $product ); ?>
                <div class="goldsmith-quickshop-notices-wrapper"></div>
            </div>

            <div class="goldsmith-quickshop-buttons-wrapper">
                <div class="goldsmith-flex">
                    <div class="goldsmith-btn-medium goldsmith-btn goldsmith-bg-black open-cart-panel"><?php echo esc_html_e( 'View Cart', 'goldsmith' ); ?></div>
                    <div class="goldsmith-btn-medium goldsmith-btn goldsmith-bg-black open-checkout-panel"><?php echo esc_html_e( 'Checkout', 'goldsmith' ); ?></div>
                </div>
            </div>

        </div>

        <?php
        wp_reset_postdata();
        die();
    }
}

/**
* ajax quick shop handler.
*/
if ( ! function_exists( 'goldsmith_quick_shop_ajax_add_to_cart' ) ) {

    add_action( 'wp_ajax_goldsmith_quick_shop_ajax_add_to_cart', 'goldsmith_quick_shop_ajax_add_to_cart' );
    add_action( 'wp_ajax_nopriv_goldsmith_quick_shop_ajax_add_to_cart', 'goldsmith_quick_shop_ajax_add_to_cart' );

    function goldsmith_quick_shop_ajax_add_to_cart() {
        WC_Form_Handler::add_to_cart_action();
        WC_AJAX::get_refreshed_fragments();
    }
}


/*************************************************
## Buy Now Button For Single Product
*************************************************/
if ( ! function_exists( 'goldsmith_add_buy_now_button_single' ) ) {
    function goldsmith_add_buy_now_button_single()
    {
        if ( '0' == goldsmith_settings( 'buy_now_visibility', '0' ) ) {
            return false;
        }
        global $product;
        $btn_title = goldsmith_settings( 'buy_now_btn_title', '' ) ? goldsmith_settings( 'buy_now_btn_title' ) : esc_html__( 'Buy Now', 'goldsmith' );
        $param     = apply_filters( 'goldsmith_buy_now_param', goldsmith_settings( 'buy_now_param', 'goldsmith-buy-now' ) );
        if ($product->is_type( 'simple' ) || $product->is_type( 'variable' ) ) {
            printf( '<button id="buynow" type="submit" name="'.$param.'" value="%d" class="goldsmith-btn-buynow goldsmith-btn goldsmith-btn-medium goldsmith-btn-dark">%s</button>', $product->get_ID(), $btn_title );
        }
    }
}


/*************************************************
## Handle for click on buy now
*************************************************/
if ( ! function_exists( 'goldsmith_handle_buy_now' ) ) {
    function goldsmith_handle_buy_now()
    {
        $param = apply_filters( 'goldsmith_buy_now_param', goldsmith_settings( 'buy_now_param', 'goldsmith-buy-now' ) );

        if ( ! isset( $_REQUEST[ $param ] ) || '0' == goldsmith_settings( 'buy_now_visibility', '0' ) ) {
            return false;
        }

        $quantity     = floatval( $_REQUEST['quantity'] ?: 1 );
        $product_id   = absint( $_REQUEST[ $param ] ?: 0 );
        $variation_id = absint( $_REQUEST['variation_id'] ?: 0 );
        $variation    = [];

        foreach ( $_REQUEST as $name => $value ) {
            if ( substr( $name, 0, 10 ) === 'attribute_' ) {
                $variation[ $name ] = $value;
            }
        }

        if ( $product_id ) {
            if ( '1' == goldsmith_settings( 'buy_now_reset_cart', '0' ) ) {
                WC()->cart->empty_cart();
            }

            if ( $variation_id ) {
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    if ( $cart_item['product_id'] == $product_id && $cart_item['variation_id'] == $variation_id ) {
                        WC()->cart->remove_cart_item( $cart_item_key );
                    }
                }
                WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
            } else {
                WC()->cart->add_to_cart( $product_id, $quantity );
            }

            switch ( apply_filters( 'goldsmith_buy_now_redirect', goldsmith_settings( 'buy_now_redirect', 'checkout' ) ) ) {
                case 'checkout':
                $redirect = wc_get_checkout_url();
                break;
                case 'cart':
                $redirect = wc_get_cart_url();
                break;
                default:
                $redirect = goldsmith_settings( 'buy_now_redirect_custom', '/' );
            }

            $redirect = esc_url( apply_filters( 'buy_now_redirect_url', $redirect ) );

            if ( empty( $redirect ) ) {
                $redirect = '/';
            }

            wp_safe_redirect( $redirect );

            exit;
        }
    }
    add_action( 'template_redirect', 'goldsmith_handle_buy_now' );
}


/**
* Add category banner if shortcode exists
*/
if ( !function_exists( 'goldsmith_print_category_banner' ) ) {
    add_action( 'goldsmith_shop_before_loop', 'goldsmith_print_category_banner', 10 );
    function goldsmith_print_category_banner()
    {
        $banner       = get_term_meta( get_queried_object_id(), 'goldsmith_wc_cat_banner', true );
        $cat_template = goldsmith_settings('shop_category_pages_before_loop_templates', null );
        $tag_template = goldsmith_settings('shop_tag_pages_before_loop_templates', null );
        $layouts      = isset( $_GET['shop_layouts'] ) && ( 'left-sidebar' == $_GET['shop_layouts'] || 'right-sidebar' == $_GET['shop_layouts'] ) ? true : false;

        if ( ( $cat_template || $tag_template || $banner ) && ( is_product_category() || is_product_tag() ) ) {

            if ( $banner && is_product_category() ) {
                printf( '<div class="shop-cat-banner goldsmith-before-loop-template">%s</div>', do_shortcode( $banner ) );
            } elseif ( $cat_template && is_product_category() ) {
                echo goldsmith_print_elementor_templates( 'shop_category_pages_before_loop_templates', 'shop-cat-banner goldsmith-before-loop', true );
            } elseif ( $tag_template && is_product_tag() ) {
                echo goldsmith_print_elementor_templates( 'shop_tag_pages_before_loop_templates', 'shop-tag-banner goldsmith-before-loop', true );
            } else {
                printf( '<div class="shop-cat-banner goldsmith-before-loop">%s</div>', do_shortcode( $banner ) );
            }

        } else {

            if ( 'left-sidebar' == goldsmith_settings('shop_layout') || 'right-sidebar' == goldsmith_settings('shop_layout') || $layouts ) {
                echo goldsmith_print_elementor_templates( 'shop_before_loop_templates', 'shop-cat-banner-template-wrapper', true );
            }
        }
    }
}


add_action('product_cat_add_form_fields', 'goldsmith_wc_taxonomy_add_new_meta_field', 15, 1);
//Product Cat Create page
function goldsmith_wc_taxonomy_add_new_meta_field() {
    woocommerce_wp_textarea_input(
        array(
            'id' => 'goldsmith_wc_cat_banner',
            'label' => esc_html__( 'Goldsmith Category Banner', 'goldsmith' ),
            'description' => esc_html__( 'If you want to show a different banner on the archive category page for this category, use this field.Iframe,shortcode,HTML content allowed.', 'goldsmith' ),
            'rows' => 4
        )
    );
    woocommerce_wp_textarea_input(
        array(
            'id' => 'goldsmith_wc_cat_hero',
            'label' => esc_html__( 'Goldsmith Category Page Hero Template', 'goldsmith' ),
            'description' => esc_html__( 'If you want to show a different hero on the archive category page for this category, use this field.Iframe,shortcode,HTML content allowed.', 'goldsmith' ),
            'rows' => 4
        )
    );
    ?>
    <div class="form-field goldsmith_term-hero-bgimage-wrap">
        <label><?php esc_html_e( 'Goldsmith Shop Category Page Hero Background Image', 'goldsmith' ); ?></label>
        <div id="goldsmith_product_cat_hero_bgimage" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
        <div style="line-height: 60px;">
            <input type="hidden" id="goldsmith_product_cat_hero_bgimage_id" name="goldsmith_product_cat_hero_bgimage_id" />
            <button type="button" class="goldsmith_upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'goldsmith' ); ?></button>
            <button type="button" class="goldsmith_remove_image_button button"><?php esc_html_e( 'Remove image', 'goldsmith' ); ?></button>
        </div>
        <div class="clear"></div>
        <span class="description"><?php esc_html_e( 'If you want to show a different background image on the shop archive category page for this category, upload your image from here.', 'goldsmith'); ?></span>
        <script type="text/javascript">

        // Only show the "remove image" button when needed
        if ( ! jQuery( '#goldsmith_product_cat_hero_bgimage_id' ).val() ) {
            jQuery( '.goldsmith_term-hero-bgimage-wrap .goldsmith_remove_image_button' ).hide();
        }

        // Uploading files
        var goldsmith_cat_hero_file_frame;

        jQuery( document ).on( 'click', '.goldsmith_term-hero-bgimage-wrap .goldsmith_upload_image_button', function( event ) {

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if ( goldsmith_cat_hero_file_frame ) {
                goldsmith_cat_hero_file_frame.open();
                return;
            }

            // Create the media frame.
            goldsmith_cat_hero_file_frame = wp.media.frames.downloadable_file = wp.media({
                title: '<?php esc_html_e( 'Choose an image', 'goldsmith' ); ?>',
                button: {
                    text: '<?php esc_html_e( 'Use image', 'goldsmith' ); ?>'
                },
                multiple: false
            });

            // When an image is selected, run a callback.
            goldsmith_cat_hero_file_frame.on( 'select', function() {
                var attachment           = goldsmith_cat_hero_file_frame.state().get( 'selection' ).first().toJSON();
                var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                jQuery( '#goldsmith_product_cat_hero_bgimage_id' ).val( attachment.id );
                jQuery( '#goldsmith_product_cat_hero_bgimage' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
                jQuery( '.goldsmith_term-hero-bgimage-wrap .goldsmith_remove_image_button' ).show();
            });

            // Finally, open the modal.
            goldsmith_cat_hero_file_frame.open();
        });

        jQuery( document ).on( 'click', '.goldsmith_term-hero-bgimage-wrap .goldsmith_remove_image_button', function() {
            jQuery( '#goldsmith_product_cat_hero_bgimage' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
            jQuery( '#goldsmith_product_cat_hero_bgimage_id' ).val( '' );
            jQuery( '.goldsmith_term-hero-bgimage-wrap .goldsmith_remove_image_button' ).hide();
            return false;
        });

        jQuery( document ).ajaxComplete( function( event, request, options ) {
            if ( request && 4 === request.readyState && 200 === request.status
                && options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

                    var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
                    if ( ! res || res.errors ) {
                        return;
                    }
                    // Clear Thumbnail fields on submit
                    jQuery( '#goldsmith_product_cat_hero_bgimage' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                    jQuery( '#goldsmith_product_cat_hero_bgimage_id' ).val( '' );
                    jQuery( '.goldsmith_term-hero-bgimage-wrap .goldsmith_remove_image_button' ).hide();
                    return;
                }
            } );

        </script>
    </div>
    <div class="clear"></div>
    <?php
}

add_action('product_cat_edit_form_fields', 'goldsmith_wc_taxonomy_edit_meta_field', 15, 1);
//Product Cat Edit page
function goldsmith_wc_taxonomy_edit_meta_field($term) {

    //getting term ID
    $term_id = $term->term_id;

    // retrieve the existing value(s) for this meta field.
    $goldsmith_wc_cat_banner = get_term_meta($term_id, 'goldsmith_wc_cat_banner', true);
    $goldsmith_wc_cat_hero   = get_term_meta($term_id, 'goldsmith_wc_cat_hero', true);
    $thumbnail_id            = absint( get_term_meta( $term_id, 'goldsmith_product_cat_hero_bgimage_id', true ) );
    $image                   = $thumbnail_id ? wp_get_attachment_thumb_url( $thumbnail_id ) : wc_placeholder_img_src();
    ?>
    <tr class="form-field term-goldsmith-banner-wrap">
        <th scope="row" valign="top"><label for="goldsmith_wc_cat_banner"><?php esc_html_e('Goldsmith Banner', 'goldsmith'); ?></label></th>
        <td>
            <textarea name="goldsmith_wc_cat_banner" id="goldsmith_wc_cat_banner" rows="5" cols="50" class="large-text"><?php echo esc_html($goldsmith_wc_cat_banner) ? $goldsmith_wc_cat_banner : ''; ?></textarea>
            <p class="description"><?php esc_html_e('If you want to show a different banner on the archive category page for this category, use this field.Iframe,shortcode,HTML content allowed.', 'goldsmith'); ?></p>
        </td>
    </tr>
    <tr class="form-field term-goldsmith-hero-wrap">
        <th scope="row" valign="top"><label for="goldsmith_wc_cat_hero"><?php esc_html_e('Goldsmith Category Page Hero Template', 'goldsmith'); ?></label></th>
        <td>
            <textarea name="goldsmith_wc_cat_hero" id="goldsmith_wc_cat_hero" rows="5" cols="50" class="large-text"><?php echo esc_html($goldsmith_wc_cat_hero) ? $goldsmith_wc_cat_hero : ''; ?></textarea>
            <p class="description"><?php esc_html_e('If you want to show a different hero template on the archive category page for this category, use this field.Iframe,shortcode,HTML content allowed.', 'goldsmith'); ?></p>
        </td>
    </tr>

    <tr class="form-field goldsmith_term-hero_bgimage-wrap">
        <th scope="row" valign="top"><label><?php esc_html_e( 'Goldsmith Shop Category Page Hero Background Image', 'goldsmith' ); ?></label></th>
        <td>
            <div id="goldsmith_product_cat_hero_bgimage" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
            <div style="line-height: 60px;">
                <input type="hidden" id="goldsmith_product_cat_hero_bgimage_id" name="goldsmith_product_cat_hero_bgimage_id" value="<?php echo esc_attr( $thumbnail_id ); ?>" />
                <button type="button" class="goldsmith_upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'goldsmith' ); ?></button>
                <button type="button" class="goldsmith_remove_image_button button"><?php esc_html_e( 'Remove image', 'goldsmith' ); ?></button>
            </div>
            <div class="clear"></div>
            <span class="description"><?php esc_html_e( 'If you want to show a different background image on the shop archive category page for this category, upload your image from here.', 'goldsmith'); ?></span>
            <script type="text/javascript">

            // Only show the "remove image" button when needed
            if ( '0' === jQuery( '#goldsmith_product_cat_hero_bgimage_id' ).val() ) {
                jQuery( '.goldsmith_term-hero_bgimage-wrap .goldsmith_remove_image_button' ).hide();
            }

            // Uploading files
            var goldsmith_cat_hero_file_frame;

            jQuery( document ).on( 'click', '.goldsmith_term-hero_bgimage-wrap .goldsmith_upload_image_button', function( event ) {

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if ( goldsmith_cat_hero_file_frame ) {
                    goldsmith_cat_hero_file_frame.open();
                    return;
                }

                // Create the media frame.
                goldsmith_cat_hero_file_frame = wp.media.frames.downloadable_file = wp.media({
                    title: '<?php esc_html_e( 'Choose an image', 'goldsmith' ); ?>',
                    button: {
                        text: '<?php esc_html_e( 'Use image', 'goldsmith' ); ?>'
                    },
                    multiple: false
                });

                // When an image is selected, run a callback.
                goldsmith_cat_hero_file_frame.on( 'select', function() {
                    var attachment           = goldsmith_cat_hero_file_frame.state().get( 'selection' ).first().toJSON();
                    var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                    jQuery( '#goldsmith_product_cat_hero_bgimage_id' ).val( attachment.id );
                    jQuery( '#goldsmith_product_cat_hero_bgimage' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
                    jQuery( '.goldsmith_term-hero_bgimage-wrap .goldsmith_remove_image_button' ).show();
                });

                // Finally, open the modal.
                goldsmith_cat_hero_file_frame.open();
            });

            jQuery( document ).on( 'click', '.goldsmith_term-hero_bgimage-wrap .goldsmith_remove_image_button', function() {
                jQuery( '#goldsmith_product_cat_hero_bgimage' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                jQuery( '#goldsmith_product_cat_hero_bgimage_id' ).val( '' );
                jQuery( '.goldsmith_term-hero_bgimage-wrap .goldsmith_remove_image_button' ).hide();
                return false;
            });

            </script>
            <div class="clear"></div>
        </td>
    </tr>
    <?php
}

add_action('edited_product_cat', 'goldsmith_wc_save_taxonomy_custom_meta', 15, 1);
add_action('create_product_cat', 'goldsmith_wc_save_taxonomy_custom_meta', 15, 1);
// Save extra taxonomy fields callback function.
function goldsmith_wc_save_taxonomy_custom_meta( $term_id ) {

    $goldsmith_wc_cat_banner = filter_input(INPUT_POST, 'goldsmith_wc_cat_banner');
    $goldsmith_wc_cat_hero   = filter_input(INPUT_POST, 'goldsmith_wc_cat_hero');
    $goldsmith_product_cat_hero_bgimage_id = filter_input(INPUT_POST, 'goldsmith_product_cat_hero_bgimage_id');
    update_term_meta($term_id, 'goldsmith_wc_cat_banner', $goldsmith_wc_cat_banner);
    update_term_meta($term_id, 'goldsmith_wc_cat_hero', $goldsmith_wc_cat_hero);
    update_term_meta($term_id, 'goldsmith_product_cat_hero_bgimage_id', $goldsmith_product_cat_hero_bgimage_id);
}

//Displaying Additional Columns
add_filter( 'manage_edit-product_cat_columns', 'goldsmith_wc_customFieldsListTitle' ); //Register Function

function goldsmith_wc_customFieldsListTitle( $columns ) {
    $columns['goldsmith_cat_banner'] = esc_html__( 'Banner', 'goldsmith' );
    $columns['goldsmith_wc_cat_hero'] = esc_html__( 'Hero', 'goldsmith' );
    return $columns;
}

add_action( 'manage_product_cat_custom_column', 'goldsmith_wc_customFieldsListDisplay' , 10, 3); //Populating the Columns
function goldsmith_wc_customFieldsListDisplay( $columns, $column, $id ) {
    if ( 'goldsmith_cat_banner' == $column ) {
        $columns = get_term_meta($id, 'goldsmith_wc_cat_banner', true);
        $columns = $columns ? '<span class="gold-wc-banner"></span>' : '';
    }
    if ( 'goldsmith_wc_cat_hero' == $column ) {
        $columns = get_term_meta($id, 'goldsmith_wc_cat_hero', true);
        $columns = $columns ? '<span class="gold-wc-banner"></span>' : '';
    }
    return $columns;
}

if ( ! function_exists( 'goldsmith_wc_per_page_select' ) ) {
    function goldsmith_wc_per_page_select()
    {
        if ( ! wc_get_loop_prop( 'is_paginated' ) ) {
            return;
        }

        $numbers = goldsmith_settings( 'per_page_select_options' );
        $per_page_opt = ( ! empty( $numbers ) ) ? explode( ',', $numbers ) : array( 9, 12, 24, 36 );

        ?>
        <div class="goldsmith-filter-per-page goldsmith-shop-filter-item">
            <ul class="goldsmith-filter-action">
                <li class="goldsmith-per-page-title"><?php esc_html_e( 'Show', 'goldsmith' ); ?></li>
                <?php foreach ( $per_page_opt as $key => $value ) {

                    $link = add_query_arg( 'per_page', $value );

                    $classes = isset( $_GET['per_page'] ) && $_GET['per_page'] === $value ? ' active' : '';
                    $val = $value == -1 ? esc_html__( 'All', 'goldsmith' ) : $value;
                    ?>
                    <li class="goldsmith-per-page-item<?php echo esc_attr( $classes ); ?>">
                        <a rel="nofollow noopener" href="<?php echo esc_url( $link ); ?>"><?php esc_html( printf( '%s', $val ) ); ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <?php
    }
}

if ( ! function_exists( 'goldsmith_wc_column_select' ) ) {
    function goldsmith_wc_column_select()
    {
        if ( ! wc_get_loop_prop( 'is_paginated' ) ) {
            return;
        }
        if ( !goldsmith_get_shop_column() && 'list' == goldsmith_settings( 'shop_product_type', '2' ) ) {
            $col = 1;
        } elseif ( intval(goldsmith_get_shop_column()) > 1 ) {
            $col = intval(goldsmith_get_shop_column());
        } else {
            $col = isset( $_GET['column'] ) && $_GET['column'] ? intval( $_GET['column'] ) : intval( goldsmith_settings( 'shop_colxl' ) );
        }

        $active = $hide = '';
        $cols = array( 1, 2, 3, 4, 5 );

        ?>
        <div class="goldsmith-filter-column-select goldsmith-shop-filter-item">
            <ul class="goldsmith-filter-action goldsmith-filter-columns goldsmith-mini-icon">
                <?php
                foreach ( $cols as $key => $value ) {

                    if ( ( $col < 6 ) && ( $col === $value ) ) {
                        $active = ' active';
                    }
                    if ( $value === 3 ) {
                        $hide = ' d-none d-sm-flex';
                    }
                    if ( $value === 4 ) {
                        $hide = ' d-none d-lg-flex';
                    }
                    if ( $value === 5 ) {
                        $hide = ' d-none d-xl-flex';
                    }
                    ?>
                    <li class="<?php echo esc_attr( 'val-'.$value.$active.$hide ); ?>">
                        <a href="<?php echo esc_url( add_query_arg( 'column', $value ) ); ?>"><?php echo goldsmith_svg_lists('column-'.$value, 'goldsmith-svg-icon');?></a>
                    </li>
                    <?php
                    $active = '';
                }
                ?>
            </ul>
        </div>
        <?php
    }
}


if ( !function_exists( 'goldsmith_wc_category_search_form' ) ) {
    function goldsmith_wc_category_search_form() {

        $terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'parent' => 0 ) );
        ?>
        <div class="header-search-wrap">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/'  ) ) ?>">
                <input  type="text" name="s"
                value="<?php get_search_query() ?>"
                placeholder="<?php echo esc_attr_e( 'Search for your item\'s type.....', 'goldsmith' ) ?>">
                <input type="hidden" name="post_type" value="product" />
                <select class="custom-select" name="product_cat">
                    <option value="" selected><?php echo esc_html_e( 'All Category', 'goldsmith' ) ?></option>
                    <?php
                    foreach ( $terms as $term ) {
                        if ( $term->count >= 1 ) {
                            ?>
                            <option value="<?php echo esc_attr( $term->slug ) ?>"><?php echo esc_html( $term->name ) ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <button class="btn-submit" type="submit"><?php echo goldsmith_svg_lists( 'search' ); ?></button>
                <?php do_action( 'wpml_add_language_form_field' ); ?>
            </form>
        </div>
        <?php
    }
}

if ( !function_exists( 'goldsmith_wc_format_sale_price' ) ) {
    /**
     * Format a sale price for display.
     *
     * @since  3.0.0
     * @param  string $regular_price Regular price.
     * @param  string $sale_price    Sale price.
     * @return string
     */
    add_filter( 'woocommerce_format_sale_price', 'goldsmith_wc_format_sale_price', 10, 3 );
    function goldsmith_wc_format_sale_price( $price, $regular_price, $sale_price ) {
        $price = '<span class="goldsmith-primary-color del"><span>' . ( is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price ) . '</span></span><span class="goldsmith-secondary-color ins">' . ( is_numeric( $sale_price ) ? wc_price( $sale_price ) : $sale_price ) . '</span>';
        return $price;
    }
}


if ( !function_exists( 'goldsmith_shop_main_loop' ) ) {
    add_action('goldsmith_shop_main_loop','goldsmith_shop_main_loop', 10 );
    function goldsmith_shop_main_loop()
    {
        $pagination = apply_filters('goldsmith_shop_pagination_type', goldsmith_settings('shop_paginate_type') );
        $loop       = woocommerce_product_loop();

        echo '<div class="goldsmith-products-wrapper">';

            do_action( 'goldsmith_shop_choosen_filters' );

            if ( $pagination == 'loadmore' || $pagination == 'infinite' ) {
                echo '<div class="shop-data-filters" data-shop-filters=\''.goldsmith_wc_filters_for_ajax().'\'></div>';
            }

            woocommerce_product_loop_start();

            if ( $loop && wc_get_loop_prop( 'total' ) ) {
                while ( have_posts() ) {
                    the_post();

                    /**
                    * Hook: woocommerce_shop_loop.
                    */
                    do_action( 'woocommerce_shop_loop' );

                    wc_get_template_part( 'content', 'product' );
                }
            }

            woocommerce_product_loop_end();

            if ( $loop ) {
                /**
                * Hook: goldsmith_shop_pagination.
                *
                * @hooked goldsmith_shop_pagination
                */
                do_action( 'goldsmith_shop_pagination' );
            } else {
                /**
                * Hook: woocommerce_no_products_found.
                *
                * @hooked wc_no_products_found - 10
                */
                do_action( 'woocommerce_no_products_found' );
            }

        echo '</div>';
    }
}


if ( !function_exists( 'goldsmith_shop_sidebar' ) ) {
    add_action('goldsmith_shop_before_loop','goldsmith_shop_choosen_filters_row', 20 );
    function goldsmith_shop_choosen_filters_row()
    {
        $layout = apply_filters('goldsmith_shop_layout', goldsmith_settings( 'shop_layout', 'left-sidebar' ) );
        if ( '0' == goldsmith_settings( 'choosen_filters_before_loop', '1' ) ) {
            return;
        }
        if ( ('left-sidebar' == $layout || 'right-sidebar' == $layout ) && is_active_sidebar( 'shop-page-sidebar' ) ) {
            ?>
            <div class="goldsmith-choosen-filters-row row goldsmith-hidden-on-mobile">
                <div class="col-12">
                    <?php do_action( 'goldsmith_choosen_filters' );?>
                </div>
            </div>
            <?php
        }
    }
}

if ( !function_exists( 'goldsmith_shop_sidebar' ) ) {
    add_action('goldsmith_shop_sidebar','goldsmith_shop_sidebar', 10 );
    function goldsmith_shop_sidebar()
    {
        $layout = apply_filters('goldsmith_shop_layout', goldsmith_settings( 'shop_layout', 'left-sidebar' ) );
        if ( 'top-sidebar' == $layout || 'fixed-sidebar' == $layout || 'no-sidebar' == $layout || !is_active_sidebar( 'shop-page-sidebar' ) ) {
            return;
        }
        ?>
        <div id="nt-sidebar" class="nt-sidebar default-sidebar col-lg-3">
            <div class="goldsmith-panel-close-button goldsmith-close-sidebar"></div>
            <div class="nt-sidebar-inner-wrapper">
                <?php do_action( 'goldsmith_choosen_filters' );?>
                <div class="nt-sidebar-inner goldsmith-scrollbar">
                    <?php dynamic_sidebar( 'shop-page-sidebar' ); ?>
                </div>
            </div>
        </div>
        <?php
    }
}

if ( !function_exists( 'goldsmith_shop_top_hidden_sidebar' ) ) {
    add_action('goldsmith_shop_before_loop','goldsmith_shop_top_hidden_sidebar', 20 );
    function goldsmith_shop_top_hidden_sidebar()
    {
        $layout = apply_filters('goldsmith_shop_layout', goldsmith_settings( 'shop_layout', 'left-sidebar' ) );
        if ( 'top-sidebar' != $layout || 'no-sidebar' == $layout || !is_active_sidebar( 'shop-page-sidebar' ) ) {
            return;
        }
        $column = goldsmith_settings( 'shop_hidden_sidebar_column', '3' );
        ?>
        <div id="nt-sidebar" class="nt-sidebar goldsmith-shop-hidden-top-sidebar d-none" data-column="row row-cols-<?php echo esc_attr( $column ); ?>">
            <div class="goldsmith-panel-close-button goldsmith-close-sidebar"></div>
            <div class="nt-sidebar-inner-wrapper">
                <?php do_action( 'goldsmith_choosen_filters' );?>
                <div class="nt-sidebar-inner row row-cols-<?php echo esc_attr( $column ); ?>">
                    <?php dynamic_sidebar( 'shop-page-sidebar' ); ?>
                </div>
            </div>
        </div>
        <?php
    }
}

if ( !function_exists( 'goldsmith_shop_sidebar_fixed' ) ) {
    add_action('goldsmith_after_shop_page','goldsmith_shop_sidebar_fixed', 20 );
    function goldsmith_shop_sidebar_fixed()
    {
        $layout = apply_filters('goldsmith_shop_layout', goldsmith_settings( 'shop_layout', 'left-sidebar' ) );
        if ( 'fixed-sidebar' != $layout || 'no-sidebar' == $layout || !is_active_sidebar( 'shop-page-sidebar' ) ) {
            return;
        }
        ?>
        <div id="nt-sidebar" class="nt-sidebar goldsmith-shop-fixed-sidebar">
            <div class="goldsmith-panel-close-button goldsmith-close-sidebar"></div>
            <div class="nt-sidebar-inner-wrapper">
                <?php do_action( 'goldsmith_choosen_filters' );?>
                <div class="nt-sidebar-inner goldsmith-scrollbar">
                    <?php dynamic_sidebar( 'shop-page-sidebar' ); ?>
                </div>
            </div>
        </div>
        <?php
    }
}

if ( !function_exists( 'goldsmith_shop_loop_notices' ) ) {
    add_action('goldsmith_before_wp_footer','goldsmith_shop_loop_notices', 15 );
    function goldsmith_shop_loop_notices()
    {
        if ( is_checkout() || '0' == goldsmith_settings( 'shop_cart_popup_notices_visibility', '1' ) ) {
            return;
        }
        ?>
        <div class="goldsmith-shop-popup-notices postion-<?php echo goldsmith_settings( 'shop_cart_popup_notices_position', 'bottom-right' );?>"></div>
        <?php
    }
}



if ( !function_exists( 'shop_loop_filters_layouts' ) ) {
    add_action('goldsmith_shop_before_loop','shop_loop_filters_layouts', 15 );
    function shop_loop_filters_layouts()
    {
        $defaults = [
            'left'=> [
                'result-count' => ''
            ],
            'right'=> [
                'sidebar-filter' => '',
                'per-page' => '',
                'ordering' => '',
                'column-select' => ''
            ]
        ];
        $layouts = apply_filters( 'goldsmith_get_filters_layouts', goldsmith_settings( 'shop_loop_filters_layouts', $defaults ) );
        $page_layout = apply_filters('goldsmith_shop_layout', goldsmith_settings( 'shop_layout', 'fixed-sidebar' ) );
        if ( $layouts ) {

            unset( $layouts['left']['placebo'] );
            unset( $layouts['right']['placebo'] );

            echo '<div class="goldsmith-inline-two-block goldsmith-before-loop goldsmith-shop-filter-top-area">';

                if ( !empty( $layouts['left'] ) ) {
                    echo '<div class="goldsmith-block-left">';
                        foreach ( $layouts['left'] as $key => $value ) {
                            switch ( $key ) {
                                case 'sidebar-filter':
                                if ( $page_layout == 'top-sidebar' && is_active_sidebar( 'shop-page-sidebar' ) ) {
                                    echo '<div class="goldsmith-toggle-hidden-sidebar"><span>'.esc_html__( 'Filter', 'goldsmith' ).'</span> '.goldsmith_svg_lists( 'filter', 'goldsmith-svg-icon' ).'<div class="goldsmith-filter-close"></div></div>';
                                }
                                if ( $page_layout != 'no-sidebar' && is_active_sidebar( 'shop-page-sidebar' ) ) {
                                    echo '<div class="goldsmith-open-fixed-sidebar"><span>'.esc_html__( 'Filter', 'goldsmith' ).'</span> '.goldsmith_svg_lists( 'filter', 'goldsmith-svg-icon' ).'</div>';
                                }
                                break;

                                case 'search':
                                echo '<div class="top-action-btn" data-name="search-popup">'.goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' ).'</div>';
                                break;

                                case 'result-count':
                                echo '<div class="goldsmith-woo-result-count">';woocommerce_result_count();echo '</div>';
                                break;

                                case 'breadcrumbs':
                                echo '<div class="goldsmith-woo-breadcrumb">'.goldsmith_breadcrumbs().'</div>';
                                break;

                                case 'per-page':
                                echo '<div class="goldsmith-shop-filter-area goldsmith-filter-per-page-area">';
                                    goldsmith_wc_per_page_select();
                                echo '</div>';
                                break;

                                case 'column-select':
                                echo '<div class="goldsmith-shop-filter-area goldsmith-filter-column-select-area">';
                                    goldsmith_wc_column_select();
                                echo '</div>';
                                break;

                                case 'ordering':
                                if ( woocommerce_product_loop() ) {
                                    echo '<div class="goldsmith-shop-filter-area goldsmith-filter-ordering-area">';
                                        woocommerce_catalog_ordering();
                                    echo '</div>';
                                }
                                break;
                            }
                        }
                    echo '</div>';
                }

                if ( !empty( $layouts['right'] ) ) {
                    echo '<div class="goldsmith-block-right">';
                        foreach ( $layouts['right'] as $key => $value ) {
                            switch ( $key ) {

                                case 'sidebar-filter':
                                if ( $page_layout == 'top-sidebar' && is_active_sidebar( 'shop-page-sidebar' ) ) {
                                    echo '<div class="goldsmith-toggle-hidden-sidebar"><span>'.esc_html__( 'Filter', 'goldsmith' ).'</span> '.goldsmith_svg_lists( 'filter', 'goldsmith-svg-icon' ).'<div class="goldsmith-filter-close"></div></div>';
                                }
                                if ( $page_layout != 'no-sidebar' && is_active_sidebar( 'shop-page-sidebar' ) ) {
                                    echo '<div class="goldsmith-open-fixed-sidebar"><span>'.esc_html__( 'Filter', 'goldsmith' ).'</span> '.goldsmith_svg_lists( 'filter', 'goldsmith-svg-icon' ).'</div>';
                                }
                                break;

                                case 'search':
                                echo '<div class="top-action-btn" data-name="search-popup">'.goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' ).'</div>';
                                break;

                                case 'result-count':
                                echo '<div class="goldsmith-woo-result-count">';woocommerce_result_count();echo '</div>';
                                break;

                                case 'breadcrumbs':
                                echo '<div class="goldsmith-woo-breadcrumb">'.goldsmith_breadcrumbs().'</div>';
                                break;

                                case 'per-page':
                                echo '<div class="goldsmith-shop-filter-area goldsmith-filter-per-page-area">';
                                    goldsmith_wc_per_page_select();
                                echo '</div>';
                                break;

                                case 'column-select':
                                echo '<div class="goldsmith-shop-filter-area goldsmith-filter-column-select-area">';
                                    goldsmith_wc_column_select();
                                echo '</div>';
                                break;

                                case 'ordering':
                                if ( woocommerce_product_loop() ) {
                                    echo '<div class="goldsmith-shop-filter-area goldsmith-filter-ordering-area">';
                                        woocommerce_catalog_ordering();
                                    echo '</div>';
                                }
                                break;
                            }
                        }
                    echo '</div>';
                }
            echo '</div>';
        }
    }
}

/**
* Product thumbnail
*/
if ( ! function_exists( 'ninetheme_loop_product_thumb_two_column_size' ) ) {
    function ninetheme_loop_product_thumb_two_column_size()
    {
        return 'goldsmith-grid';
    }
}
if ( ! function_exists( 'ninetheme_loop_product_thumb' ) ) {
    function ninetheme_loop_product_thumb()
    {
        global $product;
        $id         = $product->get_id();
        $show_video = get_post_meta( $id, 'goldsmith_product_video_on_shop', true );
        $iframe_id  = get_post_meta( $id, 'goldsmith_product_iframe_video', true );
        $video_url  = get_post_meta( $id, 'goldsmith_product_popup_video', true );
        $video_type = get_post_meta( $id, 'goldsmith_product_video_source_type', true );
        $hover_img  = goldsmith_settings( 'shop_hover_image_visibility', '0' );
        $has_images = '1' == $hover_img ? ' has-images' : '';
        $gallery    = $product->get_gallery_image_ids();
        $size       = goldsmith_settings('product_imgsize','woocommerce-thumbnail');

        if ( '2' == goldsmith_get_shop_column() ) {
            add_filter( 'single_product_archive_thumbnail_size', 'ninetheme_loop_product_thumb_two_column_size' );
        }
        if ( $iframe_id && $show_video == 'yes' ) {
            if ( 'vimeo' == $video_type ) {
                $iframe_html = '<iframe class="lazy vimeo-video" loading="lazy" src="https://player.vimeo.com/video/'.$iframe_id.'?h=e1515b84ac&autoplay=1&loop=1&title=0&byline=0&portrait=0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe><script src="https://player.vimeo.com/api/player.js"></script>';
            } elseif ( 'hosted' == $video_type ) {
                $iframe_html = '<video class="lazy hosted-video" autoplay muted loop><source src="'.$video_url.'" type="video/mp4"></video>';
            } else {
                $iframe_html = '<iframe class="lazy youtube-video" loading="lazy" src="https://www.youtube.com/embed/'.$iframe_id.'?playlist='.$iframe_id.'&modestbranding=1&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop=1" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" frameborder="0" allowfullscreen></iframe>';
            }
            echo '<div class="goldsmith-loop-product-iframe-wrapper"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'"></a>'.$iframe_html.'</div>';
        } else {
            ?>
            <a href="<?php echo esc_url( get_permalink() ) ?>" class="goldsmith-product-thumb product-link<?php echo esc_attr($has_images); ?>" title="<?php echo get_the_title($id); ?>">
                <?php
                echo woocommerce_get_product_thumbnail();
                if ( '1' == $hover_img && !empty( $gallery ) && !wp_is_mobile() ) {
                    echo wp_get_attachment_image( $gallery[0], $size, "", array( "class" => "overlay-thumb" ) );
                }
                ?>
            </a>
            <?php
        }
    }
}


if ( !function_exists( 'goldsmith_loop_product_type1' ) ) {
    function goldsmith_loop_product_type1()
    {
        global $product;
        $swatches = goldsmith_settings( 'shop_swatches_visibility', '0' );
        $excerpt = goldsmith_settings( 'shop_excerpt_visibility', '0' );
        echo '<div class="woocommerce goldsmith-product-type-1 goldsmith-product-loop-inner" data-id="'.$product->get_id().'">';
            echo '<div class="goldsmith-product-thumb-wrapper">';
                ninetheme_loop_product_thumb();
                echo '<span class="loading-wrapper"><span class="ajax-loading"></span></span>';
                goldsmith_loop_product_nostock();
            echo '</div>';
            echo goldsmith_wishlist_button();
            echo '<div class="goldsmith-product-buttons">';
                echo goldsmith_compare_button();
                echo goldsmith_quickview_button();
                echo goldsmith_add_to_cart('icon');
            echo '</div>';
            echo '<div class="goldsmith-product-labels">';
                goldsmith_product_badge();
                goldsmith_product_discount();
            echo '</div>';
            echo '<div class="goldsmith-transform-replace">';
                echo '<h6 class="goldsmith-product-name"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'">'.get_the_title().'</a></h6>';
                echo '<h6 class="goldsmith-product-cart">';
                    echo goldsmith_add_to_cart();
                echo '</h6>';
            echo '</div>';
            echo '<div class="goldsmith-inline-two-block">';
                woocommerce_template_loop_price();
                woocommerce_template_loop_rating();
            echo '</div>';
            if ( has_excerpt() && $excerpt == '1' ) {
                $limit = goldsmith_settings('shop_loop_excerpt_limit');
                echo '<p class="product-excerpt">'.wp_trim_words( get_the_excerpt(), $limit ).'</p>';
            }
            if ( $swatches == '1' ) {
                echo '<div class="goldsmith-loop-swatches">'.do_shortcode( '[goldsmith_swatches]' ).'</div>';
            }
        echo '</div>';
    }
}

if ( !function_exists( 'goldsmith_loop_product_type2' ) ) {
    function goldsmith_loop_product_type2()
    {
        global $product;
        $swatches = goldsmith_settings( 'shop_swatches_visibility', '0' );
        $excerpt = goldsmith_settings( 'shop_excerpt_visibility', '0' );
        echo '<div class="woocommerce goldsmith-product-type-2 goldsmith-product-loop-inner" data-id="'.$product->get_id().'">';
            echo '<div class="goldsmith-product-thumb-wrapper">';
                ninetheme_loop_product_thumb();
                echo goldsmith_add_to_cart('button');
                echo '<span class="loading-wrapper"><span class="ajax-loading"></span></span>';
                goldsmith_loop_product_nostock();
            echo '</div>';
            echo goldsmith_wishlist_button();
            echo '<div class="goldsmith-product-buttons">';
                echo goldsmith_compare_button();
                echo goldsmith_quickview_button();
            echo '</div>';
            echo '<div class="goldsmith-product-labels">';
                goldsmith_product_badge();
                goldsmith_product_discount();
            echo '</div>';
            echo '<h6 class="goldsmith-product-name"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'">'.get_the_title().'</a></h6>';
            echo '<div class="goldsmith-inline-two-block">';
                woocommerce_template_loop_price();
                woocommerce_template_loop_rating();
            echo '</div>';
            if ( has_excerpt() && $excerpt == '1' ) {
                $limit = goldsmith_settings('shop_loop_excerpt_limit');
                echo '<p class="product-excerpt">'.wp_trim_words( get_the_excerpt(), $limit ).'</p>';
            }
            if ( $swatches == '1' ) {
                echo '<div class="goldsmith-loop-swatches">'.do_shortcode( '[goldsmith_swatches]' ).'</div>';
            }
        echo '</div>';
    }
}

if ( !function_exists( 'goldsmith_loop_product_type_catalog' ) ) {
    function goldsmith_loop_product_type_catalog()
    {
        global $product;
        echo '<div class="woocommerce goldsmith-product-type-2 goldsmith-product-loop-inner" data-id="'.$product->get_id().'">';
            echo '<div class="goldsmith-product-thumb-wrapper">';
                ninetheme_loop_product_thumb();
            echo '</div>';
            echo '<div class="goldsmith-product-labels">';
                goldsmith_product_badge();
                goldsmith_product_discount();
            echo '</div>';
            echo '<h6 class="goldsmith-product-name"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'">'.get_the_title().'</a></h6>';
            echo '<div class="goldsmith-inline-two-block">';
                woocommerce_template_loop_price();
                woocommerce_template_loop_rating();
            echo '</div>';
            $excerpt = goldsmith_settings( 'shop_excerpt_visibility', '0' );
            if ( has_excerpt() && $excerpt == '1' ) {
                $limit = goldsmith_settings('shop_loop_excerpt_limit');
                echo '<p class="product-excerpt">'.wp_trim_words( get_the_excerpt(), $limit ).'</p>';
            }
        echo '</div>';
    }
}

if ( !function_exists( 'goldsmith_loop_product_type3' ) ) {
    function goldsmith_loop_product_type3()
    {
        global $product;
        $swatches = goldsmith_settings( 'shop_swatches_visibility', '0' );
        $excerpt = goldsmith_settings( 'shop_excerpt_visibility', '0' );
        echo '<div class="woocommerce goldsmith-product-type-3 goldsmith-product-loop-inner" data-id="'.$product->get_id().'">';
            echo '<span class="loading-wrapper"><span class="ajax-loading"></span></span>';

            ninetheme_loop_product_thumb();
            echo goldsmith_wishlist_button();
            echo '<div class="goldsmith-product-buttons">';
                echo goldsmith_compare_button();
                echo goldsmith_quickview_button();
            echo '</div>';

            echo '<div class="goldsmith-product-labels">';
                goldsmith_product_badge();
                goldsmith_product_discount();
            echo '</div>';

            echo '<div class="goldsmith-product-details">';
                goldsmith_loop_product_nostock();
                echo '<h6 class="goldsmith-product-name"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'">'.get_the_title().'</a></h6>';
                echo '<div class="goldsmith-inline-two-block">';
                    woocommerce_template_loop_price();
                    woocommerce_template_loop_rating();
                echo '</div>';
                if ( has_excerpt() && $excerpt == '1' ) {
                    $limit = goldsmith_settings('shop_loop_excerpt_limit');
                    echo '<p class="product-excerpt">'.wp_trim_words( get_the_excerpt(), $limit ).'</p>';
                }
                echo goldsmith_add_to_cart('button');
            echo '</div>';
            if ( $swatches == '1' ) {
                echo '<div class="goldsmith-loop-swatches">'.do_shortcode( '[goldsmith_swatches]' ).'</div>';
            }
        echo '</div>';
    }
}

if ( !function_exists( 'goldsmith_loop_product_type_woo_default' ) ) {
    function goldsmith_loop_product_type_woo_default()
    {
        add_action('woocommerce_before_shop_loop_item_title','goldsmith_loop_product_nostock',15);
        echo '<div class="woocommerce goldsmith-product-type-woo-default goldsmith-product-loop-inner">';
            /**
            * Hook: woocommerce_before_shop_loop_item.
            *
            * @hooked woocommerce_template_loop_product_link_open - 10
            */
            do_action( 'woocommerce_before_shop_loop_item' );

            /**
            * Hook: woocommerce_before_shop_loop_item_title.
            *
            * @hooked woocommerce_show_product_loop_sale_flash - 10
            * @hooked woocommerce_template_loop_product_thumbnail - 10
            */
            do_action( 'woocommerce_before_shop_loop_item_title' );

            /**
            * Hook: woocommerce_shop_loop_item_title.
            *
            * @hooked woocommerce_template_loop_product_title - 10
            */
            do_action( 'woocommerce_shop_loop_item_title' );

            /**
            * Hook: woocommerce_after_shop_loop_item_title.
            *
            * @hooked woocommerce_template_loop_rating - 5
            * @hooked woocommerce_template_loop_price - 10
            */
            do_action( 'woocommerce_after_shop_loop_item_title' );

            /**
            * Hook: woocommerce_after_shop_loop_item.
            *
            * @hooked woocommerce_template_loop_product_link_close - 5
            * @hooked woocommerce_template_loop_add_to_cart - 10
            */
            do_action( 'woocommerce_after_shop_loop_item' );

        echo '</div>';
    }
}


if ( !function_exists( 'goldsmith_loop_product_type_list' ) ) {
    function goldsmith_loop_product_type_list()
    {
        global $product;
        $pid        = $product->get_id();
        $stock      = get_post_meta( $pid, '_stock', true );
        $sold       = $product->get_total_sales();
        $percentage = $sold > 0 && $stock > 0 ? round( $sold / $stock * 100 ) : 0;
        $swatches   = goldsmith_settings( 'shop_swatches_visibility', '0' );

        echo '<div class="woocommerce loop-list-item goldsmith-product-loop-inner" data-id="'.$pid.'">';
            echo '<div class="list-inner parent-loading">';

                echo '<div class="thumb-wrapper">';
                    ninetheme_loop_product_thumb();
                    echo goldsmith_wishlist_button();
                    echo '<div class="list-buttons">';
                        echo goldsmith_compare_button();
                        echo goldsmith_quickview_button();
                    echo '</div>';
                    goldsmith_loop_product_nostock();

                    echo '<div class="list-product-labels">';
                        goldsmith_product_badge();
                        goldsmith_product_discount();
                    echo '</div>';
                    if ( wc_review_ratings_enabled() ) {
                        echo '<div class="rating list-part">';
                            woocommerce_template_single_rating();
                        echo '</div>';
                    }
                    echo '<span class="loading-wrapper"><span class="ajax-loading"></span></span>';
                echo '</div>';

                echo '<div class="details-wrapper">';
                    echo '<h6 class="title list-part"><a href="'.esc_url( get_permalink() ).'" title="'.get_the_title().'">'.get_the_title().'</a></h6>';
                    echo '<p class="list-price list-part">';woocommerce_template_loop_price();echo '</p>';
                    if ( has_excerpt()  ) {
                        $limit = goldsmith_settings('shop_loop_excerpt_limit');
                        echo '<p class="product-details list-part">'.wp_trim_words( get_the_excerpt(), $limit ).'</p>';
                    }
                    echo '<div class="progressbar-wrapper list-part">';
                        echo '<div class="stock-progress">';
                            echo '<div class="stock-progressbar" style="width:'.$percentage.'%"></div>';
                        echo '</div>';
                        echo '<div class="stock-details">';
                            echo '<div class="stock-sold"><span class="status-label">'.esc_html__('Sold:', 'goldsmith').' </span><span class="status-value">'.$sold.'</span></div>';
                            if ( $stock>0  ) {
                                echo '<div class="current-stock"><span class="status-label">'.esc_html__('Available:', 'goldsmith').' </span><span class="status-value">'.wc_trim_zeros($stock).'</span></div>';
                            }
                        echo '</div>';
                    echo '</div>';
                    if ( $swatches == '1' ) {
                        echo '<div class="goldsmith-loop-swatches">'.do_shortcode( '[goldsmith_swatches]' ).'</div>';
                    }
                    echo '<div class="addtocart-wrapper list-part">';
                        echo goldsmith_add_to_cart('button');
                    echo '</div>';
                echo '</div>';

            echo '</div>';
        echo '</div>';
    }
}


if ( !function_exists( 'goldsmith_loop_product_layout_manager' ) ) {
    function goldsmith_loop_product_layout_manager($column='')
    {
        global $product;

        $layouts = goldsmith_settings( 'shop_loop_product_layouts' );
        $type    = goldsmith_settings( 'shop_product_box_type', '2' );
        if ( $layouts ) {
            unset( $layouts['show']['placebo'] );
            echo '<div class="woocommerce goldsmith-product-loop-inner goldsmith-layout-custom goldsmith-product-type-'.$type.'" data-id="'.$product->get_id().'">';

                foreach ( $layouts['show'] as $key => $value ) {

                    switch ( $key ) {

                        case 'thumb':
                        echo '<div class="goldsmith-product-thumb-wrapper">';
                            if ( array_key_exists( 'wishlist', $layouts['show'] ) ) {
                                echo goldsmith_wishlist_button();
                            }

                            ninetheme_loop_product_thumb();

                            if ( array_key_exists( 'quickview', $layouts['show'] ) || array_key_exists( 'compare', $layouts['show'] ) ) {
                                echo '<div class="goldsmith-product-buttons">';
                                    if ( array_key_exists( 'quickview', $layouts['show'] ) ) {
                                        echo goldsmith_quickview_button();
                                    }
                                    if ( array_key_exists( 'compare', $layouts['show'] ) ) {
                                        echo goldsmith_compare_button();
                                    }
                                echo '</div>';
                            }
                            if ( array_key_exists( 'cart', $layouts['show'] ) && $type == '2' ) {
                                echo goldsmith_add_to_cart('button');
                            }
                        echo '</div>';
                        break;

                        case 'title':
                        echo '<h6 class="goldsmith-product-name"><a href="'.esc_url( get_permalink() ).'">'.get_the_title().'</a></h6>';
                        break;

                        case 'price':
                            woocommerce_template_loop_price();
                        break;

                        case 'rating':
                            woocommerce_template_loop_rating();
                        break;

                        case 'cart':
                            if ( $type == '1' ) {
                                echo '<div class="goldsmith-cart-static">'.goldsmith_add_to_cart('button').'</div>';
                            }
                        break;

                        case 'swatches':
                            echo '<div class="goldsmith-loop-swatches">'.do_shortcode( '[goldsmith_swatches]' ).'</div>';
                        break;

                        case 'sale':
                            echo '<div class="goldsmith-product-labels">';
                                goldsmith_product_badge();
                            echo '</div>';
                        break;

                        case 'discount':
                            echo '<div class="goldsmith-product-labels">';
                                goldsmith_product_discount();
                            echo '</div>';
                        break;

                        case 'desc':
                            goldsmith_product_excerpt();
                        break;
                    }
                }
            echo '</div>';
        }
    }
}


/**
* Single Bottom Popup Product Add To Cart
*/
if ( ! function_exists( 'goldsmith_product_bottom_popup_cart' ) ) {
    add_action( 'goldsmith_before_wp_footer', 'goldsmith_product_bottom_popup_cart', 10 );
    function goldsmith_product_bottom_popup_cart()
    {
        global $product;

        if ( !is_product() || $product->is_type( 'grouped' ) || '0' == goldsmith_settings( 'goldsmith_product_bottom_popup_cart', '0' ) ) {
            return;
        }
        if ( '1' == goldsmith_settings( 'woo_catalog_mode', '0' ) && '1' == goldsmith_settings( 'woo_disable_product_addtocart', '0' ) ) {
            return;
        }
        ?>
        <div id="product-bottom-<?php the_ID(); ?>" <?php wc_product_class( 'goldsmith-product-bottom-popup-cart', $product ); ?>>

            <div class="row">
                <div class="col-12 col-md-6 d-none d-md-flex">
                    <div class="goldsmith-product-bottom-details">
                        <?php echo get_the_post_thumbnail( $product->get_id(), array(60,60,true) ); ?>
                        <div class="goldsmith-product-bottom-title">
                            <?php echo get_the_title( $product->get_id() ); ?>
                            <?php woocommerce_template_loop_price(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="goldsmith-product-bottom-title mobile-title">
                        <?php echo get_the_title( $product->get_id() ); ?>
                    </div>
                    <?php
                    if ( $product->is_type( 'simple' ) ) {
                        woocommerce_template_single_add_to_cart();
                    } else {
                        $btn_title = esc_html__( 'Add to cart', 'goldsmith' );
                        echo '<div class="goldsmith-product-to-top"><a href="#product-'.$product->get_id().'" class="goldsmith-btn goldsmith-btn-medium goldsmith-bg-black">'.$btn_title.'</a></div>';
                    }
                    ?>
                </div>
            </div>

        </div>
        <?php
    }
}

if ( ! function_exists( 'goldsmith_get_swatches_colors' ) ) {
    function goldsmith_get_swatches_colors()
    {
        $colors = array();
        $attributes = wc_get_attribute_taxonomies();
        foreach ( $attributes as $attribute ) {
            if ( taxonomy_exists( wc_attribute_taxonomy_name( $attribute->attribute_name ) ) ) {
                $attr_id   = wc_attribute_taxonomy_id_by_name( $attribute->attribute_name );
                $attr_info = wc_get_attribute( $attr_id );

                if ( $attr_info->type == 'color' ) {
                    $terms = get_terms(wc_attribute_taxonomy_name($attribute->attribute_name), 'orderby=name&hide_empty=0');
                    foreach ( $terms as $term ) {
                        if ( !empty( $term->term_id ) ) {
                            $val = get_term_meta( $term->term_id, 'goldsmith_swatches_color', true );
                            if ( !empty( $val ) ) {
                                $colors[$term->name] = $val;
                            }
                        }
                    }
                }
            }
        }
        return !empty( $colors ) ? $colors : false;
    }
}

if ( ! function_exists( 'goldsmith_get_swatches_images' ) ) {
    function goldsmith_get_swatches_images()
    {
        $colors = array();
        $attributes = wc_get_attribute_taxonomies();
        foreach ( $attributes as $attribute ) {
            if ( taxonomy_exists( wc_attribute_taxonomy_name( $attribute->attribute_name ) ) ) {
                $attr_id   = wc_attribute_taxonomy_id_by_name( $attribute->attribute_name );
                $attr_info = wc_get_attribute( $attr_id );

                if ( $attr_info->type == 'image' ) {
                    $terms = get_terms(wc_attribute_taxonomy_name($attribute->attribute_name), 'orderby=name&hide_empty=0');
                    foreach ( $terms as $term ) {
                        if ( !empty( $term->term_id ) ) {
                            $val = get_term_meta( $term->term_id, 'goldsmith_swatches_image', true );
                            $colors[$term->name] = $val;
                        }
                    }
                }
            }
        }
        return !empty( $colors ) ? $colors : false;
    }
}


/*************************************************
## Product Trust Image and Text
*************************************************/
if ( !function_exists( 'goldsmith_product_trust_image' ) ) {
    add_action( 'woocommerce_single_product_summary', 'goldsmith_product_trust_image', 100 );
    function goldsmith_product_trust_image()
    {
        global $product;
        if ( '1' == goldsmith_settings('product_trust_image_visibility', '1') ) {
            $image = goldsmith_settings('product_trust_image');
            $size  = goldsmith_settings('product_trust_image_size');

            $terms = wc_get_product_term_ids( $product->get_id(), 'product_cat' );
            $category_exclude = goldsmith_settings( 'product_trust_category_exclude', null );
            if ( !empty($terms) ) {
                foreach ($terms as $term ) {
                    if ( !empty($category_exclude) ) {
                        foreach ($category_exclude as $val ) {
                            if ( $term == $val ) {
                                return;
                            }
                        }
                    }
                }
            }

            if ( '1' == goldsmith_settings('product_trust_image_elementor_template', null) ) {

                goldsmith_print_elementor_templates( 'product_trust_image_elementor_template', '' );

            } else {

                if ( !empty( $image['id'] ) ) {
                ?>
                <div class="goldsmith-summary-item goldsmith-product-trust-badge">
                        <div class="goldsmith-trust-badge-image">
                            <?php echo wp_get_attachment_image( $image['id'], $size ); ?>
                        </div>
                    <?php if ( '' != goldsmith_settings('product_trust_image_text') ) { ?>
                        <div class="goldsmith-trust-badge-text"><?php echo goldsmith_settings('product_trust_image_text'); ?></div>
                    <?php } else { ?>
                        <div class="goldsmith-trust-badge-text"><?php esc_html_e('Guaranteed safe &amp; secure checkout','goldsmith'); ?></div>
                    <?php } ?>
                </div>
                <?php
                }
            }
        }
    }
}

/*************************************************
## Shipping Class Name
*************************************************/
if ( !function_exists( 'goldsmith_shipping_class_name' ) ) {
    function goldsmith_shipping_class_name( $type = 'name' )
    {
        global $product;
        $class_id = $product->get_shipping_class_id();
        if ( $class_id ) {
            $term = get_term_by( 'id', $class_id, 'product_shipping_class' );
            if( $type == 'desc' ) {
                if ( $term && ! is_wp_error( $term ) ) {
                    return $term->description;
                }
            } else {
                if ( $term && ! is_wp_error( $term ) ) {
                    return $term->name;
                }
            }
        }
        return '';
    }
}


/*************************************************
## Shop Fast Filters
*************************************************/
if ( !function_exists( 'goldsmith_shop_check_fast_filters' ) ) {
    function goldsmith_shop_check_fast_filters()
    {
        $terms = goldsmith_settings( 'shop_fast_filter_terms' );

        $check_filters = false;

        if ( !empty( $terms ) ) {
            foreach ( $terms as $tax ) {
                if ( isset( $_GET['filter_'.$tax] ) ) {
                    $check_filters = true;
                }
            }
        }

        if ( ( isset( $_GET['featured'] ) && $_GET['featured'] == 'yes' )
        || ( isset( $_GET['best_seller'] ) && $_GET['best_seller'] == 'yes' )
        || ( isset( $_GET['rating_filter'] ) && $_GET['rating_filter'] == '5' )
        || ( isset( $_GET['on_sale'] ) && $_GET['on_sale'] == 'onsale' )
        || ( isset( $_GET['stock_status'] ) && $_GET['stock_status'] == 'instock' )
        || $check_filters ) {
            return true;
        }

        return false;
    }
}
if ( !function_exists( 'goldsmith_shop_top_fast_filters' ) ) {

    add_action( 'goldsmith_shop_before_loop', 'goldsmith_shop_top_fast_filters',12 );
    function goldsmith_shop_top_fast_filters()
    {
        global $wp;

        $has_filter = goldsmith_shop_check_fast_filters();

        if ( '0' == goldsmith_settings('shop_fast_filter_visibility', '1' ) ) {
            return;
        }

        wp_enqueue_style( 'goldsmith-wc-fast-filters' );

        $filter_main = goldsmith_settings( 'shop_fast_filter_main' );
        $terms       = goldsmith_settings( 'shop_fast_filter_terms' );
        $is_ajax     = '1' == goldsmith_settings( 'shop_fast_filter_ajax' ) ? ' is-ajax' : '';
        $stock_sale  = 'show-always' == goldsmith_settings( 'shop_fast_filter_stock_sale_status' ) ? ' show-always' : ' show-after-filter';

        // titles
        $maintitle_title  = goldsmith_settings( 'shop_fast_filter_main_title' );
        $removeall_title  = goldsmith_settings( 'shop_fast_filter_remove_title' );
        $featured_title   = goldsmith_settings( 'shop_fast_filter_featured_title' );
        $bestseller_title = goldsmith_settings( 'shop_fast_filter_bestseller_title' );
        $toprated_title   = goldsmith_settings( 'shop_fast_filter_toprated_title' );
        $onsale_title     = goldsmith_settings( 'shop_fast_filter_onsale_title' );
        $instock_title    = goldsmith_settings( 'shop_fast_filter_instock_title' );

        $titles = [
            'maintitle'  => $maintitle_title ? $maintitle_title : esc_html__('Fast Filters:', 'goldsmith'),
            'removeall'  => $removeall_title ? $removeall_title : esc_html__('Remove All', 'goldsmith'),
            'featured'   => $featured_title ? $featured_title : esc_html__('Featured', 'goldsmith'),
            'bestseller' => $bestseller_title ? $bestseller_title : esc_html__('Best sellers', 'goldsmith'),
            'toprated'   => $toprated_title ? $toprated_title : esc_html__('Top rated', 'goldsmith'),
            'onsale'     => $onsale_title ? $onsale_title : esc_html__('On Sale', 'goldsmith'),
            'instock'    => $instock_title ? $instock_title : esc_html__('In Stock', 'goldsmith')
        ];

        // icons
        $featured_icon    = goldsmith_settings( 'shop_fast_filter_featured_icon' );
        $bestseller_icon  = goldsmith_settings( 'shop_fast_filter_bestseller_icon' );
        $toprated_icon    = goldsmith_settings( 'shop_fast_filter_toprated_icon' );
        $onsale_icon      = goldsmith_settings( 'shop_fast_filter_onsale_icon' );
        $instock_icon     = goldsmith_settings( 'shop_fast_filter_instock_icon' );

        $featured_icon    = trim($featured_icon) ? $featured_icon : goldsmith_svg_lists( 'featured', 'goldsmith-svg-icon' );
        $bestseller_icon  = trim($bestseller_icon) ? $bestseller_icon :  goldsmith_svg_lists( 'best-seller', 'goldsmith-svg-icon' );
        $toprated_icon    = trim($toprated_icon) ? $toprated_icon : goldsmith_svg_lists( 'top-rated', 'goldsmith-svg-icon' );
        $onsale_icon      = trim($onsale_icon) ? $onsale_icon : goldsmith_svg_lists( 'onsale', 'goldsmith-svg-icon' );
        $instock_icon     = trim($instock_icon) ? $instock_icon : goldsmith_svg_lists( 'instock-2', 'goldsmith-svg-icon' );

        if ( '' === get_option( 'permalink_structure' ) ) {
            $baselink = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
        } else {
            $baselink = preg_replace( '%\/page/[0-9]+%', '', home_url( add_query_arg( null, null ) ) );
        }

        $shoplink = wc_get_page_permalink( 'shop' );

        $is_filter = $has_filter ? ' has-filter' : '';

        ?>
        <div class="goldsmith-shop-fast-filters is-shop<?php echo esc_attr( $is_ajax.$stock_sale ); ?>">

            <?php if ( '1' == goldsmith_settings( 'shop_fast_filter_before_label_visibility', '1' ) ) { ?>
                <span class="fast-filters-label"><strong><?php echo esc_html( $titles['maintitle'] ); ?></strong></span>
            <?php } ?>

            <ul class="goldsmith-fast-filters-list filters-first<?php echo esc_attr( $is_filter ); ?>">

                <?php if ( $has_filter ) { ?>
                    <li class="remove-fast-filter active">
                        <a href="<?php echo esc_url( remove_query_arg( array_keys( $_GET ) ) ); ?>" class="goldsmith-fast-filter-link">
                            <span class="remove-filter"></span> <?php echo esc_html( $titles['removeall'] ); ?>
                        </a>
                    </li>
                <?php } ?>

                <?php
                if ( !empty( $filter_main['show'] ) ) {
                    unset( $filter_main['show']['placebo'] );
                    foreach ( $filter_main['show'] as $key => $value ) {
                        switch($key) {
                            case 'featured':
                            if ( isset( $_GET['featured'] ) && $_GET['featured'] == 'yes' ) {
                                echo '<li class="active"><a href="'.esc_url( remove_query_arg( 'featured' ) ).'"><span class="remove-filter"></span>'.$featured_icon.esc_html( $titles['featured'] ).'</a></li>';
                            } else {
                                echo '<li><a href="'.esc_url( add_query_arg( 'featured',wc_clean( wp_unslash( 'yes' ) ) ) ).'">'.$featured_icon.' '.esc_html( $titles['featured'] ).'</a></li>';
                            }
                            break;

                            case 'bestseller':
                            if ( isset( $_GET['best_seller'] ) && $_GET['best_seller'] == 'yes' ) {
                                echo '<li class="active"><a href="'.esc_url( remove_query_arg( 'best_seller' ) ).'"><span class="remove-filter"></span>'.$bestseller_icon.esc_html( $titles['bestseller'] ).'</a></li>';
                            } else {
                                echo '<li><a href="'.esc_url( add_query_arg( 'best_seller',wc_clean( wp_unslash( 'yes' ) ) ) ).'">'.$bestseller_icon.' '.esc_html( $titles['bestseller'] ).'</a></li>';
                            }
                            break;

                            case 'toprated':
                            if ( isset( $_GET['rating_filter'] ) && $_GET['rating_filter'] == '5' ) {
                                echo '<li class="active"><a href="'.esc_url( remove_query_arg( 'rating_filter' ) ).'"><span class="remove-filter"></span>'.$toprated_icon.esc_html( $titles['toprated'] ).'</a></li>';
                            } else {
                                echo '<li><a href="'.esc_url( add_query_arg( 'rating_filter', wc_clean( wp_unslash( '5' ) ) ) ).'">'.$toprated_icon.''.esc_html( $titles['toprated'] ).'</a></li>';
                            }
                            break;
                        }
                    }
                }

                if ( $has_filter || 'show-always' == goldsmith_settings( 'shop_fast_filter_stock_sale_status' ) ) {

                    if ( isset( $_GET['on_sale'] ) && $_GET['on_sale'] == 'onsale' ) {
                        echo '<li class="on-sale active'.esc_attr( $stock_sale ).'"><a href="'.esc_url( remove_query_arg( 'on_sale' ) ).'"><span class="remove-filter"></span> '.$onsale_icon.esc_html( $titles['onsale'] ).'</a></li>';
                    } else {
                        echo '<li class="on-sale'.esc_attr( $stock_sale ).'"><a href="'.esc_url( add_query_arg( 'on_sale', wc_clean( wp_unslash( 'onsale' ) ) ) ).'">'.$onsale_icon.' '.esc_html( $titles['onsale'] ).'</a></li>';
                    }

                    if ( isset( $_GET['stock_status'] ) && $_GET['stock_status'] == 'instock' ) {
                        echo '<li class="instock active'.esc_attr( $stock_sale ).'"><a href="'.esc_url( remove_query_arg( 'stock_status' ) ).'"><span class="remove-filter"></span>'.$instock_icon.' '.esc_html( $titles['instock'] ).'</a></li>';
                    } else {
                        echo '<li class="instock'.esc_attr( $stock_sale ).'"><a href="'.esc_url( add_query_arg( 'stock_status', wc_clean( wp_unslash( 'instock' ) ) ) ).'">'.$instock_icon.esc_html( $titles['instock'] ).'</a></li>';
                    }
                }

                if ( !empty( $terms ) ) {
                    foreach ( $terms as $tax ) {
                        $terms_title = goldsmith_settings( 'shop_fast_filter_terms_title_'.$tax );
                        $terms_attr  = goldsmith_settings( 'shop_fast_filter_terms_attr_'.$tax );
                        $terms_icon  = goldsmith_settings( 'shop_fast_filter_terms_icon_'.$tax );
                        $terms_icon  = $terms_icon ? $terms_icon : '';

                        if ( $terms_title && !empty( $terms_attr ) && !is_wp_error($terms_attr) ) {
                            $terms_active = $has_filter == true && isset( $_GET['filter_'.$tax] ) ? ' active' : '';
                            echo '<li class="goldsmith-has-submenu'. $terms_active .'">';
                            if ( $has_filter && isset( $_GET['filter_'.$tax] ) ) {
                                printf('%s','<a href="'.esc_url( remove_query_arg( 'filter_'.$tax ) ).'"><span class="remove-filter"></span>'.$terms_icon.$terms_title.'</a>');
                            } else {
                                printf('%s','<a href="#0">'.$terms_icon.$terms_title.'</a>');
                            }
                                echo '<ul class="goldsmith-fast-filters-submenu">';

                                foreach ( $terms_attr as $term ) {
                                    $term_name = get_term_by( 'id', $term, 'pa_'.$tax);

                                    if ( $has_filter && isset( $_GET['filter_'.$tax] ) && ( $_GET['filter_'.$tax] == $term_name->slug ) ) {
                                        echo '<li class="active"><a href="'.esc_url( remove_query_arg( 'filter_'.$tax ) ).'"><span class="remove-filter"></span> '.$term_name->name.'</a></li>';
                                    } else {
                                        if ( !empty( $term_name ) ) {
                                            echo '<li><a href="'.esc_url( add_query_arg( 'filter_'.$tax, wc_clean( wp_unslash( $term_name->slug ) ) ) ).'">'.$term_name->name.'</a></li>';
                                        }
                                    }
                                }
                                echo '</ul>';
                            echo '</li>';
                        }
                    }
                }
                ?>
            </ul>
        </div>
        <?php
    }
}

if ( ! function_exists( 'goldsmith_show_products_by_tax_query' ) ) {
    add_action( 'woocommerce_product_query', 'goldsmith_product_query', 10, 2 );
    function goldsmith_product_query( $q )
    {
        if ( is_shop() ) {
            if ( isset( $_GET['featured'] ) && $_GET['featured'] == 'yes' ) {
                $q->set( 'tax_query', array (
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => array( 'featured' ),
                        'operator' => 'IN'
                    )
                ));
            }

            if ( isset( $_GET['best_seller'] ) && $_GET['best_seller'] == 'yes' ) {
                $q->set ( 'meta_key', 'total_sales' );
                $q->set ( 'orderby', 'meta_value_num' );
            }
        }
    }
}

if ( ! function_exists( 'goldsmith_cart_goal_progressbar' ) ) {
    //add_action( 'woocommerce_before_cart', 'goldsmith_cart_goal_progressbar', 10 );
    add_action( 'goldsmith_side_panel_after_header', 'goldsmith_cart_goal_progressbar', 10 );
    function goldsmith_cart_goal_progressbar()
    {
        $amount = round( goldsmith_settings( 'free_shipping_progressbar_amount', 500 ), wc_get_price_decimals() );
        if ( !( $amount > 0 ) || '1' != goldsmith_settings( 'free_shipping_progressbar_visibility', 1 ) ) {
            return;
        }

        $message_initial = goldsmith_settings( 'free_shipping_progressbar_message_initial' );
        $message_success = goldsmith_settings( 'free_shipping_progressbar_message_success' );

        $total     = WC()->cart->get_displayed_subtotal();
        $remainder = ( $amount - $total );
        //$value     = $total <= $amount ? ( $total / $amount ) * 100 : 0;
        $success   = $total >= $amount ? ' free-shipping-success' : '';
        $value     = $total >= $amount ? ( $total / $amount ) * 100 : 0;
        if ( is_cart() ) {
            $success .= ' cart-page-goal';
        } elseif ( is_checkout() ) {
            $success .= ' checkout-page-goal';
        }

        ?>
        <div class="goldsmith-cart-goal-wrapper<?php echo esc_attr( $success ); ?>">
            <div class="goldsmith-cart-goal-text">
                <?php
                if ( $total >= $amount ) {
                    if ( $message_success ) {
                        echo sprintf('%s', $message_success );
                    } else {
                        echo sprintf('%s <strong>%s</strong>',
                        esc_html__('Congrats! You are eligible for', 'goldsmith'),
                        esc_html__('more to enjoy FREE Shipping', 'goldsmith'));
                    }
                } else {
                    if ( $message_initial ) {
                        echo sprintf('%s', str_replace( '[remainder]', wc_price( $remainder ), $message_initial ) );
                    } else {
                        echo sprintf('%s %s <strong>%s</strong>',
                        esc_html__('Buy', 'goldsmith'),
                        wc_price( $remainder ),
                        esc_html__('more to enjoy FREE Shipping', 'goldsmith'));
                    }
                }
                ?>
                <div data-percent="<?php echo esc_attr( $value ); ?>" class="goldsmith-cart-goal-percent"></div>
            </div>
            <div class="goldsmith-free-shipping-progress">
                <div class="goldsmith-progress-bar-wrap">
                    <div class="goldsmith-progress-bar" style="width:<?php echo esc_attr( $value ); ?>%;">
                        <div class="goldsmith-progress-value">
                            <?php echo goldsmith_svg_lists( 'delivery-return', 'goldsmith-svg-icon' ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
*  countdown for product
*/
if ( ! function_exists( 'goldsmith_product_cart_countdown' ) ) {

    add_action( 'goldsmith_side_panel_after_header', 'goldsmith_product_cart_countdown', 1 );
    function goldsmith_product_cart_countdown()
    {
        if ( '0' != goldsmith_settings('cart_limited_timer_visibility','1') ) {

            $time = goldsmith_settings('cart_limited_timer_date');
            $text = goldsmith_settings('cart_limited_timer_message_initial');

            if ( $time ) {

                $data[] = '"min":"'.esc_html__('min', 'goldsmith').'"';
                $data[] = '"sec":"'.esc_html__('sec', 'goldsmith').'"';

                echo '<div class="goldsmith-summary-item goldsmith-viewed-offer-time">';
                    if ( $text ) {
                        echo '<p class="offer-time-text">'.$text.'</p>';
                    }
                    echo '<div class="goldsmith-cart-timer" data-time="'.$time.'"></div>';
                echo '</div>';
            }
        }
    }
}

if ( ! function_exists( 'goldsmith_product_extra_html' ) ) {
    function goldsmith_product_extra_html()
    {
        if ( '' != goldsmith_settings('goldsmith_product_extra_html','') ) {
            $html = goldsmith_settings('goldsmith_product_extra_html');
            echo '<div class="goldsmith-summary-item goldsmith-product-extra-html">'.do_shortcode($html).'</div>';
        }
    }
}

if ( ! function_exists( 'goldsmith_product_page_custom_btn' ) ) {
    add_action( 'woocommerce_single_product_summary', 'goldsmith_product_page_custom_btn', 31 );
    function goldsmith_product_page_custom_btn()
    {
        if ( '1' == goldsmith_settings('product_custom_btn_visibility', '0' ) ) {
            $page_link = get_the_permalink();
            $page_id   = get_the_ID();
            $action    = goldsmith_settings('product_custom_btn_action','');
            $title     = goldsmith_settings('product_custom_btn_title','');
            $link      = goldsmith_settings('product_custom_btn_link','');
            $target    = goldsmith_settings( 'product_custom_btn_target' );
            $shortcode = goldsmith_settings('product_custom_btn_form_shortcode','');
            $wlink     = goldsmith_settings( 'product_custom_btn_whatsapp_link' );
            $wlink     = $wlink ? $wlink : 'https://api.whatsapp.com/send?text=' . urlencode( $page_link );
            $wm_link   = goldsmith_settings( 'product_custom_btn_whatsapp_mobile_link' );
            $wm_link   = $wm_link ? $wm_link : 'whatsapp://send?text=' . urlencode( $page_link );
            $w_link    = wp_is_mobile() ? $wm_link : $wlink;

            echo '<div class="goldsmith-summary-item goldsmith-product-action-button" data-action="'.$action.'">';
                if ( 'link' == $action ) {

                    echo '<a class="goldsmith-btn goldsmith-btn-dark goldsmith-btn-solid goldsmith-btn-square goldsmith-btn-large" href="'.$link.'" target="'.$target.'">'.$title.'</a>';

                } elseif ( 'form' == $action ) {

                    echo '<a class="goldsmith-btn goldsmith-btn-dark goldsmith-btn-solid goldsmith-btn-square goldsmith-btn-large" data-fancybox="dialog" data-src="#dialog-content-'.$page_id.'">'.$title.'</a>';
                    echo '<div id="dialog-content-'.$page_id.'" style="display:none;max-width:500px;">'.do_shortcode( $shortcode ).'</div>';

                } elseif ( 'whatsapp' == $action ) {

                    echo '<a rel="noopener noreferrer nofollow" href="'.$w_link.'" target="'.esc_html( $target ).'" class="goldsmith-btn goldsmith-btn-dark goldsmith-btn-solid goldsmith-btn-square goldsmith-btn-large">
                        <i class="fab fa-whatsapp"></i>
                        <span class="whatsapp-text">'.$title.'</span>
                    </a>';
                }
            echo '</div>';
        }
    }
}

if ( !function_exists( 'goldsmith_product_summary_layouts_manager' ) ) {
    function goldsmith_product_summary_layouts_manager()
    {
        if ( 'default' == goldsmith_settings( 'single_shop_summary_layout_type', 'default' ) ) {
            return;
        }
        $defaults = [
            'show'=> [
                'bread' => '',
                'title' => '',
                'rating' => '',
                'price' => '',
                'excerpt' => '',
                'cart' => '',
                'meta' => ''
            ]
        ];

        $layouts = goldsmith_settings( 'single_shop_summary_layouts', $defaults );

        if ( $layouts ) {

            unset( $layouts['show']['placebo'] );

            foreach ( $layouts['show'] as $key => $value ) {
                switch ( $key ) {
                    case 'bread':
                        echo goldsmith_breadcrumbs();
                    break;
                    case 'title':
                        woocommerce_template_single_title();
                    break;
                    case 'labels':
                        goldsmith_single_stretch_type_product_labels();
                    break;
                    case 'rating':
                        woocommerce_template_single_rating();
                    break;
                    case 'price':
                        woocommerce_template_single_price();
                    break;
                    case 'cart':
                        woocommerce_template_single_add_to_cart();
                    break;
                    case 'excerpt':
                         woocommerce_template_single_excerpt();
                    break;
                    case 'meta':
                         woocommerce_template_single_meta();
                    break;
                    case 'timer':
                         goldsmith_product_countdown();
                    break;
                    case 'visitors-message':
                         goldsmith_product_visitiors_message();
                    break;
                    case 'trust-badge':
                         goldsmith_product_trust_image();
                    break;
                    case 'progressbar':
                         goldsmith_product_stock_progress_bar();
                    break;
                    case 'extra':
                         goldsmith_product_extra_html();
                    break;
                }
            }
        }
    }
}

$goldsmith_options = get_option('goldsmith');

if ( '1' == $goldsmith_options['woo_catalog_mode'] && '1' == $goldsmith_options['woo_disable_cart_checkout'] ) {
    add_filter( 'get_pages','goldsmith_hide_cart_checkout_pages' );
    add_filter( 'wp_get_nav_menu_items', 'goldsmith_hide_cart_checkout_pages' );
    add_filter( 'wp_nav_menu_objects', 'goldsmith_hide_cart_checkout_pages' );
    add_action( 'wp', 'goldsmith_check_pages_redirect' );
}

if ( !function_exists( 'goldsmith_hide_cart_checkout_pages' ) ) {
    function goldsmith_hide_cart_checkout_pages( $pages )
    {
        $excluded_pages = array(
            wc_get_page_id( 'cart' ),
            wc_get_page_id( 'checkout' )
        );

        foreach ( $pages as $key => $page ) {

            if ( in_array( current_filter(), array( 'wp_get_nav_menu_items', 'wp_nav_menu_objects' ), true ) ) {
                $page_id = $page->object_id;
                if ( 'page' !== $page->obect_id ) {
                    continue;
                }
            } else {
                $page_id = $page->ID;
            }

            if ( in_array( (int) $page_id, $excluded_pages, true ) ) {
                unset( $pages[ $key ] );
            }
        }

        return $pages;
    }
}

if ( !function_exists( 'goldsmith_check_pages_redirect' ) ) {
    function goldsmith_check_pages_redirect()
    {
        $cart     = is_page( wc_get_page_id( 'cart' ) );
        $checkout = is_page( wc_get_page_id( 'checkout' ) );

        wp_reset_postdata();

        if ( $cart || $checkout ) {
            wp_safe_redirect( home_url() );
            exit;
        }
    }
}

if ( !function_exists( 'goldsmith_remove_some_fields_checkout' ) ) {
    add_filter( 'woocommerce_checkout_fields' , 'goldsmith_remove_some_fields_checkout' );
    function goldsmith_remove_some_fields_checkout($fields)
    {
        if ( 'no' == goldsmith_settings( 'checkout_form_customize', 'no' ) ) {
            return $fields;
        }

        $layouts  = goldsmith_settings( 'checkout_form_layouts' );

        // billing fields
        if ( '0' == $layouts['billing_first_name'] ) {
            unset( $fields['billing']['billing_first_name'] );
        }
        if ( '0' == $layouts['billing_last_name'] ) {
            unset( $fields['billing']['billing_last_name'] );
        }
        if ( '0' == $layouts['billing_company'] ) {
            unset( $fields['billing']['billing_company'] );
        }
        if ( '0' == $layouts['billing_address_1'] ) {
            unset( $fields['billing']['billing_address_1'] );
        }
        if ( '0' == $layouts['billing_address_2'] ) {
            unset( $fields['billing']['billing_address_2'] );
        }
        if ( '0' == $layouts['billing_city'] ) {
            unset( $fields['billing']['billing_city'] );
        }
        if ( '0' == $layouts['billing_postcode'] ) {
            unset( $fields['billing']['billing_postcode'] );
        }
        if ( '0' == $layouts['billing_country'] ) {
            unset( $fields['billing']['billing_country'] );
        }
        if ( '0' == $layouts['billing_state'] ) {
            unset( $fields['billing']['billing_state'] );
        }
        if ( '0' == $layouts['billing_phone'] ) {
            unset( $fields['billing']['billing_phone'] );
        }
        if ( '0' == $layouts['billing_email'] ) {
            unset( $fields['billing']['billing_email'] );
        }
        // order field
        if ( '0' == $layouts['order_comments'] ) {
            unset( $fields['order']['order_comments'] );
        }
        // account fields
        if ( '0' == $layouts['account_username'] ) {
            unset( $fields['account']['account_username'] );
        }
        if ( '0' == $layouts['account_password'] ) {
            unset( $fields['account']['account_password'] );
        }
        if ( '0' == $layouts['account_password-2'] ) {
            unset( $fields['account']['account_password-2'] );
        }
        // shipping fields
        if ( '0' == $layouts['shipping_first_name'] ) {
            unset( $fields['shipping']['shipping_first_name'] );
        }
        if ( '0' == $layouts['shipping_last_name'] ) {
            unset( $fields['shipping']['shipping_last_name'] );
        }
        if ( '0' == $layouts['shipping_company'] ) {
            unset( $fields['shipping']['shipping_company'] );
        }
        if ( '0' == $layouts['shipping_address_1'] ) {
            unset( $fields['shipping']['shipping_address_1'] );
        }
        if ( '0' == $layouts['shipping_address_2'] ) {
            unset( $fields['shipping']['shipping_address_2'] );
        }
        if ( '0' == $layouts['shipping_city'] ) {
            unset( $fields['shipping']['shipping_city'] );
        }
        if ( '0' == $layouts['shipping_postcode'] ) {
            unset( $fields['shipping']['shipping_postcode'] );
        }
        if ( '0' == $layouts['shipping_country'] ) {
            unset( $fields['shipping']['shipping_country'] );
        }
        if ( '0' == $layouts['shipping_state'] ) {
            unset( $fields['shipping']['shipping_state'] );
        }

        return $fields;
    }
}

if ( !function_exists( 'goldsmith_remove_requirement_from_fields_checkout' ) ) {
    add_filter( 'woocommerce_billing_fields', 'goldsmith_remove_requirement_from_fields_checkout', 10, 1 );
    function goldsmith_remove_requirement_from_fields_checkout($fields)
    {
        if ( 'no' == goldsmith_settings( 'checkout_form_customize', 'no' ) ) {
            return $fields;
        }

        $required = goldsmith_settings( 'checkout_form_required_fields_layouts' );

        // billing fields
        if ( '0' == $required['billing_first_name'] && isset( $fields['billing_first_name'] ) ) {
            $fields['billing_first_name']['required'] = false;
        }
        if ( '0' == $required['billing_last_name'] && isset( $fields['billing_last_name'] ) ) {
            $fields['billing_last_name']['required'] = false;
        }
        if ( '1' == $required['billing_company'] && isset( $fields['billing_company'] ) ) {
            $fields['billing_company']['required'] = true;
        }
        if ( '0' == $required['billing_address_1'] && isset( $fields['billing_address_1'] ) ) {
            $fields['billing_address_1']['required'] = false;
        }
        if ( '1' == $required['billing_address_2'] && isset( $fields['billing_address_2'] ) ) {
            $fields['billing_address_2']['required'] = true;
        }
        if ( '0' == $required['billing_city'] && isset( $fields['billing_city'] ) ) {
            $fields['billing_city']['required'] = false;
        }
        if ( '0' == $required['billing_postcode'] && isset( $fields['billing_postcode'] ) ) {
            $fields['billing_postcode']['required'] = false;
        }
        if ( '0' == $required['billing_country'] && isset( $fields['billing_country'] ) ) {
            $fields['billing_country']['required'] = false;
        }
        if ( '0' == $required['billing_state'] && isset( $fields['billing_state'] ) ) {
            $fields['billing_state']['required'] = false;
        }
        if ( '0' == $required['billing_phone'] && isset( $fields['billing_phone'] ) ) {
            $fields['billing_phone']['required'] = false;
        }
        if ( '0' == $required['billing_email'] && isset( $fields['billing_email'] ) ) {
            $fields['billing_email']['required'] = false;
        }

        return $fields;
    }
}

if ( !function_exists( 'goldsmith_remove_requirement_shipping_from_fields_checkout' ) ) {
    add_filter( 'woocommerce_shipping_fields', 'goldsmith_remove_requirement_shipping_from_fields_checkout', 10, 1 );
    function goldsmith_remove_requirement_shipping_from_fields_checkout($fields)
    {
        if ( 'no' == goldsmith_settings( 'checkout_form_customize', 'no' ) ) {
            return $fields;
        }

        $required = goldsmith_settings( 'checkout_form_required_fields_layouts' );

        // shipping fields
        if ( '0' == $required['shipping_first_name'] && isset( $fields['shipping_first_name'] ) ) {
            $fields['shipping_first_name']['required'] = false;
        }
        if ( '0' == $required['shipping_last_name'] && isset( $fields['shipping_last_name'] ) ) {
            $fields['shipping_last_name']['required'] = false;
        }
        if ( '1' == $required['shipping_company'] && isset( $fields['shipping_company'] ) ) {
            $fields['shipping_company']['required'] = true;
        }
        if ( '0' == $required['shipping_address_1'] && isset( $fields['shipping_address_1'] ) ) {
            $fields['shipping_address_1']['required'] = false;
        }
        if ( '1' == $required['shipping_address_2'] && isset( $fields['shipping_address_2'] ) ) {
            $fields['shipping_company']['required'] = true;
        }
        if ( '0' == $required['shipping_city'] && isset( $fields['shipping_city'] ) ) {
            $fields['shipping_city']['required'] = false;
        }
        if ( '0' == $required['shipping_postcode'] && isset( $fields['shipping_postcode'] ) ) {
            $fields['shipping_postcode']['required'] = false;
        }
        if ( '0' == $required['shipping_country'] && isset( $fields['shipping_country'] ) ) {
            $fields['shipping_country']['required'] = false;
        }
        if ( '0' == $required['shipping_state'] && isset( $fields['shipping_state'] ) ) {
            $fields['shipping_state']['required'] = false;
        }

        return $fields;
    }
}


/**
* Exclude products from a particular category on the shop page
*/
if ( ! function_exists( 'goldsmith_shop_custom_query' ) ) {

    add_action( 'woocommerce_product_query', 'goldsmith_shop_custom_query' );
    function goldsmith_shop_custom_query( $q )
    {
        if ( is_shop() && '1' == goldsmith_settings( 'shop_custom_query_visibility', '0' ) ) {

            $tax_query        = $q->get( 'tax_query' );
            $meta_query       = $q->get( 'meta_query' );
            $scenario         = goldsmith_settings( 'shop_custom_query_scenario' );
            $cats             = goldsmith_settings( 'shop_custom_query_cats', null );
            $tags             = goldsmith_settings( 'shop_custom_query_tags', null );
            $attrs            = goldsmith_settings( 'shop_custom_query_attr', null );
            $order            = goldsmith_settings( 'shop_custom_query_order' );
            $orderby          = goldsmith_settings( 'shop_custom_query_orderby' );
            $cats_operator    = 'include' == goldsmith_settings( 'shop_custom_query_cats_operator' ) ? 'IN' : 'NOT IN';
            $tags_operator    = 'include' == goldsmith_settings( 'shop_custom_query_tags_operator' ) ? 'IN' : 'NOT IN';
            $display_operator = 'include' == goldsmith_settings( 'shop_custom_query_display_mode_operator' ) ? 'IN' : 'NOT IN';
            $perpage          = wp_is_mobile() ? goldsmith_settings( 'shop_custom_query_mobile_perpage' ) : goldsmith_settings( 'shop_custom_query_perpage' );
            $per_page         = isset( $_GET['per_page'] ) && $_GET['per_page'] ? esc_html( $_GET['per_page'] ) : $perpage;

            $q->set( 'order', $order );
            $q->set( 'posts_per_page', $per_page );

            $args['tax_query'] = array(
                'relation' => 'AND'
            );

            if ( 'featured' == $scenario ) {

                $tax_query[] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN'
                );

            } elseif ( 'on-sale' == $scenario ) {

                $meta_query[] = array(
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

                $q->set( 'orderby', 'meta_value_num' );
                $q->set( 'meta_key', 'total_sales' );

            } elseif ( 'rated' == $scenario ) {

                $q->set( 'meta_key', '_wc_average_rating' );
                $q->set( 'order', 'DESC' );
                $q->set( 'orderby', 'meta_value_num' );

            } elseif ( 'popularity' == $scenario ) {

                $q->set( 'meta_key', 'total_sales' );
                $q->set( 'order', 'DESC' );
                $q->set( 'orderby', 'meta_value_num' );

            } else {

                $q->set( 'orderby', $orderby );

            }

            if ( !empty( $cats ) ) {
                $tax_query[] = array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $cats,
                    'operator' => $cats_operator
                );
            }

            if ( !empty( $tags ) ) {
                $tax_query[] = array(
                    'taxonomy' => 'product_tag',
                    'field'    => 'term_id',
                    'terms'    => $tags,
                    'operator' => $tags_operator
                );
            }

            if ( !empty( $attrs ) ) {
                foreach ( $attrs as $key ) {

                    $attr_terms      = goldsmith_settings( 'shop_custom_query_attr_terms_'.$key );
                    $terms_operator  = 'include' == goldsmith_settings( 'shop_custom_query_attr_terms_operator_'.$key ) ? 'IN' : 'NOT IN';
                    $attr_id         = wc_attribute_taxonomy_id_by_name( $key );
                    $attr_info       = wc_get_attribute( $attr_id );

                    if ( !empty( $attr_terms ) ) {
                        $tax_query[] = array(
                            'taxonomy' => $attr_info->slug,
                            'field'    => 'term_id',
                            'terms'    => $attr_terms,
                            'operator' => $terms_operator
                        );
                    }
                }
            }
            $q->set( 'meta_query', $meta_query );
            $q->set( 'tax_query', $tax_query );
        }
    }
}

/*************************************************
## Recently Viewed Products Always
*************************************************/
if ( ! function_exists( 'goldsmith_track_product_view' ) ) {
    remove_action( 'template_redirect', 'wc_track_product_view', 20 );
    add_action( 'template_redirect', 'goldsmith_track_product_view', 20 );
    function goldsmith_track_product_view() {
        if ( ! is_singular( 'product' ) || '1' != goldsmith_settings( 'shop_recently_visibility', 1 ) ) {
            return;
        }

        global $post;

        if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) { // @codingStandardsIgnoreLine.
            $viewed_products = array();
        } else {
            $viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ); // @codingStandardsIgnoreLine.
        }

        // Unset if already in viewed products list.
        $keys = array_flip( $viewed_products );

        if ( isset( $keys[ $post->ID ] ) ) {
            unset( $viewed_products[ $keys[ $post->ID ] ] );
        }

        $viewed_products[] = $post->ID;

        if ( count( $viewed_products ) > 15 ) {
            array_shift( $viewed_products );
        }

        // Store for session only.
        wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
    }
}

/*************************************************
## Recently Viewed Products Loop
*************************************************/
if ( ! function_exists( 'goldsmith_recently_viewed_product_loop' ) ) {
    add_action('goldsmith_after_main_content','goldsmith_recently_viewed_product_loop');
    function goldsmith_recently_viewed_product_loop()
    {
        if ( ! is_singular( 'product' ) || '1' != goldsmith_settings( 'shop_recently_visibility', 1 ) ) {
            return;
        }
        $viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array(); // @codingStandardsIgnoreLine
        $viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

        if ( empty( $viewed_products) || !is_woocommerce() ) {
            return;
        }

        $heading = goldsmith_settings('shop_recently_title', '');
        $heading = $heading ? esc_html( $heading ) : esc_html__( 'Recently Viewed Products', 'goldsmith' );

        $slider_options = json_encode(array(
            "loop"          => '1' == goldsmith_settings( 'shop_recently_loop', '0' ) ? true : false,
            "speed"         => intval(goldsmith_settings( 'shop_recently_speed', 800 )),
            "spaceBetween"  => intval(goldsmith_settings( 'shop_recently_gap', 20 )),
            "slidesPerView" => 1,
            "grabCursor"    => true,
            "autoHeight"    => false,
            "watchSlidesProgress" => true,
            "autoplay"      => '1' == goldsmith_settings( 'shop_recently_autoplay', 1 ) ? ["pauseOnMouseEnter" => true,"disableOnInteraction" => false] : false,
            "navigation"    => [
                "nextEl" => ".goldsmith-product-recently .goldsmith-swiper-next",
                "prevEl" => ".goldsmith-product-recently .goldsmith-swiper-prev"
            ],
            "breakpoints"   => [
                "0" => [
                    "slidesPerView" => intval(goldsmith_settings( 'shop_recently_smperview', 2 ))
                ],
                "768" => [
                    "slidesPerView" => intval(goldsmith_settings( 'shop_recently_mdperview', 3 ))
                ],
                "1024" => [
                    "slidesPerView" => intval(goldsmith_settings( 'shop_recently_perview', 6 ))
                ]
            ]
        ));

        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 8,
            'post__in'       => $viewed_products,
            'orderby'        => 'post__in',
            'post_status'    => 'publish'
        );

        $loop = new WP_Query( $args );

        if ( $loop ) {
            ?>
            <div class="goldsmith-product-recently goldsmith-recently-product-wrapper goldsmith-section">
                <div class="container">
                    <div class="row">
                        <div class="col-12">

                            <div class="section-title-wrapper">
                                <?php if ( $heading ) : ?>
                                    <h4 class="section-title"><?php echo esc_html( $heading ); ?></h4>
                                <?php endif; ?>
                                <div class="recently-slider-nav">
                                    <div class="goldsmith-slide-nav goldsmith-swiper-prev"></div>
                                    <div class="goldsmith-slide-nav goldsmith-swiper-next"></div>
                                </div>
                            </div>

                            <div class="goldsmith-wc-swipper-wrapper woocommerce">
                                <div class="goldsmith-swiper-slider goldsmith-swiper-container" data-swiper-options="<?php echo esc_attr( $slider_options ); ?>">
                                    <div class="swiper-wrapper">
                                        <?php
                                            if ( $loop->have_posts() ) {
                                                while ( $loop->have_posts() ) {
                                                    $loop->the_post();
                                                    echo '<div class="swiper-slide">';
                                                        wc_get_template_part( 'content', 'product' );
                                                    echo '</div>';
                                                }
                                            } else {
                                                echo esc_html__( 'No products found', 'goldsmith');
                                            }
                                            wp_reset_postdata();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if ( !function_exists( 'goldsmith_fly_cart' ) ) {
    add_action( 'goldsmith_before_wp_footer', 'goldsmith_fly_cart' );
    function goldsmith_fly_cart()
    {
        if ( class_exists('WooCommerce') && '1' == goldsmith_settings( 'shop_fly_cart_visibility', '0' ) ) {
            $count = WC()->cart->get_cart_contents_count();
            if ( 'page' == goldsmith_settings( 'shop_fly_cart_action_type', 'panel' ) ) {
                ?>
                <div id="goldsmith-sticky-cart-toggle" class="goldsmith-sticky-cart-toggle has-page-link" data-duration="<?php echo goldsmith_settings( 'shop_fly_cart_duration', 1500 ); ?>">
                    <a class="goldsmith-view-cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                        <span class="goldsmith-cart-count goldsmith-wc-count"><?php echo esc_html( $count ); ?></span>
                        <?php echo goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ); ?>
                    </a>
                </div>
                <?php
            } else {
                ?>
                <div id="goldsmith-sticky-cart-toggle" class="goldsmith-sticky-cart-toggle" data-duration="<?php echo goldsmith_settings( 'shop_fly_cart_duration', 1500 ); ?>">
                    <span class="goldsmith-cart-count goldsmith-wc-count"><?php echo esc_html( $count ); ?></span>
                    <?php echo goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ); ?>
                </div>
                <?php
            }
        }
    }
}


if ( !function_exists( 'wooma_custom_stock_status_filter' ) ) {
    add_action( 'woocommerce_product_query', 'wooma_custom_stock_status_filter', 10, 2 );
    function wooma_custom_stock_status_filter($query)
    {
        if ( isset( $_GET['stock_status'] ) && $_GET['stock_status'] === 'instock' ) {
            $query->set('meta_query', array(
                array(
                    'key'     => '_stock_status',
                    'value'   => 'instock',
                    'compare' => '='
                )
            ));
        }

        if ( isset( $_GET['on_sale'] ) && $_GET['on_sale'] == 'onsale' ) {
            $query->set ( 'post__in', wc_get_product_ids_on_sale() );
        }
    }
}
