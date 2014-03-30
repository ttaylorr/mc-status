<?php
  require_once "php/Application.php";
  require_once "php/Views.php";
  require_once "php/DBServer.php";

  $app = new Application();
  $view = new Views($app);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minecraft Server Tracking</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
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
        print_r($view->buildServerTables());
      ?>
    </div>
  </body>
</html>