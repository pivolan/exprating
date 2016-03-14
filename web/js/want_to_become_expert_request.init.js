function set_categories(node, selected, event) {
    console.log(selected);
    var all = $('#jstree').jstree(true).get_bottom_checked(true);
    var html = '';
    for (key in all) {
        html += '<option value="' + all[key].id + '" selected>' + all[key].text + '</option>';
    }
    $('#create_expert_request_categories').html(html);
}
$('#jstree').jstree({
    core: {
        data: Bridge.js_tree_data
    },
    checkbox: {
        kepp_selected_style: false,
        tie_selection: false
    },
    plugins: ["checkbox"]
}).on("check_node.jstree", set_categories).on('uncheck_node.jstree')
;
