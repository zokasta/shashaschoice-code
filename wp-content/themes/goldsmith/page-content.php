<div id="nt-page-container" class="nt-page-layout">

    <?php

    goldsmith_hero_section();

    ?>

    <div id="nt-page" class="nt-goldsmith-inner-container pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-12">

                    <?php while ( have_posts() ) : the_post(); ?>

                        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <div class="nt-goldsmith-content nt-clearfix content-container">
                                <?php

                                /* translators: %s: Name of current post */
                                the_content( sprintf(
                                    esc_html__( 'Continue reading %s', 'goldsmith' ),
                                    the_title( '<span class="screen-reader-text">', '</span>', false )
                                ) );

                                /* theme page link pagination */
                                goldsmith_wp_link_pages();

                                ?>
                            </div>
                        </div>
                        <?php

                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) {
                            wp_enqueue_script( 'comment-reply' );
                            comments_template();
                        }

                    // End the loop.
                    endwhile;
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>
