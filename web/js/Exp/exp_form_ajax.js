Exp.form_ajax = function (selectorForm, selectorUpdate, callback) {
    $(document).on('submit', selectorForm, function (event) {
        event.preventDefault();
        $(this).ajaxSubmit({
            success: function (response) {
                $(selectorUpdate).html(response);
                callback();
            }
        });
    });
};