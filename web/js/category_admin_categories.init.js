$(document).ready(function(){
    Exp.jstree('#treeCategory', Bridge.js_tree_data);
    Exp.historical('#treeCategory a, div.breadcrumbs a:last', 'div.history_category');
    Exp.form_ajax('form[name="rating_settings"]', 'div.history_category');
});