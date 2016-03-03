$(document).ready(function(){
    Exp.jstree('#treeCategory', Bridge.js_tree_data);
    Exp.historical('#treeCategory a, div.breadcrumbs a:last, ul.pagination a', 'div.history_category');
});