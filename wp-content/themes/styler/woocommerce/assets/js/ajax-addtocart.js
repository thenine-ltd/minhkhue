jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';

    // AJax single add to cart
    if ( styler_vars.product_type === 'woo' ) {

        $(document.body).on('added_to_cart', function(data,fragments, cart_hash, btn){
            $.ajax({
                url        : styler_vars.wc_ajax_url.toString().replace( '%%endpoint%%', 'styler_ajax_add_to_cart' ),
                data       : data,
                type       : 'POST',
                processData: false,
                contentType: false,
                dataType   : 'json',
                success    : function( response ) {

                    var duration = styler_vars.duration;

                    btn.removeClass('loading');

                    var fragments = response.fragments;
                    var appended  = '<div class="woocommerce-notices-wrapper">'+fragments.notices+'</div>';

                    $(appended).prependTo('.styler-shop-popup-notices').delay(duration).fadeOut(300, function(){
                        $(this).remove();
                    });

                    // update other areas
                    $('.minicart-panel').replaceWith(fragments.minicart);
                    $('.styler-cart-count').html(fragments.count);
                    $('.styler-side-panel').attr('data-cart-count',fragments.count);
                    $('.styler-cart-total:not(.page-total)').html(fragments.total);

                    if ( $('.styler-cart-goal-text').length>0 ) {
                        $('.styler-cart-goal-text').html(fragments.shipping.message);
                        $('.styler-progress-bar').css('width',fragments.shipping.value+'%');
                        if ( fragments.shipping.value >= 100 ) {
                            $('.styler-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                        }
                    }

                    $(document.body).trigger('styler_update_minicart');

                    if ( styler_vars.minicart_open === 'yes' ) {
                        $('html,body').addClass('styler-overlay-open');
                        $('.styler-side-panel,.panel-content .cart-area').addClass('active');
                    }
                },
                error: function() {
                    $( document.body ).trigger( 'wc_fragments_ajax_error' );
                }
            });
        });
    }

    if ( styler_vars.product_ajax != 'no' ) {
        $('body').on('submit', '.styler-product-summary form.cart', function(e) {

            if ( $(this).hasClass('product-type-external') || $(e.originalEvent.submitter).hasClass('styler-btn-buynow') ) {
                return;
            }

            e.preventDefault();

            var form = $(this),
                btn  = form.find('.styler-btn.single_add_to_cart_button'),
                val  = form.find('[name=add-to-cart]').val(),
                data = new FormData(form[0]);

            btn.addClass('loading');

            data.append('add-to-cart', val );

            // Ajax action.
            $.ajax({
                url         : styler_vars.wc_ajax_url.toString().replace( '%%endpoint%%', 'styler_ajax_add_to_cart' ),
                data        : data,
                type        : 'POST',
                processData : false,
                contentType : false,
                dataType    : 'json',
                complete    : function( response ) {

                    btn.removeClass('loading');

                    response = response.responseJSON;
                    var fragments = response.fragments;
                    var duration  = styler_vars.duration;
                    var appended  = '<div class="woocommerce-notices-wrapper">'+fragments.notices+'</div>';

                    if ( fragments.notices.indexOf('woocommerce-error') > -1 ) {

                        btn.addClass('disabled');
                        $(appended).prependTo('.styler-shop-popup-notices').delay(duration).fadeOut(300, function(){
                            $(this).remove();
                        });

                    } else {

                        if ( $('.styler-side-panel').length>0 && styler_vars.minicart_open === 'yes' ) {
                            if ( !(form.parents('.styler-quickshop-wrapper').length>0) ) {
                                $('html,body').addClass('styler-overlay-open');
                                $('.panel-header-actions .active,.panel-content .active').removeClass('active');
                                $('.styler-side-panel,.panel-content .cart-area').addClass('active');
                                $('.styler-header-overlay').addClass('close-cursor');
                            }
                        }

                        if ( $('.styler-shop-popup-notices .woocommerce-notices-wrapper').length>0 ) {
                            $('.styler-shop-popup-notices .woocommerce-notices-wrapper').remove();
                            $(appended).prependTo('.styler-shop-popup-notices').delay(duration).fadeOut(300, function(){
                                $(this).remove();
                            });
                        } else {
                            $(appended).prependTo('.styler-shop-popup-notices').delay(duration).fadeOut(300, function(){
                                $(this).remove();
                            });
                        }
                    }

                    // update other areas
                    $('.minicart-panel').replaceWith(fragments.minicart);
                    $('.styler-cart-count').html(fragments.count);
                    $('.styler-side-panel').data('cart-count',fragments.count);
                    $('.styler-cart-total:not(.page-total)').html(fragments.total);

                    if ( $('.styler-cart-goal-wrapper').length>0 ) {
                        $('.styler-cart-goal-text').html(fragments.shipping.message);
                        $('.styler-progress-bar').css('width',fragments.shipping.value+'%');
                        if ( fragments.shipping.value >= 100 ) {
                            $('.styler-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                        } else {
                            $('.styler-cart-goal-wrapper').removeClass('free-shipping-success shakeY');
                        }
                    }

                    $(document.body).trigger('added_to_cart');
                    $(document.body).trigger('styler_update_minicart');
                    // Redirect to cart option
                    if ( styler_vars.cart_redirect === 'yes' ) {
                        window.location = styler_vars.cart_url;
                        return;
                    }
                }
            });
        });
    }

    $('body').on('click', '.styler-btn-buynow', function() {
        if ($(this).parents('form.cart').length) {
            return;
        }
        $('form.cart').find('.styler-btn-buynow').trigger('click');
    });

    // AJax single add to cart
    if ( styler_vars.cart_ajax != 'no' && styler_vars.ajax_addtocart != 'no' ){
        $(document).on('click', '.styler_ajax_add_to_cart', function(e){
            e.preventDefault();

            var btn  = $(this),
                pid  = btn.attr( 'data-product_id' ),
                qty  = parseFloat( btn.data('quantity') ),
                data = new FormData();

            data.append('add-to-cart', pid);

            if ( qty > 0 ) {
                data.append('quantity', qty);
            }

            $(this).parent().addClass('added');
            btn.parents('.styler-product').addClass('loading');

            var lodingHtml = '<span class="loading-wrapper"><span class="ajax-loading"></span></span>';

            if ( btn.closest('.styler-side-panel').length && ( btn.closest('.wishlist-area').length || btn.closest('.compare-area').length ) ) {
                if ( $('.styler-side-panel .cart-empty-content').length ) {
                    $('.styler-side-panel .cart-empty-content').addClass('loading').append(lodingHtml);
                    $('.styler-side-panel [data-name="cart"]').trigger('click');
                } else {
                    $('.styler-side-panel [data-name="cart"]').trigger('click');
                    $('.styler-side-panel .woocommerce-mini-cart').addClass('loading').append(lodingHtml);
                }
            }
            if ( btn.closest('.styler-header-mobile').length && ( btn.closest('.wishlist-area').length || btn.closest('.compare-area').length ) ) {
                if ( $('.styler-header-mobile .cart-empty-content').length ) {
                    $('.styler-header-mobile .cart-empty-content').addClass('loading').append(lodingHtml);
                    $('.styler-header-mobile [data-name="cart"]').trigger('click');
                } else {
                    $('.styler-header-mobile [data-name="cart"]').trigger('click');
                    $('.styler-header-mobile .woocommerce-mini-cart').addClass('loading').append(lodingHtml);
                }
            }

            // Ajax action.
            $.ajax({
                url         : styler_vars.wc_ajax_url.toString().replace( '%%endpoint%%', 'styler_ajax_add_to_cart' ),
                data        : data,
                type        : 'POST',
                processData : false,
                contentType : false,
                dataType    : 'json',
                success     : function( response ) {

                    btn.parents('.styler-product').removeClass('loading');

                    if ( ! response ) {
                        return;
                    }

                    var fragments = response.fragments;
                    var duration = styler_vars.duration;
                    var appended  = '<div class="woocommerce-notices-wrapper">'+fragments.notices+'</div>';

                    $(appended).prependTo('.styler-shop-popup-notices').delay(duration).fadeOut(300, function(){
                        $(this).remove();
                    });

                    // update other areas
                    $('.minicart-panel').replaceWith(fragments.minicart);
                    $('.styler-cart-count').html(fragments.count);
                    $('.styler-side-panel').data('cart-count',fragments.count);
                    $('.styler-cart-total:not(.page-total)').html(fragments.total);

                    if ( $('.styler-cart-goal-wrapper').length>0 ) {
                        $('.styler-cart-goal-text').html(fragments.shipping.message);
                        $('.styler-progress-bar').css('width',fragments.shipping.value+'%');
                        if ( fragments.shipping.value >= 100 ) {
                            $('.styler-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                        } else {
                            $('.styler-cart-goal-wrapper').removeClass('free-shipping-success shakeY');
                        }
                    }

                    if ( $('.styler-side-panel').length>0 && styler_vars.minicart_open === 'yes' ) {
                        if ( !(btn.parents('.styler-quickshop-wrapper').length>0) ) {
                            $('html,body').addClass('styler-overlay-open');
                            $('.panel-header-actions .active,.panel-content .active').removeClass('active');
                            $('.styler-side-panel,.panel-content .cart-area').addClass('active');
                            $('.styler-header-overlay').addClass('close-cursor');
                        }
                    }

                    $(document.body).trigger('added_to_cart');
                    $(document.body).trigger('styler_update_minicart');

                    if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
                        window.location = wc_add_to_cart_params.cart_url;
                        return;
                    }

                    if ( response.error && response.product_url ) {
                        window.location = response.product_url;
                        return;
                    }
                }
            });
        });
    }

    $(document).on('click', '.styler_remove_from_cart_button', function(e){
        e.preventDefault();

        var $this = $(this),
            pid   = $this.data('product_id'),
            note  = styler_vars.removed,
            cart  = $this.closest('.styler-minicart'),
            row   = $this.closest('.styler-cart-item'),
            key   = $this.data( 'cart_item_key' ),
            name  = $this.data('name'),
            qty   = $this.data('qty'),
            msg   = qty ? qty+' &times '+name+' '+note : name+' '+note,
            btn   = $('.styler_ajax_add_to_cart[data-product_id="'+pid+'"]');

            msg   = '<div class="woocommerce-notices-wrapper"><div class="woocommerce-message">'+msg+'</div></div>';

        var duration = styler_vars.duration;

        $(msg).appendTo('.styler-shop-popup-notices').delay(duration).fadeOut(300, function(){
            $(this).remove();
        });

        cart.addClass('loading');

        row.remove();

        var cartItems = cart.find('.mini-cart-item').length;

        if ( cartItems == 0 ) {
            cart.addClass('no-products');
        }

        $.ajax({
            url      : styler_vars.wc_ajax_url.toString().replace( '%%endpoint%%', 'styler_remove_from_cart' ),
            type     : 'POST',
            dataType : 'json',
            data     : {
                cart_item_key : key
            },
            success  : function( response ){
                var fragments = response.fragments;

                $('.minicart-panel').replaceWith(fragments.minicart);
                $('.styler-cart-count').html(fragments.count);
                $('.styler-side-panel').attr('data-cart-count',fragments.count);
                $('.styler-cart-total:not(.page-total)').html(fragments.total);

                cart.removeClass('loading no-products');

                if ( $('.styler-cart-goal-wrapper').length>0 ) {
                    $('.styler-cart-goal-text').html(fragments.shipping.message);
                    $('.styler-progress-bar').css('width',fragments.shipping.value+'%');
                    if ( fragments.shipping.value >= 100 ) {
                        $('.styler-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                    } else {
                        $('.styler-cart-goal-wrapper').removeClass('free-shipping-success shakeY');
                    }
                }

                $(document.body).trigger( 'removed_from_cart', [ fragments, response.cart_hash, btn ] );
                $(document.body).trigger('styler_update_minicart');


				if ( styler_vars.is_cart == 'yes' && fragments.count != 0  ) {
					location.reload(); // page reload
				}

                if ( styler_vars.is_cart == 'yes' && fragments.count == 0 ) {
                    location.reload(); // page reload
                }

                if ( styler_vars.is_checkout == 'yes' && fragments.count == 0 ){
                    location.reload(); // page reload
                }
            },
            error: function() {
                $( document.body ).trigger( 'wc_fragments_ajax_error' );
            }
        });
    });

    $(document).on('updated_wc_div', function() {
        if ( styler_vars.is_cart == 'yes' ) {
            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'styler_ajax_add_to_cart' ),
                type: 'POST',
                data: {
                    action: 'styler_ajax_add_to_cart'
                },
                success: function(response) {

                    var fragments = response.fragments;

                    $('.minicart-panel').replaceWith(fragments.minicart);
                    $('.styler-cart-count').html(fragments.count);
                    $('.styler-side-panel').data('cart-count',fragments.count);
                    $('.styler-cart-total:not(.page-total)').html(fragments.total);

                    if ( $('.styler-cart-goal-wrapper').length>0 ) {
                        $('.styler-cart-goal-text').html(fragments.shipping.message);
                        $('.styler-progress-bar').css('width',fragments.shipping.value+'%');
                        if ( fragments.shipping.value >= 100 ) {
                            $('.styler-cart-goal-wrapper').addClass('free-shipping-success shakeY');
                        } else {
                            $('.styler-cart-goal-wrapper').removeClass('free-shipping-success shakeY');
                        }
                    }
                    $(document.body).trigger('styler_update_minicart');
                }
            });
        }
    });

    function checkCartItems() {
        var ids = [];
        $('.cart-area .del-icon').each( function(item){
            var id = $(this).data('id');
            if ( ids.indexOf(id) < 0 ) {
                ids.push(id);
            }
        });

        if ( typeof ids != 'undefined' && ids.length ) {
            for (let i = 0; i < ids.length; i++) {
                $('.styler-product[data-id="'+ids[i]+'"]').addClass('cart-added');
                $('.styler-product[data-id="'+ids[i]+'"] .styler-btn').addClass('added');
            }
        } else {
            $('.styler-product').removeClass('cart-added');
            $('.styler-product .styler-btn').removeClass('added');
        }
    }

    $( document.body ).on( 'added_to_cart removed_from_cart', function( event ) {
        checkCartItems();
    });

});
