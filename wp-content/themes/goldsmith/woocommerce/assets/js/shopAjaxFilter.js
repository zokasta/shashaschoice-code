(function($) {

    var $doc  = $(document),
        $win  = $(window),
        $body = $('body'),
        links = '.goldsmith-shop-fast-filters.is-shop.is-ajax .goldsmith-fast-filters-list li a,.woocommerce .widget_rating_filter ul li a, .widget_product_categories a, .woocommerce-widget-layered-nav a, body.post-type-archive-product:not(.woocommerce-account) .goldsmith-woocommerce-pagination a, body.tax-product_cat:not(.woocommerce-account) .goldsmith-woocommerce-pagination a, .woocommerce-widget-layered-nav-list a, .top-action a, .goldsmith-choosen-filters a, .widget_product_tag_cloud a, .goldsmith-shop-filter-area a, .goldsmith-woo-breadcrumb .breadcrumb li a, .shop-slider-categories .slick-slide a, .goldsmith-remove-filter a, .goldsmith-product-status a, .goldsmith-widget-product-categories a,.goldsmith-shop-hero .goldsmith-wc-category-list li a, .goldsmith-shop-hero .goldsmith-category-slide-item.type-filter a, .shop-cat-banner-template-wrapper .goldsmith-banner-link,.goldsmith-products-column .goldsmith-cat-item a',
        sidebar_links = '.nt-sidebar-inner .widget_rating_filter ul li a, .nt-sidebar-inner .widget_product_categories a, .nt-sidebar-inner .woocommerce-widget-layered-nav a, .nt-sidebar-inner .woocommerce-widget-layered-nav-list a, .nt-sidebar-inner .goldsmith-choosen-filters a, .goldsmith-choosen-filters-row a, .nt-sidebar-inner .widget_product_tag_cloud a, .nt-sidebar-inner .goldsmith-remove-filter a, .nt-sidebar-inner .goldsmith-product-status a, .nt-sidebar-inner .goldsmith-widget-product-categories a';

    function scrollToTop($target) {
        if ( $($target).length ) {
            var adminBarHeight = $body.hasClass('admin-bar') ? 46 + 140 : 140;
            setTimeout(function(){
                $('html, body').stop().animate({
                    scrollTop: $($target).offset().top - adminBarHeight
                }, 800);
            }, 800 );
        }
    }

    function sortOrder() {
        var $order = $('.woocommerce-ordering');

        $order.on('change', 'select.orderby', function() {
            var $form = $(this).closest('form');
            $form.find('[name="_pjax"]').remove();

            $.pjax({
                container: '.nt-shop-page-wrapper',
                timeout  : 10000,
                url      : '?' + $form.serialize(),
                scrollTo : false,
                renderCallback: function(context, html, afterRender) {
                    beforeRender(context, html);
                    $(context).replaceWith(html);
                    goldsmithAfterRender();
                    $doc.trigger('goldsmithShopInit');
                }
            });
        });

        $order.on('submit', function(e) {
            return false;
        });
    }

    function priceSlider() {

        if ( $('body').hasClass('shop-layout-no-sidebar') || !$( '.price_slider' ).length > 0 ) {
            return;
        }

        $( document.body ).on( 'price_slider_create price_slider_slide', function( event, min, max ) {

            $( '.price_slider_amount span.from' ).html( accounting.formatMoney( min, {
                symbol:    woocommerce_price_slider_params.currency_format_symbol,
                decimal:   woocommerce_price_slider_params.currency_format_decimal_sep,
                thousand:  woocommerce_price_slider_params.currency_format_thousand_sep,
                precision: woocommerce_price_slider_params.currency_format_num_decimals,
                format:    woocommerce_price_slider_params.currency_format
            } ) );

            $( '.price_slider_amount span.to' ).html( accounting.formatMoney( max, {
                symbol:    woocommerce_price_slider_params.currency_format_symbol,
                decimal:   woocommerce_price_slider_params.currency_format_decimal_sep,
                thousand:  woocommerce_price_slider_params.currency_format_thousand_sep,
                precision: woocommerce_price_slider_params.currency_format_num_decimals,
                format:    woocommerce_price_slider_params.currency_format
            } ) );

            $( document.body ).trigger( 'price_slider_updated', [ min, max ] );

        });

        function initPriceFilter() {
            if ( $('body').hasClass('shop-layout-no-sidebar') || !$( '.price_slider' ).length > 0 ) {
                return;
            }
            $( 'input#min_price, input#max_price' ).hide();
            $( '.price_slider, .price_label' ).show();

            var min_price         = $( '.price_slider_amount #min_price' ).data( 'min' ),
                max_price         = $( '.price_slider_amount #max_price' ).data( 'max' ),
                step              = $( '.price_slider_amount' ).data( 'step' ) || 1,
                current_min_price = $( '.price_slider_amount #min_price' ).val(),
                current_max_price = $( '.price_slider_amount #max_price' ).val();

            $( '.price_slider:not(.ui-slider)' ).slider({
                range  : true,
                animate: true,
                min    : min_price,
                max    : max_price,
                step   : step,
                values : [ current_min_price, current_max_price ],
                create : function() {

                    $( '.price_slider_amount #min_price' ).val( current_min_price );
                    $( '.price_slider_amount #max_price' ).val( current_max_price );

                    $( document.body ).trigger( 'price_slider_create', [ current_min_price, current_max_price ] );
                },
                slide: function( event, ui ) {

                    $( 'input#min_price' ).val( ui.values[0] );
                    $( 'input#max_price' ).val( ui.values[1] );

                    $( document.body ).trigger( 'price_slider_slide', [ ui.values[0], ui.values[1] ] );
                },
                change: function( event, ui ) {

                    $( document.body ).trigger( 'price_slider_change', [ ui.values[0], ui.values[1] ] );
                }
            });
        }

        initPriceFilter();

        $( document.body ).on( 'init_price_filter', initPriceFilter );

        var hasSelectiveRefresh = (
            'undefined' !== typeof wp &&
            wp.customize &&
            wp.customize.selectiveRefresh &&
            wp.customize.widgetsPreview &&
            wp.customize.widgetsPreview.WidgetPartial
        );
        if ( hasSelectiveRefresh ) {
            wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function() {
                initPriceFilter();
            } );
        }

        var $min_price = $('.price_slider_amount #min_price');
        var $max_price = $('.price_slider_amount #max_price');
        var $products  = $('.products');

        if (typeof woocommerce_price_slider_params === 'undefined' || $min_price.length < 1 || !$.fn.slider) {
            return false;
        }

        var $slider = $('.price_slider');

        if ($slider.slider('instance') !== undefined) {
            return;
        }

        $('input#min_price, input#max_price').hide();
        $('.price_slider, .price_label').show();

        var min_price         = $min_price.data('min'),
            max_price         = $max_price.data('max'),
            current_min_price = parseInt(min_price, 10),
            current_max_price = parseInt(max_price, 10);

        if ($products.attr('data-min_price') && $products.attr('data-min_price').length > 0) {
            current_min_price = parseInt($products.attr('data-min_price'), 10);
        }

        if ($products.attr('data-max_price') && $products.attr('data-max_price').length > 0) {
            current_max_price = parseInt($products.attr('data-max_price'), 10);
        }

        $slider.slider({
            range  : true,
            animate: true,
            min    : min_price,
            max    : max_price,
            values : [
                current_min_price,
                current_max_price
            ],
            create : function() {
                $min_price.val(current_min_price);
                $max_price.val(current_max_price);

                $body.trigger('price_slider_create', [
                    current_min_price,
                    current_max_price
                ]);
            },
            slide  : function(event, ui) {
                $('input#min_price').val(ui.values[0]);
                $('input#max_price').val(ui.values[1]);

                $body.trigger('price_slider_slide', [
                    ui.values[0],
                    ui.values[1]
                ]);
            },
            change : function(event, ui) {
                $body.trigger('price_slider_change', [
                    ui.values[0],
                    ui.values[1]
                ]);
            }
        });

        setTimeout(function() {
            $body.trigger('price_slider_create', [
                current_min_price,
                current_max_price
            ]);

            if ($slider.find('.ui-slider-range').length > 1) {
                $slider.find('.ui-slider-range').first().remove();
            }
        }, 10);
    }

    function ajaxHandler() {
        $doc.pjax(links, '.nt-shop-page-wrapper', {
            timeout       : 10000,
            scrollTo      : false,
            renderCallback: function(context, html, afterRender) {
                beforeRender(context, html);
                $(context).replaceWith(html);
                html.find('.goldsmith-products>.product-category').each(function(){
                    $(this).appendTo('.goldsmith-products-category-wrapper');
                    $(this).removeClass('goldsmith-hidden');
                });

                afterRender();
                goldsmithAfterRender();
                $doc.trigger('goldsmithShopInit');
            }
        });

        $doc.on('submit', '.widget_price_filter form', function(event) {
            var $form = $(this);
            $form.find('[name="_pjax"]').remove();
            $.pjax({
                container: '.nt-shop-page-wrapper',
                timeout  : 10000,
                url      : '?' + $form.serialize(),
                scrollTo : false,
                renderCallback: function(context, html, afterRender) {
                    beforeRender(context, html);
                    $(context).replaceWith(html);
                    goldsmithAfterRender();
                    $doc.trigger('goldsmithShopInit');
                }
            });

            return false;
        });

        $doc.on('pjax:error', function(xhr, textStatus, error) {
            console.log('pjax error ' + error);
            $('.nt-shop-page-wrapper').removeClass('loading');
        });

        $doc.on('pjax:start', function() {
            $('.nt-shop-page-wrapper').addClass('loading');
            scrollToTop('.shop-area .goldsmith-products-wrapper');
        });

        $doc.on('pjax:complete', function() {
            $doc.trigger('goldsmithShopInit');
        });

        $doc.on('pjax:end', function() {
            $('.nt-shop-page-wrapper').removeClass('loading');
        });
    }

    function beforeRender(context, html) {
        var fixedSidebar = context.find('.goldsmith-shop-fixed-sidebar');

        if ( $(fixedSidebar).hasClass('active')) {
            html.find('.goldsmith-shop-fixed-sidebar').addClass('active');
        }
        if ( $('.woocommerce.widget_price_filter').length) {
            var currentURL = window.location.href;
            html.find('.woocommerce.widget_price_filter> form').attr('action', currentURL);
        }
    }

    function goldsmithAfterRender() {
        sortOrder();
        priceSlider();

        $('.goldsmith-products .product' ).each(function(e, index){
            var delay = e/10;
            var anim = $(this).data('product-animation');
            $(this).addClass( 'animated '+ anim ).css('animation-delay', delay+'s');
        });

        $('[data-label-color]').each( function() {
            var $this = $(this);
            var $color = $this.data('label-color');
            $this.css( {'background-color': $color,'border-color': $color } );
        });

        $('.row-infinite').hide();

        if ( $('.woocommerce-ordering select').length ) {
            $('.woocommerce-ordering select').niceSelect();
        }

        if ( $('.goldsmith-swiper-slider') ) {
            $('.nt-shop-page-wrapper .goldsmith-swiper-slider').each(function () {
                const options = $(this).data('swiper-options');
                const mySlider = new NTSwiper(this, options);
            });
        }

        if ( $('.goldsmith-slick-slider') ) {
            $('.nt-shop-page-wrapper .goldsmith-slick-slider').each(function () {
                $(this).not('.slick-initialized').slick();
            });
        }

        if ( typeof goldsmith_vars !== 'undefined' && goldsmith_vars ) {
            var colors = goldsmith_vars.swatches;

            $('.woocommerce-widget-layered-nav-list li a').each(function () {
                var $this = $(this);
                var title = $this.html();
                for (var i in colors) {
                    if ( title == i ) {
                        var is_white = color == '#fff' || color == '#FFF' || color == '#ffffff' || color == '#FFFFFF' ? 'is_white' : '';
                        var color = '<span class="goldsmith-swatches-widget-color-item'+is_white+'" style="background-color: '+colors[i]+';"></span>';
                        $this.prepend(color);
                    }
                }
            });

            $('.goldsmith-fast-filters-submenu span[data-color]').each(function () {
                var $this    = $(this);
                var color    = $this.data('color');
                var is_white = color == '#fff' || color == '#FFF' || color == '#ffffff' || color == '#FFFFFF' ? 'is_white' : '';
                $this.css('background-color',color);
                if (is_white) {
                    $this.addClass(is_white);
                }
            });
        }
        $(document.body).trigger('goldsmith_variations_init');

        $('.site-scroll li.cat-parent.checked').each(function () {
            $(this).find('.subDropdown').trigger('click');
        });

        $('.nt-sidebar-widget-body li.checked, .nt-sidebar-widget-body .chosen').each(function () {
            $(this).parents('.nt-sidebar-widget-body').prev('.nt-sidebar-inner-widget-title').addClass('active');
        });

        $('.nt-sidebar-widget-body a').on('click', function (e) {
            $(this).parents('.nt-sidebar-widget-body').prev().toggleClass('active');
        });

        $('.nt-sidebar-inner .woocommerce-widget-layered-nav-list__item.chosen a').each(function() {
            $(this).prepend('<span class="remove-filter"></span>');
        });

        $('[data-countdown]').each(function () {
            var $this     = $(this),
                data      = $this.data('countdown'),
                finalDate = data.date,
                hr        = data.hr,
                min       = data.min,
                sec       = data.sec;
            $this.countdown(finalDate, function (event) {
                $this.html(event.strftime('<div class="time-count day"><span>%D</span>Day</div><div class="time-count hour"><span>%H</span>'+hr+'</div><div class="time-count min"><span>%M</span>'+min+'</div><div class="time-count sec"><span>%S</span>'+sec+'</div>'));
            });
        });
    }

    $doc.ready(function() {
        sortOrder();
        ajaxHandler();
    });

})(jQuery);
