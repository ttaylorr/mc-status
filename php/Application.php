<?php
  require_once "DBServer.php";

  class Application {
    private $db = null;
    private $servers = array();

    function __construct($config_path) {
      $config = json_decode(file_get_contents($config_path), true);

      $hostname = $config['hostname'];
      $db_name = $config['db-name'];
      $username = $config['username'];
      $password = $config['password'];

      $this->db = new mysqli($hostname, $username, $password, $db_name);
      $this->populateServers();
    }

    private function populateServers() {
      $result = $this->db->query("SELECT * FROM servers");
      
      while($row = $result->fetch_assoc()) {
        $this->servers[] = new DBServer($row);
      }
    }

    function dbc() {
      return $this->db;
    }

    function getServers() {
      return $this->servers;
    }
  }
?>