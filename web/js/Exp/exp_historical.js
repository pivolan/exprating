Exp.historical = function (selectorClick, selectorUpdate, callback) {
    $(document).on('click', selectorClick, function (event) {
        event.preventDefault();
        var $this = $(this);
        var href = $this.attr('href');
        History.pushState(null, null, href);
    });
    History.Adapter.bind(window, 'statechange', function () {
        var State = History.getState();
        $(selectorUpdate).load(State.url, callback);
    });
};