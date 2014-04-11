$(document).ready(function() {
  $.each($('#mojang-status .alert'), function (i, elm) {
    $(elm).html('Fetching status for <b>' + $(elm).attr('data-name') + '</b>');
  });

  $.getJSON('./api/services/current', function (data) {
    $.each($('#mojang-status .alert'), function (i, e) {
      var cur = $(e).attr('data-service');
      var service = data[cur];

      var name = $(e).attr('data-name');
      var className = service.bootstrapClass;
      var status;

      switch (service['status']) {
        case 'green':
          status = 'online';
          break;
        case 'yellow':
          status = 'having problems';
          break;
        case 'red':
          status = 'offline';
          break;
      }

      $(e).html(name + ' is <b>' + status + '</b>');
      $(e).removeClass();
      $(e).addClass('alert');
      $(e).addClass(service['bootstrapClass']);
    });
  });
});