(function($) {
    "use strict";
    $(document).ready(function($) {
        $(document).on("click", '.button.knowx-flush-font-files', function(e) {
            e.preventDefault();
            var data = {
                'action': 'knowx_regenerate_fonts_folder',
            };

            $.post(ajaxurl, data, function(response) {});
        });
    });
}(jQuery));