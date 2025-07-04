jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';

    // quick shop start
    goldsmithQuickShopPopup();

    $(document).on('goldsmithShopInit', function() {
        goldsmithQuickShopPopup();
    });

    $(document.body).on('trigger_quick_shop', function(e,btn) {
        $(btn).trigger('click');
    });

    function goldsmithQuickShopPopup(){

       $( document.body ).on('click', '.goldsmith-quick-shop-btn', function(event) {
            event.preventDefault();

            var $this = $(this),
                id    = $this.data('product_id');

            $.magnificPopup.open({
                items           : {
                    src : goldsmith_vars.ajax_url + '?product_id=' + id
                },
                mainClass       : 'mfp-goldsmith-quickshop goldsmith-mfp-slide-bottom',
                removalDelay    : 160,
                overflowY       : 'scroll',
                fixedContentPos : false,
                closeBtnInside  : true,
                tClose          : '',
                closeMarkup     : '<div class="mfp-close goldsmith-panel-close-button"></div>',
                tLoading        : '<span class="loading-wrapper"><span class="ajax-loading"></span></span>',
                type            : 'ajax',
                ajax            : {
                    settings : {
                        type : 'GET',
                        data : {
                            action : 'goldsmith_ajax_quick_shop'
                        }
                    }
                },
                callbacks       : {
                    beforeOpen  : function() {},
                    open        : function() {
                        $('.mfp-preloader').addClass('loading');
                    },
                    ajaxContentAdded: function() {

                        $('.mfp-preloader').removeClass('loading');

                        var variations_form = $('.goldsmith-quickshop-form-wrapper').find('form.cart');
                        var termsWrapper    = $('.goldsmith-quickshop-form-wrapper').find('.goldsmith-selected-variations-terms-wrapper');

                        variations_form.wc_variation_form();

                        $('.goldsmith-quickshop-form-wrapper .goldsmith-variations .goldsmith-small-title').sameSize(true);

                        $(variations_form).on('show_variation', function( event, data ){
                            $('.goldsmith-quickshop-form-wrapper').find('.goldsmith-btn-reset-wrapper,.single_variation_wrap').addClass('active');
                        });

                        $(variations_form).on('hide_variation', function(){
                            $('.goldsmith-quickshop-form-wrapper').find('.goldsmith-btn-reset-wrapper,.single_variation_wrap').removeClass('active');
                        });

                        if ( $('.grouped_form').length>0 || $(variations_form).length>0 ) {
                            $(document.body).trigger('goldsmith_on_qtybtn');
                        }

                        if ( $('.goldsmith-selected-variations-terms-wrapper').length > 0 ) {
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

                        $('.goldsmith-variations-items>.goldsmith-small-title').sameSize(true);

                        $('.goldsmith-quickshop-form-wrapper form.cart').submit(function(e) {

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

                                    $('.goldsmith-quickshop-notices-wrapper').html(fragments.notices).slideDown();

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

                                    if ( response.error && response.product_url ) {
                                        window.location = response.product_url;
                                        return;
                                    }

                                    $('.goldsmith-quickshop-notices-wrapper .close-error').on('click touch', function(e) {
                                        $('.goldsmith-quickshop-notices').slideUp();
                                    });

                                    $('.goldsmith-quickshop-wrapper .goldsmith-btn-reset,.goldsmith-quickshop-wrapper .plus,.goldsmith-quickshop-wrapper .minus').on('click touch', function(event) {
                                        $('.goldsmith-quickshop-notices-wrapper').slideUp();
                                    });

                                    $('.goldsmith-quickshop-buttons-wrapper').slideDown().addClass('active');

                                    $('.goldsmith-quickshop-buttons-wrapper .goldsmith-btn').on('click touch', function(e) {
                                        if ( $(this).hasClass('open-cart-panel') ) {
                                            $('html,body').addClass('goldsmith-overlay-open');
                                            $('.goldsmith-side-panel .active').removeClass('active');
                                            $('.goldsmith-side-panel').addClass('active');
                                            $('.cart-area').addClass('active');
                                        }
                                        $.magnificPopup.close();
                                    });
                                }
                            });
                        });

                        $('body').on('click', '.goldsmith-btn-buynow', function() {
                            if ($(this).parents('form.cart').length) {
                                return;
                            }
                            $('form.cart').find('.goldsmith-btn-buynow').trigger('click');
                        });
                    },
                    beforeClose : function() {},
                    close : function() {},
                    afterClose : function() {}
                }
            });
        });
    }
    // quick shop end
});
