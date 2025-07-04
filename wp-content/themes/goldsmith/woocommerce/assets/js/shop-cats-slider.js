jQuery(document).ready(function($) {

    'use strict';
    
    shopCatsSlider();

    $(document).on('goldsmithShopInit', function() {
        shopCatsSlider();
    });

    function shopCatsSlider() {

        var product_cats = $('.shop-area .slick-slide.product-category');

        if ( product_cats.length ) {
            
            product_cats.each(function (i, el) {
                $(this).appendTo('.shop-slider-categories .slick-slider');
            });
            
            var myContainer = $('.shop-slider-categories');
            var mySlick = $('.slick-slider', myContainer);
            mySlick.not('.slick-initialized').slick({
                autoplay      : false,
                slidesToShow  : 6,
                speed         : 500,
                focusOnSelect : true,
                infinite      : false,
                prevArrow     : '.slide-prev-cats',
                nextArrow     : '.slide-next-cats',
                responsive    : [
                    {
                        breakpoint: 576,
                        settings  : {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 768,
                        settings  : {
                            slidesToShow: 4
                        }
                    },
                    {
                        breakpoint: 992,
                        settings  : {
                            slidesToShow: 5
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings  : {
                            slidesToShow: 6
                        }
                    }
                ]
            });
        }
    }

});
