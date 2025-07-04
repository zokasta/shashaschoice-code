<?php

namespace Hostinger\EasyOnboarding\Admin;

use Hostinger\WpHelper\Utils;

defined( 'ABSPATH' ) || exit;

class Menu {
    public const WEBSITE_LIST_URL = 'https://hpanel.hostinger.com/websites';
    public const HPANEL_HOME = 'https://hpanel.hostinger.com';
    public const WEBSITE_BILLINGS_URL = 'https://hpanel.hostinger.com/billing/subscriptions';

	public function __construct() {
		add_filter( 'hostinger_menu_subpages', [ $this, 'add_menu_sub_pages' ] );
        add_filter( 'hostinger_admin_menu_bar_items', [ $this, 'add_admin_bar_items' ] );
        add_filter( 'hostinger_admin_menu_bar_items', [ $this, 'add_hpanel_bar_items' ], 999 );
	}

    /**
     * @param array $menu_items
     *
     * @return array
     */
    public function add_admin_bar_items(array $menu_items): array {
        $menu_items[] = array(
            'id'     => 'hostinger-easy-onboarding-admin-bar-onboarding',
            'title'  => esc_html__( 'Onboarding', 'hostinger-easy-onboarding' ),
            'href'  => admin_url( 'admin.php?page=hostinger-get-onboarding' )
        );

        return $menu_items;
    }

    /**
     * @param array $menu_items
     *
     * @return array
     */
    public function add_hpanel_bar_items(array $menu_items): array {
        if (empty(Utils::getApiToken())) {
            return $menu_items;
        }

        $external_icon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M5 21C4.45 21 3.97917 20.8042 3.5875 20.4125C3.19583 20.0208 3 19.55 3 19V5C3 4.45 3.19583 3.97917 3.5875 3.5875C3.97917 3.19583 4.45 3 5 3H11C11.2833 3 11.5208 3.09583 11.7125 3.2875C11.9042 3.47917 12 3.71667 12 4C12 4.28333 11.9042 4.52083 11.7125 4.7125C11.5208 4.90417 11.2833 5 11 5H5V19H19V13C19 12.7167 19.0958 12.4792 19.2875 12.2875C19.4792 12.0958 19.7167 12 20 12C20.2833 12 20.5208 12.0958 20.7125 12.2875C20.9042 12.4792 21 12.7167 21 13V19C21 19.55 20.8042 20.0208 20.4125 20.4125C20.0208 20.8042 19.55 21 19 21H5ZM19 6.4L10.4 15C10.2167 15.1833 9.98333 15.275 9.7 15.275C9.41667 15.275 9.18333 15.1833 9 15C8.81667 14.8167 8.725 14.5833 8.725 14.3C8.725 14.0167 8.81667 13.7833 9 13.6L17.6 5H15C14.7167 5 14.4792 4.90417 14.2875 4.7125C14.0958 4.52083 14 4.28333 14 4C14 3.71667 14.0958 3.47917 14.2875 3.2875C14.4792 3.09583 14.7167 3 15 3H21V9C21 9.28333 20.9042 9.52083 20.7125 9.7125C20.5208 9.90417 20.2833 10 20 10C19.7167 10 19.4792 9.90417 19.2875 9.7125C19.0958 9.52083 19 9.28333 19 9V6.4Z" fill=""/>
		</svg>';

        $menu_items[] = array(
            'id'     => 'hostinger_hpanel_home_admin_bar',
            'title'  => esc_html__( 'hPanel - Home', 'hostinger-easy-onboarding' ) . $external_icon,
            'href'  => self::HPANEL_HOME,
            'meta'   => array(
                'target' => '_blank',
            )
        );

        $menu_items[] = array(
            'id'     => 'hostinger_website_list_admin_bar',
            'title'  => esc_html__( 'hPanel - Websites', 'hostinger-easy-onboarding' ) . $external_icon,
            'href'  => self::WEBSITE_LIST_URL,
            'meta'   => array(
                'target' => '_blank',
            )
        );

        $menu_items[] = array(
            'id'     => 'hostinger_billings_admin_bar',
            'title'  => esc_html__( 'hPanel - Billing', 'hostinger-easy-onboarding' ) . $external_icon,
            'href'  => self::WEBSITE_BILLINGS_URL,
            'meta'   => array(
                'target' => '_blank',
            )
        );

        return $menu_items;
    }

    /**
     * @param array $submenus
     *
     * @return array
     */
	public function add_menu_sub_pages( array $submenus ): array {
		$submenus[] = array(
			'page_title' => __( 'Onboarding', 'hostinger-easy-onboarding' ),
			'menu_title' => __( 'Onboarding', 'hostinger-easy-onboarding' ),
			'capability' => 'manage_options',
			'menu_slug'  => 'hostinger-get-onboarding',
			'callback'   => array( $this, 'renderOnboarding' ),
            'menu_identifier' => 'home',
			'menu_order' => 10
		);

		return $submenus;
	}

    /**
     * @return void
     */
	public function renderOnboarding(): void {
		include_once __DIR__ . '/Views/Onboarding.php';
	}
}
