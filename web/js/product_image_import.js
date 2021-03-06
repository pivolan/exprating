//Всплывающее сообщение
function message(text) {
    $('#alert-message').hide();
    $('#alert-message p').text(text);
    $('#alert-message').fadeIn('slow');
    setTimeout(function () {
        $('#alert-message').fadeOut('slow');
    }, 3000);
}
//Отправка данных об импорте картинки
function importImages(srcList) {
    $.ajax({
        url: $('#accordion').data('url'),
        method: 'post',
        dataType: 'json',
        data: {urls: srcList},
        success: function (data) {
            if (data.status == 'ok') {
                for (i in srcList) {
                    var src = srcList[i];
                    $('img[src="' + src + '"]').addClass('disable');
                    addImage(data.srcList[i]);
                    message('Импорт картинки прошел успешно');
                }
            } else {
                message('При импорте возникли ошибки: ' + data)
            }
        }
    });
}
$('document').ready(function () {
    //Импорт по одной картинке
    $(document).on('click', 'button.image-import', function () {
        var src = $(this).parent().find('img').attr('src');
        importImages([src]);
    });
    //Просмотр картинки, увеличение
    $(document).on('click', 'button.image-zoom-in', function () {
        var src = $(this).data('src');
        $.colorbox({href: src});
    });
    //Импорт сразу всех картинок
    $(document).on('click', 'button.image-import-all', function () {
        var srcList = [];
        $(this).parent().parent().find('img').each(function (index, img) {
            if (!$(img).parent().hasClass('disable')) {
                srcList.push(img.src);
            }
        });
        if (srcList.length > 0) {
            importImages(srcList);
        }
    });
});