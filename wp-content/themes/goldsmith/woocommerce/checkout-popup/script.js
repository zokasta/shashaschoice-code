(function(window, document, $) {

    "use strict";

    jQuery(document).ready(function($) {

        // checkout popup start
        goldsmithCheckoutPopup();

        $(document.body).on('goldsmith_checkout_popup', function(){
            goldsmithCheckoutPopup();
        });

        function goldsmithCheckoutPopup(){

            if ( typeof wc_checkout_params === 'undefined' && typeof goldsmith_vars === 'undefined' && goldsmith_vars.shop.checkout_popup != 'yes' ) {
                return;
            }

            $.blockUI.defaults.overlayCSS.cursor = 'default';

            var wc_checkout_form = {
                updateTimer           : false,
                dirtyInput            : false,
                selectedPaymentMethod : false,
                xhr                   : false,
                $order_review         : $( '.goldsmith-ajax-checkout-popup-wrapper #order_review' ),
                $checkout_form        : $( '.goldsmith-ajax-checkout-popup-wrapper form.checkout' ),
                init                  : function() {

                    var $checkout_form = $( '.goldsmith-ajax-checkout-popup-wrapper form.checkout' );

                    $( document.body ).on( 'update_checkout', this.update_checkout );
                    $( document.body ).on( 'init_checkout', this.init_checkout );

                    // Payment methods
                    $checkout_form.on( 'click', 'input[name="payment_method"]', this.payment_method_selected );

                    if ( $( document.body ).hasClass( 'woocommerce-order-pay' ) ) {
                        $( '.goldsmith-ajax-checkout-popup-wrapper #order_review' ).on( 'click', 'input[name="payment_method"]', this.payment_method_selected );
                        $( '.goldsmith-ajax-checkout-popup-wrapper #order_review' ).on( 'submit', this.submitOrder );
                        $( '.goldsmith-ajax-checkout-popup-wrapper #order_review' ).attr( 'novalidate', 'novalidate' );
                    }

                    // Prevent HTML5 validation which can conflict.
                    $checkout_form.attr( 'novalidate', 'novalidate' );

                    // Form submission
                    $checkout_form.on( 'submit', this.submit );

                    // Inline validation
                    $checkout_form.on( 'input validate change', '.input-text, select, input:checkbox', this.validate_field );

                    // Manual trigger
                    $checkout_form.on( 'update', this.trigger_update_checkout );

                    // Inputs/selects which update totals
                    $checkout_form.on( 'change', 'select.shipping_method, input[name^="shipping_method"], #ship-to-different-address input, .update_totals_on_change select, .update_totals_on_change input[type="radio"], .update_totals_on_change input[type="checkbox"]', this.trigger_update_checkout ); // eslint-disable-line max-len
                    $checkout_form.on( 'change', '.address-field select', this.input_changed );
                    $checkout_form.on( 'change', '.address-field input.input-text, .update_totals_on_change input.input-text', this.maybe_input_changed ); // eslint-disable-line max-len
                    $checkout_form.on( 'keydown', '.address-field input.input-text, .update_totals_on_change input.input-text', this.queue_update_checkout ); // eslint-disable-line max-len

                    // Address fields
                    $checkout_form.on( 'change', '#ship-to-different-address input', this.ship_to_different_address );

                    // Trigger events
                    $checkout_form.find( '#ship-to-different-address input' ).trigger( 'change' );
                    this.init_payment_methods();

                    // Update on page load
                    if ( wc_checkout_params.is_checkout === '1' ) {
                        $( document.body ).trigger( 'init_checkout' );
                    }
                    if ( wc_checkout_params.option_guest_checkout === 'yes' ) {
                        $( 'input#createaccount' ).on( 'change', this.toggle_create_account ).trigger( 'change' );
                    }
                },
                init_payment_methods: function() {
                    var $payment_methods = $( '.goldsmith-ajax-checkout-popup-wrapper .woocommerce-checkout' ).find( 'input[name="payment_method"]' );

                    // If there is one method, we can hide the radio input
                    if ( 1 === $payment_methods.length ) {
                        $payment_methods.eq(0).hide();
                    }

                    // If there was a previously selected method, check that one.
                    if ( wc_checkout_form.selectedPaymentMethod ) {
                        $( '#' + wc_checkout_form.selectedPaymentMethod ).prop( 'checked', true );
                    }

                    // If there are none selected, select the first.
                    if ( 0 === $payment_methods.filter( ':checked' ).length ) {
                        $payment_methods.eq(0).prop( 'checked', true );
                    }

                    // Get name of new selected method.
                    var checkedPaymentMethod = $payment_methods.filter( ':checked' ).eq(0).prop( 'id' );

                    if ( $payment_methods.length > 1 ) {
                        // Hide open descriptions.
                        $( 'div.payment_box:not(".' + checkedPaymentMethod + '")' ).filter( ':visible' ).slideUp( 0 );
                    }

                    // Trigger click event for selected method
                    $payment_methods.filter( ':checked' ).eq(0).trigger( 'click' );
                },
                get_payment_method: function() {
                    return wc_checkout_form.$checkout_form.find( 'input[name="payment_method"]:checked' ).val();
                },
                payment_method_selected: function( e ) {
                    e.stopPropagation();

                    if ( $( '.goldsmith-ajax-checkout-popup-wrapper .payment_methods input.input-radio' ).length > 1 ) {
                        var target_payment_box = $( 'div.payment_box.' + $( this ).attr( 'ID' ) ),
                        is_checked         = $( this ).is( ':checked' );

                        if ( is_checked && ! target_payment_box.is( ':visible' ) ) {
                            $( '.goldsmith-ajax-checkout-popup-wrapper div.payment_box' ).filter( ':visible' ).slideUp( 230 );

                            if ( is_checked ) {
                                target_payment_box.slideDown( 230 );
                            }
                        }
                    } else {
                        $( '.goldsmith-ajax-checkout-popup-wrapper div.payment_box' ).show();
                    }

                    if ( $( this ).data( 'order_button_text' ) ) {
                        $( '.goldsmith-ajax-checkout-popup-wrapper #place_order' ).text( $( this ).data( 'order_button_text' ) );
                    } else {
                        $( '.goldsmith-ajax-checkout-popup-wrapper #place_order' ).text( $( '#place_order' ).data( 'value' ) );
                    }

                    var selectedPaymentMethod = $( '.woocommerce-checkout input[name="payment_method"]:checked' ).attr( 'id' );

                    if ( selectedPaymentMethod !== wc_checkout_form.selectedPaymentMethod ) {
                        $( document.body ).trigger( 'payment_method_selected' );
                    }

                    wc_checkout_form.selectedPaymentMethod = selectedPaymentMethod;
                },
                toggle_create_account: function() {
                    $( '.goldsmith-ajax-checkout-popup-wrapper div.create-account' ).hide();

                    if ( $( this ).is( ':checked' ) ) {
                        // Ensure password is not pre-populated.
                        $( '.goldsmith-ajax-checkout-popup-wrapper #account_password' ).val( '' ).trigger( 'change' );
                        $( '.goldsmith-ajax-checkout-popup-wrapper div.create-account' ).slideDown();
                    }
                },
                init_checkout: function() {
                    $( document.body ).trigger( 'update_checkout' );
                },
                maybe_input_changed: function( e ) {
                    if ( wc_checkout_form.dirtyInput ) {
                        wc_checkout_form.input_changed( e );
                    }
                },
                input_changed: function( e ) {
                    wc_checkout_form.dirtyInput = e.target;
                    wc_checkout_form.maybe_update_checkout();
                },
                queue_update_checkout: function( e ) {
                    var code = e.keyCode || e.which || 0;

                    if ( code === 9 ) {
                        return true;
                    }

                    wc_checkout_form.dirtyInput = this;
                    wc_checkout_form.reset_update_checkout_timer();
                    wc_checkout_form.updateTimer = setTimeout( wc_checkout_form.maybe_update_checkout, '1000' );
                },
                trigger_update_checkout: function() {
                    wc_checkout_form.reset_update_checkout_timer();
                    wc_checkout_form.dirtyInput = false;
                    $( document.body ).trigger( 'update_checkout' );
                },
                maybe_update_checkout: function() {
                    var update_totals = true;

                    if ( $( wc_checkout_form.dirtyInput ).length ) {
                        var $required_inputs = $( wc_checkout_form.dirtyInput ).closest( 'div' ).find( '.address-field.validate-required' );

                        if ( $required_inputs.length ) {
                            $required_inputs.each( function() {
                                if ( $( this ).find( 'input.input-text' ).val() === '' ) {
                                    update_totals = false;
                                }
                            });
                        }
                    }
                    if ( update_totals ) {
                        wc_checkout_form.trigger_update_checkout();
                    }
                },
                ship_to_different_address: function() {
                    $( '.goldsmith-ajax-checkout-popup-wrapper div.shipping_address' ).hide();
                    if ( $( this ).is( ':checked' ) ) {
                        $( '.goldsmith-ajax-checkout-popup-wrapper div.shipping_address' ).slideDown();
                    }
                },
                reset_update_checkout_timer: function() {
                    clearTimeout( wc_checkout_form.updateTimer );
                },
                is_valid_json: function( raw_json ) {
                    try {
                        var json = JSON.parse( raw_json );

                        return ( json && 'object' === typeof json );
                    } catch ( e ) {
                        return false;
                    }
                },
                validate_field: function( e ) {
                    var $this             = $( this ),
                    $parent           = $this.closest( '.form-row' ),
                    validated         = true,
                    validate_required = $parent.is( '.validate-required' ),
                    validate_email    = $parent.is( '.validate-email' ),
                    validate_phone    = $parent.is( '.validate-phone' ),
                    pattern           = '',
                    event_type        = e.type;

                    if ( 'input' === event_type ) {
                        $parent.removeClass( 'woocommerce-invalid woocommerce-invalid-required-field woocommerce-invalid-email woocommerce-invalid-phone woocommerce-validated' ); // eslint-disable-line max-len
                    }

                    if ( 'validate' === event_type || 'change' === event_type ) {

                        if ( validate_required ) {
                            if ( 'checkbox' === $this.attr( 'type' ) && ! $this.is( ':checked' ) ) {
                                $parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-required-field' );
                                validated = false;
                            } else if ( $this.val() === '' ) {
                                $parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-required-field' );
                                validated = false;
                            }
                        }

                        if ( validate_email ) {
                            if ( $this.val() ) {
                                /* https://stackoverflow.com/questions/2855865/jquery-validate-e-mail-address-regex */
                                pattern = new RegExp( /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[0-9a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i ); // eslint-disable-line max-len

                                if ( ! pattern.test( $this.val() ) ) {
                                    $parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-email woocommerce-invalid-phone' ); // eslint-disable-line max-len
                                    validated = false;
                                }
                            }
                        }

                        if ( validate_phone ) {
                            pattern = new RegExp( /[\s\#0-9_\-\+\/\(\)\.]/g );

                            if ( 0 < $this.val().replace( pattern, '' ).length ) {
                                $parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-phone' );
                                validated = false;
                            }
                        }

                        if ( validated ) {
                            $parent.removeClass( 'woocommerce-invalid woocommerce-invalid-required-field woocommerce-invalid-email woocommerce-invalid-phone' ).addClass( 'woocommerce-validated' ); // eslint-disable-line max-len
                        }
                    }
                },
                update_checkout: function( event, args ) {
                    // Small timeout to prevent multiple requests when several fields update at the same time
                    wc_checkout_form.reset_update_checkout_timer();
                    wc_checkout_form.updateTimer = setTimeout( wc_checkout_form.update_checkout_action, '5', args );
                },
                update_checkout_action: function( args ) {
                    if ( wc_checkout_form.xhr ) {
                        wc_checkout_form.xhr.abort();
                    }

                    if ( $( '.goldsmith-ajax-checkout-popup-wrapper form.checkout' ).length === 0 ) {
                        return;
                    }

                    args = typeof args !== 'undefined' ? args : {
                        update_shipping_method: true
                    };

                    var country      = $( '#billing_country' ).val(),
                    state            = $( '#billing_state' ).val(),
                    postcode         = $( ':input#billing_postcode' ).val(),
                    city             = $( '#billing_city' ).val(),
                    address          = $( ':input#billing_address_1' ).val(),
                    address_2        = $( ':input#billing_address_2' ).val(),
                    s_country        = country,
                    s_state          = state,
                    s_postcode       = postcode,
                    s_city           = city,
                    s_address        = address,
                    s_address_2      = address_2,
                    $required_inputs = $( wc_checkout_form.$checkout_form ).find( '.address-field.validate-required:visible' ),
                    has_full_address = true;

                    if ( $required_inputs.length ) {
                        $required_inputs.each( function() {
                            if ( $( this ).find( ':input' ).val() === '' ) {
                                has_full_address = false;
                            }
                        });
                    }

                    if ( $( '#ship-to-different-address' ).find( 'input' ).is( ':checked' ) ) {
                        s_country   = $( '#shipping_country' ).val();
                        s_state     = $( '#shipping_state' ).val();
                        s_postcode  = $( ':input#shipping_postcode' ).val();
                        s_city      = $( '#shipping_city' ).val();
                        s_address   = $( ':input#shipping_address_1' ).val();
                        s_address_2 = $( ':input#shipping_address_2' ).val();
                    }

                    var data = {
                        security        : wc_checkout_params.update_order_review_nonce,
                        payment_method  : wc_checkout_form.get_payment_method(),
                        country         : country,
                        state           : state,
                        postcode        : postcode,
                        city            : city,
                        address         : address,
                        address_2       : address_2,
                        s_country       : s_country,
                        s_state         : s_state,
                        s_postcode      : s_postcode,
                        s_city          : s_city,
                        s_address       : s_address,
                        s_address_2     : s_address_2,
                        has_full_address: has_full_address,
                        post_data       : $( 'form.checkout' ).serialize()
                    };

                    if ( false !== args.update_shipping_method ) {
                        var shipping_methods = {};

                        // eslint-disable-next-line max-len
                        $( 'select.shipping_method, input[name^="shipping_method"][type="radio"]:checked, input[name^="shipping_method"][type="hidden"]' ).each( function() {
                            shipping_methods[ $( this ).data( 'index' ) ] = $( this ).val();
                        } );

                        data.shipping_method = shipping_methods;
                    }

                    $( '.woocommerce-checkout-payment, .woocommerce-checkout-review-order-table' ).block({
                        message: null,
                        overlayCSS: {
                            background: '#fff',
                            opacity: 0.6
                        }
                    });

                    wc_checkout_form.xhr = $.ajax({
                        type    : 'POST',
                        url     : wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'update_order_review' ),
                        data    : data,
                        success : function( data ) {

                            // Reload the page if requested
                            if ( data && true === data.reload ) {
                                window.location.reload();
                                return;
                            }

                            // Remove any notices added previously
                            $( '.woocommerce-NoticeGroup-updateOrderReview' ).remove();

                            var termsCheckBoxChecked = $( '#terms' ).prop( 'checked' );

                            // Save payment details to a temporary object
                            var paymentDetails = {};
                            $( '.payment_box :input' ).each( function() {
                                var ID = $( this ).attr( 'id' );

                                if ( ID ) {
                                    if ( $.inArray( $( this ).attr( 'type' ), [ 'checkbox', 'radio' ] ) !== -1 ) {
                                        paymentDetails[ ID ] = $( this ).prop( 'checked' );
                                    } else {
                                        paymentDetails[ ID ] = $( this ).val();
                                    }
                                }
                            });

                            // Always update the fragments
                            if ( data && data.fragments ) {
                                $.each( data.fragments, function ( key, value ) {
                                    if ( ! wc_checkout_form.fragments || wc_checkout_form.fragments[ key ] !== value ) {
                                        $( key ).replaceWith( value );
                                    }
                                    $( key ).unblock();
                                } );
                                wc_checkout_form.fragments = data.fragments;
                            }

                            // Recheck the terms and conditions box, if needed
                            if ( termsCheckBoxChecked ) {
                                $( '#terms' ).prop( 'checked', true );
                            }

                            // Fill in the payment details if possible without overwriting data if set.
                            if ( ! $.isEmptyObject( paymentDetails ) ) {
                                $( '.payment_box :input' ).each( function() {
                                    var ID = $( this ).attr( 'id' );
                                    if ( ID ) {
                                        if ( $.inArray( $( this ).attr( 'type' ), [ 'checkbox', 'radio' ] ) !== -1 ) {
                                            $( this ).prop( 'checked', paymentDetails[ ID ] ).trigger( 'change' );
                                        } else if ( $.inArray( $( this ).attr( 'type' ), [ 'select' ] ) !== -1 ) {
                                            $( this ).val( paymentDetails[ ID ] ).trigger( 'change' );
                                        } else if ( null !== $( this ).val() && 0 === $( this ).val().length ) {
                                            $( this ).val( paymentDetails[ ID ] ).trigger( 'change' );
                                        }
                                    }
                                });
                            }

                            // Check for error
                            if ( data && 'failure' === data.result ) {

                                var $form = $( 'form.checkout' );

                                // Remove notices from all sources
                                $( '.woocommerce-error, .woocommerce-message' ).remove();

                                // Add new errors returned by this event
                                if ( data.messages ) {
                                    $form.prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-updateOrderReview">' + data.messages + '</div>' ); // eslint-disable-line max-len
                                } else {
                                    $form.prepend( data );
                                }

                                // Lose focus for all fields
                                $form.find( '.input-text, select, input:checkbox' ).trigger( 'validate' ).trigger( 'blur' );

                                //wc_checkout_form.scroll_to_notices();
                            }

                            // Re-init methods
                            wc_checkout_form.init_payment_methods();

                            // Fire updated_checkout event.
                            $( document.body ).trigger( 'updated_checkout', [ data ] );
                        }

                    });
                },
                handleUnloadEvent: function( e ) {
                    // Modern browsers have their own standard generic messages that they will display.
                    // Confirm, alert, prompt or custom message are not allowed during the unload event
                    // Browsers will display their own standard messages

                    // Check if the browser is Internet Explorer
                    if((navigator.userAgent.indexOf('MSIE') !== -1 ) || (!!document.documentMode)) {
                        // IE handles unload events differently than modern browsers
                        e.preventDefault();
                        return undefined;
                    }

                    return true;
                },
                attachUnloadEventsOnSubmit: function() {
                    $( window ).on('beforeunload', this.handleUnloadEvent);
                },
                detachUnloadEventsOnSubmit: function() {
                    $( window ).off('beforeunload', this.handleUnloadEvent);
                },
                blockOnSubmit: function( $form ) {
                    var isBlocked = $form.data( 'blockUI.isBlocked' );

                    if ( 1 !== isBlocked ) {
                        $form.block({
                            message: null,
                            overlayCSS: {
                                background: '#fff',
                                opacity: 0.6
                            }
                        });
                    }
                },
                submitOrder: function() {
                    wc_checkout_form.blockOnSubmit( $( this ) );
                },
                submit: function() {
                    wc_checkout_form.reset_update_checkout_timer();
                    var $form = $( '.goldsmith-ajax-checkout-popup-wrapper form.checkout' );

                    if ( $form.is( '.processing' ) ) {
                        return false;
                    }

                    // Trigger a handler to let gateways manipulate the checkout if needed
                    // eslint-disable-next-line max-len
                    if ( $form.triggerHandler( 'checkout_place_order' ) !== false && $form.triggerHandler( 'checkout_place_order_' + wc_checkout_form.get_payment_method() ) !== false ) {

                        $form.addClass( 'processing' );

                        wc_checkout_form.blockOnSubmit( $form );

                        // Attach event to block reloading the page when the form has been submitted
                        wc_checkout_form.attachUnloadEventsOnSubmit();

                        // ajaxSetup is global, but we use it to ensure JSON is valid once returned.
                        $.ajaxSetup( {
                            dataFilter: function( raw_response, dataType ) {
                                // We only want to work with JSON
                                if ( 'json' !== dataType ) {
                                    return raw_response;
                                }

                                if ( wc_checkout_form.is_valid_json( raw_response ) ) {
                                    return raw_response;
                                } else {
                                    // Attempt to fix the malformed JSON
                                    var maybe_valid_json = raw_response.match( /{"result.*}/ );

                                    if ( null === maybe_valid_json ) {
                                        console.log( 'Unable to fix malformed JSON' );
                                    } else if ( wc_checkout_form.is_valid_json( maybe_valid_json[0] ) ) {
                                        console.log( 'Fixed malformed JSON. Original:' );
                                        console.log( raw_response );
                                        raw_response = maybe_valid_json[0];
                                    } else {
                                        console.log( 'Unable to fix malformed JSON' );
                                    }
                                }

                                return raw_response;
                            }
                        } );

                        $.ajax({
                            type     : 'POST',
                            url      : wc_checkout_params.checkout_url,
                            data     : $form.serialize(),
                            dataType : 'json',
                            success  : function( result ) {
                                // Detach the unload handler that prevents a reload / redirect
                                wc_checkout_form.detachUnloadEventsOnSubmit();

                                try {
                                    if ( 'success' === result.result && $form.triggerHandler( 'checkout_place_order_success', result ) !== false ) {
                                        if ( -1 === result.redirect.indexOf( 'https://' ) || -1 === result.redirect.indexOf( 'http://' ) ) {
                                            window.location = result.redirect;
                                        } else {
                                            window.location = decodeURI( result.redirect );
                                        }
                                    } else if ( 'failure' === result.result ) {
                                        throw 'Result failure';
                                    } else {
                                        throw 'Invalid response';
                                    }
                                } catch( err ) {
                                    // Reload page
                                    if ( true === result.reload ) {
                                        window.location.reload();
                                        return;
                                    }

                                    // Trigger update in case we need a fresh nonce
                                    if ( true === result.refresh ) {
                                        $( document.body ).trigger( 'update_checkout' );
                                    }

                                    // Add new errors
                                    if ( result.messages ) {
                                        wc_checkout_form.submit_error( result.messages );
                                    } else {
                                        wc_checkout_form.submit_error( '<div class="woocommerce-error">' + wc_checkout_params.i18n_checkout_error + '</div>' ); // eslint-disable-line max-len
                                    }
                                }
                            },
                            error:	function( jqXHR, textStatus, errorThrown ) {
                                // Detach the unload handler that prevents a reload / redirect
                                wc_checkout_form.detachUnloadEventsOnSubmit();

                                wc_checkout_form.submit_error( '<div class="woocommerce-error">' + errorThrown + '</div>' );
                            }
                        });
                    }

                    return false;
                },
                submit_error: function( error_message ) {
                    var $checkout_form = $( '.goldsmith-ajax-checkout-popup-wrapper form.checkout' );
                    $( '.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message' ).remove();
                    $checkout_form.prepend( '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' + error_message + '</div>' ); // eslint-disable-line max-len
                    $checkout_form.removeClass( 'processing' ).unblock();
                    $checkout_form.find( '.input-text, select, input:checkbox' ).trigger( 'validate' ).trigger( 'blur' );

                    $('.goldsmith-ajax-checkout-popup-wrapper form.checkout .swiper-slide.has-error').removeClass('has-error');
                    $('.goldsmith-checkout-popup-steps .swiper-slide.has-error').removeClass('has-error');

                    $('.goldsmith-ajax-checkout-popup-wrapper form.checkout .woocommerce-error li').each( function(){
                        var dataId = $(this).attr('data-id');
                        if ( dataId ) {
                            $('.goldsmith-ajax-checkout-popup-wrapper form.checkout .swiper-slide #'+dataId).parents('.swiper-slide').addClass('has-error');
                        } else {
                            $('.goldsmith-ajax-checkout-popup-wrapper form.checkout .swiper-slide.goldsmith-order-review').addClass('has-error');
                        }
                    });

                    $('.goldsmith-ajax-checkout-popup-wrapper form.checkout .woocommerce-error li').each( function(){
                        var dataId = $(this).attr('data-id');
                        var btntitle = goldsmith_vars.strings.button.show_field;
                        if ( dataId ) {
                            $('<span class="show-field" data-id="#'+dataId+'">'+btntitle+'</span>').appendTo($(this));
                        } else {
                            $('<span class="show-field" data-id="#terms">'+btntitle+'</span>').appendTo($(this));
                        }
                    });

                    $('.goldsmith-ajax-checkout-popup-wrapper form.checkout .swiper-slide.has-error').each( function(){
                        var dataId = $(this).attr('aria-label');
                        if ( dataId ) {
                            $('.goldsmith-checkout-popup-steps .swiper-slide[aria-label="'+dataId+'"]').removeClass('step-success').addClass('has-error');
                        } else {
                            $('.goldsmith-checkout-popup-steps .swiper-slide.step-order').addClass('has-error');
                        }
                    });

                    $('.goldsmith-checkout-popup-steps .step-billing:not(.has-error),.goldsmith-checkout-popup-steps .step-shipping:not(.has-error)').addClass('step-success');

                    $('body').trigger('get_first_error_slide');
                    //wc_checkout_form.scroll_to_notices();
                    $( document.body ).trigger( 'checkout_error' , [ error_message ] );
                },
                scroll_to_notices: function() {
                    //var scrollElement = $( '.woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout' );
                    //if ( ! scrollElement.length ) {
                    //scrollElement = $( '.goldsmith-ajax-checkout-popup-wrapper form.checkout' );
                    //}
                    //$.scroll_to_notices( scrollElement );
                }
            };

            var wc_checkout_coupons = {
                init: function() {
                    $( '.goldsmith-ajax-checkout-popup-wrapper a.showcoupon' ).off('click').on( 'click', this.show_coupon_form );
                    $( '.goldsmith-ajax-checkout-popup-wrapper .woocommerce-remove-coupon' ).off('click').on( 'click', this.remove_coupon );
                    $( '.goldsmith-ajax-checkout-popup-wrapper form.checkout_coupon' ).hide().on( 'submit', this.submit );
                },
                show_coupon_form: function() {
                    $( '.goldsmith-ajax-checkout-popup-wrapper .checkout_coupon' ).slideToggle( 400, function() {
                        $( '.goldsmith-ajax-checkout-popup-wrapper .checkout_coupon' ).find( ':input:eq(0)' ).trigger( 'focus' );
                    });
                    return false;
                },
                submit: function() {
                    var $form = $( this );

                    if ( $form.is( '.processing' ) ) {
                        return false;
                    }

                    $form.addClass( 'processing' ).block({
                        message: null,
                        overlayCSS: {
                            background: '#fff',
                            opacity: 0.6
                        }
                    });

                    var data = {
                        security    : wc_checkout_params.apply_coupon_nonce,
                        coupon_code : $form.find( 'input[name="coupon_code"]' ).val()
                    };

                    $.ajax({
                        type    : 'POST',
                        url     : wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'apply_coupon' ),
                        data    : data,
                        success : function( code ) {
                            $( '.woocommerce-error, .woocommerce-message' ).remove();
                            $form.removeClass( 'processing' ).unblock();

                            if ( code ) {
                                $form.before( code );
                                $form.slideUp();

                                $( document.body ).trigger( 'applied_coupon_in_checkout', [ data.coupon_code ] );
                                $( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
                            }
                        },
                        dataType : 'html'
                    });

                    return false;
                },
                remove_coupon: function( e ) {
                    e.preventDefault();

                    var container = $( this ).parents( '.woocommerce-checkout-review-order' ),
                    coupon    = $( this ).data( 'coupon' );

                    container.addClass( 'processing' ).block({
                        message    : null,
                        overlayCSS : {
                            background : '#fff',
                            opacity    : 0.6
                        }
                    });

                    var data = {
                        security : wc_checkout_params.remove_coupon_nonce,
                        coupon   :   coupon
                    };

                    $.ajax({
                        type    : 'POST',
                        url     : wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'remove_coupon' ),
                        data    : data,
                        success : function( code ) {
                            $( '.woocommerce-error, .woocommerce-message' ).remove();
                            container.removeClass( 'processing' ).unblock();

                            if ( code ) {
                                $( 'form.woocommerce-checkout' ).before( code );

                                $( document.body ).trigger( 'removed_coupon_in_checkout', [ data.coupon_code ] );
                                $( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );

                                // Remove coupon code from coupon field
                                $( 'form.checkout_coupon' ).find( 'input[name="coupon_code"]' ).val( '' );
                            }
                        },
                        error : function ( jqXHR ) {
                            if ( wc_checkout_params.debug_mode ) {
                                /* jshint devel: true */
                                console.log( jqXHR.responseText );
                            }
                        },
                        dataType : 'html'
                    });
                }
            };

            var wc_checkout_login_form = {
                init: function() {
                    $( document.body ).on( 'click', 'a.showlogin', this.show_login_form );
                },
                show_login_form: function() {
                    $( 'form.login, form.woocommerce-form--login' ).slideToggle();
                    return false;
                }
            };

            var wc_terms_toggle = {
                init: function() {
                    $( document.body ).on( 'click', 'a.woocommerce-terms-and-conditions-link', this.toggle_terms );
                },

                toggle_terms: function() {
                    if ( $( '.woocommerce-terms-and-conditions' ).length ) {
                        $( '.woocommerce-terms-and-conditions' ).slideToggle( function() {
                            var link_toggle = $( '.woocommerce-terms-and-conditions-link' );

                            if ( $( '.woocommerce-terms-and-conditions' ).is( ':visible' ) ) {
                                link_toggle.addClass( 'woocommerce-terms-and-conditions-link--open' );
                                link_toggle.removeClass( 'woocommerce-terms-and-conditions-link--closed' );
                            } else {
                                link_toggle.removeClass( 'woocommerce-terms-and-conditions-link--open' );
                                link_toggle.addClass( 'woocommerce-terms-and-conditions-link--closed' );
                            }
                        } );

                        return false;
                    }
                }
            };

            var wrapper = $('.goldsmith-ajax-checkout-popup-wrapper');

            $( '.goldsmith-ajax-checkout-popup-wrapper .goldsmith-panel-close-button,.goldsmith-ajax-checkout-popup-wrapper .goldsmith-ajax-checkout-popup-overlay' ).on( 'click touch', function(){
                wrapper.addClass('mfp-hide');
            });

            $(document.body).on('checkout_error', function(){

                $('.woocommerce-NoticeGroup.woocommerce-NoticeGroup-checkout .woocommerce-error .close-error').on('click', function() {
                    $(this).parent().slideUp(300);
                });
            });

            $('body').on('check_slide_item_input_status', function(){

                $('.goldsmith-ajax-checkout-popup-wrapper form.checkout').on('change','input', function(){
                    var $this    = $(this),
                    $error   = $this.parents('form.checkout').find('.woocommerce-error'),
                    $this_id = $this.attr('id'),
                    $message = $error.find('li[data-id="'+$this_id+'"]');

                    if ( $this.parents('.form-row').hasClass('woocommerce-validated') ) {
                        $message.hide(300);
                    } else {
                        if ( $this.val() === '' ) {
                            //var req   = goldsmith_vars.strings.form.req_suffix;
                            var strId = $this.attr('id');
                            var str   = strId.split('_');
                                str   = str.join(' ');
                            var new_message = '<li data-id="'+strId+'"><strong>'+str+'</strong> '+req+'.</li>';

                            if ( $($error).length > 0 ) {
                                if ( !$($error).find('li[data-id="'+strId+'"]').length > 0 ) {
                                    $(new_message).appendTo($($error));
                                } else {
                                    $message.show(300);
                                }
                            }
                        }
                    }
                    $('body').trigger('check_slide_item_steps_status');
                });
            });

            $('body').on('check_slide_item_steps_status', function(){
                var $this    = $('.goldsmith-ajax-checkout-popup-wrapper form.checkout .swiper-slide-active'),
                $this_Id = $this.attr('id'),
                $step    = $('.goldsmith-panel-checkout-labels .goldsmith-step-item[data-id="'+$this_Id+'"]');

                if ( $this.find('.form-row.validate-required.woocommerce-invalid').length > 0 ) {
                    $this.addClass('has-error').removeClass('step-success');
                    $step.addClass('has-error').removeClass('step-success');
                } else {
                    $this.removeClass('has-error').addClass('step-success');
                    $step.removeClass('has-error').addClass('step-success');
                }
            });

            $(document).on('click touch', 'a.goldsmith-ajax-checkout-popup', function(event){

                event.preventDefault();

                if ( $('.goldsmith-ajax-checkout-popup-wrapper').hasClass('checkout-loaded') ) {
                    wrapper.addClass('active').removeClass('mfp-hide');
                    return;
                }

                var data = {
                    cache      : false,
                    action     : 'goldsmith_ajax_checkout_popup',
                    beforeSend : function() {
                        wrapper.addClass('loading').removeClass('mfp-hide');
                    },
                    'ajaxurl'  : goldsmith_vars.ajax_url,
                };
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                $.post(goldsmith_vars.ajax_url, data, function(response) {

                    wrapper.addClass('active checkout-loaded').removeClass('loading');

                    if ( response ) {
                        wrapper.append(response);

                        var checkoutPopupFormSteps = new NTSwiper('.goldsmith-checkout-popup-steps', {
                            loop          : false,
                            speed         : 500,
                            slidesPerView : 3,
                            spaceBetween  : 1,
                            direction     : 'vertical',
                            navigation    : {
                                //nextEl: '.goldsmith-ajax-checkout-popup-wrapper .goldsmith-myaccount-form-button-register',
                                //prevEl: '.goldsmith-ajax-checkout-popup-wrapper .goldsmith-myaccount-form-button-login'
                            },
                            on            : {
                                resize : function () {
                                    var swiper = this;
                                    swiper.update();
                                }
                            },
                            breakpoints   : {
                                // when window width is >= 576px
                                576: {
                                    direction    : 'horizontal',
                                    spaceBetween : 0
                                }
                            }
                        });

                        var checkoutPopupFormMain = new NTSwiper('.goldsmith-checkout-popup-main', {
                            loop                : false,
                            simulateTouch       : false,
                            slideToClickedSlide : false,
                            autoplay            : false,
                            slidesPerView       : 1,
                            speed               : 500,
                            spaceBetween        : 30,
                            autoHeight          : true,
                            thumbs              : {swiper: checkoutPopupFormSteps},
                            navigation          : {
                                //nextEl: '.goldsmith-ajax-checkout-popup-wrapper .goldsmith-myaccount-form-button-register',
                                //prevEl: '.goldsmith-ajax-checkout-popup-wrapper .goldsmith-myaccount-form-button-login'
                            },
                            on                  : {
                                slideChange: function () {
                                    var swiper = this;
                                    var active = swiper.activeIndex;
                                    $('.goldsmith-panel-checkout-form-wrapper.goldsmith-scrollbar').scrollTop(0);
                                    $( '.goldsmith-checkout-popup-main .swiper-wrapper' ).removeClass('height-auto');
                                    $('body').trigger('check_slide_item_input_status');
                                },
                                resize: function () {
                                    var swiper = this;
                                    swiper.update();
                                }
                            }
                        });
                        $( document.body ).trigger( 'country_to_state_changed' );
                        $( document.body ).trigger( 'update_checkout' );
                        $( document.body ).trigger( 'init_add_payment_method' );
                        $( '.goldsmith-ajax-checkout-popup-wrapper .checkout div.shipping_address' ).hide();

                        var checkShippingInput = function () {
                            if ( $( '.goldsmith-ajax-checkout-popup-wrapper .checkout #ship-to-different-address-checkbox' ).is( ':checked' ) ) {
                                $( '.goldsmith-ajax-checkout-popup-wrapper .checkout div.shipping_address' ).slideDown(300);
                                $( '.goldsmith-checkout-popup-main .swiper-wrapper' ).addClass('height-auto');

                            } else {
                                $( '.goldsmith-ajax-checkout-popup-wrapper .checkout div.shipping_address' ).slideUp(300);
                                $('.step-shpping.has-error').removeClass('has-error').addClass('step-success');
                                $( '.goldsmith-checkout-popup-main .swiper-wrapper' ).addClass('auto-height-auto');
                            }
                            //checkoutPopupFormMain.updateAutoHeight(1000);
                            setTimeout(function(){
                                $( '.goldsmith-checkout-popup-main .swiper-wrapper' ).removeClass('height-auto');
                                $( '.goldsmith-checkout-popup-main .swiper-wrapper' ).removeClass('auto-height-auto');
                            }, 1000 );
                        }

                        checkShippingInput();

                        $( '.goldsmith-ajax-checkout-popup-wrapper .checkout' ).on( 'change', '#ship-to-different-address input', function(){
                            checkShippingInput();
                        });

                        $( 'body' ).on( 'get_first_error_slide', function(){
                            var inedexItem = $('.goldsmith-checkout-popup-steps .swiper-wrapper .swiper-slide.has-error').index();
                            checkoutPopupFormSteps.slideTo(inedexItem);
                            checkoutPopupFormMain.slideTo(inedexItem);
                            checkoutPopupFormSteps.update();
                            checkoutPopupFormMain.update();

                            $('.goldsmith-panel-checkout-form-wrapper.goldsmith-scrollbar').scrollTop(0);

                            $('.show-field').on('click',function(){
                                var target = $(this).data('id');
                                if ( $(target).parents('.goldsmith-customer-billing-details').hasClass('has-error') ) {
                                    if ( $(target).parents('.goldsmith-customer-billing-details').hasClass('swiper-slide-active') ) {
                                        $('.goldsmith-panel-checkout-form-wrapper.goldsmith-scrollbar').stop().animate({
                                            scrollTop: $(target).offset().top - 180
                                        }, 400);
                                        $(target).trigger( 'focus' );
                                        console.log($(target).offset().top);
                                    } else {
                                        checkoutPopupFormSteps.slideTo(0);
                                        checkoutPopupFormMain.slideTo(0);
                                        setTimeout(function(){
                                            $('.goldsmith-panel-checkout-form-wrapper.goldsmith-scrollbar').stop().animate({
                                                scrollTop: $(target).offset().top - 180
                                            }, 400);
                                            $(target).trigger( 'focus' );
                                        }, 700);
                                    }
                                }
                                if ( $(target).parents('.goldsmith-customer-shipping-details').hasClass('has-error') ) {
                                    if ( $(target).parents('.goldsmith-customer-shipping-details').hasClass('swiper-slide-active') ) {
                                        $('.goldsmith-panel-checkout-form-wrapper.goldsmith-scrollbar').stop().animate({
                                            scrollTop: $(target).offset().top - 180
                                        }, 400);
                                        $(target).trigger( 'focus' );
                                    } else {
                                        checkoutPopupFormSteps.slideTo(1);
                                        checkoutPopupFormMain.slideTo(1);
                                        setTimeout(function(){
                                            $('.goldsmith-panel-checkout-form-wrapper.goldsmith-scrollbar').stop().animate({
                                                scrollTop: $(target).offset().top - 180
                                            }, 400);
                                            $(target).trigger( 'focus' );
                                        }, 700);
                                    }
                                }
                                /*
                                if ( $(target).parents('.goldsmith-order-review').hasClass('has-error') ) {
                                    if ( $(target).parents('.goldsmith-order-review').hasClass('swiper-slide-active') ) {
                                        $('.goldsmith-panel-checkout-form-wrapper.goldsmith-scrollbar').stop().animate({
                                            scrollTop: $(target).offset().top - 180
                                        }, 400);
                                        $(target).trigger( 'focus' );
                                    } else {
                                        checkoutPopupFormSteps.slideTo(2);
                                        checkoutPopupFormMain.slideTo(2);
                                        setTimeout(function(){
                                            $('.goldsmith-panel-checkout-form-wrapper.goldsmith-scrollbar').stop().animate({
                                                scrollTop: $(target).offset().top - 180
                                            }, 400);
                                            $(target).trigger( 'focus' );
                                        }, 700);
                                    }
                                }
                                */
                            });
                        });

                        wc_checkout_form.init();
                        wc_checkout_coupons.init();
                        wc_checkout_login_form.init();
                        wc_terms_toggle.init();
                    }

                });
            });
            $( document.body ).on( 'country_to_state_changed', function(){
                $('.goldsmith-panel-checkout-form-wrapper.goldsmith-scrollbar').off("scroll").scrollTop(0);
            });

        }

    });

})(window, document, jQuery);
