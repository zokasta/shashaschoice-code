jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';

    if ( $(".goldsmith-product-stock-progressbar").length ) {
        var percent = $(".goldsmith-product-stock-progressbar").data('stock-percent');
        $(".goldsmith-product-stock-progressbar").css('width',percent);
    }

    $(document).on('ready', function(e){
        if (typeof goldsmith.Swatches !== 'undefined') {
            $('.products-wrapper .variations_form').each(function () {
                $(this).wc_variation_form();
            });
        }
    });

    // product tabs
    $('.goldsmith-product-tab-title-item').on('click', function() {
        var id = $(this).data('id');
        $('.goldsmith-product-tabs-wrapper div[data-id="'+id+'"]').addClass('active');
        $('.goldsmith-product-tabs-wrapper div:not([data-id="'+id+'"])').removeClass('active');
    });

    // product summary accordion tabs
    $('.cr-qna-link').on('click', function() {
        var name  = 'accordion';
        var offset  = 32;
        if ($('.goldsmith-product-tabs-wrapper').length) {
            name  = 'tabs';
            offset = 0;
        }
        var target = $('.goldsmith-product-'+name+'-wrapper').position();

        $('html,body').stop().animate({
            scrollTop: target.top + offset
        }, 1500);
        if ( $('[data-id="accordion-cr_qna"]').parent().hasClass('active') ) {
            return;
        } else {
            setTimeout(function(){
    			$('[data-id="accordion-cr_qna"]').trigger('click');
            }, 700);
        }
        if ( $('[data-id="tab-cr_qna"]').hasClass('active') ) {
            return;
        } else {
            setTimeout(function(){
                $('[data-id="tab-cr_qna"]').trigger('click');
            }, 700);
        }
    });

    $('.goldsmith-product-summary .woocommerce-review-link').on('click', function() {
        var target = $('.nt-woo-single #reviews').position();
        if ($('.goldsmith-product-tabs-wrapper').length) {
            target = $('.nt-woo-single .goldsmith-product-tabs-wrapper').position();
        }
        $('html,body').stop().animate({
            scrollTop: target.top
        }, 1500);

        if ( $('[data-id="tab-reviews"]').hasClass('active') ) {
            return;
        } else {
            setTimeout(function(){
                $('[data-id="tab-reviews"]').trigger('click');
            }, 700);
        }
    });

    // product summary accordion tabs
    $('.goldsmith-product-accordion-wrapper .goldsmith-accordion-item .goldsmith-accordion-header').on('click', function() {

        var accordionItem   = $(this),
            accordionParent = accordionItem.parent(),
            accordionHeight = accordionItem.outerHeight(),
            headerHeight    = $('body').hasClass('admin-bar') ? 32 : 0,
            totalHeight     = accordionHeight + headerHeight;

        accordionParent.toggleClass('active');
        accordionItem.next('.goldsmith-accordion-body').slideToggle();
        accordionParent.siblings().removeClass('active').find('.goldsmith-accordion-body').slideUp();
    });

    // product selected-variations-terms
    if ( $('.goldsmith-selected-variations-terms-wrapper').length > 0 ) {
        $('.variations_form').on('change', function() {
            var $this = $(this);
            var selectedterms = '';
            $this.find('.goldsmith-variations-items select').each(function(){
                var title = $(this).parents('.goldsmith-variations-items').find('.goldsmith-small-title').text();
                var val = $(this).val();
                if (val) {
                    selectedterms += '<span class="selected-features">'+title+': '+val+'</span>';
                }
            });
            if (selectedterms){
                $('.goldsmith-selected-variations-terms-wrapper').slideDown().find('.goldsmith-selected-variations-terms').html(selectedterms);
            }
        });
        $('.goldsmith-btn-reset.reset_variations').on('click', function() {
            $('.goldsmith-selected-variations-terms-wrapper').slideUp();
        });
    }

    if ( goldsmith_vars.product_ajax == 'yes' ) {
        if ( goldsmith_vars.elementorpro == 'yes' ) {
            // single page ajax add to cart
            $('.single-product form.cart .goldsmith-btn.single_add_to_cart_button').on('click', function(e) {
                var form = $(this).parents('form.cart');

                if ( $(form).parents('.product').hasClass('product-type-external') ) {
                    return;
                }

                e.preventDefault();

                var btn  = $(this),
                val  = form.find('[name=add-to-cart]').val(),
                data = new FormData(form[0]);

                btn.addClass('loading');

                data.append('add-to-cart', val );

                // Ajax action.
                $.ajax({
                    url         : goldsmith_vars.wc_ajax_url.toString().replace( '%%endpoint%%', 'goldsmith_ajax_add_to_cart' ),
                    data        : data,
                    type        : 'POST',
                    processData : false,
                    contentType : false,
                    dataType    : 'json',
                    success     : function( response ) {

                        btn.removeClass('loading');

                        var fragments = response.fragments;
                        var appended  = '<div class="woocommerce-notices-wrapper">'+fragments.notices+'</div>';

                        if ( fragments.notices.indexOf('woocommerce-error') > -1 ) {

                            btn.addClass('disabled');
                            $(appended).prependTo('.goldsmith-shop-popup-notices');

                        } else {

                            if ( $('.goldsmith-shop-popup-notices .woocommerce-notices-wrapper').length>0 ) {
                                $('.goldsmith-shop-popup-notices .woocommerce-notices-wrapper').remove();
                                $(appended).prependTo('.goldsmith-shop-popup-notices').delay(4000).fadeOut(300, function(){
                                    $(this).remove();
                                });
                            } else {
                                $(appended).prependTo('.goldsmith-shop-popup-notices').delay(4000).fadeOut(300, function(){
                                    $(this).remove();
                                });
                            }
                        }

                        // update other areas
                        $('.goldsmith-minicart').replaceWith(fragments.minicart);
                        $('.goldsmith-cart-count').html(fragments.count);
                        $('.goldsmith-cart-total').html(fragments.total);

                        if ( $('.goldsmith-cart-goal-text').length>0 ) {
                            $('.goldsmith-cart-goal-text').html(fragments.shipping.message);
                            $('.goldsmith-progress-bar').css('width',fragments.shipping.value+'%');
                            if ( fragments.shipping.value >= 100 ) {
                                $('.goldsmith-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                            }
                        }

                        if ( goldsmith_vars.minicart_open === 'yes' ) {
                            $('html,body').addClass('goldsmith-overlay-open');
                            $('.goldsmith-side-panel,.panel-content .cart-area').addClass('active');
                        }
                        // Redirect to cart option
                        if ( goldsmith_vars.cart_redirect === 'yes' ) {
                            window.location = goldsmith_vars.cart_url;
                            return;
                        }
                    },
                    error: function() {
                        btn.removeClass('loading');
                        console.log('cart-error');
                        $( document.body ).trigger( 'wc_fragments_ajax_error' );
                    }
                });
            });

        } else {

            // single page ajax add to cart
            $('body').on('submit', '.nt-woo-single form.cart', function(e) {

                if ( $(this).parents('.product').hasClass('product-type-external') || $(e.originalEvent.submitter).hasClass('goldsmith-btn-buynow') ) {
                    return;
                }

                e.preventDefault();

                var form = $(this),
                btn  = form.find('.goldsmith-btn.single_add_to_cart_button'),
                val  = form.find('[name=add-to-cart]').val(),
                data = new FormData(form[0]);

                btn.addClass('loading');

                data.append('add-to-cart', val );

                // Ajax action.
                $.ajax({
                    url         : goldsmith_vars.wc_ajax_url.toString().replace( '%%endpoint%%', 'goldsmith_ajax_add_to_cart' ),
                    data        : data,
                    type        : 'POST',
                    processData : false,
                    contentType : false,
                    dataType    : 'json',
                    success     : function( response ) {

                        btn.removeClass('loading');

                        var fragments = response.fragments;
                        var appended  = '<div class="woocommerce-notices-wrapper">'+fragments.notices+'</div>';

                        if ( fragments.notices.indexOf('woocommerce-error') > -1 ) {

                            btn.addClass('disabled');
                            $(appended).prependTo('.goldsmith-shop-popup-notices');

                        } else {

                            if ( $('.goldsmith-shop-popup-notices .woocommerce-notices-wrapper').length>0 ) {
                                $('.goldsmith-shop-popup-notices .woocommerce-notices-wrapper').remove();
                                $(appended).prependTo('.goldsmith-shop-popup-notices').delay(4000).fadeOut(300, function(){
                                    $(this).remove();
                                });
                            } else {
                                $(appended).prependTo('.goldsmith-shop-popup-notices').delay(4000).fadeOut(300, function(){
                                    $(this).remove();
                                });
                            }
                        }

                        // update other areas
                        $('.goldsmith-minicart').replaceWith(fragments.minicart);
                        $('.goldsmith-cart-count').html(fragments.count);
                        $('.goldsmith-cart-total').html(fragments.total);

                        if ( $('.goldsmith-cart-goal-text').length>0 ) {
                            $('.goldsmith-cart-goal-text').html(fragments.shipping.message);
                            $('.goldsmith-progress-bar').css('width',fragments.shipping.value+'%');
                            if ( fragments.shipping.value >= 100 ) {
                                $('.goldsmith-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                            }
                        }

                        if ( goldsmith_vars.minicart_open === 'yes' ) {
                            $('html,body').addClass('goldsmith-overlay-open');
                            $('.goldsmith-side-panel,.panel-content .cart-area').addClass('active');
                        }
                        // Redirect to cart option
                        if ( goldsmith_vars.cart_redirect === 'yes' ) {
                            window.location = goldsmith_vars.cart_url;
                            return;
                        }
                    },
                    error: function() {
                        btn.removeClass('loading');
                        console.log('cart-error');
                        $( document.body ).trigger( 'wc_fragments_ajax_error' );
                    }
                });
            });
        }
    } else {
        $('body').on('submit', '.nt-woo-single form.cart', function(e) {

            if ( $(this).parents('.product').hasClass('product-type-external') || $(e.originalEvent.submitter).hasClass('goldsmith-btn-buynow') ) {
                return;
            }
            var form = $(this),
                btn  = form.find('.goldsmith-btn.single_add_to_cart_button');

            btn.addClass('loading');
        });
    }

    /***** buynow start *****/

    $('body').on('click', '.nt-woo-single .goldsmith-btn-buynow', function() {
        if ($(this).parents('form.cart').length) {
            return;
        }
        $(this).parents('form.cart').find('.goldsmith-btn-buynow').trigger('click');
    });

    // Product Fake View

    var viewingItem = $('.goldsmith-product-view'),
        data        = viewingItem.data('product-view'),
        countView   = viewingItem.find('.goldsmith-view-count'),
        current     = 0,
        change_counter;

    singleProductFakeView();
    function singleProductFakeView() {

        if ( viewingItem.length ) {
            var min    = data.min,
                max    = data.max,
                delay  = data.delay,
                change = data.change,
                id     = data.id;

            if ( !viewingItem.hasClass( 'inited' ) ) {
                if ( typeof change !== 'undefined' && change ) {
                    clearInterval( change );
                }

                current = $.cookie( 'goldsmith_cpv_' + id );

                if ( typeof current === 'undefined' || !current ) {
                    current = Math.floor(Math.random() * max) + min;
                }

                viewingItem.addClass('inited');

                $.cookie('goldsmith_cpv_' + id, current, { expires: 1 / 24, path: '/'} );

                countView.html( current );

            }

            change_counter = setInterval( function() {
                current    = parseInt( countView.text() );

                if ( !current ) {
                    current = min;
                }

                var pm = Math.floor( Math.random() * 2 );
                var others = Math.floor( Math.random() * change + 1 );
                current = ( pm < 1 && current > others ) ? current - others : current + others;
                $.cookie('goldsmith_cpv_' + id, current, { expires: 1 / 24, path: '/'} );

                countView.html( current );

            }, delay);
        }
    }

    $(document.body).on('show_variation','.goldsmith-product-summary form.variations_form', function( event, data ){
        $('.goldsmith-product-summary .goldsmith-btn-reset-wrapper').addClass('active');
    });

    $(document.body).on('hide_variation','.goldsmith-product-summary form.variations_form', function( event, data ){
        $('.goldsmith-product-summary .goldsmith-btn-reset-wrapper').removeClass('active');
    });

    goldsmithProductGalleryInit();

    function goldsmithProductGalleryInit() {
        if ( $('.goldsmith-product-gallery-main-slider').length ) {
            var thumbsDirection = 'horizontal';
            if ( $('.goldsmith-swiper-slider-wrapper').hasClass('thumbs-right') || $('.goldsmith-swiper-slider-wrapper').hasClass('thumbs-left') ) {
                var thumbsDirection = 'vertical';
            }
            $('.goldsmith-product-gallery-main-slider .swiper-slide').each(function(i,e){
                var thumbUrl = $(this).data('thumb') ? $(this).data('thumb') : $(this).data('src');
                var active   = i == 0 ? ' swiper-slide-thumb-active' : '';
                var videoH   = $(this).hasClass('iframe-video') ? ' style="height:'+Math.round($('.goldsmith-product-thumbnails .swiper-slide:first-child img').height())+'px"' : '';
                var tumbImg = $(this).hasClass('iframe-video') ? '<div class="goldsmith-slide-video-item-icon"'+videoH+'><i class="nt-icon-button-play-2"></i></div>' : '<img src="'+thumbUrl+'">';
                $('<div class="swiper-slide'+active+'">'+tumbImg+'</div>').appendTo($('.goldsmith-product-thumbnails .goldsmith-swiper-wrapper'));
            });
            var galleryThumbs  = new NTSwiper( '.goldsmith-product-thumbnails', {
                spaceBetween         : 10,
                slidesPerView        : 5,
                direction            : "horizontal",
                wrapperClass         : "goldsmith-swiper-wrapper",
                watchOverflow        : true,
                watchSlidesProgress  : true,
                watchSlidesVisibility: true,
                rewind               : true,
                resizeObserver       : true,
                grabCursor           : true,
                breakpoints          : {
                    320 : {
                        slidesPerView : 5,
                        direction     : "horizontal"
                    },
                    576 : {
                        slidesPerView : 8,
                        direction     : "horizontal",
                    },
                    768 : {
                        slidesPerView : thumbsDirection == 'vertical' ? 'auto' : 8,
                        direction     : thumbsDirection,
                    }
                },
                on                   : {
                    init : function ( swiper ) {
                        var heightFirstImage = $('.goldsmith-product-thumbnails .swiper-slide:nth-child(2) img').height();
                        $('.goldsmith-slide-video-item-icon').css('height', heightFirstImage);
                        swiper.update();
                    },
                    resize : function ( swiper ) {
                        var heightFirstImage = $('.goldsmith-product-thumbnails .swiper-slide:nth-child(2) img').height();
                        $('.goldsmith-slide-video-item-icon').css('height', heightFirstImage);
                        swiper.update();
                    }
                }
            });

            var galleryMain = new NTSwiper( '.goldsmith-product-gallery-main-slider', {
                speed                 : 800,
                spaceBetween          : 0,
                slidesPerView         : 1,
                direction             : "horizontal",
                wrapperClass          : "goldsmith-swiper-wrapper",
                watchSlidesVisibility : true,
                watchSlidesProgress   : true,
                rewind                : true,
                resizeObserver        : true,
                grabCursor            : true,
                autoHeight            : true,
                navigation            : {
                    nextEl : ".goldsmith-product-gallery-main-slider .goldsmith-swiper-next",
                    prevEl : ".goldsmith-product-gallery-main-slider .goldsmith-swiper-prev"
                },
                thumbs                : {
                    swiper: galleryThumbs
                },
                on                    : {
                    init : function ( swiper ) {
                        var heightVertical = $('.goldsmith-product-gallery-main-slider').height();
                        $('.goldsmith-product-thumbnails').css('max-height', heightVertical );
                    },
                    resize : function ( swiper ) {
                        var heightVertical = $('.goldsmith-product-gallery-main-slider').height();
                        $('.goldsmith-product-thumbnails').css('max-height', heightVertical );
                        swiper.update();
                    },
                    transitionEnd : function ( swiper ) {
                        var  active = swiper.realIndex;

                        $( '.goldsmith-product-gallery-main-slider .swiper-slide:not(.swiper-slide-active)' ).each(function () {
                            var iframe = $( this ).find('iframe');
                            if ( iframe.size() ) {
                                iframe[0].contentWindow.postMessage('{"event":"command","func":"' + 'pauseVideo' + '","args":""}', '*');
                            }
                        });

                        $( '.goldsmith-product-gallery-main-slider .swiper-slide.swiper-slide-active' ).each(function () {
                            var iframe2 = $( this ).find('iframe');
                            if ( iframe2.size() ) {
                                iframe2[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
                            }
                        });
                    },
                    afterInit: function(swiper){
                        var iframesrc = $('.goldsmith-product-gallery-main-slider .iframe-video iframe').data('src');
                        $( '.goldsmith-product-gallery-main-slider .iframe-video iframe' ).attr('src', iframesrc);
                    }
                }
            });

            var $gallery      = $('.goldsmith-product-gallery-main-slider'),
                $mainImg      = $gallery.find('.goldsmith-swiper-slide-first'),
                $oMainImg     = $mainImg.find('img'),
                $oZoomImg     = $mainImg.find('img.zoomImg'),
                $oZoomSrc     = $oMainImg.attr('src'),
                $popupSrc     = $mainImg.attr('data-src'),
                $oThumbImg    = $('.goldsmith-product-thumbnails .swiper-slide:first-child img'),
                $hasThumbs    = $mainImg.attr('data-thumb') ? true : false,
                $oThumbSrc    = $hasThumbs ? $mainImg.attr('data-thumb') : $popupSrc,
                $mainSkuHtml  = $('.goldsmith-sku-wrapper .sku'),
                $mainSku      = $mainSkuHtml.html(),
                $mainPrice    = $('.goldsmith-product-summary .goldsmith-price-wrapper'),
                $mainPriceHml = $mainPrice.html(),
                $mainStock    = $('.goldsmith-product-summary .goldsmith-price>.goldsmith-stock-status'),
                $mainStockHml = $mainStock.html();

                $('.goldsmith-product-summary form.variations_form').on('show_variation', function( event, data ){
                    $('.goldsmith-product-summary .goldsmith-product-info').addClass('found');

                    if ( data.sku ) {
                        $mainSkuHtml.html(data.sku);
                    }

                    var $price = $(event.target).closest('.goldsmith-product-summary').find('.goldsmith-summary-item.price .goldsmith-price-wrapper');
                    // product price
                    if ( $price.length && data.price_html != '' ) {
                        var price = $(data.price_html).html();
                        $price.html( price );
                    }

                    var $stock = $(event.target).closest('.goldsmith-product-summary').find('.goldsmith-price>.goldsmith-stock-status');
                    // product stock
                    if ( $stock.length && data.availability_html != '' ) {
                        var stcokhtml = $(data.availability_html);
                        $stock.replaceWith( stcokhtml );
                    }

                    var fullsrc = data.image.full_src;
                    var src     = data.image.src;
                    var tsrc    = data.image.gallery_thumbnail_src;

                    $mainImg.attr('data-src',fullsrc);
                    $oMainImg.attr('src',src);
                    $oZoomImg.attr('src',fullsrc);

                    if ( $hasThumbs ) {
                        $oThumbImg.attr('src',tsrc);
                    } else {
                        $oThumbImg.attr('src',fullsrc);
                    }

                    setTimeout( function() {
                        if ( !$oMainImg.hasClass('active') ) {
                            galleryMain.slideTo(0);
                            galleryThumbs.slideTo(0);
                        }
                        galleryMain.update();
                        galleryMain.updateAutoHeight(10);
                        galleryThumbs.update();
                        initZoom('reinit',fullsrc);
                    }, 100 );
                });

                $('.goldsmith-product-summary form.variations_form').on('hide_variation', function( event, data ){
                    $('.goldsmith-product-summary .goldsmith-product-info').removeClass('found');
                    $mainSkuHtml.html($mainSku);
                    $mainPrice.html($mainPriceHml);
                    var $stock = $(event.target).closest('.goldsmith-product-summary').find('.goldsmith-price>.goldsmith-stock-status');
                    $stock.replaceWith($mainStock);

                    $mainImg.attr('data-src',$oZoomSrc);
                    $oMainImg.attr('src',$oZoomSrc);
                    $oZoomImg.attr('src',$oZoomSrc);
                    if ( $hasThumbs ) {
                        $oThumbImg.attr('src',$oThumbSrc);
                    } else {
                        $oThumbImg.attr('src',$oZoomSrc);
                    }
                    setTimeout( function() {
                        if ( !$oMainImg.hasClass('active') ) {
                            galleryMain.slideTo(0);
                            galleryThumbs.slideTo(0);
                        }
                        galleryMain.update();
                        galleryMain.updateAutoHeight(10);
                        galleryThumbs.update();
                        initZoom('reinit',$oZoomSrc);
                    }, 100 );
                });

            initZoom('load');

            /**
            * Init zoom.
            */
            function initZoom($action,$url) {
                if ( 'function' !== typeof $.fn.zoom && !wc_single_product_params.zoom_enabled ) {
                    return false;
                }

                var galleryWidth = $('.goldsmith-product-gallery-main-slider .swiper-slide').width(),
                    zoomEnabled  = false,
                    zoom_options = {
                        touch: false
                    };

                if ( 'ontouchstart' in document.documentElement ) {
                    zoom_options.on = 'click';
                }

                $('.goldsmith-product-gallery-main-slider .swiper-slide img').each( function( index, target ) {
                    var image = $( target );
                    var imageIndex = image.parents('.swiper-slide');

                    if ( image.attr( 'width' ) > galleryWidth ) {
                        if ( $action == 'load' ) {
                            zoom_options.url = image.parent().data('zoom-img');
                            image.wrap('<span class="goldsmith-zoom-wrapper" style="display:block"></span>')
                              .css('display', 'block')
                              .parent()
                              .zoom(zoom_options);
                        } else {
                            image.trigger('zoom.destroy').unwrap();
                            zoom_options.url = imageIndex.hasClass('goldsmith-swiper-slide-first') ? $url : image.parent().data('zoom-img');
                            image.wrap('<span class="goldsmith-zoom-wrapper" style="display:block"></span>')
                              .css('display', 'block')
                              .parent()
                              .zoom(zoom_options);
                        }
                    }
                });
            }
        }
    }


    /**
    * singleGalleryGridVariations
    */
    singleGalleryGridVariations();
    function singleGalleryGridVariations() {
        if ( !$('.goldsmith-product-main-gallery-grid .goldsmith-gallery-grid-item-first img').length ) {
            return;
        }
        var $oMainImg       = $('.goldsmith-product-main-gallery-grid .goldsmith-gallery-grid-item-first img'),
            $oMainSrc       = $oMainImg.data('src'),
            $oMainSrcSet    = $oMainImg.data('srcset'),
            $oMainSrcSizes  = $oMainImg.data('sizes'),
            gallery         = $('.goldsmith-product-main-gallery-grid');

        $( document ).on('change','.goldsmith-product-summary .variations_form select', function( e ) {
            var $this      = $(this),
                $form      = $this.parents('.variations_form'),
                variations = $form.data('product_variations');

            setTimeout( function() {
                var current_id = $form.attr('current-image'),
                    image,
                    src,
                    srcset,
                    sizes;

                $.map(variations, function(elementOfArray, indexInArray) {
                    if ( elementOfArray.image_id == current_id ) {
                        image  = elementOfArray.image;
                        src    = image.src;
                        srcset = image.srcset;
                        sizes  = image.sizes;
                    }
                });
                if ( current_id ) {
                    $oMainImg.attr('src',src);
                    $oMainImg.attr('data-src',src);
                    if ( srcset ) {
                        $oMainImg.attr('srcset',srcset);
                    }
                    if ( sizes ) {
                        $oMainImg.attr('sizes',sizes);
                    }
                }
            }, 50 );
        });

        $( document ).on('click','.goldsmith-product-summary .reset_variations', function( e ) {
            //e.preventDefault();
            var $form     = $(this).parents('.variations_form'),
                gallery   = $('.goldsmith-product-main-gallery-grid');

            $oMainImg.attr('src',$oMainSrc);
            $oMainImg.attr('data-src',$oMainSrc);

            if ( $oMainSrcSet ) {
                $oMainImg.attr('srcset',$oMainSrcSet);
            }
            if ( $oMainSrcSizes ) {
                $oMainImg.attr('sizes',$oMainSrcSizes);
            }
        });
    }

    stylerProductPopup();
    function stylerProductPopup() {
        if ( $('[data-fancybox="gallery"]').length > 0 ) {
            Fancybox.bind('[data-fancybox="gallery"]', {
                showClass : 'fancybox-zoomInUp',
                hideClass : 'fancybox-zoomOutDown',
                animated  : false,
                groupAll  : true
            });
        }
    }

});
