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
    // update the chart
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

    // update the table
    $.each(data, function (i, e) {
      var row = $("table tbody td.serverName:contains('" + e.name + "')").parent();
      var ping = (e.pings[e.pings.length - 1]);

      var current = ping.players;
      var max = ping.maxPlayers;

      var title = e.name + ' has ';

      var arrow = $('> td.serverPlayers span.glyphicon', row);

      if (current > e.pings[0].players) {
        title += 'gained ';
        arrow.addClass('glyphicon-arrow-up');
        arrow.removeClass('glyphicon-arrow-down');
      } else {
        title += 'lost ';
        arrow.addClass('glyphicon-arrow-down');
        arrow.removeClass('glyphicon-arrow-up');
      }

      title += Math.abs(current - e.pings[0].players) + ' players over the last 24 hours.'

      $('> td.serverPlayers span.text', row).text(current + ' / ' + max);
      $('> td.serverPlayers', row).attr('data-original-title', title);
    });

    // render them both
    doRender(chart);
    reorderServers();
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

function reorderServers() {
  var arr = $('table tbody tr');

  arr.sort(function(a, b) {
    var a = $('> .serverPlayers', a).attr('data-players');
    var b = $('> .serverPlayers', b).attr('data-players');

    return b - a; // descending
  });

  // redo the numberings
  arr.each(function (i, e) {
    $('> td', e).first().text(i + 1);
  });

  $('table tbody').append(arr);
}

function renderPage(options) {
  startUpdate();

  // Since this data is not bundled with the static page, query onload
  $.each($('#mojang-status .alert'), function (i, elm) {
    $(elm).html('Fetching status for <b>' + $(elm).attr('data-name') + '</b>');
  });
  updateMojangServices();

  $('[rel=\'tooltip\']').tooltip();

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
