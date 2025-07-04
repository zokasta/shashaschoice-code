'use strict';

window.goldsmith = {};

(
function(goldsmith, $) {
    goldsmith = goldsmith || {};

    $.extend(goldsmith, {
        Swatches: {
            init: function() {
                var $term = $('.goldsmith-term'),
                $active_term = $('.goldsmith-term:not(.goldsmith-disabled)');

                // load default value
                $term.each(function() {
                    var $this       = $(this),
                        term        = $this.attr('data-term'),
                        attr        = $this.closest('.goldsmith-terms').attr('data-attribute'),
                        $select_box = $this.closest('.goldsmith-terms').parent().find('select#' + attr),
                        val         = $select_box.val();

                    if ( val != '' && term == val ) {
                        $(this).addClass('goldsmith-selected').find('input[type="radio"]').prop('checked', true);
                    }
                });

                $active_term.unbind('click touch').on('click touch', function(e) {
                    var $this       = $(this),
                        term        = $this.attr('data-term'),
                        title       = $this.attr('title'),
                        attr        = $this.closest('.goldsmith-terms').attr('data-attribute'),
                        $select_box = $this.closest('.goldsmith-terms').parent().find('select#' + attr);

                    if ( $this.hasClass('goldsmith-disabled') ) {
                        return false;
                    }

                    if ( !$this.hasClass('goldsmith-selected') ) {
                        $select_box.val(term).trigger('change');

                        $this.closest('.goldsmith-terms').find('.goldsmith-selected').removeClass('goldsmith-selected').find('input[type="radio"]').prop('checked', false);

                        $this.addClass('goldsmith-selected').find('input[type="radio"]').prop('checked', true);

                        $(document).trigger('goldsmith_selected', [attr, term, title]);
                    }

                    e.preventDefault();
                });

                $(document).on('woocommerce_update_variation_values', function(e) {
                    $(e['target']).find('select').each(function() {
                        var $this = $(this);
                        var $terms = $this.parent().parent().find('.goldsmith-terms');

                        $terms.find('.goldsmith-term').removeClass('goldsmith-enabled').addClass('goldsmith-disabled');

                        $this.find('option.enabled').each(function() {
                            var val = $(this).val();

                            $terms.find('.goldsmith-term[data-term="' + val + '"]').removeClass('goldsmith-disabled').addClass('goldsmith-enabled');
                        });
                    });
                });

                $(document).on('reset_data', function(e) {
                    $(document).trigger('goldsmith_reset');
                    var $this = $(e['target']);

                    $this.find('.goldsmith-selected').removeClass('goldsmith-selected').find('input[type="radio"]').prop('checked', false);

                    $this.find('select').each(function() {
                        var attr = $(this).attr('id');
                        var title = $(this).find('option:selected').text();
                        var term = $(this).val();

                        if ( term != '' ) {
                            $(this).parent().parent().
                            find('.goldsmith-term[data-term="' + term + '"]').
                            addClass('goldsmith-selected').find('input[type="radio"]').
                            prop('checked', true);

                            $(document).trigger('goldsmith_reset', [attr, term, title]);
                        }
                    });
                });
            }
        }
    });

}).apply(this, [window.goldsmith, jQuery]);

