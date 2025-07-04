<?php
/**
* Taxonomy: Goldsmith Brands.
*/
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
if ( ! class_exists( 'Goldsmith_Popup_Builder' ) ) {
    class Goldsmith_Popup_Builder {
        private static $instance = null;
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }
        public function __construct() {
            if ( ! get_option( 'disable_goldsmith_popups' ) == 1 ) {
                add_action( 'init', array( $this, 'goldsmith_register_popups' ) );

                $cpt_support = get_option( 'elementor_cpt_support' );
                if ( is_array( $cpt_support ) && ! in_array( 'goldsmith_popups', $cpt_support ) ) {
                    $cpt_support[] = 'goldsmith_popups';
                    update_option( 'elementor_cpt_support', $cpt_support );
                }
                // Add the custom columns to the book post type:
                add_filter( 'manage_goldsmith_popups_posts_columns', array( $this, 'set_custom_edit_goldsmith_popups_columns' ) );
                // Add the data to the custom columns for the book post type:
                add_action( 'manage_goldsmith_popups_posts_custom_column' , array( $this, 'custom_goldsmith_popups_column' ), 10, 2 );
            }
        }
        public function goldsmith_register_popups() {

            /**
            * Post Type: Goldsmith Popups.
            */

            $labels = [
                "name" => __( "Popups Builder", "goldsmith" ),
                "singular_name" => __( "Popup Builder", "goldsmith" ),
                "menu_name" => __( "Popups Builder", "goldsmith" ),
                "all_items" => __( "Popups Builder", "goldsmith" ),
                "add_new" => __( "Add Popup", "goldsmith" ),
                "add_new_item" => __( "Add new Popup", "goldsmith" ),
                "edit_item" => __( "Edit Popup", "goldsmith" ),
                "new_item" => __( "New Popup", "goldsmith" ),
                "view_item" => __( "View Popup", "goldsmith" ),
                "view_items" => __( "View Popups", "goldsmith" ),
                "search_items" => __( "Search Popups", "goldsmith" ),
                "not_found" => __( "No Popups found", "goldsmith" ),
                "not_found_in_trash" => __( "No Popups found in trash", "goldsmith" ),
                "archives" => __( "Popup archives", "goldsmith" ),
            ];

            $args = [
                "label" => __( "Goldsmith Popups", "goldsmith" ),
                "labels" => $labels,
                "description" => "",
                "public" => true,
                "publicly_queryable" => true,
                "show_ui" => true,
                "show_in_rest" => true,
                "rest_base" => "",
                "rest_controller_class" => "WP_REST_Posts_Controller",
                "has_archive" => false,
                "show_in_menu" => "ninetheme_theme_manage",
                "show_in_nav_menus" => true,
                "delete_with_user" => false,
                "exclude_from_search" => true,
                "capability_type" => "post",
                "map_meta_cap" => true,
                "hierarchical" => false,
                "rewrite" => [ "slug" => "goldsmith_popups", "with_front" => true ],
                "query_var" => true,
                "supports" => [ "title", "editor", "author" ],
                "show_in_graphql" => false,
            ];

            register_post_type( "goldsmith_popups", $args );
        }

        public function set_custom_edit_goldsmith_popups_columns($columns) {
            $columns[ 'shortcode' ] = __( "Popups ID", "goldsmith" );
        
            return $columns;
        }
        
        public function custom_goldsmith_popups_column( $column, $post_id ) {
            
            if ( 'shortcode' === $column ) {
        
                /** %s = shortcode tag, %d = post_id */
                $shortcode = esc_attr(
                    sprintf(
                        '#%s%d',
                        'goldsmith-popup-',
                        $post_id
                    )
                );
                printf(
                    '<input class="goldsmith-popup-input widefat" type="text" readonly onfocus="this.select()" value="%s" />',
                    $shortcode
                );
            } 
        }
    }
    Goldsmith_Popup_Builder::get_instance();
}
