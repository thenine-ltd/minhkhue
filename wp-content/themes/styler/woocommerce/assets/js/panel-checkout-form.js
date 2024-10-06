jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';

    $(document.body).on( 'added_to_cart removed_from_cart updated_cart_totals', function() {
        panelPopupCheckOut();
    });

    panelPopupCheckOut();
    function panelPopupCheckOut() {
        $('body').on('click', '.styler-side-panel .styler-btn[data-name="checkout"]', function () {
            $('.styler-side-panel .panel-content-item').removeClass('active');
            $('.styler-side-panel .panel-header-btn').removeClass('active');
            $('.styler-side-panel .panel-content-item[data-name="checkout"]').addClass('active');
        });
        $('.minicart-panel .styler-btn[data-name="checkout"]').on('click', function () {
            $('.styler-header-mobile, .styler-header-mobile .action-content').removeClass('active');
            $('.top-action-btn[data-name="cart"]').trigger('click');
            $('.styler-side-panel .styler-btn[data-name="checkout"]').off("click").trigger('click');
        });
        $('.styler-side-panel .styler-panel-close,.styler-side-panel .panel-header-btn,.styler-header-overlay').on('click', function () {
            if ( $('.checkout-area.panel-content-item').length ) {
                setTimeout(function(){
                    $( '.styler-panel-checkout-button-prev' ).trigger( 'click' );
                }, 500 );
            }
        });
    }

    $(document.body).on('close_woocommerce_error checkout_error applied_coupon_in_checkout removed_coupon_in_checkout',function(){
        closeCheckoutNotices();
    });

    function closeCheckoutNotices() {
        $('ul.woocommerce-error .close-error').on('click',function(){
            $('ul.woocommerce-error').fadeOut();
            $('ul.woocommerce-error .close-error').remove();
        });
    }

    if ( $('.checkout-area.panel-content-item').length ) {

        if ( $('.checkout-area form.checkout.woocommerce-checkout').length ) {
            var sidePanelHeight    = $('.styler-side-panel').height();
            var checkForm          = $('.panel-content .checkout-area form.checkout.woocommerce-checkout').position();
            var checkFormTop       = checkForm.top;
            var checkFormMaxHeight = (sidePanelHeight - checkFormTop) - 70;
            var orderHeight        = $( '[data-target-name="order"]' ).height();

            var checkoutFormsWrapper     = $('.styler-panel-checkout-form-wrapper');
            var billingFormsWrapper      = $('.checkout-area .styler-customer-billing-details');
            var billingFormsValidateReq  = $('.checkout-area .styler-customer-billing-details .validate-required');
            var shippingFormsWrapper     = $('.checkout-area .styler-customer-shipping-details');
            var shippingFormsValidateReq = $('.checkout-area .styler-customer-shipping-details .validate-required');
            var shippingFormsIsCheck     = $( '#ship-to-different-address-checkbox' );
            var stepBilling              = $( '.checkout-area .styler-step-item-1' );
            var stepOrder                = $( '.checkout-area .styler-step-item-2' );
            var stepOrderButton          = $( '.checkout-area .styler-panel-checkout-button-next' );
            var stepBillingButton        = $( '.checkout-area .styler-panel-checkout-button-prev' );
            var billingFields            = $( '[data-target-name="billing"]' );
            var orderFields              = $( '[data-target-name="order"]' );

            function checkPanelShippingFields() {
                if ( shippingFormsIsCheck.is( ':checked' ) ) {
                    shippingFormsValidateReq.find('.input-text, select, input[type="checkbox"]').each(function () {
                        var $this = $( this );
                        if ( $this.val() == '' || $this.val() == null || $this.parents('.form-row.validate-required').hasClass( 'has-error' ) ) {
                            shippingFormsWrapper.addClass( 'has-error' );
                            return false;
                        } else {
                            shippingFormsWrapper.removeClass( 'has-error' );
                        }
                    });
                } else {
                    shippingFormsWrapper.removeClass( 'has-error' );
                }
            }
            function checkPanelBillingFields() {
                billingFormsValidateReq.find('.input-text, select, input:checkbox').each(function () {
                    var $this = $( this );
                    if ( $this.val() == '' || $this.val() == null || $this.parents('.validate-required').hasClass( 'has-error' ) ) {
                        $this.attr("placeholder", styler_vars.required).blur();
                        billingFormsWrapper.addClass( 'has-error' );
                        return false;
                    } else {
                        billingFormsWrapper.removeClass( 'has-error' );
                    }
                });
            }

            $(document.body).on( 'country_to_state_changed', function() {
                billingFormsValidateReq.find('.input-text, select, input:checkbox').each(function () {
                    var $this = $( this );
                    if ( $this.val() == '' || $this.val() == null || $this.parents('.validate-required').hasClass( 'has-error' ) ) {
                        $this.parents('.validate-required').addClass( 'has-error' );
                        billingFormsWrapper.addClass( 'has-error' );
                        return false;
                    } else {
                        $this.parents('.validate-required').removeClass( 'has-error' );
                        billingFormsWrapper.removeClass( 'has-error' );
                    }
                });
                if ( shippingFormsIsCheck.is( ':checked' ) ) {
                    shippingFormsValidateReq.find('.input-text, select, input[type="checkbox"]').each(function () {
                        var $this = $( this );
                        if ( $this.val() == '' || $this.val() == null || $this.parents('.form-row.validate-required').hasClass( 'has-error' ) ) {
                            $this.parents('.validate-required').addClass( 'has-error' );
                            shippingFormsWrapper.addClass( 'has-error' );
                            return false;
                        } else {
                            $this.parents('.validate-required').removeClass( 'has-error' );
                            shippingFormsWrapper.removeClass( 'has-error' );
                        }
                    });
                } else {
                    shippingFormsWrapper.removeClass( 'has-error' );
                }
            });

            checkPanelShippingFields();
            checkPanelBillingFields();

            $('body').on('styler_panel_input_change',function(){
                checkPanelShippingFields();
                checkPanelBillingFields();
            });
            stepOrder.removeClass( 'active-step' );
            stepBillingButton.on('click',function(){
                orderFields.slideUp();
                billingFields.slideDown(1000);
                stepOrder.removeClass( 'active-step' );
                stepBilling.removeClass( 'active-step' ).addClass( 'active-step' );
            });

            stepOrderButton.on('click',function(){
                $('body').trigger('styler_panel_input_change');
                if ( billingFormsWrapper.hasClass( 'has-error' ) || ( shippingFormsIsCheck.is( ':checked' ) && shippingFormsWrapper.hasClass( 'has-error' ) ) ) {
                    return;
                } else {
                    billingFields.slideUp();
                    orderFields.slideDown(1000);
                    stepBilling.removeClass( 'active-step' );
                    $('.styler-panel-checkout-form-wrapper.styler-perfect-scrollbar').stop().animate({
                        scrollTop: $('.styler-panel-checkout-form-wrapper.styler-perfect-scrollbar').position().top
                    }, 800);
                }
            });
            $('.checkout-area form.woocommerce-checkout').on('submit',function(){

                setTimeout(function(){
                    if ( $('.checkout-area ul.woocommerce-error li[data-id]').length ) {

                        var errorItemId = $('.checkout-area ul.woocommerce-error li:first-child').data('id');

                        $('.checkout-area #'+errorItemId+'_field' ).parents('.styler-panel-checkout-content-inner').addClass('has-error');

                        if ( $('.checkout-area .styler-panel-checkout-content-inner[data-target-name="billing"]').hasClass('has-error') ) {
                            $('.styler-panel-checkout-button-prev').trigger('click');
                        }

                        setTimeout(function(){
                            var targetError = $('.checkout-area #'+errorItemId ).position();
                            $('.checkout-area #'+errorItemId+'_field' ).addClass('has-error');
                            $('.styler-panel-checkout-form-wrapper').stop().animate({
                                scrollTop: targetError.top
                            }, 800);
                        }, 500, errorItemId );
                    }
                    $('body').trigger('close_woocommerce_error');

                }, 1500 );
            });

            $('body').on('input validate change', '.checkout-area .styler-customer-billing-details .input-text,.checkout-area .styler-customer-billing-details select,.checkout-area .styler-customer-billing-details input:checkbox', function(e){
                var $this       = $( this ),
                $id         = $this.attr( 'id' ),
                $parent     = $this.closest( '.form-row.validate-required' ),
                event_type  = e.type;
                if ( 'change' === event_type ) {

                    $parent.each(function () {
                        var $this = $( this );
                        if ( $this.hasClass( 'woocommerce-invalid-required-field' ) || $this.val() == null ) {
                            $this.addClass( 'has-error' );
                        } else {
                            $this.removeClass( 'has-error' );
                            $('.checkout-area ul.woocommerce-error li[data-id="'+$id+'"]').remove();
                        }
                        $('body').trigger('styler_panel_input_change');
                        if ( billingFormsWrapper.hasClass( 'has-error' ) ) {
                            var targetErrorAnother = $this.closest( '.styler-customer-billing-details' ).find('.has-error');
                            var targetError = $this.hasClass( 'has-error' ) ? $this.position() : $(targetErrorAnother).position();

                            if ( targetError ) {
                                setTimeout(function(){
                                    checkoutFormsWrapper.stop().animate({
                                        scrollTop: targetError.top
                                    }, 800);
                                }, 400 );
                                return false;
                            }

                        }
                    });
                }
            });

            $('body').on('input validate change', '.checkout-area .styler-customer-shipping-details .input-text,.checkout-area .styler-customer-shipping-details select,.checkout-area .styler-customer-shipping-details input:checkbox', function(e){
                var $this       = $( this ),
                $parent     = $this.closest( '.form-row.validate-required' ),
                event_type  = e.type;

                if ( 'change' === event_type ) {

                    $parent.each(function () {
                        var $this = $( this );
                        if ( $this.hasClass( 'woocommerce-invalid-required-field' ) || $this.val() == null ) {
                            $this.addClass( 'has-error' );
                        } else {
                            $this.removeClass( 'has-error' );
                        }
                    });

                    if ( shippingFormsWrapper.hasClass( 'has-error' ) ) {
                        var targetError = shippingFormsWrapper.find('.has-error').position();
                        if ( targetError ) {
                            setTimeout(function(){
                                checkoutFormsWrapper.stop().animate({
                                    scrollTop: targetError.top
                                }, 800);
                            }, 400 );
                            return false;
                        }
                    } else {
                        if ( billingFormsWrapper.hasClass( 'has-error' ) ) {
                            var targetError = billingFormsWrapper.find('.has-error').position();

                            if ( targetError ) {
                                setTimeout(function(){
                                    checkoutFormsWrapper.stop().animate({
                                        scrollTop: targetError.top
                                    }, 800);
                                }, 400 );
                                return false;
                            }

                        }
                    }
                }
            });

            setTimeout(function(){
                orderFields.slideUp();
            }, 400 );
        }
    }
});