(
function(goldsmith, $) {

    $(document).on('wc_variation_form', function() {
        if ( typeof goldsmith.Swatches !== 'undefined' ) {
            goldsmith.Swatches.init();
        }
    });
    $(document.body).on('goldsmith_variations_init', function() {
        if ( typeof goldsmith.Swatches !== 'undefined' ) {
            goldsmith.Swatches.init();
        }
        $('.goldsmith-products-wrapper .variations_form').each(function () {
            $(this).wc_variation_form();
        });
    });

    $(document).on('found_variation', function(e, t) {
        if ( $(e['target']).closest('.goldsmith-loop-swatches').length ) {
            var $product  = $(e['target']).closest('.goldsmith-product-loop-inner'),
                $atc      = $product.find('.goldsmith-product-cart'),
                $image    = $product.find('.goldsmith-product-thumb img'),
                $price    = $product.find('.price');

            if ( $atc.length ) {
                $atc.addClass('goldsmith_swatches_add_to_cart').removeClass('goldsmith-quick-shop-btn').attr('data-variation_id', t['variation_id']).attr('data-product_sku', t['sku']);

                if ( !t['is_purchasable'] || !t['is_in_stock'] ) {
                    $atc.addClass('disabled wc-variation-is-unavailable');
                } else {
                    $atc.removeClass('disabled wc-variation-is-unavailable');
                }

                $atc.removeClass('added error loading');
            }

            $product.find('a.added_to_cart').remove();
            $product.find('.goldsmith-reset-variations').addClass('active');

            // add to cart button text
            if ( $atc.length ) {
                $atc.text(goldsmith_vars.addto);
            }

            // product image
            if ( $image.length ) {

                if ( $image.attr('data-src') == undefined ) {
                    $image.attr('data-src', $image.attr('src'));
                }

                if ( t['image']['thumb_src'] != undefined && t['image']['thumb_src'] != '' ) {
                    $image.attr('src', t['image']['thumb_src']);
                } else {
                    if ( t['image']['src'] != undefined && t['image']['src'] != '' ) {
                        $image.attr('src', t['image']['src']);
                    }
                }
            }

            // product price
            if ( $price.length ) {
                if ( $price.attr('data-price') == undefined ) {
                    $price.attr('data-price', $price.html());
                }

                if ( t['price_html'] ) {
                    $price.html( t['price_html'] );
                }
            }

            $(document).trigger('goldsmith_archive_found_variation', [t]);
        }
    });

    $(document).on('reset_data', function(e) {
        if ( $(e['target']).closest('.goldsmith-loop-swatches').length ) {
            var $product  = $(e['target']).closest('.goldsmith-product-loop-inner'),
                $atc      = $product.find('.goldsmith-product-cart'),
                $otitle   = $atc.data('otitle'),
                $oclass   = $atc.data('oclass'),
                $image    = $product.find('img'),
                $price    = $product.find('.price');

                if ( $atc.length ) {
                    $atc.attr('class',$oclass).attr('data-variation_id', '0').attr('data-product_sku', '');
                    $product.removeClass('added error loading');
                }

                $product.find('a.added_to_cart').remove();
                $product.find('.goldsmith-reset-variations').removeClass('active');

                // add to cart button text
                if ( $atc.length ) {
                    $atc.text($otitle);
                }

                // product image
                if ( $image.length ) {
                    $image.attr('src', $image.attr('data-src'));
                    $image.attr('srcset', $image.attr('data-srcset'));
                    $image.attr('sizes', $image.attr('data-sizes'));
                }

                // product price
                if ( $price.length ) {
                    $price.html($price.attr('data-price'));
                }

                $(document).trigger('goldsmith_archive_reset_data');
            }
        });

        $(document).on('click touch', '.goldsmith_swatches_add_to_cart', function(e) {
            e.preventDefault();
            var $this = $(this);
            var $product = $this.closest('.goldsmith-product-loop-inner');
            var attributes = {};

            $product.removeClass('added error').addClass('loading');

            if ($product.length) {
                $product.find('a.added_to_cart').remove();

                $product.find('[name^="attribute"]').each(function() {
                    attributes[$(this).attr('data-attribute_name')] = $(this).val();
                });

                var data = {
                    action       : 'goldsmith_swatches_add_to_cart',
                    nonce        : goldsmith_vars.security,
                    product_id   : $this.attr('data-product_id'),
                    variation_id : $this.attr('data-variation_id'),
                    quantity     : $this.attr('data-quantity'),
                    attributes   : JSON.stringify(attributes),
                };

                $.post(goldsmith_vars.ajax_url, data, function(response) {
                    if (response) {
                        $product.removeClass('loading').addClass('added');

                        var fragments = response.fragments;
                        var appended  = '<div class="woocommerce-notices-wrapper">'+fragments.notices+'</div>';
                        var duration  = goldsmith_vars.duration;

                        $(appended).prependTo('.goldsmith-shop-popup-notices').delay(duration).fadeOut(300, function(){
                            $(this).remove();
                        });

                        // update other areas
                        $('.goldsmith-minicart').replaceWith(fragments.minicart);
                        $('.goldsmith-cart-count').html(fragments.count);
                        $('.goldsmith-side-panel').attr('data-cart-count',fragments.count);
                        $('.goldsmith-cart-total:not(.page-total)').html(fragments.total);
                        if ( $('.goldsmith-cart-goal-text').length>0 ) {
                            $('.goldsmith-cart-goal-text').html(fragments.shipping.message);
                            $('.goldsmith-progress-bar').css('width',fragments.shipping.value+'%');
                            if ( fragments.shipping.value >= 100 ) {
                                $('.goldsmith-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                            }
                        }

                        // Redirect to cart option
                        if ( goldsmith_vars.cart_redirect === 'yes' ) {
                            window.location = goldsmith_vars.cart_url;
                            return;
                        }

                        if ( goldsmith_vars.minicart_open === 'yes' ) {
                            $('html,body').addClass('goldsmith-overlay-open');
                            $('.goldsmith-side-panel,.panel-content .cart-area').addClass('active');
                        }

                        if ( goldsmith_vars.is_checkout == 'yes' ){
                            location.reload(); // page reload
                        }

                        $(document.body).trigger('added_to_cart').trigger('wc_fragment_refresh');
                    } else {
                        $product.removeClass('loading').addClass('error');
                    }
                });
            }
        });

    }
).apply(this, [window.goldsmith, jQuery]);
