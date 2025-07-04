'use strict';

(function ($) {
    $(function () {
        if (!$('.woobt-wrap').length) {
            return;
        }

        $('.woobt-wrap').each(function () {
            woobt_check_position($(this));
            woobt_init($(this));
        });
    });

    $(document).on('woosq_loaded', function () {
        woobt_init($('#woosq-popup').find('.woobt-wrap'));
    });

    $(document).on('woovr_selected', function (e, selected, variations) {
        var $wrap = variations.closest('.woobt-wrap');
        var $products = variations.closest('.woobt-products');
        var $product = variations.closest('.woobt-product');
        var atc_button = $wrap.attr('data-atc-button');
        var id = selected.attr('data-id');
        var sku = selected.attr('data-sku');
        var weight = selected.attr('data-weight');
        var dimensions = selected.attr('data-dimensions');
        var order = $product.attr('data-order');
        var pricing = $products.attr('data-pricing');
        var price_html = selected.attr('data-pricehtml');
        var display_price = selected.attr('data-price');
        var price = selected.attr('data-price');
        var regular_price = selected.attr('data-regular-price');
        var purchasable = selected.attr('data-purchasable');
        var attrs = selected.attr('data-attrs');
        var woobt_image = selected.attr('data-woobt_image');

        if (pricing == 'regular_price') {
            price = regular_price;
        }

        if ($product.length) {
            if (purchasable === 'yes') {
                // change data
                $product.attr('data-id', id);
                $product.attr('data-price', price);
                $product.attr('data-regular-price', regular_price);

                // change image
                if (woobt_image !== undefined && woobt_image !== '') {
                    var $img = $wrap.find('.woobt-img-order-' + order);

                    if ($img.length) {
                        $img.html(woobt_image);
                    }
                } else {
                    var $img = $wrap.find('.woobt-img-order-' + order);

                    if ($img.length) {
                        $img.html($img.attr('data-img'));
                    }
                }

                // change price
                var new_price = $product.attr('data-new-price') ?? '100%';

                $product.find('.woobt-price-ori').hide();

                if (new_price != '100%') {
                    if (isNaN(new_price)) {
                        new_price = price * parseFloat(new_price) / 100;
                    }

                    $product.find('.woobt-price-new').html(woobt_price_html(display_price, new_price)).show();
                } else {
                    $product.find('.woobt-price-new').html(price_html).show();
                }

                // change attributes
                $product.attr('data-attrs', attrs.replace(/\/$/, ''));

                // change separate add to cart
                if ($product.hasClass('woobt-product-this')) {
                    $wrap.attr('data-product-id', id);
                    $wrap.find('.variation_id').attr('value', id);
                }
            } else {
                // reset data
                $product.attr('data-id', 0);
                $product.attr('data-attrs', '');
                $product.attr('data-price', 0);
                $product.attr('data-regular-price', 0);

                // reset price
                $product.find('.woobt-price-ori').show();
                $product.find('.woobt-price-new').html('').hide();

                // reset separate add to cart
                if ($product.hasClass('woobt-product-this')) {
                    $wrap.attr('data-product-id', 0);
                    $wrap.find('.variation_id').attr('value', 0);
                }

                // reset image
                var $img = $wrap.find('.woobt-img-order-' + order);

                if ($img.length) {
                    $img.html($img.attr('data-img'));
                }
            }

            // prevent changing SKU / weight / dimensions
            $('.product_meta .sku').html($wrap.attr('data-product-sku'));
            $('.product_weight, .woocommerce-product-attributes-item--weight .woocommerce-product-attributes-item__value').html($wrap.attr('data-product-weight'));
            $('.product_dimensions, .woocommerce-product-attributes-item--dimensions .woocommerce-product-attributes-item__value').html($wrap.attr('data-product-dimensions'));
        } else {
            var pid = variations.closest('.variations_form').attr('data-product_id');

            $wrap = $('.woobt-wrap-' + pid);
            atc_button = $wrap.attr('data-atc-button');

            if (atc_button === 'main' || atc_button === 'both') {
                $products = $('.woobt-products-' + pid);

                if (id > 0) {
                    $wrap.attr('data-product-id', id);

                    $products.find('.woobt-product-this').attr('data-price', price);
                    $products.find('.woobt-product-this').attr('data-regular-price', regular_price);

                    if (price_html !== '') {
                        // change this price
                        $products.find('.woobt-product-this .woobt-price-ori').hide();
                        $products.find('.woobt-product-this .woobt-price-new').html(price_html).show();
                    }

                    if (woobt_image !== undefined && woobt_image !== '') {
                        // change this image
                        $wrap.find('.woobt-img-order-' + order).html(woobt_image);
                    }

                    $wrap.attr('data-product-sku', sku);
                    $wrap.attr('data-product-weight', weight);
                    $wrap.attr('data-product-dimensions', dimensions);
                } else {
                    // reset
                    $products.find('.woobt-product-this').attr('data-price', 0);
                    $products.find('.woobt-product-this').attr('data-regular-price', 0);

                    $products.find('.woobt-product-this .woobt-price-new').hide();
                    $products.find('.woobt-product-this .woobt-price-ori').show();

                    var $img = $wrap.find('.woobt-img-order-' + order);

                    if ($img.length) {
                        $img.html($img.attr('data-img'));
                    }

                    $wrap.attr('data-product-id', 0);
                    $wrap.attr('data-product-sku', $wrap.attr('data-product-o_sku'));
                    $wrap.attr('data-product-weight', $wrap.attr('data-product-o_weight'));
                    $wrap.attr('data-product-dimensions', $wrap.attr('data-product-o_dimensions'));
                }
            }
        }

        woobt_init($wrap);
    });

    $(document).on('found_variation', function (e, t) {
        var $wrap = $(e['target']).closest('.woobt-wrap');
        var $products = $(e['target']).closest('.woobt-products');
        var $product = $(e['target']).closest('.woobt-product');
        var atc_button = $wrap.attr('data-atc-button');
        var pricing = $products.attr('data-pricing');
        var price_html = t['price_html'];
        var display_price = t['display_price'];
        var display_regular_price = t['display_regular_price'];
        var order = $product.attr('data-order');
        var pid = $(e['target']).closest('.variations_form').attr('data-product_id');

        if (pricing === 'regular_price') {
            display_price = display_regular_price;
        }

        if ($product.length) {
            if ($product.hasClass('woobt-product-together')) {
                var new_price = $product.attr('data-new-price') ?? '100%';

                if (new_price !== '100%') {
                    if (isNaN(new_price)) {
                        new_price = display_price * parseFloat(new_price) / 100;
                    }

                    $product.find('.woobt-price-ori').hide();
                    $product.find('.woobt-price-new').html(woobt_price_html(display_price, new_price)).show();
                } else if (price_html !== '') {
                    $product.find('.woobt-price-ori').hide();
                    $product.find('.woobt-price-new').html(price_html).show();
                }
            } else {
                $wrap.attr('data-product-id', t['variation_id']);

                if (price_html !== '') {
                    $product.find('.woobt-price-ori').hide();
                    $product.find('.woobt-price-new').html(price_html).show();
                }
            }

            $product.attr('data-price', display_price);
            $product.attr('data-regular-price', display_regular_price);

            if (t['is_purchasable'] && t['is_in_stock']) {
                $product.attr('data-id', t['variation_id']);

                if ($product.hasClass('woobt-product-this')) {
                    $wrap.find('.variation_id').attr('value', t['variation_id']);
                }

                // change attributes
                var attrs = {};

                $product.find('select[name^="attribute_"]').each(function () {
                    var attr_name = $(this).attr('name');

                    attrs[attr_name] = $(this).val();
                });

                $product.attr('data-attrs', JSON.stringify(attrs));
            } else {
                $product.attr('data-id', 0);
                $product.attr('data-attrs', '');

                if ($product.hasClass('woobt-product-this')) {
                    $wrap.find('.variation_id').attr('value', 0);
                }
            }

            // change availability
            if (t['availability_html'] && t['availability_html'] !== '') {
                $product.find('.woobt-availability').html(t['availability_html']).show();
            } else {
                $product.find('.woobt-availability').html('').hide();
            }

            // prevent changing SKU / weight / dimensions
            $('.product_meta .sku').html($wrap.attr('data-product-sku'));
            $('.product_weight, .woocommerce-product-attributes-item--weight .woocommerce-product-attributes-item__value').html($wrap.attr('data-product-weight'));
            $('.product_dimensions, .woocommerce-product-attributes-item--dimensions .woocommerce-product-attributes-item__value').html($wrap.attr('data-product-dimensions'));

            if (woobt_vars.change_image === 'no') {
                // prevent changing the main image
                $wrap.find('.variations_form').trigger('reset_image');
                $('form.variations_form').wc_variations_image_update($wrap.data('variation'));
            }

            // change image
            if (t['woobt_image'] !== undefined && t['woobt_image'] !== '') {
                var $img = $wrap.find('.woobt-img-order-' + order);

                if ($img.length) {
                    $img.html(t['woobt_image']);
                }
            } else {
                var $img = $wrap.find('.woobt-img-order-' + order);

                if ($img.length) {
                    $img.html($img.attr('data-img'));
                }
            }

            woobt_init($wrap);
        } else {
            if ($(e['target']).closest('.woosb-product').length || $(e['target']).closest('.woosg-product').length || $(e['target']).closest('.woofs-product').length) {
                return;
            }

            $wrap = $('.woobt-wrap-' + pid);
            atc_button = $wrap.attr('data-atc-button');
            $wrap.data('variation', t);

            if ($wrap.length) {
                if (t['woobt_items'] !== undefined) {
                    if (t['woobt_items'] === 'yes') {
                        var $ids = $('.woobt-ids-' + pid);
                        var $main_btn = $ids.closest('form.cart').find('.single_add_to_cart_button:not(.wpcbn-btn)');
                        var $separate_btn = $ids.closest('.woobt-form').find('.single_add_to_cart_button:not(.wpcbn-btn)');

                        // reset ids
                        $ids.val('');
                        $main_btn.find('.woobt-count').remove();
                        $separate_btn.find('.woobt-count').remove();
                        $wrap.addClass('woobt-loading');

                        var data = {
                            action: 'woobt_get_variation_items',
                            variation_id: t['variation_id'],
                            nonce: woobt_vars.nonce,
                        };

                        $.post(woobt_vars.wc_ajax_url.toString().replace('%%endpoint%%', 'woobt_get_variation_items'), data, function (response) {
                            if (response !== '') {
                                $wrap.addClass('woobt-wrap-variation').html(response);
                            } else {
                                $wrap.removeClass('woobt-wrap-variation').html($wrap.data('variable'));
                            }

                            $wrap.find('.woobt_variations_form').each(function () {
                                $(this).wc_variation_form();
                            });

                            $wrap.removeClass('woobt-loading');
                        });
                    } else {
                        if ($wrap.hasClass('woobt-wrap-variation')) {
                            $wrap.removeClass('woobt-wrap-variation').html($wrap.data('variable'));
                            $wrap.find('.woobt_variations_form').each(function () {
                                $(this).wc_variation_form();
                            });
                        }
                    }
                }

                $wrap.attr('data-product-sku', t['sku']);
                $wrap.attr('data-product-weight', t['weight_html']);
                $wrap.attr('data-product-dimensions', t['dimensions_html']);
                $wrap.attr('data-product-id', t['variation_id']);

                if (atc_button === 'main' || atc_button === 'both') {
                    $products = $('.woobt-products-' + pid);

                    if (t['price_html'] !== '') {
                        $wrap.attr('data-product-price-html', t['price_html']);
                    }

                    if ($products.find('.woobt-product-this').length) {
                        $products.find('.woobt-product-this').attr('data-id', t['variation_id']);
                        $products.find('.woobt-product-this').attr('data-price', display_price);
                        $products.find('.woobt-product-this').attr('data-regular-price', display_regular_price);

                        if (price_html !== '') {
                            // change this price
                            $products.find('.woobt-product-this .woobt-price-ori').hide();
                            $products.find('.woobt-product-this .woobt-price-new').html(price_html).show();
                        }

                        // change this image
                        if (t['woobt_image'] !== undefined && t['woobt_image'] !== '') {
                            var $img_0 = $wrap.find('.woobt-img-order-0');

                            if ($img_0.length) {
                                $img_0.html(t['woobt_image']);
                            }
                        } else {
                            var $img_0 = $wrap.find('.woobt-img-order-0');

                            if ($img_0.length) {
                                $img_0.html($img_0.attr('data-img'));
                            }
                        }
                    }
                }

                woobt_init($wrap);
            }
        }
    });

    $(document).on('reset_data', function (e) {
        var $wrap = $(e['target']).closest('.woobt-wrap');
        var atc_button = $wrap.attr('data-atc-button');
        var $products = $(e['target']).closest('.woobt-products');
        var $product = $(e['target']).closest('.woobt-product');
        var order = $product.attr('data-order');
        var pid = $(e['target']).closest('.variations_form').attr('data-product_id');

        if ($product.length) {
            $product.attr('data-id', 0);
            $product.attr('data-attrs', '');

            // prevent changing the main image
            $('form.variations_form').wc_variations_image_update($wrap.data('variation'));

            // reset stock
            $(e['target']).closest('.variations_form').find('p.stock').remove();

            // reset SKU / weight / dimensions
            $('.product_meta .sku').html($wrap.attr('data-product-sku'));
            $('.product_weight, .woocommerce-product-attributes-item--weight .woocommerce-product-attributes-item__value').html($wrap.attr('data-product-weight'));
            $('.product_dimensions, .woocommerce-product-attributes-item--dimensions .woocommerce-product-attributes-item__value').html($wrap.attr('data-product-dimensions'));

            // reset availability
            $product.find('.woobt-availability').html('').hide();

            // reset price
            $product.find('.woobt-price-new').hide();
            $product.find('.woobt-price-ori').show();

            if ($product.hasClass('woobt-product-this')) {
                $wrap.attr('data-product-id', 0);
            }

            // reset image
            var $img = $wrap.find('.woobt-img-order-' + order);

            if ($img.length) {
                $img.html($img.attr('data-img'));
            }
        } else {
            if ($(e['target']).closest('.woosb-product').length || $(e['target']).closest('.woosg-product').length || $(e['target']).closest('.woofs-product').length) {
                return;
            }

            $wrap = $('.woobt-wrap-' + pid);
            atc_button = $wrap.attr('data-atc-button');

            if ($wrap.length && ($wrap.data('variable') !== undefined)) {
                $wrap.removeClass('woobt-wrap-variation').html($wrap.data('variable'));
            }

            $wrap.find('.woobt_variations_form').each(function () {
                $(this).wc_variation_form();
            });

            $wrap.removeData('variation');

            $wrap.attr('data-product-id', 0);
            $wrap.attr('data-product-sku', $wrap.attr('data-product-o_sku'));
            $wrap.attr('data-product-weight', $wrap.attr('data-product-o_weight'));
            $wrap.attr('data-product-dimensions', $wrap.attr('data-product-o_dimensions'));
            $wrap.attr('data-product-price-html', $wrap.attr('data-product-o_price-html'));

            if (atc_button === 'main' || atc_button === 'both') {
                $products = $('.woobt-products-' + pid);

                // change this price
                $products.find('.woobt-product-this').attr('data-id', 0);

                // reset this image
                var $img_0 = $wrap.find('.woobt-img-order-0');

                if ($img_0.length) {
                    $img_0.html($img_0.attr('data-img'));
                }
            }
        }

        if ($wrap.length) {
            woobt_init($wrap);
        }
    });

    $(document).on('click touch', '.woobt-quantity-input-plus, .woobt-quantity-input-minus', function () {
        // get values
        var $qty = $(this).closest('.woobt-quantity').find('.woobt-qty');

        if (!$qty.length) {
            $qty = $(this).closest('.woobt-quantity').find('.qty');
        }

        var val = parseFloat($qty.val()), max = parseFloat($qty.attr('max')), min = parseFloat($qty.attr('min')),
            step = $qty.attr('step');

        // format values
        if (!val || val === '' || val === 'NaN') {
            val = 0;
        }

        if (max === '' || max === 'NaN') {
            max = '';
        }

        if (min === '' || min === 'NaN') {
            min = 0;
        }

        if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') {
            step = 1;
        } else {
            step = parseFloat(step);
        }

        // change the value
        if ($(this).is('.woobt-quantity-input-plus')) {
            if (max && (max == val || val > max)) {
                $qty.val(max);
            } else {
                $qty.val((val + step).toFixed(woobt_decimal_places(step)));
            }
        } else {
            if (min && (min == val || val < min)) {
                $qty.val(min);
            } else if (val > 0) {
                $qty.val((val - step).toFixed(woobt_decimal_places(step)));
            }
        }

        // trigger change event
        $qty.trigger('change');
    });

    $(document).on('click touch', '.single_add_to_cart_button:not(.wpcbn-btn)', function (e) {
        if ($(this).hasClass('woobt-disabled')) {
            e.preventDefault();
        }
    });

    $(document).on('change', '.woobt-checkbox', function () {
        var $this = $(this);
        var $wrap = $this.closest('.woobt-wrap');
        var selection = $wrap.attr('data-selection');

        if (selection === 'single') {
            $wrap.find('.woobt-checkbox').not('.woobt-checkbox-this').not(this).prop('checked', false);
        }

        $(document).trigger('woobt_checkbox', [$this]);

        woobt_init($wrap);
    });

    $(document).on('change keyup mouseup', '.woobt-this-qty', function () {
        var val = $(this).val();
        var pid = $(this).closest('.woobt-wrap').attr('data-id');
        var $ids = $('.woobt-ids-' + pid);
        var $form = $ids.closest('form.cart').length ? $ids.closest('form.cart') : $ids.closest('.woobt-form');

        $(this).closest('.woobt-product-this').attr('data-qty', val);

        $form.find('input[name="quantity"]').val(val).trigger('change');
    });

    $(document).on('change keyup mouseup', '.woobt-qty, .woobt-quantity .qty', function () {
        var $this = $(this);
        var $wrap = $this.closest('.woobt-wrap');
        var $product = $this.closest('.woobt-product');
        var $checkbox = $product.find('.woobt-checkbox');
        var val = parseFloat($this.val());

        if ($checkbox.prop('checked')) {
            var min = parseFloat($this.attr('min'));
            var max = parseFloat($this.attr('max'));

            if (val < min) {
                $this.val(min);
            }

            if (val > max) {
                $this.val(max);
            }

            $product.attr('data-qty', $this.val());

            woobt_init($wrap);
        }
    });

    $(document).on('change', 'form.cart .qty', function () {
        var $this = $(this);
        var qty = parseFloat($this.val());

        if ($this.hasClass('woobt-qty') || $this.closest('.woobt-quantity').length) {
            return;
        }

        if (!$this.closest('form.cart').find('.woobt-ids').length) {
            return;
        }

        var wrap_id = $this.closest('form.cart').find('.woobt-ids').attr('data-id');
        var $wrap = $('.woobt-wrap-' + wrap_id);
        var $products = $wrap.find('.woobt-products');
        var optional = $products.attr('data-optional');
        var sync_qty = $products.attr('data-sync-qty');

        $products.find('.woobt-product-this').attr('data-qty', qty);

        if ((optional !== 'on') && (sync_qty === 'on')) {
            $products.find('.woobt-product-together').each(function () {
                var _qty = parseFloat($(this).attr('data-o_qty')) * qty;

                $(this).attr('data-qty', _qty);
                $(this).find('.woobt-qty-num .woobt-qty').html(_qty);
            });
        }

        woobt_init($wrap);
    });

    $(document).on('click touch', '.woobt-form .single_add_to_cart_button', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.woobt-form');
        var $wrap = $btn.closest('.woobt-wrap');
        // variable product
        var data = {};
        var attrs = {};

        $btn.removeClass('added').addClass('loading');

        $wrap.find('.woobt-product-this select[name^=attribute]').each(function () {
            var attribute = $(this).attr('name');

            attrs[attribute] = $(this).val();
        });

        data.action = 'woobt_add_all_to_cart';
        data.quantity = $form.find('input[name="quantity"]').val();
        data.product_id = $form.find('input[name="product_id"]').val();
        data.variation_id = $form.find('input[name="variation_id"]').val();
        data.woobt_ids = $form.find('input[name="woobt_ids"]').val();
        data.variation = attrs;
        data.nonce = woobt_vars.nonce;

        $.post(woobt_vars.wc_ajax_url.toString().replace('%%endpoint%%', 'woobt_add_all_to_cart'), data, function (response) {
            if (!response) {
                return;
            }

            if (response.error && response.product_url) {
                window.location = response.product_url;
                return;
            }

            if ((typeof wc_add_to_cart_params !== 'undefined') && (wc_add_to_cart_params.cart_redirect_after_add === 'yes')) {
                window.location = wc_add_to_cart_params.cart_url;
                return;
            }

            $btn.removeClass('loading');
            $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $btn]);
            $(document.body).trigger('woobt_added_to_cart', [response.fragments, response.cart_hash, $btn]);
        });
    });
})(jQuery);

