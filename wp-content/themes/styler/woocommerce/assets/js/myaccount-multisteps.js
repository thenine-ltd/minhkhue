(function(window, document, $) {

    "use strict";

    jQuery(document).ready(function($) {

        var myAccountFormSteps = new NTSwiper('.styler-myaccount-steps-register', {
            loop             : false,
            speed            : 500,
            spaceBetween     : 0,
            autoHeight       : false,
            simulateTouch    : false,
            observer         : true,
            observerChildren : true,
            navigation       : {
                nextEl: '.styler-myaccount-steps-register .styler-myaccount-form-button-register',
                prevEl: '.styler-myaccount-steps-register .styler-myaccount-form-button-login'
            },
            on: {
                resize: function () {
                    var swiper = this;
                    swiper.update();
                }
            },
            effect: 'slide'
        });

        $('body').on('styler_myaccount_steps_register', function(){
            myAccountFormSteps.update();
        });
        
    });

})(window, document, jQuery);
