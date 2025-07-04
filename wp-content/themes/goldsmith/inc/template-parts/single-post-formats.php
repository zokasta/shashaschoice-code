<?php


add_action( 'goldsmith_single_post', 'goldsmith_single_post_thumbnail', 5 );
add_action( 'goldsmith_single_post', 'goldsmith_single_post_meta_top', 10 );
add_action( 'goldsmith_single_post', 'goldsmith_single_post_title', 15 );
add_action( 'goldsmith_single_post', 'goldsmith_single_post_content', 20 );
add_action( 'goldsmith_single_post', 'goldsmith_single_post_bottom_meta', 25 );

add_action( 'goldsmith_after_single_post', 'goldsmith_single_navigation', 5 );
add_action( 'goldsmith_after_single_post', 'goldsmith_single_post_author_box', 10 );

add_action( 'goldsmith_single_post_comment', 'goldsmith_single_post_comment_template' );
add_action( 'goldsmith_single_post_related', 'goldsmith_single_post_related_template' );

add_action( 'goldsmith_single', 'goldsmith_single_layout' );

if ( ! function_exists( 'goldsmith_single_layout' ) ) {

    function goldsmith_single_layout()
    {
        $hero_template     = apply_filters( 'goldsmith_single_post_hero_template', goldsmith_settings( 'single_hero_elementor_templates', null ) );
        $goldsmith_layout  = goldsmith_settings( 'single_layout', 'right-sidebar' );
        $goldsmith_sidebar = goldsmith_sidebar( 'goldsmith-single-sidebar', 'sidebar-1' );
        $goldsmith_column  = 'full-width' != $goldsmith_layout && ( ! empty( goldsmith_settings( 'blog_sidebar_templates', null ) ) || $goldsmith_sidebar ) ? 'col-lg-9' : 'col-lg-12';
        $row_reverse       = ( ! empty( goldsmith_settings( 'blog_sidebar_templates', null ) ) || $goldsmith_sidebar ) && 'left-sidebar' == $goldsmith_layout ? ' flex-lg-row-reverse' : '';

        wp_enqueue_style( 'goldsmith-blog-post' );

        ?>
        <!-- Single page general div -->
        <div id="nt-single" class="nt-single">

            <?php
            if ( $hero_template ) {

                echo goldsmith_print_elementor_templates( $hero_template, 'custom-single-hero' );

            } else {

                goldsmith_hero_section();

            }
            ?>

            <div class="goldsmith-blog-details-area section-padding">
                <div class="container">
                    <div class="row <?php echo esc_attr( $row_reverse ); ?>">

                        <div class="<?php echo esc_attr( $goldsmith_column ); ?>">
                            <div <?php post_class( 'goldsmith-blog-post-details', get_the_ID() ); ?>>
                                <div class="nt-goldsmith-content">
                                    <?php

                                    do_action( 'goldsmith_before_single_post' );

                                    while ( have_posts() ) : the_post();

                                    /**
                                    * Hook: goldsmith_single_post.
                                    *
                                    * @hooked goldsmith_single_post_thumbnail', 5 );
                                    * @hooked goldsmith_single_post_meta_top', 10 );
                                    * @hooked goldsmith_single_post_content', 15 );
                                    * @hooked goldsmith_single_post_category', 20 );
                                    * @hooked goldsmith_single_post_bottom_meta', 25 );
                                    */
                                    do_action( 'goldsmith_single_post' );


                                    endwhile;
                                    /**
                                    * Hook: goldsmith_after_single_post.
                                    *
                                    * @hooked goldsmith_single_post_author_box', 5 );
                                    * @hooked goldsmith_single_navigation', 10 );
                                    */
                                    do_action( 'goldsmith_after_single_post' );

                                    ?>
                                </div>
                                <?php do_action( 'goldsmith_single_post_comment' ); ?>
                            </div>
                        </div>

                        <?php
                        if ( 'full-width' != $goldsmith_layout ) {
                            if ( ! empty( goldsmith_settings( 'blog_sidebar_templates', null ) ) ) {

                                get_sidebar();

                            } elseif ( $goldsmith_sidebar ) {
                                ?>
                                <div id="nt-sidebar" class="col-lg-3 col-md-3">
                                    <div class="blog-sidebar nt-sidebar-inner">

                                        <?php dynamic_sidebar( $goldsmith_sidebar ); ?>

                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
            <?php
            /**
            * Hook: goldsmith_single_post_related.
            *
            * @hooked goldsmith_single_post_related_template' );
            */
            do_action( 'goldsmith_single_post_related' );
            ?>
        </div>
        <?php
    }
}

/*************************************************
##  POST FORMAT
*************************************************/

if ( ! function_exists( 'goldsmith_single_post_title' ) ) {

    function goldsmith_single_post_title()
    {
        the_title('<h2 class="goldsmith-post-title">', '</h2>' );

    }
}

if ( ! function_exists( 'goldsmith_single_post_thumbnail' ) ) {

    function goldsmith_single_post_thumbnail()
    {
        if ( has_post_thumbnail() ) {
            ?>
            <div class="goldsmith-post-thumb-wrapper">
                <?php the_post_thumbnail( 'large' ); ?>
            </div>
            <?php
        }
    }
}

if ( ! function_exists( 'goldsmith_single_post_content' ) ) {

    function goldsmith_single_post_content()
    {
        $content = get_the_content();
        if ( '' != $content ) {
            echo '<div class="goldsmith-post-content-wrapper">';
                the_content();
                goldsmith_wp_link_pages();
            echo '</div>';
        }
    }
}

if ( ! function_exists( 'goldsmith_single_post_bottom_meta' ) ) {

    function goldsmith_single_post_bottom_meta()
    {
        if ( has_tag() || has_category() ) {
        ?>
        <div class="goldsmith-blog-post-meta-bottom">
            <?php goldsmith_single_post_categories(); ?>
            <?php goldsmith_single_post_tags(); ?>
        </div>
        <?php
        }
    }
}

if ( ! function_exists( 'goldsmith_single_post_categories' ) ) {

    function goldsmith_single_post_categories()
    {
        if ( has_category() && '0' != goldsmith_settings( 'single_postmeta_category_visibility', '1' ) ) {
            ?>
            <div class="goldsmith-post-categories-wrapper goldsmith-post-meta">
                <span class="goldsmith-meta-label"><?php esc_html_e( 'Categories:', 'goldsmith' ); ?></span> <?php the_category(', ',''); ?>
            </div>
            <?php
        }
    }
}

if ( ! function_exists( 'goldsmith_single_post_tags' ) ) {

    function goldsmith_single_post_tags()
    {
        if ( '0' != goldsmith_settings('single_postmeta_tags_visibility', '1' ) && has_tag() ) {
            ?>
            <div class="goldsmith-post-tags-wrapper goldsmith-post-meta">
                <span class="goldsmith-meta-label"><?php esc_html_e( 'Tags:', 'goldsmith' ); ?></span> <?php the_tags( '', ', ' ); ?>
            </div>
            <?php
        }
    }
}

if ( ! function_exists( 'goldsmith_single_post_meta_top' ) ) {

    function goldsmith_single_post_meta_top()
    {
        if ( '0' != goldsmith_settings( 'single_postmeta_author_visibility', '1' ) || '0' != goldsmith_settings( 'single_postmeta_date_visibility', '1' ) ) {
            $archive_year = get_the_time( 'Y' );
            $archive_month = get_the_time( 'm' );
            $archive_day = get_the_time( 'd' );
            ?>
            <div class="goldsmith-blog-post-meta-top">
                <?php goldsmith_single_post_author(); ?>
                <?php goldsmith_single_post_date(); ?>
            </div>
            <?php
        }
    }
}

if ( ! function_exists( 'goldsmith_single_post_date' ) ) {

    function goldsmith_single_post_date()
    {
        if ( '0' != goldsmith_settings( 'single_postmeta_date_visibility', '1' ) ) {
            $archive_year  = get_the_time( 'Y' );
            $archive_month = get_the_time( 'm' );
            $archive_day   = get_the_time( 'd' );
            ?>
            <a href="<?php echo esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a>
            <?php
        }
    }
}

if ( ! function_exists( 'goldsmith_single_post_author' ) ) {

    function goldsmith_single_post_author()
    {
        global $post;
        $author_id   = $post->post_author;
        $author_link = get_author_posts_url( $author_id );
        if ( '0' != goldsmith_settings( 'single_postmeta_author_visibility', '1' ) ) {
            ?>
            <a href="<?php echo esc_url( $author_link ); ?>"><?php the_author_meta( 'display_name', $post->post_author ); ?></a>
            <?php
        }
    }
}

if ( ! function_exists( 'goldsmith_single_post_comment_number' ) ) {

    function goldsmith_single_post_comment_number()
    {
        if ( comments_open() && '0' != get_comments_number() && '0' != goldsmith_settings( 'single_postmeta_comments_visibility', '1' ) ) {
            wp_enqueue_script( 'comment-reply' );
            ?>
            <a href="<?php echo get_comments_link( get_the_ID() ); ?>"><?php printf( _nx( 'One Comment', '%1$s Comments', get_comments_number(), 'comments title', 'goldsmith' ), number_format_i18n( get_comments_number() ) ); ?></a>
            <?php
        }
    }
}


if ( ! function_exists( 'goldsmith_single_post_comment_template' ) ) {

    function goldsmith_single_post_comment_template()
    {
        if ( comments_open() ) {
            wp_enqueue_script( 'comment-reply' );
            ?>
            <div class="goldsmith-post-comments">
                <?php echo comments_template(); ?>
            </div>
            <?php
        }
    }
}


/*************************************************
## SINGLE POST AUTHOR BOX FUNCTION
*************************************************/

if ( ! function_exists( 'goldsmith_single_post_author_box' ) ) {

    function goldsmith_single_post_author_box()
    {
        global $post;

        if ( '0' != goldsmith_settings('single_post_author_box_visibility', '0') ) {
            // Get author's display name
            $display_name     = get_the_author_meta('display_name', $post->post_author);
            // If display name is not available then use nickname as display name
            $display_name     = empty( $display_name ) ? get_the_author_meta('nickname', $post->post_author) : $display_name ;
            // Get author's biographical information or description
            $user_description = get_the_author_meta('user_description', $post->post_author);
            // Get author's website URL
            $user_website     = get_the_author_meta('url', $post->post_author);
            // Get link to the author archive page
            $user_posts       = get_author_posts_url(get_the_author_meta('ID', $post->post_author));
            // Get the rest of the author links. These are stored in the
            // wp_usermeta table by the key assigned in wpse_user_contactmethods()
            $author_facebook  = get_the_author_meta('facebook', $post->post_author);
            $author_twitter   = get_the_author_meta('twitter', $post->post_author);
            $author_instagram = get_the_author_meta('instagram', $post->post_author);
            $author_linkedin  = get_the_author_meta('linkedin', $post->post_author);
            $author_youtube   = get_the_author_meta('youtube', $post->post_author);

            if ( '' != $user_description ) {
                ?>
                <div class="avatar-post mt-50 mb-50">

                    <div class="post-avatar-img">
                        <?php
                        if ( function_exists( 'get_avatar' ) ) {
                            echo get_avatar( get_the_author_meta( 'email' ), '150');
                        }
                        ?>
                    </div>

                    <div class="post-avatar-content">

                        <h5><?php echo esc_html( $display_name ); ?></h5>
                        <p class="mb-0"><?php echo esc_html($user_description); ?></p>

                        <div class="post-avatar-social mt-20">
                            <ul>
                                <?php if ('' != $author_facebook) { ?>
                                    <li><a href="<?php echo esc_url($author_facebook); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                <?php } ?>
                                <?php if ('' != $author_twitter) { ?>
                                    <li><a href="<?php echo esc_url($author_twitter); ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                <?php } ?>
                                <?php if ('' != $author_instagram) { ?>
                                    <li><a href="<?php echo esc_url($author_instagram); ?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                <?php } ?>
                                <?php if ('' != $author_linkedin) { ?>
                                    <li><a href="<?php echo esc_url($author_linkedin); ?>" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                                <?php } ?>
                                <?php if ('' != $author_youtube) { ?>
                                    <li><a href="<?php echo esc_url($author_youtube); ?>" target="_blank"><i class="ifab fa-youtube"></i></a></li>
                                <?php } ?>

                            </ul>
                        </div>

                    </div>
                </div>
                <?php
            }
        }
    }
}

/*************************************************
## SINGLE POST RELATED POSTS
*************************************************/

if ( ! function_exists( 'goldsmith_single_post_related_template' ) ) {

    function goldsmith_single_post_related_template()
    {
        if ( '0' == goldsmith_settings( 'single_related_visibility', '0' ) ) {
            return;
        }

        if ( ! empty( goldsmith_settings( 'blog_sidebar_templates', null ) ) ) {

            echo goldsmith_print_elementor_templates( 'single_related_elementor_templates', true );

        } else {

            global $post;
            $goldsmith_post_type = get_post_type( $post->ID );

            $sattr      = array();
            $speed      = goldsmith_settings( 'related_speed', 1000 );
            $perview    = goldsmith_settings( 'related_perview', 4 );
            $mdperview  = goldsmith_settings( 'related_mdperview', 3 );
            $smperview  = goldsmith_settings( 'related_smperview', 2 );
            $xsperview  = goldsmith_settings( 'related_xsperview', 1 );
            $gap        = goldsmith_settings( 'related_gap', 30 );
            $centered   = '1' == goldsmith_settings( 'related_centered', 1 ) ? 'true' : 'false';
            $loop       = '1' == goldsmith_settings( 'related_loop', 0 ) ? 'true' : 'false';
            $autoplay   = '1' == goldsmith_settings( 'related_autoplay', 1 ) ? 'true' : 'false';
            $mousewheel = '1' == goldsmith_settings( 'related_mousewheel', 0 ) ? 'true' : 'false';

            $imgsize    = goldsmith_settings( 'related_imgsize', 'goldsmith-square' );
            $imgsize2   = goldsmith_settings( 'related_custom_imgsize' );
            $imgsize    = '' == $imgsize && !empty( $imgsize2 ) ? array($imgsize2['width'],$imgsize2['height'] ) : $imgsize;
            $ttag       = goldsmith_settings( 'related_title_tag', 'h3' );
            $subtag     = goldsmith_settings( 'related_subtitle_tag', 'p' );
            $style      = goldsmith_settings( 'related_post_style', goldsmith_settings( 'post_style', 'default' ) );

            $cats = get_the_category( $post->ID );
            $args = array(
                'post__not_in' => array( $post->ID ),
                'posts_per_page' => goldsmith_settings( 'related_perpage', 6 )
            );

            $related_query = new WP_Query( $args );

            if ( $related_query->have_posts() ) {
                ?>
                <div class="goldsmith-related-post-area goldsmith-bg section-padding">
                    <div class="container">
                        <div class="row justify-content-center">
                            <?php if ( '' != goldsmith_settings( 'related_subtitle' ) || '' != goldsmith_settings( 'related_title' ) ) { ?>
                                <div class="col-lg-8">
                                    <div class="section-title text-center mb-40">
                                        <?php if ( '' != goldsmith_settings( 'related_subtitle' ) ) { ?>
                                            <<?php echo esc_attr( $subtag ); ?> class="sub-title"><?php echo esc_html( goldsmith_settings( 'related_subtitle' ) ); ?></<?php echo esc_attr( $subtag ); ?>>
                                        <?php } ?>
                                        <?php if ( '' != goldsmith_settings( 'related_title' ) ) { ?>
                                            <<?php echo esc_attr( $ttag ); ?> class="title"><?php echo esc_html( goldsmith_settings( 'related_title' ) ); ?></<?php echo esc_attr( $ttag ); ?>>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="col-lg-12">
                                <div class="goldsmith-related-slider goldsmith-swiper-theme-style swiper-container goldsmith-swiper-slider goldsmith-blog-slider" data-swiper-options=<?php echo '\'{"speed": '.$speed.',"centered": '.$centered.',"loop": '.$loop.',"autoplay": '.$autoplay.',"mousewheel": '.$mousewheel.',"slidesPerView": '.$perview.',"spaceBetween": '.$gap.',"navigation": {"nextEl": ".goldsmith-related-slider .slide-next","prevEl": ".goldsmith-related-slider .slide-prev"},"pagination" : {"el": ".goldsmith-related-slider .goldsmith-related-pagination","type" : "bullets","clickable" : "true"},"breakpoints": {"320": {"slidesPerView": '.$xsperview.'},"768": {"slidesPerView": '.$smperview.'},"1024": {"slidesPerView": '.$mdperview.'},"1200": {"slidesPerView": '.$perview.'}}}\''; ?>>
                                    <div class="swiper-wrapper">
                                        <?php
                                        while ( $related_query->have_posts() ) {
                                            $related_query->the_post();
                                            ?>
                                            <div class="swiper-slide">
                                                <?php get_template_part( 'blog/style/'. $style ); ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <div class="swiper-pagination goldsmith-related-pagination"></div>

                                    <div class="swiper-button-prev slide-prev"></div>
                                    <div class="swiper-button-next slide-next"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                wp_reset_postdata();
            }
        }
    }
}
