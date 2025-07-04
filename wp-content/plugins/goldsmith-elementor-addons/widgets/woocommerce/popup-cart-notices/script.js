'use strict';
 
(function($) {
  
  $(document.body).on('added_to_cart', function(e) {
      if ( jQuery('.goldsmith-popup-notices').length ) {
        setTimeout(function() {
            jQuery('.goldsmith-popup-notices').addClass('slide-in');
        }, 100);
        setTimeout(function() {
             jQuery('.goldsmith-popup-notices').removeClass('slide-in');
        }, 4000);
      }
  });
  
})(jQuery);

