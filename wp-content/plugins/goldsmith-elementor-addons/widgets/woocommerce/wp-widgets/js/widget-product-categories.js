(function ($) {
    "use strict";

    $(document).on('goldsmithShopInit', function () {
        goldsmithWcProductCats();
    });

    function goldsmithWcProductCats() {

        $('.widget_goldsmith_product_categories ul.children input[checked]').closest('li.cat-parent').addClass("current-cat");
        
        $('body').off('click', '.subDropdown').on('click', '.subDropdown', function () {
            $(this).toggleClass("plus"),
            $(this).toggleClass("minus"),
            $(this).parent().find("ul").slideToggle();
        });
    }

    $(document).ready(function() {
        goldsmithWcProductCats();
    });

})(jQuery);
