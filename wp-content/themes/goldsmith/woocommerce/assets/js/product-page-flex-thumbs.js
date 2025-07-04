jQuery(document).ready(function($) {
	"use strict";

    //$(".flex-control-thumbs").addClass("swiper-container");
    //$(".woocommerce-product-gallery__image").addClass("swiper-slide");
    var countThumb = $(".flex-control-thumbs li").length;
    //$(".flex-control-thumbs li").addClass("swiper-slide").wrapAll('<div class="goldsmith-swiper-wrapper"></div>');
    $(".flex-control-thumbs").attr("id","product-thumbnails");
    $(".flex-nav-prev a").html('<i class="nt-icon-left-arrow-chevron"></i>');
    $(".flex-nav-next a").html('<i class="nt-icon-right-arrow-chevron"></i>');
    var items = $('.woocommerce-product-gallery.woocommerce-product-gallery--with-images').attr('data-columns');

/*
    var galleryThumbs  = new NTSwiper( '.goldsmith-gallery-swiper-enabled', {
        spaceBetween          : 0,
        slidesPerView         : 1,
        direction             : "horizontal",
        wrapperClass          : "goldsmith-swiper-wrapper",
        slideActiveClass      : "active",
        rewind                : true,
        navigation            : {
            nextEl : ".goldsmith-gallery-swiper-enabled .flex-nav-next",
            prevEl : ".goldsmith-gallery-swiper-enabled .flex-nav-prev"
        }
    });
*/
    $( '.flex-control-thumbs' ).slick({
        infinite: false,
        slidesToShow: countThumb,
        slidesToScroll: 1,
        //swipeToSlide: false,
        //arrows: true,
        focusOnSelect: true,
        prevArrow: $('.goldsmith-gallery-swiper-enabled .flex-nav-prev'),
        nextArrow: $('.goldsmith-gallery-swiper-enabled .flex-nav-next'),
    });

/*
    $( 'body' ).on('change','.goldsmith-variations select', function( e ) {
        galleryThumbs.slideTo(0);
        galleryThumbs.update();
    });
*/
});
