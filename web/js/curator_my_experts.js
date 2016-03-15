$(document).ready(function () {
    var url = $('#experts').data('url');
    $('#experts').jstree({
        core: {
            data: {
                url: url,
                data: function (node) {
                    return {"id": node.id};
                }
            }
        }
    }).on("select_node.jstree", function (node, selected) {
        var href = selected.node.a_attr['data-href'];
        if (href)
            window.open(href, '_blank');
    });
});