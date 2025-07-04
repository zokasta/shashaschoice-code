<?php

if ( ! function_exists( 'goldsmith_side_panel_cart_content' ) ) {
    add_action( 'goldsmith_before_wp_footer', 'goldsmith_side_panel_cart_content' );
    function goldsmith_side_panel_cart_content()
    {
        if ( '1' == goldsmith_settings( 'woo_catalog_mode', '0' ) || '1' == goldsmith_settings( 'disable_minicart', '0' ) ) {
            return;
        }
        $auto_open = '1' == goldsmith_settings('disable_right_panel_auto', '0' ) ? ' disable-auto-open' : '';
        $count     = WC()->cart->get_cart_contents_count();
        ?>
        <div class="goldsmith-side-panel<?php echo esc_attr( $auto_open ); ?>" data-cart-count="<?php echo esc_html( $count ); ?>">
            <div class="panel-header">
                <div class="goldsmith-panel-close goldsmith-panel-close-button"></div>
                <div class="panel-header-actions">
                    <div class="panel-header-cart panel-header-btn" data-name="cart">
                        <span class="goldsmith-cart-count goldsmith-wc-count"><?php echo esc_html( $count ); ?></span>
                        <?php echo goldsmith_svg_lists( 'bag', 'goldsmith-svg-icon' ); ?>
                        <span class="goldsmith-cart-total"></span>
                    </div>
                    <?php do_action( 'goldsmith_side_panel_header_after_cart' ); ?>
                </div>
            </div>
            <?php do_action( 'goldsmith_side_panel_after_header' ); ?>
            <div class="panel-content">
                <div class="cart-area panel-content-item active" data-name="cart">
                    <div class="cart-content">
                        <?php get_template_part('woocommerce/minicart/minicart'); ?>
                    </div>
                </div>
                <?php do_action( 'goldsmith_side_panel_content_after_cart' ); ?>
            </div>
        </div>
        <?php
    }
}
