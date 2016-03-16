Exp.form_ajax = function (selectorForm, selectorUpdate, callback, callback201) {
    $(document).on('submit', selectorForm, function (event) {
        event.preventDefault();
        $(this).ajaxSubmit({
            success: function (response) {
                $(selectorUpdate).html(response);
                if (callback) {
                    callback();
                }
            },
            statusCode: {
                201: callback201
            }
        });
    });
};