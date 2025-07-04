<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>

    <!-- Meta UTF8 charset -->
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="initial-scale=1.0" />
    <meta name="viewport" content="width=device-width, height=device-height, minimal-ui" />
    <?php wp_head(); ?>
	<link rel="manifest" href="/site.webmanifest">


</head>

<!-- BODY START -->
<body <?php body_class(); ?>>
    <?php

    if ( function_exists( 'wp_body_open' ) ) {
        wp_body_open();
    }
    /**
    * Hook: goldsmith_after_body_open
    *
    * @hooked goldsmith_preloader - 10
    */
	do_action( 'goldsmith_after_body_open' );
    ?>
    <div id="wrapper" class="page-wrapper">

        <div class="goldsmith-main-overlay"></div>
        <?php
        // Elementor `header` location
        if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
            /**
            * Hook: goldsmith_header_action
            *
            * @hooked goldsmith_header - 10
            */
            do_action( 'goldsmith_header_action' );
        }
        ?>
        <div role="main" class="site-content">
            <div class="header-spacer"></div>
