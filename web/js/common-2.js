$(document).ready(function () {

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");
    });

    $("#mainSiteMenuMMenuNav").append($("#mainSiteMenu").clone().attr("id", "mainSiteMenuMMenu").attr("class", ""));
    $("#mainSiteMenuMMenuNav ul").removeAttr('class');
    $("#mainSiteMenuMMenuNav li").removeAttr('class');
    $("#mainSiteMenuMMenuNav a").removeAttr('class');
    $("#mainSiteMenuMMenuNav div").removeAttr('class');
    $("#mainSiteMenuMMenuNav").mmenu({
        configuration: {
            pageNodetype: "div"
        },
        extensions 	: [ "border-none" ],
        navbar 		: false,
        navbars		: {
            //content : [ "searchfield" ],
            height 	: 1
        },
        //searchfield: {
        //    add: true,
        //    placeholder: 'Поиск'
        //}
    });
    var API = $("#mainSiteMenuMMenuNav").data( "mmenu" );

    $(".navbar-toggle").click(function() {
        API.open();
        return false;
    });

    $('ul.sf-menu').superfish({
        autoArrows: false,
        delay:       0,
        animation:   {opacity:'show',height:'show'},
        speed:       'fast'
    });

    var fixed = false;
    $(document).scroll(function () {
        var scroll = $(this).scrollTop();
        if (scroll > 52) {
            if (!fixed) {
                fixed = true;
                $('#top-menu').css({position:'fixed', top: 0, 'width':'100%', 'z-index': 2100});
                $('#header').css({'margin-top': '31px'});
                $('.front #page').css({'background': '31px'});
            }

            if (scroll > 80) {
                $('#top-menu').css({'opacity':0.95});
            }
        }
        else {
            if (fixed) {
                fixed = false;
                $('#top-menu').css({position:'static', 'width':'100%'});
                $('#header').css({'margin-top': 0});
            }

            if (scroll <= 10) {
                $('#top-menu').css({'opacity':1});
            }
        }
    });

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    //$('#add-comment').click(function() {
    //    $('#add-comment-wrapper form').fadeToggle();
    //})


//    var inFilters = false;
//    $('#rubric-map-wrapper').mouseenter(function() {
//        $('#rubric-filters-wrapper').fadeIn();
//    });
//    $('#rubric-map-wrapper').mouseleave(function() {
//        $('#rubric-filters-wrapper').fadeOut();
//    })
//    $('#rubric-filters-wrapper').mouseover(function() {
//        inFilters = true;
//    })
//    $('#rubric-filters-wrapper').mouseleave(function() {
//        $('#rubric-filters-wrapper').fadeOut();
//        inFilters = false;
//    });

    //$('#more-comments-btn button').click(function() {
    //    $('#more-comments').toggle();
    //    $('#more-comments-btn').hide();
    //    $('#hide-more-comments-btn').show();
    //});
    //$('#hide-more-comments-btn button').click(function() {
    //    $('#more-comments').toggle();
    //    $('#hide-more-comments-btn').hide();
    //    $('#more-comments-btn').show();
    //});
    //
    //var moreAddress = false;
    //$('#more-address-btn button').click(function() {
    //    if (!moreAddress) {
    //        $('.address-wrapper-content .more').show();
    //        $(this).text('Скрыть адреса ⬆');
    //        moreAddress = true;
    //    } else {
    //        $('.address-wrapper-content .more').hide();
    //        $(this).text('Все адреса ⬇');
    //        moreAddress = false;
    //    }
    //});

});
