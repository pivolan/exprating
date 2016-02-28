$(document).on('click', '.rubric-filters ul.dropdown-menu li a, ul.pagination li a, #mainSiteMenu a, .category-child a, .breadcrumbs a', function (event) {
    event.preventDefault();
    var $this = this;
    var href = $($this).attr('href');
    History.pushState(null, null, href);
});
History.Adapter.bind(window, 'statechange', function () {
    var State = History.getState();
    History.log(State.data, State.title, State.url);
    $('div.content').load(State.url);
});