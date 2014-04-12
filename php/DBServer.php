<?php

  require 'DBPing.php';

  class DBServer {

    const NAME_FIELD = "name";
    const IP_FIELD = "ip";
    const WEBSITE_FIELD = "website";

    private $name;
    private $ip_addr;
    private $website;
    private $pings = array();

    function __construct($row, $dbc, $deeplyPopulate = true) {
      $this->name = $row[self::NAME_FIELD];
      $this->ip_addr = $row[self::IP_FIELD];
      $this->website = $row[self::WEBSITE_FIELD];

      if ($deeplyPopulate) {
        $this->populatePings($dbc);
      }
    }

    private function populatePings($dbc) {
      $offset = time() - (24*60*60);
      # ignore pings that did not get responded to
      $query = "SELECT * FROM pings WHERE server_name = '".$this->name."' AND `time` >= " . $offset . " AND `players` != 0";
      $result = $dbc->query($query);
      while($row = $result->fetch_assoc()) {
        $ping = new DBPing();
        $ping->fromDatabase($row, $dbc);
        $this->pings[] = $ping;
      }
    }

    function getName() {
      return $this->name;
    }

    function getIpAddress() {
      return $this->ip_addr;
    }

    function getWebsite() {
      return $this->website;
    }

    function getPings() {
      return $this->pings;
    }
  }
?>