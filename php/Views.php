<?php

require_once "DBServer.php";

class Views {
  private $app;

  function __construct($app) {
    $this->app = $app;
  }

  function buildServerTables() {
    $table = "<table class='table table-hover'>";
      $table .= "<thead>";
        $table .= "<tr>";
          $table .= "<th>#</th>";
          $table .= "<th>Server Name</th>";
          $table .= "<th>Minecraft IP</th>";
          $table .= "<th>Website</th>";
          $table .= "<th>Players</th>";
        $table .= "</tr>";
      $table .= "</thead>";
      $table .= "<tbody>";
        $i = 1;
        $servers = $this->app->getServers();
        uasort($servers, function($s1, $s2) {
          return $s2->getMostRecentPing()->getPlayers() - $s1->getMostRecentPing()->getPlayers();
        });

        foreach ($servers as $server) {
          $table .= "<tr>";
          $table .= "<td>".$i."</td>";
          $table .= "<td class='serverName'>".$server->getName()."</td>";
          $table .= "<td>".$server->getIpAddress()."</td>";
          $table .= "<td><a href='".$server->getWebsite()."'>".$server->getWebsite()."</a></td>";

          $pings = $server->getPings();

          $improvement = -1;
          if (is_array($pings) && ! empty($pings)) {
            $improvement = $server->getMostRecentPing()->getPlayers() - $pings[0]->getPlayers();
          } else {
            $improvement = 0;
          }

          $gain = $server->getName() . ' has ' . ($improvement >= 0 ? 'gained' : 'lost') . ' ' . abs($improvement) . ' players over the last 24 hours.';
          $icon = "<span class='glyphicon glyphicon-arrow-" . ($improvement >= 0 ? 'up' : 'down') . "'></span>";

          $table .= "<td class='serverPlayers' rel='tooltip' data-players='" . $server->getMostRecentPing()->getPlayers() . "' data-toggle='tooltip' data-placement='top' data-container='body' title='$gain'> $icon <span class='text'>".$server->getMostRecentPing()->getPlayers(). " / " . $server->getMostRecentPing()->getMaxPlayers() . "</span></td>";
          $table .= "</tr>";
          $i++;
        }
      $table .= "</tbody>";
    $table .= "</table>";

    return $table;
  }
}
