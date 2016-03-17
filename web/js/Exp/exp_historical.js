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
        $(selectorUpdate).load(State.url, callback);
    });
};