function woobt_init($wrap) {
    // has placeholder for variable product
    if ($wrap.data('variable') === undefined) {
        $wrap.data('variable', $wrap.html());
    }

    if ($wrap.find('.woobt-products').length) {
        woobt_carousel($wrap);
        woobt_check_ready($wrap);
        woobt_calc_price($wrap);
        woobt_save_ids($wrap);

        if (woobt_vars.counter !== 'hide') {
            woobt_update_count($wrap);
        }
    }

    jQuery(document).trigger('woobt_init', [$wrap]);
}

function woobt_carousel($wrap) {
    if ($wrap.find('[class*="woobt-products-layout-carousel"]').length) {
        let slides = 2;
        let carousel = JSON.parse(woobt_vars.carousel_params);
        let $products = $wrap.find('[class*="woobt-products-layout-carousel"]');

        if ($products.hasClass('woobt-products-layout-carousel-2') || $products.hasClass('woobt-products-layout-carousel-3') || $products.hasClass('woobt-products-layout-carousel-4')) {
            if ($products.hasClass('woobt-products-layout-carousel-3')) {
                slides = 3;
            } else if ($products.hasClass('woobt-products-layout-carousel-4')) {
                slides = 4;
            }

            carousel.slidesToShow = slides;
            carousel.slidesToScroll = slides;

            if ($products.find('.woobt-product:not(.woobt-hide-this)').length > slides) {
                if (!$products.hasClass('woobt-carousel')) {
                    $products.addClass('woobt-carousel').slick(carousel);

                    if ($products.find('.woobt-hide-this').length) {
                        $products.slick('slickFilter', '.woobt-product:not(.woobt-hide-this)');
                    }
                }
            } else {
                if ($products.hasClass('woobt-carousel')) {
                    $products.removeClass('woobt-carousel').slick('unslick');
                }

                $products.addClass('woobt-no-carousel');
            }
        }
    }
}

