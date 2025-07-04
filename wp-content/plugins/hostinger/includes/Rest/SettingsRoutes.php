<?php

namespace Hostinger\Rest;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

use Hostinger\Admin\Options\PluginOptions;
use Hostinger\Admin\PluginSettings;
use Hostinger\Helper;

/**
 * Class for handling Settings Rest API
 */
class SettingsRoutes {


    /**
     * @var PluginSettings plugin settings.
     */
    private PluginSettings $plugin_settings;

    /**
     * Construct class with dependencies
     *
     * @param PluginSettings $plugin_settings instance.
     */
    public function __construct( PluginSettings $plugin_settings ) {
        $this->plugin_settings = $plugin_settings;
    }

    /**
     * Return all stored plugin settings
     *
     * @param WP_REST_Request $request WordPress rest request.
     *
     * @return \WP_REST_Response
     */

    /** PHPCS:disable Generic.CodeAnalysis.UnusedFunctionParameter.Found */
    public function get_settings( \WP_REST_Request $request ): \WP_REST_Response {
        global $wp_version;

        $data = array(
            'newest_wp_version'        => $this->get_latest_wordpress_version(),
            'current_wp_version'       => $wp_version,
            'php_version'              => phpversion(),
            'newest_php_version'       => '8.2', // Will be refactored.
            'is_eligible_www_redirect' => $this->is_eligible_www_redirect(),
        );

        $hostinger_plugin_settings = get_option( HOSTINGER_PLUGIN_SETTINGS_OPTION, array() );

        if ( empty( $this->plugin_settings->get_plugin_settings()->get_bypass_code() ) ) {
            if ( empty( $hostinger_plugin_settings['bypass_code'] ) ) {
                $hostinger_plugin_settings['bypass_code'] = Helper::generate_bypass_code( 16 );
            }
        }

        $plugin_settings = $this->plugin_settings->get_plugin_settings()->to_array();

        $response = array(
            'data' => array_merge( $data, $plugin_settings ),
        );

        $response = new \WP_REST_Response( $response );

        $response->set_headers( array( 'Cache-Control' => 'no-cache' ) );

        $response->set_status( \WP_Http::OK );

        return $response;
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return \WP_REST_Response
     */
    public function regenerate_bypass_code( \WP_REST_Request $request ): \WP_REST_Response {
        $settings = $this->plugin_settings->get_plugin_settings();

        $settings->set_bypass_code( Helper::generate_bypass_code( 16 ) );

        $new_settings = $settings->to_array();

        $new_plugin_options = new PluginOptions( $new_settings );

        $response = new \WP_REST_Response( array( 'data' => $this->plugin_settings->save_plugin_settings( $new_plugin_options )->to_array() ) );

        $response->set_headers( array( 'Cache-Control' => 'no-cache' ) );

        $response->set_status( \WP_Http::OK );

        return $response;
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return \WP_REST_Response
     */
    public function update_settings( \WP_REST_Request $request ): \WP_REST_Response {
        $settings = $this->plugin_settings->get_plugin_settings();

        $new_settings = array();

        $parameters = $request->get_params();

        foreach ( $settings->to_array() as $field_key => $field_value ) {
            if ( $field_key === 'bypass_code' ) {
                $new_settings[ $field_key ] = $field_value;
                continue;
            }

            if ( isset( $parameters[ $field_key ] ) ) {
                $new_settings[ $field_key ] = ! empty( $parameters[ $field_key ] );
            } else {
                $new_settings[ $field_key ] = $field_value;
            }

            if ( $this->has_changed( $field_key, $new_settings[ $field_key ] ) ) {
                do_action( "hostinger_tools_setting_{$field_key}_update", $new_settings[ $field_key ] );
            }
        }

        $new_plugin_options = new PluginOptions( $new_settings );

        $response = new \WP_REST_Response( array( 'data' => $this->plugin_settings->save_plugin_settings( $new_plugin_options )->to_array() ) );

        $this->update_urls( $new_plugin_options );

        $response->set_headers( array( 'Cache-Control' => 'no-cache' ) );

        $response->set_status( \WP_Http::OK );

        if ( has_action( 'litespeed_purge_all' ) ) {
            do_action( 'litespeed_purge_all' );
        }

        return $response;
    }
    /** PHPCS:enable */
    /**
     * @param PluginOptions $plugin_options
     *
     * @return bool
     */
    private function update_urls( PluginOptions $plugin_options ): bool {
        $siteurl = get_option( 'siteurl' );
        $home    = get_option( 'home' );

        if ( empty( $siteurl ) || empty( $home ) ) {
            return false;
        }

        if ( $plugin_options->get_force_https() ) {
            $siteurl = str_replace( 'http://', 'https://', $siteurl );
            $home    = str_replace( 'http://', 'https://', $home );
        }

        if ( $this->is_eligible_www_redirect() ) {
            if ( $plugin_options->get_force_www() ) {
                $siteurl = $this->add_www_to_url( $siteurl );
                $home    = $this->add_www_to_url( $home );
            } else {
                $siteurl = str_replace( 'www.', '', $siteurl );
                $home    = str_replace( 'www.', '', $home );
            }
        }

        update_option( 'siteurl', $siteurl );
        update_option( 'home', $home );

        return true;
    }

    /**
     * @param string $url
     *
     * @return mixed
     */
    private function add_www_to_url( string $url ): string {
        $parsed_url = wp_parse_url( $url );

        if ( isset( $parsed_url['host'] ) ) {
            $host = $parsed_url['host'];

            if ( strpos( $host, 'www.' ) !== 0 ) {
                $host = 'www.' . $host;
            }

            $parsed_url['host'] = $host;

            return $this->rebuild_url( $parsed_url );
        }

        return $url;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    private function rebuild_url( array $params ): string {
        $scheme   = isset( $params['scheme'] ) ? $params['scheme'] . '://' : '';
        $host     = isset( $params['host'] ) ? $params['host'] : '';
        $path     = isset( $params['path'] ) ? $params['path'] : '';
        $query    = isset( $params['query'] ) ? '?' . $params['query'] : '';
        $fragment = isset( $params['fragment'] ) ? '#' . $params['fragment'] : '';

        return "$scheme$host$path$query$fragment";
    }

    /**
     * @return string
     */
    private function get_latest_wordpress_version(): string {
        $newest_wordpress_version = get_transient( 'hostinger_newest_wordpress_version' );

        if ( $newest_wordpress_version !== false ) {
            return $newest_wordpress_version;
        }

        $newest_wordpress_version = $this->fetch_wordpress_version();

        if ( ! empty( $newest_wordpress_version ) ) {
            set_transient( 'hostinger_newest_wordpress_version', $newest_wordpress_version, 86400 );

            return $newest_wordpress_version;
        }

        return '';
    }

    /**
     * @return string
     */
    private function fetch_wordpress_version(): string {
        $url      = 'https://api.wordpress.org/core/version-check/1.7/';
        $response = wp_remote_get( $url );

        if ( is_wp_error( $response ) ) {
            return '';
        }

        $response_body = wp_remote_retrieve_body( $response );

        if ( $response_body === false ) {
            return '';
        }

        $data = json_decode( $response_body, true );

        if ( json_last_error() !== JSON_ERROR_NONE || empty( $data['offers'][0]['current'] ) ) {
            return '';
        }

        return $data['offers'][0]['current'];
    }

    /**
     * @return bool
     */
    private function is_eligible_www_redirect(): bool {
        $is_eligible_www_redirect = get_transient( 'hostinger_is_eligible_www_redirect' );

        if ( $is_eligible_www_redirect !== false ) {
            return $is_eligible_www_redirect;
        }

        $domain     = str_replace( 'www.', '', get_option( 'siteurl' ) );
        $www_domain = $this->add_www_to_url( $domain );

        $is_eligible_www_redirect = $this->check_domain_records( $domain, $www_domain );

        if ( isset( $is_eligible_www_redirect ) ) {
            set_transient( 'hostinger_is_eligible_www_redirect', $is_eligible_www_redirect, 120 );

            return $is_eligible_www_redirect;
        }

        return '';
    }

    /**
     * Check if the field has changed.
     */
    private function has_changed( string $field, mixed $new_value ): bool {
        $settings = $this->plugin_settings->get_plugin_settings();
        $settings = $settings->to_array();

        if ( ! array_key_exists( $field, $settings ) ) {
            return false;
        }

        return $new_value !== $settings[ $field ];
    }

    /**
     * @param string $domain_a
     * @param string $domain_b
     *
     * @return bool
     */
    public function check_domain_records( string $domain_a, string $domain_b ): bool {
        return ( gethostbyname( $domain_a ) === gethostbyname( $domain_b ) );
    }
}
