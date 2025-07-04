<?php

/*************************************************
## THEME DEFAULT HERO TEMPLATE
*************************************************/
if ( ! function_exists( 'goldsmith_hero_section' ) ) {

    function goldsmith_hero_section()
    {
        $h_t = get_the_title();
        $page_id = '';

        if ( is_404() ) {

            $name = 'error';
            $h_t  = esc_html__( 'Page Not Found', 'goldsmith' );

        } elseif ( is_archive() ) {

            $name = 'archive';
            $h_t  = get_the_archive_title();
            $oh_t = goldsmith_settings( 'archive_title', '' );
            $h_t  = $oh_t ? $oh_t : $h_t;

        } elseif ( is_search() ) {

            $name = 'search';
            $h_t  = esc_html__( 'Search results for :', 'goldsmith' );
            $oh_t = goldsmith_settings( 'search_title', '' );
            $h_t  = $oh_t ? $oh_t : $h_t;

        } elseif ( is_home() || is_front_page() ) {

            $name = 'blog';
            $h_t  = esc_html__( 'Blog', 'goldsmith' );
            $oh_t = goldsmith_settings( 'blog_title', '' );
            $h_t  = $oh_t ? $oh_t : $h_t;

        } elseif ( is_single() ) {

            $name = 'single';
            $h_t  = get_the_title();

        } elseif ( is_page() ) {

            $name = 'page';
            $h_t = get_the_title();
            $page_id = 'page-'.get_the_ID();
        }

        do_action( 'goldsmith_before_page_hero' );

        if ( '0' != goldsmith_settings( $name.'_hero_visibility', '1' ) ) {
            ?>
            <div class="goldsmith-page-hero <?php echo esc_attr( $page_id ); ?>">
                <div class="goldsmith-page-hero-content container">
                    <?php
                    do_action( 'goldsmith_before_page_title' );
                    echo goldsmith_breadcrumbs();

                    if ( !is_single() ) {
                        if ( $h_t ) {

                            printf( '<h2 class="nt-hero-title page-title">%s %s</h2>',
                                wp_kses( $h_t, goldsmith_allowed_html() ),
                                strlen( get_search_query() ) > 16 ? substr( get_search_query(), 0, 16 ).'...' : get_search_query()
                            );

                        } else {

                            the_title('<h2 class="nt-hero-title page-title">', '</h2>');
                        }
                    }

                    do_action( 'goldsmith_after_page_title' );
                    ?>
                </div>
            </div>
            <?php
        }
        do_action( 'goldsmith_after_page_hero' );
    }
}