function woobt_check_position($wrap) {
    var pid = $wrap.attr('data-id');
    var position = $wrap.attr('data-position');
    var atc_button = $wrap.attr('data-atc-button');
    var $products = $wrap.find('.woobt-products');
    var $ids = $wrap.parent().find('.woobt-ids-' + pid);

    if ((position === 'before') && (atc_button === 'main' || atc_button === 'both') && (($products.attr('data-product-type') === 'variable') || ($products.attr('data-product-type') === 'variable-subscription')) && ($products.attr('data-variables') === 'no' || woobt_vars.variation_selector === 'woovr')) {
        $wrap.insertAfter($ids);
    }

    jQuery(document).trigger('woobt_check_position', [$wrap]);
}

function woobt_check_ready($wrap) {
    var pid = $wrap.attr('data-id');
    var $products = $wrap.find('.woobt-products');
    var $alert = $wrap.find('.woobt-alert');
    var $ids = jQuery('.woobt-ids-' + pid);
    var $form = $ids.closest('.woobt-form').length ? $ids.closest('.woobt-form') : $ids.closest('form.cart');
    var $btn = $form.find('.single_add_to_cart_button:not(.wpcbn-btn)');
    var is_selection = false;
    var is_empty = true;
    var selection_name = '';

    $products.find('.woobt-product').each(function () {
        var $this = jQuery(this);

        if ($this.hasClass('woobt-hide-this')) {
            // skip
            return true;
        }

        var $images = $this.closest('.woobt-wrap').find('.woobt-images');
        var _checked = $this.find('.woobt-checkbox').prop('checked');
        var _id = parseInt($this.attr('data-id'));
        var _qty = parseInt($this.attr('data-qty'));
        var _order = parseInt($this.attr('data-order'));

        if (!_checked) {
            $this.addClass('woobt-hide');

            if ($images.length) {
                $images.find('.woobt-image-order-' + _order).addClass('woobt-image-hide');
            }
        } else {
            $this.removeClass('woobt-hide');

            if ($images.length) {
                $images.find('.woobt-image-order-' + _order).removeClass('woobt-image-hide');
            }
        }

        if (_checked && (_id === 0) && (_qty > 0)) {
            is_selection = true;

            if (selection_name === '') {
                selection_name = $this.attr('data-name');
            }
        }

        if (!$this.hasClass('woobt-product-this') && _checked && (_id > 0) && (_qty > 0)) {
            is_empty = false;
        }
    });

    if (is_selection) {
        $btn.addClass('woobt-disabled woobt-selection');
        $alert.html(woobt_vars.alert_selection.replace('[name]', '<strong>' + selection_name + '</strong>')).slideDown();
    } else {
        $btn.removeClass('woobt-disabled woobt-selection');
        $alert.html('').slideUp();
    }

    // trigger
    jQuery(document).trigger('woobt_check_ready', [is_empty, is_selection, $wrap]);
}

