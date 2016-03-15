$(document).ready(function () {
    var url = $('#treeCategory').data('url');
    var route_name = $('#treeCategory').data('route_name');
    var is_admin = $('#treeCategory').data('is_admin');
    $('#treeCategory').jstree({
        core: {
            data: {
                url: url+'?route_name='+route_name+'&is_admin='+is_admin
            }
        }
    });

    Exp.historical('#treeCategory a, div.breadcrumbs a:last, ul.pagination a', 'div.history_category');
});