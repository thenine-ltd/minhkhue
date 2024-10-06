(function ($) {
    "use strict";

    $(document).on('stylerShopInit', function () {
        stylerWcProductCats();
    });

    function stylerWcProductCats() {

        $('.widget_styler_product_categories ul.children input[checked]').closest('li.cat-parent').addClass("current-cat");
        
        $('body').off('click', '.subDropdown').on('click', '.subDropdown', function () {
            $(this).toggleClass("plus"),
            $(this).toggleClass("minus"),
            $(this).parent().find("ul").slideToggle();
        });
    }

    $(document).ready(function() {
        stylerWcProductCats();
    });

})(jQuery);
