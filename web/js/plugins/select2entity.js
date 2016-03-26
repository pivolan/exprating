$(document).ready(function () {
  $.fn.select2entity = function(action) {
    if(action){
      return this.select2(action);
    }
    this.select2({
      ajax: {
        data: function (params) {
          return {
            q: params.term
          };
        },
        processResults: function (data) {
          return {
            results: data
          };
        }
      }
    });
    return this;
  };

  $('.select2entity').select2entity();
});
