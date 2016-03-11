Exp.jstree = function (selector, data, plugins) {
    return $(selector).jstree({
        core: {
            data: data
        },
        plugins: plugins
    });
};