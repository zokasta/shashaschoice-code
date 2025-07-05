<?php

/**
 * Custom template parts for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package goldsmith
*/


/*************************************************
##  LOGO
*************************************************/


if ( ! function_exists( 'goldsmith_logo' ) ) {
    function goldsmith_logo( $mobile=false )
    {
        $logo          = goldsmith_settings( 'logo_type', 'sitename' );
        $mainlogo      = '' != goldsmith_settings( 'img_logo' ) ? goldsmith_settings( 'img_logo' )[ 'id' ] : '';
        $stickylogo    = '' != goldsmith_settings( 'sticky_logo' ) ? goldsmith_settings( 'sticky_logo' )[ 'id' ] : '';
        $mobilelogo    = $mobile == true && '' != goldsmith_settings( 'mobile_logo' ) ? goldsmith_settings( 'mobile_logo' )[ 'id' ] : '';
        $hasstickylogo = '' != $stickylogo ? ' has-sticky-logo': '';
        $type          = true == $mobile ? 'nav-logo logo-type-'.$logo : 'logo logo-type-'.$logo;

        if ( is_page() ) {
            $page_logo  = goldsmith_page_settings( 'goldsmith_page_header_logo' );
            $mainlogo   = !empty( $page_logo['url'] ) ? $page_logo['url'] : $mainlogo;
            $logo       = !empty( $page_logo['url'] ) ? 'img' : $logo;
            $page_slogo = goldsmith_page_settings( 'goldsmith_page_header_sticky_logo' );
            $stickylogo = !empty( $page_slogo['url'] ) ? $page_slogo['url'] : $stickylogo;
            $hasstickylogo = !empty( $page_slogo['url'] ) ? ' has-sticky-logo': $hasstickylogo;
        }

        if ( '0' != goldsmith_settings( 'logo_visibility', '1' ) ) {
            ?>
            <div class="<?php echo esc_attr( $type ); ?>">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"  aria-label="logo image" class="nt-logo header-logo logo-type-<?php echo esc_attr( $logo.$hasstickylogo ); ?>">

                    <?php
                    if ( 'img' == $logo && '' != $mainlogo ) {

                        if ( true == $mobile && $mobilelogo ) {

                            echo wp_get_attachment_image($mobilelogo,'full', false, ['class' => 'mobile-menu-logo','alt' => esc_attr( get_bloginfo( 'name' ) )]);

                        } else {

                            if ( '' != $mainlogo ) {

                                echo wp_get_attachment_image($mainlogo, 'full', false, ['class' => 'main-logo','alt' => esc_attr( get_bloginfo( 'name' ) )]);

                                if ( '' != $stickylogo ) {

                                    echo wp_get_attachment_image($stickylogo, 'full', false, ['class' => 'main-logo sticky-logo','alt' => esc_attr( get_bloginfo( 'name' ) )]);

                                }
                            }
                        }

                    } elseif ( 'sitename' == $logo ) {

                        echo '<span class="header-text-logo">'.esc_html( get_bloginfo( 'name' ) ).'</span>';

                    } elseif ( 'customtext' == $logo ) {

                        echo '<span class="header-text-logo">'.goldsmith_settings( 'text_logo' ).'</span>';

                    } else {

                        echo '<span class="header-text-logo">'.esc_html( get_bloginfo( 'name' ) ).'</span>';

                    }
                    ?>
                </a>
            </div>
            <?php
        }
    }
}


if ( ! function_exists( 'goldsmith_sidebar_header' ) ) {
    //add_action( 'goldsmith_header_action', 'goldsmith_sidebar_header' );
    function goldsmith_sidebar_header()
    {
        if ( ! class_exists( 'Redux' ) || false == goldsmith_settings( 'header_layouts' ) ) {

            goldsmith_sidebar_header_simple();

            return;

        } else {

            ?>
            <div class="goldsmith-mobile-header-spacer"></div>
            <div class="goldsmith-mobile-header">
                <?php goldsmith_logo(); ?>
                <div class="goldsmith-mobile-header-actions">
                    <span class="goldsmith-mobile-search-trigger mobile-header-actions">
                        <?php echo goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' ); ?>
                    </span>
                    <span class="goldsmith-mobile-menu-trigger mobile-header-actions">
                        <?php echo goldsmith_svg_lists( 'bars', 'goldsmith-svg-icon' ); ?>
                    </span>
                </div>
            </div>

            <div class="goldsmith-main-sidebar-header goldsmith-scrollbar">
                <div class="goldsmith-mobile-header-top">
                    <div class="goldsmith-mobile-header-actions">
                        <?php goldsmith_logo(); ?>
                        <div class="goldsmith-mobile-menu-close-trigger goldsmith-panel-close-button"></div>
                    </div>
                    <?php if ( has_nav_menu( 'header_menu' ) ) { ?>
                        <div class="goldsmith-main-sidebar-inner goldsmith-scrollbar">
                            <ul class="primary-menu">
                                <?php
                                echo wp_nav_menu(array(
                                    'menu' => '',
                                    'theme_location' => 'header_menu',
                                    'container' => '',
                                    'container_class' => '',
                                    'container_id' => '',
                                    'menu_class' => '',
                                    'menu_id' => '',
                                    'items_wrap' => '%3$s',
                                    'before' => '',
                                    'after' => '',
                                    'link_before' => '',
                                    'link_after' => '',
                                    'echo' => true,
                                    'fallback_cb' => 'Goldsmith_Wp_Bootstrap_Navwalker::fallback',
                                    'walker' => new Goldsmith_Wp_Bootstrap_Navwalker()
                                ));
                                ?>
                            </ul>
                        </div>
                        <?php

                    } else {

                        if ( current_user_can('edit_theme_options') ) {
                            echo '<div class="goldsmith-main-sidebar-inner goldsmith-scrollbar">
                                <ul class="primary-menu">
                                    <li class="menu-item"><a href="'.admin_url('nav-menus.php').'">'.esc_html__('Add a menu', 'goldsmith').'</a></li>
                                </ul>
                            </div>';
                        }
                    }
                    ?>

                    <?php if ( has_nav_menu( 'sidebar_second_menu' ) ) { ?>
                        <div class="goldsmith-main-sidebar-inner second-menu goldsmith-scrollbar">
                            <ul class="primary-menu">
                                <?php
                                echo wp_nav_menu(array(
                                    'menu' => '',
                                    'theme_location' => 'sidebar_second_menu',
                                    'container' => '',
                                    'container_class' => '',
                                    'container_id' => '',
                                    'menu_class' => '',
                                    'menu_id' => '',
                                    'items_wrap' => '%3$s',
                                    'before' => '',
                                    'after' => '',
                                    'link_before' => '',
                                    'link_after' => '',
                                    'echo' => true,
                                    'fallback_cb' => 'Goldsmith_Wp_Bootstrap_Navwalker::fallback',
                                    'walker' => new Goldsmith_Wp_Bootstrap_Navwalker()
                                ));
                                ?>
                            </ul>
                        </div>
                    <?php } ?>

                    <?php
                    goldsmith_sidebar_lang_menu();

                    if ( goldsmith_settings( 'sidebar_header_extra_html', '' ) ) {
                        echo '<div class="goldsmith-sidebar-extra-content">'.goldsmith_settings( 'sidebar_header_extra_html', '' ).'</div>';
                    }
                    ?>

                </div>

                <div class="goldsmith-mobile-header-bottom">
                    <?php goldsmith_header_buttons_layouts(); ?>
                    <?php if ( class_exists('WooCommerce') && shortcode_exists( 'goldsmith_wc_ajax_search' ) ) { ?>
                        <div class="search-area-top active">
                            <?php echo goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' ); ?>
                            <?php echo do_shortcode('[goldsmith_wc_ajax_search]'); ?>
                        </div>
                    <?php } ?>
                </div>

                <nav class="goldsmith-header-mobile after-sidebar-header">
                    <div class="goldsmith-panel-close no-bar"></div>

                    <div class="goldsmith-header-mobile-content">
                        <div class="category-area action-content" data-target-name="search-cats">
                            <?php if ( '' != goldsmith_settings('sidebar_panel_categories_custom_title') ) { ?>
                                <span class="panel-top-title"><?php echo esc_html( goldsmith_settings('sidebar_panel_categories_custom_title') ); ?></span>
                            <?php } else { ?>
                                <span class="panel-top-title"><?php esc_html_e( 'All Products Categories', 'goldsmith' ); ?></span>
                            <?php } ?>
                            <div class="category-area-inner goldsmith-scrollbar">
                                <?php goldsmith_get_all_products_categories(); ?>
                            </div>
                        </div>

                        <?php goldsmith_popup_myaccount_form_template(); ?>
                    </div>
                </nav>

            </div>
            <?php
        }
    }
}


