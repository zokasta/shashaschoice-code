<?php
/**
* Taxonomy: Goldsmith Brands.
*/
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
if ( ! class_exists( 'Goldsmith_Product_Brand' ) ) {
    class Goldsmith_Product_Brand {
        private static $instance = null;
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }
        public function __construct() {
            add_action( 'init', array( $this, 'register_taxes' ) );
            // Set Brand taxonomy term when you duplicate the product
            add_action( 'woocommerce_product_duplicate', array( $this, 'woocommerce_product_duplicate' ), 10, 2 );

            add_action('goldsmith_product_brands_add_form_fields', array( $this, 'goldsmith_wc_taxonomy_add_new_meta_field' ));
            add_action('goldsmith_product_brands_edit_form_fields', array( $this, 'goldsmith_wc_taxonomy_edit_meta_field' ), 15, 1);
            add_action('edited_goldsmith_product_brands', array( $this, 'goldsmith_wc_save_taxonomy_custom_meta'), 10, 2);
            add_action('create_goldsmith_product_brands', array( $this, 'goldsmith_wc_save_taxonomy_custom_meta'), 10, 2);
        }
        public function register_taxes() {
            $labels = [
                "name" => __( "Brands", "goldsmith" ),
                "singular_name" => __( "Brand", "goldsmith" ),
                "menu_name" => __( "Brands", "goldsmith" ),
                "all_items" => __( "All Brands", "goldsmith" ),
                "edit_item" => __( "Edit Brand", "goldsmith" ),
                "view_item" => __( "View Brand", "goldsmith" ),
                "update_item" => __( "Update Brand name", "goldsmith" ),
                "add_new_item" => __( "Add new Brand", "goldsmith" ),
                "new_item_name" => __( "New brand name", "goldsmith" ),
                "parent_item" => __( "Parent Brand", "goldsmith" ),
                "parent_item_colon" => __( "Parent Brand:", "goldsmith" ),
                "search_items" => __( "Search Brands", "goldsmith" ),
                "popular_items" => __( "Popular Brands", "goldsmith" ),
                "separate_items_with_commas" => __( "Separate brand with commas", "goldsmith" ),
                "add_or_remove_items" => __( "Add or remove brand", "goldsmith" ),
                "choose_from_most_used" => __( "Choose from the most used brand", "goldsmith" ),
                "not_found" => __( "No brand found", "goldsmith" ),
                "no_terms" => __( "No brand", "goldsmith" ),
                "items_list_navigation" => __( "Brands list navigation", "goldsmith" ),
                "items_list" => __( "Brands list", "goldsmith" )
            ];
            $args = [
                "label" => __( "Goldsmith Brands", "goldsmith" ),
                "labels" => $labels,
                "public" => true,
                "publicly_queryable" => true,
                "hierarchical" => true,
                "show_ui" => true,
                "show_in_menu" => true,
                "show_in_nav_menus" => true,
                "query_var" => true,
                "has_archive" => true,
                "rewrite" => array(
                    'slug' => 'product-brands',
                    'with_front' => true,
                    'hierarchical' => true
                ),
                "show_admin_column" => true,
                "show_in_quick_edit" => true,
                'capabilities' => array(
                    'manage_terms' => 'manage_product_terms',
                    'edit_terms' => 'edit_product_terms',
                    'delete_terms' => 'delete_product_terms',
                    'assign_terms' => 'assign_product_terms',
                ),
                'update_count_callback' => '_wc_term_recount'
            ];
            register_taxonomy( "goldsmith_product_brands", "product", $args );
            register_taxonomy_for_object_type( "goldsmith_product_brands", "product" );
        }
        /**
        * Set brands for duplicated product
        *
        * @param $duplicate
        * @param $product
        */
        public function woocommerce_product_duplicate( $duplicate, $product ) {
            $brands     = wp_get_object_terms( $product->get_id(), "goldsmith_product_brands" );
            $brands_ids = array();
            if ( count( $brands ) > 0 ) {
                foreach ( $brands as $brand ) {
                    $brands_ids[] = $brand->term_id;
                }
                wp_set_object_terms( $duplicate->get_id(), $brands_ids, "goldsmith_product_brands" );
            }
        }

        //Product Cat Create page
        public function goldsmith_wc_taxonomy_add_new_meta_field() {
            wp_enqueue_media();
            ?>
            <div class="form-field goldsmith_term-brand_image-wrap">
                <label><?php esc_html_e( 'Brand Image', 'goldsmith' ); ?></label>
                <div id="goldsmith_product_brand_image" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
                <div style="line-height: 60px;">
                    <input type="hidden" id="brand_thumbnail_id" name="term_meta[brand_thumbnail_id]" />
                    <button type="button" class="goldsmith_upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'goldsmith' ); ?></button>
                    <button type="button" class="goldsmith_remove_image_button button"><?php esc_html_e( 'Remove image', 'goldsmith' ); ?></button>
                </div>
                <div class="clear"></div>
                <span class="description"><?php esc_html_e( 'Upload product brand image from here.', 'goldsmith'); ?></span>
                <script type="text/javascript">

                    // Only show the "remove image" button when needed
                    if ( ! jQuery( '#brand_thumbnail_id' ).val() ) {
                        jQuery( '.goldsmith_term-brand_image-wrap .goldsmith_remove_image_button' ).hide();
                    }

                    // Uploading files
                    var goldsmith_brand_image_file_frame;

                    jQuery( document ).on( 'click', '.goldsmith_term-brand_image-wrap .goldsmith_upload_image_button', function( event ) {

                        event.preventDefault();

                        // If the media frame already exists, reopen it.
                        if ( goldsmith_brand_image_file_frame ) {
                            goldsmith_brand_image_file_frame.open();
                            return;
                        }

                        // Create the media frame.
                        goldsmith_brand_image_file_frame = wp.media.frames.downloadable_file = wp.media({
                            title: '<?php esc_html_e( 'Choose an image', 'goldsmith' ); ?>',
                            button: {
                                text: '<?php esc_html_e( 'Use image', 'goldsmith' ); ?>'
                            },
                            multiple: false
                        });

                        // When an image is selected, run a callback.
                        goldsmith_brand_image_file_frame.on( 'select', function() {
                            var attachment           = goldsmith_brand_image_file_frame.state().get( 'selection' ).first().toJSON();
                            var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                            jQuery( '#brand_thumbnail_id' ).val( attachment.id );
                            jQuery( '#goldsmith_product_brand_image' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
                            jQuery( '.goldsmith_term-brand_image-wrap .goldsmith_remove_image_button' ).show();
                        });

                        // Finally, open the modal.
                        goldsmith_brand_image_file_frame.open();
                    });

                    jQuery( document ).on( 'click', '.goldsmith_term-brand_image-wrap .goldsmith_remove_image_button', function() {
                        jQuery( '#goldsmith_product_brand_image' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                        jQuery( '#brand_thumbnail_id' ).val( '' );
                        jQuery( '.goldsmith_term-brand_image-wrap .goldsmith_remove_image_button' ).hide();
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
                            jQuery( '#goldsmith_product_brand_image' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                            jQuery( '#brand_thumbnail_id' ).val( '' );
                            jQuery( '.goldsmith_term-brand_image-wrap .goldsmith_remove_image_button' ).hide();
                            return;
                        }
                    });

                </script>
            </div>
            <div class="clear"></div>
            <?php
        }

        //Product Cat Edit page
        public function goldsmith_wc_taxonomy_edit_meta_field($term) {
            //getting term ID
            $t_id      = $term->term_id;
            $term_meta = get_option( "taxonomy_$t_id" );
            $image_id  = absint( $term_meta['brand_thumbnail_id'] );
            wp_enqueue_media();
            if ( $image_id ) {
                $image = wp_get_attachment_thumb_url( $image_id );
            } else {
                $image = wc_placeholder_img_src();
            }
            ?>
            <tr class="form-field goldsmith_term-brand_image-wrap">
                <th scope="row" valign="top"><label><?php esc_html_e( 'Brand Image', 'goldsmith' ); ?></label></th>
                <td>
                    <div id="goldsmith_product_brand_image" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
                    <div style="line-height: 60px;">
                        <input type="hidden" id="brand_thumbnail_id" name="term_meta[brand_thumbnail_id]" value="<?php echo esc_attr( $image_id ); ?>" />
                        <button type="button" class="goldsmith_upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'goldsmith' ); ?></button>
                        <button type="button" class="goldsmith_remove_image_button button"><?php esc_html_e( 'Remove image', 'goldsmith' ); ?></button>
                    </div>
                    <div class="clear"></div>
                    <span class="description"><?php esc_html_e( 'Upload product brand image from here.', 'goldsmith'); ?></span>
                    <script type="text/javascript">

                        // Only show the "remove image" button when needed
                        if ( '0' === jQuery( '#brand_thumbnail_id' ).val() ) {
                            jQuery( '.goldsmith_term-brand_image-wrap .goldsmith_remove_image_button' ).hide();
                        }

                        // Uploading files
                        var goldsmith_brand_image_file_frame;

                        jQuery( document ).on( 'click', '.goldsmith_term-brand_image-wrap .goldsmith_upload_image_button', function( event ) {

                            event.preventDefault();

                            // If the media frame already exists, reopen it.
                            if ( goldsmith_brand_image_file_frame ) {
                                goldsmith_brand_image_file_frame.open();
                                return;
                            }

                            // Create the media frame.
                            goldsmith_brand_image_file_frame = wp.media.frames.downloadable_file = wp.media({
                                title: '<?php esc_html_e( 'Choose an image', 'goldsmith' ); ?>',
                                button: {
                                    text: '<?php esc_html_e( 'Use image', 'goldsmith' ); ?>'
                                },
                                multiple: false
                            });

                            // When an image is selected, run a callback.
                            goldsmith_brand_image_file_frame.on( 'select', function() {
                                var attachment           = goldsmith_brand_image_file_frame.state().get( 'selection' ).first().toJSON();
                                var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                                jQuery( '#brand_thumbnail_id' ).val( attachment.id );
                                jQuery( '#goldsmith_product_brand_image' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
                                jQuery( '.goldsmith_term-brand_image-wrap .goldsmith_remove_image_button' ).show();
                            });

                            // Finally, open the modal.
                            goldsmith_brand_image_file_frame.open();
                        });

                        jQuery( document ).on( 'click', '.goldsmith_term-brand_image-wrap .goldsmith_remove_image_button', function() {
                            jQuery( '#goldsmith_product_brand_image' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                            jQuery( '#brand_thumbnail_id' ).val( '' );
                            jQuery( '.goldsmith_term-brand_image-wrap .goldsmith_remove_image_button' ).hide();
                            return false;
                        });

                    </script>
                    <div class="clear"></div>
                </td>
            </tr>
            <?php
        }

        // Save extra taxonomy fields callback function.
        public function goldsmith_wc_save_taxonomy_custom_meta( $term_id ) {
            if ( isset( $_POST['term_meta'] ) ) {
                $t_id = $term_id;
                $term_meta = get_option( "taxonomy_$t_id" );
                $cat_keys = array_keys( $_POST['term_meta'] );
                foreach ( $cat_keys as $key ) {
                    if ( isset ( $_POST['term_meta'][$key] ) ) {
                        $term_meta[$key] = $_POST['term_meta'][$key];
                    }
                }
                // Save the option array.
                update_option( "taxonomy_$t_id", $term_meta );
            }
        }
    }
    Goldsmith_Product_Brand::get_instance();


    class Goldsmith_Widget_Product_Brands extends WP_Widget {

        // Widget Settings
        function __construct() {
            $widget_ops  = array('description' => esc_html__('For Main Shop Page.','goldsmith') );
            $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'goldsmith_product_brands' );
            parent::__construct( 'goldsmith_product_brands', esc_html__('Goldsmith Product Brands','goldsmith'), $widget_ops, $control_ops );

            add_filter( 'woocommerce_product_query_tax_query', [ $this, 'product_query_tax_query' ], 10, 2 );
        }

        function product_query_tax_query( $tax_query, $instance )
        {
            if ( isset( $_GET['brand_id'] ) && !empty( $_GET['brand_id'] ) ) {
                $tax_query[] = array(
                    'taxonomy' => 'goldsmith_product_brands',
                    'field'    => 'id',
                    'terms'    => explode( ',', $_GET['brand_id'] )
                );
            }
            return $tax_query;
        }


        // Widget Output
        function widget( $args, $instance )
        {
            if ( ( is_shop() || is_product_category() || is_product_tag() ) && !is_tax('product-brands') )
            {
                extract( $args );
                $title      = apply_filters( 'widget_title', empty($instance['title'] ) ? '' : $instance['title'], $instance );
                $exclude    = $instance['brand_exclude'];
                $hide_empty = $instance['hide_empty'];

                echo $before_widget;

                if ( $title ) {
                    echo $before_title . $title . $after_title;
                }

                $terms = get_terms( array(
                    'taxonomy'   => 'goldsmith_product_brands',
                    'hide_empty' => $hide_empty ? true : false,
                    'parent'     => 0,
                    'exclude'    => !empty( $exclude ) ? $exclude : '',
                ));

                echo '<div class="widget-body site-checkbox-lists goldsmith-widget-product-categories">';
                echo '<div class="site-scroll">';
                echo '<ul>';

                foreach ( $terms as $term ) {

                    $checkbox = '';

                    if ( isset( $_GET['brand_id'] ) ) {
                        if ( in_array( $term->term_id, explode( ',', $_GET['brand_id'] ) ) ) {
                            $checkbox = 'checked';
                        }
                    }

                    echo '<li>';
                    echo '<a href="'.esc_url( goldsmith_get_brand_url( $term->term_id ) ).'" class="goldsmith_product_brands">';
                    echo '<input name="goldsmith_product_brands[]" value="'.esc_attr( $term->term_id ).'" id="'.esc_attr( $term->name ).'" type="checkbox" '.esc_attr( $checkbox ).'>';
                    echo '<label >'.esc_html( $term->name ).'</label>';
                    echo '</a>';
                    echo '</li>';
                }
                echo '</ul>';
                echo '</div>';
                echo '</div>';

                echo $after_widget;
            }
        }

        // Update
        function update( $new_instance, $old_instance )
        {
            $instance = $old_instance;

            $instance['title']         = strip_tags($new_instance['title']);
            $instance['hide_empty']    = $new_instance['hide_empty'];
            $instance['brand_exclude'] = $new_instance['brand_exclude'];

            return $instance;
        }

        // Backend Form
        function form( $instance )
        {
            $defaults   = array('title' => 'Product Brands', 'brand_exclude' => array(), 'hide_empty' => '' );
            $instance   = wp_parse_args(( array ) $instance, $defaults );
            $select     = is_array( $instance['brand_exclude'] ) ? $instance['brand_exclude'] : array();
            $hide_empty = $instance['hide_empty'] ? true : false;

            $terms = get_terms( array(
                'taxonomy'   => 'goldsmith_product_brands',
                'hide_empty' => $hide_empty,
                'parent'     => 0
            ));

            wp_enqueue_style( 'select2-full' );
            wp_enqueue_script( 'select2-full' );
            ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','goldsmith'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php esc_html_e( 'Hide if empty:','goldsmith' ); ?></label>
                <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>" value="1" <?php checked( $instance['hide_empty'], 1 ); ?> />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('brand_exclude'); ?>"><?php esc_html_e( 'Exclude Brand(s):','goldsmith' ); ?></label>
                <select class="goldsmith-select2" id="brand_exclude" name="<?php echo $this->get_field_name('brand_exclude'); ?>[]" multiple>
                    <?php foreach ( $terms as $term ) {
                        $selected = in_array( $term->term_id, $select) ? 'selected="selected"' : '';
                        ?>
                        <option value="<?php echo $term->term_id; ?>" <?php echo $selected; ?>><?php echo $term->name; ?></option>
                    <?php } ?>
                </select>
            </p>

            <?php
        }
    }

    // Add Widget
    function goldsmith_widget_product_brands_init() {
        register_widget('Goldsmith_Widget_Product_Brands');
    }
    add_action('widgets_init', 'goldsmith_widget_product_brands_init');
}