function woobt_calc_price($wrap) {
    var pid = $wrap.attr('data-id');
    var atc_button = $wrap.attr('data-atc-button');
    var $additional = $wrap.find('.woobt-additional');
    var $total = $wrap.find('.woobt-total');
    var $products = $wrap.find('.woobt-products');
    var $product_this = $products.find('.woobt-product-this');
    var count = 0, total = 0, total_regular = 0;
    var ori_price_suffix = $products.attr('data-product-price-suffix');
    var ori_price = parseFloat($product_this.length ? $product_this.attr('data-price') : 0);
    var ori_price_regular = parseFloat($product_this.length ? $product_this.attr('data-regular-price') : 0);
    var ori_qty = parseFloat($product_this.length ? $product_this.attr('data-qty') : 0);
    var total_ori = ori_price * ori_qty;
    var total_ori_regular = ori_price_regular * ori_qty;
    var $price = jQuery('.woobt-price-' + pid);
    var show_price = $products.attr('data-show-price');
    var fix = Math.pow(10, Number(woobt_vars.price_decimals) + 1);

    if ((woobt_vars.change_price === 'yes_custom') && (woobt_vars.price_selector != null) && (woobt_vars.price_selector !== '')) {
        $price = jQuery(woobt_vars.price_selector);
    }

    $products.find('.woobt-product-together').each(function () {
        var $this = jQuery(this);
        var _checked = $this.find('.woobt-checkbox').prop('checked');
        var _id = parseInt($this.attr('data-id'));
        var _qty = parseFloat($this.attr('data-qty'));
        var _price = $this.attr('data-new-price') ?? '100%';
        var _price_suffix = $this.attr('data-price-suffix');
        var _sale_price = parseFloat($this.attr('data-price'));
        var _regular_price = parseFloat($this.attr('data-regular-price'));
        var _total_ori = 0, _total_ori_regular = 0, _total = 0;

        _total_ori = _qty * _sale_price;
        _total_ori_regular = _qty * _sale_price;

        if (isNaN(_price)) {
            // is percent
            if (_price === '100%') {
                _total_ori = _qty * _regular_price;
                _total_ori_regular = _qty * _regular_price;
                _total = _qty * _sale_price;
            } else {
                _total = _total_ori * parseFloat(_price) / 100;
            }
        } else {
            _total = _qty * parseFloat(_price);
        }

        if (show_price === 'total') {
            $this.find('.woobt-price-ori').hide();
            $this.find('.woobt-price-new').html(woobt_price_html(_total_ori, _total) + _price_suffix).show();
        }

        // calc total
        if (_checked && (_qty > 0) && (_id > 0)) {
            count++;
            total += _total;
            total_regular += _total_ori_regular;
            total_ori_regular += _total_ori_regular;
        }
    });

    total = Math.round(total * fix) / fix;
    total_regular = Math.round(total_regular * fix) / fix;

    if ($product_this.length) {
        var _this_id = parseInt($product_this.attr('data-id'));
        var _this_qty = parseFloat($product_this.attr('data-qty'));

        if (_this_qty > 0 && _this_id > 0) {
            var _this_price_suffix = $product_this.attr('data-price-suffix');
            var _this_sale_price = parseFloat($product_this.attr('data-price'));
            var _this_new_price = _this_sale_price;
            var _this_regular_price = parseFloat($product_this.attr('data-regular-price'));

            if (total > 0 && parseFloat($product_this.attr('data-id')) > 0) {
                var _this_price = $product_this.attr('data-new-price') ?? '100%';

                if (isNaN(_this_price)) {
                    // is percent
                    if (_this_price !== '100%') {
                        _this_new_price = woobt_round(_this_sale_price * parseFloat(_this_price) / 100);
                    }
                } else {
                    _this_new_price = parseFloat(_this_price);
                }

                total_ori = _this_new_price * _this_qty;
            }

            $product_this.find('.woobt-price-ori').hide();

            if (show_price === 'total') {
                $product_this.find('.woobt-price-new').html(woobt_price_html(_this_qty * _this_regular_price, _this_qty * _this_new_price) + _this_price_suffix).show();
            } else {
                $product_this.find('.woobt-price-new').html(woobt_price_html(_this_regular_price, _this_new_price) + _this_price_suffix).show();
            }
        } else {
            $product_this.find('.woobt-price-new').hide();
            $product_this.find('.woobt-price-ori').show();
        }
    }

    if (count > 0) {
        total_ori += total;

        $additional.html(woobt_vars.additional_price_text + ' ' + woobt_price_html(total_regular, total) + ori_price_suffix).slideDown();
        $total.html(woobt_vars.total_price_text + ' ' + woobt_price_html(total_ori_regular, total_ori) + ori_price_suffix).slideDown();
    } else {
        $additional.html('').slideUp();
        $total.html('').slideUp();
    }

    // change the main price
    if ((woobt_vars.change_price !== 'no') && (atc_button !== 'separate')) {
        if (parseInt($wrap.attr('data-product-id')) > 0 && count > 0) {
            $price.html(woobt_price_html(total_ori_regular, total_ori) + ori_price_suffix);
        } else {
            $price.html($wrap.attr('data-product-price-html'));
        }
    }

    if ($wrap.find('.woobt-wrap').length) {
        $wrap.find('.woobt-wrap').attr('data-total', total);
    } else {
        $wrap.attr('data-total', total);
    }

    jQuery(document).trigger('woobt_calc_price', [total, total_ori, total_ori_regular, $wrap]);
}

