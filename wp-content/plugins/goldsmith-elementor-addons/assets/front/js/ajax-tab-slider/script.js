jQuery(document).ready(function($) {
    "use strict";

    $('.goldsmith-wc-tab-slider').each(function () {
        var myWrapper = $( this ),
            ajaxTab   = myWrapper.find('.goldsmith-tab-nav-item:not(.loaded)'),
            loadedTab = myWrapper.find('.goldsmith-tab-nav-item');

        myWrapper.find('.goldsmith-tab-slider.is-active .thm-tab-slider').each(function (el,i) {
            let mySwiper = new NTSwiper(this, JSON.parse(this.dataset.swiperOptions));
        });

        loadedTab.on('click', function(event){
            var $this = $(this),
                terms = $this.data('tab-terms'),
                id    = terms.id;
            myWrapper.find('.goldsmith-tab-nav-item').removeClass('is-active');
            $this.addClass('is-active');
            $('.goldsmith-tab-slider:not([data-cat-id="'+id+'"])').removeClass('is-active');
            $('.goldsmith-tab-slider[data-cat-id="'+id+'"]').addClass('is-active');
        });
        
        var height = myWrapper.find('.goldsmith-tabs-wrapper .thm-tab-slider').height();
        
        ajaxTab.on('click', function(event){
            var $this    = $(this),
                terms    = $this.data('tab-terms'),
                cat_id   = terms.id,
                per_page = terms.per_page,
                order    = terms.order,
                orderby  = terms.orderby,
                imgsize  = terms.imgsize,
                ajaxurl  = terms.ajaxurl,
                data     = {
                    action     : 'goldsmith_ajax_tab_slider',
                    cat_id     : cat_id,
                    per_page   : per_page,
                    order      : order,
                    orderby    : orderby,
                    img_size   : imgsize,
                    beforeSend : function() {
                        $('.goldsmith-tab-slider[data-cat-id="'+cat_id+'"]').css('min-height', height ).addClass('tab-loading');
                        myWrapper.find('.goldsmith-tab-nav-item').removeClass('is-active');
                        $this.addClass('is-active');
                    }
                };
                
            if ( !$this.hasClass('loaded') && $('.goldsmith-tab-slider:not([data-cat-id="'+cat_id+'"])').length ) {

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                $.post(ajaxurl, data, function(response) {
                    
                    $('.goldsmith-tab-slider:not([data-cat-id="'+cat_id+'"])').removeClass('is-active');
                    $('.goldsmith-tab-slider[data-cat-id="'+cat_id+'"]').addClass('is-active loaded');
                    $('.goldsmith-tab-slider[data-cat-id="'+cat_id+'"] .goldsmith-swiper-wrapper').append(response);

                    $this.addClass('loaded');

                    $('.goldsmith-tab-slider[data-cat-id="'+cat_id+'"] .thm-tab-slider').each(function () {
                        const options = JSON.parse(this.dataset.swiperOptions);
                        var mySwiper  = new NTSwiper( this, options );
                        $('body').trigger('goldsmith_lazy_load');
                    });

                    $('.goldsmith-tab-slider[data-cat-id="'+cat_id+'"] .variations_form').each(function () {
                        $(this).wc_variation_form();
                    });
                    
                    $('.goldsmith-tab-slider[data-cat-id="'+cat_id+'"]').removeClass('tab-loading');
                    
                    $(document.body).trigger('goldsmith_quick_shop');
                    $('body').trigger('goldsmith_quick_init');
                    $(document.body).trigger('goldsmith_variations_init');
                    
                });
            }
        });

    });

});
