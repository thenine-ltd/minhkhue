'use strict';

var styler_ids = [],
    styler_products = [];
(function($) {

    jQuery(document).ready(function($) {
        $('.styler-quickview-btn').each(function() {
            var id = $(this).data('id');
            if (-1 === $.inArray(id, styler_ids)) {
                styler_ids.push(id);
                styler_products.push({src: styler_vars.ajax_url + '?product_id=' + id});
            }
        });
    });

    function styler_get_key(array, key, value) {
      for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
          return i;
        }
      }
      return -1;
    }

    jQuery(document).on('decoratyShopInit',function() {
        $('.styler-quickview-btn').each(function() {
            var id = $(this).data('id');
            if (-1 === $.inArray(id, styler_ids)) {
                styler_ids.push(id);
                styler_products.push({src: styler_vars.ajax_url + '?product_id=' + id});
            }
        });
        init(styler_products);
    });
    jQuery(document).on('styler_quick_init',function() {
        $('.styler-quickview-btn').each(function() {
            var id = $(this).data('id');
            if (-1 === $.inArray(id, styler_ids)) {
                styler_ids.push(id);
                styler_products.push({src: styler_vars.ajax_url + '?product_id=' + id});
            }
        });
        init(styler_products);
    });

    init(styler_products);

    function init(styler_products){

        $(document).on('click touch', '.styler-quickview-btn', function(event) {
            event.preventDefault();

            var $this        = $(this),
                id           = $this.data('id'),
                is_quickShop = $this.parents('.styler-loop-product').find('.ninetheme-quick-shop-btn'),
                clicked      = false;

            var index = styler_get_key(styler_products, 'src', styler_vars.ajax_url + '?product_id=' + id);

            jQuery.magnificPopup.open({
                items           : styler_products,
                type            : 'ajax',
                mainClass       : 'mfp-styler-quickview styler-mfp-slide-bottom',
                removalDelay    : 160,
                overflowY       : 'scroll',
                fixedContentPos : true,
                closeBtnInside  :true,
                tClose          : '',
                closeMarkup     : '<div class="mfp-close styler-panel-close-button"></div>',
                tLoading        : '<span class="loading-wrapper"><span class="ajax-loading"></span></span>',
                gallery         : {
                    tPrev   : '',
                    tNext   : '',
                    enabled : true
                },
                ajax            : {
                    settings: {
                        type: 'GET',
                        data: {
                            action: 'styler_quickview'
                        }
                    }
                },
                callbacks      : {
                    beforeOpen: function() {},
                    open: function() {
                        $('.mfp-preloader').addClass('loading');
                    },
                    ajaxContentAdded: function() {
                        $('.mfp-preloader').removeClass('loading');

                        var variations_form = $('.styler-quickview-product-details').find('.variations_form');

                        variations_form.each(function() {
                          $(this).wc_variation_form();
                        });

                        $('body').trigger('styler_lazy_load');

                        jQuery('.ajax_add_to_cart').on('click', function() {
                            setTimeout( function(){
                                $.magnificPopup.close();
                            }, 500);
                        });

                        $( '.template-add-to-cart .ninetheme-quick-shop-btn' ).on('click', function(event) {
                            event.preventDefault();
                            $.magnificPopup.close();
                            clicked = true;
                        });

                        if ( $('.styler-quickview-main img').length > 1) {
                            var galleryThumbs = new NTSwiper('.styler-quickview-thumbnails', {
                                loop          : false,
                                watchOverflow : false,
                                speed         : 1000,
                                spaceBetween  : 10,
                                slidesPerView : 4,
                                navigation    : {
                                    nextEl : '.styler-quickview-main .swiper-button-next',
                                    prevEl : '.styler-quickview-main .swiper-button-prev'
                                }
                            });
                            var galleryTop = new NTSwiper('.styler-quickview-main', {
                                loop         : false,
                                speed        : 1000,
                                slidesPerView: 1,
                                spaceBetween : 0,
                                observer     : true,
                                rewind       : true,
                                navigation   : {
                                    nextEl: '.styler-quickview-main .swiper-button-next',
                                    prevEl: '.styler-quickview-main .swiper-button-prev'
                                },
                                thumbs       : {
                                    swiper : galleryThumbs
                                }
                            });
                        }
                    },
                    close: function(){},
                    afterClose: function(){

                        if ( is_quickShop.length > 0 && clicked == true ) {
                            $('.styler-loop-product .ninetheme-quick-shop-btn[data-product_id="'+id+'"]').off('click').trigger('click');
                        }
                    }
                }
            },index);
        });
    }
})(jQuery);
