/*-----------------------------------------------------------------------------------

    Theme Name: Styler
    Description: WordPress Theme
    Author: Ninetheme
    Author URI: https://ninetheme.com/
    Version: 1.0

-----------------------------------------------------------------------------------*/

(function(window, document, $) {

    "use strict";

    var instance;
    var update_lazyload;
    var winw = $(window).outerWidth();

    var init_lazyload = function(){
        instance = new LazyLoad( {
            elements_selector: '[data-src],[data-bg],.lazy',
            callback_loading: (el)=>{
                $(el).closest('.styler-thumb-wrapper').addClass('loading');
                $(el).closest('.styler-swiper-slide-first').addClass('img-loading');

                if ( typeof elementorFrontend != 'undefined' ) {
                    var deviceMode = elementorFrontend.getCurrentDeviceMode();
                    var elBg = $(el).data('bg-responsive');

                    if ( typeof elBg != 'undefined' ) {
                        var desktop = $(el).data('bg');

                        var widescreen   = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : desktop;
                        var laptop       = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : desktop;
                        var tablet_extra = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : laptop;
                        var tablet       = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : tablet_extra;
                        var mobile_extra = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : tablet;
                        var mobile       = typeof elBg[deviceMode] != 'undefined' ? elBg[deviceMode] : mobile_extra;
                        var bgUrl        = mobile;

                        if ( bgUrl ) {
                            $(el).css('background-image', 'url(' + bgUrl + ')' );
                        }
                    }
                }
            },
            callback_loaded: (el)=>{
                $(el).closest('.styler-thumb-wrapper').removeClass('loading');

                var iframeWrapper = $(el).closest('.styler-loop-product-iframe-wrapper');
                var iframeWrapper2 = $(el).closest('.styler-woo-banner-iframe-wrapper');

                var videoid = $(el).data('styler-bg-video'),
                    aspectRatioSetting = $(el).data('bg-aspect-ratio');

                if ( iframeWrapper2.hasClass('styler-video-calculate') ) {
                    var containerWidth = iframeWrapper2.outerWidth(),
                        containerHeight = iframeWrapper2.outerHeight(),
                        aspectRatioArray = aspectRatioSetting.split(':'),
                        aspectRatio = aspectRatioArray[0] / aspectRatioArray[1],
                        ratioWidth = containerWidth / aspectRatio,
                        ratioHeight = containerHeight * aspectRatio,
                        isWidthFixed = containerWidth / containerHeight > aspectRatio,
                        size = {
                            w: isWidthFixed ? containerWidth : ratioHeight,
                            h: isWidthFixed ? ratioWidth : containerHeight
                        };

                    $(el).css({
                        width: size.w + 100,
                        height: size.h + 100
                    });
                }
                if ( winw <= 1024 && ( iframeWrapper.length || iframeWrapper2.length ) ) {
                    var iframe = $(el);
                    if ( iframeWrapper.length ) {
                        iframe[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
                    }

                    if ( iframeWrapper2.hasClass('styler-video-youtube') ) {
                        iframe[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
                    }
                    if ( iframeWrapper2.hasClass('styler-video-vimeo') ) {
                        iframe[0].contentWindow.postMessage('{"method":"play"}', '*');
                    }
                    if ( $(el).hasClass('styler-video-local') ) {
                        iframe.get(0).play();
                    }
                }
                if ( $('.styler-masonry-container').length ) {
                    var container = document.querySelector('.styler-masonry-container');
                    //create empty var msnry
                    var msnry;
                    // initialize Masonry after all images have loaded
                    msnry = new Masonry( container, {
                        itemSelector: '.styler-masonry-container>div'
                    });
                }
            },
            callback_finish: (el)=>{
                document.body.classList.add( 'styler_lazyloaded' );
            },
            threshold: 1000,
        });

        update_lazyload = function(){
            instance.update();
        };

        if ( window.MutationObserver ) {
            new MutationObserver( update_lazyload ).observe( document.documentElement, { childList: true, subtree: true, attributes: true } ) ;
        }
    };
    window.addEventListener ? window.addEventListener( "load", init_lazyload, false ) : window.attachEvent( "onload", init_lazyload );

    $(document).ready(function(){
        var init_beforelazyload = function(){
            $('.styler-product-gallery-main-slider-carousel img[data-src]:not(.loaded),.styler-product-thumbnails img[data-src]:not(.loaded),.styler-product-gallery-main-slider img[data-src]:not(.loaded),.styler-checkout-review-order-table img[data-src]:not(.loaded), .styler-thumb-wrapper .styler-loop-slider img[data-src]:not(.loaded)').each(function(){
                var $this  = $(this);
                var src    = $this.data('src');
                var srcset = $this.data('srcset');
                var sizes  = $this.data('sizes');
                $this.attr('src', src);
                $this.attr('srcset', srcset);
            });
            $('.styler-product-gallery-main-slider').addClass('loading');
        };
        init_beforelazyload();
    });


})(window, document, jQuery);
