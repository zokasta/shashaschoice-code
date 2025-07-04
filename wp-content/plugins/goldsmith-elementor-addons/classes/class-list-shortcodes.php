<?php
use Elementor\TemplateLibrary\Source_Local;

// includes/shortcodes/elementor

/**
* Prevent direct access to this file.
*
* @since 1.0.0
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
* Add post type list table column only for Elementor free version:
* @since 1.0.0
*/
function goldsmith_string_shortcode() {

    return __( 'Shortcode', 'naturall' );

}  // end function

add_action( 'manage_' . Source_Local::CPT . '_posts_columns', 'goldsmith_admin_columns_headers_elementor_templates' );
/**
* Add post type list table column for "Saved Templates" post type.
*
* @since 1.0.0
*
* @uses goldsmith_string_shortcode()
*
* @param array $columns Holds all post type list table columns.
* @return array Modified array of post type list table columns.
*/
function goldsmith_admin_columns_headers_elementor_templates( $columns ) {

    $columns[ 'shortcode' ] = goldsmith_string_shortcode();

    return $columns;
    
}  // end function


add_action( 'manage_' . Source_Local::CPT . '_posts_custom_column', 'goldsmith_admin_columns_content_elementor_templates', 10, 2 );
/**
* Add the "Shortcode" content for the post type list table for "Saved
*   Templates" post type.
*
* @since 1.0.0
*
* @param string $column_name Name of the column the content gets added for.
* @param string $post_id     Id of the current post type item, for table row.
* @return string Echoing column content.
*/
function goldsmith_admin_columns_content_elementor_templates( $column_name, $post_id ) {

    if ( 'shortcode' === $column_name ) {

        /** %s = shortcode tag, %d = post_id */
        $shortcode = esc_attr(
            sprintf(
                '[%s id="%d"]',
                'goldsmith-template',
                $post_id
            )
        );
        printf(
            '<input class="goldsmith-shortcode-input widefat" type="text" readonly onfocus="this.select()" value="%s" />',
            $shortcode
        );
    }  // end if
}  // end function


if ( ! function_exists( 'goldsmith_shortcode_elementor_template' ) ) {

    add_shortcode( 'goldsmith-template', 'goldsmith_shortcode_elementor_template' );
    /**
    * Shortcode to output an Elementor Saved Template by given ID.
    *
    * @since 1.0.0
    *
    * @uses \Elementor\Plugin::$instance
    *
    * @param array|string $atts Shortcode attributes. Empty string if no attributes.
    * @return string Output for `footer_home_link` shortcode.
    */
    function goldsmith_shortcode_elementor_template( $atts = [] ) {

        /** Set default shortcode attributes */
        $defaults = apply_filters(
            'goldsmith/filter/shortcode_defaults/elementor_template',
            array(
                'id' => '',
                'css' => 'false',
            )
        );

        /** Default shortcode attributes */
        $atts = shortcode_atts( $defaults, $atts, 'goldsmith-template' );

        if ( empty( $atts[ 'id' ] ) ) {
            return '';
        }

        $include_css = false;

        if ( isset( $atts[ 'css' ] ) && 'false' !== $atts[ 'css' ] ) {
            $include_css = (bool) $atts[ 'css' ];
        }

        $output = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $atts[ 'id' ], $include_css );

        /** Return the output - filterable */
        return apply_filters(
            'goldsmith/filter/shortcode/elementor_template',
            $output,
            $atts // additional param
        );
    }  // end function
}
