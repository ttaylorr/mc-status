#!/usr/bin/php -q
<?php
  require_once "Application.php";
  require_once "DBPing.php";

  # This script should execute every minute and will ping all servers
  if (php_sapi_name() != 'cli') {
    die("This script may only be invoked over the command line!");
  }

  $app = new Application("../config/config.json", false);
  $pinger = new MinecraftPing(30000);

  $start = time();

  foreach($app->getServers() as $server) {
    $ping = new DBPing($start);
    $ping->makePing($server);

    echo('Updated server \'' . $ping->getHostname() . '\' with response time ' . $ping->getPing() . 'ms.' . PHP_EOL);

    $ping->toDatabase($app->dbc());
  }
?>