function woobt_save_ids($wrap) {
    var pid = $wrap.attr('data-id');
    var $products = $wrap.find('.woobt-products');
    var sync_qty = $products.attr('data-sync-qty');
    var $ids = jQuery('.woobt-ids-' + pid).length ? jQuery('.woobt-ids-' + pid) : $wrap.find('.woobt-ids');
    var items = [];

    $products.find('.woobt-product-together').each(function () {
        var $this = jQuery(this);
        var checked = $this.find('.woobt-checkbox').prop('checked');
        var id = parseInt($this.attr('data-id'));
        var key = $this.data('key');
        var qty = parseFloat($this.attr('data-qty'));
        var qty_ori = parseFloat($this.attr('data-o_qty'));
        var attrs = $this.attr('data-attrs');

        if (checked && (qty > 0) && (id > 0)) {
            if (attrs !== undefined) {
                attrs = encodeURIComponent(attrs);
            } else {
                attrs = '';
            }

            if (sync_qty === 'on') {
                items.push(id + '/' + key + '/' + qty_ori + '/' + attrs);
            } else {
                items.push(id + '/' + key + '/' + qty + '/' + attrs);
            }
        }
    });

    if (items.length > 0) {
        $ids.val(items.join(','));
    } else {
        $ids.val('');
    }

    jQuery(document.body).trigger('woobt_save_ids', [items, $wrap]);
}

