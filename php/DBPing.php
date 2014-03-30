<?php

  require_once "MinecraftPing.php";

  class DBPing {

    const HOSTNAME_FIELD = 'hostname';
    const VERSION_FIELD  = 'version';
    const PLAYERS_FIELD = 'players';
    const MAX_PLAYERS_FIELD = 'maxplayers';
    const PING_FIELD = 'ping';
    const TIME_FIELD = 'time';

    const DEFAULT_VERSION = '1.7*';
    const DEFAULT_PORT = 25565;

    private $server;

    private $hostname;
    private $version;
    private $players;
    private $maxplayers;
    private $ping;

    private $time;
    
    # This function must be called in tandem with either loadFromDatabase, or createPing
    function __construct() {

    }

    function fromDatabase($row) {
      $this->hostname = idToHostname($row['server_id']);
      $this->version = $row[self::VERSION_FIELD];
      $this->players = $row[self::PLAYERS_FIELD];
      $this->maxplayers = $row[self::MAX_PLAYERS_FIELD];
      $this->ping = $row[self::PING_FIELD];      
      $this->time = $row[self::PING_FIELD];      
    }

    function makePing($server) {
      $pinger = new MinecraftPing(8);
      $this->server = $server;
      $data = $pinger->getStatus($server->getIpAddress(), self::DEFAULT_VERSION, self::DEFAULT_PORT);

      $this->hostname = $data[self::HOSTNAME_FIELD];
      $this->version = $data[self::VERSION_FIELD];
      $this->players = $data[self::PLAYERS_FIELD];
      $this->maxplayers = $data[self::MAX_PLAYERS_FIELD];
      $this->ping = $data[self::PING_FIELD];

      $this->time = time();
    }

    function toDatabase($db) {
      $id = $this->hostnameToId($this->server->getIpAddress(), $db);

      $query = "INSERT INTO pings";
      $query .= "(`server_id`,`version`,`players`,`maxplayers`,`ping`,`time`)";
      $query .= "VALUES('".$id."','".$this->getVersion()."','".$this->getPlayers()."','".$this->getMaxPlayers();
      $query .= "','".$this->getPing()."','".$this->getTime()."')";

      return $db->query($query);
    }

    private function hostnameToId($hostname, $db) {
      $query = "SELECT * FROM servers WHERE `ip` = '".$hostname."'";
      $result = $db->query($query);

      $id = false;
      while($row = $result->fetch_assoc()) {
        $id = $row['id'];
        break;
      }

      return $id;
    }

    private function idToHostname($id, $db) {
      $query = "SELECT * FROM servers WHERE `id` = '".$id."'";
      $result = $db->query($query);

      $hostname = false;
      while($row = $result->fetch_assoc()) {
        $hostname = $row['ip'];
        break;
      }

      return $hostname;
    }

    function getHostname() {
      return $this->hostname;
    }

    function getVersion() {
      return $this->version;
    }

    function getPlayers() {
      return $this->players;
    }

    function getMaxPlayers() {
      return $this->maxplayers;
    }

    function getPing() {
      return $this->ping;
    }

    function getTime() {
      return $this->time;
    }
  }
?>