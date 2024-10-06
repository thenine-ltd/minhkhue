jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';

    // quick shop start
    stylerQuickShopPopup();

    $(document).on('stylerShopInit', function() {
        stylerQuickShopPopup();
    });

    $(document.body).on('trigger_quick_shop', function(e,btn) {
        $(btn).trigger('click');
    });

    function stylerQuickShopPopup(){

       $( document.body ).on('click', '.styler-quick-shop-btn', function(event) {
            event.preventDefault();

            var $this = $(this),
                id    = $this.data('product_id');

            $.magnificPopup.open({
                items           : {
                    src : styler_vars.ajax_url + '?product_id=' + id
                },
                mainClass       : 'mfp-styler-quickshop styler-mfp-slide-bottom',
                removalDelay    : 160,
                overflowY       : 'scroll',
                fixedContentPos : false,
                closeBtnInside  : true,
                tClose          : '',
                closeMarkup     : '<div class="mfp-close styler-panel-close-button"></div>',
                tLoading        : '<span class="loading-wrapper"><span class="ajax-loading"></span></span>',
                type            : 'ajax',
                ajax            : {
                    settings : {
                        type : 'GET',
                        data : {
                            action : 'styler_ajax_quick_shop'
                        }
                    }
                },
                callbacks       : {
                    beforeOpen  : function() {},
                    open        : function() {
                        $('.mfp-preloader').addClass('loading');
                    },
                    ajaxContentAdded: function() {
                        $('.mfp-preloader').removeClass('loading');

                        var variations_form = $('.styler-quickshop-form-wrapper').find('form.cart');
                        var termsWrapper    = $('.styler-quickshop-form-wrapper').find('.styler-selected-variations-terms-wrapper');

                        variations_form.wc_variation_form();

                        $(variations_form).on('show_variation', function( event, data ){
                            $('.styler-quickshop-form-wrapper').find('.styler-btn-reset-wrapper,.single_variation_wrap').addClass('active');
                        });

                        $(variations_form).on('hide_variation', function(){
                            $('.styler-quickshop-form-wrapper').find('.styler-btn-reset-wrapper,.single_variation_wrap').removeClass('active');
                        });

                        if ( $('.grouped_form').length>0 || $(variations_form).length>0 ) {
                            $(document.body).trigger('styler_on_qtybtn');
                        }

                        if ( termsWrapper.length > 0 ) {
                            $(variations_form).on('change', function( event, data ){
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
                            $('.styler-quickshop-form-wrapper .styler-btn-reset.reset_variations').on('click', function() {
                                $('.styler-quickshop-form-wrapper .styler-selected-variations-terms-wrapper').slideUp();
                                $('.styler-quickshop-form-wrapper .styler-select-variations-terms-title').slideDown();
                            });
                        }

                        $('.styler-quickshop-form-wrapper form.cart').submit(function(e) {

                            if ( $(e.originalEvent.submitter).hasClass('styler-btn-buynow') ) {
                                return;
                            }

                            e.preventDefault();

                            var form = $(this),
                                btn  = form.find('.styler-btn.single_add_to_cart_button'),
                                data = new FormData(form[0]),
                                val  = form.find('[name=add-to-cart]').val();

                            data.append('add-to-cart',val);

                            btn.addClass('loading');

                            $.ajax({
                                url         : styler_vars.wc_ajax_url.toString().replace( '%%endpoint%%', 'styler_ajax_add_to_cart' ),
                                data        : data,
                                type        : 'POST',
                                processData : false,
                                contentType : false,
                                dataType    : 'json',
                                success     : function( response ) {

                                    btn.removeClass('loading');

                                    if ( ! response ) {
                                        return;
                                    }

                                    if ( response.error && response.product_url ) {
                                        window.location = response.product_url;
                                        return;
                                    }

                                    var fragments = response.fragments;

                                    $('.styler-quickshop-notices-wrapper').html(fragments.notices).slideDown();

                                    // update other areas
                                    $('.minicart-panel').replaceWith(fragments.minicart);
                                    $('.styler-cart-count').html(fragments.count);
                                    $('.styler-cart-total').html(fragments.total);
                                    $('.styler-cart-goal-text').html(fragments.shipping.message);
                                    $('.styler-progress-bar').css('width',fragments.shipping.value+'%');

                                    $('.styler-quickshop-notices-wrapper .close-error').on('click touch', function(e) {
                                        $('.styler-quickshop-notices-wrapper').slideUp();
                                    });

                                    $('.styler-quickshop-wrapper .styler-btn-reset,.styler-quickshop-wrapper .plus,.styler-quickshop-wrapper .minus').on('click touch', function(event) {
                                        $('.styler-quickshop-notices').slideUp();
                                    });

                                    $('.styler-quickshop-buttons-wrapper').slideDown().addClass('active');

                                    $('.styler-quickshop-buttons-wrapper .styler-btn').on('click touch', function(e) {
                                        if ( $(this).hasClass('open-cart-panel') ) {
                                            $('html,body').addClass('styler-overlay-open');
                                            $('.styler-side-panel .active').removeClass('active');
                                            $('.styler-side-panel').addClass('active');
                                            $('.cart-area').addClass('active');
                                        }
                                        $.magnificPopup.close();
                                    });
                                }
                            });
                        });

                        $('body').on('click', '.styler-btn-buynow', function() {
                            if ($(this).parents('form.cart').length) {
                                return;
                            }
                            $('form.cart').find('.styler-btn-buynow').trigger('click');
                        });
                    },
                    beforeClose : function() {},
                    close : function() {},
                    afterClose : function() {}
                }
            });
        });
    }
    // quick shop end
});
