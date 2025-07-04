<?php

/**
* The template for displaying 404 pages (not found)
*
* @link https://codex.wordpress.org/Creating_an_Error_404_Page
*
* @package WordPress
* @subpackage Goldsmith
* @since 1.0.0
*/

if ( '0' == goldsmith_settings( 'error_header_visibility', '1' ) ) {
    remove_action( 'goldsmith_header_action', 'goldsmith_main_header', 10 );
}
if ( '0' == goldsmith_settings( 'error_footer_visibility', '1' ) ) {
    remove_action( 'goldsmith_footer_action', 'goldsmith_footer', 10 );
}

get_header();

// you can use this action for add any content before container element
do_action( 'goldsmith_before_404' );

if ( 'elementor' == goldsmith_settings( 'error_page_type', 'default' ) && !empty( goldsmith_settings( 'error_page_elementor_templates' ) ) ) {

    echo goldsmith_print_elementor_templates( 'error_page_elementor_templates', false );

} else {
    $btn_title  = '' != goldsmith_settings( 'error_content_btn_title' ) ? goldsmith_settings( 'error_content_btn_title' ) : esc_html__( 'Go to home page', 'goldsmith' );
    $error_desc = '' != goldsmith_settings( 'error_content_desc' ) ? goldsmith_settings( 'error_content_desc' ) : esc_html__( 'Sorry, but the page you are looking for does not exist or has been removed!', 'goldsmith' );
    ?>
    <div id="nt-404" class="nt-404 error nt-inner-page-wrapper">

        <?php goldsmith_hero_section(); ?>

        <div class="nt-goldsmith-inner-container goldsmith-error-area pt-100 pb-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="goldsmith-error-content">

                            <div class="goldsmith-error-txt"><?php esc_html_e( '404','goldsmith' ); ?></div>

                            <?php
                            if ( '0' != goldsmith_settings('error_content_desc_visibility', '1' ) ) {
                                printf( '<h5 class="content-text">%s</h5>', esc_html( $error_desc ) );
                            }

                            if ( '0' != goldsmith_settings( 'error_content_form_visibility', '0' ) ) {
                                echo goldsmith_search_form();
                            }

                            if ( '0' != goldsmith_settings('error_content_btn_visibility', '1' ) ) {
                                printf( '<a href="%1$s" class="goldsmith-btn-medium goldsmith-btn goldsmith-bg-black mt-30"><span>%2$s</span></a>',
                                    esc_url( home_url('/') ),
                                    esc_html( $btn_title )
                                );
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

// use this action to add any content after 404 page container element
do_action( 'goldsmith_after_404' );

get_footer();

?>
