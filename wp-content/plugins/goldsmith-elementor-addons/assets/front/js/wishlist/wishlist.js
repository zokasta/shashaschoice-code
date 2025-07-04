/*-----------------------------------------------------------------------------------

    Theme Name: Styler
    Description: WordPress Theme
    Author: Ninetheme
    Author URI: https://ninetheme.com/
    Version: 1.0

-----------------------------------------------------------------------------------*/
"use strict";

(function(window, document, $) {

    if (goldsmith_wishlist_get_cookie('goldsmith_wishlist_key') == '') {
        goldsmith_wishlist_set_cookie('goldsmith_wishlist_key', goldsmith_wishlist_get_key(), 7);
    }

    function goldsmith_wishlist_get_key() {
        var result = [];
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var charactersLength = characters.length;

        for (var i = 0; i < 6; i++) {
            result.push(characters.charAt(Math.floor(Math.random() *
            charactersLength)));
        }

        return result.join('');
    }

    function goldsmith_wishlist_set_cookie(cname, cvalue, exdays) {
        var d = new Date();

        d.setTime(d.getTime() + (
            exdays * 24 * 60 * 60 * 1000
        ));

        var expires = 'expires=' + d.toUTCString();

        document.cookie = cname + '=' + cvalue + '; ' + expires + '; path=/';
    }

    function goldsmith_wishlist_get_cookie(cname) {
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

    $( '.goldsmith-panel-content-items .goldsmith-content-item' ).each(function(){
        var id = $(this).data('id');
        $('.goldsmith-wishlist-btn[data-id="'+id+'"]').addClass('added');
    });

    $( document.body ).on('click touch', '.goldsmith-wishlist-btn.added', function(e) {
        var $this    = $(this),
            name     = $this.parents('.goldsmith-loop-product').find('.goldsmith-product-name>a').html(),
            appended = '<div class="woocommerce-notices-wrapper"><div class="woocommerce-message wishlist-message"><div class="goldsmith-small-title"><strong class="product-name">'+name+'</strong> <span>'+wishlist_vars.already+'</span></div></div></div>';

        $(appended).prependTo('.goldsmith-shop-popup-notices').delay(3500).fadeOut(300, function(){
            $(this).remove();
        });
    });

    // add
    $( document.body ).on('click touch', '.goldsmith-wishlist-btn:not(.added)', function(e) {
        var $this = $(this),
            id = $this.attr('data-id'),
            count = $('[data-wishlist-count]').attr('data-wishlist-count');

        if ( ( typeof wishlist_vars != 'undefined' ) && wishlist_vars.products ) {
            var max_count = wishlist_vars.max_count;
            if ( wishlist_vars.is_login == 'yes' ) {
                alert(wishlist_vars.login_mesage);
               return;
            }
            if ( max_count != '' && ( count == max_count || count > max_count ) ) {
                alert(wishlist_vars.max_message);
               return;
            }
        }
        var data = {
            action     : 'goldsmith_wishlist_add',
            product_id : id,
            beforeSend : function() {
                if ($this.parent().is('.goldsmith-product-after-cart')) {
                    $this.append('<span class="loading-wrapper"><span class="ajax-loading"></span></span>').addClass('loading');
                } else {
                    $this.parent().append('<span class="loading-wrapper"><span class="ajax-loading"></span></span>').addClass('loading');
                }
            }
        };
        $.post(wishlist_vars.ajax_url, data, function(response) {
            response = JSON.parse( response );
            var count    = response['count'];
            var notice   = response['notice'];
            var products = wishlist_vars.products;
            if ( typeof wishlist_vars != 'undefined' && wishlist_vars.btn_action == 'message' ) {
                $('.goldsmith-wishlist-count').html(count);
                $('[data-wishlist-count]').attr('data-wishlist-count',count).addClass('has-products');
                $('.goldsmith-wishlist-content-items').html(response['value']);
                var appended = '<div class="woocommerce-notices-wrapper">'+notice+'</div>';
                $(appended).prependTo('.goldsmith-shop-popup-notices').delay(3500).fadeOut(300, function(){
                    $(this).remove();
                });
            } else {
                $('.goldsmith-wishlist-count').html(count);
                $('[data-wishlist-count]').attr('data-wishlist-count', count).addClass('has-products');
                $('.panel-content-item').removeClass('active');
                $('.goldsmith-side-panel').addClass('active');
                $('.wishlist-content').parent().addClass('active');
                $('.goldsmith-wishlist-content-items').html(response['value']);
                $('.goldsmith-wishlist-content-notice').html( notice );
                $('body').trigger('goldsmith_lazy_load');
                $('body').addClass('goldsmith-overlay-open');
                setTimeout(function() {
                    $('.goldsmith-wishlist-content-notice .goldsmith-small-title').fadeOut( 'fast' );
                }, 3000);
            }
            $this.addClass('added');
            setTimeout(function() {
                $this.removeClass('loading');
                $this.parent().removeClass('loading');
                $this.parent().find('.loading-wrapper').remove();
                $this.find('.loading-wrapper').remove();
            }, 1000);
            products.push(parseFloat(id));
            console.log(wishlist_vars.products);
        });

        e.preventDefault();
    });
    // remove
    $( document ).on('click touch', '.clear-all-wishlist', function(e) {

        $( '.goldsmith-panel-content-items .goldsmith-content-item' ).each(function(){
            var removed_id = $(this).data('id');
            $(this).remove();
            $('.goldsmith-wishlist-btn[data-id="'+removed_id+'"]').removeClass('added');
        });

        var data = {
            action : 'goldsmith_wishlist_clear',
        };

        $.post(wishlist_vars.ajax_url, data, function(response) {
            response = JSON.parse( response );
            if ( response['notice_type'] == 'empty' ) {
                $('.goldsmith-wishlist-content-notice').html( response['notice'] );
                $('.goldsmith-wishlist-count').html('0');
                $('[data-wishlist-count]').attr('data-wishlist-count', '0').removeClass('has-products');
            }
            if ( wishlist_vars.wishlist_page == 'yes' ) {
                $('.wishlist-all-items').html( response['notice'] );
            }
        });
        e.preventDefault();
    });

    $( document ).on('click touch', '.goldsmith-wishlist-del-icon', function(e) {

        var $this = $(this),
            product_id = $this.parents('.goldsmith-content-item').attr('data-id'),
            data = {
                action: 'goldsmith_wishlist_remove',
                product_id: product_id,
                beforeSend: function() {
                    $this.parent().append('<span class="loading-wrapper"><span class="ajax-loading"></span></span>').addClass('loading');
                }
            };

        $.post(wishlist_vars.ajax_url, data, function(response) {
            $( '.goldsmith-wishlist-item[data-id="'+product_id+'"]' ).remove();
            response  = JSON.parse( response );
            var count = response['count'];

            if ( response['status'] == 1 ) {
                $this.parent().removeClass('loading');
                $this.parent().find('.loading-wrapper').remove();
                $('body').trigger('goldsmith_lazy_load');
                if ( response['notice'] != null ) {
                    $('.goldsmith-wishlist-content-notice').html( response['notice'] );
                }
                if ( response['notice_type'] !== 'empty' ) {
                    setTimeout(function() {
                        $('.goldsmith-wishlist-content-notice .goldsmith-small-title').fadeOut( 'fast' );
                    }, 3000);
                    $('.wishlist-page-link').slideDown('slow');
                }
                if ( response['notice_type'] === 'empty' && wishlist_vars.wishlist_page == 'yes' ) {
                    var empty_html = '<div class="goldsmith-panel-content-notice goldsmith-empty-content">'+response['notice']+'</div>'
                    $('.wishlist-all-items').addClass('content-empty').html( empty_html );
                }

            } else {
                $('.goldsmith-wishlist-content-items .loading-wrapper').remove();
                if ( response['notice'] != null ) {
                    $('.goldsmith-wishlist-content-notice').html( response['notice'] );
                }
            }

            $('.goldsmith-wishlist-count').html(response['count']);
            $('[data-wishlist-count]').attr('data-wishlist-count', response['count']);
            if ( response['count'] == null || response['count'] == 0 ) {
                $('[data-wishlist-count]').removeClass('has-products');
            }
            wishlist_vars.count = response['count'];

            if ( response['count'] != null ) {
                $(document.body).trigger( 'goldsmith_wishlist_count', [count] );
            }

            $('.goldsmith-wishlist-btn[data-id="'+product_id+'"]').removeClass('added');

        });
        e.preventDefault();
    });

    $('.goldsmith-wishlist-count').html(wishlist_vars.count);
    $('[data-wishlist-count]').attr('data-wishlist-count', wishlist_vars.count);
    if ( wishlist_vars.count > 0 ) {
        $('[data-wishlist-count]').addClass('has-products');
    }
    $( document.body ).on( 'goldsmith_wishlist_count', function( event ) {
        $('.goldsmith-wishlist-count').html(wishlist_vars.count);
        $('[data-wishlist-count]').attr('data-wishlist-count', wishlist_vars.count);
    });

    if ( ( typeof wishlist_vars != 'undefined' ) && wishlist_vars.products ) {
        var ids = wishlist_vars.products;
        for (let i = 0; i < ids.length; i++) {
          $('.goldsmith-wishlist-btn[data-id="'+ids[i]+'"]').addClass('added');
        }
    }


    // copy link
    $(document).on('click touch', '#goldsmith-wishlist_copy_url, #goldsmith-wishlist_copy_btn', function(e) {
        e.preventDefault();
        copy_to_clipboard('#goldsmith-wishlist_copy_url');
    });

    function copy_to_clipboard(el) {
      // resolve the element
      el = (typeof el === 'string') ? document.querySelector(el) : el;

      // handle iOS as a special case
      if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
        // save current contentEditable/readOnly status
        var editable = el.contentEditable;
        var readOnly = el.readOnly;

        // convert to editable with readonly to stop iOS keyboard opening
        el.contentEditable = true;
        el.readOnly = true;

        // create a selectable range
        var range = document.createRange();
        range.selectNodeContents(el);

        // select the range
        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
        el.setSelectionRange(0, 999999);

        // restore contentEditable/readOnly to original state
        el.contentEditable = editable;
        el.readOnly = readOnly;
      } else {
        el.select();
      }

      // execute copy command
      document.execCommand('copy');

      // alert
      alert(goldsmith_vars.copied_text + ' ' + el.value);
    }


})(window, document, jQuery);
