Exp.hide_menu_on_click = function (selectorClick) {
    $(document).on('click', selectorClick, function (event) {
        var $this = $(this);
        $this.parent().parent().hide();
        $(document).one('mousemove', function(){$this.parent().parent().removeAttr('style')});
        //setTimeout(function(){$this.parent().parent().removeAttr('style')}, 100);
    });
};