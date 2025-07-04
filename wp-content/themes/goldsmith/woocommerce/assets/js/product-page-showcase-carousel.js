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

    var options = $('.goldsmith-product-showcase-main').data('swiper-options');
    options['on'] = {
        'afterInit' : function( swiper ) {
            console.log('hello');
            var iframesrc = $('.goldsmith-product-showcase-main .iframe-video iframe').data('src');
            $( '.goldsmith-product-showcase-main .iframe-video iframe' ).attr('src', iframesrc);
        },
        'transitionEnd': function( swiper ) {
            var active = swiper.realIndex;
            $( '.goldsmith-product-showcase-main .swiper-slide:not(.swiper-slide-active)' ).each(function () {
                var iframe = $( this ).find('iframe');
                if ( iframe.size() ) {
                    iframe[0].contentWindow.postMessage('{"event":"command","func":"' + 'pauseVideo' + '","args":""}', '*');
                }
            });

            $( '.goldsmith-product-showcase-main .swiper-slide.swiper-slide-active' ).each(function () {
                var iframe2 = $( this ).find('iframe');
                if ( iframe2.size() ) {
                    iframe2[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
                }
            });
        }
    };

    const galleryMain = new NTSwiper( '.goldsmith-product-showcase-main', options);

    var $oMainSlide     = $('.goldsmith-product-showcase-main .goldsmith-swiper-slide-first'),
        $oMainImg       = $('.goldsmith-product-showcase-main .goldsmith-swiper-slide-first img'),
        $oMainSrc       = $oMainSlide.data('src'),
        $oZoomSrc       = $('.goldsmith-product-showcase-main .goldsmith-swiper-slide-first').data('zoom-img'),
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

            if ( $hasThumbs ) {
                $oThumbImg.attr('src',tsrc);
            } else {
                $oThumbImg.attr('src',fullsrc);
            }

            setTimeout( function() {
                galleryMain.slideTo(0);
                galleryMain.update();
                galleryMain.updateAutoHeight(10);
                initZoom('reinit',fullsrc);
            }, 100 );
        });


    $( document ).on('click','.goldsmith-product-summary .reset_variations', function( e ) {
        var $form     = $(this).parents('.variations_form'),
            gallery   = $('.goldsmith-product-showcase-main'),
            $oZoomImg = $('.goldsmith-product-showcase-main .goldsmith-swiper-slide-first img.zoomImg');

        $oMainImg.attr('src',$oMainSrc);
        $oMainImg.attr('data-src',$oMainSrc);
        $oZoomImg.attr('src',$oZoomSrc);
        if ( $oMainSrcSet ) {
            $oMainImg.attr('srcset',$oMainSrcSet);
        }
        if ( $oMainSrcSizes ) {
            $oMainImg.attr('sizes',$oMainSrcSizes);
        }

        $oThumbImg.attr('src',$oThumbSrc);
        if ( $oThumbSrcSet ) {
            $oThumbImg.attr('srcset',$oThumbSrcSet);
        }
        if ( $oThumbSrcSizes ) {
            $oThumbImg.attr('sizes',$oThumbSrcSizes);
        }

        setTimeout( function() {
            if ( !$oMainImg.hasClass('swiper-slide-active') ) {
                galleryMain.slideTo(0);
                galleryThumbs.slideTo(0);
            }
            galleryMain.update();
            galleryMain.updateAutoHeight(10);
            galleryThumbs.update();
            initZoom('reinit',$oZoomSrc);
            $('.goldsmith-product-gallery-popups .styler-product-popup').removeClass('active');
            $('.goldsmith-product-gallery-popups .styler-product-popup:first-child').attr('href',$oMainSrc).addClass('active');
        }, 100 );
    });

    initZoom('load');

});
