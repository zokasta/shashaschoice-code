<?php

/**
* Custom template parts for this theme.
*
* Eventually, some of the functionality here could be replaced by core features.
*
* @package goldsmith
*/


add_action( 'goldsmith_footer_action', 'goldsmith_footer', 10 );

if ( ! function_exists( 'goldsmith_footer' ) ) {
    function goldsmith_footer()
    {
        $footer_id = false;

        if ( class_exists( '\Elementor\Core\Settings\Manager' ) ) {

            $page_settings  = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' )->get_model( get_the_ID() );
            $page_footer_id = $page_settings->get_settings( 'goldsmith_page_footer_template' );
            $footer_id      = isset( $page_footer_id ) !== '' ? $page_footer_id : $footer_id;
            $footer_id      = apply_filters('goldsmith_elementor_footer_template', $footer_id );
        }

        if ( '0' != goldsmith_settings( 'footer_visibility', '1' ) ) {

            if ( class_exists( '\Elementor\Frontend' ) && 'elementor' == goldsmith_settings( 'footer_template', 'default' ) ) {

                if ( $footer_id ) {
                    $frontend = new \Elementor\Frontend;
                    printf( '<footer class="goldsmith-elementor-footer footer-'.$footer_id.'">%1$s</footer>', $frontend->get_builder_content_for_display( $footer_id, true ) );

                } else {

                    echo goldsmith_print_elementor_templates( 'footer_elementor_templates', 'goldsmith-elementor-footer', true );
                }

            } else {

                goldsmith_copyright();

            }
        }
    }
}

/*************************************************
##  FOOTER COPYRIGHT
*************************************************/

if ( ! function_exists( 'goldsmith_copyright' ) ) {
    function goldsmith_copyright()
    {
        ?>
        <footer id="nt-footer" class="goldsmith-footer-area goldsmith-default-copyright">
            <div class="container">
                <div class="copyright-text">
                    <?php
                    if ( '' != goldsmith_settings( 'footer_copyright' ) ) {

                        echo wp_kses( goldsmith_settings( 'footer_copyright' ), goldsmith_allowed_html() );

                    } else {
                        echo sprintf( '<p>Copyright &copy; %1$s, <a class="theme" href="%2$s">%3$s</a> Website. %4$s <a class="dev" href="https://ninetheme.com/contact/"> %5$s</a></p>',
                            date_i18n( _x( 'Y', 'copyright date format', 'goldsmith' ) ),
                            esc_url( home_url( '/' ) ),
                            get_bloginfo( 'name' ),
                            esc_html__( 'Made with passion by', 'goldsmith' ),
                            esc_html__( 'Ninetheme.', 'goldsmith' )
                        );
                    }
                    ?>
                </div>
            </div>
        </footer>
        <?php
    }
}
