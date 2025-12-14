jQuery(document).ready(function($) {
    "use strict";

    $(document).on('click', '.goldsmith-load-more', function(event){

        event.preventDefault();
        var loading = $('.goldsmith-load-more').data('title');
        var more    = $('.goldsmith-load-more').text();
        var obj     = $('.shop-data-filters').data('shop-filters');

        var data    = {
            cache      : false,
            action     : 'goldsmith_shop_load_more',
            beforeSend : function() {
                $('.goldsmith-load-more').html(loading).addClass('loading');
            },
            'ajaxurl'      : obj.ajaxurl,
            'current_page' : obj.current_page,
            'max_page'     : obj.max_page,
            'per_page'     : obj.per_page,
            'layered_nav'  : obj.layered_nav,
            'cat_id'       : obj.cat_id,
            'brand_id'     : obj.brand_id,
            'filter_cat'   : obj.filter_cat,
            'filter_brand' : obj.filter_brand,
            'on_sale'      : obj.on_sale,
            'in_stock'     : obj.in_stock,
            'orderby'      : obj.orderby,
            'min_price'    : obj.min_price,
            'max_price'    : obj.max_price,
            'product_style': obj.product_style,
            'column'       : obj.column,
            'no_more'      : obj.no_more,
            'is_search'    : obj.is_search,
            'is_shop'      : obj.is_shop,
            'is_brand'     : obj.is_brand,
            'is_cat'       : obj.is_cat,
            'is_tag'       : obj.is_tag,
            's'            : obj.s
        };
        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post(obj.ajaxurl, data, function(response) {

            $('div.goldsmith-products.row').append(response);

            $('[data-label-color]').each( function() {
                var $this = $(this);
                var $color = $this.data('label-color');
                $this.css( {'background-color': $color,'border-color': $color } );
            });

            obj.current_page++;

            $('.goldsmith-load-more').html(more).removeClass('loading');

            if ( obj.current_page == obj.max_page ) {
                $('.goldsmith-more').remove();
            }

            $(document.body).trigger('goldsmith_quick_shop');
            $('body').trigger('goldsmith_quick_init');
            $(document.body).trigger('goldsmith_variations_init');

        });
    });
});
