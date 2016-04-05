function update_head_group(object) {
    console.log(object);
    var head_group = $(object).parent().parent().find('input.class_title').eq(0).val();
    console.log(head_group);
    $(object).find('div.headGroup input').each(function (index, object2) {
        object2.value = head_group;
    });
}

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
        update_positions('div.orderIndex input');
        html.find('select').append('<option value="' + slug + '" selected>' + name + '</option>').each(function (index, select) {
            select.value = slug;
        });

        $('.select2entity').select2entity();

        $.colorbox.close();
    });
    $('.history_category').sortable({connectWith: '.sortable', items: '.sortable > div'});

    $(document).on('sortupdate', '.history_category', function (e, ui) {
        console.log('update');
        update_positions('div.orderIndex input');
        update_head_group($(ui.item));
    });

    $(document).on('click', 'button.add_new', function () {
        $('#characteristicGroups').append('<div class="col-md-6">\
                <input type="text" value="" placeholder="Название группы" class="form-control class_title"/>\
                <button class="btn btn-danger btn-xs remove"><span class="glyphicon glyphicon-trash"></span></button>\
                <div class="sortable list-group well">\
                    <div></div>\
                    <button class="btn btn-info btn-sm add_param" type="button">Добавить характеристику</button>\
                </div>\
                </div>');
        $('.sortable').sortable({connectWith: '.sortable', items: '> div'});
    });
    $(document).on('click', 'button.add_param', function () {
        var $this = $(this);
        var prototype = $('#characteristics').data('prototype');
        var index = $('#characteristics').find('select').length;
        var html = $(prototype.replace(/__name__/g, index));
        $this.before(html);
        update_head_group(html);
        update_positions('div.orderIndex input');

        $('.select2entity').select2entity();
    });
    $(document).on('click', 'button.remove', function () {
        $(this).parent().remove();
    });
    $(document).on('change', 'input.class_title', function (e) {
        var $this = $(this);
        $this.parent().find('.headGroup input').each(function (index, object) {
            object.value = e.target.value;
            console.log(object);
        });
    });
});