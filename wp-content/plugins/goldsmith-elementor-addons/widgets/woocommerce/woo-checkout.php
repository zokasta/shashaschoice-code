<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Goldsmith_Woo_Checkout extends Widget_Base {
    use Goldsmith_Helper;
    public function get_name() {
        return 'goldsmith-woo-checkout-form';
    }
    public function get_title() {
        return 'WC Checkout Form (N)';
    }
    public function get_icon() {
        return 'eicon-shortcode';
    }
    public function get_categories() {
        return [ 'goldsmith-woo' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'Goldsmith_woo_ma_account_settings',
            [
                'label' => esc_html__( 'Checkout Form', 'Goldsmith' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'type',
            [
                'label' => esc_html__( 'Form Type', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'link',
                'options' => [
                    'full' => esc_html__( 'Full Checkout', 'goldsmith' ),
                    'parts' => esc_html__( 'Checkout Parts', 'goldsmith' ),
                ]
            ]
        );
        $this->add_control( 'parts',
            [
                'label' => esc_html__( 'Form Parts', 'goldsmith' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'link',
                'options' => [
                    'coupon' => esc_html__( 'Coupon Form', 'goldsmith' ),
                    'billing' => esc_html__( 'Billing Form', 'goldsmith' ),
                    'shipping' => esc_html__( 'Shipping Form', 'goldsmith' ),
                ],
                'condition' => ['type' => 'parts']
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        if ( class_exists('WooCommerce') ) {
            
            $checkout = new WC_Checkout();
            
            // If checkout registration is disabled and not logged in, the user cannot checkout.
            if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
                echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
                return;
            }
            
            do_action( 'woocommerce_before_checkout_form', $checkout );
        
            ?>
            <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
        
                <?php if ( $checkout->get_checkout_fields() ) : ?>
        
                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
        
                    <div class="col2-set row row-cols-1 row-cols-lg-2" id="customer_details">
        
                        <div class="col">
                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                        </div>
        
                        <div class="col">
                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
        
                            <div class="goldsmith-order-review">
                            
                                <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
        
                                <h4 class="goldsmith-form-title" id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h4>
        
                                <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
        
                                <div id="order_review" class="woocommerce-checkout-review-order">
                                    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                                </div>
        
                                <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
        
                            </div>
                        </div>
                    </div>
        
                    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
        
                <?php endif; ?>
        
            </form>
            <?php
            do_action( 'woocommerce_after_checkout_form', $checkout );
        }
    }
}
