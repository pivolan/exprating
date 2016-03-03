$(document).ready(function () {
    Exp.hide_menu_on_click('#mainSiteMenu a');
    Exp.historical(
        '.rubric-filters ul.dropdown-menu li a, ul.pagination li a, #mainSiteMenu a, .category-child a, .breadcrumbs a',
        'div.content')
});