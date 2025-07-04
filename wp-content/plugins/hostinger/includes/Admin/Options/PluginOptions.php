<?php
namespace Hostinger\Admin\Options;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Class for handling plugin options
 */
class PluginOptions {
    /**
     * @var bool
     */
    private bool $maintenance_mode = false;

    /**
     * @var string
     */
    private string $bypass_code = '';

    /**
     * @var bool
     */
    private bool $disable_xml_rpc = false;

    /**
     * @var bool
     */
    private bool $force_https = false;

    /**
     * @var bool
     */
    private bool $force_www = false;

    /**
     * @var bool
     */
    private bool $disable_authentication_password = false;

    /**
     * @var bool
     */
    private bool $enable_llms_txt = false;

    /**
     * @param array $settings plugin settings array.
     */
    public function __construct( array $settings = array() ) {
        $this->maintenance_mode                = ! empty( $settings['maintenance_mode'] );
        $this->bypass_code                     = ! empty( $settings['bypass_code'] ) ? $settings['bypass_code'] : '';
        $this->disable_xml_rpc                 = ! empty( $settings['disable_xml_rpc'] );
        $this->force_https                     = ! empty( $settings['force_https'] );
        $this->force_www                       = ! empty( $settings['force_www'] );
        $this->disable_authentication_password = ! empty( $settings['disable_authentication_password'] );
        $this->enable_llms_txt                 = ! empty( $settings['enable_llms_txt'] );
    }

    /**
     * @return bool
     */
    public function get_maintenance_mode(): bool {
        return $this->maintenance_mode;
    }

    /**
     * @param bool $maintenance_mode
     *
     * @return void
     */
    public function set_maintenance_mode( bool $maintenance_mode ): void {
        $this->maintenance_mode = $maintenance_mode;
    }

    /**
     * @return string
     */
    public function get_bypass_code(): string {
        return $this->bypass_code;
    }

    /**
     * @param string $bypass_code
     *
     * @return void
     */
    public function set_bypass_code( string $bypass_code ): void {
        $this->bypass_code = $bypass_code;
    }

    /**
     * @return bool
     */
    public function get_disable_xml_rpc(): bool {
        return $this->disable_xml_rpc;
    }

    /**
     * @param bool $disable_xml_rpc
     *
     * @return void
     */
    public function set_disable_xml_rpc( bool $disable_xml_rpc ): void {
        $this->disable_xml_rpc = $disable_xml_rpc;
    }

    /**
     * @return bool
     */
    public function get_force_https(): bool {
        return $this->force_https;
    }

    /**
     * @param bool $force_https
     *
     * @return void
     */
    public function set_force_https( bool $force_https ): void {
        $this->force_https = $force_https;
    }

    /**
     * @return bool
     */
    public function get_force_www(): bool {
        return $this->force_www;
    }

    /**
     * @param bool $force_www
     *
     * @return void
     */
    public function set_force_www( bool $force_www ): void {
        $this->force_www = $force_www;
    }

    /**
     * @return bool
     */
    public function get_disable_authentication_password(): bool {
        return $this->disable_authentication_password;
    }

    /**
     * @param bool $authentication_password
     *
     * @return void
     */
    public function set_disable_authentication_password( bool $authentication_password ): void {
        $this->disable_authentication_password = $authentication_password;
    }

    public function get_enable_llms_txt(): bool {
        return $this->enable_llms_txt;
    }

    public function set_enable_llms_txt( bool $enable_llms_txt ): void {
        $this->enable_llms_txt = $enable_llms_txt;
    }

    /**
     * @return array
     */
    public function to_array(): array {
        return array(
            'maintenance_mode'                => $this->get_maintenance_mode(),
            'bypass_code'                     => $this->get_bypass_code(),
            'disable_xml_rpc'                 => $this->get_disable_xml_rpc(),
            'force_https'                     => $this->get_force_https(),
            'force_www'                       => $this->get_force_www(),
            'disable_authentication_password' => $this->get_disable_authentication_password(),
            'enable_llms_txt'                 => $this->get_enable_llms_txt(),
        );
    }
}
