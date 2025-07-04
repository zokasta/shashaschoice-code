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
* @version 4.1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$errors = wc_get_notices();
$errors = !empty( $errors['error'] ) ? $errors['error'][0]['notice'] : '';

$goldsmith_ajax_login_register = goldsmith_settings( 'wc_ajax_login_register', '1' );
$goldsmith_ajax_attr = '1' == $goldsmith_ajax_login_register ? ' goldsmith-ajax-login' : '';

?>
<div class="goldsmith-myaccount-wrapper" id="customer_login">

    <div class="row goldsmith-justify-center">
        <div class="col-12 col-md-10 col-lg-5">

            <?php do_action( 'woocommerce_before_customer_login_form' ); ?>

            <div class="swiper-container goldsmith-myaccount-steps-register">

                <div class="goldsmith-myaccount-steps-buttons">
                    <span class="goldsmith-myaccount-form-button-login login-title">
                        <?php echo goldsmith_svg_lists( 'arrow-right', 'goldsmith-svg-icon' ); ?>
                        <span><?php esc_html_e( 'Login', 'goldsmith' ); ?></span>
                    </span>
                    <span class="goldsmith-myaccount-form-button-register register-title">
                        <?php echo goldsmith_svg_lists( 'user-2', 'goldsmith-svg-icon' ); ?>
                        <span><?php esc_html_e( 'Register', 'goldsmith' ); ?></span>
                    </span>
                </div>

                <div class="swiper-wrapper">

                    <div class="swiper-slide">
                        <div class="goldsmith-slide-item-inner goldsmith-slide-item-login">
                            <form class="woocommerce-form woocommerce-form-login login<?php echo esc_attr( $goldsmith_ajax_attr ); ?>" method="post">

                                <?php do_action( 'woocommerce_login_form_start' ); ?>

                                <p class="woocommerce-form-row form-row goldsmith-row goldsmith-is-required">
                                    <label for="username"><?php esc_html_e( 'Username or email address', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                                    <span class="goldsmith-form-message"></span>
                                </p>
                                <p class="woocommerce-form-row form-row goldsmith-row goldsmith-is-required">
                                    <label for="password"><?php esc_html_e( 'Password', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
                                    <span class="goldsmith-form-message"></span>
                                </p>

                                <?php do_action( 'woocommerce_login_form' ); ?>

                                <p class="woocommerce-form-row form-row goldsmith-row">
                                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                                        <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'goldsmith' ); ?></span>
                                    </label>
                                    <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                                    <button type="submit" class="woocommerce-button button woocommerce-form-login__submit goldsmith-btn-large goldsmith-btn goldsmith-bg-black" name="login" value="<?php esc_attr_e( 'Log in', 'goldsmith' ); ?>"><?php esc_html_e( 'Log in', 'goldsmith' ); ?></button>
                                </p>
                                <p class="woocommerce-LostPassword lost_password goldsmith-row">
                                    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'goldsmith' ); ?></a>
                                </p>

                                <?php do_action( 'woocommerce_login_form_end' ); ?>
                                
                                <?php if ( '1' == $goldsmith_ajax_login_register ) { ?>
                                    <input type="hidden" name="action" value="ajaxlogin">
                                <?php } ?>
                                
                            </form>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="goldsmith-slide-item-inner goldsmith-slide-item-register">
                            <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

                                <?php do_action( 'woocommerce_register_form_start' ); ?>

                                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                                    <p class="woocommerce-form-row form-row goldsmith-row goldsmith-is-required">
                                        <label for="reg_username"><?php esc_html_e( 'Username', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                                        <span class="goldsmith-form-message"></span>
                                    </p>

                                <?php endif; ?>

                                <p class="woocommerce-form-row form-row goldsmith-row goldsmith-is-required">
                                    <label for="reg_email"><?php esc_html_e( 'Email address', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                                    <span class="goldsmith-form-message"></span>
                                </p>

                                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                                    <p class="woocommerce-form-row form-row goldsmith-row goldsmith-is-required">
                                        <label for="reg_password"><?php esc_html_e( 'Password', 'goldsmith' ); ?>&nbsp;<span class="required">*</span></label>
                                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
                                        <span class="goldsmith-form-message"></span>
                                    </p>

                                <?php else : ?>

                                    <p><?php esc_html_e( 'A password will be sent to your email address.', 'goldsmith' ); ?></p>

                                <?php endif; ?>

                                <?php do_action( 'woocommerce_register_form' ); ?>

                                <p class="woocommerce-form-row form-row goldsmith-row">
                                    <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                                    <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit goldsmith-btn-large goldsmith-btn goldsmith-bg-black" name="register" value="<?php esc_attr_e( 'Register', 'goldsmith' ); ?>"><?php esc_html_e( 'Register', 'goldsmith' ); ?></button>
                                </p>

                                <?php do_action( 'woocommerce_register_form_end' ); ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php do_action( 'woocommerce_after_customer_login_form' ); ?>

</div>
