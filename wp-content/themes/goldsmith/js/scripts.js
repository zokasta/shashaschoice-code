/*-----------------------------------------------------------------------------------

    Theme Name: Goldsmith
    Description: WordPress Theme
    Author: Ninetheme
    Author URI: https://ninetheme.com/
    Version: 1.0

-----------------------------------------------------------------------------------*/

(function(window, document, $) {

    "use strict";

    $.fn.sameSize = function( width, max ) {
        var prop = width ? 'width' : 'height',
        size = Math.max.apply( null, $.map( this, function( elem ) {
            return $( elem )[ prop ]();
        })),
        max = size < max ? size : max;
        return this[ prop ]( max || size );
    };

    jQuery.event.special.touchstart = {
        setup: function( _, ns, handle ) {
            this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
        }
    };
    jQuery.event.special.touchmove = {
        setup: function( _, ns, handle ) {
            this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
        }
    };
    jQuery.event.special.wheel = {
        setup: function( _, ns, handle ){
            this.addEventListener("wheel", handle, { passive: true });
        }
    };
    jQuery.event.special.mousewheel = {
        setup: function( _, ns, handle ){
            this.addEventListener("mousewheel", handle, { passive: true });
        }
    };

    var doc         = $(document),
        win         = $(window),
        body        = $('body'),
        winw        = $(window).outerWidth(),
        megasubmenu = $('.elementor-top-section .goldsmith-header-top-menu-area li.menu-item-mega-parent > ul.submenu');

    function bodyResize(winw) {
        if ( winw <= 1024 ) {
            body.removeClass('nt-desktop').addClass('nt-mobile');
        } else {
            body.removeClass('nt-mobile').addClass('nt-desktop');
        }
    }

    function sideMainHeader() {
        $('.goldsmith-main-sidebar-header .dropdown-btn').on('click',function(e){
            e.preventDefault();
            var $this    = $(this),
                $parent  = $this.parent().parent(),
                $submenu = $this.parent().next();

            if ( $parent.hasClass('goldsmith-active') ) {
                $parent.removeClass('goldsmith-active');
                $submenu.slideUp();
            } else {
                $parent.siblings('.goldsmith-active').removeClass('goldsmith-active').find('.submenu').slideUp();
                $parent.addClass('goldsmith-active');
                $submenu.slideDown();
            }
        });

        $('.goldsmith-mobile-menu-trigger').on('click',function(e){
            e.preventDefault();
            var $this = $(this);

            if ( $this.hasClass('goldsmith-active') ) {
                $('html,body').removeClass('goldsmith-overlay-open sidebar-menu-active');
                $this.removeClass('goldsmith-active');
                $('.goldsmith-main-sidebar-header').removeClass('goldsmith-active');
            } else {
                $('html,body').addClass('goldsmith-overlay-open sidebar-menu-active');
                $this.addClass('goldsmith-active');
                $('.goldsmith-main-sidebar-header').addClass('goldsmith-active');
            }
        });

        $('.goldsmith-mobile-menu-close-trigger').on('click',function(e){
            $('html,body').removeClass('goldsmith-overlay-open sidebar-menu-active');
            $('.goldsmith-mobile-menu-trigger,.goldsmith-main-sidebar-header').removeClass('goldsmith-active');
        });
    }

    function topMainHeader() {
        megasubmenu.each( function() {
            var cont     = $( this ),
                wrap     = cont.closest( '.navigation' ),
                wrapoff  = wrap.offset(),
                wrapleft = wrapoff.left,
                parentw  = cont.closest( '.elementor-top-section' ).outerWidth();

            if ( winw > 1024 ) {
                cont.css({
                    'left':'-'+ ( wrapleft ) +'px',
                    'width': parentw+'px',
                });
            } else {
                cont.removeAttr('style');
            }
        });
    }

    function topMainHeaderResize(winw) {
        if ( winw <= 1024 ) {
            megasubmenu.each( function() {
                var cont = $( this );
                cont.removeAttr('style');
            });
        } else {
            megasubmenu.each( function() {
                var cont     = $( this ),
                    wrap     = cont.closest( '.navigation' ),
                    wrapoff  = wrap.offset(),
                    wrapleft = wrapoff.left,
                    parentw  = cont.closest('.elementor-top-section').outerWidth();
                cont.css({
                    'left':'-'+ ( wrapleft ) +'px',
                    'width': parentw+'px',
                });
            });
        }
    }

    function mobileSlidingMenu() {

        if ( $('.goldsmith-header-mobile-slide-menu').length ){
            $('.goldsmith-header-mobile-slide-menu').slidingMenu({
                className : "goldsmith-header-mobile-slide-menu",
                transitionDuration : 250,
                dataJSON : false,
                initHref : false,
                backLabel: 'Back'
            });
        }
        if ( $('.goldsmith-header-lang-slide-menu').length ){
            $('.goldsmith-header-lang-slide-menu').slidingMenu({
                className : "goldsmith-header-lang-slide-menu",
                transitionDuration : 250,
                dataJSON : false,
                initHref : false,
                backLabel: 'Back'
            });
        }

        $('.sliding-menu .menu-item-has-children>.sliding-menu__nav').each( function() {
            var $this = $( this ),
                id = $this.data( 'id' ),
                parentTitle = $this.text(),
                parents = $this.parents( '.sliding-menu' ),
                subBack = parents.find( '.sliding-menu__panel[data-id="'+id+'"] .sliding-menu__back' );
            subBack.text(parentTitle);
        });

        $('.sliding-menu__panel:not(.shortcode_panel)').each( function() {
            $( '<li class="sliding-menu-inner"><ul></ul></li>' ).appendTo($(this ));
        });
        $('.sliding-menu__panel .menu-item').each( function() {
            $( this ).appendTo($( this ).parents('.sliding-menu__panel').find('.sliding-menu-inner>ul'));
        });
        $('.sliding-menu').each( function() {
            var height = $( this ).find('.sliding-menu__panel-root').outerHeight();
            $( this ).css('height',height);
        });
    }

    $('.header-top-buttons .top-action-btn:not(.has-custom-action)[data-name], .goldsmith-header-mobile-top-actions .top-action-btn[data-name]').on('click',function(e){
        var $this = $(this),
            $name = $this.data('name');
            
        if ($this.is('[data-name="search-popup"]')) {
            return;
        }

        $('html,body').addClass('goldsmith-overlay-open');
        $('.top-action-btn:not([data-name="'+$name+'"],.panel-header-btn').removeClass('active');
        $('.goldsmith-side-panel .panel-content-item:not([data-name="'+$name+'"]),.panel-header-btn:not([data-name="'+$name+'"])').removeClass('active');
        $('.goldsmith-side-panel,.goldsmith-side-panel [data-name="'+$name+'"],.panel-header-btn[data-name="'+$name+'"]').addClass('active');
    });

    $('[data-name="search-popup"], .popup-search, a[href="#goldsmith-popup-search"],.goldsmith-mobile-search-trigger').on('click',function(e){
        $('html,body').addClass('goldsmith-overlay-open');
        $('.goldsmith-popup-search-panel').addClass('active');
        $('.top-action-btn:not([data-name="search"]),.panel-header-btn').removeClass('active');
        $('.goldsmith-side-panel .panel-content-item,.panel-header-btn').removeClass('active');
    });

    $('.goldsmith-bottom-mobile-nav [data-name="search-cats"]').on('click',function(e){
        $('html,body').addClass('goldsmith-overlay-open');
        $('.goldsmith-header-mobile').addClass('active');
        $('.goldsmith-header-mobile .action-content:not([data-target-name="search-cats"])').removeClass('active');
        $('.goldsmith-header-mobile .action-content[data-target-name="search-cats"]').addClass('active');
        $('.goldsmith-header-mobile .top-action-btn').removeClass('active');
        $('.goldsmith-header-mobile [data-name="search-cats"]').addClass('active');
    });

    $('[data-account-action="account"]').on('click',function(e){
        $('html,body').addClass('goldsmith-overlay-open');
        $('.account-area-form-wrapper .active').removeClass('active');
        $('.goldsmith-header-mobile, .goldsmith-header-mobile .account-area, .goldsmith-header-mobile-content .login-form-content').addClass('active');
        $('.top-action-btn[data-name="account"]').trigger('click');
    });

    $('.goldsmith-open-popup').on('click',function(e){
        $('html,body').removeClass('goldsmith-overlay-open');
        $('.goldsmith-header-mobile, .goldsmith-side-panel .panel-content-item,.panel-header-btn').removeClass('active');
    });

    $('.has-default-header-type-trans:not(.force-transparent-header) .goldsmith-header-default .navigation.primary-menu').hover(
        function(){
            $('.goldsmith-header-default').addClass('trans-hover');
        },
        function(){
            $('.goldsmith-header-default').removeClass('trans-hover');
        }
    );

    function mobileHeaderActions() {
        $('.top-action-btn:not(.has-custom-action)[data-name]').each( function(){
            var $this = $(this),
                $name = $this.data('name');

            $this.on('click',function(e){
                var $thiss = $(this);
                $('.top-action-btn:not([data-name="'+$name+'"]').removeClass('active');

                $('[data-target-name]').removeClass('active');
                if ( $thiss.hasClass('active') ) {
                    $thiss.removeClass('active');
                    $('.goldsmith-header-slide-menu,.search-area-top').addClass('active');
                    $('[data-target-name="'+$name+'"]').removeClass('active');
                } else {
                    $thiss.addClass('active');
                    $('.goldsmith-header-slide-menu,.search-area-top').removeClass('active');
                    $('[data-target-name="'+$name+'"]').addClass('active');
                }
                if ( !($('[data-target-name="'+$name+'"]').length) ) {
                    $('.search-area-top,.goldsmith-header-slide-menu').addClass('active');
                }
                if ( $('.goldsmith-header-mobile-content div[data-name="checkout"]').hasClass('active') ) {
                    $('.goldsmith-header-mobile-content div[data-name="checkout"]').removeClass('active');
                }
                e.preventDefault();
            });
        });

        $('.mobile-toggle').on('click',function(e){

            $('.goldsmith-header-mobile-content .active, .sidebar-top-action .active, .goldsmith-side-panel').removeClass('active');
            $('.search-area-top').addClass('active');
            $('.account-area .login-form-content').addClass('active');
            if ( $('.goldsmith-header-mobile').hasClass('active') ) {
                $('html,body').removeClass('goldsmith-overlay-open');
                $('.goldsmith-header-mobile').removeClass('active');
            } else {
                $('html,body').addClass('goldsmith-overlay-open');
                $('.goldsmith-header-mobile,.menu-area').addClass('active');
            }
            e.preventDefault();
        });

        $('.account-area .signin-title').on('click',function(){
            $('.form-action-btn').removeClass('active');
            $(this).addClass('active');
            $('.account-area .register-form-content').removeClass('active');
            $('.account-area .login-form-content').addClass('active');
        });
        $('.account-area .register-title').on('click',function(){
            $('.form-action-btn').removeClass('active');
            $(this).addClass('active');
            $('.account-area .login-form-content').removeClass('active');
            $('.account-area .register-form-content').addClass('active');
        });
        if ( $('.account-area.action-content .account-area-social-form-wrapper').length ) {
            $('.account-area-form-wrapper').css('min-height', $('.account-area-form-wrapper .woocommerce-form-login').height()+50);
        }
    }

    $('.goldsmith-panel-close,.goldsmith-main-overlay').on('click',function(){
    	$('.goldsmith-main-sidebar-header, .goldsmith-mobile-menu-trigger').removeClass('goldsmith-active');
        $('html,body').removeClass('goldsmith-overlay-open');
        $('.goldsmith-header-mobile, .goldsmith-side-panel, .goldsmith-popup-search-panel, .nt-sidebar').removeClass('active');
        $('.goldsmith-header-mobile-content .active, .goldsmith-header-mobile-sidebar-bottom, .sidebar-top-action .active').removeClass('active');
        $('.goldsmith-header-slide-menu').addClass('active');
        $('.goldsmith-shop-popup-notices').removeClass('active');
        $('.goldsmith-shop-popup-notices').removeClass('goldsmith-notices-has-error');
    });

    $('.panel-header-btn').on('click',function(){
        var $this = $(this),
            $name = $this.data('name');
        if ( !$this.hasClass( 'active' ) ) {
            $('.panel-header-btn,.panel-content-item').removeClass('active');
            $this.addClass('active');
            $('.panel-content-item[data-name="'+$name+'"]').addClass('active');
        }
    });

    $(".goldsmith-header-top-menu-area .menu-item-has-children").hover(
        function(){
            $(this).addClass('on-hover');
        },
        function(){
            $(this).removeClass('on-hover');
        }
    );

    function mobileHeaderResize(winw) {
        if ( winw >= 490 ) {
            if ( $('.top-action-btn.share').hasClass('active') ) {
                $('.top-action-btn.share,.goldsmith-header-mobile-content').removeClass('active');
            }
        }
        if ( winw > 992 ) {
            $('html,body').removeClass('goldsmith-overlay-open');
            $('.goldsmith-header-mobile').removeClass('active');
            $('.goldsmith-popup-search-panel').removeClass('active');
        }
    }

    /*=============================================
    Mobile Menu
    =============================================*/
    //SubMenu Dropdown Toggle
    if ( $('.header-widget').length ) {
        $('.header-widget.header-style-two').parents('.elementor-top-section').addClass('big-index has-header-style-two');
    }

    // set height for header spacer
    function headerSpacerHeight(winw) {
        if ( $('.goldsmith-header-default').length ) {
            var height;
            if ( winw > 992 ) {
                height = $('.goldsmith-header-default').height();
            } else {
                height = $('.goldsmith-header-mobile-top').height();
            }
            $('.header-spacer').css('height', height );
        }
    }


    function goldsmithHeaderCatMenu() {
        $('.goldsmith-vertical-menu-wrapper').each(function () {
            const $this    = $(this);
            const menu     = $this.find('.goldsmith-vertical-menu');
            const toggle   = $this.find('.goldsmith-vertical-menu-toggle');
            const more     = $this.find('.goldsmith-more-item-open');
            const morecats = $this.find('.goldsmith-more-categories');
            /*=============================================
            Toggle Active
            =============================================*/
            $(toggle).on('click', function () {
                $(menu).slideToggle(500);
                return false;
            });
            $(more).slideUp();
            $(morecats).on('click', function () {
                $(this).toggleClass('show');
                $(more).slideToggle();
            });
        });
    }

    /*=============================================
    Menu sticky & Scroll to top
    =============================================*/
    function scrollToTopBtnClick() {
        if ( $(".scroll-to-target").length ) {
            $( ".scroll-to-target" ).on("click", function () {
                var target = $(this).attr("data-target");
                // animate
                $("html, body").animate({scrollTop: $(target).offset().top},1000);
                return false;
            });
        }
    }

    if ( $(".scroll-to-target").length ) {
        $( ".scroll-to-target" ).on("click", function () {
            var target = $(this).attr("data-target");
            // animate
            $("html, body").animate({scrollTop: $(target).offset().top},1000);
            return false;
        });
    }

    /*=============================================
    Menu sticky & Scroll to top
    =============================================*/
    function scrollToTopBtnHide() {
        var offset = 100;
        if ( $(".scroll-to-target").length ) {
            if ( $(window).scrollTop() > offset ) {
                $(".scroll-to-top").fadeIn(500);
            } else if ( $(".scroll-to-top").scrollTop() <= offset ) {
                $(".scroll-to-top").fadeOut(500);
            }
        }
    }

    /*=============================================
    Data Background
    =============================================*/
    $("[data-background]").each(function () {
        $(this).css("background-image", "url(" + $(this).attr("data-background") + ")")
    });


    /* goldsmithSwiperSlider */
    function goldsmithSwiperSlider() {
        if ( $(".goldsmith-swiper-slider").length ) {
            $('.goldsmith-swiper-slider').each(function () {
            	var container  = $(this);
                const options  = $(this).data('swiper-options');
                const mySlider = new NTSwiper(this, options );
                mySlider.on('transitionEnd', function () {
                    var animIn = $(container).find('.swiper-slide').data('anim-in');
                    var active = $(container).find('.swiper-slide-active');
                    var inactive = $(container).find('.swiper-slide:not(.swiper-slide-active)');

                    if( typeof animIn != 'undefined' ) {
                        inactive.find('.has-animation').each(function(e){
                            $(this).removeClass('animated '+animIn);
                        });
                        active.find('.has-animation').each(function(e){
                            $(this).addClass('animated '+animIn);
                        });
                    }
                });
            });
        }
    }

    /* goldsmithSlickSlider */
    function goldsmithSlickSlider() {
        $('.goldsmith-slick-slider').each(function () {
            $(this).not('.slick-initialized').slick();
        });
    }

    // goldsmithVegasSlider Preview function
    function goldsmithVegasSlider() {

        $(".home-slider-vegas-wrapper").each(function (i, el) {
            var myEl       = jQuery(el),
                myVegasId  = myEl.find('.nt-home-slider-vegas').attr('id'),
                myVegas    = $( '#' + myVegasId ),
                myPrev     = myEl.find('.vegas-control-prev'),
                myNext     = myEl.find('.vegas-control-next'),
                mySettings = myEl.find('.nt-home-slider-vegas').data('slider-settings'),
                myContent  = myEl.find('.nt-vegas-slide-content'),
                myCounter  = myEl.find('.nt-vegas-slide-counter'),
                myTitle    = myEl.find('.slider_title'),
                myDesc     = myEl.find('.slider_desc'),
                myBtn      = myEl.find('.btn'),
                myCounter  = myEl.find('.nt-vegas-slide-counter');

            myEl.parents('.elementor-widget-agrikon-vegas-slider').removeClass('elementor-invisible');

            if( mySettings.slides.length ) {
                var slides = mySettings.slides,
                    anim   = mySettings.animation ? mySettings.animation : 'kenburns',
                    trans  = mySettings.transition ? mySettings.transition : 'slideLeft',
                    delay  = mySettings.delay ? mySettings.delay : 7000,
                    dur    = mySettings.duration ? mySettings.duration : 2000,
                    autoply= mySettings.autoplay,
                    shuf   = 'yes' == mySettings.shuffle ? true : false,
                    timer  = 'yes' == mySettings.timer ? true : false,
                    over   = 'none' != mySettings.overlay ? true : false;

                myVegas.vegas({
                    autoplay: autoply,
                    delay: delay,
                    timer: timer,
                    shuffle: shuf,
                    animation: anim,
                    transition: trans,
                    transitionDuration: dur,
                    overlay: over,
                    slides: mySettings.slides,
                    init: function (globalSettings) {
                        myContent.eq(0).addClass('active');
                        myTitle.eq(0).addClass('fadeInLeft');
                        myDesc.eq(0).addClass('fadeInLeft');
                        myBtn.eq(0).addClass('fadeInLeft');
                        var total = myContent.size();
                        myCounter.find('.total').html(total);
                    },
                    walk: function (index, slideSettings) {
                        myContent.removeClass('active').eq(index).addClass('active');
                        myTitle.removeClass('fadeInLeft').addClass('fadeOutLeft').eq(index).addClass('fadeInLeft').removeClass('fadeOutLeft');
                        myDesc.removeClass('fadeInLeft').addClass('fadeOutLeft').eq(index).addClass('fadeInLeft').removeClass('fadeOutLeft');
                        myBtn.removeClass('fadeInLeft').addClass('fadeOutLeft').eq(index).addClass('fadeInLeft').removeClass('fadeOutLeft');
                        var current = index +1;
                        myCounter.find('.current').html(current);
                    },
                    end: function (index, slideSettings) {
                    }
                });

                myPrev.on('click', function () {
                    myVegas.vegas('previous');
                });

                myNext.on('click', function () {
                    myVegas.vegas('next');
                });
            }
        });
        // add video support on mobile device for vegas slider
        if( $(".home-slider-vegas-wrapper").length ) {
            $.vegas.isVideoCompatible = function () {
                return true;
            }
        }
    }

   // goldsmithJarallax
    function goldsmithJarallax() {
        var myParallaxs = $('.goldsmith-parallax');
        myParallaxs.each(function (i, el) {

            var myParallax = $(el),
                myData     = myParallax.data('goldsmithParallax');

            if (!myData) {
                return true; // next iteration
            }

             myParallax.jarallax({
                type            : myData.type,
                speed           : myData.speed,
                imgSize         : myData.imgsize,
                imgSrc          : myData.imgsrc,
                disableParallax : myData.mobile ? /iPad|iPhone|iPod|Android/ : null,
                keepImg         : false,
            });
        });
    }

   // goldsmithFixedSection
    function goldsmithFixedSection() {
        var myFixedSection = $( '.goldsmith-section-fixed-yes' );
        if ( myFixedSection.length ) {
            myFixedSection.parents( '[data-elementor-type="section"]' ).addClass( 'goldsmith-section-fixed goldsmith-custom-header' );
            win.on( "scroll", function () {
                var bodyScroll = win.scrollTop();
                if ( bodyScroll > 100 ) {
                    myFixedSection.parents( '[data-elementor-type="section"]' ).addClass( 'section-fixed-active' );
                } else {
                   myFixedSection.parents( '[data-elementor-type="section"]' ).removeClass( 'section-fixed-active' );
                }
            });
        }
    }

    // goldsmithPopup
    function goldsmithPopupTemplate() {
        var myPopups = $('.goldsmith-popup-item');
        myPopups.each(function (i, el) {
            var myPopup = $(el),
                myId    = myPopup.attr('id'),
                myEl    = $('body a[href="#'+myId+'"]' );

            if ( myEl.length ) {
                myEl.addClass('goldsmith-open-popup');
            }
        });

        if ( $(".goldsmith-open-popup").length ) {
            $(".goldsmith-open-popup").magnificPopup({
                type            : 'inline',
                fixedContentPos : false,
                fixedBgPos      : true,
                overflowY       : 'scroll',
                closeBtnInside  : true,
                preloader       : false,
                midClick        : true,
                removalDelay    : 0,
                mainClass       : 'goldsmith-mfp-slide-bottom',
                tClose          : '',
                tLoading        : '<span class="loading-wrapper"><span class="ajax-loading"></span></span>',
                closeMarkup     : '<div title="%title%" class="mfp-close goldsmith-mfp-close"></div>',
                callbacks       : {
                    open : function() {
                        $("html,body").addClass('goldsmith-popup-open');
                        if ( $('.goldsmith-popup-item .goldsmith-slick-slider').length ) {
                            $('.goldsmith-popup-item .goldsmith-slick-slider').each(function () {
                                $(this).slick('refresh');
                            });
                        }
                        $(document.body).trigger('styler_popup_opened');
                    },
                    close : function() {
                        $("html,body").removeClass('goldsmith-popup-open');
                        $(document.body).trigger('styler_popup_closed');
                    }
                }
            });
        }
    }



    /*=============================================
    Theme WooCommerce
    =============================================*/
    /* added_to_cart
    *  updated_cart_totals
    */

    // none elementor page fix some js
    function noneElementorPageFix() {
        if ( !$('body').hasClass('archive') ) {
            return;
        }
        $('[data-widget_type="accordion.default"] .elementor-accordion-item .elementor-tab-title').each(function(e){
            $( this ).on('click',function(e){
                var $this = $( this );
                var $parent = $this.parent();

                $this.toggleClass('elementor-active');
                $parent.find('.elementor-tab-content').slideToggle();
                $parent.siblings().find('.elementor-tab-title').removeClass('elementor-active');
                $parent.siblings().find('.elementor-tab-content').slideUp();
            });
        });
    }

    // goldsmithCf7Form
    function goldsmithCf7Form() {
        $('.goldsmith-cf7-form-wrapper.form_front').each( function(){
            $(this).find('form>*').each( function(index,el){
                $(this).addClass('child-'+index);
            });
        });
    }


    // popupNewsletter
    function popupNewsletter() {
        if ( !$('body').hasClass('newsletter-popup-visible') ) {
            return;
        }

        var expires = $( '.goldsmith-newsletter.goldsmith-open-popup' ).data( 'expires' );
        var delay   = parseFloat( $( '.goldsmith-newsletter.goldsmith-open-popup' ).data( 'delay' ) );
        var once    = $( '.goldsmith-newsletter.goldsmith-open-popup' ).data( 'once' );

        if (typeof Cookies !== 'undefined') {
            if (!( Cookies.get( 'newsletter-popup-visible' ) ) ) {
                $( window ).on( 'load', function() {

                    if ( delay > 1 ) {
                        setTimeout(function(){
                            $('.goldsmith-newsletter.goldsmith-open-popup').trigger( 'click' );
                        },delay);
                    } else {
                        $('.goldsmith-newsletter.goldsmith-open-popup').trigger( 'click' );
                    }
                });
            }

            $(document.body).on('click', ".goldsmith-newsletter .dontshow",function() {
                if ( once == '1' ) {
                    Cookies.set( 'newsletter-popup-visible', 'disable', { expires: expires, path: '/' });
                } else {
                    if ($(this).is(":checked")) {
                        Cookies.set( 'newsletter-popup-visible', 'disable', { expires: expires, path: '/' });
                    } else {
                        Cookies.remove('newsletter-popup-visible');
                    }
                }
            });
            if ( once == '1' ) {
                Cookies.set( 'newsletter-popup-visible', 'disable', { expires: expires, path: '/' });
            }
        }
    }


    function goldsmithLightbox() {
        var myLightboxes = $('[data-goldsmith-lightbox]');
        if (myLightboxes.length) {
            myLightboxes.each(function(i, el) {
                var myLightbox = $(el);
                var myData = myLightbox.data('goldsmithLightbox');
                var myOptions = {};
                if (!myData || !myData.type) {
                    return true; // next iteration
                }
                if (myData.type === 'gallery') {
                    if (!myData.selector) {
                        return true; // next iteration
                    }
                    myOptions = {
                        delegate: myData.selector,
                        type: 'image',
                        gallery: {
                            enabled: true
                        }
                    };
                }
                if (myData.type === 'image') {
                    myOptions = {
                        type: 'image'
                    };
                }
                if (myData.type === 'iframe') {
                    myOptions = {
                        type: 'iframe'
                    };
                }
                if (myData.type === 'inline') {
                    myOptions = {
                        type: 'inline',
                    };
                }
                if (myData.type === 'modal') {
                    myOptions = {
                        type: 'inline',
                        modal: false
                    };
                }
                if (myData.type === 'ajax') {
                    myOptions = {
                        type: 'ajax',
                        overflowY: 'scroll'
                    };
                }
                myLightbox.magnificPopup(myOptions);
            });
        }
    }

    // popupGdpr
    function popupGdpr() {
        if ( !$('body').hasClass('gdpr-popup-visible') ) {
            return;
        }

        var body        = $('body'),
            popup       = $('.site-gdpr'),
            popupClose  = $('.site-gdpr .gdpr-button a'),
            expiresDate = popup.data('expires');

        if ( !( Cookies.get( 'gdpr-popup-visible' ) ) ) {
            setTimeout(function(){
                popup.addClass( 'active' );
            },1000);
        }

        popupClose.on( 'click', function(e) {
            e.preventDefault();
            Cookies.set( 'gdpr-popup-visible', 'disable', { expires: expiresDate, path: '/' });
            popup.removeClass( 'active' );
            $.cookie("ninetheme_gdpr", 'accepted');
        });
    }

    // product list type masonry for mobile
    function masonryInit(winw) {
        var masonry = $('.goldsmith-products.goldsmith-product-list');
        if ( masonry.length && winw <= 1200 ) {
            //set the container that Masonry will be inside of in a var
            var container = document.querySelector('.goldsmith-products.goldsmith-product-list');
            //create empty var msnry
            var msnry;
            // initialize Masonry after all images have loaded
            imagesLoaded( container, function() {
               msnry = new Masonry( container, {
                   itemSelector: '.goldsmith-products.goldsmith-product-list>div.product'
               });
            });
        }
    }

    function goldsmithWcInit() {

        var getAddedProducts = function() {
            var ids = [];
            $('.cart-area .del-icon').each( function(item){
                var id = $(this).data('id');
                if ( ids.indexOf(id) < 0 ) {
                    ids.push(id);
                }
            });

            if ( typeof ids != 'undefined' && ids.length ) {
                for (let i = 0; i < ids.length; i++) {
                    $('.goldsmith-product[data-id="'+ids[i]+'"]').addClass('cart-added');
                    $('.goldsmith-product[data-id="'+ids[i]+'"] .goldsmith-btn').addClass('added');
                }
            } else {
                $('.goldsmith-product').removeClass('cart-added');
                $('.goldsmith-product .goldsmith-btn').removeClass('added');
            }
        }
        getAddedProducts();

        var getRemovedProducts = function() {
            var ids = [];
            $('.cart-area .del-icon').each( function(item){
                var id = $(this).data('id');
                if ( ids.indexOf(id) < 0 ) {
                    ids.push(id);
                }
            });

            if ( typeof ids != 'undefined' && ids.length ) {
                for (let i = 0; i < ids.length; i++) {
                    $('.goldsmith-product:not([data-id="'+ids[i]+'"])').removeClass('cart-added');
                    $('.goldsmith-product:not([data-id="'+ids[i]+'"]) .goldsmith-btn').removeClass('added');
                }
            } else {
                $('.goldsmith-product').removeClass('cart-added');
                $('.goldsmith-product .goldsmith-btn').removeClass('added');
            }
        }
        getRemovedProducts();

        $( document.body ).on( 'added_to_cart', function( event ) {
            $('.goldsmith-product').removeClass('loading');
            $('.goldsmith-cart-hidden').removeClass('loading');
            $( '.product .btn-ajax-start' ).removeClass('btn-ajax-start loading');
            $( '.woocommerce-mini-cart' ).addClass('has-product');
            $('.panel-content .cart-area').removeClass('cart-item-loading');
            getAddedProducts();
        });

        $( document ).on( 'removed_from_cart', function( event ) {
            getRemovedProducts();
        });

        $( '.goldsmith-product .reset' ).on('click', function() {
            var $this = $(this),
                imgs = $this.parents( '.goldsmith-product' ).find('.swiper-slide .product-link');

            imgs.each(function(){
                var $this  = $(this);
                var img    = $this.find('img');
                var imgsrc = $this.data('img');
                setTimeout(function() {
                    img.attr('src', imgsrc );
                }, 500);
            });
        });

        $( '.goldsmith-add-to-cart-btn .ajax_add_to_cart' ).on('click', function() {
            $(this).parent().addClass('added');
            $(this).parents( '.product.purchasable' ).find('.goldsmith-thumb-wrapper').addClass('btn-ajax-start loading');
        });

        $('[data-label-color]').each( function() {
            var $this = $(this);
            var $color = $this.data('label-color');
            $this.css( {'background-color': $color,'border-color': $color } );
        });

        $( document ).on('click','.goldsmith-product .goldsmith-term', function( event ) {
            var $this = $( this ),
                parent = $this.closest( '.goldsmith-product' );
            $this.closest( '.goldsmith-product' ).addClass('added-term');
            parent.find( '.goldsmith-btn' ).append('<span class="loading-wrapper"><span class="ajax-loading"></span></span>');
        });

        $( document ).on('click','.goldsmith-product .reset_variations', function( event ) {
            var $this = $( this );
            $this.closest( '.goldsmith-product' ).removeClass('added-term');
        });

        // sidebar-widget-toggle
        $( document.body ).on('click','.nt-sidebar-widget-toggle', function() {
            var $this = $(this);
            $this.toggleClass('active');
            $this.parents('.nt-sidebar-inner-widget').toggleClass('goldsmith-widget-show goldsmith-widget-hide');
            $this.parent().next().slideToggle('fast');

            if ( $('.nt-sidebar-inner-wrapper .goldsmith-widget-show').length ) {
                $this.parents('.nt-sidebar-inner-wrapper').removeClass('all-closed');
            } else {
                $this.parents('.nt-sidebar-inner-wrapper').addClass('all-closed');
            }
        });


        function goldsmithGallery() {
            if ( $('.gallery_front').length > 0 ){
                const $this     = $('.gallery_front');
                const gallery   = $this.find('.goldsmith-wc-gallery .row');
                const filter    = $this.find('.gallery-menu');
                const filterbtn = $this.find('.gallery-menu span');
                gallery.imagesLoaded(function () {
                    // init Isotope
                    var $grid = gallery.isotope({
                        itemSelector    : '.grid-item',
                        percentPosition : true,
                        masonry         : {
                            columnWidth : '.grid-sizer'
                        }
                    });
                    // filter items on button click
                    filter.on('click', 'span', function () {
                        var filterValue = $(this).attr('data-filter');
                        $grid.isotope({ filter: filterValue });
                    });
                });
                //for menu active class
                filterbtn.on('click', function (event) {
                    $(this).siblings('.active').removeClass('active');
                    $(this).addClass('active');
                    event.preventDefault();
                });
            }
        }

        goldsmithGallery();
        masonryInit(winw);
    }

    function bannerBgVideo(){

        var iframeWrapper      = $('.goldsmith-loop-product-iframe-wrapper'),
            iframeWrapper2     = $('.goldsmith-woo-banner-iframe-wrapper'),
            videoid            = iframeWrapper2.data('goldsmith-bg-video'),
            aspectRatioSetting = iframeWrapper2.find('iframe').data('bg-aspect-ratio');

        if ( iframeWrapper2.hasClass('goldsmith-video-calculate') ) {
            var containerWidth   = iframeWrapper2.outerWidth(),
                containerHeight  = iframeWrapper2.outerHeight(),
                aspectRatioArray = aspectRatioSetting.split(':'),
                aspectRatio      = aspectRatioArray[0] / aspectRatioArray[1],
                ratioWidth       = containerWidth / aspectRatio,
                ratioHeight      = containerHeight * aspectRatio,
                isWidthFixed     = containerWidth / containerHeight > aspectRatio,
                size             = {
                    w: isWidthFixed ? containerWidth : ratioHeight,
                    h: isWidthFixed ? ratioWidth : containerHeight
                };

            iframeWrapper2.find('iframe').css({
                width: size.w + 100,
                height: size.h + 100
            });
        }
        if ( winw <= 1024 && ( iframeWrapper.length || iframeWrapper2.length ) ) {
            var iframe = iframeWrapper.find('iframe');
            if ( iframeWrapper.length ) {
                iframe[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
            }
            if ( iframeWrapper2.hasClass('goldsmith-video-youtube') ) {
                iframe[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
            }
            if ( iframeWrapper2.hasClass('goldsmith-video-vimeo') ) {
                iframe[0].contentWindow.postMessage('{"method":"play"}', '*');
            }
            if ( iframeWrapper.hasClass('goldsmith-video-local') ) {
                iframe.get(0).play();
            }
        }
    }

    // masonry reinit
    $(document.body).on('goldsmith_masonry_init', function() {
        masonryInit(winw);
    });


    doc.ready( function() {
        winw = $(window).outerWidth();
        bodyResize();
        headerSpacerHeight(winw);
        sideMainHeader();
        topMainHeader();
        mobileSlidingMenu();
        mobileHeaderActions();
        goldsmithHeaderCatMenu();
        goldsmithSwiperSlider();
        goldsmithSlickSlider();
        goldsmithVegasSlider();
        goldsmithFixedSection();
        goldsmithPopupTemplate();
        scrollToTopBtnClick();
        noneElementorPageFix();
        popupNewsletter();
        popupGdpr();
        goldsmithCf7Form();
        goldsmithJarallax();
        bannerBgVideo();
        goldsmithLightbox();

        // WooCommerce
        goldsmithWcInit();

        $('.goldsmith-header-bottom-bar .goldsmith-shop-filter-top-area').removeClass('goldsmith-shop-filter-top-area');
        if ( $('.goldsmith-header-content>div').length == 3 ) {
            //$('div.header-top-side').sameSize(true);
        }

        var mobileHeaderHeight = $('.goldsmith-header-mobile-top').height();
        $('.goldsmith-header-mobile-top-height').css('height',mobileHeaderHeight+'px');

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

        if ( typeof elementorFrontend != 'undefined' ) {
            var deviceMode = elementorFrontend.getCurrentDeviceMode();

            $('[data-bg]').each( function(index, el) {
                var $this = $(el);
                var elBg  = $this.data('bg');

                if ( typeof elBg != 'undefined' ) {
                    var desktop = elBg;

                    var widescreen   = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : desktop;
                    var laptop       = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : desktop;
                    var tablet_extra = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : laptop;
                    var tablet       = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : tablet_extra;
                    var mobile_extra = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : tablet;
                    var mobile       = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : mobile_extra;
                    var bgUrl        = mobile;

                    if ( bgUrl ) {
                        $this.css('background-image', 'url(' + bgUrl + ')' );
                    }
                }
            });
        }

    });

    // === window When resize === //
    win.resize( function() {
        winw = $(window).outerWidth();
        bodyResize(winw);
        topMainHeaderResize(winw);
        mobileHeaderResize(winw);
        headerSpacerHeight(winw);
        masonryInit(winw);
        if ( $('.goldsmith-header-content>div').length == 3 ) {
            //$('div.header-top-side').sameSize(true);
        }
        body.addClass("goldsmith-on-resize");
        body.attr("data-goldsmith-resize", winw);

        var mobileHeaderHeight = $('.goldsmith-header-mobile-top').height();
        $('.goldsmith-header-mobile-top-height').css('height',mobileHeaderHeight+'px');

        if ( typeof elementorFrontend != 'undefined' ) {
            var deviceMode = elementorFrontend.getCurrentDeviceMode();

            $('[data-bg-responsive]').each( function(index, el) {
                var $this = $(el);
                var elBg  = $this.data('bg-responsive');

                if ( typeof elBg != 'undefined' ) {
                    var desktop = $(el).data('bg');

                    var widescreen   = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : desktop;
                    var laptop       = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : desktop;
                    var tablet_extra = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : laptop;
                    var tablet       = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : tablet_extra;
                    var mobile_extra = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : tablet;
                    var mobile       = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : mobile_extra;
                    var bgUrl        = mobile;

                    if ( bgUrl ) {
                        $this.css('background-image', 'url(' + bgUrl + ')' );
                    }
                }
            });
        }
    });

    var headerH = $('.has-sticky-header .goldsmith-header-default').height(),
        headerP = $('.has-sticky-header .goldsmith-header-default').position(),
        headerP = typeof headerP != 'undefined' ? headerP.top : 0,
        topbarH = $('.goldsmith-header-top-area').height(),
        offSetH = headerH + topbarH;

    // === window When scroll === //

    win.on("scroll", function () {
        var bodyScroll = win.scrollTop();

        if ( bodyScroll > headerP ) {
            $('.has-sticky-header .goldsmith-header-default').addClass("sticky-start");
        } else {
            $('.has-sticky-header .goldsmith-header-default').removeClass("sticky-start");
        }

        if ( bodyScroll > 0 ) {
            body.addClass("scroll-start");
        } else {
            body.removeClass("scroll-start");
        }

        var filterArea = $('.goldsmith-products-column .goldsmith-before-loop.goldsmith-shop-filter-top-area');

        if ( filterArea.length ) {
            var filterAreaPos = filterArea.offset(),
                topoffset = $('.goldsmith-header-bottom-bar').hasClass('goldsmith-elementor-template') ? 10 : filterAreaPos.top-62;
            if ( bodyScroll > topoffset ) {
                $('.goldsmith-header-bottom-bar').addClass('sticky-filter-active');
            } else {
                $('.goldsmith-header-bottom-bar').removeClass('sticky-filter-active');
            }
        }
        if ( $('.goldsmith-header-mobile-top .goldsmith-header-bottom-bar').length ) {
            var filterAreaPos = filterArea.offset(),
                topoffset = $('.goldsmith-header-bottom-bar').hasClass('goldsmith-elementor-template') ? 10 : filterAreaPos.top-62;
            if ( bodyScroll > topoffset ) {
                $('.goldsmith-header-mobile-top').addClass('filter-active');
            } else {
                $('.goldsmith-header-mobile-top').removeClass('filter-active');
            }
        }
        if ( $('.header-top-area.sticky-template').length ) {
            var headerTopbarH = $('.header-top-area.sticky-template').height();
            if ( bodyScroll > 0 ) {
                $('.goldsmith-header-default,.goldsmith-header-mobile-top').css('top',headerTopbarH);
            } else {
                $('.goldsmith-header-default,.goldsmith-header-mobile-top').removeAttr('style');
            }
        }

        scrollToTopBtnHide();

    });

    // === window When Loading === //
    win.on("load", function () {
        var bodyScroll = win.scrollTop();

        if ( bodyScroll > 10 ) {
            body.addClass("scroll-start");
            $('.has-sticky-header .goldsmith-header-default').addClass("sticky-start");
        } else {
            body.removeClass("scroll-start");
            $('.has-sticky-header .goldsmith-header-default').removeClass("sticky-start");
        }

        if ( $(".preloader").length || $("#nt-preloader").length ) {
            $('.preloader,#nt-preloader').fadeOut(1000);
        }

        body.addClass("page-loaded");

    });

    win.on('orientationchange', function(event) {
        body.addClass("goldsmith-orientation-changed");

        win.height() > win.width() ? body.removeClass("goldsmith-portrait").addClass("goldsmith-landscape") : body.removeClass("goldsmith-landscape").addClass("goldsmith-portrait");

    });

})(window, document, jQuery);
