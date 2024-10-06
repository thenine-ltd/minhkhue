jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';

    if ( $('.styler-product-gallery-main-slider').length ) {
        var scrollOffset = $('.styler-header-default').height();

        if ( $('body').hasClass('admin-bar') ) {
            scrollOffset = scrollOffset + 32;
        }

        function scrollToTop(target,delay,timeout) {
            setTimeout(function(){
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - scrollOffset
                }, delay);
            }, timeout );
        }

        function stylerProductGalleryStretch() {
            if ( $('.styler-product-gallery-main-slider').length ) {
                var thumbsOptions = $('.styler-product-thumbnails').data('swiper-options');
                var galleryThumbs = new NTSwiper( '.styler-product-thumbnails', thumbsOptions );

                galleryThumbs.on('resize', function(swiper){
                    swiper.update();
                });

                var galleryOptions        = $('.styler-product-gallery-main-slider').data('swiper-options');
                galleryOptions["thumbs"]  = {swiper: galleryThumbs};
                galleryOptions["on"]      = {
                    transitionEnd : function ( swiper ) {
                        var  active = swiper.realIndex;

                        $( '.styler-product-gallery-main-slider .swiper-slide:not(.swiper-slide-active)' ).each(function () {

                            var iframe = $( this ).find('iframe');

                            if ( iframe.size() ) {
                                iframe[0].contentWindow.postMessage('{"event":"command","func":"' + 'pauseVideo' + '","args":""}', '*');
                            }

                        });

                        $( '.styler-product-gallery-main-slider .swiper-slide-active' ).each(function () {
                            var iframe2 = $( this ).find('iframe');
                            if ( iframe2.size() ) {
                                iframe2[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
                            }
                        });
                    }
                };
                var galleryMain = new NTSwiper( '.styler-product-gallery-main-slider', galleryOptions );

                var heightFirstImage = $('.styler-product-thumbnails .styler-swiper-slide-first img').height();
                $('.styler-slide-video-item-icon').css('height', $('.styler-slide-video-item-icon').data('height'));

                var $oMainImg       = $('.styler-product-gallery-main-slider .styler-swiper-slide-first img'),
                $oZoomSrc       = $('.styler-product-gallery-main-slider .styler-swiper-slide-first').data('src'),
                $oMainSrc       = $oMainImg.data('src'),
                $oMainSrcSet    = $oMainImg.data('srcset'),
                $oMainSrcSizes  = $oMainImg.data('sizes'),
                $oThumbImg      = $('.styler-product-thumbnails .styler-swiper-slide-first img'),
                $oThumbSrc      = $oThumbImg.data('src'),
                $oThumbSrcSet   = $oThumbImg.data('srcset'),
                $oThumbSrcSizes = $oThumbImg.data('sizes');

                $( document ).on('change','.styler-product-summary .variations_form select', function( e ) {
                    var $this      = $(this),
                    $form      = $this.parents('.variations_form'),
                    variations = $form.data('product_variations'),
                    $oZoomImg  = $('.styler-product-gallery-main-slider .styler-swiper-slide-first img.zoomImg'),
                    gallery    = $('.styler-product-gallery-main-slider');

                    setTimeout( function() {
                        var current_id = $form.attr('current-image'),
                        image,
                        timage,
                        full_src,
                        src,
                        srcset,
                        sizes,
                        tsrc,
                        tsrcset,
                        tsizes;

                        $.map(variations, function(elementOfArray, indexInArray) {
                            if (elementOfArray.image_id == current_id) {
                                image   = elementOfArray.image;
                                src     = image.src;
                                full_src= image.full_src;
                                srcset  = image.srcset;
                                sizes   = image.sizes;
                            }
                        });
                        $.map(variations, function(elementOfArray, indexInArray) {
                            if (elementOfArray.image_id == current_id) {
                                timage  = elementOfArray.image;
                                tsrc    = timage.src;
                                tsrcset = timage.srcset;
                                tsizes  = timage.sizes;
                            }
                        });
                        if ( current_id ) {
                            $oMainImg.attr('src',src);
                            $oMainImg.attr('data-src',src);
                            $oZoomImg.attr('src',full_src);
                            if ( srcset ) {
                                $oMainImg.attr('srcset',srcset);
                            }
                            if ( sizes ) {
                                $oMainImg.attr('sizes',sizes);
                            }
                            $oThumbImg.attr('src',tsrc);
                            if ( tsrcset ) {
                                $oThumbImg.attr('srcset',tsrcset);
                            }
                            if ( tsizes ) {
                                $oThumbImg.attr('sizes',tsizes);
                            }

                            setTimeout( function() {
                                if ( !$oMainImg.hasClass('swiper-slide-active') ) {
                                    galleryMain.slideTo(0);
                                    galleryThumbs.slideTo(0);
                                }
                                galleryMain.update();
                                galleryMain.updateAutoHeight(10);
                                galleryThumbs.update();
                                $('.styler-swiper-slide-first').attr('data-src',full_src);
                                initZoom('reinit',full_src);
                            }, 100 );

                            if ( styler_vars.scrolltop == 'yes' ) {
                                scrollToTop(gallery,300,300);
                            }
                        }
                    }, 50 );
                });

                $( document ).on('click','.styler-product-summary .reset_variations', function( e ) {
                    var $form     = $(this).parents('.variations_form'),
                    gallery   = $('.styler-product-gallery-main-slider'),
                    $oZoomImg = $('.styler-product-gallery-main-slider .styler-swiper-slide-first img.zoomImg');

                    $oMainImg.attr('src',$oMainSrc);
                    $oMainImg.attr('data-src',$oMainSrc);
                    $oZoomImg.attr('src',$oZoomSrc);
                    if ( $oMainSrcSet ) {
                        $oMainImg.attr('srcset',$oMainSrcSet);
                    }
                    if ( $oMainSrcSizes ) {
                        $oMainImg.attr('sizes',$oMainSrcSizes);
                    }

                    $oThumbImg.attr('src',$oThumbSrc);
                    if ( $oThumbSrcSet ) {
                        $oThumbImg.attr('srcset',$oThumbSrcSet);
                    }
                    if ( $oThumbSrcSizes ) {
                        $oThumbImg.attr('sizes',$oThumbSrcSizes);
                    }

                    setTimeout( function() {
                        if ( !$oMainImg.hasClass('swiper-slide-active') ) {
                            galleryMain.slideTo(0);
                            galleryThumbs.slideTo(0);
                        }
                        galleryMain.update();
                        galleryMain.updateAutoHeight(10);
                        galleryThumbs.update();

                        $('.styler-swiper-slide-first').attr('data-src',$oMainSrc);
                        initZoom('reinit',$oZoomSrc);
                    }, 100 );

                    if ( styler_vars.scrolltop == 'yes' ) {
                        scrollToTop(gallery,400,300);
                    }
                });

                initZoom('load');

                /**
                * Init zoom.
                */
                function initZoom($action,$url) {
                    if ( 'function' !== typeof $.fn.zoom && !wc_single_product_params.zoom_enabled ) {
                        return false;
                    }

                    var galleryWidth = $('.styler-product-gallery-main-slider .swiper-slide').width(),
                    zoomEnabled  = false,
                    zoom_options = {
                        touch: false
                    };

                    if ( 'ontouchstart' in document.documentElement ) {
                        zoom_options.on = 'click';
                    }

                    $('.styler-product-gallery-main-slider .swiper-slide img').each( function( index, target ) {
                        var image = $( target );
                        var imageIndex = image.parents('.swiper-slide');

                        if ( image.attr( 'width' ) > galleryWidth ) {
                            if ( $action == 'load' ) {
                                zoom_options.url = image.parent().data('src');
                                image.wrap('<span class="styler-zoom-wrapper" style="display:block"></span>')
                                .css('display', 'block')
                                .parent()
                                .zoom(zoom_options);
                            } else {
                                image.trigger('zoom.destroy').unwrap();
                                zoom_options.url = imageIndex.hasClass('styler-swiper-slide-first') ? $url : image.parent().data('src');
                                image.wrap('<span class="styler-zoom-wrapper" style="display:block"></span>')
                                .css('display', 'block')
                                .parent()
                                .zoom(zoom_options);
                            }
                        }
                    });
                }
            }
        }

        stylerProductGalleryInit();

        function stylerProductGalleryInit() {
            if ( $('.styler-product-gallery-main-slider').parents('.styler-single-product-type-stretch').length ) {
                stylerProductGalleryStretch();
            } else {
                if ( $('.styler-product-gallery-main-slider').length ) {

                    var options    = $('.styler-product-gallery-main-slider').data('swiper-options');
                    var direction  = options.direction;
                    var perview    = options.perview;
                    var mobperview = options.mobperview;

                    $('.styler-product-gallery-main-slider .swiper-slide').each(function(i,e){
                        var $this    = $(this);
                        var thumbUrl = $this.data('thumb') ? $this.data('thumb') : $this.data('src');
                        var active   = i == 0 ? ' swiper-slide-thumb-active' : '';
                        var videoBg  = $this.hasClass('iframe-video') && $this.data('preview') ? 'background-image:url('+$this.data('preview')+');' : '';
                        var videoH   = $this.hasClass('iframe-video') ? ' style="height:'+Math.round($('.styler-product-thumbnails .swiper-slide:first-child img').outerHeight())+'px;'+videoBg+'"' : '';

                        var tumbImg = $this.hasClass('iframe-video') ? '<div class="styler-slide-video-item-icon"'+videoH+'><i class="fa fa-play"></i></div>' : '<img src="'+thumbUrl+'">';
                        $('<div class="swiper-slide thumb-video-icon'+active+'">'+tumbImg+'</div>').appendTo($('.styler-product-thumbnails .swiper-wrapper'));
                    });

                    var galleryThumbs  = new NTSwiper( '.styler-product-thumbnails', {
                        spaceBetween         : 10,
                        slidesPerView        : mobperview,
                        direction            : "horizontal",
                        watchOverflow        : true,
                        watchSlidesProgress  : true,
                        watchSlidesVisibility: true,
                        rewind               : true,
                        resizeObserver       : true,
                        grabCursor           : true,
                        navigation            : {
                            nextEl : ".styler-swiper-slider-wrapper .styler-swiper-next",
                            prevEl : ".styler-swiper-slider-wrapper .styler-swiper-prev"
                        },
                        breakpoints          : {
                            768 : {
                                slidesPerView : direction == 'vertical' ? 'auto' : perview,
                                direction     : direction
                            }
                        },
                        on                   : {
                            resize : function ( swiper ) {
                                swiper.update();
                                var videoicon = $('.styler-product-thumbnails .swiper-slide:not(.swiper-slide-active)').height();
                                $('.styler-slide-video-item-icon').css('height', videoicon );
                            },
                            init : function ( swiper ) {

                                setTimeout(function(){
                                    var videoicon = $('.styler-product-thumbnails .swiper-slide:first-child').height();
                                    $('.styler-slide-video-item-icon').css('height', videoicon - 6 );
                                }, 500);
                            }
                        }
                    });

                    var galleryMain = new NTSwiper( '.styler-product-gallery-main-slider', {
                        speed                 : 800,
                        spaceBetween          : 0,
                        slidesPerView         : 1,
                        direction             : "horizontal",
                        watchSlidesVisibility : true,
                        watchSlidesProgress   : true,
                        rewind                : true,
                        resizeObserver        : true,
                        grabCursor            : true,
                        navigation            : {
                            nextEl : ".styler-swiper-slider-wrapper .styler-swiper-next",
                            prevEl : ".styler-swiper-slider-wrapper .styler-swiper-prev"
                        },
                        thumbs                : {
                            swiper: galleryThumbs
                        },
                        on                    : {
                            init : function ( swiper ) {
                                var heightVertical = $('.styler-product-gallery-main-slider').height();
                                $('.styler-product-thumbnails').css('max-height', heightVertical );
                            },
                            resize : function ( swiper ) {
                                var heightVertical = $('.styler-product-gallery-main-slider').height();
                                $('.styler-product-thumbnails').css('max-height', heightVertical );
                                swiper.update();
                            },
                            transitionEnd : function ( swiper ) {
                                var  active = swiper.realIndex;

                                $( '.styler-product-gallery-main-slider .iframe-video:not(.swiper-slide-active)' ).each(function () {
                                    var iframe = $( this ).find('iframe');
                                    if ( iframe.length && $( this ).hasClass('video-src-youtube') ) {
                                        iframe[0].contentWindow.postMessage('{"event":"command","func":"' + 'pauseVideo' + '","args":""}', '*');
                                    }
                                    if ( iframe.length && $( this ).hasClass('video-src-vimeo') ) {
                                        iframe[0].contentWindow.postMessage('{"method":"pause"}', '*');
                                    }
                                });

                                $( '.styler-product-gallery-main-slider .iframe-video.swiper-slide-active' ).each(function () {
                                    var iframe2 = $( this ).find('iframe');
                                    if ( iframe2.length && $( this ).hasClass('video-src-youtube') ) {
                                        iframe2[0].contentWindow.postMessage('{"event":"command","func":"' + 'playVideo' + '","args":""}', '*');
                                    }
                                    if ( iframe2.length && $( this ).hasClass('video-src-vimeo') ) {
                                        iframe2[0].contentWindow.postMessage('{"method":"play"}', '*');
                                    }
                                });
                            },
                            afterInit: function(swiper){
                                var iframesrc = $('.styler-product-gallery-main-slider .iframe-video iframe').data('src');
                                $( '.styler-product-gallery-main-slider .iframe-video iframe' ).attr('src', iframesrc);
                            }
                        }
                    });

                    var $gallery     = $('.styler-product-gallery-main-slider'),
                    $mainImg     = $gallery.find('.styler-swiper-slide-first'),
                    $oMainImg    = $mainImg.find('img'),
                    $oZoomImg    = $mainImg.find('img.zoomImg'),
                    $oZoomSrc    = $oMainImg.attr('src'),
                    $popupSrc    = $mainImg.attr('data-src'),
                    $oThumbImg   = $('.styler-product-thumbnails .swiper-slide:first-child img'),
                    $hasThumbs   = $mainImg.attr('data-thumb') ? true : false,
                    $oThumbSrc   = $hasThumbs ? $mainImg.attr('data-thumb') : $popupSrc,
                    resetBtn     = $('.styler-btn-reset.reset_variations'),
                    $mainSkuHtml = $('.styler-sku-wrapper .sku'),
                    $mainSku     = $mainSkuHtml.html();

                    $('.styler-product-summary form.variations_form').on('show_variation', function( event, data ){
                        if ( data.sku ) {
                            $mainSkuHtml.html(data.sku);
                        }
                        resetBtn.addClass( 'active' );
                        var fullsrc = data.image.full_src;
                        var src     = data.image.src;
                        var tsrc    = data.image.gallery_thumbnail_src;
                        $mainImg.attr('data-src',fullsrc);
                        $oMainImg.attr('src',src);
                        $oZoomImg.attr('src',fullsrc);
                        if ( $hasThumbs ) {
                            $oThumbImg.attr('src',tsrc);
                        } else {
                            $oThumbImg.attr('src',fullsrc);
                        }
                        setTimeout( function() {
                            if ( !$oMainImg.hasClass('active') ) {
                                galleryMain.slideTo(0);
                                galleryThumbs.slideTo(0);
                            }
                            galleryMain.update();
                            galleryMain.updateAutoHeight(10);
                            galleryThumbs.update();
                            initZoom('reinit',fullsrc);
                        }, 100 );
                    });

                    $('.styler-product-summary form.variations_form').on('hide_variation', function(){
                        $mainSkuHtml.html($mainSku);
                        resetBtn.removeClass( 'active' );
                        $mainImg.attr('data-src',$popupSrc);
                        $oMainImg.attr('src',$oZoomSrc);
                        $oZoomImg.attr('src',$oZoomSrc);
                        $oThumbImg.attr('src',$oThumbSrc);

                        setTimeout( function() {
                            if ( !$oMainImg.hasClass('active') ) {
                                galleryMain.slideTo(0);
                                galleryThumbs.slideTo(0);
                            }
                            galleryMain.update();
                            galleryMain.updateAutoHeight(10);
                            galleryThumbs.update();
                            initZoom('reinit',$oZoomSrc);
                        }, 100 );
                    });

                    initZoom('load');

                    /**
                    * Init zoom.
                    */
                    function initZoom($action,$url) {
                        if ( 'function' !== typeof $.fn.zoom && !wc_single_product_params.zoom_enabled ) {
                            return false;
                        }

                        var galleryWidth = $('.styler-product-gallery-main-slider .swiper-slide').width(),
                        zoomEnabled  = false,
                        zoom_options = {
                            touch: false
                        };

                        if ( 'ontouchstart' in document.documentElement ) {
                            zoom_options.on = 'click';
                        }

                        $('.styler-product-gallery-main-slider .swiper-slide img').each( function( index, target ) {
                            var image = $( target );
                            var imageIndex = image.parents('.swiper-slide');

                            if ( image.attr( 'width' ) > galleryWidth ) {
                                if ( $action == 'load' ) {
                                    zoom_options.url = image.parent().data('zoom-img');
                                    image.wrap('<span class="styler-zoom-wrapper" style="display:block"></span>')
                                    .css('display', 'block')
                                    .parent()
                                    .zoom(zoom_options);
                                } else {
                                    image.trigger('zoom.destroy').unwrap();
                                    zoom_options.url = imageIndex.hasClass('styler-swiper-slide-first') ? $url : image.parent().data('zoom-img');
                                    image.wrap('<span class="styler-zoom-wrapper" style="display:block"></span>')
                                    .css('display', 'block')
                                    .parent()
                                    .zoom(zoom_options);
                                }
                            }
                        });
                    }
                }
            }
        }
    }
});
