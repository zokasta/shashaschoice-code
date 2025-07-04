jQuery(document).ready(function($) {

    'use strict';

    // AJax single add to cart
    $(document).on('click', 'a.goldsmith_ajax_add_to_cart', function(e){
        e.preventDefault();

        var btn    = $(this),
            pid    = btn.attr( 'data-product_id' ),
            qty    = parseFloat( btn.data('quantity') ),
            data   = new FormData();

        data.append('add-to-cart', pid);

        if ( qty > 0 ) {
            data.append('quantity', qty);
        }

        btn.addClass('loading');
        btn.closest('.goldsmith-add-to-cart-btn').addClass('loading');
        btn.closest('.goldsmith-product-loop-inner').addClass('loading');

        var lodingHtml = '<span class="loading-wrapper"><span class="ajax-loading"></span></span>';

        if ( btn.closest('.goldsmith-side-panel').length && btn.closest('.wishlist-area').length ) {
            if ( $('.goldsmith-side-panel .cart-empty-content').length ) {
                $('.goldsmith-side-panel .cart-empty-content').addClass('loading').append(lodingHtml);
                $('.goldsmith-side-panel [data-name="cart"]').trigger('click');
            } else {
                $('.goldsmith-side-panel .woocommerce-mini-cart').addClass('loading').append(lodingHtml);
                $('.goldsmith-side-panel [data-name="cart"]').trigger('click');
            }
        }
        if ( btn.closest('.goldsmith-header-mobile').length && btn.closest('.wishlist-area').length ) {
            if ( $('.goldsmith-header-mobile .cart-empty-content').length ) {
                $('.goldsmith-header-mobile .cart-empty-content').addClass('loading').append(lodingHtml);
                $('.goldsmith-header-mobile [data-name="cart"]').trigger('click');
            } else {
                $('.goldsmith-header-mobile .woocommerce-mini-cart').addClass('loading').append(lodingHtml);
                $('.goldsmith-header-mobile [data-name="cart"]').trigger('click');
            }
        }

        $.ajax({
            url        : goldsmith_vars.wc_ajax_url.toString().replace( '%%endpoint%%', 'goldsmith_ajax_add_to_cart' ),
            data       : data,
            type       : 'POST',
            processData: false,
            contentType: false,
            dataType   : 'json',
            success    : function( response ) {
                btn.removeClass('loading').addClass('added');
                btn.closest('.goldsmith-add-to-cart-btn').removeClass('loading').addClass('added');
                btn.closest('.goldsmith-product-loop-inner').removeClass('loading');

                var fragments = response.fragments;
                var appended  = '<div class="woocommerce-notices-wrapper">'+fragments.notices+'</div>';
                var duration  = goldsmith_vars.duration;

                $(appended).prependTo('.goldsmith-shop-popup-notices').delay(duration).fadeOut(300, function(){
                    $(this).remove();
                });

                // update other areas
                $('.goldsmith-minicart').replaceWith(fragments.minicart);
                $('.goldsmith-cart-count').html(fragments.count);
                $('.goldsmith-side-panel').attr('data-cart-count',fragments.count);
                $('.goldsmith-cart-total:not(.page-total)').html(fragments.total);
                if ( $('.goldsmith-cart-goal-text').length>0 ) {
                    $('.goldsmith-cart-goal-text').html(fragments.shipping.message);
                    $('.goldsmith-progress-bar').css('width',fragments.shipping.value+'%');
                    if ( fragments.shipping.value >= 100 ) {
                        $('.goldsmith-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                    }
                }

                // Redirect to cart option
                if ( goldsmith_vars.cart_redirect === 'yes' ) {
                    window.location = goldsmith_vars.cart_url;
                    return;
                }

                if ( goldsmith_vars.minicart_open === 'yes' ) {
                    $('html,body').addClass('goldsmith-overlay-open');
                    $('.goldsmith-side-panel,.panel-content .cart-area').addClass('active');
                }

                if ( goldsmith_vars.is_checkout == 'yes' ){
                    location.reload(); // page reload
                }
            },
            error: function() {
                $( document.body ).trigger( 'wc_fragments_ajax_error' );
            }
        });
    });

    $(document).on('click', '.goldsmith_remove_from_cart_button', function(e){
        e.preventDefault();

        var $this = $(this),
            pid   = $this.data('product_id'),
            note  = goldsmith_vars.removed,
            cart  = $this.closest('.goldsmith-minicart'),
            row   = $this.closest('.goldsmith-cart-item'),
            key   = $this.data( 'cart_item_key' ),
            name  = $this.data('name'),
            qty   = $this.data('qty'),
            msg   = qty ? qty+' &times '+name+' '+note : name+' '+note,
            btn   = $('.goldsmith_ajax_add_to_cart[data-product_id="'+pid+'"]'),
            dur   = goldsmith_vars.duration;
            msg   = '<div class="woocommerce-notices-wrapper"><div class="woocommerce-message">'+msg+'</div></div>';

        $(msg).appendTo('.goldsmith-shop-popup-notices').delay(dur).fadeOut(300, function(){
            $(this).remove();
        });

        cart.addClass('loading');

        row.remove();

        var cartItems = cart.find('.mini-cart-item').length;

        if ( cartItems == 0 ) {
            cart.addClass('no-products');
        }

        $.ajax({
            url      : goldsmith_vars.wc_ajax_url.toString().replace( '%%endpoint%%', 'goldsmith_remove_from_cart' ),
            type     : 'POST',
            dataType : 'json',
            data     : {
                cart_item_key : key
            },
            success  : function( response ){
                var fragments = response.fragments;

                $('.goldsmith-minicart').replaceWith(fragments.minicart);
                $('.goldsmith-cart-count').html(fragments.count);
                $('.goldsmith-side-panel').attr('data-cart-count',fragments.count);
                $('.goldsmith-cart-total:not(.page-total)').html(fragments.total);
                if ( $('.goldsmith-cart-goal-text').length>0 ) {
                    $('.goldsmith-cart-goal-text').html(fragments.shipping.message);
                    $('.goldsmith-progress-bar').css('width',fragments.shipping.value+'%');
                    if ( fragments.shipping.value >= 100 ) {
                        $('.goldsmith-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                    } else {
                        $('.goldsmith-cart-goal-wrapper').removeClass('free-shipping-success shakeY');
                    }
                }

                cart.removeClass('loading no-products');
                btn.removeClass('added');

                $( document.body ).trigger( 'removed_from_cart', [ fragments, response.cart_hash, btn ] );

                $('.goldsmith-product-inner[data-product_id="'+pid+'"] .reset_variations').trigger('click');

                if ( goldsmith_vars.is_cart == 'yes' ) {
                    location.reload(); // page reload
                }

                if ( goldsmith_vars.is_checkout == 'yes' ){
                    location.reload(); // page reload
                }
            },
            error: function() {
                $( document.body ).trigger( 'wc_fragments_ajax_error' );
            }
        });
    });

    $(document).on('click', '.product-remove .remove', function(e){
        var $this = $(this),
            pid   = $this.data('product_id');

        $( '.goldsmith-minicart .goldsmith_remove_from_cart_button[data-product_id="'+pid+'"]' ).trigger( 'click' );
    });

    $(document).on('updated_wc_div', function() {
        if ( goldsmith_vars.is_cart == 'yes' ) {
            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'goldsmith_ajax_add_to_cart' ),
                type: 'POST',
                data: {
                    action: 'goldsmith_ajax_add_to_cart'
                },
                success: function(response) {

                    var fragments = response.fragments;

                    $('.goldsmith-minicart').replaceWith(fragments.minicart);
                    $('.goldsmith-cart-count').html(fragments.count);
                    $('.goldsmith-side-panel').attr('data-cart-count',fragments.count);
                    $('.goldsmith-cart-total:not(.page-total)').html(fragments.total);

                    if ( $('.goldsmith-cart-goal-text').length>0 ) {
                        $('.goldsmith-cart-goal-text').html(fragments.shipping.message);
                        $('.goldsmith-progress-bar').css('width',fragments.shipping.value+'%');
                        if ( fragments.shipping.value >= 100 ) {
                            $('.goldsmith-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                        } else {
                            $('.goldsmith-cart-goal-wrapper').removeClass('free-shipping-success shakeY');
                        }
                    }
                }
            });
        }
    });

    $(document).on('click', '.goldsmith_clear_cart_button', function(e){
        var confirmMsg = goldsmith_vars.clear;
        if ( confirm( confirmMsg ) ){
            $.ajax({
                type     : 'POST',
                dataType : 'json',
                url      : wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'goldsmith_clear_cart' ),
                data     : {
                    action : 'goldsmith_clear_cart'
                },
                success  : function ( response ) {

                    var fragments = response.fragments;
                    var message   = fragments.clear.msg;
                    var duration  = goldsmith_vars.duration;

                    if ( fragments.clear.status != 'success' ) {
                        alert(message);
                    } else {

                        var appended = '<div class="woocommerce-notices-wrapper"><div class="woocommerce-message">'+message+'</div></div>';
                        $(appended).appendTo('.goldsmith-shop-popup-notices').delay(duration).fadeOut(300, function(){
                            $(this).remove();
                        });

                        // update other areas
                        $('.goldsmith-minicart').replaceWith(fragments.minicart);
                        $('.goldsmith-cart-count').html(fragments.count);
                        $('.goldsmith-side-panel').attr('data-cart-count',fragments.count);
                        $('.goldsmith-cart-total:not(.page-total)').html(fragments.total);

                        if ( $('.goldsmith-cart-goal-text').length>0 ) {
                            $('.goldsmith-cart-goal-text').html(fragments.shipping.message);
                            $('.goldsmith-progress-bar').css('width',fragments.shipping.value+'%');
                            $('.goldsmith-cart-goal-wrapper').removeClass('free-shipping-success shakeY');
                        }

                        location.reload(); // page reload

                        $(document.body).trigger('goldsmith_reset_all_cart_btn');
                    }
                }
            });
        }
    });

    // AJax cart cuantity
    var timeout;

    $(document).on('change input', '.cart-quantity-wrapper .quantity .qty', function() {

        var input = $(this),
            qty   = input.val(),
            key   = input.parents('.cart-quantity-wrapper').data('key'),
            id    = input.parents('.cart-quantity-wrapper').data('product_id'),
            name  = input.parents('.woocommerce-mini-cart-item').find('.cart-name').html();

        if ( goldsmith_vars.is_cart == 'yes' ) {
            var referer = $('.goldsmith-cart-row .goldsmith-hidden input[name="_wp_http_referer"]');
        }
        if ( input.parents('.goldsmith-loop-product').length ) {
            name = input.parents('.goldsmith-loop-product').find('.goldsmith-loop-product-name').html();
        }

        clearTimeout(timeout);

        timeout = setTimeout(function() {
            $.ajax({
                url     : goldsmith_vars.ajax_url,
                dataType: 'json',
                method  : 'GET',
                data    : {
                    action  : 'goldsmith_quantity_button',
                    id      : key,
                    qty     : qty,
                    is_cart : goldsmith_vars.is_cart
                },
                beforeSend  : function(){
                    if ( input.parents('.woocommerce-mini-cart-item').length ) {
                        input.parents('.woocommerce-mini-cart-item').addClass('loading').append('<span class="loading-wrapper"><span class="ajax-loading"></span></span>');
                    } else {
                        input.parents('.cart-quantity-wrapper').addClass('loading');
                    }
                },
                success : function(data) {
                    input.parents('.cart-quantity-wrapper').removeClass('loading');

                    if (data && data.fragments) {

                        var fragments = data.fragments;
                        var duration  = goldsmith_vars.duration;
                        var appended  = '';

                        if ( fragments.count != 0 ) {
                            if ( qty == 0 ) {
                                appended  = '<div class="woocommerce-notices-wrapper"><div class="woocommerce-message update-message"><span class="update">'+goldsmith_vars.updated+'</span> <strong>"'+name+'"</strong> '+goldsmith_vars.removed+'</div></div>';
                            } else {
                                appended  = '<div class="woocommerce-notices-wrapper"><div class="woocommerce-message update-message"><span class="update">'+goldsmith_vars.updated+'</span>'+qty+'&times <strong>"'+name+'"</strong> '+goldsmith_vars.added+'</div></div>';
                            }
                        }

                        if ( fragments.count == 0 ) {
                            appended  = '<div class="woocommerce-notices-wrapper"><div class="woocommerce-message update-message">'+fragments.update.msg+'</div></div>';
                        }

                        $(appended).prependTo('.goldsmith-shop-popup-notices').delay(duration).fadeOut(300, function(){
                            $(this).remove();
                        });

                        // update other areas
                        $('.goldsmith-minicart').replaceWith(fragments.minicart);
                        $('.goldsmith-cart-count').html(fragments.count);
                        $('.goldsmith-side-panel').data('cart-count',fragments.count);
                        $('.goldsmith-cart-total:not(.page-total)').html(fragments.total);

                        if ( $('.goldsmith-cart-goal-wrapper').length>0 ) {
                            $('.goldsmith-cart-goal-text').html(fragments.shipping.message);
                            $('.goldsmith-progress-bar').css('width',fragments.shipping.value+'%');
                            if ( fragments.shipping.value >= 100 ) {
                                $('.goldsmith-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                            } else {
                                $('.goldsmith-cart-goal-wrapper').removeClass('free-shipping-success shakeY');
                            }
                        }

                        $(document.body).trigger('goldsmith_update_minicart');

                        if ( goldsmith_vars.is_cart == 'yes' && fragments.count != 0  ) {
                            $('.goldsmith-cart-row').replaceWith(fragments.update.cart);
                            $('.goldsmith-cart-row .goldsmith-hidden input[name="_wp_http_referer"]').replaceWith(referer);
                        }

                        if ( $('.cross-sells .goldsmith-swiper-slider').length>0 ) {
                            $('.goldsmith-swiper-slider').each(function () {
                                const options  = $(this).data('swiper-options');
                                const mySlider = new NTSwiper(this, options );
                            });
                        }

                        if ( goldsmith_vars.is_cart == 'yes' ) {
                            location.reload();
                        }

                        if ( goldsmith_vars.is_checkout == 'yes' ) {
                            location.reload();
                        }
                    }
                },
                error: function() {
                    console.log('error');
                    $( document.body ).trigger( 'wc_fragments_ajax_error' );
                }
            });
        }, 500);
    });

});
