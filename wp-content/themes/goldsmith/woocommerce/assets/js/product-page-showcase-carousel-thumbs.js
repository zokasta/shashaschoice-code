jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';
    /**
    * Init zoom.
    */
    function initZoom($action,$url) {
        if ( 'function' !== typeof $.fn.zoom && !wc_single_product_params.zoom_enabled ) {
            return false;
        }

        var galleryWidth = $('.goldsmith-product-showcase-main .swiper-slide').width(),
            zoomEnabled  = false,
            zoom_options = {
                touch: false
            };

        if ( 'ontouchstart' in document.documentElement ) {
            zoom_options.on = 'click';
        }

        $('.goldsmith-product-showcase-main .swiper-slide img').each( function( index, target ) {
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

    var galleryThumbs  = new NTSwiper( '.goldsmith-product-showcase-thumbnails', {
        spaceBetween        : 10,
        slidesPerView       : 5,
        direction           : "horizontal",
        wrapperClass        : "goldsmith-swiper-wrapper",
        grabCursor          : true,
        watchSlidesProgress : true,
        breakpoints         : {
            992 : {
                slidesPerView :  'auto'
            }
        },
        on                  : {
            resize : function ( swiper ) {
                var heightFirstImage = $('.goldsmith-product-showcase-thumbnails .goldsmith-swiper-slide-first').height();
                $('.goldsmith-slide-video-item-icon').css('height', heightFirstImage);
                swiper.update();
            },
            afterInit: function(swiper){
                var heightFirstImage = $('.goldsmith-product-showcase-thumbnails .goldsmith-swiper-slide-first').height();
                $('.goldsmith-slide-video-item-icon').css('height', heightFirstImage);
            }
        }
    });

    var galleryMain = new NTSwiper( '.goldsmith-product-showcase-main', {
        speed                 : 800,
        spaceBetween          : 0,
        slidesPerView         : '1',
        direction             : "horizontal",
        effect                : "slide",
        wrapperClass          : "goldsmith-swiper-wrapper",
        slideActiveClass      : "active",
        loop                  : true,
        centeredSlides        : true,
        slideToClickedSlide   : true,
        grabCursor            : true,
        autoHeight            : false,
        autoPlay              : false,
        rewind                : false,
        observer              : true,
        observeParents        : true,
        observeSlideChildren  : true,
        watchOverflow         : true,
        watchSlidesVisibility : true,
        watchSlidesProgress   : true,
        breakpoints         : {
            992 : {
                slidesPerView : 3
            }
        },
        navigation            : {
            nextEl : ".goldsmith-product-showcase-main .goldsmith-swiper-next",
            prevEl : ".goldsmith-product-showcase-main .goldsmith-swiper-prev"
        },
        thumbs                : {
            swiper: galleryThumbs
        },
        on                    : {
            transitionEnd : function ( swiper ) {
                var  active = swiper.realIndex;

                $( '.goldsmith-product-showcase-main .swiper-slide:not(.active)' ).each(function () {
                    var iframe = $( this ).find('iframe');
                    if ( iframe.size() ) {
                        iframe[0].contentWindow.postMessage('{"event":"command","func":"' + 'pauseVideo' + '","args":""}', '*');
                    }
                });

                $( '.goldsmith-product-showcase-main .swiper-slide.active' ).each(function () {
                    var iframe2 = $( this ).find('iframe');
                    if ( iframe2.size() ) {
                        iframe2[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
                    }
                });
            },
            afterInit: function(swiper){
                var iframesrc = $('.goldsmith-product-showcase-main .iframe-video iframe').data('src');
                $( '.goldsmith-product-showcase-main .iframe-video iframe' ).attr('src', iframesrc);
            }
        }
    });

    var $oMainImg       = $('.goldsmith-product-showcase-main .goldsmith-swiper-slide-first img'),
        $oMainSrc       = $oMainImg.data('src'),
        $oZoomImg       = $oMainImg.find('img.zoomImg'),
        $ThumbFirst     = $('.goldsmith-product-showcase-thumbnails .goldsmith-swiper-slide-first'),
        $oThumbImg      = $('.goldsmith-product-showcase-thumbnails .goldsmith-swiper-slide-first img'),
        $oThumbSrc      = $oThumbImg.data('src'),
        $mainSkuHtml    = $('.goldsmith-sku-wrapper .sku'),
        $mainSku        = $mainSkuHtml.html(),
        $mainPrice      = $('.goldsmith-product-summary .goldsmith-price-wrapper'),
        $mainPriceHml   = $mainPrice.html(),
        $mainStock      = $('.goldsmith-product-summary .goldsmith-price>.goldsmith-stock-status'),
        $mainStockHml   = $mainStock.html();

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

            $oMainImg.attr('data-src',fullsrc);
            $oMainImg.attr('src',src);
            $oZoomImg.attr('src',fullsrc);
            $oThumbImg.attr('src',tsrc);

            setTimeout( function() {
                $($ThumbFirst).trigger('click');
                galleryMain.update();
                galleryMain.updateAutoHeight(10);
                initZoom('reinit',fullsrc);
            }, 100 );
        });


    $( document ).on('click','.goldsmith-product-summary .reset_variations', function( event, data ) {

        $('.goldsmith-product-summary .goldsmith-product-info').removeClass('found');
        $mainSkuHtml.html($mainSku);
        $mainPrice.html($mainPriceHml);
        var $stock = $(event.target).closest('.goldsmith-product-summary').find('.goldsmith-price>.goldsmith-stock-status');
        $stock.replaceWith($mainStock);

        $oMainImg.attr('src',$oMainSrc);
        $oMainImg.attr('data-src',$oMainSrc);
        $oZoomImg.attr('src',$oMainSrc);
        $oThumbImg.attr('src',$oThumbSrc);

        setTimeout( function() {
            if ( !$oMainImg.hasClass('swiper-slide-active') ) {
                galleryMain.slideTo(0);
                galleryThumbs.slideTo(0);
            }
            galleryMain.update();
            galleryMain.updateAutoHeight(10);
            galleryThumbs.update();
            initZoom('reinit',$oMainSrc);
        }, 100 );
    });

    initZoom('load');

});
