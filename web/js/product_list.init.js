$(document).ready(function () {
    Exp.hide_menu_on_click('#mainSiteMenu ul.menu_level_1 a');
    Exp.historical(
        '.rubric-filters a, ul.pagination li a, #mainSiteMenu a, .category-child a, .breadcrumbs a',
        'div.content')
});