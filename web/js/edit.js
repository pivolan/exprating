$(document).on('submit', 'form[name="product"]', function (event) {
    event.preventDefault();
    $(this).ajaxSubmit({
        success: function (response) {
            $('div.content-inner.clearfix').html(response);
        }
    });
});