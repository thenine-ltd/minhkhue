/*-----------------------------------------------------------------------------------

    Theme Name: Styler
    Description: WordPress Theme
    Author: Ninetheme
    Author URI: https://ninetheme.com/
    Version: 1.0

-----------------------------------------------------------------------------------*/

//var wishlist_vars = {};
"use strict";

(function(window, document, $) {

    function set_Cookie(cname, cvalue, exdays) {
        var d = new Date();

        d.setTime(d.getTime() + (
            exdays * 24 * 60 * 60 * 1000
        ));

        var expires = 'expires=' + d.toUTCString();

        document.cookie = cname + '=' + cvalue + '; ' + expires + '; path=/';
    }

    function get_Cookie(cname) {
        var name = cname + '=';
        var ca = document.cookie.split(';');

        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];

            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }

            if (c.indexOf(name) == 0) {
                return decodeURIComponent(c.substring(name.length, c.length));
            }
        }

        return '';
    }

    function getProducts() {
        var cookie = 'styler_products',
            cookie = compare_vars.user_id != '' ? 'styler_products_' + compare_vars.user_id : '';

        return get_Cookie( cookie ) != '' ? get_Cookie( cookie ) : '';
    }

    function addProduct( id ) {
        var cookie = 'styler_products',
            count,
            limit  = false,
            notice = compare_vars.notice,
            btn    = $('.styler-compare-btn[data-id="' + id + '"]');

        if ( compare_vars.user_id != '' ) {
            cookie = 'styler_products_' + compare_vars.user_id;
        }

        if ( get_Cookie( cookie ) != '' ) {
            var products = get_Cookie( cookie ).split(',');

            if ( products.length < compare_vars.limit ) {
                products = $.grep( products, function( value ) {
                    return value != id;
                });
                products.unshift( id );

                var products = products.join();

                set_Cookie( cookie, products, 7 );
            } else {
                limit = true;
                notice = notice.replace( '{max_limit}', compare_vars.limit );
            }

            count = products.length;

        } else {
            set_Cookie( cookie, id, 7 );
            count = 1;
        }

        if ( limit ) {
            alert( notice );
        } else {
            btn.addClass('added');
        }
    }

    function removeProduct( id ) {
        var cookie = 'styler_products',
            count  = 0,
            btn    = $('.styler-compare-btn[data-id="' + id + '"]'),
            cookie = compare_vars.user_id != '' ? 'styler_products_' + compare_vars.user_id : '';

        if ( cookie != '' ) {
            var products = get_Cookie( cookie ).split(',');

            products = $.grep( products, function( value ) {
                return value != id;
            });

            var products_str = products.join();

            set_Cookie( cookie, products_str, 7 );
            count = products.length;
        }

        btn.removeClass('added');
    }

    function get_count() {
        var products = getProducts(),
            count = 0;

        if ( products != '' ) {
            var arr = products.split(',');
                count = arr.length;
        }
        return count;
    }

    function change_count() {
        var count = get_count();
        $('[data-compare-count]').attr('data-compare-count', count );
        $('.styler-compare-count').html( count );
        compare_vars.count = count;
    }

    function showMessage(id,message, name) {
        var title = '<strong>'+name+'</strong>';
        if ( message == 'inlist' ) {
            message = compare_vars.inlist.replace( '{name}', title );
        }
        if ( message == 'added' ) {
            message = compare_vars.added.replace( '{name}', title );
        }
        if ( message == 'removed' ) {
            message = compare_vars.removed.replace( '{name}', title );
        }
        $( '.styler-compare-btn[data-id="'+id+'"]').parent().find('.loading-wrapper').remove();
        $( '.styler-compare-btn[data-id="' + id + '"]').parent().removeClass('loading');
        $( '.styler-shop-popup-notices .woocommerce-notices-wrapper').html(message);
        $( '.styler-shop-popup-notices').addClass('active styler-compare-message');

        setTimeout(function() {
            if ( !$('.styler-shop-popup-notices-wrapper .woocommerce-error').length ) {
                $('.styler-shop-popup-notices').removeClass('active');
            }
            setTimeout(function() {
                $('.styler-shop-popup-notices').removeClass('styler-compare-message');
            }, 1000);
        }, 3000);
    }

    // add product to compare list
    $(document).on('click touch', '.styler-compare-btn', function(e) {
        var $this = $( this ),
            id    = $this.attr('data-id'),
            name  = $this.attr('data-title');

        if ( compare_vars.btn_action == 'message' ) {
            if ( $this.hasClass('added') ) {
                showMessage(id,'inlist', name);
                addCompare( 'add', id );
            } else {
                showMessage(id,'added', name);
                addProduct( id );
                change_count();
            }
        } else {
            if ( $this.hasClass('added') ) {
                addCompare( 'add', id );
            } else {
                $this.addClass('added');
                $this.parent().append('<span class="loading-wrapper"><span class="ajax-loading"></span></span>').addClass('loading');
                addProduct( id );
                addCompare( 'add', id );
            }
        }

        if ( get_count() == '0' ) {
            $('.compare-area').removeClass('has-product');
        } else {
            $('.compare-area').addClass('has-product');
        }

        e.preventDefault();
    });

    // remove from compare list
    $(document).on('click touch', '.styler-compare-del-icon', function(e) {

        var id = $(this).attr('data-id');

        $('.styler-compare-item[data-id="' + id + '"]').remove();
        $( '.styler-compare-btn[data-id="' + id + '"]').removeClass('added');
        if ( get_count() < 4 ) {
            var src = $( '.styler-compare-items').data('placeholder');
            $( '.styler-compare-items .image[data-id="' + id + '"]').addClass('img-placeholder');
            $( '.styler-compare-items [data-id="' + id + '"]:not(.image)').addClass('td-placeholder').html('');
            $( '.styler-compare-items th[data-id="' + id + '"]').removeClass('td-placeholder').addClass('th-placeholder');
            $( '.styler-compare-items .td-placeholder,.styler-compare-items .img-placeholder,.styler-compare-items .th-placeholder').each( function(){
                $(this).appendTo($(this).parent());
            });
            $( '.styler-compare-items [data-id="' + id + '"] img').attr('src',src);
        } else {
            $( '.styler-compare-items [data-id="' + id + '"]').remove();
        }

        removeProduct( id );

        change_count();

        $('.styler-compare-items').attr('data-count',get_count());

        if ( get_count() == '0' ) {
            $('.compare-area').removeClass('has-product');
            $('.styler-compare-items').addClass('no-product');

        } else {
            $('.compare-area').addClass('has-product');
        }

        e.preventDefault();
    });

    $(document.body).on('click touch','.styler-compare-popup-list .styler-panel-close-button', function(){
        $('.styler-compare-popup-list').removeClass('loaded');
        $('body').removeClass('styler-overlay-open');
    });

    $(document.body).on('click touch','.open-compare-popup', function(){
        if ( $('.styler-compare-popup-list').length>0 ) {
            $('.styler-compare-popup-list').addClass('loaded');
            $('body').addClass('styler-overlay-open');
            $('.styler-header-mobile').removeClass('active');
        } else {
            $('body').append('<div class="styler-compare-popup-list loading"><div class="styler-panel-close-button"></div><svg class="svgloading styler-big-svg-icon" width="512" height="512" fill="currentColor" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"><path d="m26 9a1 1 0 0 0 0-2h-4a1 1 0 0 0 -1 1v4a1 1 0 0 0 2 0v-1.66a9 9 0 0 1 -7 14.66c-.3 0-.6 0-.9 0a1 1 0 1 0 -.2 2c.36 0 .73.05 1.1.05a11 11 0 0 0 8.48-18.05z"></path><path d="m10 19a1 1 0 0 0 -1 1v1.66a9 9 0 0 1 8.8-14.48 1 1 0 0 0 .4-2 10.8 10.8 0 0 0 -2.2-.18 11 11 0 0 0 -8.48 18h-1.52a1 1 0 0 0 0 2h4a1 1 0 0 0 1-1v-4a1 1 0 0 0 -1-1z"></path></svg><div class="styler-compare-popup-list-inner"></div></div>');
            var data = {
                action   : 'styler_load_compare_table',
                products : getProducts(),
                nonce    : compare_vars.nonce
            };

            $.post( compare_vars.ajaxurl, data, function( response ) {
                $('body').addClass('styler-overlay-open');
                $('.styler-compare-popup-list-inner').html( response );
                $('.styler-compare-popup-list').removeClass('loading').addClass('loaded');
                $('.styler-header-mobile').removeClass('active');
            });
        }
    });

    function addCompare( $action, id ) {
        if ( compare_vars.btn_action == 'message' ) {
            return;
        }
        if ( compare_vars.btn_action == 'popup' ) {
            if ( $('styler-compare-popup-list').length>0 ) {
                $('.styler-compare-popup-list').addClass('loading');
            } else {
                $('body').append('<div class="styler-compare-popup-list loading"><div class="styler-panel-close-button"></div><svg class="svgloading styler-big-svg-icon" width="512" height="512" fill="currentColor" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"><path d="m26 9a1 1 0 0 0 0-2h-4a1 1 0 0 0 -1 1v4a1 1 0 0 0 2 0v-1.66a9 9 0 0 1 -7 14.66c-.3 0-.6 0-.9 0a1 1 0 1 0 -.2 2c.36 0 .73.05 1.1.05a11 11 0 0 0 8.48-18.05z"></path><path d="m10 19a1 1 0 0 0 -1 1v1.66a9 9 0 0 1 8.8-14.48 1 1 0 0 0 .4-2 10.8 10.8 0 0 0 -2.2-.18 11 11 0 0 0 -8.48 18h-1.52a1 1 0 0 0 0 2h4a1 1 0 0 0 1-1v-4a1 1 0 0 0 -1-1z"></path></svg><div class="styler-compare-popup-list-inner"></div></div>');
            }
            var data = {
                action   : 'styler_load_compare_table',
                products : getProducts(),
                nonce    : compare_vars.nonce
            };

            $.post( compare_vars.ajaxurl, data, function( response ) {
                $('body').addClass('styler-overlay-open');
                $('.styler-compare-popup-list-inner').html( response );
                $('.styler-compare-popup-list').removeClass('loading').addClass('loaded');
                $( '.styler-compare-btn[data-id="'+id+'"]').parent().find('.loading-wrapper').remove();
                $( '.styler-compare-btn[data-id="'+id+'"]').parent().removeClass('loading');
                change_count();
                $('body').trigger('styler_lazy_load');
            });

        } else {
            var data = {
                action   : 'styler_add_compare',
                products : getProducts(),
                nonce    : compare_vars.nonce
            };

            $.post( compare_vars.ajaxurl, data, function( response ) {

                $('.styler-compare-content-items').html( response );
                if ( compare_vars.btn_action == 'panel' ) {
                    $('body').addClass('styler-overlay-open');
                    $('.styler-side-panel div:not([data-name="compare"])').removeClass('active');
                    $('.styler-side-panel, .compare-area, .styler-side-panel div[data-name="compare"]').addClass('active');
                }
                $( '.styler-compare-btn[data-id="'+id+'"]').parent().find('.loading-wrapper').remove();
                $( '.styler-compare-btn[data-id="'+id+'"]').parent().removeClass('loading');
                change_count();
                $('body').trigger('styler_lazy_load');
            });
        }
    }


    $( document ).ready( function( $ ) {
        $('.styler-compare-count').html( compare_vars.count );
    });

    if ( ( typeof compare_vars != 'undefined' ) && compare_vars.products ) {
        var ids = compare_vars.products;
        for (let i = 0; i < ids.length; i++) {
          $('.styler-compare-btn[data-id="'+ids[i]+'"]').addClass('added');
        }
    }


})(window, document, jQuery);
