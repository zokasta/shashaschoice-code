jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';

    multiStepCheckout();

    function multiStepCheckout() {
        var $body               = $('body'),
            login               = $('.goldsmith-woocommerce-checkout-login'),
            billing             = $('.goldsmith-customer-billing-details'),
            shipping            = $('.goldsmith-customer-shipping-details'),
            order               = $('.goldsmith-order-review'),
            payment             = $('.goldsmith-payment'),
            steps               = new Array(login, billing, shipping, order, payment);

        $body.on( 'updated_checkout goldsmith_multistep_myaccount_order_pay', function(e) {
            steps[4] = $('#payment');
            if(e.type == 'updated_checkout' ) {
                steps[4] = $('#payment');
            }
            $('#payment').find( 'input[name=payment_method]' ).on( 'click', function() {
                if ($('.payment_methods input.input-radio').length > 1) {
                    var target_payment_box = $('div.payment_box.' + $(this).attr('ID'));
                    if ($(this).is(':checked') && !target_payment_box.is(':visible')) {
                        $('div.payment_box').filter(':visible').slideUp(250);

                        if ($(this).is(':checked')) {
                            $('div.payment_box.' + $(this).attr('ID')).slideDown(250);
                        }
                    }
                } else {
                    $('div.payment_box').show();
                }

                if ($(this).data('order_button_text')) {
                    $('#place_order').val($(this).data('order_button_text'));
                } else {
                    $('#place_order').val($('#place_order').data('value'));
                }
            });
        });

        if ($body.hasClass('woocommerce-order-pay')) {
            $body.trigger('goldsmith_multistep_myaccount_order_pay');
        }

        $body.on('goldsmith_multistep_select2', function (event) {
            if ($().select2) {
                var wc_country_select_select2 = function () {
                    $('select.country_select, select.state_select').each(function () {
                        var select2_args = {
                            placeholder      : $(this).attr('placeholder'),
                            placeholderOption: 'first',
                            width            : '100%'
                        };

                        $(this).select2(select2_args);
                    });
                };

                wc_country_select_select2();

                $body.bind('country_to_state_changed', function () {
                    wc_country_select_select2();
                });
            }
        });

        $body.trigger('goldsmith_multistep_select2');

        if ( $('.goldsmith-page-multistep-checkout').length ) {

            var checkoutMultiSteps = new NTSwiper('.goldsmith-checkout-content', {
                loop          : false,
                speed         : 500,
                spaceBetween  : 10,
                autoHeight    : false,
                observe       : true,
                nested        : true,
                simulateTouch : false,
                navigation    : {
                    nextEl: '.goldsmith-checkout-content .goldsmith-checkout-button-next',
                    prevEl: '.goldsmith-checkout-content .goldsmith-checkout-button-prev'
                },
                on: {
                    resize: function () {
                        var swiper = this;
                        swiper.update();
                    },
                    slideChange: function () {
                        var swiper = this;
                        var realIndex = swiper.realIndex;
                        $( '.goldsmith-step-item:not(:eq('+realIndex+'))' ).addClass('active');
                        $( '.goldsmith-step-item:eq('+realIndex+')' ).next().removeClass('active');
                        $( '.goldsmith-step-item:eq('+realIndex+')' ).next().next().removeClass('active');
                    }
                },
                effect: 'slide',
                pagination: {
                    el: ".goldsmith-page-multistep-checkout .goldsmith-swiper-pagination",
                    type: 'bullets',
                    bulletClass: 'goldsmith-bullets',
                    bulletActiveClass: 'active',
                    clickable: true,
                    renderBullet: function (index, className) {
                        var labels = $('.goldsmith-page-multistep-checkout .goldsmith-swiper-pagination').data('steps-labels');
                        return '<div class="goldsmith-step-item goldsmith-step-item-' + (index + 1) + ' ' + className + '"><span class="goldsmith-step">' + (index + 1) + '</span><span class="goldsmith-step-label goldsmith-login">' + labels.labels[(index)] + '</span></div>';
                    }
                }
            });

            var checkoutLoginSteps = new NTSwiper('.goldsmith-checkout-form-login', {
                loop          : false,
                speed         : 500,
                spaceBetween  : 5,
                autoHeight    : false,
                observe       : true,
                simulateTouch : false,
                navigation    : {
                    nextEl: '.goldsmith-checkout-form-login .goldsmith-checkout-form-button-register',
                    prevEl: '.goldsmith-checkout-form-login .goldsmith-checkout-form-button-login'
                },
                on: {
                    resize: function () {
                        var swiper = this;
                        swiper.update();
                    }
                },
                effect: 'slide'
            });

            $('body').on('input validate change', '.input-text, select, input:checkbox', function(e){
                var $this       = $( this ),
                    $parent     = $this.closest( '.form-row' ),
                    event_type  = e.type;

                if ( 'validate' === event_type && $parent.hasClass( 'woocommerce-invalid-required-field' ) ) {

                    $( '.woocommerce-billing-fields .woocommerce-invalid-required-field' ).parents('.swiper-slide').addClass( 'has-error' );

                    setTimeout(function(){
                        var getInvalidSection = $( '.swiper-slide.has-error' ).index();
                        checkoutMultiSteps.slideTo(getInvalidSection);
                        checkoutMultiSteps.updateAutoHeight(10);
                    }, 300 );

                    if ( $('.woocommerce-NoticeGroup').length ) {
                        var $targetFirst = $('.woocommerce-NoticeGroup .woocommerce-error li:first-child').data('id'),
                            $targetFirstEl = $('#'+$targetFirst+'_field');
                    }
                }
            });
        }
    }
});
