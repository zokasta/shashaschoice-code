(function(window, document, $) {

"use strict";

jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';

    var scrollOffset = $('.goldsmith-header-default').height();

    if ( $('body').hasClass('admin-bar') ) {
        scrollOffset = scrollOffset + 32;
    }

    /**
    * scrollToTop
    */
    function scrollToTop(target,delay,timeout) {
        setTimeout(function(){
            $('html, body').stop().animate({
                scrollTop: target.offset().top - scrollOffset
            }, delay);
        }, timeout );
    }


    /***** shop sidebar *****/

    var scrollToTopSidebar = function() {
        var shopP = 30;

        if ( $('body').hasClass('admin-bar') ) {
            shopP = 32;
        }

        $('html, body').stop().animate({
            scrollTop: $('.shop-area').offset().top - shopP
        }, 400);
    };

    $(document.body).on('click','.goldsmith-open-fixed-sidebar,.goldsmith-close-sidebar', function () {
        $('body').toggleClass('goldsmith-overlay-open');
        $('.nt-sidebar').toggleClass('active');
    });

    $(document.body).on('click','.goldsmith-toggle-hidden-sidebar', function (e) {

        $('.goldsmith-toggle-hidden-sidebar').toggleClass('active');
        $('.nt-sidebar').toggleClass('active').slideToggle();

        setTimeout(function(){
            scrollToTopSidebar();
        }, 100 );
    });

    $('.nt-sidebar ul.product-categories li.cat-parent> ul.children').each( function (e) {
        $(this).before('<span class="subDropdown"></span>');
        $(this).slideUp();
    });

    goldsmithWcProductCats();

    $(document).on('goldsmithShopInit', function() {
        goldsmithWcProductCats();
    });

    function goldsmithWcProductCats() {
        $('.widget_goldsmith_product_categories ul.children input[checked]').closest('li.cat-parent').addClass("current-cat");
    }

    $(document.body).on('click','.nt-sidebar ul li.cat-parent .subDropdown', function (e) {
        if ( $(this).hasClass('active') ) {
            $(this).removeClass('active minus').addClass("plus");
            $(this).next('.children').slideUp('slow');
        } else {
            $(this).removeClass('plus').addClass("active minus");
            $(this).next('.children').slideDown('slow');
        }
    });

    if ( typeof goldsmith_vars !== 'undefined' && goldsmith_vars ) {
        var colors = goldsmith_vars.swatches;

        $('.woocommerce-widget-layered-nav-list li a').each(function () {
            var $this = $(this);
            var title = $this.html();
            for (var i in colors) {
                if ( title == i ) {
                    var is_white = colors[i] == '#fff' || colors[i] == '#ffffff' ? ' is_white' : '';
                    var color = '<span class="goldsmith-swatches-widget-color-item'+is_white+'" style="background-color: '+colors[i]+';"></span>';
                    $this.prepend(color);
                }
            }
        });
    }

    if ( $(window).width() < 992 ) {
        var columnSize = $('.goldsmith-shop-hidden-top-sidebar').data('column');
        $('.goldsmith-shop-hidden-top-sidebar').removeClass('d-none active').removeAttr('style');
        $('.goldsmith-toggle-hidden-sidebar').removeClass('active');
        $('.goldsmith-shop-hidden-top-sidebar:not(.d-none) .nt-sidebar-inner').removeClass(columnSize);
    }

    $(window).on('resize', function(){
        var columnSize = $('.goldsmith-shop-hidden-top-sidebar').data('column');
        if ( $(window).width() >= 992 ) {
            if ( $('body').hasClass('goldsmith-overlay-open') ) {
                $('body').removeClass('goldsmith-overlay-open');
                $('.goldsmith-shop-hidden-top-sidebar').removeClass('active');
            }
            $('.goldsmith-shop-hidden-top-sidebar').addClass('d-none');
            $('.goldsmith-shop-hidden-top-sidebar .nt-sidebar-inner').addClass(columnSize);
        }
        if ( $(window).width() < 992 ) {
            $('.goldsmith-shop-hidden-top-sidebar').removeClass('d-none active').removeAttr('style');
            $('.goldsmith-toggle-hidden-sidebar').removeClass('active');
            $('.goldsmith-shop-hidden-top-sidebar:not(.d-none) .nt-sidebar-inner').removeClass(columnSize);
        }
    });

    /***** shop sidebar *****/


    /***** cart shipping form show-hide start *****/

    $('.goldsmith-shipping-calculator-button').on('click', function (e) {
        var cartTotals = $('.goldsmith-cart-totals'),
            form = $('.shipping-calculator-form');

        if ( cartTotals.hasClass('active')) {
            cartTotals.removeClass('active');
            form.slideUp('slow');
        } else {
            cartTotals.addClass('active');
            form.slideDown('slow');
            setTimeout(function(){
                $('html, body').stop().animate({
                    scrollTop: cartTotals.offset().top - scrollOffset
                }, 400);
            }, 300 );
        }
    });

    /***** cart shipping form show-hide end *****/

    $('.goldsmith-variations .goldsmith-small-title').sameSize(true);

    /***** panel Cart Content Height start *****/

    function panelCartContentHeight() {
        if ( $('.goldsmith-side-panel .cart-area').length ) {
            var cartPos          = $('.goldsmith-side-panel .panel-content').position();
            var cartFooterHeight = $('.goldsmith-side-panel .woocommerce-mini-cart').length();
            var cartMaxHeight    = cartPos.top + cartFooterHeight + 80;
        }
    }

    jQuery.fn.isChildOverflowing = function (child) {
        var p = jQuery(this).get(0);
        var el = jQuery(child).get(0);
        if ( jQuery(child).length>0 ) {
            return (el.offsetTop < p.offsetTop || el.offsetLeft < p.offsetLeft) ||
            (el.offsetTop + el.offsetHeight > p.offsetTop + p.offsetHeight || el.offsetLeft + el.offsetWidth > p.offsetLeft + p.offsetWidth);
        }
    };
    var is_overflow = jQuery('.goldsmith-side-panel').isChildOverflowing('.panel-content');
    if ( is_overflow ) {
        jQuery('.goldsmith-side-panel').addClass('goldsmith-scrollbar');
    }

    $(document.body).on('added_to_cart removed_from_cart updated_cart_totals', function(){
        setTimeout( function(){
            var is_overflow = jQuery('.goldsmith-side-panel').isChildOverflowing('.panel-content');
            if ( is_overflow ) {
                jQuery('.goldsmith-side-panel').addClass('goldsmith-scrollbar');
            }
        },500);
    });

    /***** panel Cart Content Height *****/


    /***** shop-popup-notices close trigger *****/

    $('.goldsmith-shop-popup-notices .goldsmith-panel-close-button').on('click', function() {
        $('.goldsmith-shop-popup-notices').removeClass('active');
        setTimeout(function(){
            $('.goldsmith-shop-popup-notices').removeClass('goldsmith-notices-has-error');
        }, 1000 );
    });

    /***** shop-popup-notices close trigger *****/


    /***** panel Free Shipping Progressbar *****/

    if ( $('.goldsmith-cart-goal-wrapper').length>0 ) {
        var val = $('.goldsmith-cart-goal-wrapper .goldsmith-cart-goal-percent').data('value');
        if ( val >= 100 ) {
            $('.goldsmith-cart-goal-wrapper').addClass('free-shipping-success shakeY');
        }
    }

    /***** panel Free Shipping Progressbar *****/


    /***** my account page multisteps slider *****/

    if ( $('.goldsmith-myaccount-steps-register').length>0 ) {
        var myAccountFormSteps = new NTSwiper('.goldsmith-myaccount-steps-register', {
            loop          : false,
            speed         : 500,
            spaceBetween  : 0,
            autoHeight    : false,
            simulateTouch : false,
            observer      : true,
            observerChildren      : true,
            navigation    : {
                nextEl: '.goldsmith-myaccount-steps-register .goldsmith-myaccount-form-button-register',
                prevEl: '.goldsmith-myaccount-steps-register .goldsmith-myaccount-form-button-login'
            },
            on: {
                resize: function () {
                    var swiper = this;
                    swiper.update();
                }
            },
            effect: 'slide'
        });
    }

    /***** my account page multisteps slider *****/



    /***** compare button fix *****/

    if ( $('#woosc-area').length> 0) {
        var woosc = $('#woosc-area').data('count');
        $('.has-custom-action.open-compare-btn .goldsmith-compare-count').html(woosc);
        $('.panel-header-compare .goldsmith-compare-count').html(woosc);

        $('.woosc-bar-item').each(function () {
            var $id = $(this).data('id');
            $('.goldsmith-product-button.woosc-btn[data-id="'+$id+'"]').addClass('woosc-added added');
        });
    }

    $(document.body).on('woosc_change_count', function(){
	     var woosc_count = $('#woosc-area').attr('data-count');
	     $('.has-custom-action.open-compare-btn .goldsmith-compare-count').html(woosc_count);
	     $('.panel-header-compare .goldsmith-compare-count').html(woosc_count);
    });

    /***** compare button fix *****/


    /***** change sku *****/

    var $mainSkuHtml = $('.goldsmith-sku-wrapper .sku'),
        $mainSku     = $mainSkuHtml.html();

    $('.goldsmith-product-summary form.variations_form').on('show_variation', function( event, data ){
        $mainSkuHtml.html(data.sku);
    });
    $('.goldsmith-product-summary form.variations_form').on('hide_variation', function(){
        $mainSkuHtml.html($mainSku);
    });

    $('.goldsmith-loop-swatches .variations_form').on('show_variation', function( event, data ){
        console.log(event);
        console.log(data);
    });

    /***** change sku *****/


    $('.woocommerce-product-rating').addClass('goldsmith-summary-item');

    if ($('body').hasClass('woocommerce-checkout') || $('body').hasClass('woocommerce-cart')) {
        $('.goldsmith-woocommerce-cart-form .product-remove').on('click touch', function(event) {
            $(this).addClass('loading');
        });
    }

    $('.goldsmith-shop-filter-top-area .goldsmith-block-right>div:last-child').addClass('last-child');

    if ( $("#goldsmith-sticky-cart-toggle").length > 0 ) {
        var flyCart   = $("#goldsmith-sticky-cart-toggle");
        var cartCount = $("#goldsmith-sticky-cart-toggle .goldsmith-wc-count").text();
        var duration  = parseFloat(flyCart.data('duration'));

        if ( cartCount != 0 ) {
            flyCart.addClass('active');
        }

        $(document.body).on('added_to_cart removed_from_cart updated_cart_totals', function(){
            var cartCount = $("#goldsmith-sticky-cart-toggle .goldsmith-wc-count").text();
            if ( cartCount != 0 ) {
                flyCart.addClass('active');
            } else {
                flyCart.removeClass('active');
            }
        });

        $(document).on('click', '.add_to_cart_button.product_type_simple', function() {
            if ( $(this).closest('.goldsmith-quickview-wrapper').length ) {
                var img    = $(this).closest('.goldsmith-quickview-wrapper').find('.swiper-wrapper .swiper-slide:first-child img'),
                    src    = img.attr('src'),
                    pos    = img.offset(),
                    width  = img.width(),
                    endPos = flyCart.offset();
            } else {
                var img    = $(this).closest('.goldsmith-loop-product').find('.goldsmith-product-thumb-wrapper img'),
                    src    = img.attr('src'),
                    pos    = img.offset(),
                    width  = img.width(),
                    endPos = flyCart.offset();
            }

            $('body').append('<div id="goldsmith-cart-fly"><img src="' + src + '"></div>');

            $('#goldsmith-cart-fly').css({
                'top'   : pos.top + 'px',
                'left'  : pos.left + 'px',
                'width' : width + 'px',
            }).animate({
                opacity : 1,
                top     : endPos.top,
                left    : endPos.left,
                'width' : '60px',
                'height': 'auto',
            }, duration, 'linear', function() {
                var $this = $(this);
                flyCart.addClass('added');
                $this.fadeOut(1000);
                $(this).detach();
            });
        });

        flyCart.on('click', function() {
            if ( $(this).hasClass('has-page-link') ){
                return;
            }
            $('html,body').addClass('goldsmith-overlay-open');
            $('.goldsmith-side-panel .panel-header-btn[data-name="cart"]').trigger('click');
            $('.goldsmith-side-panel').addClass('active');
        });
    }

});

})(window, document, jQuery);
