Exp.form_ajax = function (selectorForm, selectorUpdate, callback, callback201) {
    $(document).on('submit', selectorForm, function (event) {
        event.preventDefault();
        var $html = $(selectorUpdate);
        var $loader = $('<span class="loader-ajax"></span>');
        $html.css('opacity', '0.5');
        $loader.css('top', window.innerHeight/2+window.scrollY);
        $('body').append($loader);
        $(this).ajaxSubmit({
            success: function (response) {
                $html.css('opacity', '1');
                $loader.remove();
                $(selectorUpdate).html(response);
                if (callback) {
                    callback();
                }
            },
            statusCode: {
                201: callback201
            }
        });
    });
};