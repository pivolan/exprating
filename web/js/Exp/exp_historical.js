Exp.historical = function (selectorClick, selectorUpdate, callback) {
    $(document).on('click', selectorClick, function (event) {
        var $this = $(this);
        var href = $this.attr('href');
        if (href != '/') {
            History.pushState(null, null, href);
            event.preventDefault();
        }
    });
    History.Adapter.bind(window, 'statechange', function () {
        var State = History.getState();
        var $html = $(selectorUpdate);
        var $loader = $('<span class="loader-ajax"></span>');
        $html.css('opacity', '0.5');
        $loader.css('top', window.innerHeight/2+window.scrollY);
        $('body').append($loader);
        $html.load(State.url, function () {
            $html.css('opacity', '1');
            $loader.remove();
            callback();
        });
    });
};