<?php


/**
 * Custom template parts for this theme.
 *
 * preloader, backtotop, conten-none
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package goldsmith
*/


/*************************************************
## START PRELOADER
*************************************************/

if ( ! function_exists( 'goldsmith_preloader' ) ) {
    function goldsmith_preloader()
    {
        $type = goldsmith_settings('pre_type', 'default');

        if ( '0' != goldsmith_settings('preloader_visibility', '1') ) {

            if ( 'default' == $type && '' != goldsmith_settings( 'pre_img', '' ) ) {
                ?>
                <div class="preloader">
                    <img class="preloader__image" width="55" src="<?php echo esc_url( goldsmith_settings( 'pre_img' )[ 'url' ] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
                </div>
                <?php
            } else {
                ?>
                <div id="nt-preloader" class="preloader">
                    <div class="loader<?php echo esc_attr( $type );?>"></div>
                </div>
                <?php
            }
        }
    }
}
add_action( 'goldsmith_after_body_open', 'goldsmith_preloader', 10 );
add_action( 'elementor/page_templates/canvas/before_content', 'goldsmith_preloader', 10 );

/*************************************************
##  BACKTOP
*************************************************/

if ( ! function_exists( 'goldsmith_backtop' ) ) {
    add_action( 'goldsmith_before_wp_footer', 'goldsmith_backtop', 10 );
    function goldsmith_backtop() {
        if ( '1' == goldsmith_settings('backtotop_visibility', '1') ) { ?>
            <a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="nt-icon-up-chevron"></i></a>
            <?php
        }
    }
}


/*************************************************
##  CONTENT NONE
*************************************************/

if ( ! function_exists( 'goldsmith_content_none' ) ) {
    function goldsmith_content_none() {
        ?>

        <div class="col-lg-6 col-12 goldsmith_content_none">
            <div class="content-none-container">
                <h3 class="__title mb-20"><?php esc_html_e( 'Nothing Found', 'goldsmith' ); ?></h3>
                <?php
                    if ( is_home() && current_user_can( 'publish_posts' ) ) :

                        printf( '<p>%s</p> <a class="thm-btn" href="%s">%s</a>',
                        esc_html__( 'Ready to publish your first post?', 'goldsmith' ),
                        esc_url( admin_url( 'post-new.php' ) ),
                        esc_html__( 'Get started here', 'goldsmith' )
                    );
                    elseif ( is_search() ) :
                    ?>
                    <h5 class="__nothing"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'goldsmith' ); ?></h5>

                    <?php printf( '<a href="%1$s" class="goldsmith-btn-medium goldsmith-btn goldsmith-bg-black"><span>%2$s</span></a>',
                            esc_url( home_url('/') ),
                            esc_html__( 'Go to home page', 'goldsmith' )
                        );
                    ?>

                <?php else : ?>
                    <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'goldsmith' ); ?></p>


                <?php printf( '<a href="%1$s" class="goldsmith-btn-medium goldsmith-btn goldsmith-bg-black"><span>%2$s</span></a>',
                        esc_url( home_url('/') ),
                        esc_html__( 'Go to home page', 'goldsmith' )
                    );
                ?>

                <?php endif; ?>
            </div>
        </div>

        <?php
    }
}
