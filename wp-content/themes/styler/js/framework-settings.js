(function(window, document, $) {

  "use strict";

	jQuery(document).ready(function( $ ) {

        // masonry
        var masonry = $('.styler-masonry-container');
        if ( masonry.length ) {
            //set the container that Masonry will be inside of in a var
            var container = document.querySelector('.styler-masonry-container');
            //create empty var msnry
            var msnry;
            // initialize Masonry after all images have loaded
            imagesLoaded( container, function() {
               msnry = new Masonry( container, {
                   itemSelector: '.styler-masonry-container>div'
               });
            });
        }

        var block_check = $('.nt-single-has-block');
        if ( block_check.length ) {
            $( ".nt-styler-content ul" ).addClass( "nt-styler-content-list" );
            $( ".nt-styler-content ol" ).addClass( "nt-styler-content-number-list" );
        }
        $( ".styler-post-content-wrapper>*:last-child" ).addClass( "styler-last-child" );


        // add class for bootstrap table
        $( ".menu-item-has-shortcode" ).parent().parent().addClass( "menu-item-has-shortcode-parent" );
        $( ".nt-styler-content table, #wp-calendar" ).addClass( "table table-striped" );
        $( ".woocommerce-order-received .nt-styler-content table" ).removeClass( "table table-striped" );
        // CF7 remove error message
        $('.wpcf7-response-output').ajaxComplete(function(){
            window.setTimeout(function(){
                $('.wpcf7-response-output').addClass('display-none');
            }, 4000); //<-- Delay in milliseconds
            window.setTimeout(function(){
                $('.wpcf7-response-output').removeClass('wpcf7-validation-errors display-none');
                $('.wpcf7-response-output').removeAttr('style');
            }, 4500); //<-- Delay in milliseconds
        });
        if ( $('.woocommerce-ordering select').length ) {
            $('.woocommerce-ordering select').niceSelect();
        }
        if ( $('.styler-ajax-product-search select').length ) {
            $('.styler-ajax-product-search select').niceSelect();
            $('.styler-ajax-product-search .nice-select .list').addClass('styler-scrollbar');
        }
        // Animate loader off screen
        $('#nt-preloader').fadeOut(1000);
    }); // end ready

    // Animate loader off screen
    $('#nt-preloader').fadeOut(1000);
    
})(window, document, jQuery);
