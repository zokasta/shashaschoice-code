<?php
/**
* My Addresses
*
* This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see     https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 9.3.0
*/

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        array(
            'billing'  => __( 'Billing address', 'goldsmith' ),
            'shipping' => __( 'Shipping address', 'goldsmith' ),
        ),
        $customer_id
    );
} else {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        array(
            'billing' => __( 'Billing address', 'goldsmith' ),
        ),
        $customer_id
    );
}

$col = ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ? 6 : 12;
?>

<p>
    <?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'The following addresses will be used on the checkout page by default.', 'goldsmith' ) );?>
</p>


<div class="goldsmith-addresses row addresses">
    <?php foreach ( $get_addresses as $name => $address_title ) :
        $address = wc_get_account_formatted_address( $name );
        $link = $address ? esc_html__( 'Edit', 'goldsmith' ) : esc_html__( 'Add', 'goldsmith' );
        $address = $address ? wp_kses_post( $address ) : esc_html__( 'You have not set up this type of address yet.', 'goldsmith' );
        ?>
        <div class="col-12 col-lg-<?php echo esc_attr( $col ); ?> goldsmith-address">
            <header class="goldsmith-address-title title">
                <h4 class="goldsmith-form-title"><?php echo esc_html( $address_title ); ?></h4>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="edit"><?php echo esc_html( $link ); ?></a>
            </header>
            <address>
                <?php
                printf( '%s', $address );

                /**
                * Used to output content after core address fields.
                *
                * @param string $name Address type.
                * @since 8.7.0
                */
                do_action( 'woocommerce_my_account_after_my_address', $name );
                ?>
            </address>
        </div>
    <?php endforeach; ?>
</div>
