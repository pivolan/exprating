Exp.ajax_load = function (selector, callback) {
    //url to send request
    //target - content element selector, response will replace its content
    $(document).on('click', selector, function (event) {
        event.preventDefault();
        var $this = $(this);
        var contentSelector = $this.data('target');
        var $html = $(contentSelector);
        var $loader = $('<span class="loader-ajax"></span>');
        $html.css('opacity', '0.5');
        $loader.css('top', window.innerHeight / 2 + window.scrollY);
        $('body').append($loader);
        $.ajax({
            url: $this.data('url'),
            method: 'get',
            success: function (response) {
                $html.css('opacity', '1');
                $loader.remove();
                $html.html(response);
                if (callback) {
                    callback();
                }
            },
            error: function () {
                $loader.remove();
                alert('it was some error, try again.');
            }
        });
    });
};