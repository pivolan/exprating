$(document).on('click', '.edit-menu a', function (event) {
    event.preventDefault();
    var $this = this;
    var href = $($this).attr('href');
    History.pushState(null, null, href);
});
History.Adapter.bind(window, 'statechange', function () {
    var State = History.getState();
    History.log(State.data, State.title, State.url);
    $('div.content-inner.clearfix').load(State.url);
});
$(document).on('submit', 'form[name="comment"]', function (event) {
    event.preventDefault();
    $(this).ajaxSubmit({
        success: function (response) {
            $('div.content-inner.clearfix').html(response);
        }
    });
});