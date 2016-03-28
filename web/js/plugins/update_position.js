function update_positions(selector) {
    $(selector).each(function (index, object) {
        object.value = index;
        console.log(object);
    });
}