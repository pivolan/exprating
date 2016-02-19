jQuery.fn.outerHTML = function (s) {
    return s
        ? this.before(s).remove()
        : jQuery("<p>").append(this.eq(0).clone()).html();
};
String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};
$(document).on('click', 'button[data-type="add"]', function (event) {
    console.log('as');
    var $this = $(this);
    var html = $this.data('prototype');
    $this.parent().before(html);
});
$(document).on('click', 'button[data-type="remove"]', function (event) {
    console.log('sd');
    var $this = $(this);
    $this.parent().remove();
});
$(document).on('click', 'button[data-type="add-ch"]', function (event) {
    console.log('as');
    var $this = $(this);
    var $html = $('[data-type="characteristic"]:last');
    var index = $html.data('index');
    var indexNext = index + 1;
    var html = $html.outerHTML();
    var htmlNew = html.replaceAll('_' + index + '_', '_' + indexNext + '_').replaceAll('\[' + index + '\]', '[' + indexNext + ']').replaceAll('data-index="' + index + '"', 'data-index="' + indexNext + '"');
    $this.before(htmlNew);
    $this.parent().find('select:last').removeAttr('disabled');
    $this.parent().find('input:last').val('');
});
$(document).on('click', 'button[data-type="remove-ch"]', function (event) {
    console.log('sd');
    var $this = $(this);
    $this.parent().parent().remove();
});
$(document).on('submit', 'form[name="product"]', function (event) {
    event.preventDefault();
    var buttonId = event.originalEvent.explicitOriginalTarget.id;
    var data = {};
    if(buttonId == 'product_publish'){
        data = {'product[publish]': 'Сохранить'};
    }
    $(this).ajaxSubmit({
        data: data,
        success: function (response) {
            $('div.content-inner.clearfix').html(response);
        }
    });
});
$(document).on('click', '.dropdown-menu', function (event) {
    event.stopPropagation();
});
