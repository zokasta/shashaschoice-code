<?php
namespace Hostinger\EasyOnboarding\Rest;

use Hostinger\WpHelper\Utils as Helper;
use Hostinger\WpHelper\Requests\Client;

/**
 * Avoid possibility to get file accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Class for handling Rest Api Routes
 */
class Routes {
    /**
     * @var WelcomeRoutes
     */
    private WelcomeRoutes $welcome_routes;

    /**
     * @var StepRoutes
     */
    private StepRoutes $step_routes;

    /**
     * @var WooRoutes
     */
    private WooRoutes $woo_routes;

    /**
     * @var TutorialRoutes
     */
    private TutorialRoutes $tutorial_routes;

    /**
     * @var HostingRoutes
     */
    private HostingRoutes $hosting_routes;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @param WelcomeRoutes  $welcome_routes
     * @param StepRoutes     $step_routes
     * @param WooRoutes      $woo_routes
     * @param TutorialRoutes $tutorial_routes
     * @param Client $client
     * @param Helper $helper
     */
    public function __construct(
        WelcomeRoutes $welcome_routes,
        StepRoutes $step_routes,
        WooRoutes $woo_routes,
        TutorialRoutes $tutorial_routes,
        Client $client,
        Helper $helper
    ) {
        $this->welcome_routes = $welcome_routes;
        $this->step_routes = $step_routes;
        $this->woo_routes = $woo_routes;
        $this->tutorial_routes = $tutorial_routes;
        $this->client = $client;
        $this->helper = $helper;
        $this->hosting_routes = new HostingRoutes( $client, $helper );
    }

    /**
     * Init rest routes
     *
     * @return void
     */
    public function init(): void {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    /**
     * Register routes
     *
     * @return void
     */
    public function register_routes(): void {
        $this->register_welcome_routes();
        $this->register_step_routes();
        $this->register_woo_routes();
        $this->register_tutorial_routes();
        $this->register_hosting_routes();
    }

    /**
     * @param WP_REST_Request $request WordPress rest request.
     *
     * @return bool
     */
    public function permission_check( $request ): bool {
        // Workaround if Rest Api endpoint cache is enabled.
        // We don't want to cache these requests.
        if( has_action('litespeed_control_set_nocache') ) {
            do_action(
                'litespeed_control_set_nocache',
                'Custom Rest API endpoint, not cacheable.'
            );
        }

        if ( empty( is_user_logged_in() ) ) {
            return false;
        }

        // Implement custom capabilities when needed.
        return current_user_can( 'manage_options' );
    }

    /**
     *
     * @return void
     */
    private function register_welcome_routes(): void {
        // Return welcome status.
        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE,
            'get-welcome-status',
            array(
                'methods'             => 'GET',
                'callback'            => array( $this->welcome_routes, 'get_welcome_status' ),
                'permission_callback' => array( $this, 'permission_check' ),
            )
        );

        // Update welcome status.
        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE,
            'update-welcome-status',
            array(
                'methods'             => 'POST',
                'callback'            => array( $this->welcome_routes, 'update_welcome_status' ),
                'permission_callback' => array( $this, 'permission_check' ),
            )
        );

        // Update addons banner status.
        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE,
            'update-addons-banner-status',
            array(
                'methods'             => 'POST',
                'callback'            => array( $this->welcome_routes, 'update_addons_banner_status' ),
                'permission_callback' => array( $this, 'permission_check' ),
            )
        );
    }

    /**
     * @return void
     */
    private function register_step_routes(): void {
        // Return steps.
        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE,
            'get-steps',
            array(
                'methods'             => 'GET',
                'callback'            => array( $this->step_routes, 'get_steps' ),
                'permission_callback' => array( $this, 'permission_check' ),
            )
        );

        // Complete step.
        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE,
            'complete-step',
            array(
                'methods'             => 'POST',
                'callback'            => array( $this->step_routes, 'complete_step' ),
                'permission_callback' => array( $this, 'permission_check' ),
            )
        );

        // Toggle list step.
        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE,
            'toggle-list-visibility',
            array(
                'methods'             => 'POST',
                'callback'            => array( $this->step_routes, 'toggle_list_visibility' ),
                'permission_callback' => array( $this, 'permission_check' ),
            )
        );

        // Activate plugin
        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE, 'activate-plugin', [
                'methods'             => 'POST',
                'callback'            => [$this->step_routes, 'activate_plugin'],
                'permission_callback' => [$this, 'permission_check'],
            ]
        );

        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE, 'activate-theme', [
                'methods'             => 'POST',
                'callback'            => [$this->step_routes, 'activate_theme'],
                'permission_callback' => [$this, 'permission_check'],
            ]
        );

        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE, 'install-ai-theme', [
                'methods'             => 'POST',
                'callback'            => [$this->step_routes, 'install_ai_theme'],
                'permission_callback' => [$this, 'permission_check'],
            ]
        );
    }

    /**
     * @return void
     */
    private function register_woo_routes(): void {
        // Woo Setup
        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE, 'woo-setup', [
                'methods'             => 'POST',
                'callback'            => [$this->woo_routes, 'woo_setup'],
                'permission_callback' => [$this, 'permission_check'],
            ]
        );

        // Plugins
        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE, 'get-plugins', [
                'methods'             => 'GET',
                'callback'            => [$this->woo_routes, 'get_plugins'],
                'permission_callback' => [$this, 'permission_check'],
            ]
        );
    }

    /**
     * @return void
     */
    private function register_tutorial_routes(): void {
        // Return steps.
        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE,
            'get-tutorials',
            array(
                'methods'             => 'GET',
                'callback'            => array( $this->tutorial_routes, 'get_tutorials' ),
                'permission_callback' => array( $this, 'permission_check' ),
            )
        );
    }

    /**
     * @return void
     */
    private function register_hosting_routes(): void {
        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE,
            'get-domain-details',
            array(
                'methods'             => 'GET',
                'callback'            => array( $this->hosting_routes, 'get_domain_details' ),
                'permission_callback' => array( $this, 'permission_check' ),
            )
        );

        register_rest_route(
            HOSTINGER_EASY_ONBOARDING_REST_API_BASE,
            'get-plan-details',
            array(
                'methods'             => 'GET',
                'callback'            => array( $this->hosting_routes, 'get_hosting_details' ),
                'permission_callback' => array( $this, 'permission_check' ),
            )
        );
    }
}
