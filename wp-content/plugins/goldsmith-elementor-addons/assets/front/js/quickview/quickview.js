'use strict';

var goldsmith_ids = [],
    goldsmith_products = [];
(function($) {

    jQuery(document).ready(function($) {
        $('.goldsmith-quickview-btn').each(function() {
            var id = $(this).data('id');
            if (-1 === $.inArray(id, goldsmith_ids)) {
                goldsmith_ids.push(id);
                goldsmith_products.push({src: goldsmith_vars.ajax_url + '?product_id=' + id});
            }
        });
    });

    function goldsmith_get_key(array, key, value) {
      for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
          return i;
        }
      }
      return -1;
    }

    jQuery(document).on('goldsmithShopInit',function() {
        $('.goldsmith-quickview-btn').each(function() {
            var id = $(this).data('id');
            if (-1 === $.inArray(id, goldsmith_ids)) {
                goldsmith_ids.push(id);
                goldsmith_products.push({src: goldsmith_vars.ajax_url + '?product_id=' + id});
            }
        });
        init(goldsmith_products);
    });

    jQuery(document).on('goldsmith_quick_init',function() {
        $('.goldsmith-quickview-btn').each(function() {
            var id = $(this).data('id');
            if (-1 === $.inArray(id, goldsmith_ids)) {
                goldsmith_ids.push(id);
                goldsmith_products.push({src: goldsmith_vars.ajax_url + '?product_id=' + id});
            }
        });
        init(goldsmith_products);
    });

    init(goldsmith_products);

    function init(goldsmith_products){

        $(document).on('click touch', '.goldsmith-quickview-btn', function(event) {
            event.preventDefault();

            var $this        = $(this),
                id           = $this.data('id'),
				clicked      = false,
                is_quickShop = $this.parents('.goldsmith-loop-product').find('.goldsmith-quick-shop-btn');


            var index = goldsmith_get_key(goldsmith_products, 'src', goldsmith_vars.ajax_url + '?product_id=' + id);

            $.magnificPopup.open({
                items           : goldsmith_products,
                type            : 'ajax',
                mainClass       : 'mfp-goldsmith-quickview goldsmith-mfp-slide-bottom',
                removalDelay    : 160,
                overflowY       : 'scroll',
                fixedContentPos : true,
                closeBtnInside  :true,
                tClose          : '',
                closeMarkup     : '<div class="mfp-close goldsmith-panel-close-button"></div>',
                tLoading        : '<span class="loading-wrapper"><span class="ajax-loading"></span></span>',
                gallery         : {
                    tPrev: '',
                    tNext: '',
                    enabled: true
                },
                ajax: {
                    settings: {
                        type: 'GET',
                        data: {
                            action: 'goldsmith_quickview'
                        }
                    }
                },
                callbacks: {
                    beforeOpen: function() {},
                    open: function() {
                        $('.mfp-preloader').addClass('loading');
                    },
                    ajaxContentAdded: function() {
                        $('.mfp-preloader').removeClass('loading');

                        var variations_form = $('.goldsmith-quickview-wrapper').find('form.variations_form');
                        var termsWrapper    = $('.goldsmith-quickview-wrapper').find('.goldsmith-selected-variations-terms-wrapper');

                        variations_form.wc_variation_form();

                        $('.goldsmith-quickshop-form-wrapper .goldsmith-variations .goldsmith-small-title').sameSize(true);

                        $(variations_form).on('show_variation', function( event, data ){
                            $('.goldsmith-quickview-wrapper').find('.goldsmith-btn-reset-wrapper,.single_variation_wrap').addClass('active');
                            //$('.goldsmith-quickview-wrapper').find('.goldsmith-selected-variations-terms-wrapper').addClass('active').slideDown();;
                        });

                        $(variations_form).on('hide_variation', function(){
                            $('.goldsmith-quickview-wrapper').find('.goldsmith-btn-reset-wrapper,.single_variation_wrap').removeClass('active');
                            //$('.goldsmith-quickview-wrapper .goldsmith-selected-variations-terms').html('');
                            //$('.goldsmith-quickview-wrapper').find('.goldsmith-selected-variations-terms-wrapper').removeClass('active').slideUp();
                        });

                        if ( $('.goldsmith-quickview-wrapper .grouped_form').length>0 || $(variations_form).length>0 ) {
                            $(document.body).trigger('goldsmith_on_qtybtn');
                        }

                        if ( termsWrapper.length > 0 ) {
                            $(variations_form).on('change', function() {
                                var $this = $(this);
                                var selectedterms = '';
                                $this.find('.goldsmith-variations-items select').each(function(){
                                    var title = $(this).parents('.goldsmith-variations-items').find('.goldsmith-small-title').text();
                                    var val   = $(this).val();
                                    if (val) {
                                        selectedterms += '<span class="selected-features"><span class="selected-label">'+title+': </span><span class="selected-value">'+val+'</span></span>';
                                    }
                                });
                                if (selectedterms){
                                    termsWrapper.slideDown().find('.goldsmith-selected-variations-terms').html(selectedterms);
                                } else {
                                    termsWrapper.slideUp();
                                }
                            });
                        }

                        $('.goldsmith-variations .goldsmith-small-title').sameSize(true);

                        $('.goldsmith-quickview-wrapper form.cart').submit(function(e) {

                            if ( $(e.originalEvent.submitter).hasClass('goldsmith-btn-buynow') ) {
                                return;
                            }

                            e.preventDefault();

                            var form = $(this),
                                btn  = form.find('.goldsmith-btn.single_add_to_cart_button'),
                                data = new FormData(form[0]),
                                val  = form.find('[name=add-to-cart]').val();

                            data.append('add-to-cart',val);

                            btn.addClass('loading');

                            $.ajax({
                                url         : goldsmith_vars.wc_ajax_url.toString().replace( '%%endpoint%%', 'goldsmith_ajax_add_to_cart' ),
                                data        : data,
                                type        : 'POST',
                                processData : false,
                                contentType : false,
                                dataType    : 'json',
                                success     : function( response ) {

                                    btn.removeClass('loading');

                                    if ( ! response ) {
                                        return;
                                    }

                                    var fragments = response.fragments;

                                    var appended  = '<div class="woocommerce-notices-wrapper goldsmith-summary-item">'+fragments.notices+'</div>';

                                    $(appended).appendTo('.goldsmith-quickview-product-details').delay(5000).fadeOut(300, function(){
                                        $(this).remove();
                                    });

                                    // update other areas
                                    $('.minicart-panel').replaceWith(fragments.minicart);
                                    $('.goldsmith-cart-count').html(fragments.count);
                                    $('.goldsmith-cart-total').html(fragments.total);

                                    if ( $('.goldsmith-cart-goal-text').length>0 ) {
                                        $('.goldsmith-cart-goal-text').html(fragments.shipping.message);
                                        $('.goldsmith-progress-bar').css('width',fragments.shipping.value+'%');
                                        if ( fragments.shipping.value >= 100 ) {
                                            $('.goldsmith-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                                        } else {
                                            $('.goldsmith-cart-goal-wrapper').removeClass('free-shipping-success shakeY');
                                        }
                                    }

                                    $('.goldsmith-quickview-wrapper .close-error').on('click touch', function(e) {
                                        $(this).parent().remove();
                                    });

                                    $('.goldsmith-quickview-wrapper .goldsmith-btn-reset,.goldsmith-quickview-wrapper .plus,.goldsmith-quickview-wrapper .minus').on('click touch', function(event) {
                                        $('.goldsmith-quickview-notices').slideUp();
                                    });

                                    if ( response.error && response.product_url ) {
                                        window.location = response.product_url;
                                        return;
                                    }
                                }
                            });
                        });

                        $('body').on('click', '.goldsmith-btn-buynow', function() {
                            if ($(this).parents('form.cart').length) {
                                return;
                            }
                            $('form.cart').find('.goldsmith-btn-buynow').trigger('click');
                        });

                        if ( $('.goldsmith-quickview-main img').length > 1) {

                            $('.goldsmith-quickview-main .swiper-slide img').each( function(){
                                var src = $(this).attr('src');
                                $('<div class="swiper-slide"><img src="'+src+'"/></div>').appendTo('.goldsmith-quickview-thumbnails .goldsmith-swiper-wrapper');
                            });

                            var galleryThumbs = new NTSwiper('.goldsmith-quickview-thumbnails', {
                                loop                  : false,
                                speed                 : 1000,
                                spaceBetween          : 10,
                                slidesPerView         : 4,
                                autoHeight            : false,
                                watchSlidesVisibility : true,
                                wrapperClass          : "goldsmith-swiper-wrapper",
                                grabCursor            : true,
                                navigation            : {
                                    nextEl: '.goldsmith-quickview-main .goldsmith-swiper-next',
                                    prevEl: '.goldsmith-quickview-main .goldsmith-swiper-prev'
                                }
                            });
                            var galleryTop = new NTSwiper('.goldsmith-quickview-main', {
                                loop         : false,
                                speed        : 1000,
                                slidesPerView: 1,
                                spaceBetween : 0,
                                observer     : true,
                                rewind       : true,
                                wrapperClass : "goldsmith-swiper-wrapper",
                                grabCursor   : true,
                                navigation   : {
                                    nextEl: '.goldsmith-quickview-main .goldsmith-swiper-next',
                                    prevEl: '.goldsmith-quickview-main .goldsmith-swiper-prev'
                                },
                                thumbs       : {
                                    swiper: galleryThumbs
                                }
                            });
                        }
                    },
                    close: function(){},
                    afterClose: function(){
                        $('html,body').removeClass('popup-open');
                    }
                }
            },index);
        });
    }
})(jQuery);
