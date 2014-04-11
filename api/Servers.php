<?php
  require_once 'APIBase.php';
  require_once './php/DBServer.php';
  require_once './php/DBPing.php';

  class Servers extends APIBase {
    public static function call() {
      $data = array();
      print_r($_GET);
      foreach(parent::app()->getServers() as $server) {
        $pings = array();
        foreach($server->getPings() as $ping) {
          $pings[] = array(
            'players' => $ping->getPlayers(),
            'maxPlayers' => $ping->getMaxPlayers(),
            'responseTime' => $ping->getPing(),
            'queryTime' => $ping->getTime()
          );
        }

        $data[$server->getName()] = array(
          'name' => $server->getName(),
          'ip' => $server->getIPAddress(),
          'website' => $server->getWebsite(),
          'pings' => $pings
        );

      }

      return $data;
    }
  }
?>