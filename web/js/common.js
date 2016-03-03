$(document).ready(function () {

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");
    });


    $(".navbar-toggle").click(function () {
        API.open();
        return false;
    });

    var fixed = false;
    $(document).scroll(function () {
        var scroll = $(this).scrollTop();
        if (scroll > 52) {
            if (!fixed) {
                fixed = true;
                $('#top-menu').css({position: 'fixed', top: 0, 'width': '100%', 'z-index': 2100});
                $('#header').css({'margin-top': '31px'});
                $('.front #page').css({'background': '31px'});
            }

            if (scroll > 80) {
                $('#top-menu').css({'opacity': 0.95});
            }
        }
        else {
            if (fixed) {
                fixed = false;
                $('#top-menu').css({position: 'static', 'width': '100%'});
                $('#header').css({'margin-top': 0});
            }

            if (scroll <= 10) {
                $('#top-menu').css({'opacity': 1});
            }
        }
    });

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(document).on('click', '.message-box .close', function () {
        $(this).parent().hide();
    });
});
