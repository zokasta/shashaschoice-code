<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPCleverWoobt' ) && class_exists( 'WC_Product' ) ) {
	class WPCleverWoobt {
		protected static $instance = null;
		protected static $image_size = 'woocommerce_thumbnail';
		protected static $localization = [];
		protected static $positions = [];
		protected static $settings = [];
		protected static $rules = [];
		protected static $types = [
			'simple',
			'woosb',
			'bundle',
			'subscription',
		];

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function __construct() {
			// Get rules
			self::$rules = (array) get_option( 'woobt_rules', [] );

			// Init
			add_action( 'init', [ $this, 'init' ] );

			// Add image to variation
			add_filter( 'woocommerce_available_variation', [ $this, 'available_variation' ], 10, 3 );
			add_filter( 'woovr_data_attributes', [ $this, 'woovr_data_attributes' ], 10, 2 );

			// Settings
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
			add_action( 'admin_init', [ $this, 'register_settings' ] );
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
			add_action( 'wp_ajax_woobt_update_search_settings', [ $this, 'ajax_update_search_settings' ] );
			add_action( 'wp_ajax_woobt_get_search_results', [ $this, 'ajax_get_search_results' ] );
			add_action( 'wp_ajax_woobt_add_text', [ $this, 'ajax_add_text' ] );
			add_action( 'wp_ajax_woobt_add_rule', [ $this, 'ajax_add_rule' ] );
			add_action( 'wp_ajax_woobt_add_combination', [ $this, 'ajax_add_combination' ] );
			add_action( 'wp_ajax_woobt_search_term', [ $this, 'ajax_search_term' ] );
			add_action( 'wp_ajax_woobt_import_export', [ $this, 'ajax_import_export' ] );
			add_action( 'wp_ajax_woobt_import_export_save', [ $this, 'ajax_import_export_save' ] );

			// Frontend scripts
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

			// Shortcode
			add_shortcode( 'woobt', [ $this, 'shortcode' ] );
			add_shortcode( 'woobt_items', [ $this, 'shortcode' ] );

			// Product data tabs
			add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_data_tabs' ] );

			// Product data panels
			add_action( 'woocommerce_product_data_panels', [ $this, 'product_data_panels' ] );
			add_action( 'woocommerce_process_product_meta', [ $this, 'process_product_meta' ] );

			// Product price
			add_filter( 'woocommerce_product_price_class', [ $this, 'product_price_class' ] );

			// Add to cart button & form
			add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'add_to_cart_button' ] );

			// Show items in standard position
			add_action( 'woocommerce_before_add_to_cart_form', [ $this, 'show_items_before_atc' ] );
			add_action( 'woocommerce_after_add_to_cart_form', [ $this, 'show_items_after_atc' ] );
			add_action( 'woocommerce_single_product_summary', [ $this, 'show_items_below_title' ], 6 );
			add_action( 'woocommerce_single_product_summary', [ $this, 'show_items_below_price' ], 11 );
			add_action( 'woocommerce_single_product_summary', [ $this, 'show_items_below_excerpt' ], 21 );
			add_action( 'woocommerce_single_product_summary', [ $this, 'show_items_below_meta' ], 41 );
			add_action( 'woocommerce_after_single_product_summary', [ $this, 'show_items_below_summary' ], 9 );

			// Show items in custom position
			add_action( 'woobt_custom_position', [ $this, 'show_items_position' ] );

			// Add to cart
			add_filter( 'woocommerce_add_to_cart_sold_individually_found_in_cart', [
				$this,
				'found_in_cart'
			], 11, 2 );
			add_filter( 'woocommerce_add_to_cart_validation', [ $this, 'add_to_cart_validation' ], 11, 4 );
			add_action( 'woocommerce_add_to_cart', [ $this, 'add_to_cart' ], 11, 6 );
			add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_cart_item_data' ], 11, 2 );
			add_filter( 'woocommerce_get_cart_item_from_session', [
				$this,
				'get_cart_item_from_session'
			], 11, 2 );

			// Frontend AJAX
			add_action( 'wc_ajax_woobt_get_variation_items', [ $this, 'ajax_get_variation_items' ] );
			add_action( 'wc_ajax_woobt_add_all_to_cart', [ $this, 'ajax_add_all_to_cart' ] );

			// Cart contents
			add_action( 'woocommerce_before_mini_cart_contents', [ $this, 'before_mini_cart_contents' ], 9999 );
			add_action( 'woocommerce_before_calculate_totals', [ $this, 'before_calculate_totals' ], 9999 );

			// Cart item
			add_filter( 'woocommerce_cart_item_name', [ $this, 'cart_item_name' ], 10, 2 );
			add_filter( 'woocommerce_cart_item_quantity', [ $this, 'cart_item_quantity' ], 10, 3 );
			add_action( 'woocommerce_cart_item_removed', [ $this, 'cart_item_removed' ], 10, 2 );

			// Order item
			add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'order_line_item' ], 10, 3 );
			add_filter( 'woocommerce_order_item_name', [ $this, 'cart_item_name' ], 10, 2 );

			// Admin order
			add_filter( 'woocommerce_hidden_order_itemmeta', [ $this, 'hidden_order_item_meta' ] );
			add_action( 'woocommerce_before_order_itemmeta', [ $this, 'before_order_item_meta' ], 10, 2 );

			// Order again
			add_filter( 'woocommerce_order_again_cart_item_data', [ $this, 'order_again_item_data' ], 10, 2 );
			add_action( 'woocommerce_cart_loaded_from_session', [ $this, 'cart_loaded_from_session' ] );

			// Undo remove
			add_action( 'woocommerce_cart_item_restored', [ $this, 'cart_item_restored' ], 10, 2 );

			// Add settings link
			add_filter( 'plugin_action_links', [ $this, 'action_links' ], 10, 2 );
			add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 2 );

			// Admin
			add_filter( 'display_post_states', [ $this, 'display_post_states' ], 10, 2 );

			// Search filters
			if ( WPCleverWoobt_Helper()->get_setting( 'search_sku', 'no' ) === 'yes' ) {
				add_filter( 'pre_get_posts', [ $this, 'search_sku' ], 99 );
			}

			if ( WPCleverWoobt_Helper()->get_setting( 'search_exact', 'no' ) === 'yes' ) {
				add_action( 'pre_get_posts', [ $this, 'search_exact' ], 99 );
			}

			if ( WPCleverWoobt_Helper()->get_setting( 'search_sentence', 'no' ) === 'yes' ) {
				add_action( 'pre_get_posts', [ $this, 'search_sentence' ], 99 );
			}

			// Admin product filter
			add_filter( 'woocommerce_products_admin_list_table_filters', [ $this, 'product_filter' ] );
			add_action( 'pre_get_posts', [ $this, 'apply_product_filter' ] );

			// WPML
			if ( function_exists( 'wpml_loaded' ) && apply_filters( 'woobt_wpml_filters', true ) ) {
				add_filter( 'woobt_item_id', [ $this, 'wpml_product_id' ], 99 );
				add_filter( 'woobt_parent_id', [ $this, 'wpml_product_id' ], 99 );
			}

			// WPC Variations Radio Buttons
			add_filter( 'woovr_default_selector', [ $this, 'woovr_default_selector' ], 99, 4 );

			// WPC Smart Messages
			add_filter( 'wpcsm_locations', [ $this, 'wpcsm_locations' ] );

			// Export
			add_filter( 'woocommerce_product_export_meta_value', [ $this, 'export_process' ], 10, 3 );

			// Import
			add_filter( 'woocommerce_product_import_pre_insert_product_object', [
				$this,
				'import_process'
			], 10, 2 );
		}

		function init() {
			// load text-domain
			load_plugin_textdomain( 'woo-bought-together', false, basename( WOOBT_DIR ) . '/languages/' );

			self::$types      = (array) apply_filters( 'woobt_product_types', self::$types );
			self::$image_size = apply_filters( 'woobt_image_size', self::$image_size );
			self::$positions  = apply_filters( 'woobt_positions', [
				'before'        => esc_html__( 'Above add to cart button', 'woo-bought-together' ),
				'after'         => esc_html__( 'Under add to cart button', 'woo-bought-together' ),
				'below_title'   => esc_html__( 'Under the title', 'woo-bought-together' ),
				'below_price'   => esc_html__( 'Under the price', 'woo-bought-together' ),
				'below_excerpt' => esc_html__( 'Under the excerpt', 'woo-bought-together' ),
				'below_meta'    => esc_html__( 'Under the meta', 'woo-bought-together' ),
				'below_summary' => esc_html__( 'Under summary', 'woo-bought-together' ),
				'none'          => esc_html__( 'None (hide it)', 'woo-bought-together' ),
			] );
		}

		function available_variation( $data, $variable, $variation ) {
			if ( $image_id = $variation->get_image_id() ) {
				$data['woobt_image'] = wp_get_attachment_image( $image_id, self::$image_size );
			}

			$items = self::get_rule_items( $variation->get_id(), 'available_variation' );

			if ( ! empty( $items ) ) {
				$data['woobt_items'] = 'yes';
			} else {
				$data['woobt_items'] = 'no';
			}

			return $data;
		}

		function woovr_data_attributes( $attributes, $variation ) {
			if ( $image_id = $variation->get_image_id() ) {
				$attributes['woobt_image'] = wp_get_attachment_image( $image_id, self::$image_size );
			}

			return $attributes;
		}

		function register_settings() {
			// settings
			register_setting( 'woobt_settings', 'woobt_settings' );

			// rules
			register_setting( 'woobt_rules', 'woobt_rules' );

			// localization
			register_setting( 'woobt_localization', 'woobt_localization' );
		}

		function admin_menu() {
			add_submenu_page( 'wpclever', esc_html__( 'WPC Frequently Bought Together', 'woo-bought-together' ), esc_html__( 'Bought Together', 'woo-bought-together' ), 'manage_options', 'wpclever-woobt', [
				$this,
				'admin_menu_content'
			] );
		}

		function admin_menu_content() {
			add_thickbox();
			$active_tab = sanitize_key( $_GET['tab'] ?? 'settings' );
			?>
            <div class="wpclever_settings_page wrap">
                <div class="wpclever_settings_page_header">
                    <a class="wpclever_settings_page_header_logo" href="https://wpclever.net/"
                       target="_blank" title="Visit wpclever.net"></a>
                    <div class="wpclever_settings_page_header_text">
                        <div class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Frequently Bought Together', 'woo-bought-together' ) . ' ' . esc_html( WOOBT_VERSION ) . ' ' . ( defined( 'WOOBT_PREMIUM' ) ? '<span class="premium" style="display: none">' . esc_html__( 'Premium', 'woo-bought-together' ) . '</span>' : '' ); ?></div>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( /* translators: stars */ esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'woo-bought-together' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WOOBT_REVIEWS ); ?>"
                                   target="_blank"><?php esc_html_e( 'Reviews', 'woo-bought-together' ); ?></a> |
                                <a href="<?php echo esc_url( WOOBT_CHANGELOG ); ?>"
                                   target="_blank"><?php esc_html_e( 'Changelog', 'woo-bought-together' ); ?></a> |
                                <a href="<?php echo esc_url( WOOBT_DISCUSSION ); ?>"
                                   target="_blank"><?php esc_html_e( 'Discussion', 'woo-bought-together' ); ?></a>
                            </p>
                        </div>
                    </div>
                </div>
                <h2></h2>
				<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
                    <div class="notice notice-success is-dismissible">
                        <p><?php esc_html_e( 'Settings updated.', 'woo-bought-together' ); ?></p>
                    </div>
				<?php } ?>
                <div class="wpclever_settings_page_nav">
                    <h2 class="nav-tab-wrapper">
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woobt&tab=settings' ) ); ?>"
                           class="<?php echo esc_attr( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
							<?php esc_html_e( 'Settings', 'woo-bought-together' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woobt&tab=rules' ) ); ?>"
                           class="<?php echo esc_attr( $active_tab === 'rules' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
							<?php esc_html_e( 'Smart Rules', 'woo-bought-together' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woobt&tab=localization' ) ); ?>"
                           class="<?php echo esc_attr( $active_tab === 'localization' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
							<?php esc_html_e( 'Localization', 'woo-bought-together' ); ?>
                        </a>
                        <!--
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woobt&tab=tools' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'tools' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
                            <?php esc_html_e( 'Tools', 'woo-bought-together' ); ?>
                        </a>
                        -->
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woobt&tab=premium' ) ); ?>"
                           class="<?php echo esc_attr( $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>"
                           style="color: #c9356e">
							<?php esc_html_e( 'Premium Version', 'woo-bought-together' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-kit' ) ); ?>"
                           class="nav-tab">
							<?php esc_html_e( 'Essential Kit', 'woo-bought-together' ); ?>
                        </a>
                    </h2>
                </div>
                <div class="wpclever_settings_page_content">
					<?php if ( $active_tab === 'settings' ) {
						$pricing               = WPCleverWoobt_Helper()->get_setting( 'pricing', 'sale_price' );
						$default               = WPCleverWoobt_Helper()->get_setting( 'default', [ 'default' ] );
						$default_limit         = WPCleverWoobt_Helper()->get_setting( 'default_limit', '5' );
						$default_price         = WPCleverWoobt_Helper()->get_setting( 'default_price', '100%' );
						$default_discount      = WPCleverWoobt_Helper()->get_setting( 'default_discount', '0' );
						$layout                = WPCleverWoobt_Helper()->get_setting( 'layout', 'default' );
						$atc_button            = WPCleverWoobt_Helper()->get_setting( 'atc_button', 'main' );
						$show_this_item        = WPCleverWoobt_Helper()->get_setting( 'show_this_item', 'yes' );
						$exclude_unpurchasable = WPCleverWoobt_Helper()->get_setting( 'exclude_unpurchasable', 'no' );
						$show_thumb            = WPCleverWoobt_Helper()->get_setting( 'show_thumb', 'yes' );
						$show_price            = WPCleverWoobt_Helper()->get_setting( 'show_price', 'yes' );
						$show_description      = WPCleverWoobt_Helper()->get_setting( 'show_description', 'no' );
						$plus_minus            = WPCleverWoobt_Helper()->get_setting( 'plus_minus', 'no' );
						$variations_selector   = WPCleverWoobt_Helper()->get_setting( 'variations_selector', 'default' );
						$selector_interface    = WPCleverWoobt_Helper()->get_setting( 'selector_interface', 'unset' );
						$link                  = WPCleverWoobt_Helper()->get_setting( 'link', 'yes' );
						$change_image          = WPCleverWoobt_Helper()->get_setting( 'change_image', 'yes' );
						$change_price          = WPCleverWoobt_Helper()->get_setting( 'change_price', 'yes' );
						$counter               = WPCleverWoobt_Helper()->get_setting( 'counter', 'individual' );
						$responsive            = WPCleverWoobt_Helper()->get_setting( 'responsive', 'yes' );
						$cart_quantity         = WPCleverWoobt_Helper()->get_setting( 'cart_quantity', 'yes' );
						?>
                        <form method="post" action="options.php">
                            <table class="form-table">
                                <tr class="heading">
                                    <th colspan="2">
										<?php esc_html_e( 'General', 'woo-bought-together' ); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Pricing method', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label> <select name="woobt_settings[pricing]">
                                                <option value="sale_price" <?php selected( $pricing, 'sale_price' ); ?>><?php esc_html_e( 'from Sale price', 'woo-bought-together' ); ?></option>
                                                <option value="regular_price" <?php selected( $pricing, 'regular_price' ); ?>><?php esc_html_e( 'from Regular price', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                        <span class="description"><?php esc_html_e( 'Calculate prices from the sale price (default) or regular price of products.', 'woo-bought-together' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Smart rules', 'woo-bought-together' ); ?></th>
                                    <td>
                                        You can configure advanced rules for multiple Bought Together products
                                        at once with the Smart Rules
                                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woobt&tab=rules' ) ); ?>">here</a>.
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Default products', 'woo-bought-together' ); ?></th>
                                    <td>
                                                <span class="description"><?php esc_html_e( 'Choose which to be used as default Bought Together for products with no item list  specified individually or no Smart Rules applicable.', 'woo-bought-together' ); ?> You can use
                                                    <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=woo-bought-together&TB_iframe=true&width=800&height=550' ) ); ?>"
                                                       class="thickbox" title="WPC Custom Related Products">WPC Custom Related Products</a> or
                                                    <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=wpc-smart-linked-products&TB_iframe=true&width=800&height=550' ) ); ?>"
                                                       class="thickbox" title="WPC Smart Linked Products">WPC Smart Linked Products</a> plugin to configure related/upsells/cross-sells in bulk with smart conditions.</span>
										<?php
										// backward compatibility before 5.1.1
										if ( ! is_array( $default ) ) {
											switch ( (string) $default ) {
												case 'upsells':
													$default = [ 'upsells' ];
													break;
												case 'related':
													$default = [ 'related' ];
													break;
												case 'related_upsells':
													$default = [ 'upsells', 'related' ];
													break;
												case 'none':
													$default = [];
													break;
												default:
													$default = [];
											}
										}
										?>
                                        <div class="woobt_inner_lines" style="margin-top: 10px">
                                            <div class="woobt_inner_line">
                                                <div class="woobt_inner_value">
                                                    <input type="hidden" name="woobt_settings[default][]"
                                                           value="default" checked/>
                                                    <label><input type="checkbox"
                                                                  name="woobt_settings[default][]"
                                                                  value="related" <?php echo esc_attr( in_array( 'related', $default ) ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Related products', 'woo-bought-together' ); ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="woobt_inner_line">
                                                <div class="woobt_inner_value">
                                                    <label><input type="checkbox"
                                                                  name="woobt_settings[default][]"
                                                                  value="upsells" <?php echo esc_attr( in_array( 'upsells', $default ) ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Upsells products', 'woo-bought-together' ); ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="woobt_inner_line">
                                                <div class="woobt_inner_value">
                                                    <label><input type="checkbox"
                                                                  name="woobt_settings[default][]"
                                                                  value="crosssells" <?php echo esc_attr( in_array( 'crosssells', $default ) ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Cross-sells products', 'woo-bought-together' ); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="woobt_show_if_default_products woobt_inner_lines"
                                             style="margin-top: 10px">
                                            <div class="woobt_inner_line">
                                                <div class="woobt_inner_label"><?php esc_html_e( 'Limit', 'woo-bought-together' ); ?></div>
                                                <div class="woobt_inner_value">
                                                    <label>
                                                        <input type="number" class="small-text"
                                                               name="woobt_settings[default_limit]"
                                                               value="<?php echo esc_attr( $default_limit ); ?>"/>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="woobt_inner_line">
                                                <div class="woobt_inner_label"><?php esc_html_e( 'New price', 'woo-bought-together' ); ?></div>
                                                <div class="woobt_inner_value">
                                                    <label>
                                                        <input type="text" class="small-text"
                                                               name="woobt_settings[default_price]"
                                                               value="<?php echo esc_attr( $default_price ); ?>"/>
                                                    </label>
                                                    <span class="description"><?php esc_html_e( 'Set a new price for each product using a number (eg. "49") or percentage (eg. "90%" of original price).', 'woo-bought-together' ); ?></span>
                                                </div>
                                            </div>
                                            <div class="woobt_inner_line">
                                                <div class="woobt_inner_label"><?php esc_html_e( 'Discount', 'woo-bought-together' ); ?></div>
                                                <div class="woobt_inner_value">
                                                    <label>
                                                        <input type="number" class="small-text"
                                                               name="woobt_settings[default_discount]"
                                                               value="<?php echo esc_attr( $default_discount ); ?>"/>
                                                    </label>%.
                                                    <span class="description"><?php esc_html_e( 'Discount for the main product when buying at least one product in this list.', 'woo-bought-together' ); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Layout', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label> <select name="woobt_settings[layout]">
                                                <option value="default" <?php selected( $layout, 'default' ); ?>><?php esc_html_e( 'List', 'woo-bought-together' ); ?></option>
                                                <option value="compact" <?php selected( $layout, 'compact' ); ?>><?php esc_html_e( 'Compact', 'woo-bought-together' ); ?></option>
                                                <option value="separate" <?php selected( $layout, 'separate' ); ?>><?php esc_html_e( 'Separate images', 'woo-bought-together' ); ?></option>
                                                <option value="grid-2" <?php selected( $layout, 'grid-2' ); ?>><?php esc_html_e( 'Grid - 2 columns', 'woo-bought-together' ); ?></option>
                                                <option value="grid-3" <?php selected( $layout, 'grid-3' ); ?>><?php esc_html_e( 'Grid - 3 columns', 'woo-bought-together' ); ?></option>
                                                <option value="grid-4" <?php selected( $layout, 'grid-4' ); ?>><?php esc_html_e( 'Grid - 4 columns', 'woo-bought-together' ); ?></option>
                                                <option value="carousel-2" <?php selected( $layout, 'carousel-2' ); ?>><?php esc_html_e( 'Carousel - 2 columns', 'woo-bought-together' ); ?></option>
                                                <option value="carousel-3" <?php selected( $layout, 'carousel-3' ); ?>><?php esc_html_e( 'Carousel - 3 columns', 'woo-bought-together' ); ?></option>
                                                <option value="carousel-4" <?php selected( $layout, 'carousel-4' ); ?>><?php esc_html_e( 'Carousel - 4 columns', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Position', 'woo-bought-together' ); ?></th>
                                    <td>
										<?php
										$position = apply_filters( 'woobt_position', WPCleverWoobt_Helper()->get_setting( 'position', apply_filters( 'woobt_default_position', 'before' ) ) );

										if ( is_array( self::$positions ) && ( count( self::$positions ) > 0 ) ) {
											echo '<select name="woobt_settings[position]">';

											foreach ( self::$positions as $k => $p ) {
												echo '<option value="' . esc_attr( $k ) . '" ' . ( $k === $position ? 'selected' : '' ) . '>' . esc_html( $p ) . '</option>';
											}

											echo '</select>';
										}
										?>
                                        <p class="description"><?php esc_html_e( 'Choose the position to show the products list. You also can use the shortcode [woobt] to show the list where you want.', 'woo-bought-together' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Add to cart button', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <select name="woobt_settings[atc_button]" class="woobt_atc_button">
                                                <option value="main" <?php selected( $atc_button, 'main' ); ?>><?php esc_html_e( 'Main product\'s button', 'woo-bought-together' ); ?></option>
                                                <option value="separate" <?php selected( $atc_button, 'separate' ); ?>><?php esc_html_e( 'Separate buttons', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Show "this item"', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <select name="woobt_settings[show_this_item]"
                                                    class="woobt_show_this_item">
                                                <option value="yes" <?php selected( $show_this_item, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                                                <option value="no" <?php selected( $show_this_item, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                        <span class="description"><?php esc_html_e( '"This item" cannot be hidden if "Separate buttons" is in use for the Add to Cart button.', 'woo-bought-together' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Exclude unpurchasable', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label> <select name="woobt_settings[exclude_unpurchasable]">
                                                <option value="yes" <?php selected( $exclude_unpurchasable, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                                                <option value="no" <?php selected( $exclude_unpurchasable, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                        <span class="description"><?php esc_html_e( 'Exclude unpurchasable products from the list.', 'woo-bought-together' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Show thumbnail', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label> <select name="woobt_settings[show_thumb]">
                                                <option value="yes" <?php selected( $show_thumb, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                                                <option value="no" <?php selected( $show_thumb, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Show price', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label> <select name="woobt_settings[show_price]">
                                                <option value="yes" <?php selected( $show_price, 'yes' ); ?>><?php esc_html_e( 'Price', 'woo-bought-together' ); ?></option>
                                                <option value="total" <?php selected( $show_price, 'total' ); ?>><?php esc_html_e( 'Total', 'woo-bought-together' ); ?></option>
                                                <option value="no" <?php selected( $show_price, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Show short description', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label> <select name="woobt_settings[show_description]">
                                                <option value="yes" <?php selected( $show_description, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                                                <option value="no" <?php selected( $show_description, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Show plus/minus button', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label> <select name="woobt_settings[plus_minus]">
                                                <option value="yes" <?php selected( $plus_minus, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                                                <option value="no" <?php selected( $plus_minus, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                        <span class="description"><?php esc_html_e( 'Show the plus/minus button for the quantity input.', 'woo-bought-together' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Variations selector', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <select name="woobt_settings[variations_selector]"
                                                    class="woobt_variations_selector">
                                                <option value="default" <?php selected( $variations_selector, 'default' ); ?>><?php esc_html_e( 'Default', 'woo-bought-together' ); ?></option>
                                                <option value="woovr" <?php selected( $variations_selector, 'woovr' ); ?>><?php esc_html_e( 'Use WPC Variations Radio Buttons', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                        <p class="description">If you choose "Use WPC Variations Radio Buttons",
                                            please install
                                            <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=wpc-variations-radio-buttons&TB_iframe=true&width=800&height=550' ) ); ?>"
                                               class="thickbox" title="WPC Variations Radio Buttons">WPC
                                                Variations Radio Buttons</a> to make it work.
                                        </p>
                                        <div class="woobt_show_if_woovr" style="margin-top: 10px">
											<?php esc_html_e( 'Selector interface', 'woo-bought-together' ); ?>
                                            <label> <select name="woobt_settings[selector_interface]">
                                                    <option value="unset" <?php selected( $selector_interface, 'unset' ); ?>><?php esc_html_e( 'Unset', 'woo-bought-together' ); ?></option>
                                                    <option value="ddslick" <?php selected( $selector_interface, 'ddslick' ); ?>><?php esc_html_e( 'ddSlick', 'woo-bought-together' ); ?></option>
                                                    <option value="select2" <?php selected( $selector_interface, 'select2' ); ?>><?php esc_html_e( 'Select2', 'woo-bought-together' ); ?></option>
                                                    <option value="default" <?php selected( $selector_interface, 'default' ); ?>><?php esc_html_e( 'Radio buttons', 'woo-bought-together' ); ?></option>
                                                    <option value="select" <?php selected( $selector_interface, 'select' ); ?>><?php esc_html_e( 'HTML select tag', 'woo-bought-together' ); ?></option>
                                                    <option value="grid-2" <?php selected( $selector_interface, 'grid-2' ); ?>><?php esc_html_e( 'Grid - 2 columns', 'woo-bought-together' ); ?></option>
                                                    <option value="grid-3" <?php selected( $selector_interface, 'grid-3' ); ?>><?php esc_html_e( 'Grid - 3 columns', 'woo-bought-together' ); ?></option>
                                                    <option value="grid-4" <?php selected( $selector_interface, 'grid-4' ); ?>><?php esc_html_e( 'Grid - 4 columns', 'woo-bought-together' ); ?></option>
                                                </select> </label>
                                            <p class="description"><?php esc_html_e( 'Choose a selector interface that apply for variations of frequently bought together products only.', 'woo-bought-together' ); ?></p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Link to individual product', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label> <select name="woobt_settings[link]">
                                                <option value="yes" <?php selected( $link, 'yes' ); ?>><?php esc_html_e( 'Yes, open in the same tab', 'woo-bought-together' ); ?></option>
                                                <option value="yes_blank" <?php selected( $link, 'yes_blank' ); ?>><?php esc_html_e( 'Yes, open in the new tab', 'woo-bought-together' ); ?></option>
                                                <option value="yes_popup" <?php selected( $link, 'yes_popup' ); ?>><?php esc_html_e( 'Yes, open quick view popup', 'woo-bought-together' ); ?></option>
                                                <option value="no" <?php selected( $link, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                        <p class="description">If you choose "Open quick view popup", please
                                            install
                                            <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=woo-smart-quick-view&TB_iframe=true&width=800&height=550' ) ); ?>"
                                               class="thickbox" title="WPC Smart Quick View">WPC Smart Quick
                                                View</a> to make it work.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Change image', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label> <select name="woobt_settings[change_image]">
                                                <option value="yes" <?php selected( $change_image, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                                                <option value="no" <?php selected( $change_image, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                        <span class="description"><?php esc_html_e( 'Change the main product image when choosing the variation of variable products.', 'woo-bought-together' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Change price', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <select name="woobt_settings[change_price]"
                                                    class="woobt_change_price">
                                                <option value="yes" <?php selected( $change_price, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                                                <option value="yes_custom" <?php selected( $change_price, 'yes_custom' ); ?>><?php esc_html_e( 'Yes, custom selector', 'woo-bought-together' ); ?></option>
                                                <option value="no" <?php selected( $change_price, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                            </select> </label> <label>
                                            <input type="text" name="woobt_settings[change_price_custom]"
                                                   value="<?php echo WPCleverWoobt_Helper()->get_setting( 'change_price_custom', '.summary > .price' ); ?>"
                                                   placeholder=".summary > .price"
                                                   class="woobt_change_price_custom"/>
                                        </label>
                                        <p class="description"><?php esc_html_e( 'Change the main product price when choosing the variation or quantity of products. It uses JavaScript to change product price so it is very dependent on theme’s HTML. If it cannot find and update the product price, please contact us and we can help you find the right selector or adjust the JS file.', 'woo-bought-together' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Counter', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label> <select name="woobt_settings[counter]">
                                                <option value="individual" <?php selected( $counter, 'individual' ); ?>><?php esc_html_e( 'Count the individual products', 'woo-bought-together' ); ?></option>
                                                <option value="qty" <?php selected( $counter, 'qty' ); ?>><?php esc_html_e( 'Count the product quantities', 'woo-bought-together' ); ?></option>
                                                <option value="hide" <?php selected( $counter, 'hide' ); ?>><?php esc_html_e( 'Hide', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                        <span class="description"><?php esc_html_e( 'Counter on the add to cart button.', 'woo-bought-together' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Responsive', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label> <select name="woobt_settings[responsive]">
                                                <option value="yes" <?php selected( $responsive, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                                                <option value="no" <?php selected( $responsive, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                        <span class="description"><?php esc_html_e( 'Change the layout for small screen devices.', 'woo-bought-together' ); ?></span>
                                    </td>
                                </tr>
                                <tr class="heading">
                                    <th colspan="2">
										<?php esc_html_e( 'Cart & Checkout', 'woo-bought-together' ); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Change quantity', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label> <select name="woobt_settings[cart_quantity]">
                                                <option value="yes" <?php selected( $cart_quantity, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                                                <option value="no" <?php selected( $cart_quantity, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                            </select> </label>
                                        <span class="description"><?php esc_html_e( 'Buyer can change the quantity of associated products or not?', 'woo-bought-together' ); ?></span>
                                    </td>
                                </tr>
                                <tr class="heading">
                                    <th colspan="2">
										<?php esc_html_e( 'Search', 'woo-bought-together' ); ?>
                                    </th>
                                </tr>
								<?php self::search_settings(); ?>
                                <tr class="submit">
                                    <th colspan="2">
										<?php settings_fields( 'woobt_settings' ); ?><?php submit_button(); ?>
                                    </th>
                                </tr>
                            </table>
                        </form>
					<?php } elseif ( $active_tab === 'rules' ) {
						self::rules( 'woobt_rules', self::$rules );
					} elseif ( $active_tab === 'localization' ) { ?>
                        <form method="post" action="options.php">
                            <table class="form-table">
                                <tr class="heading">
                                    <th scope="row"><?php esc_html_e( 'General', 'woo-bought-together' ); ?></th>
                                    <td>
										<?php esc_html_e( 'Leave blank to use the default text and its equivalent translation in multiple languages.', 'woo-bought-together' ); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'This item', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="text" name="woobt_localization[this_item]"
                                                   class="regular-text"
                                                   value="<?php echo esc_attr( WPCleverWoobt_Helper()->localization( 'this_item' ) ); ?>"
                                                   placeholder="<?php esc_attr_e( 'This item:', 'woo-bought-together' ); ?>"/>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Choose an attribute', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="text" name="woobt_localization[choose]"
                                                   class="regular-text"
                                                   value="<?php echo esc_attr( WPCleverWoobt_Helper()->localization( 'choose' ) ); ?>"
                                                   placeholder="<?php /* translators: attribute name */
											       esc_attr_e( 'Choose %s', 'woo-bought-together' ); ?>"/>
                                        </label>
                                        <span class="description"><?php /* translators: attribute name */
											esc_html_e( 'Use %s to show the attribute name.', 'woo-bought-together' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Clear', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="text" name="woobt_localization[clear]"
                                                   class="regular-text"
                                                   value="<?php echo esc_attr( WPCleverWoobt_Helper()->localization( 'clear' ) ); ?>"
                                                   placeholder="<?php esc_attr_e( 'Clear', 'woo-bought-together' ); ?>"/>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Additional price', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="text" name="woobt_localization[additional]"
                                                   class="regular-text"
                                                   value="<?php echo esc_attr( WPCleverWoobt_Helper()->localization( 'additional' ) ); ?>"
                                                   placeholder="<?php esc_attr_e( 'Additional price:', 'woo-bought-together' ); ?>"/>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Total price', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="text" name="woobt_localization[total]"
                                                   class="regular-text"
                                                   value="<?php echo esc_attr( WPCleverWoobt_Helper()->localization( 'total' ) ); ?>"
                                                   placeholder="<?php esc_attr_e( 'Total:', 'woo-bought-together' ); ?>"/>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Associated', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="text" name="woobt_localization[associated]"
                                                   class="regular-text"
                                                   value="<?php echo esc_attr( WPCleverWoobt_Helper()->localization( 'associated' ) ); ?>"
                                                   placeholder="<?php /* translators: product name */
											       esc_attr_e( '(bought together %s)', 'woo-bought-together' ); ?>"/>
                                        </label> <span class="description"><?php /* translators: product name */
											esc_html_e( 'The text behind associated products. Use "%s" for the main product name.', 'woo-bought-together' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Add to cart', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="text" name="woobt_localization[add_to_cart]"
                                                   class="regular-text"
                                                   value="<?php echo esc_attr( WPCleverWoobt_Helper()->localization( 'add_to_cart' ) ); ?>"
                                                   placeholder="<?php esc_attr_e( 'Add to cart', 'woo-bought-together' ); ?>"/>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Add all to cart', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="text" name="woobt_localization[add_all_to_cart]"
                                                   class="regular-text"
                                                   value="<?php echo esc_attr( WPCleverWoobt_Helper()->localization( 'add_all_to_cart' ) ); ?>"
                                                   placeholder="<?php esc_attr_e( 'Add all to cart', 'woo-bought-together' ); ?>"/>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Default above text', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="text" name="woobt_localization[above_text]"
                                                   class="large-text"
                                                   value="<?php echo esc_attr( WPCleverWoobt_Helper()->localization( 'above_text' ) ); ?>"/>
                                        </label>
                                        <span class="description"><?php esc_html_e( 'The default text above products list. You can overwrite it for each product in product settings.', 'woo-bought-together' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Default under text', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="text" name="woobt_localization[under_text]"
                                                   class="large-text"
                                                   value="<?php echo esc_attr( WPCleverWoobt_Helper()->localization( 'under_text' ) ); ?>"/>
                                        </label>
                                        <span class="description"><?php esc_html_e( 'The default text under products list. You can overwrite it for each product in product settings.', 'woo-bought-together' ); ?></span>
                                    </td>
                                </tr>
                                <tr class="heading">
                                    <th colspan="2">
										<?php esc_html_e( 'Alert', 'woo-bought-together' ); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Require selection', 'woo-bought-together' ); ?></th>
                                    <td>
                                        <label>
                                            <input type="text" name="woobt_localization[alert_selection]"
                                                   class="large-text"
                                                   value="<?php echo esc_attr( WPCleverWoobt_Helper()->localization( 'alert_selection' ) ); ?>"
                                                   placeholder="<?php esc_attr_e( 'Please select a purchasable variation for [name] before adding this product to the cart.', 'woo-bought-together' ); ?>"/>
                                        </label>
                                    </td>
                                </tr>
                                <tr class="submit">
                                    <th colspan="2">
										<?php settings_fields( 'woobt_localization' ); ?><?php submit_button(); ?>
                                    </th>
                                </tr>
                            </table>
                        </form>
					<?php } elseif ( $active_tab == 'tools' ) { ?>
                        <table class="form-table">
                            <tr class="heading">
                                <th scope="row"><?php esc_html_e( 'Data Migration', 'woo-bought-together' ); ?></th>
                                <td>
									<?php esc_html_e( 'If selected products don\'t appear on the current version. Please try running Migrate tool.', 'woo-bought-together' ); ?>

									<?php
									echo '<p>';
									$num   = absint( $_GET['num'] ?? 50 );
									$paged = absint( $_GET['paged'] ?? 1 );

									if ( isset( $_GET['act'] ) && ( $_GET['act'] === 'migrate' ) ) {
										$args = [
											'post_type'      => 'product',
											'posts_per_page' => $num,
											'paged'          => $paged,
											'meta_query'     => [
												[
													'key'     => 'woobt_ids',
													'compare' => 'EXISTS'
												]
											]
										];

										$posts = get_posts( $args );

										if ( ! empty( $posts ) ) {
											foreach ( $posts as $post ) {
												$ids = get_post_meta( $post->ID, 'woobt_ids', true );

												if ( ! empty( $ids ) && is_string( $ids ) ) {
													$items     = explode( ',', $ids );
													$new_items = [];

													foreach ( $items as $item ) {
														$item_data = explode( '/', $item );
														$item_key  = WPCleverWoobt_Helper()->generate_key();
														$item_id   = absint( $item_data[0] ?? 0 );

														if ( $item_product = wc_get_product( $item_id ) ) {
															$item_sku   = $item_product->get_sku();
															$item_price = $item_data[1] ?? '100%';
															$item_qty   = (float) ( $item_data[2] ?? 1 );

															$new_items[ $item_key ] = [
																'id'    => $item_id,
																'sku'   => $item_sku,
																'price' => $item_price,
																'qty'   => $item_qty,
															];
														}
													}

													update_post_meta( $post->ID, 'woobt_ids', $new_items );
												}
											}

											echo '<span style="color: #2271b1; font-weight: 700">' . esc_html__( 'Migrating...', 'woo-bought-together' ) . '</span>';
											echo '<p class="description">' . esc_html__( 'Please wait until it has finished!', 'woo-bought-together' ) . '</p>';
											?>
                                            <script type="text/javascript">
                                                (function ($) {
                                                    $(function () {
                                                        setTimeout(function () {
                                                            window.location.href = '<?php echo admin_url( 'admin.php?page=wpclever-woobt&tab=tools&act=migrate&num=' . $num . '&paged=' . ( $paged + 1 ) ); ?>';
                                                        }, 1000);
                                                    });
                                                })(jQuery);
                                            </script>
										<?php } else {
											echo '<span style="color: #2271b1; font-weight: 700">' . esc_html__( 'Finished!', 'woo-bought-together' ) . '</span>';
										}
									} else {
										echo '<a class="button btn" href="' . esc_url( admin_url( 'admin.php?page=wpclever-woobt&tab=tools&act=migrate' ) ) . '">' . esc_html__( 'Migrate', 'woo-bought-together' ) . '</a>';
									}
									echo '</p>';
									?>
                                </td>
                            </tr>
                        </table>
					<?php } elseif ( $active_tab == 'premium' ) { ?>
                        <div class="wpclever_settings_page_content_text">
                            <p>Get the Premium Version just $29!
                                <a href="https://wpclever.net/downloads/frequently-bought-together?utm_source=pro&utm_medium=woobt&utm_campaign=wporg"
                                   target="_blank">https://wpclever.net/downloads/frequently-bought-together</a>
                            </p>
                            <p><strong>Extra features for Premium Version:</strong></p>
                            <ul style="margin-bottom: 0">
                                <li>- Add a variable product or a specific variation of a product.</li>
                                <li>- Use Smart Rules to configure multiple bought-together products at once.</li>
                                <li>- Insert heading/paragraph into products list.</li>
                                <li>- Use the carousel layout.</li>
                                <li>- Get the lifetime update & premium support.</li>
                            </ul>
                        </div>
					<?php } ?>
                </div><!-- /.wpclever_settings_page_content -->
                <div class="wpclever_settings_page_suggestion">
                    <div class="wpclever_settings_page_suggestion_label">
                        <span class="dashicons dashicons-yes-alt"></span> Suggestion
                    </div>
                    <div class="wpclever_settings_page_suggestion_content">
                        <div>
                            To display custom engaging real-time messages on any wished positions, please
                            install
                            <a href="https://wordpress.org/plugins/wpc-smart-messages/" target="_blank">WPC
                                Smart Messages</a> plugin. It's free!
                        </div>
                        <div>
                            Wanna save your precious time working on variations? Try our brand-new free plugin
                            <a href="https://wordpress.org/plugins/wpc-variation-bulk-editor/" target="_blank">WPC
                                Variation Bulk Editor</a> and
                            <a href="https://wordpress.org/plugins/wpc-variation-duplicator/" target="_blank">WPC
                                Variation Duplicator</a>.
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}

		function rules( $name = 'woobt_rules', $rules = [] ) {
			?>
            <form method="post" action="options.php">
                <table class="form-table">
                    <tr>
                        <td>
							<?php esc_html_e( 'Our plugin checks rules from the top down the list. When there are products that satisfy more than 1 rule, the first rule on top will be prioritized. Please make sure you put the rules in the order of the most to the least prioritized.', 'woo-bought-together' ); ?>
                            <p class="description" style="color: #c9356e">
                                * This feature only available on Premium Version. Click
                                <a href="https://wpclever.net/downloads/frequently-bought-together?utm_source=pro&utm_medium=woobt&utm_campaign=wporg"
                                   target="_blank">here</a> to buy, just $29!
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="woobt_rules">
								<?php
								$rules = array_filter( $rules );

								if ( ! empty( $rules ) ) {
									foreach ( $rules as $key => $rule ) {
										self::rule( $key, $name, $rule, false );
									}
								}
								?>
                            </div>
                            <div class="woobt_add_rule">
                                <div>
                                    <a href="#" class="woobt_new_rule button"
                                       data-name="<?php echo esc_attr( $name ); ?>">
										<?php esc_html_e( '+ Add rule', 'woo-bought-together' ); ?>
                                    </a> <a href="#" class="woobt_expand_all">
										<?php esc_html_e( 'Expand All', 'woo-bought-together' ); ?>
                                    </a> <a href="#" class="woobt_collapse_all">
										<?php esc_html_e( 'Collapse All', 'woo-bought-together' ); ?>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="submit">
                        <th colspan="2">
							<?php settings_fields( $name ); ?><?php submit_button(); ?>
                        </th>
                    </tr>
                </table>
            </form>
			<?php
		}

		function rule( $key = '', $name = 'woobt_rules', $rule = null, $open = false ) {
			if ( empty( $key ) || is_numeric( $key ) ) {
				$key = WPCleverWoobt_Helper()->generate_key();
			}

			$rule_name         = $rule['name'] ?? '';
			$active            = $rule['active'] ?? 'yes';
			$apply             = $rule['apply'] ?? 'all';
			$apply_products    = (array) ( $rule['apply_products'] ?? [] );
			$apply_terms       = (array) ( $rule['apply_terms'] ?? [] );
			$apply_combination = (array) ( $rule['apply_combination'] ?? [] );
			$get               = $rule['get'] ?? 'all';
			$get_products      = (array) ( $rule['get_products'] ?? [] );
			$get_terms         = (array) ( $rule['get_terms'] ?? [] );
			$get_combination   = (array) ( $rule['get_combination'] ?? [] );
			$get_limit         = absint( $rule['get_limit'] ?? 3 );
			$get_orderby       = $rule['get_orderby'] ?? 'default';
			$get_order         = $rule['get_order'] ?? 'default';
			$price             = $rule['price'] ?? '100%';
			$discount          = $rule['discount'] ?? '0';
			$before_text       = $rule['before_text'] ?? '';
			$after_text        = $rule['after_text'] ?? '';
			$input_name        = $name . '[' . $key . ']';
			$rule_class        = 'woobt_rule' . ( $open ? ' open' : '' ) . ( $active === 'yes' ? ' active' : '' );
			?>
            <div class="<?php echo esc_attr( $rule_class ); ?>" data-key="<?php echo esc_attr( $key ); ?>">
                <input type="hidden" name="<?php echo esc_attr( $input_name . '[key]' ); ?>"
                       value="<?php echo esc_attr( $key ); ?>"/>
                <div class="woobt_rule_heading">
                    <span class="woobt_rule_move"></span>
                    <span class="woobt_rule_label"><span
                                class="woobt_rule_name"><?php echo esc_html( $rule_name ?: '#' . $key ); ?></span><span
                                class="woobt_rule_apply"></span><span class="woobt_rule_get"></span></span>
                    <a href="#" class="woobt_rule_duplicate"
                       data-name="<?php echo esc_attr( $name ); ?>"><?php esc_html_e( 'duplicate', 'woo-bought-together' ); ?></a>
                    <a href="#"
                       class="woobt_rule_remove"><?php esc_html_e( 'remove', 'woo-bought-together' ); ?></a>
                </div>
                <div class="woobt_rule_content">
                    <div class="woobt_tr woobt_tr_stripes">
                        <div class="woobt_th"><?php esc_html_e( 'Active', 'woo-bought-together' ); ?></div>
                        <div class="woobt_td woobt_rule_td">
                            <label><select name="<?php echo esc_attr( $input_name . '[active]' ); ?>"
                                           class="woobt_rule_active">
                                    <option value="yes" <?php selected( $active, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                                    <option value="no" <?php selected( $active, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                </select></label>
                        </div>
                    </div>
                    <div class="woobt_tr">
                        <div class="woobt_th"><?php esc_html_e( 'Name', 'woo-bought-together' ); ?></div>
                        <div class="woobt_td woobt_rule_td">
                            <label>
                                <input type="text" class="regular-text woobt_rule_name_val"
                                       name="<?php echo esc_attr( $input_name . '[name]' ); ?>"
                                       value="<?php echo esc_attr( $rule_name ); ?>"/>
                            </label>
                            <span class="description"><?php esc_html_e( 'For management only.', 'woo-bought-together' ); ?></span>
                        </div>
                    </div>
                    <div class="woobt_tr">
                        <div class="woobt_th woobt_th_full">
							<?php esc_html_e( 'Add FBT products to which?', 'woo-bought-together' ); ?>
                        </div>
                    </div>
					<?php self::source( $name, $key, $apply, $apply_products, $apply_terms, $apply_combination, 'apply' ); ?>
                    <div class="woobt_tr">
                        <div class="woobt_th woobt_th_full">
							<?php esc_html_e( 'Define applicable FBT products:', 'woo-bought-together' ); ?>
                        </div>
                    </div>
					<?php self::source( $name, $key, $get, $get_products, $get_terms, $get_combination, 'get', $get_limit, $get_orderby, $get_order ); ?>
                    <div class="woobt_tr">
                        <div class="woobt_th"><?php esc_html_e( 'New price', 'woo-bought-together' ); ?></div>
                        <div class="woobt_td woobt_rule_td">
                            <label>
                                <input type="text" class="small-text"
                                       name="<?php echo esc_attr( $input_name . '[price]' ); ?>"
                                       value="<?php echo esc_attr( $price ); ?>"/>
                            </label>
                            <span class="description"><?php esc_html_e( 'Set a new price for each product using a number (eg. "49") or percentage (eg. "90%" of original price).', 'woo-bought-together' ); ?></span>
                        </div>
                    </div>
                    <div class="woobt_tr">
                        <div class="woobt_th"><?php esc_html_e( 'Discount', 'woo-bought-together' ); ?></div>
                        <div class="woobt_td woobt_rule_td">
                            <label>
                                <input type="text" class="small-text"
                                       name="<?php echo esc_attr( $input_name . '[discount]' ); ?>"
                                       value="<?php echo esc_attr( $discount ); ?>"/>
                            </label>%.
                            <span class="description"><?php esc_html_e( 'Discount for the main product when buying at least one product in this list.', 'woo-bought-together' ); ?></span>
                        </div>
                    </div>
                    <div class="woobt_tr">
                        <div class="woobt_th"><?php esc_html_e( 'Above text', 'woo-bought-together' ); ?></div>
                        <div class="woobt_td woobt_rule_td">
                            <label>
                                <textarea name="<?php echo esc_attr( $input_name . '[before_text]' ); ?>" rows="1"
                                          style="width: 100%"><?php echo esc_textarea( $before_text ); ?></textarea>
                            </label>
                        </div>
                    </div>
                    <div class="woobt_tr">
                        <div class="woobt_th"><?php esc_html_e( 'Under text', 'woo-bought-together' ); ?></div>
                        <div class="woobt_td woobt_rule_td">
                            <label>
                                <textarea name="<?php echo esc_attr( $input_name . '[after_text]' ); ?>" rows="1"
                                          style="width: 100%"><?php echo esc_textarea( $after_text ); ?></textarea>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}

		function source( $name, $key, $apply, $products, $terms, $combination, $type = 'apply', $get_limit = null, $get_orderby = null, $get_order = null ) {
			$input_name = $name . '[' . $key . ']';
			?>
            <div class="woobt_tr">
                <div class="woobt_th"><?php esc_html_e( 'Source', 'woo-bought-together' ); ?></div>
                <div class="woobt_td woobt_rule_td">
                    <label>
                        <select class="woobt_source_selector woobt_source_selector_<?php echo esc_attr( $type ); ?>"
                                data-type="<?php echo esc_attr( $type ); ?>"
                                name="<?php echo esc_attr( $input_name . '[' . $type . ']' ); ?>">
                            <option value="all"><?php esc_html_e( 'All products', 'woo-bought-together' ); ?></option>
                            <option value="products" <?php selected( $apply, 'products' ); ?>><?php esc_html_e( 'Products', 'woo-bought-together' ); ?></option>
                            <option value="combination" <?php selected( $apply, 'combination' ); ?>><?php esc_html_e( 'Combined', 'woo-bought-together' ); ?></option>
							<?php
							$taxonomies = get_object_taxonomies( 'product', 'objects' );

							foreach ( $taxonomies as $taxonomy ) {
								echo '<option value="' . esc_attr( $taxonomy->name ) . '" ' . ( $apply === $taxonomy->name ? 'selected' : '' ) . '>' . esc_html( $taxonomy->label ) . '</option>';
							}
							?>
                        </select> </label>
					<?php if ( $type === 'get' ) { ?>
                        <span class="show_get hide_if_get_products">
										<span><?php esc_html_e( 'Limit', 'woo-bought-together' ); ?> <label>
<input type="number" min="1" max="50" name="<?php echo esc_attr( $input_name . '[get_limit]' ); ?>"
       value="<?php echo esc_attr( $get_limit ); ?>"/>
</label></span>
										<span>
										<?php esc_html_e( 'Order by', 'woo-bought-together' ); ?> <label>
<select name="<?php echo esc_attr( $input_name . '[get_orderby]' ); ?>">
<option value="default" <?php selected( $get_orderby, 'default' ); ?>><?php esc_html_e( 'Default', 'woo-bought-together' ); ?></option>
<option value="none" <?php selected( $get_orderby, 'none' ); ?>><?php esc_html_e( 'None', 'woo-bought-together' ); ?></option>
<option value="ID" <?php selected( $get_orderby, 'ID' ); ?>><?php esc_html_e( 'ID', 'woo-bought-together' ); ?></option>
<option value="name" <?php selected( $get_orderby, 'name' ); ?>><?php esc_html_e( 'Name', 'woo-bought-together' ); ?></option>
<option value="type" <?php selected( $get_orderby, 'type' ); ?>><?php esc_html_e( 'Type', 'woo-bought-together' ); ?></option>
<option value="date" <?php selected( $get_orderby, 'date' ); ?>><?php esc_html_e( 'Date', 'woo-bought-together' ); ?></option>
<option value="price" <?php selected( $get_orderby, 'price' ); ?>><?php esc_html_e( 'Price', 'woo-bought-together' ); ?></option>
<option value="modified" <?php selected( $get_orderby, 'modified' ); ?>><?php esc_html_e( 'Modified', 'woo-bought-together' ); ?></option>
<option value="rand" <?php selected( $get_orderby, 'rand' ); ?>><?php esc_html_e( 'Random', 'woo-bought-together' ); ?></option>
</select>
</label>
									</span>
										<span><?php esc_html_e( 'Order', 'woo-bought-together' ); ?> <label>
<select name="<?php echo esc_attr( $input_name . '[get_order]' ); ?>">
<option value="default" <?php selected( $get_order, 'default' ); ?>><?php esc_html_e( 'Default', 'woo-bought-together' ); ?></option>
<option value="DESC" <?php selected( $get_order, 'DESC' ); ?>><?php esc_html_e( 'DESC', 'woo-bought-together' ); ?></option>
<option value="ASC" <?php selected( $get_order, 'ASC' ); ?>><?php esc_html_e( 'ASC', 'woo-bought-together' ); ?></option>
</select>
</label></span>
									</span>
					<?php } ?>
                </div>
            </div>
            <div class="woobt_tr hide_<?php echo esc_attr( $type ); ?> show_if_<?php echo esc_attr( $type ); ?>_products">
                <div class="woobt_th"><?php esc_html_e( 'Products', 'woo-bought-together' ); ?></div>
                <div class="woobt_td woobt_rule_td">
                    <label>
                        <select class="wc-product-search woobt-product-search"
                                name="<?php echo esc_attr( $input_name . '[' . $type . '_products][]' ); ?>"
                                multiple="multiple" data-sortable="1"
                                data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woo-bought-together' ); ?>"
                                data-action="woocommerce_json_search_products_and_variations">
							<?php
							if ( ! empty( $products ) ) {
								foreach ( $products as $_product_id ) {
									if ( $_product = wc_get_product( $_product_id ) ) {
										echo '<option value="' . esc_attr( $_product_id ) . '" selected>' . wp_kses_post( $_product->get_formatted_name() ) . '</option>';
									}
								}
							}
							?>
                        </select> </label>
                </div>
            </div>
            <div class="woobt_tr hide_<?php echo esc_attr( $type ); ?> show_if_<?php echo esc_attr( $type ); ?>_combination">
                <div class="woobt_th"><?php esc_html_e( 'Combined', 'woo-bought-together' ); ?></div>
                <div class="woobt_td woobt_rule_td">
                    <div class="woobt_combinations">
                        <p class="description"><?php esc_html_e( '* Configure to find products that match all listed conditions.', 'woo-bought-together' ); ?></p>
						<?php
						if ( ! empty( $combination ) ) {
							foreach ( $combination as $ck => $cmb ) {
								self::combination( $ck, $name, $cmb, $key, $type );
							}
						} else {
							self::combination( '', $name, null, $key, $type );
						}
						?>
                    </div>
                    <div class="woobt_add_combination">
                        <a class="woobt_new_combination" href="#" data-name="<?php echo esc_attr( $name ); ?>"
                           data-type="<?php echo esc_attr( $type ); ?>"><?php esc_attr_e( '+ Add condition', 'woo-bought-together' ); ?></a>
                    </div>
                </div>
            </div>
            <div class="woobt_tr show_<?php echo esc_attr( $type ); ?> hide_if_<?php echo esc_attr( $type ); ?>_all hide_if_<?php echo esc_attr( $type ); ?>_products hide_if_<?php echo esc_attr( $type ); ?>_combination">
                <div class="woobt_th woobt_<?php echo esc_attr( $type ); ?>_text"><?php esc_html_e( 'Terms', 'woo-bought-together' ); ?></div>
                <div class="woobt_td woobt_rule_td">
                    <label>
                        <select class="woobt_terms" data-type="<?php echo esc_attr( $type ); ?>"
                                name="<?php echo esc_attr( $input_name . '[' . $type . '_terms][]' ); ?>"
                                multiple="multiple"
                                data-<?php echo esc_attr( $apply ); ?>="<?php echo esc_attr( implode( ',', $terms ) ); ?>">
							<?php
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $at ) {
									if ( $term = get_term_by( 'slug', $at, $apply ) ) {
										echo '<option value="' . esc_attr( $at ) . '" selected>' . esc_html( self::get_term_name( $term, $apply ) ) . '</option>';
									}
								}
							}
							?>
                        </select> </label>
                </div>
            </div>
			<?php
		}

		function combination( $c_key = '', $name = 'woobt_rules', $combination = null, $key = '', $type = 'apply' ) {
			if ( empty( $c_key ) || is_numeric( $c_key ) ) {
				$c_key = WPCleverWoobt_Helper()->generate_key();
			}

			$apply   = $combination['apply'] ?? '';
			$compare = $combination['compare'] ?? 'is';
			$same    = $combination['same'] ?? '';
			$terms   = (array) ( $combination['terms'] ?? [] );
			$name    .= '[' . $key . '][' . $type . '_combination][' . $c_key . ']';
			?>
            <div class="woobt_combination">
                <span class="woobt_combination_remove">&times;</span>
                <span class="woobt_combination_selector_wrap">
                                    <label>
                                    <select class="woobt_combination_selector"
                                            name="<?php echo esc_attr( $name . '[apply]' ); ?>">
                                        <?php
                                        if ( $type === 'apply' ) {
	                                        echo '<option value="variation" ' . selected( $apply, 'variation', false ) . '>' . esc_html__( 'Variations only', 'woo-bought-together' ) . '</option>';
	                                        echo '<option value="not_variation" ' . selected( $apply, 'not_variation', false ) . '>' . esc_html__( 'Non-variation products', 'woo-bought-together' ) . '</option>';
                                        } elseif ( $type === 'get' ) {
	                                        echo '<option value="same" ' . selected( $apply, 'same', false ) . '>' . esc_html__( 'Has same', 'woo-bought-together' ) . '</option>';
                                        }

                                        $taxonomies = get_object_taxonomies( 'product', 'objects' );

                                        foreach ( $taxonomies as $taxonomy ) {
	                                        echo '<option value="' . esc_attr( $taxonomy->name ) . '" ' . ( $apply === $taxonomy->name ? 'selected' : '' ) . '>' . esc_html( $taxonomy->label ) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    </label>
                                </span> <span class="woobt_combination_compare_wrap">
                            <label> <select class="woobt_combination_compare"
                                            name="<?php echo esc_attr( $name . '[compare]' ); ?>">
                                <option value="is" <?php selected( $compare, 'is' ); ?>><?php esc_html_e( 'including', 'woo-bought-together' ); ?></option>
                                <option value="is_not" <?php selected( $compare, 'is_not' ); ?>><?php esc_html_e( 'excluding', 'woo-bought-together' ); ?></option>
                            </select> </label></span> <span class="woobt_combination_val_wrap">
                                    <label>
                                    <select class="woobt_combination_val woobt_apply_terms" multiple="multiple"
                                            name="<?php echo esc_attr( $name . '[terms][]' ); ?>">
                                        <?php
                                        if ( ! empty( $terms ) ) {
	                                        foreach ( $terms as $ct ) {
		                                        if ( $term = get_term_by( 'slug', $ct, $apply ) ) {
			                                        echo '<option value="' . esc_attr( $ct ) . '" selected>' . esc_html( $term->name ) . '</option>';
		                                        }
	                                        }
                                        }
                                        ?>
                                    </select>
                                    </label>
                                </span> <span class="woobt_combination_same_wrap"><label>
							<select name="<?php echo esc_attr( $name . '[same]' ); ?>">
								<?php foreach ( $taxonomies as $taxonomy ) {
									echo '<option value="' . esc_attr( $taxonomy->name ) . '" ' . selected( $same, $taxonomy->name, false ) . '>' . esc_html( $taxonomy->label ) . '</option>';
								} ?>
							</select></label></span>
            </div>
			<?php
		}

		function ajax_add_rule() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woobt-security' ) || ! current_user_can( 'manage_options' ) ) {
				die( 'Permissions check failed!' );
			}

			$rule      = [];
			$name      = sanitize_key( $_POST['name'] ?? 'woobt_rules' );
			$rule_data = $_POST['rule_data'] ?? '';

			if ( ! empty( $rule_data ) ) {
				$form_rule = [];
				parse_str( $rule_data, $form_rule );

				if ( isset( $form_rule[ $name ] ) && is_array( $form_rule[ $name ] ) ) {
					$rule = reset( $form_rule[ $name ] );
				}
			}

			self::rule( '', $name, $rule, true );
			wp_die();
		}

		function ajax_add_combination() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woobt-security' ) || ! current_user_can( 'manage_options' ) ) {
				die( 'Permissions check failed!' );
			}

			$key  = sanitize_key( $_POST['key'] ?? WPCleverWoobt_Helper()->generate_key() );
			$name = sanitize_key( $_POST['name'] ?? 'woobt_rules' );
			$type = sanitize_key( $_POST['type'] ?? 'apply' );

			self::combination( '', $name, null, $key, $type );
			wp_die();
		}

		function ajax_search_term() {
			if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'woobt-security' ) || ! current_user_can( 'manage_options' ) ) {
				die( 'Permissions check failed!' );
			}

			$return = [];

			$args = [
				'taxonomy'   => sanitize_text_field( $_REQUEST['taxonomy'] ),
				'orderby'    => 'id',
				'order'      => 'ASC',
				'hide_empty' => false,
				'fields'     => 'all',
				'name__like' => sanitize_text_field( $_REQUEST['q'] ),
			];

			$terms = get_terms( $args );

			if ( count( $terms ) ) {
				foreach ( $terms as $term ) {
					$return[] = [
						$term->slug,
						self::get_term_name( $term, sanitize_text_field( $_REQUEST['taxonomy'] ) )
					];
				}
			}

			wp_send_json( $return );
		}

		function get_term_name( $term, $taxonomy ) {
			if ( $term->parent ) {
				$separator = ' > ';
				$name      = get_term_parents_list( $term->term_id, $taxonomy, [
					'link'      => false,
					'separator' => $separator
				] );

				$name = rtrim( $name, $separator );
			} else {
				$name = $term->name;
			}

			return apply_filters( 'woobt_get_term_name', $name, $term, $taxonomy );
		}

		function search_settings() {
			$search_sku      = WPCleverWoobt_Helper()->get_setting( 'search_sku', 'no' );
			$search_id       = WPCleverWoobt_Helper()->get_setting( 'search_id', 'no' );
			$search_exact    = WPCleverWoobt_Helper()->get_setting( 'search_exact', 'no' );
			$search_sentence = WPCleverWoobt_Helper()->get_setting( 'search_sentence', 'no' );
			$search_same     = WPCleverWoobt_Helper()->get_setting( 'search_same', 'no' );
			?>
            <tr>
                <th><?php esc_html_e( 'Search limit', 'woo-bought-together' ); ?></th>
                <td>
                    <label>
                        <input class="woobt_search_limit" type="number" name="woobt_settings[search_limit]"
                               value="<?php echo WPCleverWoobt_Helper()->get_setting( 'search_limit', 10 ); ?>"/>
                    </label>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Search by SKU', 'woo-bought-together' ); ?></th>
                <td>
                    <label> <select name="woobt_settings[search_sku]" class="woobt_search_sku">
                            <option value="yes" <?php selected( $search_sku, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                            <option value="no" <?php selected( $search_sku, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                        </select> </label>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Search by ID', 'woo-bought-together' ); ?></th>
                <td>
                    <label> <select name="woobt_settings[search_id]" class="woobt_search_id">
                            <option value="yes" <?php selected( $search_id, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                            <option value="no" <?php selected( $search_id, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                        </select> </label>
                    <span class="description"><?php esc_html_e( 'Search by ID when entering the numeric only.', 'woo-bought-together' ); ?></span>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Search exact', 'woo-bought-together' ); ?></th>
                <td>
                    <label> <select name="woobt_settings[search_exact]" class="woobt_search_exact">
                            <option value="yes" <?php selected( $search_exact, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                            <option value="no" <?php selected( $search_exact, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                        </select> </label>
                    <span class="description"><?php esc_html_e( 'Match whole product title or content?', 'woo-bought-together' ); ?></span>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Search sentence', 'woo-bought-together' ); ?></th>
                <td>
                    <label> <select name="woobt_settings[search_sentence]" class="woobt_search_sentence">
                            <option value="yes" <?php selected( $search_sentence, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                            <option value="no" <?php selected( $search_sentence, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                        </select> </label>
                    <span class="description"><?php esc_html_e( 'Do a phrase search?', 'woo-bought-together' ); ?></span>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Accept same products', 'woo-bought-together' ); ?></th>
                <td>
                    <label> <select name="woobt_settings[search_same]" class="woobt_search_same">
                            <option value="yes" <?php selected( $search_same, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                            <option value="no" <?php selected( $search_same, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                        </select> </label>
                    <span class="description"><?php esc_html_e( 'If yes, a product can be added many times.', 'woo-bought-together' ); ?></span>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Product types', 'woo-bought-together' ); ?></th>
                <td>
					<?php
					$search_types  = WPCleverWoobt_Helper()->get_setting( 'search_types', [ 'all' ] );
					$product_types = wc_get_product_types();
					$product_types = array_merge( [ 'all' => esc_html__( 'All', 'woo-bought-together' ) ], $product_types );
					$key_pos       = array_search( 'variable', array_keys( $product_types ) );

					if ( $key_pos !== false ) {
						$key_pos ++;
						$second_array  = array_splice( $product_types, $key_pos );
						$product_types = array_merge( $product_types, [ 'variation' => esc_html__( ' → Variation', 'woo-bought-together' ) ], $second_array );
					}

					echo '<select name="woobt_settings[search_types][]" multiple style="width: 200px; height: 150px;" class="woobt_search_types">';

					foreach ( $product_types as $key => $name ) {
						echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( $key, $search_types, true ) ? 'selected' : '' ) . '>' . esc_html( $name ) . '</option>';
					}

					echo '</select>';
					?>
                </td>
            </tr>
			<?php
		}

		function enqueue_scripts() {
			// carousel
			wp_enqueue_style( 'slick', WOOBT_URI . 'assets/slick/slick.css' );
			wp_enqueue_script( 'slick', WOOBT_URI . 'assets/slick/slick.min.js', [ 'jquery' ], WOOBT_VERSION, true );

			wp_enqueue_style( 'woobt-frontend', WOOBT_URI . 'assets/css/frontend.css', [], WOOBT_VERSION );
			wp_enqueue_script( 'woobt-frontend', WOOBT_URI . 'assets/js/frontend.js', [ 'jquery' ], WOOBT_VERSION, true );
			wp_localize_script( 'woobt-frontend', 'woobt_vars', apply_filters( 'woobt_vars', [
					'wc_ajax_url'              => WC_AJAX::get_endpoint( '%%endpoint%%' ),
					'nonce'                    => wp_create_nonce( 'woobt-security' ),
					'change_image'             => WPCleverWoobt_Helper()->get_setting( 'change_image', 'yes' ),
					'change_price'             => WPCleverWoobt_Helper()->get_setting( 'change_price', 'yes' ),
					'price_selector'           => WPCleverWoobt_Helper()->get_setting( 'change_price_custom', '' ),
					'counter'                  => WPCleverWoobt_Helper()->get_setting( 'counter', 'individual' ),
					'variation_selector'       => ( class_exists( 'WPClever_Woovr' ) && ( WPCleverWoobt_Helper()->get_setting( 'variations_selector', 'default' ) === 'woovr' ) ) ? 'woovr' : 'default',
					'price_format'             => get_woocommerce_price_format(),
					'price_suffix'             => ( $suffix = get_option( 'woocommerce_price_display_suffix' ) ) && wc_tax_enabled() ? $suffix : '',
					'price_decimals'           => wc_get_price_decimals(),
					'price_thousand_separator' => wc_get_price_thousand_separator(),
					'price_decimal_separator'  => wc_get_price_decimal_separator(),
					'currency_symbol'          => get_woocommerce_currency_symbol(),
					'trim_zeros'               => apply_filters( 'woocommerce_price_trim_zeros', false ),
					'additional_price_text'    => WPCleverWoobt_Helper()->localization( 'additional', esc_html__( 'Additional price:', 'woo-bought-together' ) ),
					'total_price_text'         => WPCleverWoobt_Helper()->localization( 'total', esc_html__( 'Total:', 'woo-bought-together' ) ),
					'add_to_cart'              => WPCleverWoobt_Helper()->get_setting( 'atc_button', 'main' ) === 'main' ? WPCleverWoobt_Helper()->localization( 'add_to_cart', esc_html__( 'Add to cart', 'woo-bought-together' ) ) : WPCleverWoobt_Helper()->localization( 'add_all_to_cart', esc_html__( 'Add all to cart', 'woo-bought-together' ) ),
					'alert_selection'          => WPCleverWoobt_Helper()->localization( 'alert_selection', esc_html__( 'Please select a purchasable variation for [name] before adding this product to the cart.', 'woo-bought-together' ) ),
					'carousel_params'          => apply_filters( 'woobt_carousel_params', json_encode( apply_filters( 'woobt_carousel_params_arr', [
						'dots'           => true,
						'arrows'         => true,
						'infinite'       => false,
						'adaptiveHeight' => true,
						'rtl'            => is_rtl(),
						'responsive'     => [
							[
								'breakpoint' => 768,
								'settings'   => [
									'slidesToShow'   => 2,
									'slidesToScroll' => 2
								]
							],
							[
								'breakpoint' => 480,
								'settings'   => [
									'slidesToShow'   => 1,
									'slidesToScroll' => 1
								]
							]
						]
					] ) ) ),
				] )
			);
		}

		function admin_enqueue_scripts( $hook ) {
			if ( apply_filters( 'woobt_ignore_backend_scripts', false, $hook ) ) {
				return null;
			}

			wp_enqueue_style( 'hint', WOOBT_URI . 'assets/css/hint.css' );
			wp_enqueue_style( 'woobt-backend', WOOBT_URI . 'assets/css/backend.css', [ 'woocommerce_admin_styles' ], WOOBT_VERSION );
			wp_enqueue_script( 'woobt-backend', WOOBT_URI . 'assets/js/backend.js', [
				'jquery',
				'jquery-ui-dialog',
				'jquery-ui-sortable',
				'wc-enhanced-select',
				'selectWoo',
			], WOOBT_VERSION, true );
			wp_localize_script( 'woobt-backend', 'woobt_vars', [ 'nonce' => wp_create_nonce( 'woobt-security' ), ] );
		}

		function action_links( $links, $file ) {
			static $plugin;

			if ( ! isset( $plugin ) ) {
				$plugin = plugin_basename( WOOBT_FILE );
			}

			if ( $plugin === $file ) {
				$settings             = '<a href="' . esc_url( admin_url( 'admin.php?page=wpclever-woobt&tab=settings' ) ) . '">' . esc_html__( 'Settings', 'woo-bought-together' ) . '</a>';
				$links['wpc-premium'] = '<a href="' . esc_url( admin_url( 'admin.php?page=wpclever-woobt&tab=premium' ) ) . '">' . esc_html__( 'Premium Version', 'woo-bought-together' ) . '</a>';
				array_unshift( $links, $settings );
			}

			return (array) $links;
		}

		function row_meta( $links, $file ) {
			static $plugin;

			if ( ! isset( $plugin ) ) {
				$plugin = plugin_basename( WOOBT_FILE );
			}

			if ( $plugin === $file ) {
				$row_meta = [
					'support' => '<a href="' . esc_url( WOOBT_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'woo-bought-together' ) . '</a>',
				];

				return array_merge( $links, $row_meta );
			}

			return (array) $links;
		}

		function display_post_states( $states, $post ) {
			if ( 'product' == get_post_type( $post->ID ) ) {
				if ( self::is_disable( $post->ID, 'edit' ) ) {
					$states[] = apply_filters( 'woobt_post_states', '<span class="woobt-state">' . esc_html__( 'Bought together (Disabled)', 'woo-bought-together' ) . '</span>', $post->ID );
				} else {
					$items = self::get_product_items( $post->ID, 'edit' );
					$count = 0;

					if ( ! empty( $items ) ) {
						foreach ( $items as $item ) {
							if ( ! empty( $item['id'] ) ) {
								$count += 1;
							}
						}

						$states[] = apply_filters( 'woobt_post_states', '<span class="woobt-state">' . sprintf( /* translators: count */ esc_html__( 'Bought together (%d)', 'woo-bought-together' ), $count ) . '</span>', $count, $post->ID );
					}
				}
			}

			return $states;
		}

		function cart_item_removed( $cart_item_key, $cart ) {
			if ( isset( $cart->removed_cart_contents[ $cart_item_key ]['woobt_keys'] ) ) {
				$keys = $cart->removed_cart_contents[ $cart_item_key ]['woobt_keys'];

				foreach ( $keys as $key ) {
					unset( $cart->cart_contents[ $key ] );
				}
			}
		}

		function cart_item_name( $item_name, $item ) {
			if ( ! empty( $item['woobt_parent_id'] ) ) {
				$parent_id       = apply_filters( 'woobt_parent_id', $item['woobt_parent_id'], $item );
				$associated_text = WPCleverWoobt_Helper()->localization( 'associated', /* translators: product name */ esc_html__( '(bought together %s)', 'woo-bought-together' ) );

				if ( str_contains( $item_name, '</a>' ) ) {
					$name = sprintf( $associated_text, '<a href="' . get_permalink( $parent_id ) . '">' . get_the_title( $parent_id ) . '</a>' );
				} else {
					$name = sprintf( $associated_text, get_the_title( $parent_id ) );
				}

				$item_name .= ' <span class="woobt-item-name">' . apply_filters( 'woobt_item_name', $name, $item ) . '</span>';
			}

			return $item_name;
		}

		function cart_item_quantity( $quantity, $cart_item_key, $cart_item ) {
			// add qty as text - not input
			if ( isset( $cart_item['woobt_parent_id'] ) ) {
				if ( ( WPCleverWoobt_Helper()->get_setting( 'cart_quantity', 'yes' ) === 'no' ) || ( isset( $cart_item['woobt_sync_qty'] ) && $cart_item['woobt_sync_qty'] ) ) {
					return $cart_item['quantity'];
				}
			}

			return $quantity;
		}

		function check_in_cart( $product_id ) {
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				if ( $cart_item['product_id'] === $product_id ) {
					return true;
				}
			}

			return false;
		}

		function found_in_cart( $found_in_cart, $product_id ) {
			if ( apply_filters( 'woobt_sold_individually_found_in_cart', true ) && self::check_in_cart( $product_id ) ) {
				return true;
			}

			return $found_in_cart;
		}

		function add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = 0 ) {
			if ( apply_filters( 'woobt_add_to_cart_validation', true ) && ( isset( $_REQUEST['woobt_ids'] ) || isset( $_REQUEST['data']['woobt_ids'] ) ) ) {
				if ( isset( $_REQUEST['woobt_ids'] ) ) {
					$validate_items = self::get_items_from_ids( $_REQUEST['woobt_ids'], $product_id );
				} elseif ( isset( $_REQUEST['data']['woobt_ids'] ) ) {
					$validate_items = self::get_items_from_ids( $_REQUEST['data']['woobt_ids'], $product_id );
				} else {
					$validate_items = [];
				}

				// validate if it has items
				$items         = self::get_items( $product_id, 'validate' );
				$rule_items    = self::get_rule_items( $product_id, 'validate' );
				$product_items = $variation_id ? array_merge( self::get_product_items( $product_id, 'validate' ), self::get_rule_items( $variation_id, 'validate' ) ) : self::get_product_items( $product_id, 'validate' );

				if ( ! empty( $validate_items ) && ! empty( $items ) ) {
					foreach ( $validate_items as $validate_key => $validate_item ) {
						$item_product = wc_get_product( $validate_item['id'] );

						if ( ! $item_product ) {
							wc_add_notice( esc_html__( 'One of the associated products is unavailable.', 'woo-bought-together' ), 'error' );
							wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'woo-bought-together' ), 'error' );

							return false;
						}

						if ( ! empty( $product_items ) ) {
							// if it has specified items
							if ( ! isset( $product_items[ $validate_key ] ) ) {
								wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'woo-bought-together' ), 'error' );

								return false;
							}

							if ( is_a( $item_product, 'WC_Product_Variation' ) ) {
								$parent_id = apply_filters( 'woobt_parent_id', $item_product->get_parent_id() );

								if ( ( $product_items[ $validate_key ]['id'] != $parent_id ) && ( $product_items[ $validate_key ]['id'] != $validate_item['id'] ) ) {
									wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'woo-bought-together' ), 'error' );

									return false;
								}
							} else {
								if ( $product_items[ $validate_key ]['id'] != $validate_item['id'] ) {
									wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'woo-bought-together' ), 'error' );

									return false;
								}
							}
						} elseif ( ! empty( $rule_items ) ) {
							// if it has rule items
							if ( ! isset( $rule_items[ $validate_key ] ) ) {
								wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'woo-bought-together' ), 'error' );

								return false;
							}

							if ( is_a( $item_product, 'WC_Product_Variation' ) ) {
								$parent_id = apply_filters( 'woobt_parent_id', $item_product->get_parent_id() );

								if ( ( $rule_items[ $validate_key ]['id'] != $parent_id ) && ( $rule_items[ $validate_key ]['id'] != $validate_item['id'] ) ) {
									wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'woo-bought-together' ), 'error' );

									return false;
								}
							} else {
								if ( $rule_items[ $validate_key ]['id'] != $validate_item['id'] ) {
									wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'woo-bought-together' ), 'error' );

									return false;
								}
							}
						}

						if ( $item_product->is_type( 'variable' ) ) {
							wc_add_notice( sprintf( /* translators: product name */ esc_html__( '"%s" is un-purchasable.', 'woo-bought-together' ), esc_html( apply_filters( 'woobt_product_get_name', $item_product->get_name(), $item_product ) ) ), 'error' );
							wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'woo-bought-together' ), 'error' );

							return false;
						}

						if ( $item_product->is_sold_individually() && apply_filters( 'woobt_sold_individually_found_in_cart', true ) && self::check_in_cart( $validate_item['id'] ) ) {
							wc_add_notice( sprintf( /* translators: product name */ esc_html__( 'You cannot add another "%s" to the cart.', 'woo-bought-together' ), esc_html( apply_filters( 'woobt_product_get_name', $item_product->get_name(), $item_product ) ) ), 'error' );
							wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'woo-bought-together' ), 'error' );

							return false;
						}

						if ( apply_filters( 'woobt_custom_qty', get_post_meta( $product_id, 'woobt_custom_qty', true ) === 'on', $product_id ) ) {
							// custom qty
							if ( ( $limit_min = apply_filters( 'woobt_limit_each_min', get_post_meta( $product_id, 'woobt_limit_each_min', true ), $validate_item, $product_id ) ) && ( $validate_item['qty'] < (float) $limit_min ) ) {
								wc_add_notice( sprintf( /* translators: product name */ esc_html__( '"%s" does not reach the minimum quantity.', 'woo-bought-together' ), esc_html( apply_filters( 'woobt_product_get_name', $item_product->get_name(), $item_product ) ) ), 'error' );
								wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'woo-bought-together' ), 'error' );

								return false;
							}

							if ( ( $limit_max = apply_filters( 'woobt_limit_each_max', get_post_meta( $product_id, 'woobt_limit_each_max', true ), $validate_item, $product_id ) ) && ( $validate_item['qty'] > (float) $limit_max ) ) {
								wc_add_notice( sprintf( /* translators: product name */ esc_html__( '"%s" passes the maximum quantity.', 'woo-bought-together' ), esc_html( apply_filters( 'woobt_product_get_name', $item_product->get_name(), $item_product ) ) ), 'error' );
								wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'woo-bought-together' ), 'error' );

								return false;
							}
						} else {
							// fixed qty
							if ( isset( $product_items[ $validate_key ]['qty'] ) && ( $product_items[ $validate_key ]['qty'] != $validate_item['qty'] ) ) {
								wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'woo-bought-together' ), 'error' );

								return false;
							}
						}
					}
				}
			}

			return $passed;
		}

		function add_cart_item_data( $cart_item_data, $product_id ) {
			if ( isset( $_REQUEST['woobt_ids'] ) || isset( $_REQUEST['data']['woobt_ids'] ) ) {
				if ( isset( $_REQUEST['woobt_ids'] ) ) {
					$ids = $_REQUEST['woobt_ids'];
					unset( $_REQUEST['woobt_ids'] );
				} elseif ( isset( $_REQUEST['data']['woobt_ids'] ) ) {
					$ids = $_REQUEST['data']['woobt_ids'];
					unset( $_REQUEST['data']['woobt_ids'] );
				} else {
					$ids = '';
				}

				if ( ! empty( $ids ) ) {
					$cart_item_data['woobt_ids'] = $ids;
				}
			} else {
				$ids = apply_filters( 'woobt_add_cart_item_data_ids', '', $cart_item_data, $product_id );

				if ( ! empty( $ids ) ) {
					$cart_item_data['woobt_ids'] = $ids;
				}
			}

			return $cart_item_data;
		}

		function add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
			$product_id = apply_filters( 'woobt_add_to_cart_product_id', $product_id, $cart_item_data );
			$items      = $variation_id ? array_merge( self::get_items( $product_id, 'add-to-cart' ), self::get_items( $variation_id, 'add-to-cart' ) ) : self::get_items( $product_id, 'add-to-cart' ); // make sure it has items

			if ( ! empty( $cart_item_data['woobt_ids'] ) && ! empty( $items ) ) {
				$ids = $cart_item_data['woobt_ids'];

				if ( $add_items = self::get_items_from_ids( $ids, $product_id ) ) {
					$custom_qty  = apply_filters( 'woobt_custom_qty', get_post_meta( $product_id, 'woobt_custom_qty', true ) === 'on', $product_id );
					$separately  = apply_filters( 'woobt_separately', get_post_meta( $product_id, 'woobt_separately', true ) === 'on', $product_id );
					$reset_price = apply_filters( 'woobt_separately_reset_price', true, $product_id, 'add-to-cart' );
					$ignore_this = apply_filters( 'woobt_separately_ignore_this_item', false, $product_id );
					$sync_qty    = ! $custom_qty && apply_filters( 'woobt_sync_qty', get_post_meta( $product_id, 'woobt_sync_qty', true ) === 'on' );

					if ( ! $separately ) {
						// add sync_qty for the main product
						WC()->cart->cart_contents[ $cart_item_key ]['woobt_ids']      = $ids;
						WC()->cart->cart_contents[ $cart_item_key ]['woobt_key']      = $cart_item_key;
						WC()->cart->cart_contents[ $cart_item_key ]['woobt_sync_qty'] = $sync_qty;
					} else {
						WC()->cart->remove_cart_item( $cart_item_key );

						if ( ! $ignore_this ) {
							// unset woobt_ids then add main product again
							unset( $cart_item_data['woobt_ids'] );

							if ( ! $reset_price ) {
								$cart_item_data['woobt_new_price'] = ( 100 - self::get_discount( $product_id ) ) . '%';
							}

							WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data );
						}
					}

					// add child products
					self::add_to_cart_items( $add_items, $cart_item_key, $product_id, $quantity );
				}
			}
		}

		function add_to_cart_items( $items, $cart_item_key, $product_id, $quantity ) {
			$custom_qty  = apply_filters( 'woobt_custom_qty', get_post_meta( $product_id, 'woobt_custom_qty', true ) === 'on', $product_id );
			$separately  = apply_filters( 'woobt_separately', get_post_meta( $product_id, 'woobt_separately', true ) === 'on', $product_id );
			$reset_price = apply_filters( 'woobt_separately_reset_price', true, $product_id, 'add-to-cart' );
			$sync_qty    = ! $custom_qty && apply_filters( 'woobt_sync_qty', get_post_meta( $product_id, 'woobt_sync_qty', true ) === 'on' );

			// add child products
			foreach ( $items as $item ) {
				$item_id           = apply_filters( 'woobt_item_id', $item['id'], $item, $product_id );
				$item_qty          = apply_filters( 'woobt_item_qty', $item['qty'], $item, $product_id );
				$item_price        = apply_filters( 'woobt_item_price', $item['price'], $item, $product_id );
				$item_variation    = apply_filters( 'woobt_item_attrs', $item['attrs'], $item, $product_id );
				$item_variation_id = 0;
				$item_product      = wc_get_product( $item_id );

				if ( $item_product instanceof WC_Product_Variation ) {
					// ensure we don't add a variation to the cart directly by variation ID
					$item_variation_id = $item_id;
					$item_id           = $item_product->get_parent_id();

					if ( empty( $item_variation ) ) {
						$item_variation = $item_product->get_variation_attributes();
					}
				}

				if ( $item_product && $item_product->is_in_stock() && $item_product->is_purchasable() && ( 'trash' !== $item_product->get_status() ) ) {
					if ( ! $separately ) {
						// add to cart
						$item_key = WC()->cart->add_to_cart( $item_id, $item_qty, $item_variation_id, $item_variation, [
							'woobt_parent_id'  => $product_id,
							'woobt_parent_key' => $cart_item_key,
							'woobt_qty'        => $item_qty,
							'woobt_sync_qty'   => $sync_qty,
							'woobt_price_item' => $item_price
						] );

						if ( $item_key ) {
							WC()->cart->cart_contents[ $item_key ]['woobt_key']         = $item_key;
							WC()->cart->cart_contents[ $cart_item_key ]['woobt_keys'][] = $item_key;
						}
					} else {
						$item_data = apply_filters( 'woobt_add_to_cart_item_data', $reset_price ? [] : [ 'woobt_new_price' => $item_price ] );

						if ( $sync_qty ) {
							WC()->cart->add_to_cart( $item_id, $item_qty * $quantity, $item_variation_id, $item_variation, $item_data );
						} else {
							WC()->cart->add_to_cart( $item_id, $item_qty, $item_variation_id, $item_variation, $item_data );
						}
					}
				}
			}
		}

		function ajax_get_variation_items() {
			if ( ! apply_filters( 'woobt_disable_security_check', false, 'get_variation' ) ) {
				if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woobt-security' ) ) {
					die( 'Permissions check failed!' );
				}
			}

			if ( ! isset( $_POST['variation_id'] ) ) {
				return;
			}

			$variation_id = absint( sanitize_text_field( $_POST['variation_id'] ) );

			self::show_items( $variation_id, false, true );

			wp_die();
		}

		function ajax_add_all_to_cart() {
			if ( ! apply_filters( 'woobt_disable_security_check', false, 'add_all_to_cart' ) ) {
				if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woobt-security' ) ) {
					die( 'Permissions check failed!' );
				}
			}

			if ( ! isset( $_POST['product_id'] ) ) {
				return;
			}

			$product_id     = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
			$product        = wc_get_product( $product_id );
			$product_status = get_post_status( $product_id );
			$quantity       = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
			$variation_id   = absint( $_POST['variation_id'] ?? 0 );
			$variation      = (array) ( $_POST['variation'] ?? [] );

			if ( $product && 'variation' === $product->get_type() ) {
				$variation_id = $product_id;
				$product_id   = $product->get_parent_id();

				if ( empty( $variation ) ) {
					$variation = $product->get_variation_attributes();
				}
			}

			$cart_item_data    = apply_filters( 'woobt_add_to_cart_data', [] );
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variation );

			if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data ) && 'publish' === $product_status ) {
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );

				if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
					wc_add_to_cart_message( [ $product_id => $quantity ], true );
				}

				WC_AJAX::get_refreshed_fragments();
			} else {
				$data = [
					'error'       => true,
					'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
				];

				wp_send_json( $data );
			}

			wp_die();
		}

		function before_mini_cart_contents() {
			WC()->cart->calculate_totals();
		}

		function before_calculate_totals( $cart_object ) {
			if ( ! defined( 'DOING_AJAX' ) && is_admin() ) {
				// This is necessary for WC 3.0+
				return;
			}

			$cart_contents = $cart_object->cart_contents;
			$new_keys      = [];

			foreach ( $cart_contents as $cart_item_key => $cart_item ) {
				if ( ! empty( $cart_item['woobt_key'] ) ) {
					$new_keys[ $cart_item_key ] = $cart_item['woobt_key'];
				}
			}

			foreach ( $cart_contents as $cart_item_key => $cart_item ) {
				// add separately but don't reset price
				if ( isset( $cart_item['woobt_new_price'] ) && ( $cart_item['woobt_new_price'] !== '100%' ) && ( $cart_item['woobt_new_price'] !== '' ) ) {
					$item_product   = wc_get_product( ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : $cart_item['product_id'] );
					$item_price     = apply_filters( 'woobt_cart_item_product_price', $item_product->get_price(), $item_product );
					$item_new_price = WPCleverWoobt_Helper()->new_price( $item_price, $cart_item['woobt_new_price'] );

					$cart_item['data']->set_price( $item_new_price );
				}

				// associated products
				if ( isset( $cart_item['woobt_parent_id'], $cart_item['woobt_price_item'] ) && ( $cart_item['woobt_price_item'] !== '100%' ) && ( $cart_item['woobt_price_item'] !== '' ) ) {
					$item_product   = wc_get_product( ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : $cart_item['product_id'] );
					$item_price     = apply_filters( 'woobt_cart_item_product_price', ( WPCleverWoobt_Helper()->get_setting( 'pricing', 'sale_price' ) === 'sale_price' ? $item_product->get_price() : $item_product->get_regular_price() ), $item_product );
					$item_new_price = WPCleverWoobt_Helper()->new_price( $item_price, $cart_item['woobt_price_item'] );

					$cart_item['data']->set_price( $item_new_price );
				}

				// sync quantity
				if ( ! empty( $cart_item['woobt_parent_key'] ) && ! empty( $cart_item['woobt_qty'] ) && ! empty( $cart_item['woobt_sync_qty'] ) ) {
					$parent_key     = $cart_item['woobt_parent_key'];
					$parent_new_key = array_search( $parent_key, $new_keys );

					if ( isset( $cart_contents[ $parent_key ] ) ) {
						WC()->cart->cart_contents[ $cart_item_key ]['quantity'] = $cart_item['woobt_qty'] * $cart_contents[ $parent_key ]['quantity'];
					} elseif ( isset( $cart_contents[ $parent_new_key ] ) ) {
						WC()->cart->cart_contents[ $cart_item_key ]['quantity'] = $cart_item['woobt_qty'] * $cart_contents[ $parent_new_key ]['quantity'];
					}
				}

				// main product
				if ( ! empty( $cart_item['woobt_ids'] ) ) {
					$separately = apply_filters( 'woobt_separately', get_post_meta( $cart_item['product_id'], 'woobt_separately', true ) === 'on', $cart_item['product_id'] );

					if ( ! $separately ) {
						$discount = self::get_discount( $cart_item['product_id'] );

						if ( ! empty( $discount ) ) {
							if ( $cart_item['variation_id'] > 0 ) {
								$item_product = wc_get_product( $cart_item['variation_id'] );
							} else {
								$item_product = wc_get_product( $cart_item['product_id'] );
							}

							$item_price = apply_filters( 'woobt_cart_item_product_price', $item_product->get_price(), $item_product );

							// has associated products
							$has_associated = false;

							if ( isset( $cart_item['woobt_keys'] ) ) {
								foreach ( $cart_item['woobt_keys'] as $key ) {
									if ( isset( $cart_contents[ $key ] ) ) {
										$has_associated = true;
										break;
									}
								}
							}

							if ( $has_associated ) {
								$item_new_price = $item_price * ( 100 - (float) $discount ) / 100;
								$cart_item['data']->set_price( $item_new_price );
							}
						}
					}
				}
			}
		}

		function get_cart_item_from_session( $cart_item, $item_session_values ) {
			if ( ! empty( $item_session_values['woobt_ids'] ) ) {
				$cart_item['woobt_ids'] = $item_session_values['woobt_ids'];
			}

			if ( ! empty( $item_session_values['woobt_parent_id'] ) ) {
				$cart_item['woobt_parent_id']  = $item_session_values['woobt_parent_id'];
				$cart_item['woobt_parent_key'] = $item_session_values['woobt_parent_key'];
				$cart_item['woobt_price_item'] = $item_session_values['woobt_price_item'];
				$cart_item['woobt_qty']        = $item_session_values['woobt_qty'];
			}

			if ( ! empty( $item_session_values['woobt_sync_qty'] ) ) {
				$cart_item['woobt_sync_qty'] = $item_session_values['woobt_sync_qty'];
			}

			return $cart_item;
		}

		function order_line_item( $item, $cart_item_key, $values ) {
			// add _ to hide
			if ( isset( $values['woobt_parent_id'] ) ) {
				$item->update_meta_data( '_woobt_parent_id', $values['woobt_parent_id'] );
			}

			if ( isset( $values['woobt_ids'] ) ) {
				$item->update_meta_data( '_woobt_ids', $values['woobt_ids'] );
			}
		}

		function hidden_order_item_meta( $hidden ) {
			return array_merge( $hidden, [
				'_woobt_parent_id',
				'_woobt_ids',
				'woobt_parent_id',
				'woobt_ids'
			] );
		}

		function before_order_item_meta( $item_id, $item ) {
			if ( $parent_id = $item->get_meta( '_woobt_parent_id' ) ) {
				echo sprintf( WPCleverWoobt_Helper()->localization( 'associated', /* translators: product name */ esc_html__( '(bought together %s)', 'woo-bought-together' ) ), get_the_title( $parent_id ) );
			}
		}

		function order_again_item_data( $data, $item ) {
			if ( $ids = $item->get_meta( '_woobt_ids' ) ) {
				$data['woobt_order_again'] = 'yes';
				$data['woobt_ids']         = $ids;
			}

			if ( $parent_id = $item->get_meta( '_woobt_parent_id' ) ) {
				$data['woobt_order_again'] = 'yes';
				$data['woobt_parent_id']   = $parent_id;
			}

			return $data;
		}

		function cart_loaded_from_session( $cart ) {
			foreach ( $cart->cart_contents as $cart_item_key => $cart_item ) {
				// remove orphaned products
				if ( isset( $cart_item['woobt_parent_key'] ) && ( $parent_key = $cart_item['woobt_parent_key'] ) && ! isset( $cart->cart_contents[ $parent_key ] ) ) {
					$cart->remove_cart_item( $cart_item_key );
				}

				// remove associated products first
				if ( isset( $cart_item['woobt_order_again'], $cart_item['woobt_parent_id'] ) ) {
					$cart->remove_cart_item( $cart_item_key );
				}
			}

			foreach ( $cart->cart_contents as $cart_item_key => $cart_item ) {
				// add associated products again
				if ( isset( $cart_item['woobt_order_again'], $cart_item['woobt_ids'] ) ) {
					unset( $cart->cart_contents[ $cart_item_key ]['woobt_order_again'] );

					$product_id = $cart_item['product_id'];
					$custom_qty = apply_filters( 'woobt_custom_qty', get_post_meta( $product_id, 'woobt_custom_qty', true ) === 'on', $product_id );
					$sync_qty   = ! $custom_qty && apply_filters( 'woobt_sync_qty', get_post_meta( $product_id, 'woobt_sync_qty', true ) === 'on' );

					$cart->cart_contents[ $cart_item_key ]['woobt_key']      = $cart_item_key;
					$cart->cart_contents[ $cart_item_key ]['woobt_sync_qty'] = $sync_qty;

					if ( $add_items = self::get_items_from_ids( $cart_item['woobt_ids'], $cart_item['product_id'] ) ) {
						self::add_to_cart_items( $add_items, $cart_item_key, $cart_item['product_id'], $cart_item['quantity'] );
					}
				}
			}
		}

		function cart_item_restored( $cart_item_key, $cart ) {
			if ( isset( $cart->cart_contents[ $cart_item_key ]['woobt_ids'] ) ) {
				// remove old keys
				unset( $cart->cart_contents[ $cart_item_key ]['woobt_keys'] );

				$ids        = $cart->cart_contents[ $cart_item_key ]['woobt_ids'];
				$product_id = $cart->cart_contents[ $cart_item_key ]['product_id'];
				$quantity   = $cart->cart_contents[ $cart_item_key ]['quantity'];
				$separately = apply_filters( 'woobt_separately', get_post_meta( $product_id, 'woobt_separately', true ) === 'on', $product_id );

				if ( ! $separately ) {
					if ( $add_items = self::get_items_from_ids( $ids, $product_id ) ) {
						self::add_to_cart_items( $add_items, $cart_item_key, $product_id, $quantity );
					}
				}
			}
		}

		function ajax_update_search_settings() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woobt-security' ) || ! current_user_can( 'manage_options' ) ) {
				die( 'Permissions check failed!' );
			}

			$settings                    = (array) get_option( 'woobt_settings', [] );
			$settings['search_limit']    = (int) sanitize_text_field( $_POST['limit'] );
			$settings['search_sku']      = sanitize_text_field( $_POST['sku'] );
			$settings['search_id']       = sanitize_text_field( $_POST['id'] );
			$settings['search_exact']    = sanitize_text_field( $_POST['exact'] );
			$settings['search_sentence'] = sanitize_text_field( $_POST['sentence'] );
			$settings['search_same']     = sanitize_text_field( $_POST['same'] );
			$settings['search_types']    = array_map( 'sanitize_text_field', (array) $_POST['types'] );

			update_option( 'woobt_settings', $settings );
			wp_die();
		}

		function ajax_get_search_results() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woobt-security' ) ) {
				die( 'Permissions check failed!' );
			}

			$types         = WPCleverWoobt_Helper()->get_setting( 'search_types', [ 'all' ] );
			$keyword       = esc_html( $_POST['woobt_keyword'] );
			$id            = absint( $_POST['woobt_id'] );
			$exclude_ids   = explode( ',', $_POST['woobt_ids'] );
			$exclude_ids[] = $id;

			if ( ( WPCleverWoobt_Helper()->get_setting( 'search_id', 'no' ) === 'yes' ) && is_numeric( $keyword ) ) {
				// search by id
				$query_args = [
					'p'         => absint( $keyword ),
					'post_type' => 'product'
				];
			} else {
				$limit = WPCleverWoobt_Helper()->get_setting( 'search_limit', 10 );

				if ( $limit < 1 ) {
					$limit = 10;
				} elseif ( $limit > 500 ) {
					$limit = 500;
				}

				$query_args = [
					'is_woobt'       => true,
					'post_type'      => 'product',
					'post_status'    => 'publish',
					's'              => $keyword,
					'posts_per_page' => $limit
				];

				if ( ! empty( $types ) && ! in_array( 'all', $types, true ) ) {
					$product_types = $types;

					if ( in_array( 'variation', $types, true ) ) {
						$product_types[] = 'variable';
					}

					$query_args['tax_query'] = [
						[
							'taxonomy' => 'product_type',
							'field'    => 'slug',
							'terms'    => $product_types,
						],
					];
				}

				if ( WPCleverWoobt_Helper()->get_setting( 'search_same', 'no' ) !== 'yes' ) {
					$query_args['post__not_in'] = $exclude_ids;
				}
			}

			$query = new WP_Query( $query_args );

			if ( $query->have_posts() ) {
				echo '<ul>';

				while ( $query->have_posts() ) {
					$query->the_post();
					$product = wc_get_product( get_the_ID() );

					if ( ! $product || ( 'trash' === $product->get_status() ) ) {
						continue;
					}

					if ( ! $product->is_type( 'variable' ) || in_array( 'variable', $types, true ) || in_array( 'all', $types, true ) ) {
						self::product_data_li( $product, '100%', 1, true );
					}

					if ( $product->is_type( 'variable' ) && ( empty( $types ) || in_array( 'all', $types, true ) || in_array( 'variation', $types, true ) ) ) {
						// show all children
						$children = $product->get_children();

						if ( is_array( $children ) && count( $children ) > 0 ) {
							foreach ( $children as $child ) {
								$product_child = wc_get_product( $child );

								if ( $product_child ) {
									self::product_data_li( $product_child, '100%', 1, true );
								}
							}
						}
					}
				}

				echo '</ul>';
				wp_reset_postdata();
			} else {
				echo '<ul><span>' . sprintf( /* translators: keyword */ esc_html__( 'No results found for "%s"', 'woo-bought-together' ), esc_html( $keyword ) ) . '</span></ul>';
			}

			wp_die();
		}

		function ajax_add_text() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woobt-security' ) ) {
				die( 'Permissions check failed!' );
			}

			self::text_data_li();

			wp_die();
		}

		function product_data_li( $product, $price = '100%', $qty = 1, $search = false, $key = null ) {
			if ( empty( $key ) || is_numeric( $key ) ) {
				$key = WPCleverWoobt_Helper()->generate_key();
			}

			$product_id    = $product->get_id();
			$product_sku   = $product->get_sku();
			$product_class = 'woobt-li-product woobt-item';
			$product_class .= ! $product->is_in_stock() ? ' out-of-stock' : '';
			$product_class .= ! in_array( $product->get_type(), self::$types, true ) ? ' disabled' : '';

			if ( class_exists( 'WPCleverWoopq' ) && ( WPCleverWoopq::get_setting( 'decimal', 'no' ) === 'yes' ) ) {
				$step = '0.000001';
			} else {
				$step = '1';
				$qty  = (int) $qty;
			}

			if ( $search ) {
				$remove_btn = '<span class="woobt-remove hint--left" aria-label="' . esc_html__( 'Add', 'woo-bought-together' ) . '">+</span>';
			} else {
				$remove_btn = '<span class="woobt-remove hint--left" aria-label="' . esc_html__( 'Remove', 'woo-bought-together' ) . '">×</span>';
			}

			$hidden_input = '<input type="hidden" name="woobt_ids[' . $key . '][id]" value="' . $product_id . '"/><input type="hidden" name="woobt_ids[' . $key . '][sku]" value="' . $product_sku . '"/>';

			echo '<li class="' . esc_attr( trim( $product_class ) ) . '" data-id="' . $product->get_id() . '">' . $hidden_input . '<span class="woobt-move"></span><span class="price hint--right" aria-label="' . esc_html__( 'Set a new price using a number (eg. "49") or percentage (eg. "90%" of original price)', 'woo-bought-together' ) . '"><input type="text" name="woobt_ids[' . $key . '][price]" value="' . $price . '"/></span><span class="qty hint--right" aria-label="' . esc_html__( 'Default quantity', 'woo-bought-together' ) . '"><input type="number" name="woobt_ids[' . $key . '][qty]" value="' . esc_attr( $qty ) . '" step="' . esc_attr( $step ) . '"/></span><span class="img">' . $product->get_image( [
					30,
					30
				] ) . '</span><span class="data">' . ( $product->get_status() === 'private' ? '<span class="info">private</span> ' : '' ) . '<span class="name">' . wp_strip_all_tags( $product->get_name() ) . '</span> <span class="info">' . $product->get_price_html() . '</span></span> <span class="type"><a href="' . get_edit_post_link( $product_id ) . '" target="_blank">' . $product->get_type() . '<br/>#' . $product->get_id() . '</a></span> ' . $remove_btn . '</li>';
		}

		function product_data_li_deleted( $product_id, $key ) {
			$hidden_input = '<input type="hidden" name="woobt_ids[' . $key . '][id]" value="' . $product_id . '"/><input type="hidden" name="woobt_ids[' . $key . '][sku]" value=""/>';
			echo '<li class="woobt-li-product woobt-item" data-id="' . esc_attr( $product_id ) . '">' . $hidden_input . '<span class="woobt-move"></span><span class="data"><span class="name">' . sprintf( esc_html__( 'Product ID %d does not exist.', 'woo-bought-together' ), $product_id ) . '</span></span><span class="woobt-remove hint--left" aria-label="' . esc_html__( 'Remove', 'woo-bought-together' ) . '">×</span></li>';
		}

		function text_data_li( $data = [], $key = null ) {
			if ( empty( $key ) || is_numeric( $key ) ) {
				$key = WPCleverWoobt_Helper()->generate_key();
			}

			$data = array_merge( [ 'type' => 'h1', 'text' => '' ], $data );
			$type = '<select name="woobt_ids[' . $key . '][type]"><option value="h1" ' . selected( $data['type'], 'h1', false ) . '>H1</option><option value="h2" ' . selected( $data['type'], 'h2', false ) . '>H2</option><option value="h3" ' . selected( $data['type'], 'h3', false ) . '>H3</option><option value="h4" ' . selected( $data['type'], 'h4', false ) . '>H4</option><option value="h5" ' . selected( $data['type'], 'h5', false ) . '>H5</option><option value="h6" ' . selected( $data['type'], 'h6', false ) . '>H6</option><option value="p" ' . selected( $data['type'], 'p', false ) . '>p</option><option value="span" ' . selected( $data['type'], 'span', false ) . '>span</option><option value="none" ' . selected( $data['type'], 'none', false ) . '>none</option></select>';

			echo '<li class="woobt-li-text"><span class="woobt-move"></span><span class="tag">' . $type . '</span><span class="data"><input type="text" name="woobt_ids[' . $key . '][text]" value="' . esc_attr( $data['text'] ) . '"/></span><span class="woobt-remove hint--left" aria-label="' . esc_html__( 'Remove', 'woo-bought-together' ) . '">×</span></li>';
		}

		function product_data_tabs( $tabs ) {
			$tabs['woobt'] = [
				'label'  => esc_html__( 'Bought Together', 'woo-bought-together' ),
				'target' => 'woobt_settings',
			];

			return $tabs;
		}

		function product_data_panels() {
			global $post, $thepostid, $product_object;

			if ( $product_object instanceof WC_Product ) {
				$product_id = $product_object->get_id();
			} elseif ( is_numeric( $thepostid ) ) {
				$product_id = $thepostid;
			} elseif ( $post instanceof WP_Post ) {
				$product_id = $post->ID;
			} else {
				$product_id = 0;
			}

			if ( ! $product_id ) {
				?>
                <div id='woobt_settings' class='panel woocommerce_options_panel woobt_table'>
                    <p style="padding: 0 12px; color: #c9356e"><?php esc_html_e( 'Product wasn\'t returned.', 'woo-bought-together' ); ?></p>
                </div>
				<?php
				return;
			}

			$disable        = get_post_meta( $product_id, 'woobt_disable', true ) ?: 'no';
			$selection      = get_post_meta( $product_id, 'woobt_selection', true ) ?: 'multiple';
			$layout         = get_post_meta( $product_id, 'woobt_layout', true ) ?: 'unset';
			$position       = get_post_meta( $product_id, 'woobt_position', true ) ?: 'unset';
			$atc_button     = get_post_meta( $product_id, 'woobt_atc_button', true ) ?: 'unset';
			$show_this_item = get_post_meta( $product_id, 'woobt_show_this_item', true ) ?: 'unset';
			?>
            <div id='woobt_settings' class='panel woocommerce_options_panel woobt_table'>
                <div id="woobt_search_settings" style="display: none"
                     data-title="<?php esc_html_e( 'Search settings', 'woo-bought-together' ); ?>">
                    <table>
						<?php self::search_settings(); ?>
                        <tr>
                            <th></th>
                            <td>
                                <button id="woobt_search_settings_update" class="button button-primary">
									<?php esc_html_e( 'Update Options', 'woo-bought-together' ); ?>
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
                <table>
                    <tr>
                        <th><?php esc_html_e( 'Disable', 'woo-bought-together' ); ?></th>
                        <td>
                            <label for="woobt_disable"></label><input id="woobt_disable" name="woobt_disable"
                                                                      type="checkbox" <?php echo esc_attr( $disable === 'yes' ? 'checked' : '' ); ?>/>
                        </td>
                    </tr>
                </table>
                <table class="woobt_table_enable">
                    <tr class="woobt_tr_space">
                        <th><?php esc_html_e( 'Search', 'woo-bought-together' ); ?> (<a
                                    href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woobt&tab=settings#search' ) ); ?>"
                                    id="woobt_search_settings_btn"><?php esc_html_e( 'settings', 'woo-bought-together' ); ?></a>)
                        </th>
                        <td>
                            <div class="w100">
                                        <span class="loading" id="woobt_loading"
                                              style="display: none"><?php esc_html_e( 'searching...', 'woo-bought-together' ); ?></span>
                                <label for="woobt_keyword"></label><input type="search" id="woobt_keyword"
                                                                          placeholder="<?php esc_attr_e( 'Type any keyword to search', 'woo-bought-together' ); ?>"/>
                                <div id="woobt_results" class="woobt_results" style="display: none"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space">
                        <th>
							<?php esc_html_e( 'Selected', 'woo-bought-together' ); ?>
                            <div class="woobt_tools">
                                <a href="#"
                                   class="woobt-import-export"><?php esc_html_e( 'import/export', 'woo-bought-together' ); ?></a>
                            </div>
                        </th>
                        <td>
                            <div class="w100">
								<?php echo '<div class="woobt_notice_default">' . sprintf( /* translators: links */ esc_html__( '* If don\'t choose any products, it can shows products from Smart Rules %1$s or Default %2$s.', 'woo-bought-together' ), '<a
                                                    href="' . esc_url( admin_url( 'admin.php?page=wpclever-woobt&tab=rules' ) ) . '" target="_blank">' . esc_html__( 'here', 'woo-bought-together' ) . '</a>', '<a
                                                    href="' . esc_url( admin_url( 'admin.php?page=wpclever-woobt&tab=settings' ) ) . '" target="_blank">' . esc_html__( 'here', 'woo-bought-together' ) . '</a>' ) . '</div>'; ?>
                                <div id="woobt_selected" class="woobt_selected">
                                    <ul>
										<?php
										if ( $items = self::get_product_items( $product_id ) ) {
											foreach ( $items as $item_key => $item ) {
												if ( ! empty( $item['id'] ) ) {
													$item_id      = $item['id'];
													$item_price   = $item['price'];
													$item_qty     = $item['qty'];
													$item_product = wc_get_product( $item_id );

													if ( ! $item_product ) {
														self::product_data_li_deleted( $item_id, $item_key );
													} else {
														self::product_data_li( $item_product, $item_price, $item_qty, false, $item_key );
													}
												} else {
													self::text_data_li( $item, $item_key );
												}
											}
										}
										?>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space">
                        <th></th>
                        <td>
                            <a href="https://wpclever.net/downloads/frequently-bought-together?utm_source=pro&utm_medium=woobt&utm_campaign=wporg"
                               target="_blank" class="woobt_add_txt button"
                               onclick="return confirm('This feature only available in Premium Version!\nBuy it now? Just $29')">
								<?php esc_html_e( '+ Add heading/paragraph', 'woo-bought-together' ); ?>
                            </a>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space">
                        <th><?php esc_html_e( 'Add separately', 'woo-bought-together' ); ?></th>
                        <td>
                            <label for="woobt_separately"></label><input id="woobt_separately"
                                                                         name="woobt_separately"
                                                                         type="checkbox" <?php echo esc_attr( get_post_meta( $product_id, 'woobt_separately', true ) === 'on' ? 'checked' : '' ); ?>/>
                            <span class="woocommerce-help-tip"
                                  data-tip="<?php esc_attr_e( 'If enabled, the associated products will be added as separate items and stay unaffected from the main product, their prices will change back to the original.', 'woo-bought-together' ); ?>"></span>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space">
                        <th><?php esc_html_e( 'Discount', 'woo-bought-together' ); ?></th>
                        <td>
                            <label for="woobt_discount"></label><input id="woobt_discount" name="woobt_discount"
                                                                       type="number" min="0" max="100"
                                                                       step="0.0001" style="width: 50px"
                                                                       value="<?php echo get_post_meta( $product_id, 'woobt_discount', true ); ?>"/>%
                            <span class="woocommerce-help-tip"
                                  data-tip="<?php esc_attr_e( 'Discount for the main product when buying at least one product in this list.', 'woo-bought-together' ); ?>"></span>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space">
                        <th><?php esc_html_e( 'Selecting method', 'woo-bought-together' ); ?></th>
                        <td>
                            <label> <select name="woobt_selection">
                                    <option value="multiple" <?php selected( $selection, 'multiple' ); ?>><?php esc_html_e( 'Multiple selection (default)', 'woo-bought-together' ); ?></option>
                                    <option value="single" <?php selected( $selection, 'single' ); ?>><?php esc_html_e( 'Single selection (choose 1 only)', 'woo-bought-together' ); ?></option>
                                </select> </label>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space">
                        <th><?php esc_html_e( 'Checked all', 'woo-bought-together' ); ?></th>
                        <td>
                            <input id="woobt_checked_all" name="woobt_checked_all"
                                   type="checkbox" <?php echo esc_attr( apply_filters( 'woobt_checked_all', get_post_meta( $product_id, 'woobt_checked_all', true ) === 'on', $product_id ) ? 'checked' : '' ); ?>/>
                            <label for="woobt_checked_all"><?php esc_html_e( 'Checked all by default.', 'woo-bought-together' ); ?></label>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space">
                        <th><?php esc_html_e( 'Custom quantity', 'woo-bought-together' ); ?></th>
                        <td>
                            <input id="woobt_custom_qty" name="woobt_custom_qty"
                                   type="checkbox" <?php echo esc_attr( get_post_meta( $product_id, 'woobt_custom_qty', true ) === 'on' ? 'checked' : '' ); ?>/>
                            <label for="woobt_custom_qty"><?php esc_html_e( 'Allow the customer can change the quantity of each product.', 'woo-bought-together' ); ?></label>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space woobt_tr_hide_if_custom_qty">
                        <th><?php esc_html_e( 'Sync quantity', 'woo-bought-together' ); ?></th>
                        <td>
                            <input id="woobt_sync_qty" name="woobt_sync_qty"
                                   type="checkbox" <?php echo esc_attr( get_post_meta( $product_id, 'woobt_sync_qty', true ) === 'on' ? 'checked' : '' ); ?>/>
                            <label for="woobt_sync_qty"><?php esc_html_e( 'Sync the quantity of the main product with associated products.', 'woo-bought-together' ); ?></label>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space woobt_tr_show_if_custom_qty">
                        <th><?php esc_html_e( 'Limit each item', 'woo-bought-together' ); ?></th>
                        <td>
                            <input id="woobt_limit_each_min_default" name="woobt_limit_each_min_default"
                                   type="checkbox" <?php echo esc_attr( get_post_meta( $product_id, 'woobt_limit_each_min_default', true ) === 'on' ? 'checked' : '' ); ?>/>
                            <label for="woobt_limit_each_min_default"><?php esc_html_e( 'Use default quantity as min', 'woo-bought-together' ); ?></label>
                            <u>or</u> Min <label>
                                <input name="woobt_limit_each_min" type="number" min="0"
                                       value="<?php echo esc_attr( get_post_meta( $product_id, 'woobt_limit_each_min', true ) ?: '' ); ?>"
                                       style="width: 60px; float: none"/>
                            </label> Max <label>
                                <input name="woobt_limit_each_max" type="number" min="1"
                                       value="<?php echo esc_attr( get_post_meta( $product_id, 'woobt_limit_each_max', true ) ?: '' ); ?>"
                                       style="width: 60px; float: none"/>
                            </label>
                        </td>
                    </tr>
					<?php do_action( 'woobt_product_settings', $product_id ); ?>
                    <tr class="woobt_tr_space">
                        <th><?php esc_html_e( 'Displaying', 'woo-bought-together' ); ?></th>
                        <td>
                            <a href="#"
                               class="woobt_displaying"><?php esc_html_e( 'Overwrite the default displaying settings', 'woo-bought-together' ); ?></a>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space woobt_show_if_displaying">
                        <th><?php esc_html_e( 'Layout', 'woo-bought-together' ); ?></th>
                        <td>
                            <label> <select name="woobt_layout">
                                    <option value="unset" <?php selected( $layout, 'unset' ); ?>><?php esc_html_e( 'Unset (default setting)', 'woo-bought-together' ); ?></option>
                                    <option value="default" <?php selected( $layout, 'default' ); ?>><?php esc_html_e( 'List', 'woo-bought-together' ); ?></option>
                                    <option value="compact" <?php selected( $layout, 'compact' ); ?>><?php esc_html_e( 'Compact', 'woo-bought-together' ); ?></option>
                                    <option value="separate" <?php selected( $layout, 'separate' ); ?>><?php esc_html_e( 'Separate images', 'woo-bought-together' ); ?></option>
                                    <option value="grid-2" <?php selected( $layout, 'grid-2' ); ?>><?php esc_html_e( 'Grid - 2 columns', 'woo-bought-together' ); ?></option>
                                    <option value="grid-3" <?php selected( $layout, 'grid-3' ); ?>><?php esc_html_e( 'Grid - 3 columns', 'woo-bought-together' ); ?></option>
                                    <option value="grid-4" <?php selected( $layout, 'grid-4' ); ?>><?php esc_html_e( 'Grid - 4 columns', 'woo-bought-together' ); ?></option>
                                    <option value="carousel-2" <?php selected( $layout, 'carousel-2' ); ?>><?php esc_html_e( 'Carousel - 2 columns', 'woo-bought-together' ); ?></option>
                                    <option value="carousel-3" <?php selected( $layout, 'carousel-3' ); ?>><?php esc_html_e( 'Carousel - 3 columns', 'woo-bought-together' ); ?></option>
                                    <option value="carousel-4" <?php selected( $layout, 'carousel-4' ); ?>><?php esc_html_e( 'Carousel - 4 columns', 'woo-bought-together' ); ?></option>
                                </select> </label>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space woobt_show_if_displaying">
                        <th><?php esc_html_e( 'Position', 'woo-bought-together' ); ?></th>
                        <td>
							<?php
							if ( is_array( self::$positions ) && ( count( self::$positions ) > 0 ) ) {
								echo '<select name="woobt_position">';

								echo '<option value="unset" ' . ( 'unset' === $position ? 'selected' : '' ) . '>' . esc_html__( 'Unset (default setting)', 'woo-bought-together' ) . '</option>';

								foreach ( self::$positions as $k => $p ) {
									echo '<option value="' . esc_attr( $k ) . '" ' . ( $k === $position ? 'selected' : '' ) . '>' . esc_html( $p ) . '</option>';
								}

								echo '</select>';
							}
							?>
                            <span class="description"><?php esc_html_e( 'Choose the position to show the products list. You also can use the shortcode [woobt] to show the list where you want.', 'woo-bought-together' ); ?></span>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space woobt_show_if_displaying">
                        <th><?php esc_html_e( 'Add to cart button', 'woo-bought-together' ); ?></th>
                        <td>
                            <label> <select name="woobt_atc_button" class="woobt_atc_button">
                                    <option value="unset" <?php selected( $atc_button, 'unset' ); ?>><?php esc_html_e( 'Unset (default setting)', 'woo-bought-together' ); ?></option>
                                    <option value="main" <?php selected( $atc_button, 'main' ); ?>><?php esc_html_e( 'Main product\'s button', 'woo-bought-together' ); ?></option>
                                    <option value="separate" <?php selected( $atc_button, 'separate' ); ?>><?php esc_html_e( 'Separate buttons', 'woo-bought-together' ); ?></option>
                                </select> </label>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space woobt_show_if_displaying">
                        <th><?php esc_html_e( 'Show "this item"', 'woo-bought-together' ); ?></th>
                        <td>
                            <label> <select name="woobt_show_this_item" class="woobt_show_this_item">
                                    <option value="unset" <?php selected( $show_this_item, 'unset' ); ?>><?php esc_html_e( 'Unset (default setting)', 'woo-bought-together' ); ?></option>
                                    <option value="yes" <?php selected( $show_this_item, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-bought-together' ); ?></option>
                                    <option value="no" <?php selected( $show_this_item, 'no' ); ?>><?php esc_html_e( 'No', 'woo-bought-together' ); ?></option>
                                </select> </label>
                            <span class="description"><?php esc_html_e( '"This item" cannot be hidden if "Separate buttons" is in use for the Add to Cart button.', 'woo-bought-together' ); ?></span>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space woobt_show_if_displaying">
                        <th><?php esc_html_e( 'Above text', 'woo-bought-together' ); ?></th>
                        <td>
                            <div class="w100">
                                <label>
                                            <textarea name="woobt_before_text" rows="1"
                                                      style="width: 100%"><?php echo esc_textarea( get_post_meta( $product_id, 'woobt_before_text', true ) ); ?></textarea>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr class="woobt_tr_space woobt_show_if_displaying">
                        <th><?php esc_html_e( 'Under text', 'woo-bought-together' ); ?></th>
                        <td>
                            <div class="w100">
                                <label>
                                            <textarea name="woobt_after_text" rows="1"
                                                      style="width: 100%"><?php echo esc_textarea( get_post_meta( $product_id, 'woobt_after_text', true ) ); ?></textarea>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
			<?php
		}

		function process_product_meta( $post_id ) {
			if ( isset( $_POST['woobt_disable'] ) ) {
				update_post_meta( $post_id, 'woobt_disable', 'yes' );
			} else {
				update_post_meta( $post_id, 'woobt_disable', 'no' );
			}

			if ( isset( $_POST['woobt_ids'] ) ) {
				update_post_meta( $post_id, 'woobt_ids', WPCleverWoobt_Helper()->sanitize_array( $_POST['woobt_ids'] ) );
			} else {
				delete_post_meta( $post_id, 'woobt_ids' );
			}

			if ( isset( $_POST['woobt_discount'] ) ) {
				update_post_meta( $post_id, 'woobt_discount', sanitize_text_field( $_POST['woobt_discount'] ) );
			}

			if ( isset( $_POST['woobt_checked_all'] ) ) {
				update_post_meta( $post_id, 'woobt_checked_all', 'on' );
			} else {
				update_post_meta( $post_id, 'woobt_checked_all', 'off' );
			}

			if ( isset( $_POST['woobt_separately'] ) ) {
				update_post_meta( $post_id, 'woobt_separately', 'on' );
			} else {
				update_post_meta( $post_id, 'woobt_separately', 'off' );
			}

			if ( isset( $_POST['woobt_selection'] ) ) {
				update_post_meta( $post_id, 'woobt_selection', sanitize_text_field( $_POST['woobt_selection'] ) );
			}

			if ( isset( $_POST['woobt_custom_qty'] ) ) {
				update_post_meta( $post_id, 'woobt_custom_qty', 'on' );
			} else {
				update_post_meta( $post_id, 'woobt_custom_qty', 'off' );
			}

			if ( isset( $_POST['woobt_sync_qty'] ) ) {
				update_post_meta( $post_id, 'woobt_sync_qty', 'on' );
			} else {
				update_post_meta( $post_id, 'woobt_sync_qty', 'off' );
			}

			if ( isset( $_POST['woobt_limit_each_min_default'] ) ) {
				update_post_meta( $post_id, 'woobt_limit_each_min_default', 'on' );
			} else {
				update_post_meta( $post_id, 'woobt_limit_each_min_default', 'off' );
			}

			if ( isset( $_POST['woobt_limit_each_min'] ) ) {
				update_post_meta( $post_id, 'woobt_limit_each_min', sanitize_text_field( $_POST['woobt_limit_each_min'] ) );
			}

			if ( isset( $_POST['woobt_limit_each_max'] ) ) {
				update_post_meta( $post_id, 'woobt_limit_each_max', sanitize_text_field( $_POST['woobt_limit_each_max'] ) );
			}

			// overwrite displaying

			if ( isset( $_POST['woobt_layout'] ) ) {
				update_post_meta( $post_id, 'woobt_layout', sanitize_text_field( $_POST['woobt_layout'] ) );
			}

			if ( isset( $_POST['woobt_position'] ) ) {
				update_post_meta( $post_id, 'woobt_position', sanitize_text_field( $_POST['woobt_position'] ) );
			}

			if ( isset( $_POST['woobt_atc_button'] ) ) {
				update_post_meta( $post_id, 'woobt_atc_button', sanitize_text_field( $_POST['woobt_atc_button'] ) );
			}

			if ( isset( $_POST['woobt_show_this_item'] ) ) {
				update_post_meta( $post_id, 'woobt_show_this_item', sanitize_text_field( $_POST['woobt_show_this_item'] ) );
			}

			if ( isset( $_POST['woobt_before_text'] ) ) {
				update_post_meta( $post_id, 'woobt_before_text', sanitize_post_field( 'post_content', $_POST['woobt_before_text'], $post_id, 'display' ) );
			}

			if ( isset( $_POST['woobt_after_text'] ) ) {
				update_post_meta( $post_id, 'woobt_after_text', sanitize_post_field( 'post_content', $_POST['woobt_after_text'], $post_id, 'display' ) );
			}
		}

		function ajax_import_export() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woobt-security' ) || ! current_user_can( 'manage_options' ) ) {
				die( 'Permissions check failed!' );
			}

			$ids      = [];
			$ids_arr  = [];
			$ids_data = sanitize_post( $_POST['ids'] ?? '' );
			parse_str( $ids_data, $ids_arr );

			if ( isset( $ids_arr['woobt_ids'] ) && is_array( $ids_arr['woobt_ids'] ) ) {
				$ids = $ids_arr['woobt_ids'];
			}

			echo '<textarea class="woobt_import_export_data" style="width: 100%; height: 200px">' . esc_textarea( ( ! empty( $ids ) ? wp_json_encode( $ids ) : '' ) ) . '</textarea>';
			echo '<div style="display: flex; align-items: center"><button class="button button-primary woobt-import-export-save">' . esc_html__( 'Update', 'woo-product-timer' ) . '</button>';
			echo '<span style="color: #ff4f3b; font-size: 10px; margin-left: 10px">' . esc_html__( '* All current selected products will be replaced after pressing Update!', 'woo-product-timer' ) . '</span>';
			echo '</div>';

			wp_die();
		}

		function ajax_import_export_save() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woobt-security' ) || ! current_user_can( 'manage_options' ) ) {
				die( 'Permissions check failed!' );
			}

			$ids = sanitize_textarea_field( $_POST['ids'] ?? '' );

			if ( ! empty( $ids ) ) {
				$items = json_decode( stripcslashes( $ids ), true );

				if ( ! empty( $items ) ) {
					foreach ( $items as $item_key => $item ) {
						if ( ! empty( $item['id'] ) ) {
							$item_id      = $item['id'];
							$item_price   = $item['price'];
							$item_qty     = $item['qty'];
							$item_product = wc_get_product( $item_id );

							if ( ! $item_product ) {
								continue;
							}

							self::product_data_li( $item_product, $item_price, $item_qty, false, $item_key );
						} else {
							self::text_data_li( $item, $item_key );
						}
					}
				}
			}

			wp_die();
		}

		function product_price_class( $class ) {
			global $product;

			return $class . ' woobt-price-' . $product->get_id();
		}

		function show_items_position( $pos = 'before' ) {
			global $product;

			if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
				return;
			}

			$_position = get_post_meta( $product->get_id(), 'woobt_position', true ) ?: 'unset';
			$position  = $_position !== 'unset' ? $_position : apply_filters( 'woobt_position', WPCleverWoobt_Helper()->get_setting( 'position', apply_filters( 'woobt_default_position', 'before' ) ) );

			if ( $position === $pos ) {
				self::show_items();
			}
		}

		function show_items_before_atc() {
			self::show_items_position( 'before' );
		}

		function show_items_after_atc() {
			self::show_items_position( 'after' );
		}

		function show_items_below_title() {
			self::show_items_position( 'below_title' );
		}

		function show_items_below_price() {
			self::show_items_position( 'below_price' );
		}

		function show_items_below_excerpt() {
			self::show_items_position( 'below_excerpt' );
		}

		function show_items_below_meta() {
			self::show_items_position( 'below_meta' );
		}

		function show_items_below_summary() {
			self::show_items_position( 'below_summary' );
		}

		function add_to_cart_button() {
			global $product;

			if ( ! $product || ! is_a( $product, 'WC_Product' ) || $product->is_type( 'grouped' ) || $product->is_type( 'external' ) ) {
				return;
			}

			$product_id  = $product->get_id();
			$_position   = get_post_meta( $product_id, 'woobt_position', true ) ?: 'unset';
			$_atc_button = get_post_meta( $product_id, 'woobt_atc_button', true ) ?: 'unset';
			$position    = $_position !== 'unset' ? $_position : apply_filters( 'woobt_position', WPCleverWoobt_Helper()->get_setting( 'position', apply_filters( 'woobt_default_position', 'before' ) ) );
			$atc_button  = apply_filters( 'woobt_atc_button', $_atc_button !== 'unset' ? $_atc_button : WPCleverWoobt_Helper()->get_setting( 'atc_button', 'main' ), $product_id );

			if ( ( $atc_button === 'main' || $atc_button === 'both' ) && ( $position !== 'none' ) ) {
				echo '<input name="woobt_ids" class="woobt-ids woobt-ids-' . esc_attr( $product_id ) . '" data-id="' . esc_attr( $product_id ) . '" type="hidden"/>';
			}
		}

		function has_variables( $items ) {
			foreach ( $items as $item ) {
				if ( is_array( $item ) && isset( $item['id'] ) ) {
					$item_id = $item['id'];
				} else {
					$item_id = absint( $item );
				}

				$item_product = wc_get_product( $item_id );

				if ( ! $item_product ) {
					continue;
				}

				if ( $item_product->is_type( 'variable' ) ) {
					return true;
				}
			}

			return false;
		}

		function shortcode( $attrs ) {
			$attrs = shortcode_atts( [ 'id' => null, 'custom_position' => true ], $attrs );

			ob_start();

			self::show_items( $attrs['id'], wc_string_to_bool( $attrs['custom_position'] ) );

			return ob_get_clean();
		}

		function show_items( $product = null, $custom_position = false, $is_variation = false ) {
			$product_id = 0;

			if ( ! $product ) {
				global $product;

				if ( $product ) {
					$product_id = $product->get_id();
				}
			} else {
				if ( is_a( $product, 'WC_Product' ) ) {
					$product_id = $product->get_id();
				}

				if ( is_numeric( $product ) ) {
					$product_id = absint( $product );
					$product    = wc_get_product( $product_id );
				}
			}

			if ( ! $product_id || ! $product || $product->is_type( 'grouped' ) || $product->is_type( 'external' ) ) {
				return;
			}

			if ( ! $is_variation ) {
				wp_enqueue_script( 'wc-add-to-cart-variation' );
			}

			$custom_qty  = apply_filters( 'woobt_custom_qty', get_post_meta( $product_id, 'woobt_custom_qty', true ) === 'on', $product_id );
			$sync_qty    = apply_filters( 'woobt_sync_qty', get_post_meta( $product_id, 'woobt_sync_qty', true ) === 'on', $product_id );
			$checked_all = apply_filters( 'woobt_checked_all', get_post_meta( $product_id, 'woobt_checked_all', true ) === 'on', $product_id );
			$separately  = apply_filters( 'woobt_separately', get_post_meta( $product_id, 'woobt_separately', true ) === 'on', $product_id );
			$separately  &= apply_filters( 'woobt_separately_reset_price', true, $product_id, 'view' ); // change it to false if you want to keep the discounted price
			$selection   = apply_filters( 'woobt_selection', get_post_meta( $product_id, 'woobt_selection', true ) ?: 'multiple', $product_id );

			$_position       = get_post_meta( $product_id, 'woobt_position', true ) ?: 'unset';
			$_layout         = get_post_meta( $product_id, 'woobt_layout', true ) ?: 'unset';
			$_atc_button     = get_post_meta( $product_id, 'woobt_atc_button', true ) ?: 'unset';
			$_show_this_item = get_post_meta( $product_id, 'woobt_show_this_item', true ) ?: 'unset';

			// settings
			$pricing          = WPCleverWoobt_Helper()->get_setting( 'pricing', 'sale_price' );
			$plus_minus       = WPCleverWoobt_Helper()->get_setting( 'plus_minus', 'no' ) === 'yes';
			$position         = $_position !== 'unset' ? $_position : apply_filters( 'woobt_position', WPCleverWoobt_Helper()->get_setting( 'position', apply_filters( 'woobt_default_position', 'before' ) ) );
			$layout           = apply_filters( 'woobt_layout', $_layout !== 'unset' ? $_layout : WPCleverWoobt_Helper()->get_setting( 'layout', 'default' ), $product_id );
			$show_this_item   = apply_filters( 'woobt_show_this_item', $_show_this_item !== 'unset' ? $_show_this_item : WPCleverWoobt_Helper()->get_setting( 'show_this_item', 'yes' ), $product_id );
			$atc_button       = apply_filters( 'woobt_atc_button', $_atc_button !== 'unset' ? $_atc_button : WPCleverWoobt_Helper()->get_setting( 'atc_button', 'main' ), $product_id );
			$separate_atc     = $atc_button === 'separate' || $atc_button === 'both';
			$separate_images  = $layout === 'separate';
			$hide_this_item   = apply_filters( 'woobt_hide_this_item', ! $custom_position && ! $separate_atc && ! wc_string_to_bool( $show_this_item ), $product_id );
			$ignore_this_item = apply_filters( 'woobt_separately_ignore_this_item', false, $product_id );
			$discount         = $separately ? '0' : self::get_discount( $product_id );

			if ( ! $is_variation ) {
				$wrap_class = 'woobt-wrap woobt-layout-' . esc_attr( $layout ) . ' woobt-wrap-' . esc_attr( $product_id ) . ' ' . ( WPCleverWoobt_Helper()->get_setting( 'responsive', 'yes' ) === 'yes' ? 'woobt-wrap-responsive' : '' );

				if ( $custom_position ) {
					$wrap_class .= ' woobt-wrap-custom-position';
				}

				if ( $separate_atc ) {
					$wrap_class .= ' woobt-wrap-separate-atc';
				}

				$sku        = htmlentities( $product->get_sku() );
				$weight     = htmlentities( wc_format_weight( $product->get_weight() ) );
				$dimensions = htmlentities( wc_format_dimensions( $product->get_dimensions( false ) ) );
				$price_html = htmlentities( $product->get_price_html() );

				$wrap_attrs = apply_filters( 'woobt_wrap_data_attributes', [
					'id'                   => $product_id,
					'selection'            => $selection,
					'position'             => $position,
					'atc-button'           => $atc_button,
					'this-item'            => $hide_this_item ? 'no' : 'yes',
					'ignore-this'          => $ignore_this_item ? 'yes' : 'no',
					'separately'           => $separately ? 'on' : 'off',
					'layout'               => $layout,
					'product-id'           => $product->is_type( 'variable' ) ? '0' : $product_id,
					'product-sku'          => $sku,
					'product-o_sku'        => $sku,
					'product-weight'       => $weight,
					'product-o_weight'     => $weight,
					'product-dimensions'   => $dimensions,
					'product-o_dimensions' => $dimensions,
					'product-price-html'   => $price_html,
					'product-o_price-html' => $price_html,
				], $product );

				echo '<div class="' . esc_attr( $wrap_class ) . '" ' . WPCleverWoobt_Helper()->data_attributes( $wrap_attrs ) . '>';
			}

			// get items
			$items = apply_filters( 'woobt_show_items', self::get_items( $product_id, 'view' ), $product_id );

			if ( ! empty( $items ) ) {
				// format items
				foreach ( $items as $key => $item ) {
					if ( is_array( $item ) ) {
						if ( ! empty( $item['id'] ) ) {
							$_item['id']    = $item['id'];
							$_item['price'] = $item['price'];
							$_item['qty']   = $item['qty'];
						} else {
							// heading/paragraph
							$_item = $item;
						}
					} else {
						// make it works with upsells/cross-sells/related
						$_item['id']    = absint( $item );
						$_item['price'] = WPCleverWoobt_Helper()->get_setting( 'default_price', '100%' );
						$_item['qty']   = 1;
					}

					if ( ! empty( $_item['id'] ) ) {
						if ( $_item_product = wc_get_product( $_item['id'] ) ) {
							$_item['product'] = $_item_product;
						} else {
							unset( $items[ $key ] );
							continue;
						}
					}

					if ( ! empty( $_item['product'] ) && ( ! in_array( $_item['product']->get_type(), self::$types, true ) || ( ( WPCleverWoobt_Helper()->get_setting( 'exclude_unpurchasable', 'no' ) === 'yes' ) && ( ! $_item['product']->is_purchasable() || ! $_item['product']->is_in_stock() ) ) ) ) {
						unset( $items[ $key ] );
						continue;
					}

					if ( ! empty( $_item['product'] ) && ! apply_filters( 'woobt_item_visible', $_item['product']->get_status() === 'publish', $_item ) ) {
						unset( $items[ $key ] );
						continue;
					}

					$items[ $key ] = $_item;
				}
			}

			if ( ! empty( $items ) ) {
				$before_text = apply_filters( 'woobt_before_text', self::get_text( $product, 'before' ), $product_id );
				$after_text  = apply_filters( 'woobt_after_text', self::get_text( $product, 'after' ), $product_id );

				// show items
				do_action( 'woobt_wrap_before', $product );

				if ( ! empty( $before_text ) ) {
					do_action( 'woobt_before_text_above', $product );
					echo '<div class="woobt-before-text woobt-text">' . wp_kses_post( do_shortcode( $before_text ) ) . '</div>';
					do_action( 'woobt_before_text_below', $product );
				}

				if ( $layout === 'compact' ) {
					echo '<div class="woobt-inner">';
				}

				if ( $separate_images ) {
					do_action( 'woobt_images_above', $product );
					?>
                    <div class="woobt-images">
						<?php
						do_action( 'woobt_images_before', $product );

						if ( ! $ignore_this_item ) {
							echo '<div class="woobt-image woobt-image-this woobt-image-order-0 woobt-image-' . esc_attr( $product_id ) . '">';
							do_action( 'woobt_product_thumb_before', $product, 0, 'separate' );
							echo '<span class="woobt-img woobt-img-order-0" data-img="' . esc_attr( htmlentities( $product->get_image( self::$image_size ) ) ) . '">' . $product->get_image( self::$image_size ) . '</span>';
							do_action( 'woobt_product_thumb_after', $product, 0, 'separate' );
							echo '</div>';
						}

						$order = 1;

						foreach ( $items as $item ) {
							if ( ! empty( $item['id'] ) ) {
								$item_product     = $item['product'];
								$item_image_class = 'woobt-image woobt-image-order-' . $order . ' woobt-image-' . $item['id'];

								echo '<div class="' . esc_attr( $item_image_class ) . '" data-order="' . esc_attr( $order ) . '">';

								do_action( 'woobt_product_thumb_before', $item_product, $order, 'separate', $item );

								if ( WPCleverWoobt_Helper()->get_setting( 'link', 'yes' ) !== 'no' ) {
									echo '<a class="' . esc_attr( WPCleverWoobt_Helper()->get_setting( 'link', 'yes' ) === 'yes_popup' ? 'woosq-link woobt-img woobt-img-order-' . $order : 'woobt-img woobt-img-order-' . $order ) . '" data-id="' . esc_attr( $item['id'] ) . '" data-context="woobt" href="' . $item_product->get_permalink() . '" data-img="' . esc_attr( htmlentities( $item_product->get_image( self::$image_size ) ) ) . '" ' . ( WPCleverWoobt_Helper()->get_setting( 'link', 'yes' ) === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . $item_product->get_image( self::$image_size ) . '</a>';
								} else {
									echo '<span class="' . esc_attr( 'woobt-img woobt-img-order-' . $order ) . '" data-img="' . esc_attr( htmlentities( $item_product->get_image( self::$image_size ) ) ) . '">' . $item_product->get_image( self::$image_size ) . '</span>';
								}

								do_action( 'woobt_product_thumb_after', $item_product, $order, 'separate', $item );

								echo '</div>';
								$order ++;
							}
						}

						do_action( 'woobt_images_after', $product );
						?>
                    </div>
					<?php
					do_action( 'woobt_images_below', $product );
				}

				$products_class = apply_filters( 'woobt_products_class', 'woobt-products woobt-products-layout-' . $layout . ' woobt-products-' . $product_id, $product );
				$products_attrs = apply_filters( 'woobt_products_data_attributes', [
					'show-price'           => WPCleverWoobt_Helper()->get_setting( 'show_price', 'yes' ),
					'optional'             => $custom_qty ? 'on' : 'off',
					'separately'           => $separately ? 'on' : 'off',
					'sync-qty'             => $sync_qty ? 'on' : 'off',
					'variables'            => self::has_variables( $items ) ? 'yes' : 'no',
					'product-id'           => $product->is_type( 'variable' ) ? '0' : $product_id,
					'product-type'         => $product->get_type(),
					'product-price-suffix' => htmlentities( $product->get_price_suffix() ),
					'pricing'              => $pricing,
					'discount'             => $discount,
				], $product );

				do_action( 'woobt_products_above', $product );
				?>
                <div class="<?php echo esc_attr( $products_class ); ?>" <?php echo WPCleverWoobt_Helper()->data_attributes( $products_attrs ); ?>>
					<?php
					do_action( 'woobt_products_before', $product );

					if ( ! $ignore_this_item ) {
						// this item
						$this_item_quantity = apply_filters( 'woobt_this_item_quantity', false, $product );
						$this_item_name     = apply_filters( 'woobt_product_get_name', $product->get_name(), $product );
						$this_item_attrs    = apply_filters( 'woobt_this_item_data_attributes', [
							'order'         => 0,
							'qty'           => 1,
							'o_qty'         => 1,
							'id'            => $product->is_type( 'variable' ) || ! $product->is_in_stock() ? 0 : $product_id,
							'pid'           => $product_id,
							'name'          => $this_item_name,
							'price'         => apply_filters( 'woobt_item_data_price', wc_get_price_to_display( $product ), $product ),
							'regular-price' => apply_filters( 'woobt_item_data_regular_price', wc_get_price_to_display( $product, [ 'price' => $product->get_regular_price() ] ), $product ),
							'new-price'     => ! $separately && ( $discount = get_post_meta( $product_id, 'woobt_discount', true ) ) ? ( 100 - (float) $discount ) . '%' : '100%',
							'price-suffix'  => htmlentities( $product->get_price_suffix() )
						], $product );

						ob_start();

						if ( $hide_this_item ) {
							?>
                            <div class="woobt-product woobt-product-this woobt-hide-this" <?php echo WPCleverWoobt_Helper()->data_attributes( $this_item_attrs ); ?>>
                                <div class="woobt-choose">
                                    <label for="woobt_checkbox_0"><?php echo esc_html( $this_item_name ); ?></label>
                                    <input id="woobt_checkbox_0" class="woobt-checkbox woobt-checkbox-this"
                                           type="checkbox" checked disabled/>
                                    <span class="checkmark"></span>
                                </div>
                            </div>
						<?php } else { ?>
                            <div class="woobt-product woobt-product-this" <?php echo WPCleverWoobt_Helper()->data_attributes( $this_item_attrs ); ?>>

								<?php do_action( 'woobt_product_before', $product ); ?>

                                <div class="woobt-choose">
                                    <label for="woobt_checkbox_0"><?php echo esc_html( $this_item_name ); ?></label>
                                    <input id="woobt_checkbox_0" class="woobt-checkbox woobt-checkbox-this"
                                           type="checkbox" checked disabled/>
                                    <span class="checkmark"></span>
                                </div>

								<?php if ( ! $separate_images && ( WPCleverWoobt_Helper()->get_setting( 'show_thumb', 'yes' ) !== 'no' ) ) {
									echo '<div class="woobt-thumb">';
									do_action( 'woobt_product_thumb_before', $product, 0, 'default' );
									echo '<span class="woobt-img woobt-img-order-0" data-img="' . esc_attr( htmlentities( $product->get_image( self::$image_size ) ) ) . '">' . $product->get_image( self::$image_size ) . '</span>';
									do_action( 'woobt_product_thumb_after', $product, 0, 'default' );
									echo '</div>';
								} ?>

                                <div class="woobt-title">
                                <span class="woobt-title-inner">
                                    <?php echo apply_filters( 'woobt_product_this_name', '<span>' . WPCleverWoobt_Helper()->localization( 'this_item', esc_html__( 'This item:', 'woo-bought-together' ) ) . '</span> <span>' . apply_filters( 'woobt_product_get_name', $product->get_name(), $product ) . '</span>', $product ); ?>
                                </span>

									<?php if ( $separate_images && ( WPCleverWoobt_Helper()->get_setting( 'show_price', 'yes' ) !== 'no' ) ) { ?>
                                        <span class="woobt-price">
                                        <span class="woobt-price-new">
                                            <?php
                                            if ( ! $separately && ( $discount = get_post_meta( $product_id, 'woobt_discount', true ) ) ) {
	                                            $sale_price = $product->get_price() * ( 100 - (float) $discount ) / 100;
	                                            echo wc_format_sale_price( $product->get_price(), $sale_price ) . $product->get_price_suffix( $sale_price );
                                            } else {
	                                            echo $product->get_price_html();
                                            }
                                            ?>
                                        </span>
                                        <span class="woobt-price-ori">
                                            <?php echo $product->get_price_html(); ?>
                                        </span>
                                    </span>
									<?php }

									if ( ( $separate_atc || $custom_position ) && $product->is_type( 'variable' ) ) {
										// this item's variations
										if ( ( WPCleverWoobt_Helper()->get_setting( 'variations_selector', 'default' ) === 'woovr' ) && class_exists( 'WPClever_Woovr' ) ) {
											echo '<div class="wpc_variations_form">';
											// use class name wpc_variations_form to prevent found_variation in woovr
											WPClever_Woovr::woovr_variations_form( $product, false, 'woobt' );
											echo '</div>';
										} else {
											$attributes           = $product->get_variation_attributes();
											$available_variations = $product->get_available_variations();

											if ( is_array( $attributes ) && ( count( $attributes ) > 0 ) ) {
												echo '<div class="variations_form woobt_variations_form" action="' . esc_url( $product->get_permalink() ) . '" data-product_id="' . absint( $product_id ) . '" data-product_variations="' . htmlspecialchars( wp_json_encode( $available_variations ) ) . '">';

												if ( apply_filters( 'woobt_variations_table_layout', false ) ) {
													echo '<table class="variations" cellspacing="0" role="presentation"><tbody>';

													foreach ( $attributes as $attribute_name => $options ) {
														$attribute_name_sz = sanitize_title( $attribute_name );
														?>
                                                        <tr class="<?php echo esc_attr( 'variation variation-' . $attribute_name_sz ); ?>">
                                                            <th class="label">
                                                                <label for="<?php echo esc_attr( $attribute_name_sz ); ?>"><?php echo esc_html( wc_attribute_label( $attribute_name ) ); ?></label>
                                                            </th>
                                                            <td class="value">
																<?php
																$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
																wc_dropdown_variation_attribute_options( [
																	'options'          => $options,
																	'attribute'        => $attribute_name,
																	'product'          => $product,
																	'selected'         => $selected,
																	'show_option_none' => sprintf( WPCleverWoobt_Helper()->localization( 'choose', /* translators: attribute name */ esc_html__( 'Choose %s', 'woo-bought-together' ) ), wc_attribute_label( $attribute_name ) )
																] );
																?>
                                                            </td>
                                                        </tr>
													<?php }

													echo '</tbody></table><!-- /.variations -->';
												} else {
													echo '<div class="variations">';

													foreach ( $attributes as $attribute_name => $options ) {
														$attribute_name_sz = sanitize_title( $attribute_name );
														?>
                                                        <div class="<?php echo esc_attr( 'variation variation-' . $attribute_name_sz ); ?>">
                                                            <div class="label">
                                                                <label for="<?php echo esc_attr( $attribute_name_sz ); ?>"><?php echo esc_html( wc_attribute_label( $attribute_name ) ); ?></label>
                                                            </div>
                                                            <div class="value">
																<?php
																$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
																wc_dropdown_variation_attribute_options( [
																	'options'          => $options,
																	'attribute'        => $attribute_name,
																	'product'          => $product,
																	'selected'         => $selected,
																	'show_option_none' => sprintf( WPCleverWoobt_Helper()->localization( 'choose', /* translators: attribute name */ esc_html__( 'Choose %s', 'woo-bought-together' ) ), wc_attribute_label( $attribute_name ) )
																] );
																?>
                                                            </div>
                                                        </div>
													<?php }

													echo '</div><!-- /.variations -->';
												}

												echo '<div class="reset">' . apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . WPCleverWoobt_Helper()->localization( 'clear', esc_html__( 'Clear', 'woo-bought-together' ) ) . '</a>' ) . '</div>';
												echo '</div><!-- /.variations_form -->';

												if ( WPCleverWoobt_Helper()->get_setting( 'show_description', 'no' ) === 'yes' ) {
													echo '<div class="woobt-variation-description"></div>';
												}
											}
										}
									}

									echo '<div class="woobt-availability">' . wc_get_stock_html( $product ) . '</div>';
									?>
                                </div>

								<?php if ( ( $separate_atc || $custom_position || $this_item_quantity ) && $custom_qty ) {
									echo '<div class="' . esc_attr( ( $plus_minus ? 'woobt-quantity woobt-quantity-plus-minus' : 'woobt-quantity' ) ) . '">';

									if ( $plus_minus ) {
										echo '<div class="woobt-quantity-input">';
										echo '<div class="woobt-quantity-input-minus">-</div>';
									}

									$qty_args = [
										'classes'    => [
											'input-text',
											'woobt-qty',
											'woobt_qty',
											'woobt-this-qty',
											'qty',
											'text'
										],
										'input_name' => 'woobt_qty_0',
										'min_value'  => $product->get_min_purchase_quantity(),
										'max_value'  => $product->get_max_purchase_quantity(),
									];

									if ( apply_filters( 'woobt_use_woocommerce_quantity_input', true ) ) {
										woocommerce_quantity_input( $qty_args, $product );
									} else {
										echo apply_filters( 'woobt_quantity_input', '<input type="number" name="woobt_qty_0" class="woobt-qty woobt-this-qty woobt_qty input-text qty text" value="1" min="' . esc_attr( $product->get_min_purchase_quantity() ) . '" max="' . esc_attr( $product->get_max_purchase_quantity() ) . '" />', $qty_args, $product );
									}

									if ( $plus_minus ) {
										echo '<div class="woobt-quantity-input-plus">+</div>';
										echo '</div>';
									}

									echo '</div>';
								}

								if ( ! $separate_images && ( WPCleverWoobt_Helper()->get_setting( 'show_price', 'yes' ) !== 'no' ) ) { ?>
                                    <div class="woobt-price">
                                        <div class="woobt-price-new">
											<?php
											if ( ! $separately && ( $discount = get_post_meta( $product_id, 'woobt_discount', true ) ) ) {
												$sale_price = $product->get_price() * ( 100 - (float) $discount ) / 100;
												echo wc_format_sale_price( $product->get_price(), $sale_price ) . $product->get_price_suffix( $sale_price );
											} else {
												echo $product->get_price_html();
											}
											?>
                                        </div>
                                        <div class="woobt-price-ori">
											<?php echo $product->get_price_html(); ?>
                                        </div>
                                    </div>
								<?php }

								do_action( 'woobt_product_after', $product );
								?>
                            </div><!-- /.woobt-product-this -->
							<?php
						}

						echo apply_filters( 'woobt_product_this_output', ob_get_clean(), $product, $custom_position );
					}

					// other items
					$order = 1;

					// store global $product
					$global_product = $product;

					foreach ( $items as $item_key => $item ) {
						if ( ! empty( $item['id'] ) ) {
							$item['key'] = $item_key;
							$product     = $item['product'];
							$item_id     = $item['id'];
							$item_price  = $item['price'];
							$item_qty    = $item['qty'];
							$item_min    = 1;
							$item_max    = 1000;

							if ( $custom_qty ) {
								if ( get_post_meta( $product_id, 'woobt_limit_each_min_default', true ) === 'on' ) {
									$item_min = $item_qty;
								} else {
									$item_min = absint( get_post_meta( $product_id, 'woobt_limit_each_min', true ) ?: 0 );
								}

								$item_min = absint( apply_filters( 'woobt_limit_each_min', $item_min, $item, $product_id ) );
								$item_max = absint( apply_filters( 'woobt_limit_each_max', get_post_meta( $product_id, 'woobt_limit_each_max', true ) ?: 1000, $item, $product_id ) );

								if ( ( $max_purchase = $product->get_max_purchase_quantity() ) && ( $max_purchase > 0 ) && ( $max_purchase < $item_max ) ) {
									// get_max_purchase_quantity can return -1
									$item_max = $max_purchase;
								}

								if ( $item_qty < $item_min ) {
									$item_qty = $item_min;
								}

								if ( ( $item_max > $item_min ) && ( $item_qty > $item_max ) ) {
									$item_qty = $item_max;
								}
							}

							$item_price         = apply_filters( 'woobt_item_price', ! $separately ? $item_price : '100%', $item, $product_id );
							$item_name          = apply_filters( 'woobt_product_get_name', $product->get_name(), $product );
							$checked_individual = apply_filters( 'woobt_checked_individual', false, $item, $product_id, $order );
							$item_checked       = apply_filters( 'woobt_item_checked', $product->is_in_stock() && ( $checked_individual || ( $checked_all && ( $selection === 'multiple' ) ) || ( $checked_all && ( $selection === 'single' ) && ( $order === 1 ) ) ), $item, $product_id, $order );
							$item_disabled      = apply_filters( 'woobt_item_disabled', ! $product->is_in_stock(), $item, $product_id, $order );
							$item_attrs         = apply_filters( 'woobt_item_data_attributes', [
								'key'           => $item_key,
								'order'         => $order,
								'id'            => $product->is_type( 'variable' ) || ! $product->is_in_stock() ? 0 : $item_id,
								'pid'           => $item_id,
								'name'          => $item_name,
								'new-price'     => $item_price,
								'price-suffix'  => htmlentities( $product->get_price_suffix() ),
								'price'         => apply_filters( 'woobt_item_data_price', ( $pricing === 'sale_price' ) ? wc_get_price_to_display( $product ) : wc_get_price_to_display( $product, [ 'price' => $product->get_regular_price() ] ), $product ),
								'regular-price' => apply_filters( 'woobt_item_data_regular_price', wc_get_price_to_display( $product, [ 'price' => $product->get_regular_price() ] ), $product ),
								'qty'           => $item_qty,
								'o_qty'         => $item_qty,
							], $item, $product_id, $order );

							ob_start();
							?>
                            <div class="woobt-product woobt-product-together" <?php echo WPCleverWoobt_Helper()->data_attributes( $item_attrs ); ?>>

								<?php do_action( 'woobt_product_before', $product, $order ); ?>

                                <div class="woobt-choose">
                                    <label for="<?php echo esc_attr( 'woobt_checkbox_' . $order ); ?>"><?php echo esc_html( $item_name ); ?></label>
                                    <input id="<?php echo esc_attr( 'woobt_checkbox_' . $order ); ?>"
                                           class="woobt-checkbox" type="checkbox"
                                           value="<?php echo esc_attr( $item_id ); ?>" <?php echo esc_attr( $item_disabled ? 'disabled' : '' ); ?> <?php echo esc_attr( $item_checked ? 'checked' : '' ); ?>/>
                                    <span class="checkmark"></span>
                                </div>

								<?php if ( ! $separate_images && ( WPCleverWoobt_Helper()->get_setting( 'show_thumb', 'yes' ) !== 'no' ) ) {
									echo '<div class="woobt-thumb">';

									do_action( 'woobt_product_thumb_before', $product, $order, 'default', $item );

									if ( WPCleverWoobt_Helper()->get_setting( 'link', 'yes' ) !== 'no' ) {
										echo '<a class="' . esc_attr( WPCleverWoobt_Helper()->get_setting( 'link', 'yes' ) === 'yes_popup' ? 'woosq-link woobt-img woobt-img-order-' . $order : 'woobt-img woobt-img-order-' . $order ) . '" data-id="' . esc_attr( $item_id ) . '" data-context="woobt" href="' . $product->get_permalink() . '" data-img="' . esc_attr( htmlentities( $product->get_image( self::$image_size ) ) ) . '" ' . ( WPCleverWoobt_Helper()->get_setting( 'link', 'yes' ) === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . $product->get_image( self::$image_size ) . '</a>';
									} else {
										echo '<span class="' . esc_attr( 'woobt-img woobt-img-order-' . $order ) . '" data-img="' . esc_attr( htmlentities( $product->get_image( self::$image_size ) ) ) . '">' . $product->get_image( self::$image_size ) . '</span>';
									}

									do_action( 'woobt_product_thumb_after', $product, $order, 'default', $item );

									echo '</div>';
								} ?>

                                <div class="woobt-title">
									<?php
									echo '<span class="woobt-title-inner">';

									do_action( 'woobt_product_name_before', $product, $order );

									if ( ! $custom_qty ) {
										$product_qty = '<span class="woobt-qty-num"><span class="woobt-qty">' . $item_qty . '</span> × </span>';
									} else {
										$product_qty = '';
									}

									echo apply_filters( 'woobt_product_qty', $product_qty, $item_qty, $product );

									if ( $product->is_in_stock() ) {
										$product_name = apply_filters( 'woobt_product_get_name', $product->get_name(), $product );
									} else {
										$product_name = '<s>' . apply_filters( 'woobt_product_get_name', $product->get_name(), $product ) . '</s>';
									}

									if ( WPCleverWoobt_Helper()->get_setting( 'link', 'yes' ) !== 'no' ) {
										$product_name = '<a ' . ( WPCleverWoobt_Helper()->get_setting( 'link', 'yes' ) === 'yes_popup' ? 'class="woosq-link" data-id="' . $item_id . '" data-context="woobt"' : '' ) . ' href="' . $product->get_permalink() . '" ' . ( WPCleverWoobt_Helper()->get_setting( 'link', 'yes' ) === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . $product_name . '</a>';
									} else {
										$product_name = '<span>' . $product_name . '</span>';
									}

									echo apply_filters( 'woobt_product_name', $product_name, $product );

									do_action( 'woobt_product_name_after', $product, $order );

									echo '</span>';

									if ( $separate_images && ( WPCleverWoobt_Helper()->get_setting( 'show_price', 'yes' ) !== 'no' ) ) {
										echo '<span class="woobt-price">';

										do_action( 'woobt_product_price_before', $product, $order );

										echo '<span class="woobt-price-new"></span>';
										echo '<span class="woobt-price-ori">';

										if ( ! $separately && ( $item_price !== '100%' ) ) {
											if ( $product->is_type( 'variable' ) ) {
												$item_ori_price_min = apply_filters( 'woobt_product_price_ori', ( $pricing === 'sale_price' ) ? $product->get_variation_price( 'min', true ) : $product->get_variation_regular_price( 'min', true ), $item, 'min' );
												$item_ori_price_max = apply_filters( 'woobt_product_price_ori', ( $pricing === 'sale_price' ) ? $product->get_variation_price( 'max', true ) : $product->get_variation_regular_price( 'max', true ), $item, 'max' );
												$item_new_price_min = WPCleverWoobt_Helper()->new_price( $item_ori_price_min, $item_price );
												$item_new_price_max = WPCleverWoobt_Helper()->new_price( $item_ori_price_max, $item_price );

												if ( $item_new_price_min < $item_new_price_max ) {
													$product_price = wc_format_price_range( $item_new_price_min, $item_new_price_max );
												} else {
													$product_price = wc_format_sale_price( $item_ori_price_min, $item_new_price_min );
												}
											} else {
												$item_ori_price = apply_filters( 'woobt_product_price_ori', ( $pricing === 'sale_price' ) ? wc_get_price_to_display( $product, [ 'price' => $product->get_price() ] ) : wc_get_price_to_display( $product, [ 'price' => $product->get_regular_price() ] ), $item );
												$item_new_price = WPCleverWoobt_Helper()->new_price( $item_ori_price, $item_price );

												if ( $item_new_price < $item_ori_price ) {
													$product_price = wc_format_sale_price( $item_ori_price, $item_new_price );
												} else {
													$product_price = wc_price( $item_new_price );
												}
											}

											$product_price .= $product->get_price_suffix();
										} else {
											$product_price = $product->get_price_html();
										}

										echo apply_filters( 'woobt_product_price', $product_price, $product, $item );

										echo '</span>';

										do_action( 'woobt_product_price_after', $product, $order );

										echo '</span>';
									}

									if ( WPCleverWoobt_Helper()->get_setting( 'show_description', 'no' ) === 'yes' ) {
										echo '<div class="woobt-description">' . apply_filters( 'woobt_product_short_description', $product->is_type( 'variation' ) ? $product->get_description() : $product->get_short_description(), $product ) . '</div>';
									}

									echo '<div class="woobt-availability">' . apply_filters( 'woobt_product_availability', wc_get_stock_html( $product ), $product ) . '</div>';
									?>
                                </div>

								<?php
								if ( $custom_qty ) {
									echo '<div class="' . esc_attr( ( $plus_minus ? 'woobt-quantity woobt-quantity-plus-minus' : 'woobt-quantity' ) ) . '">';

									do_action( 'woobt_product_qty_before', $product, $order );

									if ( $plus_minus ) {
										echo '<div class="woobt-quantity-input">';
										echo '<div class="woobt-quantity-input-minus">-</div>';
									}

									$qty_args = [
										'classes'     => [
											'input-text',
											'woobt-qty',
											'woobt_qty',
											'qty',
											'text'
										],
										'input_name'  => 'woobt_qty_' . $order,
										'input_value' => $item_qty,
										'min_value'   => $item_min,
										'max_value'   => $item_max,
										'woobt_qty'   => [
											'input_value' => $item_qty,
											'min_value'   => $item_min,
											'max_value'   => $item_max
										]
										// compatible with WPC Product Quantity
									];

									if ( apply_filters( 'woobt_use_woocommerce_quantity_input', true ) ) {
										woocommerce_quantity_input( $qty_args, $product );
									} else {
										echo apply_filters( 'woobt_quantity_input', '<input type="number" class="input-text woobt-qty woobt_qty qty text" name="' . esc_attr( 'woobt_qty_' . $order ) . '" value="' . esc_attr( $item_qty ) . '" min="' . esc_attr( $item_min ) . '" max="' . esc_attr( $item_max ) . '" />', $qty_args, $product );
									}

									if ( $plus_minus ) {
										echo '<div class="woobt-quantity-input-plus">+</div>';
										echo '</div>';
									}

									do_action( 'woobt_product_qty_after', $product, $order );

									echo '</div>';
								}

								if ( ! $separate_images && ( WPCleverWoobt_Helper()->get_setting( 'show_price', 'yes' ) !== 'no' ) ) {
									echo '<div class="woobt-price">';

									do_action( 'woobt_product_price_before', $product, $order );

									echo '<div class="woobt-price-new"></div>';
									echo '<div class="woobt-price-ori">';

									if ( ! $separately && ( $item_price !== '100%' ) ) {
										if ( $product->is_type( 'variable' ) ) {
											$item_ori_price_min = apply_filters( 'woobt_product_price_ori', ( $pricing === 'sale_price' ) ? $product->get_variation_price( 'min', true ) : $product->get_variation_regular_price( 'min', true ), $item, 'min' );
											$item_ori_price_max = apply_filters( 'woobt_product_price_ori', ( $pricing === 'sale_price' ) ? $product->get_variation_price( 'max', true ) : $product->get_variation_regular_price( 'max', true ), $item, 'max' );
											$item_new_price_min = WPCleverWoobt_Helper()->new_price( $item_ori_price_min, $item_price );
											$item_new_price_max = WPCleverWoobt_Helper()->new_price( $item_ori_price_max, $item_price );

											if ( $item_new_price_min < $item_new_price_max ) {
												$product_price = wc_format_price_range( $item_new_price_min, $item_new_price_max );
											} else {
												$product_price = wc_format_sale_price( $item_ori_price_min, $item_new_price_min );
											}
										} else {
											$item_ori_price = apply_filters( 'woobt_product_price_ori', ( $pricing === 'sale_price' ) ? wc_get_price_to_display( $product, [ 'price' => $product->get_price() ] ) : wc_get_price_to_display( $product, [ 'price' => $product->get_regular_price() ] ), $item );
											$item_new_price = WPCleverWoobt_Helper()->new_price( $item_ori_price, $item_price );

											if ( $item_new_price < $item_ori_price ) {
												$product_price = wc_format_sale_price( $item_ori_price, $item_new_price );
											} else {
												$product_price = wc_price( $item_new_price );
											}
										}

										$product_price .= $product->get_price_suffix();
									} else {
										$product_price = $product->get_price_html();
									}

									echo apply_filters( 'woobt_product_price', $product_price, $product, $item );

									echo '</div>';

									do_action( 'woobt_product_price_after', $product, $order );

									echo '</div><!-- /.woobt-price -->';
								}
								?>

								<?php do_action( 'woobt_product_after', $product, $order ); ?>

                            </div><!-- /.woobt-product-together -->

							<?php echo apply_filters( 'woobt_product_output', ob_get_clean(), $item, $product_id, $order );

							$order ++;
						} else {
							// heading/paragraph
							echo self::text_output( $item, $item_key, $product_id );
						}
					}

					// restore global $product
					$product = $global_product;

					do_action( 'woobt_products_after', $product );
					?>
                </div><!-- /woobt-products -->
				<?php
				do_action( 'woobt_products_below', $product );

				do_action( 'woobt_summary_above', $product );

				echo '<div class="woobt-summary">';

				echo '<div class="woobt-additional woobt-text"></div>';

				do_action( 'woobt_total_above', $product );

				echo '<div class="woobt-total woobt-text"></div>';

				do_action( 'woobt_alert_above', $product );

				echo '<div class="woobt-alert woobt-text"></div>';

				if ( $custom_position || $separate_atc ) {
					do_action( 'woobt_actions_above', $product );
					echo '<div class="woobt-actions">';
					do_action( 'woobt_actions_before', $product );
					echo '<div class="woobt-form">';
					echo '<input type="hidden" name="woobt_ids" class="woobt-ids woobt-ids-' . esc_attr( $product_id ) . '" data-id="' . esc_attr( $product_id ) . '"/>';
					echo '<input type="hidden" name="quantity" value="1"/>';
					echo '<input type="hidden" name="product_id" value="' . esc_attr( $product_id ) . '">';
					echo '<input type="hidden" name="variation_id" class="variation_id" value="0">';
					echo '<button type="submit" class="single_add_to_cart_button button alt">' . WPCleverWoobt_Helper()->localization( 'add_all_to_cart', esc_html__( 'Add all to cart', 'woo-bought-together' ) ) . '</button>';
					echo '</div>';
					do_action( 'woobt_actions_after', $product );
					echo '</div><!-- /woobt-actions -->';
					do_action( 'woobt_actions_below', $product );
				}

				echo '</div><!-- /woobt-summary -->';

				do_action( 'woobt_summary_below', $product );

				if ( $layout === 'compact' ) {
					echo '</div><!-- /woobt-inner -->';
				}

				if ( ! empty( $after_text ) ) {
					do_action( 'woobt_after_text_above', $product );
					echo '<div class="woobt-after-text woobt-text">' . wp_kses_post( do_shortcode( $after_text ) ) . '</div>';
					do_action( 'woobt_after_text_below', $product );
				}

				do_action( 'woobt_wrap_after', $product );
			}

			if ( ! $is_variation ) {
				echo '</div><!-- /woobt-wrap -->';
			}
		}

		function text_output( $item, $item_key = '', $product_id = 0 ) {
			ob_start();

			if ( ! empty( $item['text'] ) ) {
				$item_class = 'woobt-item-text';

				if ( ! empty( $item['type'] ) ) {
					$item_class .= ' woobt-item-text-type-' . $item['type'];
				}

				echo '<div class="' . esc_attr( apply_filters( 'woobt_item_text_class', $item_class, $item, $product_id ) ) . '" data-key="' . esc_attr( $item_key ) . '">';

				$item_text = apply_filters( 'woobt_item_text', do_shortcode( str_replace( '[woobt', '[_woobt', $item['text'] ) ), $item, $product_id );

				if ( empty( $item['type'] ) || ( $item['type'] === 'none' ) ) {
					echo wp_kses_post( $item_text );
				} else {
					echo '<' . $item['type'] . '>' . wp_kses_post( $item_text ) . '</' . $item['type'] . '>';
				}

				echo '</div>';
			}

			return apply_filters( 'woobt_text_output', ob_get_clean(), $item, $product_id );
		}

		function get_ids( $product, $context = 'display' ) {
			if ( is_a( $product, 'WC_Product' ) ) {
				$product_id = $product->get_id();
			} elseif ( is_int( $product ) ) {
				$product_id = $product;
			} else {
				$product_id = 0;
			}

			$ids = [];

			if ( $product_id && ! self::is_disable( $product_id ) ) {
				$ids = get_post_meta( $product_id, 'woobt_ids', true );
			}

			return apply_filters( 'woobt_get_ids', $ids, $product, $context );
		}

		function get_product_items( $product, $context = 'view' ) {
			if ( is_a( $product, 'WC_Product' ) ) {
				$product_id = $product->get_id();
			} elseif ( is_int( $product ) ) {
				$product_id = $product;
			} else {
				$product_id = 0;
			}

			$items = [];

			if ( $product_id && ! self::is_disable( $product_id ) ) {
				$ids = self::get_ids( $product_id, $context );

				if ( ! empty( $ids ) && is_array( $ids ) ) {
					foreach ( $ids as $item_key => $item ) {
						$item = array_merge( [
							'id'    => 0,
							'sku'   => '',
							'price' => '100%',
							'qty'   => 1,
							'attrs' => []
						], $item );

						// check for variation
						if ( ( $parent_id = wp_get_post_parent_id( $item['id'] ) ) && ( $parent = wc_get_product( $parent_id ) ) ) {
							$parent_sku = $parent->get_sku();
						} else {
							$parent_sku = '';
						}

						if ( apply_filters( 'woobt_use_sku', false ) && ! empty( $item['sku'] ) && ( $item['sku'] !== $parent_sku ) && ( $new_id = wc_get_product_id_by_sku( $item['sku'] ) ) ) {
							// get product id by SKU for export/import
							$item['id'] = $new_id;
						}

						$items[ $item_key ] = $item;
					}
				}
			}

			return apply_filters( 'woobt_get_product_items', $items, $product, $context );
		}

		function check_rule( $rule, $product ) {
			if ( is_a( $product, 'WC_Product' ) ) {
				$product_id = $product->get_id();
			} elseif ( is_int( $product ) ) {
				$product_id = $product;
				$product    = wc_get_product( $product_id );
			} else {
				$product_id = 0;
			}

			if ( ! $product_id || empty( $rule['apply'] ) ) {
				return false;
			}

			switch ( $rule['apply'] ) {
				case 'all':
					return true;
				case 'products':
					if ( ! empty( $rule['apply_products'] ) && is_array( $rule['apply_products'] ) ) {
						if ( in_array( $product_id, $rule['apply_products'] ) ) {
							return true;
						}
					}

					return false;
				case 'combination':
					if ( ! empty( $rule['apply_combination'] ) && is_array( $rule['apply_combination'] ) ) {
						$match_all = true;

						foreach ( $rule['apply_combination'] as $combination ) {
							$match = true;

							if ( ! empty( $combination['apply'] ) && ( $combination['apply'] === 'variation' || $combination['apply'] === 'not_variation' ) ) {
								if ( $combination['apply'] === 'variation' && ! $product->is_type( 'variation' ) ) {
									$match = false;
								}

								if ( $combination['apply'] === 'not_variation' && $product->is_type( 'variation' ) ) {
									$match = false;
								}
							}

							if ( ! empty( $combination['apply'] ) && ! empty( $combination['compare'] ) && ! empty( $combination['terms'] ) && is_array( $combination['terms'] ) ) {
								if ( $product->is_type( 'variation' ) ) {
									$attrs    = $product->get_attributes();
									$taxonomy = $combination['apply'];

									if ( ! empty( $attrs[ $taxonomy ] ) ) {
										if ( ( $combination['compare'] === 'is' ) && ! in_array( $attrs[ $taxonomy ], $combination['terms'] ) ) {
											$match = false;
										}

										if ( ( $combination['compare'] === 'is_not' ) && in_array( $attrs[ $taxonomy ], $combination['terms'] ) ) {
											$match = false;
										}
									} else {
										$match = false;
									}
								} else {
									if ( ( $combination['compare'] === 'is' ) && ! has_term( $combination['terms'], $combination['apply'], $product_id ) ) {
										$match = false;
									}

									if ( ( $combination['compare'] === 'is_not' ) && has_term( $combination['terms'], $combination['apply'], $product_id ) ) {
										$match = false;
									}
								}
							}

							$match_all &= $match;
						}

						return $match_all;
					}

					return false;
				default:
					if ( ! empty( $rule['apply_terms'] ) && is_array( $rule['apply_terms'] ) ) {
						if ( $product->is_type( 'variation' ) ) {
							$attrs    = $product->get_attributes();
							$taxonomy = $rule['apply'];

							if ( isset( $attrs[ $taxonomy ] ) && in_array( $attrs[ $taxonomy ], $rule['apply_terms'] ) ) {
								return true;
							}
						} else {
							if ( has_term( $rule['apply_terms'], $rule['apply'], $product_id ) ) {
								return true;
							}
						}
					}

					return false;
			}
		}

		function get_rule( $product ) {
			return apply_filters( 'woobt_get_rule', [], $product );
		}

		function get_rules( $product ) {
			return apply_filters( 'woobt_get_rules', [], $product );
		}

		function get_rule_items( $product = null, $context = 'view' ) {
			return apply_filters( 'woobt_get_rule_items', [], $product, $context );
		}

		function get_default_items( $product = null, $context = 'view' ) {
			if ( is_a( $product, 'WC_Product' ) ) {
				$product_id = $product->get_id();
			} elseif ( is_int( $product ) ) {
				$product_id = $product;
				$product    = wc_get_product( $product_id );
			} else {
				$product_id = 0;
			}

			$ids   = [];
			$items = [];

			if ( $product_id && ! self::is_disable( $product_id ) ) {
				$default       = apply_filters( 'woobt_default', WPCleverWoobt_Helper()->get_setting( 'default', [ 'default' ] ) );
				$default_limit = (int) apply_filters( 'woobt_default_limit', WPCleverWoobt_Helper()->get_setting( 'default_limit', 0 ) );
				$default_price = apply_filters( 'woobt_default_price', WPCleverWoobt_Helper()->get_setting( 'default_price', '100%' ) );

				// backward compatibility before 5.1.1
				if ( ! is_array( $default ) ) {
					switch ( (string) $default ) {
						case 'upsells':
							$default = [ 'upsells' ];
							break;
						case 'related':
							$default = [ 'related' ];
							break;
						case 'related_upsells':
							$default = [ 'upsells', 'related' ];
							break;
						case 'none':
							$default = [];
							break;
						default:
							$default = [];
					}
				}

				if ( is_array( $default ) && ! empty( $default ) ) {
					if ( in_array( 'related', $default ) ) {
						$ids = array_merge( $ids, wc_get_related_products( $product_id ) );
					}

					if ( in_array( 'upsells', $default ) ) {
						$ids = array_merge( $ids, $product->get_upsell_ids() );
					}

					if ( in_array( 'crosssells', $default ) ) {
						$ids = array_merge( $ids, $product->get_cross_sell_ids() );
					}

					if ( $default_limit ) {
						$ids = array_slice( $ids, 0, $default_limit );
					}
				}

				if ( ! empty( $ids ) ) {
					foreach ( $ids as $id ) {
						$item_key           = 'df' . WPCleverWoobt_Helper()->generate_key();
						$items[ $item_key ] = [
							'id'    => $id,
							'price' => $default_price,
							'qty'   => 1,
						];
					}
				}
			}

			return apply_filters( 'woobt_get_default_items', $items, $product, $context );
		}

		function get_items( $product, $context = 'view' ) {
			if ( is_a( $product, 'WC_Product' ) ) {
				$product_id = $product->get_id();
			} elseif ( is_int( $product ) ) {
				$product_id = $product;
			} else {
				$product_id = 0;
			}

			$items = [];

			if ( $product_id && ! self::is_disable( $product_id ) ) {
				$priority = apply_filters( 'woobt_get_items_priority', [
					'product',
					'rule',
					'default'
				], $product_id );

				foreach ( $priority as $pr ) {
					switch ( $pr ) {
						case 'product':
							$items = self::get_product_items( $product_id, $context );
							break;
						case 'rule':
							$items = self::get_rule_items( $product_id, $context );
							break;
						case 'default':
							$items = self::get_default_items( $product, $context );
							break;
						case 'combine':
							$product_items = self::get_product_items( $product_id, $context );
							$rule_items    = self::get_rule_items( $product_id, $context );
							$default_items = self::get_default_items( $product, $context );
							$items         = array_merge( $product_items, $rule_items, $default_items );
							break;
					}

					if ( ! empty( $items ) ) {
						break;
					}
				}
			}

			return apply_filters( 'woobt_get_items', $items, $product_id, $context );
		}

		function get_text( $product, $context = 'before' ) {
			// Optimize product ID extraction
			$product_id = is_a( $product, 'WC_Product' ) ? $product->get_id() :
				( is_int( $product ) ? $product : 0 );

			// Early return for invalid products
			if ( ! $product_id || self::is_disable( $product_id ) ) {
				return apply_filters( 'woobt_get_text', '', $product, $context );
			}

			// Cache context check result
			$is_before = $context === 'before' || $context === 'above';

			// Get priority array with caching
			static $priority_cache = [];

			if ( ! isset( $priority_cache[ $product_id ] ) ) {
				$priority_cache[ $product_id ] = apply_filters( 'woobt_get_items_priority',
					[ 'product', 'rule', 'default' ],
					$product_id
				);
			}

			$text = '';

			foreach ( $priority_cache[ $product_id ] as $pr ) {
				switch ( $pr ) {
					case 'product':
						$meta_key = $is_before ? 'woobt_before_text' : 'woobt_after_text';
						$text     = get_post_meta( $product_id, $meta_key, true );

						break;
					case 'rule':
						static $rule_cache = [];

						if ( ! isset( $rule_cache[ $product_id ] ) ) {
							$rule_cache[ $product_id ] = self::get_rule( $product_id );
						}

						if ( ! empty( $rule_cache[ $product_id ] ) ) {
							$text = $is_before ?
								( $rule_cache[ $product_id ]['before_text'] ?? '' ) :
								( $rule_cache[ $product_id ]['after_text'] ?? '' );
						}

						break;
					case 'default':
						static $helper;

						if ( ! isset( $helper ) ) {
							$helper = WPCleverWoobt_Helper();
						}

						$text = $is_before ?
							$helper->localization( 'above_text' ) :
							$helper->localization( 'under_text' );

						break;
				}

				if ( ! empty( $text ) ) {
					break;
				}
			}

			return apply_filters( 'woobt_get_text', $text, $product, $context );
		}

		function is_disable( $product, $context = 'view' ) {
			$disable = false;

			if ( is_a( $product, 'WC_Product' ) ) {
				$product_id = $product->get_id();
			} elseif ( is_int( $product ) ) {
				$product_id = $product;
			} else {
				$product_id = 0;
			}

			if ( $product_id ) {
				$disable = get_post_meta( $product_id, 'woobt_disable', true ) === 'yes';
			}

			return apply_filters( 'woobt_is_disable', $disable, $product, $context );
		}

		function get_discount( $product_id ) {
			$discount = 0;

			if ( $product_id && ! self::is_disable( $product_id ) ) {
				$ids = self::get_ids( $product_id );

				if ( ! empty( $ids ) ) {
					$discount = get_post_meta( $product_id, 'woobt_discount', true ) ?: 0;
				} else {
					$rule = self::get_rule( $product_id );

					if ( ! empty( $rule ) ) {
						$discount = $rule['discount'] ?? 0;
					} else {
						$discount = WPCleverWoobt_Helper()->get_setting( 'default_discount', 0 );
					}
				}
			}

			return apply_filters( 'woobt_get_discount', $discount, $product_id );
		}

		function get_items_from_ids( $ids, $product_id = 0, $context = 'view' ) {
			$product_items = self::get_product_items( $product_id, $context );
			$items         = [];

			if ( ! empty( $ids ) ) {
				$_items = explode( ',', $ids );

				if ( is_array( $_items ) && count( $_items ) > 0 ) {
					foreach ( $_items as $_item ) {
						$_item_data    = explode( '/', $_item );
						$_item_id      = apply_filters( 'woobt_item_id', absint( $_item_data[0] ?? 0 ), $_item, $product_id );
						$_item_product = wc_get_product( $_item_id );

						if ( ! $_item_product || ( $_item_product->get_status() === 'trash' ) ) {
							continue;
						}

						if ( ( $context === 'view' ) && ( ( WPCleverWoobt_Helper()->get_setting( 'exclude_unpurchasable', 'no' ) === 'yes' ) && ( ! $_item_product->is_purchasable() || ! $_item_product->is_in_stock() ) ) ) {
							continue;
						}

						$_item_key   = $_item_data[1] ?? WPCleverWoobt_Helper()->generate_key();
						$_item_price = WPCleverWoobt_Helper()->get_setting( 'default_price', '100%' );

						if ( str_contains( $_item_key, '-' ) ) {
							// smart rules
							$_item_key_arr = explode( '-', $_item_key );
							$rule_key      = $_item_key_arr[1] ?? '';

							if ( ! empty( $rule_key ) ) {
								$rules = self::$rules;

								if ( is_array( $rules ) && isset( $rules[ $rule_key ]['price'] ) ) {
									$_item_price = $rules[ $rule_key ]['price'];
								}
							}
						} else {
							// product or default
							if ( is_array( $product_items ) && isset( $product_items[ $_item_key ]['price'] ) ) {
								$_item_price = $product_items[ $_item_key ]['price'];
							}
						}

						$items[ $_item_key ] = [
							'id'    => $_item_id,
							'price' => WPCleverWoobt_Helper()->format_price( $_item_price ),
							'qty'   => (float) ( $_item_data[2] ?? 1 ),
							'attrs' => isset( $_item_data[3] ) ? (array) json_decode( rawurldecode( $_item_data[3] ) ) : []
						];
					}
				}
			}

			return apply_filters( 'woobt_get_items_from_ids', $items, $ids, $product_id, $context );
		}

		function search_sku( $query ) {
			if ( $query->is_search && isset( $query->query['is_woobt'] ) ) {
				global $wpdb;
				$sku = sanitize_text_field( $query->query['s'] );
				$ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value = %s;", $sku ) );

				if ( ! $ids ) {
					return;
				}

				unset( $query->query['s'], $query->query_vars['s'] );
				$query->query['post__in'] = [];

				foreach ( $ids as $id ) {
					$post = get_post( $id );

					if ( $post->post_type === 'product_variation' ) {
						$query->query['post__in'][]      = $post->post_parent;
						$query->query_vars['post__in'][] = $post->post_parent;
					} else {
						$query->query_vars['post__in'][] = $post->ID;
					}
				}
			}
		}

		function search_exact( $query ) {
			if ( $query->is_search && isset( $query->query['is_woobt'] ) ) {
				$query->set( 'exact', true );
			}
		}

		function search_sentence( $query ) {
			if ( $query->is_search && isset( $query->query['is_woobt'] ) ) {
				$query->set( 'sentence', true );
			}
		}

		function wpml_product_id( $id ) {
			return apply_filters( 'wpml_object_id', $id, 'product', true );
		}

		function product_filter( $filters ) {
			$filters['woobt'] = [ $this, 'product_filter_callback' ];

			return $filters;
		}

		function product_filter_callback() {
			$woobt  = wc_clean( wp_unslash( $_REQUEST['woobt'] ?? '' ) );
			$output = '<select name="woobt"><option value="">' . esc_html__( 'Bought together', 'woo-bought-together' ) . '</option>';
			$output .= '<option value="yes" ' . selected( $woobt, 'yes', false ) . '>' . esc_html__( 'With associated products', 'woo-bought-together' ) . '</option>';
			$output .= '<option value="no" ' . selected( $woobt, 'no', false ) . '>' . esc_html__( 'Without associated products', 'woo-bought-together' ) . '</option>';
			$output .= '</select>';
			echo $output;
		}

		function apply_product_filter( $query ) {
			global $pagenow;

			if ( $query->is_admin && $pagenow == 'edit.php' && isset( $_GET['woobt'] ) && $_GET['woobt'] != '' && $_GET['post_type'] == 'product' ) {
				$meta_query = (array) $query->get( 'meta_query' );

				if ( $_GET['woobt'] === 'yes' ) {
					$meta_query[] = [
						'relation' => 'AND',
						[
							'key'     => 'woobt_ids',
							'compare' => 'EXISTS'
						],
						[
							'key'     => 'woobt_ids',
							'value'   => '',
							'compare' => '!='
						],
					];
				} else {
					$meta_query[] = [
						'relation' => 'OR',
						[
							'key'     => 'woobt_ids',
							'compare' => 'NOT EXISTS'
						],
						[
							'key'     => 'woobt_ids',
							'value'   => '',
							'compare' => '=='
						],
					];
				}

				$query->set( 'meta_query', $meta_query );
			}
		}

		function woovr_default_selector( $selector, $product, $variation, $context ) {
			if ( isset( $context ) && ( $context === 'woobt' ) ) {
				if ( ( $selector_interface = WPCleverWoobt_Helper()->get_setting( 'selector_interface', 'unset' ) ) && ( $selector_interface !== 'unset' ) ) {
					$selector = $selector_interface;
				}
			}

			return $selector;
		}

		function wpcsm_locations( $locations ) {
			$locations['WPC Frequently Bought Together'] = [
				'woobt_wrap_before'          => esc_html__( 'Before wrapper', 'woo-bought-together' ),
				'woobt_wrap_after'           => esc_html__( 'After wrapper', 'woo-bought-together' ),
				'woobt_products_before'      => esc_html__( 'Before products', 'woo-bought-together' ),
				'woobt_products_after'       => esc_html__( 'After products', 'woo-bought-together' ),
				'woobt_product_before'       => esc_html__( 'Before sub-product', 'woo-bought-together' ),
				'woobt_product_after'        => esc_html__( 'After sub-product', 'woo-bought-together' ),
				'woobt_product_thumb_before' => esc_html__( 'Before sub-product thumbnail', 'woo-bought-together' ),
				'woobt_product_thumb_after'  => esc_html__( 'After sub-product thumbnail', 'woo-bought-together' ),
				'woobt_product_name_before'  => esc_html__( 'Before sub-product name', 'woo-bought-together' ),
				'woobt_product_name_after'   => esc_html__( 'After sub-product name', 'woo-bought-together' ),
				'woobt_product_price_before' => esc_html__( 'Before sub-product price', 'woo-bought-together' ),
				'woobt_product_price_after'  => esc_html__( 'After sub-product price', 'woo-bought-together' ),
				'woobt_product_qty_before'   => esc_html__( 'Before sub-product quantity', 'woo-bought-together' ),
				'woobt_product_qty_after'    => esc_html__( 'After sub-product quantity', 'woo-bought-together' ),
			];

			return $locations;
		}

		function export_process( $value, $meta, $product ) {
			if ( $meta->key === 'woobt_ids' ) {
				$ids = get_post_meta( $product->get_id(), 'woobt_ids', true );

				if ( ! empty( $ids ) && is_array( $ids ) ) {
					return json_encode( $ids );
				}
			}

			return $value;
		}

		function import_process( $object, $data ) {
			if ( isset( $data['meta_data'] ) ) {
				foreach ( $data['meta_data'] as $meta ) {
					if ( $meta['key'] === 'woobt_ids' ) {
						$object->update_meta_data( 'woobt_ids', json_decode( $meta['value'], true ) );
						break;
					}
				}
			}

			return $object;
		}

		// Deprecated functions - moved to WPCleverWoobt_Helper

		public static function get_settings() {
			return WPCleverWoobt_Helper()->get_settings();
		}

		public static function get_setting( $name, $default = false ) {
			return WPCleverWoobt_Helper()->get_setting( $name, $default );
		}

		public static function localization( $key = '', $default = '' ) {
			return WPCleverWoobt_Helper()->localization( $key, $default );
		}

		public static function data_attributes( $attrs ) {
			return WPCleverWoobt_Helper()->data_attributes( $attrs );
		}

		public static function generate_key() {
			return WPCleverWoobt_Helper()->generate_key();
		}

		public static function sanitize_array( $arr ) {
			return WPCleverWoobt_Helper()->sanitize_array( $arr );
		}

		public static function clean_ids( $ids ) {
			return WPCleverWoobt_Helper()->clean_ids( $ids );
		}

		public static function format_price( $price ) {
			return WPCleverWoobt_Helper()->format_price( $price );
		}

		public static function new_price( $old_price, $new_price ) {
			return WPCleverWoobt_Helper()->new_price( $old_price, $new_price );
		}
	}

	function WPCleverWoobt() {
		return WPCleverWoobt::instance();
	}

	WPCleverWoobt();
}