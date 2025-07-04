<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


$goldsmith_ajax_login_register = goldsmith_settings( 'wc_ajax_login_register', '1' );
$goldsmith_ajax_attr = '1' == $goldsmith_ajax_login_register ? ' goldsmith-ajax-login' : '';
if ( '1' != goldsmith_settings( 'disable_account_style', '0' ) ) {
    wp_enqueue_style( 'goldsmith-wc-account-page' );
}
if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) {
    if ( 'multisteps' == apply_filters('goldsmith_myaccount_page_type',goldsmith_settings( 'myaccount_page_type', 'multisteps' ) ) ) {

        wc_get_template_part( 'myaccount/form-login-multisteps' );

    } else {

        do_action( 'woocommerce_before_customer_login_form' );
        ?>
        <div class="goldsmith-default-type row" id="customer_login">

            <div class="col-12 col-lg-6">

                <h3><?php esc_html_e( 'Login', 'goldsmith' ); ?></h3>

                <form class="woocommerce-form woocommerce-form-login login row<?php echo esc_attr( $goldsmith_ajax_attr ); ?>" method="post">

                    <?php do_action( 'woocommerce_login_form_start' ); ?>

                    <p class="woocommerce-form-row form-row col-12">
                        <label for="username"><?php esc_html_e( 'Username or email address', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                    </p>
                    <p class="woocommerce-form-row form-row col-12">
                        <label for="password"><?php esc_html_e( 'Password', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                        <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
                    </p>

                    <?php do_action( 'woocommerce_login_form' ); ?>

                    <p class="woocommerce-form-row form-row col-12">
                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                            <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'goldsmith' ); ?></span>
                        </label>
                        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                        <button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'goldsmith' ); ?>"><?php esc_html_e( 'Log in', 'goldsmith' ); ?></button>
                    </p>

                    <p class="woocommerce-LostPassword lost_password col-12">
                        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'goldsmith' ); ?></a>
                    </p>

                    <?php do_action( 'woocommerce_login_form_end' ); ?>

                    <?php if ( '1' == $goldsmith_ajax_login_register ) { ?>
                        <input type="hidden" name="action" value="ajaxlogin">
                    <?php } ?>

                </form>
            </div>

            <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

                <div class="col-12 col-lg-6">

                    <h3><?php esc_html_e( 'Register', 'goldsmith' ); ?></h3>

                    <form method="post" class="woocommerce-form woocommerce-form-register register row" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

                        <?php do_action( 'woocommerce_register_form_start' ); ?>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                            <p class="woocommerce-form-row form-row col-12">
                                <label for="reg_username"><?php esc_html_e( 'Username', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                            </p>

                        <?php endif; ?>

                        <p class="woocommerce-form-row form-row col-12">
                            <label for="reg_email"><?php esc_html_e( 'Email address', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                        </p>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                            <p class="woocommerce-form-row form-row col-12">
                                <label for="reg_password"><?php esc_html_e( 'Password', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                                <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
                            </p>

                        <?php else : ?>

                            <p class="woocommerce-form-row form-row col-12"><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'goldsmith' ); ?></p>

                        <?php endif; ?>

                        <?php do_action( 'woocommerce_register_form' ); ?>

                        <p class="woocommerce-form-row form-row col-12">
                            <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                            <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'goldsmith' ); ?>"><?php esc_html_e( 'Register', 'goldsmith' ); ?></button>
                        </p>

                        <?php do_action( 'woocommerce_register_form_end' ); ?>

                    </form>

                </div>
            <?php endif; ?>
        </div>
        <?php
        do_action( 'woocommerce_after_customer_login_form' );
    }
}
?>
