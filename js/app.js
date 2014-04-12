var chart = null;

var updateIn = -1;

var startUpdate = function() {
  if (updateIn-- <= 0) {
    updateIn = 30;
  }

  var text = 'Updating in ' + updateIn + ' second';
  if (updateIn == 0) {
    text = 'Updating all data now';
    updateServers();
    updateMojangServices();
  } else if (updateIn != 1) {
    text += 's';
  }

  $('h2 small').text(text);
  setTimeout(startUpdate, 1000);
};

var updateServers = function() {
  $.getJSON('./api/servers', function(data) {
    if (chart !== null) {
      $.each(data, function (i, v) {
        $.each(chart.options.data, function (j, dv) {
          if (dv.name === v.name) {
            var temp = [];
            $.each(v.pings, function (k, ping) {
              temp.push({
                label: dv.name,
                x: ping.queryTime,
                y: ping.players
              });
            });
            dv.dataPoints = temp;
          }
        });
      });
    }
    doRender(chart);
  });
};

var updateMojangServices = function() {
  $.getJSON('./api/services', function (data) {
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
};

function renderPage(options) {
  startUpdate();

  // Since this data is not bundled with the static page, query onload
  $.each($('#mojang-status .alert'), function (i, elm) {
    $(elm).html('Fetching status for <b>' + $(elm).attr('data-name') + '</b>');
  });
  updateMojangServices();

  chart = new CanvasJS.Chart("chart", options);
  doRender(chart);

  $(".nav li a").click(function (e) {
    e.preventDefault();
    $(this).tab('show');
    for (var i = 0; i < chart.options.data.length; i++) {
      chart.options.data[i].type = $(e.target).attr('data-name');
    }
    doRender(chart);
  });
}

function doRender(chart) {
  chart.render();
  $(".canvasjs-chart-credit").remove();
}
