<?php
  require_once __DIR__ . '/vendor/autoload.php';
  require_once "./api/Servers.php";
  require_once "./api/Services.php";

  require_once "php/Application.php";
  require_once "php/Views.php";
  require_once "php/DBServer.php";
  require_once "php/Charter.php";
 
  $app = new Application("./config/config.json");
  $view = new Views($app);

  $klein = new \Klein\Klein();

  $klein->respond('GET', '/api/services/?', function() {
    Services::invoke();
  });

  $klein->respond('GET', '/api/servers/?', function() {
    Servers::invoke();
  });

  $klein->dispatch();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minecraft Server Tracking</title>
    <link href="css/min/bootstrap.min.css" rel="stylesheet">
    <link href="css/min/app.min.css" rel="stylesheet">
  </head>

  <body>
    <?php include_once 'php/AnalyticsTracking.php'; ?>
    <?php include 'static/Header.php'; ?>
    <div class="container">
      <h2>Tracked Servers <small>Updated every minute</small></h2>

      <?php
        echo($view->buildServerTables());
        $charter = new Charter($app);
      ?>

      <?php include 'static/Mojang.php'; ?>

      <ul class="nav nav-tabs">
        <li class="active"><a href="#" data-name="line">Line Graph</a></li>
        <li><a href="#" data-name="scatter">Scatter Chart</a></li>
        <li><a href="#" data-name="stackedArea">Stacked Area</a></li>
      </ul>

      <div id="chart" style="height: 525px; width: 100%;"></div>

      <script src="js/min/jquery-2.1.0.min.js"></script>
      <script src="js/min/bootstrap.min.js"></script>
      <script src="js/min/canvasjs.min.js"></script>
      <script src="js/min/app.min.js"></script>

      <script>
        renderPage(<?php echo $charter->render(); ?>);
      </script>

    </div>

    <?php include 'static/Footer.php'; ?>

  </body>
</html>
