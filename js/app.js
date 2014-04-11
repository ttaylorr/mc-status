function renderPage(options) {
  var chart = new CanvasJS.Chart("chart", options);
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
