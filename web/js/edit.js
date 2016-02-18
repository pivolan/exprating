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
$(document).on('submit', 'form[name="product"]', function (event) {
    event.preventDefault();
    $(this).ajaxSubmit({
        success: function (response) {
            $('div.content-inner.clearfix').html(response);
        }
    });
});