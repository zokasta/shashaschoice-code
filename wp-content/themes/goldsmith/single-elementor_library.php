<?php

/**
* The template for displaying all single posts
*
* @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
*
* @package WordPress
* @subpackage Goldsmith
* @since 1.0.0
*/

    remove_action( 'goldsmith_header_action', 'goldsmith_main_header', 10 );
    remove_action( 'goldsmith_footer_action', 'goldsmith_footer', 10 );

    get_header();

    while ( have_posts() ) : the_post();
        the_content();
    endwhile;

    get_footer();
?>
