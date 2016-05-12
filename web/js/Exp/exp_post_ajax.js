Exp.post_ajax = function (selectorButton, callback) {
    //button must have data-url, data-src, data-target.
    //url to send request
    //src - input id
    //target - content element id, response will replace its content
    $(document).on('click', selectorButton, function (event) {
        event.preventDefault();
        var $this = $(this);
        var contentId = $this.data('target');
        var $html = $('#' + contentId);
        var $input = $('#' + $this.data('src'));
        var key = $input.attr('name');
        var data = {};
        data[key] = $input.val();
        var $loader = $('<span class="loader-ajax"></span>');
        $html.css('opacity', '0.5');
        $loader.css('top', window.innerHeight / 2 + window.scrollY);
        $('body').append($loader);
        $.ajax({
            url: $this.data('url'),
            method: 'post',
            data: data,
            success: function (response) {
                $html.css('opacity', '1');
                $loader.remove();
                $html.html(response);
                if (callback) {
                    callback();
                }
            },
            error: function () {
                alert('it was some error, try again.');
            }
        });
    });
};