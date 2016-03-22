function set_categories(node, selected, event) {
    $('#category_create_parent').val(selected.node.id);
    $('#parent_category').text(selected.node.text);
}
$(document).ready(function () {
    var url = $('#treeCategory').data('url');
    var route_name = $('#treeCategory').data('route_name');
    var is_admin = $('#treeCategory').data('is_admin');
    $('#treeCategory').jstree({
        core: {
            data: {
                url: url + '?route_name=' + route_name + '&is_admin=' + is_admin
            }
        }
    }).on("select_node.jstree", set_categories);
});
