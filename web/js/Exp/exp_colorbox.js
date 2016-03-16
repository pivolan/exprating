Exp.colorbox = function (selector) {
    $(document).on('click', selector, function () {
        var url = $(selector).data('url');
        $.colorbox({href: url})
    });
};