function woobt_update_count($wrap) {
    var pid = $wrap.attr('data-id');
    var ignore_this = $wrap.attr('data-ignore-this');
    var $products = $wrap.find('.woobt-products');
    var $main_btn = jQuery('.woobt-ids-' + pid).closest('form.cart').find('.single_add_to_cart_button:not(.wpcbn-btn)');
    var $separate_btn = jQuery('.woobt-ids-' + pid).closest('.woobt-form').find('.single_add_to_cart_button:not(.wpcbn-btn)');
    var qty = 0;
    var num = 0;

    $products.find('.woobt-product').each(function () {
        var $this = jQuery(this);
        var _checked = $this.find('.woobt-checkbox').prop('checked');
        var _id = parseInt($this.attr('data-id'));
        var _qty = parseFloat($this.attr('data-qty'));

        if (_checked && (_qty > 0) && (_id > 0)) {
            qty += _qty;
            num++;
        }
    });

    if ((num > 1) || (ignore_this === 'yes' && num > 0)) {
        if (woobt_vars.counter === 'individual') {
            if ($main_btn.find('.woobt-count').length) {
                $main_btn.find('.woobt-count').text(num);
            } else {
                $main_btn.append('<span class="woobt-count">' + num + '</span>');
            }

            if ($separate_btn.find('.woobt-count').length) {
                $separate_btn.find('.woobt-count').text(num);
            } else {
                $separate_btn.append('<span class="woobt-count">' + num + '</span>');
            }
        } else {
            if ($main_btn.find('.woobt-count').length) {
                $main_btn.find('.woobt-count').text(qty);
            } else {
                $main_btn.append('<span class="woobt-count">' + qty + '</span>');
            }

            if ($separate_btn.find('.woobt-count').length) {
                $separate_btn.find('.woobt-count').text(qty);
            } else {
                $separate_btn.append('<span class="woobt-count">' + qty + '</span>');
            }
        }
    } else {
        $main_btn.find('.woobt-count').remove();
        $separate_btn.find('.woobt-count').remove();
    }

    jQuery(document.body).trigger('woobt_update_count', [num, qty, $wrap]);
}

