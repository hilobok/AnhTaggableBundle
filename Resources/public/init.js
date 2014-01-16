(function($) {
    $(function() {
        $('[data-tagit]').each(function(key, element) {
            var options = $(element).data('tagit');
            $(element).tagit(options);
        });
    });
})(jQuery);