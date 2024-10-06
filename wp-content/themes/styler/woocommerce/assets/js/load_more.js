jQuery(document).ready(function($) {
    "use strict";

    $(document).on('click', '.styler-load-more', function(event){

        event.preventDefault();
        var loading = $('.styler-load-more').data('title');
        var more    = $('.styler-load-more').text();
        var obj     = $('.shop-data-filters').data('shop-filters');
        var data    = {
            cache      : false,
            action     : 'styler_shop_load_more',
            beforeSend : function() {
                $('.styler-load-more').html(loading).addClass('loading');
            },
            'ajaxurl'      : obj.ajaxurl,
            'current_page' : obj.current_page,
            'max_page'     : obj.max_page,
            'per_page'     : obj.per_page,
            'layered_nav'  : obj.layered_nav,
            'cat_id'       : obj.cat_id,
            'tag_id'       : obj.tag_id,
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

            if ( response != '0' ) {
                $('div.styler-products.row').append(response);
            }

            obj.current_page++;
            $('.styler-load-more').html(more).removeClass('loading');

            if ( obj.current_page == obj.max_page ) {
                $('.styler-more').remove();
            }

            $(document.body).trigger('styler_quick_shop');
            $('body').trigger('styler_quick_init');
            $(document.body).trigger('styler_variations_init');

            if ( $('.styler-loop-slider') ) {
                $('.styler-loop-slider:not(.swiper-initialized)').each(function () {
                    const options  = $(this).data('swiper-options');
                    const mySlider = new NTSwiper(this, options);
                });
            }
        });
    });
});
