$(document).on('click', '#fileupload', function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            if (data.result.filename) {
                $('#user_profile_avatarImage').val(data.result.filename);
                $('.expert-img-wrapper img').attr('src', data.result.filename);
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
});
