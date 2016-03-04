Exp.historical('.edit-menu a', 'div.content-inner.clearfix');

$(document).on('click', 'button[data-type="add"]', function (event) {
    var $this = $(this);
    var html = $this.data('prototype');
    $this.parent().before(html);
});
$(document).on('click', 'button[data-type="remove"]', function (event) {
    var $this = $(this);
    $this.parent().remove();
});
$(document).on('click', 'button[data-type="add-ch"]', function (event) {
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
    var $this = $(this);
    $this.parent().parent().remove();
});
$(document).on('submit', 'form[name="product"],form[name="product_change_expert"]', function (event) {
    event.preventDefault();
    var buttonId = event.originalEvent.explicitOriginalTarget.id;
    var data = {};
    if (buttonId == 'product_publish') {
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

$('#fileupload').fileupload({
    dataType: 'json',
    done: function (e, data) {
        if (data.result.filename) {
            var $htmlImage = $('div.product-images div[data-type="prototype"]').clone();
            $htmlImage.find('img').attr('src', data.result.filename);
            $htmlImage.removeClass('hidden').removeAttr('data-type');
            var $htmlForm = $('div.image-form > div[data-type="prototype"]');
            var htmlForm = $htmlForm.outerHTML();
            var indexNext = $('div.image-form > div').length - 1;
            $htmlImage.find('button').data('image_id', indexNext);
            $htmlImage.find('input.image-is-main').val('product_images_' + indexNext + '_isMain');
            $('div.product-images').append($htmlImage);
            var htmlNew = htmlForm.replaceAll('__name__', indexNext).replaceAll('__empty__', '');
            var $htmlNew = $(htmlNew).removeAttr('class').removeAttr('data-type');
            $htmlNew.find("input:first").val(data.result.filename);
            $htmlNew.find("input:last").val(0);
            $('div.image-form').append($htmlNew);
        }
    },
    progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress').css(
            'width',
            progress + '%'
        );
    }
});
$(document).on('click', '.image-remove', function (event) {
    var imageId = $(this).data('image_id');
    $(this).parent().remove();
    $('.image-form div[data-index="' + imageId + '"]').remove();
});
$(document).on('change', 'input.image-is-main[type="radio"]', function (event) {
    $('.image-form input[name*="isMain"]').val(0);
    var selector = $(this).val();
    $('#' + selector).val(1);
});