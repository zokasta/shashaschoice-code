jQuery(document).ready(function($) {

    "use strict";

    function goldsmithQtyBtn() {
        $('body').off('click.qtyBtn').on('click.qtyBtn','.plus, .minus', function() {
            var $this   = $(this),
                wrapper = $this.closest('.cart-quantity-wrapper'),
                qty     = $this.closest( '.quantity' ).find( '.qty' ),
                qty_val = parseFloat( $(qty).val() ),
                max     = parseFloat( $(qty).attr( 'max' ) ),
                min     = parseFloat( $(qty).attr( 'min' ) ),
                step    = parseFloat( $(qty).attr( 'step' ) ),
                new_val = 0;

            if ( ! qty_val || qty_val === '' || qty_val === 'NaN' ) {
                qty_val = 0;
            }
            if ( max === '' || max === 'NaN' ) {
                max = '';
            }
            if ( min === '' || min === 'NaN' ) {
                min = 0;
            }
            if ( step === 'any' || step === '' || step === undefined || step === 'NaN' ) {
                step = 1;
            } else {
                step = step;
            }

            // Update values
            if ( $this.is( '.plus' ) ) {
                if ( max && ( max === qty_val || qty_val > max ) ) {
                    $(qty).val( max );
                    $this.addClass('disabled');
                } else {
                    $this.parent().find('.minus').removeClass('disabled');
                    new_val = qty_val + step;
                    $(qty).val( new_val );
                    if ( max && ( max === new_val || new_val > max ) ) {
                        $this.addClass('disabled');
                    }
                    $(qty).trigger('change');
                }
            } else {
                if ( min && ( min === qty_val || qty_val < min ) ) {
                    $(qty).val( min );
                    $this.addClass('disabled');
                } else if ( qty_val > 0 ) {
                    new_val = qty_val - step;
                    $(qty).val( new_val );
                    if ( min && ( min === new_val || new_val < min ) ) {
                        $this.addClass('disabled');
                    }
                    $(qty).trigger('change');
                }
            }
            $('.cart-update-button[name="update_cart"]').addClass('active').attr('aria-disabled',false);
            wrapper.addClass('active');
            $('.single_add_to_cart_button.disabled').removeClass('disabled');
            if ( $('.goldsmith-shop-popup-notices .woocommerce-error').length>0 ) {
                $('.goldsmith-shop-popup-notices .woocommerce-error').remove();
            }
        });
    }

    goldsmithQtyBtn();

    $(document.body).on('goldsmith_on_qtybtn', goldsmithQtyBtn );

    $(document.body).on( 'update_checkout', goldsmithQtyBtn );
});
