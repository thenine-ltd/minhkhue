jQuery(document).ready(function($) {


    var wrapper     = $('.styler-ajax-checkout-wrapper');
    var formWrapper = $('.styler-checkout-form-wrapper');
    var billing     = $('.styler-customer-billing-details');
    var billingForm = $('.woocommerce-billing-fields');
    var shippingForm = $('.styler-customer-billing-details');
    var order       = $('.styler-order-review');
    var stepBilling = $('.step-billing');
    var stepOrder   = $('.step-order:not(.disabled)');
    var shipTo      = $( '#ship-to-different-address-checkbox' );
    var formCheckout = $( 'form.checkout' );
    var billing_elements = '.woocommerce-billing-fields .input-text, .woocommerce-billing-fields select, .woocommerce-billing-fields input:checkbox';
    var shiping_elements = '.styler-customer-billing-details .input-text, .styler-customer-billing-details select, .styler-customer-billing-details input:checkbox';

    function nextSlide(id){
        var slidePosi = $('.slide-item[data-step="'+id+'"]').position();
        var slidePos  = slidePosi.left;
        var slideBefore = parseFloat( id - 1 );

        $('.slide-wrapper').css({
            '-webkit-transform' : 'translateX(-'+slidePos+'px)',
            '-moz-transform'    : 'translateX(-'+slidePos+'px)',
            '-ms-transform'     : 'translateX(-'+slidePos+'px)',
            '-o-transform'      : 'translateX(-'+slidePos+'px)',
            'transform'         : 'translateX(-'+slidePos+'px)'
        });
        $('.slide-item[data-step="'+slideBefore+'"]').css('height', 0);
    }

    function resetSlider(){
        $('.slide-wrapper').css({
            '-webkit-transform' : 'translateX(0px)',
            '-moz-transform'    : 'translateX(0px)',
            '-ms-transform'     : 'translateX(0px)',
            '-o-transform'      : 'translateX(0px)',
            'transform'         : 'translateX(0px)'
        });

        billing.css('height', 'auto');
    }


    $('body').on('form_billing_first_status', function() {
        var elements = shipTo.is( ':checked' ) ? shiping_elements : billing_elements;
        $( elements ).each( function() {
            var $this             = $( this ),
                $parent           = $this.closest( '.form-row' ),
                validated         = true,
                validate_required = $parent.is( '.validate-required' ),
                validate_email    = $parent.is( '.validate-email' ),
                validate_phone    = $parent.is( '.validate-phone' ),
                pattern           = '';

            if ( validate_required ) {
                if ( 'checkbox' === $this.attr( 'type' ) && ! $this.is( ':checked' ) ) {
                    $parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-required-field' );
                    validated = false;
                } else if ( $this.val() === '' ) {
                    $parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-required-field' );
                    validated = false;
                }
            }

            if ( validate_email ) {
                if ( $this.val() ) {
                    /* https://stackoverflow.com/questions/2855865/jquery-validate-e-mail-address-regex */
                    pattern = new RegExp( /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[0-9a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i ); // eslint-disable-line max-len

                    if ( ! pattern.test( $this.val() ) ) {
                        $parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-email woocommerce-invalid-phone' ); // eslint-disable-line max-len
                        validated = false;
                    }
                }
            }

            if ( validate_phone ) {
                pattern = new RegExp( /[\s\#0-9_\-\+\/\(\)\.]/g );

                if ( 0 < $this.val().replace( pattern, '' ).length ) {
                    $parent.removeClass( 'woocommerce-validated' ).addClass( 'woocommerce-invalid woocommerce-invalid-phone' );
                    validated = false;
                }
            }

            if ( validated ) {
                $parent.removeClass( 'woocommerce-invalid woocommerce-invalid-required-field woocommerce-invalid-email woocommerce-invalid-phone' ).addClass( 'woocommerce-validated' ); // eslint-disable-line max-len
            }
        });
        $('body').trigger('check_billing_input_status');
        $('body').trigger('add_required_string');
    });


    /* If there is an error when submit, add an error class to the order step */
    $(document.body).on('checkout_error', function(){
        if ( order.find('.form-row.validate-required.woocommerce-invalid').length > 0 ) {
            stepOrder.addClass('has-error');
        }
        $('.styler-checkout-form-wrapper').scrollTop(0);
    });

    /* If there is an error in the form fields, add this field is required message to the form elements */
    $('body').on('add_required_string', function(){
        var invalid_elements = shipTo.is( ':checked' ) ? shiping_elements : billing_elements;
        $( invalid_elements ).each( function() {
            var $this   = $(this),
                $parent = $this.closest( '.form-row.woocommerce-invalid').find('label'),
                reqStr  = styler_vars.required;

            var new_message = '<strong class="required">* '+reqStr+'</strong>';
            if ( $this.closest( '.form-row').is('.woocommerce-invalid') ) {
                $(new_message).appendTo($parent);
            } else {
                $this.closest( '.form-row').find('strong.required').remove();
            }
        });
    });

    /* check billing and shipping forms and trigger checks as appropriate */
    formCheckout.on('change','input', function(){
        var form_elements = shipTo.is( ':checked' ) ? shippingForm : billingForm;
        if ( form_elements.find('.form-row.validate-required.woocommerce-invalid').length > 0 ) {
            stepBilling.addClass('has-error').removeClass('success');
            stepOrder.addClass('disabled');
        } else {
            stepBilling.removeClass('has-error').addClass('success');
            stepOrder.removeClass('disabled');
        }
        $('body').trigger('add_required_string');
    });

    formCheckout.on('change','select#billing_state', function(){
        $('body').trigger('check_billing_input_status');
        $('body').trigger('add_required_string');
    });

    formCheckout.on('change','input#terms', function(){
        if ( $(this).is( ':checked' ) ) {
            stepOrder.removeClass('has-error').addClass('success');
            $('.woocommerce-error').remove();
        } else {
            stepOrder.removeClass('success').addClass('has-error');
        }
    });
    /* billing ve shipping form kontrol et ve duruma göre kontrolleri tetikle */
    $( document.body ).on( 'country_to_state_changed', function(){
        $('.styler-checkout-form-wrapper.styler-scrollbar').off("scroll").scrollTop(0);
        $('body').trigger('check_billing_input_status');
    });

    /* If there are errors in biling form fields, add an error message to step 1 and close step 2. */
    $('body').on('check_billing_input_status', function() {
        var form_elements = shipTo.is( ':checked' ) ? shippingForm : billingForm;
        if ( form_elements.find('.form-row.validate-required.woocommerce-invalid').length > 0 ) {
            stepBilling.addClass('has-error').removeClass('success');
            stepOrder.addClass('disabled');
        } else {
            stepBilling.removeClass('has-error').addClass('success');
            stepOrder.removeClass('disabled');
        }
    });

    /* trigger slider steps */
    stepBilling.on('click', function() {
        resetSlider();
    });

    stepOrder.on('click', function() {
        var id = $(this).data('step');
        $('body').trigger('form_billing_first_status');


        if ( stepBilling.is('.has-error') ) {
            resetSlider();
        } else {
            nextSlide(id);
        }
    });

    formCheckout.on( 'submit', function(){
        $( document ).ajaxComplete(function( event, xhr, settings ) {

            var response = $.parseJSON(xhr.responseText);
            if ( response.result == 'success' ) {
                stepOrder.removeClass('has-error').addClass('success');
                window.location = response.redirect + '&order_received=true';
            }
        });
    });

    $( '.styler-ajax-checkout-wrapper .checkout div.shipping_address' ).hide();

});
