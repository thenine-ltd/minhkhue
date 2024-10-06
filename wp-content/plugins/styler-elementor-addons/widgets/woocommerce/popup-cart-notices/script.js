'use strict';
 
(function($) {
  
  $(document.body).on('added_to_cart', function(e) {
      if ( jQuery('.styler-popup-notices').length ) {
        setTimeout(function() {
            jQuery('.styler-popup-notices').addClass('slide-in');
        }, 100);
        setTimeout(function() {
             jQuery('.styler-popup-notices').removeClass('slide-in');
        }, 4000);
      }
  });
  
})(jQuery);

