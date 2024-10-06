(function(window, document, $) {

"use strict";

jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';
    var doc         = $(document),
        win         = $(window),
        body        = $('body'),
        winw        = $(window).outerWidth(),
        scrollOffset = $('.styler-header-default').height();

    if ( $('body').hasClass('admin-bar') ) {
        scrollOffset = scrollOffset + 32;
    }

    var scrollToTopSidebar = function() {
        var shopP = 30;

        if ( $('body').hasClass('admin-bar') ) {
            shopP = 32;
        }

        $('html, body').stop().animate({
            scrollTop: $('.shop-area').offset().top - shopP
        }, 400);
    };

    if ( $(".password-protected").length ) {
        var heightFooter = $(".password-protected .styler-elementor-footer").height();
        $(".password-protected .form_password").css('height','calc(100vh - '+heightFooter+'px)');
    }

    var galleryHeight = $(".gallery-col").height();
    var summaryHeight = $(".summary-col .styler-product-summary-inner").height();
    if ( galleryHeight > summaryHeight ) {
        $(".summary-col").addClass('styler-sticky');
    }
    if ( summaryHeight > galleryHeight ) {
        $(".gallery-col").addClass('styler-sticky');
    }

    $(document.body).on('click','.styler-open-fixed-sidebar', function () {
        $('body').addClass('styler-overlay-open');
        $('.nt-sidebar').addClass('active');
    });

    $(document.body).on('click','.styler-close-sidebar', function () {
        $('body').removeClass('styler-overlay-open');
        $('.nt-sidebar').removeClass('active');
    });

    $(document.body).on('click','.styler-toggle-hidden-sidebar', function (e) {
        $('.styler-toggle-hidden-sidebar').toggleClass('active');
        $('.nt-sidebar').toggleClass('active').slideToggle();
        setTimeout(function(){
            scrollToTopSidebar();
        }, 100 );
    });

    $(document.body).on('click','.subDropdown', function (e) {
        if ( $(this).hasClass('active') ) {
            $(this).removeClass('active minus').addClass("plus");
            $(this).next('.children').slideUp('slow');
        } else {
            $(this).removeClass('plus').addClass("active minus");
            $(this).next('.children').slideDown('slow');
        }
    });

    $(document.body).on('click','.styler-shop-popup-notices .close-error', function() {
        $('.styler-shop-popup-notices').removeClass('active');
    });

    // cart shipping form show-hide
    $(document.body).on('click','.styler-shipping-calculator-button', function (e) {
        var cartTotals = $('.styler-cart-totals'),
            form       = $('.shipping-calculator-form');

        if ( cartTotals.hasClass('active')) {
            cartTotals.removeClass('active');
            form.slideUp('slow');
        } else {
            cartTotals.addClass('active');
            form.slideDown('slow');
            setTimeout(function(){
                $('html, body').stop().animate({
                    scrollTop: cartTotals.offset().top - scrollOffset
                }, 400);
            }, 300 );
        }
    });

    $(document.body).on('click','.styler-product .reset', function() {
        var $this = $(this),
            imgs = $this.parents( '.styler-product' ).find('.swiper-slide .product-link');

        imgs.each(function(){
            var $this  = $(this);
            var img    = $this.find('img');
            var imgsrc = $this.data('img');
            setTimeout(function() {
                img.attr('src', imgsrc );
            }, 500);
        });
    });


    $(document.body).on('click','.styler-product .styler-term', function( event ) {
        var $this = $( this ),
            parent = $this.closest( '.styler-product' );
        $this.closest( '.styler-product' ).addClass('added-term');
        parent.find( '.styler-btn' ).append('<span class="loading-wrapper"><span class="ajax-loading"></span></span>');
    });

    $(document.body).on('click','.styler-product .reset_variations', function( event ) {
        var $this = $( this );
        $this.closest( '.styler-product' ).removeClass('added-term');
    });

    // product tabs
    $(document.body).on('click','.styler-product-tab-title-item', function() {
        var id = $(this).data('id');
        $('.styler-product-tabs-wrapper div[data-id="'+id+'"]').addClass('active');
        $('.styler-product-tabs-wrapper div:not([data-id="'+id+'"])').removeClass('active');
    });

    // product summary accordion tabs
    $(document.body).on('click','.cr-qna-link', function() {
        var name  = 'accordion';
        var offset  = 32;
        if ($('.styler-product-tabs-wrapper').length) {
            name  = 'tabs';
            offset = 0;
        }
        var target = $('.styler-product-'+name+'-wrapper').position();

        $('html,body').stop().animate({
            scrollTop: target.top + offset
        }, 1500);
        if ( $('[data-id="accordion-cr_qna"]').parent().hasClass('active') ) {
            return;
        } else {
            setTimeout(function(){
                $('[data-id="accordion-cr_qna"]').trigger('click');
            }, 700);
        }
        if ( $('[data-id="tab-cr_qna"]').hasClass('active') ) {
            return;
        } else {
            setTimeout(function(){
                $('[data-id="tab-cr_qna"]').trigger('click');
            }, 700);
        }
    });

    $(document.body).on('click','.styler-product-summary .woocommerce-review-link', function() {
        var target = $('.nt-woo-single #reviews').position();
        if ($('.styler-product-tabs-wrapper').length) {
            target = $('.nt-woo-single .styler-product-tabs-wrapper').position();
        }
        $('html,body').stop().animate({
            scrollTop: target.top
        }, 1500);

        if ( $('[data-id="tab-reviews"]').hasClass('active') ) {
            return;
        } else {
            setTimeout(function(){
                $('[data-id="tab-reviews"]').trigger('click');
            }, 700);
        }
    });

    // product summary accordion tabs
    $(document.body).on('click','.styler-accordion-header', function() {
        var accordionItem   = $(this),
            accordionParent = accordionItem.parent(),
            accordionHeight = accordionItem.outerHeight(),
            headerHeight    = $('body').hasClass('admin-bar') ? 32 : 0,
            totalHeight     = accordionHeight + headerHeight;

        accordionParent.toggleClass('active');
        accordionItem.next('.styler-accordion-body').slideToggle();
        accordionParent.siblings().removeClass('active').find('.styler-accordion-body').slideUp();
    });

    // product summary accordion tabs
    $(document.body).on('click','.nt-sidebar-widget-toggle', function() {
        var $this = $(this);
        $this.toggleClass('active');
        $this.parents('.nt-sidebar-inner-widget').toggleClass('styler-widget-show styler-widget-hide');
        $this.parent().next().slideToggle('fast');

        if ( $('.nt-sidebar-inner-wrapper .styler-widget-show').length ) {
            $this.parents('.nt-sidebar-inner-wrapper').removeClass('all-closed');
        } else {
            $this.parents('.nt-sidebar-inner-wrapper').addClass('all-closed');
        }
    });

    if ( $('.styler-selected-variations-terms-wrapper').length > 0 ) {
        $('form.variations_form').on('change', function( event, data ){
            var $this = $(this);
            var selectedterms = '';
            $this.find('.styler-variations-items select').each(function(){
                var title = $(this).parents('.styler-variations-items').find('.styler-small-title').text();
                var val   = $(this).val();
                var val2  = $(this).find('option[value="'+val+'"]').html();
                if (val) {
                    selectedterms += '<span class="selected-features">'+title+': '+val2+'</span>';
                }
            });
            if (selectedterms){
                $('.styler-selected-variations-terms-wrapper').slideDown().find('.styler-selected-variations-terms').html(selectedterms);
                $('.styler-select-variations-terms-title').slideUp();
            }
        });
        $('.styler-btn-reset.reset_variations').on('click', function() {
            $('.styler-selected-variations-terms-wrapper').slideUp();
            $('.styler-select-variations-terms-title').slideDown();
        });
    }

    $('[data-label-color]').each( function() {
        var $this = $(this);
        var $color = $this.data('label-color');
        $this.css( {'background-color': $color,'border-color': $color } );
    });

    $('.nt-sidebar ul.product-categories li.cat-parent> ul.children').each( function (e) {
        $(this).before('<span class="subDropdown"></span>');
        $(this).slideUp();
    });

    stylerWcProductCats();
    function stylerWcProductCats() {
        $('.widget_styler_product_categories ul.children input[checked]').closest('li.cat-parent').addClass("current-cat");
    }

    if ( window.innerWidth < 1024 ) {
        var columnSize = $('.styler-shop-hidden-top-sidebar').data('column');
        $('.styler-shop-hidden-top-sidebar').removeClass('d-none active').removeAttr('style');
        $('.styler-toggle-hidden-sidebar').removeClass('active');
        $('.styler-shop-hidden-top-sidebar:not(.d-none) .nt-sidebar-inner').removeClass(columnSize).addClass('styler-scrollbar');
    }

    $(window).on('resize', function(){
        var columnSize = $('.styler-shop-hidden-top-sidebar').data('column');
        if ( window.innerWidth >= 1024 ) {
            if ( $('body').hasClass('styler-overlay-open') ) {
                $('body').removeClass('styler-overlay-open');
                $('.styler-shop-hidden-top-sidebar').removeClass('active');
            }
            $('.styler-shop-hidden-top-sidebar').addClass('d-none');
            $('.styler-shop-hidden-top-sidebar .nt-sidebar-inner').addClass(columnSize);
        }
        if ( window.innerWidth < 1024 ) {
            $('.styler-shop-hidden-top-sidebar').removeClass('d-none active').removeAttr('style');
            $('.styler-toggle-hidden-sidebar').removeClass('active');
            $('.styler-shop-hidden-top-sidebar:not(.d-none) .nt-sidebar-inner').removeClass(columnSize).addClass('styler-scrollbar');
        }
    });

    if ( window.innerWidth < 1024 && $(".styler-bottom-mobile-nav").length ) {
        $("body").addClass('has-bottom-fixed-menu');
    }

    if ( $(".styler-product-video-button").length ) {
        $(".styler-product-video-button").magnificPopup({
            type: 'iframe'
        });
    }

    if ( $(".styler-product-stock-progressbar").length ) {
        var percent = $(".styler-product-stock-progressbar").data('stock-percent');
        $(".styler-product-stock-progressbar").css('width',percent);
    }


    if ( typeof styler.Swatches !== 'undefined' ) {
        $('.products-wrapper .variations_form').each(function () {
            $(this).wc_variation_form();
        });
    }

    shopCatsSlider();

    $(document).on('stylerShopInit', function() {
        shopCatsSlider();
        stylerWcProductCats();
    });

    function shopCatsSlider() {

        var product_cats = $('.shop-area .slick-slide.product-category');

        if ( product_cats.length ) {
            product_cats.each(function (i, el) {
                $(this).appendTo('.shop-slider-categories .slick-slider');
            });
            var myContainer = $('.shop-slider-categories');
            var mySlick = $('.slick-slider', myContainer);
            mySlick.not('.slick-initialized').slick({
                autoplay      : false,
                slidesToShow  : 6,
                speed         : 500,
                focusOnSelect : true,
                infinite      : false,
                prevArrow     : '.slide-prev-cats',
                nextArrow     : '.slide-next-cats',
                responsive    : [
                    {
                        breakpoint: 576,
                        settings  : {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 768,
                        settings  : {
                            slidesToShow: 4
                        }
                    },
                    {
                        breakpoint: 992,
                        settings  : {
                            slidesToShow: 5
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings  : {
                            slidesToShow: 6
                        }
                    }
                ]
            });
        }
    }

    var viewingItem = $('.styler-product-view'),
        data        = viewingItem.data('product-view'),
        countView   = viewingItem.find('.styler-view-count'),
        current     = 0,
        change_counter;

    function singleProductFakeView() {

        if ( viewingItem.length ) {
            var min    = data.min,
                max    = data.max,
                delay  = data.delay,
                change = data.change,
                id     = data.id;

            if ( !viewingItem.hasClass( 'inited' ) ) {
                if ( typeof change !== 'undefined' && change ) {
                    clearInterval( change );
                }

                current = $.cookie( 'styler_cpv_' + id );

                if ( typeof current === 'undefined' || !current ) {
                    current = Math.floor(Math.random() * max) + min;
                }

                viewingItem.addClass('inited');

                $.cookie('styler_cpv_' + id, current, { expires: 1 / 24, path: '/'} );

                countView.html( current );
            }

            change_counter = setInterval( function() {
                current    = parseInt( countView.text() );

                if ( !current ) {
                    current = min;
                }

                var pm = Math.floor( Math.random() * 2 );
                var others = Math.floor( Math.random() * change + 1 );
                current = ( pm < 1 && current > others ) ? current - others : current + others;
                $.cookie('styler_cpv_' + id, current, { expires: 1 / 24, path: '/'} );

                countView.html( current );

            }, delay);
        }
    }
    singleProductFakeView();

    $( document.body ).on( 'added_to_cart removed_from_cart updated_cart_totals', function() {
        $(document.body).trigger("update_checkout");
        $(".styler-product-bottom-popup-cart").removeClass('active');
    });

    if ( $('.woocommerce-error').length ) {
        $('.styler-is-required').each(function () {
            if ( typeof styler_vars !== 'undefined' && styler_vars ) {
                var message = styler_vars.required;
                $( this ).addClass('styler-invalid').find( '.styler-form-message' ).html(message);
            }
        });
    }

    var singleCartPos   = $('.styler-product-summary .single_add_to_cart_button').offset();
    var singleCartTop   = $('.styler-product-summary .single_add_to_cart_button').length && $(".styler-product-bottom-popup-cart").length ? singleCartPos.top : 0;
    var singleDocHeight = $(document).height() - 25;

    $(window).on("scroll", function () {

        if ( $(".styler-product-bottom-popup-cart").length && $(".styler-product-summary .single_add_to_cart_button").length ) {

            if ( $(window).scrollTop() > singleCartTop ) {
                $(".styler-product-bottom-popup-cart").addClass('active');
                $("body").addClass('bottom-popup-cart-active');
            } else {
                $(".styler-product-bottom-popup-cart").removeClass('active');
                $("body").removeClass('bottom-popup-cart-active');
            }
            if($(window).scrollTop() + $(window).height() > singleDocHeight ) {
                $(".styler-product-bottom-popup-cart").addClass('relative');
            } else {
                $(".styler-product-bottom-popup-cart").removeClass('relative');
            }
        }
    });

    if ( typeof styler_vars !== 'undefined' && styler_vars ) {
        var colors = styler_vars.swatches;

        $('.woocommerce-widget-layered-nav-list li a').each(function () {
            var $this = $(this);
            var title = $this.html();
            for (var i in colors) {
                if ( title == i ) {
                    var is_white = colors[i] == '#fff' || colors[i] == '#ffffff' ? ' is_white' : '';
                    var color = '<span class="styler-swatches-widget-color-item'+is_white+'" style="background-color: '+colors[i]+';"></span>';
                    $this.prepend(color);
                }
            }
        });
    }

    $(document).on('click touch','.styler-woocommerce-cart-form .product-remove', function(event) {
        $(this).addClass('loading');
    });

    $(document.body).on('styler_update_minicart', function(){
        minicartUpdateHeight();
    });

    minicartUpdateHeight();
    function minicartUpdateHeight(){

        var footerH = $('.minicart-panel .header-cart-footer').outerHeight();
        var headerH = $('.panel-header-wrapper').outerHeight();
        var cartH   = $('.styler-side-panel .cart-content').outerHeight();
        var maxH    = parseFloat(footerH+headerH) + 125;
        var panelH  = $('.styler-side-panel').outerHeight();

        if ( panelH < (cartH+headerH+50) ) {
            $('.minicart-panel .styler-perfect-scrollbar').addClass('overflowed').css('max-height','calc(100vh - '+maxH+'px)');
            $('.styler-header-mobile-content .styler-perfect-scrollbar').css('max-height','calc(100vh - '+(maxH-50)+'px)');
        } else {
            $('.minicart-panel .styler-perfect-scrollbar').removeClass('overflowed');
        }
    }

    /***** compare button fix *****/

    if ( typeof woosc_vars != 'undefined' ) {
        $('.top-action-btn.open-compare-popup').addClass('open-compare-btn');
    }
    if ( $('#woosc-area').length> 0) {
        var woosc = $('#woosc-area').data('count');
        $('.styler-compare-count').html(woosc);
        $('.woosc-bar-item').each(function () {
            var $id = $(this).attr('data-id');
            $('.woosc-btn-icon-only[data-id="'+$id+'"]').addClass('woosc-added added');
        });
    }

    $(document.body).on('woosc_change_count', function(){
        var woosc_count = $('#woosc-area').attr('data-count');
        $('.styler-compare-count').html(woosc_count);
    });

    if ( typeof yith_woocompare != 'undefined' ) {
        function yith_add_query_arg(key, value)
        {
            key = escape(key); value = escape(value);

            var s = document.location.search;
            var kvp = key+"="+value;

            var r = new RegExp("(&|\\?)"+key+"=[^\&]*");

            s = s.replace(r,"$1"+kvp);

            if(!RegExp.$1) {s += (s.length>0 ? '&' : '?') + kvp;};

            //again, do what you will here
            return s;
        }
        $('.top-action-btn.open-compare-popup').on('click', function(e){
            e.preventDefault();
            $('body').trigger('yith_woocompare_open_popup',{ response: yith_add_query_arg('action', yith_woocompare.actionview) + '&iframe=true' });
        });

        $('body').on('yith_woocompare_product_removed yith_woocompare_open_popup', function(){
            var list  = $.cookie('yith_woocompare_list').split(',');
            var str   = list.toString().replace( '[', '' ).replace( ']', '' );
            var arr   = str.split(',');
            var count = arr.length;
            $('.styler-compare-count').html(count);
        });
    }
    /***** compare button fix *****/

    /***** fly cart *****/
    if ( $("#styler-sticky-cart-toggle").length > 0 ) {
        var flyCart   = $("#styler-sticky-cart-toggle");
        var cartCount = $("#styler-sticky-cart-toggle .styler-wc-count").text();
        var duration  = parseFloat(flyCart.data('duration'));

        if ( cartCount != 0 ) {
            flyCart.addClass('active');
        }

        $(document.body).on('added_to_cart removed_from_cart updated_cart_totals', function(){
            var cartCount = $("#styler-sticky-cart-toggle .styler-wc-count").text();
            if ( cartCount != 0 ) {
                flyCart.addClass('active');
            } else {
                flyCart.removeClass('active');
            }
        });

        $(document).on('click', '.add_to_cart_button.product_type_simple', function() {
            if ( $(this).closest('.add_to_cart_inline').length ) {
                return;
            }
            if ( $(this).closest('.styler-quickview-wrapper').length ) {
                var src    = $(this).closest('.styler-quickview-wrapper').find('.swiper-wrapper .swiper-slide:first-child img').attr('src'),
                    pos    = $(this).closest('.styler-quickview-wrapper').find('.swiper-wrapper .swiper-slide:first-child img').offset(),
                    width  = $(this).closest('.styler-quickview-wrapper').find('.swiper-wrapper .swiper-slide:first-child img').width(),
                    endPos = flyCart.offset();
            } else {
                var src    = $(this).closest('.styler-loop-product').find('.product-link img:first-child').attr('src'),
                    pos    = $(this).closest('.styler-loop-product').find('.product-link img:first-child').offset(),
                    width  = $(this).closest('.styler-loop-product').find('.product-link img:first-child').width(),
                    endPos = flyCart.offset();
            }

            $('body').append('<div id="styler-cart-fly"><img src="' + src + '"></div>');

            $('#styler-cart-fly').css({
                'top'   : pos.top + 'px',
                'left'  : pos.left + 'px',
                'width' : width + 'px',
            }).animate({
                opacity : 1,
                top     : endPos.top,
                left    : endPos.left,
                'width' : '60px',
                'height': 'auto',
            }, duration, 'linear', function() {
                var $this = $(this);
                flyCart.addClass('added');
                $this.fadeOut(1000);
                $(this).detach();
            });
        });

        flyCart.on('click', function() {
            $('html,body').addClass('styler-overlay-open');
            $('.styler-side-panel .panel-header-btn[data-name="cart"]').trigger('click');
            $('.styler-side-panel').addClass('active');
        });
    }
    /***** fly cart *****/


    // product list type masonry for mobile
    function masonryInit(winw) {
        var masonry = $('.styler-product-list');
        if ( masonry.length && winw <= 1200 ) {
            //set the container that Masonry will be inside of in a var
            var container = document.querySelector('.styler-products.styler-product-list');
            //create empty var msnry
            var msnry;
            // initialize Masonry after all images have loaded
            imagesLoaded( container, function() {
               msnry = new Masonry( container, {
                   itemSelector: '.styler-product-list>div.product'
               });
            });
        }
    }

    // product summary accordion tabs
    $(document.body).on('styler_masonry_init', function() {
        masonryInit(winw);
    });

    win.resize( function() {
        winw = $(window).outerWidth();
        masonryInit(winw);
    });

    if ($.support.pjax) {
        $(document).on('click', '.elementor-section .ajax-paginate .styler-woocommerce-pagination a', function(event) {
            var id = $(this).closest('.elementor-section').data('id');
            $(this).closest('.ajax-paginate').addClass('loading');
            $.pjax.click(event, {
                container: '[data-id="'+id+'"]',
                renderCallback: function(context, html, afterRender) {
                    var data = $(html).find('[data-id="'+id+'"]');
                    $(context).replaceWith(data);
                }
            });
        });
    }

});

})(window, document, jQuery);
