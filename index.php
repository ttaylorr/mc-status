<?php
  include_once "php/AnalyticsTracking.php";

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
    <header class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Minecraft Server Tracking</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li>
              <a href="http://ttaylorr.com">Made with <span style="color:red;">&hearts;</span> by ttaylorr</a>
            </li>
          </ul>
        </div>
      </div>
    </header>
    <div class="container">
      <h2>Tracked Servers <small>Updated every minute</small></h2>
      <?php
        echo($view->buildServerTables());
        $charter = new Charter($app);
      ?>
      <div class="row row-fluid" id="mojang-status">
        <div class="col-md-3">
          <div class="alert alert-warning" data-service="minecraft.net" data-name='minecraft.net'></div>
        </div>
        <div class="col-md-3">
          <div class="alert alert-warning" data-service="auth.mojang.com" data-name="Login Server"></div>
        </div>
        <div class="col-md-3">
          <div class="alert alert-warning" data-service="sessionserver.mojang.com" data-name="Session Server"></div>
        </div>
        <div class="col-md-3">
          <div class="alert alert-warning" data-service="skins.minecraft.net" data-name="Skin Server"></div>
        </div>
      </div>
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
    <footer style="padding-top: 48px;text-align:center;">
      <div class="container text-muted">
        Check us out on <a href="https://github.com/ttaylorr/mc">Github</a>!<br/>
        &copy; <a href="http://ttaylorr.com">Taylor Blau</a> 2014, All Rights Reserved<br/><br/>
        <?php echo $app->rev(); ?>
      </div>
    </footer>
  </body>
</html>