if ( ! function_exists( 'goldsmith_sidebar_header_simple' ) ) {
    function goldsmith_sidebar_header_simple()
    {
        ?>
        <div class="goldsmith-mobile-header-spacer"></div>
        <div class="goldsmith-mobile-header">
            <?php goldsmith_logo(); ?>
            <div class="goldsmith-mobile-header-actions">
                <span class="goldsmith-mobile-menu-trigger mobile-header-actions">
                    <?php echo goldsmith_svg_lists( 'bars', 'goldsmith-svg-icon' ); ?>
                </span>
            </div>
        </div>

        <div class="goldsmith-main-sidebar-header goldsmith-scrollbar">
            <div class="goldsmith-mobile-header-top">
                <div class="goldsmith-mobile-header-actions">
                    <?php goldsmith_logo(); ?>
                    <div class="goldsmith-mobile-menu-close-trigger goldsmith-panel-close-button"></div>
                </div>
                <?php if ( has_nav_menu( 'header_menu' ) ) { ?>
                    <div class="goldsmith-main-sidebar-inner goldsmith-scrollbar">
                        <ul class="primary-menu">
                            <?php
                            echo wp_nav_menu(array(
                                'menu' => '',
                                'theme_location' => 'header_menu',
                                'container' => '',
                                'container_class' => '',
                                'container_id' => '',
                                'menu_class' => '',
                                'menu_id' => '',
                                'items_wrap' => '%3$s',
                                'before' => '',
                                'after' => '',
                                'link_before' => '',
                                'link_after' => '',
                                'echo' => true,
                                'fallback_cb' => 'Goldsmith_Wp_Bootstrap_Navwalker::fallback',
                                'walker' => new Goldsmith_Wp_Bootstrap_Navwalker()
                            ));
                    		?>
                        </ul>
                    </div>

                <?php } else {

                    if ( current_user_can('edit_theme_options') ) {
                        echo '<div class="goldsmith-main-sidebar-inner goldsmith-scrollbar">
                            <ul class="primary-menu">
                                <li class="menu-item"><a href="'.admin_url('nav-menus.php').'">'.esc_html__('Add a menu', 'goldsmith').'</a></li>
                            </ul>
                        </div>';
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
}


if ( ! function_exists( 'goldsmith_sidebar_lang_menu' ) ) {
    function goldsmith_sidebar_lang_menu()
    {
        if ( '1' == goldsmith_settings( 'sidebar_menu_lang_visibility', '0' ) ) {

            if ( has_action( 'wpml_add_language_selector' ) ) {

                echo '<div class="goldsmith-sidemenu-lang-switcher">';
                    do_action('wpml_add_language_selector');
                echo '</div>';

            } elseif ( function_exists( 'pll_the_languages' ) ) {

                echo '<div class="goldsmith-sidemenu-lang-switcher">';
                    pll_the_languages(
                        array(
                            'show_flags'=>1,
                            'show_names'=>1,
                            'dropdown'=>1,
                            'raw'=>0,
                            'hide_current'=>0,
                            'display_names_as'=>'name'
                        )
                    );
                echo '</div>';

            } else {

                wp_enqueue_script( 'sliding-menu');

                if ( has_nav_menu( 'header_lang_menu' ) ) {

                    $lang_menu = wp_nav_menu(
                        array(
                            'menu' => '',
                            'theme_location' => 'header_lang_menu',
                            'container' => '',
                            'container_class' => '',
                            'container_id' => '',
                            'menu_class' => '',
                            'menu_id' => '',
                            'items_wrap' => '%3$s',
                            'before' => '',
                            'after' => '',
                            'link_before' => '',
                            'link_after' => '',
                            'depth' => 2,
                            'echo' => false,
                            'fallback_cb' => 'Goldsmith_Wp_Bootstrap_Navwalker::fallback',
                            'walker' => new Goldsmith_Wp_Bootstrap_Navwalker()
                        )
                    );
                    echo '<div class="goldsmith-sidemenu-lang-switcher">
                        <div class="goldsmith-header-lang-slide-menu">
                            <ul class="goldsmith-lang-menu">'.$lang_menu.'</ul>
                        </div>
                    </div>';
                }
            }
        }
    }
}

if ( ! function_exists( 'goldsmith_header_buttons_layouts' ) ) {
    function goldsmith_header_buttons_layouts()
    {
        $layouts = goldsmith_settings( 'header_buttons_layouts' );

        if ( class_exists( 'WooCommerce') ) {
            if ( is_product() && '1' == goldsmith_settings( 'single_shop_different_header_layouts', '0' ) ) {
                $layouts  = goldsmith_settings( 'single_shop_header_buttons_layouts' );
            } elseif ( is_shop() && '1' == goldsmith_settings( 'shop_different_header_layouts', '0' ) ) {
                $layouts  = goldsmith_settings( 'shop_header_buttons_layouts' );
            }
        }

        $layouts = apply_filters( 'header_buttons_layouts', $layouts );
        $catalog_mode = goldsmith_settings( 'woo_catalog_mode', '0' );
        $html = $html_out = '';
        if ( $layouts ) {
            unset( $layouts['show']['placebo'] );
            foreach ( $layouts['show'] as $key => $value ) {

                switch ( $key ) {
                    case 'cart':
                    if ( class_exists( 'WooCommerce') && '1' != $catalog_mode ) {
                        $count = WC()->cart->get_cart_contents_count();
                        if ( '1' == goldsmith_settings( 'disable_minicart', '0' ) ) {
                            $html .= '<div class="top-action-btn"><a class="cart-page-link" href="'.esc_url( wc_get_page_permalink( 'cart' ) ).'"><span class="goldsmith-cart-count goldsmith-wc-count">'.$count.'</span>'.goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ).'</a></div>';
                        } else {
                            $html .= '<div class="top-action-btn" data-name="cart"><span class="goldsmith-cart-count goldsmith-wc-count">'.$count.'</span>'.goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ).'</div>';
                        }
                    }
                    break;

                    case 'wishlist':
                    if ( class_exists( 'Goldsmith_Wishlist' ) && '1' != $catalog_mode ) {
                        $html .= '<div class="top-action-btn" data-name="wishlist"><span class="goldsmith-wishlist-count goldsmith-wc-count"></span>'.goldsmith_svg_lists( 'love', 'goldsmith-svg-icon' ).'</div>';
                    }
                    break;

                    case 'compare':
                    if ( class_exists( 'WPCleverWoosc' ) && '1' != $catalog_mode ) {
                        $html .= '<div class="top-action-btn has-custom-action open-compare-btn"><span class="goldsmith-compare-count goldsmith-wc-count"></span>'.goldsmith_svg_lists( 'compare', 'goldsmith-svg-icon' ).'</div>';
                    }
                    break;

                    case 'account':

                    $action_type  = goldsmith_settings( 'header_myaccount_action_type', 'panel' );
                    $account_url  = class_exists('WooCommerce') ? wc_get_page_permalink( 'myaccount' ) : '';
                    $account_url  = apply_filters('goldsmith_myaccount_page_url', $account_url );
                    $account_link = '<a aria-label="My Account" class="account-page-link" href="'.esc_url( $account_url ).'">';
                    $account_data = '';

                    if ( class_exists( 'WooCommerce' ) && !is_account_page() ) {

                        if ( 'popup' == $action_type ) {
                            $account_link  = '<a class="goldsmith-open-popup" href="#goldsmith-account-popup">';
                            $account_data  = '';
                        } elseif ( 'page' == $action_type ) {
                            $account_link  = '<a aria-label="My Account" class="account-page-link" href="'.esc_url( $account_url ).'">';
                            $account_data = '';
                        } else {
                            $account_link = '<a aria-label="My Account" class="account-page-link" href="#0">';
                            $account_data = ' data-account-action="account"';
                        }
                    }

                    $html .= '<div class="top-action-btn"'.$account_data.'>'.$account_link.goldsmith_svg_lists( 'user-1', 'goldsmith-svg-icon' ).'</a></div>';
                    break;
                }
            }

            if ( !shortcode_exists( 'goldsmith_wc_ajax_search' ) ) {
                $html .= '<div class="top-action-btn goldsmith-mobile-search-trigger mobile-header-actions">'.goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' ).'</div>';
            }

            $html_out = '<div class="header-top-buttons">'.$html.'</div>';
        }
        echo apply_filters('goldsmith_header_buttons_html', $html_out );
    }
}


if ( ! function_exists( 'goldsmith_popup_myaccount_form_template' ) ) {
	function goldsmith_popup_myaccount_form_template()
	{
	    if ( !class_exists( 'WooCommerce' ) ) {
	        return;
	    }

	    if ( is_account_page() || 'page' == goldsmith_settings( 'header_myaccount_action_type', 'panel' ) ) {
	        return;
	    }

	    if ( is_user_logged_in() ) {
	        $current_user = wp_get_current_user();

	        ?>
	        <?php if ( 'popup' == goldsmith_settings( 'header_myaccount_action_type', 'panel' ) ) { ?>
	            <div class="account-area account-logged-in">
	        <?php } else { ?>
	            <div class="account-area action-content account-logged-in" data-target-name="account">
	        <?php } ?>
	            <span class="panel-top-title"><?php echo esc_html__( 'Hello', 'goldsmith' ); ?><strong class="nt-strong-sfot"> <?php echo esc_html( $current_user->display_name );?></strong></span>
	            <ul class="navigation">
	            <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) { ?>
	                <li class="menu-item <?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
	                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
	                </li>
	            <?php } ?>

	            </ul>
	        </div>
	        <?php

	    } else {

	        $url         = wc_get_page_permalink( 'myaccount' );
	        $actionturl  = goldsmith_settings( 'header_account_url', '' );
	        $redirecturl = '' != $actionturl ? array( 'redirect' => $actionturl ) : '';
	        $redirecturl = class_exists('NextendSocialLogin', false) ? ' has-social-login' : '';
	        ?>
	        <?php if ( 'popup' == goldsmith_settings( 'header_myaccount_action_type', 'panel' ) ) { ?>
	            <div class="account-area account-logged-in">
	        <?php } else { ?>
	            <div class="account-area action-content" data-target-name="account">
	        <?php } ?>

	            <div class="panel-top-title">
	                <span class="form-action-btn signin-title active" data-target-form="login">
	                    <span><?php esc_html_e( 'Sign in', 'goldsmith' ); ?>&nbsp;</span>
	                    <?php echo goldsmith_svg_lists( 'arrow-right' ); ?>
	                </span>
	                <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) { ?>
	                    <span class="form-action-btn register-title" data-target-form="register">
	                        <?php echo goldsmith_svg_lists( 'user-2' ); ?>
	                        <span>&nbsp;<?php esc_html_e( 'Register', 'goldsmith' ); ?></span>
	                    </span>
	                <?php } ?>
	            </div>

	            <div class="account-area-form-wrapper">
	                <div class="login-form-content active">
	                    <?php woocommerce_login_form( $redirecturl ); ?>
	                </div>
	                <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) { ?>
	                    <div class="register-form-content">
	                        <?php goldsmith_print_account_register_form(); ?>
	                    </div>
	                <?php } ?>
	            </div>

	            <?php
	            if ( class_exists('NextendSocialLogin', false ) ) {
	                echo '<div class="account-area-social-form-wrapper">';
	                echo NextendSocialLogin::renderButtonsWithContainer();
	                echo '</div>';
	            }
	            ?>
	        </div>
	        <?php
	    }
	}
}

