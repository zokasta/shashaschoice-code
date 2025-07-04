jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';

    /**
    * Init zoom.
    */
    function initZoomFull($action,$url) {
        if ( 'function' !== typeof $.fn.zoom && !wc_single_product_params.zoom_enabled ) {
            return false;
        }

        var galleryWidthFull = $('.goldsmith-product-carousel .swiper-slide').width(),
            zoomEnabled  = false,
            zoom_options = {
                touch: false
            };

        if ( 'ontouchstart' in document.documentElement ) {
            zoom_options.on = 'click';
        }

        $('.goldsmith-product-carousel .swiper-slide img').each( function( index, target ) {
            var imageFull = $( target );
            var imageIndex = imageFull.parents('.swiper-slide').data('swiper-slide-index');

            if ( imageFull.attr( 'width' ) > galleryWidthFull ) {
                if ( $action == 'load' ) {

                    zoom_options.url = imageFull.parent().data('zoom-img');
                    imageFull.wrap('<span class="goldsmith-zoom-wrapper" style="display:inline-block"></span>')
                      .css('display', 'block')
                      .parent()
                      .zoom(zoom_options);
                } else {
                    imageFull.trigger('zoom.destroy').unwrap();
                    zoom_options.url = imageIndex == 0 ? $url : imageFull.parent().data('zoom-img');
                    imageFull.wrap('<span class="goldsmith-zoom-wrapper" style="display:inline-block"></span>')
                      .css('display', 'block')
                      .parent()
                      .zoom(zoom_options);
                }
            }
        });
    }

    if ( $('.goldsmith-product-carousel').length ) {

        var options = {
            loop                 : true,
            roundLengths         : true,
            speed                : 800,
            spaceBetween         : 0,
            slidesPerView        : 1,
            direction            : "horizontal",
            wrapperClass         : "goldsmith-swiper-wrapper",
            slideActiveClass     : "active",
            centeredSlides       : true,
            slideToClickedSlide  : true,
            grabCursor           : true,
            autoHeight           : false,
            preventClicks        : false,
            navigation           : {
                nextEl : ".goldsmith-product-carousel .goldsmith-swiper-next",
                prevEl : ".goldsmith-product-carousel .goldsmith-swiper-prev"
            },
            pagination           : {
                el                : ".goldsmith-product-carousel .goldsmith-swiper-pagination",
                bulletClass       : "goldsmith-swiper-bullet",
                bulletActiveClass : "active",
                type              : "bullets",
                clickable         : true
            },
            effect               : 'slide',
            coverflowEffect      : {
                rotate       : goldsmith_vars.shop_single.showcase_carousel_rotate,
                slideShadows : false
            },
            breakpoints          : {
                768 : {
                    slidesPerView : 3
                },
                1024 : {
                    slidesPerView : 4
                }
            },
            on                    : {
                transitionEnd : function ( swiper ) {
                    var  active = swiper.realIndex;

                    $( '.goldsmith-product-carousel .swiper-slide:not(.swiper-slide-active)' ).each(function () {
                        var iframe = $( this ).find('iframe');
                        if ( iframe.length>0 ) {
                            iframe[0].contentWindow.postMessage('{"event":"command","func":"' + 'pauseVideo' + '","args":""}', '*');
                        }
                    });

                    $( '.goldsmith-product-carousel .swiper-slide-active' ).each(function () {
                        var iframe2 = $( this ).find('iframe');
                        if ( iframe2.length>0 ) {
                            iframe2[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
                        }
                    });
                },
                afterInit : function(swiper){
                    var iframesrc = $('.goldsmith-product-carousel .iframe-video iframe').data('src');
                    $( '.goldsmith-product-carousel .iframe-video iframe' ).attr('src', iframesrc);
                }
            }
        };

        var galleryCarousel     = new NTSwiper( '.goldsmith-product-carousel', options );

        var $oMainImgFull       = $('goldsmith-product-carousel .goldsmith-swiper-slide-first img'),
            $oZoomSrc           = $('.goldsmith-product-carousel .goldsmith-swiper-slide-first').data('zoom-img'),
            $oMainSrcFull       = $oMainImgFull.data('src'),
            $oMainSrcSetFull    = $oMainImgFull.data('srcset'),
            $oMainSrcSizesFull  = $oMainImgFull.data('sizes');

        $( document ).on('change','.goldsmith-product-summary .variations_form select', function( e ) {
            var $thisFull      = $(this),
                $formFull      = $thisFull.parents('.variations_form'),
                variationsFull = $formFull.data('product_variations'),
                $oZoomImgFull  = $('.goldsmith-product-carousel .goldsmith-swiper-slide-first img.zoomImg'),
                galleryFull    = $('.goldsmith-product-carousel');

            setTimeout( function() {
                var current_id = $formFull.attr('current-image'),
                    full_src,
                    imageFull,
                    timageFull,
                    srcFull,
                    srcsetFull,
                    sizesFull;

                $.map(variationsFull, function(elementOfArray, indexInArray) {
                    if (elementOfArray.image_id == current_id) {
                        imageFull  = elementOfArray.image;
                        full_src   = imageFull.full_src;
                        srcFull    = imageFull.src;
                        srcsetFull = imageFull.srcset;
                        sizesFull  = imageFull.sizes;
                    }
                });

                if ( current_id ) {

                    $oMainImgFull.attr('src',srcFull);
                    $oMainImgFull.attr('data-src',srcFull);
                    $oZoomImgFull.attr('src',full_src);
                    if ( srcsetFull ) {
                        $oMainImgFull.attr('srcset',srcsetFull);
                    }

                    if ( sizesFull ) {
                        $oMainImgFull.attr('sizes',sizesFull);
                    }

                    setTimeout( function() {

                        if ( !$oMainImgFull.hasClass('swiper-slide-active') ) {
                            $('.goldsmith-product-carousel .swiper-pagination .swiper-pagination-bullet:first').trigger('click');
                        }

                        initZoomFull('reinit',full_src);

                    }, 100, $oMainImgFull,galleryCarousel );
                }
            }, 50 );
        });

        $( document ).on('click','.goldsmith-product-summary .reset_variations', function( e ) {

            var $formFull     = $(this).parents('.variations_form'),
                galleryFull   = $('.goldsmith-product-carousel'),
                $oZoomImgFull = $('.goldsmith-product-carousel .goldsmith-swiper-slide-first img.zoomImg');

            $oMainImgFull.attr('src',$oMainSrcFull);
            $oMainImgFull.attr('data-src',$oMainSrcFull);
            $oZoomImgFull.attr('src',$oZoomSrc);

            if ( $oMainSrcSetFull ) {
                $oMainImgFull.attr('srcset',$oMainSrcSetFull);
            }

            if ( $oMainSrcSizesFull ) {
                $oMainImgFull.attr('sizes',$oMainSrcSizesFull);
            }

            setTimeout( function() {

                if ( !$oMainImgFull.hasClass('swiper-slide-active') ) {
                    $('.goldsmith-product-carousel .swiper-pagination .swiper-pagination-bullet:first').trigger('click');
                }

                initZoomFull('reinit',$oZoomSrc);

            }, 100, $oMainImgFull,galleryCarousel );

        });

        initZoomFull('load');
    }

});
