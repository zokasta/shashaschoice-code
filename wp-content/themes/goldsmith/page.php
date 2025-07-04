<?php

/**
* default page template
*/


get_header();

// Elementor `single` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {

    if ( goldsmith_check_is_elementor() ) {

        while ( have_posts() )
        {
            the_post();
            the_content();
        }

    } else {

        get_template_part( 'page', 'content' );

    }
}

get_footer();
