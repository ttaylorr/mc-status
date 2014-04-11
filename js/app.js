var chart = null;

setInterval(function() {
  $.getJSON('./api/servers', function(data) {
    if (chart !== null) {
      $.each(data, function (i, v) {
        $.each(chart.options.data, function (j, dv) {
          if (dv.name === v.name) {
            var temp = [];
            $.each(v.pings, function (k, ping) {
              var newping = {
                label: dv.name,
                x: ping.queryTime,
                y: ping.players
              };
              temp.push(newping);
            });
            console.log(temp);
            dv.dataPoints = temp;
          }
        });
      });
    }
    doRender(chart);
  });
}, 30000);

function renderPage(options) {
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
