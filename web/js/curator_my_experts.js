$(document).ready(function () {
    var url = $('#experts').data('url');
    $('#experts').jstree({
        core: {
            data: {
                url: url,
                data : function (node) {
                    return { "id" : node.id, "type": node.type };
                }
            }
        }
    });
});