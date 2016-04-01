function set_categories(node, selected, event) {
    if(Bridge.on_init){
        Bridge.on_init = false;
        return;
    }
    var prototype = $('#registration_request_categories').data('prototype');
    var all = $('#jstree').jstree(true).get_bottom_checked(true);
    var html = '';
    for (key in all) {
        html += prototype.replace(/__name__/g, key).replace('/>', 'value="' + all[key].id + '" />');
    }
    $('#registration_request_categories').html(html);
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
    })
    .on("check_node.jstree", set_categories)
    .on('uncheck_node.jstree', set_categories)
    .on('ready.jstree', function () {
        $('#registration_request_categories input').each(function (index, input) {
            Bridge.on_init = true;
            $('#jstree').jstree(true).check_node([input.value]);
        });
    });