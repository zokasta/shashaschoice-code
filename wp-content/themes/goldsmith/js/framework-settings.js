(function(window, document, $) {

  "use strict";

	jQuery(document).ready(function( $ ) {

        // masonry
        var masonry = $('.goldsmith-masonry-container');
        if ( masonry.length ) {
            //set the container that Masonry will be inside of in a var
            var container = document.querySelector('.goldsmith-masonry-container');
            //create empty var msnry
            var msnry;
            // initialize Masonry after all images have loaded
            imagesLoaded( container, function() {
               msnry = new Masonry( container, {
                   itemSelector: '.goldsmith-masonry-container>div'
               });
            });
        }

        var block_check = $('.nt-single-has-block');
        if ( block_check.length ) {
            $( ".nt-goldsmith-content ul" ).addClass( "nt-goldsmith-content-list" );
            $( ".nt-goldsmith-content ol" ).addClass( "nt-goldsmith-content-number-list" );
        }
        $( ".goldsmith-post-content-wrapper>*:last-child" ).addClass( "goldsmith-last-child" );


        // add class for bootstrap table
        $( ".menu-item-has-shortcode" ).parent().parent().addClass( "menu-item-has-shortcode-parent" );
        $( ".nt-goldsmith-content table, #wp-calendar" ).addClass( "table table-striped" );
        $( ".woocommerce-order-received .nt-goldsmith-content table" ).removeClass( "table table-striped" );
        // CF7 remove error message
        $('.wpcf7-response-output').ajaxComplete(function(){
            window.setTimeout(function(){
                $('.wpcf7-response-output').addClass('display-none');
            }, 4000); //<-- Delay in milliseconds
            window.setTimeout(function(){
                $('.wpcf7-response-output').removeClass('wpcf7-validation-errors display-none');
                $('.wpcf7-response-output').removeAttr('style');
            }, 4500); //<-- Delay in milliseconds
        });
        if ( $('.woocommerce-ordering select').length ) {
            $('.woocommerce-ordering select').niceSelect();
        }

    }); // end ready

    // Animate loader off screen
    $('#nt-preloader').fadeOut(1000);

})(window, document, jQuery);
