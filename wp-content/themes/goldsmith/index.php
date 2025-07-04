<?php
/**
* The main template file
*
* This is the most generic template file in a WordPress theme
* and one of the two required files for a theme (the other being style.css).
* It is used to display a page when nothing more specific matches a query.
* E.g., it puts together the home page when no home.php file exists.
*
* @link https://developer.wordpress.org/themes/basics/template-hierarchy/
*
* @package WordPress
* @subpackage Goldsmith
* @since 1.0.0
*/

get_header();

do_action( 'goldsmith_before_index' );

wp_enqueue_style( 'goldsmith-blog-post' );

// Elementor `archive` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
    ?>
    <div id="nt-index" class="nt-index nt-inner-page-wrapper">

        <?php
        // Hero section - this function using on all inner pages -->
        if ( !empty( goldsmith_settings( 'blog_hero_templates', null ) ) ) {

            echo goldsmith_print_elementor_templates( 'blog_hero_templates', 'custom-blog-hero' );

        } else {

            goldsmith_hero_section();

        }

        get_template_part( 'blog/layout/main' );

        ?>

    </div><!--End index general div -->
    <?php
}

// you can use this action to add any content after index page
do_action( 'goldsmith_after_index' );

get_footer();

?>
