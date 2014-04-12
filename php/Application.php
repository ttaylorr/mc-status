<?php
  require_once "DBServer.php";

  class Application {
    private $db = null;
    private $servers = array();

    function __construct($config_path, $deeplyPopulate = true) {
      $config = json_decode(file_get_contents($config_path), true);

      $hostname = $config['hostname'];
      $db_name = $config['db-name'];
      $username = $config['username'];
      $password = $config['password'];

      $this->db = new mysqli($hostname, $username, $password, $db_name);
      $this->populateServers($deeplyPopulate);
    }

    private function populateServers($deeplyPopulate) {
      $result = $this->db->query("SELECT * FROM servers");
      
      while($row = $result->fetch_assoc()) {
        $this->servers[] = new DBServer($row, $this->dbc(), $deeplyPopulate);
      }
    }

    function allPings() {
      return $this->db->query("SELECT * FROM pings")->fetch_assoc();
    }

    function dbc() {
      return $this->db;
    }

    function getServers() {
      return $this->servers;
    }

    function rev() {
      $short = `git rev-parse --short HEAD`;
      $long = `git rev-parse HEAD`;

      return "Current Revision: <a href='https://github.com/ttaylorr/mc/commit/".$long."'>".$short."</a>";
    }
  }
?>