<?php

    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if (! class_exists('Redux' )) {
        return;
    }

    // This is your option name where all the Redux data is stored.
    $goldsmith_pre = "goldsmith";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $goldsmith_theme = wp_get_theme(); // For use with some settings. Not necessary.

    $goldsmith_options_args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name' => $goldsmith_pre,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name' => $goldsmith_theme->get('Name' ),
        // Name that appears at the top of your panel
        'display_version' => $goldsmith_theme->get('Version' ),
        // Version that appears at the top of your panel
        'menu_type' => 'submenu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu' => false,
        // Show the sections below the admin menu item or not
        'menu_title' => esc_html__( 'Theme Options', 'goldsmith' ),
        'page_title' => esc_html__( 'Theme Options', 'goldsmith' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key' => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography' => false,
        // Use a asynchronous font on the front end or font string
        'admin_bar' => false,
        // Show the panel pages on the admin bar
        'admin_bar_icon' => 'dashicons-admin-generic',
        // Choose an icon for the admin bar menu
        'admin_bar_priority' => 50,
        // Choose an priority for the admin bar menu
        'global_variable' => 'goldsmith',
        // Set a different name for your global variable other than the goldsmith_pre
        'dev_mode' => false,
        // Show the time the page took to load, etc
        'update_notice' => false,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer' => true,
        // Enable basic customizer support

        // OPTIONAL -> Give you extra features
        'page_priority' => 99,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent' => apply_filters( 'ninetheme_parent_slug', 'themes.php' ),
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions' => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon' => '',
        // Specify a custom URL to an icon
        'last_tab' => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon' => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug' => '',
        // Page slug used to denote the panel, will be based off page title then menu title then goldsmith_pre if not provided
        'save_defaults' => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show' => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark' => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export' => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time' => 60 * MINUTE_IN_SECONDS,
        'output' => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag' => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database' => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn' => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints' => array(
            'icon' => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color' => 'lightgray',
            'icon_size' => 'normal',
            'tip_style' => array(
                'color' => 'dark',
                'shadow' => true,
                'rounded' => false,
                'style' => '',
            ),
            'tip_position' => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect' => array(
                'show' => array(
                    'effect' => 'slide',
                    'duration' => '500',
                    'event' => 'mouseover',
                ),
                'hide' => array(
                    'effect' => 'slide',
                    'duration' => '500',
                    'event' => 'click mouseleave',
                ),
            ),
        )
    );

    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $goldsmith_options_args['admin_bar_links'][] = array(
        'id' => 'ninetheme-goldsmith-docs',
        'href' => 'https://ninetheme.com/docs/goldsmith-documentation/',
        'title' => esc_html__( 'goldsmith Documentation', 'goldsmith' ),
    );
    $goldsmith_options_args['admin_bar_links'][] = array(
        'id' => 'ninetheme-support',
        'href' => 'https://9theme.ticksy.com/',
        'title' => esc_html__( 'Support', 'goldsmith' ),
    );
    $goldsmith_options_args['admin_bar_links'][] = array(
        'id' => 'ninetheme-portfolio',
        'href' => 'https://themeforest.net/user/ninetheme/portfolio',
        'title' => esc_html__( 'NineTheme Portfolio', 'goldsmith' ),
    );

    // Add content after the form.
    $goldsmith_options_args['footer_text'] = esc_html__( 'If you need help please read docs and open a ticket on our support center.', 'goldsmith' );

    Redux::setArgs($goldsmith_pre, $goldsmith_options_args);

    /* END ARGUMENTS */

    /* START SECTIONS */

    $el_args = array(
        'post_type'      => 'elementor_library',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'elementor_library_type',
                'field'    => 'slug',
                'terms'    => 'section'
            )
        )
    );

    $activekit = get_option( 'elementor_active_kit' );

    $wpcf7_args = array(
        'post_type'      => 'wpcf7_contact_form',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC'
    );

    /*************************************************
    ## MAIN SETTING SECTION
    *************************************************/
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Main Setting', 'goldsmith' ),
        'id' => 'basic',
        'desc' => esc_html__( 'These are main settings for general theme!', 'goldsmith' ),
        'icon' => 'el el-cog',
        'fields' => array()
    ));
    //BREADCRUMBS SETTINGS SUBSECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Layout', 'goldsmith' ),
        'id' => 'thememainlayoutsubsection',
        'icon' => 'el el-brush',
        'subsection' => true,
        'fields' => array(
            array(
                'id' =>'edit_layout_settings',
                'type' => 'info',
                'desc' => esc_html__( 'Wrapper layout settings', 'goldsmith' ),
            ),
            array(
                'title' => esc_html__( 'Boxed Layout', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can change main layout as boxed or use fullwidth', 'goldsmith' ),
                'customizer' => true,
                'id' => 'theme_boxed_layout',
                'type' => 'switch',
                'default' => false
            ),
            array(
                'title' => esc_html__( 'Theme Wrapper Max Width', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'boxed_max_width',
                'type' => 'slider',
                'default' => 1600,
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'display_value' => 'text',
                'required' => array( 'theme_boxed_layout', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Theme Content Width', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'content_width',
                'type' => 'slider',
                'default' => 1560,
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'display_value' => 'text',
                'required' => array( 'theme_boxed_layout', '=', '0' )
            )
        )
    ));
    //BREADCRUMBS SETTINGS SUBSECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Typograhy', 'goldsmith' ),
        'id' => 'themetypograhysubsection',
        'icon' => 'el el-brush',
        'subsection' => true,
        'fields' => array(
            array(
                'id' =>'edit_typograhy_settings',
                'type' => 'info',
                'desc' => sprintf( '<b>%s</b> <a class="thm-btn" href="%s" target="_blank">%s</a>',
                    esc_html__( 'This theme uses Elementor Site Settings', 'goldsmith' ),
                    admin_url('post.php?post='.$activekit.'&action=elementor'),
                    esc_html__( 'Site Settings', 'goldsmith' )
                )
            ),
            array(
                'title' => esc_html__( 'Disable Theme Default All Fonts', 'goldsmith' ),
                'subtitle' => esc_html__( 'If you want to remove the default fonts of the theme, you can use this option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'theme_fonts_visibility',
                'type' => 'switch',
                'on' => esc_html__( 'Yes', 'goldsmith' ),
                'off' => esc_html__( 'No', 'goldsmith' ),
                'default' => false
            ),
            array(
                'title' => esc_html__( 'Disable Theme Main Fonts ( Jost )', 'goldsmith' ),
                'subtitle' => esc_html__( 'If you want to remove the default Jost font family of the theme, you can use this option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'theme_fonts_jost_visibility',
                'type' => 'switch',
                'on' => esc_html__( 'Yes', 'goldsmith' ),
                'off' => esc_html__( 'No', 'goldsmith' ),
                'default' => false,
                'required' => array( 'theme_fonts_visibility', '!=', '1' )
            ),
            array(
                'title' => esc_html__( 'Theme Main Font Weights ( Jost )', 'goldsmith' ),
                'subtitle' => esc_html__( 'If you want to remove the default Jost font family of the theme, you can use this option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'theme_fonts_jost_weights',
                'type' => 'select',
                'sortable'  => true,
                'options' => array(
                    '300' => 'light 300',
                    '400' => 'regular 400',
                    '500' => 'medium 500',
                    '600' => 'bold 600',
                    '700' => 'semi-bold 700'
                ),
                'multi' => true,
                'default' => array('300','400','500','600','700'),
                'required' => array(
                    array( 'theme_fonts_visibility', '!=', '1' ),
                    array( 'theme_fonts_jost_visibility', '!=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Disable Theme Second Fonts ( Manrope )', 'goldsmith' ),
                'subtitle' => esc_html__( 'If you want to remove the default manrope font family of the theme, you can use this option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'theme_fonts_manrope_visibility',
                'type' => 'switch',
                'default' => false,
                'on' => esc_html__( 'Yes', 'goldsmith' ),
                'off' => esc_html__( 'No', 'goldsmith' ),
                'required' => array( 'theme_fonts_visibility', '!=', '1' )
            ),
            array(
                'title' => esc_html__( 'Theme Second Font Weights ( Manrope )', 'goldsmith' ),
                'subtitle' => esc_html__( 'If you want to remove the default Manrope font family of the theme, you can use this option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'theme_fonts_manrope_weights',
                'type' => 'select',
                'sortable'  => true,
                'options' => array(
                    '400' => 'regular 400',
                    '500' => 'medium 500',
                    '600' => 'bold 600',
                    '700' => 'semi-bold 700',
                    '800' => 'extra-bold 800',
                ),
                'multi' => true,
                'default' => array('400','500','600','700','800'),
                'required' => array(
                    array( 'theme_fonts_visibility', '!=', '1' ),
                    array( 'theme_fonts_manrope_visibility', '!=', '1' )
                )
            )
        )
    ));
    //BREADCRUMBS SETTINGS SUBSECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Colors', 'goldsmith' ),
        'id' => 'themecolorssubsection',
        'icon' => 'el el-brush',
        'subsection' => true,
        'fields' => array(
            array(
                'title' => esc_html__( 'Theme Base Color', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add theme root base color.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'theme_clr1',
                'type' => 'color',
                'default' => ''
            ),
            array(
                'title' => esc_html__( 'Theme Primary Color', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add theme root primary color.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'theme_clr2',
                'type' => 'color',
                'default' => ''
            ),
            array(
                'title' => esc_html__( 'Theme Black Color', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add theme root black color.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'theme_clr3',
                'type' => 'color',
                'default' => ''
            ),
            array(
                'title' => esc_html__( 'Theme Black Color 2', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add theme root black color.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'theme_clr4',
                'type' => 'color',
                'default' => ''
            )
        )
    ));
    //BREADCRUMBS SETTINGS SUBSECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Breadcrumbs', 'goldsmith' ),
        'id' => 'themebreadsubsection',
        'icon' => 'el el-brush',
        'subsection' => true,
        'fields' => array(
            array(
                'title' => esc_html__( 'Breadcrumbs', 'goldsmith' ),
                'subtitle' => esc_html__( 'If enabled, adds breadcrumbs navigation to bottom of page title.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'breadcrumbs_visibility',
                'type' => 'switch',
                'default' => true
            ),
            array(
                'id' =>'shop_breadcrumbs_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Shop Pages Breadcrumbs Template', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'theme' => esc_html__( 'Theme Breadcrumbs', 'goldsmith' ),
                    'woo' => esc_html__( 'WooCommerce Breadcrumbs', 'goldsmith' ),
                ),
                'default' => 'theme',
                'required' => array( 'breadcrumbs_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Typography', 'goldsmith' ),
                'id' => 'breadcrumbs_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '.goldsmith-breadcrumb li a,.woocommerce-breadcrumb a' ),
                'required' => array(
                    array( 'shop_hero_visibility', '=', '1' ),
                    array( 'shop_hero_type', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Breadcrumbs Link Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'breadcrumbs_link_clr',
                'type' => 'color',
                'default' => '',
                'output' => array( '.goldsmith-breadcrumb li a,.woocommerce-breadcrumb a' ),
                'required' => array( 'breadcrumbs_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Breadcrumbs Link Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'breadcrumbs_link_hvrclr',
                'type' => 'color',
                'default' => '',
                'output' => array( '.goldsmith-breadcrumb li a:hover,.woocommerce-breadcrumb a:hover' ),
                'required' => array( 'breadcrumbs_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Breadcrumbs Current Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'breadcrumbs_current',
                'type' => 'color',
                'default' => '',
                'output' => array( '.goldsmith-breadcrumb li.breadcrumb_active, .goldsmith-breadcrumb .breadcrumb-item.active, .woocommerce-breadcrumb' ),
                'required' => array( 'breadcrumbs_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Breadcrumbs Separator Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'breadcrumbs_icon',
                'type' => 'color',
                'default' => '',
                'output' => array( '.goldsmith-breadcrumb .breadcrumb_link_seperator, .goldsmith-breadcrumb .breadcrumb-item+.breadcrumb-item::before,.woocommerce-breadcrumb a:after' ),
                'required' => array( 'breadcrumbs_visibility', '=', '1' )
            )
        )
    ));
    //PRELOADER SETTINGS SUBSECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Preloader', 'goldsmith' ),
        'id' => 'themepreloadersubsection',
        'icon' => 'el el-brush',
        'subsection' => true,
        'fields' => array(
            array(
                'title' => esc_html__( 'Preloader', 'goldsmith' ),
                'subtitle' => esc_html__( 'If enabled, adds preloader.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'preloader_visibility',
                'type' => 'switch',
                'default' => true
            ),
            array(
                'title' => esc_html__( 'Preloader Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your preloader type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'pre_type',
                'type' => 'select',
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Default', 'goldsmith' ),
                    '01' => esc_html__( 'Type 1', 'goldsmith' ),
                    '02' => esc_html__( 'Type 2', 'goldsmith' ),
                    '03' => esc_html__( 'Type 3', 'goldsmith' ),
                    '04' => esc_html__( 'Type 4', 'goldsmith' ),
                    '05' => esc_html__( 'Type 5', 'goldsmith' ),
                    '06' => esc_html__( 'Type 6', 'goldsmith' ),
                    '07' => esc_html__( 'Type 7', 'goldsmith' ),
                    '08' => esc_html__( 'Type 8', 'goldsmith' ),
                    '09' => esc_html__( 'Type 9', 'goldsmith' ),
                    '10' => esc_html__( 'Type 10', 'goldsmith' ),
                    '11' => esc_html__( 'Type 11', 'goldsmith' ),
                    '12' => esc_html__( 'Type 12', 'goldsmith' )
                ),
                'default' => '12',
                'required' => array( 'preloader_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Preloader Image', 'goldsmith' ),
                'subtitle' => esc_html__( 'Upload your Logo. If left blank theme will use site default preloader.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'pre_img',
                'type' => 'media',
                'url' => true,
                'customizer' => true,
                'required' => array(
                    array( 'preloader_visibility', '=', '1' ),
                    array( 'pre_type', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Background Color', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add preloader background color.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'pre_bg',
                'type' => 'color',
                'default' => '',
                'required' => array( 'preloader_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Spin Color', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add preloader spin color.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'pre_spin',
                'type' => 'color',
                'default' => '',
                'required' => array( 'preloader_visibility', '=', '1' )
            )
    	)
    ));
    //NEWSLETTER SETTINGS SUBSECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Popup Newsletter', 'goldsmith' ),
        'id' => 'themenewslettersubsection',
        'icon' => 'el el-brush',
        'subsection' => true,
        'fields' => array(
            array(
                'title' => esc_html__( 'Newsletter Popup', 'goldsmith' ),
                'subtitle' => esc_html__( 'If enabled, adds preloader.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_newsletter_visibility',
                'type' => 'switch',
                'default' => false
            ),
            array(
                'title' => esc_html__( 'Template Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your preloader type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_newsletter_type',
                'type' => 'select',
                'customizer' => true,
                'options' => array(
                    'elementor' => esc_html__( 'Elementor', 'goldsmith' ),
                    'shortcode' => esc_html__( 'Shortcode', 'goldsmith' )
                ),
                'default' => 'elementor',
                'required' => array( 'popup_newsletter_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_newsletter_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'popup_newsletter_visibility', '=', '1' ),
                    array( 'popup_newsletter_type', '=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Shortcode', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your shortcode here', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_newsletter_shortcode',
                'type' => 'text',
                'validate' => 'number',
                'customizer' => true,
                'required' => array(
                    array( 'popup_newsletter_visibility', '=', '1' ),
                    array( 'popup_newsletter_type', '=', 'shortcode' )
                )
            ),
            array(
                'title' => esc_html__( 'Expire Date', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your expire date here', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_newsletter_expire_date',
                'type' => 'text',
                'validate' => 'number',
                'default' => 15,
                'customizer' => true,
                'required' => array( 'popup_newsletter_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Delay (ms)', 'goldsmith' ),
                'subtitle' => esc_html__( 'Show after page load 1000 = 1s', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_newsletter_delay',
                'type' => 'text',
                'validate' => 'number',
                'default' => '',
                'customizer' => true,
                'required' => array( 'popup_newsletter_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Show only once', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_newsletter_show_once',
                'type' => 'switch',
                'default' => false,
                'required' => array( 'popup_newsletter_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Show only these pages', 'goldsmith' ),
                'subtitle' => esc_html__( 'If enabled, adds preloader.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_newsletter_show_custom_page',
                'type' => 'switch',
                'default' => false,
                'required' => array( 'popup_newsletter_visibility', '=', '1' )
            ),
            array(
                'id' =>'popup_newsletter_show_on_pages',
                'type' => 'select',
                'title' => esc_html__( 'Select Pages', 'goldsmith' ),
                'multi' => true,
                'customizer' => true,
                'data' => 'pages',
                'required' => array(
                    array( 'popup_newsletter_visibility', '=', '1' ),
                    array( 'popup_newsletter_show_custom_page', '=', '1' )
                )
            )
    	)
    ));
    //NEWSLETTER SETTINGS SUBSECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Popup GDPR', 'goldsmith' ),
        'id' => 'themegdprsubsection',
        'icon' => 'el el-brush',
        'subsection' => true,
        'fields' => array(
            array(
                'title' => esc_html__( 'Popup GDPR', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can choose status of GDPR', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_gdpr_visibility',
                'type' => 'switch',
                'default' => 0
            ),
            array(
                'title' => esc_html__( 'Template Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your preloader type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_gdpr_type',
                'type' => 'select',
                'customizer' => true,
                'options' => array(
                    'elementor' => esc_html__( 'Elementor', 'goldsmith' ),
                    'shortcode' => esc_html__( 'Shortcode', 'goldsmith' ),
                    'deafult' => esc_html__( 'Default', 'goldsmith' ),
                ),
                'default' => 'deafult',
                'required' => array( 'popup_gdpr_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_gdpr_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'popup_gdpr_visibility', '=', '1' ),
                    array( 'popup_gdpr_type', '=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Shortcode', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your shortcode here', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_gdpr_shortcode',
                'type' => 'text',
                'customizer' => true,
                'required' => array(
                    array( 'popup_gdpr_visibility', '=', '1' ),
                    array( 'popup_gdpr_type', '=', 'shortcode' )
                )
            ),
            array(
                'title' => esc_html__( 'Expire Date', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your expire date here', 'goldsmith' ),
                'customizer' => true,
                'id' => 'popup_gdpr_expire_date',
                'type' => 'text',
                'validate' => 'number',
                'default' => 15,
                'customizer' => true,
                'required' => array( 'popup_gdpr_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Icon Image', 'goldsmith' ),
                'subtitle' => esc_html__( 'Upload your image.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'gdpr_image',
                'type' => 'media',
                'url' => true,
                'customizer' => true,
                'required' => array(
                    array( 'popup_gdpr_visibility', '=', '1' ),
                    array( 'popup_gdpr_type', '=', 'deafult' )
                )
            ),
            array(
                'title' => esc_html__( 'GDPR Text', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your gdpr text here', 'goldsmith' ),
                'customizer' => true,
                'id' => 'gdpr_text',
                'type' => 'textarea',
                'default' => 'In order to provide you a personalized shopping experience, our site uses cookies. <br><a href="#">cookie policy</a>.',
                'customizer' => true,
                'required' => array(
                    array( 'popup_gdpr_visibility', '=', '1' ),
                    array( 'popup_gdpr_type', '=', 'deafult' )
                )
            ),
            array(
                'title' => esc_html__( 'GDPR Button Text', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your gdpr button text here', 'goldsmith' ),
                'customizer' => true,
                'id' => 'gdpr_button_text',
                'type' => 'text',
                'default' => 'Accept Cookies',
                'customizer' => true,
                'required' => array( 'popup_gdpr_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'gdpr_bg',
                'type' => 'color',
                'mode' => 'background-color',
                'validate' => 'color',
                'output' => array('.site-gdpr'),
                'required' => array( 'popup_gdpr_visibility', '=', '1' )
            )
    	)
    ));

    $is_right = is_rtl() ? 'right' : 'left';
    $is_left = is_rtl() ? 'left' : 'right';
    //BACKTOTOP BUTTON SUBSECTION
    Redux::setSection($goldsmith_pre, array(
	    'title' => esc_html__( 'Back-to-top Button', 'goldsmith' ),
	    'id' => 'backtotop',
	    'icon' => 'el el-brush',
	    'subsection' => true,
	    'fields' => array(
	        array(
	            'title' => esc_html__( 'Back-to-top', 'goldsmith' ),
	            'subtitle' => esc_html__( 'Switch On-off', 'goldsmith' ),
	            'desc' => esc_html__( 'If enabled, adds back to top.', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'backtotop_visibility',
	            'type' => 'switch',
	            'default' => true
	        ),
	        array(
	            'title' => esc_html__( 'Bottom Offset', 'goldsmith' ),
	            'subtitle' => esc_html__( 'Set custom bottom offset for the back-to-top button', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'backtotop_top_offset',
	            'type' => 'spacing',
	            'output' => array('.scroll-to-top'),
	            'mode' => 'absolute',
	            'units' => array('px'),
	            'all' => false,
	            'top' => false,
	            $is_left => true,
	            'bottom' => true,
	            $is_right => false,
	            'default' => array(
	                $is_left => '30',
	                'bottom' => '30',
	                'units' => 'px'
	            ),
	            'required' => array( 'backtotop_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Background Color', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'backtotop_bg',
	            'type' => 'color',
	            'mode' => 'background-color',
	            'validate' => 'color',
	            'output' => array('.scroll-to-top'),
	            'required' => array( 'backtotop_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Hover Background Color', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'backtotop_hvrbg',
	            'type' => 'color',
	            'mode' => 'background-color',
	            'validate' => 'color',
	            'output' => array('.scroll-to-top:hover'),
	            'required' => array( 'backtotop_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Arrow Color', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'backtotop_icon',
	            'type' => 'color',
	            'default' =>  '',
	            'validate' => 'color',
	            'output' => array('.scroll-to-top'),
	            'required' => array( 'backtotop_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Hover Arrow Color', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'backtotop_hvricon',
	            'type' => 'color',
	            'default' =>  '',
	            'validate' => 'color',
	            'output' => array('.scroll-to-top:hover'),
	            'required' => array( 'backtotop_visibility', '=', '1' )
	        )
    	)
    ));

    // THEME PAGINATION SUBSECTION
    Redux::setSection($goldsmith_pre, array(
	    'title' => esc_html__( 'Pagination', 'goldsmith' ),
	    'desc' => esc_html__( 'These are main settings for general theme!', 'goldsmith' ),
	    'id' => 'pagination',
	    'subsection' => true,
	    'icon' => 'el el-link',
	    'fields' => array(
	        array(
	            'title' => esc_html__( 'Pagination', 'goldsmith' ),
	            'subtitle' => esc_html__( 'Switch On-off', 'goldsmith' ),
	            'desc' => esc_html__( 'If enabled, adds pagination.', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'pagination_visibility',
	            'type' => 'switch',
	            'default' => true
	        ),
	        array(
	            'title' => esc_html__( 'Alignment', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'pagination_alignment',
	            'type' => 'button_set',
	            'customizer' => true,
	            'options' => array(
	                'flex-start' => esc_html__( 'Left', 'goldsmith' ),
	                'center' => esc_html__( 'Center', 'goldsmith' ),
	                'flex-end' => esc_html__( 'Right', 'goldsmith' )
	            ),
	            'default' => 'center',
	            'required' => array( 'pagination_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Size', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'pagination_size',
	            'type' => 'dimensions',
	            'output' => array('.nt-pagination .nt-pagination-item .nt-pagination-link,.goldsmith-woocommerce-pagination ul li a, .goldsmith-woocommerce-pagination ul li span' ),
	            'required' => array( 'pagination_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Border', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'pagination_border',
	            'type' => 'border',
	            'output' => array('.nt-pagination .nt-pagination-item .nt-pagination-link,.goldsmith-woocommerce-pagination ul li a, .goldsmith-woocommerce-pagination ul li span' ),
	            'required' => array( 'pagination_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Border ( Hover/Active )', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'pagination_hvrborder',
	            'type' => 'border',
	            'output' => array('.nt-pagination .nt-pagination-item.active .nt-pagination-link,.nt-pagination .nt-pagination-item .nt-pagination-link:hover,.goldsmith-woocommerce-pagination ul li a:focus, .goldsmith-woocommerce-pagination ul li a:hover, .goldsmith-woocommerce-pagination ul li span.current' ),
	            'required' => array( 'pagination_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Border Radius ( px )', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'pagination_border_radius',
	            'type' => 'slider',
	            'max' => 300,
	            'required' => array( 'pagination_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Background Color', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'pagination_bgclr',
	            'type' => 'color_rgba',
	            'mode' => 'background-color',
	            'validate' => 'color',
	            'output' => array('.nt-pagination .nt-pagination-item .nt-pagination-link,.goldsmith-woocommerce-pagination ul li a, .goldsmith-woocommerce-pagination ul li span' ),
	            'required' => array( 'pagination_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Background Color ( Hover/Active )', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'pagination_hvrbgclr',
	            'type' => 'color_rgba',
	            'mode' => 'background-color',
	            'output' => array('.nt-pagination .nt-pagination-item.active .nt-pagination-link,.nt-pagination .nt-pagination-item .nt-pagination-link:hover,.goldsmith-woocommerce-pagination ul li a:focus, .goldsmith-woocommerce-pagination ul li a:hover, .goldsmith-woocommerce-pagination ul li span.current' ),
	            'required' => array( 'pagination_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Number Color', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'pagination_clr',
	            'type' => 'color',
	            'validate' => 'color',
	            'output' => array('.nt-pagination .nt-pagination-item .nt-pagination-link,.goldsmith-woocommerce-pagination ul li a, .goldsmith-woocommerce-pagination ul li span' ),
	            'required' => array( 'pagination_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Number Color ( Hover/Active )', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'pagination_hvrclr',
	            'type' => 'color',
	            'validate' => 'color',
	            'output' => array('.nt-pagination .nt-pagination-item.active .nt-pagination-link,.nt-pagination .nt-pagination-item .nt-pagination-link:hover,.goldsmith-woocommerce-pagination ul li a:focus, .goldsmith-woocommerce-pagination ul li a:hover, .goldsmith-woocommerce-pagination ul li span.current' ),
	            'required' => array( 'pagination_visibility', '=', '1' )
	        )
    	)
    ));

    // THEME LIGHTBOX POPUP SUBSECTION
    Redux::setSection($goldsmith_pre, array(
	    'title' => esc_html__( 'Lightbox', 'goldsmith' ),
	    'desc' => esc_html__( 'These are main settings for general theme!', 'goldsmith' ),
	    'id' => 'themelightbox',
	    'subsection' => true,
	    'icon' => 'el el-brush',
	    'fields' => array(
	        array(
	            'title' => esc_html__( 'Overlay Color', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'lightbox_overlay_bgclr',
	            'type' => 'color_rgba',
	            'mode' => 'background-color',
	            'validate' => 'color',
	            'output' => array('.mfp-bg' ),
	            'required' => array( 'pagination_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Content Background Color', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'lightbox_content_bgclr',
	            'type' => 'color_rgba',
	            'mode' => 'background-color',
	            'validate' => 'color',
	            'output' => array('.goldsmith-product360-wrapper, .goldsmith-single-product-delivery, .goldsmith-single-product-question, .goldsmith-quickview-wrapper' )
	        ),
	        array(
	            'title' => esc_html__( 'Content Max-width', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'lightbox_maxwidth',
	            'type' => 'dimensions',
	            'output' => array('.nt-pagination .nt-pagination-item .nt-pagination-link,.goldsmith-woocommerce-pagination ul li a, .goldsmith-woocommerce-pagination ul li span' ),
	            'required' => array( 'pagination_visibility', '=', '1' )
	        ),
	        array(
	            'title' => esc_html__( 'Close Background Color', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'lightbox_close_bgclr',
	            'type' => 'color_rgba',
	            'mode' => 'background-color',
	            'validate' => 'color',
	            'output' => array('.goldsmith-mfp-close' )
	        ),
	        array(
	            'title' => esc_html__( 'Close Color', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'lightbox_close_clr',
	            'type' => 'color_rgba',
	            'mode' => 'background-color',
	            'validate' => 'color',
	            'output' => array('.mfp-close-btn-in .mfp-close' )
	        )
    	)
    ));

    // THEME OPTIMIZATION
    Redux::setSection($goldsmith_pre, array(
	    'title' => esc_html__( 'Optimization', 'goldsmith' ),
	    'desc' => esc_html__( 'These are main settings for general theme!', 'goldsmith' ),
	    'id' => 'themeoptimization',
	    'subsection' => true,
	    'icon' => 'el el-brush',
	    'fields' => array(
	        array(
	            'title' => esc_html__( 'LazyLoad', 'goldsmith' ),
	            'subtitle' => esc_html__( 'You can use this option to disable or enable lazy loading of image files.', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'theme_lazyload_images',
	            'type' => 'switch',
	            'default' => true
	        ),
	        array(
	            'title' => esc_html__( 'Theme JS/CSS Minify', 'goldsmith' ),
	            'subtitle' => esc_html__( 'This option uses compressed versions of many js files in one file to reduce server requests.', 'goldsmith' ),
	            'on' => esc_html__( 'Yes', 'goldsmith' ),
	            'off' => esc_html__( 'No', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'theme_all_plugins',
	            'type' => 'switch',
	            'default' => true
	        ),
	        array(
	            'title' => esc_html__( 'Disable Gutenberg Editor', 'goldsmith' ),
	            'subtitle' => esc_html__( 'This theme does not support gutenberg so some css files are filtered, if you want to use gutenberg you can use this option', 'goldsmith' ),
	            'on' => esc_html__( 'Yes', 'goldsmith' ),
	            'off' => esc_html__( 'No', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'theme_blocks_styles',
	            'type' => 'switch',
	            'default' => false
	        ),
	        array(
	            'title' => esc_html__( 'Disable Emojis', 'goldsmith' ),
	            'subtitle' => esc_html__( 'You can use this option to disable or enable emojis.', 'goldsmith' ),
	            'on' => esc_html__( 'Yes', 'goldsmith' ),
	            'off' => esc_html__( 'No', 'goldsmith' ),
	            'customizer' => true,
	            'id' => 'disable_emojis',
	            'type' => 'switch',
	            'default' => true
	        )
    	)
    ));

    /*************************************************
    ## LOGO SECTION
    *************************************************/
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Logo', 'goldsmith' ),
        'desc' => esc_html__( 'These are main settings for general theme!', 'goldsmith' ),
        'id' => 'logosection',
        'icon' => 'el el-star-empty',
        'fields' => array(
            array(
                'title' => esc_html__( 'Logo Switch', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can select logo on or off.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'logo_visibility',
                'type' => 'switch',
                'default' => true
            ),
            array(
                'title' => esc_html__( 'Logo Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your logo type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'logo_type',
                'type' => 'select',
                'customizer' => true,
                'options' => array(
                    'img' => esc_html__( 'Image Logo', 'goldsmith' ),
                    'sitename' => esc_html__( 'Site Name', 'goldsmith' ),
                    'customtext' => esc_html__( 'Custom HTML', 'goldsmith' )
                ),
                'default' => 'sitename',
                'required' => array( 'logo_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Custom text for logo', 'goldsmith' ),
                'desc' => esc_html__( 'Text entered here will be used as logo', 'goldsmith' ),
                'customizer' => true,
                'id' => 'text_logo',
                'type' => 'editor',
                'args' => array(
                    'teeny' => false,
                    'textarea_rows' => 10
                ),
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'customtext' )
                ),
            ),
            array(
                'title' => esc_html__( 'Text Logo Typography', 'goldsmith' ),
                'id' => 'text_logo_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '.nt-logo .header-text-logo' ),
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '!=', 'img' )
                )
            ),
            array(
                'title' => esc_html__( 'Hover Logo Color', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own hover color for the text logo.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'text_logo_hvr',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.nt-logo .header-text-logo:hover' ),
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '!=', 'img' )
                )
            ),
            array(
                'title' => esc_html__( 'Logo Image', 'goldsmith' ),
                'subtitle' => esc_html__( 'Upload your Logo. If left blank theme will use site default logo.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'img_logo',
                'type' => 'media',
                'url' => true,
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' )
                )
            ),
            array(
                'title' => esc_html__( 'Logo Size', 'goldsmith' ),
                'subtitle' => esc_html__( 'Set the logo max-width of the image.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'logo_size',
                'type' => 'slider',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 400,
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' ),
                    array( 'logo_type', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Sticky Logo', 'goldsmith' ),
                'subtitle' => esc_html__( 'Upload your Logo. If left blank theme will use site default logo.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sticky_logo',
                'type' => 'media',
                'url' => true,
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' )
                )
            ),
            array(
                'title' => esc_html__( 'Sticky Logo Size', 'goldsmith' ),
                'subtitle' => esc_html__( 'Set the logo max-width of the image.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sticky_logo_size',
                'type' => 'slider',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 400,
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' )
                )
            ),
            array(
                'title' => esc_html__( 'Mobile Menu Logo', 'goldsmith' ),
                'subtitle' => esc_html__( 'Upload your Logo. If left blank theme will use site default logo.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_logo',
                'type' => 'media',
                'url' => true,
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' )
                )
            ),
            array(
                'title' => esc_html__( 'Mobile Logo Size', 'goldsmith' ),
                'subtitle' => esc_html__( 'Set the logo max-width of the image.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_logo_size',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 400,
                'type' => 'slider',
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' ),
                    array( 'logo_type', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Sidebar Logo Size', 'goldsmith' ),
                'subtitle' => esc_html__( 'Set the logo max-width of the image.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_logo_size',
                'default' => 80,
                'min' => 0,
                'step' => 1,
                'max' => 400,
                'type' => 'slider',
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' ),
                    array( 'logo_type', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Sidebar Logo Left Offset', 'goldsmith' ),
                'subtitle' => esc_html__( 'Set the logo max-width of the image.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_logo_left_offset',
                'default' => '',
                'min' => -200,
                'step' => 1,
                'max' => 400,
                'type' => 'slider',
                'required' => array(
                    array( 'logo_visibility', '=', '1' ),
                    array( 'logo_type', '=', 'img' ),
                    array( 'logo_type', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Logo Padding', 'goldsmith' ),
                'customizer' => true,
                'id' => 'text_logo_pad',
                'type' => 'spacing',
                'mode' => 'padding',
                'all' => false,
                'units' => array( 'em', 'px', '%' ),
                'units_extended' => 'true',
                'output' => array( '.nt-logo' ),
                'required' => array( 'logo_visibility', '=', '1' )
            )
    	)
    ));

    /*************************************************
    ## HEADER & NAV SECTION
    *************************************************/
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Header', 'goldsmith' ),
        'id' => 'headersection',
        'icon' => 'fa fa-bars',
        'fields' => array()
    ));
    //HEADER MENU
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'General', 'goldsmith' ),
        'id' => 'headernavgeneralsection',
        'subsection' => true,
        'icon' => 'fa fa-cog',
        'fields' => array(
            array(
                'title' => esc_html__( 'Header Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site navigation.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'id' =>'header_template',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Template', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header template.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Top Header', 'goldsmith' ),
                    'sidebar' => esc_html__( 'Sidebar Header', 'goldsmith' ),
                    'elementor' => esc_html__( 'Elementor Templates', 'goldsmith' )
                ),
                'default' => 'default',
                'required' => array( 'header_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Mobile Header Breakpoint', 'goldsmith' ),
                'subtitle' => esc_html__( 'Here you can set the screen width where the mobile header will be active.Please use number.ex: 1280', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_header_breakpoint',
                'type' => 'text',
                'validate' => array('numeric'),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'sidebar_header_color',
                'type' => 'button_set',
                'title' => esc_html__( 'Sidebar Header Color Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'light' => esc_html__( 'Light', 'goldsmith' ),
                    'dark' => esc_html__( 'Dark', 'goldsmith' ),
                ),
                'default' => 'light',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'sidebar' )
                )
            ),
            array(
                'id' =>'sidebar_header_position',
                'type' => 'button_set',
                'title' => esc_html__( 'Sidebar Header Position', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'left' => esc_html__( 'Left', 'goldsmith' ),
                    'right' => esc_html__( 'Right', 'goldsmith' ),
                ),
                'default' => 'left',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'sidebar' )
                )
            ),
            array(
                'title' => esc_html__( 'Sidebar Header Extra HTML', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your site extra here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_header_extra_html',
                'type' => 'textarea',
                'default' => '',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'sidebar' )
                )
            ),
            array(
                'title' => esc_html__( 'Sticky Header Display', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_sticky_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'elementor' )
                )
            ),
            array(
                'id' =>'edit_header_elementor_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'elementor' ),
                    array( 'header_elementor_templates', '!=', '' )
                )
            ),
            array(
                'id' => 'header_top_start',
                'type' => 'section',
                'title' => esc_html__('Header Main Options', 'goldsmith'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'header_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Header Layout Manager', 'goldsmith' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme header', 'goldsmith' ),
                'options' => array(
                    'left' => array(
                        'sidemenu' => esc_html__( 'Sidemenu Toggle', 'goldsmith' ),
                        'logo' => esc_html__( 'Logo', 'goldsmith' )
                    ),
                    'center'=> array(
                        'menu' => esc_html__( 'Main Menu', 'goldsmith' )
                    ),
                    'right'=> array(
                        'search' => esc_html__( 'Search', 'goldsmith' ),
                        'buttons' => esc_html__( 'Buttons', 'goldsmith' )
                    ),
                    'hide'  => array(
                        'center-logo' => esc_html__( 'Menu Logo Menu', 'goldsmith' ),
                        'mini-menu' => esc_html__( 'Mini Menu', 'goldsmith' ),
                        'double-menu' => esc_html__( 'Double Menu', 'goldsmith' ),
                        'custom-html' => esc_html__( 'Phone Number', 'goldsmith' )
                    )
                ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Phone Number Custom HTML', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your custom html here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_custom_html',
                'type' => 'textarea',
                'default' => '<a href="tel:280 900 3434"><i aria-hidden="true" class="goldsmith-icons flaticon-24-hours-support"></i><span>280 900 3434<span class="phone-text">Call Anytime</span></span></a>',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Height', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can use this option to control the header height.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_height',
                'type' => 'slider',
                'default' => 80,
                'min' => 0,
                'step' => 1,
                'max' => 500,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'header_width',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Container Width', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Deafult', 'goldsmith' ),
                    'stretch' => esc_html__( 'Stretch', 'goldsmith' ),
                    'custom' => esc_html__( 'Custom Width', 'goldsmith' ),
                ),
                'default' => 'default',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Custom Container Width', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_top_custom_width',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array( 'header_width', '=', 'custom' )
            ),
            array(
                'title' => esc_html__( 'Sticky Header Custom Container Width', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sticky_header_top_custom_width',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array( 'header_width', '=', 'custom' )
            ),
            array(
                'title' => esc_html__( 'Header Left Items Spacing', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can use this option to adjust the spacing between header left items.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_left_item_spacing',
                'type' => 'slider',
                'default' => 20,
                'min' => 0,
                'step' => 1,
                'max' => 50,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Right Items Spacing', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can use this option to adjust the spacing between header right items.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_right_item_spacing',
                'type' => 'slider',
                'default' => 15,
                'min' => 0,
                'step' => 1,
                'max' => 50,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'header_buttons_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Header Buttons Manager', 'goldsmith' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme header for buttons', 'goldsmith' ),
                'options' => array(
                    'show'  => array(
                        'cart' => esc_html__( 'Cart', 'goldsmith' ),
                    ),
                    'hide'  => array(
                        'wishlist' => esc_html__( 'Wishlist', 'goldsmith' ),
                        'compare' => esc_html__( 'Compare', 'goldsmith' ),
                        'account' => esc_html__( 'Account', 'goldsmith' ),
                        'search' => esc_html__( 'Search', 'goldsmith' )
                    )
                ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Buttons Spacing', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can use this option to adjust the spacing between header buttons.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_buttons_spacing',
                'type' => 'slider',
                'default' => 15,
                'min' => 0,
                'step' => 1,
                'max' => 50,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' => 'header_top_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            // DEFAULT HEADER OPTIONS
            array(
                'id' => 'header_menu_items_customize_start',
                'type' => 'section',
                'title' => esc_html__('Header Customize Options', 'goldsmith'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'id' =>'header_bg_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Background Type ( General )', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Deafult', 'goldsmith' ),
                    'dark' => esc_html__( 'Dark', 'goldsmith' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'goldsmith' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'goldsmith' )
                ),
                'default' => 'default',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Force Transparent', 'goldsmith' ),
                'customizer' => true,
                'id' => 'goldsmith_header_force_transparent',
                'type' => 'switch',
                'default' => 0
            ),
            array(
                'id' =>'archive_cat_header_bg_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Background Type ( Archive Category Page )', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'general' => esc_html__( 'General', 'goldsmith' ),
                    'default' => esc_html__( 'Deafult', 'goldsmith' ),
                    'dark' => esc_html__( 'Dark', 'goldsmith' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'goldsmith' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'goldsmith' )
                ),
                'default' => 'general',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'archive_tag_header_bg_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Background Type ( Archive Tag Page )', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'general' => esc_html__( 'General', 'goldsmith' ),
                    'default' => esc_html__( 'Deafult', 'goldsmith' ),
                    'dark' => esc_html__( 'Dark', 'goldsmith' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'goldsmith' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'goldsmith' )
                ),
                'default' => 'general',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'single_post_header_bg_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Background Type ( Single Post Page )', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'general' => esc_html__( 'General', 'goldsmith' ),
                    'default' => esc_html__( 'Deafult', 'goldsmith' ),
                    'dark' => esc_html__( 'Dark', 'goldsmith' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'goldsmith' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'goldsmith' )
                ),
                'default' => 'general',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_bg',
                'type' => 'color_rgba',
                'mode' => 'background-color',
                'output' => array( 'header.goldsmith-header-default, .has-header-sidebar .goldsmith-main-sidebar-header, .has-header-sidebar .goldsmith-main-sidebar-header.goldsmith-active' ),
            ),
            array(
                'title' => esc_html__( 'Sidebar Header Border Right Color', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'nav_submenu_bg',
                'type' => 'color',
                'mode' => 'border-right-color',
                'validate' => 'color',
                'output' => array( '.has-header-sidebar .goldsmith-main-sidebar-header, .has-header-sidebar .goldsmith-main-sidebar-header.goldsmith-active' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'sidebar' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Typography', 'goldsmith' ),
                'id' => 'nav_a_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '.goldsmith-header-top-menu-area>ul>li.menu-item>a,.has-header-sidebar .header-text-logo, .has-header-sidebar .goldsmith-main-sidebar-header .primary-menu > li > a, .has-header-sidebar .goldsmith-main-sidebar-header .submenu > li > a, .has-header-sidebar .sliding-menu .sliding-menu-inner li a, .has-header-sidebar .sliding-menu li .sliding-menu__nav, .has-header-sidebar .goldsmith-main-sidebar-header .goldsmith-svg-icon' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own hover color for the navigation menu item.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'nav_a',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.goldsmith-header-top-menu-area>ul>li.menu-item>a,.has-header-sidebar .header-text-logo, .has-header-sidebar .goldsmith-main-sidebar-header .primary-menu > li > a, .has-header-sidebar .goldsmith-main-sidebar-header .submenu > li > a, .has-header-sidebar .sliding-menu .sliding-menu-inner li a, .has-header-sidebar .sliding-menu li .sliding-menu__nav, .has-header-sidebar .goldsmith-main-sidebar-header .goldsmith-svg-icon' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color ( Hover and Active )', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own hover color for the navigation menu item.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'nav_hvr_a',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.current-menu-parent>a, .current-menu-item>a, .goldsmith-header-top-menu-area>ul>li.menu-item>a:hover, .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.goldsmith-header-top-menu-area>ul>li.menu-item.active>a, .goldsmith-header-top-menu-area ul li .submenu>li.menu-item.active>a,.has-header-sidebar .header-text-logo:hover, .has-header-sidebar .goldsmith-main-sidebar-header .primary-menu > li > a:hover, .has-header-sidebar .goldsmith-main-sidebar-header .primary-menu > li.goldsmith-active > a, .has-header-sidebar .goldsmith-main-sidebar-header .submenu > li > a:hover, .has-header-sidebar .goldsmith-main-sidebar-header .submenu > li.goldsmith-active > a, .has-header-sidebar .sliding-menu .sliding-menu-inner li a:hover, .has-header-sidebar .sliding-menu li .sliding-menu__nav:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Sticky Header Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'nav_top_sticky_bg',
                'type' => 'color_rgba',
                'mode' => 'background-color',
                'output' => array( '.has-sticky-header.scroll-start header.goldsmith-header-default' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Typography', 'goldsmith' ),
                'id' => 'sticky_nav_atypo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '.has-sticky-header.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item>a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Sticky Menu Item Color', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own hover color for the sticky navigation menu item.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sticky_nav_a',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.has-sticky-header.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item>a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Sticky Menu Item Color ( Hover and Active )', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own hover color for the sticky navigation menu item.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sticky_nav_hvr_a',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.has-sticky-header.scroll-start .current-menu-parent>a, .has-sticky-header.scroll-start .current-menu-item>a, .has-sticky-header.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item>a:hover, .has-sticky-header.scroll-start .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.has-sticky-header.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item.active>a, .has-sticky-header.scroll-start .goldsmith-header-top-menu-area ul li .submenu>li.menu-item.active>a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' => 'header_menu_items_style_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'id' => 'header_submenumenu_items_style_end',
                'type' => 'section',
                'title' => esc_html__('Header Sub Menu Customize Options', 'goldsmith'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Sub Menu Background Color', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'nav_submenu_bg',
                'type' => 'color',
                'mode' => 'background-color',
                'validate' => 'color',
                'output' => array( '.goldsmith-header-top-menu-area ul li .submenu' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Typography', 'goldsmith' ),
                'id' => 'nav_submenu_atypo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '.goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a,.has-header-sidebar .goldsmith-main-sidebar-header .submenu > li > a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'nav_submenu_a',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a,.has-header-sidebar .goldsmith-main-sidebar-header .submenu > li > a' ),
            ),
            array(
                'title' => esc_html__( 'Menu Item Color ( Hover and Active )', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'nav_submenu_hvr_a',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.goldsmith-header-top-menu-area ul li .submenu>li.menu-item.active>a,.has-header-sidebar .goldsmith-main-sidebar-header .submenu > li > a,.has-header-sidebar .goldsmith-main-sidebar-header .submenu > li.active > a' ),
            ),
            array(
                'id' => 'header_submenu_items_style_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'id' => 'header_svgbuttons_items_style_start',
                'type' => 'section',
                'title' => esc_html__('Header SVG Buttons Customize Options', 'goldsmith'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'SVG Icon Color', 'goldsmith' ),
                'desc' => esc_html__( 'Cart, Wishlist, Compare, Account, Search, Sidemenu bar', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_buttons_svg_color',
                'type' => 'color',
                'mode' => 'fill',
                'validate' => 'color',
                'output' => array( '.goldsmith-header-default .top-action-btn .goldsmith-svg-icon,.has-header-sidebar .goldsmith-main-sidebar-header .goldsmith-svg-icon' ),
            ),
            array(
                'title' => esc_html__( 'Button Counter Background Color', 'goldsmith' ),
                'desc' => esc_html__( 'Cart, Wishlist, Compare', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_buttons_counter_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'validate' => 'color',
                'output' => array( '.goldsmith-header-default .goldsmith-wc-count, .has-header-sidebar .goldsmith-main-sidebar-header .goldsmith-wc-count' ),
            ),
            array(
                'title' => esc_html__( 'Button Counter Color', 'goldsmith' ),
                'desc' => esc_html__( 'Cart, Wishlist, Compare', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_buttons_counter_color',
                'type' => 'color',
                'validate' => 'color',
                'output' => array( '.goldsmith-header-default .goldsmith-wc-count, .has-header-sidebar .goldsmith-main-sidebar-header .goldsmith-wc-count' ),
            ),
            array(
                'id' => 'header_svgbuttons_items_style_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            //information on-off
            array(
                'id' =>'info_nav0',
                'type' => 'info',
                'style' => 'success',
                'title' => esc_html__( 'Success!', 'goldsmith' ),
                'icon' => 'el el-info-circle',
                'customizer' => true,
                'desc' => sprintf(esc_html__( '%s is disabled on the site. Please activate to view options.', 'goldsmith' ), '<b>Header</b>' ),
                'required' => array( 'header_visibility', '=', '0' )
            )
    	)
    ));
    //HEADER MOBILE TOP
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Mobile Top Header', 'goldsmith' ),
        'id' => 'headermobilesection',
        'subsection' => true,
        'icon' => 'fa fa-cog',
        'fields' => array(
            array(
                'id' => 'mobile_header_start',
                'type' => 'section',
                'title' => esc_html__('Mobile Top Header Options', 'goldsmith'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            ),
            array(
                'id' =>'mobile_header_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Mobile Top Header Layouts Manager', 'goldsmith' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme mobile header', 'goldsmith' ),
                'options' => array(
                    'show'  => array(
                        'toggle' => esc_html__( 'Toggle Button', 'goldsmith' ),
                        'logo' => esc_html__( 'Logo', 'goldsmith' ),
                        'buttons' => esc_html__( 'Buttons', 'goldsmith' )
                    ),
                    'hide'  => array(
                    )
                )
            ),
            array(
                'id' =>'mobile_header_buttons_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Mobile Header Buttons Layouts Manager', 'goldsmith' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme mobile header', 'goldsmith' ),
                'options' => array(
                    'show'  => array(
                        'cart' => esc_html__( 'Cart', 'goldsmith' ),
                    ),
                    'hide'  => array(
                        'account' => esc_html__( 'Account', 'goldsmith' ),
                        'search' => esc_html__( 'Search Form', 'goldsmith' )
                    )
                )
            ),
            array(
                'id' =>'mobile_header_bg_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Mobile Header Background Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Deafult', 'goldsmith' ),
                    'dark' => esc_html__( 'Dark', 'goldsmith' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'goldsmith' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'goldsmith' )
                ),
                'default' => 'default'
            ),
            array(
                'id' =>'archive_cat_mobile_header_bg_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Background Type ( Archive Category Page )', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Deafult', 'goldsmith' ),
                    'dark' => esc_html__( 'Dark', 'goldsmith' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'goldsmith' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'goldsmith' )
                ),
                'default' => 'default'
            ),
            array(
                'id' =>'archive_tag_mobile_header_bg_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Background Type ( Archive Tag Page )', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Deafult', 'goldsmith' ),
                    'dark' => esc_html__( 'Dark', 'goldsmith' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'goldsmith' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'goldsmith' )
                ),
                'default' => 'default'
            ),
            array(
                'id' =>'single_post_mobile_header_bg_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Header Background Type ( Single Post Page )', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Deafult', 'goldsmith' ),
                    'dark' => esc_html__( 'Dark', 'goldsmith' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'goldsmith' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'goldsmith' )
                ),
                'default' => 'default',
            ),
            array(
                'title' => esc_html__( 'Header Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_header_bgcolor',
                'type' => 'color_rgba',
                'mode' => 'background-color',
                'validate' => 'color',
                'output' => array( '.goldsmith-header-mobile-top' )
            ),
            array(
                'title' => esc_html__( 'Sticky Header Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_sticky_header_bgcolor',
                'type' => 'color_rgba',
                'mode' => 'background-color',
                'validate' => 'color',
                'output' => array( '.scroll-start .goldsmith-header-mobile-top' ),
            ),
            array(
                'title' => esc_html__( 'Box Shadow Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site header bottom area.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_header_box_shadow_visibility',
                'type' => 'switch',
                'default' => 0
            ),
            array(
                'title' => esc_html__( 'Box Shadow', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_header_box_shadow',
                'type' => 'box_shadow',
                'default' => '',
                'output' => array( '.goldsmith-header-mobile-top' ),
                'required' => array( 'mobile_header_box_shadow_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Sticky Header Box Shadow', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_sticky_header_box_shadow',
                'type' => 'box_shadow',
                'default' => '',
                'output' => array( '.scroll-start .goldsmith-header-mobile-top' ),
                'required' => array( 'mobile_header_box_shadow_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Mobile Menu Trigger Bar Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_header_trigger_menubar_color',
                'type' => 'color',
                'mode' => 'color',
                'validate' => 'color',
                'output' => array( '.goldsmith-header-mobile-top .mobile-toggle' ),
            ),
            array(
                'title' => esc_html__( 'Button Icon Color', 'goldsmith' ),
                'desc' => esc_html__( 'Cart, Account, Search', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_header_buttons_color',
                'type' => 'color',
                'mode' => 'fill',
                'validate' => 'color',
                'output' => array( '.goldsmith-header-mobile-top .goldsmith-svg-icon' ),
            ),
            array(
                'title' => esc_html__( 'Button Counter Background Color', 'goldsmith' ),
                'desc' => esc_html__( 'Cart', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_header_buttons_counter_color',
                'type' => 'color',
                'mode' => 'background-color',
                'validate' => 'color',
                'output' => array( '.goldsmith-header-mobile-top .top-action-btn .goldsmith-wc-count' ),
            ),
            array(
                'title' => esc_html__( 'Button Counter Number Color', 'goldsmith' ),
                'desc' => esc_html__( 'Cart', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_header_buttons_counter_color',
                'type' => 'color',
                'mode' => 'color',
                'validate' => 'color',
                'output' => array( '.goldsmith-header-mobile-top .top-action-btn .goldsmith-wc-count' ),
            ),
            array(
                'id' => 'mobile_header_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '!=', 'elementor' )
                )
            )
    	)
    ));
    //HEADER MOBILE TOP
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Sidebar Panel', 'goldsmith' ),
        'id' => 'headermobilesidebarsection',
        'subsection' => true,
        'icon' => 'fa fa-cog',
        'fields' => array(
            array(
                'id' => 'sidebar_menu_start',
                'type' => 'section',
                'title' => esc_html__('Sidebar Panel Options', 'goldsmith'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'sidebar_menu_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Minibar Layouts Manager', 'goldsmith' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme mobile header sidebar', 'goldsmith' ),
                'options' => array(
                    'show'  => array(
                        'buttons' => esc_html__( 'Buttons', 'goldsmith' ),
                        'logo' => esc_html__( 'Logo', 'goldsmith' ),
                        'socials' => esc_html__( 'Socials', 'goldsmith' )
                    ),
                    'hide'  => array(
                    )
                ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'sidebar_menu_buttons_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Minibar Buttons Layouts Manager', 'goldsmith' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme mobile header', 'goldsmith' ),
                'options' => array(
                    'show'  => array(
                        'cart' => esc_html__( 'Cart', 'goldsmith' ),
                        'wishlist' => esc_html__( 'Wishlist', 'goldsmith' ),
                        'compare' => esc_html__( 'Compare', 'goldsmith' ),
                        'search' => esc_html__( 'Search Category', 'goldsmith' ),
                        'contact' => esc_html__( 'Contact Form', 'goldsmith' ),
                        'account' => esc_html__( 'Account', 'goldsmith' ),
                        'socials' => esc_html__( 'Socials', 'goldsmith' )
                    ),
                    'hide'  => array(
                    )
                ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Sidebar Menu Social Icons', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your social links here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_socials',
                'type' => 'textarea',
                'default' => '<a href="#0" title="facebook"><i class="fab fa-facebook"></i></a>
<a href="#0" title="twitter"><i class="fab fa-twitter"></i></a>
<a href="#0" title="instagram"><i class="fab fa-instagram"></i></a>
<a href="#0" title="youtube"><i class="fab fa-youtube"></i></a>',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Sidebar Menu Container Max Width ( px )', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can control sidebar menu content width.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_content_width',
                'type' => 'slider',
                'default' => 530,
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_bg',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( 'body .goldsmith-header-mobile, .goldsmith-header-mobile .action-content' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' => 'sidebar_menu_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            )
    	)
    ));

    //HEADER MOBILE TOP
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Sidebar Panel Minibar', 'goldsmith' ),
        'id' => 'headermobilesidebarminibarsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Sidebar Menu Minibar Min Width ( px )', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can control sidebar menu bar width.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_width',
                'type' => 'slider',
                'default' => 80,
                'min' => 0,
                'step' => 1,
                'max' => 300,
                'display_value' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_bg',
                'type' => 'color_rgba',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-sidebar' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Close Icon Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_close_icon_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile .goldsmith-panel-close-button' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Close Icon Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_close_icon_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile .goldsmith-panel-close-button' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar SVG Icon Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_svg_color',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array( '.goldsmith-header-mobile-sidebar .goldsmith-svg-icon' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Active SVG Icon Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_active_svg_color',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array( '.goldsmith-header-mobile-sidebar .sidebar-top-action .top-action-btn.active .goldsmith-svg-icon' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Active SVG Icon Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_active_svg_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-sidebar .sidebar-top-action .top-action-btn.active .goldsmith-svg-icon' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Counter Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_counter_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-sidebar .sidebar-top-action .top-action-btn .goldsmith-wc-count' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Counter Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_counter_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-sidebar .sidebar-top-action .top-action-btn .goldsmith-wc-count' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Text Logo Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_textlogo_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-sidebar .sidebar-top-action .top-action-btn.active .goldsmith-svg-icon' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Social Icon Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_social_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-sidebar .goldsmith-header-mobile-sidebar-bottom a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Minibar Social Icon Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_bar_social_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-sidebar .goldsmith-header-mobile-sidebar-bottom a:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            )
    	)
    ));

    //HEADER MOBILE TOP
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Sidebar Panel Search', 'goldsmith' ),
        'id' => 'headermobilesidebarsearchsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Search Placeholder Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_placeholder_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-slide-menu .search-area-top input::-webkit-input-placeholder' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-slide-menu .search-area-top input' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-slide-menu .search-area-top input' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Icon Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_icon_color',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array( '.goldsmith-header-slide-menu .search-area-top svg' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Border Bottom Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_brdcolor',
                'type' => 'color',
                'mode' => 'border-bottom-color',
                'output' => array( '.goldsmith-header-slide-menu .search-area-top' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Result Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_result_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-slide-menu .goldsmith-asform-container .autocomplete-suggestions' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Result Item Price Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_search_result_item_price_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-slide-menu .goldsmith-asform-container .autocomplete-suggestion .woocommerce-variation-price .price, .goldsmith-header-slide-menu .goldsmith-asform-container .autocomplete-suggestion .goldsmith-price' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            )
        )
    ));

    //HEADER MOBILE TOP
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Sidebar Panel Sliding Menu', 'goldsmith' ),
        'id' => 'headermobilesidebarslidingmenusubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Panel Sliding Menu Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_menuitem_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .sliding-menu' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Typography', 'goldsmith' ),
                'id' => 'sidebar_panel_menuitem_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '.sliding-menu .sliding-menu-inner li a, .sliding-menu li .sliding-menu__nav,.sliding-menu .sliding-menu__nav:before' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Menu Item Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_menuitem_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.sliding-menu .sliding-menu-inner li a, .sliding-menu li .sliding-menu__nav,.sliding-menu .sliding-menu__nav:before' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Menu Item Color ( Hover/Active )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_menuitem_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.sliding-menu li.current-menu-parent>.sliding-menu__nav, .sliding-menu li.current-menu-item>.sliding-menu__nav, .sliding-menu li.current-menu-item>a, .sliding-menu li a:hover, .sliding-menu li.active a, .sliding-menu li .sliding-menu__nav:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Submenu Back Title Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_submenu_back_title_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.sliding-menu li .sliding-menu__nav.sliding-menu__back' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Submenu Back Title Border Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_submenu_back_title_brdcolor',
                'type' => 'color',
                'mode' => 'border-bottom-color',
                'output' => array( '.sliding-menu .sliding-menu__back:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Submenu Item Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_submenuitem_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.sliding-menu .sliding-menu-inner ul li a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Submenu Item Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_submenuitem_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.sliding-menu .sliding-menu-inner ul li a:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Copyright Text', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your site copyright here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_copyright',
                'type' => 'textarea',
                'default' => sprintf( '<p>&copy; %1$s, <a class="theme" href="%2$s">%3$s</a> Website. %4$s <a class="dev" href="https://ninetheme.com/contact/">%5$s</a></p>',
                    date( 'Y' ),
                    esc_url( home_url( '/' ) ),
                    get_bloginfo( 'name' ),
                    esc_html__( 'Made with passion by', 'goldsmith' ),
                    esc_html__( 'Ninetheme.', 'goldsmith' )
                ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Copyright Text Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_menu_copy_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-sidemenu-copyright,.goldsmith-sidemenu-copyright p' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Copyright Link Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_menu_copy_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-sidemenu-copyright a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Copyright Link Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_menu_copy_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-sidemenu-copyright a:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Sidebar Menu Language Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the sidebar language switcher if you have.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_lang_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            )
    	)
    ));

    //HEADER MOBILE TOP
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Sidebar Panel WooCommerce', 'goldsmith' ),
        'desc' => esc_html__( 'Cart,Wishlist,Compare,Categories', 'goldsmith' ),
        'id' => 'headersidebarpanelcartsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Cart Panel Title', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can change cart panel title if you want', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_custom_title',
                'type' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Wishlist Panel Title', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can change wishlist panel title if you want', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_wishlist_custom_title',
                'type' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Compare Panel Title', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can change compare panel title if you want', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_compare_custom_title',
                'type' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Categories Panel Title', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can change categories panel title if you want', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_categories_custom_title',
                'type' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_title_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .panel-top-title, .goldsmith-side-panel .panel-top-title' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Border Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_title_brdcolor',
                'type' => 'color',
                'mode' => 'border-bottom-color',
                'output' => array( '.goldsmith-header-mobile-content .panel-top-title:after, .goldsmith-side-panel .panel-top-title:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Item Title Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_title_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .cart-name, .goldsmith-side-panel .cart-name' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Item Price Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_price_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .goldsmith-price, .goldsmith-side-panel .goldsmith-price' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Item Quantity Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_qty_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .quantity, .goldsmith-side-panel .quantity' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Item Quantity Plus Minus Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_qty_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .quantity-button.plus,.goldsmith-header-mobile-content .quantity-button.minus,.goldsmith-header-mobile-content .input-text::-webkit-input-placeholder,.goldsmith-header-mobile-content .input-text,.goldsmith-side-panel .quantity-button.plus,.goldsmith-side-panel .quantity-button.minus,.goldsmith-side-panel .input-text::-webkit-input-placeholder,.goldsmith-side-panel .input-text'),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Item Quantity Plus Minus Backgroud Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_qty_hvrbgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .quantity-button.plus:hover,.goldsmith-header-mobile-content .quantity-button.minus:hover,.goldsmith-side-panel .quantity-button.plus:hover,.goldsmith-side-panel .quantity-button.minus:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Wishlist,Compare Add to Cart Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_addtocart_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .goldsmith-content-info .add_to_cart_button' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Wishlist,Compare Add to Cart Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_addtocart_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .goldsmith-content-info .add_to_cart_button:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Wishlist,Compare Stock Status Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_addtocart_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .goldsmith-content-info .product-stock' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Subtotal Border Top Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_subtotal_brdcolor',
                'type' => 'color',
                'mode' => 'border-top-color',
                'output' => array( '.goldsmith-header-mobile-content .cart-total-price,.goldsmith-side-panel .cart-total-price' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Subtotal Title Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_subtotal_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .cart-total-price,.goldsmith-side-panel .cart-total-price' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Subtotal Price Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_subtotal_price_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .cart-total-price .cart-total-price-right,.goldsmith-side-panel .cart-total-price .cart-total-price-right' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Delete Icon Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_delete_icon_color',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array( '.goldsmith-header-mobile-content .del-icon a svg,.goldsmith-side-panel .del-icon a svg' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Free Shipping Text Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_extra_text_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .minicart-extra-text,.goldsmith-side-panel .minicart-extra-text' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Buttons Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_buttons_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .cart-bottom-btn .goldsmith-btn,.goldsmith-side-panel .cart-bottom-btn .goldsmith-btn' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Buttons Background Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_buttons_hvrbgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .cart-bottom-btn .goldsmith-btn:hover,.goldsmith-side-panel .cart-bottom-btn .goldsmith-btn:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Buttons Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_buttons_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .cart-bottom-btn .goldsmith-btn,.goldsmith-side-panel .cart-bottom-btn .goldsmith-btn' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Buttons Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_buttons_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .cart-bottom-btn .goldsmith-btn:hover,.goldsmith-side-panel .cart-bottom-btn .goldsmith-btn:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Empty Cart Icon Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_empty_svg_color',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array( '.goldsmith-header-mobile-content svg,.goldsmith-side-panel .panel-content svg' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Empty Cart Text Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_cart_item_empty_text_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .cart-empty-content .goldsmith-small-title,.goldsmith-side-panel  .goldsmith-small-title' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Category Item Title Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_categories_item_title_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .category-area .category-title' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Category Item Counter Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_categories_item_counter_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .category-area .cat-count' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Category Item Counter Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_categories_item_counter_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .category-area .cat-count' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            )
    	)
    ));
    //HEADER MOBILE TOP
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Sidebar Panel Contact Form', 'goldsmith' ),
        'id' => 'headersidebarpanelcontactsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Contact Form 7', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a form from the list.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_cf7',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $wpcf7_args,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Contact Form Shotcode', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your shortcode here, if you want to use different contact form instead of Contact Form 7.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_menu_custom_form',
                'type' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Panel Title', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can change contact form panel title if you want', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_custom_title',
                'type' => 'text',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_title_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .contact-area .panel-top-title' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Border Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_title_brdcolor',
                'type' => 'color',
                'mode' => 'border-bottom-color',
                'output' => array( '.goldsmith-header-mobile-content .contact-area .panel-top-title:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Placeholder Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_placeholder_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array('.goldsmith-header-mobile-content .contact-area .wpcf7-form-control::-webkit-input-placeholder'),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_input_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .contact-area input:not([type="submit"]),.goldsmith-header-mobile-content .contact-area textarea,.goldsmith-header-mobile-content .contact-area select' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_input_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .contact-area input:not([type="submit"]),.goldsmith-header-mobile-content .contact-area textarea,.goldsmith-header-mobile-content .contact-area select' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Background Color ( Focus )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_input_focus_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .contact-area input:not([type="submit"]):focus,.goldsmith-header-mobile-content .contact-area textarea:focus,.goldsmith-header-mobile-content .contact-area select:focus' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Border', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_input_brdcolor',
                'type' => 'border',
                'output' => array( '.goldsmith-header-mobile-content .contact-area input:not([type="submit"]),.goldsmith-header-mobile-content .contact-area textarea,.goldsmith-header-mobile-content .contact-area select' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Border ( Focus )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_input_focus_brdcolor',
                'type' => 'border',
                'output' => array( '.goldsmith-header-mobile-content .contact-area input:not([type="submit"]):focus,.goldsmith-header-mobile-content .contact-area textarea:focus,.goldsmith-header-mobile-content .contact-area select:focus' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_submit_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .contact-area input[type="submit"],.goldsmith-header-mobile-content .contact-area .wpcf7-submit' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Background Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_submit_hvrbgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .contact-area input[type="submit"]:hover,.goldsmith-header-mobile-content .contact-area .wpcf7-submit:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_submit_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .contact-area input[type="submit"],.goldsmith-header-mobile-content .contact-area .wpcf7-submit' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_submit_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .contact-area input[type="submit"]:hover,.goldsmith-header-mobile-content .contact-area .wpcf7-submit:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Border', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_submit_brdcolor',
                'type' => 'border',
                'output' => array( '.goldsmith-header-mobile-content .contact-area input[type="submit"],.goldsmith-header-mobile-content .contact-area .wpcf7-submit' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Border ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_submit_hvrbrdcolor',
                'type' => 'border',
                'output' => array( '.goldsmith-header-mobile-content .contact-area input[type="submit"]:hover,.goldsmith-header-mobile-content .contact-area .wpcf7-submit:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Element Label Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_label_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .contact-area label,.goldsmith-header-mobile-content .contact-area .label' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Contact Details Heading Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_h_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .contact-area h2,.goldsmith-header-mobile-content .contact-area h3,.goldsmith-header-mobile-content .contact-area h4,.goldsmith-header-mobile-content .contact-area h5,.goldsmith-header-mobile-content .contact-area h6, .goldsmith-header-mobile-content .contact-area .goldsmith-meta-title' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Contact Details Text Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_contact_form_p_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .contact-area div,.goldsmith-header-mobile-content .contact-area p' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            )
    	)
    ));
    //HEADER MOBILE TOP
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Sidebar Panel Account', 'goldsmith' ),
        'id' => 'headersidebarpanelaccountsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'id' => 'sidebar_account_logout_start',
                'title' => esc_html__( 'Log Out Options', 'goldsmith' ),
                'type' => 'section',
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_title_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .account-area .panel-top-title' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Icon Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_title_icon_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .account-area .form-action-btn svg' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Panel Title Border Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_title_brdcolor',
                'type' => 'color',
                'mode' => 'border-bottom-color',
                'output' => array( '.goldsmith-header-mobile-content .account-area .panel-top-title:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Placeholder Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_placeholder_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array('.woocommerce .goldsmith-header-mobile-content .account-area form .form-row input.input-text::-webkit-input-placeholder'),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_input_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.woocommerce .goldsmith-header-mobile-content .account-area form .form-row input.input-text' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_input_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.woocommerce .goldsmith-header-mobile-content .account-area form .form-row input.input-text' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Background Color ( Focus )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_input_focus_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.woocommerce .goldsmith-header-mobile-content .account-area form .form-row input.input-text:focus' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Border', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_input_brdcolor',
                'type' => 'border',
                'output' => array( '.woocommerce .goldsmith-header-mobile-content .account-area form .form-row input.input-text' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Input Border ( Focus )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_input_focus_brdcolor',
                'type' => 'border',
                'output' => array( '.woocommerce .goldsmith-header-mobile-content .account-area form .form-row input.input-text:focus' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Checkbox Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_checkbox_bgcolor',
                'type' => 'border',
                'output' => array( '.goldsmith-header-mobile-content .account-area input[type="checkbox"]:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Checkbox Background Color ( Checked )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_checkbox_actbgcolor',
                'type' => 'border',
                'output' => array( '.goldsmith-header-mobile-content .account-area input[type="checkbox"]:checked:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_submit_bgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .account-area button[type="submit"],.woocommerce-page .goldsmith-header-mobile-content .account-area button.button' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Background Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_submit_hvrbgcolor',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .account-area button[type="submit"]:hover,.woocommerce-page .goldsmith-header-mobile-content .account-area button.button:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_submit_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .account-area button[type="submit"],.woocommerce-page .goldsmith-header-mobile-content .account-area button.button' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_submit_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .account-area button[type="submit"]:hover,.woocommerce-page .goldsmith-header-mobile-content .account-area button.button:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Border', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_submit_brdcolor',
                'type' => 'border',
                'output' => array( '.goldsmith-header-mobile-content .account-area button[type="submit"],.woocommerce-page .goldsmith-header-mobile-content .account-area button.button' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Submit Button Border ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_submit_hvrbrdcolor',
                'type' => 'border',
                'output' => array( '.goldsmith-header-mobile-content .account-area button[type="submit"]:hover,.woocommerce-page .goldsmith-header-mobile-content .account-area button.button:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Form Element Label Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_label_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.woocommerce .goldsmith-header-mobile-content .account-area form .form-row label' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Lost Password Text Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_form_p_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .account-area .lost_password a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' => 'sidebar_account_logout_end',
                'title' => esc_html__( 'Log In Options', 'goldsmith' ),
                'type' => 'section',
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_menu_item_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .account-area li.menu-item a' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_menu_item_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-header-mobile-content .account-area li.menu-item a:hover' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Border Bootm Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sidebar_panel_account_menu_item_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-header-mobile-content .account-area li.menu-item a:after' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' => 'sidebar_account_end',
                'type' => 'section',
                'customizer' => true,
                'indent' => false,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            )
    	)
    ));
    //HEADER MOBILE TOP
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Header Extra', 'goldsmith' ),
        'id' => 'headerbottomsection',
        'subsection' => true,
        'icon' => 'fa fa-cog',
        'fields' => array(
            array(
                'title' => esc_html__( 'Before Header ( Elementor Templates )', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates for before header.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'before_header_template',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'edit_before_header_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'before_header_template', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Sticky ( Before Header Elementor Templates )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'sticky_before_header_template',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'before_header_template', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'After Header ( Elementor Templates )', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates for after header.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'after_header_template',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_template', '=', 'default' )
                )
            ),
            array(
                'id' =>'edit_after_header_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'after_header_template', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Bottom Bar Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site header bottom area.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_bottom_area_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array( 'header_visibility', '=', '1' )
            ),
            array(
                'id' =>'header_bottom_area_display_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Bottom Bar Display Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your header template.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'show-always' => esc_html__( 'Show Always', 'goldsmith' ),
                    'show-on-scroll' => esc_html__( 'Show on Scroll', 'goldsmith' )
                ),
                'default' => 'show-on-scroll',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_bottom_area_visibility', '=', '1' )
                )
            ),
            array(
                'id' =>'header_bottom_area_template_type',
                'type' => 'button_set',
                'title' => esc_html__( 'Bottom Bar Template Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your template type.', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'filters' => esc_html__( 'Shop Filters', 'goldsmith' ),
                    'elementor' => esc_html__( 'Elementor Template', 'goldsmith' )
                ),
                'default' => 'filters',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_bottom_area_visibility', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Header Bottom Bar Elementor Templates', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates for before header bottom area.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'header_bottom_bar_template',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_bottom_area_visibility', '=', '1' ),
                    array( 'header_bottom_area_template_type', '=', 'elementor' )
                )
            ),
            array(
                'id' =>'edit_header_bottom_bar_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'header_visibility', '=', '1' ),
                    array( 'header_bottom_area_visibility', '=', '1' ),
                    array( 'header_bottom_area_template_type', '=', 'elementor' ),
                    array( 'header_bottom_bar_template', '!=', '' )
                )
            ),
            array(
                'id' =>'header_myaccount_action_type',
                'type' => 'select',
                'title' => esc_html__( 'Header My Account Click Action', 'goldsmith' ),
                'customizer' => true,
                'options' => array(
                    'page' => esc_html__( 'Redirect to Account Page', 'goldsmith' ),
                    'panel' => esc_html__( 'Open in Left Panel', 'goldsmith' ),
                    'popup' => esc_html__( 'Open in Popup', 'goldsmith' )
                ),
                'default' => 'panel',
                'required' => array( 'header_visibility', '=', '1' )
            )
    	)
    ));
    //FOOTER SECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Mobile Bottom Menu Bar', 'goldsmith' ),
        'desc' => esc_html__( 'These are main settings for general theme!', 'goldsmith' ),
        'id' => 'mobilebottommenusubsection',
        'subsection' => true,
        'icon' => 'el el-photo',
        'fields' => array(
            array(
                'title' => esc_html__( 'Mobile Bottom Menu Bar Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site mobile bottom menu bar.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'bottom_mobile_nav_visibility',
                'type' => 'switch',
                'default' => 0
            ),
            array(
                'title' => esc_html__( 'Mobile Bottom Menu Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your mobile bottom menu popup search type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'bottom_mobile_menu_type',
                'type' => 'button_set',
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Default', 'goldsmith' ),
                    'wp-menu' => esc_html__( 'WP Menu', 'goldsmith' ),
                    'elementor' => esc_html__( 'Elementor Template', 'goldsmith' ),
                ),
                'default' => 'default',
                'required' => array( 'bottom_mobile_nav_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Mobile Bottom Menu Display Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your mobile bottom menu popup search type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'bottom_mobile_menu_display_type',
                'type' => 'button_set',
                'customizer' => true,
                'options' => array(
                    'show-allways' => esc_html__( 'Always show', 'goldsmith' ),
                    'show-onscroll' => esc_html__( 'Show on scroll', 'goldsmith' ),
                ),
                'default' => 'show-allways',
                'required' => array( 'bottom_mobile_nav_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'elementor' )
                )
            ),
            array(
                'id' =>'mobile_bottom_menu_layouts',
                'type' => 'sorter',
                'title' => esc_html__( 'Layout Manager', 'goldsmith' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme bottom mobile menu bar', 'goldsmith' ),
                'options' => array(
                    'show' => array(
                        'home' => esc_html__( 'Home', 'goldsmith' ),
                        'shop' => esc_html__( 'Shop', 'goldsmith' ),
                        'cart' => esc_html__( 'Cart', 'goldsmith' ),
                        'account' => esc_html__( 'Account', 'goldsmith' ),
                        'search' => esc_html__( 'Search', 'goldsmith' ),
                        'cats' => esc_html__( 'Categories', 'goldsmith' ),
                    ),
                    'hide'  => array(
                    )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' )
                )
            ),
            array(
                'desc' => sprintf( '%s <b>"%s"</b> <a class="button" href="'.admin_url('nav-menus.php?action=edit&menu=0').'" target="_blank">%s</a>',
                    esc_html__( 'Please create new menu and assign it as', 'goldsmith' ),
                    esc_html__( 'Mobile Bottom Menu', 'goldsmith' ),
                    esc_html__( 'Create New Menu', 'goldsmith' )
                ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_menu_info',
                'type' => 'info',
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'wp-menu' )
                )
            ),
            array(
                'title' => esc_html__( 'Change Default Menu Item HTML', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can change the site mobile bottom menu item html.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'bottom_mobile_nav_item_customize',
                'type' => 'switch',
                'default' => 0,
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Home HTML ( optional )', 'goldsmith' ),
                'desc' => esc_html__( 'If you do not want to make any changes in this part, please clear the default html from the field.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_home_html',
                'type' => 'textarea',
                'default' => sprintf( '<li class="menu-item"><a href="%s">%s<span>Home</span></a></li>',
                    esc_url( home_url( '/' ) ),
                    goldsmith_svg_lists( 'arrow-left', 'goldsmith-svg-icon' )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' ),
                    array( 'bottom_mobile_nav_item_customize', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Shop HTML ( optional )', 'goldsmith' ),
                'desc' => esc_html__( 'If you do not want to make any changes in this part, please clear the default html from the field.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_shop_html',
                'type' => 'textarea',
                'default' => sprintf( '<li class="menu-item"><a href="%s">%s<span>Shop</span></a></li>',
                    function_exists('wc_get_page_permalink') ? esc_url( wc_get_page_permalink( 'shop' ) ) : '#0',
                    goldsmith_svg_lists( 'store', 'goldsmith-svg-icon' )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' ),
                    array( 'bottom_mobile_nav_item_customize', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Cart HTML ( optional )', 'goldsmith' ),
                'desc' => esc_html__( 'If you do not want to make any changes in this part, please clear the default html from the field.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_cart_html',
                'type' => 'textarea',
                'default' => sprintf( '<li class="menu-item"><a href="%s">%s<span class="goldsmith-cart-count goldsmith-wc-count"></span><span>Cart</span></a></li>',
                    function_exists('wc_get_page_permalink') ? esc_url( wc_get_page_permalink( 'cart' ) ) : '#0',
                    goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' ),
                    array( 'bottom_mobile_nav_item_customize', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Account HTML ( optional )', 'goldsmith' ),
                'desc' => esc_html__( 'If you do not want to make any changes in this part, please clear the default html from the field.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_account_html',
                'type' => 'textarea',
                'default' => sprintf( '<li class="menu-item"><a href="%s">%s<span>Account</span></a></li>',
                    function_exists('wc_get_page_permalink') ? esc_url( wc_get_page_permalink( 'myaccount' ) ) : '#0',
                    goldsmith_svg_lists( 'user-1', 'goldsmith-svg-icon' )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' ),
                    array( 'bottom_mobile_nav_item_customize', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Search HTML ( optional )', 'goldsmith' ),
                'desc' => esc_html__( 'If you do not want to make any changes in this part, please clear the default html from the field.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_search_html',
                'type' => 'textarea',
                'default' => sprintf( '<li class="menu-item"><a href="#0" data-name="search-popup">%s<span>Search</span></a></li>',
                    goldsmith_svg_lists( 'search', 'goldsmith-svg-icon' )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' ),
                    array( 'bottom_mobile_nav_item_customize', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom Categories HTML ( optional )', 'goldsmith' ),
                'desc' => esc_html__( 'If you do not want to make any changes in this part, please clear the default html from the field.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_custom_cats_html',
                'type' => 'textarea',
                'default' => sprintf( '<li class="menu-item"><a href="#0" data-name="search-cats">%s<span>Categories</span></a></li>',
                    goldsmith_svg_lists( 'paper-search', 'goldsmith-svg-icon' )
                ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '=', 'default' ),
                    array( 'bottom_mobile_nav_item_customize', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Backgroud Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_bg_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-bottom-mobile-nav' ),
                'required' => array( 'bottom_mobile_nav_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-bottom-mobile-nav .menu-item a,.goldsmith-bottom-mobile-nav .menu-item a span' ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Menu Item Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-bottom-mobile-nav .menu-item a:hover,.goldsmith-bottom-mobile-nav .menu-item a:hover span'),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'SVG Icon Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_icon_color',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array('.goldsmith-bottom-mobile-nav .menu-item svg,.goldsmith-bottom-mobile-nav .goldsmith-svg-icon'),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'SVG Icon Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_icon_hvrcolor',
                'type' => 'color',
                'mode' => 'fill',
                'output' => array('.goldsmith-bottom-mobile-nav .menu-item a:hover svg,.goldsmith-bottom-mobile-nav a:hover .goldsmith-svg-icon'),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Font Icon Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_icon2_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array('.goldsmith-bottom-mobile-nav a i,.goldsmith-bottom-mobile-nav a span' ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Font Icon Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_icon2_hvrcolor',
                'type' => 'color',
                'mode' => 'color',
                'output' => array('.goldsmith-bottom-mobile-nav a:hover i,.goldsmith-bottom-mobile-nav a:hover span' ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Count Background Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_cart_count_bg_color',
                'type' => 'color',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-bottom-mobile-nav .menu-item a span.goldsmith-wc-count, .goldsmith-bottom-mobile-nav .goldsmith-wc-count' ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            ),
            array(
                'title' => esc_html__( 'Cart Count Number Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'mobile_bottom_menu_item_cart_count_number_color',
                'type' => 'color',
                'mode' => 'color',
                'output' => array( '.goldsmith-bottom-mobile-nav .menu-item a span.goldsmith-wc-count, .goldsmith-bottom-mobile-nav .goldsmith-wc-count' ),
                'required' => array(
                    array( 'bottom_mobile_nav_visibility', '=', '1' ),
                    array( 'bottom_mobile_menu_type', '!=', 'elementor' )
                )
            )
    	)
    ));
    //FOOTER SECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Footer', 'goldsmith' ),
        'desc' => esc_html__( 'These are main settings for general theme!', 'goldsmith' ),
        'id' => 'footersection',
        'icon' => 'el el-photo',
        'fields' => array(
            array(
                'title' => esc_html__( 'Footer Section Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site footer copyright and footer widget area on the site with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'footer_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Footer Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your footer type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'footer_template',
                'type' => 'select',
                'customizer' => true,
                'options' => array(
                    'default' => esc_html__( 'Deafult Site Footer', 'goldsmith' ),
                    'elementor' => esc_html__( 'Elementor Templates', 'goldsmith' )
                ),
                'default' => 'default',
                'required' => array( 'footer_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'footer_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'elementor' )
                )
            ),
            array(
                'id' =>'edit_footer_template',
                'type' => 'info',
                'desc' => 'Edit template',
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'elementor' ),
                    array( 'footer_elementor_templates', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Copyright Text', 'goldsmith' ),
                'subtitle' => esc_html__( 'HTML allowed (wp_kses)', 'goldsmith' ),
                'desc' => esc_html__( 'Enter your site copyright text here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'footer_copyright',
                'type' => 'textarea',
                'validate' => 'html',
                'default' => sprintf( '<p>&copy; %1$s, <a class="theme" href="%2$s">%3$s</a> Theme. %4$s <a class="dev" href="https://ninetheme.com/contact/">%5$s</a></p>',
                    date( 'Y' ),
                    esc_url( home_url( '/' ) ),
                    get_bloginfo( 'name' ),
                    esc_html__( 'Made with passion by', 'goldsmith' ),
                    esc_html__( 'Ninetheme.', 'goldsmith' )
                ),
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            //information on-off
            array(
                'id' =>'info_f0',
                'type' => 'info',
                'style' => 'success',
                'title' => esc_html__( 'Success!', 'goldsmith' ),
                'icon' => 'el el-info-circle',
                'customizer' => true,
                'desc' => sprintf(esc_html__( '%s section is disabled on the site.Please activate to view subsection options.', 'goldsmith' ), '<b>Site Main Footer</b>' ),
                'required' => array( 'footer_visibility', '=', '0' )
            )
    	)
    ));
    //FOOTER SECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Footer Style', 'goldsmith' ),
        'desc' => esc_html__( 'These are main settings for general theme!', 'goldsmith' ),
        'id' => 'footerstylesubsection',
        'icon' => 'el el-photo',
        'subsection' => true,
        'fields' => array(
            array(
                'id' =>'footer_color_customize',
                'type' => 'info',
                'icon' => 'el el-brush',
                'customizer' => false,
                'desc' => sprintf(esc_html__( '%s', 'goldsmith' ), '<h2>Footer Color Customize</h2>' ),
                'customizer' => true,
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Footer Padding', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can set the top spacing of the site main footer.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'footer_pad',
                'type' => 'spacing',
                'output' => array('#nt-footer' ),
                'mode' => 'padding',
                'units' => array('em', 'px' ),
                'units_extended' => 'false',
                'default' => array(
                    'padding-top' => '',
                    'padding-right' => '',
                    'padding-bottom' => '',
                    'padding-left' => '',
                    'units' => 'px'
                ),
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Footer Background Color', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own colors for the footer.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'footer_bg_clr',
                'type' => 'color',
                'validate' => 'color',
                'mode' => 'background-color',
                'output' => array( '#nt-footer' ),
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Copyright Text Color', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own colors for the copyright.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'footer_copy_clr',
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'output' => array( '#nt-footer, #nt-footer p' ),
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Link Color', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own colors for the copyright.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'footer_link_clr',
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'output' => array( '#nt-footer a' ),
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            array(
                'title' => esc_html__( 'Link Color ( Hover )', 'goldsmith' ),
                'desc' => esc_html__( 'Set your own colors for the copyright.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'footer_link_hvr_clr',
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'output' => array( '#nt-footer a:hover' ),
                'required' => array(
                    array( 'footer_visibility', '=', '1' ),
                    array( 'footer_template', '=', 'default' )
                )
            ),
            //information on-off
            array(
                'id' =>'info_fc0',
                'type' => 'info',
                'style' => 'success',
                'title' => esc_html__( 'Success!', 'goldsmith' ),
                'icon' => 'el el-info-circle',
                'customizer' => true,
                'desc' => sprintf(esc_html__( '%s section is disabled on the site.Please activate to view subsection options.', 'goldsmith' ), '<b>Site Main Footer</b>' ),
                'required' => array( 'footer_visibility', '=', '0' )
            )
    	)
    ));

    /*************************************************
    ## WOOCOMMERCE SECTION
    *************************************************/
    if ( class_exists( 'WooCommerce' ) ) {
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__( 'WOOCOMMERCE', 'goldsmith' ),
            'id' => 'woocommercesection',
            'icon' => 'el el-shopping-cart-sign',
            'fields' => array(
                array(
                    'title' => esc_html__('Ajax Shop', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_ajax_filter',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Ajax Login/Register', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'wc_ajax_login_register',
                    'type' => 'switch',
                    'default' => 1
                ),
	            array(
	                'title' => esc_html__( 'Free Shipping Text', 'goldsmith' ),
	                'subtitle' => esc_html__( 'Add your custom text to header cart before buttons.', 'goldsmith' ),
	                'customizer' => true,
	                'id' => 'header_cart_before_buttons',
	                'type' => 'text',
	                'default' => ''
	            ),
            )
        ));
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__( 'Ajax Live Search', 'goldsmith' ),
            'id' => 'woocommer_ajax_search_cesection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Search Display', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'popup_search_form_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Custom Shortcode (Plugin)', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'popup_search_form_shortcode',
                    'type' => 'text',
                    'default' => ''
                )
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Cross-Sells Products', 'goldsmith'),
            'id' => 'singleshopcrosssells',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Cross-Sells Title', 'goldsmith'),
                    'subtitle' => esc_html__('Add your cart page cross-sells section title here.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_title',
                    'type' => 'text',
                    'default' => ''
                ),
                array(
                    'id' =>'shop_cross_sells_type',
                    'type' => 'button_set',
                    'title' => esc_html__('Cross-Sells Layout Type', 'goldsmith'),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop product page cross-sells.', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'slider' => esc_html__( 'Slider', 'goldsmith' ),
                        'grid' => esc_html__( 'Grid', 'goldsmith' )
                    ),
                    'default' => 'slider'
                ),
                array(
                    'title' => esc_html__('Post Column', 'goldsmith'),
                    'subtitle' => esc_html__('You can control cross-sells post column with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_colxl',
                    'type' => 'slider',
                    'default' => 3,
                    'min' => 1,
                    'step' => 1,
                    'max' => 5,
                    'display_value' => 'text',
                    'required' => array( 'shop_cross_sells_type', '=', 'grid' )
                ),
                array(
                    'title' => esc_html__('Post Column ( Desktop/Tablet )', 'goldsmith'),
                    'subtitle' => esc_html__('You can control cross-sells post column for tablet device with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_collg',
                    'type' => 'slider',
                    'default' => 3,
                    'min' => 1,
                    'step' => 1,
                    'max' => 4,
                    'display_value' => 'text',
                    'required' => array( 'shop_cross_sells_type', '=', 'grid' )
                ),
                array(
                    'title' => esc_html__('Post Column ( Tablet )', 'goldsmith'),
                    'subtitle' => esc_html__('You can control cross-sells post column for phone device with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_colsm',
                    'type' => 'slider',
                    'default' => 2,
                    'min' => 1,
                    'step' => 1,
                    'max' => 3,
                    'display_value' => 'text',
                    'required' => array( 'shop_cross_sells_type', '=', 'grid' )
                ),
                array(
                    'title' => esc_html__('Post Column ( Phone )', 'goldsmith'),
                    'subtitle' => esc_html__('You can control cross-sells post column for phone device with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_colxs',
                    'type' => 'slider',
                    'default' => 2,
                    'min' => 1,
                    'step' => 1,
                    'max' => 2,
                    'display_value' => 'text',
                    'required' => array( 'shop_cross_sells_type', '=', 'grid' )
                ),
                array(
                    'id' => 'shop_cross_sells_section_slider_start',
                    'type' => 'section',
                    'title' => esc_html__('Cross-Sells Slider Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Perview ( Min 1024px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control cross-sells post slider item count for big device with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_perview',
                    'type' => 'slider',
                    'default' => 5,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Perview ( Min 768px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control cross-sells post slider item count for big device with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_mdperview',
                    'type' => 'slider',
                    'default' => 3,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Perview ( Min 480px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control cross-sells post slider item count for big device with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_smperview',
                    'type' => 'slider',
                    'default' => 2,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Speed', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control cross-sells post slider item gap.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_speed',
                    'type' => 'slider',
                    'default' => 1000,
                    'min' => 100,
                    'step' => 1,
                    'max' => 10000,
                    'display_value' => 'text',
                    'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Gap', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control cross-sells post slider item gap.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_gap',
                    'type' => 'slider',
                    'default' => 30,
                    'min' => 0,
                    'step' => 1,
                    'max' => 100,
                    'display_value' => 'text',
                    'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Autoplay', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_autoplay',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Loop', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_loop',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Mousewheel', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_mousewheel',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Free Mode', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_cross_sells_freemode',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                ),
                array(
                    'id' => 'shop_cross_sells_section_slider_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array( 'shop_cross_sells_type', '=', 'slider' )
                )
            )
        ));
        // Cross-Sells Posts
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Recently Viewed Products', 'goldsmith'),
            'id' => 'shop_recently_subsection',
            'subsection' => true,
            'icon' => 'el el-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Recently Viewed Display', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_recently_visibility',
                    'type' => 'switch',
                    'default' => 0
                ),
                array(
                    'title' => esc_html__('Recently Title', 'goldsmith'),
                    'subtitle' => esc_html__('Add your recently viewed section title here.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_recently_title',
                    'type' => 'text',
                    'default' => '',
                    'required' => array( 'shop_recently_visibility', '=', '1' )
                ),
                array(
                    'id' => 'shop_recently_section_slider_start',
                    'type' => 'section',
                    'title' => esc_html__('Recently Slider Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array( 'shop_recently_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Perview ( Min 1024px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control recently viewed product slider item count for big device with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_recently_perview',
                    'type' => 'slider',
                    'default' => 5,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array( 'shop_recently_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Perview ( Min 768px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control recently viewed product slider item count for big device with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_recently_mdperview',
                    'type' => 'slider',
                    'default' => 3,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array( 'shop_recently_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Perview ( Min 480px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control recently viewed product slider item count for big device with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_recently_smperview',
                    'type' => 'slider',
                    'default' => 2,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array( 'shop_recently_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Speed', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control recently viewed product slider item gap.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_recently_speed',
                    'type' => 'slider',
                    'default' => 1000,
                    'min' => 100,
                    'step' => 1,
                    'max' => 10000,
                    'display_value' => 'text',
                    'required' => array( 'shop_recently_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Gap', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control recently viewed product slider item gap.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_recently_gap',
                    'type' => 'slider',
                    'default' => 30,
                    'min' => 0,
                    'step' => 1,
                    'max' => 100,
                    'display_value' => 'text',
                    'required' => array( 'shop_recently_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Autoplay', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_recently_autoplay',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array( 'shop_recently_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Loop', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_recently_loop',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'shop_recently_visibility', '=', '1' )
                ),
                array(
                    'id' => 'shop_recently_section_slider_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array( 'shop_recently_visibility', '=', '1' )
                )
            )
        ));
        // Popup Notices SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Wishlist', 'goldsmith'),
            'id' => 'compare_wishlist_subsection',
            'subsection' => true,
            'icon' => 'el el-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Wishlist Display', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'wishlist_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'id' =>'wishlist_shortcoe_info',
                    'type' => 'info',
                    'desc' =>  sprintf( esc_html__( 'Create new Wishlist page and use this shortcode %s to display the wishlist on a page.', 'goldsmith' ),'<code>[goldsmith_wishlist]</code>'),
                    'required' => array( 'wishlist_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Wishlist Page', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select page from the list.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'wishlist_page_id',
                    'type' => 'select',
                    'data' => 'page',
                    'multi' => false,
                    'required' => array( 'wishlist_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Wishlist Page Copy', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'wishlist_page_copy',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array( 'wishlist_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Wishlist My Account Page', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'wishlist_page_myaccount',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'wishlist_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Sidebar Panel Clear Button', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'sidebar_panel_wishlist_clear_btn',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'wishlist_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Disable the wishlist for unauthenticated users', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'wishlist_disable_unauthenticated',
                    'type' => 'switch',
                    'on' => esc_html__( 'Yes', 'goldsmith' ),
                    'off' => esc_html__( 'No', 'goldsmith' ),
                    'default' => 0,
                    'required' => array( 'wishlist_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Maximum wishlists per user', 'goldsmith' ),
                    'desc' => esc_html__( 'Please leave this field blank for unlimited additions', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'wishlist_max_count',
                    'type' => 'text',
                    'default' => '',
                    'validate' => array( 'numeric' ),
                    'required' => array( 'wishlist_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Wishlist Button Action', 'goldsmith' ),
                    'id' =>'wishlist_btn_action',
                    'type' => 'select',
                    'mutiple' => false,
                    'options' => array(
                        'panel' => esc_html__( 'Open Sidebar Panel', 'goldsmith' ),
                        'message' => esc_html__( 'Show Message', 'goldsmith' ),
                    ),
                    'default' => 'panel',
                    'required' => array( 'wishlist_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Header Wishlist Button Action', 'goldsmith' ),
                    'id' =>'header_wishlist_btn_action',
                    'type' => 'select',
                    'mutiple' => false,
                    'options' => array(
                        'panel' => esc_html__( 'Open Sidebar Panel', 'goldsmith' ),
                        'page' => esc_html__( 'Open Wishlist Page', 'goldsmith' ),
                    ),
                    'default' => 'panel',
                    'required' => array( 'wishlist_visibility', '=', '1' )
                ),
            )
        ));
        // Quick View SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Quick View', 'goldsmith'),
            'id' => 'shopquickviewsubsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Quick View Display', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Overlay Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change quick view overlay color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_overlaycolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.mfp-bg.mfp-goldsmith-quickview'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Content Background Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change quick view background color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-quickview-wrapper'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Content Border', 'goldsmith'),
                    'subtitle' => esc_html__('Set your custom border styles for the posts.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_brd',
                    'type' => 'border',
                    'all' => false,
                    'output' => array('.goldsmith-quickview-wrapper'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Content Padding', 'goldsmith'),
                    'subtitle' => esc_html__('You can set the spacing of the site shop page post.', 'goldsmith'),
                    'customizer' => true,
                    'id' =>'quick_view_pad',
                    'type' => 'spacing',
                    'output' => array('.goldsmith-quickview-wrapper'),
                    'mode' => 'padding',
                    'units' => array('em', 'px'),
                    'units_extended' => 'false',
                    'default' => array(
                        'units' => 'px'
                    ),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Content Width', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'quick_view_width',
                    'type' => 'slider',
                    'default' => '',
                    'min' => 0,
                    'step' => 1,
                    'max' => 4000,
                    'display_value' => 'text',
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Content Width Responsive ( min-width 768px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'quick_view_width_sm',
                    'type' => 'slider',
                    'default' => '',
                    'min' => 0,
                    'step' => 1,
                    'max' => 1200,
                    'display_value' => 'text',
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Close Button Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_close_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.mfp-goldsmith-quickview .goldsmith-panel-close-button'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Close Button Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_close_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-quickview-wrapper .mfp-close.goldsmith-panel-close-button:before,.goldsmith-quickview-wrapper .mfp-close.goldsmith-panel-close-button:after'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Product Title Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_title_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-quickview-wrapper .goldsmith-product-title'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Product Price Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_price_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-quickview-wrapper .goldsmith-price'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Product Description Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_desc_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-quickview-wrapper .goldsmith-product-summary .goldsmith-summary-item p'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Add to Cart Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_btn_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-quickview-wrapper .goldsmith-btn-small'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Add to Cart Background Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_btn_hvrbgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-quickview-wrapper .goldsmith-btn-small:hover'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Add to Cart Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_btn_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-quickview-wrapper .goldsmith-btn-small'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Add to Cart Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_btn_hvrcolor',
                    'type' => 'color',
                    'mode' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-quickview-wrapper .goldsmith-btn-small:hover'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Meta Label Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_meta_label_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-quickview-wrapper .goldsmith-attr-label'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Meta Label Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_view_meta_value_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-quickview-wrapper .product_meta>span a, .goldsmith-quickview-wrapper .goldsmith-attr-value, .goldsmith-quickview-wrapper .goldsmith-attr-value a'),
                    'required' => array( 'quick_view_visibility', '=', '1' )
                )
            )
        ));
        // Quick Shop SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Quick Shop View', 'goldsmith'),
            'id' => 'shopquickshopsubsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Ajax Quick Shop Display', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_shop_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Popup Overlay Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change quick view overlay color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_shop_overlaycolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.mfp-bg.mfp-goldsmith-quickshop'),
                    'required' => array( 'quick_shop_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Content Background Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change quick view background color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_shop_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-quickshop-wrapper'),
                    'required' => array( 'quick_shop_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Content Border', 'goldsmith'),
                    'subtitle' => esc_html__('Set your custom border styles for the posts.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'quick_shop_brd',
                    'type' => 'border',
                    'all' => false,
                    'output' => array('.goldsmith-quickshop-wrapper'),
                    'required' => array( 'quick_shop_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Content Padding', 'goldsmith'),
                    'subtitle' => esc_html__('You can set the spacing of the site shop page post.', 'goldsmith'),
                    'customizer' => true,
                    'id' =>'quick_shop_pad',
                    'type' => 'spacing',
                    'output' => array('.goldsmith-quickshop-wrapper'),
                    'mode' => 'padding',
                    'units' => array('em', 'px'),
                    'units_extended' => 'false',
                    'default' => array(
                        'units' => 'px'
                    ),
                    'required' => array( 'quick_shop_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Max Width', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'quick_shop_width',
                    'type' => 'slider',
                    'default' => '',
                    'min' => 0,
                    'step' => 1,
                    'max' => 4000,
                    'display_value' => 'text',
                    'required' => array( 'quick_view_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Max Width Responsive ( min-width 768px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can use this option to control the theme content width.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'quick_shop_width_sm',
                    'type' => 'slider',
                    'default' => '',
                    'min' => 0,
                    'step' => 1,
                    'max' => 1200,
                    'display_value' => 'text',
                    'required' => array( 'quick_shop_visibility', '=', '1' )
                )
            )
        ));
        // Popup Notices SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Popup Notices', 'goldsmith'),
            'id' => 'shoppopupnoticessubsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Ajax Add to Cart Notices Display', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_cart_popup_notices_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__( 'Notices Duration ( ms )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_cart_popup_notices_duration',
                    'type' => 'slider',
                    'default' => 3500,
                    'min' => 0,
                    'step' => 100,
                    'max' => 20000,
                    'display_value' => 'text',
                    'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Background Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change popup notices background color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_cart_popup_notices_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-popup-notices .woocommerce-message'),
                    'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Border Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change popup notices background color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_cart_popup_notices_brdcolor',
                    'type' => 'color',
                    'mode' => 'border-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-popup-notices .woocommerce-message'),
                    'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Text Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change popup notices text color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_cart_popup_notices_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-popup-notices .woocommerce-message'),
                    'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Error Background Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change popup error notices background color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_cart_popup_notices_error_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-popup-notices .woocommerce-error'),
                    'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Error Border Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change popup error notices background color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_cart_popup_notices_error_brdcolor',
                    'type' => 'color',
                    'mode' => 'border-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-popup-notices .woocommerce-error'),
                    'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Text Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change popup notices text color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_cart_popup_notices_error_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-popup-notices .woocommerce-error'),
                    'required' => array( 'shop_cart_popup_notices_visibility', '=', '1' )
                )
            )
        ));
        // Extra
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__( 'Catalog Mode', 'goldsmith' ),
            'id' => 'shop_catalog_mode_subsection',
            'subsection'=> true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Catalog Mode', 'goldsmith'),
                    'subtitle' => esc_html__('Use this option to hide all the "Add to Cart" buttons in the shop.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'woo_catalog_mode',
                    'type' => 'switch',
                    'default' => 0
                ),
                array(
                    'title' => esc_html__('Disable Add to Cart', 'goldsmith'),
                    'subtitle' => esc_html__('Use this option to hide all the "Add to Cart" buttons in the shop.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'woo_disable_addtocart',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array( 'woo_catalog_mode', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Disable Product Page Add to Cart', 'goldsmith'),
                    'subtitle' => esc_html__('Use this option to hide all the "Add to Cart" buttons in the product page.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'woo_disable_product_addtocart',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array( 'woo_catalog_mode', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Disable Cart and Checkout Page', 'goldsmith'),
                    'subtitle' => esc_html__('Use this option to hide the "Cart" page, "Checkout" page in the shop.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'woo_disable_cart_checkout',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array( 'woo_catalog_mode', '=', '1' )
                )
            )
        ));
        // Extra
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__( 'Extra', 'goldsmith' ),
            'id' => 'shop_extra_subsection',
            'subsection'=> true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Product Sale Label Display', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_sale_label_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Product Discount Display', 'goldsmith'),
                    'subtitle' => esc_html__('You can enable or disable the site shop free shipping progressbar with switch option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'discount_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'id' =>'discount_percantage_position',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Discount Percentage Position', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'before' => esc_html__( 'Before', 'goldsmith' ),
                        'after' => esc_html__( 'After', 'goldsmith' ),
                    ),
                    'default' => 'after',
                    'required' => array( 'discount_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Woocommerce Ajax Variation Threshold', 'goldsmith'),
                    'subtitle' => esc_html__('Default: 200', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'woo_ajax_variation_threshold',
                    'type' => 'text',
                    'validate' => array( 'numeric' ),
                    'default' => ''
                )
            )
        ));

        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('SHOP PAGE', 'goldsmith'),
            'id' => 'shopsection',
            'icon' => 'el el-shopping-cart-sign',
            'fields' => array()
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__( 'Shop Page Layout', 'goldsmith' ),
            'id' => 'shoplayoutsection',
            'subsection'=> true,
            'icon' => 'el el-website',
            'fields' => array(
                array(
                    'id' =>'shop_layout',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Shop Layouts', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop page sidebar area.', 'goldsmith' ),
                    'options' => array(
                        'top-sidebar' => esc_html__( 'Top Hidden Sidebar', 'goldsmith' ),
                        'fixed-sidebar' => esc_html__( 'Left Fixed Sidebar', 'goldsmith' ),
                        'left-sidebar' => esc_html__( 'Left Sidebar', 'goldsmith' ),
                        'right-sidebar' => esc_html__( 'Right Sidebar', 'goldsmith' ),
                        'no-sidebar' => esc_html__( 'No Sidebar', 'goldsmith' )
                    ),
                    'default' => 'fixed-sidebar',
                ),
                array(
                    'title' => esc_html__('Choosen Filters', 'goldsmith'),
                    'subtitle' => esc_html__('You can enable or disable the filters selected before the loop.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'choosen_filters_before_loop',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'shop_layout', '!=', 'top-sidebar' ),
                        array( 'shop_layout', '!=', 'top-sidebar' ),
                        array( 'shop_layout', '!=', 'no-sidebar' )
                    )
                ),
                array(
                    'id' =>'shop_grid_type',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Shop Grid Type', 'goldsmith' ),
                    'options' => array(
                        'grid' => esc_html__( 'Default Grid', 'goldsmith' ),
                        'masonry' => esc_html__( 'Masonry', 'goldsmith' )
                    ),
                    'default' => 'grid',
                    'required' => array(
                        array( 'shop_layout', '!=', 'left-sidebar' ),
                        array( 'shop_layout', '!=', 'right-sidebar' )
                    )
                ),
                array(
                    'id' =>'shop_masonry_column',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Shop Masonry Column Width', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select your shop masonry type column width', 'goldsmith' ),
                    'options' => array(
                        '3' => esc_html__( '3 Column', 'goldsmith' ),
                        '4' => esc_html__( '4 Column', 'goldsmith' ),
                        '5' => esc_html__( '5 Column', 'goldsmith' ),
                        '6' => esc_html__( '6 Column', 'goldsmith' ),
                    ),
                    'default' => '4',
                    'required' => array(
                        array( 'shop_layout', '!=', 'left-sidebar' ),
                        array( 'shop_layout', '!=', 'right-sidebar' ),
                        array( 'shop_grid_type', '=', 'masonry' )
                    )
                ),
                array(
                    'id' =>'shop_hidden_sidebar_column',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Hidden Sidebar Widget Column Width', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select your shop sidebar widget column width', 'goldsmith' ),
                    'options' => array(
                        '1' => esc_html__( '1 Column', 'goldsmith' ),
                        '2' => esc_html__( '2 Column', 'goldsmith' ),
                        '3' => esc_html__( '3 Column', 'goldsmith' ),
                        '4' => esc_html__( '4 Column', 'goldsmith' ),
                        '5' => esc_html__( '5 Column', 'goldsmith' ),
                    ),
                    'default' => '3',
                    'required' => array( 'shop_layout', '=', 'top-sidebar' )
                ),
                array(
                    'id' =>'shop_loop_filters_layouts',
                    'type' => 'sorter',
                    'title' => esc_html__( 'Shop Filter Area Layouts Manager', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop page filter area.', 'goldsmith' ),
                    'options' => array(
                        'left' => array(
                            'breadcrumbs' => esc_html__( 'Breadcrumbs', 'goldsmith' ),
                        ),
                        'right' => array(
                            'sidebar-filter' => esc_html__( 'Sidebar Toggle', 'goldsmith' ),
                            'per-page' => esc_html__( 'Perpage Selection', 'goldsmith' ),
                            'column-select' => esc_html__( 'Column Selection', 'goldsmith' ),
                            'ordering' => esc_html__( 'Ordering', 'goldsmith' )
                        ),
                        'hide' => array(
                            'result-count' => esc_html__( 'Result Count', 'goldsmith' ),
                            'search' => esc_html__( 'Search Popup', 'goldsmith' ),
                        )
                    )
                ),
                array(
                    'title' => esc_html__('Per Page Select Options', 'goldsmith'),
                    'subtitle' => esc_html__('Separate each number with a comma.For example: 12,24,36', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'per_page_select_options',
                    'type' => 'text',
                    'default' => '9,12,18,24'
                ),
                array(
                    'id' =>'shop_paginate_type',
                    'type' => 'button_set',
                    'title' => esc_html__('Pagination Type', 'goldsmith'),
                    'subtitle' => esc_html__('Select your pagination type.', 'goldsmith'),
                    'options' => array(
                        'pagination' => esc_html__('Default Pagination', 'goldsmith'),
                        'ajax-pagination' => esc_html__('Ajax Pagination', 'goldsmith'),
                        'loadmore' => esc_html__('Ajax Load More', 'goldsmith'),
                        'infinite' => esc_html__('Ajax Infinite Scroll', 'goldsmith')
                    ),
                    'default' => 'ajax-pagination',
                    'required' => array( 'shop_ajax_filter', '=', '1' )
                ),
                array(
                    'id' =>'shop_container_width',
                    'type' => 'select',
                    'title' => esc_html__( 'Container Width', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'default' => esc_html__( 'Deafult ( theme Content Width from Main settings )', 'goldsmith' ),
                        'stretch' => esc_html__( 'Stretch', 'goldsmith' ),
                        'custom' => esc_html__( 'Custom', 'goldsmith' ),
                    ),
                    'default' => 'default'
                ),
                array(
                    'title' => esc_html__( 'Shop Custom Container Width', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_custom_container_width',
                    'default' => '',
                    'min' => 0,
                    'step' => 1,
                    'max' => 4000,
                    'type' => 'slider',
                    'required' => array( 'shop_container_width', '=', 'custom' )
                )
            )
        ));
        function goldsmith_shop_loop_custom_query(){
            $filters_field = array();
            $filters_field[] = array(
                'title' => esc_html__('Custom Query', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_custom_query_visibility',
                'type' => 'switch',
                'default' => 0
            );
            $filters_field[] = array(
                'title' => esc_html__('Scenario', 'goldsmith'),
                'subtitle' => esc_html__('Choose the your scenario.', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_custom_query_scenario',
                'type' => 'button_set',
                'options' => array(
                    '' => esc_html__( 'Newest', 'goldsmith' ),
                    'featured' => esc_html__( 'Featured', 'goldsmith' ),
                    'on-sale' => esc_html__( 'On Sale', 'goldsmith' ),
                    'best' => esc_html__( 'Best Selling', 'goldsmith' ),
                    'rated' => esc_html__( 'Top-rated', 'goldsmith' ),
                    'popularity' => esc_html__( 'Popularity', 'goldsmith' ),
                    'custom' => esc_html__( 'Specific Categories', 'goldsmith' ),
                ),
                'default' => '',
                'required' => array( 'shop_custom_query_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Order', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_custom_query_order',
                'type' => 'button_set',
                'options' => array(
                    'ASC' => esc_html__( 'Ascending', 'goldsmith' ),
                    'DESC' => esc_html__( 'Descending', 'goldsmith' )
                ),
                'default' => 'DESC',
                'required' => array( 'shop_custom_query_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Order By', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_custom_query_orderby',
                'type' => 'button_set',
                'options' => array(
                    'ID' => esc_html__( 'Post ID', 'goldsmith' ),
                    'menu_order' => esc_html__( 'Menu Order', 'goldsmith' ),
                    'rand' => esc_html__( 'Random', 'goldsmith' ),
                    'date' => esc_html__( 'Date', 'goldsmith' ),
                    'title' => esc_html__( 'Title', 'goldsmith' ),
                    'popularity' => esc_html__( 'Popularity', 'goldsmith' ),
                ),
                'default' => 'menu_order',
                'required' => array( 'shop_custom_query_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Show Products ( for Per Page )', 'goldsmith'),
                'subtitle' => esc_html__('Here you can set the maximum number of products you want to show on your shop page.', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_custom_query_perpage',
                'type' => 'slider',
                'default' => 20,
                'min' => 1,
                'step' => 1,
                'max' => 1000,
                'display_value' => 'text',
                'required' => array( 'shop_custom_query_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Mobile Device Show Products ( for Per Page )', 'goldsmith'),
                'subtitle' => esc_html__('Here you can set the maximum number of products you want to show on mobile device.', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_custom_query_mobile_perpage',
                'type' => 'slider',
                'default' => 20,
                'min' => 1,
                'step' => 1,
                'max' => 1000,
                'display_value' => 'text',
                'required' => array( 'shop_custom_query_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Category(s)', 'goldsmith'),
                'subtitle' => esc_html__('Select category(s) from the list.', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_custom_query_cats',
                'type' => 'select',
                'data' => 'terms',
                'multi' => true,
                'args' => [ 'taxonomies' => array('product_cat') ],
                'required' => array( 'shop_custom_query_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Category(s) Filter Type', 'goldsmith'),
                'subtitle' => esc_html__('Choose the your product category filter type.', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_custom_query_cats_operator',
                'type' => 'button_set',
                'options' => array(
                    'include' => esc_html__('Include', 'goldsmith'),
                    'exclude' => esc_html__('Exclude', 'goldsmith'),
                ),
                'default' => 'exclude',
                'required' => array(
                    array( 'shop_custom_query_visibility', '=', '1' ),
                    array( 'shop_custom_query_cats', '!=', '' )
                )
            );
            $filters_field[] = array(
                'title' => esc_html__('Tags(s)', 'goldsmith'),
                'subtitle' => esc_html__('Select category(s) from the list.', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_custom_query_tags',
                'type' => 'select',
                'data' => 'terms',
                'multi' => true,
                'args' => [ 'taxonomies' => array('product_tag') ],
                'required' => array( 'shop_custom_query_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Tag(s) Filter Type', 'goldsmith'),
                'subtitle' => esc_html__('Choose the your product tag filter type.', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_custom_query_tags_operator',
                'type' => 'button_set',
                'options' => array(
                    'include' => esc_html__('Include', 'goldsmith'),
                    'exclude' => esc_html__('Exclude', 'goldsmith'),
                ),
                'default' => 'exclude',
                'required' => array(
                    array( 'shop_custom_query_visibility', '=', '1' ),
                    array( 'shop_custom_query_tags', '!=', '' )
                )
            );
            $filters_field[] = array(
                'title' => esc_html__( 'Select Attributes', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select Attribute(s) from the list.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'shop_custom_query_attr',
                'type' => 'select',
                'sortable' => true,
                'options' => goldsmith_wc_attributes(),
                'multi' => true,
                'required' => array( 'shop_custom_query_visibility', '=', '1' )
            );

            $attribute_taxonomies = wc_get_attribute_taxonomies();
            foreach ( $attribute_taxonomies as $tax ) {
                $options = array();
                if ( !empty($tax) ) {
                    $tax_name = $tax->attribute_name;
                    $tax_label = $tax->attribute_label;
                    if ( $tax_name ) {
                        $filters_field[] = array(
                            'title' => esc_html__( 'Attributes Terms ( for '.$tax_label.' )', 'goldsmith' ),
                            'customizer' => true,
                            'id' => 'shop_custom_query_attr_terms_'.$tax_name,
                            'type' => 'select',
                            'sortable' => true,
                            'data' => 'terms',
                            'args' => array( 'taxonomies' => array( 'pa_'.$tax_name ) ),
                            'multi' => true,
                            'required' => array(
                                array( 'shop_custom_query_visibility', '=', '1' ),
                                array( 'shop_custom_query_attr', '=', $tax_name )
                            )
                        );
                        $filters_field[] = array(
                            'title' => esc_html__('Attributes Terms Filter Type ( for '.$tax_label.' )', 'goldsmith'),
                            'subtitle' => esc_html__('Choose the your product attribute terms filter type.', 'goldsmith'),
                            'customizer' => true,
                            'id' => 'shop_custom_query_attr_terms_operator_'.$tax_name,
                            'type' => 'button_set',
                            'options' => array(
                                'include' => esc_html__('Include', 'goldsmith'),
                                'exclude' => esc_html__('Exclude', 'goldsmith'),
                            ),
                            'default' => 'include',
                            'required' => array(
                                array( 'shop_custom_query_visibility', '=', '1' ),
                                array( 'shop_custom_query_attr', '=', $tax_name ),
                                array( 'shop_custom_query_attr_terms_'.$tax_name, '!=', '' ),
                            )
                        );
                    }
                }
            }

            return $filters_field;
        }
        // SINGLE CONTENT SUBSECTION
        Redux::setSection($goldsmith_pre,
            array(
                'title' => esc_html__('Shop Custom Query', 'goldsmith'),
                'id' => 'shop_loop_custom_query_subsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => goldsmith_shop_loop_custom_query()
            )
        );
        // SINGLE HERO SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Shop Page Header', 'goldsmith'),
            'desc' => esc_html__('These are shop page header section settings', 'goldsmith'),
            'id' => 'shopheadersubsection',
            'subsection' => true,
            'icon' => 'el el-brush',
            'fields' => array(
                array(
                    'title' => esc_html__('Use Different Header Layouts', 'goldsmith'),
                    'subtitle' => esc_html__('You can use different header layouts type on shop pages.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_different_header_layouts',
                    'type' => 'switch',
                    'default' => 0
                ),
                array(
                    'id' =>'shop_header_layouts',
                    'type' => 'sorter',
                    'title' => esc_html__( 'Shop Header Layout Manager', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme header', 'goldsmith' ),
                    'options' => array(
                        'left' => array(
                            'sidemenu' => esc_html__( 'Sidemenu Toggle', 'goldsmith' ),
                            'logo' => esc_html__( 'Logo', 'goldsmith' )
                        ),
                        'center'=> array(
                            'menu' => esc_html__( 'Main Menu', 'goldsmith' )
                        ),
                        'right'=> array(
                            'search' => esc_html__( 'Search', 'goldsmith' ),
                            'buttons' => esc_html__( 'Buttons', 'goldsmith' )
                        ),
                        'hide'  => array(
                            'center-logo' => esc_html__( 'Menu Logo Menu', 'goldsmith' ),
                            'mini-menu' => esc_html__( 'Mini Menu', 'goldsmith' ),
                            'double-menu' => esc_html__( 'Double Menu', 'goldsmith' ),
                            'custom-html' => esc_html__( 'Phone Number', 'goldsmith' )
                        )
                    ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_layouts', '=', '1' )
                    )
                ),
                array(
                    'id' =>'shop_header_buttons_layouts',
                    'type' => 'sorter',
                    'title' => esc_html__( 'Shop Header Buttons Manager', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme header for buttons', 'goldsmith' ),
                    'options' => array(
                        'show'  => array(
                            'cart' => esc_html__( 'Cart', 'goldsmith' ),
                            'wishlist' => esc_html__( 'Wishlist', 'goldsmith' ),
                            'compare' => esc_html__( 'Compare', 'goldsmith' ),
                            'account' => esc_html__( 'Account', 'goldsmith' )
                        ),
                        'hide'  => array(
                            'search' => esc_html__( 'Search', 'goldsmith' )
                        )
                    ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_layouts', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__('Use Different Header Background Type', 'goldsmith'),
                    'subtitle' => esc_html__('You can use different header background type on product page.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_different_header_bg_type',
                    'type' => 'switch',
                    'default' => 0
                ),
                array(
                    'id' => 'shop_header_menu_items_customize_start',
                    'type' => 'section',
                    'title' => esc_html__('Header Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'id' =>'shop_header_bg_type',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Header Background Type', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'default' => esc_html__( 'Deafult', 'goldsmith' ),
                        'dark' => esc_html__( 'Dark', 'goldsmith' ),
                        'trans-light' => esc_html__( 'Transparent Light', 'goldsmith' ),
                        'trans-dark' => esc_html__( 'Transparent Dark', 'goldsmith' )
                    ),
                    'default' => 'default',
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Header Background Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_header_bg',
                    'type' => 'color_rgba',
                    'mode' => 'background-color',
                    'output' => array( '.archive.post-type-archive-product header.goldsmith-header-default,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Menu Item Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the navigation menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_nav_a',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product .goldsmith-header-top-menu-area>ul>li.menu-item>a,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .goldsmith-header-top-menu-area>ul>li.menu-item>a' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Menu Item Color ( Hover and Active )', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the navigation menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_nav_hvr_a',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product .current-menu-parent>a,.archive.post-type-archive-product .current-menu-item>a,.archive.post-type-archive-product .goldsmith-header-top-menu-area>ul>li.menu-item>a:hover,.archive.post-type-archive-product .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .current-menu-parent>a,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .current-menu-item>a,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .goldsmith-header-top-menu-area>ul>li.menu-item>a:hover,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover'),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sticky Header Background Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_nav_top_sticky_bg',
                    'type' => 'color_rgba',
                    'mode' => 'background-color',
                    'output' => array( '.archive.post-type-archive-product.has-sticky-header.scroll-start header.goldsmith-header-default' ),
                    'required' => array( 'header_sticky_visibility', '=', '1' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sticky Menu Item Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the sticky navigation menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_sticky_nav_a',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product.has-sticky-header.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item>a' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sticky Menu Item Color ( Hover and Active )', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the sticky navigation menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_sticky_nav_hvr_a',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product.has-sticky-header.scroll-start .current-menu-parent>a, .archive.post-type-archive-product.has-sticky-header.scroll-start .current-menu-item>a, .archive.post-type-archive-product.has-sticky-header.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item>a:hover, .archive.post-type-archive-product.has-sticky-header.scroll-start .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'id' => 'shop_header_menu_items_style_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'id' => 'shop_header_submenu_items_style_start',
                    'type' => 'section',
                    'title' => esc_html__('Header Sub Menu Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sub Menu Background Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_nav_submenu_bg',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product .goldsmith-header-top-menu-area ul li .submenu' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Menu Item Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_nav_submenu_a',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Menu Item Color ( Hover and Active )', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_nav_submenu_hvr_a',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.archive.post-type-archive-product .goldsmith-header-top-menu-area ul li .submenu>li.menu-item.active>a' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'id' => 'shop_header_submenu_items_style_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'id' => 'shop_header_svgbuttons_items_style_start',
                    'type' => 'section',
                    'title' => esc_html__('Header SVG Buttons Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'SVG Icon Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Cart, Wishlist, Compare, Account, Search, Sidemenu bar', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_header_buttons_svg_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product .goldsmith-header-default .header-top-buttons .top-action-btn,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .header-top-buttons .top-action-btn' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Button Counter Background Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Cart, Wishlist, Compare', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_header_buttons_counter_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product .goldsmith-header-default .goldsmith-wc-count,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .goldsmith-wc-count' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Button Counter Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Cart, Wishlist, Compare', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_header_buttons_counter_color',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product .goldsmith-header-default .goldsmith-wc-count,.archive.post-type-archive-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .goldsmith-wc-count' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sticky Header SVG Icon Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Cart, Wishlist, Compare, Account, Search, Sidemenu bar', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_sticky_header_buttons_svg_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product.scroll-start .goldsmith-header-default .header-top-buttons .top-action-btn,.archive.post-type-archive-product.has-default-header-type-trans.scroll-start header.goldsmith-header-default .header-top-buttons .top-action-btn' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sticky Header Button Counter Background Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Cart, Wishlist, Compare', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_sticky_header_buttons_counter_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product.scroll-start .goldsmith-header-default .goldsmith-wc-count,.archive.post-type-archive-product.has-default-header-type-trans.scroll-start header.goldsmith-header-default .goldsmith-wc-count' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sticky Header Button Counter Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Cart, Wishlist, Compare', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_sticky_header_buttons_counter_color',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.archive.post-type-archive-product.scroll-start .goldsmith-header-default .goldsmith-wc-count,.archive.post-type-archive-product.has-default-header-type-trans.scroll-start header.goldsmith-header-default .goldsmith-wc-count' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'id' => 'shop_header_svgbuttons_items_style_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'shop_different_header_bg_type', '=', '1' )
                    )
                )
            )
        ));
        // SINGLE HERO SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Shop Page Hero', 'goldsmith'),
            'desc' => esc_html__('These are shop page hero section settings', 'goldsmith'),
            'id' => 'shopherosubsection',
            'subsection' => true,
            'icon' => 'el el-brush',
            'fields' => array(
                array(
                    'title' => esc_html__('Hero display', 'goldsmith'),
                    'subtitle' => esc_html__('You can enable or disable the site shop page hero section with switch option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_hero_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Custom Page Title', 'goldsmith'),
                    'subtitle' => esc_html__('Add your shop page custom title here.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_title',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' )
                    )
                ),
                array(
                    'id' =>'shop_hero_type',
                    'type' => 'button_set',
                    'title' => esc_html__('Shop Hero Type', 'goldsmith'),
                    'subtitle' => esc_html__('Select your pagination type.', 'goldsmith'),
                    'options' => array(
                        'default' => esc_html__('Default Hero', 'goldsmith'),
                        'elementor' => esc_html__('Elementor Templates', 'goldsmith'),
                    ),
                    'default' => 'default',
                    'required' => array( 'shop_hero_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates.If you want to show the theme default hero template please leave a blank.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_elementor_templates',
                    'type' => 'select',
                    'customizer' => true,
                    'data' => 'posts',
                    'args' => $el_args,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'elementor' ),
                    )
                ),
                array(
                    'title' => esc_html__( 'Category Pages Hero Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates.If you want to show the theme default hero template please leave a blank.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_cats_hero_elementor_templates',
                    'type' => 'select',
                    'customizer' => true,
                    'data' => 'posts',
                    'args' => $el_args,
                    'required' => array( 'shop_hero_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Tags Pages Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates.If you want to show the theme default hero template please leave a blank.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_tax_hero_elementor_templates',
                    'type' => 'select',
                    'customizer' => true,
                    'data' => 'posts',
                    'args' => $el_args,
                    'required' => array( 'shop_hero_visibility', '=', '1' )
                ),
                array(
                    'id' =>'shop_hero_layout_type',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Default Hero Layouts', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select how you want the layout to appear on the theme shop page hero area.', 'goldsmith' ),
                    'options' => array(
                        'mini' => esc_html__( 'Title + Breadcrumbs', 'goldsmith' ),
                        'small' => esc_html__( 'Title Center', 'goldsmith' ),
                        'big' => esc_html__( 'Title + Categories', 'goldsmith' ),
                        'cat-slider' => esc_html__( 'Title + Categories Slider', 'goldsmith' ),
                    ),
                    'default' => 'mini',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                    )
                ),
                array(
                    'id' =>'shop_hero_mini_text_align',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Text Alingment', 'goldsmith' ),
                    'options' => array(
                        'left' => esc_html__( 'Left', 'goldsmith' ),
                        'center' => esc_html__( 'Center', 'goldsmith' ),
                        'right' => esc_html__( 'Right', 'goldsmith' ),
                    ),
                    'default' => 'left',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'mini' ),
                    )
                ),
                array(
                    'title' => esc_html__('Categories Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_cat_customize_section_start',
                    'type' => 'section',
                    'indent' => true,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '!=', 'mini' ),
                        array( 'shop_hero_layout_type', '!=', 'small' ),
                    )
                ),
                array(
                    'title' => esc_html__( 'Category(s) Include/Exclude', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select category(s) from the list.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_cats',
                    'type' => 'select',
                    'data' => 'terms',
                    'multi' => true,
                    'sortable' => true,
                    'args'  => [
                        'taxonomies' => array( 'product_cat' ),
                    ],
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '!=', 'mini' ),
                        array( 'shop_hero_layout_type', '!=', 'small' ),
                    )
                ),
                array(
                    'id' =>'shop_hero_carousel_catfilter',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Category Filter Type', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'exclude' => esc_html__( 'Exclude', 'goldsmith' ),
                        'include' => esc_html__( 'Include', 'goldsmith' ),
                    ),
                    'default' => 'include',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '!=', 'mini' ),
                        array( 'shop_hero_layout_type', '!=', 'small' ),
                        array( 'shop_hero_carousel_cats', '!=', '' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Only Top Categories', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_catparent',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '!=', 'mini' ),
                        array( 'shop_hero_layout_type', '!=', 'small' )
                    )
                ),
                array(
                    'id' => 'shop_hero_carousel_cathideempty',
                    'type' => 'switch',
                    'title' => esc_html__('Hide Empty Categories', 'goldsmith'),
                    'customizer' => true,
                    'default' => '1',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '!=', 'mini' ),
                        array( 'shop_hero_layout_type', '!=', 'small' ),
                    )
                ),
                array(
                    'id' =>'shop_hero_carousel_catorder',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Category Order', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'ASC' => esc_html__( 'ASC', 'goldsmith' ),
                        'DESC' => esc_html__( 'DESC', 'goldsmith' ),
                    ),
                    'default' => 'ASC',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '!=', 'mini' ),
                        array( 'shop_hero_layout_type', '!=', 'small' ),
                    )
                ),
                array(
                    'id' =>'shop_hero_carousel_catorderby',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Category Orderby', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'name' => esc_html__( 'Name', 'goldsmith' ),
                        'slug' => esc_html__( 'Slug', 'goldsmith' ),
                        'id' => esc_html__( 'ID', 'goldsmith' ),
                        'count' => esc_html__( 'Count', 'goldsmith' ),
                        'description' => esc_html__( 'Description', 'goldsmith' ),
                        'menu_order' => esc_html__( 'Menu order', 'goldsmith' ),
                    ),
                    'default' => 'name',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '!=', 'mini' ),
                        array( 'shop_hero_layout_type', '!=', 'small' ),
                    )
                ),
                array(
                    'id' => 'shop_hero_carousel_catthumb',
                    'type' => 'switch',
                    'title' => esc_html__('Category Thumbnail', 'goldsmith'),
                    'customizer' => true,
                    'default' => '1',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '!=', 'mini' ),
                        array( 'shop_hero_layout_type', '!=', 'small' ),
                    )
                ),
                array(
                    'id' => 'shop_hero_carousel_cattcount',
                    'type' => 'switch',
                    'title' => esc_html__('Category Count', 'goldsmith'),
                    'customizer' => true,
                    'default' => '1',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '!=', 'mini' ),
                        array( 'shop_hero_layout_type', '!=', 'small' ),
                    )
                ),
                array(
                    'id' => 'shop_hero_carousel_ajax',
                    'type' => 'button_set',
                    'title' => esc_html__('Category Click Action', 'goldsmith'),
                    'customizer' => true,
                    'default' => 'filter',
                    'options' => array(
                        'filter' => esc_html__( 'Filter Products ( Ajax )', 'goldsmith' ),
                        'link' => esc_html__( 'Category Link', 'goldsmith' ),
                    ),
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '!=', 'mini' ),
                        array( 'shop_hero_layout_type', '!=', 'small' ),
                    )
                ),
                array(
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_cat_customize_section_end',
                    'type' => 'section',
                    'indent' => false,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '!=', 'mini' ),
                        array( 'shop_hero_layout_type', '!=', 'small' ),
                    )
                ),
                array(
                    'title' => esc_html__('Carousel Slider Options', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_customize_section_start',
                    'type' => 'section',
                    'indent' => true,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Slider Perview ( Min 1500px )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_perview1',
                    'type' => 'slider',
                    'default' => 9,
                    'min' => 1,
                    'step' => 1,
                    'max' => 15,
                    'display_value' => 'text',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Slider Perview ( Min 1400px )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_perview2',
                    'type' => 'slider',
                    'default' => 8,
                    'min' => 1,
                    'step' => 1,
                    'max' => 15,
                    'display_value' => 'text',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Slider Perview ( Min 1200px )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_perview3',
                    'type' => 'slider',
                    'default' => 7,
                    'min' => 1,
                    'step' => 1,
                    'max' => 15,
                    'display_value' => 'text',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Slider Perview ( Min 992px )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_perview4',
                    'type' => 'slider',
                    'default' => 5,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Slider Perview ( Min 768px )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_perview5',
                    'type' => 'slider',
                    'default' => 7,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Slider Perview ( Min 320px )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_perview6',
                    'type' => 'slider',
                    'default' => 3,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Loop', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_loop',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Autoplay', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_autoplay',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Rewind', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_rewind',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Centered', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_centred',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Speed', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_speed',
                    'type' => 'slider',
                    'default' => 2000,
                    'min' => 100,
                    'step' => 1,
                    'max' => 10000,
                    'display_value' => 'text',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Gap', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_gap',
                    'type' => 'slider',
                    'default' => 1,
                    'min' => 1,
                    'step' => 1,
                    'max' => 100,
                    'display_value' => 'text',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'customizer' => true,
                    'id' => 'shop_hero_carousel_customize_section_end',
                    'type' => 'section',
                    'indent' => false,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'shop_hero_layout_type', '=', 'cat-slider' )
                    )
                ),
                array(
                    'title' => esc_html__('Hero Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_hero_customize_section_start',
                    'type' => 'section',
                    'indent' => true,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' )
                    )
                ),
                array(
                    'id' =>'shop_hero_bg_type',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Hero Image Type', 'goldsmith' ),
                    'options' => array(
                        'bg' => esc_html__( 'Background', 'goldsmith' ),
                        'img' => esc_html__( 'Image', 'goldsmith' ),
                    ),
                    'default' => 'img',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__('Hero Background', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_hero_bg',
                    'type' => 'background',
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Background Image Size', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_bg_imgsize',
                    'type' => 'select',
                    'data' => 'image_sizes'
                ),
                array(
                    'title' => esc_html__( 'Hero height', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_height',
                    'type' => 'dimensions',
                    'width' => false,
                    'output' => array( '#nt-shop-page .goldsmith-page-hero' ),
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Hero height (Laptop)', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_height_laptop',
                    'type' => 'dimensions',
                    'width' => false,
                    'unit' => false,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Hero height (Tablet)', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_height_tablet',
                    'type' => 'dimensions',
                    'width' => false,
                    'unit' => false,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Hero height (Phone)', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_height_phone',
                    'type' => 'dimensions',
                    'width' => false,
                    'unit' => false,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Text Typography', 'goldsmith' ),
                    'id' => 'shop_hero_title_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-shop-hero .goldsmith-page-hero-content .page-title' ),
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Hero Page Title Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_title_color',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.goldsmith-shop-hero .goldsmith-page-hero-content .page-title' ),
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Hero Page Description Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_hero_desc_color',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.goldsmith-shop-hero .goldsmith-page-hero-content .term-description' ),
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Breadcrumbs Link Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_breadcrumbs_link_clr',
                    'type' => 'color',
                    'default' => '',
                    'output' => array( '.goldsmith-breadcrumb li a,.woocommerce-breadcrumb a' ),
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'breadcrumbs_visibility', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Breadcrumbs Link Color ( Hover )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_breadcrumbs_link_hvrclr',
                    'type' => 'color',
                    'default' => '',
                    'output' => array( '.goldsmith-breadcrumb li a:hover,.woocommerce-breadcrumb a:hover' ),
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'breadcrumbs_visibility', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Breadcrumbs Current Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_breadcrumbs_current',
                    'type' => 'color',
                    'default' => '',
                    'output' => array( '.goldsmith-breadcrumb li.breadcrumb_active, .goldsmith-breadcrumb .breadcrumb-item.active, .woocommerce-breadcrumb' ),
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'breadcrumbs_visibility', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Breadcrumbs Separator Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_breadcrumbs_sep',
                    'type' => 'color',
                    'default' => '',
                    'output' => array( '.goldsmith-breadcrumb .breadcrumb_link_seperator, .goldsmith-breadcrumb .breadcrumb-item+.breadcrumb-item::before,.woocommerce-breadcrumb a:after' ),
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' ),
                        array( 'breadcrumbs_visibility', '=', '1' )
                    )
                ),
                array(
                    'customizer' => true,
                    'id' => 'shop_hero_customize_section_end',
                    'type' => 'section',
                    'indent' => false,
                    'required' => array(
                        array( 'shop_hero_visibility', '=', '1' ),
                        array( 'shop_hero_type', '=', 'default' )
                    )
                )
            )
        ));
        // SINGLE CONTENT SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Shop Page Content', 'goldsmith'),
            'id' => 'shopcontentsubsection',
            'subsection' => true,
            'icon' => 'el el-brush',
            'fields' => array(
                array(
                    'title' => esc_html__( 'Product Box Pre-layouts', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Choose the your product box type.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_product_type',
                    'type' => 'image_select',
                    'width' => '175',
                    'options' => array(
                        '1' => array(
                            'title' => 'Type 1',
                            'img' => get_template_directory_uri() . '/inc/core/theme-options/img/style-1.png'
                        ),
                        '2' => array(
                            'title' => 'Type 2',
                            'img' => get_template_directory_uri() . '/inc/core/theme-options/img/type-2.png'
                        ),
                        '3' => array(
                            'title' => 'Type 3',
                            'img' => get_template_directory_uri() . '/inc/core/theme-options/img/style-3.png'
                        ),
                        'custom' => array(
                            'title' => 'Custom',
                            'img' => get_template_directory_uri() . '/inc/core/theme-options/img/style-4.png'
                        ),
                        'woo' => array(
                            'title' => 'WooCommerce Default',
                            'img' => get_template_directory_uri() . '/inc/core/theme-options/img/style-4.png'
                        )
                    ),
                    'default' => '2'
                ),
                array(
                    'id' =>'shop_loop_product_layouts',
                    'type' => 'sorter',
                    'title' => esc_html__( 'Custom Product Layouts', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the shop loop product item.', 'goldsmith' ),
                    'options' => array(
                        'show'  => array(
                            'thumb' => esc_html__( 'Image', 'goldsmith' ),
                            'price' => esc_html__( 'Price', 'goldsmith' ),
                            'cart' => esc_html__( 'Add to Cart', 'goldsmith' ),
                            'sale' => esc_html__( 'Sale', 'goldsmith' ),
                            'wishlist' => esc_html__( 'Wishlist', 'goldsmith' ),
                            'compare' => esc_html__( 'Compare', 'goldsmith' ),
                            'quickview' => esc_html__( 'Quick View', 'goldsmith' ),
                        ),
                        'hide'  => array(
                            'rating' => esc_html__( 'Stars Rating', 'goldsmith' ),
                            'swatches' => esc_html__( 'Swatches', 'goldsmith' ),
                            'discount' => esc_html__( 'Discount', 'goldsmith' ),
                            'stock' => esc_html__( 'Stock Status', 'goldsmith' )
                        )
                    ),
                    'required' => array('shop_product_type', '=', 'custom' )
                ),
                array(
                    'id' =>'shop_product_box_type',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Product Box Style', 'goldsmith' ),
                    'options' => array(
                        '1' => esc_html__( 'Style 1', 'goldsmith' ),
                        '2' => esc_html__( 'Style 2', 'goldsmith' )
                    ),
                    'default' => '2',
                    'required' => array('shop_product_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Swatches Display', 'goldsmith'),
                    'subtitle' => esc_html__('You can enable or disable the product swatches for variable prodcuts.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_swatches_visibility',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array('shop_product_type', '!=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Excerpt Display', 'goldsmith'),
                    'subtitle' => esc_html__('You can enable or disable the product swatches for variable prodcuts.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_swatches_visibility',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array('shop_product_type', '!=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Second Image ( On Hover Product )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_hover_image_visibility',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array('shop_product_type', '!=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Post Column', 'goldsmith'),
                    'subtitle' => esc_html__('You can control post column with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_colxxl',
                    'type' => 'slider',
                    'default' => 5,
                    'min' => 1,
                    'step' => 1,
                    'max' => 8,
                    'display_value' => 'text'
                ),
                array(
                    'title' => esc_html__('Post 1200px Column ( Responsive: Desktop, Tablet )', 'goldsmith'),
                    'subtitle' => esc_html__('You can control post column with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_colxl',
                    'type' => 'slider',
                    'default' => 4,
                    'min' => 1,
                    'step' => 1,
                    'max' => 6,
                    'display_value' => 'text'
                ),
                array(
                    'title' => esc_html__('Post 992px Column ( Responsive: Desktop, Tablet )', 'goldsmith'),
                    'subtitle' => esc_html__('You can control post column on max-device width 992px with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_collg',
                    'type' => 'slider',
                    'default' =>3,
                    'min' => 1,
                    'step' => 1,
                    'max' => 4,
                    'display_value' => 'text'
                ),
                array(
                    'title' => esc_html__('Post 768px Column ( Responsive: Tablet, Phone )', 'goldsmith'),
                    'subtitle' => esc_html__('You can control post column on max-device-width 768px with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_colsm',
                    'type' => 'slider',
                    'default' =>2,
                    'min' => 1,
                    'step' => 1,
                    'max' => 3,
                    'display_value' => 'text'
                ),
                array(
                    'title' => esc_html__('Post 480px Column ( Responsive: Phone )', 'goldsmith'),
                    'subtitle' => esc_html__('You can control post column on max-device-width 768px with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_colxs',
                    'type' => 'slider',
                    'default' =>2,
                    'min' => 1,
                    'step' => 1,
                    'max' => 2,
                    'display_value' => 'text'
                ),
                array(
                    'title' => esc_html__('Post Count for Per Page', 'goldsmith'),
                    'subtitle' => esc_html__('You can control show post count with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_perpage',
                    'type' => 'slider',
                    'default' => 10,
                    'min' => 1,
                    'step' => 1,
                    'max' => 100,
                    'display_value' => 'text'
                ),
                array(
                    'title' => esc_html__( 'Post Image Size', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_imgsize',
                    'type' => 'select',
                    'data' => 'image_sizes'
                ),
                array(
                    'title' => esc_html__( 'Excerpt Size (for Shop page list type)', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control blog post excerpt size with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_loop_excerpt_limit',
                    'type' => 'slider',
                    'default' => 17,
                    'min' => 0,
                    'step' => 1,
                    'max' => 300,
                    'display_value' => 'text'
                ),
                array(
                    'title' => esc_html__('Shop List Type Column for Per Row', 'goldsmith'),
                    'subtitle' => esc_html__('You can control post column with this option for shop list type.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_list_type_colxl',
                    'type' => 'slider',
                    'default' => 2,
                    'min' => 1,
                    'step' => 1,
                    'max' => 3,
                    'display_value' => 'text'
                )
            )
        ));
        function goldsmith_fast_filters_fields(){
            $filters_field = array();
            $filters_field[] = array(
                'title' => esc_html__('Fast Filters Display', 'goldsmith'),
                'subtitle' => esc_html__('You can enable or disable the site shop page fast filters section with switch option.', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_fast_filter_visibility',
                'type' => 'switch',
                'default' => 1
            );
            $filters_field[] = array(
                'id' =>'shop_fast_filter_main',
                'type' => 'sorter',
                'title' => esc_html__( 'Main Filters Layout Manager', 'goldsmith' ),
                'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme fast filters area', 'goldsmith' ),
                'options' => array(
                    'show' => array(
                        'featured' => esc_html__( 'Featured', 'goldsmith' ),
                        'bestseller' => esc_html__( 'Best Seller', 'goldsmith' ),
                        'toprated' => esc_html__( 'Top Rated', 'goldsmith' ),
                    ),
                    'hide'  => array()
                ),
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__( 'Featured Filter Title', 'goldsmith' ),
                'customizer' => true,
                'id' => 'shop_fast_filter_featured_title',
                'type' => 'text',
                'default' => '',
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__( 'Best Seller Filter Title', 'goldsmith' ),
                'customizer' => true,
                'id' => 'shop_fast_filter_bestseller_title',
                'type' => 'text',
                'default' => '',
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__( 'Top Rated Filter Title', 'goldsmith' ),
                'customizer' => true,
                'id' => 'shop_fast_filter_toprated_title',
                'type' => 'text',
                'default' => '',
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__( 'Select Attributes', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select category(s) from the list.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'shop_fast_filter_terms',
                'type' => 'select',
                'sortable'  => true,
                'options' => goldsmith_wc_attributes(),
                'multi' => true,
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );

            $attribute_taxonomies = wc_get_attribute_taxonomies();
            foreach ( $attribute_taxonomies as $tax ) {
                $options = array();
                if ( !empty($tax) ) {
                    $tax_name = $tax->attribute_name;
                    $tax_label = $tax->attribute_label;
                    if ( $tax_name ) {
                        $filters_field[] = array(
                            'title' => esc_html__( 'Terms Attributes for '.$tax_label, 'goldsmith' ),
                            'customizer' => true,
                            'id' => 'shop_fast_filter_terms_attr_'.$tax_name,
                            'type' => 'select',
                            'sortable'  => true,
                            'data'  => 'terms',
                            'args'  => array(
                                'taxonomies' => array( 'pa_'.$tax_name ),
                            ),
                            'multi' => true,
                            'required' => array( 'shop_fast_filter_terms', '=', $tax_name )
                        );
                        $filters_field[] = array(
                            'title' => esc_html__( 'Terms Title for '.$tax_label, 'goldsmith' ),
                            'customizer' => true,
                            'id' => 'shop_fast_filter_terms_title_'.$tax_name,
                            'type' => 'text',
                            'default' => 'Select '.$tax_label,
                            'required' => array( 'shop_fast_filter_terms', '=', $tax_name )
                        );
                    }
                }
            }

            $filters_field[] = array(
                'title' => esc_html__('Fast Filters Label Before Display', 'goldsmith'),
                'subtitle' => esc_html__('You can enable or disable the site shop page fast filters label before with switch option.', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_fast_filter_before_label_visibility',
                'type' => 'switch',
                'default' => 1,
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__( 'Fast Filters Label Before', 'goldsmith' ),
                'customizer' => true,
                'id' => 'shop_fast_filter_main_title',
                'type' => 'text',
                'default' => '',
                'required' => array(
                    array( 'shop_ajax_filter', '=', '1' ),
                    array( 'shop_fast_filter_before_label_visibility', '=', '1' )
                )
            );
            $filters_field[] = array(
                'title' => esc_html__( 'Remove All Filter Title', 'goldsmith' ),
                'customizer' => true,
                'id' => 'shop_fast_filter_remove_title',
                'type' => 'text',
                'default' => '',
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__( 'In Stock Filter Title', 'goldsmith' ),
                'customizer' => true,
                'id' => 'shop_fast_filter_instock_title',
                'type' => 'text',
                'default' => '',
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__( 'On Sale Filter Title', 'goldsmith' ),
                'customizer' => true,
                'id' => 'shop_fast_filter_onsale_title',
                'type' => 'text',
                'default' => '',
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Fast Filters Ajax Loading', 'goldsmith'),
                'subtitle' => esc_html__('You can enable or disable the site shop page fast filters ajax loading section with switch option.', 'goldsmith'),
                'customizer' => true,
                'id' => 'shop_fast_filter_ajax',
                'type' => 'switch',
                'default' => 1,
                'required' => array(
                    array( 'shop_ajax_filter', '=', '1' ),
                    array( 'shop_fast_filter_visibility', '=', '1' )
                )
            );
            $filters_field[] = array(
                'title' => esc_html__('In Stock & On Sale Filter Display Status', 'goldsmith'),
                'type' => 'button_set',
                'customizer' => true,
                'id' => 'shop_fast_filter_stock_sale_status',
                'options' => array(
                    'show-always' => esc_html__( 'Show Always', 'goldsmith' ),
                    'hidden' => esc_html__( 'Show After Filter', 'goldsmith' ),
                ),
                'default' => 'hidden',
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Fetaured Icon HTML', 'goldsmith'),
                'type' => 'textarea',
                'customizer' => true,
                'id' => 'shop_fast_filter_featured_icon',
                'default' => '',
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Best Seller Icon HTML', 'goldsmith'),
                'type' => 'textarea',
                'customizer' => true,
                'id' => 'shop_fast_filter_bestseller_icon',
                'default' => '',
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Top Rated Icon HTML', 'goldsmith'),
                'type' => 'textarea',
                'customizer' => true,
                'id' => 'shop_fast_filter_toprated_icon',
                'default' => '',
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Onsale Icon HTML', 'goldsmith'),
                'type' => 'textarea',
                'customizer' => true,
                'id' => 'shop_fast_filter_onsale_icon',
                'default' => '',
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            $filters_field[] = array(
                'title' => esc_html__('Instock Icon HTML', 'goldsmith'),
                'type' => 'textarea',
                'customizer' => true,
                'id' => 'shop_fast_filter_instock_icon',
                'default' => '',
                'required' => array( 'shop_fast_filter_visibility', '=', '1' )
            );
            foreach ( $attribute_taxonomies as $tax ) {
                $tax_name = $tax->attribute_name;
                $tax_label = $tax->attribute_label;

                $filters_field[] = array(
                    'title' => $tax_label.' '.esc_html__( 'Icon HTML ', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_terms_icon_'.$tax_name,
                    'type' => 'textarea',
                    'default' => '',
                    'required' => array( 'shop_fast_filter_terms', '=', $tax_name )
                );
            }

            return $filters_field;
        }

        // FAST FILTER SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Shop Fast Filters', 'goldsmith'),
            'id' => 'shopfastfilterstsubsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => goldsmith_fast_filters_fields()
        ));
        // FAST FILTER SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Shop Fast Filters Colors', 'goldsmith'),
            'id' => 'shopfastfilterstylesubsection',
            'subsection' => true,
            'icon' => 'el el-brush',
            'fields' => array(
                array(
                    'title' => esc_html__('Filters Label Before Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change fast filters label before color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_main_title_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .fast-filters-label')
                ),
                array(
                    'title' => esc_html__('Button Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li > a')
                ),
                array(
                    'title' => esc_html__('Button Background Color (Hover/Active)', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_hvrbgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:hover> a,.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.active > a')
                ),
                array(
                    'title' => esc_html__('Button Title Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li > a')
                ),
                array(
                    'title' => esc_html__('Button Title Color (Hover/Active)', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_hvrcolor',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:hover> a,.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.active > a')
                ),
                array(
                    'title' => esc_html__('Button Border Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_brdcolor',
                    'type' => 'color',
                    'mode' => 'border-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li > a')
                ),
                array(
                    'title' => esc_html__('Button Border Color (Hover/Active)', 'goldsmith'),
                    'subtitle' => esc_html__('Change post background color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_hvrbrdcolor',
                    'type' => 'color',
                    'mode' => 'border-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:hover> a,.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.active > a')
                ),
                array(
                    'title' => esc_html__('Button Close Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_close_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:not(.remove-fast-filter) .remove-filter')
                ),
                array(
                    'title' => esc_html__('Button Close Color (Hover/Active)', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_close_color',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:not(.remove-fast-filter) .remove-filter:before, .goldsmith-shop-fast-filters .goldsmith-fast-filters-list li:not(.remove-fast-filter) .remove-filter:after')
                ),
                array(
                    'title' => esc_html__('Clear All Button Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_clear_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list .remove-fast-filter.active > a')
                ),
                array(
                    'title' => esc_html__('Clear All Button Background Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_clear_hvrbgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list .remove-fast-filter.active:hover > a')
                ),
                array(
                    'title' => esc_html__('Clear All Button Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_clear_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list .remove-fast-filter.active > a')
                ),
                array(
                    'title' => esc_html__('Clear All Button Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_clear_hvrcolor',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list .remove-fast-filter.active:hover > a')
                ),
                array(
                    'title' => esc_html__('Clear All Button Close Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_clear_close_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.remove-fast-filter .remove-filter')
                ),
                array(
                    'title' => esc_html__('Clear All Button Close Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_fast_filter_btn_clear_close_color',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-shop-fast-filters .goldsmith-fast-filters-list li.remove-fast-filter .remove-filter:before, .goldsmith-fast-filters-list li.remove-fast-filter .remove-filter:after')
                ),
            )
        ));

        // SINGLE CONTENT SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Shop Elementor Template', 'goldsmith'),
            'id' => 'shopaftercontentsubsection',
            'subsection' => true,
            'icon' => 'el el-brush',
            'fields' => array(
                array(
                    'title' => esc_html__( 'Before Shop Content Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after hero section.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_before_content_templates',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                ),
                array(
                    'title' => esc_html__( 'After Shop Content Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after products.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_after_content_templates',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                ),
                array(
                    'title' => esc_html__( 'Before Loop Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content before products loop.Note:This option is only compatible with shop left sidebar and right sidebar layouts.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_before_loop_templates',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                ),
                array(
                    'title' => esc_html__( 'After Loop Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after products loop.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_after_loop_templates',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args,
                ),
                array(
                    'title' => esc_html__( 'Category Pages Before Loop Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content before products loop.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_category_pages_before_loop_templates',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                ),
                array(
                    'title' => esc_html__( 'Category Pages After Loop Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after products loop.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_category_pages_after_loop_templates',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args,
                ),
                array(
                    'title' => esc_html__( 'Tag Pages Before Loop Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content before products loop.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_tag_pages_before_loop_templates',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                ),
                array(
                    'title' => esc_html__( 'Tag Pages After Loop Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after products loop.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_tag_pages_after_loop_templates',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                ),
                array(
                    'title' => esc_html__( 'Category Pages Before Content Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content before products loop.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_category_pages_before_content_templates',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                ),
                array(
                    'title' => esc_html__( 'Category Pages After Content Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after products loop.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_category_pages_after_content_templates',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                ),
                array(
                    'title' => esc_html__( 'Tag Pages Before Content Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content before products loop.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_tag_pages_before_content_templates',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                ),
                array(
                    'title' => esc_html__( 'Tag Pages After Content Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after products loop.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_tag_pages_after_content_templates',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                )
            )
        ));
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Shop Page Post Style', 'goldsmith'),
            'id' => 'shoppoststylesubsection',
            'subsection' => true,
            'icon' => 'el el-brush',
            'fields' => array(
                array(
                    'title' => esc_html__('Background Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change post background color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_post_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.woocommerce.goldsmith-product-loop-inner')
                ),
                array(
                    'title' => esc_html__('Border', 'goldsmith'),
                    'subtitle' => esc_html__('Set your custom border styles for the posts.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_post_brd',
                    'type' => 'border',
                    'all' => false,
                    'output' => array('.woocommerce.goldsmith-product-loop-inner')
                ),
                array(
                    'title' => esc_html__('Padding', 'goldsmith'),
                    'subtitle' => esc_html__('You can set the spacing of the site shop page post.', 'goldsmith'),
                    'customizer' => true,
                    'id' =>'shop_post_pad',
                    'type' => 'spacing',
                    'output' => array('.woocommerce.goldsmith-product-loop-inner'),
                    'mode' => 'padding',
                    'units' => array('em', 'px'),
                    'units_extended' => 'false',
                    'default' => array(
                        'units' => 'px'
                    )
                ),
                // post button ( Add to cart )
                array(
                    'title' => esc_html__('Post title', 'goldsmith'),
                    'subtitle' => esc_html__('Change theme main color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_loop_post_title_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-product .goldsmith-product-name')
                ),
                array(
                    'title' => esc_html__('Price', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_loop_post_price_reg_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.woocommerce .goldsmith-price')
                ),
                array(
                    'title' => esc_html__('Price Regular', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_loop_post_price_reg_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.woocommerce .goldsmith-price del')
                ),
                array(
                    'title' => esc_html__('Price Sale', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_loop_post_price_sale_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.woocommerce .goldsmith-price ins')
                ),
                array(
                    'title' => esc_html__('Discount Background', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_loop_post_discount_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.woocommerce .goldsmith-label')
                ),
                // post button ( Add to cart )
                array(
                    'title' => esc_html__('Button Background ( Add to cart )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_addtocartbtn_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.woocommerce .goldsmith-btn')
                ),
                array(
                    'title' => esc_html__('Hover Button Background ( Add to cart )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_addtocartbtn_hvrbgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.woocommerce .goldsmith-btn:hover')
                ),
                array(
                    'title' => esc_html__('Button Title ( Add to cart )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_addtocartbtn_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.woocommerce .goldsmith-btn')
                ),
                array(
                    'title' => esc_html__('Hover Button Title ( Add to cart )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_addtocartbtn_hvrcolor',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.woocommerce .goldsmith-btn:hover')
                ),
                // post button ( view cart )
                array(
                    'title' => esc_html__('Button Background ( View cart )', 'goldsmith'),
                    'subtitle' => esc_html__('Change button background color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_viewcartbtn_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.woocommerce.goldsmith-product a.added_to_cart')
                ),
                array(
                    'title' => esc_html__('Hover Button Background ( View cart )', 'goldsmith'),
                    'subtitle' => esc_html__('Change button hover background color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_viewcartbtn_hvrbgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.woocommerce.goldsmith-product a.added_to_cart:hover')
                ),
                array(
                    'title' => esc_html__('Button Title ( View cart )', 'goldsmith'),
                    'subtitle' => esc_html__('Change button title color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_viewcartbtn_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.woocommerce.goldsmith-product a.added_to_cart')
                ),
                array(
                    'title' => esc_html__('Hover Button Title ( View cart )', 'goldsmith'),
                    'subtitle' => esc_html__('Change button hover title color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_viewcartbtn_hvrcolor',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.woocommerce.goldsmith-product a.added_to_cart')
                ),
                array(
                    'title' => esc_html__('Button Border ( View cart )', 'goldsmith'),
                    'subtitle' => esc_html__('Change hover button border style.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_viewcartbtn_brd',
                    'type' => 'border',
                    'output' => array('.woocommerce.goldsmith-product a.added_to_cart')
                ),
                array(
                    'title' => esc_html__('Hover Button Border ( View cart )', 'goldsmith'),
                    'subtitle' => esc_html__('Change hover button border style.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_viewcartbtn_hvrbrd',
                    'type' => 'border',
                    'output' => array('.woocommerce.goldsmith-product a.added_to_cart:hover')
                ),
                array(
                    'title' => esc_html__('Pagination Background Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change shop page pagination background color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_pagination_bgcolor',
                    'type' => 'color',
                    'mode' => 'background',
                    'default' => '',
                    'output' => array('.woocommerce nav.goldsmith-woocommerce-pagination ul li a, .woocommerce nav.goldsmith-woocommerce-pagination ul li span')
                ),
                array(
                    'title' => esc_html__('Active Pagination Background Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change shop page pagination hover and active item background color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_pagination_hvrbgcolor',
                    'type' => 'color',
                    'mode' => 'background',
                    'default' => '',
                    'output' => array('.woocommerce nav.goldsmith-woocommerce-pagination ul li a:focus, .woocommerce nav.goldsmith-woocommerce-pagination ul li a:hover, .woocommerce nav.goldsmith-woocommerce-pagination ul li span.current')
                ),
                array(
                    'title' => esc_html__('Pagination Text Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change shop page pagination text color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_pagination_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.woocommerce nav.goldsmith-woocommerce-pagination ul li a, .woocommerce nav.goldsmith-woocommerce-pagination ul li span')
                ),
                array(
                    'title' => esc_html__('Active Pagination Text Color', 'goldsmith'),
                    'subtitle' => esc_html__('Change shop page pagination hover and active item text color.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_pagination_hvrcolor',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.woocommerce nav.goldsmith-woocommerce-pagination ul li a:focus, .woocommerce nav.goldsmith-woocommerce-pagination ul li a:hover, .woocommerce nav.goldsmith-woocommerce-pagination ul li span.current')
                )
            )
        ));

        /*************************************************
        ## SINGLE PAGE SECTION
        *************************************************/
        // create sections in the theme options
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('PRODUCT PAGE', 'goldsmith'),
            'id' => 'singleshopsection',
            'icon' => 'el el-shopping-cart-sign',
            'fields' => array()
        ));
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Layout', 'goldsmith'),
            'id' => 'single_shop_layout_general_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'id' =>'single_shop_layout',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Page Sidebar Layouts', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop page sidebar area.', 'goldsmith' ),
                    'options' => array(
                        'left-sidebar' => esc_html__( 'Left Sidebar', 'goldsmith' ),
                        'right-sidebar' => esc_html__( 'Right Sidebar', 'goldsmith' ),
                        'full-width' => esc_html__( 'Fullwidth ( no-sidebar )', 'goldsmith' ),
                        'stretch' => esc_html__( 'Fullwidth ( stretch )', 'goldsmith' ),
                        'showcase' => esc_html__( 'Showcase Carousel', 'goldsmith' )
                    ),
                    'default' => 'full-width'
                ),
                array(
                    'id' =>'product_thumbs_layout',
                    'type' => 'button_set',
                    'title' => esc_html__('Gallery Type', 'goldsmith'),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop product page tumbs.', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'grid' => esc_html__( 'Grid', 'goldsmith' ),
                        'slider' => esc_html__( 'Slider', 'goldsmith' ),
                        'woo' => esc_html__( 'WooCommerce Default', 'goldsmith' ),
                    ),
                    'default' => 'slider',
                    'required' => array(
                        array( 'single_shop_layout', '!=', 'showcase' ),
                        array( 'single_shop_layout', '!=', 'stretch' ),
                    )
                ),
                array(
                    'id' =>'product_gallery_position',
                    'type' => 'button_set',
                    'title' => esc_html__('Slider Gallery Position', 'goldsmith'),
                    'customizer' => true,
                    'options' => array(
                        'left' => esc_html__( 'Left', 'goldsmith' ),
                        'right' => esc_html__( 'Right', 'goldsmith' ),
                    ),
                    'default' => 'right',
                    'required' => array( 'single_shop_layout', '=', 'stretch' )
                ),
                array(
                    'title' => esc_html__('Thumbs Column Width', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_thumbs_column_width',
                    'type' => 'spinner',
                    'default' => '7',
                    'min' => '1',
                    'step' => '1',
                    'max' => '12',
                    'required' => array(
                        array( 'single_shop_layout', '!=', 'showcase' ),
                        array( 'single_shop_layout', '!=', 'stretch' ),
                    )
                ),
                array(
                    'id' =>'goldsmith_product_gallery_grid_column',
                    'type' => 'button_set',
                    'title' => esc_html__('Grid Column', 'goldsmith'),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop product page tumbs.', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        '1' => esc_html__( '1 Column', 'goldsmith' ),
                        '2' => esc_html__( '2 Column', 'goldsmith' ),
                        '3' => esc_html__( '3 Column', 'goldsmith' ),
                        '4' => esc_html__( '4 Column', 'goldsmith' ),
                    ),
                    'default' => '2',
                    'required' => array(
                        array( 'single_shop_layout', '!=', 'showcase' ),
                        array( 'single_shop_layout', '!=', 'stretch' ),
                        array( 'product_thumbs_layout', '=', 'grid' )
                    )
                ),
                array(
                    'id' =>'product_gallery_thumb_position',
                    'type' => 'button_set',
                    'title' => esc_html__('Slider Thumbs Position', 'goldsmith'),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop product page tumbs.', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'top' => esc_html__( 'Horizontal Top', 'goldsmith' ),
                        'bottom' => esc_html__( 'Horizontal Bottom', 'goldsmith' ),
                        'left' => esc_html__( 'Vertical Left', 'goldsmith' ),
                        'right' => esc_html__( 'Vertical Right', 'goldsmith' ),
                    ),
                    'default' => 'left',
                    'required' => array(
                        array( 'single_shop_layout', '!=', 'showcase' ),
                        array( 'product_thumbs_layout', '=', 'slider' )
                    )
                ),
                array(
                    'id' =>'gallery_slider_imgsize',
                    'type' => 'button_set',
                    'title' => esc_html__('Slider Gallery Image Width', 'goldsmith'),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop product page gallery slider.', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'auto' => esc_html__( 'Auto', 'goldsmith' ),
                        'full' => esc_html__( 'Full (100%)', 'goldsmith' )
                    ),
                    'default' => 'full',
                    'required' => array( 'product_thumbs_layout', '=', 'slider' )
                ),
                array(
                    'id' => 'product_gallery_imgsize',
                    'title' => esc_html__( 'Custom Product Gallery Image Size', 'goldsmith' ),
                    'subtitle' => esc_html__( '! Important: Cropping images will not work if the width of your images is less than the entered value.', 'goldsmith' ),
                    'desc' => esc_html__( 'Default: 980', 'goldsmith' ),
                    'placeholder' => esc_html__( '980', 'goldsmith' ),
                    'default' => 980,
                    'customizer' => true,
                    'type' => 'text',
                    'validate' => array( 'numeric' )
                ),
                array(
                    'id' =>'gallery_thumb_imgsize',
                    'type' => 'button_set',
                    'title' => esc_html__('Slider Thumbs Image Size', 'goldsmith'),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop product page tumbs.', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'thumb' => esc_html__( 'Thumbnail', 'goldsmith' ),
                        'full' => esc_html__( 'Full', 'goldsmith' )
                    ),
                    'default' => 'full',
                    'required' => array( 'product_thumbs_layout', '=', 'slider' )
                ),
                array(
                    'id' =>'single_shop_showcase_carousel_width',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Carousel Container Width', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select theme shop page showcase type.', 'goldsmith' ),
                    'options' => array(
                        'boxed' => esc_html__( 'Boxed', 'goldsmith' ),
                        'fullwidth' => esc_html__( 'Fullwidth', 'goldsmith' ),
                    ),
                    'default' => 'fullwidth',
                    'required' => array( 'single_shop_layout', '=', 'showcase' )
                ),
                array(
                    'id' => 'single_shop_showcase_carousel_count',
                    'title' => esc_html__('Carousel Items Count', 'goldsmith'),
                    'customizer' => true,
                    'type' => 'spinner',
                    'default' => '4',
                    'min' => '1',
                    'step' => '1',
                    'max' => '10',
                    'required' => array( 'single_shop_layout', '=', 'showcase' )
                ),
                array(
                    'id' => 'single_shop_showcase_carousel_loop',
                    'title' => esc_html__('Loop ( Infinite )', 'goldsmith'),
                    'customizer' => true,
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array( 'single_shop_layout', '=', 'showcase' )
                ),
                array(
                    'id' => 'single_shop_showcase_carousel_thumbs',
                    'title' => esc_html__('Show Thumbnails', 'goldsmith'),
                    'customizer' => true,
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'single_shop_layout', '=', 'showcase' )
                ),
                array(
                    'id' => 'single_shop_showcase_carousel_mobile_thumbs',
                    'title' => esc_html__('Show Thumbnails on Mobile', 'goldsmith'),
                    'customizer' => true,
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_layout', '=', 'showcase' ),
                        array( 'single_shop_showcase_carousel_thumbs', '=', '0' )
                    )
                ),
                array(
                    'id' => 'single_shop_showcase_carousel_dots',
                    'title' => esc_html__('Show Dots', 'goldsmith'),
                    'customizer' => true,
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_layout', '=', 'showcase' ),
                        array( 'single_shop_showcase_carousel_thumbs', '=', '0' )
                    )
                ),
                array(
                    'id' =>'single_shop_showcase_carousel_effect_type',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Carousel Effect Type', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select theme shop page showcase carousel type effect.', 'goldsmith' ),
                    'options' => array(
                        'slide' => esc_html__( 'Default', 'goldsmith' ),
                        'coverflow' => esc_html__( 'Coverflow', 'goldsmith' ),
                    ),
                    'default' => 'slide',
                    'required' => array( 'single_shop_layout', '=', 'showcase' )
                ),
                array(
                    'title' => esc_html__( 'Coverflow Rotate', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Set the rotate of the coverflow effect.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_showcase_carousel_coverflow_rotate',
                    'type' => 'slider',
                    'default' => 30,
                    'min' => -90,
                    'step' => 1,
                    'max' => 90,
                    'required' => array(
                        array( 'single_shop_layout', '=', 'showcase' ),
                        array( 'single_shop_showcase_carousel_effect_type', '=', 'coverflow' ),
                    )
                ),
                array(
                    'id' =>'single_shop_showcase_bg_type',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Showcase BG Style', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select theme shop page showcase background style.', 'goldsmith' ),
                    'options' => array(
                        'light' => esc_html__( 'Light', 'goldsmith' ),
                        'dark' => esc_html__( 'Dark', 'goldsmith' ),
                        'custom' => esc_html__( 'Custom Color', 'goldsmith' ),
                    ),
                    'default' => 'light',
                    'required' => array( 'single_shop_layout', '=', 'showcase' )
                ),
                array(
                    'title' => esc_html__('Showcase Background', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_showcase_custom_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-showcase.goldsmith-bg-custom' ),
                    'required' => array(
                        array( 'single_shop_layout', '=', 'showcase' ),
                        array( 'single_shop_showcase_bg_type', '=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__('Showcase Text Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_showcase_custom_textcolor',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-showcase.goldsmith-bg-custom' ),
                    'required' => array(
                        array( 'single_shop_layout', '=', 'showcase' ),
                        array( 'single_shop_showcase_bg_type', '=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__('Showcase Content Padding', 'goldsmith'),
                    'subtitle' => esc_html__('You can set the spacing of the site single page showcase content.', 'goldsmith'),
                    'customizer' => true,
                    'id' =>'single_shop_showcase_content_pad',
                    'type' => 'spacing',
                    'output' => array('.product .goldsmith-product-showcase.goldsmith-bg-custom'),
                    'mode' => 'padding',
                    'units' => array('em', 'px'),
                    'units_extended' => 'false',
                    'required' => array( 'single_shop_layout', '=', 'showcase' )
                ),
                array(
                    'title' => esc_html__('Gallery Zoom Effect', 'goldsmith'),
                    'subtitle' => esc_html__('You can enable or disable the site product image zoom option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'goldsmith_product_zoom',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__( 'Product Custom Container Width', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_custom_container_width',
                    'default' => '',
                    'min' => 0,
                    'step' => 1,
                    'max' => 4000,
                    'type' => 'slider',
                    'required' => array( 'shop_container_width', '=', 'custom' )
                )
            )
        ));
        // SINGLE HERO SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Product Header', 'goldsmith'),
            'desc' => esc_html__('These are shop product page header section settings', 'goldsmith'),
            'id' => 'single_shop_header_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Use Different Header Layouts', 'goldsmith'),
                    'subtitle' => esc_html__('You can use different header layouts type on shop product pages.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_different_header_layouts',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'id' =>'single_shop_header_layouts',
                    'type' => 'sorter',
                    'title' => esc_html__( 'Shop Header Layout Manager', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme header', 'goldsmith' ),
                    'options' => array(
                        'left' => array(
                            'sidemenu' => esc_html__( 'Sidemenu Toggle', 'goldsmith' ),
                            'logo' => esc_html__( 'Logo', 'goldsmith' )
                        ),
                        'center'=> array(
                            'menu' => esc_html__( 'Main Menu', 'goldsmith' )
                        ),
                        'right'=> array(
                            'search' => esc_html__( 'Search', 'goldsmith' ),
                            'buttons' => esc_html__( 'Buttons', 'goldsmith' )
                        ),
                        'hide'  => array(
                            'center-logo' => esc_html__( 'Menu Logo Menu', 'goldsmith' ),
                            'mini-menu' => esc_html__( 'Mini Menu', 'goldsmith' ),
                            'double-menu' => esc_html__( 'Double Menu', 'goldsmith' ),
                            'custom-html' => esc_html__( 'Phone Number', 'goldsmith' )
                        )
                    ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_layouts', '=', '1' )
                    )
                ),
                array(
                    'id' =>'single_shop_header_buttons_layouts',
                    'type' => 'sorter',
                    'title' => esc_html__( 'Shop Header Buttons Manager', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme header for buttons', 'goldsmith' ),
                    'options' => array(
                        'show'  => array(
                            'cart' => esc_html__( 'Cart', 'goldsmith' ),
                            'wishlist' => esc_html__( 'Wishlist', 'goldsmith' ),
                            'compare' => esc_html__( 'Compare', 'goldsmith' ),
                            'account' => esc_html__( 'Account', 'goldsmith' )
                        ),
                        'hide'  => array(
                            'search' => esc_html__( 'Search', 'goldsmith' )
                        )
                    ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_layouts', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__('Use Different Header Background Type', 'goldsmith'),
                    'subtitle' => esc_html__('You can use different header background type on product page.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_different_header_bg_type',
                    'type' => 'switch',
                    'default' => 0
                ),
                array(
                    'id' => 'single_shop_header_menu_items_customize_start',
                    'type' => 'section',
                    'title' => esc_html__('Header Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'id' =>'single_shop_header_bg_type',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Header Background Type', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'default' => esc_html__( 'Deafult', 'goldsmith' ),
                        'dark' => esc_html__( 'Dark', 'goldsmith' ),
                        'trans-light' => esc_html__( 'Transparent Light', 'goldsmith' ),
                        'trans-dark' => esc_html__( 'Transparent Dark', 'goldsmith' )
                    ),
                    'default' => 'default',
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Header Background Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_header_bg',
                    'type' => 'color_rgba',
                    'mode' => 'background-color',
                    'output' => array( '.single.single-product header.goldsmith-header-default,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Menu Item Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the navigation menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_nav_a',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.single.single-product .goldsmith-header-top-menu-area>ul>li.menu-item>a,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .goldsmith-header-top-menu-area>ul>li.menu-item>a' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Menu Item Color ( Hover and Active )', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the navigation menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_nav_hvr_a',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.single.single-product .current-menu-parent>a,.single.single-product .current-menu-item>a,.single.single-product .goldsmith-header-top-menu-area>ul>li.menu-item>a:hover,.single.single-product .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .current-menu-parent>a,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .current-menu-item>a,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .goldsmith-header-top-menu-area>ul>li.menu-item>a:hover,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover'),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sticky Header Background Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_nav_top_sticky_bg',
                    'type' => 'color_rgba',
                    'mode' => 'background-color',
                    'output' => array( '.single.single-product.has-sticky-header.scroll-start header.goldsmith-header-default' ),
                    'required' => array( 'header_sticky_visibility', '=', '1' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sticky Menu Item Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the sticky navigation menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_sticky_nav_a',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.single.single-product.has-sticky-header.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item>a' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sticky Menu Item Color ( Hover and Active )', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the sticky navigation menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_sticky_nav_hvr_a',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.single.single-product.has-sticky-header.scroll-start .current-menu-parent>a, .single.single-product.has-sticky-header.scroll-start .current-menu-item>a, .single.single-product.has-sticky-header.scroll-start .goldsmith-header-top-menu-area>ul>li.menu-item>a:hover, .single.single-product.has-sticky-header.scroll-start .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'id' => 'single_shop_header_menu_items_style_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'id' => 'single_shop_header_submenu_items_style_start',
                    'type' => 'section',
                    'title' => esc_html__('Header Sub Menu Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sub Menu Background Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_nav_submenu_bg',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'validate' => 'color',
                    'output' => array( '.single.single-product .goldsmith-header-top-menu-area ul li .submenu' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Menu Item Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_nav_submenu_a',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.single.single-product .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Menu Item Color ( Hover and Active )', 'goldsmith' ),
                    'desc' => esc_html__( 'Set your own hover color for the sticky navigation sub menu item.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_nav_submenu_hvr_a',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.single.single-product .goldsmith-header-top-menu-area ul li .submenu>li.menu-item>a:hover,.single.single-product .goldsmith-header-top-menu-area ul li .submenu>li.menu-item.active>a' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'id' => 'single_shop_header_submenu_items_style_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'id' => 'single_shop_header_svgbuttons_items_style_start',
                    'type' => 'section',
                    'title' => esc_html__('Header SVG Buttons Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'SVG Icon Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Cart, Wishlist, Compare, Account, Search, Sidemenu bar', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_header_buttons_svg_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'validate' => 'color',
                    'output' => array( '.single.single-product .goldsmith-header-default .header-top-buttons .top-action-btn,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .header-top-buttons .top-action-btn' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Button Counter Background Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Cart, Wishlist, Compare', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_header_buttons_counter_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'validate' => 'color',
                    'output' => array( '.single.single-product .goldsmith-header-default .goldsmith-wc-count,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .goldsmith-wc-count' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Button Counter Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Cart, Wishlist, Compare', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_header_buttons_counter_color',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.single.single-product .goldsmith-header-default .goldsmith-wc-count,.single.single-product.has-default-header-type-trans:not(.scroll-start) header.goldsmith-header-default .goldsmith-wc-count' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sticky Header SVG Icon Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Cart, Wishlist, Compare, Account, Search, Sidemenu bar', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_sticky_header_buttons_svg_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'validate' => 'color',
                    'output' => array( '.single.single-product.scroll-start .goldsmith-header-default .header-top-buttons .top-action-btn,.single.single-product.has-default-header-type-trans.scroll-start header.goldsmith-header-default .header-top-buttons .top-action-btn' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sticky Header Button Counter Background Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Cart, Wishlist, Compare', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_sticky_header_buttons_counter_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'validate' => 'color',
                    'output' => array( '.single.single-product.scroll-start .goldsmith-header-default .goldsmith-wc-count,.single.single-product.has-default-header-type-trans.scroll-start header.goldsmith-header-default .goldsmith-wc-count' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Sticky Header Button Counter Color', 'goldsmith' ),
                    'desc' => esc_html__( 'Cart, Wishlist, Compare', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_sticky_header_buttons_counter_color',
                    'type' => 'color',
                    'validate' => 'color',
                    'output' => array( '.single.single-product.scroll-start .goldsmith-header-default .goldsmith-wc-count,.single.single-product.has-default-header-type-trans.scroll-start header.goldsmith-header-default .goldsmith-wc-count' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                ),
                array(
                    'id' => 'single_shop_header_svgbuttons_items_style_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'single_shop_different_header_bg_type', '=', '1' )
                    )
                )
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Summary Layout', 'goldsmith'),
            'id' => 'single_shop_summary_layout_type_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'id' =>'single_shop_summary_layout_type',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Product Summary Layouts', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'default' => esc_html__( 'Default', 'goldsmith' ),
                        'custom' => esc_html__( 'Custom Layout', 'goldsmith' )
                    ),
                    'default' => 'default'
                ),
                array(
                    'id' =>'single_shop_summary_layouts',
                    'type' => 'sorter',
                    'title' => esc_html__( 'Product Summary Layouts Manager', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme product page summary area.', 'goldsmith' ),
                    'options' => array(
                        'show' => array(
                            'bread' => esc_html__( 'Breadcrumbs', 'goldsmith' ),
                            'title' => esc_html__( 'Title', 'goldsmith' ),
                            'rating' => esc_html__( 'Rating', 'goldsmith' ),
                            'labels' => esc_html__( 'Labels', 'goldsmith' ),
                            'price' => esc_html__( 'Price', 'goldsmith' ),
                            'excerpt' => esc_html__( 'Excerpt', 'goldsmith' ),
                            'cart' => esc_html__( 'Cart', 'goldsmith' ),
                            'meta' => esc_html__( 'Meta', 'goldsmith' ),
                            'visitors-message' => esc_html__( 'Visitors Message', 'goldsmith' ),
                            'trust-badge' => esc_html__( 'Trusted Badge', 'goldsmith' ),
                            'timer' => esc_html__( 'Countdown', 'goldsmith' ),
                            'progressbar' => esc_html__( 'Progressbar', 'goldsmith' ),
                            'extra' => esc_html__( 'Extra HTML', 'goldsmith' ),
                        ),
                        'hide' => array()
                    ),
                    'required' => array( 'single_shop_summary_layout_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__( 'Extra HTML', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'goldsmith_product_extra_html',
                    'type' => 'editor',
                    'args' => array(
                        'teeny' => false,
                        'textarea_rows' => 10
                    ),
                    'required' => array( 'single_shop_summary_layout_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__( 'Sticky Summary', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_sticky_summary',
                    'type' => 'switch',
                    'default' => 0
                )
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Products Labels', 'goldsmith'),
            'id' => 'single_shop_labels_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Products Labels', 'goldsmith'),
                    'subtitle' => esc_html__('Sale, Stock, Discount', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_labels_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Sale Label Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_labels_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-labels .goldsmith-label' ),
                    'required' => array( 'single_shop_labels_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Sale Label Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_labels_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-labels .goldsmith-label' ),
                    'required' => array( 'single_shop_labels_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Discount Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_discount_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-labels .goldsmith-discount' ),
                    'required' => array( 'single_shop_labels_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Discount Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_discount_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-labels .goldsmith-discount' ),
                    'required' => array( 'single_shop_labels_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Stock Status Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_stock_status_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-summary-item.goldsmith-price p.stock.goldsmith-stock-status' ),
                    'required' => array( 'single_shop_labels_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Stock Status Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_stock_status_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-summary-item.goldsmith-price p.stock.goldsmith-stock-status' ),
                    'required' => array( 'single_shop_labels_visibility', '=', '1' )
                ),
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Product Title', 'goldsmith'),
            'id' => 'single_shop_title_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__( 'Title Typography', 'goldsmith' ),
                    'id' => 'product_title_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-product-summary .goldsmith-product-title' ),
                ),
                array(
                    'title' => esc_html__( 'Title Tag', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_title_tag',
                    'type' => 'select',
                    'options' => array(
                        '' => esc_html__( 'Select type', 'goldsmith' ),
                        'h1' => esc_html__( 'H1', 'goldsmith' ),
                        'h2' => esc_html__( 'H2', 'goldsmith' ),
                        'h3' => esc_html__( 'H3', 'goldsmith' ),
                        'h4' => esc_html__( 'H4', 'goldsmith' ),
                        'h5' => esc_html__( 'H5', 'goldsmith' ),
                        'h6' => esc_html__( 'H6', 'goldsmith' ),
                        'p' => esc_html__( 'p', 'goldsmith' ),
                        'div' => esc_html__( 'div', 'goldsmith' ),
                        'span' => esc_html__( 'span', 'goldsmith' )
                    ),
                    'default' => 'h2'
                )
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Product Excerpt', 'goldsmith'),
            'id' => 'single_shop_excerpt_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__( 'Excerpt Typography', 'goldsmith' ),
                    'id' => 'product_title_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item .woocommerce-product-details__short-description p' ),
                )
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Product Add To Cart', 'goldsmith'),
            'id' => 'single_shop_cart_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__( 'Ajax Add to Cart', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_ajax_cart',
                    'on' => esc_html__( 'Yes', 'goldsmith' ),
                    'off' => esc_html__( 'No', 'goldsmith' ),
                    'type' => 'switch',
                    'customizer' => true,
                    'default' => 1,
                ),
                array(
                    'title' => esc_html__('Cart Container Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_container_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-product-info' )
                ),
                array(
                    'title' => esc_html__( 'Cart Container Border', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_cart_container_border',
                    'type' => 'border',
                    'output' => array('.goldsmith-product-summary .goldsmith-product-info' )
                ),
                array(
                    'title' => esc_html__( 'Add To Cart Button Typography', 'goldsmith' ),
                    'id' => 'product_cart_btn_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item form.cart .single_add_to_cart_button' )
                ),
                array(
                    'title' => esc_html__('Cart Button Title Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_btn_title_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item form.cart .single_add_to_cart_button' )
                ),
                array(
                    'title' => esc_html__('Cart Button Title Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_btn_title_hvrcolor',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item form.cart .single_add_to_cart_button:hover' )
                ),
                array(
                    'title' => esc_html__('Cart Button Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_btn_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item form.cart .single_add_to_cart_button' )
                ),
                array(
                    'title' => esc_html__('Cart Button Background Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_btn_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item form.cart .single_add_to_cart_button:hover' )
                ),
                array(
                    'title' => esc_html__( 'Cart Button Border', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_cart_btn_border',
                    'type' => 'border',
                    'output' => array('.goldsmith-product-summary .goldsmith-summary-item form.cart .single_add_to_cart_button' )
                ),
                array(
                    'title' => esc_html__( 'Cart Button Border ( Hover )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_cart_btn_hvrborder',
                    'type' => 'border',
                    'output' => array('.goldsmith-product-summary .goldsmith-summary-item form.cart .single_add_to_cart_button:hover' )
                ),
                array(
                    'title' => esc_html__('Wishlist & Compare Icon Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_svg_icon_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item .goldsmith-product-button .goldsmith-svg-icon' )
                ),
                array(
                    'title' => esc_html__('Wishlist & Compare Icon Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_svg_icon_hvrcolor',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item .goldsmith-product-button:hover .goldsmith-svg-icon' )
                ),
                array(
                    'title' => esc_html__('Wishlist & Compare Hint Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_svg_icon_hint_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item .goldsmith-product-hint' )
                ),
                array(
                    'title' => esc_html__('Wishlist & Compare Hint Text Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_svg_icon_hint_text_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item .goldsmith-product-hint' )
                ),
                array(
                    'title' => esc_html__('Cart Info Bottom ( Border Top Color )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_info_bordertop',
                    'type' => 'color',
                    'mode' => 'border-top-color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item .goldsmith-product-info-bottom' )
                ),
                array(
                    'title' => esc_html__('Cart Info Bottom Delivery ( Text Color )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_info_delivery_text_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item .goldsmith-product-info-bottom .info-message strong' )
                ),
                array(
                    'title' => esc_html__('Cart Info Bottom Delivery ( Icon Color )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_info_delivery_icon_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item .goldsmith-product-info-bottom .shipping-class svg' )
                ),
                array(
                    'title' => esc_html__('Cart Info Bottom Delivery ( Message Color )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_info_delivery_message_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item .goldsmith-product-info-bottom .info-message' )
                ),
            )
        ));
        // Popup Notices SUBSECTION
        Redux::setSection($goldsmith_pre,
            array(
                'title' => esc_html__('Wishlist/Compare', 'goldsmith'),
                'id' => 'product_btns_subsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Wishlist Display', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_action_wishlist_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__('Compare Display', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_action_compare_visibility',
                        'type' => 'switch',
                        'default' => 1
                    ),
                    array(
                        'title' => esc_html__( 'Button Type', 'goldsmith' ),
                        'id' =>'product_action_btntype',
                        'type' => 'button_set',
                        'mutiple' => false,
                        'options' => array(
                            'icon' => esc_html__( 'Icon', 'goldsmith' ),
                            'btn' => esc_html__( 'Button', 'goldsmith' ),
                        ),
                        'default' => 'icon'
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_product_action_btntype_bg',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.goldsmith-product-summary .product-after-cart-wrapper .goldsmith-product-button' )
                    ),
                    array(
                        'title' => esc_html__('Hover Background Color', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_product_action_btntype_hvrbg',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'output' => array( '.goldsmith-product-summary .product-after-cart-wrapper .goldsmith-product-button:hover' )
                    ),
                    array(
                        'title' => esc_html__('Icon Color', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_product_action_btntype_icon_clr',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.goldsmith-product-summary .product-after-cart-wrapper .goldsmith-product-button svg' )
                    ),
                    array(
                        'title' => esc_html__('Hover Icon Color', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_product_action_btntype_icon_hvrclr',
                        'type' => 'color',
                        'mode' => 'fill',
                        'output' => array( '.goldsmith-product-summary .product-after-cart-wrapper .goldsmith-product-button:hover svg' )
                    ),
                    array(
                        'title' => esc_html__('Text Color ( Button Type )', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_product_action_btntype_text_clr',
                        'type' => 'color',
                        'output' => array( '.goldsmith-product-summary .product-after-cart-wrapper .goldsmith-product-button' )
                    ),
                    array(
                        'title' => esc_html__('Hover Text Color ( Button Type )', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_product_action_btntype_text_hvrclr',
                        'type' => 'color',
                        'output' => array( '.goldsmith-product-summary .product-after-cart-wrapper .goldsmith-product-button:hover' )
                    ),
                )
            )
        );
        // Popup Notices SUBSECTION
        Redux::setSection($goldsmith_pre,
            array(
                'title' => esc_html__('Buy Now Button', 'goldsmith'),
                'id' => 'shopbuynowsubsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Buy Now Button Display', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'buy_now_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__( 'Button Text', 'goldsmith' ),
                        'subtitle' => esc_html__('Leave blank to use the default text or its equivalent translation in multiple languages.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'buy_now_btn_title',
                        'type' => 'text',
                        'default' => '',
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Reset Cart', 'goldsmith'),
                        'subtitle' => esc_html__('Reset the cart before doing buy now.', 'goldsmith'),
                        'on' => esc_html__('Yes', 'goldsmith'),
                        'off' => esc_html__('No', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'buy_now_reset_cart',
                        'type' => 'switch',
                        'default' => 0,
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Parameter', 'goldsmith' ),
                        'customizer' => true,
                        'id' => 'buy_now_param',
                        'type' => 'text',
                        'default' => 'ninetheme-buy-now',
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'id' =>'buy_now_redirect',
                        'type' => 'button_set',
                        'title' => esc_html__( 'Redirect to', 'goldsmith' ),
                        'options' => array(
                            'checkout' => esc_html__( 'Checkout page', 'goldsmith' ),
                            'cart' => esc_html__( 'Cart page', 'goldsmith' ),
                            'custom' => esc_html__( 'Custom', 'goldsmith' ),
                        ),
                        'default' => 'checkout',
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Custom Page', 'goldsmith' ),
                        'customizer' => true,
                        'id' => 'buy_now_redirect_custom',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'buy_now_visibility', '=', '1' ),
                            array( 'buy_now_redirect', '=', 'custom' ),
                        )
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'goldsmith'),
                        'subtitle' => esc_html__('Change button background color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'buy_now_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.ninetheme-btn.ninetheme-btn-buynow'),
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color ( Hover )', 'goldsmith'),
                        'subtitle' => esc_html__('Change button hover background color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'buy_now_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.ninetheme-btn.ninetheme-btn-buynow:hover'),
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color', 'goldsmith'),
                        'subtitle' => esc_html__('Change button text color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'buy_now_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.ninetheme-btn.ninetheme-btn-buynow'),
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color ( Hover )', 'goldsmith'),
                        'subtitle' => esc_html__('Change button hover text color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'buy_now_hvrcolor',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.ninetheme-btn.ninetheme-btn-buynow:hover'),
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Border Color', 'goldsmith'),
                        'subtitle' => esc_html__('Change button border color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'buy_now_brdcolor',
                        'type' => 'border',
                        'all' => true,
                        'default' => '',
                        'output' => array('.ninetheme-btn.ninetheme-btn-buynow'),
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Border Color ( Hover )', 'goldsmith'),
                        'subtitle' => esc_html__('Change button border color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'buy_now_hvrbrdcolor',
                        'type' => 'border',
                        'all' => true,
                        'default' => '',
                        'output' => array('.ninetheme-btn.ninetheme-btn-buynow:hover'),
                        'required' => array( 'buy_now_visibility', '=', '1' )
                    ),
                )
            )
        );
        // Popup Notices SUBSECTION
        Redux::setSection($goldsmith_pre,
            array(
                'title' => esc_html__('Product Custom Button', 'goldsmith'),
                'id' => 'shop_product_custom_subsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Product Page Custom Button Display', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'title' => esc_html__( 'Button Text', 'goldsmith' ),
                        'subtitle' => esc_html__('Leave blank to use the default text or its equivalent translation in multiple languages.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_title',
                        'type' => 'text',
                        'default' => 'Request Information',
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'id' =>'product_custom_btn_action',
                        'type' => 'select',
                        'title' => esc_html__( 'Action', 'goldsmith' ),
                        'options' => array(
                            'link' => esc_html__( 'Custom Link', 'goldsmith' ),
                            'form' => esc_html__( 'Open Popup Form', 'goldsmith' ),
                            'whatsapp' => esc_html__( 'Open Whatsapp', 'goldsmith' ),
                        ),
                        'default' => 'link',
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__( 'Custom Link', 'goldsmith' ),
                        'customizer' => true,
                        'id' => 'product_custom_btn_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'product_custom_btn_visibility', '=', '1' ),
                            array( 'product_custom_btn_action', '=', 'link' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Form Shortcode or Custom HTML', 'goldsmith' ),
                        'subtitle' => esc_html__('Add your form shortcode here.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_form_shortcode',
                        'type' => 'textarea',
                        'default' => '',
                        'required' => array(
                            array( 'product_custom_btn_visibility', '=', '1' ),
                            array( 'product_custom_btn_action', '=', 'form' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Whatsapp Desktop Link', 'goldsmith' ),
                        'subtitle' => esc_html__('Add your whatsapp link here.Deafult: https://api.whatsapp.com/send?text=', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_whatsapp_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'product_custom_btn_visibility', '=', '1' ),
                            array( 'product_custom_btn_action', '=', 'whatsapp' )
                        )
                    ),
                    array(
                        'title' => esc_html__( 'Whatsapp Mobile Link', 'goldsmith' ),
                        'subtitle' => esc_html__('Add your whatsapp link here.Deafult: whatsapp://send?text=', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_whatsapp_mobile_link',
                        'type' => 'text',
                        'default' => '',
                        'required' => array(
                            array( 'product_custom_btn_visibility', '=', '1' ),
                            array( 'product_custom_btn_action', '=', 'whatsapp' )
                        )
                    ),
                    array(
                        'id' =>'product_whatsapp_target',
                        'type' => 'select',
                        'title' => esc_html__( 'Target', 'goldsmith' ),
                        'options' => array(
                            '' => esc_html__( 'Select an option', 'goldsmith' ),
                            '_blank' => esc_html__( 'Open in a new window', 'goldsmith' ),
                            '_self' => esc_html__( 'Open in the same frame', 'goldsmith' ),
                            '_parent' => esc_html__( 'Open in the parent frame', 'goldsmith' ),
                            '_top' => esc_html__( 'Open in the full body of the window', 'goldsmith' )
                        ),
                        'required' => array(
                            array( 'product_custom_btn_visibility', '=', '1' ),
                            array( 'product_custom_btn_action', '!=', 'form' )
                        )
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'goldsmith'),
                        'subtitle' => esc_html__('Change button background color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.goldsmith-product-action-button .goldsmith-btn:not(.type-widget)'),
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color ( Hover )', 'goldsmith'),
                        'subtitle' => esc_html__('Change button hover background color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_hvrbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.goldsmith-product-action-button .goldsmith-btn:not(.type-widget):hover'),
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color', 'goldsmith'),
                        'subtitle' => esc_html__('Change button text color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_color',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.goldsmith-product-action-button .goldsmith-btn:not(.type-widget)'),
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Text Color ( Hover )', 'goldsmith'),
                        'subtitle' => esc_html__('Change button hover text color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_hvrcolor',
                        'type' => 'color',
                        'mode' => 'color',
                        'default' => '',
                        'output' => array('.goldsmith-product-action-button .goldsmith-btn:not(.type-widget):hover'),
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Border', 'goldsmith'),
                        'subtitle' => esc_html__('Change button border.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_brdcolor',
                        'type' => 'border',
                        'output' => array('.goldsmith-product-action-button .goldsmith-btn:not(.type-widget)'),
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Border ( Hover )', 'goldsmith'),
                        'subtitle' => esc_html__('Change button hover border.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'product_custom_btn_hvrbrdcolor',
                        'type' => 'border',
                        'output' => array('.goldsmith-product-action-button .goldsmith-btn:not(.type-widget):hover'),
                        'required' => array( 'product_custom_btn_visibility', '=', '1' )
                    )
                )
            )
        );
        // Product Variable Products Terms SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Variable Products Terms ( Swatches )', 'goldsmith'),
            'id' => 'product_variations_subsection',
            'subsection' => true,
            'icon' => 'el el-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Theme Swatches', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'swatches_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'id' => 'greengrocery_product_variations_attr_start',
                    'type' => 'section',
                    'title' => esc_html__('Product Attribute Terms Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'id' =>'variations_terms_shape',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Terms Box Type', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'default' => esc_html__( 'default', 'goldsmith' ),
                        'circle' => esc_html__( 'circle', 'goldsmith' ),
                        'square' => esc_html__( 'square', 'goldsmith' ),
                        'radius' => esc_html__( 'radius', 'goldsmith' )
                    ),
                    'default' => 'default',
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Outline', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_type_bordered',
                    'type' => 'switch',
                    'default' => 0
                ),
                array(
                    'id' =>'variations_terms_checked_closed_icon_visibility',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Checked Icon', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        '1' => esc_html__( 'Show', 'goldsmith' ),
                        '0' => esc_html__( 'Hide', 'goldsmith' )
                    ),
                    'default' => '1',
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Attribute Customize ( for genaral )', 'goldsmith' ),
                    'indent' => false,
                    'id' => 'product_attr_genaral_term_divide',
                    'type' => 'info',
                    'style' => 'success',
                    'color' => '#000',
                    'icon' => 'el el-brush',
                    'notice' => true,
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Term Size', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_attr_term_size',
                    'type' => 'dimensions',
                    'output' => array('.goldsmith-terms.goldsmith-type-color .goldsmith-term,.goldsmith-terms.goldsmith-type-button .goldsmith-term'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Border', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_term_brd',
                    'type' => 'border',
                    'all' => true,
                    'output' => array('.goldsmith-terms.goldsmith-type-color .goldsmith-term:not(.type-outline),.goldsmith-terms.goldsmith-type-color .type-outline,.goldsmith-terms.goldsmith-type-image .goldsmith-term,.goldsmith-terms.goldsmith-type-button .goldsmith-term'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Active Term Border Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_term_active_brd',
                    'type' => 'border',
                    'all' => true,
                    'output' => array('.goldsmith-terms.goldsmith-type-button .goldsmith-term.goldsmith-selected,.goldsmith-terms.goldsmith-type-color .type-outline.goldsmith-selected,.goldsmith-terms.goldsmith-type-color .goldsmith-selected:not(.type-outline),.goldsmith-terms.goldsmith-type-image .goldsmith-term.goldsmith-selected'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Disabled Term Border Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_term_inactive_brd',
                    'type' => 'border',
                    'all' => true,
                    'output' => array('.goldsmith-terms.goldsmith-type-color .type-outline.goldsmith-disabled,.goldsmith-terms.goldsmith-type-color .goldsmith-disabled:not(.type-outline),.goldsmith-terms.goldsmith-type-image .goldsmith-term.goldsmith-disabled,.goldsmith-terms.goldsmith-type-button .goldsmith-term.goldsmith-disabled'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Disabled Terms Opacity', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_term_inactive_opacity',
                    'type' => 'slider',
                    'default' => 0.4,
                    'min' => 0,
                    'step' => 0.01,
                    'max' => 1,
                    'resolution' => 0.01,
                    'display_value' => 'text',
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Attribute Customize ( for type Color )', 'goldsmith' ),
                    'id' => 'product_attr_type_color_term_divide',
                    'type' => 'info',
                    'style' => 'success',
                    'color' => '#000',
                    'icon' => 'el el-brush',
                    'notice' => true,
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Term Size', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_attr_type_color_term_size',
                    'type' => 'dimensions',
                    'output' => array('.variations-items .goldsmith-terms.goldsmith-type-color .goldsmith-term'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Padding', 'goldsmith'),
                    'customizer' => true,
                    'id' =>'product_attr_type_color_term_pad',
                    'type' => 'spacing',
                    'output' => array('.variations-items .goldsmith-terms.goldsmith-type-color .goldsmith-term:not(.type-outline),.variations-items .goldsmith-terms.goldsmith-type-color .goldsmith-term.type-outline'),
                    'mode' => 'padding',
                    'units' => array('em', 'px'),
                    'units_extended' => 'false',
                    'default' => array('units' => 'px'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Border', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_type_color_term_brd',
                    'type' => 'border',
                    'all' => true,
                    'output' => array('.variations-items .goldsmith-terms.goldsmith-type-color .goldsmith-term:not(.type-outline),.variations-items .goldsmith-terms.goldsmith-type-color .goldsmith-term.type-outline'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Active Term Border Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_type_color_term_active_brd',
                    'type' => 'border',
                    'all' => true,
                    'output' => array('.variations-items .goldsmith-terms.goldsmith-type-color .goldsmith-term:not(.type-outline).goldsmith-selected,.variations-items .goldsmith-terms.goldsmith-type-color .goldsmith-term.type-outline.goldsmith-selected'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Attribute Customize ( for type Button )', 'goldsmith' ),
                    'id' => 'product_attr_type_button_term_divide',
                    'type' => 'info',
                    'style' => 'success',
                    'color' => '#000',
                    'icon' => 'el el-brush',
                    'notice' => true,
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Term Size', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_attr_type_button_term_size',
                    'type' => 'dimensions',
                    'output' => array('.variations-items .goldsmith-terms.goldsmith-type-button .goldsmith-term'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Padding', 'goldsmith'),
                    'customizer' => true,
                    'id' =>'product_attr_type_button_term_pad',
                    'type' => 'spacing',
                    'output' => array('.variations-items .goldsmith-terms.goldsmith-type-button .goldsmith-term'),
                    'mode' => 'padding',
                    'units' => array('em', 'px'),
                    'units_extended' => 'false',
                    'default' => array('units' => 'px'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Border', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_type_button_term_brd',
                    'type' => 'border',
                    'all' => true,
                    'output' => array('.variations-items .goldsmith-terms.goldsmith-type-button .goldsmith-term'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Active Term Border Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_type_button_term_active_brd',
                    'type' => 'border',
                    'all' => true,
                    'output' => array('.goldsmith-terms.goldsmith-type-button .goldsmith-term.goldsmith-enabled.goldsmith-selected'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Term Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_type_button_term_color',
                    'type' => 'color',
                    'output' => array( '.variations-items .goldsmith-terms.goldsmith-type-button .goldsmith-term' ),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Active Term Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_type_button_term_active_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-terms.goldsmith-type-button .goldsmith-term.goldsmith-enabled.goldsmith-selected' ),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Term Backgorund Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_type_button_term_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.variations-items .goldsmith-terms.goldsmith-type-button .goldsmith-term' ),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Active Term Backgorund Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_type_button_term_active_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.variations-items .goldsmith-terms.goldsmith-type-button .goldsmith-term.goldsmith-enabled.goldsmith-selected' ),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Attribute Customize ( for type Image )', 'goldsmith' ),
                    'id' => 'product_attr_type_image_term_divide',
                    'type' => 'info',
                    'style' => 'success',
                    'color' => '#000',
                    'icon' => 'el el-brush',
                    'notice' => true,
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Term Size', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_attr_type_image_term_size',
                    'type' => 'dimensions',
                    'output' => array('.variations-items .goldsmith-terms.goldsmith-type-image .goldsmith-term'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Padding', 'goldsmith'),
                    'customizer' => true,
                    'id' =>'product_attr_type_image_term_pad',
                    'type' => 'spacing',
                    'output' => array('.variations-items .goldsmith-terms.goldsmith-type-image .goldsmith-term'),
                    'mode' => 'padding',
                    'units' => array('em', 'px'),
                    'units_extended' => 'false',
                    'default' => array('units' => 'px'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Border', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_type_image_term_brd',
                    'type' => 'border',
                    'all' => true,
                    'output' => array('.variations-items .goldsmith-terms.goldsmith-type-image .goldsmith-term'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Active Term Border Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_attr_type_image_term_active_brd',
                    'type' => 'border',
                    'all' => true,
                    'output' => array('.variations-items .goldsmith-terms.goldsmith-type-image .goldsmith-term.goldsmith-enabled.goldsmith-selected'),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'id' => 'greengrocery_product_variations_attr_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'id' => 'product_attr_title_typo_start',
                    'type' => 'section',
                    'title' => esc_html__('Attribute Title Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Attribute Title Typography', 'goldsmith' ),
                    'id' => 'product_attr_title_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'text-decoration' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-variations-items .goldsmith-small-title' ),
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'id' => 'product_attr_title_typo_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                //
                array(
                    'id' => 'product_variations_hints_start',
                    'type' => 'section',
                    'title' => esc_html__('Attribute Hints Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Show Hints', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'variations_terms_hints_visibility',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Hints Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'selected_variations_hints_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.show-hints .goldsmith-terms .goldsmith-term:not(.goldsmith-disabled):after' ),
                    'required' => array( 'variations_terms_hints_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Hints Arrow Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'selected_variations_hints_bgcolor2',
                    'type' => 'color',
                    'mode' => 'border-top-color',
                    'output' => array( '.show-hints .goldsmith-terms .goldsmith-term:not(.goldsmith-disabled):before' ),
                    'required' => array( 'variations_terms_hints_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Hints Text Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'selected_variations_hints_titlecolor',
                    'type' => 'color',
                    'output' => array( '.show-hints .goldsmith-terms .goldsmith-term:not(.goldsmith-disabled):after' ),
                    'required' => array( 'variations_terms_hints_visibility', '=', '1' )
                ),
                array(
                    'id' => 'product_variations_hints_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array( 'variations_terms_hints_visibility', '=', '1' )
                ),
                //
                array(
                    'id' => 'product_selected_variations_terms_start',
                    'type' => 'section',
                    'title' => esc_html__('Product Selected Variations Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Selected Variations', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'selected_variations_terms_visibility',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array( 'swatches_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Selected Terms Tiltle', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'selected_variations_terms_title',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'swatches_visibility', '=', '1' ),
                        array( 'selected_variations_terms_visibility', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__( 'Title Typography', 'goldsmith' ),
                    'id' => 'selected_variations_terms_title_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'text-decoration' => true,
                    'all_styles' => true,
                    'output' => array('.goldsmith-selected-variations-terms-wrapper .goldsmith-selected-variations-terms-title'),
                    'required' => array(
                        array( 'swatches_visibility', '=', '1' ),
                        array( 'selected_variations_terms_visibility', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'selected_variations_terms_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array('.goldsmith-selected-variations-terms-wrapper .goldsmith-selected-variations-terms'),
                    'required' => array(
                        array( 'swatches_visibility', '=', '1' ),
                        array( 'selected_variations_terms_visibility', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Border', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'selected_variations_terms_brd',
                    'type' => 'border',
                    'all' => false,
                    'output' => array('.goldsmith-selected-variations-terms-wrapper .goldsmith-selected-variations-terms'),
                    'required' => array(
                        array( 'swatches_visibility', '=', '1' ),
                        array( 'selected_variations_terms_visibility', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__('Border Radius', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'selected_variations_terms_brd_radius',
                    'type' => 'slider',
                    'default' => 4,
                    'min' => 0,
                    'step' => 1,
                    'max' => 100,
                    'display_value' => 'text',
                    'required' => array(
                        array( 'swatches_visibility', '=', '1' ),
                        array( 'selected_variations_terms_visibility', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__('Padding', 'goldsmith'),
                    'customizer' => true,
                    'id' =>'selected_variations_terms_pad',
                    'type' => 'spacing',
                    'output' => array('.goldsmith-selected-variations-terms-wrapper .goldsmith-selected-variations-terms'),
                    'mode' => 'padding',
                    'units' => array('em', 'px'),
                    'units_extended' => 'false',
                    'default' => array(
                        'units' => 'px'
                    ),
                    'required' => array(
                        array( 'swatches_visibility', '=', '1' ),
                        array( 'selected_variations_terms_visibility', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__('Terms Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'selected_variations_terms_value_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-selected-variations-terms-wrapper .selected-features' ),
                    'required' => array(
                        array( 'swatches_visibility', '=', '1' ),
                        array( 'selected_variations_terms_visibility', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Selected Terms Typography', 'goldsmith' ),
                    'id' => 'selected_variations_terms_value_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'text-decoration' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-selected-variations-terms-wrapper .selected-features' ),
                    'required' => array(
                        array( 'swatches_visibility', '=', '1' ),
                        array( 'selected_variations_terms_visibility', '=', '1' )
                    )
                ),
                array(
                    'id' => 'product_selected_variations_terms_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array(
                        array( 'swatches_visibility', '=', '1' ),
                        array( 'selected_variations_terms_visibility', '=', '1' )
                    )
                ),
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Countdown', 'goldsmith'),
            'id' => 'single_shop_countdown_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Countdown', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_countdown_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Text Before Countdown', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'goldsmith_countdown_text',
                    'type' => 'textarea',
                    'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Expired Text', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'goldsmith_countdown_expired',
                    'type' => 'text',
                    'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Icon HTML', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'goldsmith_countdown_icon',
                    'type' => 'textarea',
                    'default' => '<svg class="svgFlash goldsmith-svg-icon" fill="currentColor" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g id="a"><g><path d="m452.51 194.38c3.59-3.25 21.83-19.17 24.92-21.99 5 5.57 9.58 10.59 10.24 11.13 2.28 2.05 6.34 2.46 9.4.71 1.83-1.06 4.24-2.64 7.18-5.21 2.93-2.58 4.81-4.76 6.11-6.45 2.15-2.81 2.32-6.93.62-9.5-.96-1.62-20.53-25.25-22.82-27.75-2.13-2.64-22.54-25.53-24-26.72-2.28-2.06-6.34-2.46-9.4-.71-1.83 1.06-4.24 2.64-7.18 5.21-2.93 2.58-4.81 4.76-6.11 6.44-2.15 2.81-2.32 6.93-.62 9.5.44.74 4.72 6.02 9.47 11.8-2.85 2.39-20.75 17.81-24.02 20.59l26.21 32.94z" fill="#454565"></path><path d="m356.57 126.14c.5-4.1 5.2-25.34 5.62-28.97 11.36-.21 21.68-.47 22.98-.67 4.69-.51 9.73-4.21 11.42-8.77 1-2.74 2.14-6.49 2.87-11.63.71-5.14.63-8.89.4-11.63-.41-4.55-4.41-8.25-8.95-8.77-2.74-.44-49.07-1.17-54.22-1.03-5.11-.14-51.64.59-54.5 1.03-4.69.51-9.73 4.22-11.42 8.77-1 2.74-2.14 6.49-2.87 11.63-.71 5.13-.63 8.89-.4 11.63.41 4.55 4.41 8.25 8.95 8.77 1.25.2 11.5.46 22.79.67-.59 3.63-5.47 24.87-6.12 28.97h63.44z" fill="#454565"></path><rect fill="#f04760" height="37.83" rx="18.91" width="37.83" x="15.97" y="225.7"></rect><path d="m327.25 121.9c-34.31 0-67.66 10.31-96.71 27.99l-67.56-.03h-.13l-.06-.02-.04.02h-116.87c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86l92.75.05c9.78.7 17.49 8.85 17.49 18.81v.19c0 10.42-8.45 18.86-18.86 18.86h-51.97c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86h20.4c10.42 0 18.86 8.45 18.86 18.86v.19c0 10.42-8.45 18.86-18.86 18.86h-86.71c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86h101.67c10.42 0 18.86 8.44 18.86 18.86v.19c0 10.42-8.45 18.86-18.86 18.86h-49.4c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86h103.7c25.91 26.16 62.55 42.06 105.15 42.06 92.63 0 178.27-75.09 191.29-167.72s-51.52-167.72-144.15-167.72z" fill="#e03757"></path><path d="m135.64 369.91c131.56-6.76 238.81-105.43 258.84-233.05-19.78-9.61-42.51-14.96-67.24-14.96-34.31 0-67.66 10.31-96.71 27.99l-67.56-.03h-.13l-.06-.02-.04.02h-116.86c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86l92.75.05c9.78.7 17.49 8.85 17.49 18.81v.19c0 10.42-8.45 18.86-18.86 18.86h-51.97c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86h20.4c10.42 0 18.86 8.45 18.86 18.86v.19c0 10.42-8.45 18.86-18.86 18.86h-86.71c-10.42 0-18.86 8.45-18.86 18.86v.19c0 10.42 8.45 18.86 18.86 18.86h101.67c10.42 0 18.86 8.44 18.86 18.86v.19c0 4.29-1.45 8.24-3.87 11.41z" fill="#f04760"></path><path d="m389.77 272.6-79.02 121.93c-1.82 2.8-4.93 4.49-8.27 4.49h-6.19c-6.38 0-11.08-5.97-9.57-12.17l19.47-80.36h-47.47c-5.69 0-9.88-5.32-8.54-10.85l26.34-108.72c.95-3.94 4.48-6.72 8.54-6.72h54.62c5.69 0 9.88 5.32 8.54 10.85l-16.07 66.33h49.35c7.81 0 12.51 8.65 8.27 15.21z"></path></g></g></svg>',
                    'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Text Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_countdown_text_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-viewed-offer-time .offer-time-text' ),
                    'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_countdown_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-summary-item.goldsmith-viewed-offer-time' ),
                    'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Border Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_countdown_brdcolor',
                    'type' => 'color',
                    'mode' => 'border-color',
                    'output' => array( '.goldsmith-summary-item.goldsmith-viewed-offer-time' ),
                    'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Count Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_countdown_count_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-summary-item.goldsmith-viewed-offer-time .goldsmith-coming-time .time-count' ),
                    'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Width', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_countdown_width',
                    'type' => 'select',
                    'options' => array(
                        '' => esc_html__( 'Select type', 'goldsmith' ),
                        'full' => esc_html__( 'Fullwidth', 'goldsmith' ),
                        'boxed' => esc_html__( 'Boxed', 'goldsmith' )
                    ),
                    'default' => 'full',
                    'required' => array( 'single_shop_countdown_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Alingment', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_countdown_alignment',
                    'type' => 'select',
                    'options' => array(
                        '' => esc_html__( 'Select type', 'goldsmith' ),
                        'flex-start' => esc_html__( 'Left', 'goldsmith' ),
                        'center' => esc_html__( 'Center', 'goldsmith' ),
                        'flex-end' => esc_html__( 'Right', 'goldsmith' )
                    ),
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_countdown_visibility', '=', '1' ),
                        array( 'product_countdown_width', '=', 'full' )
                    )
                )
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Stock Progress Bar', 'goldsmith'),
            'id' => 'single_shop_progressbar_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Stock Progress Bar', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_progressbar_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_progressbar_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-single-product-stock .goldsmith-product-stock-progress' ),
                    'required' => array( 'single_shop_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Progress Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_progressbar_progress_color',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-single-product-stock .goldsmith-product-stock-progressbar' ),
                    'required' => array( 'single_shop_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Text Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_progressbar_text_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-single-product-stock .goldsmith-product-stock-progressbar .stock-details' ),
                    'required' => array( 'single_shop_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Value Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_progressbar_value_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-single-product-stock .goldsmith-product-stock-progressbar .stock-details span' ),
                    'required' => array( 'single_shop_progressbar_visibility', '=', '1' )
                ),
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Delivery & Return Popup', 'goldsmith'),
            'id' => 'single_shop_delivery_popup_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__( 'Delivery & Return Popup', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select an elementor template from list', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_delivery_template',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                ),
                array(
                    'title' => esc_html__( 'Category(s) Exclude', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select category(s) from the list.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_delivery_template_category_exclude',
                    'type' => 'select',
                    'data' => 'terms',
                    'multi' => true,
                    'args'  => [
                        'taxonomies' => array( 'product_cat' ),
                    ],
                    'required' => array( 'single_shop_delivery_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__( 'Tag(s) Exclude', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select category(s) from the list.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_delivery_template_tag_exclude',
                    'type' => 'select',
                    'data' => 'terms',
                    'multi' => true,
                    'args'  => [
                        'taxonomies' => array( 'product_tag' ),
                    ],
                    'required' => array( 'single_shop_delivery_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__( 'Product(s) Exclude', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select category(s) from the list.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_delivery_template_product_exclude',
                    'type' => 'select',
                    'data' => 'post',
                    'multi' => true,
                    'args'  => [
                        'post_type'      => 'product',
                        'posts_per_page' => -1,
                        'orderby'        => 'title',
                        'order'          => 'ASC'
                    ],
                    'required' => array( 'single_shop_delivery_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__( 'Custom Text', 'goldsmith' ),
                    'desc' => esc_html__( 'Text entered here will be used for Delivery & Return area', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'delivery_return_text',
                    'type' => 'text',
                    'required' => array( 'single_shop_delivery_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__( 'Custom SVG Icon HTML', 'goldsmith' ),
                    'desc' => esc_html__( 'Icon entered here will be used for Delivery & Return area', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'delivery_return_icon',
                    'type' => 'textarea',
                    'required' => array( 'single_shop_delivery_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__('Text Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_delivery_popup_text_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-delivery-btn a' ),
                    'required' => array( 'single_shop_delivery_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__('Text Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_delivery_popup_text_hvrcolor',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-delivery-btn a:hover' ),
                    'required' => array( 'single_shop_delivery_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__('Icon Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_delivery_popup_svg_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-product-delivery-btn svg' ),
                    'required' => array( 'single_shop_delivery_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__('Icon Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_delivery_popup_svg_hvrcolor',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-product-delivery-btn:hover svg' ),
                    'required' => array( 'single_shop_delivery_template', '!=', '' )
                ),
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Size Guide Popup', 'goldsmith'),
            'id' => 'single_shop_size_guide_popup_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__( 'Size Guide', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select an elementor template from list', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_size_guide_template',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                ),
                array(
                    'title' => esc_html__( 'Category(s) Exclude', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select category(s) from the list.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_size_guide_template_category_exclude',
                    'type' => 'select',
                    'data' => 'terms',
                    'multi' => true,
                    'args'  => [
                        'taxonomies' => array( 'product_cat' ),
                    ],
                    'required' => array( 'single_shop_size_guide_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__( 'Tag(s) Exclude', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select category(s) from the list.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_size_guide_template_tag_exclude',
                    'type' => 'select',
                    'data' => 'terms',
                    'multi' => true,
                    'args'  => [
                        'taxonomies' => array( 'product_tag' ),
                    ],
                    'required' => array( 'single_shop_size_guide_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__( 'Product(s) Exclude', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select category(s) from the list.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_size_guide_template_product_exclude',
                    'type' => 'select',
                    'data' => 'post',
                    'multi' => true,
                    'args'  => [
                        'post_type'      => 'product',
                        'posts_per_page' => -1,
                        'orderby'        => 'title',
                        'order'          => 'ASC'
                    ],
                    'required' => array( 'single_shop_size_guide_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__( 'Custom Text', 'goldsmith' ),
                    'desc' => esc_html__( 'Text entered here will be used for Size Guide area', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'size_guide_text',
                    'type' => 'text',
                    'required' => array( 'single_shop_size_guide_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__( 'Custom SVG Icon HTML', 'goldsmith' ),
                    'desc' => esc_html__( 'Icon entered here will be used for Size Guide area', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'size_guide_icon',
                    'type' => 'textarea',
                    'required' => array( 'single_shop_size_guide_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__('Text Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_size_guide_popup_text_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-question-btn a' ),
                    'required' => array( 'single_shop_size_guide_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__('Text Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_size_guide_popup_text_hvrcolor',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-question-btn a:hover' ),
                    'required' => array( 'single_shop_size_guide_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__('Icon Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_size_guide_popup_svg_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-product-question-btn svg' ),
                    'required' => array( 'single_shop_size_guide_template', '!=', '' )
                ),
                array(
                    'title' => esc_html__('Icon Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_size_guide_popup_svg_hvrcolor',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-product-question-btn:hover svg' ),
                    'required' => array( 'single_shop_size_guide_template', '!=', '' )
                ),
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Estimated Delivery', 'goldsmith'),
            'id' => 'single_shop_estimated_delivery_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Bottom Add to Cart Delivery', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_shipping_delivery_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Estimated Delivery', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_estimated_delivery_visibility',
                    'type' => 'switch',
                    'default' => 0
                ),
                array(
                    'title' => esc_html__('Estimated Delivery ( Min )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_min_estimated_delivery',
                    'type' => 'spinner',
                    'default' => '3',
                    'min' => '1',
                    'step' => '1',
                    'max' => '31',
                    'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Estimated Delivery ( Max )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_max_estimated_delivery',
                    'type' => 'spinner',
                    'default' => '7',
                    'min' => '1',
                    'step' => '1',
                    'max' => '31',
                    'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Custom Text', 'goldsmith' ),
                    'desc' => esc_html__( 'Text entered here will be used for Estimated Delivery area', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'estimated_delivery_text',
                    'type' => 'text',
                    'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Custom SVG Icon HTML', 'goldsmith' ),
                    'desc' => esc_html__( 'Icon entered here will be used for Estimated Delivery area', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'estimated_delivery_icon',
                    'type' => 'textarea',
                    'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Text Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_estimated_delivery_text_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-estimated-delivery span' ),
                    'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Date Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_estimated_delivery_date_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-estimated-delivery' ),
                    'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Icon Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_estimated_delivery_svg_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-estimated-delivery svg' ),
                    'required' => array( 'single_shop_estimated_delivery_visibility', '=', '1' )
                ),
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Product Metas', 'goldsmith'),
            'id' => 'single_shop_metas_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Meta', 'goldsmith'),
                    'subtitle' => esc_html__('SKU, Categories, Tags', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_meta_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__( 'Meta Label Typography', 'goldsmith' ),
                    'id' => 'product_meta_label_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item.goldsmith-product-meta .goldsmith-meta-label' )
                ),
                array(
                    'title' => esc_html__( 'Meta Link Typography', 'goldsmith' ),
                    'id' => 'product_meta_link_color',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item.goldsmith-product-meta a' )
                ),
                array(
                    'title' => esc_html__('Meta Link Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_meta_link_hvrcolor',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-summary-item.goldsmith-product-meta a:hover' )
                )
            )
        ));
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Product Visitors Message', 'goldsmith'),
            'id' => 'single_shop_visitiors_message_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Visitors Message', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_visitiors_message_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'id' =>'product_visitiors_message_type',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Visitors Message Type', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select your header background type.', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'default' => esc_html__( 'Deafult', 'goldsmith' ),
                        'fake' => esc_html__( 'Fake', 'goldsmith' ),
                    ),
                    'default' => 'default',
                    'required' => array( 'product_visitiors_message_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Fake Visitor Count ( Min )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'visit_count_min',
                    'type' => 'spinner',
                    'default' => '10',
                    'min' => '1',
                    'step' => '1',
                    'max' => '100',
                    'required' => array(
                        array( 'product_visitiors_message_visibility', '=', '1' ),
                        array( 'product_visitiors_message_type', '=', 'fake' )
                    )
                ),
                array(
                    'title' => esc_html__('Fake Visitor Count ( Max )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'visit_count_max',
                    'type' => 'spinner',
                    'default' => '50',
                    'min' => '1',
                    'step' => '1',
                    'max' => '100',
                    'required' => array(
                        array( 'product_visitiors_message_visibility', '=', '1' ),
                        array( 'product_visitiors_message_type', '=', 'fake' )
                    )
                ),
                array(
                    'title' => esc_html__('Fake Visitor Count ( Delay )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'visit_count_delay',
                    'type' => 'spinner',
                    'default' => '30000',
                    'min' => '1000',
                    'step' => '100',
                    'max' => '100000',
                    'required' => array(
                        array( 'product_visitiors_message_visibility', '=', '1' ),
                        array( 'product_visitiors_message_type', '=', 'fake' )
                    )
                ),
                array(
                    'title' => esc_html__('Fake Visitor Count ( Change )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'visit_count_change',
                    'type' => 'spinner',
                    'default' => '5',
                    'min' => '1',
                    'step' => '1',
                    'max' => '50',
                    'required' => array(
                        array( 'product_visitiors_message_visibility', '=', '1' ),
                        array( 'product_visitiors_message_type', '=', 'fake' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Text 1', 'goldsmith' ),
                    'desc' => esc_html__( 'Text entered here will be used for message area', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_visitiors_message_text1',
                    'type' => 'text',
                    'required' => array( 'product_visitiors_message_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Text 2', 'goldsmith' ),
                    'desc' => esc_html__( 'Text entered here will be used for message area', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_visitiors_message_text2',
                    'type' => 'text',
                    'required' => array( 'product_visitiors_message_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Text Typography', 'goldsmith' ),
                    'id' => 'product_visitiors_message_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-visitors-product-message' ),
                    'required' => array( 'product_visitiors_message_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Text 1 Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_visitiors_message_textcolor1',
                    'type' => 'color',
                    'output' => array( '.goldsmith-accordion-header strong, .goldsmith-visitors-product-text' ),
                    'required' => array( 'product_visitiors_message_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Visitor Count Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_visitiors_count_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-visitors-product-text .goldsmith-view-count' ),
                    'required' => array(
                        array( 'product_visitiors_message_visibility', '=', '1' ),
                        array( 'product_visitiors_message_type', '=', 'fake' ),
                    )
                ),
                array(
                    'title' => esc_html__('Icon Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_visitiors_count_icon_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-visitors-product-text .goldsmith-view-count' ),
                    'required' => array(
                        array( 'product_visitiors_message_visibility', '=', '1' ),
                        array( 'product_visitiors_message_type', '=', 'fake' ),
                    )
                ),
                array(
                    'title' => esc_html__('Text 2 Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_visitiors_message_textcolor2',
                    'type' => 'color',
                    'output' => array( '.goldsmith-visitors-product-message' ),
                    'required' => array( 'product_visitiors_message_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_visitiors_message_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-visitors-product-message' ),
                    'required' => array( 'product_visitiors_message_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Border Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_visitiors_message_brdcolor',
                    'type' => 'color',
                    'mode' => 'border-color',
                    'output' => array( '.goldsmith-visitors-product-message' ),
                    'required' => array( 'product_visitiors_message_visibility', '=', '1' )
                )
            )
        ));
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Product Trusted Image', 'goldsmith'),
            'id' => 'single_shop_trust_image_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Trusted Image', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_trust_image_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__( 'Custom Elementor Template', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select an elementor template from list', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_trust_image_elementor_template',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args
                ),
                array(
                    'title' => esc_html__( 'Image', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Upload your image', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_trust_image',
                    'type' => 'media',
                    'url' => true,
                    'required' => array( 'product_trust_image_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Image Size', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_trust_image_size',
                    'type' => 'select',
                    'data' => 'image_sizes',
                    'required' => array( 'product_trust_image_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Text', 'goldsmith' ),
                    'desc' => esc_html__( 'Text entered here will be used for trust area', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_trust_image_text',
                    'type' => 'editor',
                    'args' => array(
                        'teeny' => false,
                        'textarea_rows' => 10
                    ),
                    'required' => array( 'product_trust_image_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Text Typography', 'goldsmith' ),
                    'id' => 'product_trust_image_text_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-trust-badge-text' ),
                    'required' => array( 'product_trust_image_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Category(s) Exclude', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select category(s) from the list.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_trust_category_exclude',
                    'type' => 'select',
                    'data' => 'terms',
                    'multi' => true,
                    'args'  => [
                        'taxonomies' => array( 'product_cat' ),
                    ],
                    'required' => array( 'product_trust_image_visibility', '=', '1' )
                )
            )
        ));
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Tabs', 'goldsmith'),
            'id' => 'single_shop_tabs_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__( 'Tabs Display', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_tabs_visibility',
                    'type' => 'switch',
                    'default' => 1,
                    'on' => esc_html__( 'On', 'goldsmith' ),
                    'off' => esc_html__( 'Off', 'goldsmith' )
                ),
                array(
                    'id' =>'product_tabs_type',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Tabs Type', 'goldsmith' ),
                    'options' => array(
                        'tabs' => esc_html__( 'Default Tabs', 'goldsmith' ),
                        'accordion' => esc_html__( 'Accordion In Summary', 'goldsmith' ),
                        'accordion-2' => esc_html__( 'Accordion After Summary', 'goldsmith' )
                    ),
                    'default' => 'tabs',
                    'required' => array( 'product_tabs_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Description Tab', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_hide_description_tab',
                    'type' => 'switch',
                    'default' => 1,
                    'on' => esc_html__( 'Show', 'goldsmith' ),
                    'off' => esc_html__( 'Hide', 'goldsmith' ),
                    'required' => array( 'product_tabs_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Hide Reviews Tab', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_hide_reviews_tab',
                    'type' => 'switch',
                    'default' => 1,
                    'on' => esc_html__( 'Show', 'goldsmith' ),
                    'off' => esc_html__( 'Hide', 'goldsmith' ),
                    'required' => array( 'product_tabs_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Hide Additional Information Tab', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_hide_additional_tab',
                    'type' => 'switch',
                    'default' => 1,
                    'on' => esc_html__( 'Show', 'goldsmith' ),
                    'off' => esc_html__( 'Hide', 'goldsmith' ),
                    'required' => array( 'product_tabs_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Hide Q & A Tab', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_hide_crqna_tab',
                    'type' => 'switch',
                    'default' => 1,
                    'on' => esc_html__( 'Show', 'goldsmith' ),
                    'off' => esc_html__( 'Hide', 'goldsmith' ),
                    'required' => array( 'product_tabs_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Custom Order', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_tabs_custom_order',
                    'type' => 'switch',
                    'default' => 0,
                    'on' => esc_html__( 'Yes', 'goldsmith' ),
                    'off' => esc_html__( 'No', 'goldsmith' ),
                    'required' => array( 'product_tabs_visibility', '=', '1' )
                ),
                array(
                    'id' =>'product_tabs_order',
                    'type' => 'sorter',
                    'title' => esc_html__( 'Tabs Layouts Manager', 'goldsmith' ),
                    'options' => array(
                        'show' => array(
                            'description' => esc_html__( 'Description', 'goldsmith' ),
                            'additional_information' => esc_html__( 'Additional Information', 'goldsmith' ),
                            'reviews' => esc_html__( 'Reviews', 'goldsmith' ),
                            'cr_qna' => esc_html__( 'Q & A', 'goldsmith' )
                        ),
                        'hide' => array()
                    ),
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_custom_order', '=', '1' )
                    )
                ),
                array(
                    'id' =>'product_tabs_active_tab',
                    'type' => 'button_set',
                    'title' => esc_html__( 'Active Tab', 'goldsmith' ),
                    'options' => array(
                        '' => esc_html__( 'None', 'goldsmith' ),
                        'all' => esc_html__( 'All Tabs', 'goldsmith' ),
                        ':first-child' => esc_html__( '1. Tab', 'goldsmith' ),
                        ':nth-child(2)' => esc_html__( '2. Tab', 'goldsmith' ),
                        ':nth-child(3)' => esc_html__( '3. Tab', 'goldsmith' ),
                        ':nth-child(4)' => esc_html__( '4. Tab', 'goldsmith' ),
                        ':nth-child(5)' => esc_html__( '5. Tab', 'goldsmith' ),
                        ':nth-child(6)' => esc_html__( '6. Tab', 'goldsmith' )
                    ),
                    'default' => '',
                    'required' => array( 'product_tabs_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Description Tab Content Title', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_description_tab_title_visibility',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'product_tabs_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Description Tab Content Title', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_description_tab_title',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_description_tab_title_visibility', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__( 'After Tabs Elementor Templates', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select a template from elementor templates, If you want to show any content after product page tabs section.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_stretch_elementor_template',
                    'type' => 'select',
                    'data' => 'posts',
                    'args' => $el_args,
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '=', 'tabs' )
                    )
                ),
                array(
                    'id' => 'goldsmith_product_accordion_start',
                    'type' => 'section',
                    'title' => esc_html__('Accordion Color Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '!=', 'tabs' )
                    )
                ),
                array(
                    'title' => esc_html__('Title Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_accordion_titlecolor',
                    'type' => 'color',
                    'output' => array( '.goldsmith-accordion-header' ),
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '!=', 'tabs' )
                    )
                ),
                array(
                    'title' => esc_html__('Title Color ( Active )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_accordion_active_titlecolor',
                    'type' => 'color',
                    'output' => array( '.goldsmith-accordion-item.active .goldsmith-accordion-header' ),
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '!=', 'tabs' )
                    )
                ),
                array(
                    'title' => esc_html__('Title Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_accordion_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-accordion-item' ),
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '!=', 'tabs' )
                    )
                ),
                array(
                    'title' => esc_html__('Title Background Color ( Active )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_accordion_active_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-accordion-item.active .goldsmith-accordion-header' ),
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '!=', 'tabs' )
                    )
                ),
                array(
                    'title' => esc_html__('Content Text Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_accordion_textcolor',
                    'type' => 'color',
                    'output' => array( '.goldsmith-accordion-body,.goldsmith-product-showcase.goldsmith-bg-custom .product-desc-content h4,.goldsmith-product-showcase.goldsmith-bg-custom .product-desc-content .title' ),
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '!=', 'tabs' )
                    )
                ),
                array(
                    'title' => esc_html__('Border Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_accordion_bordercolor',
                    'type' => 'color',
                    'mode' => 'border-color',
                    'output' => array( '.goldsmith-accordion-item' ),
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '!=', 'tabs' )
                    )
                ),
                array(
                    'id' => 'goldsmith_product_accordion_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '!=', 'tabs' )
                    )
                ),
                array(
                    'id' => 'goldsmith_product_tabs_start',
                    'type' => 'section',
                    'title' => esc_html__('Tabs Color Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '=', 'tabs' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Tab Title Typography', 'goldsmith' ),
                    'id' => 'single_shop_tabs_title_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-product-tab-title-item' ),
                    'required' => array( 'product_tabs_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Title Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_tabs_titlecolor',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-tab-title-item' ),
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '=', 'tabs' )
                    )
                ),
                array(
                    'title' => esc_html__('Title Color ( Active )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_tabs_active_titlecolor',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-tab-title-item.active' ),
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '=', 'tabs' )
                    )
                ),
                array(
                    'title' => esc_html__('Title Border Color ( Active )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_tabs_active_bordercolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-tab-title-item::after' ),
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '=', 'tabs' )
                    )
                ),
                array(
                    'title' => esc_html__('Tabs Border Bottom Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_tabs_bordercolor',
                    'type' => 'color',
                    'mode' => 'border-bottom-color',
                    'output' => array( '.goldsmith-product-tab-title' ),
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '=', 'tabs' )
                    )
                ),
                array(
                    'id' => 'goldsmith_product_tabs_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array(
                        array( 'product_tabs_visibility', '=', '1' ),
                        array( 'product_tabs_type', '=', 'tabs' )
                    )
                )
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Reviews Section', 'goldsmith'),
            'id' => 'single_shop_reviews_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Reviews Section', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_review_visibility',
                    'type' => 'switch',
                    'default' => 1
                )
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Related Posts', 'goldsmith'),
            'id' => 'single_shop_related_subsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Related Section', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_ralated_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Related Title', 'goldsmith'),
                    'subtitle' => esc_html__('Add your single shop page related section title here.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_related_title',
                    'type' => 'text',
                    'default' => '',
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Title Tag', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_related_title_tag',
                    'type' => 'select',
                    'options' => array(
                        '' => esc_html__( 'Select type', 'goldsmith' ),
                        'h1' => esc_html__( 'H1', 'goldsmith' ),
                        'h2' => esc_html__( 'H2', 'goldsmith' ),
                        'h3' => esc_html__( 'H3', 'goldsmith' ),
                        'h4' => esc_html__( 'H4', 'goldsmith' ),
                        'h5' => esc_html__( 'H5', 'goldsmith' ),
                        'h6' => esc_html__( 'H6', 'goldsmith' ),
                        'p' => esc_html__( 'p', 'goldsmith' ),
                        'div' => esc_html__( 'div', 'goldsmith' ),
                        'span' => esc_html__( 'span', 'goldsmith' )
                    ),
                    'default' => 'h4',
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Title Typography', 'goldsmith' ),
                    'id' => 'breadcrumbs_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-product-related .goldsmith-section .section-title' ),
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Post Count ( Per Page )', 'goldsmith'),
                    'subtitle' => esc_html__('You can control show related post count with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_related_count',
                    'type' => 'slider',
                    'default' => 10,
                    'min' => 1,
                    'step' => 1,
                    'max' => 24,
                    'display_value' => 'text',
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'id' => 'shop_related_section_slider_start',
                    'type' => 'section',
                    'title' => esc_html__('Related Slider Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Perview ( Min 1024px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_related_perview',
                    'type' => 'slider',
                    'default' => 4,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Perview ( Min 768px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_related_mdperview',
                    'type' => 'slider',
                    'default' => 3,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Perview ( Min 480px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_related_smperview',
                    'type' => 'slider',
                    'default' => 2,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Speed', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control related post slider item gap.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_related_speed',
                    'type' => 'slider',
                    'default' => 1000,
                    'min' => 100,
                    'step' => 1,
                    'max' => 10000,
                    'display_value' => 'text',
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Gap', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control related post slider item gap.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_related_gap',
                    'type' => 'slider',
                    'default' => 30,
                    'min' => 0,
                    'step' => 1,
                    'max' => 100,
                    'display_value' => 'text',
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Autoplay', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_related_autoplay',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Loop', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_related_loop',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Mousewheel', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_related_mousewheel',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Free Mode', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_related_freemode',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                ),
                array(
                    'id' => 'shop_related_section_slider_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array( 'single_shop_ralated_visibility', '=', '1' )
                )
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Upsells Products', 'goldsmith'),
            'id' => 'singleshopupsellssubsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Upsells Title', 'goldsmith'),
                    'subtitle' => esc_html__('Add your single shop page upsells section title here.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_upsells_title',
                    'type' => 'text',
                    'default' => ''
                ),
                array(
                    'title' => esc_html__( 'Title Tag', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_upsells_title_tag',
                    'type' => 'select',
                    'options' => array(
                        '' => esc_html__( 'Select type', 'goldsmith' ),
                        'h1' => esc_html__( 'H1', 'goldsmith' ),
                        'h2' => esc_html__( 'H2', 'goldsmith' ),
                        'h3' => esc_html__( 'H3', 'goldsmith' ),
                        'h4' => esc_html__( 'H4', 'goldsmith' ),
                        'h5' => esc_html__( 'H5', 'goldsmith' ),
                        'h6' => esc_html__( 'H6', 'goldsmith' ),
                        'p' => esc_html__( 'p', 'goldsmith' ),
                        'div' => esc_html__( 'div', 'goldsmith' ),
                        'span' => esc_html__( 'span', 'goldsmith' )
                    ),
                    'default' => 'h4'
                ),
                array(
                    'title' => esc_html__( 'Title Typography', 'goldsmith' ),
                    'id' => 'breadcrumbs_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'all_styles' => true,
                    'output' => array( '.up-sells .goldsmith-section .section-title' )
                ),
                array(
                    'id' =>'shop_upsells_type',
                    'type' => 'button_set',
                    'title' => esc_html__('Upsells Layout Type', 'goldsmith'),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop product page upsells.', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'slider' => esc_html__( 'Slider', 'goldsmith' ),
                        'grid' => esc_html__( 'Grid', 'goldsmith' )
                    ),
                    'default' => 'slider'
                ),
                array(
                    'title' => esc_html__('Post Column', 'goldsmith'),
                    'subtitle' => esc_html__('You can control upsells post column with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_upsells_colxl',
                    'type' => 'slider',
                    'default' => 4,
                    'min' => 1,
                    'step' => 1,
                    'max' => 6,
                    'display_value' => 'text',
                    'required' => array( 'shop_upsells_type', '=', 'grid' )
                ),
                array(
                    'title' => esc_html__('Post Column ( Desktop/Tablet )', 'goldsmith'),
                    'subtitle' => esc_html__('You can control upsells post column for tablet device with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_upsells_collg',
                    'type' => 'slider',
                    'default' => 3,
                    'min' => 1,
                    'step' => 1,
                    'max' => 4,
                    'display_value' => 'text',
                    'required' => array( 'shop_upsells_type', '=', 'grid' )
                ),
                array(
                    'title' => esc_html__('Post Column ( Tablet )', 'goldsmith'),
                    'subtitle' => esc_html__('You can control upsells post column for phone device with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_upsells_colsm',
                    'type' => 'slider',
                    'default' => 1,
                    'min' => 1,
                    'step' => 1,
                    'max' => 3,
                    'display_value' => 'text',
                    'required' => array( 'shop_upsells_type', '=', 'grid' )
                ),
                array(
                    'title' => esc_html__('Post Column ( Phone )', 'goldsmith'),
                    'subtitle' => esc_html__('You can control upsells post column for phone device with this option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'shop_upsells_colxs',
                    'type' => 'slider',
                    'default' => 1,
                    'min' => 1,
                    'step' => 1,
                    'max' => 3,
                    'display_value' => 'text',
                    'required' => array( 'shop_upsells_type', '=', 'grid' )
                ),
                array(
                    'id' => 'shop_upsells_section_slider_start',
                    'type' => 'section',
                    'title' => esc_html__('Related Slider Options', 'goldsmith'),
                    'customizer' => true,
                    'indent' => true,
                    'required' => array( 'shop_upsells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Perview ( Min 1024px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_upsells_perview',
                    'type' => 'slider',
                    'default' => 4,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array( 'shop_upsells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Perview ( Min 768px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_upsells_mdperview',
                    'type' => 'slider',
                    'default' => 3,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array( 'shop_upsells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Perview ( Min 480px )', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_upsells_smperview',
                    'type' => 'slider',
                    'default' => 2,
                    'min' => 1,
                    'step' => 1,
                    'max' => 10,
                    'display_value' => 'text',
                    'required' => array( 'shop_upsells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Speed', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control related post slider item gap.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_upsells_speed',
                    'type' => 'slider',
                    'default' => 1000,
                    'min' => 100,
                    'step' => 1,
                    'max' => 10000,
                    'display_value' => 'text',
                    'required' => array( 'shop_upsells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Gap', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can control related post slider item gap.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_upsells_gap',
                    'type' => 'slider',
                    'default' => 30,
                    'min' => 0,
                    'step' => 1,
                    'max' => 100,
                    'display_value' => 'text',
                    'required' => array( 'shop_upsells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Autoplay', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_upsells_autoplay',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array( 'shop_upsells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Loop', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_upsells_loop',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'shop_upsells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Mousewheel', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_upsells_mousewheel',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'shop_upsells_type', '=', 'slider' )
                ),
                array(
                    'title' => esc_html__( 'Free Mode', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'shop_upsells_freemode',
                    'type' => 'switch',
                    'default' => 0,
                    'required' => array( 'shop_upsells_type', '=', 'slider' )
                ),
                array(
                    'id' => 'shop_upsells_section_slider_end',
                    'type' => 'section',
                    'customizer' => true,
                    'indent' => false,
                    'required' => array( 'shop_upsells_type', '=', 'slider' )
                )
            )
        ));
        // SINGLE CONTENT SUBSECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Share Buttons', 'goldsmith'),
            'id' => 'singleshopsharesubsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Products share', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__( 'Share type', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select your product share type.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_share_type',
                    'type' => 'select',
                    'multiple' => false,
                    'options' => array(
                        'share' => esc_html__( 'Share', 'goldsmith' ),
                        'follow' => esc_html__( 'follow', 'goldsmith' ),
                        'custom' => esc_html__( 'Custom HTML', 'goldsmith' ),
                    ),
                    'default' => 'share',
                    'required' => array( 'single_shop_share_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Custom HTML', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Add your HTML or Shortcode here.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_custom_share',
                    'type' => 'textarea',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Color type', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select your product share type.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_share_color_type',
                    'type' => 'select',
                    'multiple' => false,
                    'options' => array(
                        'official' => esc_html__( 'Official', 'goldsmith' ),
                        'custom' => esc_html__( 'Custom', 'goldsmith' )
                    ),
                    'default' => 'official',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Shape type', 'goldsmith' ),
                    'subtitle' => esc_html__( 'Select your product share type.', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_share_shape_type',
                    'type' => 'select',
                    'multiple' => false,
                    'options' => array(
                        'square' => esc_html__( 'Square', 'goldsmith' ),
                        'circle' => esc_html__( 'Circle', 'goldsmith' ),
                        'round' => esc_html__( 'Round', 'goldsmith' )
                    ),
                    'default' => 'circle',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__('Share Customize Options', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_customize_divider',
                    'type' => 'info',
                    'style' => 'success',
                    'color' => '#000',
                    'icon' => 'el el-brush',
                    'notice' => true,
                    'output' => array( '.goldsmith-product-summary .goldsmith-social-icons span.share-title' ),
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Share Label Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_label_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-social-icons span.share-title' ),
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__( 'Size', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'single_shop_share_size',
                    'type' => 'dimensions',
                    'output' => array('.goldsmith-product-summary .goldsmith-social-icons a'),
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-social-icons a' ),
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Background Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_hvrbgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-social-icons a' ),
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-social-icons a' ),
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_hvrcolor',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-social-icons a:hover' ),
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Border', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_brd',
                    'type' => 'border',
                    'all' => true,
                    'output' => array('.goldsmith-product-summary .goldsmith-social-icons a'),
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Border ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_hvrbrd',
                    'type' => 'border',
                    'all' => true,
                    'output' => array('.goldsmith-product-summary .goldsmith-social-icons a:hover'),
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Hint Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_hint_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-social-icons a:after' ),
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Hint Arrow Color ', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_hint_arrow_color',
                    'type' => 'color',
                    'mode' => 'border-top-color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-social-icons a:before' ),
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Hint Text Color ', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_hint_text_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-summary .goldsmith-social-icons a:after' ),
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Share Icons Options', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'single_shop_share_icons_divider',
                    'type' => 'info',
                    'style' => 'success',
                    'color' => '#000',
                    'icon' => 'el el-cog',
                    'notice' => true,
                    'required' => array( 'single_shop_share_color_type', '=', 'custom' )
                ),
                array(
                    'title' => esc_html__('Facebook', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_facebook',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__('Facebook link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'facebook_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' ),
                        array( 'share_facebook', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__('Twitter', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_twitter',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__('Twitter link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'twitter_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' ),
                        array( 'share_twitter', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__('Instagram', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_instagram',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' )
                    )
                ),
                array(
                    'title' => esc_html__('Instagram link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'instagram_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' ),
                        array( 'share_instagram', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Youtube', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_youtube',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' )
                    )
                ),
                array(
                    'title' => esc_html__('Youtube link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'youtube_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' ),
                        array( 'share_youtube', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Vimeo', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_vimeo',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' )
                    )
                ),
                array(
                    'title' => esc_html__('Vimeo link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'vimeo_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' ),
                        array( 'share_vimeo', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Pinterest', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_pinterest',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__('Pinterest link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'pinterest_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' ),
                        array( 'share_pinterest', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Linkedin', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_linkedin',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__('Linkedin link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'linkedin_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' ),
                        array( 'share_linkedin', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Tumblr', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_tumblr',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' )
                    )
                ),
                array(
                    'title' => esc_html__('Tumblr link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'tumblr_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' ),
                        array( 'share_tumblr', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Flickr', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_flickr',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' )
                    )
                ),
                array(
                    'title' => esc_html__('Flickr link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'flickr_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' ),
                        array( 'share_flickr', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Github', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_github',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' )
                    )
                ),
                array(
                    'title' => esc_html__('Github link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'github_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' ),
                        array( 'share_github', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Behance', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_behance',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' )
                    )
                ),
                array(
                    'title' => esc_html__('Behance link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'behance_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' ),
                        array( 'share_behance', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Dribbble', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_dribbble',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' )
                    )
                ),
                array(
                    'title' => esc_html__('Dribbble link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'dribbble_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' ),
                        array( 'share_dribbble', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Soundcloud', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_soundcloud',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' )
                    )
                ),
                array(
                    'title' => esc_html__('Soundcloud link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'soundcloud_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' ),
                        array( 'share_soundcloud', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Spotify', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_spotify',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' )
                    )
                ),
                array(
                    'title' => esc_html__('Spotify link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'spotify_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' ),
                        array( 'share_spotify', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Ok', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_ok',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__('Ok link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'ok_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' ),
                        array( 'share_ok', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Whatsapp', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_whatsapp',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__('Whatsapp link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'whatsapp_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' ),
                        array( 'share_whatsapp', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Telegram', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_telegram',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__('Telegram link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'telegram_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' ),
                        array( 'share_telegram', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Viber', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_viber',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'share' )
                    )
                ),
                array(
                    'title' => esc_html__('Viber link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'viber_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'share' ),
                        array( 'share_viber', '=', '1' )
                    )
                ),
                array(
                    'title' => esc_html__('Tiktok', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_tiktok',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' )
                    )
                ),
                array(
                    'title' => esc_html__('Tiktok link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'tiktok_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' ),
                        array( 'share_tiktok', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Snapchat', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_snapchat',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' )
                    )
                ),
                array(
                    'title' => esc_html__('Snapchat link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'snapchat_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '=', 'follow' ),
                        array( 'share_snapchat', '=', '1' ),
                    )
                ),
                array(
                    'title' => esc_html__('Vk', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'share_vk',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' )
                    )
                ),
                array(
                    'title' => esc_html__('Vk link', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'vk_link',
                    'type' => 'text',
                    'default' => '',
                    'required' => array(
                        array( 'single_shop_share_visibility', '=', '1' ),
                        array( 'single_shop_share_type', '!=', 'custom' ),
                        array( 'share_vk', '=', '1' ),
                    )
                ),
            )
        ));
        // SHOP PAGE SECTION
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('Bottom Popup Cart on Scroll', 'goldsmith'),
            'id' => 'singleshopbottompopupcartsubsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Bottom Popup Cart on Scroll', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'goldsmith_product_bottom_popup_cart',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_bottom_popup_cart_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-bottom-popup-cart' ),
                    'required' => array( 'goldsmith_product_bottom_popup_cart', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Product Title Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_bottom_popup_cart_title_color',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-bottom-popup-cart .goldsmith-product-bottom-title' ),
                    'required' => array( 'goldsmith_product_bottom_popup_cart', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Product Price Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_bottom_popup_cart_price_color',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-bottom-popup-cart .goldsmith-product-bottom-title .goldsmith-price' ),
                    'required' => array( 'goldsmith_product_bottom_popup_cart', '=', '1' )
                ),
                array(
                    'title' => esc_html__( 'Add To Cart Button Typography', 'goldsmith' ),
                    'id' => 'product_cart_btn_typo',
                    'type' => 'typography',
                    'font-backup' => false,
                    'letter-spacing' => true,
                    'text-transform' => true,
                    'all_styles' => true,
                    'output' => array( '.goldsmith-product-bottom-popup-cart form.cart .single_add_to_cart_button' )
                ),
                array(
                    'title' => esc_html__('Cart Button Title Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_btn_title_color',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-bottom-popup-cart form.cart .single_add_to_cart_button' )
                ),
                array(
                    'title' => esc_html__('Cart Button Title Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_btn_title_hvrcolor',
                    'type' => 'color',
                    'output' => array( '.goldsmith-product-bottom-popup-cart form.cart .single_add_to_cart_button:hover' )
                ),
                array(
                    'title' => esc_html__('Cart Button Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_btn_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-bottom-popup-cart form.cart .single_add_to_cart_button' )
                ),
                array(
                    'title' => esc_html__('Cart Button Background Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_btn_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-bottom-popup-cart form.cart .single_add_to_cart_button:hover' )
                ),
                array(
                    'title' => esc_html__( 'Cart Button Border', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_cart_btn_border',
                    'type' => 'border',
                    'output' => array('.goldsmith-product-bottom-popup-cart form.cart .single_add_to_cart_button' )
                ),
                array(
                    'title' => esc_html__( 'Cart Button Border ( Hover )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'product_cart_btn_hvrborder',
                    'type' => 'border',
                    'output' => array('.goldsmith-product-bottom-popup-cart form.cart .single_add_to_cart_button:hover' )
                ),
                array(
                    'title' => esc_html__('Wishlist & Compare Icon Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_svg_icon_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-product-bottom-popup-cart .goldsmith-product-button .goldsmith-svg-icon' )
                ),
                array(
                    'title' => esc_html__('Wishlist & Compare Icon Color ( Hover )', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_svg_icon_hvrcolor',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-product-bottom-popup-cart .goldsmith-product-button:hover .goldsmith-svg-icon' )
                ),
                array(
                    'title' => esc_html__('Wishlist & Compare Hint Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_svg_icon_hint_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-product-bottom-popup-cart .goldsmith-product-hint' )
                ),
                array(
                    'title' => esc_html__('Wishlist & Compare Hint Text Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'product_cart_svg_icon_hint_text_color',
                    'type' => 'color',
                    'output' => array( 'g.oldsmith-product-bottom-popup-cart .goldsmith-product-hint' )
                ),
            )
        ));
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('CART', 'goldsmith'),
            'id' => 'shop_cart_popup_notices_positionsection',
            'subsection' => false,
            'icon' => 'el el-shopping-cart-sign',
            'fields' => array()
        ));
        //HEADER MOBILE TOP
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__( 'Minicart Panel', 'goldsmith' ),
            'desc' => esc_html__( 'Cart,Wishlist,Compare', 'goldsmith' ),
            'id' => 'headerrightpanelsubsection',
            'subsection' => true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__( 'Disable Minicart Panel', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can disable right panel( mini cart )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'disable_minicart',
                    'on' => esc_html__( 'Yes', 'goldsmith' ),
                    'off' => esc_html__( 'No', 'goldsmith' ),
                    'type' => 'switch',
                    'customizer' => true,
                    'default' => 0,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Total Display', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'minicart_total_visibility',
                    'type' => 'switch',
                    'customizer' => true,
                    'default' => 0,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Clear All Button Display', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'minicart_clearbtn_visibility',
                    'type' => 'switch',
                    'customizer' => true,
                    'default' => 1,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Quantity Input Display', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'minicart_quantity_visibility',
                    'type' => 'switch',
                    'customizer' => true,
                    'default' => 1,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Disable Panel Auto-Open', 'goldsmith' ),
                    'subtitle' => esc_html__( 'You can disable automatic opening of the right panel( mini cart ) when a product is added to the cart', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'disable_right_panel_auto',
                    'on' => esc_html__( 'Yes', 'goldsmith' ),
                    'off' => esc_html__( 'No', 'goldsmith' ),
                    'type' => 'switch',
                    'customizer' => true,
                    'default' => 0,
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Panel Header Border Bottom Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_header_brdcolor',
                    'type' => 'color',
                    'mode' => 'border-bottom-color',
                    'output' => array( '.goldsmith-side-panel .panel-header' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Panel Header Close Icon Background Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_close_icon_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-side-panel .goldsmith-panel-close-button' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Panel Header Close Icon Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_close_icon_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .goldsmith-panel-close-button' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Panel Header SVG Icon Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_svg_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-side-panel .panel-header .goldsmith-svg-icon' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Panel Header Active SVG Icon Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_active_svg_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-side-panel .panel-header .panel-header-btn.active .goldsmith-svg-icon' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Panel Header Active SVG Icon Background Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_active_svg_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-side-panel .panel-header .panel-header-btn.active .goldsmith-svg-icon' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Panel Header Counter Background Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_counter_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-side-panel .panel-header .panel-header-btn .goldsmith-wc-count' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Panel Header Counter Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_counter_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .panel-header .panel-header-btn  .goldsmith-wc-count' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Panel Header Cart Total Text Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_total_text_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .panel-header .panel-header-btn span.goldsmith-cart-total' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Panel Title Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_title_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .panel-top-title' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Panel Title Border Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_title_brdcolor',
                    'type' => 'color',
                    'mode' => 'border-bottom-color',
                    'output' => array( '.goldsmith-side-panel .panel-top-title:after' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Cart Item Title Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_title_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .cart-name' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Cart Item Price Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_price_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .goldsmith-price' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Cart Item Quantity Background Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_qty_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-side-panel .quantity' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Cart Item Quantity Plus Minus Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_qty_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .quantity-button.plus,.goldsmith-side-panel .quantity-button.minus,.goldsmith-side-panel .input-text::-webkit-input-placeholder,.goldsmith-side-panel .input-text'),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Cart Item Quantity Plus Minus Backgroud Color ( Hover )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_qty_hvrbgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-side-panel .quantity-button.plus:hover,.goldsmith-side-panel .quantity-button.minus:hover' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Wishlist,Compare Add to Cart Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_addtocart_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .goldsmith-content-info .add_to_cart_button' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Wishlist,Compare Add to Cart Color ( Hover )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_addtocart_hvrcolor',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .goldsmith-content-info .add_to_cart_button:hover' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Wishlist,Compare Stock Status Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_addtocart_hvrcolor',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .goldsmith-content-info .product-stock' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Subtotal Border Top Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_subtotal_brdcolor',
                    'type' => 'color',
                    'mode' => 'border-top-color',
                    'output' => array( '.goldsmith-side-panel .cart-total-price' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Subtotal Title Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_subtotal_color',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-side-panel .cart-total-price' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Subtotal Price Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_subtotal_price_color',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-side-panel .cart-total-price .cart-total-price-right' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Delete Icon Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_delete_icon_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-side-panel .del-icon a svg' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Free Shipping Text Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_extra_text_color',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-side-panel .minicart-extra-text' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Buttons Background Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_buttons_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-side-panel .cart-bottom-btn .goldsmith-btn, .goldsmith-side-panel .checkout-area .goldsmith-bg-black' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Buttons Background Color ( Hover )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_buttons_hvrbgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'output' => array( '.goldsmith-side-panel .cart-bottom-btn .goldsmith-btn:hover, .goldsmith-side-panel .checkout-area .goldsmith-bg-black:hover' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Buttons Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_buttons_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .cart-bottom-btn .goldsmith-btn, .goldsmith-side-panel .checkout-area .goldsmith-bg-black' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Buttons Color ( Hover )', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_buttons_hvrcolor',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .cart-bottom-btn .goldsmith-btn:hover, .goldsmith-side-panel .checkout-area .goldsmith-bg-black:hover' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Empty Cart Icon Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_empty_svg_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'output' => array( '.goldsmith-side-panel .panel-content svg' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                ),
                array(
                    'title' => esc_html__( 'Empty Cart Text Color', 'goldsmith' ),
                    'customizer' => true,
                    'id' => 'sidebar_right_panel_cart_item_empty_text_color',
                    'type' => 'color',
                    'mode' => 'color',
                    'output' => array( '.goldsmith-side-panel .goldsmith-small-title' ),
                    'required' => array(
                        array( 'header_visibility', '=', '1' ),
                        array( 'header_template', '=', 'default' ),
                        array( 'disable_minicart', '=', '0' )
                    )
                )
            )
        ));
        Redux::setSection($goldsmith_pre,
            array(
                'title' => esc_html__('Sticky Fly Cart', 'goldsmith'),
                'id' => 'shopflycartsubsection',
                'subsection' => true,
                'icon' => 'el el-cog',
                'fields' => array(
                    array(
                        'title' => esc_html__('Sticky Fly Cart Display', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_visibility',
                        'type' => 'switch',
                        'default' => 0
                    ),
                    array(
                        'id' =>'shop_fly_cart_action_type',
                        'type' => 'button_set',
                        'title' => esc_html__('Click Action Type', 'goldsmith'),
                        'options' => array(
                            'page' => esc_html__('Open Cart Page', 'goldsmith'),
                            'panel' => esc_html__('Open Minicart Panel', 'goldsmith'),
                        ),
                        'default' => 'panel',
                        'required' => array('shop_fly_cart_visibility', '=', '1')
                    ),
                    array(
                        'title' => esc_html__( 'Animation Duration ( ms )', 'goldsmith' ),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_duration',
                        'type' => 'slider',
                        'default' => 1500,
                        'min' => 0,
                        'step' => 100,
                        'max' => 5000,
                        'display_value' => 'text',
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color', 'goldsmith'),
                        'subtitle' => esc_html__('Change Fly Cart background color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.greengrocery-sticky-cart-toggle'),
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Background Color ( Active )', 'goldsmith'),
                        'subtitle' => esc_html__('Change Fly Cart background color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_actbgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.greengrocery-sticky-cart-toggle.active'),
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Icon Color', 'goldsmith'),
                        'subtitle' => esc_html__('Change fly cart icon color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_svgcolor',
                        'type' => 'color',
                        'mode' => 'fill',
                        'default' => '',
                        'output' => array('.greengrocery-sticky-cart-toggle svg'),
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Icon Color', 'goldsmith'),
                        'subtitle' => esc_html__('Change fly cart icon color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_actsvgcolor',
                        'type' => 'color',
                        'mode' => 'fill',
                        'default' => '',
                        'output' => array('.greengrocery-sticky-cart-toggle.active svg'),
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Counter Background Color', 'goldsmith'),
                        'subtitle' => esc_html__('Change fly cart background color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_counter_bgcolor',
                        'type' => 'color',
                        'mode' => 'background-color',
                        'default' => '',
                        'output' => array('.greengrocery-sticky-cart-toggle .greengrocery-wc-count'),
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    ),
                    array(
                        'title' => esc_html__('Counter Number Color', 'goldsmith'),
                        'subtitle' => esc_html__('Change fly cart icon color.', 'goldsmith'),
                        'customizer' => true,
                        'id' => 'shop_fly_cart_counter_color',
                        'type' => 'color',
                        'default' => '',
                        'output' => array('.greengrocery-sticky-cart-toggle .greengrocery-wc-count'),
                        'required' => array( 'shop_fly_cart_visibility', '=', '1' )
                    )
                )
            )
        );
        // Free Shipping Progressbar
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__( 'Free Shipping Progressbar', 'goldsmith' ),
            'id' => 'shopshippingprogressbarsubsection',
            'subsection'=> true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Progressbar Display', 'goldsmith'),
                    'subtitle' => esc_html__('You can enable or disable the site shop free shipping progressbar with switch option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_progressbar_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Targeted Amount', 'goldsmith'),
                    'subtitle' => esc_html__('Please enter the targeted amount without currency for free shipping in this field.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_progressbar_amount',
                    'validate' => array( 'numeric', 'not_empty' ),
                    'type' => 'text',
                    'default' => 500,
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Initial Message', 'goldsmith'),
                    'subtitle' => sprintf('%s <code>[remainder]</code> %s',
                        esc_html__('Please enter the initial message with', 'goldsmith'),
                        esc_html__('for free shipping in this field.', 'goldsmith')
                    ),
                    'customizer' => true,
                    'id' => 'free_shipping_progressbar_message_initial',
                    'type' => 'textarea',
                    'default' => 'Buy [remainder] more to enjoy FREE Shipping',
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Success Message', 'goldsmith'),
                    'subtitle' => esc_html__('Please enter the success message with for free shipping in this field.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_progressbar_message_success',
                    'type' => 'textarea',
                    'default' => 'Congrats! You are eligible for more to enjoy FREE Shipping',
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Customize Options', 'goldsmith'),
                    'customizer' => false,
                    'id' => 'free_shipping_progressbar_customize_divider',
                    'type' => 'info',
                    'style' => 'success',
                    'color' => '#000',
                    'icon' => 'el el-brush',
                    'notice' => true,
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Message Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_progressbar_message_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-cart-goal-wrapper .goldsmith-cart-goal-text'),
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Price Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_progressbar_message_price_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-cart-goal-wrapper .goldsmith-cart-goal-text .woocommerce-Price-amount.amount'),
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Progress Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_progress_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-free-shipping-progress .goldsmith-progress-bar-wrap:before'),
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Progressbar Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_progressbar_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-free-shipping-progress .goldsmith-progress-bar:before'),
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Icon Border Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_progressbar_icon_brdcolor',
                    'type' => 'color',
                    'mode' => 'border-color',
                    'default' => '',
                    'output' => array('.goldsmith-free-shipping-progress .goldsmith-progress-value'),
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Icon Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_progressbar_icon_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'default' => '',
                    'output' => array('.goldsmith-free-shipping-progress .goldsmith-progress-value svg *'),
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Success Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_progressbar_success_info',
                    'type' => 'info',
                    'style' => 'success',
                    'color' => '#000',
                    'icon' => 'el el-brush',
                    'notice' => true,
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Message Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_success_progressbar_message_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-cart-goal-wrapper.free-shipping-success .goldsmith-cart-goal-text'),
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Progress Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_success_progress_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.free-shipping-success .goldsmith-free-shipping-progress .goldsmith-progress-bar-wrap:before'),
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Progressbar Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_success_progressbar_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.free-shipping-success .goldsmith-free-shipping-progress .goldsmith-progress-bar:before'),
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Icon Border Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_success_progressbar_icon_brdcolor',
                    'type' => 'color',
                    'mode' => 'border-color',
                    'default' => '',
                    'output' => array('.free-shipping-success .goldsmith-free-shipping-progress .goldsmith-progress-value'),
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Icon Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'free_shipping_success_progressbar_icon_color',
                    'type' => 'color',
                    'mode' => 'fill',
                    'default' => '',
                    'output' => array('.free-shipping-success .goldsmith-free-shipping-progress .goldsmith-progress-value svg *'),
                    'required' => array( 'free_shipping_progressbar_visibility', '=', '1' )
                ),
            )
        ));
        // Free Shipping Progressbar
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__( 'Cart Limited Timer', 'goldsmith' ),
            'id' => 'shopcartlimitedtimersubsection',
            'subsection'=> true,
            'icon' => 'fa fa-cog',
            'fields' => array(
                array(
                    'title' => esc_html__('Minicart Limited Timer Display', 'goldsmith'),
                    'subtitle' => esc_html__('You can enable or disable the site shop free shipping progressbar with switch option.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'cart_limited_timer_visibility',
                    'type' => 'switch',
                    'default' => 1
                ),
                array(
                    'title' => esc_html__('Time', 'goldsmith'),
                    'subtitle' => sprintf('%s <code>5m = 300</code>',esc_html__('Please enter the time in miliseconds for example: ', 'goldsmith')),
                    'customizer' => true,
                    'validate' => array( 'numeric', 'not_empty' ),
                    'id' => 'cart_limited_timer_date',
                    'type' => 'text',
                    'default' => 300,
                    'required' => array( 'cart_limited_timer_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Initial Message', 'goldsmith'),
                    'subtitle' => esc_html__('Please enter the date for limited time in this field.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'cart_limited_timer_message_initial',
                    'type' => 'textarea',
                    'default' => 'These products are limited, checkout within',
                    'required' => array( 'cart_limited_timer_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Expired Message', 'goldsmith'),
                    'subtitle' => esc_html__('Please enter the expired message in this field.', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'cart_limited_timer_message_expired',
                    'type' => 'textarea',
                    'default' => '',
                    'required' => array( 'cart_limited_timer_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Restart Limited Timer Display', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'cart_limited_timer_restart',
                    'type' => 'switch',
                    'default' => 1,
                    'required' => array( 'cart_limited_timer_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Customize Options', 'goldsmith'),
                    'customizer' => false,
                    'id' => 'cart_limited_timer_customize_divider',
                    'type' => 'info',
                    'style' => 'success',
                    'color' => '#000',
                    'icon' => 'el el-brush',
                    'notice' => true,
                    'required' => array( 'cart_limited_timer_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Background Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'cart_limited_timer_bgcolor',
                    'type' => 'color',
                    'mode' => 'background-color',
                    'default' => '',
                    'output' => array('.goldsmith-summary-item.goldsmith-viewed-offer-time'),
                    'required' => array( 'cart_limited_timer_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Text Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'cart_limited_timer_text_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-viewed-offer-time .offer-time-text'),
                    'required' => array( 'cart_limited_timer_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Timer Color', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'cart_limited_timer_color',
                    'type' => 'color',
                    'default' => '',
                    'output' => array('.goldsmith-viewed-offer-time .goldsmith-cart-timer'),
                    'required' => array( 'cart_limited_timer_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Border', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'cart_limited_timer_border',
                    'type' => 'border',
                    'all' => false,
                    'output' => array('.goldsmith-summary-item.goldsmith-viewed-offer-time'),
                    'required' => array( 'cart_limited_timer_visibility', '=', '1' )
                ),
                array(
                    'title' => esc_html__('Padding', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'cart_limited_timer_padding',
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'all' => false,
                    'output' => array('.goldsmith-summary-item.goldsmith-viewed-offer-time'),
                    'required' => array( 'cart_limited_timer_visibility', '=', '1' )
                ),
            )
        ));

        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('CHECKOUT PAGE', 'goldsmith'),
            'id' => 'shopcheckoutpagessection',
            'subsection' => false,
            'icon' => 'el el-shopping-cart-sign',
            'fields' => array(
                array(
                    'id' =>'checkout_enable_multistep',
                    'type' => 'button_set',
                    'title' => esc_html__('Checkout Page Type', 'goldsmith'),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop checkout page.', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'default' => esc_html__( 'Default', 'goldsmith' ),
                        'multisteps' => esc_html__( 'Multi Steps', 'goldsmith' )
                    ),
                    'default' => 'default'
                ),
                array(
                    'id' =>'checkout_form_customize',
                    'type' => 'button_set',
                    'title' => esc_html__('Custom Checkout Form Fields', 'goldsmith'),
                    'subtitle' => esc_html__('If there is a field in the checkout form that you do not want to be, you can use this option.', 'goldsmith'),
                    'customizer' => true,
                    'options' => array(
                        'yes' => esc_html__('Yes', 'goldsmith'),
                        'no' => esc_html__('No', 'goldsmith')
                    ),
                    'default' => 'no'
                ),
                array(
                    'id' =>'checkout_form_layouts',
                    'type' => 'checkbox',
                    'title' => esc_html__('Checkout Form Manager', 'goldsmith'),
                    'subtitle' => esc_html__('Organize how you want the layout to appear on the theme Checkout Form.', 'goldsmith'),
                    'options' => array(
                        'billing_first_name' => esc_html__('First name ( Billing Form )', 'goldsmith'),
                        'billing_last_name' => esc_html__('Last name ( Billing Form )', 'goldsmith'),
                        'billing_company' => esc_html__('Company ( Billing Form )', 'goldsmith'),
                        'billing_address_1' => esc_html__('Address 1 ( Billing Form )', 'goldsmith'),
                        'billing_address_2' => esc_html__('Address 2 ( Billing Form )', 'goldsmith'),
                        'billing_city' => esc_html__('City ( Billing Form )', 'goldsmith'),
                        'billing_postcode' => esc_html__('Postcode ( Billing Form )', 'goldsmith'),
                        'billing_country' => esc_html__('Country ( Billing Form )', 'goldsmith'),
                        'billing_state' => esc_html__('State ( Billing Form )', 'goldsmith'),
                        'billing_phone' => esc_html__('Phone ( Billing Form )', 'goldsmith'),
                        'billing_email' => esc_html__('Email ( Billing Form )', 'goldsmith'),
                        'order_comments' => esc_html__('Order comments ( Order )', 'goldsmith'),
                        'account_username' => esc_html__('Account username ( Account )', 'goldsmith'),
                        'account_password' => esc_html__('Account password ( Account )', 'goldsmith'),
                        'account_password-2' => esc_html__('Account password 2 ( Account )', 'goldsmith'),
                        'shipping_first_name' => esc_html__('First name ( Shipping Form )', 'goldsmith'),
                        'shipping_last_name' => esc_html__('Last name ( Shipping Form )', 'goldsmith'),
                        'shipping_company' => esc_html__('Company ( Shipping Form )', 'goldsmith'),
                        'shipping_address_1' => esc_html__('Address 1 ( Shipping Form )', 'goldsmith'),
                        'shipping_address_2' => esc_html__('Address 2 ( Shipping Form )', 'goldsmith'),
                        'shipping_city' => esc_html__('City ( Shipping Form )', 'goldsmith'),
                        'shipping_postcode' => esc_html__('Postcode ( Shipping Form )', 'goldsmith'),
                        'shipping_country' => esc_html__('Country ( Shipping Form )', 'goldsmith'),
                        'shipping_state' => esc_html__('State ( Shipping Form )', 'goldsmith')
                    ),
                    'default' => array(
                        'billing_first_name' => '1',
                        'billing_last_name' => '1',
                        'billing_company' => '1',
                        'billing_address_1' => '1',
                        'billing_address_2' => '1',
                        'billing_city' => '1',
                        'billing_postcode' => '1',
                        'billing_country' => '1',
                        'billing_state' => '1',
                        'billing_phone' => '1',
                        'billing_email' => '1',
                        'order_comments' => '1',
                        'account_username' => '1',
                        'account_password' => '1',
                        'account_password-2' => '1',
                        'shipping_first_name' => '1',
                        'shipping_last_name' => '1',
                        'shipping_company' => '1',
                        'shipping_address_1' => '1',
                        'shipping_address_2' => '1',
                        'shipping_city' => '1',
                        'shipping_postcode' => '1',
                        'shipping_country' => '1',
                        'shipping_state' => '1'
                    ),
                    'required' => array('checkout_form_customize', '=', 'yes')
                ),
                array(
                    'id' =>'checkout_form_required_fields_layouts',
                    'type' => 'checkbox',
                    'title' => esc_html__('Required Fields', 'goldsmith'),
                    'options' => array(
                        'billing_first_name' => esc_html__('First name ( Billing Form )', 'goldsmith'),
                        'billing_last_name' => esc_html__('Last name ( Billing Form )', 'goldsmith'),
                        'billing_company' => esc_html__('Company ( Billing Form )', 'goldsmith'),
                        'billing_address_1' => esc_html__('Address 1 ( Billing Form )', 'goldsmith'),
                        'billing_address_2' => esc_html__('Address 2 ( Billing Form )', 'goldsmith'),
                        'billing_city' => esc_html__('City ( Billing Form )', 'goldsmith'),
                        'billing_postcode' => esc_html__('Postcode ( Billing Form )', 'goldsmith'),
                        'billing_country' => esc_html__('Country ( Billing Form )', 'goldsmith'),
                        'billing_state' => esc_html__('State ( Billing Form )', 'goldsmith'),
                        'billing_phone' => esc_html__('Phone ( Billing Form )', 'goldsmith'),
                        'billing_email' => esc_html__('Email ( Billing Form )', 'goldsmith'),
                        'shipping_first_name' => esc_html__('First name ( Shipping Form )', 'goldsmith'),
                        'shipping_last_name' => esc_html__('Last name ( Shipping Form )', 'goldsmith'),
                        'shipping_company' => esc_html__('Company ( Shipping Form )', 'goldsmith'),
                        'shipping_address_1' => esc_html__('Address 1 ( Shipping Form )', 'goldsmith'),
                        'shipping_address_2' => esc_html__('Address 2 ( Shipping Form )', 'goldsmith'),
                        'shipping_city' => esc_html__('City ( Shipping Form )', 'goldsmith'),
                        'shipping_postcode' => esc_html__('Postcode ( Shipping Form )', 'goldsmith'),
                        'shipping_country' => esc_html__('Country ( Shipping Form )', 'goldsmith'),
                        'shipping_state' => esc_html__('State ( Shipping Form )', 'goldsmith')
                    ),
                    'default' => array(
                        'billing_first_name' => '1',
                        'billing_last_name' => '1',
                        'billing_company' => '0',
                        'billing_address_1' => '1',
                        'billing_address_2' => '0',
                        'billing_city' => '1',
                        'billing_postcode' => '1',
                        'billing_country' => '1',
                        'billing_state' => '1',
                        'billing_phone' => '1',
                        'billing_email' => '1',
                        'shipping_first_name' => '1',
                        'shipping_last_name' => '1',
                        'shipping_company' => '0',
                        'shipping_address_1' => '1',
                        'shipping_address_2' => '0',
                        'shipping_city' => '1',
                        'shipping_postcode' => '1',
                        'shipping_country' => '1',
                        'shipping_state' => '1'
                    ),
                    'required' => array('checkout_form_customize', '=', 'yes')
                )
            )
        ));
        Redux::setSection($goldsmith_pre, array(
            'title' => esc_html__('MY ACCOUNT', 'goldsmith'),
            'id' => 'shopmyaccountpagessubsection',
            'subsection' => false,
            'icon' => 'el el-shopping-cart-sign',
            'fields' => array(
                array(
                    'id' =>'myaccount_page_type',
                    'type' => 'button_set',
                    'title' => esc_html__('My Account Page Type', 'goldsmith'),
                    'subtitle' => esc_html__( 'Organize how you want the layout to appear on the theme shop account page.', 'goldsmith' ),
                    'customizer' => true,
                    'options' => array(
                        'default' => esc_html__( 'Default', 'goldsmith' ),
                        'multisteps' => esc_html__( 'Multi Steps', 'goldsmith' ),
                    ),
                    'default' => 'default'
                ),
                array(
                    'title' => esc_html__('Disable New Style', 'goldsmith'),
                    'customizer' => true,
                    'id' => 'disable_account_style',
                    'type' => 'switch',
                    'default' => 0
                )
            )
        ));
    }

    /*************************************************
    ## DEFAULT PAGE SECTION
    *************************************************/
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Default Page', 'goldsmith' ),
        'id' => 'defaultpagesection',
        'icon' => 'el el-home',
        'fields' => array()
    ));
    // BLOG HERO SUBSECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Default Page Hero', 'goldsmith' ),
        'desc' => esc_html__( 'These are default page hero settings!', 'goldsmith' ),
        'id' => 'pageherosubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Page Hero Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site default page hero section with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'page_hero_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Page Hero Background', 'goldsmith' ),
                'customizer' => true,
                'id' => 'page_hero_bg',
                'type' => 'background',
                'preview' => true,
                'preview_media' => true,
                'output' => array( '#nt-page-container .breadcrumb-bg,.nt-page-layout .goldsmith-page-hero' ),
                'required' => array( 'page_hero_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Use Featured Image for Page Hero Background', 'goldsmith' ),
                'customizer' => true,
                'id' => 'page_hero_use_featured_image',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array( 'page_hero_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Page Hero Text Alignment', 'goldsmith' ),
                'customizer' => true,
                'id' => 'page_hero_text_align',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select type', 'goldsmith' ),
                    'left' => esc_html__( 'left', 'goldsmith' ),
                    'center' => esc_html__( 'center', 'goldsmith' ),
                    'right' => esc_html__( 'right', 'goldsmith' ),
                ),
                'default' => '',
                'required' => array( 'page_hero_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Page Hero Title Typography', 'goldsmith' ),
                'id' => 'page_hero_title_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '.page-template-default:not(.elementor-page) .nt-page-layout .goldsmith-page-hero-content .page-title' ),
                'required' => array( 'page_hero_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__('Page Hero Breadcrumbs Color', 'goldsmith'),
                'customizer' => true,
                'id' => 'page_hero_bread_color',
                'type' => 'color',
                'output' => array( '.page-template-default:not(.elementor-page) .goldsmith-breadcrumb li, .page-template-default:not(.elementor-page) .goldsmith-breadcrumb li a' ),
                'required' => array( 'page_hero_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Page Hero Min Height (px)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'page_hero_height',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array( 'page_hero_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Page Hero Min Height (Tablet)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'page_hero_height_tablet',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array( 'page_hero_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Page Hero Min Height (Phone)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'page_hero_height_phone',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array( 'page_hero_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Page Hero Padding', 'goldsmith' ),
                'customizer' => true,
                'id' => 'page_hero_pad',
                'type' => 'spacing',
                'mode' => 'padding',
                'all' => false,
                'units' => array( 'em', 'px', '%' ),
                'units_extended' => 'true',
                'output' => array( '.page-template-default:not(.elementor-page) .nt-page-layout .goldsmith-page-hero' ),
                'required' => array( 'page_hero_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Page Content Custom Container Width', 'goldsmith' ),
                'customizer' => true,
                'id' => 'page_custom_width',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider'
            )
        )
    ));
    /*************************************************
    ## BLOG PAGE SECTION
    *************************************************/
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Blog Posts Page', 'goldsmith' ),
        'id' => 'blogsection',
        'icon' => 'el el-home',
        'fields' => array()
    ));
    // BLOG HERO SUBSECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Blog Hero', 'goldsmith' ),
        'desc' => esc_html__( 'These are blog index page hero text settings!', 'goldsmith' ),
        'id' => 'blogherosubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Blog Hero Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site blog index page hero section with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'blog_hero_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Blog Hero Elementor Templates', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates instead of default template.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'blog_hero_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args
            ),
            array(
                'id' =>'edit_blog_hero_elementor_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Blog Hero Background', 'goldsmith' ),
                'customizer' => true,
                'id' => 'blog_hero_bg',
                'type' => 'background',
                'preview' => true,
                'preview_media' => true,
                'output' => array( '#nt-index .goldsmith-page-hero' ),
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Blog Hero Min Height (px)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'blog_hero_height',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Blog Hero Min Height (Tablet)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'blog_hero_height_tablet',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Blog Hero Min Height (Phone)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'blog_hero_height_phone',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Blog Title', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your blog index page title here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'blog_title',
                'type' => 'text',
                'default' => '',
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Blog Hero Title Typography', 'goldsmith' ),
                'id' => 'blog_hero_title_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '#nt-index .goldsmith-page-hero .goldsmith-page-hero-content .page-title' ),
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Blog Hero Breadcrumbs Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'blog_hero_bread_color',
                'type' => 'color',
                'preview' => true,
                'preview_media' => true,
                'output' => array( '#nt-index .goldsmith-page-hero .goldsmith-breadcrumb li, #nt-index .goldsmith-page-hero .goldsmith-breadcrumb li a,#nt-index .goldsmith-page-hero .woocommerce-breadcrumb a' ),
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Blog Hero Breadcrumbs Link Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'blog_hero_bread_hvrcolor',
                'type' => 'color',
                'preview' => true,
                'preview_media' => true,
                'output' => array( '#nt-index .goldsmith-page-hero .goldsmith-breadcrumb li a:hover,#nt-index .goldsmith-page-hero .woocommerce-breadcrumb a:hover' ),
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Blog Hero Text Alignment', 'goldsmith' ),
                'customizer' => true,
                'id' => 'blog_hero_text_align',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select type', 'goldsmith' ),
                    'left' => esc_html__( 'left', 'goldsmith' ),
                    'center' => esc_html__( 'center', 'goldsmith' ),
                    'right' => esc_html__( 'right', 'goldsmith' ),
                ),
                'default' => '',
                'required' => array(
                    array( 'blog_hero_visibility', '=', '1' ),
                    array( 'blog_hero_templates', '=', '' )
                )
            ),
        )
    ));
    // BLOG LAYOUT AND POST COLUMN STYLE
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Blog Layout', 'goldsmith' ),
        'id' => 'bloglayoutsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Blog Page Layout', 'goldsmith' ),
                'subtitle' => esc_html__( 'Choose the blog index page layout.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'index_layout',
                'type' => 'image_select',
                'options' => array(
                    'left-sidebar' => array(
                        'alt' => 'Left Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cl.png'
                    ),
                    'full-width' => array(
                        'alt' => 'Full Width',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/1col.png'
                    ),
                    'right-sidebar' => array(
                        'alt' => 'Right Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cr.png'
                    )
                ),
                'default' => 'right-sidebar'
            ),
            array(
                'title' => esc_html__( 'Blog Sidebar Elementor Templates', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can use elementor templates instead of default sidebar if you want.Select a template from elementor templates.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'blog_sidebar_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array( 'index_layout', '!=', 'full-width' )
            ),
            array(
                'id' =>'edit_sidebar_elementor_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'index_layout', '!=', 'full-width' ),
                    array( 'blog_sidebar_templates', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Container Width', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select blog page container width type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'index_container_type',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select type', 'goldsmith' ),
                    'container' => esc_html__( 'Default Boxed', 'goldsmith' ),
                    'container-fluid' => esc_html__( 'Fluid', 'goldsmith' )
                ),
                'default' => 'container'
            )
    	)
    ));
    // BLOG LAYOUT AND POST COLUMN STYLE
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Blog Post', 'goldsmith' ),
        'id' => 'blogpostsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Layout Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select blog page layout type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'index_type',
                'type' => 'select',
                'options' => array(
                    'grid' => esc_html__( 'grid', 'goldsmith' ),
                    'masonry' => esc_html__( 'masonry', 'goldsmith' )
                ),
                'default' => 'masonry',
                'select2' => array('select2' => array( 'allowClear' => false ) )
            ),
            array(
                'title' => esc_html__( 'Post Style', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select blog page post style type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'post_style',
                'type' => 'select',
                'options' => array(
                    'classic' => esc_html__( 'Classic', 'goldsmith' ),
                    'card' => esc_html__( 'Card', 'goldsmith' ),
                    'split' => esc_html__( 'Split', 'goldsmith' )
                ),
                'default' => 'classic',
                'select2' => array('select2' => array( 'allowClear' => false ) )
            ),
            array(
                'title' => esc_html__( 'Post Image Size Style', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select blog page post image size style type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'post_image_style',
                'type' => 'select',
                'options' => array(
                    'default' => esc_html__( 'Default', 'goldsmith' ),
                    'fit' => esc_html__( 'Fit', 'goldsmith' ),
                    'split' => esc_html__( 'Split', 'goldsmith' )
                ),
                'default' => 'default',
                'select2' => array('select2' => array( 'allowClear' => false ) )
            ),
            array(
                'title' => esc_html__( 'Post Overlay Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'post_card_style_overlay_color',
                'type' => 'color_rgba',
                'mode' => 'background-color',
                'output' => array( '.goldsmith-blog-posts-item.style-card .goldsmith-blog-post-item-inner:before' ),
                'required' => array( 'post_style', '=', 'card' )
            ),
            array(
                'title' => esc_html__( 'Post Min Height', 'goldsmith' ),
                'subtitle' => esc_html__( 'Set the logo width and height of the image.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'post_card_style_height',
                'type' => 'dimensions',
                'width' => false,
                'output' => array('.goldsmith-blog-posts-item.style-card .goldsmith-blog-post-item-inner' ),
                'required' => array( 'post_style', '=', 'card' )
            ),
            array(
                'title' => esc_html__( 'Column Width', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a column width.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'grid_column',
                'type' => 'select',
                'options' => array(
                    '1' => esc_html__( '1 column', 'goldsmith' ),
                    '2' => esc_html__( '2 column', 'goldsmith' ),
                    '3' => esc_html__( '3 column', 'goldsmith' ),
                    '4' => esc_html__( '4 column', 'goldsmith' )
                ),
                'default' => '3',
                'select2' => array('select2' => array( 'allowClear' => false ) )
            ),
            array(
                'title' => esc_html__( 'Column Width (Tablet)', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a column width for mobile device.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'grid_mobile_column',
                'type' => 'select',
                'options' => array(
                    '1' => esc_html__( '1 column', 'goldsmith' ),
                    '2' => esc_html__( '2 column', 'goldsmith' ),
                    '3' => esc_html__( '3 column', 'goldsmith' )
                ),
                'default' => '2',
                'select2' => array('select2' => array( 'allowClear' => false ) )
            ),
            array(
                'title' => esc_html__( 'Column Width (Phone)', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a column width for mobile device.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'grid_mobile_column_xs',
                'type' => 'select',
                'options' => array(
                    '1' => esc_html__( '1 column', 'goldsmith' ),
                    '2' => esc_html__( '2 column', 'goldsmith' ),
                    '3' => esc_html__( '3 column', 'goldsmith' )
                ),
                'default' => '1',
                'select2' => array('select2' => array( 'allowClear' => false ) )
            ),
            array(
                'title' => esc_html__( 'Post Image Size', 'goldsmith' ),
                'customizer' => true,
                'id' => 'post_imgsize',
                'type' => 'select',
                'data' => 'image_sizes'
            ),
            array(
                'title' => esc_html__( 'Custom Post Image Size', 'goldsmith' ),
                'customizer' => true,
                'id' => 'post_custom_imgsize',
                'unit' => false,
                'type' => 'dimensions'
            ),
            array(
                'title' => esc_html__( 'Post Title Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site blog index page post title with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'post_title_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Excerpt Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site blog index page post meta with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'post_excerpt_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Excerpt Size (max word count)', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can control blog post excerpt size with this option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'post_excerpt_limit',
                'type' => 'slider',
                'default' => 30,
                'min' => 0,
                'step' => 1,
                'max' => 100000,
                'display_value' => 'text',
                'required' => array( 'post_excerpt_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Button Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site blog index page post read more button wityh switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'post_button_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Read More Button Title', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your blog post read more button title here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'post_button_title',
                'type' => 'text',
                'default' => '',
                'required' => array( 'post_button_visibility', '=', '1' )
            )
    	)
    ));

    /*************************************************
    ## SINGLE PAGE SECTION
    *************************************************/
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Single Post Page', 'goldsmith' ),
        'id' => 'singlesection',
        'icon' => 'el el-home-alt',
        'fields' => array()
    ));
    // SINGLE HERO SUBSECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Single Hero', 'goldsmith' ),
        'desc' => esc_html__( 'These are single page hero section settings!', 'goldsmith' ),
        'id' => 'singleherosubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Single Hero Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page hero section with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_hero_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates instead of default template.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_hero_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array( 'single_hero_visibility', '=', '1' )
            ),
            array(
                'id' =>'edit_single_hero_template',
                'type' => 'info',
                'desc' => 'Select template',
                'required' => array(
                    array( 'single_hero_visibility', '=', '1' ),
                    array( 'single_hero_elementor_templates', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Single Hero Background', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_hero_bg',
                'type' => 'background',
                'output' => array( '#nt-single .goldsmith-page-hero' ),
                'required' => array(
                    array( 'single_hero_visibility', '=', '1' ),
                    array( 'single_hero_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Single Hero Min Height (px)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_hero_height',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array(
                    array( 'single_hero_visibility', '=', '1' ),
                    array( 'single_hero_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Single Hero Min Height (Tablet)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_hero_height_tablet',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array(
                    array( 'single_hero_visibility', '=', '1' ),
                    array( 'single_hero_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Single Hero Min Height (Phone)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_hero_height_phone',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array(
                    array( 'single_hero_visibility', '=', '1' ),
                    array( 'single_hero_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Single Hero Breadcrumbs Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_hero_bread_color',
                'type' => 'color',
                'preview' => true,
                'preview_media' => true,
                'output' => array( '#nt-single .goldsmith-page-hero .goldsmith-breadcrumb li, #nt-single .goldsmith-page-hero .goldsmith-breadcrumb li a,#nt-single .goldsmith-page-hero .woocommerce-breadcrumb a' ),
                'required' => array(
                    array( 'single_hero_visibility', '=', '1' ),
                    array( 'single_hero_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Single Hero Breadcrumbs Link Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_hero_bread_hvrcolor',
                'type' => 'color',
                'preview' => true,
                'preview_media' => true,
                'output' => array( '#nt-single .goldsmith-page-hero .goldsmith-breadcrumb li a:hover,#nt-single .goldsmith-page-hero .woocommerce-breadcrumb a:hover' ),
                'required' => array(
                    array( 'single_hero_visibility', '=', '1' ),
                    array( 'single_hero_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Single Hero Text Alignment', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_hero_text_align',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select type', 'goldsmith' ),
                    'left' => esc_html__( 'left', 'goldsmith' ),
                    'center' => esc_html__( 'center', 'goldsmith' ),
                    'right' => esc_html__( 'right', 'goldsmith' ),
                ),
                'default' => '',
                'required' => array(
                    array( 'single_hero_visibility', '=', '1' ),
                    array( 'single_hero_elementor_templates', '=', '' )
                )
            ),
    	)
    ));
    // SINGLE CONTENT SUBSECTION
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Single Content', 'goldsmith' ),
        'id' => 'singlecontentsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Post Page Layout', 'goldsmith' ),
                'subtitle' => esc_html__( 'Choose the single post page layout.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_layout',
                'type' => 'image_select',
                'options' => array(
                    'left-sidebar' => array(
                        'alt' => 'Left Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cl.png'
                    ),
                    'full-width' => array(
                        'alt' => 'Full Width',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/1col.png'
                    ),
                    'right-sidebar' => array(
                        'alt' => 'Right Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cr.png'
                    )
                ),
                'default' => 'right-sidebar'
            ),
            array(
                'title' => esc_html__( 'Author Name Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page post date with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_postmeta_author_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Date Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page post date number with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_postmeta_date_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Categories Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page post meta tags with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_postmeta_category_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Tags Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page post meta tags with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_postmeta_tags_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Authorbox Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page post authorbox with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_post_author_box_visibility',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Post Pagination Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page post next and prev pagination with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_navigation_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            )
    	)
    ));
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Single Related Posts', 'goldsmith' ),
        'id' => 'singlerelatedsubsection',
        'subsection' => true,
        'icon' => 'el el-brush',
        'fields' => array(
            array(
                'title' => esc_html__( 'Related Post Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site single page related post with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_related_visibility',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates instead of default related post template.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'single_related_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array( 'single_related_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Post Style', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select single page related post style type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_post_style',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select style', 'goldsmith' ),
                    'default' => esc_html__( 'Classic', 'goldsmith' ),
                    'card' => esc_html__( 'Card', 'goldsmith' ),
                    'split' => esc_html__( 'Split', 'goldsmith' )
                ),
                'default' => 'default',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'id' => 'related_section_heading_start',
                'type' => 'section',
                'title' => esc_html__('Related Section Heading', 'goldsmith'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Related Section Subtitle', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your single page related post section subtitle here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_subtitle',
                'type' => 'text',
                'default' => '',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Subtitle Tag', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_subtitle_tag',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select type', 'goldsmith' ),
                    'h1' => esc_html__( 'H1', 'goldsmith' ),
                    'h2' => esc_html__( 'H2', 'goldsmith' ),
                    'h3' => esc_html__( 'H3', 'goldsmith' ),
                    'h4' => esc_html__( 'H4', 'goldsmith' ),
                    'h5' => esc_html__( 'H5', 'goldsmith' ),
                    'h6' => esc_html__( 'H6', 'goldsmith' ),
                    'p' => esc_html__( 'p', 'goldsmith' ),
                    'div' => esc_html__( 'div', 'goldsmith' ),
                    'span' => esc_html__( 'span', 'goldsmith' )
                ),
                'default' => 'p',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' ),
                    array( 'related_subtitle', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Related Section Title', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your single page related post section title here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_title',
                'type' => 'text',
                'default' => esc_html__( 'Related Post', 'goldsmith' ),
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Title Tag', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_title_tag',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select type', 'goldsmith' ),
                    'h1' => esc_html__( 'H1', 'goldsmith' ),
                    'h2' => esc_html__( 'H2', 'goldsmith' ),
                    'h3' => esc_html__( 'H3', 'goldsmith' ),
                    'h4' => esc_html__( 'H4', 'goldsmith' ),
                    'h5' => esc_html__( 'H5', 'goldsmith' ),
                    'h6' => esc_html__( 'H6', 'goldsmith' ),
                    'p' => esc_html__( 'p', 'goldsmith' ),
                    'div' => esc_html__( 'div', 'goldsmith' ),
                    'span' => esc_html__( 'span', 'goldsmith' )
                ),
                'default' => 'h3',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' ),
                    array( 'related_title', '!=', '' )
                )
            ),
            array(
                'id' => 'related_section_heading_end',
                'customizer' => true,
                'type' => 'section',
                'indent' => false,
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'id' => 'related_section_posts_start',
                'type' => 'section',
                'title' => esc_html__('Related Post Options', 'goldsmith'),
                'customizer' => true,
                'indent' => true
            ),
            array(
                'title' => esc_html__( 'Posts Perpage', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can control related post count with this option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_perpage',
                'type' => 'slider',
                'default' => 3,
                'min' => 1,
                'step' => 1,
                'max' => 24,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Post Image Size', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_imgsize',
                'type' => 'select',
                'data' => 'image_sizes',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Post Excerpt Display', 'goldsmith' ),
                'id' => 'related_excerpt_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Post Excerpt Limit', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can control related post excerpt word limit.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_excerpt_limit',
                'type' => 'slider',
                'default' => 30,
                'min' => 0,
                'step' => 1,
                'max' => 100,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' ),
                    array( 'related_excerpt_visibility', '=', '1' )
                )
            ),
            array(
                'id' => 'related_section_posts_end',
                'customizer' => true,
                'type' => 'section',
                'indent' => false,
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'id' => 'related_section_slider_start',
                'type' => 'section',
                'title' => esc_html__('Related Slider Options', 'goldsmith'),
                'customizer' => true,
                'indent' => true,
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Perview ( Min 1200px )', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_perview',
                'type' => 'slider',
                'default' => 5,
                'min' => 1,
                'step' => 1,
                'max' => 10,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Slider Perview ( Min 992px )', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_mdperview',
                'type' => 'slider',
                'default' => 3,
                'min' => 1,
                'step' => 1,
                'max' => 10,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Perview ( Min 768px )', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_smperview',
                'type' => 'slider',
                'default' => 3,
                'min' => 1,
                'step' => 1,
                'max' => 10,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Perview ( Min 480px )', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can control related post slider item count for big device with this option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_xsperview',
                'type' => 'slider',
                'default' => 2,
                'min' => 1,
                'step' => 1,
                'max' => 10,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Speed', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can control related post slider item gap.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_speed',
                'type' => 'slider',
                'default' => 1000,
                'min' => 100,
                'step' => 1,
                'max' => 10000,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Gap', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can control related post slider item gap.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_gap',
                'type' => 'slider',
                'default' => 30,
                'min' => 0,
                'step' => 1,
                'max' => 100,
                'display_value' => 'text',
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Centered', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_centered',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Autoplay', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_autoplay',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Loop', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_loop',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'title' => esc_html__( 'Mousewheel', 'goldsmith' ),
                'customizer' => true,
                'id' => 'related_mousewheel',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            ),
            array(
                'id' => 'related_section_slider_end',
                'customizer' => true,
                'type' => 'section',
                'indent' => false,
                'required' => array(
                    array( 'single_related_visibility', '=', '1' ),
                    array( 'single_related_elementor_templates', '=', '' )
                )
            )
    	)
    ));
    /*************************************************
    ## ARCHIVE PAGE SECTION
    *************************************************/
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Archive Page', 'goldsmith' ),
        'id' => 'archivesection',
        'icon' => 'el el-folder-open',
        'fields' => array(
            array(
                'title' => esc_html__( 'Archive Page Layout', 'goldsmith' ),
                'subtitle' => esc_html__( 'Choose the archive page layout.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'archive_layout',
                'type' => 'image_select',
                'options' => array(
                    'left-sidebar' => array(
                        'alt' => 'Left Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cl.png'
                    ),
                    'full-width' => array(
                        'alt' => 'Full Width',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/1col.png'
                    ),
                    'right-sidebar' => array(
                        'alt' => 'Right Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cr.png'
                    )
                ),
                'default' => 'full-width'
            ),
            array(
                'title' => esc_html__( 'Archive Hero Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site archive page hero section with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'archive_hero_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Archive Hero Background', 'goldsmith' ),
                'customizer' => true,
                'id' => 'archive_hero_bg',
                'type' => 'background',
                'output' => array( '#nt-archive .goldsmith-page-hero' ),
                'required' => array( 'archive_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Custom Archive Title', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your custom archive page title here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'archive_title',
                'type' => 'text',
                'default' =>'',
                'required' => array( 'archive_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Title Typography', 'goldsmith' ),
                'id' => 'archive_hero_title_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '#nt-archive .goldsmith-page-hero .goldsmith-page-hero-content .page-title' ),
                'required' => array( 'archive_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Min Height (px)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'archive_hero_height',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array( 'archive_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Min Height (Tablet)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'archive_hero_height_tablet',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array( 'archive_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Min Height (Phone)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'archive_hero_height_phone',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array( 'archive_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Breadcrumbs Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'archive_hero_bread_color',
                'type' => 'color',
                'preview' => true,
                'preview_media' => true,
                'output' => array( '#nt-archive .goldsmith-page-hero .goldsmith-breadcrumb li, #nt-archive .goldsmith-page-hero .goldsmith-breadcrumb li a,#nt-archive .goldsmith-page-hero .woocommerce-breadcrumb a' ),
                'required' => array( 'archive_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Breadcrumbs Link Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'archive_hero_bread_hvrcolor',
                'type' => 'color',
                'preview' => true,
                'preview_media' => true,
                'output' => array( '#nt-archive .goldsmith-page-hero .goldsmith-breadcrumb li a:hover,#nt-archive .goldsmith-page-hero .woocommerce-breadcrumb a:hover' ),
                'required' => array( 'archive_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Text Alignment', 'goldsmith' ),
                'customizer' => true,
                'id' => 'archive_hero_text_align',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select type', 'goldsmith' ),
                    'left' => esc_html__( 'left', 'goldsmith' ),
                    'center' => esc_html__( 'center', 'goldsmith' ),
                    'right' => esc_html__( 'right', 'goldsmith' ),
                ),
                'default' => '',
                'required' => array( 'archive_hero_visibility', '=', '1' ),
            ),
    	)
    ));
    /*************************************************
    ## SEARCH PAGE SECTION
    *************************************************/
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( 'Search Page', 'goldsmith' ),
        'id' => 'searchsection',
        'icon' => 'el el-search',
        'fields' => array(
            array(
                'title' => esc_html__( 'Search Page Layout', 'goldsmith' ),
                'subtitle' => esc_html__( 'Choose the search page layout.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'search_layout',
                'type' => 'image_select',
                'options' => array(
                    'left-sidebar' => array(
                        'alt' => 'Left Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cl.png'
                    ),
                    'full-width' => array(
                        'alt' => 'Full Width',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/1col.png'
                    ),
                    'right-sidebar' => array(
                        'alt' => 'Right Sidebar',
                        'img' => get_template_directory_uri() . '/inc/core/theme-options/img/2cr.png'
                    )
                ),
                'default' => 'full-width'
            ),
            array(
                'title' => esc_html__( 'Search Hero Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site search page hero section with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'search_hero_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' )
            ),
            array(
                'title' => esc_html__( 'Search Hero Background', 'goldsmith' ),
                'customizer' => true,
                'id' =>'search_hero_bg',
                'type' => 'background',
                'output' => array( '#nt-search .goldsmith-page-hero' ),
                'required' => array( 'search_hero_visibility', '=', '1' )
            ),
            array(
                'title' => esc_html__( 'Custom Search Title', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your custom archive page title here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'search_title',
                'type' => 'text',
                'default' =>'',
                'required' => array( 'search_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Search Hero Title Typography', 'goldsmith' ),
                'id' => 'search_hero_title_typo',
                'type' => 'typography',
                'font-backup' => false,
                'letter-spacing' => true,
                'text-transform' => true,
                'all_styles' => true,
                'output' => array( '#nt-search .goldsmith-page-hero .goldsmith-page-hero-content .page-title' ),
                'required' => array( 'search_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Min Height (px)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'search_hero_height',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array( 'search_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Min Height (Tablet)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'search_hero_height_tablet',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array( 'search_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Min Height (Phone)', 'goldsmith' ),
                'customizer' => true,
                'id' => 'search_hero_height_phone',
                'default' => '',
                'min' => 0,
                'step' => 1,
                'max' => 4000,
                'type' => 'slider',
                'required' => array( 'search_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Breadcrumbs Color', 'goldsmith' ),
                'customizer' => true,
                'id' => 'search_hero_bread_color',
                'type' => 'color',
                'preview' => true,
                'preview_media' => true,
                'output' => array( '#nt-search .goldsmith-page-hero .goldsmith-breadcrumb li, #nt-search .goldsmith-page-hero .goldsmith-breadcrumb li a,#nt-search .goldsmith-page-hero .woocommerce-breadcrumb a' ),
                'required' => array( 'search_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Breadcrumbs Link Color ( Hover )', 'goldsmith' ),
                'customizer' => true,
                'id' => 'search_hero_bread_hvrcolor',
                'type' => 'color',
                'preview' => true,
                'preview_media' => true,
                'output' => array( '#nt-search .goldsmith-page-hero .goldsmith-breadcrumb li a:hover,#nt-search .goldsmith-page-hero .woocommerce-breadcrumb a:hover' ),
                'required' => array( 'search_hero_visibility', '=', '1' ),
            ),
            array(
                'title' => esc_html__( 'Archive Hero Text Alignment', 'goldsmith' ),
                'customizer' => true,
                'id' => 'search_hero_text_align',
                'type' => 'select',
                'options' => array(
                    '' => esc_html__( 'Select type', 'goldsmith' ),
                    'left' => esc_html__( 'left', 'goldsmith' ),
                    'center' => esc_html__( 'center', 'goldsmith' ),
                    'right' => esc_html__( 'right', 'goldsmith' ),
                ),
                'default' => '',
                'required' => array( 'search_hero_visibility', '=', '1' ),
            ),
    	)
    ));
    /*************************************************
    ## 404 PAGE SECTION
    *************************************************/
    Redux::setSection($goldsmith_pre, array(
        'title' => esc_html__( '404 Page', 'goldsmith' ),
        'id' => 'errorsection',
        'icon' => 'el el-error',
        'fields' => array(
            array(
                'title' => esc_html__( '404 Type', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select your 404 page type.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'error_page_type',
                'type' => 'select',
                'options' => array(
                    'default' => esc_html__( 'Deafult', 'goldsmith' ),
                    'elementor' => esc_html__( 'Elementor Templates', 'goldsmith' )
                ),
                'default' => 'default'
            ),
            array(
                'title' => esc_html__( 'Elementor Templates', 'goldsmith' ),
                'subtitle' => esc_html__( 'Select a template from elementor templates.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'error_page_elementor_templates',
                'type' => 'select',
                'data' => 'posts',
                'args'  => $el_args,
                'required' => array( 'error_page_type', '=', 'elementor' )
            ),
            array(
                'id' =>'edit_error_page_template',
                'type' => 'info',
                'desc' => 'Edit template',
                'required' => array(
                    array( 'error_page_type', '=', 'elementor' ),
                    array( 'error_page_elementor_templates', '!=', '' )
                )
            ),
            array(
                'title' => esc_html__( '404 Header Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site 404 page header with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'error_header_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array( 'error_page_type', '=', 'elementor' )
            ),
            array(
                'title' => esc_html__( '404 Footer Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site 404 page footer with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'error_footer_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array( 'error_page_type', '=', 'elementor' )
            ),
            array(
                'title' => esc_html__( '404 Hero Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site 404 page hero section with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'error_hero_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array( 'error_page_type', '=', 'default' )
            ),
            array(
                'title' => esc_html__( '404 Hero Background', 'goldsmith' ),
                'customizer' => true,
                'id' => 'error_hero_bg',
                'type' => 'background',
                'output' => array( '#nt-archive .goldsmith-page-hero' ),
                'required' => array(
                    array( 'error_page_type', '=', 'default' ),
                    array( 'error_hero_visibility', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Custom 404 Title', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your custom 404 page title here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'error_title',
                'type' => 'text',
                'default' =>'',
                'required' => array(
                    array( 'error_page_type', '=', 'default' ),
                    array( 'error_hero_visibility', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Content Description Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site 404 page content description with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'error_content_desc_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array( 'error_page_type', '=', 'default' )
            ),
            array(
                'title' => esc_html__( 'Content Description', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your 404 page content description here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'error_content_desc',
                'type' => 'textarea',
                'default' => '',
                'required' => array(
                    array( 'error_page_type', '=', 'default' ),
                    array( 'error_content_desc_visibility', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Button Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site 404 page content back to home button with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'error_content_btn_visibility',
                'type' => 'switch',
                'default' => 1,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array( 'error_page_type', '=', 'default' )
            ),
            array(
                'title' => esc_html__( 'Button Title', 'goldsmith' ),
                'subtitle' => esc_html__( 'Add your 404 page content back to home button title here.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'error_content_btn_title',
                'type' => 'text',
                'default' => '',
                'required' => array(
                    array( 'error_page_type', '=', 'default' ),
                    array( 'error_content_btn_visibility', '=', '1' )
                )
            ),
            array(
                'title' => esc_html__( 'Search Form Display', 'goldsmith' ),
                'subtitle' => esc_html__( 'You can enable or disable the site 404 page content search form with switch option.', 'goldsmith' ),
                'customizer' => true,
                'id' => 'error_content_form_visibility',
                'type' => 'switch',
                'default' => 0,
                'on' => esc_html__( 'On', 'goldsmith' ),
                'off' => esc_html__( 'Off', 'goldsmith' ),
                'required' => array( 'error_page_type', '=', 'default' )
            )
    	)
    ));

    Redux::setSection($goldsmith_pre, array(
        'id' => 'inportexport_settings',
        'title' => esc_html__( 'Import / Export', 'goldsmith' ),
        'desc' => esc_html__( 'Import and Export your Theme Options from text or URL.', 'goldsmith' ),
        'icon' => 'fa fa-download',
        'fields' => array(
            array(
                'id' => 'opt-import-export',
                'type' => 'import_export',
                'title' => '',
                'customizer' => false,
                'subtitle' => '',
                'full_width' => true
            )
    	)
    ));
    Redux::setSection($goldsmith_pre, array(
	    'id' => 'nt_support_settings',
	    'title' => esc_html__( 'Support', 'goldsmith' ),
	    'icon' => 'el el-idea',
	    'fields' => array(
	        array(
	            'id' => 'doc',
	            'type' => 'raw',
	            'markdown' => true,
	            'class' => 'theme_support',
	            'content' => '<div class="support-section">
	            <h5>'.esc_html__( 'WE RECOMMEND YOU READ IT BEFORE YOU START', 'goldsmith' ).'</h5>
	            <h2><i class="el el-website"></i> '.esc_html__( 'DOCUMENTATION', 'goldsmith' ).'</h2>
	            <a target="_blank" class="button" href="https://ninetheme.com/docs/goldsmith/">'.esc_html__( 'READ MORE', 'goldsmith' ).'</a>
	            </div>'
	        ),
	        array(
	            'id' => 'support',
	            'type' => 'raw',
	            'markdown' => true,
	            'class' => 'theme_support',
	            'content' => '<div class="support-section">
	            <h5>'.esc_html__( 'DO YOU NEED HELP?', 'goldsmith' ).'</h5>
	            <h2><i class="el el-adult"></i> '.esc_html__( 'SUPPORT CENTER', 'goldsmith' ).'</h2>
	            <a target="_blank" class="button" href="https://ninetheme.com/contact/">'.esc_html__( 'GET SUPPORT', 'goldsmith' ).'</a>
	            </div>'
	        ),
	        array(
	            'id' => 'portfolio',
	            'type' => 'raw',
	            'markdown' => true,
	            'class' => 'theme_support',
	            'content' => '<div class="support-section">
	            <h5>'.esc_html__( 'SEE MORE THE NINETHEME WORDPRESS THEMES', 'goldsmith' ).'</h5>
	            <h2><i class="el el-picture"></i> '.esc_html__( 'NINETHEME PORTFOLIO', 'goldsmith' ).'</h2>
	            <a target="_blank" class="button" href="https://ninetheme.com/themes/">'.esc_html__( 'SEE MORE', 'goldsmith' ).'</a>
	            </div>'
	        ),
	        array(
	            'id' => 'like',
	            'type' => 'raw',
	            'markdown' => true,
	            'class' => 'theme_support',
	            'content' => '<div class="support-section">
	            <h5>'.esc_html__( 'WOULD YOU LIKE TO REWARD OUR EFFORT?', 'goldsmith' ).'</h5>
	            <h2><i class="el el-thumbs-up"></i> '.esc_html__( 'PLEASE RATE US!', 'goldsmith' ).'</h2>
	            <a target="_blank" class="button" href="https://themeforest.net/downloads/">'.esc_html__( 'GET STARS', 'goldsmith' ).'</a>
	            </div>'
	        )
	    )
    ));
    /*
     * <--- END SECTIONS
     */


    /** Action hook examples **/

    function goldsmith_remove_demo()
    {
        // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
        if (class_exists('ReduxFrameworkPlugin' )) {
            // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
            remove_action('admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ));
        }
    }
    //include get_template_directory() . '/inc/core/theme-options/redux-extensions/loader.php';
    function goldsmith_newIconFont() {
        // Uncomment this to remove elusive icon from the panel completely
        // wp_deregister_style( 'redux-elusive-icon' );
        // wp_deregister_style( 'redux-elusive-icon-ie7' );
        wp_register_style(
            'redux-font-awesome',
            '//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
            array(),
            time(),
            'all'
        );
        wp_enqueue_style( 'redux-font-awesome' );
    }
    add_action( 'redux/page/goldsmith/enqueue', 'goldsmith_newIconFont' );
