<?php

  require_once 'Application.php';
  require_once 'DBServer.php';
  require_once 'DBPing.php';
  require_once 'MinecraftPing.php';

  class Charter {
    private $app;

    function __construct($app) {
      $this->app = $app;
    }

    function render() {
      $chart = array();
      $chart['zoomEnabled'] = true;

      $data = array();
      foreach ($this->app->getServers() as $server) {
        $server_data = array();
        $server_data['type'] = 'line';
        $server_data['showInLegend'] = true;
        $server_data['lineThickness'] = intval(3);
        $server_data['name'] = $server->getName();

        $points = array();
        $x = 0;
        foreach ($server->getPings() as $ping) {
          $points[] = array('label' => $server->getName(), 'x' => $x, 'y' => intval($ping->getPlayers()));
          $x++;
        }

        $server_data['dataPoints'] = $points;
        $data[] = $server_data;
      }

      $chart['data'] = $data;
      $chart['legend']['cursor'] = 'pointer';
      return json_encode($chart);
    }
  }
?>
