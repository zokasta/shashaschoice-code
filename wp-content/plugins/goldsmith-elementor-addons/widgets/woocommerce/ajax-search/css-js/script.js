/**
 * frontend.js
 *
 * @author Your Inspiration Themes
 * @package goldsmith WooCommerce Ajax Search
 * @version 1.1.1
 */

jQuery(function ($) {
    "use strict";

    var el = $('.goldsmith-as'),
        def_loader = ( typeof woocommerce_params != 'undefined' && typeof woocommerce_params.ajax_loader_url != 'undefined' ) ? woocommerce_params.ajax_loader_url : goldsmith_as_params.loading,
        loader_icon = def_loader,
        search_btn = $('.goldsmith-searchsubmit'),
        min_chrs = el.data('min-chars'),
        ajaxurl = goldsmith_as_params.ajax_url;

    if ( ajaxurl.indexOf('?') == -1 ) {
        ajaxurl += '?';
    }

    search_btn.on('click', function () {
        var form = $(this).closest('form');
        if ( form.find('.goldsmith-as').val() == '' ) {
            return false;
        }
        return true;
    });

    if ( el.length == 0 ) el = $('.goldsmith-as');

    el.each(function () {
        var $t = $(this),
            loader = $('.search-icon'),
            form = $(this).closest('form'),
            append_to = (typeof  $t.data('append-to') == 'undefined') ? $t.closest('.goldsmith-asform-container') : $t.closest($t.data('append-to'));
        var escapeRegExChars = function (value) {
            return value.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
        };
        $t.goldsmithautocomplete({
            minChars: min_chrs,
            appendTo: append_to,
            maxHeight: 400,
            triggerSelectOnValidInput: false,
            serviceUrl: ajaxurl + 'action=goldsmith_as_products',
            onSearchStart: function () {
                loader.addClass('loading');
            },
            onSelect: function (suggestion) {
                if (suggestion.id != -1) {
                    window.location.href = suggestion.url;
                }
            },
            onSearchComplete: function ( suggestion ) {
                loader.removeClass('loading');
            },
            formatResult : function (suggestion, currentValue) {
                if ( 'No results' == suggestion.value ) {
                    form.addClass('no-result');
                    return '<strong>'+ suggestion.value +'</strong>';
                } else {
                    form.removeClass('no-result');
                    var pattern = '(' + escapeRegExChars(currentValue) + ')';
                    var val =  suggestion.value.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>');
                    var html = '<img width="50" height="50" src="' + suggestion.img + '"/>';
                        html += '<div class="goldsmith-small-title">';
                        html += '<span class="value">'+ val +'</span>';
                        html += '<span class="goldsmith-price price">'+ suggestion.prc +'</span>';
                        html += '</div>';
                    return html;
                }
            }
        });
    });
});
