<?php

  class DBServer {

    const NAME_FIELD = "name";
    const IP_FIELD = "ip";
    const WEBSITE_FIELD = "website";

    private $name;
    private $ip_addr;
    private $website;
    private $pings = array();

    function __construct($row, $dbc) {
      $this->name = $row[self::NAME_FIELD];
      $this->ip_addr = $row[self::IP_FIELD];
      $this->website = $row[self::WEBSITE_FIELD];
      $this->populatePings($dbc);
    }

    private function populatePings($dbc) {
      $result = $dbc->query("SELECT * FROM pings WHERE server_name = '".$this->name."'");
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