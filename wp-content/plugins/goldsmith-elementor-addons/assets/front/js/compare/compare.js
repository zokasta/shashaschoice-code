/*-----------------------------------------------------------------------------------

    Theme Name: Goldsmith
    Description: WordPress Theme
    Author: Ninetheme
    Author URI: https://ninetheme.com/
    Version: 1.0

-----------------------------------------------------------------------------------*/

//var wishlist_vars = {};
"use strict";

(function(window, document, $) {

    function set_Cookie(cname, cvalue, exdays) {
        var d = new Date();

        d.setTime(d.getTime() + (
            exdays * 24 * 60 * 60 * 1000
        ));

        var expires = 'expires=' + d.toUTCString();

        document.cookie = cname + '=' + cvalue + '; ' + expires + '; path=/';
    }

    function get_Cookie(cname) {
        var name = cname + '=';
        var ca = document.cookie.split(';');

        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];

            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }

            if (c.indexOf(name) == 0) {
                return decodeURIComponent(c.substring(name.length, c.length));
            }
        }

        return '';
    }

    function getProducts() {
        var cookie = 'goldsmith_products',
            cookie = compare_vars.user_id != '' ? 'goldsmith_products_' + compare_vars.user_id : '';

        return get_Cookie( cookie ) != '' ? get_Cookie( cookie ) : '';
    }

    function addProduct( id ) {
        var cookie = 'goldsmith_products',
            count,
            limit  = false,
            notice = compare_vars.notice,
            btn    = $('.goldsmith-compare-btn[data-id="' + id + '"]');

        if ( compare_vars.user_id != '' ) {
            cookie = 'goldsmith_products_' + compare_vars.user_id;
        }

        if ( get_Cookie( cookie ) != '' ) {
            var products = get_Cookie( cookie ).split(',');

            if ( products.length < compare_vars.limit ) {
                products = $.grep( products, function( value ) {
                    return value != id;
                });
                products.unshift( id );

                var products = products.join();

                set_Cookie( cookie, products, 7 );
            } else {
                limit = true;
                notice = notice.replace( '{max_limit}', compare_vars.limit );
            }

            count = products.length;

        } else {
            set_Cookie( cookie, id, 7 );
            count = 1;
        }

        if ( limit ) {
            alert( notice );
        } else {
            btn.addClass('added');
        }
    }

    function removeProduct( id ) {
        var cookie = 'goldsmith_products',
            count  = 0,
            btn    = $('.goldsmith-compare-btn[data-id="' + id + '"]'),
            cookie = compare_vars.user_id != '' ? 'goldsmith_products_' + compare_vars.user_id : '';

        if ( cookie != '' ) {
            var products = get_Cookie( cookie ).split(',');

            products = $.grep( products, function( value ) {
                return value != id;
            });

            var products_str = products.join();

            set_Cookie( cookie, products_str, 7 );
            count = products.length;
        }

        btn.removeClass('added');
    }

    function get_count() {
        var products = getProducts(),
            count = 0;

        if ( products != '' ) {
            var arr = products.split(',');
                count = arr.length;
        }
        return count;
    }

    function change_count() {
        var count = get_count();
        $('[data-compare-count]').attr('data-compare-count', count );
        $('.goldsmith-compare-count').html( count );
        compare_vars.count = count;
    }

    // add product to compare list
    $(document).on('click touch', '.goldsmith-compare-btn', function(e) {
        var $this = $( this ),
            id = $this.attr('data-id');

        if ( $this.hasClass('added') ) {
            addCompare( 'add', id );
        } else {
            $this.addClass('added');
            if ( $this.parents('.goldsmith-product-loop-inner') ) {
            	$this.parents('.goldsmith-product-loop-inner').addClass('loading');
            } else {
            	$this.parent().append('<span class="loading-wrapper"><span class="ajax-loading"></span></span>').addClass('loading');
            }
            
            addProduct( id );
            addCompare( 'add', id );
        }

        if ( get_count() == '0' ) {
            $('.compare-area').removeClass('has-product');
        } else {
            $('.compare-area').addClass('has-product');
        }

        e.preventDefault();
    });

    // remove from compare list
    $(document).on('click touch', '.goldsmith-compare-del-icon', function(e) {
        var id = $(this).parents('.goldsmith-content-item').attr('data-id');

        $('.goldsmith-compare-item[data-id="' + id + '"]').remove();
        $( '.goldsmith-compare-btn[data-id="' + id + '"]').removeClass('added');
        //$( '.goldsmith-product-summary .goldsmith-summary-item .goldsmith-compare-btn[data-id="' + id + '"]').removeClass('added');
        removeProduct( id );

        change_count();

        if ( get_count() == '0' ) {
            $('.compare-area').removeClass('has-product');
        } else {
            $('.compare-area').addClass('has-product');
        }

        e.preventDefault();
    });


    function addCompare( $action, id ) {
        var data = {
            action: 'goldsmith_add_compare',
            products: getProducts(),
            nonce: compare_vars.nonce
        };

        $.post( compare_vars.ajaxurl, data, function( response ) {

            $('.goldsmith-compare-content-items').html( response );
            $('body').addClass('goldsmith-overlay-open');
            $('.goldsmith-side-panel div:not([data-name="compare"])').removeClass('active');
            $('.goldsmith-side-panel, .compare-area, .goldsmith-side-panel div[data-name="compare"]').addClass('active');
            $( '.goldsmith-compare-btn[data-id="' + id + '"]').parents('.goldsmith-product-loop-inner').removeClass('.loading');
            $( '.goldsmith-compare-btn[data-id="' + id + '"]').parent().find('.loading-wrapper').remove();
            $( '.goldsmith-compare-btn[data-id="' + id + '"]').parent().removeClass('loading');
            change_count();
            $('body').trigger('goldsmith_lazy_load');

        });
    }

    $( document ).ready( function( $ ) {
        $('.goldsmith-compare-count').html( compare_vars.count );
    });

    if ( ( typeof compare_vars != 'undefined' ) && compare_vars.products ) {
        var ids = compare_vars.products;
        for (let i = 0; i < ids.length; i++) {
          $('.goldsmith-compare-btn[data-id="'+ids[i]+'"]').addClass('added');
        }
    }


})(window, document, jQuery);
