jQuery(document).ready(function($) {

    /*-- Strict mode enabled --*/
    'use strict';

    function scrollToTop(target,delay,timeout) {
        setTimeout(function(){
            $('html, body').stop().animate({
                scrollTop: target.offset().top
            }, delay);
        }, timeout );
    }
    $('.styler-gallery-grid-item img').each( function() {
        var imgUrl = $(this).data('src');
        $(this).attr('src',imgUrl );
    });
    /**
    * singleGalleryGridVariations
    */
    singleGalleryGridVariations();
    function singleGalleryGridVariations() {
        if ( !$('.styler-product-main-gallery-grid .styler-gallery-grid-item-first img').length ) {
            return;
        }
        var $oMainImg      = $('.styler-product-main-gallery-grid .styler-gallery-grid-item-first img'),
            $oMainSrc      = $oMainImg.data('src'),
            $oMainSrcSet   = $oMainImg.data('srcset'),
            $oMainSrcSizes = $oMainImg.data('sizes'),
            gallery        = $('.styler-product-main-gallery-grid');

        $( document ).on('change','.styler-product-summary .variations_form select', function( e ) {
            var $this      = $(this),
                $form      = $this.parents('.variations_form'),
                variations = $form.data('product_variations');

            setTimeout( function() {
                var current_id = $form.attr('current-image'),
                    image,
                    src,
                    srcset,
                    sizes;

                $.map(variations, function(elementOfArray, indexInArray) {
                    if ( elementOfArray.image_id == current_id ) {
                        image  = elementOfArray.image;
                        src    = image.src;
                        srcset = image.srcset;
                        sizes  = image.sizes;
                    }
                });
                if ( current_id ) {
                    $oMainImg.attr('src',src);
                    $oMainImg.attr('data-src',src);
                    if ( srcset ) {
                        $oMainImg.attr('srcset',srcset);
                    }
                    if ( sizes ) {
                        $oMainImg.attr('sizes',sizes);
                    }
                    if ( styler_vars.scrolltop == 'yes' ) {
                        scrollToTop(gallery,300,300);
                    }
                    $('.styler-gallery-grid-item-first').attr('data-src',src);
                }
            }, 50 );
        });

        $( document ).on('click','.styler-product-summary .reset_variations', function( e ) {
            var $form   = $(this).parents('.variations_form'),
                gallery = $('.styler-product-main-gallery-grid');

            $oMainImg.attr('src',$oMainSrc);
            $oMainImg.attr('data-src',$oMainSrc);

            if ( $oMainSrcSet ) {
                $oMainImg.attr('srcset',$oMainSrcSet);
            }
            if ( $oMainSrcSizes ) {
                $oMainImg.attr('sizes',$oMainSrcSizes);
            }
            if ( styler_vars.scrolltop == 'yes' ) {
                scrollToTop(gallery,400,300);
            }
            $('.styler-gallery-grid-item-first').attr('data-src',$oMainSrc);
        });

        initZoom('load');

        /**
        * Init zoom.
        */
        function initZoom($action,$url) {
            if ( 'function' !== typeof $.fn.zoom && !wc_single_product_params.zoom_enabled ) {
                return false;
            }

            var galleryWidth = $('.styler-gallery-grid-item').width(),
                zoomEnabled  = false,
                zoom_options = {
                    touch: false
                };

            if ( 'ontouchstart' in document.documentElement ) {
                zoom_options.on = 'click';
            }

            $('.styler-gallery-grid-item img').each( function( index, target ) {
                var image      = $( target );
                var imageIndex = image.parent();

                if ( image.attr( 'width' ) > galleryWidth ) {
                    if ( $action == 'load' ) {
                        zoom_options.url = image.parent().data('zoom-img');
                        image.wrap('<span class="styler-zoom-wrapper" style="display:block"></span>')
                          .css('display', 'block')
                          .parent()
                          .zoom(zoom_options);
                    } else {
                        image.trigger('zoom.destroy').unwrap();
                        image.wrap('<span class="styler-zoom-wrapper" style="display:block"></span>')
                          .css('display', 'block')
                          .parent()
                          .zoom(zoom_options);
                    }
                }
            });
        }
    }
});
