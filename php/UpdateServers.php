<?php
  require_once "Application.php";
  require_once "DBPing.php";

  # This script should execute every minute and will ping all servers
  if (php_sapi_name() != 'cli') {
    die("This script may only be invoked over the command line!");
  }

  $app = new Application("../config/config.json");
  $pinger = new MinecraftPing(30000);

  for ($i = 0; $i < count($app->getServers()); $i++) {
    $server = $app->getServers()[$i];

    $ping = new DBPing();
    $ping->makePing($server);
    $ping->toDatabase($app->dbc());
    print_r($ping);
  }
?>