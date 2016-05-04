function message(text){
	$('#alert-message').hide();
	$('#alert-message p').text(text);
	$('#alert-message').fadeIn('slow');
	setTimeout(function(){
		$('#alert-message').fadeOut('slow');
	}, 3000);
}
$('document').ready(function(){
    var dd = $('.panel-body');
    dd.find('.images50').each(function(){
        var d = $(this);
        var url = d.attr('data-url');
        d.find('.partner-picture').each(function(){
            var th = $(this);
            var src = th.find('img').attr('src');
            th.find('.image-zoom-in').colorbox({href: src});
            th.find('.image-import').click(function(){
                $.ajax({
                    url: url,
                    method: 'post',
                    data: {urls: [src]},
                    success: function(){
                        th.addClass('disable');
                        message('Импорт картинки прошел успешно');
                    }
                });
            });
        });
        var data = [];
        d.find('.text-center button').click(function(){
            d.find('.partner-picture').each(function(){
                data.push($(this).find('img').attr('src'));
            });
            $.ajax({
                url: url,
                method: 'post',
                data: {urls: data},
                success: function(){
                    d.find('.partner-picture').addClass('disable');
                    message('Импорт картинок прошел успешно');
                }
            });
        });
    });
});