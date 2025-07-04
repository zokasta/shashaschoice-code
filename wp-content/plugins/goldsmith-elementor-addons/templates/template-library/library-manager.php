<?php
namespace Goldsmith_Addons\Elementor;

use Elementor\Core\Common\Modules\Ajax\Module as Ajax;

defined('ABSPATH') || die();

class Library_Manager {

    protected static $source = null;

    public static function init() {
        add_action( 'elementor/editor/footer', [ __CLASS__, 'print_template_views' ] );
        add_action( 'elementor/ajax/register_actions', [ __CLASS__, 'register_ajax_actions' ] );
        // Enqueue editor scripts
        add_action('elementor/editor/after_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
    }

    public static function print_template_views() {

        include_once( GOLDSMITH_PLUGIN_PATH . 'templates/template-library/templates.php' );

    }

    public static function enqueue_assets() {
        wp_enqueue_style(
            'goldsmith-addons-templates-library',
            GOLDSMITH_PLUGIN_URL . 'templates/template-library/js-css/template-library.css',
            [
                'elementor-editor',
            ],
            GOLDSMITH_PLUGIN_VERSION
        );
        wp_enqueue_script(
            'goldsmith-elementor-addons-editor',
            GOLDSMITH_PLUGIN_URL . 'templates/template-library/js-css/editor.min.js',
            ['elementor-editor', 'jquery'],
            GOLDSMITH_PLUGIN_VERSION,
            true
        );
        wp_enqueue_script(
            'goldsmith-addons-templates-library',
            GOLDSMITH_PLUGIN_URL . 'templates/template-library/js-css/template-library.min.js',
            [
                'goldsmith-elementor-addons-editor',
                'jquery-hover-intent',
            ],
            GOLDSMITH_PLUGIN_VERSION,
            true
        );
        $localize_data = [
            'placeholder_widgets' => [],
            //*'hasPro'                  => goldsmith_has_pro(),
            'editor_nonce'            => wp_create_nonce('goldsmith_editor_nonce'),
            //'dark_stylesheet_url'     => self::get_dark_stylesheet_url(),
            'i18n' => [
                'promotionDialogHeader'     => esc_html__('%s Widget', 'goldsmith'),
                'promotionDialogMessage'    => esc_html__('Use %s widget with other exclusive pro widgets and 100% unique features to extend your toolbox and build sites faster and better.', 'goldsmith'),
                'templatesEmptyTitle'       => esc_html__('No Templates Found', 'goldsmith'),
                'templatesEmptyMessage'     => esc_html__('Try different category or sync for new templates.', 'goldsmith'),
                'templatesNoResultsTitle'   => esc_html__('No Results Found', 'goldsmith'),
                'templatesNoResultsMessage' => esc_html__('Please make sure your search is spelled correctly or try a different words.', 'goldsmith'),
            ],
        ];
        wp_localize_script(
            'goldsmith-elementor-addons-editor',
            'GoldsmithAddonsEditor',
            $localize_data
        );
    }

    /**
    * Undocumented function
    *
    * @return Library_Source
    */
    public static function get_source() {
        if ( is_null( self::$source ) ) {
            self::$source = new Library_Source();
        }

        return self::$source;
    }

    public static function register_ajax_actions( Ajax $ajax ) {
        $ajax->register_ajax_action( 'get_goldsmith_library_data', function( $data ) {
            if ( ! current_user_can( 'edit_posts' ) ) {
                throw new \Exception( 'Access Denied' );
            }

            if ( ! empty( $data['editor_post_id'] ) ) {
                $editor_post_id = absint( $data['editor_post_id'] );

                if ( ! get_post( $editor_post_id ) ) {
                    throw new \Exception( __( 'Post not found.', 'goldsmith' ) );
                }

                \Elementor\Plugin::instance()->db->switch_to_post( $editor_post_id );
            }

            $result = self::get_library_data( $data );

            return $result;
        } );

        $ajax->register_ajax_action( 'get_goldsmith_template_data', function( $data ) {
            if ( ! current_user_can( 'edit_posts' ) ) {
                throw new \Exception( 'Access Denied' );
            }

            if ( ! empty( $data['editor_post_id'] ) ) {
                $editor_post_id = absint( $data['editor_post_id'] );

                if ( ! get_post( $editor_post_id ) ) {
                    throw new \Exception( __( 'Post not found', 'goldsmith' ) );
                }

                \Elementor\Plugin::instance()->db->switch_to_post( $editor_post_id );
            }

            if ( empty( $data['template_id'] ) ) {
                throw new \Exception( __( 'Template id missing', 'goldsmith' ) );
            }

            $result = self::get_template_data( $data );

            return $result;
        } );
    }

    public static function get_template_data( array $args ) {
        $source = self::get_source();
        $data = $source->get_data( $args );
        return $data;
    }

    /**
    * Get library data from cache or remote
    *
    * type_tags has been added in version 2.15.0
    *
    * @param array $args
    *
    * @return array
    */
    public static function get_library_data( array $args ) {
        $source = self::get_source();

        if ( ! empty( $args['sync'] ) ) {
            Library_Source::get_library_data( true );
        }

        return [
            'templates' => $source->get_items(),
            'tags'      => $source->get_tags(),
            'type_tags' => $source->get_type_tags(),
        ];
    }
}

Library_Manager::init();
