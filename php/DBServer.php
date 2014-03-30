<?php

  class DBServer {

    const NAME_FIELD = "name";
    const IP_FIELD = "ip";
    const WEBSITE_FIELD = "website";

    private $name;
    private $ip_addr;
    private $website;

    function __construct($row) {
      $this->name = $row[self::NAME_FIELD];
      $this->ip_addr = $row[self::IP_FIELD];
      $this->website = $row[self::WEBSITE_FIELD];
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
  }
?>