function woobt_format_money(number, places, symbol, thousand, decimal) {
    number = number || 0;
    places = !isNaN(places = Math.abs(places)) ? places : 2;
    symbol = symbol !== undefined ? symbol : '$';
    thousand = thousand !== undefined ? thousand : ',';
    decimal = decimal !== undefined ? decimal : '.';

    var negative = number < 0 ? '-' : '', i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + '', j = 0;

    if (i.length > 3) {
        j = i.length % 3;
    }

    if (woobt_vars.trim_zeros === '1') {
        return symbol + negative + (j ? i.substr(0, j) + thousand : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) + (places && (parseFloat(number) > parseFloat(i)) ? decimal + Math.abs(number - i).toFixed(places).slice(2).replace(/(\d*?[1-9])0+$/g, '$1') : '');
    } else {
        return symbol + negative + (j ? i.substr(0, j) + thousand : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : '');
    }
}

function woobt_format_price(total) {
    var total_html = '<span class="woocommerce-Price-amount amount">';
    var total_formatted = woobt_format_money(total, woobt_vars.price_decimals, '', woobt_vars.price_thousand_separator, woobt_vars.price_decimal_separator);

    switch (woobt_vars.price_format) {
        case '%1$s%2$s':
            // left
            total_html += '<span class="woocommerce-Price-currencySymbol">' + woobt_vars.currency_symbol + '</span>' + total_formatted;
            break;
        case '%1$s %2$s':
            // left with space
            total_html += '<span class="woocommerce-Price-currencySymbol">' + woobt_vars.currency_symbol + '</span> ' + total_formatted;
            break;
        case '%2$s%1$s':
            // right
            total_html += total_formatted + '<span class="woocommerce-Price-currencySymbol">' + woobt_vars.currency_symbol + '</span>';
            break;
        case '%2$s %1$s':
            // right with space
            total_html += total_formatted + ' <span class="woocommerce-Price-currencySymbol">' + woobt_vars.currency_symbol + '</span>';
            break;
        default:
            // default
            total_html += '<span class="woocommerce-Price-currencySymbol">' + woobt_vars.currency_symbol + '</span> ' + total_formatted;
    }

    total_html += '</span>';

    return total_html;
}

function woobt_price_html(regular_price, sale_price) {
    var price_html = '';

    if (parseFloat(woobt_round(sale_price)) < parseFloat(woobt_round(regular_price))) {
        price_html = '<del>' + woobt_format_price(regular_price) + '</del> <ins>' + woobt_format_price(sale_price) + '</ins>';
    } else {
        price_html = woobt_format_price(regular_price);
    }

    return price_html;
}

function woobt_decimal_places(num) {
    var match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);

    if (!match) {
        return 0;
    }

    return Math.max(0, // Number of digits right of decimal point.
        (match[1] ? match[1].length : 0)
        // Adjust for scientific notation.
        - (match[2] ? +match[2] : 0));
}

function woobt_round(value) {
    return Number(Math.round(value + 'e' + woobt_vars.price_decimals) + 'e-' + woobt_vars.price_decimals);
}
