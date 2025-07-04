jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';

    $(document.body).on('added_to_cart removed_from_cart updated_cart_totals', function(){
        $(".goldsmith-product-bottom-popup-cart").removeClass('active');
    });

    if ( $(window).width() < 992 && $(".goldsmith-bottom-mobile-nav").length ) {
        $("body").addClass('has-bottom-fixed-menu');
    }

    var singleCartPos = $('.goldsmith-product-summary .single_add_to_cart_button').offset();
    var singleCartTop = $('.goldsmith-product-summary .single_add_to_cart_button').length && $(".goldsmith-product-bottom-popup-cart").length ? singleCartPos.top : 0;
    var singleDocHeight = $(document).height() - 25;

    $(window).on("scroll", function () {

        if ( $(".goldsmith-product-bottom-popup-cart").length && $(".goldsmith-product-summary .single_add_to_cart_button").length ) {

            if ( $(window).scrollTop() > singleCartTop ) {
                $(".goldsmith-product-bottom-popup-cart").addClass('active');
                $("body").addClass('bottom-popup-cart-active');
            } else {
                $(".goldsmith-product-bottom-popup-cart").removeClass('active');
                $("body").removeClass('bottom-popup-cart-active');
            }
            
            if($(window).scrollTop() + $(window).height() > singleDocHeight ) {
                $(".goldsmith-product-bottom-popup-cart").addClass('relative');
            } else {
                $(".goldsmith-product-bottom-popup-cart").removeClass('relative');
            }
        }
    });

});

