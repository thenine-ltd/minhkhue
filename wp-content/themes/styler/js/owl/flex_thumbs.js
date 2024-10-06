jQuery(document).ready(function($) {
	"use strict";

    $(".flex-control-thumbs").addClass("owl-carousel");
    $(".flex-control-thumbs").attr("id","product-thumbnails");
    var items = $('.woocommerce-product-gallery.woocommerce-product-gallery--with-images').attr('data-columns');
    var rtl = $('body').hasClass('rtl') ? true : false;

    $('.flex-control-thumbs').owlCarousel({
        items      : items,
        margin     : 10,
        pagination : true,
        rewindNav  : true,
        dots       : false,
        rtl        : rtl,
        responsive : {
            0:{
                items : 4
            },
            600:{
                items : 4
            },
            1300:{
                items : items
            }
        }
    });

});