if ( ! function_exists( 'goldsmith_print_account_register_form' ) ) {
    function goldsmith_print_account_register_form()
    {
        if ( !class_exists( 'WooCommerce' ) ) {
            return;
        }
        if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) {
            ?>
            <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

                <?php do_action( 'woocommerce_register_form_start' ); ?>

                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                    <p class="form-row goldsmith-is-required">
                        <label for="reg_username"><?php esc_html_e( 'Username', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                        <span class="goldsmith-form-message"></span>
                    </p>

                <?php endif; ?>

                <p class="form-row goldsmith-is-required">
                    <label for="reg_email"><?php esc_html_e( 'Email address', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                    <span class="goldsmith-form-message"></span>
                </p>

                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                    <p class="form-row goldsmith-is-required">
                        <label for="reg_password"><?php esc_html_e( 'Password', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
                        <span class="goldsmith-form-message"></span>
                    </p>

                <?php else : ?>

                    <p><?php esc_html_e( 'A password will be sent to your email address.', 'goldsmith' ); ?></p>

                <?php endif; ?>

                <?php do_action( 'woocommerce_register_form' ); ?>

                <p class="form-row">
                    <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                    <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit goldsmith-btn-medium goldsmith-btn goldsmith-bg-black" name="register" value="<?php esc_attr_e( 'Register', 'goldsmith' ); ?>"><?php esc_html_e( 'Register', 'goldsmith' ); ?></button>
                </p>

                <?php do_action( 'woocommerce_register_form_end' ); ?>

            </form>
            <?php
        }
    }
}


if ( ! function_exists( 'goldsmith_get_all_products_categories' ) ) {
    function goldsmith_get_all_products_categories()
    {
        if ( !class_exists( 'WooCommerce' )  ) {
            return;
        }

        $product_categories = get_terms( 'product_cat', array(
            'orderby'    => 'name',
            'order'      => 'asc',
            'hide_empty' => true,
        ));

        if ( !empty( $product_categories ) ) {
            ?>
            <div class="row row-cols-3">
                <?php
                foreach ( $product_categories as $key => $category ) {
                    $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                    $imgurl = wp_get_attachment_image_URL($thumbnail_id,'goldsmith-panel');
                    $imgsrc = $imgurl ? $imgurl : wc_placeholder_img_src();
                    ?>
                    <div class="col">
                        <div class="product-category">
                            <a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
                                <img width="90" height="90" src="<?php echo esc_html( $imgsrc ); ?>" alt="<?php echo esc_html( $category->name ); ?>"/>
                                <span class="cat-count"><?php echo esc_html( $category->count ); ?></span>
                                <span class="category-title"><?php echo esc_html( $category->name ); ?></span>
                            </a>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
    }
}


if ( ! class_exists( 'Goldsmith_Header' ) ) {
    class Goldsmith_Header
    {
        private static $instance = null;
        public static $location  = 'header_menu';
        public static $menu      = '';

        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        function __construct()
        {
            add_action( 'goldsmith_theme_header_layouts', [ $this, 'header_layouts' ] );
            add_action( 'goldsmith_theme_mobile_header', [ $this, 'mobile_header' ] );
            add_action( 'goldsmith_theme_header_mobile_sidebar', [ $this, 'mobile_sidebar_menu' ] );
            add_action( 'goldsmith_theme_header_elementor', [ $this, 'header_elementor' ] );
            add_action( 'goldsmith_header_action', [ $this, 'main_header' ] );
            add_action( 'goldsmith_theme_header_before', [ $this, 'header_before' ] );
            add_action( 'goldsmith_theme_header_after', [ $this, 'header_after' ] );
            add_action( 'goldsmith_mobile_menu_bottom', [ $this, 'sidebar_menu_copyright' ] );
            add_action( 'goldsmith_after_mobile_menu', [ $this, 'sidebar_menu_lang' ] );
            add_action( 'goldsmith_before_wp_footer', [ $this, 'my_account_form_popup_template' ] );
        }

        public static function check_layout_manager( $layouts, $item )
        {
            if ( is_array( $layouts ) ) {
                unset( $layouts['show']['placebo'] );
                return isset( $layouts['show'][$item] ) ? true : false;
            }
            return false;
        }

        public static function get_nav_menu( $location, $menu )
        {
            self::$location = $location;
            self::$menu = $menu;
            return wp_nav_menu(
                array(
                    'menu' => self::$menu,
                    'theme_location' => self::$location,
                    'container' => '',
                    'container_class' => '',
                    'container_id' => '',
                    'menu_class' => '',
                    'menu_id' => '',
                    'items_wrap' => '%3$s',
                    'before' => '',
                    'after' => '',
                    'link_before' => '',
                    'link_after' => '',
                    'depth' => 4,
                    'echo' => true,
                    'fallback_cb' => 'Goldsmith_Wp_Bootstrap_Navwalker::fallback',
                    'walker' => new \Goldsmith_Wp_Bootstrap_Navwalker()
                )
            );
        }

        public static function get_sidebar_nav_menu()
        {
            $sidemenu_location = has_nav_menu( 'sidebar_menu' ) ? 'sidebar_menu' : 'header_menu';
            return wp_nav_menu(
                array(
                    'menu' => '',
                    'theme_location' => $sidemenu_location,
                    'container' => '',
                    'container_class' => '',
                    'container_id' => '',
                    'menu_class' => '',
                    'menu_id' => '',
                    'items_wrap' => '%3$s',
                    'before' => '',
                    'after' => '',
                    'link_before' => '',
                    'link_after' => '',
                    'depth' => 4,
                    'echo' => true,
                    'fallback_cb' => 'Goldsmith_Sliding_Navwalker::fallback',
                    'walker' => new \Goldsmith_Sliding_Navwalker()
                )
            );
        }

        public static function header_menu()
        {
            ?>
            <div class="goldsmith-header-top-menu-area">
                <ul class="navigation primary-menu">
                    <?php echo self::get_nav_menu( self::$location, self::$menu ); ?>
                </ul>
            </div>
            <?php
        }

        public static function header_mini_menu()
        {
            $html = '';
            if ( has_nav_menu( 'header_mini_menu' ) ) {
                $mini_menu = wp_nav_menu(
                    array(
                        'menu' => '',
                        'theme_location' => 'header_mini_menu',
                        'container' => '',
                        'container_class' => '',
                        'container_id' => '',
                        'menu_class' => '',
                        'menu_id' => '',
                        'items_wrap' => '%3$s',
                        'before' => '',
                        'after' => '',
                        'link_before' => '',
                        'link_after' => '',
                        'depth' => 1,
                        'echo' => false,
                        'fallback_cb' => 'Goldsmith_Wp_Bootstrap_Navwalker::fallback',
                        'walker' => new \Goldsmith_Wp_Bootstrap_Navwalker()
                    )
                );
                $html .= '<div class="goldsmith-header-top-mini-menu-area">';
                    $html .= '<ul class="navigation-mini secondary-menu">'.$mini_menu.'</ul>';
                $html .= '</div>';
            }
            echo apply_filters( 'goldsmith_secondary_mini_menu', $html );
        }

        public static function header_double_menu()
        {
            ?>
            <div class="goldsmith-header-top-double-menu">
                <?php
                if ( has_nav_menu( 'header_menu' ) ) {
                    self::header_menu();
                }
                if ( has_nav_menu( 'header_mini_menu' ) ) {
                    self::header_mini_menu();
                }
                ?>
            </div>
            <?php
        }

        public static function menu_center_logo()
        {
            $menu      = apply_filters('goldsmith_menu_left', '' );
            $menu2     = apply_filters('goldsmith_menu_right', '' );
            $location  = apply_filters('goldsmith_menu_left', 'left_menu' );
            $location2 = apply_filters('goldsmith_menu_right', 'rigt_menu' );
            ?>
            <div class="goldsmith-header-top-menu-area nav-logo-center goldsmith-flex goldsmith-align-center goldsmith-justify-center">
                <ul class="navigation primary-menu left-menu goldsmith-flex-right">
                    <?php echo self::get_nav_menu( $location, $menu )?>
                </ul>
                <div class="center-logo-wrapper flex-center-items">
                    <?php goldsmith_logo(false); ?>
                </div>
                <ul class="navigation primary-menu right-menu goldsmith-flex-left">
                    <?php echo self::get_nav_menu( $location2, $menu2 )?>
                </ul>
            </div>
            <?php
        }

        public static function header_buttons_layouts()
        {
            $layouts = goldsmith_settings( 'header_buttons_layouts' );

            if ( class_exists( 'WooCommerce') ) {
                if ( is_product() && '1' == goldsmith_settings( 'single_shop_different_header_layouts', '0' ) ) {
                    $layouts  = goldsmith_settings( 'single_shop_header_buttons_layouts' );
                } elseif ( is_shop() && '1' == goldsmith_settings( 'shop_different_header_layouts', '0' ) ) {
                    $layouts  = goldsmith_settings( 'shop_header_buttons_layouts' );
                }
            }

            $layouts = apply_filters( 'header_buttons_layouts', $layouts );
            $catalog_mode = goldsmith_settings( 'woo_catalog_mode', '0' );
            $html = $html_out = '';
            if ( $layouts ) {
                unset( $layouts['show']['placebo'] );
                foreach ( $layouts['show'] as $key => $value ) {

                    switch ( $key ) {
                        case 'search':
                        $html .= '<div class="top-action-btn" data-name="search-popup">'.goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' ).'</div>';
                        break;

                        case 'cart':
                        if ( class_exists( 'WooCommerce') && '1' != $catalog_mode ) {
                            $count = WC()->cart->get_cart_contents_count();
                            if ( '1' == goldsmith_settings( 'disable_minicart', '0' ) ) {
                                $html .= '<div class="top-action-btn"><a class="cart-page-link" href="'.esc_url( wc_get_page_permalink( 'cart' ) ).'"><span class="goldsmith-cart-count goldsmith-wc-count">'.$count.'</span>'.goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ).'</a></div>';
                            } else {
                                $html .= '<div class="top-action-btn" data-name="cart"><span class="goldsmith-cart-count goldsmith-wc-count">'.$count.'</span>'.goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ).'</div>';
                            }
                        }
                        break;

                        case 'wishlist':
                        if ( class_exists( 'Goldsmith_Wishlist' ) && '1' != $catalog_mode ) {
                            $html .= '<div class="top-action-btn" data-name="wishlist"><span class="goldsmith-wishlist-count goldsmith-wc-count"></span>'.goldsmith_svg_lists( 'love', 'goldsmith-svg-icon' ).'</div>';
                        }
                        break;

                        case 'compare':
                        if ( class_exists( 'WPCleverWoosc' ) && '1' != $catalog_mode ) {
                            $html .= '<div class="top-action-btn has-custom-action open-compare-btn"><span class="goldsmith-compare-count goldsmith-wc-count"></span>'.goldsmith_svg_lists( 'compare', 'goldsmith-svg-icon' ).'</div>';
                        }
                        break;

                        case 'account':

                        $action_type  = goldsmith_settings( 'header_myaccount_action_type', 'panel' );
                        $account_url  = class_exists('WooCommerce') ? wc_get_page_permalink( 'myaccount' ) : '';
                        $account_url  = apply_filters('goldsmith_myaccount_page_url', $account_url );
                        $account_link = '<a aria-label="My Account" class="account-page-link" href="'.esc_url( $account_url ).'">';
                        $account_data = '';

                        if ( class_exists( 'WooCommerce' ) && !is_account_page() ) {

                            if ( 'popup' == $action_type ) {
                                $account_link  = '<a class="goldsmith-open-popup" href="#goldsmith-account-popup">';
                                $account_data  = '';
                            } elseif ( 'page' == $action_type ) {
                                $account_link  = '<a aria-label="My Account" class="account-page-link" href="'.esc_url( $account_url ).'">';
                                $account_data = '';
                            } else {
                                $account_link = '<a aria-label="My Account" class="account-page-link" href="#0">';
                                $account_data = ' data-account-action="account"';
                            }
                        }

                        $html .= '<div class="top-action-btn"'.$account_data.'>'.$account_link.goldsmith_svg_lists( 'user-1', 'goldsmith-svg-icon' ).'</a></div>';
                        break;
                    }
                }
                $html_out = '<div class="header-top-buttons">'.$html.'</div>';
            }
            echo apply_filters('goldsmith_header_buttons_html', $html_out );
        }

        public static function mobile_buttons_layouts()
        {
            $layouts = goldsmith_settings( 'mobile_header_buttons_layouts' );
            $layouts = apply_filters( 'goldsmith_mobile_header_buttons_layouts', $layouts );

            if ( $layouts ) {
                unset( $layouts['show']['placebo'] );

                foreach ( $layouts['show'] as $key => $value ) {

                    switch ( $key ) {
                        case 'search':
                        echo '<div class="top-action-btn" data-name="search-popup">'.goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' ).'</div>';
                        break;

                        case 'cart':
                        if ( class_exists( 'WooCommerce') && '1' != goldsmith_settings( 'woo_catalog_mode', '0' ) ) {
                            $count = WC()->cart->get_cart_contents_count();
                            if ( '1' == goldsmith_settings( 'disable_minicart', '0' ) ) {
                                echo '<div class="top-action-btn"><a class="cart-page-link" href="'.esc_url( wc_get_page_permalink( 'cart' ) ).'"><span class="goldsmith-cart-count goldsmith-wc-count">'.$count.'</span>'.goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ).'</a></div>';
                            } else {
                                echo '<div class="top-action-btn" data-name="cart"><span class="goldsmith-cart-count goldsmith-wc-count">'.$count.'</span>'.goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ).'</div>';
                            }
                        }
                        break;

                        case 'account':

                        $action_type  = goldsmith_settings( 'header_myaccount_action_type', 'panel' );
                        $account_url  = class_exists('WooCommerce') ? wc_get_page_permalink( 'myaccount' ) : '';
                        $account_url  = apply_filters('goldsmith_myaccount_page_url', $account_url );
                        $account_link = '<a aria-label="My Account" class="account-page-link" href="'.esc_url( $account_url ).'">';
                        $account_data = '';

                        if ( class_exists( 'WooCommerce' ) && !is_account_page() ) {

                            if ( 'popup' == $action_type ) {
                                $account_link  = '<a class="goldsmith-open-popup" href="#goldsmith-account-popup">';
                            } elseif ( 'page' == $action_type ) {
                                $account_link  = '<a aria-label="My Account" class="account-page-link" href="'.esc_url( $account_url ).'">';
                            } else {
                                $account_link = '<a aria-label="My Account" class="account-page-link" href="#0">';
                                $account_data = ' data-account-action="account"';
                            }
                        }

                        echo '<div class="top-action-btn"'.$account_data.'>'.$account_link.goldsmith_svg_lists( 'user-1', 'goldsmith-svg-icon' ).'</a></div>';
                        break;
                    }
                }
            }
        }

        public static function sidebar_buttons_layouts()
        {
            $layouts   = goldsmith_settings( 'sidebar_menu_buttons_layouts' );
            $layouts   = apply_filters( 'goldsmith_sidebar_buttons_layouts', $layouts );
            $cat_count = self::get_products_categories_count();
            $catalog_mode = goldsmith_settings( 'woo_catalog_mode', '0' );

            if ( $layouts ) {
                unset( $layouts['show']['placebo'] );

                foreach ( $layouts['show'] as $key => $value ) {

                    switch ( $key ) {
                        case 'search':
                        echo '<div class="top-action-btn" data-name="search-cats"><span class="goldsmith-category-count goldsmith-wc-count">'.$cat_count.'</span>'.goldsmith_svg_lists( 'paper-search', 'goldsmith-svg-icon' ).'</div>';
                        break;

                        case 'contact':
                        echo '<div class="top-action-btn" data-name="contact">'.goldsmith_svg_lists( 'contact-form', 'goldsmith-svg-icon' ).'</div>';
                        break;

                        case 'cart':
                        if ( class_exists( 'WooCommerce') && '1' != $catalog_mode ) {
                            $count = WC()->cart->get_cart_contents_count();
                            if ( '1' == goldsmith_settings( 'disable_minicart', '0' ) ) {
                                echo '<div class="top-action-btn"><a class="cart-page-link" href="'.esc_url( wc_get_page_permalink( 'cart' ) ).'"><span class="goldsmith-cart-count goldsmith-wc-count">'.$count.'</span>'.goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ).'</a></div>';
                            } else {
                                echo '<div class="top-action-btn" data-name="cart"><span class="goldsmith-cart-count goldsmith-wc-count">'.$count.'</span>'.goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ).'</div>';
                            }
                        }
                        break;

                        case 'wishlist':
                        if ( class_exists( 'Goldsmith_Wishlist' ) && '1' != $catalog_mode ) {
                            echo '<div class="top-action-btn" data-name="wishlist"><span class="goldsmith-wishlist-count goldsmith-wc-count"></span>'.goldsmith_svg_lists( 'love', 'goldsmith-svg-icon' ).'</div>';
                        }
                        break;

                        case 'compare':
                        if ( class_exists( 'WPCleverWoosc' ) && '1' != $catalog_mode ) {
                            echo '<div class="top-action-btn has-custom-action open-compare-btn"><span class="goldsmith-compare-count goldsmith-wc-count"></span>'.goldsmith_svg_lists( 'compare', 'goldsmith-svg-icon' ).'</div>';
                        }
                        break;

                        case 'account':
                        $action_type  = goldsmith_settings( 'header_myaccount_action_type', 'panel' );
                        $account_url  = class_exists('WooCommerce') ? wc_get_page_permalink( 'myaccount' ) : '';
                        $account_url  = apply_filters('goldsmith_myaccount_page_url', $account_url );
                        $link_open    = '<a aria-label="My Account" class="account-page-link" href="'.esc_url( $account_url ).'">';
                        $link_close   = '</a>';
                        $account_data = '';

                        if ( class_exists( 'WooCommerce' ) && !is_account_page() ) {
                            if ( 'popup' == $action_type ) {
                                $link_open  = '<a class="goldsmith-open-popup" href="#goldsmith-account-popup">';
                            } elseif ( 'page' == $action_type ) {
                                $link_open  = '<a aria-label="My Account" class="account-page-link" href="'.esc_url( $account_url ).'">';
                            } else {
                                $link_open = '';
                                $link_close = '';
                                $account_data = ' data-name="account"';
                            }
                        }

                        echo '<div class="top-action-btn"'.$account_data.'>'.$link_open.goldsmith_svg_lists( 'user-1', 'goldsmith-svg-icon' ).$link_close .'</div>';
                        break;

                        case 'socials':
                        echo '<div class="top-action-btn share" data-name="share">'.goldsmith_svg_lists( 'share', 'goldsmith-svg-icon' ).'</div>';
                        break;
                    }
                }
            }
        }

        public static function header_layouts()
        {
            $layouts = goldsmith_settings( 'header_layouts' );

            if ( class_exists( 'WooCommerce') ) {
                if ( is_product() && '1' == goldsmith_settings( 'single_shop_different_header_layouts', '0' ) ) {
                    $layouts  = goldsmith_settings( 'single_shop_header_layouts' );
                } elseif ( is_shop() && '1' == goldsmith_settings( 'shop_different_header_layouts', '0' ) ) {
                    $layouts  = goldsmith_settings( 'shop_header_layouts' );
                }
            }

            $custom_html = goldsmith_settings( 'header_custom_html', '' );
            $layouts     = apply_filters( 'goldsmith_header_layouts', $layouts );
            $bg_type     = apply_filters( 'goldsmith_header_bg_type', goldsmith_settings( 'header_bg_type', 'default' ) );
            $header_w    = apply_filters( 'goldsmith_header_width', goldsmith_settings( 'header_width', 'default' ) );

            echo '<header class="goldsmith-header-default header-width-'.$header_w.'">';
                echo '<div class="container">';
                    echo '<div class="goldsmith-header-content">';
                        if ( $layouts ) {

                            unset( $layouts['left']['placebo'] );
                            unset( $layouts['center']['placebo'] );
                            unset( $layouts['right']['placebo'] );

                            if ( !empty( $layouts['left'] ) ) {

                                echo '<div class="goldsmith-header-top-left header-top-side">';
                                    echo '<div class="goldsmith-header-default-inner">';
                                        foreach ( $layouts['left'] as $key => $value ) {

                                            switch( $key ) {
                                                case 'logo': goldsmith_logo();
                                                break;

                                                case 'search': echo '<div class="top-action-btn" data-name="search-popup">'.goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' ).'</div>';
                                                break;

                                                case 'sidemenu': echo '<div class="mobile-toggle">'.goldsmith_svg_lists( 'bars', 'goldsmith-svg-icon' ).'</div>';
                                                break;

                                                case 'menu': self::header_menu();
                                                break;

                                                case 'mini-menu': self::header_mini_menu();
                                                break;

                                                case 'double-menu': self::header_double_menu();
                                                break;

                                                case 'custom-html': echo goldsmith_settings( 'header_custom_html', '' );
                                                break;

                                                case 'buttons': self::header_buttons_layouts();
                                                break;
                                            }
                                        }
                                        do_action( 'goldsmith_theme_header_left' );
                                    echo '</div>';
                                echo '</div>';
                            }
                            if ( !empty( $layouts['center'] ) ) {
                                echo '<div class="goldsmith-header-top-center">';
                                    echo '<div class="goldsmith-header-default-inner">';
                                        foreach ( $layouts['center'] as $key => $value ) {

                                            switch( $key ) {
                                                case 'logo': goldsmith_logo();
                                                break;

                                                case 'search': echo '<div class="top-action-btn" data-name="search-popup">'.goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' ).'</div>';
                                                break;

                                                case 'sidemenu': echo '<div class="mobile-toggle">'.goldsmith_svg_lists( 'bars', 'goldsmith-svg-icon' ).'</div>';
                                                break;

                                                case 'menu': self::header_menu();
                                                break;

                                                case 'mini-menu': self::header_mini_menu();
                                                break;

                                                case 'double-menu': self::header_double_menu();
                                                break;

                                                case 'custom-html': echo goldsmith_settings( 'header_custom_html', '' );
                                                break;

                                                case 'center-logo': self::menu_center_logo();
                                                break;

                                                case 'buttons': self::header_buttons_layouts();
                                                break;
                                            }
                                        }
                                        do_action( 'goldsmith_theme_header_center' );
                                    echo '</div>';
                                echo '</div>';
                            }
                            if ( !empty( $layouts['right'] ) ) {
                                echo '<div class="goldsmith-header-top-right header-top-side">';
                                    echo '<div class="goldsmith-header-default-inner">';
                                        foreach ( $layouts['right'] as $key => $value ) {

                                            switch( $key ) {
                                                case 'logo': goldsmith_logo();
                                                break;

                                                case 'search': echo '<div class="top-action-btn" data-name="search-popup">'.goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' ).'</div>';
                                                break;

                                                case 'sidemenu': echo '<div class="mobile-toggle">'.goldsmith_svg_lists( 'bars', 'goldsmith-svg-icon' ).'</div>';
                                                break;

                                                case 'menu': self::header_menu();
                                                break;

                                                case 'mini-menu': self::header_mini_menu();
                                                break;

                                                case 'double-menu': self::header_double_menu();
                                                break;

                                                case 'custom-html': echo goldsmith_settings( 'header_custom_html', '' );
                                                break;

                                                case 'buttons': self::header_buttons_layouts();
                                                break;
                                            }
                                        }
                                        do_action( 'goldsmith_theme_header_right' );
                                    echo '</div>';
                                echo '</div>';
                            }
                        }
                    echo '</div>';

                    self::header_bottom_bar();

                echo '</div>';
            echo '</header>';
        }

        public static function header_bottom_bar()
        {
            if ( '1' == goldsmith_settings( 'header_bottom_area_visibility', '0' ) ) {
                $template_type  = goldsmith_settings( 'header_bottom_area_template_type', 'filters' );
                $header_filter  = goldsmith_settings( 'header_bottom_area_display_type', 'show-on-scroll' );
                $header_filter .= ' shop-layout-'.goldsmith_settings( 'shop_layout', 'left-sidebar' );
                $header_filter .= ' fixed-sidebar-'.goldsmith_settings( 'shop_hidden_sidebar_position', 'left' );

                if ( class_exists('WooCommerce') && ( is_shop() || is_product_category() || is_product_tag() ) && 'filters' == $template_type ) {
                    echo '<div class="goldsmith-header-bottom-bar '.$header_filter.'">';
                        shop_loop_filters_layouts();
                    echo '</div>';
                } else {
                    echo '<div class="goldsmith-header-bottom-bar goldsmith-elementor-template '.goldsmith_settings( 'header_bottom_area_display_type', 'show-on-scroll' ).'">';
                        echo goldsmith_print_elementor_templates( 'header_bottom_bar_template' );
                    echo '</div>';
                }
            }
        }

        public static function mobile_header()
        {
            $layouts = goldsmith_settings( 'mobile_header_layouts' );
            $layouts = apply_filters( 'goldsmith_mobile_header_layouts', $layouts );
            $bg_type = goldsmith_settings( 'mobile_header_bg_type', 'default' );
            if ( is_category() ) {
                $bg_type = goldsmith_settings( 'archive_cat_mobile_header_bg_type' );
            }
            if ( is_tag() ) {
                $bg_type = goldsmith_settings( 'archive_tag_mobile_header_bg_type' );
            }
            if ( is_single() ) {
                $bg_type = goldsmith_settings( 'single_post_header_bg_type' );
            }

            if ( $layouts ) {
                if ( !empty( $layouts ) && isset( $layouts['show'] ) ) {
                    unset( $layouts['show']['placebo'] );
                }
                echo '<div class="goldsmith-header-mobile-top-height">';
                    echo '<div class="goldsmith-header-mobile-top mobile-header-bg-type-'.$bg_type.'">';
                        foreach ( $layouts['show'] as $key => $value ) {

                            switch ( $key ) {
                                case 'toggle':
                                echo '<div class="mobile-toggle">'.goldsmith_svg_lists( 'bars', 'goldsmith-svg-icon' ).'</div>';
                                break;

                                case 'logo':
                                echo '<div class="goldsmith-header-mobile-logo">';
                                    goldsmith_logo(true);
                                echo '</div>';
                                break;

                                case 'buttons':
                                echo '<div class="goldsmith-header-mobile-top-actions">';
                                    self::mobile_buttons_layouts();
                                echo '</div>';
                                break;
                            }
                        }
                        self::header_bottom_bar();
                    echo '</div>';
                echo '</div>';
            }
        }

        public static function mobile_sidebar_menu()
        {
            $layouts = goldsmith_settings( 'sidebar_menu_layouts' );
            $layouts = apply_filters( 'goldsmith_mobile_header_sidebar_layouts', $layouts );
            if ( !empty( $layouts ) && isset( $layouts['show'] ) ) {
                unset( $layouts['show']['placebo'] );
            }

            $sidebar_layouts = goldsmith_settings( 'sidebar_menu_buttons_layouts' );
            $bg_type         = apply_filters( 'goldsmith_sidebar_menu_bg_type', goldsmith_settings( 'sidebar_menu_bg_type', 'default' ) );
            $class           = !empty($layouts['show']) ? 'has-bar' : 'no-bar';
            $class          .= ' sidebar-header-bg-type-'.$bg_type;
            $class          .= isset($layouts['show']['buttons']) ? ' has-buttons' : ' no-buttons';
            $class          .= isset($layouts['show']['socials']) ? ' has-socials' : ' no-socials';
            $class          .= isset($layouts['show']['logo']) ? ' has-logo' : ' no-logo';
            $cf7_form        = goldsmith_settings('sidebar_menu_cf7') ? '[contact-form-7 id="'.goldsmith_settings('sidebar_menu_cf7').'"]' : '';
            $form            = goldsmith_settings('sidebar_menu_custom_form') ? goldsmith_settings('sidebar_menu_custom_form') : $cf7_form;

            wp_enqueue_script( 'sliding-menu');
            ?>
            <nav class="goldsmith-header-mobile <?php echo esc_attr( $class ); ?>">
                <div class="goldsmith-panel-close no-bar"></div>
                <?php
                if ( !empty( $layouts['show'] ) ) {
                    echo '<div class="goldsmith-header-mobile-sidebar">';
                        echo '<div class="goldsmith-panel-close goldsmith-panel-close-button"></div>';
                        echo '<div class="goldsmith-header-mobile-sidebar-inner">';
                            foreach ( $layouts['show'] as $key => $value ) {

                                switch ( $key ) {
                                    case 'socials':
                                    echo '<div class="goldsmith-header-mobile-sidebar-bottom" data-target-name="share">';
                                        echo '<div class="sidebar-bottom-socials">';
                                            echo goldsmith_settings('sidebar_menu_socials');
                                        echo '</div>';
                                    echo '</div>';
                                    break;

                                    case 'logo':
                                    echo '<div class="goldsmith-header-mobile-sidebar-logo">';
                                        goldsmith_logo(true);
                                    echo '</div>';
                                    break;

                                    case 'buttons':
                                    echo '<div class="sidebar-top-action">';
                                        self::sidebar_buttons_layouts();
                                    echo '</div>';
                                    break;
                                }
                            }
                        echo '</div>';
                    echo '</div>';
                }
                ?>

                <div class="goldsmith-header-mobile-content">

                    <div class="goldsmith-header-slide-menu menu-area">
                        <?php if ( class_exists('WooCommerce') && shortcode_exists( 'goldsmith_wc_ajax_search' ) ) { ?>
                        <div class="search-area-top active">
                            <?php echo goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' ); ?>
                            <?php echo do_shortcode('[goldsmith_wc_ajax_search]'); ?>
                        </div>
                        <?php } ?>
                        <div class="goldsmith-header-mobile-slide-menu">
                            <ul class="navigationn primary-menuu">
                                <?php echo self::get_sidebar_nav_menu(); ?>
                            </ul>
                        </div>

                        <?php do_action( 'goldsmith_after_mobile_menu' ); ?>

                        <?php do_action( 'goldsmith_mobile_menu_bottom' ); ?>
                    </div>

                    <?php if ( self::check_layout_manager( $sidebar_layouts, 'search' ) ) { ?>
                        <div class="category-area action-content" data-target-name="search-cats">
                            <?php if ( '' != goldsmith_settings('sidebar_panel_categories_custom_title') ) { ?>
                                <span class="panel-top-title"><?php echo esc_html( goldsmith_settings('sidebar_panel_categories_custom_title') ); ?></span>
                            <?php } else { ?>
                                <span class="panel-top-title"><?php esc_html_e( 'All Products Categories', 'goldsmith' ); ?></span>
                            <?php } ?>
                            <div class="category-area-inner goldsmith-scrollbar">
                                <?php self::get_all_products_categories(); ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ( self::check_layout_manager( $sidebar_layouts, 'cart' ) && '1' != goldsmith_settings( 'disable_minicart', '0' ) ) { ?>
                        <div class="cart-area action-content" data-target-name="cart">
                            <?php if ( '' != goldsmith_settings('sidebar_panel_cart_custom_title') ) { ?>
                                <span class="panel-top-title"><?php echo esc_html( goldsmith_settings('sidebar_panel_cart_custom_title') ); ?></span>
                            <?php } else { ?>
                                <span class="panel-top-title"><?php esc_html_e( 'Your Cart', 'goldsmith' ); ?></span>
                            <?php } ?>
                            <?php do_action( 'goldsmith_side_panel_after_header' ); ?>
                            <div class="cart-content">
                                <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ( self::check_layout_manager( $sidebar_layouts, 'contact' ) && $form ) { ?>
                        <div class="contact-area action-content" data-target-name="contact">
                            <?php if ( '' != goldsmith_settings('sidebar_panel_contact_custom_title') ) { ?>
                                <span class="panel-top-title"><?php echo esc_html( goldsmith_settings('sidebar_panel_contact_custom_title') ); ?></span>
                            <?php } else { ?>
                                <span class="panel-top-title"><?php esc_html_e( 'Contact Us', 'goldsmith' ); ?></span>
                            <?php } ?>
                            <?php echo do_shortcode( $form ); ?>
                        </div>
                    <?php } ?>

                    <?php
                    if ( self::check_layout_manager( $sidebar_layouts, 'wishlist' ) || self::check_layout_manager( $sidebar_layouts, 'compare' ) ) {
                        /**
                        *
                        * Hook: goldsmith_mobile_panel_content_after_cart.
                        *
                        * @hooked Goldsmith_Compare::side_mobile_panel_content()
                        * @hooked Goldsmith_Wishlist::template_mobile_header_content()
                        */
                        do_action( 'goldsmith_mobile_panel_content_after_cart' );
                    }
                    ?>

                    <?php
                    if ( 'panel' == goldsmith_settings( 'header_myaccount_action_type', 'panel' ) ) {
                        if ( self::check_layout_manager( $sidebar_layouts, 'account' ) ) {
                            self::my_account_form_template();
                        }
                    }
                    ?>
                </div>
            </nav>
            <?php
        }

        public static function sidebar_menu_lang()
        {
            if ( '1' == goldsmith_settings( 'sidebar_menu_lang_visibility', '0' ) ) {

                if ( has_action( 'wpml_add_language_selector' ) ) {

                    echo '<div class="goldsmith-sidemenu-lang-switcher">';
                        do_action('wpml_add_language_selector');
                    echo '</div>';

                } elseif ( function_exists( 'pll_the_languages' ) ) {

                    echo '<div class="goldsmith-sidemenu-lang-switcher">';
                        pll_the_languages(
                            array(
                                'show_flags'=>1,
                                'show_names'=>1,
                                'dropdown'=>1,
                                'raw'=>0,
                                'hide_current'=>0,
                                'display_names_as'=>'name'
                            )
                        );
                    echo '</div>';

                } else {

                    if ( has_nav_menu( 'header_lang_menu' ) ) {
                        wp_enqueue_script( 'sliding-menu');
                        echo '';
                        $lang_menu = wp_nav_menu(
                            array(
                                'menu' => '',
                                'theme_location' => 'header_lang_menu',
                                'container' => '',
                                'container_class' => '',
                                'container_id' => '',
                                'menu_class' => '',
                                'menu_id' => '',
                                'items_wrap' => '%3$s',
                                'before' => '',
                                'after' => '',
                                'link_before' => '',
                                'link_after' => '',
                                'depth' => 2,
                                'echo' => false,
                                'fallback_cb' => 'Goldsmith_Wp_Bootstrap_Navwalker::fallback',
                                'walker' => new \Goldsmith_Wp_Bootstrap_Navwalker()
                            )
                        );
                        echo '<div class="goldsmith-sidemenu-lang-switcher">
                            <div class="goldsmith-header-lang-slide-menu">
                                <ul class="goldsmith-lang-menu">'.$lang_menu.'</ul>
                            </div>
                        </div>';
                    }
                }
            }
        }

        public static function sidebar_menu_copyright()
        {
            if ( goldsmith_settings( 'sidebar_menu_copyright', '' ) ) {
                echo '<div class="goldsmith-sidemenu-copyright">'.goldsmith_settings( 'sidebar_menu_copyright', '' ).'</div>';
            }
        }

        public static function header_elementor()
        {
            $header_id = false;

            if ( class_exists( '\Elementor\Core\Settings\Manager' ) ) {

                $page_settings = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' )->get_model( get_the_ID() );
                $pageheader_id = $page_settings->get_settings( 'goldsmith_page_header_template' );
                $header_id     = isset( $pageheader_id ) !== '' ? $pageheader_id : $header_id;
            }

            if ( $header_id ) {

                $frontend = new \Elementor\Frontend;
                printf( '<div class="goldsmith-elementor-header header-'.$header_id.'">%1$s</div>', $frontend->get_builder_content_for_display( $header_id, false ) );

            } else {

                echo goldsmith_print_elementor_templates( 'header_elementor_templates', 'goldsmith-elementor-header header-'.$pageheader_id );

            }
        }

        public static function main_header()
        {

            if ( '0' == goldsmith_settings( 'header_visibility', '1' ) ) {
                return;
            }

            if ( ! class_exists( 'Redux' ) || false == goldsmith_settings( 'header_layouts' ) ) {
                self::header_default();
                return;
            }

            $header_template = apply_filters( 'goldsmith_header_template', goldsmith_settings( 'header_template', 'default' ) );

            if ( 'elementor' == $header_template ) {

                /**
                * HEADER ELEMENTOR TEMPLATES.
                * Hook: goldsmith_theme_header_elementor.
                *
                * @hooked header_elementor
                */
                do_action( 'goldsmith_theme_header_elementor' );

            } elseif ( 'sidebar' == $header_template ) {

                goldsmith_sidebar_header();

            } else {

                do_action( 'goldsmith_theme_header_before' );

                /**
                * HEADER TOP
                * Hook: goldsmith_theme_header_layouts.
                *
                * @hooked header_layouts
                */
                do_action( 'goldsmith_theme_header_layouts' );

                do_action( 'goldsmith_theme_header_after' );

                /**
                * HEADER MOBILE
                * Hook: goldsmith_theme_mobile_header.
                *
                * @hooked mobile_header
                */
                do_action( 'goldsmith_theme_mobile_header' );

                /**
                * HEADER SIDEBAR MENU
                * Hook: goldsmith_theme_header_mobile_sidebar.
                *
                * @hooked mobile_sidebar_menu
                */
                do_action( 'goldsmith_theme_header_mobile_sidebar' );

            }
        }

        public static function get_all_products_categories()
        {
            if ( !class_exists( 'WooCommerce' )  ) {
                return;
            }

            $product_categories = get_terms( 'product_cat', array(
                'orderby'    => 'name',
                'order'      => 'asc',
                'hide_empty' => true,
            ));

            if ( !empty( $product_categories ) ) {
                ?>
                <div class="row row-cols-3">
                    <?php
                    foreach ( $product_categories as $key => $category ) {
                        $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                        $imgurl = wp_get_attachment_image_URL($thumbnail_id,'goldsmith-panel');
                        $imgsrc = $imgurl ? $imgurl : wc_placeholder_img_src();
                        ?>
                        <div class="col">
                            <div class="product-category">
                                <a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
                                    <img width="90" height="90" src="<?php echo esc_html( $imgsrc ); ?>" alt="<?php echo esc_html( $category->name ); ?>"/>
                                    <span class="cat-count"><?php echo esc_html( $category->count ); ?></span>
                                    <span class="category-title"><?php echo esc_html( $category->name ); ?></span>
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
        }

        public static function get_products_categories_count()
        {
            if ( !class_exists( 'WooCommerce' )  ) {
                return;
            }

            $product_categories = get_terms( 'product_cat', array(
                'orderby'    => 'name',
                'order'      => 'asc',
                'hide_empty' => true,
            ));

            if ( !empty( $product_categories ) ) {
                return count( $product_categories );
            }
        }

        public static function print_account_register_form()
        {
            if ( !class_exists( 'WooCommerce' ) ) {
                return;
            }
            if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) {
                ?>
                <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

                    <?php do_action( 'woocommerce_register_form_start' ); ?>

                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                        <p class="form-row goldsmith-is-required">
                            <label for="reg_username"><?php esc_html_e( 'Username', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                            <span class="goldsmith-form-message"></span>
                        </p>

                    <?php endif; ?>

                    <p class="form-row goldsmith-is-required">
                        <label for="reg_email"><?php esc_html_e( 'Email address', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                        <span class="goldsmith-form-message"></span>
                    </p>

                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                        <p class="form-row goldsmith-is-required">
                            <label for="reg_password"><?php esc_html_e( 'Password', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
                            <span class="goldsmith-form-message"></span>
                        </p>

                    <?php else : ?>

                        <p><?php esc_html_e( 'A password will be sent to your email address.', 'goldsmith' ); ?></p>

                    <?php endif; ?>

                    <?php do_action( 'woocommerce_register_form' ); ?>

                    <p class="form-row">
                        <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                        <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit goldsmith-btn-medium goldsmith-btn goldsmith-bg-black" name="register" value="<?php esc_attr_e( 'Register', 'goldsmith' ); ?>"><?php esc_html_e( 'Register', 'goldsmith' ); ?></button>
                    </p>

                    <?php do_action( 'woocommerce_register_form_end' ); ?>

                </form>
                <?php
            }
        }

        public static function my_account_form_template()
        {
            if ( !class_exists( 'WooCommerce' ) || is_account_page() ) {
                return;
            }

            if ( is_user_logged_in() ) {
                $current_user = wp_get_current_user();

                ?>
                <?php if ( 'popup' == goldsmith_settings( 'header_myaccount_action_type', 'panel' ) ) { ?>
                    <div class="account-area account-logged-in">
                <?php } else { ?>
                    <div class="account-area action-content account-logged-in" data-target-name="account">
                <?php } ?>
                    <span class="panel-top-title"><?php echo esc_html__( 'Hello', 'goldsmith' ); ?><strong class="nt-strong-sfot"> <?php echo esc_html( $current_user->display_name );?></strong></span>
                    <ul class="navigation">
                    <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) { ?>
                        <li class="menu-item <?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
                            <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                        </li>
                    <?php } ?>

                    </ul>
                </div>
                <?php

            } else {

                $url         = wc_get_page_permalink( 'myaccount' );
                $actionturl  = goldsmith_settings( 'header_account_url', '' );
                $redirecturl = '' != $actionturl ? array( 'redirect' => $actionturl ) : '';
                $redirecturl = class_exists('NextendSocialLogin', false) ? ' has-social-login' : '';
                ?>
                <?php if ( 'popup' == goldsmith_settings( 'header_myaccount_action_type', 'panel' ) ) { ?>
                    <div class="account-area account-logged-in">
                <?php } else { ?>
                    <div class="account-area action-content" data-target-name="account">
                <?php } ?>

                    <div class="panel-top-title">
                        <span class="form-action-btn signin-title active" data-target-form="login">
                            <span><?php esc_html_e( 'Sign in', 'goldsmith' ); ?>&nbsp;</span>
                            <?php echo goldsmith_svg_lists( 'arrow-right' ); ?>
                        </span>
                        <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) { ?>
                            <span class="form-action-btn register-title" data-target-form="register">
                                <?php echo goldsmith_svg_lists( 'user-2' ); ?>
                                <span>&nbsp;<?php esc_html_e( 'Register', 'goldsmith' ); ?></span>
                            </span>
                    <?php } ?>
                    </div>

                    <div class="account-area-form-wrapper">
                        <div class="login-form-content active">
                            <?php woocommerce_login_form( $redirecturl ); ?>
                        </div>
                        <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) { ?>
                            <div class="register-form-content">
                                <?php self::print_account_register_form(); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <?php
                    if ( class_exists('NextendSocialLogin', false ) ) {
                        echo '<div class="account-area-social-form-wrapper">';
                        echo NextendSocialLogin::renderButtonsWithContainer();
                        echo '</div>';
                    }
                    ?>
                </div>
                <?php
            }
        }

        public static function my_account_form_popup_template()
        {
            if ( !class_exists( 'WooCommerce' ) || is_account_page() ) {
                return;
            }
            if ( 'popup' == goldsmith_settings( 'header_myaccount_action_type', 'panel' ) ) {
                ?>
                <div class="account-popup-content goldsmith-popup-item zoom-anim-dialog mfp-hide" id="goldsmith-account-popup">
                    <?php self::my_account_form_template(); ?>
                </div>
                <?php
            }
        }

        public static function header_before()
        {
            $sticky = '1' == goldsmith_settings( 'sticky_before_header_template', 'panel' ) ? ' sticky-template' : '';
            echo goldsmith_print_elementor_templates( 'before_header_template', 'header-top-area goldsmith-elementor-before-header'.$sticky );
        }

        public static function header_after()
        {
            echo goldsmith_print_elementor_templates( 'after_header_template', 'header-search-area goldsmith-elementor-after-header' );
        }

        public static function header_default()
        {
            wp_enqueue_script( 'sliding-menu');
            ?>
            <header class="goldsmith-header-default sticky header-basic">
                <div class="container">
                    <div class="goldsmith-header-content">
                        <div class="goldsmith-header-top-left header-top-side">
                            <div class="goldsmith-header-default-inner">
                                <div class="goldsmith-default-logo">
                                    <?php goldsmith_logo(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="goldsmith-header-top-center">
                            <div class="goldsmith-header-default-inner">
                                <div class="goldsmith-header-top-menu-area">
                                    <ul class="navigation primary-menu">
                                        <?php echo self::get_nav_menu( self::$location, self::$menu ); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="goldsmith-header-top-right header-top-side">
                            <div class="goldsmith-header-default-inner">
                                <div class="top-action-btn" data-name="search-popup">
                                    <?php echo goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' ); ?>
                                </div>
                                <?php if ( class_exists('WooCommerce') ) { ?>
                                    <div class="header-top-buttons">
                                        <div class="top-action-btn" data-name="cart">
                                            <span class="goldsmith-cart-count goldsmith-wc-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
                                            <?php echo goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="goldsmith-header-mobile-top">
                <div class="mobile-toggle"><?php echo goldsmith_svg_lists( 'bars', 'goldsmith-svg-icon' ); ?></div>
                <div class="goldsmith-header-mobile-logo">
                    <?php goldsmith_logo(); ?>
                </div>
                <?php if ( class_exists('WooCommerce') ) { ?>
                    <div class="goldsmith-header-mobile-top-actions">
                        <div class="top-action-btn" data-name="cart">
                            <span class="goldsmith-cart-count goldsmith-wc-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
                            <?php echo goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <nav class="goldsmith-header-mobile no-bar">
                <div class="goldsmith-panel-close no-bar"></div>
                <div class="goldsmith-header-mobile-content">
                    <div class="goldsmith-header-slide-menu menu-area">
                        <div class="goldsmith-header-mobile-slide-menu">
                            <ul class="navigationn primary-menuu">
                                <?php echo self::get_sidebar_nav_menu(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <?php
        }

    }
    Goldsmith_Header::get_instance();
}


if ( !function_exists( 'goldsmith_bottom_mobile_menu' ) ) {
    add_action( 'goldsmith_before_wp_footer', 'goldsmith_bottom_mobile_menu' );
    function goldsmith_bottom_mobile_menu() {
        if ( '0' == goldsmith_settings( 'bottom_mobile_nav_visibility', '0' ) ) {
            return;
        }
        $menu_type = goldsmith_settings( 'bottom_mobile_menu_type' );
        $display_type = goldsmith_settings( 'bottom_mobile_menu_display_type' );
        ?>
        <nav class="goldsmith-bottom-mobile-nav <?php echo esc_attr( $display_type ); ?>">
            <?php
            if ( 'elementor' == $menu_type ) {

                echo goldsmith_print_elementor_templates( 'mobile_bottom_menu_elementor_templates' );

            } elseif ( 'wp-menu' == $menu_type ) {

                if ( has_nav_menu( 'mobile_bottom_menu' ) ) {

                    $html .= '<ul>';
                        $html .= wp_nav_menu(
                            array(
                                'menu' => '',
                                'theme_location' => 'mobile_bottom_menu',
                                'container' => '',
                                'container_class' => '',
                                'container_id' => '',
                                'menu_class' => '',
                                'menu_id' => '',
                                'items_wrap' => '%3$s',
                                'before' => '',
                                'after' => '',
                                'link_before' => '',
                                'link_after' => '',
                                'depth' => 1,
                                'echo' => true,
                                'fallback_cb' => 'Goldsmith_Wp_Bootstrap_Navwalker::fallback',
                                'walker' => new Goldsmith_Wp_Bootstrap_Navwalker()
                            )
                        );
                    $html .= '</ul>';
                    echo '<div class="mobile-nav-wrapper">'.$html.'</div>';
                }

            } else {

                $layouts = goldsmith_settings( 'mobile_bottom_menu_layouts' );
                $layouts = apply_filters( 'goldsmith_mobile_bottom_menu_layouts', $layouts );
                $arrow = is_rtl() ? 'arrow-right' : 'arrow-left';
                if ( !empty( $layouts ) && isset( $layouts['show'] ) ) {
                    unset( $layouts['show']['placebo'] );
                }
                $html = '';
                if ( !empty( $layouts['show'] ) ) {

                        $html .= '<ul>';
                        foreach ( $layouts['show'] as $key => $value ) {

                            switch ( $key ) {
                                case 'home':
                                    if ( '1' == goldsmith_settings( 'bottom_mobile_nav_item_customize' ) && '' != goldsmith_settings( 'mobile_bottom_menu_custom_home_html' ) ) {
                                        $html .= goldsmith_settings( 'mobile_bottom_menu_custom_home_html' );
                                    } else {
                                        $html .= '<li class="menu-item">';
                                            $html .= '<a href="'.esc_url( home_url( '/' ) ).'" class="home-page-link">';
                                                $html .= goldsmith_svg_lists( $arrow, 'goldsmith-svg-icon' );
                                                $html .= '<span>'.esc_html__( 'Home', 'goldsmith' ).'</span>';
                                            $html .= '</a>';
                                        $html .= '</li>';
                                    }
                                break;

                                case 'shop':
                                    if ( '1' == goldsmith_settings( 'bottom_mobile_nav_item_customize' ) && '' != goldsmith_settings( 'mobile_bottom_menu_custom_shop_html' ) ) {
                                        $html .= goldsmith_settings( 'mobile_bottom_menu_custom_shop_html' );
                                    } else {
                                        $html .= '<li class="menu-item">';
                                            $html .= '<a href="'.esc_url( wc_get_page_permalink( 'shop' ) ).'" class="shop-page-link">';
                                                $html .= goldsmith_svg_lists( 'store', 'goldsmith-svg-icon' );
                                                $html .= '<span>'.esc_html__( 'Store', 'goldsmith' ).'</span>';
                                            $html .= '</a>';
                                        $html .= '</li>';
                                    }
                                break;

                                case 'cart':
                                    if ( '1' == goldsmith_settings( 'bottom_mobile_nav_item_customize' ) && '' != goldsmith_settings( 'mobile_bottom_menu_custom_cart_html' ) ) {
                                        $html .= goldsmith_settings( 'mobile_bottom_menu_custom_cart_html' );
                                    } else {
                                        $html .= '<li class="menu-item">';
                                            $html .= '<a href="'.esc_url( wc_get_page_permalink( 'cart' ) ).'" class="cart-page-link">';
                                                $html .= goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' );
                                                $html .= '<span class="goldsmith-cart-count goldsmith-wc-count">'.esc_html(WC()->cart->get_cart_contents_count()).'</span>';
                                                $html .= '<span>'.esc_html__( 'Cart', 'goldsmith' ).'</span>';
                                            $html .= '</a>';
                                        $html .= '</li>';
                                    }
                                break;

                                case 'account':
                                    if ( '1' == goldsmith_settings( 'bottom_mobile_nav_item_customize' ) && '' != goldsmith_settings( 'mobile_bottom_menu_custom_account_html' ) ) {
                                        $html .= goldsmith_settings( 'mobile_bottom_menu_custom_account_html' );
                                    } else {
                                        $action_type = goldsmith_settings( 'header_myaccount_action_type', 'panel' );
                                        $account_url = class_exists('WooCommerce') ? wc_get_page_permalink( 'myaccount' ) : '';
                                        $account_url = apply_filters('goldsmith_myaccount_page_url', $account_url );
                                        $account_class = 'acoount-page-link';
                                        $account_data = '';

                                        if ( class_exists( 'WooCommerce' ) && !is_account_page() ) {
                                            if ( 'popup' == $action_type ) {
                                                $account_class = 'goldsmith-open-popup';
                                                $account_url   = '#goldsmith-account-popup';
                                            }
                                            if ( 'panel' == $action_type ) {
                                                $account_class = 'goldsmith-open-account-panel';
                                                $account_url   = '#0';
                                                $account_data  = ' data-account-action="account"';
                                            }
                                        }

                                        $html .= '<li class="menu-item">';
                                            $html .= '<a href="'.esc_url( $account_url ).'" class="'.$account_class.'"'.$account_data.'>';
                                                $html .= goldsmith_svg_lists( 'user-1', 'goldsmith-svg-icon' );
                                                $html .= '<span>'.esc_html__( 'Account', 'goldsmith' ).'</span>';
                                            $html .= '</a>';
                                        $html .= '</li>';
                                    }
                                break;

                                case 'search':
                                    if ( '1' == goldsmith_settings( 'bottom_mobile_nav_item_customize' ) && '' != goldsmith_settings( 'mobile_bottom_menu_custom_search_html' ) ) {
                                        $html .= goldsmith_settings( 'mobile_bottom_menu_custom_search_html' );
                                    } else {
                                        $html .= '<li class="menu-item">';
                                            $html .= '<a href="#0" data-name="search-popup" class="search-link">';
                                                $html .= goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' );
                                                $html .= '<span>'.esc_html__( 'Search', 'goldsmith' ).'</span>';
                                            $html .= '</a>';
                                        $html .= '</li>';
                                    }
                                break;

                                case 'cats':
                                    if ( '1' == goldsmith_settings( 'bottom_mobile_nav_item_customize' ) && '' != goldsmith_settings( 'mobile_bottom_menu_custom_cats_html' ) ) {
                                        $html .= goldsmith_settings( 'mobile_bottom_menu_custom_cats_html' );
                                    } else {
                                        $html .= '<li class="menu-item">';
                                            $html .= '<a href="#0" data-name="search-cats" class="search-category-link">';
                                                $html .= goldsmith_svg_lists( 'paper-search', 'goldsmith-svg-icon' );
                                                $html .= '<span>'.esc_html__( 'Categories', 'goldsmith' ).'</span>';
                                            $html .= '</a>';
                                        $html .= '</li>';
                                    }
                                break;
                            }
                        }
                        $html .= '</ul>';
                    echo '<div class="mobile-nav-wrapper">'.$html.'</div>';
                }
            }
            ?>
        </nav>
        <?php
    }
}
