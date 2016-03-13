$(document).ready(function () {
    callback = function () {
        $('.select2entity').select2entity();
    };
    Exp.jstree('#treeCategory', Bridge.js_tree_data);
    Exp.historical('#treeCategory a, div.breadcrumbs a:last', 'div.history_category', callback);
    Exp.form_ajax('form[name="category"]', 'div.history_category', callback);
});