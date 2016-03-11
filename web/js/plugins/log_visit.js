$(document).ready(function () {
    var url = $('meta[name="log-visit"]').attr('content');
    if (url) {
        $.ajax(url);
    }
});
