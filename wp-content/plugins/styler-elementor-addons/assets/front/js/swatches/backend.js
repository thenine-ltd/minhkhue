(
    function($) {
        'use strict';
        $(document).ready(function() {
            $('.styler_swatches_color').wpColorPicker();
            // Only show the "remove image" button when needed
            if ('' === jQuery('#styler_swatches_image').val()) {
                jQuery('#styler_swatches_remove_image').hide();
            }
            // Uploading files
            var file_frame_tm;
            jQuery(document).on('click', '#styler_swatches_upload_image', function(event) {
                event.preventDefault();
                // If the media frame already exists, reopen it.
                if (file_frame_tm) {
                    file_frame_tm.open();
                    return;
                }
                // Create the media frame.
                file_frame_tm = wp.media.frames.downloadable_file = wp.media({
                    title: 'Choose an image',
                    button: {
                        text: 'Use image',
                    },
                    multiple: false,
                });
                // When an image is selected, run a callback.
                file_frame_tm.on('select', function() {
                    var attachment = file_frame_tm.state().
                    get('selection').
                    first().
                    toJSON();
                    jQuery('#styler_swatches_image').val(attachment.id);
                    jQuery('#styler_swatches_image_thumbnail').
                    find('img').
                    attr('src', attachment.sizes.thumbnail.url);
                    jQuery('#styler_swatches_remove_image').show();
                });
                // Finally, open the modal.
                file_frame_tm.open();
            });
            jQuery(document).on('click', '#styler_swatches_remove_image', function() {
                jQuery('#styler_swatches_image_thumbnail').
                find('img').
                attr('src', styler_swatches_vars.placeholder_img);
                jQuery('#styler_swatches_image').val('');
                jQuery('#styler_swatches_remove_image').hide();
                return false;
            });
        });
    }
)(jQuery);
