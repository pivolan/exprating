$(document).ready(function () {
    callback = function () {
        $('.select2entity').select2entity();
    };
    Exp.jstree('#treeCategory', Bridge.js_tree_data);
    Exp.historical('#treeCategory a, div.breadcrumbs a:last', 'div.history_category', callback);
    Exp.form_ajax('form[name="category"]', 'div.history_category', callback);
    Exp.colorbox('#add_characteristic');
    Exp.form_ajax('form[name="characteristic"]', '#cboxLoadedContent', null, function () {
        var slug = $('#characteristic_slug').val();
        var name = $('#characteristic_name').val();
        var prototype = $('#characteristics').data('prototype');
        var index = $('#characteristics').find('select').length;
        var html = $(prototype.replace(/__name__/g, index));
        var $this = $('.sortable.list-group.well:first div.list-group-item:last');
        $this.after(html);
        update_head_group(html);
        update_positions();
        html.find('select').append('<option value="' + slug + '" selected>' + name + '</option>').each(function (index, select) {
            select.value = slug;
        });

        $('.select2entity').select2entity();

        $.colorbox.close();
    });
});