function set_categories(node, selected, event) {
    $('#product_choose_category_category').val(selected.node.id);
    $('#new_category').text(selected.node.text);
}
$('#jstree').jstree({
    core: {
        data: Bridge.js_tree_data
    },
    checkbox: {
        kepp_selected_style: false,
        tie_selection: false
    }
}).on("select_node.jstree", set_categories);
