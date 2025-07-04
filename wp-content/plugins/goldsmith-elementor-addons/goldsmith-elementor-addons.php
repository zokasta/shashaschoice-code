<?php
/**
* Plugin Name: Goldsmith Elementor Addons
* Description: Premium & Advanced Essential Elements for Elementor
* Plugin URI:  http://themeforest.net/user/Ninetheme
* Version:     1.1.4
* Author:      Ninetheme
* Text Domain: goldsmith
* Domain Path: /languages/
* Author URI:  https://ninetheme.com/
*/

/*
* Exit if accessed directly.
*/

if ( ! defined( 'ABSPATH' ) ) exit;
define( 'GOLDSMITH_PLUGIN_VERSION', '1.1.4' );
define( 'GOLDSMITH_PLUGIN_FILE', __FILE__ );
define( 'GOLDSMITH_PLUGIN_BASENAME', plugin_basename(__FILE__) );
define( 'GOLDSMITH_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'GOLDSMITH_PLUGIN_URL', plugins_url('/', __FILE__) );

final class Goldsmith_Elementor_Addons
{

    /**
    * Plugin Version
    *
    * @since 1.0
    *
    * @var string The plugin version.
    */
    const VERSION = '1.1.4';

    /**
    * Minimum Elementor Version
    *
    * @since 1.0
    *
    * @var string Minimum Elementor version required to run the plugin.
    */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
    * Minimum PHP Version
    *
    * @since 1.0
    *
    * @var string Minimum PHP version required to run the plugin.
    */
    const MINIMUM_PHP_VERSION = '5.6';

    /**
    * Instance
    *
    * @since 1.0
    *
    * @access private
    * @static
    *
    * @var Goldsmith_Elementor_Addons The single instance of the class.
    */
    private static $_instance = null;

    /**
    * Instance
    *
    * Ensures only one instance of the class is loaded or can be loaded.
    *
    * @since 1.0
    *
    * @access public
    * @static
    *
    * @return Goldsmith_Elementor_Addons An instance of the class.
    */
    public static function instance()
    {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
    * Constructor
    *
    * @since 1.0
    *
    * @access public
    */
    public function __construct()
    {
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );

        function goldsmith_pjax()
        {
            $request_headers = function_exists( 'getallheaders') ? getallheaders() : array();

            $is_pjax = isset( $_REQUEST['_pjax'] ) && ( ( isset( $request_headers['X-Requested-With'] ) && 'xmlhttprequest' === strtolower( $request_headers['X-Requested-With'] ) ) || ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && 'xmlhttprequest' === strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) );

            return $is_pjax ? true : false;
        }

    }

    /**
    * Load Textdomain
    *
    * Load plugin localization files.
    *
    * Fired by `init` action hook.
    *
    * @since 1.0
    *
    * @access public
    */
    public function i18n()
    {
        load_plugin_textdomain( 'goldsmith', false, basename( __DIR__ ) . '/languages/' );
    }

    /**
    * Initialize the plugin
    *
    * Load the plugin only after Elementor (and other plugins) are loaded.
    * Checks for basic plugin requirements, if one check fail don't continue,
    * if all check have passed load the files required to run the plugin.
    *
    * Fired by `plugins_loaded` action hook.
    *
    * @since 1.0
    *
    * @access public
    */
    public function init()
    {
        // Check if Elementor is installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'goldsmith_admin_notice_missing_main_plugin' ] );
            return;
        }
        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'goldsmith_admin_notice_minimum_elementor_version' ] );
            return;
        }
        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'goldsmith_admin_notice_minimum_php_version' ] );
            return;
        }
        // register template name for the elementor saved templates
        add_filter( 'elementor/editor/localize_settings', [ $this,'goldsmith_register_template'],10,2 );
        //add_filter( 'elementor/icons_manager/additional_tabs', [ $this,'goldsmith_add_custom_icons_tab'],10,2 );

        /* Custom plugin helper functions */
        require_once( GOLDSMITH_PLUGIN_PATH . '/classes/class-helpers-functions.php' );
        /* Add custom controls elementor section */
        require_once( GOLDSMITH_PLUGIN_PATH . '/classes/class-custom-elementor-section.php' );
        /* Add custom controls to default widgets */
        require_once( GOLDSMITH_PLUGIN_PATH . '/classes/class-customizing-default-widgets.php' );
        /* Add custom controls to page settings */
        require_once( GOLDSMITH_PLUGIN_PATH . '/classes/class-customizing-page-settings.php' );

        if ( is_user_logged_in() ) {
            //include_once( GOLDSMITH_PLUGIN_PATH . '/templates/template-library/library-manager.php' );
            //include_once( GOLDSMITH_PLUGIN_PATH . '/templates/template-library/library-source.php' );
        }

        /* includes/shortcodes/elementor */
        if ( ! get_option( 'disable_goldsmith_list_shortcodes' ) == 1 ) {
            require_once( GOLDSMITH_PLUGIN_PATH . '/classes/class-list-shortcodes.php' );
        }
        if ( class_exists('WooCommerce') ) {

            /* Add custom wp woocommerce widgets */
            require_once( GOLDSMITH_PLUGIN_PATH . '/widgets/woocommerce/wp-widgets/widget-product-status.php' );
            require_once( GOLDSMITH_PLUGIN_PATH . '/widgets/woocommerce/wp-widgets/widget-product-categories.php' );

            if ( ! get_option( 'disable_goldsmith_wc_brands' ) == 1 ) {
                require_once( GOLDSMITH_PLUGIN_PATH . '/widgets/woocommerce/brands/brands.php' );
            }
            if ( ! get_option( 'disable_goldsmith_wc_compare' ) == 1 ) {
                require_once( GOLDSMITH_PLUGIN_PATH . '/classes/class-compare.php' );
            }
            if ( ! get_option( 'disable_goldsmith_wc_wishlist' ) == 1 ) {
                require_once( GOLDSMITH_PLUGIN_PATH . '/classes/class-wishlist.php' );
            }
            if ( ! get_option( 'disable_goldsmith_wc_swatches' ) == 1 ) {
                require_once( GOLDSMITH_PLUGIN_PATH . '/classes/class-swatches.php' );
            }
            if ( ! get_option( 'disable_goldsmith_wc_quickview' ) == 1 ) {
                require_once( GOLDSMITH_PLUGIN_PATH . '/classes/class-quick-view.php' );
            }
            if ( ! get_option( 'disable_goldsmith_product360_builder' ) == 1 ) {
                require_once( GOLDSMITH_PLUGIN_PATH . '/widgets/woocommerce/product360/product360.php' );
            }
            if ( ! get_option( 'disable_goldsmith_wc_ajax_search' ) == 1 ) {
                require_once( GOLDSMITH_PLUGIN_PATH . '/widgets/woocommerce/ajax-search/class-ajax-search.php' );
            }

            add_action( 'wp_ajax_goldsmith_ajax_tab_slider', [ $this, 'goldsmith_ajax_tab_slider_handler' ] );
            add_action( 'wp_ajax_nopriv_goldsmith_ajax_tab_slider', [ $this, 'goldsmith_ajax_tab_slider_handler' ] );
            add_action( 'woocommerce_single_product_summary', [ $this, 'goldsmith_product_share_buttons' ], 90 );
            add_shortcode( 'goldsmith_share_icon', [ $this, 'goldsmith_product_share_buttons' ] );
        }

        if ( ! get_option( 'disable_goldsmith_popups_builder' ) == 1 ) {
            require_once( GOLDSMITH_PLUGIN_PATH . '/classes/class-popup-builder.php' );
        }

        /* Admin template */
        require_once( GOLDSMITH_PLUGIN_PATH . '/templates/admin/admin.php' );
        // Categories registered
        add_action( 'elementor/elements/categories_registered', [ $this, 'goldsmith_add_widget_category' ] );
        // Widgets registered
        add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
		add_action( 'elementor/widgets/register', [ $this, 'init_woo_widgets' ] );
        add_action( 'elementor/widgets/register', [ $this, 'init_single_widgets' ] );

        // Register Widget Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'widget_scripts' ] );
        // Register Widget Styles
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
        // Register Widget Scripts
        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'widget_front_scripts' ] );

        add_action('elementor/editor/after_enqueue_styles', [ $this, 'admin_custom_scripts' ]);

        //add_action( 'elementor/ajax/register_actions', [ $this, 'register_actions' ] );
    }

    public function register_actions() {}

    public function goldsmith_register_template( $localized_settings, $config )
    {
        $localized_settings = [
            'i18n' => [
                'my_templates' => esc_html__( 'Goldsmith Templates', 'goldsmith' )
            ]
        ];
        return $localized_settings;
    }

    public function admin_custom_scripts()
    {
        // Elementor Editor custom css
        wp_enqueue_style( 'goldsmith-custom-editor', GOLDSMITH_PLUGIN_URL. 'assets/front/css/plugin-editor.css' );
    }

    public function widget_styles()
    {
        // Plugin custom css
        $rtl = is_rtl() ? '-rtl' : '';
        //wp_enqueue_style( 'goldsmith-custom', GOLDSMITH_PLUGIN_URL. 'assets/front/css/custom'.$rtl.'.css' );
    }

    public function widget_front_scripts()
    {
        wp_enqueue_script( 'goldsmith-addons-custom-scripts', GOLDSMITH_PLUGIN_URL. 'assets/front/js/custom-scripts.js', [ 'jquery' ], GOLDSMITH_PLUGIN_VERSION, true );
    }

    public function widget_scripts()
    {
        // vegas slider
        wp_register_style( 'vegas', GOLDSMITH_PLUGIN_URL. 'assets/front/js/vegas/vegas.css', '1.0', true );
        wp_register_script( 'vegas', GOLDSMITH_PLUGIN_URL. 'assets/front/js/vegas/vegas.min.js', array( 'jquery' ), '1.0', true );

        // magnific-popup-lightbox
        //wp_register_style( 'magnific', GOLDSMITH_PLUGIN_URL. 'assets/front/js/magnific/magnific-popup.css', false, '1.0' );
        wp_register_script( 'magnific', GOLDSMITH_PLUGIN_URL. 'assets/front/js/magnific/magnific-popup.min.js', array( 'jquery' ), false, '1.0' );

        // animated-headline
        wp_register_style( 'animated-headline', GOLDSMITH_PLUGIN_URL. 'assets/front/js/animated-headline/style.css');
        wp_register_script( 'animated-headline', GOLDSMITH_PLUGIN_URL. 'assets/front/js/animated-headline/script.js', [ 'jquery','elementor-frontend' ], '1.0.0', true);

        // isotope
        wp_register_script( 'isotope', GOLDSMITH_PLUGIN_URL. 'assets/front/js/isotope/isotope.min.js', array( 'jquery' ), false, '1.0' );
        wp_register_script( 'imagesloaded', GOLDSMITH_PLUGIN_URL. 'assets/front/js/isotope/imagesloaded.pkgd.min.js', array( 'jquery' ), false, '1.0' );
        wp_register_script( 'anime', GOLDSMITH_PLUGIN_URL. 'assets/front/js/anime/anime.min.js', array( 'jquery' ), false, '1.0' );
        wp_register_script( 'slide-show', GOLDSMITH_PLUGIN_URL. 'assets/front/js/slide-show.js', array( 'jquery' ), false, '1.0' );

        // isotope
        wp_register_style( 'cbp', GOLDSMITH_PLUGIN_URL . 'assets/front/js/cbp/cubeportfolio.min.css', false, '1.0' );
        wp_register_style( 'cbp-custom', GOLDSMITH_PLUGIN_URL . 'assets/front/js/cbp/cubeportfolio-custom.css', false, '1.0' );
        wp_register_script( 'cbp', GOLDSMITH_PLUGIN_URL. 'assets/front/js/cbp/cubeportfolio.min.js', array( 'jquery' ), false, '1.0' );

        // jarallax
        wp_register_script( 'jarallax', GOLDSMITH_PLUGIN_URL. 'assets/front/js/jarallax/jarallax.min.js', array( 'jquery' ), false, '1.0' );
        wp_register_script( 'simple-parallax', GOLDSMITH_PLUGIN_URL. 'assets/front/js/simpleParallax/simpleParallax.min.js', array( 'jquery' ), false, '1.0' );
        wp_register_script( 'universal-parallax', GOLDSMITH_PLUGIN_URL. 'assets/front/js/jarallax/universal-parallax.min.js', array( 'jquery' ), false, '1.0' );
        wp_register_script( 'particles', GOLDSMITH_PLUGIN_URL. 'assets/front/js/particles/particles.min.js', array( 'jquery' ), false, '1.0' );
        wp_register_script( 'tilt', GOLDSMITH_PLUGIN_URL. 'assets/front/js/tilt/tilt.jquery.min.js', array( 'jquery' ), false, '1.0' );
        wp_register_script( 'instafeed', GOLDSMITH_PLUGIN_URL. 'assets/front/js/instafeed/instafeed.min.js', array( 'jquery' ), false, '1.0' );

        // jquery-ui
        wp_register_style( 'jquery-ui', GOLDSMITH_PLUGIN_URL. 'assets/front/js/jquery-ui/jquery-ui.min.css', false, '1.0' );
        wp_register_script( 'jquery-ui', GOLDSMITH_PLUGIN_URL. 'assets/front/js/jquery-ui/jquery-ui.min.js', array( 'jquery' ), false, '1.0' );

        // widget-tab-slider
        wp_register_script( 'widget-tab-slider', GOLDSMITH_PLUGIN_URL . 'assets/front/js/ajax-tab-slider/script.js', array('jquery'), '1.0.0', true );
    }

    /**
    * Admin notice
    *
    * Warning when the site doesn't have Elementor installed or activated.
    *
    * @since 1.0
    *
    * @access public
    */
    public function goldsmith_admin_notice_missing_main_plugin()
    {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '%1$s requires %2$s to be installed and activated.', 'goldsmith' ),
            '<strong>' . esc_html__( 'Goldsmith Elementor Addons', 'goldsmith' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'goldsmith' ) . '</strong>'
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
    * Admin notice
    *
    * Warning when the site doesn't have a minimum required Elementor version.
    *
    * @since 1.0
    *
    * @access public
    */
    public function goldsmith_admin_notice_minimum_elementor_version()
    {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '%1$s requires %2$s version %3$s or greater.', 'goldsmith' ),
            '<strong>' . esc_html__( 'Goldsmith Elementor Addons', 'goldsmith' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'goldsmith' ) . '</strong>',
             self::MINIMUM_ELEMENTOR_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
    * Admin notice
    *
    * Warning when the site doesn't have a minimum required PHP version.
    *
    * @since 1.0
    *
    * @access public
    */
    public function goldsmith_admin_notice_minimum_php_version()
    {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '%1$s requires %2$s version %3$s or greater.', 'goldsmith' ),
            '<strong>' . esc_html__( 'Goldsmith Elementor Addons', 'goldsmith' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'goldsmith' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
    * Register Widgets Category
    *
    */
    public function goldsmith_add_widget_category( $elements_manager )
    {
        $elements_manager->add_category( 'goldsmith', [ 'title' => esc_html__( 'Goldsmith Basic', 'goldsmith' ),'icon' => 'fa fa-smile-o' ] );
        $elements_manager->add_category( 'goldsmith-post', [ 'title' => esc_html__( 'Goldsmith Post', 'goldsmith' ) ] );
        $elements_manager->add_category( 'goldsmith-woo', [ 'title' => esc_html__( 'Goldsmith WooCommerce', 'goldsmith' ) ] );
        $elements_manager->add_category( 'goldsmith-woo-product', [ 'title' => esc_html__( 'Goldsmith WooCommerce Product', 'goldsmith' ) ] );
    }

    public function goldsmith_widgets_list()
    {
        $list = array(
            array( 'name' => 'menu',                'class' => 'Goldsmith_Menu_Dropdown' ),
            array( 'name' => 'menu-vertical',       'class' => 'Goldsmith_Vertical_Menu' ),
            array( 'name' => 'button',              'class' => 'Goldsmith_Button' ),
            array( 'name' => 'label',               'class' => 'Goldsmith_Label' ),
            array( 'name' => 'animated-headline',   'class' => 'Goldsmith_Animated_Headline' ),
            array( 'name' => 'home-slider',         'class' => 'Goldsmith_Home_Slider' ),
            array( 'name' => 'swiper-template',     'class' => 'Goldsmith_Template_Slider' ),
            array( 'name' => 'slide-show',          'class' => 'Goldsmith_Slide_Show' ),
            array( 'name' => 'posts-base',          'class' => 'Goldsmith_Posts_Base' ),
            array( 'name' => 'breadcrumbs',         'class' => 'Goldsmith_Breadcrumbs' ),
            array( 'name' => 'image-slider',        'class' => 'Goldsmith_Images_Slider' ),
            array( 'name' => 'team',                'class' => 'Goldsmith_Team_Slider' ),
            array( 'name' => 'instagram-slider',    'class' => 'Goldsmith_Instagram_Slider' ),
            array( 'name' => 'fetatures-item',      'class' => 'Goldsmith_Features_Item' ),
            array( 'name' => 'timer',               'class' => 'Goldsmith_Timer' ),
            array( 'name' => 'contact-form-7',      'class' => 'Goldsmith_Contact_Form_7' ),
            array( 'name' => 'testimonials-slider', 'class' => 'Goldsmith_Testimonials' ),
            array( 'name' => 'sidebar-widgets',     'class' => 'Goldsmith_Sidebar_Widgets' ),
            array( 'name' => 'vegas-slider',        'class' => 'Goldsmith_Vegas_Slider' ),
            array( 'name' => 'vegas-template',      'class' => 'Goldsmith_Vegas_Template' ),
            array( 'name' => 'gallery',             'class' => 'Goldsmith_Portfolio' )
        );
        return $list;
    }

    /**
    * Init Widgets
    */
    public function init_widgets()
    {
        $widgets = $this->goldsmith_widgets_list();

        if ( ! empty( $widgets ) ) {

            foreach ( $widgets as $widget ) {

                $option = 'disable_'.str_replace( '-', '_', $widget['name'] );
                $path = GOLDSMITH_PLUGIN_PATH . '/widgets/';
                $file = $widget['name'] . '.php';
                $file = isset( $widget['subfolder'] ) != '' ? $path.$widget['subfolder'] . '/' . $widget['name']. '.php' : $path.$file;
                $class = 'Elementor\\'.$widget['class'];

                if ( ! get_option( $option ) == 1 ) {

                    if ( file_exists( $file ) ) {
                        require_once( $file );
                        \Elementor\Plugin::instance()->widgets_manager->register( new $class() );
                    }
                }
            }
        }
    }

    public function goldsmith_woo_widgets_list()
    {
		// wocommerce widgets
        $list = array(
            //array( 'name' => 'woo-fast-filters',        'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Fast_Filters' ),
            array( 'name' => 'woo-tab-slider',          'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Ajax_Tab_Slider' ),
            array( 'name' => 'woo-grid',                'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Grid' ),
            array( 'name' => 'woo-grid-two',            'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Grid_Two' ),
            array( 'name' => 'woo-category-grid',       'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Category_Grid' ),
            array( 'name' => 'woo-list',                'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Products_List' ),
            array( 'name' => 'woo-slider',              'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Slider' ),
            array( 'name' => 'woo-gallery',             'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Gallery' ),
            array( 'name' => 'woo-banner',              'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Banner' ),
            array( 'name' => 'woo-banner-slider',       'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Banner_Slider' ),
            array( 'name' => 'woo-banner-hero-slider',  'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Banner_Hero_Slider' ),
            array( 'name' => 'woo-brands',              'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Brands' ),
            array( 'name' => 'woo-custom-reviews',      'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Custom_Reviews' ),
            array( 'name' => 'woo-ajax-search',         'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Ajax_Search' ),
            array( 'name' => 'woo-archive-description', 'subfolder' => 'woocommerce', 'class' => 'Goldsmith_WC_Archive_Description' ),
            array( 'name' => 'woo-page-title',          'subfolder' => 'woocommerce', 'class' => 'Goldsmith_WC_Page_Title' ),
            array( 'name' => 'woo-categories',          'subfolder' => 'woocommerce', 'class' => 'Goldsmith_WC_Categories' ),
            array( 'name' => 'woo-product-item',        'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Product_Item' ),
            array( 'name' => 'woo-product-deals',       'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Product_Deals' ),
            array( 'name' => 'woo-special-offer',       'subfolder' => 'woocommerce', 'class' => 'Goldsmith_Woo_Special_Offer' )
        );
        return $list;
    }

    /**
    * Init Widgets
    */
    public function init_woo_widgets()
    {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        $widgets = $this->goldsmith_woo_widgets_list();

        if ( ! empty( $widgets ) ) {

            foreach ( $widgets as $widget ) {

                $option = 'disable_'.str_replace( '-', '_', $widget['name'] );
                $path = GOLDSMITH_PLUGIN_PATH . '/widgets/';
                $file = $widget['name'] . '.php';
                $file = isset( $widget['subfolder'] ) != '' ? $path.$widget['subfolder'] . '/' . $widget['name']. '.php' : $path.$file;
                $class = 'Elementor\\'.$widget['class'];

                if ( ! get_option( $option ) == 1 ) {

                    if ( file_exists( $file ) ) {
                        require_once( $file );
                        \Elementor\Plugin::instance()->widgets_manager->register( new $class() );
                    }
                }
            }
        }
    }


    /**
    * Register Single Post Widgets
    */
    public function goldsmith_single_widgets_list()
    {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        $list = array(
            // post widgets
            array( 'post-type' => 'post', 'name' => 'post-data', 'class' => 'Goldsmith_Post_Data' ),
            // wocommerce widgets
            array( 'post-type' => 'product','name' => 'add-to-cart',                    'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Add_To_Cart' ),
            array( 'post-type' => 'product','name' => 'breadcrumb',                     'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Breadcrumb' ),
            array( 'post-type' => 'product','name' => 'product-add-to-cart',            'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Product_Add_To_Cart' ),
            array( 'post-type' => 'product','name' => 'product-additional-information', 'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Product_Additional_Information' ),
            array( 'post-type' => 'product','name' => 'product-data-tabs',              'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Product_Data_Tabs' ),
            array( 'post-type' => 'product','name' => 'product-images',                 'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Product_Images' ),
            array( 'post-type' => 'product','name' => 'product-meta',                   'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Product_Meta' ),
            array( 'post-type' => 'product','name' => 'product-price',                  'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Product_Price' ),
            array( 'post-type' => 'product','name' => 'product-rating',                 'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Product_Rating' ),
            array( 'post-type' => 'product','name' => 'product-related',                'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Product_Related' ),
            array( 'post-type' => 'product','name' => 'product-short-description',      'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Product_Short_Description' ),
            array( 'post-type' => 'product','name' => 'product-stock',                  'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Product_Stock' ),
            array( 'post-type' => 'product','name' => 'product-title',                  'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Product_Title' ),
            array( 'post-type' => 'product','name' => 'product-upsell',                 'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Product_Upsell' ),
            array( 'post-type' => 'product','name' => 'single-elements',                'subfolder' => 'woocommerce/product', 'class' => 'Goldsmith_WC_Single_Elements' )
        );
        return $list;
    }

    /**
    * Init Single Post Widgets
    */
    public function init_single_widgets()
    {
        $widgets = $this->goldsmith_single_widgets_list();
        global $post;
        $goldsmith_post_type = false;

        if ( is_single() && !empty( $widgets ) && !is_customize_preview() ) {

            $goldsmith_post_type = get_post_type( $post->ID );

            $count = 0;

            foreach ( $widgets as $widget ) {

                if ( $goldsmith_post_type == $widgets[$count]['post-type'] || $goldsmith_post_type == 'elementor_library' ) {

                    $option = 'disable_'.str_replace( '-', '_', $widget['name'] );
                    $path = GOLDSMITH_PLUGIN_PATH . '/widgets/';
                    $file = $widget['name'] . '.php';
                    $file = isset( $widget['subfolder'] ) != '' ? $path.$widget['subfolder'] . '/' . $widget['name']. '.php' : $path.$file;
                    $class = 'Elementor\\'.$widget['class'];

                    if ( ! get_option( $option ) == 1 ) {

                        if ( file_exists( $file ) ) {

                            require_once( $file );
                            \Elementor\Plugin::instance()->widgets_manager->register( new $class() );
                        }
                    }
                }
                $count++;
            }
        }
    }

    /*
    * List Icons
    */

    public function goldsmith_add_custom_icons_tab( $tabs = array() )
    {
        $new_icons = array(
            'shopping-bags',
            'magnifying-glass',
            'menu',
            'heart',
            'two-arrows',
            'shopping-bag',
            'user',
            'menu-1',
            'justification',
            'scroll',
            'shuffle',
            'shuffle-1',
            'supermarket',
            'witness',
            'quote-left',
            'list',
            'menu-2',
            'grid',
            'project',
            'revenue',
            'quality',
            'shuttle',
            'invoice',
            'secure-payment',
            '24-hours-support',
            'placeholder',
            'telephone',
            'mail',
            'zoom-in',
            'right-arrow',
            'left-arrow',
            'cancel',
            'cancel-1',
            'done',
            'check',
            'select',
            'cancel-2',
            'password',
            'scroll-1',
            'calendar',
            'exit',
            'plus',
            'crosshair',
            'loupe',
            'magnifying-glass-1',
            'right-quote',
            'plus-1',
            'lock',
            'copyright',
            'list-1'
        );

        $tabs['goldsmith-custom-icons'] = array(
            'name' => 'goldsmith-custom-icons',
            'label' => esc_html__( 'Goldsmith Icons', 'goldsmith' ),
            'labelIcon' => 'flaticon-heart',
            'prefix' => 'flaticon-',
            'displayPrefix' => 'goldsmith-icons',
            'url' => get_template_directory_uri() . '/css/flaticon/flaticon.css',
            'icons' => $new_icons,
            'ver' => '1.0.0',
        );

        return $tabs;
    }

    public function goldsmith_ajax_tab_slider_handler() {
        global $product;
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $_POST['per_page'],
            'order'          => $_POST['order'],
            'orderby'        => $_POST['orderby']
        );
        if ( $_POST['cat_id'] != null ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'id',
                'terms'    => $_POST['cat_id']
            );
        }

        $loop = new WP_Query( $args );
        if ( $loop->have_posts() ) {
            while ( $loop->have_posts() ) {
                $loop->the_post();
                $product = new WC_Product(get_the_ID());
                $visibility = $product->get_catalog_visibility();
                if ( $product->is_visible() ) {
                    echo '<div class="swiper-slide product_item '.$_POST['img_size'].'">';
                        wc_get_template_part( 'content', 'product' );
                    echo '</div>';
                }
            }
        } else {
            echo esc_html__( 'No products found','goldsmith' );
        }
        wp_reset_postdata();

        wp_die();
    }

    /**
    * ------------------------------------------------------------------------------------------------
    * Single product share buttons
    * ------------------------------------------------------------------------------------------------
    */
    public function goldsmith_product_share_buttons()
    {
        if ( !function_exists( 'goldsmith_settings' ) ) {
            return;
        }
        if ( '1' == goldsmith_settings( 'single_shop_share_visibility', '0' ) ) {

            $type = goldsmith_settings( 'single_shop_share_type' );
            if ( $type == 'custom' ) {
                echo do_shortcode( goldsmith_settings( 'product_custom_share' ) );
            } else {

                $title = 'share' === $type ? esc_html__( 'Share', 'goldsmith' ) : esc_html__( 'Follow', 'goldsmith' );
                ?>
                <div class="goldsmith-summary-item goldsmith-product-share">
                    <span class="share-title goldsmith-small-title"><?php echo esc_html( $title ); ?>: </span> <?php $this->goldsmith_shortcode_social( array( 'type' => $type ) ); ?>
                </div>
                <?php
            }
        }
    }

	public function goldsmith_shortcode_social($args) {

        if ( !function_exists( 'goldsmith_settings' ) ) {
            return;
        }

        $def_args = array(
            'type' => 'share',
            'page_link' => false
        );

        $type      = !empty( $args ) ? $args['type'] : $def_args['type'];
        $page_link = !empty( $args ) && isset( $args['page_link'] ) ? $args['page_link'] : $def_args['page_link'];
        $target    = "_blank";

        $thumb_id   = get_post_thumbnail_id();
        $thumb_url  = wp_get_attachment_image_src($thumb_id, 'thumbnail-size', true);
        $page_title = get_the_title();

        if ( ! $page_link ) {
            $page_link = get_the_permalink();
        }

        if ( class_exists( 'WooCommerce' ) && is_shop() ) {
            $page_link = get_permalink( get_option( 'woocommerce_shop_page_id' ) );
        }
        if ( class_exists( 'WooCommerce' ) && ( is_product_category() || is_category() ) ) {
            $page_link = get_category_link( get_queried_object()->term_id );
        }
        if ( is_home() && ! is_front_page() ) {
            $page_link = get_permalink( get_option( 'page_for_posts' ) );
        }

        ?>
        <div class="goldsmith-social-icons">
            <?php if ( '1' == goldsmith_settings( 'share_facebook', '0' ) ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'fb_link' )) : 'https://www.facebook.com/sharer/sharer.php?u=' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-facebook" data-title="facebook">
                    <i class="nt-icon-facebook"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_twitter', '0') ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'twitter_link' )) : 'https://twitter.com/share?url=' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-twitter" data-title="twitter">
                    <i class="nt-icon-twitter"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_instagram', '0') && $type == 'follow' && '' != goldsmith_settings( 'instagram_link' ) ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'instagram_link' )) : '' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-instagram" data-title="instagram">
                    <i class="nt-icon-instagram"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_youtube', '0') && $type == 'follow' && '' != goldsmith_settings( 'youtube_link' ) ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'youtube_link' )) : '' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-youtube" data-title="youtube">
                    <i class="nt-icon-youtube"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_vimeo', '0') && $type == 'follow' && '' != goldsmith_settings( 'vimeo_link' ) ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url( goldsmith_settings( 'vimeo_link' ) ) : '' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-vimeo" data-title="vimeo">
                    <i class="nt-icon-vimeo"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_pinterest', '0') ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'pinterest_link' )) : 'https://pinterest.com/pin/create/button/?url=' . $page_link . '&media=' . $thumb_url[0] . '&description=' . urlencode( $page_title ); ?>" target="<?php echo esc_attr( $target ); ?>" class="social-pinterest" data-title="pinterest">
                    <i class="nt-icon-pinterest"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_linkedin', '0') ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'linkedin_link' )) : 'https://www.linkedin.com/shareArticle?mini=true&url=' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-linkedin" data-title="linkedin">
                    <i class="nt-icon-linkedin"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_tumblr', '0') && $type == 'follow' && '' != goldsmith_settings( 'tumblr_link' ) ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'tumblr_link' )) : '' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-tumblr" data-title="tumblr">
                    <i class="nt-icon-tumblr"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_flickr', '0') && $type == 'follow' && '' != goldsmith_settings( 'flickr_link' ) ): ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'flickr_link' )) : '' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-flickr" data-title="flickr">
                    <i class="nt-icon-flickr"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_github', '0') && $type == 'follow' && '' != goldsmith_settings( 'github_link' ) ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'github_link' )) : '' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-github" data-title="github">
                    <i class="nt-icon-github"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_behance', '0') && $type == 'follow' && '' != goldsmith_settings( 'behance_link' ) ): ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'behance_link' )) : '' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-behance" data-title="behance">
                    <i class="nt-icon-behance"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_dribbble', '0') && $type == 'follow' && '' != goldsmith_settings( 'dribbble_link' ) ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'dribbble_link' )) : '' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-dribbble" data-title="dribbble">
                    <i class="nt-icon-dribbble"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_soundcloud', '0') && $type == 'follow' && '' != goldsmith_settings( 'soundcloud_link' ) ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'soundcloud_link' )) : '' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-soundcloud" data-title="soundcloud">
                    <i class="nt-icon-soundcloud"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_spotify', '0') && $type == 'follow' && '' != goldsmith_settings( 'spotify_link' ) ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'spotify_link' )) : '' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-spotify" data-title="spotify">
                    <i class="nt-icon-spotify"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_ok', '0') ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? esc_url(goldsmith_settings( 'ok_link' )) : 'https://connect.ok.ru/offer?url=' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-ok" data-title="ok">
                    <i class="nt-icon-odnoklassniki-square"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_whatsapp', '0') ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? ( goldsmith_settings( 'whatsapp_link' )) : 'https://api.whatsapp.com/send?text=' . urlencode( $page_link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="whatsapp-desktop social-whatsapp" data-title="whatsapp">
                    <i class="nt-icon-whatsapp"></i>
                </a>

                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? ( goldsmith_settings( 'whatsapp_link' ) ) : 'whatsapp://send?text=' . urlencode( $page_link ); ?>" target="<?php echo esc_attr( $target ); ?>" class="whatsapp-mobile social-whatsapp">
                    <i class="nt-icon-whatsapp"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_telegram', '0') ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? ( goldsmith_settings( 'tg_link' )) : 'https://telegram.me/share/url?url=' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-telegram" data-title="telegram">
                    <i class="nt-icon-telegram"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_viber', '0') && $type == 'share' && goldsmith_settings( 'share_viber' ) ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'viber://forward?text=' . $page_link; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-viber" data-title="viber">
                    <i class="nt-icon-viber"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_vk', '0') ) : ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo 'follow' === $type ? ( goldsmith_settings( 'vk_link' )) : 'https://vk.com/share.php?url=' . $page_link . '&image=' . $thumb_url[0] . '&title=' . $page_title; ?>" target="<?php echo esc_attr( $target ); ?>" class="social-vk" data-title="vk">
                    <i class="nt-icon-vk"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_snapchat', '0') && $type == 'follow' && '' != goldsmith_settings( 'snapchat_link' ) ): ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo goldsmith_settings( 'snapchat_link' ); ?>" target="<?php echo esc_attr( $target ); ?>" class="social-snapchat" data-title="snapchat">
                    <i class="nt-icon-snapchat"></i>
                </a>
            <?php endif ?>

            <?php if ( '1' == goldsmith_settings('share_tiktok', '0') && $type == 'follow' && '' != goldsmith_settings( 'tiktok_link' ) ): ?>
                <a rel="noopener noreferrer nofollow" href="<?php echo goldsmith_settings( 'tiktok_link' ); ?>" target="<?php echo esc_attr( $target ); ?>" class="social-tiktok" data-title="tiktok">
                    <i class="nt-icon-tiktok"></i>
                </a>
            <?php endif ?>

        </div>
        <?php
    }

}
Goldsmith_Elementor_Addons::instance();
